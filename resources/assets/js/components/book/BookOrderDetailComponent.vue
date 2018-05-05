<template>
    <div id="app">
        <el-form :model="order" :rules="rules" ref="order" label-width="200px" size="small">

            <el-row>
                <el-col :span="12">
                    <el-form-item label="教材名称" prop="book_id">
                        <el-select
                                v-model="order.plan_id"
                                filterable
                                remote
                                reserve-keyword
                                placeholder="请输入教材名称关键词"
                                :remote-method="getBookPlan"
                                :loading="loading"
                                @change="changeBook"
                        >
                            <el-option
                                    v-for="item in books"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <span v-if="need_buy > 0">库存差计划数量还需要购买 {{need_buy}} 本</span>
                </el-col>
            </el-row>


            <el-form-item label="预购数量" prop="number">
                <el-col :span="16">
                    <el-input-number v-model="order.number" ></el-input-number>
                </el-col>
            </el-form-item>

            <el-form-item label="店家入库编号" prop="order_num">
                <el-col :span="12">
                    <el-input v-model="order.order_num" ></el-input>
                </el-col>
                <el-col :span="12">
                    <span>真实入库成立后输入</span>
                </el-col>
            </el-form-item>

            <el-form-item label="单价" prop="price">
                <el-col :span="12">
                    <el-input v-model="order.price" ></el-input>
                </el-col>
                <el-col :span="12">
                    <span>实际购买单价</span>
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
    import {BookOrderRule} from '../../tools/element-ui-validate';
    export default {
        name: "book-order-detail",
        data: function () {
            return {
                id: this.$route.params.id,
                books:[],
                classes:[],
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Content-Type': 'multipart/form-data'
                },
                order:{
                    id:0,
                    plan_id:"",
                    number:0,
                    order_num:"",
                    price:""
                },
                book_stock:0,
                need_buy:0,
                rules: BookOrderRule,
                again:0,
                loading: false,
                checkedClass:[]
            }
        },
        created : function() {

        },
        mounted: function () {
            this.$nextTick(function() {

            });
        },
        methods: {
            changeBook(id){
                let that = this;
              this.books.forEach(function (item) {
                  if(item.id == id) {
                      that.need_buy = item.number - item.stock;
                  }
              })
            },
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
            getBookPlan(query) {
                    let that = this;
                    if(query !== '') {
                        axios.get('/book/plan/select?keyword='+query).then(function (response) {
                            that.books = response.data.response.list;
                        }).catch(function (error) {
                            that.books = []
                        });
                    }else{
                        that.books = []
                    }
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