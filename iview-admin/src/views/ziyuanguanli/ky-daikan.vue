<style lang="less">

</style>
<template>
    <Row>
        <Col :lg="24">
        <Card>
            <Row :gutter="5">
<!--                <Col :lg="2">-->
<!--                <Select v-model="searchData.c_type" placeholder="客源类型" :transfer="true">-->
<!--                    <Option value="0">买卖客源</Option>-->
<!--                    <Option value="1">租赁客源</Option>-->
<!--                    <Option value="2">高端客源</Option>-->
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
        <Modal v-model="genjinjilv_ky" title="客户带看过的房源列表" width="780">
            <div slot="footer">
                <Button type="text" size="large" @click="genjinjilv_kyCancel">取消</Button>
                <Button type="primary" size="large" @click="genjinjilv_kyCancel">确定</Button>
            </div>
            <Row>
                <Col>
                <Table border :columns="listColumns" :data="listData" height="360"></Table>
                </Col>
            </Row>
        </Modal>
    </Row>
</template>

<script>
    import Cookies from 'js-cookie';

    export default {
        name: 'kyDaikan',
        data: function () {
            return {
                genjinjilv_ky: false,
                pageTotal: 0,
                pageCurrent: 1,
                pageSize: 10,
                FPpageTotal: 0,
                FPpageCurrent: 1,
                FPpageSize: 10,
                settings: [],
                mDaterange: [],
                searchDaterange: [],
                searchData: {
                    'u_id': '',
                    'c_type': ''
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
                        key: 'customer_type',
                        align: 'center',
                        render: (h, params) => {
                            let type = params.row.customer_type;
                            let texts = '';
                            if (type == '0') {
                                texts = '买卖客源';
                            } else if (type == '1') {
                                texts = '租赁客源';
                            } else if (type == '2') {
                                texts = '高端客源';
                            } else {
                                return '未知客源';
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
                        title: '客源编号',
                        key: 'xuqiubianhao',
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
                                        innerHTML: params.row.xuqiubianhao
                                    },
                                    on: {
                                        click: () => {
                                            let argu = {
                                                customer_uuid: params.row.customer_uuid,
                                                customer_type: params.row.customer_type
                                            };
                                            this.$router.push({
                                                name: 'customerEtails',
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
                                            this.selId = params.row.customer_uuid;
                                            this.listData = [];
                                            this.genjinjilv_ky = true;
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
                    // {
                    //     title: '房源类型',
                    //     key: 'sale_type',
                    //     align: 'center',
                    //     render: (h, params) => {
                    //         let type = params.row.sale_type;
                    //         let texts = '';
                    //         if (type == '2') {
                    //             texts = '出售房源';
                    //         } else if (type == '1') {
                    //             texts = '出租房源';
                    //         } else if (type == '3') {
                    //             texts = '高端房源';
                    //         } else {
                    //             return '未知房源';
                    //         }
                    //         return h('div', {props: {},}, texts);
                    //     }
                    // },
                    {
                        title: '房源编号',
                        key: 'house_sn',
                        align: 'center',
                        width: 108,
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
                                            if (params.row.house_uuid) {
                                                let argu = {
                                                    houseId: params.row.house_uuid,
                                                    saleType: params.row.sale_type
                                                };
                                                this.$router.push({
                                                    name: 'roomDetails',
                                                    params: argu
                                                });
                                            } else {
                                                this.$Message.warning('参数错误');
                                            }
                                            this.genjinjilv_ky = false;
                                            Cookies.set('genjinjilv_ky', 1);
                                        }
                                    }
                                })]);
                        }
                    },
                    {
                        title: '小区',
                        key: 'village_name',
                        align: 'center'
                    },
                    {
                        title: '楼层',
                        key: 'louceng',
                        align: 'center',
                        render: (h, params) => {
                            let loudong = params.row.loudong_name;
                            let danyuan = params.row.danyuan_name;
                            let fanghao = params.row.fanghao_name;
                            let louceeng = loudong + '栋' + danyuan + '单元' + fanghao + '室';
                            return h('div', {props: {},}, louceeng);
                        }
                    },
                    {
                        title: '户型',
                        key: 'fyhuxing',
                        align: 'center',
                        render: (h, params) => {
                            let shi = params.row.huxing_shi;
                            let ting = params.row.huxing_ting;
                            let wei = params.row.huxing_wei;
                            let huxing = shi + '室' + ting + '厅' + wei + '卫';
                            return h('div', {props: {},}, huxing);
                        }
                    },
                    {
                        title: '面积',
                        key: 'jianzhumianji',
                        align: 'center'
                    },
                    // 如果是租赁房源显示租价
                    {
                        title: '售价',
                        key: 'sell_price',
                        align: 'center'
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
            genjinjilv_kyCancel () {
                Cookies.set('genjinjilv_ky', 0);
                this.genjinjilv_ky = false;
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
                data.daikan = 'cust';
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
                    {'customer_uuid': this.selId, 'page': this.FPpageCurrent},
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
        }, watch: {
            '$route' (to, from) {
                if (this.$route.name == 'kyDaikan') {
                    if (Cookies.get('genjinjilv_ky') == 1) {
                        this.genjinjilv_ky = Cookies.get('genjinjilv_ky');
                        Cookies.set('genjinjilv_ky', 0);
                    } else {
                        this.genjinjilv_ky = false;
                    }
                }
            }
        }
    };
</script>
