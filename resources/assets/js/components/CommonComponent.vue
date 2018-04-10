<template>
    <div id="app">
        <el-row>
            <el-col :span="24">
                <div class="home-header">
                    <div class="home-span" @click="goToIndex">
                        <span>VueAdmin</span>
                    </div>
                    <div class="home-icon">
                        <!-- <i class="el-icon-setting"></i> -->
                        <el-dropdown split-button type="primary" @click="dialogTableVisible = true" @command="handleCommand">
                            查看个人信息
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item command="login">退出登录</el-dropdown-item>
                                <el-dropdown-item command="password">修改密码</el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </div>
                </div>
            </el-col>
            <el-col :span="24">
                <aside class="menu-expanded">
                    <!--导航菜单-->
                    <div class="home-header-menu" :style="{ height: menuHeight + 'px' }">
                        <el-menu :default-active="$route.path" unique-opened router>
                            <template v-for="(item,index) in $router.options.routes" v-if="!item.hidden">
                                <el-submenu :index="index+''" v-if="!item.leaf" :key="index">
                                    <template slot="title">
                                        <i :class="item.iconCls"></i>{{item.name}}</template>
                                    <el-menu-item v-for="child in item.children" :index="child.path" :key="child.path" v-if="!child.hidden">{{child.name}}</el-menu-item>
                                </el-submenu>
                            </template>
                        </el-menu>
                    </div>
                </aside>
                <section class="content-container" :style="{ width: contentWidth + 'px' }">
                    <div class="home-header-breadcrumb">
                        <el-breadcrumb separator="/" class="breadcrumb-inner">
                            <el-breadcrumb-item :to="{ path: '/echarts' }">首页</el-breadcrumb-item>
                            <el-breadcrumb-item v-for="(item, index) in $route.matched" :key="index">
                                {{ item.name }}
                            </el-breadcrumb-item>
                        </el-breadcrumb>
                    </div>
                    <div class="home-header-router" :style="{ height: routerHeight + 'px' }">
                        <router-view></router-view>
                    </div>
                </section>
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
    .el-button--primary {
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
