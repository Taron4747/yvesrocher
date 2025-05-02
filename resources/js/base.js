// resources/js/base.js

export default {
    methods: {
      /**
       * Translate the given key.
       */
      __(key, replace = {}) {
        let translation = this.$page.language?.[key] || key
  
        Object.keys(replace).forEach(function (param) {
          translation = translation.replace(`:${param}`, replace[param])
        })
  
        return translation
      },
    },
  }
  