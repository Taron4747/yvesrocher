<template>
  <div>
    <Head title="Create Contact" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/proposals">Предложения бизнеса  </Link>
      <span class="text-indigo-400 font-medium">/</span> Добавить
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class=" -mb-8 -mr-6 p-8">
      
          <select-input v-model="form.type" :error="form.errors.type" class="pb-8 pr-6 w-full " label="Тип">
            <option :value="null" />
            <option v-for="opt in dataTypes" :key="opt.name" :value="opt.name">{{ opt.name }}</option> 
            <!-- <option :value="Новинки" :key="Новинки">Новинки </option>
            <option :value="Бестселлеры" :key="Бестселлеры"> Бестселлеры </option>
            <option :value="Скидки" :key="Скидки">    Скидки</option>
            <option :value="Экоформаты" :key="Экоформаты">    Экоформаты </option> -->
          </select-input>
          <div class="title_big">Описание</div>
          <text-input v-model="form.description_arm" :error="form.errors.description_arm" class="pb-8 pr-6 w-full " label="Հայերեն"/>
          <text-input v-model="form.description_ru" :error="form.errors.description_ru" class="pb-8 pr-6 w-full " label="Русский"/>
          <text-input v-model="form.description_en" :error="form.errors.description_en" class="pb-8 pr-6 w-full " label="English"/>
          <div class="title_big">Значение</div>
          {{ form.errors.text_arm }}
          {{ form.errors.proposition_arm }}
      <label class="form-label">Հայերեն</label>
                <QuillEditor label=""   contentType="html"
      v-model:content="form.proposition_arm" theme="snow" toolbar="full"   style="height: 300px"
/>
      <label class="form-label">Русский</label>

                <QuillEditor   contentType="html"
      v-model:content="form.proposition_ru" theme="snow" toolbar="full"   style="height: 300px"
/>
      <label class="form-label">English</label>

                <QuillEditor   contentType="html"
      v-model:content="form.proposition_en" theme="snow" toolbar="full"   style="height: 300px"
/>
<image-input  :max-files="1" v-model="form.image" :error="form.errors.image" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create Contact</loading-button>
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
import ImageInput from '@/Shared/ImageInput.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    ImageInput,
  },
  layout: Layout,
  props: {
  },
  remember: 'form',
  data() {
    return {
      dataTypes: [
  { name: 'Новинки' },
  { name: 'Бестселлеры' },
  { name: 'Скидки' },
  { name: 'Экоформаты' }
],
      form: this.$inertia.form({
        type: null,
        description_arm: '',
        
        description_en: '',
        description_ru: '',
        proposition_ru: '',
        proposition_en: '',
        proposition_arm: '',
        image: '',
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/admin/proposals')
    },
  },
}
</script>
