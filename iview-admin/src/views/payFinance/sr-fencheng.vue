<style lang="less">
</style>

<template>
    <Row>
<!--        <Col :lg="24" :md="24">-->
<!--        <Card>-->
<!--            <Row type="flex" justify="end">-->
<!--                <Col>-->
<!--                <Button type="primary" @click="addFencheng">新增分成配置</Button>-->
<!--                <Modal v-model="fenchengModal" title="新增分成配置" width="480" :mask-closable="false">-->
<!--                    <div slot="header">-->
<!--                        <a class="ivu-modal-close" @click="fenchengModalCancel"><i-->
<!--                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>-->
<!--                        <div class="ivu-modal-header-inner">新增分成配置</div>-->
<!--                    </div>-->
<!--                    <div slot="footer">-->
<!--                        <Button type="text" size="large" @click="fenchengModalCancel">取消</Button>-->
<!--                        <Button type="primary" size="large" @click="fenchengModalOk">确定</Button>-->
<!--                    </div>-->
<!--                    <Form ref="formValidate" :model="formValidate" :rules="ruleValidate"  :label-width="100">-->
<!--                        <FormItem label="名称" prop="finance_name">-->
<!--                                <Input v-model="formValidate.finance_name" placeholder="方案名称"></Input>-->
<!--                        </FormItem>-->
<!--                        <FormItem label="英文简称" prop="finance_shorthand">-->
<!--                            <Input v-model="formValidate.finance_shorthand" placeholder="方案英文简称"></Input>-->
<!--                        </FormItem>-->
<!--                    </Form>-->
<!--                </Modal>-->
<!--                </Col>-->
<!--            </Row>-->
<!--        </Card>-->
<!--        </Col>-->
        <Col :lg="24" :md="24" style="">
        <Card>
            <Row :gutter="10">
                <Col :lg="8" :md="8">
<!--                <Table :columns="fenchengColumns" :data="fenchengData" @on-row-click="rowClick" highlight-row border script></Table>-->
<!--                <div style="margin: 10px;overflow: hidden">-->
<!--                    <div style="float: right;">-->
<!--                        <Page :total="totalnum" :current="currentpage" @on-change="changePage"></Page>-->
<!--                    </div>-->
<!--                </div>-->
                <!--分成配置-->
                <Modal v-model="fenchengInfoModal" title="分成配置" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="infoModalCancel"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">分成配置</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="infoModalCancel">取消</Button>
                        <Button type="primary" size="large" @click="infoModalOk">确定</Button>
                    </div>
                    <Form ref="formPeizhiValidate" :model="formPeizhiValidate" :rules="rulePeizhiValidate" style="text-align: center;line-height: 32px">
                        <Row>
                            <Col :lg="12" :md="12">
                            <strong>分成人</strong>
                            </Col>
                            <Col :lg="12" :md="12">
                            <strong>分成比例</strong>
                            </Col>
                        </Row>
                        <Row style="margin-top: 10px">
                            <Col :lg="12" :md="12">
                            <p>房源录入人</p>
                            </Col>
                            <Col :lg="12" :md="12">
                            <FormItem prop="fangyuanlururen">
                                <Input v-model="formPeizhiValidate.fangyuanlururen" placeholder="分成比例" number="true"><span
                                    slot="append">%</span></Input>
                            </FormItem>
                            </Col>
                        </Row>
                        <Row>
                            <Col :lg="12" :md="12">
                                <p>房源维护人</p>
                            </Col>
                            <Col :lg="12" :md="12">
                                <FormItem prop="fangyuanweihuren">
                                    <Input v-model="formPeizhiValidate.fangyuanweihuren" placeholder="分成比例" number="true"><span
                                            slot="append">%</span></Input>
                                </FormItem>
                            </Col>
                        </Row>
                        <Row>
                            <Col :lg="12" :md="12">
                            <p>图片录入人</p>
                            </Col>
                            <Col :lg="12" :md="12">
                            <FormItem prop="tupianlururen">
                                <Input v-model="formPeizhiValidate.tupianlururen" placeholder="分成比例" number="true"><span
                                    slot="append">%</span></Input>
                            </FormItem>
                            </Col>
                        </Row>
                        <Row>
                            <Col :lg="12" :md="12">
                            <p>拿钥匙人</p>
                            </Col>
                            <Col :lg="12" :md="12">
                            <FormItem prop="yaoshiren">
                                <Input v-model="formPeizhiValidate.yaoshiren" placeholder="分成比例" number="true"><span
                                    slot="append">%</span></Input>
                            </FormItem>
                            </Col>
                        </Row>
                        <Row>
                            <Col :lg="12" :md="12">
                                <p>一般委托人</p>
                            </Col>
                            <Col :lg="12" :md="12">
                                <FormItem prop="yibanweituoren">
                                    <Input v-model="formPeizhiValidate.yibanweituoren" placeholder="分成比例" number="true"><span
                                            slot="append">%</span></Input>
                                </FormItem>
                            </Col>
                        </Row>
                        <Row>
                            <Col :lg="12" :md="12">
                            <p>独家委托人</p>
                            </Col>
                            <Col :lg="12" :md="12">
                            <FormItem prop="dujiaweituoren">
                                <Input v-model="formPeizhiValidate.dujiaweituoren" placeholder="分成比例" number="true"><span
                                    slot="append">%</span></Input>
                            </FormItem>
                            </Col>
                        </Row>
                        <Row>
                            <Col :lg="12" :md="12">
                            <p>合同成交人</p>
                            </Col>
                            <Col :lg="12" :md="12">
                            <FormItem prop="hetongchengjiaoren">
                                <Input v-model="formPeizhiValidate.hetongchengjiaoren" placeholder="分成比例" number="true"><span
                                    slot="append">%</span></Input>
                            </FormItem>
                            </Col>
                        </Row>
                    </Form>
                </Modal>
                </Col>
                <Col :lg="25" :md="25">
                <Table :columns="infoColumns" :data="infoData.data" border script></Table>
                </Col>
            </Row>
        </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'srFencheng',
        data () {
            return {
                fenchengModal: false, // 添加名称
                key:0,
                flag:{
                    del:0,
                    set:0,
                },
                totalnum: 0,
                currentpage: 1,
                formValidate: {
                    finance_name: '',
                    finance_shorthand: '',
                },
                ruleValidate: {
                    finance_name:[{required: true, message: '请输入分成配置名称', trigger: 'blur'}],
                    finance_shorthand:[{required: true, message: '请输入分成配置简称', trigger: 'blur'}],
                },
                fenchengInfoModal: false, // 分成配置
                formPeizhiValidate: {
                    finance_id:'',
                    fangyuanlururen: '',
                    fangyuanweihuren:'',
                    tupianlururen: '',
                    yaoshiren: '',
                    yibanweituoren:'',
                    dujiaweituoren: '',
                    hetongchengjiaoren: ''
                },
                rulePeizhiValidate: {
                    fangyuanlururen: [{ type: "number", message: '请填写正确比例', trigger: 'blur'}],
                    fangyuanweihuren: [{ type: "number", message: '请填写正确比例', trigger: 'blur'}],
                    tupianlururen: [{ type: "number", message: '请填写正确比例', trigger: 'blur'}],
                    yaoshiren: [{ type: "number", message: '请填写正确比例', trigger: 'blur'}],
                    yibanweituoren: [{ type: "number", message: '请填写正确比例', trigger: 'blur'}],
                    dujiaweituoren: [{ type: "number", message: '请填写正确比例', trigger: 'blur'}],
                    hetongchengjiaoren: [{ type: "number", message: '请填写正确比例', trigger: 'blur'}]
                },
                fenchengColumns: [
                    {
                        title: '名称',
                        key: 'finance_name',
                        align: 'center'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        align: 'center',
                        render: (h,params) => {
                            let ret = [];
                            if(true){
                                ret.push(h('a', {
                                    props: {},
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.$set(this.flag,'set',1);
                                            this.setFenchengInfo(params.row);
                                        }
                                    }
                                }, '设置'));
                            }
//                            if(true){
//                                ret.push(h('a', {
//                                    props: {},
//                                    style: {
//                                        marginRight: '5px'
//                                    },
//                                    on: {
//                                        click: () => {
//                                            this.$set(this.flag,'del',1);
//                                            this.delFencheng(params.row);
//                                        }
//                                    }
//                                }, '删除'));
//                            }
                            return h('div', ret)
                        }
                    }
                ],
                fenchengData: [],
                infoColumns: [
                    {
                        title: '分成人',
                        key: 'finance_name',
                        align: 'center'
                    },
                    {
                        title: '分成比例',
                        key: 'finance_per',
                        align: 'center',
                        render: (h, params) => {
                            let texts = params.row.finance_per+'%';
                            return h('div', {
                                props: {},
                            },texts)
                        }
                    },
                    {
                        title: '操作',
                        key: 'name',
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
                                            this.editFenchengInfo();
                                        }
                                    }
                                }, '编辑'),

                            ]);
                        }
                    }
                ],
                infoData: {
                    data:[],
                }
            };
        },
        created () {
            this.getFinanceList();
            this.getFinanceListChild();
        },
        methods: {
            //选择分成配置
            rowClick(data, index) {
                this.key = index;
//                alert(this.flag.del);alert(this.flag.set);
                this.$set(this.formPeizhiValidate,'finance_id',data.finance_id);
                if(this.flag.del != 1 && this.flag.set != 1){
                    this.getFinanceListChild();
                }
            },
            //获取分成配置列表
            changePage (page) {
                this.currentpage = page;
                this.getFinanceList();
            },
            getFinanceList() {
                this.$http.get(api_param.apiurl + '/settingfinance/getlist',
                    {
                        params: {
                            page: this.currentpage,
                            pagesize: 10,
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum = response.data.data.totalnum;
                        this.fenchengData = response.data.data.list;
                        this.$set(this.formPeizhiValidate,'finance_id',this.fenchengData.finance_id);
                        // console.log(this.fenchengData.finance_id);
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
            //获取右侧分成详情
            getFinanceListChild() {
                this.$http.post(api_param.apiurl + '/settingfinance/getchildlist',
                    {
                        finance_id: this.formPeizhiValidate.finance_id
                    },
                    {
                        emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.$set(this.infoData,'data',response.data.data);
                        console.log(this.infoData);
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
            },
            //新增方案
            addFencheng(){
                this.fenchengModal = true;
            },
            fenchengModalCancel(){
                this.$refs['formValidate'].resetFields();
                this.$set(this.formValidate,'finance_name','');
                this.$set(this.formValidate,'finance_shorthand','');
                this.fenchengModal = false;
            },
            fenchengModalOk(){
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'settingfinance/add',
                            {
                                finance_id: '',
                                finance_shorthand: this.formValidate.finance_shorthand,
                                finance_name: this.formValidate.finance_name
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['formValidate'].resetFields();
                                this.fenchengModal = false;
                                this.$Message.success('添加成功');
                                this.getFinanceList();
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
                            console.log(response)
                        })
                    }
                });
            },
            //删除方案
            delFencheng(data){
                this.$Modal.confirm({
                    title: '删除方案',
                    content: '确定要删除吗',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + '/settingfinance/del',
                        {
                            finance_id: data.finance_id
                        },
                        {
                            emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}
                        }).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$set(this.flag,'del',0);
                                this.$set(this.infoData,'data',[]);
                                this.getFinanceList();
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
                        })
                    },
                    onCancel: () => {
                        this.$set(this.flag,'del',0);
                    }
                })
            },
            //设置方案内容
            setFenchengInfo(data){
                this.$set(this.formPeizhiValidate,'finance_id',data.finance_id);
                this.fenchengInfoModal = true;
            },
            infoModalCancel(){
                this.$refs['formPeizhiValidate'].resetFields();
                this.$set(this.formPeizhiValidate,'fangyuanlururen','');
                this.$set(this.formPeizhiValidate,'tupianlururen','');
                this.$set(this.formPeizhiValidate,'yaoshiren','');
                this.$set(this.formPeizhiValidate,'dujiaweituoren','');
                this.$set(this.formPeizhiValidate,'hetongchengjiaoren','');
                this.$set(this.formPeizhiValidate,'liandonghuachengren','');
                this.$set(this.formPeizhiValidate,'xieshanghuachengren','');
                this.$set(this.formPeizhiValidate,'fangyuanweihuren','');
                this.$set(this.formPeizhiValidate,'yibanweituoren','');
                this.$set(this.flag,'set',0);
                this.fenchengInfoModal = false;
            },
            infoModalOk(){
                let totalper = 0;
                this.$refs['formPeizhiValidate'].validate((valid) => {
                    if (valid) {
                        totalper = parseInt(this.formPeizhiValidate.fangyuanlururen)+parseInt(this.formPeizhiValidate.tupianlururen)+parseInt(this.formPeizhiValidate.yaoshiren)+parseInt(this.formPeizhiValidate.dujiaweituoren)+parseInt(this.formPeizhiValidate.hetongchengjiaoren);
                        if(totalper >100){
                            this.$Message.warning('分成比例超出总和');
                            return false;
                        }
                        this.$http.post(api_param.apiurl + 'settingfinance/edit',
                            {
                                finance_id: this.formPeizhiValidate.finance_id,
                                fangyuanlururen: this.formPeizhiValidate.fangyuanlururen,
                                tupianlururen: this.formPeizhiValidate.tupianlururen,
                                yaoshiren: this.formPeizhiValidate.yaoshiren,
                                dujiaweituoren: this.formPeizhiValidate.dujiaweituoren,
                                hetongchengjiaoren: this.formPeizhiValidate.hetongchengjiaoren,
                                fangyuanweihuren:this.formPeizhiValidate.fangyuanweihuren,
                                yibanweituoren:this.formPeizhiValidate.yibanweituoren
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['formPeizhiValidate'].resetFields();
                                this.$set(this.flag,'set',0);
                                this.fenchengInfoModal = false;
                                this.$Message.success('更新成功');
                                //this.$set(this.formPeizhiValidate,'finance_id',);
                                this.getFinanceListChild();
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
                            console.log(response)
                        })
                    }
                });
            },
            //修改方案内容
            editFenchengInfo(){
                this.$http.post(api_param.apiurl + '/settingfinance/getchildlist',
                    {
                        finance_id: this.formPeizhiValidate.finance_id
                    },
                    {
                        emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        response.data.data.forEach((item)=>{
                            this.$set(this.formPeizhiValidate,item['finance_short'],parseInt(item['finance_per']));
                        });
                        this.fenchengInfoModal = true;
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
            },
        }
    };
</script>