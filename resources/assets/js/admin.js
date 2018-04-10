
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//import { Row,Col,Breadcrumb,BreadcrumbItem,Submenu,Menu,MenuItemGroup,MenuItem,DropdownMenu,Dropdown,DropdownItem, Button , Input, Select, Dialog, Pagination, Table, TableColumn} from 'element-ui';
import ElementUi from "element-ui"
import Axios from 'axios'
import router from './router/index'
import App from  './components/RouteComponent.vue'
import VUE from 'vue'
import 'element-ui/lib/theme-chalk/index.css'
window.Vue = VUE;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(ElementUi);


window.axios = Axios;
window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest'
};
Vue.prototype.$ajax = Axios;
const app = new Vue({
    router,
    render: h => h(App)
}).$mount('#app');