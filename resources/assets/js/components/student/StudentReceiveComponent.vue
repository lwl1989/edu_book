<template>
    <div id="app">
        <el-dialog  :visible.sync="showReceive">
            receives
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "student-receive",

        data:function () {
            return {
                receives:[],
                rules:{},
                showReceive:false,
                editId:0
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
            studentReceiveHide(){
                this.showReceive = false;
                this.resetReceive();
            },
            resetReceive(){
                this.student = {
                    id:0,
                    book_id:"",
                    number:0,
                    note_book_num:0,
                    classes:[
                        1
                    ],
                    plan_year:0,
                    up_down:"0"
                };
            }
        }
    }
</script>

<style scoped>

</style>