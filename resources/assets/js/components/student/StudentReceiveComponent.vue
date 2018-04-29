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
                editId:0,
                code:"",
                start:new Date(),
                books:[]
            }
        },
        watch:{
            showReceive(current, old){
                if(current === true) {
                    document.body.onkeydown = this.keyDown
                }else{
                    document.body.onkeydown = null
                }
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
                axios.get('/book/get?sn='+sn) .then(function (response) {
                    this.books.push(response.data.response.data);
                }).catch(function (error) {

                });
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