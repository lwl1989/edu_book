<template>
    <div id="app">

        <!-- 商品查詢列 -->
        <el-row>

        </el-row>
        <!--  商品按鈕列 -->
        <el-row>
            <el-col :span="24" style="margin-top: 20px;">
                <el-button type="primary" icon="el-icon-goods" @click="doEditStudent(0)">新增老师</el-button>
            </el-col>
        </el-row>
        <!--  商品Table列 -->
        <el-row>
            <el-col :span="24" style="margin-top: 20px;">
                <el-table :data="teacher" stripe style="width: 100%" v-loading="loading">
                    <el-table-column type="selection">
                    </el-table-column>
                    <el-table-column
                            type="index"
                            width="50">
                    </el-table-column>
                    <el-table-column prop="name" label="老师名称(点击查看缴费记录)" >
                        <template slot-scope="scope">
                            <el-button  size="small"
                                        type="text" @click="listPay(scope.row)">
                                {{scope.row.name}}
                            </el-button>
                        </template>
                    </el-table-column>
                    <el-table-column prop="teacher_num" label="学号" width="200" >
                    </el-table-column>
                    <el-table-column prop="mobile" label="联系电话" width="200" >
                    </el-table-column>
                    <el-table-column prop="created_at" label="创建时间" width="200">
                    </el-table-column>
                    <el-table-column  label="操作">
                        <template slot-scope="scope">
                            <el-button  size="small"
                                        type="primary" @click="editStudent(scope.row, scope.$index)">
                                编辑老师
                            </el-button>
                            <el-button  size="small"
                                        type="danger" @click="listReceive(scope.row, scope.$index)">
                                领&nbsp;&nbsp;&nbsp;&nbsp;书
                            </el-button>
                            <el-button size="small"
                                       type="warning" @click="deleteStudent(scope.row, scope.$index)">
                                删除老师
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-col>

            <el-col :span="24" v-if="total > 0">
                <div class="app-pagination">
                    <el-pagination  @current-change="handleTeacherCurrentChange" :current-page="currentPage"  :page-size="pageSize" layout="total, prev, pager, next" :total="total">
                    </el-pagination>
                </div>
            </el-col>
        </el-row>

        <teacher-detail ref="detail" v-on:add="addStudentList" v-on:edit="editStudentList"></teacher-detail>
        <teacher-receive ref="receive"></teacher-receive>
    </div>
</template>

<script>
    import TeacherDetail from "./TeacherDetailComponent";
    import TeacherReceive from "./TeacherReceiveComponent";
    export default {
        name: "TeacherComponent",
        components: {TeacherDetail,TeacherReceive},
        data: function () {
            return {
                currentPage: 1,
                pageSize: 15,
                total: 1,
                loading: true,
                teacher: [],
                editId:0
            }
        },
        mounted: function () {
            this.$nextTick(function() {
                this.getTeacherMaxPage();
                this.handleTeacherCurrentChange(1);
                this.loading = false;
            })
        },
        methods:{
            getTeacherMaxPage() {
                let that = this;
                axios.get('/teacher/count')
                    .then(function (response) {
                        that.total = response.data.response.count;
                    })
                    .catch(function (error) {
                        that.$emit('refresh',function () {
                            window.location.reload(true)
                        },'網絡不穩定，是否重試？');
                    });
            },
            handleTeacherCurrentChange(currentPage) {
                let that = this;
                axios.get('/teacher/select?page='+currentPage+'&limit='+that.pageSize) .then(function (response) {
                    that.teacher = response.data.response.list;
                }).catch(function (error) {
                    that.$emit('refresh',function () {
                        window.location.reload(true)
                    },'網絡不穩定，是否重試？');
                });
            },
            editStudent(item, index){
                this.doEditStudent(item.id);
            },
            listReceive(item){
                this.$refs.receive.teacherReceiveShow(item.id)
            },
            deleteStudent(item, index) {
                let that = this;
                axios.delete('/teacher/delete?id='+item.id) .then(function (response) {
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
            doEditStudent(id){
                this.editId = id;
                this.$refs.detail.teacherShow(id)
            },
            addStudentList(item){
                this.teacher.push(item);
            },
            editStudentList(item){
                let index = -1;
                this.teacher.forEach(function (value, key) {
                    if(value.id === item.id) {
                        index = key;
                    }
                });
                this.teacher[index].name = item.name;
                this.teacher[index].teacher_num = item.teacher_num;
                this.teacher[index].mobile = item.mobile;
            }
        },
    }
</script>

<style scoped>

</style>