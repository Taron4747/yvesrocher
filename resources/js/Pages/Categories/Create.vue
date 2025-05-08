<template>
  <div>
    <Head title="Create Contact" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/categories">Категории</Link>
      <span class="text-indigo-400 font-medium">/</span> Создать
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name_arm" :error="form.errors.name_arm" class="pb-8 pr-6 w-full lg:w-1/2" label="Название Арм" />
          <text-input v-model="form.name_ru" :error="form.errors.name_ru" class="pb-8 pr-6 w-full lg:w-1/2" label="Название Ру" />
          <text-input v-model="form.name_en" :error="form.errors.name_en" class="pb-8 pr-6 w-full lg:w-1/2" label="Название Анг" />
          <file-input v-model="form.image" :error="form.errors.image" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="Фото" />
        </div>
        <div class="w-full px-8 mt-6">
          <label class="block font-bold mb-4">Фильтры по значениям</label>
          <div v-for="filter in filtersData" :key="filter.id" class="mb-4">
            <label class="custom_checkbox">{{filter.name_ru}}
                <input v-model="filter.type" type="checkbox" checked="checked" @change="onFilterChange(filter)">
                <span class="checkmark"></span>
            </label>
            <div class="flex flex-wrap">
              <div v-for="value in filter.sub_filters" :key="value.id" class="mr-4 mb-2">
                <label class="inline-flex items-center">
                  <label class="custom_checkbox">{{value.name_ru}}
                      <input v-model="value.type" type="checkbox" checked="checked">
                      <span class="checkmark"></span>
                  </label>
                </label>
              </div>
            </div>
          </div>
        </div>
        <!-- Мультиселект: buttonFilters -->
        <!-- <div class="w-full px-8 mt-6">
          <label class="block font-bold mb-2">Кнопочные фильтры (мультиселект)</label>
          <select-input  v-model="form.button_filters" :error="form.errors.button_filters" class="pb-8 pr-6 w-full lg:w-1/2" label="Кнопочные фильтры (мультиселект)">
            <option v-for="filter in butonFilters" :key="filter.id" :value="filter.id">
              {{ filter.name_ru }}
            </option>
          </select-input>
        </div> -->
        <multiselect 
          v-model="selected" 
          :options="butonFiltersData" 
          :multiple="true" 
          placeholder="Выберите фильтры" 
          label="name_arm"  
          track-by="id"  
        />
        <!-- <MultiSelect  v-model="form.button_filters" display="chip" :options="butonFiltersData" optionLabel="name_arm"
        :maxSelectedLabels="3" class="w-full md:w-20rem" /> -->
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Создать категорию</loading-button>
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

// import 'vue-multiselect/dist/vue-multiselect.min.css';

export default {
  components: {
    Head,
    Link,
    FileInput,
    LoadingButton,
    SelectInput,
    TextInput,
    Multiselect,
  },
  layout: Layout,
  props: {
    filters: Array,
    butonFilters: Array,
  },
  remember: 'form',
  data() {
    return {
      dsadsadsa:"sfafsa",
      filtersData:this.filters,
      butonFiltersData:this.butonFilters,
      selected: [],
      options: ['Option 1', 'Option 2', 'Option 3', 'Option 4'],
      form: this.$inertia.form({
        name_arm: '',
        name_ru: '',
        name_en: '',
        image: null,
        filters: [], // selected filterable IDs (checkbox)
        button_filters: [], // selected button filter IDs (multi-select)
      }),
    }
  },
  mounted(){
    console.log(this.butonFiltersData);
    this.filtersData.forEach((filters) => {
      filters.type = false;
      filters.sub_filters.forEach((value) => {
        value.type = false;
      });
    });
  },
  methods: {
    store() {
      this.form.filters = this.filtersData
      this.form.button_filters = this.selected      
      this.form.post('/categories')
    },
    onFilterChange(filter){
      if(filter.type == true){
        this.filtersData.forEach((filters) => {
        if(filters.id == filter.id){
          filters.sub_filters.forEach((value) => {
          value.type = true;
        });
        }
      });
      }
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
