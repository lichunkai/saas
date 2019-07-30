<template>
    <Row>
        <!--按钮-->
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="5">
                <!--部门员工-->
                <Col :lg="3" :md="3">
                <Cascader :data="this.peizhi.departlist" trigger="click" filterable change-on-select
                          v-model="sousuo.bumen" @on-change="changeDepart" placeholder="部门选择"></Cascader>
                </Col>
                <Col :lg="3" :md="3">
                <Select v-model="sousuo.user" placeholder="" :transfer="true" placeholder="员工选择"
                        @on-change="changeuser">
                    <Option v-for="v in sousuo.users" :value="v.u_id" :key="v.u_id">{{v.u_name}}
                    </Option>
                </Select>
                </Col>
                <!--日期-->
                <Col :lg="3" :md="3">
                <DatePicker type="month" @on-change="setPublishTime2" v-model="sousuo.shijian_x" placeholder="请选择日期"
                            format="yyyy-MM" :transfer="true"></DatePicker>
                </Col>
                <!--查询-->
                <Col :lg="4" :md="4">
                <Button type="primary" @click="searchCustomerList">搜索</Button>
                <Button type="primary" @click="qkym">清空</Button>
                </Col>
                <Col :lg="11" :md="11">
                <Row type="flex" justify="end">
                    <Col>
                    <Button type="primary" @click="fagongzi= true">发工资</Button>
                    <Button type="primary" @click="gongzishengcheng = true">工资生成</Button>
                    <Button type="primary"><a :href="exporturl" target="_blank" style="color:#fff">导出</a></Button>
                    </Col>
                </Row>
                <Modal v-model="gongzishengcheng" title="生成工资" width="360">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="shengchengCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">生成工资</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="shengchengCancel">取消</Button>
                        <Button type="primary" size="large" @click="addshengcheng">生成本月工资</Button>
                    </div>
                    <Form ref="shengcheng" :model="shengcheng" :label-width="70">
                        <FormItem label="开始时间" prop="kaishiriqi_x"
                                  :rules="{required: true,type:'array', message: '开始时间不能为空', trigger: 'blur'}">
                            <!--名称唯一-->
                            <DatePicker type="date" @on-change="setPublishTime" v-model="shengcheng.kaishiriqi_x"
                                        format="yyyy-MM-dd" :transfer="true"></DatePicker>
                        </FormItem>
                        <FormItem label="结束时间" prop="jieshuriqi_x"
                                  :rules="{required: true,type:'array' ,message: '结束时间不能为空', trigger: 'blur'}">
                            <!--名称唯一-->
                            <DatePicker type="date" @on-change="setPublishTime1" v-model="shengcheng.jieshuriqi_x"
                                        format="yyyy-MM-dd" :transfer="true"></DatePicker>
                        </FormItem>
                    </Form>
                </Modal>
                <Modal v-model="fagongzi" title="发工资" width="360">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="faCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">发工资</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="faCancel">取消</Button>
                        <Button type="primary" size="large" @click="f_gongzi">发工资</Button>
                    </div>
                    <Form ref="fafanggongzi" :model="fafanggongzi" :label-width="70">
                        <FormItem label="工资时间" prop="f_kaishiriqi_x"
                                  :rules="{required: true,type:'array', message: '时间不能为空', trigger: 'blur'}">
                            <!--名称唯一-->
                            <DatePicker type="month" @on-change="setPublishTime_f" v-model="fafanggongzi.f_kaishiriqi_x"
                                        format="yyyy-MM" :transfer="true"></DatePicker>
                        </FormItem>
                    </Form>
                </Modal>
                </Col>
            </Row>
        </Card>
        </Col>
        <!--表格-->
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Table border :columns="columns" :data="data"></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="totalnum1" :current="currentpage1" @on-change="changePage1" show-total></Page>
                </div>
            </div>
        </Card>
        </Col>
        <!--MODAL-->
        <!--排班弹窗-->
        <Modal v-model="yejimingxi" title="业绩明细" @on-ok="yejiOk" @on-cancel="yejiCancel" width="1020"
               :mask-closable="false">
            <Row>
                <!--按钮-->
                <Col :lg="24" :md="24">
                <Card>
                    <Row :gutter="10">
                        <!--变动类型-->
                        <Col :lg="5" :md="5">
                        <Input v-model="kw" placeholder="订单编号"></Input>
                        </Select>
                        </Col>
                        <!--查询-->
                        <Col :lg="5" :md="5">
                        <Button type="primary" @click="searchCustomerList1">查询</Button>
                        <Button type="primary" @click="qkym1">清空</Button>
                        </Col>

                    </Row>
                </Card>
                </Col>
                <!--表格-->
                <Col :lg="24" :md="24" style="margin-top: 10px">
                <Card>
                    <Table border :columns="yejicolumns" :data="yejidata"></Table>
                </Card>
                </Col>
            </Row>
        </Modal>
    </Row>
</template>

<script>
    export default {
        name: 'payXinchoutongji',
        data () {
            return {
                yejimingxi: false,
                fagongzi: false,
                yejitype: [
                    {
                        value: 'maimaiyeji',
                        label: '买卖业绩'
                    }
                ], kw: '',
                totalnum1: [],
                currentpage1: 1,
                exporturl: api_param.apiurl + '/gongzi/index?token=' + api_param.XAccessToken + '&exporturl=yes',
                bumenValue: [],
                bumenData: [],
                gongzishengcheng: false,
                shengcheng: {
                    kaishiriqi: '',
                    kaishiriqi_x: [],
                    jieshuriqi: '',
                    jieshuriqi_x: [],
                },
                fafanggongzi: {
                    f_kaishiriqi: '',
                    f_kaishiriqi_x: [],
                },
                mingxi: {
                    kaishiriqi: '',
                    jieshuriqi: '',
                    user_id: '',
                },
                columns: [
                    {
                        title: '员工编号',
                        key: 'u_employee_no',
                        align: 'center',
                        width: 128,
                        fixed: 'left',
                    },
                    {
                        title: '月份',
                        key: 'gongziriqi',
                        align: 'center',
                        width: 128
                    },
                    {
                        title: '开始日期',
                        key: 'kaishiriqi',
                        align: 'center',
                        width: 80,
                    },
                    {
                        title: '结束日期',
                        key: 'jieshuriqi',
                        align: 'center',
                        width: 80
                    },
                    {
                        title: '经纪人',
                        key: 'jingjiren',
                        align: 'center',
                        width: 128
                    },
                    {
                        title: '部门',
                        key: 'bumen',
                        align: 'center',
                        width: 128
                    },
                    {
                        title: '个人总业绩',
                        key: 'gr_zongyeji',
                        align: 'center',
                        width: 128
                    },
                    {
                        title: '个人提成金额',
                        key: 'gr_tichengjine',
                        align: 'center',
                        width: 128
                    },
                    {
                        title: '直营店总业绩',
                        key: 'zyd_zongyeji',
                        align: 'center',
                        width: 128
                    },
                    {
                        title: '直营店提成金额',
                        key: 'zyd_tichengjine',
                        align: 'center',
                        width: 128
                    },
                    {
                        title: '管理店总业绩',
                        key: 'gld_zongyeji',
                        align: 'center',
                        width: 128
                    },
                    {
                        title: '管理店提成金额',
                        key: 'gld_tichengjine',
                        align: 'center',
                        width: 128
                    },
                    {
                        title: '社保（个人）',
                        key: 'wuxian',
                        align: 'center',
                        width: 128
                    },
                    {
                        title: '基本工资',
                        key: 'jibengongzi',
                        align: 'center',
                        width: 128
                    },
                    {
                        title: '奖',
                        key: 'jiangli',
                        align: 'center',
                        width: 128
                    }, {
                        title: '罚',
                        key: 'fakuan',
                        align: 'center',
                        width: 128
                    },
                    {
                        title: '工资合计',
                        key: 'hejigongzi',
                        align: 'center',
                        width: 88,
                        fixed: 'right',
                    },
                    {
                        title: '状态',
                        key: 'is_payoff',
                        align: 'center',
                        width: 88,
                        fixed: 'right',
                    },
                    {
                        title: '操作',
                        key: 'caozuo',
                        align: 'center',
                        width: 108,
                        fixed: 'right',
                        render: (h, params) => {
                            var ret = [];
                            ret.push(h('Button', {
                                props: {
                                    type: 'primary',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.mingxi.jieshuriqi = params.row.jieshuriqi;
                                        this.mingxi.kaishiriqi = params.row.kaishiriqi;
                                        this.mingxi.user_id = params.row.user_id;
                                        this.mingxi.benzuin = params.row.benzuin;
                                        this.kw = '';
                                        this.totalnum2 = 0;
                                        this.yejidata = [];
                                        this.getMingxi();
                                        this.yejimingxi = true;
                                    }
                                }
                            }, '明细'));
                            if (params.row.is_payoff == '未发') {
                                ret.push(h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.mingxi.jieshuriqi = params.row.jieshuriqi;
                                            this.mingxi.kaishiriqi = params.row.kaishiriqi;
                                            this.mingxi.user_id = params.row.user_id;
                                            this.reEdit();
                                        }
                                    }
                                }, '重算'));
                            }

                            return h('div', ret);
                        }
                    }
                ],
                data: [],
                peizhi: [],
                sousuo: {
                    bumen: [],
                    users: [],
                    user: '',
                    d_id: '',
                    shijian: '',
                    shijian_x: '',
                },
                yejicolumns: [
                    {
                        title: '订单编号',
                        key: 'order_sn',
                        align: 'center',
                        fixed: 'left',
                        width: 120,
                        // fixed: 'left'
                    },
                    {
                        title: '月份',
                        key: 'dtime',
                        align: 'center',
                        width: 88,
                    },
                    {
                        title: '门店',
                        key: 'depart_name',
                        align: 'center',
                        width: 88,
                    },
                    {
                        title: '经纪人',
                        key: 'user_name',
                        align: 'center',
                        width: 128,
                    },
                    {
                        title: '成交时间',
                        key: 'cstctime',
                        align: 'center',
                        width: 128,
                    },
                    {
                        title: '业绩类型',
                        key: 'cost_type',
                        align: 'center',
                        width: 88,
                    },
                    {
                        title: '实收金额',
                        key: 'cost_money',
                        align: 'center',
                        width: 88,
                    },
                    {
                        title: '分成缘由',
                        key: 'reason',
                        align: 'center',
                        width: 88,
                    },
                    {
                        title: '实收日期',
                        key: 'cstctime',
                        align: 'center',
                        width: 128,
                    },
                    {
                        title: '分成金额',
                        key: 'divide_money',
                        align: 'center',
                        width: 88,
                    },
                    {
                        title: '提成（百分比）',
                        key: 'divide_per',
                        align: 'center',
                        width: 88,
                    }
                ],
                yejidata: [],
            };
        }
        , created: function () {
            this.getIndex();
        }, methods: {
            qkym1 () {
                this.kw = '';
                this.getMingxi();
            },
            searchCustomerList1 () {
                this.getMingxi();
            },
            reEdit () {
                this.$http.get(api_param.apiurl + '/gongzi/rsedit',
                    {
                        params: {
                            jieshuriqi: this.mingxi.jieshuriqi,
                            kaishiriqi: this.mingxi.kaishiriqi,
                            u_id: this.mingxi.user_id,
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    console.log(response);
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.getIndex();
                        this.$Message.success(response.data.message);
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
            changePage1 (page) {
                this.currentpage1 = page;
                this.getIndex();
            },
            searchCustomerList () {
                this.currentpage1 = 1;
                this.getIndex();
            },
            qkym () {
                this.sousuo.bumen = [];
                this.sousuo.users = [];
                this.sousuo.shijian = '';
                this.sousuo.shijian_x = [];
                this.sousuo.user = '';
                this.sousuo.d_id = '';
                this.getIndex();
            },
            setPublishTime (date) {
                this.shengcheng.kaishiriqi = date;
                this.shengcheng.kaishiriqi_x = [date];
            },
            setPublishTime_f (date) {
                this.fafanggongzi.f_kaishiriqi = date;
                this.fafanggongzi.f_kaishiriqi_x = [date];
            },
            setPublishTime1 (date) {
                this.shengcheng.jieshuriqi = date;
                this.shengcheng.jieshuriqi_x = [date];
            },
            setPublishTime2 (date) {
                this.sousuo.shijian = date;
                this.sousuo.shijian_x = [date];
            },
            shengchengCancel () {
                this.gongzishengcheng = false;
            },
            faCancel () {
                this.fagongzi = false;
            },
            getMingxi () {
                this.$http.get(api_param.apiurl + '/gongzi/getmingxi',
                    {
                        params: {
                            page: this.currentpage1,
                            jieshuriqi: this.mingxi.jieshuriqi,
                            kaishiriqi: this.mingxi.kaishiriqi,
                            user_id: this.mingxi.user_id,
                            benzuin: this.mingxi.benzuin,
                            pagesize: 10,
                            kw: this.kw,
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum2 = response.body.data.count;
                        this.yejidata = response.body.data.list;
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
            addshengcheng () {
                this.$refs['shengcheng'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/gongzi/add',
                            {
                                kaishiriqi: this.shengcheng.kaishiriqi,
                                jieshuriqi: this.shengcheng.jieshuriqi,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.gongzishengcheng = false;
                                this.getIndex();
                                this.$Message.success(response.data.message);
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
                            this.$Message.warning('更新失败');
                        });
                    }
                });
            },
            f_gongzi () {
                this.$refs['fafanggongzi'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/gongzi/payoff',
                            {
                                fagongzi: this.fafanggongzi.f_kaishiriqi,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.fagongzi = false;
                                this.getIndex();
                                this.$Message.success(response.data.message);
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
                            this.$Message.warning('更新失败');
                        });
                    }
                });
            },
            changeDepart (value, selectedData) {
                this.sousuo.bumen = selectedData;
                this.sousuo.d_id = value.pop();
                this.sousuo.users = this.peizhi.users[this.sousuo.d_id];
            },
            getIndex () {
                this.exporturl = api_param.apiurl + '/gongzi/index?token=' + api_param.XAccessToken + '&exporturl=yes' + '&shijian=' + this.sousuo.shijian + '&d_id=' + this.sousuo.d_id + '&user=' + this.sousuo.user;
                this.$http.get(api_param.apiurl + '/gongzi/index',
                    {
                        params: {
                            page: this.currentpage1,
                            pagesize: 10,
                            shijian: this.sousuo.shijian,
                            d_id: this.sousuo.d_id,
                            user: this.sousuo.user,
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    console.log(response);
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum1 = response.body.data.count;
                        this.data = response.body.data.list;
                        this.peizhi = response.body.data.peizhi;
                        // if(!this.sousuo.shijian_x){
                        //     this.sousuo.shijian_x=[response.body.data.shijian.thismonth_start];
                        //     this.sousuo.shijian=response.body.data.shijian.thismonth_start;
                        // }
                        this.shengcheng.kaishiriqi_x = [response.body.data.shijian.thismonth_start];
                        this.fafanggongzi.f_kaishiriqi_x = [response.body.data.shijian.thismonth_start];
                        this.shengcheng.jieshuriqi_x = [response.body.data.shijian.thismonth_end];
                        this.shengcheng.kaishiriqi = response.body.data.shijian.thismonth_start;
                        this.fafanggongzi.f_kaishiriqi = response.body.data.shijian.thismonth_start;
                        this.shengcheng.jieshuriqi = response.body.data.shijian.thismonth_end;
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
        }
    };
</script>

<style scoped>

</style>
