<template>
    <div id="app">
        <!-- 商品查詢列 -->
        <el-row>

        </el-row>
        <!--  商品按鈕列 -->
        <el-row>
            <el-col :span="24" style="margin-top: 20px;">
                <el-button type="primary" icon="el-icon-goods" @click="addBook">新增入库</el-button>
            </el-col>
        </el-row>
        <!--  商品Table列 -->
        <el-row>
            <el-col :span="24" style="margin-top: 20px;">
                <el-table :data="book" stripe style="width: 100%" v-loading="loading">
                    <el-table-column type="selection">
                    </el-table-column>
                    <el-table-column
                            type="index"
                            width="50">
                    </el-table-column>
                    <el-table-column prop="order_num" label="入库号" width="120">
                    </el-table-column>
                    <el-table-column prop="name" label="教材名称" :show-overflow-tooltip=true width="100">
                    </el-table-column>
                    <el-table-column prop="number" label="计划数量" width="100">
                    </el-table-column>
                    <el-table-column prop="price" label="购买价格" width="100">
                    </el-table-column>
                    <el-table-column prop="plan_year" label="年份" width="100">
                    </el-table-column>
                    <el-table-column prop="created_at" label="创建时间" width="150">
                    </el-table-column>
                    <el-table-column  label="操作">
                        <template slot-scope="scope">
                            <el-button v-if="scope.row.status == 1" size="small"
                                       type="success" @click="eventStateUpdate(scope.row, scope.$index)">
                                完成入库
                            </el-button>
                            <el-button v-if="scope.row.status == 1" size="small"
                                       type="warning" @click="eventStateUpdate(scope.row, scope.$index)">
                                删除入库
                            </el-button>
                            <el-button v-if="scope.row.state == 2" size="small"
                                       type="danger" @click="eventStateUpdate(scope.row, scope.$index)">
                                隐藏
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-col>

            <el-col :span="24" v-if="total > 0">
                <div class="app-pagination">
                    <el-pagination  @current-change="handleCurrentChange" :current-page="currentPage"  :page-size="pageSize" layout="total, prev, pager, next" :total="total">
                    </el-pagination>
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    export default {
        name: "book_order",
        data: function () {
            return {
                currentPage: 1,
                pageSize: 15,
                total: 1,
                loading: true,
                book: []
            }
        },
        mounted: function () {
            this.$nextTick(function() {
                this.getMaxPage();
                this.handleCurrentChange(1);
                this.loading = false;
            })
        },
        methods: {
            getMaxPage() {
                let that = this;
                axios.get('/book/order/count')
                    .then(function (response) {
                        that.total = response.data.response.count;
                    })
                    .catch(function (error) {
                        that.openRefresh('網絡不穩定，是否重試？',function () {
                            window.location.reload(true)
                        });
                    });
            },

            handleCurrentChange(currentPage) {
                let that = this;
                axios.get('/book/order/select?page='+currentPage+'&limit='+that.pageSize) .then(function (response) {
                    that.book = response.data.response.list;
                }).catch(function (error) {
                    that.openRefresh('網絡不穩定，是否重試？',function () {
                        window.location.reload(true)
                    });
                });
            },
            openBLSuccess(callback){
                this.$message({
                    type: 'success',
                    message: '操作成功'
                });
                callback();
            },
            openBLWarning(){
                this.$message({
                    type: 'warning',
                    message: '操作失敗，請重試'
                });
            },
            upDownFormat(item,field) {
                let value = item["up_down"];
                if (value == 0) {
                    return "全年"
                }
                if (value == 1) {
                    return "上学期"
                }
                if (value == 2) {
                    return "下学期"
                }
            },
            openRefresh(message,callback){
                let h = this.$createElement;
                this.$msgbox({
                    title: '提示',
                    message: h('p', null, [
                        h('span', null, message)
                    ]),
                    showCancelButton: true,
                    confirmButtonText: '確定',
                    cancelButtonText: '取消',
                    beforeClose: (action,instance,done) => {
                        if (action === 'confirm') {
                            callback();
                        }else {
                            done();
                        }
                    },
                }).then(action => {
                    this.$message({
                        type: 'info',
                        message: 'action: ' + action
                    });
                }).catch(e=>{
                    //console.log(e)
                });
            },
            offSale(){

            },
            addBook(){
                this.$router.push({path:'/book/order/detail/0'});
            },
        }

    }
</script>

<style scoped>

</style>