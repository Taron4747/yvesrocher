<template>
  <div>
    <div class="margin_1">
      <Link class="group flex items-center py-3" href="/admin">
      <icon name="dashboard" class="mr-2 w-4 h-4"
        :class="isUrl('admin') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="isUrl('admin') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Панель</div>
      </Link>
    </div>
    <div class="margin_1">
      <Link class="group flex items-center py-3" href="/admin/filter">
      <icon name="printer" class="mr-2 w-4 h-4"
        :class="isUrl('admin/filters') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="isUrl('admin/filters') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Фильтры</div>
      </Link>
    </div>
    <div class="margin_1 group flex items-center" style="cursor: pointer;" @click="active = !active">
      <icon name="office" class="mr-2 w-4 h-4"
        :class="active ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="active ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Группировка товаров</div>
      <icon style="width: 20px;" :class="active ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" name="cheveron-down" />
    </div>
    <div class="dropdown bg-indigo-700" v-if="active">
      <div class="margin_1">
        <Link class="group flex items-center py-3" href="/admin/categories">
        <icon name="office" class="mr-2 w-4 h-4"
          :class="isUrl('admin/categories') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('admin/categories') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Семейство продуктов</div>
        </Link>
      </div>
      <div class="margin_1">
        <Link class="group flex items-center py-3" href="/admin/sub-categories">
        <icon name="office" class="mr-2 w-4 h-4"
          :class="isUrl('admin/sub-categories') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('admin/sub-categories') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Категории
        </div>
        </Link>
      </div>
      <div class="margin_1">
        <Link class="group flex items-center py-3" href="/admin/sub-sub-categories">
        <icon name="office" class="mr-2 w-4 h-4"
          :class="isUrl('admin/sub-sub-categories') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('admin/sub-sub-categories') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Подкатегории</div>
        </Link>
      </div>
    </div>
 
    <div class="margin_1">
      <Link class="group flex items-center py-3" href="/admin/product">
      <icon name="printer" class="mr-2 w-4 h-4"
        :class="isUrl('admin/product') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="isUrl('admin/product') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Продукты</div>
      </Link>
    </div>

    <div class="margin_1">
      <Link class="group flex items-center py-3" href="/admin/banners">
      <icon name="printer" class="mr-2 w-4 h-4"
        :class="isUrl('admin/banners') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="isUrl('admin/banners') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Баннеры</div>
      </Link>
    </div>

    <div class="margin_1">
      <Link class="group flex items-center py-3" href="/admin/settings">
      <icon name="printer" class="mr-2 w-4 h-4"
        :class="isUrl('admin/settings') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="isUrl('admin/settings') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Настройки</div>
      </Link>
    </div>
    <div class="margin_1">
      <Link class="group flex items-center py-3" href="/admin/shops">
      <icon name="printer" class="mr-2 w-4 h-4"
        :class="isUrl('admin/shops') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="isUrl('admin/shops') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Магазины</div>
      </Link>
    </div>
    <div class="margin_1">
      <Link class="group flex items-center py-3" href="/admin/proposals">
      <icon name="printer" class="mr-2 w-4 h-4"
        :class="isUrl('admin/proposals') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="isUrl('admin/proposals') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Предложения бизнеса </div>
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
   <div class="margin_1">
      <Link class="group flex items-center py-3" href="/admin/users">
        <icon name="users" class="mr-2 w-4 h-4" :class="isUrl('admin/users') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('admin/users') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Пользователи</div>
      </Link>
    </div>
   <!--   <div class="mb-4">
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
<style scoped lang="scss">
.dropdown{
  
}
</style>
