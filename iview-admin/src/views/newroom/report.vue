<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="5">
                <RadioGroup v-model="ss.type" @on-change="changessxz">
                    <Radio label="">不限</Radio>
                    <Radio label="1">已报备</Radio>
                    <Radio label="2">申请带看</Radio>
                    <Radio label="3">已带看</Radio>
                    <Radio label="4">已签约</Radio>
                    <Radio label="6">业绩确认</Radio>
                    <Radio label="8">已开票</Radio>
                    <Radio label="9">带看被驳回</Radio>
                </RadioGroup>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Table :columns="newroomColumns" :data="newroomData" border srcipt></Table>

            <!--项目报备-->

            <!--项目详情-->

        </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'report_index',
        data () {
            return {
                ss: {
                    type: ''
                },
                formValidate: {
                    name: '',
                    mail: '',
                    city: ''
                },
                phone: 'apple',
                users: [],
                newdetails: false,
                newbaobei: false,
                newroomColumns: [
                    {
                        title: '客户姓名',
                        key: 'realname',
                        align: 'center'
                    },
                    {
                        title: '客户编号',
                        key: 'bianhao',
                        align: 'center'
                    },
                    {
                        title: '报备楼盘',
                        key: 'name',
                        align: 'center'
                    },
                    {
                        title: '楼盘电话',
                        key: 'phone',
                        align: 'center'
                    },
                    {
                        title: '客户电话',
                        key: 'mobile',
                        align: 'center'
                    },
                    {
                        title: '客户状态',
                        key: 'status',
                        align: 'center',
                        render: (h, params) => {
                            var ret = '';
                            switch (params.row.see_status) {
                                case '1':
                                    ret = '已报备';
                                    break;
                                case '2':
                                    ret = '申请带看';
                                    break;
                                case '3':
                                    ret = '已带看';
                                    break;
                                case '4':
                                    ret = '已签约';
                                    break;
                                case '6':
                                    ret = '业绩确认';
                                    break;
                                case '8':
                                    ret = '已开票';
                                    break;
                                case '9':
                                    ret = '带看被驳回';
                                    break;
                            }
                            return h('div', ret);
                        }
                    },
                    {
                        title: '操作',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            var ret = [];
                            if (params.row.see_status == '1') {
                                ret.push(
                                    h('Button', {
                                        props: {
                                            type: 'primary',
                                            size: 'small'
                                        },
                                        style: {
                                            marginRight: '5px'
                                        },
                                        on: {
                                            click: () => {
                                                this.sq(params.row);
                                            }
                                        }
                                    }, '申请带看')
                                );
                            }
                            return h('div', ret);
                        }
                    }
                ],
                newroomData: []
            };
        },
        created () {
            this.getUser();
        },
        methods: {
            changessxz () {
                this.getIndex();
            },
            getUser () {
                this.$http.get(api_param.apiurl + 'user_deploy/getindex', {
                    params: {}, headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    this.users = response.body.data;
                    this.getIndex();
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            },
            getIndex () {
                this.$http.get(api_param.newHouseUrl + '/web/index.php?c=site&a=entry&act=getcustomerlist&do=customer_api&m=superman_house', {
                    params: {
                        token: this.$md5(api_param.newHouseKey + api_param.newHouseTime),
                        tokenTime: api_param.newHouseTime,
                        company_id: this.users.company_id,
                        partnerid: this.users.u_id,
                        type: this.ss.type,
                    }, headers: {}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    this.newroomData = response.body.data.list;
                    this.tableNum = response.body.data.count;
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            },
            sq (params) {
                this.$http.post(api_param.newHouseUrl + '/web/index.php?c=site&a=entry&act=changestatus&do=customer_api&m=superman_house',
                    {
                        token: this.$md5(api_param.newHouseKey + api_param.newHouseTime),
                        tokenTime: api_param.newHouseTime,
                        status: 2,
                        uniacid: params.uniacid,
                        partnerid: params.partnerid,
                        houseid: params.houseid,
                        seeid: params.seeid,
                        customerid: params.customerid,
                        company_id: this.users.company_id,
                    },
                    {emulateJSON: true}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    this.$Message.success(response.data.message);
                    this.getIndex();
                    this.qxbb();
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            }
        }, watch: {
            '$route' (to, from) {
                if (this.$route.name == 'report_index') {
                    this.getIndex();
                }
            }
        }
    };
</script>

<style scoped>

</style>
