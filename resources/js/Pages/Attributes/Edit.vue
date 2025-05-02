<template>
  <div>
    <Head :title="`${form.name_arm} `" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/categories">categories</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name_arm }} 
    </h1>
    <trashed-message v-if="category.deleted_at" class="mb-6" @restore="restore"> This category has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name_arm" :error="form.errors.name_arm" class="pb-8 pr-6 w-full lg:w-1/2" label="Name arm" />
          <text-input v-model="form.name_ru" :error="form.errors.name_ru" class="pb-8 pr-6 w-full lg:w-1/2" label="Name ru" />
         
          <text-input v-model="form.name_en" :error="form.errors.name_en" class="pb-8 pr-6 w-full lg:w-1/2" label="Name en" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!category.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete category</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update category</loading-button>
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
    category: Object,
    organizations: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
      
        name_arm: this.category.name_arm,
        name_ru: this.category.name_ru,
        name_en: this.category.name_en,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/categories/${this.category.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this category?')) {
        this.$inertia.delete(`/categories/${this.category.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this category?')) {
        this.$inertia.put(`/categories/${this.category.id}/restore`)
      }
    },
  },
}
</script>
