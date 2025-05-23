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
      <div class="product_data_content">
        <div class="sorting">
          <span>Сортитовать ПО</span>
          <img src="/images/Vector.svg">  
        </div>
        <div class="product_data">
          <div class="product_data_item" v-for="item in products.data">
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
    // console.log(this.textBanners)
    // console.log(this.categories)
    // console.log(this.filtersWithCounts)
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
  margin: 25px auto 0 auto;
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
  margin-top: 32px;
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
  .product_data_content{
    .sorting{
      display: flex;
      justify-content: flex-end;
      color: #014E2E;
      font-weight: 600;
      img{
        margin-left: 12px;
      }
    }
    .product_data{
      display: flex;
      flex-wrap: wrap;
      width: 845px;
      margin-top: 16px;
      .product_data_item{
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 200px;
        margin-right: 15px;
        height: 440px;
        margin-bottom: 24px;
        box-shadow: inset 0 4px 6px 0 rgba(0, 0, 0, 0.1);
        .product_image{
          width: 100%;
          height: 230px;
          img{
            width: 100%;
            height: 100%;
            border-radius: 5px 5px 0 0;
            object-fit: cover;
          }
        }
        .product_text{
          padding: 0 11px;
          .title{
            font-weight: 400;
            margin-bottom: 5px;
            line-height: 100%;
          }
          .size{
            font-size: 12px;
            color: #767676;
            margin-bottom: 5px;
          }
          .rating{
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            div{
              margin-left: 10px;
            }
          }
          .price{
            line-height: 100%;
            color: #014E2E;
            font-weight: 600;
          }
        }
        button{
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
      .product_data_item:nth-child(4n){
        margin-right: 0;
      }
    }
  }
}
</style>