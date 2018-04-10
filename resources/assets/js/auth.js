
import VUE from 'vue'
import ElementUi from "element-ui"
import Axios from 'axios'
import App from  './components/auth/LoginComponent.vue'

window.Vue = VUE;

Vue.use(ElementUi);
window.axios = Axios;
window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest'
};

const app = new Vue({
    render: h => h(App)
}).$mount('#app');


