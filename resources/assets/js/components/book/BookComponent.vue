<template>
    <div id="app">
        <!-- 商品查詢列 -->
        <el-row>
            <el-form :inline="true"  :model="searchBookFrom"  ref="searchBookFrom" class="demo-form-inline">
                <el-form-item>
                    <el-select v-model="searchBookFrom.profile" placeholder="名稱">
                        <el-option label="名稱" value="title"></el-option>
                    </el-select>
                </el-form-item>

                <el-form-item>
                    <el-input v-model="searchBookFrom.profileValue" auto-complete="off"></el-input>
                </el-form-item>

                <el-form-item>
                    <el-select v-model="searchBookFrom.timeType">
                        <el-option label="新增时间" value="create_time"></el-option>
                        <el-option label="修改时间" value="update_time"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-date-picker
                            v-model="searchBookFrom.time"
                            type="daterange"
                            align="right"
                            unlink-panels
                            range-separator="至"
                            start-placeholder="开始日期"
                            end-placeholder="结束日期"
                            :picker-options="pickerOptions2">
                    </el-date-picker>
                    <!--:picker-options="pickerOptions2"-->
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" icon="el-icon-search" @click="searchAd()">查詢</el-button>
                </el-form-item>
            </el-form>
        </el-row>
        <!--  商品按鈕列 -->
        <el-row>
            <el-col :span="24" style="margin-top: 20px;">
                <el-button type="primary" icon="el-icon-goods" @click="addBook">新增教材</el-button>
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
                    <el-table-column prop="cost" label="定价">
                    </el-table-column>
                    <el-table-column  label="操作">
                        <template slot-scope="scope">
                            <el-button  size="small"
                                       type="edit" @click="editBook(scope.row)">
                                编辑教材
                            </el-button>
                            <el-button size="small"
                                       type="warning" @click="deleteBook(scope.row, scope.$index)">
                                删除教材
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
        name: "goods-list",
        data: function () {
            return {
                currentPage: 1,
                pageSize: 15,
                total: 1,
                loading: true,
                book: [],
                searchBookFrom: {
                    profile: "title",
                    profileValue: "",

                    time: [],
                    timeType: "create_time"
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
            getMaxPage() {
                let that = this;
                let url = '/book/count?'+this.getAppNowSearchUrl();
                axios.get(url)
                    .then(function (response) {
                        that.total = response.data.response.count;
                    })
                    .catch(function (error) {
                        that.openRefresh(error.toString(),function () {
                            window.location.reload(true)
                        });
                    });
            },

            handleCurrentChange(currentPage) {
                let that = this;
                let url = '/book/select?page='+currentPage+'&limit='+that.pageSize+'&'+this.getAppNowSearchUrl();
                axios.get(url) .then(function (response) {
                    that.book = response.data.response.list;
                }).catch(function (error) {
                    that.openRefresh(error.toString(),function () {
                        window.location.reload(true)
                    });
                });
            },
            getAppNowSearchUrl(){
                let time = [];
                if(this.searchBookFrom.time.length > 0) {
                    this.searchBookFrom.time.forEach(function (value) {
                        time.push(value.getTime());
                    });
                }
                time = time.join(',');
                return this.searchBookFrom.profile+'='+this.searchBookFrom.profileValue+'&'+this.searchBookFrom.timeType+'='+time;
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
            editBook(item){
                this.$router.push({path:'/book/detail/'+item.id});
            },
            deleteBook(item,index){
                let that = this;
                axios.delete('/book/delete?id='+item.id) .then(function (response) {
                    if(response.data.code === 0) {
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
            addBook(){
                this.$router.push({path:'/book/detail/0'});
            },
        }

    }
</script>

<style scoped>

</style>