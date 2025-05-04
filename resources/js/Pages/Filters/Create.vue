<template>
  <div>
    <Head title="Create Contact" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/filter">Фильтр</Link>
      <span class="text-indigo-400 font-medium">/</span> Создать
    </h1>
    <div class="mb-8 bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
      
          <text-input v-model="form.name_arm" :error="form.errors.name_arm" class="pb-8 pr-6 w-full lg:w-1/3" label="Название Арм" />
          <text-input v-model="form.name_ru" :error="form.errors.name_ru" class="pb-8 pr-6 w-full lg:w-1/3" label="Название Ру" />
          <text-input v-model="form.name_en" :error="form.errors.name_en" class="pb-8 pr-6 w-full lg:w-1/3" label="Название Анг" />
          <label class="custom_checkbox">One
              <input v-model="form.filterable" type="checkbox" checked="checked">
              <span class="checkmark"></span>
          </label>
          <div class="flex flex-wrap w-full -mb-8 -mr-6 p-8" v-if="form.filterable">
            <div v-for="key in countOfCustom" class="flex flex-wrap w-full -mb-8 -mr-6 p-8">
              <text-input v-model="customData[key-1].name_arm" class="pb-8 pr-6 w-full lg:w-1/3" label="Значение Арм" />
              <text-input v-model="customData[key-1].name_ru" class="pb-8 pr-6 w-full lg:w-1/3" label="Значение Ру" />
              <text-input v-model="customData[key-1].name_en" class="pb-8 pr-6 w-full lg:w-1/3" label="Значение Анг" />
            </div>
            <div class="add_button" @click="addDelivery()">
              <img src="/images/add_photo_blue.svg">
              <span>Добавить значение</span>
            </div>
          </div>
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
    organizations: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name_arm: '',
        name_ru: '',
        name_en: '',
        customData: [],
        filterable: false,
      }),
      customData: [
        {
          name_arm: '',
          name_ru: '',
          name_en: '',
        }
      ],
      countOfCustom:1,
    }
  },
  methods: {
    // store() {
    //   this.form.post('/categories')
    // },
    store() {
      this.form.customData = this.customData;
        this.form.post('/filter')
      },
  },
}
</script>
<style  lang="scss">
.add_button{
  display: flex;
  align-items: center;
  margin: 0 auto;
  width: fit-content;
  color: #3F4EBD;
  font-size: 14px;
  line-height: 130%;
  padding: 14px 73px;
  cursor: pointer;
  img{
    height: 20px;
    margin-right: 6px;
  }
}
</style>
