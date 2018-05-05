<template>
    <div id="app">
        records
    </div>
</template>

<script>
    export default {
        name: "BookReceive",
        data:function(){
            return {
                code : "",
                books: [],
                showReceive: false
            }
        },
        watch:{
            showReceive(current, old){
                console.log(current,old);
            }
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
    }
</script>

<style scoped>

</style>