<template>
    <div id="app">
        <el-dialog  :visible.sync="dialogVisible">
            <el-form :model="plan" :rules="rules" ref="plan" label-width="150px" size="small">


                <el-form-item label="预购数量" prop="number">
                    <el-col :span="16">
                        <el-input v-model="plan.number" ></el-input>
                    </el-col>
                </el-form-item>

                <el-form-item label="笔记本数量" prop="note_book_num">
                    <el-col :span="12">
                        <el-input v-model="plan.note_book_num" ></el-input>
                    </el-col>
                </el-form-item>

                <el-form-item label="计划使用年份" prop="plan_year">
                    <el-col :span="12">
                        <el-input v-model="plan.plan_year" ></el-input>
                    </el-col>
                </el-form-item>

                <el-form-item label="全年/上/下学期" prop="up_down">
                    <el-select v-model="plan.up_down">
                        <el-option key="0" label="全年" value="0"></el-option>
                        <el-option key="1" label="上学期" value="1"></el-option>
                        <el-option key="2" label="下学期" value="2"></el-option>
                    </el-select>
                </el-form-item>

            </el-form>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "student-detail",
        data:function () {
            return {
                plan:{
                    id:0,
                    book_id:"",
                    number:0,
                    note_book_num:0,
                    classes:[
                        1
                    ],
                    plan_year:0,
                    up_down:"0"
                },
                rules:{},
                //dialogVisible:true
            }
        },
        prop:['dialogVisible']
    }
</script>

<style scoped>

</style>