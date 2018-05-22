import Vue from 'vue'
import Router from 'vue-router'


import Admin from '../components/admin/AdminIndexComponent'
import Home from '../components/CommonComponent'
import HomeIndex from '../components/admin/HomeIndexComponent'
import ChangePass from '../components/admin/ChangePassComponent'

import Book from '../components/book/BookComponent'
import BookDetail from '../components/book/BookDetailComponent'
import BookOrder from '../components/book/BookOrderComponent'
import BookOrderDetail from '../components/book/BookOrderDetailComponent'
import BookPlan from '../components/book/BookPlanComponent'
import BookPlanDetail from '../components/book/BookPlanDetailComponent'
import BookReceived from '../components/book/BookReceivedComponent'

import Teacher from '../components/teacher/TeacherComponent'

import Student from '../components/student/StudentComponent'

import Classes from '../components/classes/ClassesComponent'
import ClassDetail from '../components/classes/ClassesDetailComponent'


import Cookies from '../tools/vue-cookies';

Vue.use(Router);


let type = Cookies.getCookie('type');
type = decodeURIComponent(type);

if (type == null || type === '') {
    window.location.href = '/login';
}

let index = {
    path: '/',
    component: Home,
    redirect: '/home',
    name: '导航',
    hidden: true,
    children: [
        {path: 'home', component: HomeIndex, name: '说明', hidden: true},
    ]
};
let routes = [
    index,
    {
        path: '/',
        component: Home,
        name: '账户管理',
        iconCls: 'el-icon-menu',
        hidden: false,
        children: [
            {path: '/changePass', component: ChangePass, name: '更換密碼', hidden: false},
        ]
    },
    {
        path: '/',
        component: Home,
        name: '班级管理',
        iconCls: 'el-icon-date',
        children: [
            {path: '/classes', component: Classes, name: '班级列表', hidden: false},
            {path: '/classes/detail/:id', component: ClassDetail, name: '班级详情', hidden: true},
        ]
    },
    {
        path: '/',
        component: Home,
        name: '书本管理',
        iconCls: 'el-icon-document',
        hidden: false,
        children: [
            {path: '/book', component: Book, name: '教材管理'},
            {path: '/book/order', component: BookOrder, name: '教材入库'},
            {path: '/book/detail/:id', component: BookDetail, name: '教材详情', hidden: true},
            {path: '/book/order/detail/:id', component: BookOrderDetail, name: '入库详情', hidden: true},
            {path: '/book/plan', component: BookPlan, name: '学年计划'},
            {path: '/book/plan/detail/:id', component: BookPlanDetail, name: '计划详情', hidden: true}
        ]
    },

    {
        path: '/',
        component: Home,
        name: '用户管理',
        iconCls:  'el-icon-service',
        hidden: false,
        children: [
            {path: '/student/index', component: Student, name: '学生领书'},
            {path: '/teacher/index', component: Teacher, name: '教师用户领书'},
            {path: '/teacher/receive', component: BookReceived, name: '教师领书记录',hidden:true},
            {path: '/student/receive', component: BookReceived, name: '学生领书记录',hidden:true},
        ]
    }
];


let myRouter = new Router({routes});

export default myRouter;
