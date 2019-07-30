<style lang="less">

</style>
<template>
    <Row>
        <Col :lg="24">
        <Card>
            <Row :gutter="5">
                <Col :lg="2">
                <Select v-model="searchData.order_type" placeholder="成交类型">
                    <Option value="2">买卖成交</Option>
                    <Option value="1">租赁成交</Option>
                    <Option value="3">高端成交</Option>
                </Select>
                </Col>
                <Col :lg="2">
                <Select v-model="searchData.orderstatus" placeholder="状态">
                    <Option v-for="(item,index) in orderstatus" :value="item.child_name">{{item.child_name}}</Option>
                </Select>
                </Col>
                <Col :lg="2">
                <Cascader :data="departData" trigger="click" v-model="searchData.departpath" @on-change="searchUserList" :clearable="false"
                        placeholder="部门选择" change-on-select></Cascader>
                </Col>
                <Col :lg="2">
                <Select v-model="searchData.u_id" placeholder="分成人">
                    <Option v-for="v in users" :value="v.value" :key="v.value">{{v.label}}</Option>
                </Select>
                </Col>
                <Col :lg="3">
                <DatePicker type="daterange" placement="bottom-end" placeholder="开始-截止日期"
                            style="width: 100%;" @on-change="changeDaterange" @on-clear="clearDaterange"></DatePicker>
                </Col>
                <Col :lg="3">
                <Input v-model="searchData.keywd" placeholder="成交编号"></Input>
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
                        <Page :total="pageTotal" :current="pageCurrent" @on-change="changePage" show-total :page-size="pageSize"></Page>
                    </div>
                </div>
            </Row>
        </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'payFenchengmingxi',
        data: function () {
            return {
                genjinjilv: false,
                pageTotal: 0,
                pageCurrent: 1,
                pageSize: 10,
                settings: [],
                searchDaterange: [],

                searchData: {
                   'u_id':'',
                   'f_type':'',
                   'keywd':'',
                   'orderstatus':''
                },
                departData: [],
                departkey:[],
                users: [],
                orderstatus: [],
                genjinColumns: [
                    {
                        title: '成交类型',
                        key: 'order_type',
                        align: 'center',
                        render: (h, params) => {
                            let texts = '';
                            let type = params.row.order_type;
                            if(type == '2'){
                                texts = '买卖成交';
                            }else if(type == '1'){
                                texts = '租赁成交';
                            }else if(type == '3'){
                                texts = '高端成交';
                            }else{
                                texts = '未知成交';
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '地址',
                        key: 'house_name',
                        align: 'center',
                        render: (h, params) => {
                             let house = params.row.house_name;
                             let b = params.row.house_building;
                             let u = params.row.house_unit;
                             let d = params.row.house_door;
                             // return house + b + '栋' + u + '单元' + d + '室';
                            return h('div', {props: {},},house + b + '栋' + u + '单元' + d + '室')
                        }
                    },
                    {
                        title: '成交编号',
                        key: 'order_sn',
                        align: 'center'
                    },
                    {
                        title: '合同号',
                        key: 'contract_sn',
                        align: 'center'
                    },
                    {
                        title: '成交日期',
                        key: 'order_deal_date',
                        align: 'center'
                    },
                    {
                        title: '分成人',
                        key: 'user_name',
                        align: 'center'
                    },
                    {
                        title: '分成门店',
                        key: 'depart_name',
                        align: 'center'
                    },
                    {
                        title: '分成缘由',
                        key: 'reason',
                        align: 'center'
                    },
                    {
                        title: '分成比例',
                        key: 'divide_per',
                        align: 'center'
                    },
                    {
                        title: '实收业绩',
                        key: 'divide_money',
                        align: 'center'
                    }

                ],
                genjinData: [
                    {
                        payleixing: '买卖成交',
                        payaddress: '金竹小区32栋2单元2室',
                        paynum: 'MMCJ-0807-1258',
                        payhetonghao: '',
                        paydata: '2018-07-16',
                        payfenchengren: '徐晓月',
                        paymendian: '金筑店',
                        payyuanyou: '成交人',
                        paybili: '12',
                        payyeji: '15000.00',
                        payshishou: '1250.00'
                    }
                ]
            };
        },
        created: function(){
            this.getDeptList();
            this.getMingxi();
        },
        methods: {
            // 获取部门
            getDeptList() {
                this.$http.get(api_param.apiurl + 'site/getdepttree', {
                    params: {},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.departData = response.data.data.departlist
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response)
                })
            },
            // 搜索部门用户
            searchUserList(value, selectedData) {
                this.departkey = value[value.length - 1];
                //alert(this.departkey);
                this.$http.get(api_param.apiurl + 'site/getstaff', {
                    params: {
                        did: this.departkey
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        //console.log(response.data);
                        this.users = response.data.data;
                        // console.log(this.users);
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response)
                })
            },
            changePage(page) {
                this.pageCurrent = page;
                this.getMingxi();
            },
            // changeFPPage(page) {
            //     this.FPpageCurrent = page;
            //     this.getFollowup();
            // },
            doSearch() {
                this.getMingxi();
            },
            clearSearch() {
                for (let i in this.searchData) {
                    this.$set(this.searchData, i, '')
                }
                this.searchDaterange = [];
                this.pageCurrent = 1;
                this.getMingxi();
            },
            changeDaterange(event) {
                this.searchDaterange = event;
            },
            clearDaterange(event) {
                this.searchDaterange = [];
            },
            getMingxi(){
                let data = this.searchData;
                data.page = this.pageCurrent;
                data.dateRange = this.searchDaterange;
                this.$http.post(api_param.apiurl + 'financecommission/getmingxi',
                    data,
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    if (response.data.code == 200) {
                        this.orderstatus = response.data.data.orderstatus;
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
            }
        }

    };
</script>