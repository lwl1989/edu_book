<template>
    <div id="app" class="update-password">
        <el-form label-width="80px">
            <el-form-item label="账户名称">
                <el-input v-model="username"></el-input>
            </el-form-item>
            <el-form-item label="原密码">
                <el-input v-model="old_password"></el-input>
            </el-form-item>
            <el-form-item label="新密码">
                <el-input v-model="new_password"></el-input>
            </el-form-item>
            <el-form-item label="确认密码">
                <el-input v-model="repeat_password"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="updateUserPassword">立即修改</el-button>
                <el-button>取消</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                username: '',
                old_password: '',
                new_password: '',
                repeat_password: ''
            }
        },
        mounted() {
            let userInfo = [{
                name: 'A神',
                sex: '女',
                age: 18
            }, {
                name: 'C神',
                sex: '男',
                age: 22
            }];
            let newUserInfo = userInfo.map((x, index) => {
                let r = new Object
                r.name = x.name
                r.sex = x.sex
                return r
            })
        },
        methods: {
            updateUserPassword() {

            }
        }
    }
</script>

<style>
    .update-password {
        width: 260px;
    }
</style>
