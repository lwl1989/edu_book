
import App from  './components/auth/LoginComponent.vue'
import VUE from 'vue'
import VueResource from 'vue-resource'

window.Vue = VUE;
Vue.use(VueResource);
Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name=csrf-token]').getAttribute('content');

import { Form, FormItem, Checkbox, Input ,Button} from 'element-ui'

Vue.use(Form);
Vue.use(FormItem);
Vue.use(Checkbox);
Vue.use(Input);
Vue.use(Button);

const app = new Vue({
    render: h => h(App)
}).$mount('#app');


