const NewDialog = function (obj) {
    return {
        vue:obj,
        openSuccess(callback,message){
            if(typeof(message) == 'undefined') {
                message = '操作成功'
            }
            this.openDialog('success', callback, message);
        },
        openWarning(callback,message){
            if(typeof(message) == 'undefined') {
                message = '操作失敗，請檢查'
            }
            this.openDialog('warning', callback, message);
        },
        openDialog(type, callback, message) {
            this.vue.$message({
                type: type,
                message: message
            });
            if(typeof(callback) == 'function') {
                callback();
            }
        },
        openRefresh(message, callback) {
            let h = this.vue.$createElement;
            this.vue.$msgbox({
                title: '提示',
                message: h('p', null, [
                    h('span', null, message)
                ]),
                showCancelButton: true,
                confirmButtonText: '確定',
                cancelButtonText: '取消',
                beforeClose: (action, instance, done) => {
                    if (action === 'confirm') {
                        callback();
                        done();
                    } else {
                        done();
                    }
                },
            }).then(action => {

                //執行完畢
                //console.log(action);
            }).catch(e => {
                //執行異常
                //console.log(e)
            });
        }
    }
};

export default NewDialog;