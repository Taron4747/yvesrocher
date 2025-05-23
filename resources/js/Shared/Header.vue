<template>
  <div class="header">
    <div class="banner_top">
      <PromoBanner 
      :banners="banners"
       />
    </div>
    <div class="banner_middle">
      <div class="banner_middle_content">
        <div class="banner_middle_left">
          <a href="/">
            <img src="/images/new_logo-2020.svg">
          </a>
        </div>
        <div class="banner_middle_center">
          <div class="search_content">
            <img src="/images/search_green.svg">
            <span>ПОИСК</span>
          </div>
        </div>
        <div class="banner_middle_right">
                  <!-- <Link href="/language/ru">
                    <span  class="dropdown_items_active"> ru</span>
                </Link>
                   <Link href="/language/arm">
                    <span  class="dropdown_items_active"> arm</span>
                </Link>
                   <Link href="/language/en">
                    <span  class="dropdown_items_active"> en</span>
                </Link> -->
            <div class="lang" @click="changeLang('arm')" :class="{ active: this.$page.props.locale == 'arm' }">ARM</div>
            <div class="lang" @click="changeLang('ru')" :class="{ active: this.$page.props.locale == 'ru' }">RU</div>
            <div class="lang" @click="changeLang('en')" :class="{ active: this.$page.props.locale == 'en' }">EN</div>
            <img class="bottom" src="/images/account.svg">
            <img src="/images/cart.svg">
        </div>
      </div>
    </div>
    <div class="banner_bottom" >
      <div class="banner_bottom_content" @mouseleave="showHide(false,0)">
        <div class="categores_data relative">
          <div
            class="categores_data_item"
            v-show="!item.hasImage"
            v-for="item in categoriesData"
            :key="item.id"
            :class="{ border_underline: choosedCategory == item.id}"
            @mouseenter="showHide(true,item.id)"
          >
           <a :href="'/category/'+item.id" >{{ item.name_ru }}</a>
          </div>
          <div class="categores_data_item old_green">НОВИНКИ</div>
          <div class="categores_data_item old_green">БЕСТСЕЛЛЕРЫ</div>
          <div class="categores_data_item old_green">Скидки</div>
          <div
            class="categores_data_item old_green"
            v-for="item in categoriesData.filter(item => item.hasImage)"
            :key="item.id"
            :class="{ border_underline: choosedCategory == item.id}"
            @mouseenter="showHide(true,item.id)"
          >
           <span v-if="item.hasImage">{{ item.name_ru }}</span>
          </div>
          <div
              v-if="showDropdown === true"
              class="absolute dropdown_content"
              @mouseleave="showHide(false,0)"
            >
          <div class="dropdown_content_left">
            <div class="subcategories_content" :key="item1.id" v-for="item1 in subCategoriesData">
                <a :href="'/subcategory/'+item1.id"  class="title" v-if="!item1.image_medium">{{ item1.name_ru }}</a>
                <a :href="'/subsubcategory/'+item2.id" class="text" v-if="!item1.image_medium" v-for="item2 in item1.children">
                  {{ item2.name_ru }}
                </a>
                <div class="" v-if="item1.image_medium">
                  <div class="image_banner" >
                    <img :src="item1.image_medium">
                    <div class="banner_text">{{ item1.title }}</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="dropdown_content_right" v-if="image">
              <img :src="image">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'

import PromoBanner from '@/Shared/PromoBanner.vue'
import axios from "axios";

export default {
  components: {
    PromoBanner,
    Head,
    Link,
  },
  props: {
    categories: Object,
    banners: Object,
  },
  data() {
    return {
      image:null,
      showDropdown:false,
      choosedCategory:0,
      categoriesData:this.categories,
      aboutImageBanners: [
        {
            id:1,
            title: 'Кто мы?',
            image_medium:'/images/about_us_1.webp',
        },
        {
            id:2,
            title: 'Наша Растительная Экспертиза',
            image_medium:'/images/about_us_2.webp',
        },
        {
            id:3,
            title: 'Наши обязательства',
            image_medium:'/images/about_us_3.webp',
        },
        {
            id:4,
            title: 'Наши сертификаты и партнеры',
            image_medium:'/images/about_us_4.webp',
        },
      ],
      subCategoriesData:[],
    }
  },
  mounted() {
    this.categoriesData.forEach((categories) => {
      categories.hasImage = false;
    });
    // const cleanImageBanners = JSON.parse(JSON.stringify(this.imageBanners));
    // this.cleanImageBanners = cleanImageBanners;
// console.log(cleanImageBanners)
    const newCategory = {
      id:1000,
      name_ru: "О МАРКЕ",  
      children: this.aboutImageBanners,
      hasImage: true, 
    };

    this.categoriesData.push(newCategory);

  },
  methods: {
    showHide(type,id){
      if(type){
        if(id != 1000){
          this.categoriesData.forEach((categories) => {
            if(categories.id == id){
              this.subCategoriesData = categories.children;
              this.image = categories.image;
            }
          });
        }else{
          this.subCategoriesData = this.aboutImageBanners;
          this.image = null;
        }
   
        this.choosedCategory = id
      }else{
        this.choosedCategory = 0
      }
      this.showDropdown = type;
    },
    changeLang(type){
       axios.get("/language/" + type).then((response) => {
        console.log(response)
      });
      // console.log(type);
    }
  },
}
</script>
<style scoped lang="scss">
.header{
  position: fixed;
  z-index: 5000;
  background: #FFFFFF;
  width: 100%;
  .banner_top {
    background: #014E2E;
    width: 100%;
    height: 46px;
  }
  .banner_middle{
     width: 100%;
    .banner_middle_content{
      display: flex;
      align-items: center;
      max-width: 1140px;
      margin: 0 auto;
      height: 65px;
      .banner_middle_left,.banner_middle_center,.banner_middle_right{
        display: flex;
        width: calc(100% / 3);
        height: 100%;
      }
      .banner_middle_left{
        align-items: center;
        justify-content: flex-start;
      }
      .banner_middle_center{
        align-items: center;
        justify-content: flex-start;
        .search_content{
          display: flex;
          align-items: center;
          cursor: pointer;
          width: 302px;
          height: 35px;
          border-bottom: 1px solid #014E2E;
          padding-left: 11px;
          img{
            width: 23px;
            height: 20px;
            margin-right: 20px;
          }
          span{
            font-size: 14px;
            color: #014E2E;
            line-height: 100%;
          }
        }
    }
    .banner_middle_right{
      align-items: center;
      justify-content: flex-end;
      .lang{
        cursor: pointer;
        font-size: 14px;
        color: #014E2E;
        margin-left: 10px;
      }
      .lang:last-child{
        margin-right: 25[x];
      }
      .active{
        text-decoration: underline;
        font-weight: 600;
      }
      img{
        cursor: pointer;
        margin-left: 24px;
      }
      img:last-child{
        margin-left: 17.6px;
      }
      .bottom{
        margin-bottom: -9px;
      }
    }
    }
  }
  .banner_bottom{
     width: 100%;
     box-shadow: 0 .25rem .375rem 0 rgba(0, 0, 0, .1);
    .banner_bottom_content{
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
      max-width: 1140px;
      height: 65px;
      .categores_data{
        display: flex;
        align-items: center;
        width: 100%;
        justify-content: space-between;
        .categores_data_item{
          font-size: 12px;
          line-height: 13.8px;
          cursor: pointer;
        }
        .dropdown_content{
          display: flex;
          justify-content: space-between;
          width: 1200px;
          height: 500px;
          box-shadow: inset 0 4px 6px 0 rgba(0, 0, 0, 0.1);
          background: #FFFFFF;
          top: 40px;
          left: -30px;
          padding:10px;
            .dropdown_content_left{
              display: flex;
              .subcategories_content{
              display: flex;
              flex-direction: column;
              padding: 15px;
              .title{
                font-weight: 400;
                font-size: 1.0625rem;
                margin-bottom: .3125rem;
                cursor: pointer;
              }
              .title:hover{
                text-decoration: underline;
              }
              .text{
                padding: .625rem 0;
                font-size: .8125rem;
                cursor: pointer;
              }
              .text:hover{
                text-decoration: underline;
              }
              .image_banner{
                position: relative;
                margin: 0 5px;
                img{
                  border-radius: 5px
                  // position: absolute;
                  // height: 100%;
                  // width: 100%;
                }
                .banner_text{
                  bottom: 0;
                  border-radius: 0 0 5px 5px;
                  padding: 10px;
                  position: absolute;
                  background: #000000;
                  width: 100%;
                  opacity: 0.5;
                  color: #FFFFFF;
                }
              }
            }
          }
          .dropdown_content_right{
            img{
              width: 180px;
              object-fit: cover;
              border-radius: .4rem;
            }
          }
        }
      }
    }
  }
}
.old_green{
  color: #939956;
  font-weight: 600;
  text-transform: uppercase;
}
.border_underline{
  border-bottom: 2px solid #000000;
}
</style>
