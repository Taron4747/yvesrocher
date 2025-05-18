<template>
  <Head title="Интернет-магазин растительной косметики и парфюмерии из Франции с доставкой — Yves Rocher" />
  <Header
    :categories="categories"
    :banners="textBanners"
  />
  <div class="page_content">
    <CategoryInfo :category="category"/>
    <div class="subacategory_content" v-if="category.children"> 
      <div class="subacategory_content_item" v-for="item in category.children">
        <a :href="'/subcategory/'+item.id">{{ item.name_ru }}</a>
      </div>
    </div>
    <div class="product_page">
      <div class="product_filters">
        <div class="title">Результаты фильтров</div>
        <label class="custom_checkbox custom_checkbox_small">Бестселлеры
            <input type="checkbox">
            <span class="checkmark"></span>
        </label>
        <label class="custom_checkbox custom_checkbox_small">Новинки
            <input type="checkbox">
            <span class="checkmark"></span>
        </label>
        <div class="product_filter" v-for="item in filtersWithCounts">
          <div class="filter_title">{{ item.name_ru }}</div>
          <label class="custom_checkbox custom_checkbox_small" v-for="item1 in item.sub_filters">{{ item1.name_ru }} <span class="small_size">({{ item1.product_count }})</span>
            <input type="checkbox">
            <span class="checkmark"></span>
          </label>
        </div>
      </div>
      <div class="product_data">
        <div></div>
      </div>
    </div>
    <!-- <ImageBanner :imageBanners="imageBanners"/>
    <CurrentOffers :imageBanners="imageBanners" />
    <AboutAs /> -->
    <Footer />
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Header from '@/Shared/Header.vue'
import CategoryInfo from '../Catalog/CategoryInfo.vue'
import Footer from '@/Shared/Footer.vue'

export default {
  components: {
    Head,
    Icon,
    Header,
    CategoryInfo,
   
    Footer,
  },
  props: {
    products: Object,
    categories: Object,
    category: Object,
    filtersWithCounts: Object,
    textBanners: Object,
  },
  mounted(){
    // console.log(this.products)
    // console.log(this.categories)
    console.log(this.filtersWithCounts)
  },
  data() {
    return {
      
    }
  },
  
  methods: {
    
  },
}
</script>
<style scoped lang="scss">
.subacategory_content{
  width: 1140px;
  margin: 25px auto 32px auto;
  display: flex;
  .subacategory_content_item{
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
.product_page{
  display: flex;
  justify-content: space-between;
  width: 1140px;
  margin: 0 auto;
  .product_filters{
    width: 270px;
    .title{
      font-size: 20px;
      color: #014E2E;
    }
    .product_filter{
      .filter_title{
        margin-top: 25px;
        font-weight: 600;
      }
      .small_size{
        font-size: 12px;
      }
    }
  }
  .product_data{

  }
}
</style>