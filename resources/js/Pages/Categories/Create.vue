<template>
  <div>
    <Head title="Create Contact" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/categories">Категории</Link>
      <span class="text-indigo-400 font-medium">/</span> Создать
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
      
          <text-input v-model="form.name_arm" :error="form.errors.name_arm" class="pb-8 pr-6 w-full lg:w-1/2" label="Название Арм" />
          <text-input v-model="form.name_ru" :error="form.errors.name_ru" class="pb-8 pr-6 w-full lg:w-1/2" label="Название Ру" />
          <text-input v-model="form.name_en" :error="form.errors.name_en" class="pb-8 pr-6 w-full lg:w-1/2" label="Название Анг" />
          <file-input v-model="form.image" :error="form.errors.image" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="Фото" />
         
        </div>


        <div class="w-full px-8 mt-6">
  <label class="block font-bold mb-4">Фильтры по значениям</label>

  <div v-for="filter in filters" :key="filter.id" class="mb-4">
    <p class="font-semibold mb-2">{{ filter.name_ru }}</p>

    <div class="flex flex-wrap">
      <div v-for="value in filter.values" :key="value.id" class="mr-4 mb-2">
        <label class="inline-flex items-center">
          <input
            type="checkbox"
            :value="value.id"
            v-model="form.filter_value_ids"
            class="form-checkbox"
          >
          <span class="ml-2">{{ value.name_ru }}</span>
        </label>
      </div>
    </div>
  </div>
</div>

<!-- Мультиселект: buttonFilters -->
<div class="w-full px-8 mt-6">
  <label class="block font-bold mb-2">Кнопочные фильтры (мультиселект)</label>

  <select-input  v-model="form.button_filters" :error="form.errors.button_filters" class="pb-8 pr-6 w-full lg:w-1/2" label="Кнопочные фильтры (мультиселект)">
    <option v-for="filter in butonFilters" :key="filter.id" :value="filter.id">
      {{ filter.name_ru }}
    </option>
          </select-input>


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
import FileInput from '@/Shared/FileInput.vue'

export default {
  components: {
    Head,
    Link,
    FileInput,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    filters: Array,
    butonFilters: Array,
  },
  remember: 'form',
  data() {
  return {
    form: this.$inertia.form({
      name_arm: '',
      name_ru: '',
      name_en: '',
      image: null,
      filters: [], // selected filterable IDs (checkbox)
      button_filters: [], // selected button filter IDs (multi-select)
    }),
  }
},
  methods: {
    store() {
      this.form.post('/categories')
    },
  },
}
</script>
