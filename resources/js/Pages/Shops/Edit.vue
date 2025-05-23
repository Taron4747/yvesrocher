<template>
  <div>
    <Head :title="`${form.name_arm} `" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/shops">Магазины </Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name_arm }} 
    </h1>
    <trashed-message v-if="shop.deleted_at" class="mb-6" @restore="restore"> This shop has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
              v-model="form.time_from" :error="form.errors.time_from"
              type="time"
              class="pb-8 pr-6 w-full lg:w-1/4"
            />
            <text-input
              v-model="form.time_to" :error="form.errors.time_to"
              type="time"
              class="pb-8 pr-6 w-full lg:w-1/4"
            />
            </div>
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!shop.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Удалить Магазин</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Редактировать Магазин </loading-button>
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
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    shop: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
      
        address_arm: this.shop.address_arm,
        address_ru: this.shop.address_ru,
        address_en: this.shop.address_en,
        phone: this.shop.phone,
        time_from: this.shop.time_from,
        time_to: this.shop.time_to,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/admin/shops/${this.shop.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this sub shop?')) {
        this.$inertia.delete(`/admin/shops/${this.shop.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this sub  shop?')) {
        this.$inertia.put(`/admin/shops/${this.shop.id}/restore`)
      }
    },
  },
}
</script>
