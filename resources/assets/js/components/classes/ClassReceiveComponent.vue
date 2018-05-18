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
        </el-dialog>
    </div>
</template>

<script>
    import NewDialog from '../../tools/element-ui-dialog';
    export default {
        name: "classes-receive",

        data:function () {
            return {
                showReceive:false,
                editId:0,
                received:[],
                noReceiver:[],
                nowReceiver:[],
                dialog:NewDialog(this)
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
                this.showReceive = true;
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
                    axios.post('receive/batch',{cid:that.editId,students:that.nowReceiver})
                        .then(function (response) {
                            if(response.data.code == 0) {
                                that.dialog.openSuccess(function () {
                                    that.ReceiveHide();
                                },'操作成功');
                            }else{
                                that.dialog.openDialog("warning",callback);
                            }
                        }).catch(function (error) {
                        that.dialog.openDialog("warning",callback);
                    });
                });
            }

        }
    }
</script>

<style scoped>

</style>