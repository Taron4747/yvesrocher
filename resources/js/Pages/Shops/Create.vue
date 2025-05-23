<template>
  <div>
    <Head title="Create Contact" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/shops">Магазины</Link>
      <span class="text-indigo-400 font-medium">/</span> Создать
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="-mb-8 -mr-6 p-8">
     
          <div class="title_big">Адрес</div>
          <text-input v-model="form.address_arm" :error="form.errors.address_arm" class="pb-8 pr-6 w-full lg:w-1/2" label="Հայերեն" />
          <text-input v-model="form.address_ru" :error="form.errors.address_ru" class="pb-8 pr-6 w-full lg:w-1/2" label="Русский" />
          <text-input v-model="form.address_en" :error="form.errors.address_en" class="pb-8 pr-6 w-full lg:w-1/2" label="English" />
          <div class="title_big">Телефон</div>
          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Номер телефона" />
          <div class="title_big">Рабочие часы</div>
    <div class="flex gap-2">
      <text-input
        v-model="form.time_from"
        type="time"
        class="pb-8 pr-6 w-full lg:w-1/4"
      />
      <text-input
        v-model="form.time_to"
        type="time"
        class="pb-8 pr-6 w-full lg:w-1/4"
      />
    </div>


        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Создать Магазин</loading-button>
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

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    categories: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        address_arm: '',
        address_ru: '',
        address_en: '',
        phone: '',
        time_to: '',
        time_from: '',
      
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/admin/shops')
    },
  },
}
</script>
