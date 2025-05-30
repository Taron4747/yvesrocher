<template>
  <div class="header">
    <div class="banner_top" v-if="showPromo">
      <PromoBanner 
      @hide="hidePromo"
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
            <input @keyup.enter="handleEnter" v-model="searchValue" :placeholder="this.$page.props.language.search" type="text" />
            <img @click="handleEnter" src="/images/search_green.svg">
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
            <img class="bottom" src="/images/account.svg">
            <img src="/images/cart.svg">
            <div class="lang" @click="changeLang('arm')" :class="{ active: this.$page.props.locale == 'arm' }">ՀԱՅ</div>
            <div class="lang" @click="changeLang('ru')" :class="{ active: this.$page.props.locale == 'ru' }">РУ</div>
            <div class="lang" @click="changeLang('en')" :class="{ active: this.$page.props.locale == 'en' }">EN</div>
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
           <a :href="'/category/'+item.id" >{{ item[`name_${$page.props.locale}`] }}</a>
          </div>
          <div class="categores_data_item old_green">{{ this.$page.props.language.new }}</div>
          <div class="categores_data_item old_green">{{ this.$page.props.language.ecoformats }}</div>
          <div class="categores_data_item old_pink">{{ this.$page.props.language.discounts }}</div>
          <div
            class="categores_data_item old_green"
            style="color: #014E2E ;"
            v-for="item in categoriesData.filter(item => item.hasImage)"
            :key="item.id"
            :class="{ border_underline: choosedCategory == item.id}"
            @mouseenter="showHide(true,item.id)"
          >
           <span v-if="item.hasImage">{{ item[`name_${$page.props.locale}`] }}</span>
          </div>
          <div
              v-if="showDropdown === true"
              class="absolute dropdown_content"
              @mouseleave="showHide(false,0)"
            >
          <div class="dropdown_content_left">
            <div class="subcategories_content" :key="item1.id" v-for="item1 in subCategoriesData">
                <a :href="'/subcategory/'+item1.id"  class="title" v-if="!item1.image_medium">{{ item1[`name_${$page.props.locale}`] }}</a>
                <a :href="'/subsubcategory/'+item2.id" class="text" v-if="!item1.image_medium" v-for="item2 in item1.children">
                  {{ item2[`name_${$page.props.locale}`] }}
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
import Cookies from 'js-cookie'
import PromoBanner from '@/Shared/PromoBanner.vue'
import axios from "axios";

export default {
  components: {
    PromoBanner,
    Head,
    Link,
  },
  emits: ["hidePromo"],
  props: {
    categories: Object,
    banners: Object,
  },
  data() {
    return {
      showPromo:true,
      searchValue:'',
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
    this.showPromo = Cookies.get('hide_promo_banner') ? false : true;
    this.categoriesData.forEach((categories) => {
      categories.hasImage = false;
    });
    // const cleanImageBanners = JSON.parse(JSON.stringify(this.imageBanners));
    // this.cleanImageBanners = cleanImageBanners;
// console.log(cleanImageBanners)
    const newCategory = {
      id:1000,
      name_arm: "Ապրանքանիշի մասին",  
      name_ru: "О МАРКЕ",  
      name_en: "The brand",  
      children: this.aboutImageBanners,
      hasImage: true, 
    };

    this.categoriesData.push(newCategory);

  },
  methods: {
    hidePromo(){
      this.showPromo = false;
      this.$emit("hidePromo");
    },
    handleEnter(){
      // const length = this.searchValue.length;
      // if(length > 2){
      //   window.location.href = `${window.location.origin}/search?search=${this.searchValue}`
      // }
      const cleanedValue = this.searchValue.replace(/\s+/g, '');
      if (cleanedValue.length > 2) {
        window.location.href = `${window.location.origin}/search?search=${this.searchValue}`
      }
    },
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
      if(this.$page.props.locale != type){
      axios.get("/language/" + type).then((response) => {
        window.location.reload();
      });
      }
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
      justify-content: space-between;
      margin: 0 auto;
      height: 65px;
      .banner_middle_left,.banner_middle_right{
        display: flex;
        // width: calc(100% / 3);
        height: 100%;
      }
      .banner_middle_center{
        width: 682px;
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
          position: relative;
          cursor: pointer;
          width: 100%;
          height: 42px;
          // border-bottom: 1px solid #014E2E;
          // padding-left: 11px;
          input{
            background: #F7F7F5;
            border-radius: 21px;
            padding-left: 60px;
            position: absolute;
            width: 100%;
            height: 100%;
            // margin-left: -11px;
            border: none;
            border: none !important;
            outline: none; /* Убирает рамку при фокусе */
            box-shadow: none; /* Если библиотека добавляет тень вместо рамки */
          }
          input::placeholder {
            font-size: 14px!important;
            color: #014E2E!important;
            text-transform: uppercase;
          }
          img{
            position: absolute;
            z-index: 5;
            left: 26px;
            top: 9px;
            width: 23px;
            height: 20px;
            margin-right: 20px;
          }
        }
    }
    .banner_middle_right{
      align-items: center;
      justify-content: flex-end;
      .lang{
        cursor: pointer;
        font-size: 12px;
        color: #767676;
        margin-left: 10px;
      }
      .lang:hover{
        color: #939956;
      }
      .active{
        color: #014E2E!important;
      }
      img{
        cursor: pointer;
        margin-right: 24px;
      }
      // img:last-child{
      //   margin-left: 17.6px;
      // }
      .bottom{
        margin-bottom: -9px;
      }
    }
    }
  }
  .banner_bottom{
     width: 100%;
     box-shadow: 0 .25rem .375rem 0 rgba(0, 0, 0, .1);
     border-top: 1px solid #F7F7F5;
    .banner_bottom_content{
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
      max-width: 1140px;
      height: 35px;
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
.old_pink{
  color: #BA1051;
  font-weight: 600;
  text-transform: uppercase;
}
.border_underline{
  border-bottom: 2px solid #000000;
}
</style>
