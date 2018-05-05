<template>
    <div id="app">
        <el-dialog  :visible.sync="showReceive">
            <el-button type="text" icon="el-icon-success">已领教材</el-button>
            <el-table :data="books_received" stripe style="width: 100%" v-if="loading" :row-class-name="tableRowClassName" >
                <el-table-column prop="sn" label="教材sn" >
                </el-table-column>
                <el-table-column prop="name" label="教材名称" >
                </el-table-column>
                <el-table-column prop="notebook_num" label="笔记本数量" >
                </el-table-column>
                <el-table-column prop="cost" label="标注价格" >
                </el-table-column>
                <el-table-column prop="received_time" label="领取时间" >
                </el-table-column>
            </el-table>

            <el-row>
                &nbsp;
            </el-row>

            <el-button type="text" icon="el-icon-location">现在领取</el-button>
            <el-table :data="books" stripe style="width: 100%">
                <el-table-column prop="sn" label="教材sn">
                </el-table-column>
                <el-table-column prop="name" label="教材名称">
                </el-table-column>
                <el-table-column prop="notebook_num" label="笔记本数量" >
                </el-table-column>
                <el-table-column prop="cost" label="标注价格">
                </el-table-column>
                <el-table-column  label="操作">
                    <template slot-scope="scope">
                        <el-button size="small"
                                   type="warning" @click="deleteBookReceive(scope.$index)">
                            删除
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div style="text-align: center;">
                <el-button type="primary" @click="doSendBook" size="small">確 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<style>
    .el-table .success_table_bg{
        background: #f0f9eb;
    }
</style>

<script>
    import NewDialog from '../../tools/element-ui-dialog';
    export default {
        name: "teacher-receive",
        data:function () {
            return {
                showReceive:false,
                loading:false,
                editId:0,
                code:"",
                start:new Date(),
                books:[],
                books_received:[],
                dialog:NewDialog(this)
            }
        },
        watch:{
            showReceive(current, old){
                if(current === true) {
                    document.body.onkeydown = this.keyDown
                }else{
                    this.resetReceive();
                    document.body.onkeydown = null
                }
            }
        },
        methods:{
            teacherReceiveShow(editId){
                this.editId = editId;
                if(editId > 0) {
                    this.getTeacherReceived();
                }else {
                    this.showReceive = true;
                }
            },
            getTeacherReceived(){
                let that = this;
                axios.get('/teacher/received?uid='+this.editId) .then(function (response) {
                    that.books_received = response.data.response.list;
                    that.loading = true;
                    that.showReceive = true;
                }).catch(function (error) {

                });
            },
            keyDown(e) {
                let now = new Date();
                let tCode = parseInt(e.keyCode);
                if (now - this.start > 10000) {
                    this.start = now;
                    this.code  = String.fromCharCode(e.keyCode);
                } else {
                    if((tCode > 47 && tCode < 58) || (tCode > 64 && tCode < 91) || (tCode > 96 && tCode < 123)) {
                        //0-9 a-z A-Z
                        this.code += String.fromCharCode(e.keyCode);
                    }
                }
                if((tCode === 40 || tCode === 13) && this.code != "") {
                    this.getBookFromSn(this.code);
                    this.code = "";
                }
            },
            getBookFromSn(sn){
                let that = this;
                axios.get('/teacher/get/received?sn='+sn+'&uid='+this.editId) .then(function (response) {
                    //console.log(that.dialog);
                    if(response.data.code !== 0) {
                        if(response.data.code === 500302) {
                            that.dialog.openWarning(function () {
                            }, '此教材已经被领取');
                        }else {
                            that.dialog.openWarning(function () {
                            }, '没有获取到本书数据，请重试或查询教材是否存在');
                        }
                    }else{
                        if(response.data.response.data.length === 0) {
                            that.dialog.openWarning(function () {},'没有获取到本书数据，请重试或查询教材是否存在');
                        }else{
                            let book = response.data.response.data;
                            let exists = false;
                            that.books.forEach(function(item){
                               if(item.id === book.id) {
                                   that.dialog.openWarning(function () {},'教材已存在');
                                   exists = true;
                               }
                            });
                            if(!exists) {
                                that.books.push(response.data.response.data);
                            }
                        }
                    }
                }).catch(function (error) {
                    that.dialog.openWarning(function () {
                    }, '没有获取到本书数据，请重试或查询教材是否存在');
                });
            },
            teacherReceiveHide(){
                this.showReceive = false;

                this.resetReceive();
            },
            deleteBookReceive(index){
                this.books.splice(index,1);
            },
            resetReceive(){
                this.loading = false;
                this.books_received = [];
                this.editId = 0;
                this.books = [];
            },
            doSendBook(){
                let that = this;
                if(that.books.length === 0) {
                    that.dialog.openWarning(function () {},'请扫描教材');
                    return;
                }
                if(that.editId === 0) {
                    that.dialog.openWarning(function () {},'学生ID错误');
                    return;
                }
                this.dialog.openRefresh("是否执行书本出库",function () {
                    axios.post('/teacher/receive',{id:that.editId,books:that.books})
                        .then(function (response) {
                            if(response.data.code == 0) {
                               that.dialog.openSuccess(function () {
                                   that.teacherReceiveHide();
                               },'操作成功');
                            }else{
                                that.dialog.openDialog("warning",function () {
                                    that.teacherReceiveHide();
                                });
                            }
                        }).catch(function (error) {
                        that.dialog.openDialog("warning",function () {
                            that.teacherReceiveHide();
                        });
                    });
                });
            },
            tableRowClassName({row, rowIndex}) {
                return 'success_table_bg';
            }
        }
    }
</script>
