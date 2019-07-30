<style lang="less">

</style>

<template>
    <Row>
        <Col :lg="24" :md="24" style="margin-bottom: 10px">
        <Card>
            <Row :gutter="5">
                <Col :lg="3" :md="3">
                <Input v-model="guanjianzi" placeholder="关键字"></Input>
                </Col>
                <Col :lg="3" :md="3">
                <Button type="primary" @click="doSearch">查询</Button>
                <Button type="primary" @click="clearSearch">清空</Button>
                </Col>
                <Col :lg="2" :md="2" offset="16">
                <!--<Row type="flex" justify="end">-->
                    <!--<Col>-->
                    <!--<Button type="primary" @click="addPutong">新增</Button>-->
                    <!--<Modal v-model="addPutongModal" title="添加配置内容" :mask-closable="false">-->
                        <!--<div slot="header">-->
                            <!--<a class="ivu-modal-close" @click="add1Cancel" style="display: block!important;"><i-->
                                    <!--class="ivu-icon ivu-icon-ios-close-empty"></i></a>-->
                            <!--<div class="ivu-modal-header-inner">添加配置内容</div>-->
                        <!--</div>-->
                        <!--<div slot="footer">-->
                            <!--<Button type="text" size="large" @click="add1Cancel">取消</Button>-->
                            <!--<Button type="primary" size="large" @click="add1Ok">确定</Button>-->
                        <!--</div>-->
                        <!--<Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="100">-->
                            <!--<FormItem label="配置名称" prop="name">-->
                                <!--<Input v-model="formValidate.name" placeholder=""></Input>-->
                            <!--</FormItem>-->
                            <!--<FormItem label="配置英文简称" prop="jname">-->
                                <!--<Input v-model="formValidate.jname" placeholder=""></Input>-->
                            <!--</FormItem>-->

                        <!--</Form>-->
                    <!--</Modal>-->
                    <!--</Col>-->
                <!--</Row>-->
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="20">
                <Col :lg="12" :md="12">
                <div class="peizhiLeft">
                    <Table border :columns="peizhiLeftTable" :data="peizhiLeftData" @on-row-click="rowClick1" highlight-row></Table>
                    <Modal v-model="xiugaiPutongChildModal" title="修改明细" :mask-closable="false">
                        <div slot="header">
                            <a class="ivu-modal-close" @click="xiugaiCancel" style="display: block!important;"><i
                                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                            <div class="ivu-modal-header-inner">修改明细</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="xiugaiCancel">取消</Button>
                            <Button type="primary" size="large" @click="xiugaiOk">确定</Button>
                        </div>
                        <Form ref="xiugaiPutongChildformValidate" :model="xiugaiPutongChildformValidate"
                              :rules="xiugaiPutongChildruleValidate" :label-width="90">
                            <FormItem label="配置内容" prop="count">
                                <Input v-model="xiugaiPutongChildformValidate.count" placeholder="" disabled></Input>
                            </FormItem>
                            <FormItem label="名称" prop="name">
                                <Input v-model="xiugaiPutongChildformValidate.name" placeholder=""></Input>
                            </FormItem>
                        </Form>
                    </Modal>
                </div>
                <Row type="flex" justify="end" style="margin-top: 10px">
                    <Col>
                    <Page :total="totalnum1" :current="currentpage1" @on-change="changePage1" :page-size="pageSize"
                          show-total></Page>
                    </Col>
                </Row>
                </Col>
                <Col :lg="12" :md="12">
                <div class="peizhiRight">
                    <Table border :columns="peizhiRightTable" :data="peizhiRightData" @on-row-click="rowClick2"></Table>
                    <Modal v-model="addPutongChildModal" title="新增明细" :mask-closable="false">
                        <div slot="header">
                            <a class="ivu-modal-close" @click="add2Cancel" style="display: block!important;"><i
                                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                            <div class="ivu-modal-header-inner">新增明细</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="add2Cancel">取消</Button>
                            <Button type="primary" size="large" @click="add2Ok">确定</Button>
                        </div>
                        <Form ref="addPutongChildModalformValidate" :model="addPutongChildModalformValidate"
                              :rules="addPutongChildModalruleValidate" :label-width="90">
                            <FormItem label="配置内容" prop="count">
                                <Input v-model="addPutongChildModalformValidate.count" placeholder="" disabled></Input>
                            </FormItem>
                            <FormItem label="名称" prop="name">
                                <Input v-model="addPutongChildModalformValidate.name" placeholder=""></Input>
                            </FormItem>
                        </Form>
                    </Modal>
                </div>
                <Row type="flex" justify="end" style="margin-top: 10px">
                    <Col>
                    <Page :total="totalnum2" :current="currentpage2" @on-change="changePage2" :page-size="pageSize"
                          show-total></Page>
                    </Col>
                </Row>
                </Col>
            </Row>
        </Card>
        </Col>
    </Row>
</template>
<script>
    let inx = '';
    let inx2 = '';
    export default {
        name: 'putongpeizhi',
        data() {
            return {
                guanjianzi: '',
                totalnum1:0,
                currentpage1:1,
                totalnum2:0,
                currentpage2:1,
                pageSize:10,
                //        新增
                addPutongModal: false,
                addPutongChildModal: false,
                xiugaiPutongChildModal: false,
                addPutongChildModalformValidate: {
                    count: '',
                    name: ''
                },
                addPutongChildModalruleValidate:{
                    name: [
                        {required: true, message: '请输入配置名称', trigger: 'blur'}
                    ],

                },
                xiugaiPutongChildformValidate: {
                    count: '',
                    name: ''
                },
                xiugaiPutongChildruleValidate:{
                    name: [
                        {required: true, message: '请输入配置名称', trigger: 'blur'}
                    ],

                },
                formValidate: {
                    jname: '',
                    name: ''
                },
                ruleValidate: {
                    name: [
                        {required: true, message: '请输入配置名称', trigger: 'blur'}
                    ],
                    jname: [
                        {required: true, message: '请输入配置英文简称', trigger: 'blur'}
                    ],
                },
//        左侧内容
                peizhiLeftTable: [
                    {
                        title: '#',
                        key: 'peizhi_id',
                        align: 'center',
                        // width: '10%'
                    },
                    {
                        title: '配置内容',
                        key: 'content',
                        align: 'center',
                    },
                    {
                        title: '操作',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('a', {
                                    props: {},
                                    domProps: {
                                        innerHTML: '新增明细'
                                    },
                                    on: {
                                        click: () => {
                                            this.addPutongChild(params)
                                        }
                                    }
                                }),
                            ])
                        }
                    }],
                peizhiLeftData: [],
//右侧内容
                peizhiRightTable: [
                    {
                        title: ' 名称',
                        key: 'mingcheng',
                        align: 'center',
                    },
                    {
                        title: '操作',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
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
                                            this.xiugaiPutongChild(params)
                                        }
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.removeChild(params);
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                peizhiRightData: []
            }
        },
        methods: {
            rowClick1(data, index) {
                inx = index;
                this.xiugaiPutongChildformValidate.count = data.base_name;
                this.getputongpeizhiChild();

            },
            //
            rowClick2(data, index) {
                inx2 = index;
                this.xiugaiPutongChildformValidate.name = data.child_name;
            },
            //添加普通配置内容
            addPutong() {
                this.addPutongModal = true;
                this.formValidate.name = '';
                this.formValidate.jname = '';
            },
            add1Ok() {
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/settingbase/add',
                            {
                                base_id: '',
                                base_shorthand: this.formValidate.jname,
                                base_name: this.formValidate.name
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['formValidate'].resetFields();
                                this.addPutongModal = false;
                                this.$Message.success(response.data.message);
                                this.getputongpeizhi();
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
            add1Cancel() {
                this.$refs['formValidate'].resetFields();
                this.addPutongModal = false;
            },
            //    获取普通配置列表
            changePage1(page) {
                this.currentpage1 = page;
                this.getputongpeizhi();
            },
            getputongpeizhi() {
                this.$http.get(api_param.apiurl + '/settingbase/getlist',
                    {
                        params: {
                            page: this.currentpage1,
                            pagesize: 10,
                            kw: this.guanjianzi
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    console.log(response);
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum1 = parseInt(response.body.data.totalnum);
                        this.peizhiLeftData = response.body.data.list;
                        for (let i = 0; i < this.peizhiLeftData.length; i++) {
                            this.peizhiLeftData[i].content = this.peizhiLeftData[i].base_name;
                            this.peizhiLeftData[i].peizhi_id = this.peizhiLeftData[i].base_id;
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
            //添加普通配配置子配置
            addPutongChild(params) {
                console.log(params);
                this.addPutongChildModal = true;
                this.addPutongChildModalformValidate.count = params.row.base_name;
                this.addPutongChildModalformValidate.name = ''
            },
            add2Ok() {
                this.$refs['addPutongChildModalformValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/settingbase/addchild',
                            {
                                base_id: this.peizhiLeftData[inx].base_id,
                                child_name: this.addPutongChildModalformValidate.name,
                                // sort:2
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['addPutongChildModalformValidate'].resetFields();
                                this.addPutongChildModal = false;
                                this.$Message.success(response.data.message);
                                this.getputongpeizhiChild();
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
            add2Cancel() {
                this.$refs['addPutongChildModalformValidate'].resetFields();
                this.addPutongChildModal = false;
            },
            //    获取普通配置子配置
            changePage2(page) {
                this.currentpage2 = page;
                this.getputongpeizhiChild();
            },
            getputongpeizhiChild() {
                this.$http.post(api_param.apiurl + '/settingbase/getchildlist',
                    {
                        base_id: this.peizhiLeftData[inx].base_id
                    },
                    {
                        emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        console.log(response);
                        this.totalnum2 = parseInt(response.body.data.length);
                        this.peizhiRightData = response.body.data;
                        for (let i = 0; i < this.peizhiRightData.length; i++) {
                            this.peizhiRightData[i].mingcheng = this.peizhiRightData[i].child_name;
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
            //    修改普通配置子配置
            xiugaiPutongChild(params) {
                this.xiugaiPutongChildModal = true;
                this.xiugaiPutongChildformValidate.count = this.peizhiLeftData[inx].base_name;
                this.xiugaiPutongChildformValidate.name = this.peizhiRightData[inx2].child_name;
            },
            xiugaiOk() {
                this.$refs['xiugaiPutongChildformValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/settingbase/editchild',
                            {
                                base_id: this.peizhiLeftData[inx].base_id,
                                child_id: this.peizhiRightData[inx2].child_id,
                                child_name: this.xiugaiPutongChildformValidate.name,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['xiugaiPutongChildformValidate'].resetFields();
                                this.xiugaiPutongChildModal = false;
                                this.$Message.success(response.data.message);
                                this.getputongpeizhiChild();
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
            xiugaiCancel() {
                this.$refs['xiugaiPutongChildformValidate'].resetFields();
                this.xiugaiPutongChildModal = false;
            },
            //    删除普通配置子配置
            removeChild(params) {
                console.log(params.row.dt_id);
                this.$Modal.confirm({
                    title: '删除部门',
                    content: '确定要删除吗',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + '/settingbase/delchild',
                            {
                                base_id: this.peizhiLeftData[inx].base_id,
                                child_id: this.peizhiRightData[inx2].child_id,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.getputongpeizhiChild();
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
                            this.$Message.warning('删除失败');
                        });
                    }
                });
            },

            //查询
            doSearch() {
                this.currentpage1 = 1;
                this.getputongpeizhi();
                this.peizhiRightData=''
            },
            //清空
            clearSearch() {
                this.guanjianzi = '';
                this.getputongpeizhi();
                this.peizhiRightData=''
            },
            //

        },
        created: function () {
            this.getputongpeizhi();
            this.getputongpeizhiChild();
        },
        computed: {}
    }
</script>