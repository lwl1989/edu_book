<template>
    <div id="app">

        <!-- 商品查詢列 -->
        <el-row>

        </el-row>
        <!--  商品按鈕列 -->
        <el-row>
            <el-col :span="24" style="margin-top: 20px;">
                <el-button type="primary" icon="el-icon-goods" @click="doEditStudent(0)">新增学生</el-button>
            </el-col>
        </el-row>
        <!--  商品Table列 -->
        <el-row>
            <el-col :span="24" style="margin-top: 20px;">
                <el-table :data="student" stripe style="width: 100%" v-loading="loading">
                    <el-table-column type="selection">
                    </el-table-column>
                    <el-table-column
                            type="index"
                            width="50">
                    </el-table-column>
                    <el-table-column prop="name" label="学生名称(点击查看领书记录)" >
                        <template slot-scope="scope">
                            <el-button  size="small"
                                        type="text" @click="listReceived(scope.row, scope.$index)">
                                {{scope.row.name}}
                            </el-button>
                        </template>
                    </el-table-column>
                    <el-table-column prop="student_num" label="学号" >
                    </el-table-column>
                    <el-table-column prop="created_at" label="创建时间" width="200">
                    </el-table-column>
                    <el-table-column  label="操作">
                        <template slot-scope="scope">
                            <el-button  size="small"
                                        type="primary" @click="editStudent(scope.row, scope.$index)">
                                编辑学生
                            </el-button>
                            <el-button  size="small"
                                        type="danger" @click="listReceive(scope.row, scope.$index)">
                                领&nbsp;&nbsp;&nbsp;&nbsp;书
                            </el-button>
                            <el-button size="small"
                                       type="warning" @click="deleteStudent(scope.row, scope.$index)">
                                删除学生
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-col>

            <el-col :span="24" v-if="total > 0">
                <div class="app-pagination">
                    <el-pagination  @current-change="handleStudentCurrentChange" :current-page="currentPage"  :page-size="pageSize" layout="total, prev, pager, next" :total="total">
                    </el-pagination>
                </div>
            </el-col>
        </el-row>

        <student-detail ref="detail" v-on:add="addStudentList" v-on:edit="editStudentList"></student-detail>
        <student-receive ref="receive"></student-receive>
        <student-received ref="received"></student-received>
    </div>
</template>

<script>
    import StudentDetail from "./StudentDetailComponent";
    import StudentReceive from "./StudentReceiveComponent";
    import StudentReceived from "./StudentReceivedComponent";
    export default {
        name: "StudentComponent",
        components: {StudentReceive, StudentDetail,StudentReceived},
        data: function () {
            return {
                currentPage: 1,
                pageSize: 15,
                total: 1,
                loading: true,
                student: [],
                editId:0
            }
        },
        mounted: function () {
            this.$nextTick(function() {
                this.getStudentMaxPage();
                this.handleStudentCurrentChange(1);
                this.loading = false;
            })
        },
        methods:{
            getStudentMaxPage() {
                let that = this;
                axios.get('/student/count')
                    .then(function (response) {
                        that.total = response.data.response.count;
                    })
                    .catch(function (error) {
                        that.$emit('refresh',function () {
                            window.location.reload(true)
                        },'網絡不穩定，是否重試？');
                    });
            },
            handleStudentCurrentChange(currentPage) {
                let that = this;
                axios.get('/student/select?page='+currentPage+'&limit='+that.pageSize) .then(function (response) {
                    that.student = response.data.response.list;
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
                this.$refs.receive.studentReceiveShow(item.id)
            },
            listReceived(item){
                this.$refs.received.studentReceiveShow(item.id)
            },
            deleteStudent(item, index) {
                let that = this;
                axios.delete('/student/delete?id='+item.id) .then(function (response) {
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
                this.$refs.detail.studentShow(id)
            },
            addStudentList(item){
                this.student.push(item);
            },
            editStudentList(item){
                let index = -1;
                this.student.forEach(function (value, key) {
                    if(value.id == item.id) {
                        index = key;
                    }
                });
                this.student[index].name = item.name;
                this.student[index].student_num = item.student_num;
            }
        },
    }
</script>

<style scoped>

</style>