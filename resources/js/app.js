import '../css/app.css'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import baseMixin from './base'

// Импорт компонента vue-multiselect
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import '@vueup/vue-quill/dist/vue-quill.bubble.css';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  title: title => title ? `${title} - Ping CRM` : 'Ping CRM',
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mixin(baseMixin)

      // Регистрируем vue-multiselect
      .component('Multiselect', Multiselect)
      .component('QuillEditor', QuillEditor)

      .mount(el)
  },
})
