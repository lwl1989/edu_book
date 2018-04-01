<template>
<div id="app">
    <el-row class="home-header-every">
        <el-col :span="3">
            <div class="home-header">
                <div class="home-span" @click="goToIndex">
                    <span>TTPush</span>
                </div>
            </div>
        </el-col>
        <el-col :span="16">
                    <el-menu
                            :default-active="$route.path"
                            unique-opened
                            router
                            mode="horizontal"
                            class="el-button--primary-menu"
                            background-color="#1D8CE0"
                            text-color="#ffffff"
                            active-text-color="#ffffff"
                    >
                        <template v-for="(item,index) in $router.options.routes" v-if="!item.hidden">
                            <el-submenu :index="index+''">
                                <template slot="title">
                                    {{index}}. {{item.name}}
                                </template>
                                <el-menu-item v-for="child in item.children" :index="child.path" :key="child.path" v-if="!child.hidden">
                                    {{child.name}}
                                </el-menu-item>
                            </el-submenu>
                        </template>
                    </el-menu>
        </el-col>
        <el-col :span="5">
            <div class="home-header">
                <div class="home-icon">
                    <!-- <i class="el-icon-setting"></i> -->
                    <el-dropdown split-button type="primary" @click="dialogTableVisible = true" @command="handleCommand">
                        查看個人信息
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item command="login">退出登錄</el-dropdown-item>
                            <el-dropdown-item command="password">修改密碼</el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </div>
            </div>
        </el-col>
    </el-row>

    <el-row>
        <el-col :span="24">

        <div class="home-header-breadcrumb">
            <el-breadcrumb separator="/" class="breadcrumb-inner">
                <el-breadcrumb-item :to="{ path: '/home' }">TTPush</el-breadcrumb-item>
                <el-breadcrumb-item v-for="(item, index) in $route.matched" :key="index">
                {{ item.name }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        </el-col>
    </el-row>

    <el-row>
        <el-col :span="24">
            <router-view></router-view>
        </el-col>

    </el-row>
</div>
</template>

<script>
export default {
    data() {
        return {
            gridData: [{
                date: '2016-05-02',
                name: '王小虎',
                phone: '18612243416'
            }],
            dialogTableVisible: false,
            menuHeight: 500,
            routerHeight: 500,
            contentwidth: 500
        }
    },
    created: function() {
        this.menuHeight = window.innerHeight - 60;
        this.routerHeight = window.innerHeight - 120;
        this.contentWidth = window.innerWidth - 270;
    },
    /*
    * 显示个人信息弹出层
    */
    methods: {
        handleClick() {

        },
        goToIndex() {
            this.$router.push({ path: '/echarts' })
        },
        /*
        * 参数 string 跳转的位置
        * 页面跳转方法
        */
        handleCommand(command) {
            if (command == 'login') {
                this.$router.push({ path: '/login' })
            } else {
                this.$router.push({ path: '/system/update/password' })
            }
        }
    }
}
</script>

<style>
.el-button--primary-menu {
    padding:0;
    margin:0;
    height: 60px;
}
.el-button--primary {
    background: #1D8CE0;
}
.el-button--primary-menu i {
    color:#ffffff;
}

.home-header-every {
    width: 100%;
    height: 60px;
    padding: 0;
    margin: 0 auto;
    background: #1D8CE0;
}

.home-header {
    width: 100%;
    height: 50px;
    padding: 5px 0;
    background: #1D8CE0;
}

.home-span {
    float: left;
    cursor: pointer;
}

.home-span span {
    line-height: 50px;
    font-size: 24px;
    color: #FFF;
    margin-left: 20px;
}

.home-icon {
    float: right;
    margin-right: 20px;
    line-height: 50px;
    cursor: pointer;
}

.home-icon i {
    color: #FFF;
}

.home-header-menu {
    background: #eff1f6;
}

.menu-expanded {
    width: 230px;
    float: left;
}

.content-container {
    float: left;
}

.home-header-breadcrumb {
    width: 100%;
    height: 20px;
    padding: 20px;
    background: #FFF;
}

.home-header-router {
    width: 100%;
    padding-left: 20px;
    padding-right: 20px;
    background: #FFF;
}

.el-menu {
    border-radius: 0;
}

.el-breadcrumb__item {
    line-height: 20px;
}
</style>
