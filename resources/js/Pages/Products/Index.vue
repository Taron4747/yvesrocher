<template>
  <div>
    <Head title="filters" />
    <h1 class="mb-8 text-3xl font-bold">Продукты</h1>
    <div class="flex items-center justify-between mb-6">
      <!-- <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Удалить:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">С удаленными</option>
          <option value="only">Только удаленные</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/admin/product/create">
        <span>Создать</span>
        <span class="hidden md:inline">&nbsp;Продукт</span>
      </Link> -->
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Հայերեն</th>
          <th class="pb-4 pt-6 px-6">Русский</th>
          <th class="pb-4 pt-6 px-6">English</th>
          <th class="pb-4 pt-6 px-6">Цена</th>
          <th class="pb-4 pt-6 px-6">Колличество</th>
          <th class="pb-4 pt-6 px-6"></th>
        </tr>
        <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t whitespace-normal">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/admin/product/${product.id}/edit`">
              {{ product.name_arm }}
              <icon v-if="product.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t whitespace-normal">
            <Link class="flex items-center px-6 py-4" :href="`/admin/product/${product.id}/edit`" tabindex="-1">
                {{ product.name_ru }}
            </Link>
          </td>
          <td class="border-t whitespace-normal">
            <Link class="flex items-center px-6 py-4" :href="`/admin/product/${product.id}/edit`" tabindex="-1">
              {{ product.name_en }}
            </Link>
          </td>
          <td class="border-t">
            <input type="number" class="width_60 number_input" v-model="product.price"/>
          </td>
          <td class="border-t">
            <input type="number" class="width_60 number_input" v-model="product.count"/>
          </td>
          <td class="border-t">
            <div class="btn-indigo" @click="changePriceCount(product)" style="cursor: pointer;width: fit-content;">
              Изменить
            </div>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/admin/product/${product.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="products.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No products found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="products.links" />
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
import TextInput from '@/Shared/TextInput.vue'
import axios from "axios";

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
    TextInput,
  },
  layout: Layout,
  props: {
    filters: Object,
    products: Object,
  },
  mounted(){
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
        this.$inertia.get('/filters', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    changePriceCount(product){
      let formData = new FormData
      formData.set('id', product.id)
      formData.set('count', product.count)
      formData.set('price', product.price)
      axios.post('/admin/product-count-change', formData,
            ).then(response => {
              alert("Изменения сохранены")
            })
    }
  },
}
</script>
