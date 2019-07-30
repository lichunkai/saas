<style scoped>

</style>
<template>
    <Row>
        <Col :lg="18" :md="18" class="editBorder">
        <Row :gutter="10" style="margin-top: 10px">
            <Col :lg="24" :md="24">
            <Table :columns="columns1" :data="genjinlist" border script height="560"></Table>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: "editGenjin",
		props: ['genjinlist'],
        data() {
            return {
                columns1: [
                    {
                        title: '跟进时间',
                        key: 'ctime',
                        align: 'center'
                    },
                    {
                        title: '跟进方式',
                        key: 'genjinfangshi',
                        align: 'center'
                    },
                    {
                        title: '跟进人',
                        key: 'genjinren',
                        align: 'center'
                    },
                    {
                        title: '所在部门',
                        key: 'bumen',
                        align: 'center'
                    },
                    {
                        title: '跟进内容',
                        key: 'genjinneirong',
                        align: 'center',
                        width:220
                    },
                ],
                data1: []
            }
        },created: function () {
            
        },methods: {
            //跟进列表
            /* getIndex() {
                this.$http.get(api_param.apiurl + '/customer_follow/index',
                    {
                        params: {
                            customer_uuid: this.$route.params.customer_uuid,
                            pagesize: 10000,
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.data1 = response.body.data.list;						

                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    // console.log(response);
                });
            } */
        }
    }
</script>
