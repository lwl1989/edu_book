import Vue from 'vue'
import Router from 'vue-router'


import Admin from '../components/admin/AdminIndexComponent'
import Company from '../components/admin/Company'
import Home from '../components/CommonComponent'
import ChangePass from '../components/admin/ChangePassComponent'
import ChangeMobile from '../components/admin/ChangePassComponent'
import UserComponent from '../components/admin/UserComponent'

import Deparment from '../components/deparment/DeparmentIndex'
import DeparmentGroup from '../components/deparment/DeparmentGroup'

import GoldAccount from '../components/gold/GoldAccount'
import GoldDate from '../components/gold/GoldDate'
import GoldSend from '../components/gold/GoldSend'

import Goods from  '../components/goods/GoodsList'

import MessageList from '../components/message/MessageList'
import MessageAcitvity from '../components/message/MessageActivity'
import MessageAuto from '../components/message/MessageAuto'
import MessageExcel from '../components/message/MessageExcel'
import MessageSetting from '../components/message/MessageSetting'
import MessageQuestion from '../components/message/MessageQuestion'

import OBick from  '../components/oBick/oBick'
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
        name: '賬戶管理',
        iconCls: 'el-icon-d-caret',
        hidden: false,
        children: [
            { path: 'admins', component: Admin, name: '縣府員工' ,hidden: true},
            { path: 'company', component: Company, name: '營銷團隊' ,hidden: true},
            { path: 'users', component: UserComponent, name: '臺東縣民' ,hidden: true},
            { path: 'changeMobile', component: ChangeMobile, name: '手機轉碼' ,hidden: true},
            { path: 'changePass', component: ChangePass, name: '更換密碼' ,hidden: false},
        ]
    },
    {
        path: '/',
        component: Home,
        name: '資料維護',
        iconCls: 'el-icon-d-caret',
        hidden: true,
        children: [
            { path: 'department', component: Deparment, name: '縣府單位',hidden: true },
            { path: 'department/group', component: DeparmentGroup, name: '縣民群組' ,hidden: true}
        ]
    },
    {
        path: '/',
        component: Home,
        name: '事件管理',
        iconCls: 'el-icon-d-caret',
        hidden: true,
        children: [
            { path: 'message/setting', component: MessageSetting, name: '推播設定' ,hidden: true},
            { path: 'message/list', component: MessageList, name: '推播訊息' ,hidden: true},
            { path: 'message/auto', component: MessageAuto, name: '自動推播訊息' ,hidden: true},
            { path: 'message/question', component: MessageQuestion, name: '建立問卷' ,hidden: true},
            { path: 'message/activity', component: MessageAcitvity, name: '建立活動',hidden: true },
            { path: 'message/excel', component: MessageExcel, name: '產生報表' ,hidden: true}
        ]
    },{
        path: '/',
        component: Home,
        name: '臺東金幣',
        iconCls: 'el-icon-d-caret',
        hidden: true,
        children: [
            { path: 'gold/account', component: GoldAccount, name: '金幣賬戶' ,hidden: true},
            { path: 'gold/send', component: GoldSend, name: '金幣發放' ,hidden: true},
            { path: 'goods/list', component: Goods, name: '商品兌換' ,hidden: true},
            { path: 'oBick/voucher', component: OBick, name: 'oBike優惠券' ,hidden: true},
            { path: 'gold/date', component: GoldDate, name: '請款期別' ,hidden: true}
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
