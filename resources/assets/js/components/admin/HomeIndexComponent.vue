<template>
    <div id="app">

        <el-row>
            <h1>使用说明</h1>
        </el-row>

        <el-row>
            <h3>1. 学生系统</h3>

            <p>首先进入班级，添加班级。然后进入学生系统用户管理，进行学生的添加。</p>

            <p>学生添加完毕之后，可以在班级列表中管理学生的缴费状态(已缴费的不可撤销)</p>

            <hr />

            <h3>2. 教师系统</h3>

            <p>教师系统的单独添加，包括姓名、编号、电话等</p>

            <h3>3. 书籍系统</h3>

            <p>书籍系统可以看成2个大部分   教材入库部分（书籍、教材计划、订书订单） 出库部分（学生、教师）</p>

            <p>所有第一部分的东西都在书本管理之中</p>

            <p>学生教材出库部分在学生列表选择执行，同一个教材计划同一个学生只能进行一次出库</p>

            <p>教师教材出库部分在教师列表选择执行，同一个教材计划同一个教师只能进行一次出库</p>
        </el-row>
        <!--<el-row>-->
            <!--<el-col :span="24">-->
                <!--<div class="app-search-bg">-->
                    <!--<el-button-group>-->
                        <!--<el-button type="primary" icon="plus" @click="uploadFocusIndexDialog = true">添加首页广告图</el-button>-->
                        <!--<el-button type="primary" icon="share">刷新</el-button>-->
                    <!--</el-button-group>-->
                <!--</div>-->
            <!--</el-col>-->
            <!--<el-col :span="24" style="margin-top: 20px;">-->
                <!--<el-table :data="tableData" stripe style="width: 100%">-->
                    <!--<el-table-column prop="img_src" label="缩略图">-->
                    <!--</el-table-column>-->
                    <!--<el-table-column prop="purpose" label="用途" width="280">-->
                    <!--</el-table-column>-->
                    <!--<el-table-column label="排序">-->
                        <!--<template slot-scope="scope">-->
                            <!--<el-dropdown trigger="click">-->
                <!--<span class="el-dropdown-link">-->
                  <!--{{scope.row.sort}}<i class="el-icon-caret-bottom el-icon&#45;&#45;right"></i>-->
                <!--</span>-->
                                <!--<el-dropdown-menu slot="dropdown">-->
                                    <!--<el-dropdown-item>1</el-dropdown-item>-->
                                    <!--<el-dropdown-item>2</el-dropdown-item>-->
                                    <!--<el-dropdown-item>3</el-dropdown-item>-->
                                    <!--<el-dropdown-item divided>随机</el-dropdown-item>-->
                                <!--</el-dropdown-menu>-->
                            <!--</el-dropdown>-->
                        <!--</template>-->
                    <!--</el-table-column>-->
                    <!--<el-table-column label="显示状态">-->
                        <!--<template slot-scope="scope">-->
                            <!--<el-button v-if="scope.row.state == true" size="small" @click="eventStateUpdate(scope.row, scope.$index)">显示</el-button>-->
                            <!--<el-button v-if="scope.row.state == false" size="small" type="danger" @click="eventStateUpdate(scope.row, scope.$index)">隐藏</el-button>-->
                        <!--</template>-->
                    <!--</el-table-column>-->

                    <!--<el-table-column label="操作">-->
                        <!--<template slot-scope="scope">-->
                            <!--<el-button size="small" icon="edit" @click="handleEdit(scope.row)">编辑</el-button>-->
                            <!--<el-button size="small" type="danger" icon="delete" @click="handleDelete(scope.row)">删除</el-button>-->
                        <!--</template>-->
                    <!--</el-table-column>-->
                <!--</el-table>-->
            <!--</el-col>-->
            <!--<el-col :span="24">-->
                <!--<div class="app-pagination">-->
                    <!--<el-pagination-->
                            <!--@size-change="handleSizeChange"-->
                            <!--@current-change="handleCurrentChange"-->
                            <!--:current-page="currentPage"-->
                            <!--:page-sizes="[100, 200, 300, 400]"-->
                            <!--:page-size="100"-->
                            <!--layout="total, sizes, prev, pager, next, jumper"-->
                            <!--:total="400">-->
                    <!--</el-pagination>-->
                <!--</div>-->
            <!--</el-col>-->
        <!--</el-row>-->
        <!--<el-dialog title="焦点图上传" :visible.sync="uploadFocusIndexDialog">-->
            <!--<div class="focus-upload-img">-->
                <!--<el-upload-->
                        <!--class="upload-demo"-->
                        <!--drag-->
                        <!--action="https://jsonplaceholder.typicode.com/posts/"-->
                        <!--multiple>-->
                    <!--<i class="el-icon-upload"></i>-->
                    <!--<div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>-->
                    <!--<div class="el-upload__tip" slot="tip">只能上传jpg/png文件，且不超过500kb</div>-->
                <!--</el-upload>-->
            <!--</div>-->
            <!--<div slot="footer" class="dialog-footer">-->
                <!--<el-button @click="uploadFocusIndexDialog = false">取 消</el-button>-->
                <!--<el-button type="primary" @click="uploadFocusIndexDialog = false">确 定</el-button>-->
            <!--</div>-->
        <!--</el-dialog>-->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                uploadFocusIndexDialog: false,
                searchContent: '',
                currentPage: 1,
                tableData: [{
                    img_src: '2017-9-8 10:00:00',
                    purpose: '首页焦点图片',
                    sort: 1,
                    state: true
                }, {
                    img_src: '2017-9-8 10:00:00',
                    purpose: '首页焦点图片',
                    sort: 2,
                    state: false
                }, {
                    img_src: '2017-9-8 10:00:00',
                    purpose: '首页焦点图片',
                    sort: 3,
                    state: true
                }, {
                    img_src: '2017-9-8 10:00:00',
                    purpose: '首页焦点图片',
                    sort: 4,
                    state: true
                }, {
                    img_src: '2017-9-8 10:00:00',
                    purpose: '首页焦点图片',
                    sort: 5,
                    state: true
                }]
            }
        },
        mounted: function() {
            this.$nextTick(function() {

            })
        },
        methods: {
            handleSizeChange() {

            },
            handleCurrentChange() {

            },
            searchContentClick(item) {

            },
            handleEdit(item) {

            },
            handleDelete(item) {
                this.$confirm('此操作将永久删除该信息, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$message({
                        type: 'success',
                        message: '删除成功!'
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            eventStateUpdate(item, index) {
                this.tableData[index].state = !this.tableData[index].state
            }
        }
    }
</script>

