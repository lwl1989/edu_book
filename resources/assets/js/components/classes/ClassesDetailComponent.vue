<template>
    <div id="app">
        <el-form :model="classes" :rules="rules" ref="classes" label-width="150px" size="small">

            <el-form-item label="班级名" prop="name">
                <el-col :span="12">
                    <el-input v-model="classes.name" ></el-input>
                </el-col>
            </el-form-item>




            <el-form-item label="班级人数" prop="excepted_count">
                <el-col :span="6">
                    <el-input-number v-model="classes.excepted_count" ></el-input-number>
                </el-col>
            </el-form-item>





        </el-form>

        <div slot="footer" style="text-align: center;">
            <el-row>
                <el-col :span="4">
                    &nbsp;
                </el-col>
                <el-col :span="8">
                    <el-button @click="goBack" size="small">返回列表</el-button>
                </el-col>
                <el-col :span="8">
                    <el-button type="primary" @click="doSubmit" size="small">確 定</el-button>
                </el-col>
                <el-col :span="4">
                    &nbsp;
                </el-col>
            </el-row>
        </div>

    </div>
</template>

<script>
    import {ClassRule} from '../../tools/element-ui-validate';
    export default {
        name: "classes-detail",
        data: function () {
            return {
                id: this.$route.params.id,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Content-Type': 'multipart/form-data'
                },
                classes: {
                    name:"",
                    excepted_count:0
                },
                rules: ClassRule,
                again:0
            }
        },
        created : function() {

        },
        mounted: function () {
            this.$nextTick(function() {
                if (this.id != 0) {
                    this.getClasses();
                }
            });
        },
        methods: {
            getClasses(){
                let that = this;
                axios.get('/classes/get?id='+that.id).then(function (response) {
                    that.classes = response.data.response.data;
                    console.log(response)
                }).catch(function (error) {

                });
            },
            openClassSuccess(callback){
                this.$emit('success',callback);
            },
            openClassWarning(callback){
                this.$emit('warning',callback);
            },
            resetPage(){
                this.again = 0;
            },
            goBack(){
                this.closeClasses('直接返回将不会保存本页数据，是否返回？');
            },
            closeClasses(message){
                let that = this;
                this.$emit('refresh',message, function () {
                    that.$router.push({path:'/classes'})
                });
            },
            doSubmit() {
                let h = this.$createElement;
                let that = this;
                // 表单验证方法
                this.$refs.classes.validate(function (result) {
                    if(result){
                        this.$msgbox({
                            title: '提示',
                            message: h('p', null, [
                                h('span', null,  '确定提交？')
                            ]),
                            showCancelButton: true,
                            confirmButtonText: '确定',
                            cancelButtonText: '取消',
                            beforeClose: (action, instance, done) => {
                                let callback = function(){
                                    instance.confirmButtonLoading = false;
                                    instance.confirmButtonText = '确定';
                                    done();
                                };
                                if (action === 'confirm') {
                                    instance.confirmButtonLoading = true;
                                    instance.confirmButtonText = '提交中...';

                                    let url = that.id == 0 ? '/classes/create' : '/classes/update';

                                    axios.post(url,that.classes)
                                        .then(function (response) {
                                            if(response.data.code == 0) {
                                                that.openClassSuccess(callback)
                                            }else{
                                                that.openClassWarning(callback);
                                            }
                                        }).catch(function (error) {
                                        that.openClassWarning(callback);
                                    });
                                } else {
                                    that.openClassWarning(callback);
                                }
                            }
                        });
                    }else{
                        console.log(result)
                    }
                }.bind(this));
            },
        }

    }
</script>

<style scoped>
    .el-form{
        width: 70%;
        margin: 0 auto;
    }
</style>