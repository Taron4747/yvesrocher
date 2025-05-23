<template>
  <div>
    <Head :title="`${form.name_arm} `" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/categories">Семейство продуктов</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name_ru }} 
    </h1>
    <trashed-message v-if="category.deleted_at" class="mb-6" @restore="restore"> This category has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="-mb-8 -mr-6 p-8">
          <div class="title_big">Название</div>
          <text-input v-model="form.name_arm" :error="form.errors.name_arm" class="pb-8 pr-6 w-full lg:w-1/2" label="Հայերեն" />
          <text-input v-model="form.name_ru" :error="form.errors.name_ru" class="pb-8 pr-6 w-full lg:w-1/2" label="Русский" />
          <text-input v-model="form.name_en" :error="form.errors.name_en" class="pb-8 pr-6 w-full lg:w-1/2" label="English" />
          <div class="title_big">Медиа</div>
          <image-input v-model="form.image" :error="form.errors.image" label="Фото в меню (только 1 фото)" class="pb-8 pr-6 w-full lg:w-1/2" accept="image/*" :max-files="1"/>
          <div class="grid grid-cols-2 gap-2 mt-2">
          <img
            :src="image"
            alt="Preview"
            class="max-h-48 object-contain border rounded"
          />
        </div>
          <image-input v-model="form.second_image" :error="form.errors.second_image" label="Фото в каталоге(только 1 фото)" class="pb-8 pr-6 w-full lg:w-1/2" accept="image/*" :max-files="1"/>
          <div class="grid grid-cols-2 gap-2 mt-2">
          <img
            :src="second_image"
            alt="Preview"
            class="max-h-48 object-contain border rounded"
          />
        </div>
          <div class="w-full mt-6">
          <div class="title_big">Фильтры</div>
           <span v-if="errorMessage" class="form-error">{{ errorMessage }}</span>
          <div v-for="filter in filtersData" :key="filter.id" class="mb-4">
            <label class="custom_checkbox custom_checkbox_bold">{{filter.name_ru}}
                <input v-model="filter.type" type="checkbox" checked="checked" @change="onFilterChange(filter)">
                <span class="checkmark"></span>
            </label>
            <div class="flex flex-wrap checkbox_border">
              <div v-for="value in filter.sub_filters" :key="value.id" class="mr-4 mb-2" @change="onFilterValueChange(filter.id)">
                <label class="inline-flex items-center">
                  <label class="custom_checkbox text_color">{{value.name_ru}}
                      <input v-model="value.type" type="checkbox" checked="checked">
                      <span class="checkmark"></span>
                  </label>
                </label>
              </div>
            </div>
          </div>
        </div>
        <!-- <multiselect 
          v-model="selected" 
          :options="butonFiltersData" 
          :multiple="true" 
          placeholder="Выберите фильтры" 
          label="name_arm"  
          track-by="id"  
           class="width_30"
        /> -->
        </div>
       
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!category.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Удалить категорию</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Обновить семейство продуктов</loading-button>
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
import Multiselect from 'vue-multiselect'
import ImageInput from '@/Shared/ImageInput.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    Multiselect,
    ImageInput,
  },
  layout: Layout,
  props: {
    category: Object,
  },
  remember: 'form',
  data() {
    return {
      filtersData:this.category.filters,
      // butonFiltersData:this.category.buton_filters,
      // selected: [],
      errorMessage:"",
      form: this.$inertia.form({
        name_arm: this.category.name_arm,
        name_ru: this.category.name_ru,
        name_en: this.category.name_en,
        image:  null,
        second_image: null,
        filters: [], 
        button_filters: [], 
      }),
      image:  this.category.image,
      second_image: this.category.second_image,
    }
  },
  mounted(){
  const categoryFiltersArray = Object.values(this.category.category_filters);
  this.filtersData.forEach((filter) => {
    filter.type = false;
    filter.sub_filters.forEach((sub) => sub.type = false);
  });
  categoryFiltersArray.forEach((catFilter) => {
    this.filtersData.forEach((filter) => {
      if(catFilter.id === filter.id){
        filter.type = true;
        Object.values(catFilter.sub_filters).forEach((catSubFilter) => {
          console.log(catSubFilter)
          filter.sub_filters.forEach((subFilter) => {
            if(subFilter.id === catSubFilter.id){
              subFilter.type = true;
              
            }
          });
        });
      }
    });
  });
  },
  methods: {
    update() {
       this.errorMessage = "";
      const selectedFilters = this.filtersData.filter(filter => filter.type === true);

      if (selectedFilters.length === 0) {
        this.errorMessage = 'Выберите хотя бы один фильтр';
        return false;
      } else {
          // Проверка 2: у каждого выбранного фильтра должен быть хотя бы один активный подфильтр
          const hasInvalidSubFilters = selectedFilters.some(filter => {
              return !filter.sub_filters.some(sub => sub.type === true);
          });

          if (hasInvalidSubFilters) {
              this.errorMessage = 'Значение фильтра обязательно';
              return false;
          } 
      }
      
      
      
      this.form.filters = this.filtersData
      this.form.post(`/admin/categories/${this.category.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this category?')) {
        this.$inertia.delete(`/admin/categories/${this.category.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this category?')) {
        this.$inertia.put(`/admin/categories/${this.category.id}/restore`)
      }
    },
    onFilterChange(filter){
      if(!filter.type){
        this.filtersData.forEach((filters) => {
          if(filters.id == filter.id){
            filters.sub_filters.forEach((value) => {
            value.type = filter.type;
          });
          }
        });
      }
    },
    onFilterValueChange(id){
      const item = this.filtersData.find(item => item.id === id);
      if (item) item.type = true;
    }
  },
}
</script>
