<template>
  <div class="relative overflow-hidden w-full h-[420px] pb-6 image_banner">
    <!-- Слайды -->
    <div
      class="flex transition-transform duration-700 ease-in-out"
      :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
    >
      <div
        v-for="(slide, index) in slides"
        :key="index"
        class="w-full flex-shrink-0"
      >
        <img :src="slide.image" alt="Slide image" class="w-full h-[400px]" />
      </div>
    </div>

    <!-- Кнопки снизу -->
    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 flex gap-2">
      <button
        v-for="(slide, index) in slides"
        :key="index"
        class="w-3 h-3 rounded-full "
        :class="currentIndex === index ? 'dark' : 'light'"
        @click="goToSlide(index)"
      ></button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const slides = ref([
  { image: 'https://medias.yves-rocher.ru/medias/2504-05-HP4-FACE-CARE-AAG-Slider-D-20.jpg?context=bWFzdGVyfHJvb3R8MjIxMzkzfGltYWdlL2pwZWd8c3lzX21hc3Rlci9yb290L2g1ZC9oNjIvMTAwNTQxOTEwNTQ4NzgvMjUwNC0wNS1IUDQtRkFDRS1DQVJFLUFBRy1TbGlkZXItRC0yMC5qcGd8OTk1MzRhOWM5ZDU2Y2I0MjY1ZTZkZDQ3OGNhZjI4NjQ3NDU1MzU1ZGI0OThmZDg5YTAzOTMxMDg0MWVkOGI3Ng&twic=v1/resize=1710/background=white' },
  { image: 'https://medias.yves-rocher.ru/medias/2504-05-HP1-Nutrition-Slider-D.jpg?context=bWFzdGVyfHJvb3R8MzQyMTExfGltYWdlL2pwZWd8c3lzX21hc3Rlci9yb290L2hmZS9oYmEvMTAwNTQxOTA1NjMzNTgvMjUwNC0wNS1IUDEtTnV0cml0aW9uLVNsaWRlci1ELmpwZ3xjYWE3NTBhZWViZDkyY2Y5Yjg1ZGNhYTkxN2RjMmQwZjE5YmZmM2JhODNmMGU3YjMzOTRlOGJhMjZiZDUwNzYw&twic=v1/resize=1710/background=white' },
  { image: 'https://medias.yves-rocher.ru/medias/2504-05-OPENSET-MIX-Slider-D.jpg?context=bWFzdGVyfHJvb3R8NDgwNjQxfGltYWdlL2pwZWd8c3lzX21hc3Rlci9yb290L2g1Yy9oOTcvMTAwNTk2ODIzNDkwODYvMjUwNC0wNS1PUEVOU0VULU1JWC1TbGlkZXItRC5qcGd8MDkxNGI0MTU3N2E2MjBhNDk4MzkwYjQxNzQxN2EyY2I1ZTQ1NGU4M2E2MDNlNDkyOTQ2OWI2YzZmYTcwODg5OA&twic=v1/resize=1710/background=white' },
])

const currentIndex = ref(0)
let interval = null

const startSlider = () => {
  interval = setInterval(() => {
    currentIndex.value = (currentIndex.value + 1) % slides.value.length
  }, 5000)
}

const goToSlide = (index) => {
  currentIndex.value = index
}

onMounted(() => {
  startSlider()
})

onBeforeUnmount(() => {
  clearInterval(interval)
})
</script>

<style scoped lang="scss">
.image_banner {
  max-width: 1140px;
  margin: 35px auto;
  .light{
    background: #e8ecbc;
  }
  .dark{
    background: #143616;
  }
}
</style>
