<style lang="less">

</style>
<template>
    <Row>
        <Col :lg="24">
        <Card>
            <Row :gutter="5">
<!--                <Col :lg="2">-->
<!--                <Select v-model="searchData.f_type" placeholder="房源类型" :transfer="true">-->
<!--                    <Option value="2">出售房源</Option>-->
<!--                    <Option value="1">出租房源</Option>-->
<!--                    <Option value="3">高端房源</Option>-->
<!--                </Select>-->
<!--                </Col>-->
                <Col :lg="2">
                <Cascader :data="settings.departlist" placeholder="部门选择" v-model="searchData.departpath" filterable
                          change-on-select @on-change="changeSearchDepart" :transfer="true"></Cascader>
                </Col>
                <Col :lg="2">
                <Select v-model="searchData.u_id" placeholder="带看人" :transfer="true">
                    <Option v-for="item in users" :value="item.u_id" :key="item.u_id">{{item.u_name}}</Option>
                </Select>
                </Col>
                <Col :lg="4">
                <DatePicker type="daterange" v-model="mDaterange" placement="bottom-end"  format="yyyy-MM-dd HH:mm" placeholder="开始-截止日期"
                            style="width: 100%;" @on-change="changeDaterange" @on-clear="clearDaterange"></DatePicker>
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
            <Row>
                <Table border :columns="genjinColumns" :data="genjinData"></Table>
                <div style="margin: 10px;overflow: hidden">
                    <div style="float: right;">
                        <Page :total="pageTotal" :current="pageCurrent" @on-change="changePage" show-total
                              :page-size="pageSize"></Page>
                    </div>
                </div>
            </Row>
        </Card>
        </Col>
        <Modal v-model="genjinjilv_fy" title="客户带看过的房源列表" width="780">
            <div slot="footer">
                <Button type="text" size="large" @click="genjinjilv_fyCancel">取消</Button>
                <Button type="primary" size="large" @click="genjinjilv_fyCancel">确定</Button>
            </div>
            <Row>
                <Col>
                <Table border :columns="listColumns" :data="listData" height="360"></Table>
                <!-- <div style="margin: 10px;overflow: hidden">
                    <div style="float: right;">
                        <Page :total="FPpageTotal" :current="FPpageCurrent" @on-change="changeFPPage" show-total
                              :page-size="FPpageSize"></Page>
                    </div>
                </div> -->
                </Col>
            </Row>
        </Modal>
    </Row>
</template>

<script>
    import Cookies from 'js-cookie';

    export default {
        name: 'fyDaikan',
        data: function () {
            return {
                genjinjilv_fy: false,
                FPpageTotal: 0,
                FPpageCurrent: 1,
                FPpageSize: 10,
                pageTotal: 0,
                pageCurrent: 1,
                pageSize: 10,
                settings: [],
                mDaterange: [],
                searchDaterange: [],
                searchData: {
                    'u_id': '',
                    'f_type': ''
                },
                departpath: [],
                users: [],
                selId: '',
                genjinColumns: [
                    {
                        title: '标签',
                        key: 'biaoqian',
                        width: 128,
                        align: 'center',
                        render: (h, params) => {
                            let color = ['blue', 'red', 'yellow', 'green', 'orange'];

                            let ret = [];
                            if (params.row.daikancishu) { ret.push(h('Tag', {props: {color: 'yellow'}}, '跟(' + params.row.daikancishu + ')')); }
                            return h('div', ret);
                        }
                    },
                    {
                        title: '类型',
                        key: 'sale_type',
                        align: 'center',
                        render: (h, params) => {
                            let type = params.row.sale_type;
                            let texts = '';
                            if (type == '2') {
                                texts = '出售房源';
                            } else if (type == '1') {
                                texts = '出售房源';
                                // texts = '出租房源';
                            } else if (type == '3') {
                                texts = '出售房源';
                                // texts = '高端房源';
                            } else {
                                return '未知房源';
                            }
                            return h('div', {props: {},}, texts);
                        }
                    },
                    {
                        title: '客户姓名',
                        key: 'customer_name',
                        align: 'center'
                    },
                    {
                        title: '房源编号',
                        key: 'house_sn',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('a', {
                                    props: {
                                        type: 'warning',
                                        size: 'small'
                                    },
                                    style: {
                                        textDecoration: 'underline',
                                    },
                                    domProps: {
                                        innerHTML: params.row.house_sn
                                    },
                                    on: {
                                        click: () => {
                                            let argu = {
                                                houseId: params.row.house_uuid,
                                                saleType: params.row.sale_type
                                            };
                                            this.$router.push({
                                                name: 'roomDetails',
                                                params: argu
                                            });
                                        }
                                    }
                                })]);
                        }

                    },
                    {
                        title: '带看人',
                        key: 'daikanren',
                        align: 'center'
                    },
                    {
                        title: '部门',
                        key: 'bumen',
                        align: 'center'
                    },
                    {
                        title: '客户评价',
                        key: 'd_pingjia',
                        align: 'center'
                    },
                    {
                        title: '带看时间',
                        key: 'ctime',
                        align: 'center'
                    },
                    {
                        title: '带盘量',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('a', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.selId = params.row.house_uuid;
                                            this.genjinjilv_fy = true;
                                            this.listData = [];
                                            this.getFollowup();
                                        }
                                    }
                                }, '查看所有带看')
                            ]);
                        }
                    }
                ],
                genjinData: [],
                listColumns: [

                    {
                        title: '客源编号',
                        key: 'xuqiubianhao',
                        align: 'center',
                        width: 128,
                        render: (h, params) => {
                            return h('div', [
                                h('div', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    style: {
                                        color: '#2d8cf0',
                                        textDecoration: 'underline',
                                        cursor: 'pointer',
                                    },
                                    domProps: {
                                        innerHTML: params.row.xuqiubianhao,
                                    },
                                    on: {
                                        click: () => {
                                            if (params.row.customer_uuid) {
                                                let argu = {customer_uuid: params.row.customer_uuid, customer_type: 0};
                                                this.$router.push({
                                                    name: 'customerEtails',
                                                    params: argu
                                                });
                                            } else {
                                                this.$Message.warning('参数错误');
                                            }
                                            this.genjinjilv_fy = false;
                                            Cookies.set('genjinjilv_fy', 1);
                                        }

                                    }
                                })
                            ]);
                        }
                    },
                    {
                        title: '需求区域',
                        key: 'kyquyu',
                        align: 'center',
                        render: (h, params) => {
                            let dts_name = params.row.dts_name;
                            let rn_name = params.row.rn_name;
                            return h('div', {props: {},}, dts_name + rn_name);
                        }
                    },
                    {
                        title: '面积',
                        key: 'kymianji',
                        align: 'center',
                        render: (h, params) => {
                            let xuqiumianji_min = params.row.xuqiumianji_min;
                            let xuqiumianji_max = params.row.xuqiumianji_max;
                            let mianji = xuqiumianji_min + '-' + xuqiumianji_max + '平方米';
                            return h('div', {props: {},}, mianji);
                        }
                    },
                    // {
                    //     title: '客源类型',
                    //     key: 'customer_type',
                    //     align: 'center',
                    //     render: (h, params) => {
                    //         let type = params.row.customer_type;
                    //         let texts = '';
                    //         if (type == '0') {
                    //             texts = '买卖客源';
                    //         } else if (type == '1') {
                    //             texts = '租赁客源';
                    //         } else if (type == '2') {
                    //             texts = '高端客源';
                    //         } else {
                    //             return '未知客源';
                    //         }
                    //         return h('div', {props: {},}, texts);
                    //     }
                    // },
                    {
                        title: '户型',
                        key: 'kyhuxing',
                        align: 'center',
                        render: (h, params) => {
                            let xuqiuhuxing_min = params.row.xuqiuhuxing_min;
                            let xuqiuhuxing_max = params.row.xuqiuhuxing_max;
                            let huxing = xuqiuhuxing_min + '-' + xuqiuhuxing_max + '室';
                            return h('div', {props: {},}, huxing);
                        }
                    },
                    {
                        title: '价格',
                        key: 'kyjiage',
                        align: 'center',
                        render: (h, params) => {
                            let xuqiujiage_min = params.row.xuqiujiage_min;
                            let xuqiujiage_max = params.row.xuqiujiage_max;
                            let jiage = xuqiujiage_min + '-' + xuqiujiage_max + '万';
                            return h('div', {props: {},}, jiage);
                        }
                    }
                ],
                listData: []
            };
        },
        created: function () {
            this.getSetting();
            this.getIndex();
        },
        methods: {
            genjinjilv_fyCancel () {
                Cookies.set('genjinjilv_fy', 0);
                this.genjinjilv_fy = false;
            },
            //搜索部门
            changeSearchDepart (value, selectedData) {
                this.departpath = value;

                let d_id = value[value.length - 1];
                this.users = this.settings.users[d_id];
                //this.getIndex();
            },
            getSetting () {//获取配置项目
                this.$http.get(api_param.apiurl + 'house/getsetting', {
                    params: {},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调

                        for (let i in response.data.data) {
                            this.$set(this.settings, i, response.data.data[i]);
                        }
                        if (response.data.data.customcolumns[5] && response.data.data.customcolumns[5] != null) {
                            this.tableColumnsChecked = response.data.data.customcolumns[5];
                        }
                        //this.changeTableColumns();
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
                    this.$Message.error('你的网络开小差了^—^');
                });
            },
            changePage (page) {
                this.pageCurrent = page;
                this.getIndex();
            },
            changeFPPage (page) {
                this.FPpageCurrent = page;
                this.getFollowup();
            },
            doSearch () {
                this.pageCurrent = 1;
                this.getIndex();
            },
            clearSearch () {
                for (let i in this.searchData) {
                    this.$set(this.searchData, i, '');
                }
                this.searchDaterange = [];
                this.mDaterange = [];
                this.pageCurrent = 1;
                this.getIndex();
            },
            changeDaterange (event) {
                this.searchDaterange = event;
            },
            clearDaterange (event) {
                this.searchDaterange = [];
            },
            getIndex () {
                let data = this.searchData;
                data.page = this.pageCurrent;
                data.dateRange = this.searchDaterange;
                data.daikan = 'house';
                this.$http.post(api_param.apiurl + 'customer_daikan/index',
                    data,
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    if (response.data.code == 200) {
                        this.genjinData = response.data.data.list;
                        this.pageTotal = parseInt(response.data.data.count);
                    } else if (response.data.code == 401) {
                        this.$Message.error('登录超时');
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({name: 'login'});
                    } else {
                        this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    this.$Message.error('网络异常');
                });
            },
            //获取跟进
            getFollowup () {
                this.$http.post(api_param.apiurl + 'customer_daikan/daikan',
                    {'house_uuid': this.selId, 'page': this.FPpageCurrent},
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.listData = response.data.data.list;
                        this.FPpageTotal = parseInt(response.data.data.count);
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
                    this.$Message.error('网络异常');
                });
            }
        }
        , watch: {
            '$route' (to, from) {
                if (this.$route.name == 'fyDaikan') {
                    if (Cookies.get('genjinjilv_fy') == 1) {
                        this.genjinjilv_fy = Cookies.get('genjinjilv_fy');
                        Cookies.set('genjinjilv_fy', 0);
                    } else {
                        this.genjinjilv_fy = false;
                    }
                }
            }
        }
    };
</script>
