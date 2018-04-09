const adminRule = {
        account: [{ required: true, message: '賬戶不能爲空'}, { min: 6, max: 30, message: '長度需要在 6 到 30 個字符'}], //, trigger: 'blur'
            role: [{ required: true, message: '角色必須選擇'}],
            status: [{ required: true, message: '狀態必須選擇'}],
            department_id: [{ required: true, message: '部門必須選擇'}],
            name: [{ required: false}, { min: 0, max: 20, message: '姓名長度不能超過 20 個字符', trigger: 'blur'}],
            alias:[{ required: false},{ min: 0, max: 20, message: '別名長度不能超過 20 個字符', trigger: 'blur'}],
            mobile:[{ required: false},{ min: 0, max: 20, message: '行動電話長度不能超過 20 個字符', trigger: 'blur'}],
            tel:[{ required: false, message: '電話不能爲空'},{ min: 0, max: 30, message: '別名長度不能超過 30 個字符', trigger: 'blur'}],
            tel_ext:[{ required: false},{ min: 0, max: 20, message: '分機長度不能超過 20 個字符', trigger: 'blur'}],
            email: [{ required: true, message: '郵箱不能爲空'}, { type: 'email', message: '請輸入正確的email地址'}],
            permissions:[{required:true,message:'請選擇權限'}]
};
const adminSearchRule = {
        name: [{ required: false}, { min: 0, max: 20, message: '姓名長度不能超過 20 個字符', trigger: 'blur'}],
        account: [{ required: false}, { min: 6, max: 30, message: '長度需要在 6 到 30 個字符', trigger: 'blur'}],
        mobile:[{ required: false},{ min: 0, max: 20, message: '行動電話長度不能超過 20 個字符', trigger: 'blur'}],
        email: [{ required: false}, { type: 'email', message: '請輸入正確的email地址', trigger: 'blur'}],
};
export let AdminRule = adminRule;
export let AdminSearchRule = adminRule;