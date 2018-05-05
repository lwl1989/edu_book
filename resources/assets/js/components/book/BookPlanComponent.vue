<template>
    <div id="app">
        <!-- 商品查詢列 -->
        <el-row>

        </el-row>
        <!--  商品按鈕列 -->
        <el-row>
            <el-col :span="24" style="margin-top: 20px;">
                <el-button type="primary" icon="el-icon-goods" @click="addBookPlan">新增教材计划单</el-button>
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
                    <el-table-column prop="name" label="教材名称" width="200" :show-overflow-tooltip=true>
                    </el-table-column>
                    <el-table-column prop="number" label="计划数量" width="80">
                    </el-table-column>
                    <el-table-column prop="stock" label="库存" width="80">
                    </el-table-column>
                    <el-table-column prop="cost" label="标注价格" width="80">
                    </el-table-column>
                    <el-table-column prop="class_list" label="使用班级" width="200" :show-overflow-tooltip=true>
                    </el-table-column>
                    <el-table-column prop="plan_year" label="年份" width="80">
                    </el-table-column>
                    <el-table-column prop="up_down" label="学期(上/下)"  :formatter="upDownFormat" width="80" >
                    </el-table-column>
                    <el-table-column prop="created_at" label="创建时间" width="150">
                    </el-table-column>

                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button  size="small"  type="primary"
                                        @click="editBookPlan(scope.row, scope.$index)">
                                编辑计划
                            </el-button>
                            <el-button size="small" v-if="scope.row.status < 2"
                                       type="warning" @click="deleteBookPlan(scope.row, scope.$index)">
                                删除计划
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-col>

            <el-col :span="24" v-if="total > 0">
                <div class="app-pagination">
                    <el-pagination  @current-change="handlePlanCurrentChange" :current-page="currentPage"  :page-size="pageSize" layout="total, prev, pager, next" :total="total">
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
                this.getPlanMaxPage();
                this.handlePlanCurrentChange(1);
                this.loading = false;
            })
        },
        methods: {
            getPlanMaxPage() {
                let that = this;
                axios.get('/book/plan/count')
                    .then(function (response) {
                        that.total = response.data.response.count;
                    })
                    .catch(function (error) {
                        that.$emit('refresh',function () {
                            window.location.reload(true)
                        },'網絡不穩定，是否重試？');
                    });
            },

            handlePlanCurrentChange(currentPage) {
                let that = this;
                axios.get('/book/plan/select?page='+currentPage+'&limit='+that.pageSize) .then(function (response) {
                    that.book = response.data.response.list;
                }).catch(function (error) {
                    that.$emit('refresh',function () {
                        window.location.reload(true)
                    },'網絡不穩定，是否重試？');
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
            editBookPlan(item,index){
                this.$router.push({path:'/book/plan/detail/'+item.id});
            },
            deleteBookPlan(item,index){
                let that = this;
                axios.delete('/book/plan/delete?id='+item.id) .then(function (response) {
                    if(response.data.code == 0) {
                        that.$emit('success',function () {
                            that.book.splice(index,1);
                        });
                    }else{
                        that.$emit('warning',function () {
                            console.log(response)
                        });
                    }
                }).catch(function (error) {
                    that.$emit('warning',function () {
                        console.log(error)
                    });
                });
            },
            addBookPlan(){
                this.$router.push({path:'/book/plan/detail/0'});
            },
        }

    }
</script>

<style scoped>

</style>