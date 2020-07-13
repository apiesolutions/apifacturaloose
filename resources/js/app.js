
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue'
import ElementUI from 'element-ui'
import Axios from 'axios'

import lang from 'element-ui/lib/locale/lang/es'
import locale from 'element-ui/lib/locale'
locale.use(lang)

//Vue.use(ElementUI)
Vue.use(ElementUI, {size: 'small'})
Vue.prototype.$eventHub = new Vue()
Vue.prototype.$http = Axios

Vue.component('companies-form', require('./views/companies/form.vue'));
Vue.component('users-index', require('./views/users/index.vue'));
Vue.component('options-form', require('./views/options/form.vue'));
Vue.component('certificates-index', require('./views/certificates/index.vue'));
Vue.component('certificates-form', require('./views/certificates/form.vue'));
Vue.component('documents-index', require('./views/documents/index.vue'));
Vue.component('retentions-index', require('./views/retentions/index.vue'));
Vue.component('perceptions-index', require('./views/perceptions/index.vue'));
Vue.component('dispatches-index', require('./views/dispatches/index.vue'));
Vue.component('search-index', require('./views/search/index.vue'));
Vue.component('summaries-index', require('./views/summaries/index.vue'));

const app = new Vue({
    el: '#main-wrapper'
});


