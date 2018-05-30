<template>
    <div id="app">

        <el-dialog  :visible.sync="showReceive">
            <el-button type="text" icon="el-icon-location">領取列表（已領書的默认勾选）</el-button>
            <el-checkbox-group v-model="nowReceiver">
                    <el-checkbox v-for="c in noReceiver" :label="c.id" :key="c.id" :value="c.id" >
                    {{c.name}}
                    </el-checkbox>
                    <el-checkbox v-for="c in received" :label="c.id" :key="c.id" :value="c.id" disabled>
                        {{c.name}}
                    </el-checkbox>
            </el-checkbox-group>

            <el-button type="primary" @click="doSendReceive" size="small">确认</el-button>

            <el-row>
                &nbsp;
            </el-row>

            <el-button type="text" icon="el-icon-success">已领教材</el-button>

            <el-table :data="books_received" stripe style="width: 100%" v-loading="loading" >
                <el-table-column prop="sn" label="教材sn" >
                </el-table-column>
                <el-table-column prop="name" label="教材名称" >
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

<script>
    import NewDialog from '../../tools/element-ui-dialog';
    export default {
        name: "classes-receive",

        data:function () {
            return {
                loading:false,
                showReceive:false,
                editId:0,
                received:[],
                noReceiver:[],
                nowReceiver:[],
                dialog:NewDialog(this),
                start:new Date(),
                books:[],
                books_received:[],
                code:"",
            }
        },
        watch:{
            showReceive(current, old){
                if(current === false) {
                    this.resetReceive();
                }
            }
        },
        methods:{
            ClassReceive(editId){
                this.editId = editId;
                this.loadReceive();
                this.loadReceiveBook();

            },
            loadReceiveBook(){
                let that = this;
                axios.get('/classes/book/receive?cid='+this.editId) .then(function (response) {
                    that.books_received = response.data.response.list;
                    console.log(that.books_received);
                    that.showReceive = true;
                    that.loading = false;
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
                if((tCode === 40 || tCode === 13) && this.code !== "") {
                    this.getBookFromSn(this.code);
                    this.code = "";
                }
            },
            getBookFromSn(sn){
                let that = this;
                axios.get('/student/get/received?sn='+sn+'&cid='+this.editId) .then(function (response) {
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
                                if(item.sn === book.sn) {
                                    that.dialog.openWarning(function () {},'教材已存在');
                                    exists = true;
                                }
                            });
                            that.books_received.forEach(function(item){
                                if(item.sn === book.sn) {
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
            deleteBookReceive(index){
                this.books.splice(index,1);
            },
            doSendBook(){
                let that = this;
                if(that.books.length === 0) {
                    that.dialog.openWarning(function () {},'请扫描教材');
                    return;
                }
                if(that.editId === 0) {
                    that.dialog.openWarning(function () {},'班级ID错误');
                    return;
                }
                this.dialog.openRefresh("是否执行书本出库",function () {
                    axios.post('/student/receive',{id:that.editId,books:that.books})
                        .then(function (response) {
                            if(response.data.code === 0) {
                                that.dialog.openSuccess(function () {
                                    that.studentReceiveHide();
                                },'操作成功');
                            }else{
                                that.dialog.openDialog("warning",function () {
                                    that.studentReceiveHide();
                                });
                            }
                        }).catch(function (error) {
                        that.dialog.openDialog("warning",function () {
                            that.studentReceiveHide();
                        });
                    });
                });
            },
            loadReceive(){
                let that = this;
                axios.get('/classes/receive?cid='+this.editId) .then(function (response) {
                    let Receive = response.data.response.list;
                    Receive.forEach(function (item) {
                       if(item.receive === true) {
                           that.received.push(item);
                           that.nowReceiver.push(item.id);
                       }else{
                           that.noReceiver.push(item);
                       }

                    });
                    that.showReceive = true;
                }).catch(function (error) {

                });
            },
            ReceiveHide(){
                this.showReceive = false;
                this.resetReceive();
            },
            resetReceive(){
                this.loading = false;
                this.received = [];
                this.editId = 0;
                this.noReceiver = [];
                this.nowReceiver = [];
            },
            doSendReceive(){
                let that = this;

                this.dialog.openRefresh("请仔细确认学生名单",function () {
                    axios.post('/student/receive/batch',{cid:that.editId,students:that.nowReceiver})
                        .then(function (response) {
                            if(response.data.code == 0) {
                                that.dialog.openSuccess(function () {
                                    that.ReceiveHide();
                                },'操作成功');
                            }else{
                                that.dialog.openDialog("warning",function () {

                                });
                            }
                        }).catch(function (error) {
                        that.dialog.openDialog("warning",function () {

                        });
                    });
                });
            }

        }
    }
</script>

<style scoped>

</style>