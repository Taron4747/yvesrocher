<template>
  <Head title="Интернет-магазин растительной косметики и парфюмерии из Франции с доставкой — Yves Rocher" />
  <Header :categories="categories" :banners="textBanners" />
  <div class="page_content">
    <CategoryInfo :category="category" />
    <div class="subacategory_content" v-if="category.children"> 
      <div class="subacategory_content_item" v-for="item in category.children" :key="item.id">
        <a :href="'/subcategory/' + item.id">{{ item.name_ru }}</a>
      </div>
    </div>
    <div class="product_page">
      <div class="product_filters">
        <div class="title">Результаты фильтров</div>
        <label class="custom_checkbox custom_checkbox_small">Бестселлеры
          <input type="checkbox" v-model="bestseller" @change="updateUrl">
          <span class="checkmark"></span>
        </label>
        <label class="custom_checkbox custom_checkbox_small">Новинки
          <input type="checkbox" v-model="isNew" @change="updateUrl">
          <span class="checkmark"></span>
        </label>
        <label class="custom_checkbox custom_checkbox_small">Скидки
          <input type="checkbox" v-model="discount" @change="updateUrl">
          <span class="checkmark"></span>
        </label>
        <div class="product_filter">
          <div class="filter_title" @click="priceFilter = !priceFilter">
            <span>Цена</span>
            <img :class="{ transform: priceFilter }" src="/images/vector.svg"/>
          </div>
          <div class="price_slider" v-if="priceFilter">
            <Slider v-model="price" :min="this.prices.min_price" :max="this.prices.max_price" @change="onPriceChange" />
          </div>
        </div>
        <div class="product_filter" v-for="item in filtersWithCountsData" :key="item.id">
          <div class="filter_title" @click="showHideFilter(item.id)">
            <span>{{ item.name_ru }}</span>
            <img :class="{ transform: item.isOpen }" src="/images/vector.svg"/>
          </div>
          <div v-if="item.isOpen">
            <label
              class="custom_checkbox custom_checkbox_small"
              v-for="item1 in item.sub_filters"
              :key="item1.id"
            >
              {{ item1.name_ru }} <span class="small_size">({{ item1.product_count }})</span>
              <input
                type="checkbox"
                :checked="(filtersSelected[item.id] || []).includes(item1.id)"
                @change="toggleFilter(item.id, item1.id)"
              >
              <span class="checkmark"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="product_data_content">
        <div class="sorting">
          <span>Сортитовать ПО</span>
          <img src="/images/Vector.svg">  
        </div>
        <div class="product_data">
          <div class="product_data_item" v-for="item in products.data" :key="item.id">
            <div class="product_image">
              <img :src="item.image">
            </div>
            <div class="product_text">
              <div class="title">{{ item.name_ru }}</div>
              <div class="size">Размер {{ item.size }}</div>
              <div class="rating">
                <img src="/images/stars.png">
                <div>(100)</div>
              </div>
              <div class="price">
                {{ item.price }} դ
              </div>
            </div>
            <button>
              В Кoрзину
            </button>
          </div>  
        </div>
      </div>
    </div>
    <Footer />
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Header from '@/Shared/Header.vue'
import CategoryInfo from '../Catalog/CategoryInfo.vue'
import Footer from '@/Shared/Footer.vue'
import Slider from '@vueform/slider'
import '@vueform/slider/themes/default.css'

export default {
  components: {
    Head,
    Header,
    CategoryInfo,
    Footer,
    Slider,
  },
  props: {
    prices: Object,
    products: Object,
    categories: Object,
    category: Object,
    filtersWithCounts: Object,
    textBanners: Object,
  },
  data() {
    return {
      priceFilter: false,
      price: [this.prices.min_price, this.prices.max_price],
      isNew: false,
      bestseller: false,
      discount: false,
      filtersSelected: {}, // { 1: [2,3], 3: [8] }
      openFilters: [], // сюда будем сохранять id открытых фильтров
    }
  },
  computed: {
    filtersWithCountsData() {
      return this.filtersWithCounts
        .map(filter => {
          const visibleSubFilters = filter.sub_filters.filter(sub => sub.product_count > 0)
          if (visibleSubFilters.length === 0) return null

          return {
            ...filter,
            sub_filters: visibleSubFilters,
            isOpen: this.openFilters.includes(filter.id)
          }
        })
        .filter(Boolean)
    }
  },
  mounted(){
    const urlParams = new URLSearchParams(window.location.search)

    // Новинки, скидки, бестселлеры
    this.isNew = urlParams.get('new') === '1'
    this.bestseller = urlParams.get('bestseller') === '1'
    this.discount = urlParams.get('discount') === '1'

    // Цена
    const minPrice = urlParams.get('price[min]')
    const maxPrice = urlParams.get('price[max]')
    if (minPrice && maxPrice) {
      this.price = [Number(minPrice), Number(maxPrice)]
    }

    // filtersSelected: filters[2][]=1&filters[2][]=2&filters[3][]=5
    const filters = {}
    for (const [key, value] of urlParams.entries()) {
      const match = key.match(/^filters\[(\d+)]\[]$/)
      if (match) {
        const sectionId = match[1]
        if (!filters[sectionId]) filters[sectionId] = []
        filters[sectionId].push(Number(value))
      }
    }
    this.filtersSelected = filters
    this.openFilters = Object.keys(this.filtersSelected).map(id => Number(id))

  },
  methods: {
    onPriceChange(newValue) {
      this.price = newValue // явно присваиваем обновлённое значение
      this.updateUrl()
    },
    showHideFilter(id) {
      if (this.openFilters.includes(id)) {
        this.openFilters = this.openFilters.filter(openId => openId !== id)
      } else {
        this.openFilters.push(id)
      }
    },
    updateUrl() {
      const params = new URLSearchParams()
      if (this.isNew) params.append('new', 1)
      if (this.bestseller) params.append('bestseller', 1)
      if (this.discount) params.append('discount', 1)
      params.append('price[min]', this.price[0])
      params.append('price[max]', this.price[1])
      for (const [sectionId, subIds] of Object.entries(this.filtersSelected)) {
        if (Array.isArray(subIds)) {
          subIds.forEach(subId => {
            params.append(`filters[${sectionId}][]`, subId)
          })
        }
      }

      // Получаем текущий путь (например, /subcategory/6)  
      if(window.location.pathname == "/search"){
         const url = new URL(window.location.href)
        let base = `${url.origin}${url.pathname}`
        let search = url.searchParams.get('search')

        if (search !== null) {
          base += `?search=${encodeURIComponent(search)}`
        }
        window.location.href = `${base}&${params.toString()}`
      }else{
        window.location.href = `${window.location.pathname}?${params.toString()}`
      }
      // Обновляем только query-параметры
     
    },
    toggleFilter(sectionId, subFilterId) {
      const current = this.filtersSelected[sectionId] || []

      let updated
      if (current.includes(subFilterId)) {
        updated = current.filter(id => id !== subFilterId)
      } else {
        updated = [...current, subFilterId]
      }

      this.filtersSelected = {
        ...this.filtersSelected,
        [sectionId]: updated
      }

      this.updateUrl()
    }
  }
}
</script>

<style scoped lang="scss">
.subacategory_content {
  width: 1140px;
  margin: 25px auto 0 auto;
  display: flex;
  .subacategory_content_item {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    border-radius: 20px;
    color: #FFFFFF;
    background: #014E2E;
    padding: 0 25px;
    height: 40px;
  }
}
.product_page {
  display: flex;
  justify-content: space-between;
  width: 1140px;
  margin: 0 auto;
  margin-top: 32px;
  .product_filters {
    width: 270px;
    .title {
      font-size: 20px;
      color: #014E2E;
    }
    .product_filter {
      .filter_title {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 25px;
        font-weight: 600;
      }
      .small_size {
        font-size: 12px;
      }
      .price_slider {
        margin: 25px 10px 50px 10px;
      }
    }
  }
  .product_data_content {
    .sorting {
      display: flex;
      justify-content: flex-end;
      color: #014E2E;
      font-weight: 600;
      img {
        margin-left: 12px;
      }
    }
    .product_data {
      display: flex;
      flex-wrap: wrap;
      width: 845px;
      margin-top: 16px;
      .product_data_item {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 200px;
        margin-right: 15px;
        height: 440px;
        margin-bottom: 24px;
        box-shadow: inset 0 4px 6px 0 rgba(0, 0, 0, 0.1);
        .product_image {
          width: 100%;
          height: 230px;
          img {
            width: 100%;
            height: 100%;
            border-radius: 5px 5px 0 0;
            object-fit: cover;
          }
        }
        .product_text {
          padding: 0 11px;
          .title {
            font-weight: 400;
            margin-bottom: 5px;
            line-height: 100%;
          }
          .size {
            font-size: 12px;
            color: #767676;
            margin-bottom: 5px;
          }
          .rating {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            div {
              margin-left: 10px;
            }
          }
          .price {
            line-height: 100%;
            color: #014E2E;
            font-weight: 600;
          }
        }
        button {
          display: flex;
          align-items: center;
          justify-content: center;
          height: 40px;
          border-radius: 20px;
          background: #014E2E;
          color: #FFF;
          font-weight: 600;
          width: calc(100% - 22px);
          margin: 0 auto;
        }
      }
      .product_data_item:nth-child(4n) {
        margin-right: 0;
      }
    }
  }
}
.transform {
  transform: rotate(180deg);
}
</style>
