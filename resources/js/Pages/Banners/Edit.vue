<template>
  <div>
    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/banners">banners</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="banner.deleted_at" class="mb-6" @restore="restore"> This banner has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <select-input v-model="form.type" :error="form.errors.type" class="pb-8 pr-6 w-full " label=" Тип баннера">
            <option :value="null" />  
            <option value="1">Текстовый баннер</option>
            <option value="2">Фото баннер</option>
          </select-input>
          <text-input  v-if="form.type ==1" v-model="form.text_arm" :error="form.errors.text_arm" class="pb-8 pr-6 w-full " label="Текст Арм" />
          <text-input v-if="form.type ==1" v-model="form.text_ru" :error="form.errors.text_ru" class="pb-8 pr-6 w-full " label="Текст Ру " />
          <text-input v-if="form.type ==1" v-model="form.text_en" :error="form.errors.text_en" class="pb-8 pr-6 w-full " label="Текст Анг  " />
        
        
          <text-input v-model="form.link" :error="form.errors.link" class="pb-8 pr-6 w-full " label="Ссылка" />
          <image-input v-if="form.type ==2" v-model="form.image_big" :error="form.errors.image_big" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото Больше" />
          <image-input v-if="form.type ==2" v-model="form.image_medium" :error="form.errors.image_medium" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото Среднее" />
          <image-input v-if="form.type ==2" v-model="form.image_small" :error="form.errors.image_small" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото Маленькое" />

        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!banner.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete banner</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update banner</loading-button>
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
        link: this.banner.link,
        image_big: this.banner.image_big,
        image_medium: this.banner.image_medium,
        image_smal: this.banner.image_smal,
       
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/admin/banners/${this.banner.id}`)
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
