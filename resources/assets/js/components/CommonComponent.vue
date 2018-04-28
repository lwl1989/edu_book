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
                        <router-view v-on:refresh="openRefresh" v-on:success="openSuccess" v-on:warning="openWarning"></router-view>
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
        logout() {
            this.$confirm('確認要退出登錄嗎？', '提示', {
                confirmButtonText: '確定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                window.location.href = '/logout';
            }).catch(() => {

            });
        },
        changePass() {
            this.$router.push({ path: '/changePass' })
        },
        openSuccess(callback,message){
            if(typeof(message) == 'undefined') {
                message = '操作成功'
            }
            this.openDialog('success', callback, message);
        },
        openWarning(callback,message){
            if(typeof(message) == 'undefined') {
                message = '操作失敗，請檢查'
            }
            this.openDialog('warning', callback, message);
        },
        openDialog(type, callback, message) {
            this.$message({
                type: type,
                message: message
            });
            if(typeof(callback) == 'function') {
                callback();
            }
        },
        openRefresh(message, callback) {
            let h = this.$createElement;
            this.$msgbox({
                title: '提示',
                message: h('p', null, [
                    h('span', null, message)
                ]),
                showCancelButton: true,
                confirmButtonText: '確定',
                cancelButtonText: '取消',
                beforeClose: (action, instance, done) => {
                    if (action === 'confirm') {
                        callback();
                        done();
                    } else {
                        done();
                    }
                },
            }).then(action => {

                //執行完畢
                //console.log(action);
            }).catch(e => {
                //執行異常
                //console.log(e)
            });
        },
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
