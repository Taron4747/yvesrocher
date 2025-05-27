<template>
  <div>
    <Head :title="`${form.first_name} ${form.last_name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/proposals">Предложения бизнеса</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.type }} 
    </h1>
    <trashed-message v-if="proposal.deleted_at" class="mb-6" @restore="restore"> This proposal has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class=" -mb-8 -mr-6 p-8">
      
      <select-input v-model="form.type" :error="form.errors.type" class="pb-8 pr-6 w-full " label="Тип">
        <option :value="null" />
        <option v-for="opt in dataTypes" :key="opt.name" :value="opt.name">{{ opt.name }}</option> 
    
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
<img
            :src="image"
            alt="Preview"
            class="max-h-48 object-contain border rounded"
          />
    </div>

        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!proposal.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy"> Удалить   предложение </button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Редактировать  предложение </loading-button>
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
import ImageInput from '@/Shared/ImageInput.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    ImageInput
  },
  layout: Layout,
  props: {
    proposal: Object,
    organizations: Array,
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
      image: this.proposal.image,
      form: this.$inertia.form({
        proposition_en: this.proposal.proposition_en,
        proposition_ru: this.proposal.proposition_ru,
        proposition_arm: this.proposal.proposition_arm,
        description_ru: this.proposal.description_ru,
        description_en: this.proposal.description_en,
        description_arm: this.proposal.description_arm,
        type: this.proposal.type,
        image: null,
       
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/admin/proposals/${this.proposal.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this proposal?')) {
        this.$inertia.delete(`/admin/proposals/${this.proposal.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this proposal?')) {
        this.$inertia.post(`/admin/proposals/${this.proposal.id}/restore`)
      }
    },
  },
}
</script>
