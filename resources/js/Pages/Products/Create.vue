<template>
  <div>
    <Head title="Create Product" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/product">Продукт</Link>
      <span class="text-indigo-400 font-medium">/</span> Создать
    </h1>
    <div class=" bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <!-- <div class="flex flex-wrap -mb-8 -mr-6 p-8"> -->
        <div class="-mb-8 -mr-6 p-8">
          <div class="title_big">Название</div>
          <text-input v-model="form.name_arm" :error="form.errors.name_arm" class="pb-8 pr-6 w-full lg:w-1/3" label="Название Арм" />
          <text-input v-model="form.name_ru" :error="form.errors.name_ru" class="pb-8 pr-6 w-full lg:w-1/3" label="Название Ру" />
          <text-input v-model="form.name_en" :error="form.errors.name_en" class="pb-8 pr-6 w-full lg:w-1/3" label="Название Анг" />
          <div class="title_big">Описание</div>
          <TextAreaInput v-model="form.description_arm" :error="form.errors.description_arm" class="pb-8 pr-6 w-full lg:w-1/3" label="Описание Арм"/>
          <TextAreaInput v-model="form.description_ru" :error="form.errors.description_ru" class="pb-8 pr-6 w-full lg:w-1/3" label="Описание Ру"/>
          <TextAreaInput v-model="form.description_en" :error="form.errors.description_en" class="pb-8 pr-6 w-full lg:w-1/3" label="Описание Анг"/>
          <div class="title_big">Состав</div>
          <text-input v-model="form.composition_arm" :error="form.errors.composition_arm" class="pb-8 pr-6 w-full lg:w-1/3" label="Состав Арм" />
          <text-input v-model="form.composition_ru" :error="form.errors.composition_ru" class="pb-8 pr-6 w-full lg:w-1/3" label="Состав Ру" />
          <text-input v-model="form.composition_en" :error="form.errors.composition_en" class="pb-8 pr-6 w-full lg:w-1/3" label="Состав Анг" />
          <div class="title_big">Медиа</div>
          <file-input v-model="form.image" :error="form.errors.image" class="pb-8 pr-6 w-full lg:w-1/3" type="file" accept="image/*" label="Фото" />
          <div class="title_big">Основные</div>
          <SelectInput v-model="form.category_id" class="pb-8 pr-6 w-full lg:w-1/3" label="Категория">
            <option v-for="opt in categoriesData" :key="opt.id" :value="opt.id">{{ opt.name_arm }}</option>
          </SelectInput>     
          <text-input v-model="form.price" :error="form.errors.price" class="pb-8 pr-6 w-full lg:w-1/3" label="Цена" />
          <text-input v-model="form.size" :error="form.errors.size" class="pb-8 pr-6 w-full lg:w-1/3" label="Размер" />
          <text-input v-model="form.discount" :error="form.errors.discount" class="pb-8 pr-6 w-full lg:w-1/3" label="Скидка" />
          <text-input v-model="form.count" :error="form.errors.count" class="pb-8 pr-6 w-full lg:w-1/3" label="Колличество" />     
          <label class="custom_checkbox">Есть в наличие
              <input v-model="form.is_exist" type="checkbox" checked="checked">
              <span class="checkmark"></span>
          </label>
          <label class="custom_checkbox">Новинка
              <input v-model="form.is_new" type="checkbox" checked="checked">
              <span class="checkmark"></span>
          </label>
          <label class="custom_checkbox">Лучшие подажи
              <input v-model="form.is_bestseller" type="checkbox" checked="checked">
              <span class="checkmark"></span>
          </label>
        
        <div class="title_big">Фильтры</div>
        <div class="w-full px-8 mt-6">
          <!-- <label class="block font-bold mb-4">Фильтры по значениям</label> -->
          <div v-for="filter in filtersData" :key="filter.id" class="mb-4">
            <label class="custom_checkbox custom_checkbox_bold">{{filter.name_arm}}
                <input v-model="filter.type" type="checkbox" checked="checked" @change="onFilterChange(filter)">
                <span class="checkmark"></span>
            </label>
            <div class="flex flex-wrap checkbox_border">
              <div v-for="value in filter.sub_filters" :key="value.id" class="mr-4 mb-2">
                <label class="inline-flex items-center">
                  <label class="custom_checkbox">{{value.name_arm}}
                      <input v-model="value.type" type="checkbox" checked="checked">
                      <span class="checkmark"></span>
                  </label>
                </label>
              </div>
            </div>
          </div>
          <multiselect 
          v-model="selected" 
          :options="butonFiltersData" 
          :multiple="true" 
          placeholder="Выберите фильтры" 
          label="name_arm"  
          track-by="id"  
          class="width_30"
        />
      </div>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Создать продукт</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import TextAreaInput from '@/Shared/TextareaInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import FileInput from '@/Shared/FileInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import Multiselect from 'vue-multiselect'

// import 'vue-multiselect/dist/vue-multiselect.min.css';

export default {
  components: {
    Head,
    Link,
    FileInput,
    LoadingButton,
    SelectInput,
    TextInput,
    TextAreaInput,
    Multiselect,
  },
  layout: Layout,
  props: {
    categories: Array,
    filters: Array,
    butonFilters: Array,
  },
  remember: 'form',
  data() {
    return {
      categoriesData:this.categories,
      filtersData:this.filters,
      butonFiltersData:this.butonFilters,
      selected: [],
      form: this.$inertia.form({
        name_arm: '',
        name_ru: '',
        name_en: '',
        description_arm: '',
        description_ru: '',
        description_en: '',
        composition_arm: '',
        composition_ru: '',
        composition_en: '',
        price: '',
        size: '',
        discount: '',
        count: '',
        is_exist: '',
        is_new: '',
        is_bestseller: '',
        image: null,
        filters: [], 
        button_filters: [], 
        category_id:'',
      }),
    }
  },
  mounted(){
    console.log(this.categories);
    this.filtersData.forEach((filters) => {
      filters.type = false;
      filters.sub_filters.forEach((value) => {
        value.type = false;
      });
    });
  
  },
  methods: {
    store() {
      this.form.post('/admin/product')
    },
    onFilterChange(filter){
        this.filtersData.forEach((filters) => {
        if(filters.id == filter.id){
          filters.sub_filters.forEach((value) => {
          value.type = filter.type;
        });
        }
      });
    }
  },
}
</script>

<style lang="scss">
.custom_checkbox{
  margin-top: 30px;
  margin-right: 25px;
}
 .input_content{
            display: flex;
            flex-direction: column;
            position: relative;
            margin-bottom: 16px;
            width:32%;
            .p-multiselect{
              padding: 0 !important;
            }
            .vuejs3-datepicker__value {
                width: 100%;
                border: none;
            }
            .form-label{
              padding-left: 16px;
              color: #555B86;
              font-size: 12px;
              margin-bottom: 8px;
            }
            input,textarea,select,.p-multiselect{
                width: 100%;
                height: 48px;
                padding: 13px 16px;
                border: none;
                outline: none;
            }
            textarea:focus, input:focus, select:focus, .p-multiselect:focus{
                outline: none;
                --tw-ring-shadow:none;
            }
            .photo_content{
              border: 1px dashed  #C4C4C4;
              border-radius: 12px;
              margin-top: 26px;
              .file-upload-form{
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 48px;
                cursor: pointer;
                img{
                  height: 20px;
                  width: 20px;
                  margin-right: 16px;
                }
                span{
                  color: #3F4EBD;
                  font-size: 14px;
                  line-height: 130%
                }
              }
              .image-preview{
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0!important;
                width: 100%;
                .preview{
                  height: 100px;
                  width: 100px;
                  object-fit: cover;
                  border-radius: 12px;
                }
                .image_name{
                  max-width: calc(100% - 165px);
                  overflow-wrap: break-word;
                }
                .remove{
                  cursor: pointer;
                  height: 20px;
                  width: 20px;
                  margin-right: 12px;
                }
              }
            }
          }
</style>
