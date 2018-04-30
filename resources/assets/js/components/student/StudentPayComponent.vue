<template>
    <div id="app">
        <el-dialog  :visible.sync="showPay">
            received
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "student-received",

        data:function () {
            return {
                showPay:false,
                editId:0,
                pay:[]
            }
        },
        methods:{
            studentReceiveShow(editId){
                this.editId = editId;
                if(editId > 0) {
                    //get
                }
                this.showPay = true;
            },

        }
    }
</script>

<style scoped>

</style>