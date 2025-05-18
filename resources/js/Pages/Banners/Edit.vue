<template>
  <div>
    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/banners"> Баннер </Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.title_ru }}
    </h1>
    <trashed-message v-if="banner.deleted_at" class="mb-6" @restore="restore"> This banner has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class=" -mb-8 -mr-6 p-8">
  
        

  <div class="title_big">Название</div>
  <text-input v-model="form.title_arm" :error="form.errors.title_arm" class="pb-8 pr-6 w-full " label="Հայերեն" />
  <text-input v-model="form.title_ru" :error="form.errors.title_ru" class="pb-8 pr-6 w-full " label="Русский" />
  <text-input v-model="form.title_en" :error="form.errors.title_en" class="pb-8 pr-6 w-full " label="English" />
  <div class="title_big">Описание</div>
  <TextAreaInput v-model="form.text_arm" :error="form.errors.text_arm" class="pb-8 pr-6 w-full " label="Հայերեն"/>
  <TextAreaInput v-model="form.text_ru" :error="form.errors.text_ru" class="pb-8 pr-6 w-full " label="Русский"/>
  <TextAreaInput v-model="form.text_en" :error="form.errors.text_en" class="pb-8 pr-6 w-full " label="English"/>
  <div class="title_big">Значение</div>
  <text-input v-model="form.proposition_arm" :error="form.errors.proposition_arm" class="pb-8 pr-6 w-full " label="Հայերեն" />
  <text-input v-model="form.proposition_ru" :error="form.errors.proposition_ru" class="pb-8 pr-6 w-full " label="Русский" />
  <text-input v-model="form.proposition_en" :error="form.errors.proposition_en" class="pb-8 pr-6 w-full " label="English" />

  <div class="title_big">Ссылка на баннер </div>

  <text-input v-model="form.link" :error="form.errors.link" class="pb-8 pr-6 w-full " label="Ссылка" />
  <div class="title_big">Медиа</div>

  <image-input  :max-files="1" v-model="form.image_big" :error="form.errors.image_big" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото Больше" />
  <img
            :src="image_big"
            alt="Preview"
            class="max-h-48 object-contain border rounded"
          />
  <image-input  :max-files="1" v-model="form.image_medium" :error="form.errors.image_medium" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото Среднее" />
  <img
            :src="image_medium"
            alt="Preview"
            class="max-h-48 object-contain border rounded"
          />
  <image-input  :max-files="1" v-model="form.image_small" :error="form.errors.image_small" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото Маленькое" />
  <img
            :src="image_small"
            alt="Preview"
            class="max-h-48 object-contain border rounded"
          />
  <image-input  :max-files="1" v-model="form.bottom_image" :error="form.errors.bottom_image" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото" />
  <img
            :src="bottom_image"
            alt="Preview"
            class="max-h-48 object-contain border rounded"
          />
  <label class="custom_checkbox text_color">Активен
      <input v-model="form.is_active" type="checkbox" :true-value="1" :false-value="0">
      <span class="checkmark"></span>
  </label>

  <text-input v-model="form.position" :error="form.errors.position" class="pb-8 pr-6 w-full " label="Позиция баннера" />

</div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!banner.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Удалить  Баннер </button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Обновить Баннер </loading-button>
        </div>
      </form>
    </div>
   
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import ImageInput from '@/Shared/ImageInput.vue'
import TextAreaInput from '@/Shared/TextareaInput.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    ImageInput,
    TextAreaInput,
  },
  layout: Layout,
  props: {
    banner: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
    
       

        text_arm: this.banner.text_arm,
        text_ru: this.banner.text_ru,
        text_en: this.banner.text_en,
        title_arm: this.banner.title_arm,
        title_ru: this.banner.title_ru,
        title_en: this.banner.title_en,
        proposition_arm: this.banner.proposition_arm,
        proposition_ru: this.banner.proposition_ru,
        proposition_en: this.banner.proposition_en,
        is_active: this.banner.is_active,
        position: this.banner.position,
        link: this.banner.link,
        image_big: null,
        image_medium: null,
        image_small: null,
        bottom_image: null,


      }),
      image_big: this.banner.image_big,
        image_medium: this.banner.image_medium,
        image_small: this.banner.image_small,
        bottom_image: this.banner.bottom_image,
    }
  },
  methods: {
    update() {
      this.form.post(`/admin/banners/${this.banner.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this banner?')) {
        this.$inertia.delete(`/admin/banners/${this.banner.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this banner?')) {
        this.$inertia.put(`/admin/banners/${this.banner.id}/restore`)
      }
    },
  },
}
</script>
