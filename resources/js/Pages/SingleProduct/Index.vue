<template>
  <Head title="Интернет-магазин растительной косметики и парфюмерии из Франции с доставкой — Yves Rocher" />
  <Header
    :categories="categories"
    :banners="banners"
    @hidePromo="hidePromo"
  />
  <div class="page_content" :class="{ 'page_content_small': !showPromo }">
    
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
    this.showPromo = Cookies.get('hide_promo_banner') ? false : true;
  },
  data() {
    return {
      showPromo:true,
    }
  },
  
  methods: {
    hidePromo(){
      this.showPromo = false;
    }
  },
}
</script>
