<template>
  <div>
    <Head title="Create Contact" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/filter">Фильтр</Link>
      <span class="text-indigo-400 font-medium">/</span> Создать
    </h1>
    <div class="mb-8 bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
      
          <text-input v-model="form.name_arm" :error="form.errors.name_arm" class="pb-8 pr-6 w-full lg:w-1/3" label="Название Арм" />
          <text-input v-model="form.name_ru" :error="form.errors.name_ru" class="pb-8 pr-6 w-full lg:w-1/3" label="Название Ру" />
          <text-input v-model="form.name_en" :error="form.errors.name_en" class="pb-8 pr-6 w-full lg:w-1/3" label="Название Анг" />
          <label class="custom_checkbox">One
              <input v-model="form.filterable" type="checkbox" checked="checked">
              <span class="checkmark"></span>
          </label>
          <div class="flex flex-wrap w-full -mb-8 -mr-6 p-8" v-if="form.filterable">
            <div v-for="key in countOfCustom" class="flex flex-wrap w-full -mb-8 -mr-6 p-8 relative">
              <text-input v-model="customData[key-1].name_arm" class="pb-8 pr-6 w-full lg:w-1/3" label="Значение Арм" />
              <text-input v-model="customData[key-1].name_ru" class="pb-8 pr-6 w-full lg:w-1/3" label="Значение Ру" />
              <text-input v-model="customData[key-1].name_en" class="pb-8 pr-6 w-full lg:w-1/3" label="Значение Анг" />
              <div v-if="countOfCustom != key" class="add_custom" @click="removeCustom(key)">
                <img src="/images/trash.svg">
              </div>   
            </div>
            <div class="add_button" @click="addValue()">
              <img src="/images/add_photo_blue.svg">
              <span>Добавить значение</span>
            </div>
          </div>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">
  Сохранить фильтр
</loading-button>
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

export default {
  components: {
    Head,
    Link,
    FileInput,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    filter: Object,

  },
  remember: 'form',
  data() {
  return {
    form: this.$inertia.form({
      id: this.filter?.id || null,
      name_arm: this.filter?.name_arm || '',
      name_ru: this.filter?.name_ru || '',
      name_en: this.filter?.name_en || '',
      filterable: this.filter?.filterable || false,
      customData: [],
    }),
    customData: this.filter?.values?.map(value => ({
      id: value.id,
      name_arm: value.name_arm,
      name_ru: value.name_ru,
      name_en: value.name_en,
    })) || [],
    countOfCustom: (this.filter?.values?.length || 1),
  }
},
  methods: {
    update() {
      this.form.customData = this.customData
      this.form.put(`/filter/${this.form.id}`) // метод PUT для обновления
    },
    addValue(){
      var key = this.customData.length-1;
        if(this.customData[key].name_arm && this.customData[key].name_ru && this.customData[key].name_en
        && this.form.name_arm && this.form.name_ru && this.form.name_en){
          this.pushData(key)
          this.countOfCustom ++
        }     
        console.log(this.customData)
    },
    pushData(){
      let newObject = {
        name_arm: '',
        name_ru:'',
        name_en: '',
      }
      this.customData.push(newObject); 
    },
    removeCustom(key){
        this.customData.splice(key-1, 1);
        this.countOfCustom --;
        console.log(this.customData)
    },
  },
}
</script>
<style  lang="scss">
.add_button{
  display: flex;
  align-items: center;
  margin: 20px auto 0 auto;
  width: fit-content;
  color: #3F4EBD;
  font-size: 14px;
  line-height: 130%;
  padding: 14px 73px;
  cursor: pointer;
  img{
    height: 20px;
    margin-right: 6px;
  }
}
.add_custom{
  cursor: pointer;
  position: absolute;
  right: 20px;
  bottom: 73px;
}
</style>
