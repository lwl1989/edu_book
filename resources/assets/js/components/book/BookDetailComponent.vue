<template>
    <div id="app">
        <el-form :model="book" :rules="rules" ref="book" label-width="150px" size="small">

            <el-form-item label="教材编号" prop="sn">
                <el-col :span="12">
                    <el-input v-model="book.sn" ></el-input>
                </el-col>
            </el-form-item>

            <el-form-item label="教材名字" prop="name">
                <el-col :span="16">
                    <el-input v-model="book.name" ></el-input>
                </el-col>
            </el-form-item>

            <el-form-item label="出版社" prop="company">
                <el-col :span="12">
                    <el-input v-model="book.company" ></el-input>
                </el-col>
            </el-form-item>

            <el-form-item label="作者" prop="author">
                <el-col :span="12">
                    <el-input v-model="book.author" ></el-input>
                </el-col>
            </el-form-item>

            <el-form-item label="原价" prop="cost">
                <el-col :span="12">
                    <el-input v-model="book.cost" ></el-input>
                </el-col>
            </el-form-item>


            <el-form-item label="目前库存" prop="stock">
                <el-col :span="6">
                    <el-input-number v-model="book.stock" ></el-input-number>
                </el-col>
            </el-form-item>


            <el-form-item label="预留库存" prop="stock">
                <el-col :span="6">
                    <el-input-number v-model="book.stock" ></el-input-number>
                </el-col>
                <el-col :span="4">
                    &nbsp;
                </el-col>
                <el-col :span="12">
                    <span>在出库时，库存必须大于这个值</span>
                </el-col>
            </el-form-item>

            <!--<el-form-item label="圖檔">-->
                <!--<el-upload-->
                        <!--action="/goods/upload/cover"-->
                        <!--:multiple=true-->
                        <!--:limit=6-->
                        <!--:headers="headers"-->
                        <!--accept="image/*"-->
                        <!--ref="upload"-->
                        <!--:on-preview="handlePreview"-->
                        <!--:on-remove="handleRemove"-->
                        <!--:file-list="goods.goods_cover"-->
                        <!--:on-success="handleSuccess"-->
                        <!--:on-change="handleFileChange"-->
                        <!--:auto-upload=false-->
                        <!--list-type="picture">-->
                    <!--<el-button size="small" type="primary">点击上传</el-button>-->
                    <!--<div slot="tip" class="el-upload__tip">-->
                        <!--圖檔限制尺寸必須爲（260*260），格式僅限[jpg、png、bmp]，最多上傳6張-->
                    <!--</div>-->
                <!--</el-upload>-->
            <!--</el-form-item>-->


        </el-form>

        <div slot="footer" style="text-align: center;">
            <el-row>
                <el-col :span="4">
                    &nbsp;
                </el-col>
                <el-col :span="8">
                    <el-button @click="goBack" size="small">返回列表</el-button>
                </el-col>
                <el-col :span="8">
                    <el-button type="primary" @click="doSubmit" size="small">確 定</el-button>
                </el-col>
                <el-col :span="4">
                    &nbsp;
                </el-col>
            </el-row>
        </div>

    </div>
</template>

<script>
    import {BookRule} from '../../tools/element-ui-validate';
    export default {
        name: "book-detail",
        data: function () {
            return {
                id: this.$route.params.id,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Content-Type': 'multipart/form-data'
                },
                book: {
                    // 'name', 'sn', 'author', 'company',
                    // 'price', 'cost', 'stock', 'reserve', 'mobile', 'email', 'permissions'
                    id: 0,
                    name: '',
                    sn: '',
                    author: '',
                    company: '',
                    cost: '',
                    stock: 0,
                    reserve: 0,
                },
                rules: BookRule,
                again:0
            }
        },
        created : function() {

        },
        mounted: function () {
            this.$nextTick(function() {
                if (this.id != 0) {
                    this.getBook();
                }
            });
        },
        methods: {
            openBookRefresh(message, callback) {
                let h = this.$createElement;
                this.$msgbox({
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
            },
            openBookSuccess(callback){
                this.$emit('success',callback);
            },
            openBookWarning(callback){
                this.$emit('warning',callback);
            },
            resetPage(){
                this.again = 0;
            },
            goBack(){
                this.closeWindow('直接返回将不会保存本页数据，是否返回？');
            },
            closeWindow(message){
                let that = this;
                this.$emit('refresh',message, function () {
                    that.$router.push({path:'/book'})
                });
            },
            doSubmit() {
                let h = this.$createElement;
                let that = this;
                // 表单验证方法
                this.$refs.book.validate(function (result) {
                    if(result){
                        //验证通过,调用module里的setUserInfo方法
                        //this.$store.dispatch("setUserInfo");
                        this.$msgbox({
                            title: '提示',
                            message: h('p', null, [
                                h('span', null,  '确定提交书名为 '),
                                h('i', { style: 'color: teal' }, that.book.name),
                                h('span', null, '的数据嗎？')
                            ]),
                            showCancelButton: true,
                            confirmButtonText: '确定',
                            cancelButtonText: '取消',
                            beforeClose: (action, instance, done) => {
                                let callback = function(){
                                    instance.confirmButtonLoading = false;
                                    instance.confirmButtonText = '确定';
                                    done();
                                };
                                if (action === 'confirm') {
                                    instance.confirmButtonLoading = true;
                                    instance.confirmButtonText = '添加中...';

                                    let url = that.id == 0 ? '/book/create' : '/book/update';

                                    axios.post(url,that.book)
                                        .then(function (response) {
                                            if(response.data.code == 0) {
                                                that.openBookSuccess(callback)
                                            }else{
                                                that.openBookWarning(callback);
                                            }
                                        }).catch(function (error) {
                                        that.openBookWarning(callback);
                                    });
                                } else {
                                    that.openBookWarning(callback);
                                }
                            }
                        });
                    }else{
                        console.log(result)
                    }
                }.bind(this));
            },
            getBook() {
                let that = this;
                axios.get('/book/get?id=' + this.id).then(function (response) {
                    if (response.data.code == 0) {
                        that.book = response.data.response.data
                    } else {
                        that.closeWindow('未獲取到商品數據,是否關閉頁面？');
                    }
                }).catch(function (error) {
                    that.openRefresh('網絡不穩定，是否重試？', function () {
                        window.location.reload(true)
                    });
                });
            },
            openBookDetailRefresh(message, callback) {
                let h = this.$createElement;
                this.$msgbox({
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
            },
            // handleSuccess(res, file) {
            //
            // },
            // handleRemove(file, fileList) {
            //     //console.log(file, fileList);
            // },
            // handleError(err, file, fileList) {
            //     console.log(file, fileList)
            //     console.log(err)
            // },
            // handlePreview(file) {
            //     //console.log(file)
            //     //console.log(file);
            // },
            // createReader(file, error, success) {
            //     let reader = new FileReader;
            //     let that = this;
            //     reader.onload = function (evt) {
            //         let image = new Image();
            //         image.onload = function () {
            //             let imageType = ['image/jpeg', 'image/png', 'image/jpg'];
            //             if (imageType.indexOf(file.type) < 0) {
            //                 error('上傳的文件是不正確的文件類型[image/jpg|image/jpeg|image/png]');
            //                 return;
            //             }
            //
            //             if (file.size / 1024 / 1024 > 2) {
            //                 error('上傳的圖片大小不能超過 2MB!');
            //                 return;
            //             }
            //             let width = parseInt(this.width);
            //             let height = parseInt(this.height);
            //
            //             if (width != that.except_width || height != that.except_height) {
            //                 error('上傳圖片尺寸不正確（280x280）');
            //             } else {
            //                 success();
            //             }
            //         };
            //         image.src = evt.target.result;
            //     };
            //     reader.readAsDataURL(file);
            // },
            // handleFileChange(file, files) {
            //     if (!("checked" in file)) {
            //         let that = this;
            //         this.createReader(file.raw, function (message) {
            //             that.$message.error(message);
            //             files.splice(-1);
            //         }, function () {
            //             file.checked = true;
            //             that.$refs.upload.submit();
            //         });
            //     }
            // }
        }

    }
</script>

<style scoped>
    .el-form{
        width: 70%;
        margin: 0 auto;
    }
</style>