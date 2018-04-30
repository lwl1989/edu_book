<template>
    <div id="app">
        <el-dialog  :visible.sync="showPay">
            received
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
                pay:[]
            }
        },
        methods:{
            ClassPay(editId){
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