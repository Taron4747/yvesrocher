<template>
  <Head title="Интернет-магазин растительной косметики и парфюмерии из Франции с доставкой — Yves Rocher" />
  <Header
    :categories="categories"
    :banners="textBanners"
  />
  <div class="page_content">
    <CategoryInfo :category="category"/>

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
    console.log(this.products)
    console.log(this.categories)
    console.log(this.category)
    console.log(this.filtersWithCounts)
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
