<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="5">
                <Col :lg='3' :md='3'>
                <Input v-model="keyword" placeholder="员工名称、员工编号、员工电话"></Input>
                </Col>
                <Col :lg='8' :md='8'>
                <Button type="primary" @click="doSearch">查询</Button>
                <Button type="primary" @click="clearSearch">清空</Button>


                <Button type="primary" @click="piliangfenpei = true">批量分配方案</Button>
                </Col>
                <Modal v-model="piliangfenpei" title="工资配置">
                    <div slot="footer">
                        <Button type="text" size="large" @click="pl_modalCancel">取消</Button>
                        <Button type="primary" size="large" @click="pl_sz">确定</Button>
                    </div>
                    <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" class="piliangfenpei"
                          :label-width="100">
                        <FormItem label="个人提成方案" style="margin-top:10px ">
                            <Row>
                                <Col :lg="16" :md="16">
                                <Cascader :data="tichengData" :value.sync="pl_ticheng" filterable change-on-select
                                          @on-change="pl_ticheng_ok" :transfer="true"></Cascader>
                                </Col>
                            </Row>
                        </FormItem>
                        <FormItem label="管理店提成方案" style="margin-top:10px ">
                            <Row>
                                <Col :lg="16" :md="16">
                                <Cascader :data="tichengData" :value.sync="fz_pl_ticheng" filterable change-on-select
                                          @on-change="fz_pl_ticheng_ok" :transfer="true"></Cascader>
                                </Col>
                            </Row>
                        </FormItem>
                        <FormItem label="直营店提成方案" style="margin-top:10px ">
                            <Row>
                                <Col :lg="16" :md="16">
                                <Cascader :data="tichengData" :value.sync="fz_zy_ticheng" filterable change-on-select
                                          @on-change="fz_zy_ticheng_ok" :transfer="true"></Cascader>
                                </Col>
                            </Row>
                        </FormItem>
                    </Form>
                </Modal>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Table border :columns="gongziColumns" :data="userList" script @on-selection-change="selectionok"></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="totalnum" :current="currentpage" @on-change="changePage" show-total></Page>
                </div>
            </div>
            <Modal v-model="gongziticheng" title="提成+工资方案">
                <div slot="header">
                    <a class="ivu-modal-close" @click="modalCancel" style="display: block!important;"><i
                            class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                    <div class="ivu-modal-header-inner">{{usermodaltitle}}</div>
                </div>
                <div slot="footer">
                    <Button type="text" size="large" @click="modalCancel">取消</Button>
                    <Button type="primary" size="large" @click="modalOk">确定</Button>
                </div>
                <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="120">
                    <div >
                        <FormItem label="部门（员工）" prop="d_name">
                            <span>{{formValidate.d_name}}</span><span>({{formValidate.u_name}})</span>
                        </FormItem>
                        <FormItem label="个人提成方案" prop="ticheng_id">
                            <Cascader :data="tichengData" :value.sync="formValidate.ticheng" filterable change-on-select
                                      @on-change="searchticheng" :transfer="true"></Cascader>
                        </FormItem>
                        <FormItem label="管理店提成方案" prop="fuzerenticheng_id">
                            <Cascader :data="tichengData" :value.sync="formValidate.fuzerenticheng" filterable
                                      change-on-select
                                      @on-change="fuzeticheng" :transfer="true"></Cascader>
                        </FormItem>
                        <FormItem label="直营店提成方案" prop="fuzerenticheng_zyid">
                            <Cascader :data="tichengData" :value.sync="formValidate.fuzerenticheng_zy" filterable
                                      change-on-select
                                      @on-change="fuzeticheng_zy" :transfer="true"></Cascader>
                        </FormItem>
                        <FormItem label="基本工资(元)" prop="jibengongzi">
                            <Input v-model="formValidate.jibengongzi"></Input>
                        </FormItem>
                        <FormItem label="五险(个人)" prop="wuxiangeren">
                            <Input v-model="formValidate.wuxiangeren"></Input>
                        </FormItem>
                        <FormItem label="五险一金(个人)" prop="wuxianyijingeren">
                            <Input v-model="formValidate.wuxianyijingeren"></Input>
                        </FormItem>
                    </div>
                </Form>
            </Modal>
        </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'srZonghe',
        data () {
            return {
                piliangfenpei: false,//批量分配方案
                gongziticheng: false, //设置工资+提成
                totalnum: 0,
                keyword: '',
                pl_ticheng_id: '',
                pl_ticheng: [],
                fz_pl_ticheng_id: '',
                fz_zy_ticheng_id: '',
                fz_pl_ticheng: [],
                fz_zy_ticheng: [],
                tichengleixing: '',
                selection: [],
                currentpage: 1,
                formValidate: {
                    jibengongzi: '',
                    wuxiangeren: '',
                    wuxianyijingeren: '',
                    departpath: [],
                    ticheng: [],
                    ticheng_id: '',
                    name: '',
                    fuzerenticheng: [],
                    fuzerenticheng_zy: []
                },
                ruleValidate: {},
                gongziColumns: [
                    {
                        type: 'selection',
                        width: 60,
                        align: 'center'
                    },
                    {
                        title: '员工编号',
                        key: 'u_employee_id',
                        align: 'center'
                    },
                    {
                        title: '部门',
                        key: 'd_name',
                        align: 'center'
                    },
                    {
                        title: '员工名称',
                        key: 'u_name',
                        align: 'center'
                    },
                    {
                        title: '提成方案',
                        key: 'fanganmingcheng',
                        align: 'center'
                    },
                    {
                        title: '管理店提成方案',
                        key: 'fuzerenfanganmingcheng',
                        align: 'center'
                    },
                    {
                        title: '直营店提成方案',
                        key: 'fuzerenfanganmingcheng_zy',
                        align: 'center'
                    },
                    {
                        title: '基本工资(元)',
                        key: 'jibengongzi',
                        align: 'center'
                    },
                    {
                        title: '五险(个人)',
                        key: 'wuxiangeren',
                        align: 'center'
                    },
                    {
                        title: '五险一金(个人)',
                        key: 'wuxianyijingeren',
                        align: 'center'
                    },
                    {
                        title: '操作',
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
                                            if (params.row.ticheng) {
                                                this.formValidate.ticheng = JSON.parse(params.row.ticheng);
                                            }
                                            if (params.row.fuzerenticheng) {
                                                this.formValidate.fuzerenticheng = JSON.parse(params.row.fuzerenticheng);
                                            }
                                            if (params.row.fuzerenticheng_zy) {
                                                this.formValidate.fuzerenticheng_zy = JSON.parse(params.row.fuzerenticheng_zy);
                                            }
                                            this.formValidate.d_name = params.row.d_name;
                                            this.tichengleixing = params.row.tichengleixing;
                                            this.formValidate.u_name = params.row.u_name;
                                            this.formValidate.fuzerenticheng_id = params.row.fuzerenticheng_id;
                                            this.formValidate.fuzerenticheng_zyid = params.row.fuzerenticheng_zyid;
                                            this.formValidate.jibengongzi = params.row.jibengongzi;
                                            this.formValidate.wuxiangeren = params.row.wuxiangeren;
                                            this.formValidate.ticheng_id = params.row.ticheng_id;
                                            this.formValidate.wuxianyijingeren = params.row.wuxianyijingeren;
                                            this.formValidate.u_id = params.row.u_id;
                                            this.gongziticheng = true;

                                        }
                                    }
                                }, '设置')
                            ]);
                        }
                    }
                ],
                userList: []
            };
        },
        created () {
            this.getIndex();
            this.getSetting();
        },
        methods: {
            //                状态
            selectionok (selection) {
                this.selection = selection;
                // console.log(this.selection);
            },
            pl_sz () {
                if (this.selection.length == 0) {
                    this.$Message.success('请选择员工');
                    return;
                }
                var i=0
                if (this.pl_ticheng.length > 0) {
                    i=1
                }
                if (this.fz_pl_ticheng.length > 0) {
                    i=1
                }
                if (this.fz_zy_ticheng.length > 0) {
                    i=1
                }
                if(!i){
                    this.$Message.success('您必须选择一个方案');
                    return;
                }
                this.$http.post(api_param.apiurl + 'usergongzi/pl_sz',
                    {
                        'ticheng': this.pl_ticheng,
                        'fuzerenticheng': this.fz_pl_ticheng,
                        'fuzerenticheng_id': this.fz_pl_ticheng_id,
                        'fuzerenticheng_zy': this.fz_zy_ticheng,
                        'fuzerenticheng_zyid': this.fz_zy_ticheng_id,
                        'ticheng_id': this.pl_ticheng_id,
                        'selection': this.selection,
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.$Message.success(response.data.message);
                        this.pl_modalCancel();
                        this.getIndex();
                    } else if (response.data.code == 400) {
                        this.$Message.success(response.data.message);
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    //console.log(response)
                });
            }
            ,
            modalCancel () {
                this.gongziticheng = false;
                this.formValidate.ticheng = [];
                this.formValidate.ticheng_id = '';
                this.formValidate.fuzerenticheng_id = '';
                this.formValidate.fuzerenticheng = '';
                this.formValidate.fuzerenticheng_zyid = '';
                this.formValidate.fuzerenticheng_zy = '';
                this.formValidate.jibengongzi = '';
                this.formValidate.wuxiangeren = '';
                this.formValidate.wuxianyijingeren = '';
            }
            ,
            pl_modalCancel () {
                this.pl_ticheng = [];
                this.fz_pl_ticheng = [];
                this.fz_zy_ticheng = [];
                this.piliangfenpei = false;
            }
            ,
            pl_Cancel () {
                this.pl_ticheng = [];
                this.fz_pl_ticheng = [];
                this.fz_zy_ticheng = [];
            }
            ,
            pl_modalOk () {

            }
            ,
            doSearch () {
                this.currentpage = 1;
                this.getIndex();
            }
            ,
            clearSearch () {
                this.keyword = '';
                this.getIndex();
            }
            ,
            //提交
            modalOk () {
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'usergongzi/edit',
                            {
                                'u_id': this.formValidate.u_id,
                                'ticheng': this.formValidate.ticheng,
                                'ticheng_id': this.formValidate.ticheng_id,
                                'fuzerenticheng_id': this.formValidate.fuzerenticheng_id,
                                'fuzerenticheng': this.formValidate.fuzerenticheng,
                                'fuzerenticheng_zyid': this.formValidate.fuzerenticheng_zyid,
                                'fuzerenticheng_zy': this.formValidate.fuzerenticheng_zy,
                                'jibengongzi': this.formValidate.jibengongzi,
                                'wuxiangeren': this.formValidate.wuxiangeren,
                                'wuxianyijingeren': this.formValidate.wuxianyijingeren,

                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.formValidate.ticheng = [];
                                this.$refs['formValidate'].resetFields();
                                this.$Message.success(response.data.message);
                                this.modalCancel();
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
                            console.log(response);
                        });
                    }

                });
            }
            ,
            changePage (page) {
                this.currentpage = page;
                this.getIndex();
            }
            ,
            searchticheng (value, selectedData) {
                this.formValidate.ticheng_id = value[value.length - 1];//console.log(this.formValidate.depart);
                this.formValidate.ticheng = value;//console.log(this.formValidate.depart);
            }
            ,
            pl_ticheng_ok (value, selectedData) {
                this.pl_ticheng_id = value[value.length - 1];//console.log(this.formValidate.depart);
                this.pl_ticheng = value;//console.log(this.formValidate.depart);
            }
            ,
            fz_pl_ticheng_ok (value, selectedData) {
                this.fz_pl_ticheng_id = value[value.length - 1];//console.log(this.formValidate.depart);
                this.fz_pl_ticheng = value;//console.log(this.formValidate.depart);
            },
            fz_zy_ticheng_ok (value, selectedData) {
                this.fz_zy_ticheng_id = value[value.length - 1];//console.log(this.formValidate.depart);
                this.fz_zy_ticheng = value;//console.log(this.formValidate.depart);
            }
            ,
            fuzeticheng (value, selectedData) {
                this.formValidate.fuzerenticheng_id = value[value.length - 1];//console.log(this.formValidate.depart);
                this.formValidate.fuzerenticheng = value;//console.log(this.formValidate.depart);
            }
            ,
            fuzeticheng_zy (value, selectedData) {
                this.formValidate.fuzerenticheng_zyid = value[value.length - 1];//console.log(this.formValidate.depart);
                this.formValidate.fuzerenticheng_zy = value;//console.log(this.formValidate.depart);
            }
            ,
            getSetting () { //获取下拉菜单
                this.$http.get(api_param.apiurl + 'usergongzi/getsetting', {
                    params: {},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.tichengData = response.data.data.tichengData;
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
                    console.log(response);
                });
            }
            ,
            getIndex () { //列表页
                this.usermodaltitle = '';
                this.editUser = false;
                this.$http.get(api_param.apiurl + 'usergongzi/getindex', {
                    params: {
                        did: this.departkey,
                        rid: this.rolekey,
                        sid: this.statuskey,
                        kw: this.keyword,
                        page: this.currentpage
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum = response.data.data.totalnum;
                        this.userList = response.data.data.userlist;
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        this.$Message.warning(response.data.message);
                    }
                    //console.log(this.purviews);
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            }
        }
    }
    ;
</script>

<style scoped>
    .piliangfenpei .ivu-form-item {
        margin-bottom: 0px !important;
    }

    .piliangfenpei .ivu-col {
        text-align: center;
        line-height: 32px;
    }
</style>
