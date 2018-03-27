require('./bootstrap');

window.Vue = require('vue');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vue from 'vue'
import App from './components/Hello.vue'
import router from './router/hello.js'
const app = new Vue({
    el: '#app',
    router,
    render: h=>h(App)
});