<template>
    <div id="app">
        <el-row>
            <el-col :span="24">
                <div class="app-search-bg">
                </div>
                <div style="padding-top:5px;">
                    <el-col :span="4">
                        <el-button-group>
                            <el-button type="primary" class="el-icon-circle-plus" @click="addAdmin">Add</el-button>
                            <el-button type="primary" class="el-icon-remove" @click="deleteAdmin">Delete</el-button>
                        </el-button-group>
                    </el-col>
                    <el-col :span="4"></el-col>
                    <el-col :span="16">
                    <el-input class="app-search" placeholder="请输入查询手机号" v-model="searchContent" >
                    </el-input>
                    <el-button type="primary" icon="el-icon-search" @click="searchContentClick">搜索</el-button>
                    </el-col>
                    <!--suffix-icon="el-icon-search"-->
                </div>
            </el-col>
            <el-col :span="24" style="margin-top: 20px;">
                <el-table :data="admin" stripe style="width: 100%">
                    <el-table-column type="selection">
                    </el-table-column>
                    <el-table-column
                            type="index"
                            width="50">
                    </el-table-column>
                    <el-table-column prop="account" label="賬戶">
                    </el-table-column>
                    <el-table-column prop="name" label="姓名">
                    </el-table-column>
                    <el-table-column prop="mobile" label="手機號">
                    </el-table-column>
                    <el-table-column prop="create_time" label="創建時間">
                    </el-table-column>
                    <el-table-column prop="update_time" label="異動時間">
                    </el-table-column>
                    <el-table-column label="查看">
                        <template slot-scope="scope">
                            <el-button size="small" @click="userListEntryRecord = true">備注</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-col>
            <el-col :span="24">
                <div class="app-pagination">
                    <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"  :page-size="pageSize" layout="total, prev, pager, next, jumper" :total="total">
                    </el-pagination>
                </div>
            </el-col>
        </el-row>

        <!-- Form -->
        <el-dialog title="添加管理員" :visible.sync="adminFormVisible">
            <el-form :model="adminForm" :rules="rules" ref="adminForm">
                <el-form-item label="賬號" :label-width="formLabelWidth"  prop="account">
                    <el-input v-model="adminForm.account" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="狀態" :label-width="formLabelWidth" prop="status">
                    <el-select v-model="adminForm.status" placeholder="啓用">
                        <el-option label="新申請，待審核" value="0"></el-option>
                        <el-option label="啓用" value="1"></el-option>
                        <el-option label="停用" value="2"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="角色" :label-width="formLabelWidth" prop="role">
                    <el-select v-model="adminForm.role" placeholder="縣府員工">
                        <el-option label="縣府員工" value="2"></el-option>
                        <el-option label="推廣團隊" value="3"></el-option>
                        <el-option label="管理員" value="1"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="姓名" :label-width="formLabelWidth" prop="name">
                    <el-input v-model="adminForm.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="別名" :label-width="formLabelWidth" prop="alias">
                    <el-input v-model="adminForm.alias" auto-complete="off"></el-input>
                </el-form-item>

                <el-form-item label="業務單位" :label-width="formLabelWidth" prop="department_id">
                    <el-select v-model="adminForm.department_id" placeholder="请选择活动区域">
                        <el-option label="区域一" value="1"></el-option>
                        <el-option label="区域二" value="2"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="電話" :label-width="formLabelWidth" prop="tel">
                    <el-col :span="9">
                        <el-input v-model="adminForm.tel" auto-complete="off"></el-input>
                    </el-col>
                    <el-col class="line" :span="3" :offset="3" prop="tel_ext">分機</el-col>
                    <el-col :span="9">
                        <el-input v-model="adminForm.tel_ext" auto-complete="off"></el-input>
                    </el-col>
                </el-form-item>

                <el-form-item label="行動電話" :label-width="formLabelWidth" prop="mobile">
                    <el-input v-model="adminForm.mobile" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="電子郵件" :label-width="formLabelWidth" prop="email">
                    <el-input v-model="adminForm.email" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="可使用功能" :label-width="formLabelWidth" prop="permissions">
                    <el-checkbox-group v-model="adminForm.permissions" auto-complete="off">
                        <template v-for="(item,index) in $router.options.routes" v-if="!item.hidden">
                            <el-row>
                                {{index}}. {{item.name}}
                            </el-row>
                            <el-row>
                                <el-col  v-for="(perm,key) in item.children" :index="perm.path" :key="perm.path" v-if="!perm.hidden" :span="4">
                                    <el-checkbox :label="perm.name" :value="perm.path"></el-checkbox>
                                </el-col>
                            </el-row>
                        </template>
                    </el-checkbox-group>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="adminFormVisible = false">取 消</el-button>
                <!--<el-button type="primary" @click="">確 定</el-button>-->
                <el-button type="primary" @click="submitAdmin">確 定</el-button>
            </div>
        </el-dialog>

    </div>
</template>

<script>
    export default {
        name: "adminIndexComponent",
        data() {
            return {
                searchContent: '',
                userListEntryRecord: false,
                userListUserInfo: false,
                currentPage: 1,
                pageSize: 5,
                total: 1,
                admin: [],
                adminFormVisible: false,
                formLabelWidth: '120px',
                adminForm:{
                    account:'',
                    role: '2',
                    status: '1',
                    name: '',
                    alias:'',
                    department_id:'',
                    tel:'',
                    tel_ext:'',
                    mobile:'',
                    email:'',
                    permissions: [],
                },
                rules: {
                    account: [{ required: true, message: '賬戶不能爲空'}, { min: 6, max: 30, message: '長度需要在 6 到 30 個字符'}], //, trigger: 'blur'
                    role: [{ required: true, message: '角色必須選擇'}],
                    status: [{ required: true, message: '狀態必須選擇'}],
                    department_id: [{ required: true, message: '部門必須選擇'}],
                    name: [{ required: false}, { min: 0, max: 20, message: '姓名長度不能超過 20 個字符', trigger: 'blur'}],
                    alias:[{ required: false},{ min: 0, max: 20, message: '別名長度不能超過 20 個字符', trigger: 'blur'}],
                    mobile:[{ required: false},{ min: 0, max: 20, message: '行動電話長度不能超過 20 個字符', trigger: 'blur'}],
                    tel:[{ required: false, message: '電話不能爲空'},{ min: 0, max: 30, message: '別名長度不能超過 30 個字符', trigger: 'blur'}],
                    tel_ext:[{ required: false},{ min: 0, max: 20, message: '分機長度不能超過 20 個字符', trigger: 'blur'}],
                    email: [{ required: true, message: '郵箱不能爲空'}, { min: 6, max: 30, message: '長度需要在 6 到 30 個字符'}],
                    permissions:[{required:true,message:'請選擇權限'}]
                }
            }
        },
        created:function () {
        },
        mounted: function() {
            this.$nextTick(function() {
                this.getMaxPage();
                this.handleCurrentChange(1);
            })
        },
        methods: {
            submitAdmin() {
                const h = this.$createElement;
                let that = this;
                // 表单验证方法
                this.$refs.adminForm.validate(function (result) {
                    if(result){
                        //验证通过,调用module里的setUserInfo方法
                        //this.$store.dispatch("setUserInfo");
                        this.$msgbox({
                            title: '提示',
                            message: h('p', null, [
                                h('span', null, '確定要創建賬戶 '),
                                h('i', { style: 'color: teal' }, that.adminForm.account),
                                h('span', null, '嗎？')
                            ]),
                            showCancelButton: true,
                            confirmButtonText: '確定',
                            cancelButtonText: '取消',
                            beforeClose: (action, instance, done) => {
                                if (action === 'confirm') {
                                    instance.confirmButtonLoading = true;
                                    instance.confirmButtonText = '創建中...';
                                    axios.post('/api/admin/create',this.adminForm)
                                        .then(function (response) {
                                            if(response.data.code == 0) {
                                                that.$refs.adminForm.resetFields();
                                                that.total += 1;
                                                if(Math.ceil(that.admin.length / that.pageSize) == that.currentPage) {
                                                    that.admin.id = response.data.response.id;
                                                    that.adminForm.create_time = response.data.response.create_time;
                                                    that.adminForm.update_time = response.data.response.update_time;
                                                    that.admin.push(that.adminForm);
                                                }
                                                that.adminFormVisible = false;
                                                that.openSuccess(done)
                                            }else{
                                                that.openWarning(done)
                                            }
                                        }).catch(function (error) {
                                            that.openWarning(done);
                                    });
                                } else {
                                    that.openWarning(done);
                                }
                            }
                        });
                    }else{
                        console.log(result)
                    }
                }.bind(this));
            },
            openSuccess(callback){
                this.$message({
                    type: 'success',
                    message: '操作成功'
                });
                callback();
            },
            openWarning(){
                this.$message({
                    type: 'warning',
                    message: '操作失敗，請重試'
                });
            },
            getMaxPage() {
                let that = this;
                axios.get('/api/admin/count')
                    .then(function (response) {
                        that.total = response.data.response.count;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            handleSizeChange() {

            },
            handleCurrentChange(currentPage) {
                let that = this;
                axios.get('/api/admin/select?page='+currentPage+'&limit='+that.pageSize) .then(function (response) {
                    that.admin = response.data.response.admin
                }).catch(function (error) {

                });
            },
            searchContentClick(item) {
                console.log(item)

            },
            handleEdit(item) {

            },
            handleDelete(item) {

            },
            eventStateUpdate(item) {

            },
            addAdmin() {
                this.adminFormVisible = true
            },
            deleteAdmin() {

            }
            // exportExcel() {
            //     let fileName = '用户列表'
            //     let sheetFilter = ['phone', 'start', 'stop', 'state']
            //     let sheetHeader = ['手机号', '进店时间', '离店时间', '状态']
            //     let excelContent = this.tableData
            //     this.jsonToExcel(fileName, sheetFilter, sheetHeader, excelContent)
            // }
        }
    }
</script>

<style scoped>
    .el-checkbox-group{
        font-size: 12px;
    }
</style>