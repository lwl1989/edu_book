<template>
    <div id="app">
        <el-row>

        </el-row>
        <el-row>
            <el-col :span="24" style="margin-top: 20px;">
                <el-button type="primary" icon="el-icon-goods" @click="addClasses">新增班级</el-button>
            </el-col>
        </el-row>
        <!--  商品Table列 -->
        <el-row>
            <el-col :span="24" style="margin-top: 20px;">
                <el-table :data="classes" stripe style="width: 100%" v-loading="loading">
                    <el-table-column type="selection">
                    </el-table-column>
                    <el-table-column
                            type="index"
                            width="50">
                    </el-table-column>
                    <el-table-column prop="name" label="班级名称(点击查看班级领书状况)" >
                        <template slot-scope="scope">
                            <el-button  size="small"
                                        type="text" @click="openClassReceiver(scope.row)">
                                {{scope.row.name}}
                            </el-button>
                        </template>
                    </el-table-column>
                    <el-table-column prop="student_count" label="现有人数" >
                    </el-table-column>
                    <el-table-column prop="excepted_count" label="预计人数" >
                    </el-table-column>
                    <el-table-column prop="created_at" label="创建时间" width="150">
                    </el-table-column>
                    <el-table-column  label="操作">
                        <template slot-scope="scope">
                            <el-button  size="small"
                                        type="edit" @click="editClasses(scope.row, scope.$index)">
                                编辑班级
                            </el-button>
                            <el-button size="small"
                                       type="warning" @click="deleteClasses(scope.row, scope.$index)">
                                删除班级
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-col>

            <el-col :span="24" v-if="total > 0">
                <div class="app-pagination">
                    <el-pagination  @current-change="handleClassesCurrentChange" :current-page="currentPage"  :page-size="pageSize" layout="total, prev, pager, next" :total="total">
                    </el-pagination>
                </div>
            </el-col>
        </el-row>

        <class-receive ref="receiver"></class-receive>
    </div>
</template>

<script>
    import ClassReceive from './ClassReceiveComponent'
    export default {
        name: "Classes",
        data: function () {
            return {
                currentPage: 1,
                pageSize: 15,
                total: 1,
                loading: true,
                classes: []
            }
        },
        components:{ClassReceive},
        mounted: function () {
            this.$nextTick(function() {
                this.getClassesMaxPage();
                this.handleClassesCurrentChange(1);
                this.loading = false;
            })
        },
        methods: {
            openClassReceiver(item){
                this.$refs.receiver.ClassReceive(item.id);
            },
            getClassesMaxPage() {
                let that = this;
                axios.get('/classes/count')
                    .then(function (response) {
                        that.total = response.data.response.count;
                    })
                    .catch(function (error) {
                        that.openRefresh('網絡不穩定，是否重試？',function () {
                            window.location.reload(true)
                        });
                    });
            },

            handleClassesCurrentChange(currentPage) {
                let that = this;
                axios.get('/classes/select?page='+currentPage+'&limit='+that.pageSize) .then(function (response) {
                    that.classes = response.data.response.list;
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
            editClasses(item,index){
                this.$router.push({path:'/classes/detail/'+item.id});
            },
            deleteClasses(item,index){
                let that = this;
                axios.delete('/classes/delete?id='+item.id) .then(function (response) {
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
            addClasses(){
                this.$router.push({path:'/classes/detail/0'});
            },
        }

    }
</script>

<style scoped>

</style>