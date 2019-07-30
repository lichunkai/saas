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
                <Select v-model="searchData.hf_type" placeholder="跟单方式" :transfer="true">
                    <Option v-for="item in settings.gengjinfangshi" :value="item" :key="item">{{ item }}</Option>
                </Select>
                </Col>
                <Col :lg="2">
                <Cascader :data="settings.departlist" placeholder="部门选择" v-model="searchData.departpath" filterable
                          change-on-select @on-change="changeSearchDepart" :transfer="true"></Cascader>
                </Col>
                <Col :lg="2">
                <Select v-model="searchData.u_id" placeholder="跟进人" :transfer="true">
                    <Option v-for="item in users" :value="item.u_id" :key="item.u_id">{{item.u_name}}</Option>
                </Select>
                </Col>
                <Col :lg="4" >
                <DatePicker type="daterange" v-model="mDaterange" placement="bottom-end"  format="yyyy-MM-dd HH:mm" placeholder="开始-截止日期"
                            style="width: 100%;" @on-change="changeDaterange" @on-clear="clearDaterange"></DatePicker>
                </Col>
                <Col :lg="3">
                <Input v-model="searchData.keywd" placeholder="小区、房源编号、跟进内容"></Input>
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
        <Modal v-model="genjinjilv" title="房源跟进记录" width="780">
            <Row>
                <Col>
                <Table border :columns="listColumns" :data="followupList" height="360"></Table>
                <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                <Page :total="FPpageTotal" :current="FPpageCurrent" @on-change="changeFPPage" show-total :page-size="FPpageSize"></Page>
                </div>
                </div>
                </Col>
            </Row>
        </Modal>
    </Row>
</template>

<script>
    import Vue from 'vue';
    import Cookies from 'js-cookie';

    export default {
        name: 'fyGenjin',
        data: function () {
            return {
                genjinjilv: false,
                genjin: '',
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
                    'f_type': '',
                    'hf_type': '',
                    'keywd': ''
                },
                departpath: [],
                users: [],
                selhouseId: '',
                genjinColumns: [
                    {
                        title: '标签',
                        key: 'biaoqian',
                        width: 128,
                        align: 'center',
                        render: (h, params) => {
                            let color = ['blue', 'red', 'yellow', 'green', 'orange'];

                            let ret = [];
                            if (params.row.genjincishu) { ret.push(h('Tag', {props: {color: 'green'}}, '跟(' + params.row.genjincishu + ')')); }
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
                                // texts = '出租房源';
                                texts = '出售房源';
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
                        title: '跟进时间',
                        key: 'ctime',
                        align: 'center',
                    },
                    {
                        title: '跟单方式',
                        key: 'hf_type',
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
                        title: '小区',
                        key: 'village_name',
                        align: 'center'
                    },
                    {
                        title: '跟进人',
                        key: 'u_name',
                        align: 'center'
                    },
                    {
                        title: '部门',
                        key: 'd_name',
                        align: 'center'
                    },
                    {
                        title: '跟进内容',
                        key: 'hf_content',
                        align: 'center'
                    },
                    // {
                    //     title: '操作',
                    //     key: 'action',
                    //     width: 150,
                    //     align: 'center',
                    //     render: (h, params) => {
                    //         return h('div', [
                    //             h('a', {
                    //                 props: {
                    //                     type: 'primary',
                    //                     size: 'small'
                    //                 },
                    //                 style: {
                    //                     marginRight: '5px'
                    //                 },
                    //                 on: {
                    //                     click: () => {
                    //                         this.selhouseId = params.row.house_id;
                    //                         this.genjinjilv = true;
                    //                         this.getFollowup();
                    //                     }
                    //                 }
                    //             }, '查看所有跟进')
                    //         ]);
                    //     }
                    // }
                ],
                genjinData: [
                    {
                        genleixing: '房源出售',
                        gentime: '2018-7-20 14:53',
                        genfangshi: '电话跟进',
                        gennum: 'CSFY-1807-0025',
                        xiaoqu: '阳光城',
                        genmen: '徐晓月',
                        genbumen: '金筑店',
                        genneirong: '业主不打算降价'
                    }
                ],
                listColumns: [
                    {
                        title: '跟进时间',
                        key: 'ctime',
                        align: 'center',
                        width: 128
                    },
                    {
                        title: '跟进方式',
                        key: 'hf_type',
                        align: 'center'
                    },
                    {
                        title: '跟进人',
                        key: 'u_name',
                        align: 'center'
                    },
                    {
                        title: '所在部门',
                        key: 'd_name',
                        align: 'center'
                    },
                    {
                        title: '跟进内容',
                        key: 'hf_content',
                        align: 'center',
                        width: 220
                    },
                ],
                followupList: []
            };
        },
        created: function () {
            this.getSetting();
            this.getIndex();
        },
        methods: {
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
                this.$http.post(api_param.apiurl + 'house/getgenjin',
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
                this.$http.post(api_param.apiurl + 'house/getfollowups',
                    {'house_id': this.selhouseId, 'page': this.FPpageCurrent},
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.followupList = response.data.data.list;
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
    };
</script>
