<template>
  <Head title="Интернет-магазин растительной косметики и парфюмерии из Франции с доставкой — Yves Rocher" />
  <Header
    :categories="categories"
    :banners="textBanners"
  />
  <div class="page_content">
    <ImageBanner :imageBanners="imageBanners"/>
    <Products />
    <CurrentOffers :imageBanners="imageBanners" />
    <WithYou />
    <AboutAs />
    <Footer />
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Header from '@/Shared/Header.vue'
import ImageBanner from '../Home/ImageBanner.vue'
import CurrentOffers from '../Home/CurrentOffers.vue'
import WithYou from '../Home/WithYou.vue'
import Products from '../Home/Products.vue'
import AboutAs from '../Home/AboutAs.vue'
import Footer from '@/Shared/Footer.vue'

export default {
  components: {
    Head,
    Icon,
    Header,
    ImageBanner,
    CurrentOffers,
    WithYou,
    Products,
    AboutAs,
    Footer,
  },
  props: {
    categories: Object,
    textBanners: Object,
    imageBanners: Object,
  },
  mounted(){
    console.log(this.textBanners)
  },
  data() {
    return {
      
    }
  },
  
  methods: {
    
  },
}
</script>
