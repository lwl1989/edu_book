<template>
    <div id="app">
        <!-- 商品查詢列 -->
        <el-row>

        </el-row>
        <!--  商品按鈕列 -->
        <el-row>
            <el-col :span="24" style="margin-top: 20px;">
                <el-button type="primary" icon="el-icon-goods" @click="addBook">新增书籍</el-button>
                <el-button type="primary" icon="el-icon-document">导出excel</el-button>
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
                    <el-table-column prop="name" label="书籍名称">
                    </el-table-column>
                    <el-table-column prop="number" label="计划数量">
                    </el-table-column>
                    <el-table-column prop="number" label="计划数量">
                    </el-table-column>
                    <el-table-column prop="cost" label="标注价格">
                    </el-table-column>
                    <el-table-column prop="price" label="班级">
                    </el-table-column>
                    <el-table-column prop="plan_year" label="年份">
                    </el-table-column>
                    <el-table-column prop="up_down" label="学期(上/下)">
                    </el-table-column>
                    <el-table-column prop="created_at" label="创建时间">
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
        name: "goods-list",
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
                axios.get('/book/plan/count')
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
                axios.get('/book/plan/select?page='+currentPage+'&limit='+that.pageSize) .then(function (response) {
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
                this.$router.push({path:'/book/detail/0'});
            },
        }

    }
</script>

<style scoped>

</style>