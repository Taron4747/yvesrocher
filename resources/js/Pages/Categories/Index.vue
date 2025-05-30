<template>
  <div>
    <Head title="Categories" />
    <h1 class="mb-8 text-3xl font-bold">Семейства продуктов</h1>
    <div class="flex items-center justify-between mb-6">
      <!-- <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Удалить:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">С удаленными</option>
          <option value="only">Только удаленные</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/admin/categories/create">
        <span>Создать</span>
        <span class="hidden md:inline">&nbsp;Семейства продуктов</span>
      </Link> -->
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Հայերեն</th>
          <th class="pb-4 pt-6 px-6">Русский</th>
          <th class="pb-4 pt-6 px-6">English</th>
          <th class="pb-4 pt-6 px-6">Фото</th>
        </tr>
        <tr v-for="category in categories.data" :key="category.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/admin/categories/${category.id}/edit`">
              {{ category.name_arm }}
              <icon v-if="category.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/admin/categories/${category.id}/edit`" tabindex="-1">
                {{ category.name_ru }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/admin/categories/${category.id}/edit`" tabindex="-1">
              {{ category.name_en }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/admin/categories/${category.id}/edit`" tabindex="-1">
              <img v-if="category.image" class="block -my-2 mr-2 w-5 h-5 rounded-full" :src="category.image" />
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/admin/categories/${category.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="categories.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No categories found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="categories.links" />
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
    categories: Object,
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
        this.$inertia.get('/admin/categories', pickBy(this.form), { preserveState: true })
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
