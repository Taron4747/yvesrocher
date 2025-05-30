<template>
  <Head title="Интернет-магазин растительной косметики и парфюмерии из Франции с доставкой — Yves Rocher" />
  <Header :categories="categories" :banners="textBanners" @hidePromo="hidePromo"/>
  <div class="page_content" :class="{ 'page_content_small': !showPromo }">
    <CategoryInfo :categoryInfoData="categoryInfoData" />
    <div class="found_count">Найдено продуктов: {{products.data.length}}</div>
    <div class="subacategory_content" v-if="category.children"> 
      <div class="subacategory_content_item" v-for="item in category.children" :key="item.id">
        <a :href="'/subcategory/' + item.id">{{ item[`name_${$page.props.locale}`] }}</a>
      </div>
    </div>
    <div class="product_page">
      
      <div class="product_filters" v-if="products.data.length > 0">
        <div class="title">{{ this.$page.props.language.filter_results }}</div>
        <label class="custom_checkbox custom_checkbox_small">{{ this.$page.props.language.bestsellers }}
          <input type="checkbox" v-model="bestseller" @change="updateUrl">
          <span class="checkmark"></span>
        </label>
        <label class="custom_checkbox custom_checkbox_small">{{ this.$page.props.language.new }}
          <input type="checkbox" v-model="isNew" @change="updateUrl">
          <span class="checkmark"></span>
        </label>
        <label class="custom_checkbox custom_checkbox_small">{{ this.$page.props.language.discounts }}
          <input type="checkbox" v-model="discount" @change="updateUrl">
          <span class="checkmark"></span>
        </label>
        <div class="product_filter">
          <!-- <div class="filter_title" @click="priceFilter = !priceFilter">
            <span>{{ this.$page.props.language.price }}</span>
            <img :class="{ transform: priceFilter }" src="/images/vector.svg"/>
          </div> -->
          <div class="price_slider" >
            <Slider v-model="price" :min="this.prices.min_price" :max="this.prices.max_price" @change="onPriceChange" />
          </div>
        </div>
        <div class="product_filter" v-for="item in filtersWithCountsData" :key="item.id">
          <div class="filter_title" @click="showHideFilter(item.id)">
            <span>{{ item[`name_${$page.props.locale}`] }}</span>
            <img :class="{ transform: item.isOpen }" src="/images/vector.svg"/>
          </div>
          <div v-if="item.isOpen">
            <label
              class="custom_checkbox custom_checkbox_small"
              v-for="item1 in item.sub_filters"
              :key="item1.id"
            >
              {{ item1[`name_${$page.props.locale}`] }} <span class="small_size">({{ item1.product_count }})</span>
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
      <div class="product_data_content" v-if="products.data.length > 0">
        <div class="sorting" @click="toggle()">
          <span>{{ this.$page.props.language.sort_by }}</span>
          <img src="/images/Vector.svg">  
          <div class="sort_data" v-if="showSorting">
            <div class="sort_data_item" v-for="item in sort_data" @click="changeSorting(item)">
                <span :class="{'bold':item.type}">{{ item.title }}</span>
            </div>
          </div>
        </div>
        <div class="product_data">
          <div class="product_data_item" v-for="item in products.data" :key="item.id">
            <a :href="/product/+item.id" class="product_image">
              <div class="img_wrapper">
                <img :src="item.image" class="main_img">
                <img :src="item.images[0].path" class="hover_img">
              </div>
              <div class="product_type" v-if="item.is_new == 1">{{ this.$page.props.language.new }}</div>
              <div class="product_type" v-if="item.is_bestseller == 1">{{ this.$page.props.language.Bestsellers }}</div>
            </a>
            <div class="product_text">
              <div class="title">{{ item[`name_${$page.props.locale}`] }}</div>
              <div class="size"> 
                <span>{{ item.size }}</span>
                <span>{{ item.type }}</span>
              </div>
              <div class="rating">
                <img src="/images/stars.png">
                <div>(100)</div>
              </div>
              <div class="price">
                <span class="big">{{ item.price }} դ</span>
                <span
                  v-if="item.discount"
                  class="small"
                >{{ item.price + (item.price * item.discount / 100) }} դ</span>
              </div>
            </div>
            <button>
              {{ this.$page.props.language.add_to_cart }}
            </button>
          </div>  
        </div>
      </div>
      <div class="no_data"v-if="products.data.length == 0">
        {{ this.$page.props.language.no_result }}
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
import Cookies from 'js-cookie'

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
    subCategory: Object,
    subSubCategory: Object,
    category: Object,
    filtersWithCounts: Object,
    textBanners: Object,
  },
  data() {
    return {
      showPromo: true,
      categoryInfoData:{
        category:'',
        category:'',
        category:'',
        category:'',
        category:''
      },
      priceFilter: false,
      price: [this.prices.min_price, this.prices.max_price],
      isNew: false,
      bestseller: false,
      discount: false,
      filtersSelected: {}, // { 1: [2,3], 3: [8] }
      openFilters: [], // сюда будем сохранять id открытых фильтров
      showSorting:false,
      sort_data: [
        {
            id:1,
            title: this.$page.props.language.alphabetically_asc,
            key:'name_asc',
            type:false
        },
        {
            id:2,
            title: this.$page.props.language.alphabetically_desc,
            key:'name_desc',
            type:false
        },
        {
            id:3,
            title: this.$page.props.language.price_asc,
            key:'price_asc',
            type:false
        },
        {
            id:4,
            title: this.$page.props.language.price_desc,
            key:'price_desc',
            type:false
        },
        {
            id:5,
            title: this.$page.props.language.discount_asc,
            key:'discount_asc',
            type:false
        },
        {
            id:6,
            title: this.$page.props.language.discount_desc,
            key:'discount_desc',
            type:false
        },
      ],
      sortKey:'',
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
    // console.log(this.products)
    this.showPromo = Cookies.get('hide_promo_banner') ? false : true;
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

    const url = new URL(window.location.href);
    const sorting = url.searchParams.get("sorting");
    if(sorting){
      this.setSortingActive(sorting)
    }
    this.setBreadcrumbs(url.href)
    console.log(this.products)
  },
  methods: {
    setBreadcrumbs(url){
      const hasSubsubcategory = url.indexOf("subsubcategory") !== -1;
      const hasSubCategory = url.indexOf("subcategory") !== -1;
      
      if(hasSubsubcategory){
        this.categoryInfoData['subsubcategory'] = this.subSubCategory[`name_${this.$page.props.locale}`];
        this.categoryInfoData['subCategory'] = this.subCategory[`name_${this.$page.props.locale}`];
        this.categoryInfoData['category'] = this.category[`name_${this.$page.props.locale}`];
        this.categoryInfoData['subsubcategoryDesc'] = this.subSubCategory[`description_${this.$page.props.locale}`];
        this.categoryInfoData['subCategoryLink'] = '/subcategory/'+this.subCategory.id;
        this.categoryInfoData['categoryLink'] = '/category/'+this.category.id;
      }else if(hasSubCategory){
        this.categoryInfoData['subCategory'] = this.subCategory[`name_${this.$page.props.locale}`];
        this.categoryInfoData['category'] = this.category[`name_${this.$page.props.locale}`];
        this.categoryInfoData['subCategoryDesc'] = this.subCategory[`description_${this.$page.props.locale}`];
        this.categoryInfoData['categoryLink'] = '/category/'+this.category.id;
      }else{
        this.categoryInfoData['category'] = this.category[`name_${this.$page.props.locale}`];
        this.categoryInfoData['categoryDesc'] = this.category[`description_${this.$page.props.locale}`];
        this.categoryInfoData['second_image'] = this.category.second_image;
      }    
      this.categoryInfoData['second_image'] = this.category.second_image;

      
      // console.log(this.category)
      // console.log(this.subCategory)
      // console.log(this.subSubCategory)
    },
    setSortingActive(key){
      this.sortKey = key;
      this.sort_data.forEach((sort) => {
        sort.type = false;
        if(sort.key == key){
          sort.type = true;
        }
      });
    },
    toggle(){
      this.showSorting = !this.showSorting
    },
    hidePromo(){
      this.showPromo = false;
    },
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
    changeSorting(item){
      this.sortKey = item.key;
      this.sort_data.forEach((sort) => {
        sort.type = false;
        if(sort.id == item.id){
          sort.type = true;
        }
      });
      this.updateUrl()
    },
    updateUrl() {
      const params = new URLSearchParams()
      if (this.isNew) params.append('new', 1)
      if (this.bestseller) params.append('bestseller', 1)
      if (this.discount) params.append('discount', 1)
      params.append('price[min]', this.price[0])
      params.append('price[max]', this.price[1])
        if (this.sortKey) params.append('sorting', this.sortKey)
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
.found_count{
  width: 1140px;
  margin: 25px auto 0 auto;
}
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
      position: relative;
      color: #014E2E;
      font-weight: 600;
      cursor: pointer;
      img {
        margin-left: 12px;
      }
      .sort_data{
        position: absolute;
        background: #FFFFFF;
        border-radius: 10px;
        border: 1px solid #014E2E;
        padding: 15px;
        right: 0;
        top: 20px;
        z-index: 2;
        .sort_data_item{
          padding: 10px;
          border-bottom: 1px solid #014E2E;
          font-weight: 500;
          .bold{
          font-weight: 600;
        }
        }
        .sort_data_item:last-child{
          border: none;
        }
        
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
          position: relative;
          width: 100%;
          height: 230px;
          .img_wrapper {
            position: relative;
            overflow: hidden;
            height: 100%;
          }

          .img_wrapper img {
            width: 100%;
            height: 100%;
            transition: opacity 0.4s ease;
            display: block;
            object-fit: cover;
          }

          .hover_img {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
          }

          .img_wrapper:hover .hover_img {
            opacity: 1;
          }

          .img_wrapper:hover .main_img {
            opacity: 0;
          }
          img {
            width: 100%;
            height: 100%;
            border-radius: 5px 5px 0 0;
            object-fit: cover;
          }
           .product_type{
            position: absolute;
            left: 10px;
            top: 10px;
            color: #014E2E;
            font-size: 12px;
            font-weight: 500;
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
            span{
              margin-right: 5px;
            }
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
          .big {
            font-size: 18px;
            color: #014E2E;
            font-weight: 600;
          }

          .small {
            font-size: 12px;
            text-decoration: line-through;
            font-weight: 500;
            margin-left: 3px;
            color: #B3261E;
          }
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
  .no_data{
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
  }
}
.transform {
  transform: rotate(180deg);
}
</style>
