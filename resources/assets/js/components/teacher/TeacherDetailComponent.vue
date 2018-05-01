<template>
    <div id="app">
        <el-dialog :visible.sync="showDetail">
            <el-form :model="teacher" :rules="rules" ref="teacher" label-width="150px" size="small">

                <el-form-item label="老师姓名" prop="name">
                    <el-col :span="12">
                        <el-input v-model="teacher.name"></el-input>
                    </el-col>
                </el-form-item>

                <el-form-item label="老师编号" prop="teacher_num">
                    <el-col :span="16">
                        <el-input v-model="teacher.teacher_num"></el-input>
                    </el-col>
                </el-form-item>
                <el-form-item label="联系电话" prop="mobile">
                    <el-col :span="16">
                        <el-input v-model="teacher.mobile"></el-input>
                    </el-col>
                </el-form-item>

                <div style="text-align: center;">
                    <el-row>
                        <el-col :span="4">
                            &nbsp;
                        </el-col>
                        <el-col :span="8">
                            <el-button @click="teacherHide" size="small">关闭</el-button>
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
    import {TeacherRule} from '../../tools/element-ui-validate';
    import NewDialog from '../../tools/element-ui-dialog';
    export default {
        name: "teacher-detail",
        data: function () {
            return {
                dialog:NewDialog(this),
                teacher: {
                    id: 0,
                    name: "",
                    teacher_num: "",
                    class_id: "",
                },
                rules: TeacherRule,
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
            teacherShow(editId) {

                let that = this;
                this.editId = editId;
                if (editId > 0) {
                    axios.get('/teacher/get?id=' + editId).then(function (response) {
                        that.teacher = response.data.response.data;
                        that.showDetail = true;
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else {
                    this.showDetail = true;
                }
            },
            teacherHide() {
                this.showDetail = false;
                this.reset();
            },
            doStudentSubmit() {
                let h = this.$createElement;
                let that = this;
                // 表单验证方法
                this.$refs.teacher.validate(function (result) {
                    if(result){
                        this.$msgbox({
                            title: '提示',
                            message: h('p', null, [
                                h('span', null,  '确定保存信息？')
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

                                    let url = that.editId === 0 ? '/teacher/create' : '/teacher/update?id='+that.editId;

                                    axios.post(url,that.teacher)
                                        .then(function (response) {
                                            if(response.data.code == 0) {
                                                let st = that.teacher;
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
                this.teacher = {
                    id: 0,
                    name: "",
                    teacher_num: ""
                };
            }
        }
    }
</script>

<style scoped>

</style>