<template>
    <div id="app">
        <el-row>
            <el-col :span="6">
                &nbsp;
            </el-col>
            <el-col :span="12">
                <el-form :model="password" :rules="rules" ref="password" label-width="150px">
                    <el-form-item label="老密碼" :label-width="formLabelWidth"  prop="oldPassword">
                        <el-input  type="password" v-model="password.oldPassword" auto-complete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="新密碼" :label-width="formLabelWidth"  prop="newPassword">
                        <el-input type="password" v-model="password.newPassword" auto-complete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="確認新密碼" :label-width="formLabelWidth"  prop="confirmPassword">
                        <el-input type="password"  v-model="password.confirmPassword" auto-complete="off"></el-input>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="6">
                &nbsp;
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="6">
                &nbsp;&nbsp;
            </el-col>
            <el-col :span="12">
                <el-button type="primary" @click="doChangePass" style="margin-left: 150px;">確 定</el-button>
            </el-col>
            <el-col :span="6">
                &nbsp;&nbsp;
            </el-col>
        </el-row>

    </div>
</template>

<script>
    export default {
        name: "change-pass-component",
        data:function () {
            return {
                rules:{},
                formLabelWidth:'150px',
                password : {
                    oldPassword: '',
                    newPassword: '',
                    confirmPassword: ''
                }
            }
        },
        methods:{
            resetPasswordFrom(){
                this.password = {
                    oldPassword: '',
                    newPassword: '',
                    confirmPassword: ''
                };
            },
            doChangePass(){
                let that = this;
                axios.post('/admin/change/password',this.password).then(function (response) {
                    if(parseInt(response.data.code) === 0) {
                        that.$emit('success',function () {
                            setTimeout(function () {
                                window.location.href = '/logout';
                            },2000);
                        },'您已修改密碼，請重新登錄，即將跳轉到登錄頁！')
                    }else{
                        that.$emit('warning',function () {
                            that.resetPasswordFrom();
                        },'操作失敗');
                    }
                }).catch(function (err) {
                    that.$emit('warning',function () {
                        that.resetPasswordFrom();
                    },'操作失敗'+err.toString());
                });
            }
        }
    }
</script>

<style scoped>

</style>