<template>
  <div class="banner_top">
    <PromoBanner :messages="banners" />
  </div>
  <div class="banner_middle">
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
      <img src="/images/shops.svg">
      <img class="bottom" src="/images/account.svg">
      <img src="/images/cart.svg">
    </div>
  </div>
  <div class="banner_bottom">

  </div>
</template>

<script>
import PromoBanner from '@/Shared/PromoBanner.vue'

export default {
  components: {
    PromoBanner,
  },
  props: {
    categories: Object,
  },
  data() {
    return {
      banners : [
        'The selection of the moment <strong>in promotion and free delivery from 20€!</strong> Code: <strong>BIENV20</strong>',
        '🌟 New arrivals just in! <strong>Free gift</strong> on orders over 30€',
        '💚 Don’t miss our <strong>eco-friendly</strong> bestsellers!'
      ]
    }
  },
  mounted() {
    console.log(this.categories);
  },
}
</script>
<style scoped lang="scss">
.banner_top {
  background: #014E2E;
  width: 100%;
  height: 46px;
}
.banner_middle{
  display: flex;
  align-items: center;
  padding: 0 150px;
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
.banner_bottom{
  display: flex;
  align-items: center;
  padding: 0 150px;
  height: 65px;
  box-shadow: 0 .25rem .375rem 0 rgba(0, 0, 0, .1);
}
</style>
