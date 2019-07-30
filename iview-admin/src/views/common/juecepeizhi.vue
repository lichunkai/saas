<style lang="less">

</style>

<template>
    <Row>
        <Col :lg="24" :md="24" style="margin-bottom: 10px">
        <Card>
            <Row>
                <Col :lg="14" :md="14">
                <Select v-model="searchType" style="width:200px">
                    <Option v-for="item in cityList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                </Select>
                <Input v-model="guanjianzi" placeholder="请输入关键字" style="width: 200px"></Input>
                <Button type="primary" @click="doSearch">查询</Button>
                <Button type="primary" @click="clearSearch">清空</Button>
                </Col>
                <!--<Col :lg="2" :md="2" offset="8">-->
                <!--<Row type="flex" justify="end">-->
                    <!--<Col>-->
                    <!--<Button type="primary" @click="addJuece">新增</Button>-->
                    <!--<Modal v-model="addJueceModal" title="添加决策" :mask-closable="false">-->
                        <!--<div slot="header">-->
                        <!--<a class="ivu-modal-close" @click="add1Cancel" style="display: block!important;"><i-->
                                <!--class="ivu-icon ivu-icon-ios-close-empty"></i></a>-->
                        <!--<div class="ivu-modal-header-inner">添加决策</div>-->
                    <!--</div>-->
                        <!--<div slot="footer">-->
                            <!--<Button type="text" size="large" @click="add1Cancel">取消</Button>-->
                            <!--<Button type="primary" size="large" @click="add1Ok">确定</Button>-->
                        <!--</div>-->
                        <!--<Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="90">-->
                            <!--<FormItem label="配置名称" prop="jsetting_name">-->
                                <!--<Input v-model="formValidate.jsetting_name" placeholder=""></Input>-->
                            <!--</FormItem>-->
                            <!--<FormItem label="配置英文简称" prop="jsetting_shorthand">-->
                                <!--<Input v-model="formValidate.jsetting_shorthand" placeholder=""></Input>-->
                            <!--</FormItem>-->
                            <!--<FormItem label="类型" prop="jsetting_type">-->
                                <!--<Input v-model="formValidate.jsetting_type" placeholder=""></Input>-->
                            <!--</FormItem>-->
                            <!--<FormItem label="说明" prop="jsetting_desp">-->
                                <!--<Input v-model="formValidate.jsetting_desp" placeholder=""></Input>-->
                            <!--</FormItem>-->
                            <!--<FormItem label="取值范围" prop="val_type">-->
                                <!--<Input v-model="formValidate.val_type" placeholder=""></Input>-->
                            <!--</FormItem>-->
                            <!--<FormItem label="默认值" prop="val">-->
                                <!--<Input v-model="formValidate.val" placeholder=""></Input>-->
                            <!--</FormItem>-->
                        <!--</Form>-->
                    <!--</Modal>-->
                    <!--</Col>-->
                <!--</Row>-->
                <!--</Col>-->
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24">
        <Card>
            <Row>
                <Col>
                <Table :columns="jueceTable" :data="jueceTableData" border stripe highlight-row></Table>
                <Modal v-model="settingsParameter" title="设置决策参数" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="modifyCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">设置决策参数</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="modifyCancel">取消</Button>
                        <Button type="primary" size="large" @click="modifyOk">确定</Button>
                    </div>
                    <Row>
                        <Col :lg="24" :md="24">
                        <h3 style="margin-bottom: 10px"></h3>
                        </Col>
                        <Col>
                        <div>{{jsetting_name}}</div>
                        <div>
                            {{front_content}}
                            <Select  v-if="type === 'select'" v-model="selected" :transfer="true" style="width: 88px">
                                <Option v-for="option in options" v-bind:value="option.value" :value="option.value" :key="option.value">{{ option.text }}</Option>
                            </Select>
                            <Input v-else-if="type === 'text'" v-model="val"  placeholder="" style="width: 80px"></Input>
                            {{back_content}}
                        </div>
                        <input type="hidden" v-model="type"/>
                        <input type="hidden" v-model="csetting_id"/>
                        </Col>
                    </Row>
                </Modal>
                </Col>
            </Row>
            <Row type="flex" justify="end" style="margin-top: 10px">
                <Col>
                <Page :total="totalnum1" :current="currentpage1" @on-change="changePage1" :page-size="pageSize"
                      show-total  ></Page>
                </Col>
            </Row>
        </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'juecepeizhi',
        data() {
            return {
                addJueceModal: false,
                guanjianzi: '',
                totalnum1:0,
                currentpage1: 1,
                pageSize: 10,
                formValidate: {
                    jsetting_id: '',
                    jsetting_shorthand: '',
                    jsetting_type: '',
                    jsetting_name: '',
                    jsetting_desp: '',
                    val_type: '',
                    val: ''
                },
                ruleValidate:{
                    jsetting_shorthand: [
                        { required: true, message: '请填写配置英文简称', trigger: 'blur' }
                    ],
                    jsetting_type: [
                        { required: true, message: '请填写配置类型', trigger: 'blur' }
                    ],
                    jsetting_name: [
                        { required: true, message: '请填写配置名称', trigger: 'blur' }
                    ],
                    jsetting_desp: [
                        { required: true, message: '请填写配置说明', trigger: 'blur' }
                    ],
                    val_type: [
                        { required: true, message: '请填写取值范围', trigger: 'blur' }
                    ],
                    val: [
                        { required: true, message: '请填写默认值', trigger: 'blur' }
                    ],
                },
                selected: 'true',
                options: [
                    {
                        value: 'true ',
                        text: '是'
                    },
                    {
                        value: 'false ',
                        text: '否'
                    },
                ],
                jsetting_name: '',
                front_content: '',
                type: 'select',
                csetting_id: '',
                back_content: '',
                settingsParameter: false,
                cityList: [
                    {
                        value: '房源', //'fangyuan ',
                        label: '房源'
                    },
                    {
                        value: '客源', //'fangyuan ',
                        label: '客源'
                    },
                    {
                        value: '求购', //'qiugou',
                        label: '求购'
                    },
                    {
                        value: '求租', //'qiuzu',
                        label: '求租'
                    },
                    {
                        value: '跟进', //'genjin',
                        label: '跟进'
                    },
                    {
                        value: '其他', //'qita',
                        label: '其他'
                    }
                ],
                searchType: '',
                jueceTable: [
                    {
                        title: '类型',
                        key: 'jsetting_type',
                        align: 'center',
                    },
                    {
                        title: '名称',
                        key: 'jsetting_name',
                        align: 'center',
                    },
                    {
                        title: '英文简称',
                        key: 'jsetting_shorthand',
                        align: 'center',
                    },
                    {
                        title: '说明',
                        key: 'jsetting_desp',
                        align: 'center',
                        render: (h, params) => {
                            const row = params.row;
                            let jsetting_desp_tpl = params.row.jsetting_desp;
                            let val = params.row.val;
                            let val_type = params.row.val_type;
                            let val_type_arr = val_type.split(';');
                            let desp = '';
                            if (val_type_arr[0] == 'text') {
                                val = val;
                            } else if (val_type_arr[0] == 'select') {
                                for (let a = 1; a < val_type_arr.length; a++) {
                                    let sub_arr = val_type_arr[a].split(':');
                                    if (sub_arr[0] == val) {
                                        val = sub_arr[1];
                                    }
                                }
                            }
                            let desp_arr = jsetting_desp_tpl.split('{$val}');
                            //desp = jsetting_desp_tpl.replace(/\{\$val\}/, val);
                            return h('div', [
                                desp_arr[0],
                                h('strong', {
                                   style: {
                                      color: 'red'
                                   }
                                },
                                val),
                                desp_arr[1],
                            ]);
                        }
                    },
                    {
                        title: '操作',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px',
                                        color: '#2d8cf0'
                                    },
                                    on: {
                                        click: () => {
                                            //this.modifySetting(params.index);
                                            this.settingsParameter=true;
                                            this.jsetting_name=params.row.jsetting_name;
                                            this.jsetting_id = params.row.jsetting_id;
                                            //this.whether = this.cityList;

                                            let val_type = params.row.val_type;
                                            let val_type_split = val_type.split(';');
                                            let desp = params.row.jsetting_desp;
                                            let desp_arr = desp.split('{$val}');
                                            this.front_content = desp_arr[0];
                                            this.back_content = desp_arr[1];
                                            this.type = val_type_split[0];
                                            this.val = params.row.val;

                                            let arr = [];
                                            if(val_type_split[0] == 'select'){
                                                 for(let i=1; i<val_type_split.length; i++){
                                                     let sub = val_type_split[i].split(':');
                                                     let obj = {value: sub[0], text: sub[1]};
                                                     arr[i-1] = obj;
                                                 }
                                            }
                                            this.options = arr;
                                            this.selected = this.val;

                                            //this.settingsCount=params.row.explain;
                                        }
                                    }
                                }, '设置')
                            ]);
                        }

                    }
                ],
                jueceTableData: [],
                data1: [
                    {
                        type: '房源',
                        name: '查看座栋是否写跟进',
                        explain: '查看座栋以后必须写跟进 '
                    },
                    {
                        type: '房源',
                        name: '淘宝审核',
                        explain: '淘宝  审核'
                    },
                    {
                        type: '房源',
                        name: '失效需要审核',
                        explain: '状态变为失效 审核'
                    },
                    {
                        type: '房源',
                        name: '封盘数量',
                        explain: '经纪人最多只能保留  个有效房源封盘（包括封电话和封路径）'
                    }
                ]
            }
        },
        methods: {
            //添加普通配置内容
            addJuece() {
                this.addJueceModal = true;
                this.formValidate.jsetting_id = '';
                this.formValidate.jsetting_shorthand = '';
                this.formValidate.jsetting_type = '';
                this.formValidate.jsetting_name = '';
                this.formValidate.jsetting_desp = '';
                this.formValidate.val_type = '';
                this.formValidate.val = '';
            },
            add1Ok() {
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/settingjuece/add',
                            {
                                jsetting_id: '',
                                jsetting_shorthand: this.formValidate.jsetting_shorthand,
                                jsetting_name: this.formValidate.jsetting_name,
                                jsetting_type: this.formValidate.jsetting_type,
                                jsetting_desp: this.formValidate.jsetting_desp,
                                val_type: this.formValidate.val_type,
                                val: this.formValidate.val
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['formValidate'].resetFields();
                                this.addJueceModal = false;
                                this.$Message.success(response.data.message);
                                this.getjuece();
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
                this.addJueceModal = false;
            },
            modifyOk(){
                this.val = this.val.replace(/(^\s*)|(\s*$)/g, "");
                if(this.type === 'text'){
                   if(this.val === ''){
                       alert('值不能为空');
                       return;
                   }
                }else if(this.type === 'select'){
                   this.val = this.selected;
                }
                this.$http.post(api_param.apiurl + '/settingjuece/editval',
                    {
                        jsetting_id: this.jsetting_id,
                        val: this.val,
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.settingsParameter = false;
                        this.$Message.success(response.data.message);
                        this.getjuece();
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
            },
            modifyCancel(){
                this.settingsParameter=false;
            },
            //    获取普通配置列表
            changePage1(page) {
                this.currentpage1 = page;
                this.getjuece();
            },
            getjuece() {
                this.$http.get(api_param.apiurl + '/settingjuece/getlist',
                    {
                        params: {
                            page: this.currentpage1,
                            pagesize: 10,
                            kw: this.guanjianzi,
                            type: this.searchType
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum1 = parseInt(response.body.data.totalnum);
                        this.jueceTableData = response.body.data.list;
                        for (let i = 0; i < this.jueceTableData.length; i++) {
                            /*let jsetting_desp_tpl = this.jueceTableData[i].jsetting_desp;
                            let val = this.jueceTableData[i].val;
                            let val_type = this.jueceTableData[i].val_type;
                            let val_type_arr = val_type.split(';');
                            let desp = '';
                            if (val_type_arr[0] == 'text') {
                                val = val;
                            } else if (val_type_arr[0] == 'select') {
                                for (let a = 1; a < val_type_arr.length; a++) {
                                    let sub_arr = val_type_arr[a].split(':');
                                    if (sub_arr[0] == val) {
                                        val = sub_arr[1];
                                    }
                                }
                            }
                            console.log(val);
                            desp = jsetting_desp_tpl.replace(/\{\$val\}/, val);
                            this.jueceTableData[i].jsetting_desp = desp;
                            this.jueceTableData[i].jsetting_desp_tpl = jsetting_desp_tpl;*/
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
            /*modifySetting(index) {
                this.$Modal.info({
                    title: 'User Info',
                    content: `Name：${this.jueceTableData[index].jsetting_shorthand}<br>Age：${this.jueceTableData[index].jsetting_name}<br>Address：${this.jueceTableData[index].jsetting_desp}`
                })
            },*/
            //查询
            doSearch(){
                this.currentpage1 = 1;
                this.guanjianzi = this.guanjianzi;
                this.searchType = this.searchType;
                this.getjuece();
            },
            //清空
            clearSearch () {
                this.guanjianzi = '';
                this.searchType = '';
                this.getjuece();
            },
        },
        created: function () {
            this.getjuece();
        },
        computed: {}
    };
</script>
