<template>
  <div>
    <Head title="Баннеры" />
    <h1 class="mb-8 text-3xl font-bold">Баннеры</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">С удаленными</option>
          <option value="only">Только удаленные</option>
        </select>
      </search-filter>
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
          <th class="pb-4 pt-6 px-6">Հայերեն</th>
          <th class="pb-4 pt-6 px-6">Русский</th>
          <th class="pb-4 pt-6 px-6">English</th>
          <th class="pb-4 pt-6 px-6">Фото</th>
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
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/admin/banners/${banner.id}/edit`">
                {{ banner.text_arm }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/admin/banners/${banner.id}/edit`" tabindex="-1">
                {{ banner.text_ru }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/admin/banners/${banner.id}/edit`" tabindex="-1">
                {{ banner.text_en }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/admin/banners/${banner.id}/edit`" tabindex="-1">
                {{ banner.image_big }}
              </Link>
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
  },
}
</script>
