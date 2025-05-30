<template>
  <Head title="Интернет-магазин растительной косметики и парфюмерии из Франции с доставкой — Yves Rocher" />
  <Header
    :categories="categories"
    :banners="banners"
    @hidePromo="hidePromo"
  />
  <div class="page_content" :class="{ 'page_content_small': !showPromo }">
    <div class="product_info_content">
      <div class="product_info_content_top">
        <a href="/">{{ this.$page.props.language.home }}</a><span>></span>
        <span >dsadsadsa</span>
      </div>
      <div class="product_info">
        <div class="product_info_left">
          <div class="product_info_left_small">
            <div class="left_small_item" :class="{'active':product.image == bigImage}" @click="setBigImage(product.image)">
              <img :src="product.image" />
            </div>
            <div class="left_small_item" :class="{'active':item.path == bigImage}" v-for="item in product.images" @click="setBigImage(item.path)">
              <img :src="item.path" />
            </div>
          </div>
          <div class="product_info_left_big">
            <img :src="bigImage"/>
          </div>
        </div>
        <div class="product_info_right">
          <div class="info_right_content">
            <div class="title">{{ product[`name_${$page.props.locale}`] }}</div>
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
import Footer from '@/Shared/Footer.vue'
import Cookies from 'js-cookie'

export default {
  components: {
    Head,
    Icon,
    Header,
    Footer,
  },
  props: {
    product: Object,
    categories: Object,
    banners: Object,
  
  },
  mounted(){
    console.log(this.product.image)
    this.bigImage = this.product.image;
    this.showPromo = Cookies.get('hide_promo_banner') ? false : true;
  },
  data() {
    return {
      showPromo:true,
      bigImage:'',
    }
  },
  
  methods: {
    hidePromo(){
      this.showPromo = false;
    },
    setBigImage(url){
      this.bigImage = url;
    }
  },
}
</script>
<style scoped lang="scss">
.product_info_content{
  width: 1140px;
  margin: 0 auto;
  padding-top: 12px;
  .product_info_content_top{
    margin-bottom: 16px;
    span,a{
      margin-right: 5px;
      color: #767676;
    }
  }
  .product_info{
    width: 100%;
    display: flex;
    .product_info_left{
      display: flex;
      width: 50%;
      .product_info_left_small{
        display: flex;
        flex-direction: column;
        width: 65px;
        .left_small_item{
          display: flex;
          align-items: center;
          justify-content: center;
          width: 50px;
          height: 50px;
          border-radius: 50%;
          cursor: pointer;
          margin-bottom: 10px;
          img{
            width: 44px;
            height: 44px;
            border-radius: 50%;
          }
        }
        .active{
          border: 1px solid #143616;
        }
      }
      .product_info_left_big{
        width: calc(100% - 65px);
        img{
          width: 490px;
          height: 490px;
          object-fit: cover;
          margin-top: 50px;
        }
      }
    }
    .product_info_right{
      padding-left: 25px;
        .info_right_content{
          display: flex;
          flex-direction: column;
          width: 485px;
          margin: 0 auto;
          .title{
            font-size: 1.875rem;
            font-weight: 700;
          }
          .category{
            font-weight: 600;
            text-decoration: underline;
            font-size: 1.125rem;
          }
        }
      
    }
  }
  
}
</style>