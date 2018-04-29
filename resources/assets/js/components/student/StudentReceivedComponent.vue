<template>
    <div id="app">
        <el-dialog  :visible.sync="showReceive">
            received
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "student-received",

        data:function () {
            return {
                showReceive:false,
                editId:0,
                books:[]
            }
        },
        methods:{
            studentReceiveShow(editId){
                this.editId = editId;
                if(editId > 0) {
                    //get
                }
                this.showReceive = true;
            },

        }
    }
</script>

<style scoped>

</style>