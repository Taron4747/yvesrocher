<template>
  <div class="relative overflow-hidden w-full h-[420px] pb-6 image_banner">
    <!-- Слайды -->
    <div
       class="flex"
      :class="{ 'transition-transform duration-700 ease-in-out': isTransitionEnabled }"
      :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
    >
      <div
        v-for="(slide, index) in slides"
        :key="index"
        class="w-full flex-shrink-0"
        @mouseenter="stopSlider"
        @mouseleave="startSlider"
      > <a :href="slide.link">
        
          <img :src="slide.image_big" alt="Slide image" class="w-full h-[400px]" />
        </a>
      </div>
    </div>

    <!-- Кнопки снизу -->
    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 flex gap-2">
      <button
         v-for="(slide, index) in slides.slice(0, -1)"
        :key="index"
        class="w-3 h-3 rounded-full"
        :class="displayedIndex === index ? 'dark' : 'light'"
        @click="goToSlide(index)"
      ></button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { computed } from 'vue'

const displayedIndex = computed(() => {
  return currentIndex.value === slides.value.length - 1
    ? 0
    : currentIndex.value
})
// Пропс для получения данных баннеров
const props = defineProps({
  imageBanners: {
    type: [Array, Object], // Принять как объект или массив
    required: true,
  },
})
const isTransitionEnabled = ref(true)

const slides = ref([])  // Массив слайдов
const currentIndex = ref(0)
let interval = null
const stopSlider = () => {
  if (interval) {
    clearInterval(interval)
    interval = null
  }
}
// Инициализация слайдов из props
const initSlides = () => {
  // Преобразуем объект в массив, если это объект
  const bannersArray = Array.isArray(props.imageBanners)
    ? props.imageBanners
    : Object.values(props.imageBanners)

  slides.value = bannersArray.map(banner => ({
    image_big: banner.image_big, // Используем image_big для слайдов
    link: banner.link, // Используем image_big для слайдов
  }))
    // Добавим клон первого слайда в конец
  if (slides.value.length > 0) {
    slides.value.push({ ...slides.value[0] })
  }
}

const startSlider = () => {
  stopSlider()
  interval = setInterval(() => {
    if (currentIndex.value < slides.value.length - 1) {
      currentIndex.value++
    } else {
      // Последний слайд (клон первого) -> быстро вернёмся к index 0
      isTransitionEnabled.value = false
      currentIndex.value = 0

      // Принудительно включаем transition обратно на след. кадр
      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          isTransitionEnabled.value = true
        })
      })
    }
  }, 5000)
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
