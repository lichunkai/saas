<style lang="less">
</style>

<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row type="flex" justify="end">
                <Col>
                <Button type="primary" @click="shezhiname = true">新增名称</Button>
                <Modal v-model="shezhiname" title="新增名称" width="360">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="addCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">新增名称</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="addCancel">取消</Button>
                        <Button type="primary" size="large" @click="addOk">确定</Button>
                    </div>
                    <Form ref="fangandata" :model="fangandata" :label-width="88">
                        <FormItem label="方案名称" prop="fanganmingcheng"
                                  :rules="{required: true, message: '方案名称不能为空', trigger: 'blur'}">
                            <!--名称唯一-->
                            <Input v-model="fangandata.fanganmingcheng" placeholder=""></Input>
                        </FormItem>
                        <FormItem label="方案说明" prop="fanganshuoming">
                            <Input v-model="fangandata.fanganshuoming" placeholder=""></Input>
                        </FormItem>
                    </Form>
                </Modal>
                <Modal v-model="shezhiyeji" title="新增业绩方案" width="360">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="addyejiCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">新增业绩方案</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="addyejiCancel">取消</Button>
                        <Button type="primary" size="large" @click="addyejiOk">确定</Button>
                    </div>
                    <Form ref="yejidateok" :model="yejidateok" :label-width="88">
                        <FormItem label="业绩名称" prop="yejimingcheng"
                                  :rules="{required: true, message: '业绩名称不能为空', trigger: 'blur'}">
                            <!--名称唯一-->
                            <Input v-model="yejidateok.yejimingcheng" placeholder=""></Input>
                        </FormItem>
                    </Form>
                </Modal>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Row :gutter="10">
                <Col :lg="8" :md="8">
                <Table :columns="fenColumns" :data="fenData" highlight-row border script
                       @on-current-change="changeyeji"></Table>
                <div style="margin: 10px;overflow: hidden">
                    <div style="float: right;">
                        <Page :total="totalnum1" :current="currentpage1" @on-change="changePage1" :page-size="pageSize"
                              show-total>
                        </Page>
                    </div>
                </div>
                <Modal v-model="fenchengpeizhi" title="分成配置">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="fenchengCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">分成配置</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="fenchengCancel">取消</Button>
                        <Button type="primary" size="large" @click="addfenchengOk">确定</Button>
                    </div>
                    <Form ref="formValidate" :model="formValidate"
                          style="text-align: center;line-height: 32px">
                        <Row>

                            <Col :lg="10" :md="10" offset="4">
                            <strong>业绩</strong>
                            </Col>
                            <Col :lg="10" :md="10">
                            <strong>提成比例</strong>
                            </Col>
                        </Row>
                        <Row style="margin-top: 10px">
                            <Col :lg="4" :md="4">
                            <p>业绩<=</p>
                            </Col>
                            <Col :lg="10" :md="10">
                            <FormItem prop="">
                                <Input v-model="formValidate.name[0]" placeholder=""><span
                                    slot="append">元</span></Input>
                            </FormItem>
                            </Col>
                            <Col :lg="10" :md="10">
                            <FormItem prop="">
                                <Input v-model="formValidate.fencheng[0]" placeholder=""><span
                                    slot="append">%</span></Input>
                            </FormItem>
                            </Col>
                        </Row>
                        <Row style="margin-top: 10px">
                            <Col :lg="4" :md="4">
                            <p>业绩<=</p>
                            </Col>
                            <Col :lg="10" :md="10">
                            <FormItem prop="">
                                <Input v-model="formValidate.name[1]" placeholder=""><span
                                    slot="append">元</span></Input>
                            </FormItem>
                            </Col>
                            <Col :lg="10" :md="10">
                            <FormItem prop="">
                                <Input v-model="formValidate.fencheng[1]" placeholder=""><span
                                    slot="append">%</span></Input>
                            </FormItem>
                            </Col>
                        </Row>
                        <Row style="margin-top: 10px">
                            <Col :lg="4" :md="4">
                            <p>业绩<=</p>
                            </Col>
                            <Col :lg="10" :md="10">
                            <FormItem prop="">
                                <Input v-model="formValidate.name[2]" placeholder=""><span
                                    slot="append">元</span></Input>
                            </FormItem>
                            </Col>
                            <Col :lg="10" :md="10">
                            <FormItem prop="">
                                <Input v-model="formValidate.fencheng[2]" placeholder=""><span
                                    slot="append">%</span></Input>
                            </FormItem>
                            </Col>
                        </Row>
                        <Row style="margin-top: 10px">
                            <Col :lg="4" :md="4">
                            <p>业绩<=</p>
                            </Col>
                            <Col :lg="10" :md="10">
                            <FormItem prop="">
                                <Input v-model="formValidate.name[3]" placeholder=""><span
                                    slot="append">元</span></Input>
                            </FormItem>
                            </Col>
                            <Col :lg="10" :md="10">
                            <FormItem prop="">
                                <Input v-model="formValidate.fencheng[3]" placeholder=""><span
                                    slot="append">%</span></Input>
                            </FormItem>
                            </Col>
                        </Row>
                        <Row style="margin-top: 10px">
                            <Col :lg="4" :md="4">
                            <p>业绩<=</p>
                            </Col>
                            <Col :lg="10" :md="10">
                            <FormItem prop="">
                                <Input v-model="formValidate.name[4]" placeholder=""><span
                                    slot="append">元</span></Input>
                            </FormItem>
                            </Col>
                            <Col :lg="10" :md="10">
                            <FormItem prop="">
                                <Input v-model="formValidate.fencheng[4]" placeholder=""><span
                                    slot="append">%</span></Input>
                            </FormItem>
                            </Col>
                        </Row>
                        <Row style="margin-top: 10px">
                            <Col :lg="4" :md="4">
                            <p>业绩<=</p>
                            </Col>
                            <Col :lg="10" :md="10">
                            <FormItem prop="">
                                <Input v-model="formValidate.name[5]" placeholder=""><span
                                    slot="append">元</span></Input>
                            </FormItem>
                            </Col>
                            <Col :lg="10" :md="10">
                            <FormItem prop="">
                                <Input v-model="formValidate.fencheng[5]" placeholder=""><span
                                    slot="append">%</span></Input>
                            </FormItem>
                            </Col>
                        </Row>

                    </Form>
                </Modal>
                </Col>
                <Col :lg="16" :md="16">
                <Table :columns="peiColumns" :data="peiData" border script></Table>
                <div style="margin: 10px;overflow: hidden">
                    <div style="float: right;">
                        <Page :total="totalnum2" :current="currentpage1" @on-change="changePage1" :page-size="pageSize"
                              show-total>
                        </Page>
                    </div>
                </div>
                </Col>
            </Row>
        </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'srTicheng',
        data() {
            return {
                fenchengpeizhi: false,
                shezhiname: false,
                shezhiyeji: false,
                formValidate: {
                    name: [],
                    fencheng: [],
                },
                yejidateok: {
                    yejimingcheng: '',
                },
                currentpage1: 1,
                currentpage2: 1,
                totalnum2: 0,
                totalnum1: 0,
                yejidata: [],
                fangandata: {
                    fanganmingcheng: '',
                    fanganshuoming: '',
                },
                fenColumns: [
                    {
                        title: '方案名称',
                        key: 'fanganmingcheng',
                        align: 'center'
                    },
                    {
                        title: '方案说明',
                        key: 'fanganshuoming',
                        align: 'center'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('a', {
                                    props: {},
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.shezhiyeji = true;
                                            this.yejidata = params.row;
                                        }
                                    }
                                }, '新增'),
                                h('a', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },

                                    on: {
                                        click: () => {
                                            this.removeChild(params.row.scm_id);
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                fenData: [],
                shezhidata: [],
                peiColumns: [
                    {
                        title: '业绩名称',
                        key: 'yejimingcheng',
                        align: 'center'
                    },
                    {
                        title: '状态',
                        key: 'zhuangtai',
                        align: 'center'
                    },
                    {
                        title: '操作',
                        key: 'name',
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
                                            this.fenchengpeizhi = true;
                                            this.shezhidata = params.row;
                                            var yeji = JSON.parse(this.shezhidata.yeji);
                                            for (var i = 0; i < yeji.length; i++) {
                                                this.formValidate.name[i]=yeji[i].name;
                                                this.formValidate.fencheng[i]=yeji[i].fencheng;
                                            }
                                        }
                                    }
                                }, '设置'),
                                h('a', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },

                                    on: {
                                        click: () => {
                                            this.removeChild1(params.row.scmy_id);
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                peiData: [],
                selectfangan: []
            };
        }, created: function () {
            this.getIndex();
        },
        methods: {
            changePage1(page) {
                this.currentpage1 = page;
                this.getIndex();
            },
            changePage2(page) {
                this.currentpage2 = page;
                this.getYeji();
            },
            getIndex() {
                this.$http.get(api_param.apiurl + '/salary_config/index',
                    {
                        params: {
                            page: this.currentpage1,
                            pagesize: 10,
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    console.log(response);
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum1 = response.body.data.count;
                        this.fenData = response.body.data.list;
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
            changeyeji(currentRow, oldCurrentRow) {
                this.selectfangan = currentRow;
                this.getYeji();
            },
            getYeji() {
                this.$http.get(api_param.apiurl + '/salary_config_yeji/index',
                    {
                        params: {
                            page: this.currentpage2,
                            scm_id: this.selectfangan.scm_id,
                            pagesize: 10,
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    console.log(response);
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum2 = response.body.data.count;
                        this.peiData = response.body.data.list;
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
            }, addfenchengOk() {
                var pd = true;
                var pd1 = true;
                for (var i = 0; i < 5; i++) {
                    var name = this.formValidate.name[i];
                    var fencheng = this.formValidate.fencheng[i];

                    if (i>0 && name && fencheng) {
                        var dangqian=Number(this.formValidate.name[i]);
                        var qian=Number(this.formValidate.name[i-1]);
                        if (dangqian<qian) {
                            pd = false;
                        }
                    } else if (!fencheng && name) {
                        pd1 = false;
                    }
                }
                if (!pd) {
                    this.$Message.warning('您设置的业绩后面项，必须大于前面输入的业绩项！');
                }
                if (!pd1) {
                    this.$Message.warning('业绩项不为空的情况下，必须设置提成比例！');
                }
                if (pd && pd1) {
                    this.$http.post(api_param.apiurl + '/salary_config_yeji/edit',
                        {
                            scmy_id: this.shezhidata.scmy_id,
                            name: this.formValidate.name,
                            fencheng: this.formValidate.fencheng,
                        },
                        {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                    ).then(function (response) {
                        // 这里是处理正确的回调
                        if (response.data.code == 200) {
                            this.fenchengCancel();
                            this.getYeji();
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

            }, fenchengCancel() {
                this.formValidate.name = [];
                this.formValidate.fencheng = [];
                this.fenchengpeizhi = false;
            },
            removeChild(scm_id) {
                this.$Modal.confirm({
                    title: '删除方案',
                    content: '确定要删除吗',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + '/salary_config/del',
                            {
                                scm_id: scm_id,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
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
                            this.$Message.warning('删除失败');
                        });
                    }
                });
            },
            removeChild1(scmy_id) {
                this.$Modal.confirm({
                    title: '删除业绩方案',
                    content: '确定要删除吗',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + '/salary_config_yeji/del',
                            {
                                scmy_id: scmy_id,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.getYeji();
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
            addOk() {
                this.$refs['fangandata'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/salary_config/add',
                            {
                                fanganmingcheng: this.fangandata.fanganmingcheng,
                                fanganshuoming: this.fangandata.fanganshuoming,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['fangandata'].resetFields();
                                this.shezhiname = false;
                                this.getIndex();
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
            addyejiOk() {
                this.$refs['yejidateok'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/salary_config_yeji/add',
                            {
                                yejimingcheng: this.yejidateok.yejimingcheng,
                                scm_id: this.yejidata.scm_id,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['yejidateok'].resetFields();
                                this.shezhiyeji = false;
                                this.getYeji();
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
            addCancel() {
                this.$refs['fangandata'].resetFields();
                this.shezhiname = false;
            }, addyejiCancel() {
                this.$refs['yejidateok'].resetFields();
                this.shezhiyeji = false;
            }
        }
    };
</script>
