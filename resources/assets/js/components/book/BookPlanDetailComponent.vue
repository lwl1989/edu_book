<template>
    <div id="app">
        <el-form :model="plan" :rules="rules" ref="plan" label-width="150px" size="small">

            <el-form-item label="教材名称" prop="book_id">
                <el-select
                        v-model="plan.book_id"
                        filterable
                        remote
                        width="500"
                        reserve-keyword
                        :placeholder="search_book"
                        :remote-method="getBooks"
                        :loading="loading">
                    <el-option
                            v-for="item in books"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="预购数量" prop="number">
                <el-col :span="16">
                    <el-input v-model="plan.number" ></el-input>
                </el-col>
            </el-form-item>

            <el-form-item label="作业本数量" prop="notebook_num">
                <el-col :span="12">
                    <el-input v-model="plan.notebook_num" ></el-input>
                </el-col>
            </el-form-item>

            <el-form-item label="计划使用年份" prop="plan_year">
                <el-col :span="12">
                    <el-input v-model="plan.plan_year" ></el-input>
                </el-col>
            </el-form-item>

            <el-form-item label="全年/上/下学期" prop="up_down">
                <el-select v-model="plan.up_down">
                    <el-option key="0" label="全年" value="0"></el-option>
                    <el-option key="1" label="上学期" value="1"></el-option>
                    <el-option key="2" label="下学期" value="2"></el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="班级" prop="classes">
                <el-col :span="24">
                    <el-checkbox-group v-model="plan.classes">
                        <el-checkbox v-for="c in classes" :label="c.id" :key="c.id" :value="c.id">{{c.name}}</el-checkbox>
                    </el-checkbox-group>
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
    import {BookPlanRule} from '../../tools/element-ui-validate';
    export default {
        name: "book-detail",
        data: function () {
            return {
                id: this.$route.params.id,
                books:[],
                classes:[],
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Content-Type': 'multipart/form-data'
                },
                plan:{
                    id:0,
                    book_id:"",
                    number:0,
                    notebook_num:0,
                    classes:[
                        1
                    ],
                    plan_year:0,
                    up_down:"0"
                },
                search_book:"请输入教材名(模糊搜索)",
                rules: BookPlanRule,
                again:0,
                loading: false,
                checkedClass:[]
            }
        },
        created : function() {

        },
        mounted: function () {
            this.$nextTick(function() {
                let now = new Date();
                this.plan.plan_year = now.getFullYear();
                this.getClasses();
                if (this.id != 0) {
                    this.getBookPlan();
                }
            });
        },
        methods: {
            getBooks(query){
                let that = this;
                if(query !== '') {
                    axios.get('/book/select?keyword='+query).then(function (response) {
                        that.books = response.data.response.list;
                    }).catch(function (error) {
                        that.books = []
                    });
                }else{
                    that.books = []
                }
            },
            getChecked(){
                let that = this;
                this.classes.forEach(function(item){
                    if(that.plan.classes.indexOf(item.id) !== -1) {
                        that.checkedClass.push(item)
                    }
                });
            },
            getClasses(){
                let that = this;
                axios.get('/classes/select?limit=999').then(function (response) {
                    that.classes = response.data.response.list;
                    that.getChecked();
                }).catch(function (error) {
                    that.classes = []
                });
            },
            openPlanSuccess(callback){
                this.$emit('success',callback);
            },
            openPlanWarning(callback){
                this.$emit('warning',callback);
            },
            resetPage(){
                this.again = 0;
            },
            closePlan(message){
                let that = this;
                this.$emit('refresh',message, function () {
                    that.$router.push({path:'/book'})
                });
            },
            goBack(){
                this.closePlan('直接返回将不会保存本页数据，是否返回？');
            },
            doSubmit() {
                let h = this.$createElement;
                let that = this;
                // 表单验证方法
                this.$refs.plan.validate(function (result) {
                    console.log(that.plan);
                    if(result){
                        this.$msgbox({
                            title: '提示',
                            message: h('p', null, [
                                h('span', null,  '确定提交本计划？')
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

                                    let url = that.id == 0 ? '/book/plan/create' : '/book/plan/update';

                                    axios.post(url,that.plan)
                                        .then(function (response) {
                                            if(response.data.code == 0) {
                                                that.openPlanSuccess(callback)
                                            }else{
                                                that.openPlanWarning(callback);
                                            }
                                        }).catch(function (error) {
                                        that.openPlanWarning(callback);
                                    });
                                } else {
                                    that.openPlanWarning(callback);
                                }
                            }
                        });
                    }else{
                        console.log(result)
                    }
                }.bind(this));
            },
            getBookPlan() {
                let that = this;
                axios.get('/book/plan/get?id=' + this.id).then(function (response) {
                    if (response.data.code == 0) {
                        that.book = response.data.response.data;
                        that.search_book = that.book.name;
                    } else {
                        that.closePlan('未獲取到本页數據,是否關閉頁面？');
                    }
                }).catch(function (error) {
                    that.openPlanDetailRefresh('網絡不穩定，是否重試？', function () {
                        window.location.reload(true)
                    });
                });
            },
            openPlanDetailRefresh(message, callback) {
                this.$emit("refresh",message,callback)
            }
        }

    }
</script>

<style scoped>
    .el-form{
        width: 70%;
        margin: 0 auto;
    }
</style>