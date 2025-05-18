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
        <img :src="slide.image_big" alt="Slide image" class="w-full h-[400px]" />
      </div>
    </div>

    <!-- Кнопки снизу -->
    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 flex gap-2">
      <button
        v-for="(slide, index) in slides"
        :key="index"
        class="w-3 h-3 rounded-full"
        :class="currentIndex === index ? 'dark' : 'light'"
        @click="goToSlide(index)"
      ></button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

// Пропс для получения данных баннеров
const props = defineProps({
  imageBanners: {
    type: [Array, Object], // Принять как объект или массив
    required: true,
  },
})

const slides = ref([])  // Массив слайдов
const currentIndex = ref(0)
let interval = null

// Инициализация слайдов из props
const initSlides = () => {
  // Преобразуем объект в массив, если это объект
  const bannersArray = Array.isArray(props.imageBanners)
    ? props.imageBanners
    : Object.values(props.imageBanners)

  slides.value = bannersArray.map(banner => ({
    image_big: banner.image_big, // Используем image_big для слайдов
  }))
}

// Функция для автоматического переключения слайдов
const startSlider = () => {
  interval = setInterval(() => {
    currentIndex.value = (currentIndex.value + 1) % slides.value.length
  }, 5000) // Переключение каждые 5 секунд
}

// Переход к слайду по индексу
const goToSlide = (index) => {
  currentIndex.value = index
}

// Инициализация слайдов после монтирования компонента
onMounted(() => {
  initSlides()
  startSlider()
})

// Очистка интервала при размонтировании компонента
onBeforeUnmount(() => {
  clearInterval(interval)
})
</script>

<style scoped lang="scss">
.image_banner {
  max-width: 1140px;
  margin: 35px auto;

  .light {
    background: #e8ecbc;
  }

  .dark {
    background: #143616;
  }
}
</style>
