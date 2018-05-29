<template>
    <div id="app">
        <!-- 商品查詢列 -->
        <el-row>
            <el-form :inline="true"  :model="searchOrderFrom"  ref="searchOrderFrom" class="demo-form-inline">
                <el-form-item>
                    <el-select v-model="searchOrderFrom.profile" placeholder="名稱">
                        <el-option label="名稱" value="title"></el-option>
                    </el-select>
                </el-form-item>

                <el-form-item>
                    <el-input v-model="searchOrderFrom.profileValue" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-select v-model="searchOrderFrom.timeType">
                        <el-option label="新增時間" value="create_time"></el-option>
                        <el-option label="最後異動時間" value="update_time"></el-option>
                        <el-option label="上架時間（起）" value="online_time"></el-option>
                        <el-option label="上架時間（迄）" value="offline_time"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-date-picker
                            v-model="searchOrderFrom.time"
                            type="datetimerange"

                            range-separator="~"
                            start-placeholder="開始日期"
                            end-placeholder="結束日期"
                            align="right">
                    </el-date-picker>
                    <!--:picker-options="pickerOptions2"-->
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" icon="el-icon-search" @click="searchOrder()">查詢</el-button>
                </el-form-item>
            </el-form>
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
                    <el-table-column prop="name" label="教材名称" >
                    </el-table-column>
                    <el-table-column prop="sn" label="SN" >
                    </el-table-column>
                    <el-table-column prop="company" label="出版社" >
                    </el-table-column>
                    <el-table-column prop="author" label="作者" >
                    </el-table-column>
                    <el-table-column prop="number" label="计划数量" width="100">
                    </el-table-column>
                    <el-table-column prop="cost" label="定价" width="100">
                    </el-table-column>
                    <el-table-column prop="stock" label="购买价格" width="100">
                    </el-table-column>
                    <el-table-column prop="plan_year" label="年份" width="100">
                    </el-table-column>
                    <el-table-column prop="created_at" label="入库时间" width="150">
                    </el-table-column>
                    <el-table-column  label="操作" style="align:center;">
                        <template slot-scope="scope">
                            <el-button v-if="scope.row.status === 1" size="small"
                                       type="success" @click="eventStateUpdate(scope.row, scope.$index)">
                                完成入库
                            </el-button>
                            <div style="width:100%;height: 3px;"> </div>
                            <el-button v-if="scope.row.status === 1" size="small"
                                       type="warning" @click="eventStateUpdate(scope.row, scope.$index)">
                                删除入库
                            </el-button>
                            <div style="width:100%;height: 3px;"> </div>
                            <el-button v-if="scope.row.state === 2" size="small"
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
                book: [],
                selectIds:[],
                searchOrderFrom:{
                    profile:"title",
                    profileValue:"",

                    time:[

                    ],
                    timeType:"create_time"
                }
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
            searchOrder(){
                this.loading = true;
                this.handleCurrentChange(1);
            },
            getNowSearchUrl(){
                let time = [];
                if(this.searchOrderFrom.time.length > 0) {
                    this.searchOrderFrom.time.forEach(function (value) {
                        time.push(value.getTime());
                    });
                }
                time = time.join(',');
                return this.searchOrderFrom.profile+'='+this.searchOrderFrom.profileValue+'&'+this.searchOrderFrom.timeType+'='+time;
            },
            getMaxPage() {
                let that = this;
                let url = '/book/order/count'+'?'+this.getNowSearchUrl();
                axios.get(url)
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
                let url = '/book/order/select?page='+currentPage+'&limit='+that.pageSize+'&'+this.getNowSearchUrl();
                axios.get(url) .then(function (response) {
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