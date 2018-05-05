<template>
    <div id="app">
        app
    </div>
</template>

<script>
    export default {
        name: "BookReceivedComponent",
        data:function(){
          return {
              data:[],
              type: this.$route.params.type,
              currentPage: 1,
              pageSize: 15,
              total: 1,
              loading: true
          }
        },
        mounted: function() {
            this.$nextTick(function() {
                this.getReceivedMaxPage();
                this.handlePlanCurrentChange(1);
                this.loading = false;
            })
        },
        methods:{
            getReceivedMaxPage() {
                let that = this;
                axios.get('/book/plan/count')
                    .then(function (response) {
                        that.total = response.data.response.count;
                    })
                    .catch(function (error) {
                        that.$emit('refresh',function () {
                            window.location.reload(true)
                        },'網絡不穩定，是否重試？');
                    });
            },

            handlePlanCurrentChange(currentPage) {
                let that = this;
                axios.get('/book/plan/select?page='+currentPage+'&limit='+that.pageSize) .then(function (response) {
                    that.book = response.data.response.list;
                }).catch(function (error) {
                    that.$emit('refresh',function () {
                        window.location.reload(true)
                    },'網絡不穩定，是否重試？');
                });
            },

        }
    }
</script>

<style scoped>

</style>