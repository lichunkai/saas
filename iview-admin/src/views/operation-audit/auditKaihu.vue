<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="5">
                <Col :lg="2">
                <Select v-model="search.is_pass" placeholder="状态">
                    <Option value="1">通过</Option>
                    <Option value="0">等待审核</Option>
                    <Option value="2">驳回</Option>
                </Select>
                </Col>
                <Col :lg="3" :md="3">
                <Input v-model="search.kw" placeholder="关键字"></Input>
                </Col>
                <Col :lg="3">
                <DatePicker type="daterange" :value="search.daterange" @on-change="selectDaterange"
                            placeholder="开始-截止日期"></DatePicker>
                </Col>
                <Col :lg="3">
                <Button type="primary" @click="doSearch">查询</Button>
                <Button type="primary" @click="clearSearch">清空</Button>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Table border :columns="kaihuColumns" :data="kaihuDdata"></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="pageTotal" :current="page" show-total @on-change="changePage"></Page>
                </div>
            </div>
        </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'auditKaihu',
        data() {
            return {
                pageTotal: 0,
                page: 1,
                kaihuColumns: [
                    {
                        title: 'IP',
                        key: 'ip',
                        align: 'center'
                    },
                    {
                        title: '门店',
                        key: 'mendian',
                        align: 'center'
                    },
                    {
                        title: '机器码',
                        key: 'code',
                        align: 'center'
                    },
                    {
                        title: '申请人',
                        key: 'shenqingren',
                        align: 'center'
                    },
                    {
                        title: '申请原因',
                        key: 'remake',
                        align: 'center'
                    },
                    {
                        title: '申请时间',
                        key: 'ctime',
                        align: 'center'
                    },
                    {
                        title: '审核时间',
                        key: 'utime',
                        align: 'center'
                    },
                    {
                        title: '状态',
                        key: 'is_pass',
                        align: 'center',
                        render: (h, params) => {
                            if (params.row.is_pass == 0) {
                                return "等待审核";
                            }
                            if (params.row.is_pass == 1) {
                                return "通过";
                            }
                            if (params.row.is_pass == 2) {
                                return "驳回";
                            }
                        }
                    },
                    {
                        title: '操作',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            if (params.row.is_pass == 0) {
                                return h('div', [
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
                                                this.$http.post(api_param.apiurl + 'license/setpass',
                                                    {'id': params.row.id, 'is_pass': '1'},
                                                    {
                                                        emulateJSON: true,
                                                        headers: {"X-Access-Token": api_param.XAccessToken}
                                                    }
                                                ).then(function (response) {
                                                    // 这里是处理正确的回调
                                                    if (response.data.code == 200) {
                                                        this.$Message.success('操作成功');
                                                        this.getIndex();

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
                                                    this.$Message.warning('网络异常');
                                                })
                                            }
                                        }
                                    }, '确认'),
                                    h('Button', {
                                        props: {
                                            type: 'error',
                                            size: 'small'
                                        },
                                        on: {
                                            click: () => {
                                                this.$http.post(api_param.apiurl + 'license/setpass',
                                                    {'id': params.row.id, 'is_pass': '2'},
                                                    {
                                                        emulateJSON: true,
                                                        headers: {"X-Access-Token": api_param.XAccessToken}
                                                    }
                                                ).then(function (response) {
                                                    // 这里是处理正确的回调
                                                    if (response.data.code == 200) {
                                                        this.$Message.success('操作成功');
                                                        this.getIndex();

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
                                                    this.$Message.warning('网络异常');
                                                })
                                            }
                                        }
                                    }, '驳回')
                                ]);
                            } else if (params.row.is_pass == 1) {
                                return h('div', [
                                    h('Button', {
                                        props: {
                                            type: 'warning',
                                            size: 'small'
                                        },
                                        style: {
                                            marginRight: '5px'
                                        },
                                        on: {
                                            click: () => {
                                                this.$http.post(api_param.apiurl + 'license/setpass',
                                                    {'id': params.row.id, 'is_pass': '2'},
                                                    {
                                                        emulateJSON: true,
                                                        headers: {"X-Access-Token": api_param.XAccessToken}
                                                    }
                                                ).then(function (response) {
                                                    // 这里是处理正确的回调
                                                    if (response.data.code == 200) {
                                                        this.$Message.success('操作成功');
                                                        this.getIndex();

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
                                                    this.$Message.warning('网络异常');
                                                })
                                            }
                                        }
                                    }, '禁用')
                                ]);
                            } else if (params.row.is_pass == 2) {
                                return h('div', [
                                    h('Button', {
                                        props: {
                                            type: '',
                                            size: 'small'
                                        },
                                        style: {
                                            marginRight: '5px'
                                        },
                                        on: {
                                            click: () => {
                                                this.$http.post(api_param.apiurl + 'license/setpass',
                                                    {'id': params.row.id, 'is_pass': '1'},
                                                    {
                                                        emulateJSON: true,
                                                        headers: {"X-Access-Token": api_param.XAccessToken}
                                                    }
                                                ).then(function (response) {
                                                    // 这里是处理正确的回调
                                                    if (response.data.code == 200) {
                                                        this.$Message.success('操作成功');
                                                        this.getIndex();

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
                                                    this.$Message.warning('网络异常');
                                                })
                                            }
                                        }
                                    }, '启用'),
                                ]);
                            }

                        }
                    }
                ],
                kaihuDdata: [],
                search: {},
            };
        },
        created() {
            this.getIndex();
        },
        methods: {
            getIndex() {
                this.search.page = this.page;
                this.$http.post(api_param.apiurl + 'license/getindex',
                    this.search,
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.$Message.success('获取成功');
                        this.kaihuDdata = response.data.data.list;
                        this.pageTotal = response.data.data.count;

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
                    this.$Message.warning('网络异常');
                })
            },
            selectDaterange(date) {
                this.search.daterange = date;
            },

            doSearch() {
                this.page = 1;
                this.getIndex();
            },
            clearSearch() {
                this.search = {};
                this.getIndex();
            },
            //时间格式化
            formatDate(date) {
                const y = date.getFullYear();
                let m = date.getMonth() + 1;
                m = m < 10 ? '0' + m : m;
                let d = date.getDate();
                d = d < 10 ? ('0' + d) : d;
                return y + '-' + m + '-' + d;
            },
            //分页
            changePage(page) {
                this.page = page;
                this.getIndex();
            },

        }
    };
</script>
