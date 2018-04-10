// const adminRule = {
//         account: [{ required: true, message: '賬戶不能爲空'}, { min: 6, max: 30, message: '長度需要在 6 到 30 個字符'}], //, trigger: 'blur'
//             role: [{ required: true, message: '角色必須選擇'}],
//             status: [{ required: true, message: '狀態必須選擇'}],
//             department_id: [{ required: true, message: '部門必須選擇'}],
//             name: [{ required: false}, { min: 0, max: 20, message: '姓名長度不能超過 20 個字符', trigger: 'blur'}],
//             alias:[{ required: false},{ min: 0, max: 20, message: '別名長度不能超過 20 個字符', trigger: 'blur'}],
//             mobile:[{ required: false},{ min: 0, max: 20, message: '行動電話長度不能超過 20 個字符', trigger: 'blur'}],
//             tel:[{ required: false, message: '電話不能爲空'},{ min: 0, max: 30, message: '別名長度不能超過 30 個字符', trigger: 'blur'}],
//             tel_ext:[{ required: false},{ min: 0, max: 20, message: '分機長度不能超過 20 個字符', trigger: 'blur'}],
//             email: [{ required: true, message: '郵箱不能爲空'}, { type: 'email', message: '請輸入正確的email地址'}],
//             permissions:[{required:true,message:'請選擇權限'}]
// };
const adminSearchRule = {
        name: [{ required: false}, { min: 0, max: 20, message: '姓名長度不能超過 20 個字符', trigger: 'blur'}],
        account: [{ required: false}, { min: 6, max: 30, message: '長度需要在 6 到 30 個字符', trigger: 'blur'}],
       // mobile:[{ required: false},{ min: 0, max: 20, message: '行動電話長度不能超過 20 個字符', trigger: 'blur'}],
       // email: [{ required: false}, { type: 'email', message: '請輸入正確的email地址', trigger: 'blur'}],
};
const studentRule = {
    student_num: [{ required: true, message: '学生学号不能为空'}, { min: 6, max: 30, message: '长度需要在 6 到 30 个字符'}], //, trigger: 'blur'
    name: [{ required: true}, { min: 0, max: 20, message: '姓名长度不能超过 20 个字符'}],
    class_id :[ {required: true}, {message:'学生班级必须进行选择'}],
    password:[{ required: true}, {  min: 6, max: 30,  message: '长度需要在 6 到 30 个字符'}],
};
const bookRule = {
    name: [{ required: true}, { min: 0, max: 50, message: '书籍名称长度不能超过 50 个字符'}],
    sn:[{ required: true}, {  message: '书籍sn必须填写'}],
    price:[{require:false},{type:'number', message:'必须填写正确的数字',trigger: 'blur'}],
    cost:[{require:false},{type:'number', message:'必须填写正确的数字',trigger: 'blur'}],
    stock:[{require:false},{type:'number', message:'必须填写正确的数字',trigger: 'blur'}],
    reserve:[{require:false},{type:'number', message:'必须填写正确的数字',trigger: 'blur'}],
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
export let AdminRule = adminRule;
export let AdminSearchRule = adminSearchRule;
export let BookRule = bookRule;
export let StudentRule = studentRule;
export let TeacherRule = teacherRule;