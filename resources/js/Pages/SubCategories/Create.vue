<template>
  <div>
    <Head title="Create Contact" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/sub-categories">Категории</Link>
      <span class="text-indigo-400 font-medium">/</span> Создать
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="-mb-8 -mr-6 p-8">
          <div class="title_big">Семейства продуктов</div>
          <select-input v-model="form.parent_id" :error="form.errors.parent_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Семейства продуктов">
            <option :value="null" />
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name_arm }}</option>
          </select-input>
          <div class="title_big">Название</div>
          <text-input v-model="form.name_arm" :error="form.errors.name_arm" class="pb-8 pr-6 w-full lg:w-1/2" label="Հայերեն" />
          <text-input v-model="form.name_ru" :error="form.errors.name_ru" class="pb-8 pr-6 w-full lg:w-1/2" label="Русский" />
          <text-input v-model="form.name_en" :error="form.errors.name_en" class="pb-8 pr-6 w-full lg:w-1/2" label="English" />
          <div class="title_big">Описание</div>
          <TextAreaInput v-model="form.description_arm" :error="form.errors.description_arm" class="pb-8 pr-6 w-full " label="Հայերեն"/>
          <TextAreaInput v-model="form.description_ru" :error="form.errors.description_ru" class="pb-8 pr-6 w-full " label="Русский"/>
          <TextAreaInput v-model="form.description_en" :error="form.errors.description_en" class="pb-8 pr-6 w-full " label="English"/>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Создать категорию</loading-button>
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
import TextAreaInput from '@/Shared/TextareaInput.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextAreaInput,
  },
  layout: Layout,
  props: {
    categories: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name_arm: '',
        name_ru: '',
        name_en: '',
        parent_id: '',
        description_en: '',
        description_arm: '',
        description_ru: '',
      
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/admin/sub-categories')
    },
  },
}
</script>
