<template>
  <div>
    <Head title="Create Organization" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/banners">Баннеры  </Link>
      <span class="text-indigo-400 font-medium">/</span> Создать
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class=" -mb-8 -mr-6 p-8">
  
        

          <div class="title_big">Название</div>
          <text-input v-model="form.title_arm" :error="form.errors.title_arm" class="pb-8 pr-6 w-full " label="Հայերեն" />
          <text-input v-model="form.title_ru" :error="form.errors.title_ru" class="pb-8 pr-6 w-full " label="Русский" />
          <text-input v-model="form.title_en" :error="form.errors.title_en" class="pb-8 pr-6 w-full " label="English" />
          <div class="title_big">Описание</div>
          <TextAreaInput v-model="form.text_arm" :error="form.errors.text_arm" class="pb-8 pr-6 w-full " label="Հայերեն"/>
          <TextAreaInput v-model="form.text_ru" :error="form.errors.text_ru" class="pb-8 pr-6 w-full " label="Русский"/>
          <TextAreaInput v-model="form.text_en" :error="form.errors.text_en" class="pb-8 pr-6 w-full " label="English"/>
          <div class="title_big">Значение</div>
          <!-- {{ form.errors.text_arm }} -->
      <label class="form-label">Հայերեն</label>
                <QuillEditor label=""   contentType="html"
      v-model:content="form.proposition_arm" theme="snow" toolbar="full"/>
      <label class="form-label">Русский</label>

                <QuillEditor   contentType="html"
      v-model:content="form.proposition_ru" theme="snow" toolbar="full"/>
      <label class="form-label">English</label>

                <QuillEditor   contentType="html"
      v-model:content="form.proposition_en" theme="snow" toolbar="full"/>

          <!-- <text-input v-model="form.proposition_arm" :error="form.errors.proposition_arm" class="pb-8 pr-6 w-full " label="Հայերեն" />
          <text-input v-model="form.proposition_ru" :error="form.errors.proposition_ru" class="pb-8 pr-6 w-full " label="Русский" />
          <text-input v-model="form.proposition_en" :error="form.errors.proposition_en" class="pb-8 pr-6 w-full " label="English" /> -->

          <div class="title_big">Ссылка на баннер </div>
        
          <text-input v-model="form.link" :error="form.errors.link" class="pb-8 pr-6 w-full " label="Ссылка" />
          <div class="title_big">Медиа</div>

          <image-input  :max-files="1" v-model="form.image_big" :error="form.errors.image_big" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото Больше" />
          <image-input  :max-files="1" v-model="form.image_medium" :error="form.errors.image_medium" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото Среднее" />
          <image-input  :max-files="1" v-model="form.image_small" :error="form.errors.image_small" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото Маленькое" />
          <image-input  :max-files="1" v-model="form.bottom_image" :error="form.errors.bottom_image" class="pb-8 pr-6 w-full" type="file" accept="image/*" label="Фото" />

          <label class="custom_checkbox text_color">
  Активен
  <input v-model="form.is_active" type="checkbox" :true-value="1" :false-value="0" />
  <span class="checkmark"></span>
</label>

<!-- Показываем ошибку -->
<span v-if="form.errors.is_active" class="text-red-600 text-sm">
  {{ form.errors.is_active }}
</span>

          <text-input v-model="form.position" :error="form.errors.position" class="pb-8 pr-6 w-full " label="Позиция баннера" />

        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Создать Баннер</loading-button>
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
import ImageInput from '@/Shared/ImageInput.vue'
import TextAreaInput from '@/Shared/TextareaInput.vue'
// import QuillEditor from 'vue-quill'QuillEditor

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    FileInput,
    ImageInput,
    TextAreaInput,
    // QuillEditor,
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        text_arm: null,
        text_ru: null,
        text_en: null,
        title_arm: null,
        title_ru: null,
        title_en: null,
        proposition_arm: null,
        proposition_ru: null,
        proposition_en: null,
        is_active: 1,
        position: null,
        link: null,
        image_big: null,
        image_medium: null,
        image_small: null,
        bottom_image: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/admin/banners')
    },
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