<template>
  <div>
    <label v-if="label" class="form-label">{{ label }}:</label>
    <div class="form-input p-0" :class="{ error: errors.length }">
      <input
        ref="file"
        type="file"
        :accept="accept"
        multiple
        class="hidden"
        @change="change"
      />

      <div class="p-2">
        <button
          type="button"
          class="px-4 py-1 text-white text-xs font-medium bg-gray-500 hover:bg-gray-700 rounded-sm"
          @click="browse"
          :disabled="files.length >= maxFiles"
        >
          Добавить
        </button>
        <div v-if="files.length >= maxFiles" class="text-xs text-red-500 mt-1">
          Максимум {{ maxFiles }} файлов
        </div>
      </div>

      <div v-if="files.length" class="flex flex-col gap-3 p-2">
        <div
          v-for="(file, index) in files"
          :key="file.name + index"
          class="flex items-center justify-between"
        >
          <div class="flex-1 pr-1">
            {{ file.name }}
            <span class="text-gray-500 text-xs">({{ filesize(file.size) }})</span>
          </div>
          <button
            type="button"
            class="px-4 py-1 text-white text-xs font-medium bg-red-500 hover:bg-red-700 rounded-sm"
            @click="remove(index)"
          >
            Удалить
          </button>
        </div>

        <div class="grid grid-cols-2 gap-2 mt-2">
          <img
            v-for="(url, index) in previewUrls"
            :key="url + index"
            :src="url"
            alt="Preview"
            class="max-h-48 object-contain border rounded"
          />
        </div>
      </div>
    </div>

    <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>
  </div>
</template>

<script>
export default {
  props: {
    modelValue: {
      type: Array,
      default: () => [],
    },
    label: String,
    accept: String,
    errors: {
      type: Array,
      default: () => [],
    },
    maxFiles: {
      type: Number,
      default: 5,
    },
  },
  emits: ['update:modelValue'],
  data() {
    return {
      previewUrls: [],
    }
  },
  computed: {
    files() {
      return this.modelValue || []
    },
  },
  watch: {
    modelValue(newFiles) {
      this.previewUrls.forEach((url) => URL.revokeObjectURL(url))
      this.previewUrls = (newFiles || [])
        .filter((file) => file.type.startsWith('image/'))
        .map((file) => URL.createObjectURL(file))
    },
  },
  methods: {
    filesize(size) {
      var i = Math.floor(Math.log(size) / Math.log(1024))
      return (size / Math.pow(1024, i)).toFixed(2) + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i]
    },
    browse() {
      this.$refs.file.click()
    },
    change(e) {
      const selected = Array.from(e.target.files)

      const remaining = this.maxFiles - this.files.length
      const accepted = selected.slice(0, remaining)

      const updated = [...this.files, ...accepted]
      this.$emit('update:modelValue', updated)
    },
    remove(index) {
      const updated = [...this.files]
      updated.splice(index, 1)
      this.$emit('update:modelValue', updated)
    },
  },
}
</script>