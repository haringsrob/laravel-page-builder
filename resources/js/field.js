Nova.booting((Vue, router, store) => {
  Vue.component('IndexLaravelPageBuilderField', require('./components/IndexField'))
  Vue.component('DetailLaravelPageBuilderField', require('./components/DetailField'))
  Vue.component('FormLaravelPageBuilderField', require('./components/FormField'))
})
