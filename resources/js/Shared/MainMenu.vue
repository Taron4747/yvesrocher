<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/admin">
      <icon name="dashboard" class="mr-2 w-4 h-4"
        :class="isUrl('admin') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="isUrl('admin') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Панель</div>
      </Link>
    </div>
    <div class="mb-4 group flex items-center" style="cursor: pointer;" @click="active = !active">
      <icon name="office" class="mr-2 w-4 h-4"
        :class="active ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="active ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Категории</div>
      <icon style="width: 20px;" :class="active ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" name="cheveron-down" />
    </div>
    <div class="dropdown" v-if="active">
      <div class="mb-4">
        <Link class="group flex items-center py-3" href="/categories">
        <icon name="office" class="mr-2 w-4 h-4"
          :class="isUrl('categories') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('categories') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Категории</div>
        </Link>
      </div>
      <div class="mb-4">
        <Link class="group flex items-center py-3" href="/sub-categories">
        <icon name="office" class="mr-2 w-4 h-4"
          :class="isUrl('sub-categories') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('sub-categories') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Подкатегории
        </div>
        </Link>
      </div>
      <div class="mb-4">
        <Link class="group flex items-center py-3" href="/sub-sub-categories">
        <icon name="office" class="mr-2 w-4 h-4"
          :class="isUrl('sub-sub-categories') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('sub-sub-categories') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Под Подкатегории</div>
        </Link>
      </div>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/filter">
      <icon name="printer" class="mr-2 w-4 h-4"
        :class="isUrl('filters') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="isUrl('filters') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Фильтры</div>
      </Link>
    </div>
    <!-- <dropdown class="mt-1" placement="bottom-end">
      <template #default>
        <div class="mb-4">
          <div class="group flex items-center py-3">
          <icon name="office" class="mr-2 w-4 h-4"
            :class="isUrl('categories') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
          <div :class="isUrl('categories') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Categories</div>
          <icon   :class="isUrl('categories') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" name="cheveron-down" />
          </div>
        </div>
      </template>
      <template #dropdown>
        
      </template>
    </dropdown> -->
    <!-- <div class="mb-4">
      <Link class="group flex items-center py-3" href="/contacts">
        <icon name="users" class="mr-2 w-4 h-4" :class="isUrl('contacts') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('contacts') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Contacts</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/reports">
        <icon name="printer" class="mr-2 w-4 h-4" :class="isUrl('reports') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('reports') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Reports</div>
      </Link>
    </div> -->
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Dropdown from '@/Shared/Dropdown.vue'

export default {
  components: {
    Dropdown,
    Icon,
    Link,
  },
  data() {
    return {
      active: false,
    }
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
  },
}
</script>
