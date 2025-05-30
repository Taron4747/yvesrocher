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
          <div class="title_big">Код Продукта</div>
          <text-input v-model="form.product_code" :error="form.errors.product_code" class="pb-8 pr-6 w-full lg:w-1/3" label="Код" />
          <div class="title_big">Категоризация</div>
          <SelectInput v-model="form.category_id" :error="form.errors.category_id" class="pb-8 pr-6 w-full lg:w-1/3" label="Семейства продуктов">
            <option disabled value="">Выберите категорию</option>
            <option v-for="opt in categoriesData.filter(cat => !cat.parent_id)" :key="opt.id" :value="opt.id">{{ opt.name_ru }}</option>
          </SelectInput>    
          <SelectInput v-model="form.sub_category_id" :error="form.errors.sub_category_id" class="pb-8 pr-6 w-full lg:w-1/3" label="Категория" v-if="form.category_id">
            <option disabled value="">Выберите подкатегорию</option>
            <option v-for="opt in categoriesData.filter(cat => cat.parent_id === form.category_id)" :key="opt.id" :value="opt.id">{{ opt.name_ru }}</option>
          </SelectInput>  
          <SelectInput v-model="form.sub_sub_category_id" :error="form.errors.sub_sub_category_id"  class="pb-8 pr-6 w-full lg:w-1/3" label="Подкатегория" v-if="form.sub_category_id">
            <option disabled value="">Выберите подподкатегорию</option>
            <option v-for="opt in categoriesData.filter(cat => cat.parent_id === form.sub_category_id)" :key="opt.id" :value="opt.id">{{ opt.name_ru }}</option>
          </SelectInput>  
          <div class="title_big">Название</div>
          <text-input v-model="form.name_arm" :error="form.errors.name_arm" class="pb-8 pr-6 w-full lg:w-1/3" label="Հայերեն" />
          <text-input v-model="form.name_ru" :error="form.errors.name_ru" class="pb-8 pr-6 w-full lg:w-1/3" label="Русский" />
          <text-input v-model="form.name_en" :error="form.errors.name_en" class="pb-8 pr-6 w-full lg:w-1/3" label="English" />
          <div class="title_big">Описание</div>
          <div  class="pb-8 pr-6 w-full lg:w-1/3 editor" >
            <label class="form-label">Հայերեն</label>
                <QuillEditor label=""   contentType="html"
            v-model:content="form.description_arm" theme="snow" toolbar="full"   style="height: 300px"
 />
            <label class="form-label">Русский</label>

                      <QuillEditor   contentType="html"
            v-model:content="form.description_ru" theme="snow" toolbar="full"   style="height: 300px"
/>
            <label class="form-label">English</label>

                      <QuillEditor   contentType="html"
            v-model:content="form.description_en" theme="snow" toolbar="full"   style="height: 300px"
/>
          </div>
          <!-- <TextAreaInput v-model="form.description_arm" :error="form.errors.description_arm" class="pb-8 pr-6 w-full lg:w-1/3" label="Հայերեն"/>
          <TextAreaInput v-model="form.description_ru" :error="form.errors.description_ru" class="pb-8 pr-6 w-full lg:w-1/3" label="Русский"/>
          <TextAreaInput v-model="form.description_en" :error="form.errors.description_en" class="pb-8 pr-6 w-full lg:w-1/3" label="English"/> -->
          
          <div class="title_big">Состав</div>
          <text-input v-model="form.composition_arm" :error="form.errors.composition_arm" class="pb-8 pr-6 w-full lg:w-1/3" label="Հայերեն" />
          <text-input v-model="form.composition_ru" :error="form.errors.composition_ru" class="pb-8 pr-6 w-full lg:w-1/3" label="Русский" />
          <text-input v-model="form.composition_en" :error="form.errors.composition_en" class="pb-8 pr-6 w-full lg:w-1/3" label="English" />
          <div class="title_big">Медиа</div>
          <!-- <image-input v-model="form.image" :error="form.errors.image" class="pb-8 pr-6 w-full lg:w-1/3" type="file" accept="image/*" label="Фото" /> -->
          <image-input v-model="form.image" :error="form.errors.image" label="Главное Фото (только 1 фото)" class="pb-8 pr-6 w-full lg:w-1/3" accept="image/*" :max-files="1"/>
          <image-input v-model="form.images" :error="form.errors.images" label="Осталные фото (максимум 9 фото)" class="pb-8 pr-6 w-full lg:w-1/3" accept="image/*" :max-files="9"/>
          <!-- <file-input v-model="form.image" :error="form.errors.image" class="pb-8 pr-6 w-full lg:w-1/3" type="file" accept="image/*" label="Фото" /> -->
          <text-input v-model="form.price" placeholder="5700" :error="form.errors.price" class="pb-8 pr-6 w-full lg:w-1/3" label="Цена" />
          <div style="display: flex;" class="lg:w-1/3">
            <text-input v-model="form.size" :error="form.errors.size" placeholder="50" class="pb-8 pr-6  lg:w-2/3" label="Размер" />
            <SelectInput v-model="form.size_type_id" class="pb-8 pr-6  lg:w-1/3" label="Выберите тип">
              <option disabled value="">Выберите тип </option>
              <option v-for="opt in dataTypes" :key="opt.id" :value="opt.id">{{ opt.name_ru }}</option> 
            </SelectInput>   
          </div>
          
          <text-input v-model="form.discount" placeholder="10" :error="form.errors.discount" class="pb-8 pr-6 w-full lg:w-1/3" label="Скидка (%)" />
          <text-input v-model="form.count" placeholder="4" :error="form.errors.count" class="pb-8 pr-6 w-full lg:w-1/3" label="Колличество" />     
          <label class="custom_checkbox text_color">Новинка
              <input v-model="form.is_new" type="checkbox"  :true-value="1" :false-value="0">
              <span class="checkmark"></span>
          </label>
          <label class="custom_checkbox text_color">Бестселлеры
              <input v-model="form.is_bestseller" type="checkbox"  :true-value="1" :false-value="0">
              <span class="checkmark"></span>
          </label>
          <label class="custom_checkbox text_color">Экоформат
              <input v-model="form.is_ecco" type="checkbox"  :true-value="1" :false-value="0">
              <span class="checkmark"></span>
          </label>
          
        <div class="title_big">Фильтры</div>
       <span v-if="errorMessage" class="form-error">{{ errorMessage }}</span>
        <div class="w-full mt-6">
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
import ImageInput from '@/Shared/ImageInput.vue'
import axios from "axios";
// import 'vue-multiselect/dist/vue-multiselect.min.css';

export default {
  components: {
    Head,
    Link,
    FileInput,
    ImageInput,
    LoadingButton,
    SelectInput,
    TextInput,
    TextAreaInput,
    Multiselect,
  },
  layout: Layout,
  props: {
    categories: Array,
    types: Array,
    filters: Array,
    butonFilters: Array,
  },
  remember: 'form',
  watch: {
    'form.category_id'(newVal) {
      axios.get("/admin/category/filters/" + newVal).then((response) => {
        this.filtersData = response.data.categoryfilters;
        this.setFilters()
      });
    }
  },
  data() {
    return {
      errorMessage:'',
      categoriesData:this.categories,
      dataTypes:this.types,
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
        product_code: '',
        is_new: '',
        is_bestseller: '',
        image: null,
        images: [],
        filters: [], 
        button_filters: [], 
        category_id:'',
        sub_category_id:'',
        sub_sub_category_id:'',
        size_type_id:'',
        is_ecco:'',
      }),
    }
  },
  mounted(){
    this.setFilters()
  },
  methods: {
    setFilters(){
      this.filtersData.forEach((filters) => {
      filters.type = false;
      filters.sub_filters.forEach((value) => {
        value.type = false;
      });
    });
    },
    store() {
      this.errorMessage = "";
      const selectedFilters = this.filtersData.filter(filter => filter.type === true);

      if (selectedFilters.length === 0) {
        this.errorMessage = 'Выберите хотя бы один фильтр';
        return false;
      } else {
          // Проверка 2: у каждого выбранного фильтра должен быть хотя бы один активный подфильтр
          // const hasInvalidSubFilters = selectedFilters.some(filter => {
          //     return !filter.sub_filters.some(sub => sub.type === true);
          // });
          const hasInvalidSubFilters = selectedFilters.some(filter => {
                const subs = Array.isArray(filter.sub_filters) ? filter.sub_filters : [];
                return !subs.some(sub => sub.type === true);
            });
          if (hasInvalidSubFilters) {
              this.errorMessage = 'Значение фильтра обязательно';
              return false;
          } 
      }
      this.form.button_filters = this.selected;
      this.form.filters = this.filtersData;
      this.form.post('/admin/product')
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
          .editor{
            label{
              margin-top: 15px;
            }
            label:first:child{
              margin-top: 0;
            }
          }
</style>
