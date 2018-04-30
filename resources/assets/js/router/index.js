import Vue from 'vue'
import Router from 'vue-router'


import Admin from '../components/admin/AdminIndexComponent'
import Home from '../components/CommonComponent'
import HomeIndex from '../components/admin/HomeIndexComponent'
import ChangePass from  '../components/admin/ChangePassComponent'

import Book from '../components/book/BookComponent'
import BookDetail from '../components/book/BookDetailComponent'
import BookOrder from '../components/book/BookOrderComponent'
import BookOrderDetail from '../components/book/BookOrderDetailComponent'
import BookPlan from '../components/book/BookPlanComponent'
import BookPlanDetail from '../components/book/BookPlanDetailComponent'

import Teacher from '../components/teacher/TeacherComponent'
import TeacherReceive from '../components/teacher/TeacherReceiveComponent'
import TeacherDetail from '../components/teacher/TeacherDetailComponent'

import Student from '../components/student/StudentComponent'
import StudentReceive from '../components/student/StudentReceiveComponent'
import StudentDetail from '../components/student/StudentDetailComponent'

import Classes from '../components/classes/ClassesComponent'
import ClassDetail from  '../components/classes/ClassesDetailComponent'


import Cookies from '../tools/vue-cookies';

Vue.use(Router);


let routes = [
    {
        path: '/',
        component: Home,
        redirect:'/home',
        hidden: true,
        children:[
            { path: 'admins', component: HomeIndex, name: '首页' ,hidden: true},
        ]
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

let type = Cookies.getCookie('type');
type = decodeURIComponent(type);

if(type == null || type === '') {
    window.location.href = '/login';
}

let index =  {
        path: '/',
        component: Home,
        redirect:'/home',
        name:'导航',
        hidden: true,
        children:[
            { path: 'home', component: HomeIndex, name: '欢迎' ,hidden: true},
        ]
    };
if (type === 'student') {
    routes = [
        index,
        {
            path: '/',
            component: Home,
            name: '账户管理',
            iconCls: 'el-icon-menu',
            hidden: false,
            children: [
                { path: '/changePass', component: ChangePass, name: '更換密碼'},
            ]
        },
        {
            path: '/',
            component: Home,
            name: '功能',
            iconCls: 'el-icon-d-caret',
            hidden: false,
            children: [
                { path: '/classes/list', component:Classes, name: '班级列表'},
                { path: '/book/list', component:Book, name: '书籍列表'},
                { path: '/student/receive', component: StudentReceive, name: '我的领书记录' }
            ]
        }
    ];
}else if(type === 'teacher') {
    routes = [
        index,
        {
            path: '/',
            component: Home,
            name: '账户管理',
            iconCls: 'el-icon-menu',
            hidden: false,
            children: [
                { path: '/changePass', component: ChangePass, name: '更換密碼'},
            ]
        },
        {
            path: '/',
            component: Home,
            name: '功能',
            iconCls: 'el-icon-document',
            hidden: false,
            children: [
                { path: '/classes/list', component:Classes, name: '班级列表'},
                { path: '/book/list', component: Book, name: '书籍列表'},
                { path: '/teacher/receive', component: TeacherReceive, name: '我的领书记录' }
            ]
        }
    ];
}else{
    routes = [
        index,
        {
            path: '/',
            component: Home,
            name: '账户管理',
            iconCls: 'el-icon-menu',
            hidden: false,
            children: [
                { path: '/admins', component: Admin, name: '教务账户' ,hidden: false},
                { path: '/changePass', component: ChangePass, name: '更換密碼' ,hidden: false},
            ]
        },
        {
            path:'/',
            component:Home,
            name:'班级管理',
            iconCls: 'el-icon-date',
            children:[
                { path: '/classes', component: Classes, name: '班级列表' ,hidden: false},
                { path: '/classes/detail/:id', component: ClassDetail, name: '班级详情',hidden:true},
            ]
        },
        {
            path: '/',
            component: Home,
            name: '书本管理',
            iconCls: 'el-icon-document',
            hidden: false,
            children: [
                { path: '/book', component: Book, name: '书籍管理'},
                { path: '/book/order', component: BookOrder, name: '书籍订单'},
                { path: '/book/detail/:id', component: BookDetail, name: '书籍详情',hidden:true},
                { path: '/book/order/detail/:id', component: BookOrderDetail, name: '订单详情',hidden:true},
                { path: '/book/plan', component: BookPlan, name: '学年计划'},
                { path: '/book/plan/detail/:id', component: BookPlanDetail, name: '计划详情',hidden:true}
            ]
        },
        {
            path: '/',
            component: Home,
            name: '学生用户管理',
            iconCls: 'el-icon-d-caret',
            hidden: false,
            children: [
                { path: '/student/index', component: Student, name: '学生用户' },
              //  { path: '/student/receive', component: StudentReceive, name: '学生领书记录' },
                { path: '/student/detail/:id', component: StudentDetail, name: '学生详情' ,hidden:true},
            ]
        },{
            path: '/',
            component: Home,
            name: '教师用户管理',
            iconCls: 'el-icon-service',
            hidden: false,
            children: [
                { path: '/teacher/index', component: Teacher, name: '教师用户' },
                { path: '/teacher/receive', component: TeacherReceive, name: '教师领书记录' },
                { path: '/teacher/detail/:id', component: TeacherDetail, name: '教师详情' ,hidden:true},
            ]
        },
    ];
}

let myRouter = new Router({routes});

export default myRouter;
