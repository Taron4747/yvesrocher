<template>
  <div class="bg-green-900 text-white px-6 py-2 relative overflow-hidden flex items-center h-10">
    <button @click="prev" class="text-white text-xl z-10 px-2 left_button">
      <img src="/images/vector_banner.svg">
    </button>

    <div class="relative flex-1 overflow-hidden mx-4">
      <transition name="slide-out" mode="out-in">
        <div :key="currentIndex" class="text-center font-medium hover_text">
          <a :href="currentLink" v-html="currentMessage" />
        </div>
      </transition>
    </div>

    <button @click="next" class="text-white text-xl z-10 px-2 right_button">
      <img src="/images/vector_banner.svg">
    </button>

    <img class="cross" src="/images/cross.svg" @click="hideBanner">
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
import Cookies from 'js-cookie'

const emit = defineEmits(['hide'])

const props = defineProps({
  banners: {
    type: Array,
    required: true,
  },
  interval: {
    type: Number,
    default: 5000, // 3 секунды покоя
  }
})

const hideBanner = () => {
  Cookies.set('hide_promo_banner', true, { expires: 7 })
    emit('hide')
}

const currentIndex = ref(0)

// Для того чтобы отобразить нужное сообщение на основе языка
const currentMessage = computed(() => {
  const banner = props.banners[currentIndex.value]
  
  if (banner && banner.text_ru) {
    return banner.text_ru + ' - ' +banner.proposition_ru
  } else {
    return 'No message available'
  }
})

const currentLink = computed(() => {
  const banner = props.banners[currentIndex.value]
  
  if (banner && banner.text_ru) {
    return banner.link
  } else {
    return '/'
  }
})

let timer = null

const resetTimer = () => {
  if (timer) clearInterval(timer)
  timer = setInterval(() => {
    next()
  }, props.interval)
}

const next = () => {
  currentIndex.value = (currentIndex.value + 1) % props.banners.length
  resetTimer()
}

const prev = () => {
  currentIndex.value = (currentIndex.value - 1 + props.banners.length) % props.banners.length
  resetTimer()
}

onMounted(() => {
  if (props.banners.length > 0) {
    resetTimer()
  } else {
    console.error('Banners are empty!')
  }
})

onBeforeUnmount(() => {
  clearInterval(timer)
})

onBeforeUnmount(() => {
  clearInterval(timer)
})
</script>

<style scoped>
.slide-out-enter-active,
.slide-out-leave-active {
  transition: all 0.5s ease;
}

.slide-out-enter-from {
  transform: translateX(100%);
  opacity: 0;
}

.slide-out-enter-to {
  transform: translateX(0);
  opacity: 1;
}

.slide-out-leave-from {
  transform: translateX(0);
  opacity: 1;
}

.slide-out-leave-to {
  transform: translateX(-100%);
  opacity: 0;
}

.left_button {
  margin-left: 20%;
}

.right_button {
  margin-right: calc(20% - 45px);
}
/* .left_button img {
  transform: rotate(90deg);
} */

.right_button img {
  transform: rotate(180deg);
}

.cross {
  cursor: pointer;
  width: 10px;
  height: 10px;
  margin-right: 35px;
}
</style>
