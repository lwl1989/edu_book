<template>
    <div id="app">
        <student-detail-component ></student-detail-component>
        <student-receive-component></student-receive-component>
    </div>
</template>

<script>
    import StudentReceiveComponent from '../student/StudentReceiveComponent';
    import StudentDetailComponent from '../student/StudentDetailComponent';
    // Vue.component('teacher-component',TeacherComponent,{
    //     data: function () {
    //         return {
    //             'name':'123'
    //         }
    //     }
    // });
    export default {
        name: "StudentComponent",
        data:function () {
            return {
                openDetail:true
            }
        },
        components:{ StudentDetailComponent,StudentReceiveComponent},
    }
</script>

<style scoped>

</style>