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
                <Select v-model="searchData.genjinfangshi" placeholder="跟单方式">
                    <Option v-for="item in JSON.parse( this.genjinfangshi[0].base_desp)"
                         :value="item.child_name" :key="item.child_name">{{ item.child_name}}</Option>
                </Select>
                </Col>
                <Col :lg="2">
                <Cascader :data="this.peizhi.benzu" trigger="click" filterable change-on-select
                                              v-model="searchData.departpath" @on-change="changeSearchDepart" placeholder="部门选择"></Cascader>
                </Col>
                <Col :lg="2">
                <Select v-model="searchData.u_id" placeholder="跟进人" :transfer="true">
                    <Option v-for="item in users" :value="item.u_id" :key="item.u_id">{{item.u_name}}</Option>
                </Select>
                </Col>
                <Col :lg="4">
                <DatePicker type="daterange" v-model="mDaterange" placement="bottom-end" format="yyyy-MM-dd HH:mm" placeholder="开始-截止日期"
                            style="width: 100%;" @on-change="changeDaterange" @on-clear="clearDaterange"></DatePicker>
                </Col>
                <Col :lg="3">
                <Input v-model="searchData.keywd" placeholder="客源编号、跟进内容"></Input>
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
        <Modal v-model="genjinjilv" title="客源跟进记录" width="780">
            <Row>
                <Col>
                <Table border :columns="listColumns" :data="listData" height="360"></Table>
<!--                    <div style="margin: 10px;overflow: hidden">-->
<!--                        <div style="float: right;">-->
<!--                            <Page :total="FPpageTotal" :current="FPpageCurrent" @on-change="changeFPPage" show-total :page-size="FPpageSize"></Page>-->
<!--                        </div>-->
<!--                    </div>-->
                </Col>
            </Row>
        </Modal>
    </Row>
</template>

<script>
    export default {
        name: 'kyGenjin',
        data: function () {
            return {
                genjinjilv: false,
                pageTotal: 0,
                pageCurrent: 1,
                pageSize: 10,
                FPpageTotal: 0,
                FPpageCurrent: 1,
                FPpageSize: 10,
                settings:[],
                genjinfangshi: [[]],
                mDaterange:[],
                searchDaterange: [],
                searchData: {
                   'u_id':'',
                   'c_type':'',
                   'genjinfangshi':'',
                   'keywd':''
                },
                departpath: [],
                users: [],
                selcustId: '',
                peizhi: [],
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
                        key: 'customer_type',
                        align: 'center',
                        render: (h, params) => {
                            let type = params.row.customer_type;
                            let texts = '';
                            if(type == '0'){
                                texts = '买卖客源';
                            }else if(type == '1'){
                                texts = '租赁客源';
                            }else if(type == '2'){
                                texts = '高端客源';
                            }else{
                                return '未知客源';
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '跟进时间',
                        key: 'ctime',
                        align: 'center'
                    },
                    {
                        title: '跟单方式',
                        key: 'genjinfangshi',
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
                        title: '跟进人',
                        key: 'genjinren',
                        align: 'center'
                    },
                    {
                        title: '部门',
                        key: 'bumen',
                        align: 'center'
                    },
                    {
                        title: '跟进内容',
                        key: 'genjinneirong',
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
                    //                         this.selcustId = params.row.customer_uuid;
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
                        genleixing: '客源出售',
                        gentime: '2018-7-20 14:53',
                        genfangshi: '电话跟进',
                        gennum: 'CSFY-1807-0025',
                        genmen: '徐晓月',
                        genbumen: '金筑店',
                        genneirong: '正常电话回访'
                    }
                ],
                listColumns: [
                    {
                        title: '跟进时间',
                        key: 'ctime',
                        align: 'center',
                        width: 120
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
                        title: '部门',
                        key: 'bumen',
                        align: 'center'
                    },
                    {
                        title: '跟进内容',
                        key: 'genjinneirong',
                        align: 'center',
                        width: 200
                    }
                ],
                listData: []
            };
        },
        created: function(){
            this.getSetting();
            this.getPeizhi();
            this.getIndex();
        },
        methods: {
            //搜索部门
            changeSearchDepart(value, selectedData) {
                this.departpath = value;

                let d_id = value[value.length - 1];
                this.users = this.peizhi.users[d_id];

                //this.getIndex();
            },
            getSetting() {//获取配置项目
                this.$http.get(api_param.apiurl + 'customer_follow/getsetting', {
                    params: {
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调
                        this.genjinfangshi = response.data.data.genjinfangshi;
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
                })
            },
            changePage(page) {
                this.pageCurrent = page;
                this.getIndex();
            },
            changeFPPage(page) {
                this.FPpageCurrent = page;
                this.getFollowup();
            },
            doSearch() {
                this.pageCurrent = 1;
                this.getIndex();
            },
            clearSearch() {
                for (let i in this.searchData) {
                    this.$set(this.searchData, i, '')
                }
                this.searchDaterange = [];
                this.mDaterange = [];
                this.getIndex();
            },
            changeDaterange(event) {
                this.searchDaterange = event;
            },
            clearDaterange(event) {
                this.searchDaterange = [];
            },
            getIndex () {
                let data = this.searchData;
                data.page = this.pageCurrent;
                data.dateRange = this.searchDaterange;
                this.$http.post(api_param.apiurl + 'customer_follow/index',
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
            getPeizhi() {
                let data = {
                    page: 1,
                    customer_type: 0,
                }
                this.$http.get(api_param.apiurl + '/customer/index',
                    {
                        params: data,
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        if(this.peizhi.length==0){
                            this.peizhi = response.data.data.peizhi;
                        }
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
            },
			//获取跟进
			getFollowup() {
				this.$http.post(api_param.apiurl + 'customer_follow/index',
					{'customer_uuid': this.selcustId, 'page':this.FPpageCurrent},
					{emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
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
				})
			}
        }
    };
</script>
