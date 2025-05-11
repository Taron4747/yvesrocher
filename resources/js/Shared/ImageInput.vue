<template>
    <div>
      <label v-if="label" class="form-label">{{ label }}:</label>
      <div class="form-input p-0" :class="{ error: errors.length }">
        <input ref="file" type="file" :accept="accept" class="hidden" @change="change" />
  
        <div v-if="!modelValue" class="p-2">
          <button type="button" class="px-4 py-1 text-white text-xs font-medium bg-gray-500 hover:bg-gray-700 rounded-sm" @click="browse">Добавить</button>
        </div>
  
        <div v-else class="flex flex-col gap-2 p-2">
          <div class="flex items-center justify-between">
            <div class="flex-1 pr-1">
              {{ modelValue.name }}
              <span class="text-gray-500 text-xs">({{ filesize(modelValue.size) }})</span>
            </div>
            <button type="button" class="px-4 py-1 text-white text-xs font-medium bg-gray-500 hover:bg-gray-700 rounded-sm" @click="remove">Удалить</button>
          </div>
  
          <img v-if="previewUrl" :src="previewUrl" alt="Preview" class="max-h-48 object-contain border rounded" />
        </div>
      </div>
  
      <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>
    </div>
  </template>
<script>
  
  export default {
    props: {
      modelValue: File,
      label: String,
      accept: String,
      errors: {
        type: Array,
        default: () => [],
      },
    },
    emits: ['update:modelValue'],
    data() {
      return {
        previewUrl: null,
      }
    },
    watch: {
      modelValue(value) {
        if (!value) {
          this.previewUrl && URL.revokeObjectURL(this.previewUrl)
          this.previewUrl = null
          this.$refs.file.value = ''
        } else if (value && value.type.startsWith('image/')) {
          this.previewUrl && URL.revokeObjectURL(this.previewUrl)
          this.previewUrl = URL.createObjectURL(value)
        }
      },
    },
    methods: {
      filesize(size) {
        var i = Math.floor(Math.log(size) / Math.log(1024))
        return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i]
      },
      browse() {
        this.$refs.file.click()
      },
      change(e) {
        this.$emit('update:modelValue', e.target.files[0])
      },
      remove() {
        this.$emit('update:modelValue', null)
      },
    },
  }
</script>
  