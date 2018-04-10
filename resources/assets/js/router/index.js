import Vue from 'vue'
import Router from 'vue-router'


import Admin from '../components/admin/AdminIndexComponent'
import Home from '../components/CommonComponent'
import ChangePass from  '../components/admin/ChangePassComponent'

import Book from '../components/book/BookComponent'
import BookDetail from '../components/book/BookDetailComponent'
import BookOrder from '../components/book/BookOrderComponent'

import Teacher from '../components/teacher/TeacherComponent'
import TeacherReceive from '../components/teacher/TeacherReceiveComponent'
import TeacherDetail from '../components/teacher/TeacherDetailComponent'

import Student from '../components/student/StudentComponent'
import StudentReceive from '../components/student/StudentReceiveComponent'
import StudentDetail from '../components/student/StudentDetailComponent'

import Classes from '../components/classes/ClassesComponent'

import Cookies from '../tools/vue-cookies';

Vue.use(Router);


let routes = [
    {
        path: '/home',
        component: Home,
        hidden: true
    },
    {
        path: '/',
        component: Home,
        name: '账户管理',
        iconCls: 'el-icon-d-caret',
        hidden: false,
        children: [
            { path: 'admins', component: Admin, name: '教务账户' ,hidden: true},
            { path: 'classes', component: Classes, name: '班级管理' ,hidden: true},
            { path: 'changePass', component: ChangePass, name: '更換密碼' ,hidden: false},
        ]
    },
    {
        path: '/',
        component: Home,
        name: '书本管理',
        iconCls: 'el-icon-d-caret',
        hidden: true,
        children: [
            { path: 'book', component: Book, name: '书籍管理'},
            { path: 'book/order', component: BookOrder, name: '书籍订单'},
            { path: 'book/detail/:id', component: BookDetail, name: '书籍详情',hidden:true}
        ]
    },
    {
        path: '/',
        component: Home,
        name: '学生用户管理',
        iconCls: 'el-icon-d-caret',
        hidden: true,
        children: [
            { path: 'student/index', component: Student, name: '学生用户' },
            { path: 'student/receive', component: StudentReceive, name: '学生领书记录' },
            { path: 'student/detail/:id', component: StudentDetail, name: '学生详情' ,hidden:true},
        ]
    },{
        path: '/',
        component: Home,
        name: '教师用户管理',
        iconCls: 'el-icon-d-caret',
        hidden: true,
        children: [
            { path: 'teacher/index', component: Teacher, name: '教师用户' },
            { path: 'teacher/receive', component: TeacherReceive, name: '教师领书记录' },
            { path: 'teacher/detail/:id', component: TeacherDetail, name: '教师详情' ,hidden:true},
        ]
    },
];

let router = Cookies.getCookie('router');
router = decodeURIComponent(router);

if(router == null || router == '') {
    router = sessionStorage.getItem('router');
}

if(router == null || router == '') {
    window.location.href = '/login';
}

try {
    router = JSON.parse(router);
}catch (e) {
    window.location.href = '/login';
}


routes.forEach((r,k)=>{
    if ("children" in r && r.children.length > 0) {
        r.children.forEach((son,key)=>{
            if (router.indexOf(son.name)) {
                r.hidden = false;
                son.hidden = false;
            }

        });
    }
});

let myRouter = new Router({routes});

export default myRouter;
