<template>
  <div>
    <Head title="Create Contact" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/categories">Семейства продуктов</Link>
      <span class="text-indigo-400 font-medium">/</span> Создать
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="-mb-8 -mr-6 p-8">
          <div class="title_big">Название</div>
          <!-- {{ form.errors }} -->
          <text-input v-model="form.name_arm" :error="form.errors.name_arm" class="pb-8 pr-6 w-full lg:w-1/2" label="Հայերեն" />
          <text-input v-model="form.name_ru" :error="form.errors.name_ru" class="pb-8 pr-6 w-full lg:w-1/2" label="Русский" />
          <text-input v-model="form.name_en" :error="form.errors.name_en" class="pb-8 pr-6 w-full lg:w-1/2" label="English" />

          <div class="title_big">Описание</div>
          <TextAreaInput v-model="form.description_arm" :error="form.errors.description_arm" class="pb-8 pr-6 w-full " label="Հայերեն"/>
          <TextAreaInput v-model="form.description_ru" :error="form.errors.description_ru" class="pb-8 pr-6 w-full " label="Русский"/>
          <TextAreaInput v-model="form.description_en" :error="form.errors.description_en" class="pb-8 pr-6 w-full " label="English"/>
          <div class="title_big">Медиа</div>  
          <image-input v-model="form.image" :error="form.errors.image" label="Фото в меню (только 1 фото)" class="pb-8 pr-6 w-full lg:w-1/2" accept="image/*" :max-files="1"/>
          <image-input v-model="form.second_image" :error="form.errors.second_image" label="Фото в каталоге (только 1 фото)" class="pb-8 pr-6 w-full lg:w-1/2" accept="image/*" :max-files="1"/>
          <!-- <file-input v-model="form.image" :error="form.errors.image" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="Фото" /> -->
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
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Создать семейство продукта</loading-button>
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
import FileInput from '@/Shared/FileInput.vue'
import Multiselect from 'vue-multiselect'
import ImageInput from '@/Shared/ImageInput.vue'
import TextAreaInput from '@/Shared/TextareaInput.vue'


export default {
  components: {
    Head,
    Link,
    FileInput,
    LoadingButton,
    SelectInput,
    TextInput,
    Multiselect,
    ImageInput,
    TextAreaInput,
  },
  layout: Layout,
  props: {
    filters: Array,
    butonFilters: Array,
  },
  remember: 'form',
  data() {
    return {
      filtersData:this.filters,
      errorMessage:"",
      // butonFiltersData:this.butonFilters,
      // selected: [],
      form: this.$inertia.form({
        name_arm: '',
        name_ru: '',
        name_en: '',
        description_en: '',
        description_arm: '',
        description_ru: '',
        image: null,
        second_image: null,
        filters: [], // selected filterable IDs (checkbox)
        // button_filters: [], // selected button filter IDs (multi-select)
      }),
    }
  },
  mounted(){
    this.filtersData.forEach((filters) => {
      filters.type = false;
      filters.sub_filters.forEach((value) => {
        value.type = false;
      });
    });
  },
  methods: {
    store() {
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
      // this.form.button_filters = this.selected      
      this.form.post('/admin/categories')
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
