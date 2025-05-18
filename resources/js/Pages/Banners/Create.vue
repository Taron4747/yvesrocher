<template>
  <div>
    <Head title="Create Organization" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/organizations">Organizations</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
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
          <image-input v-if="form.type ==2" :max-files="1" v-model="form.image_big" :error="form.errors.image_big" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото Больше" />
          <image-input v-if="form.type ==2" :max-files="1" v-model="form.image_medium" :error="form.errors.image_medium" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото Среднее" />
          <image-input v-if="form.type ==2" :max-files="1" v-model="form.image_small" :error="form.errors.image_small" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото Маленькое" />

        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Создать Баннер</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import FileInput from '@/Shared/FileInput.vue'
import ImageInput from '@/Shared/ImageInput.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    FileInput,
    ImageInput,
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        type: 1,
        text_arm: null,
        text_ru: null,
        text_en: null,
        link: null,
        image_big: null,
        image_medium: null,
        image_small: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/admin/banners')
    },
  },
}
</script>
