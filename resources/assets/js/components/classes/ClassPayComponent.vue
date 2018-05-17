<template>
    <div id="app">

        <el-dialog  :visible.sync="showPay">
            <el-button type="text" icon="el-icon-location">領取列表（已領書的默认勾选）</el-button>
            <el-checkbox-group v-model="nowPay">
                    <el-checkbox v-for="c in noPay" :label="c.id" :key="c.id" :value="c.id" >
                    {{c.name}}
                    </el-checkbox>
                    <el-checkbox v-for="c in payed" :label="c.id" :key="c.id" :value="c.id" disabled>
                        {{c.name}}
                    </el-checkbox>
            </el-checkbox-group>

            <el-button type="primary" @click="doSendPay" size="small">确认</el-button>
        </el-dialog>
    </div>
</template>

<script>
    import NewDialog from '../../tools/element-ui-dialog';
    export default {
        name: "classes-pay",

        data:function () {
            return {
                showPay:false,
                editId:0,
                payed:[],
                noPay:[],
                nowPay:[],
                dialog:NewDialog(this)
            }
        },
        watch:{
            showPay(current, old){
                if(current === false) {
                    this.resetPay();
                }
            }
        },
        methods:{
            ClassPay(editId){
                this.editId = editId;
                this.loadPay();
                this.showPay = true;
            },
            loadPay(){
                let that = this;
                axios.get('/classes/receive?cid='+this.editId) .then(function (response) {
                    let pay = response.data.response.list;
                    pay.forEach(function (item) {
                       if(item.payed != null) {
                           that.payed.push(item);
                           that.nowPay.push(item.id);
                       }else{
                           that.noPay.push(item);
                       }

                    });
                    that.showPay = true;
                }).catch(function (error) {

                });
            },
            payHide(){
                this.showPay = false;
                this.resetPay();
            },
            resetPay(){
                this.loading = false;
                this.payed = [];
                this.editId = 0;
                this.noPay = [];
                this.nowPay = [];
            },
            doSendPay(){
                let that = this;
                this.dialog.openRefresh("请仔细确认是否缴费完成",function () {
                    axios.post('/student/received',{cid:that.editId,students:that.nowPay})
                        .then(function (response) {
                            if(response.data.code == 0) {
                                that.dialog.openSuccess(function () {
                                    that.payHide();
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