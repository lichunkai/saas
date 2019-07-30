<style scoped>

</style>
<template>
    <Row>
        <Col :lg="18" :md="18" class="editBorder">
        <Row :gutter="10" style="margin-top: 10px">
            <Col :lg="24" :md="24">
            <Table :columns="columns1" :data="daikanlist" border script height="560"></Table>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: "editSee",
		props: ['daikanlist'],
        data() {
            return {
                columns1: [
                    {
                        title: '带看时间',
                        key: 'ctime',
                        align: 'center'
                    },
                    {
                        title: '房源名称',
                        key: 'house_title',
                        align: 'center'
                    },
                    {
                        title: '房源编号',
                        key: 'house_sn',
                        align: 'center',
                        render: (h, params) => {
                            return h('Button', {
                                props: {
                                    type: 'text',
                                    size: 'small'
                                },
                                style: {
                                    color: '#2d8cf0',
                                    textDecoration: 'underline',
                                },
                                domProps: {
                                    innerHTML: params.row.house_sn,
                                },
                                on: {
                                    click: () => {
                                        let argu = {saleType:params.row.sale_type,houseId: params.row.house_uuid};
                                        this.$router.push({
                                            name: 'roomDetails',
                                            params: argu
                                        });
                                    }
                                }
                            });
                        }
                    },
                    {
                        title: '带看人',
                        key: 'daikanren',
                        align: 'center'
                    },
                    {
                        title: '所在部门',
                        key: 'bumen',
                        align: 'center'
                    },
                    {
                        title: '客户评价',
                        key: 'khpj',
                        align: 'center',
                    }
                ],
                data1: []
            }
        },created: function () {
           
        },methods: {
            //带看
            /* getIndex() {
                this.$http.get(api_param.apiurl + '/customer_daikan/index',
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
