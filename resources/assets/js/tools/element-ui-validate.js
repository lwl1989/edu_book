//自然數判斷 >= 0
const naturalNumberValidator = function (rule,value,callback) {
    let reg = /^[0-9]+.?[0-9]*$/;
    if(reg.test(value)) {
        let v = parseInt(value);
        if(v < 0) {
            callback('不能輸入非自然數[小於0的數字]');
        }else{
            callback();
        }
    }else{
        callback('輸入的不是合法的數字');
    }
};
//是否爲數字判斷
const doubleValidator = function (rule,value,callback) {
    let reg = /^[0-9]+.?[0-9]*$/;
    if(reg.test(value)) {
        callback();
    }else{
        callback('輸入的不是合法的數字');
    }
};
//關鍵字判斷
const keywordValidator = function(rule,value,callback){
    let n=(value.split(',')).length;

    if(n>10) {
        callback(new Error("關鍵字不能超過10個"));
        return;
    }
    callback();

};
const adminSearchRule = {
        name: [{ required: false}, { min: 0, max: 20, message: '姓名長度不能超過 20 個字符', trigger: 'blur'}],
        account: [{ required: false}, { min: 6, max: 30, message: '長度需要在 6 到 30 個字符', trigger: 'blur'}],
       // mobile:[{ required: false},{ min: 0, max: 20, message: '行動電話長度不能超過 20 個字符', trigger: 'blur'}],
       // email: [{ required: false}, { type: 'email', message: '請輸入正確的email地址', trigger: 'blur'}],
};
const studentRule = {
    student_num: [{ required: true, message: '学生学号不能为空'}, { min: 6, max: 30, message: '长度需要在 6 到 30 个字符'}], //, trigger: 'blur'
    name: [{ required: true,message:'学生姓名不能为空'}, { min: 0, max: 20, message: '姓名长度不能超过 20 个字符'}],
    class_id :[ {required: true,message:'必须选择班级'}],
   // password:[{ required: true}, {  min: 6, max: 30,  message: '长度需要在 6 到 30 个字符'}],
};
const bookRule = {
    name: [{ required: true, message:'教材名称必须填写'}, { min: 0, max: 50, message: '教材名称长度不能超过 50 个字符'}],
    sn:[{ required: true, message: '教材sn必须填写'},{ min: 0, max: 50, message: 'sn长度不能超过 50 个字符'}],
    // price:[{require:false,type:'number', message:'必须填写正确的数字',trigger: 'blur'}],
    cost:[{require:true,validator:naturalNumberValidator, message:'必须填写正确的数字'}],
    stock:[{require:true,type:'number', message:'必须填写正确的数字'}],
    reserve:[{require:false,type:'number', message:'必须填写正确的数字'}],
};
const bookOrderRule = {
    plan_id: [{ required: true, message:'订书计划必须选择'}],
    number:[{require:true,validator:naturalNumberValidator, message:'必须填写正确的数字'}],
    price:[{require:true,type:'number', message:'必须填写正确的数字'}],
};
const bookPlanRule = {
    name: [{ required: true, message:'教材名称必须填写'}, { min: 0, max: 50, message: '教材名称长度不能超过 50 个字符'}],
    sn:[{ required: true, message: '教材sn必须填写'},{ min: 0, max: 50, message: 'sn长度不能超过 50 个字符'}],
    // price:[{require:false,type:'number', message:'必须填写正确的数字',trigger: 'blur'}],
    cost:[{require:true,validator:naturalNumberValidator, message:'必须填写正确的数字'}],
    stock:[{require:true,type:'number', message:'必须填写正确的数字'}],
    reserve:[{require:false,type:'number', message:'必须填写正确的数字'}],
};
const teacherRule = {
    student_num: [{ required: true, message: '教师编号不能为空'}, { min: 6, max: 30, message: '长度需要在 6 到 30 个字符'}], //, trigger: 'blur'
    name: [{ required: true}, { min: 0, max: 20, message: '姓名长度不能超过 20 个字符'}],
    password:[{ required: true}, {  min: 6, max: 30,  message: '长度需要在 6 到 30 个字符'}],
};
const adminRule = {
    account: [{ required: true, message: '帐户名不能为空'}, { min: 6, max: 30, message: '长度需要在 6 到 30 个字符'}], //, trigger: 'blur'
    name: [{ required: true}, { min: 0, max: 20, message: '姓名长度不能超过 20 个字符'}],
    password:[{ required: true}, {  min: 6, max: 30,  message: '长度需要在 6 到 30 个字符'}],
};
const classRule = {
    name: [{ required: true, message:'名称必须填写'}, { min: 0, max: 50, message: '名称长度不能超过 50 个字符'}],
};
export let ClassRule = classRule;
export let BookRule = bookRule;
export let BookPlanRule = bookPlanRule;
export let BookOrderRule = bookOrderRule;
export let AdminRule = adminRule;
export let AdminSearchRule = adminSearchRule;
export let StudentRule = studentRule;
export let TeacherRule = teacherRule;