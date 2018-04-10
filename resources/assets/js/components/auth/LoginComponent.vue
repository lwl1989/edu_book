<template>
    <div id="app">
        <el-form :model="loginForm" :rules="rules" ref="loginForm" label-position="left" label-width="0px" class="demo-ruleForm login-container" >
            <h3 class="title">系统登录</h3>
            <el-form-item prop="account">
                <el-input type="text" auto-complete="off" placeholder="账号" v-model="loginForm.account"></el-input>
            </el-form-item>
            <el-form-item prop="checkPass">
                <el-input type="password" auto-complete="off" placeholder="密码" v-model="loginForm.password"></el-input>
            </el-form-item>
            <el-radio v-model="loginForm.type" label="student">学生</el-radio>
            <el-radio v-model="loginForm.type" label="teacher">教师</el-radio>
            <el-radio v-model="loginForm.type" label="admin">老师</el-radio>
            <el-form-item style="width:100%;">
                <el-button type="primary" style="width:100%;" @click="login">登录</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: "login-component",
        data() {
            return {
                loginForm: {
                    account: "",
                    password: "",
                    type:"student"
                },
                rules: {
                    account: [{ required: true, message: '账户不能为空'}, { min: 6, max: 30, message: '长度需要在 6 到 30 个字符'}], //, trigger: 'blur'
                    password: [{ required: true, message: '密码不能为空'}, { min: 6, max: 30, message: '长度需要在 6 到 30 个字符'}],
                }
            }
        },
        methods: {
            openSuccess(callback){
                this.$message({
                    type: 'success',
                    message: '登录'
                });
                callback();
            },
            openWarning(str){
                this.$message({
                    type: 'warning',
                    message: '登录失败，请检查账户'
                });
            },
            login() {
                let that = this;
                console.log(this.$refs.loginForm);
                this.$refs.loginForm.validate(function (result) {
                    if(result){
                        axios.post('/login',that.loginForm)
                            .then(function (response) {
                                console.log(response);
                                if(response.data.code == 0) {
                                    that.$refs.loginForm.resetFields();
                                    that.openSuccess(function () {
                                        sessionStorage.setItem('router',JSON.stringify(response.data.response));
                                    });
                                }else{
                                    console.log(error)
                                }
                            }).catch(function (error) {
                            that.openWarning(error.toString());
                        });
                    }
                });
            },
        }

    }
</script>

<style scoped>
    body {
        margin: 0px;
        padding: 0px;
        background: #1F2D3D;
    }

    .login-container {
        box-shadow: 0 0px 8px 0 rgba(0, 0, 0, 0.06), 0 1px 0px 0 rgba(0, 0, 0, 0.02);
        -webkit-border-radius: 5px;
        border-radius: 5px;
        -moz-border-radius: 5px;
        background-clip: padding-box;
        margin: 180px auto;
        width: 350px;
        padding: 35px 35px 15px 35px;
        background: #fff;
        border: 1px solid #eaeaea;
        box-shadow: 0 0 2px #cac6c6;
    }

    .title {
        margin: 0px auto 40px auto;
        text-align: center;
        color: #505458;
    }

    .remember {
        margin: 0px 0px 35px 0px;
    }
</style>