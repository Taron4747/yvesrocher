<template>
  <div>
    <Head title="Баннеры" />
    <h1 class="mb-8 text-3xl font-bold">Баннеры</h1>
    <div class="flex items-center justify-between mb-6">
      <!-- <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">С удаленными</option>
          <option value="only">Только удаленные</option>
        </select>
      </search-filter> -->
      <Link class="btn-indigo" href="/admin/banners/create">
        <span>Создать</span>
        <span class="hidden md:inline">&nbsp; Баннер </span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">ID</th>
          <th class="pb-4 pt-6 px-6">Название</th>    
          <th class="pb-4 pt-6 px-6">Описание</th>
          <th class="pb-4 pt-6 px-6">Позиция</th>
          <th class="pb-4 pt-6 px-6">Активен</th>
          <th class="pb-4 pt-6 px-6"></th>

        </tr>
        </thead>
        <tbody>
          <tr v-for="banner in banners.data" :key="banner.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/admin/banners/${banner.id}/edit`">
                {{ banner.id }}
                <icon v-if="banner.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
              </Link>
            </td>
            <td class="border-t whitespace-normal">
              <Link class="flex items-center px-6 py-4 " :href="`/admin/banners/${banner.id}/edit`">
                {{ banner.title_ru }}
              </Link>
            </td>
            <td class="border-t whitespace-normal">
              <Link class="flex items-center px-6 py-4" :href="`/admin/banners/${banner.id}/edit`" tabindex="-1">
                {{ banner.text_ru }}
              </Link>
            </td>
            <td class="border-t ">
                 <input type="number" class="width_60 number_input" v-model="banner.position"/>

            </td>
            <td class="border-t ">
              <label class="custom_checkbox text_color"style="margin:25px" >
                <input v-model="banner.is_active" @click="changeActive(banner)" type="checkbox" :true-value="1" :false-value="0" />
                <span class="checkmark"></span>
              </label>

            </td>
            <td class="border-t">
            <div class="btn-indigo" @click="changePosition(banner)" style="cursor: pointer;width: fit-content;">
              Изменить
            </div>
          </td>
          </tr>
          <tr v-if="banners.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">No banners found.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="banners.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination.vue'
import axios from "axios";
import SearchFilter from '@/Shared/SearchFilter.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    banners: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/admin/banners', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    changePosition(banner){
      let formData = new FormData
      formData.set('id', banner.id)
      formData.set('position', banner.position)
      axios.post('/admin/change-banner', formData,
            ).then(response => {
              alert('Изменения сохранены ')
            })
    },
    changeActive(banner){
      let formData = new FormData
      formData.set('id', banner.id)
      formData.set('is_active', banner.is_active)
      axios.post('/admin/banner-activate', formData,
            ).then(response => {
              if (response.data.status === 'warning') {
                alert(response.data.message);
                banner.is_active =0
              } else {
                alert('Изменения сохранены');
              }
            })
    },
  },
}
</script>
