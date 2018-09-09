<template>
    <div id="app">
        <el-dialog :visible.sync="showDetail">
            <el-form :model="student" :rules="rules" ref="student" label-width="150px" size="small">

                <el-form-item label="学生姓名" prop="name">
                    <el-col :span="12">
                        <el-input v-model="student.name"></el-input>
                    </el-col>
                </el-form-item>

                <el-form-item label="学生学号" prop="student_num">
                    <el-col :span="16">
                        <el-input v-model="student.student_num"></el-input>
                    </el-col>
                </el-form-item>

                <el-form-item label="班级" prop="class_id">
                    <el-col :span="24">
                        <el-select  v-model="student.class_id">
                            <el-option
                                    v-for="item in classes"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id">
                            </el-option>
                        </el-select>
                        <!--<el-checkbox-group v-model="student.class_id">-->
                            <!--<el-checkbox v-for="c in classes" :label="c.id" :key="c.id" :value="c.id">-->
                                <!--{{c.name}}-->
                            <!--</el-checkbox>-->
                        <!--</el-checkbox-group>-->
                    </el-col>
                </el-form-item>

                <div style="text-align: center;">
                    <el-row>
                        <el-col :span="4">
                            &nbsp;
                        </el-col>
                        <el-col :span="8">
                            <el-button @click="studentHide" size="small">关闭</el-button>
                        </el-col>
                        <el-col :span="8">
                            <el-button type="primary" @click="doStudentSubmit" size="small">確 定</el-button>
                        </el-col>
                        <el-col :span="4">
                            &nbsp;
                        </el-col>
                    </el-row>
                </div>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
    import {StudentRule} from '../../tools/element-ui-validate';
    import NewDialog from '../../tools/element-ui-dialog';
    export default {
        name: "student-detail",
        data: function () {
            return {
                dialog:NewDialog(this),
                student: {
                    id: 0,
                    name: "",
                    student_num: "",
                    class_id: "",
                },
                rules: StudentRule,
                showDetail: false,
                editId: 0,
                classes: [],
            }
        },
        mounted(){
            this.$nextTick(function() {
               this.getStudentClass();
            });
        },
        methods: {
            getStudentClass() {
                let that = this;
                axios.get('/classes/select?limit=999').then(function (response) {
                    that.classes = response.data.response.list;
                    //that.getChecked();
                }).catch(function (error) {
                    that.classes = []
                });
            },
            studentShow(editId) {

                let that = this;
                this.editId = editId;
                if (editId > 0) {
                    axios.get('/student/get?id=' + editId).then(function (response) {
                        that.student = response.data.response.data;
                        that.showDetail = true;
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else {
                    this.showDetail = true;
                }
            },
            studentHide() {
                this.showDetail = false;
                this.reset();
            },
            doStudentSubmit() {
                let h = this.$createElement;
                let that = this;
                // 表单验证方法
                this.$refs.student.validate(function (result) {
                    if(result){
                        this.$msgbox({
                            title: '提示',
                            message: h('p', null, [
                                h('span', null,  '确定添加？')
                            ]),
                            showCancelButton: true,
                            confirmButtonText: '确定',
                            cancelButtonText: '取消',
                            beforeClose: (action, instance, done) => {
                                let callback = function(){
                                    instance.confirmButtonLoading = false;
                                    instance.confirmButtonText = '确定';
                                    that.showDetail = false;
                                    that.reset();
                                    done();
                                };
                                if (action === 'confirm') {
                                    instance.confirmButtonLoading = true;
                                    instance.confirmButtonText = '提交中...';

                                    let url = that.editId === 0 ? '/student/create' : '/student/update?id='+that.editId;

                                    axios.post(url,that.student)
                                        .then(function (response) {
                                            if(response.data.code == 0) {
                                                let st = that.student;
                                                that.dialog.openSuccess(callback,"操作成功");
                                                if(that.editId === 0) {
                                                    st.id = response.data.response.id;
                                                    st.created_at = response.data.created_at;
                                                    that.$emit('add',st);
                                                }else{
                                                    that.$emit('edit',st)
                                                }

                                            }else{
                                                that.dialog.openDialog("warning",callback);
                                            }
                                        }).catch(function (error) {
                                        that.dialog.openDialog("warning",callback);
                                    });
                                } else {
                                    that.dialog.openDialog("warning",callback);
                                }
                            }
                        });
                    }else{
                        that.reset();
                        console.log(result)
                    }
                }.bind(this));
            },
            reset() {
                this.student = {
                    id: 0,
                    name: "",
                    student_num: ""
                };
            }
        }
    }
</script>

<style scoped>

</style>