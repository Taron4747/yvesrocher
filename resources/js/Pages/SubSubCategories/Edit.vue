<template>
  <div>
    <Head :title="`${form.name_arm} `" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/sub-sub-categories">Sub sub categories</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name_arm }} 
    </h1>
    <trashed-message v-if="category.deleted_at" class="mb-6" @restore="restore"> This sub sub category has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <select-input v-model="form.parent_id" :error="form.errors.parent_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Category">
            <option :value="null" />
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name_arm }}</option>
          </select-input>
          <text-input v-model="form.name_arm" :error="form.errors.name_arm" class="pb-8 pr-6 w-full lg:w-1/2" label="Name arm" />
          <text-input v-model="form.name_ru" :error="form.errors.name_ru" class="pb-8 pr-6 w-full lg:w-1/2" label="Name ru" />
         
          <text-input v-model="form.name_en" :error="form.errors.name_en" class="pb-8 pr-6 w-full lg:w-1/2" label="Name en" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!category.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete sub category</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update sub sub category</loading-button>
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
    categories: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
      
        name_arm: this.category.name_arm,
        name_ru: this.category.name_ru,
        name_en: this.category.name_en,
        parent_id: this.category.parent_id,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/sub-sub-categories/${this.category.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this sub sub category?')) {
        this.$inertia.delete(`/sub-sub-categories/${this.category.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this sub sub  category?')) {
        this.$inertia.put(`/sub-sub-categories/${this.category.id}/restore`)
      }
    },
  },
}
</script>
