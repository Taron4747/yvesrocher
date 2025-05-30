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
         <a href="/">{{ this.$page.props.language.home }}</a><span v-if="product.category">></span>
      <a :href="/category/+product.category.id" v-if="product.subCategory || product.subsubcategory">{{product.category[`name_${$page.props.locale}`]}}</a>
      <span v-else>{{product.category[`name_${$page.props.locale}`]}}</span>
      <span v-if="product.sub_category">></span>
      <span v-if="product.sub_category">
        <a :href="/subcategory/+product.sub_category.id" v-if="product.sub_category">{{product.sub_category[`name_${$page.props.locale}`]}}</a>
        <span v-else>{{product.sub_category[`name_${$page.props.locale}`]}}</span>
      </span>
      <span v-if="product.sub_sub_category">></span>
      <span v-if="product.sub_sub_category">
        <a :href="/subsubcategory/+product.sub_sub_category.id" v-if="product.sub_sub_category">{{product.sub_sub_category[`name_${$page.props.locale}`]}}</a>
      </span>
      <span >></span>
      <span >{{ product[`name_${$page.props.locale}`] }}</span>
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
            <div class="category" v-if="product.category">
              <a :href="/category/+product.category.id">{{product.category[`name_${$page.props.locale}`]}}</a>
            </div>
            <div class="discount_price" >
              <div class="discount">
                <span>-{{ product.discount }}%</span>
              </div>
              <div class="price">
                <span class="big">{{ product.price }} դ</span>
                <span
                  v-if="product.discount"
                  class="small"
                >{{ product.price + (product.price * product.discount / 100) }} դ</span>
              </div>
            </div>
            <div class="count_calc">
              <div class="text">{{ this.$page.props.language.count }}</div>
              <div class="calculate_price">
                <div class="left" @click="changeCount(1)"><span>-</span></div>
                <div class="count"><span>{{ count }}</span></div>
                <div class="right" @click="changeCount(2)"><span>+</span></div>
              </div>
            </div>
            <button class="add_cart">
              {{ this.$page.props.language.add_to_cart }}
            </button>
            <div class="deliver">
              <div>
                <img src="/images/payment.svg"/>
                <span v-html="this.$page.props.language.secured_payment"></span>
              </div>
              <div>
                <img src="/images/refund.svg"/>
                <span v-html="this.$page.props.language.return_policy"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="product_description">
        <div class="title">{{ this.$page.props.language.description }}</div>
        <div v-html="product[`description_${$page.props.locale}`]" class="description"></div>
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
    console.log(this.product)
    this.bigImage = this.product.image;
    this.showPromo = Cookies.get('hide_promo_banner') ? false : true;
  },
  data() {
    return {
      count:1,
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
    },
    changeCount(type){
      console.log(type)
      if(type == 1 && this.count > 1){
        this.count -- 
      }
      if(type == 2 && this.count < 9){
        this.count ++
      }
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
    margin-bottom: 35px;
    span,a{
      margin-right: 5px;
      color: #767676;
    }
  }
  .product_info{
    width: 100%;
    display: flex;
    justify-content: space-between;
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
            margin-bottom: 15px;
          }
          .category{
            font-weight: 600;
            text-decoration: underline;
            font-size: 1.125rem;
            margin-bottom: 15px;
          }
          .discount_price{
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            .discount{
              font-size: 1.125rem;
              font-weight: 700;
              color: #FFFFFF;
              background-color: #143616;
              border-radius: 5px;
              padding: 5px 10px;
              margin-right: 15px;
            }
            .price {
              .big {
                font-size: 1.225rem;
                color: #014E2E;
                font-weight: 600;
              }

              .small {
                font-size: 1rem;
                text-decoration: line-through;
                font-weight: 500;
                margin-left: 3px;
                color: #B3261E;
              }
            }
          }
          .count_calc{
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            .text{
              font-size: .9375rem;
              margin-right: .625rem;
            }
            .calculate_price{
              display: flex;
              align-items: center;
              border: 1px solid #000000;
              border-radius: 15px;
              font-size: 1.475rem;
              line-height:  1.475rem;
              div{
                display: flex;
                align-items: center;
                justify-content: center;
              }
              .left{
                height: 30px;
                width: 25px;
                text-align: center;
                cursor: pointer;
              }
              .count{
                height: 30px;
                width: 33px;
                text-align: center;
                font-weight: 600;
              }
              .right{
                height: 30px;
                width: 25px;
                text-align: center;
                cursor: pointer;
                span{
                  margin-top: -2px;
                }
              }
            }
          }
          .add_cart{
            width: 100%;
            height: 55px;
            border-radius: 8px;
            background-color: #BA1051;
            color: #FFFFFF;
            font-size: 20px;
            font-weight: 600;
          }
          .deliver{
            border: 1px solid #d3d3d3;
            border-radius: 8px;
            padding: .3125rem 1.25rem;
            width: 100%;
            margin-top: .75rem;
            div{
              display: flex;
              align-items: center;
              height: 33px;
              img{
                margin-right: 15px;
              }
              span{
                
              }
            }
          }
        }
      
    }
  }
  .product_description{
    padding-top: 30px;
    .title{
      font-size: 1.5rem;
      margin: 30px 0;
    }
  }
}
</style>