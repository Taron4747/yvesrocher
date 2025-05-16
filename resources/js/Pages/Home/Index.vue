<template>
  <Head title="Интернет-магазин растительной косметики и парфюмерии из Франции с доставкой — Yves Rocher" />
  <Header
    :categories="categories"
  />
  <div class="page_content">
    <ImageBanner />
    <CurrentOffers />
    <WithYou />
    <AboutAs />
    <Footer />
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Header from '@/Shared/Header.vue'
import ImageBanner from '@/Shared/ImageBanner.vue'
import CurrentOffers from '@/Shared/CurrentOffers.vue'
import WithYou from '@/Shared/WithYou.vue'
import AboutAs from '@/Shared/AboutAs.vue'
import Footer from '@/Shared/Footer.vue'

export default {
  components: {
    Head,
    Icon,
    Header,
    ImageBanner,
    CurrentOffers,
    WithYou,
    AboutAs,
    Footer,
  },
  props: {
    categories: Object,
  },
  mounted(){
    
    // console.log(this.categories);
  },
  data() {
    return {
      form: {
      },
    }
  },
  
  methods: {
    
  },
}
</script>
