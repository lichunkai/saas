<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row type="flex" justify="end">
                <Col>
                <Button type="primary" @click="gongziModel = true">新增</Button>
                </Col>
                <Modal v-model="gongziModel" title="工资配置">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="gongziCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">分成配置</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="gongziCancel">取消</Button>
                        <Button type="primary" size="large" @click="addOk">确定</Button>
                    </div>
                    <Form ref="gongzidata" :model="gongzidata" :label-width="120">
                        <FormItem label="方案名称" prop="fanganmingcheng">
                            <Input v-model="gongzidata.fanganmingcheng" placeholder=""  :rules="{required: true, message: '方案名称不能为空', trigger: 'blur'}"></Input>
                        </FormItem>
                        <FormItem label="基本工资" prop="jibengongzi">
                            <Input v-model="gongzidata.jibengongzi" placeholder=""><span slot="append">元</span></Input>
                        </FormItem>
                        <FormItem label="五险(个人)" prop="wuxiangeren">
                            <Input v-model="gongzidata.wuxiangeren" placeholder=""><span slot="append">元</span></Input>
                        </FormItem>
                        <FormItem label="五险一金(个人)" prop="wuxianyijingeren">
                            <Input v-model="gongzidata.wuxianyijingeren" placeholder=""><span slot="append">元</span></Input>
                        </FormItem>
                        <FormItem label="备注" prop="beizhu">
                            <Input v-model="gongzidata.beizhu" type="textarea" placeholder=""></Input>
                        </FormItem>
                    </Form>
                </Modal>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Table border :columns="gongziColumns" :data="gongzilist" script></Table>
        </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'srGongzi',
        data() {
            return {
                gongziModel: false, //工资配置
                gongzidata: {
                    fanganmingcheng: '',
                    jibengongzi: '',
                    wuxiangeren: '',
                    wuxianyijingeren: '',
                    beizhu: '',
                },
                currentpage1:1,
                gongziColumns: [
                    {
                        title: '方案名称',
                        key: 'fanganmingcheng',
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
                        title: '备注',
                        key: 'beizhu',
                        align: 'center'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 150,
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
                                            this.gongziModel=true;
                                        }
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.remove(params.index);
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                gongzilist: []
            };
        },created: function () {
             this.getIndex();
        },
        methods: {
            getIndex(){
                this.$http.get(api_param.apiurl + '/salary_config_gongzi/index',
                    {
                        params: {
                            page: this.currentpage1,
                            pagesize: 10,
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum1 = response.body.data.count;
                        this.gongzilist = response.body.data.list;
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
            addOk(){
                this.$refs['gongzidata'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/salary_config_gongzi/add',
                            {
                                fanganmingcheng: this.gongzidata.fanganmingcheng,
                                jibengongzi: this.gongzidata.jibengongzi,
                                wuxiangeren: this.gongzidata.wuxiangeren,
                                wuxianyijingeren: this.gongzidata.wuxianyijingeren,
                                beizhu: this.gongzidata.beizhu,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['fangandata'].resetFields();
                                this.gongziModel = false;
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
            gongziCancel(){

            }
        }
    };
</script>

<style scoped>

</style>
