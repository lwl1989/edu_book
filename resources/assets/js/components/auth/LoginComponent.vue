<template>
    <div id="app">
        <el-form label-position="left" label-width="0px" class="demo-ruleForm login-container">
            <h3 class="title">系统登录</h3>
            <el-form-item prop="account">
                <el-input type="text" auto-complete="off" placeholder="账号"></el-input>
            </el-form-item>
            <el-form-item prop="checkPass">
                <el-input type="password" auto-complete="off" placeholder="密码"></el-input>
            </el-form-item>
            <el-checkbox checked class="remember">记住密码</el-checkbox>
            <el-form-item style="width:100%;">
                <el-button type="primary" style="width:100%;">登录</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: "login-component.vue"
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