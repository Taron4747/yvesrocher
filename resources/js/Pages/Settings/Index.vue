<template>
  <div>
    <Head :title="`${form.name_arm} `" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/settings">Настройки </Link>
      
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="-mb-8 -mr-6 p-8">
     
                <div class="title_big">Настройки</div>
                <text-input v-model="form.delivery_price" :error="form.errors.delivery_price" class="pb-8 pr-6 w-full " label="Стоимость доставки (в драмах)" />
                <text-input v-model="form.delivery_discount" :error="form.errors.delivery_discount" class="pb-8 pr-6 w-full " label="Порог для бесплатной доставки (в драмах)" />
             
             
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Сохранить </loading-button>
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
    settings: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
      
        delivery_price: this.settings.delivery_price,
        delivery_discount: this.settings.delivery_discount,

      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/admin/settings/`)
    },
    
  },
}
</script>
