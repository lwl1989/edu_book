
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

window.Vue = VUE;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//Vue.use(Axios);
Vue.use(ElementUi);

// Vue.use(Button);
// Vue.use(Input);
// Vue.use(Select);
// Vue.use(Dialog);
// Vue.use(Pagination);
// Vue.use(Table);
// Vue.use(TableColumn);
// Vue.use(Dropdown);
// Vue.use(DropdownItem);
// Vue.use(DropdownMenu);
// Vue.use(Menu);
// Vue.use(MenuItemGroup);
// Vue.use(MenuItem);
// Vue.use(Submenu);
// Vue.use(BreadcrumbItem);
// Vue.use(Breadcrumb);
// Vue.use(Row);
// Vue.use(Col);

window.axios = Axios;
window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest'
};
//Vue.prototype.$http = Axios;
Vue.prototype.$ajax = Axios;
const app = new Vue({
    router,
    render: h => h(App)
}).$mount('#app');