import Vue from 'vue'
import Router from 'vue-router'
import Admin from '../components/admin/AdminIndexComponent.vue'
import Home from '../components/CommonComponent.vue'

Vue.use(Router);

//Vue.prototype.$echarts = echarts

const routes = [
    {
        path: '/home',
        component: Home,
        hidden: true
    },
    {
        path: '/',
        component: Home,
        name: '賬戶管理',
        iconCls: 'el-icon-d-caret',
        hidden: false,
        children: [
            { path: '/admin/index', component: Admin, name: '縣府員工' },
            { path: '/admin/index15', component: Admin, name: '臺東縣民' },
            { path: '/admin/index17', component: Admin, name: '手機轉碼' },
            { path: '/admin/index16', component: Admin, name: '更換密碼' }
        ]
    },
    {
        path: '/',
        component: Home,
        name: '資料維護',
        iconCls: 'el-icon-d-caret',
        hidden: false,
        children: [
            { path: '/admin/index2', component: Admin, name: '縣府單位' },
            { path: '/admin/index3', component: Admin, name: '縣民群組' }
        ]
    },
    {
        path: '/',
        component: Home,
        name: '事件管理',
        iconCls: 'el-icon-d-caret',
        hidden: false,
        children: [
            { path: '/admin/index4', component: Admin, name: '推播設定' },
            { path: '/admin/index5', component: Admin, name: '推播訊息' },
            { path: '/admin/index8', component: Admin, name: '自動推播訊息' },
            { path: '/admin/index9', component: Admin, name: '建立問卷' },
            { path: '/admin/index19', component: Admin, name: '建立活動' },
            { path: '/admin/index10', component: Admin, name: '產生報表' }
        ]
    },{
        path: '/',
        component: Home,
        name: '臺東金幣',
        iconCls: 'el-icon-d-caret',
        hidden: false,
        children: [
            { path: '/admin/index6', component: Admin, name: '金幣賬戶' },
            { path: '/admin/index7', component: Admin, name: '金幣發放' },
            { path: '/admin/index11', component: Admin, name: '商品兌換' },
            { path: '/admin/index12', component: Admin, name: 'oBike優惠券' },
            { path: '/admin/index13', component: Admin, name: '請款期別' }
        ]
    },
];

export default new Router({ routes });