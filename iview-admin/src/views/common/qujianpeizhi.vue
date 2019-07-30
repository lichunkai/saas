<style lang="less">
    @import "common.less";
</style>

<template>
    <Row>
        <Col :lg="24" :md="24">
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
                <Row type="flex" justify="end">
                    <Col>
                    <!--<Button type="primary" @click="addQujian">新增</Button>-->
                    <Modal v-model="addQujianModal" title="新增区间配置" :mask-closable="false">
                        <div slot="header">
                            <a class="ivu-modal-close" @click="add1Cancel" style="display: block!important;"><i
                                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                            <div class="ivu-modal-header-inner">新增区间配置</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="add1Cancel">取消</Button>
                            <Button type="primary" size="large" @click="add1Ok">确定</Button>
                        </div>
                        <Form ref="addQujianformValidate" :model="addQujianformValidate" :rules="addQujianruleValidate"
                              :label-width="100">
                            <FormItem label="区间名称" prop="name">
                                <Input v-model="addQujianformValidate.name" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="区间英文简称" prop="jname">
                                <Input v-model="addQujianformValidate.jname" placeholder=""></Input>
                            </FormItem>
                        </Form>
                    </Modal>
                    </Col>
                </Row>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Row :gutter="20">
                <Col :lg="8" :md="8">
                <Table :columns="sectionName" :data="sectionNameData" @on-row-click="rowClick1" border stripe highlight-row></Table>
                <Modal v-model="orderEdit" title='设置区间' stley="width:960px;" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="setCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">设置区间</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="setCancel">取消</Button>
                        <Button type="primary" size="large" @click="setOk">确定</Button>
                    </div>
                    <Form ref="setQujianformValidate" :model="setQujianformValidate" :rules="setQujianruleValidate">
                        <Row :gutter="10">
                            <Col :lg="24" :md="24">
                            由低到高的顺序填写，后一项不填写默认以上</Col>
                            <Col :lg="8" :md="8" class="textCenter">
                            <strong>下限(>)</strong>
                            </Col>
                            <Col :lg="8" :md="8" class="textCenter">
                            <strong>上限(< =)</strong>
                            </Col>
                            <Col :lg="8" :md="8" class="textCenter">
                            <div style="visibility:hidden">单位</div>
                            </Col>
                            <Col :lg="8" :md="8">
                            <FormItem label="" prop="addleast">
                                <Input v-model="setQujianformValidate.addleast"  placeholder=""></Input>
                            </FormItem>
                            </Col>
                            <Col :lg="8" :md="8">
                            <FormItem label="" prop="addlimit">
                                <Input v-model="setQujianformValidate.addlimit"  placeholder=""></Input>
                            </FormItem>
                            </Col>
                            <Col :lg="8" :md="8">
                            <FormItem label="" prop="adddanwei" style="float: left">
                                <Input v-model="setQujianformValidate.adddanwei"  placeholder=""
                                       ><span
                                    slot="prepend">单位</span></Input>
                            </FormItem>
                            </Col>
                        </Row>
                    </Form>
                </Modal>
                <Row type="flex" justify="end" style="margin-top: 10px">
                    <Col>
                    <Page :total="totalnum1" :current="currentpage1" @on-change="changePage1" :page-size="pageSize"
                          show-total></Page>
                    </Col>
                </Row>
                </Col>
                <Col :lg="16" :md="16">
                <Table :columns="sectionDetail" :data="sectionDetailData" @on-row-click="rowClick2" border stripe></Table>
                <Modal v-model="xiugaiModel" title='修改区间' :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="setChlidCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">修改区间</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="setChlidCancel">取消</Button>
                        <Button type="primary" size="large" @click="setChlidOk">确定</Button>
                    </div>
                    <Form ref="setChlidformValidate" :model="setChlidformValidate" :rules="setChlidruleValidate">
                        <Row :gutter="10">
                            <Col :lg="24" :md="24">
                            上限不填写默认以上</Col>
                            <Col :lg="8" :md="8" class="textCenter">
                            <strong>下限(>)</strong>
                            </Col>
                            <Col :lg="8" :md="8" class="textCenter">
                            <strong>上限(< =)</strong>
                            </Col>
                            <Col :lg="8" :md="8" class="textCenter">
                            <div style="visibility:hidden">单位</div>
                            </Col>
                            <Col :lg="8" :md="8">
                            <FormItem label="" prop="setleast">
                                <Input v-model="setChlidformValidate.setleast"  placeholder=""></Input>
                            </FormItem>
                            </Col>
                            <Col :lg="8" :md="8">
                            <FormItem label="" prop="setlimit">
                                <Input v-model="setChlidformValidate.setlimit"  placeholder=""></Input>
                            </FormItem>
                            </Col>
                            <Col :lg="8" :md="8">
                            <FormItem label="" prop="setdanwei" style="float: left">
                                <Input v-model="setChlidformValidate.setdanwei"  placeholder=""><span
                                    slot="prepend">单位</span></Input>
                            </FormItem>
                            </Col>
                        </Row>
                    </Form>
                </Modal>
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
    // let TitleName='';
    export default {
        name: 'qujianpeizhi',
        data() {
            return {
                // setTitle:TitleName,
                guanjianzi: '',
                totalnum1:0,
                currentpage1:1,
                totalnum2:0,
                currentpage2:1,
                pageSize:10,
                addQujianModal: false,
                addQujianformValidate: {
                    name: '',
                    jname: ''
                },
                addQujianruleValidate: {
                    name: [
                        {required: true, message: '请输入配置名称', trigger: 'blur'}
                    ],
                    jname: [
                        {required: true, message: '请输入配置英文简称', trigger: 'blur'}
                    ],
                },
//                设置区间
                orderEdit: false,
                xiugaiModel: false,
                setQujianformValidate:{
                    addleast: '',
                    addlimit: '',
                    adddanwei: ''
                } ,
                setQujianruleValidate:{
                    addleast: [
                        {required: true, message: '请输入下限', trigger: 'blur'}
                    ],
                    // addlimit: [
                    //     {required: true, message: '请输入上限', trigger: 'blur'}
                    // ],
                    adddanwei: [
                        {required: true, message: '请输入单位', trigger: 'blur'}
                    ],
                },
                setChlidformValidate:{
                    setleast: '',
                    setlimit: '',
                    setdanwei: ''
                },
                setChlidruleValidate:{
                    setleast: [
                        {required: true, message: '请输入下限', trigger: 'blur'}
                    ],
                    // setlimit: [
                    //     {required: true, message: '请输入上限', trigger: 'blur'}
                    // ],
                    setdanwei: [
                        {required: true, message: '请输入单位', trigger: 'blur'}
                    ],
                },
                sectionName: [
                    {
                        title: '#',
                        key: 'sectionId',
                        align: 'center'
                    },
                    {
                        title: '区间名称',
                        key: 'sectionName',
                        align: 'center'
                    },
                    {
                        title: '操作',
                        key: 'peizhiSection',
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
                                            this.setQujian(params)
                                        }
                                    }
                                }, '设置区间')
                            ])
                        }
                    }
                ],
                sectionNameData: [],
                sectionDetail: [
                    {
                        title: '名称',
                        key: 'name',
                        align: 'center'
                    },
                    {
                        title: '最小值',
                        key: 'min',
                        align: 'center'
                    },
                    {
                        title: '最大值',
                        key: 'max',
                        align: 'center'
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
                                            this.xiugaiqujianChild(params)
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
                sectionDetailData: []
            }
        },
        created: function () {
            this.getqujianlist();
        },
        methods: {

            rowClick1(data, index) {
                inx = index;
                this.getchildlist();
            },
            rowClick2(data, index) {
                inx2 = index;
                console.log(data)
            },
            //增加区间配置
            addQujian() {
                this.addQujianModal = true;
            },
            add1Ok() {
                this.$refs['addQujianformValidate'].validate((valid) => {
                    if (valid) {
                        let reqData = {
                            qujian_id: '',
                            qujian_shorthand: this.addQujianformValidate.jname,
                            qujian_name: this.addQujianformValidate.name
                        };
                        this.$http.post(api_param.apiurl + '/settingqujian/add',
                            reqData,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            console.log(response);
                            // 这里是处理正确的回调
                            if (response.data.code === 200) {
                                this.$refs['addQujianformValidate'].resetFields();
                                this.$Message.success(response.data.message);
                                this.getqujianlist();
                                this.addQujianModal = false;
                            } else if (response.data.code === 401) {
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
                })
            },
            add1Cancel() {
                this.$refs['addQujianformValidate'].resetFields();
                this.addQujianModal = false;
            },
            //获取区间配置列表
            changePage1(page) {
                this.currentpage1 = page;
                this.getqujianlist();
            },
            getqujianlist() {
                this.$http.get(api_param.apiurl + '/settingqujian/getlist',
                    {
                        params: {
                            page: this.currentpage1,
                            pagesize: '10',
                            kw: this.guanjianzi
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum1 = parseInt(response.body.data.totalnum);
                        this.sectionNameData = response.body.data.list;
                        // console.log(this.sectionNameData);
                        for (let i = 0; i < this.sectionNameData.length; i++) {
                            this.sectionNameData[i]['sectionName'] = this.sectionNameData[i].qujian_name;
                            this.sectionNameData[i]['sectionId'] = this.sectionNameData[i].qujian_id;
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
                    console.log(response);
                });
            },
            //添加区间补充配置子配置
            setQujian(params) {
                this.orderEdit = true;
            },
            setCancel() {
                this.$refs['setQujianformValidate'].resetFields();
                this.orderEdit = false
            },
            setOk() {
                let childName='';
                this.$refs['setQujianformValidate'].validate((valid) => {
                    if (valid) {
                        if(this.setQujianformValidate.addlimit===''){
                            childName=this.setQujianformValidate.addleast+ this.setQujianformValidate.adddanwei+'以上'
                        }else{
                            childName=this.setQujianformValidate.addleast + "-" +
                            this.setQujianformValidate.addlimit + this.setQujianformValidate.adddanwei
                        }

                        let reqData = {
                            qujian_id: this.sectionNameData[inx].qujian_id,
                            child_name: childName,
                            min: this.setQujianformValidate.addleast,
                            max: this.setQujianformValidate.addlimit
                        };
                        if(this.setQujianformValidate.addlimit!==''){
                            if(parseInt(this.setQujianformValidate.addleast)>=parseInt(this.setQujianformValidate.addlimit)){
                                this.$Message.warning('下限不能大于或等于上限');
                            }else{
                                if(/^[\u4e00-\u9fa5]+$/gi.test(this.setQujianformValidate.adddanwei)){
                                    this.$http.post(api_param.apiurl + '/settingqujian/addchild',
                                        reqData,
                                        {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                                    ).then(function (response) {
                                        console.log(response);
                                        // 这里是处理正确的回调
                                        if (response.data.code === 200) {
                                            this.$refs['setQujianformValidate'].resetFields();
                                            this.$Message.success(response.data.message);
                                            this.orderEdit = false;
                                            this.getchildlist();
                                        } else if (response.data.code === 401) {
                                            this.$store.commit('logout', this);
                                            this.$store.commit('clearOpenedSubmenu');
                                            this.$router.push({
                                                name: 'login'
                                            });
                                        } else {
                                            this.$Message.warning(response.data.message);
                                        }
                                    }, function (response) {
                                        console.log(response);
                                        // 这里是处理错误的回调
                                        this.$Message.warning('更新失败');
                                    });
                                }else{
                                    this.$Message.warning('单位框只能输入汉字');
                                }

                            }
                        }else{
                            if(/^[\u4e00-\u9fa5]+$/gi.test(this.setQujianformValidate.adddanwei)){
                                this.$http.post(api_param.apiurl + '/settingqujian/addchild',
                                    reqData,
                                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                                ).then(function (response) {
                                    console.log(response);
                                    // 这里是处理正确的回调
                                    if (response.data.code === 200) {
                                        this.$refs['setQujianformValidate'].resetFields();
                                        this.$Message.success(response.data.message);
                                        this.orderEdit = false;
                                        this.getchildlist();
                                    } else if (response.data.code === 401) {
                                        this.$store.commit('logout', this);
                                        this.$store.commit('clearOpenedSubmenu');
                                        this.$router.push({
                                            name: 'login'
                                        });
                                    } else {
                                        this.$Message.warning(response.data.message);
                                    }
                                }, function (response) {
                                    console.log(response);
                                    // 这里是处理错误的回调
                                    this.$Message.warning('更新失败');
                                });
                            }else{
                                this.$Message.warning('单位框只能输入汉字');
                            }
                        }


                    }
                })
            },
            //获取区间补充配置子配置列表
            changePage2(page) {
                this.currentpage2 = page;
                this.getchildlist();
            },
            getchildlist() {
                this.$http.post(api_param.apiurl + '/settingqujian/getchildlist',
                    {qujian_id: this.sectionNameData[inx].qujian_id},
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}})
                    .then(function (response) {
                        // 这里是处理正确的回调
                        if (response.data.code == 200) {
                            if(response.body.data!==null){
                                this.totalnum2 = parseInt(response.body.data.length);
                                this.sectionDetailData = response.body.data;
                                console.log(this.sectionDetailData.length);
                                for (let i = 0; i < this.sectionDetailData.length; i++) {
                                    this.sectionDetailData[i]['name'] = this.sectionDetailData[i].child_name;
                                    this.sectionDetailData[i]['max'] = this.sectionDetailData[i].max;
                                    this.sectionDetailData[i]['min'] = this.sectionDetailData[i].min;
                                }
                            }else {
                                this.sectionDetailData=[]
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
                        console.log(response);
                        this.$Message.warning('更新失败');
                    });
            },
            //    修改普通配置子配置
            xiugaiqujianChild(params) {
                this.xiugaiModel = true;
                console.log(params);
                this.setChlidformValidate.setleast = params.row.min;
                this.setChlidformValidate.setlimit = params.row.max;
                if(params.row.child_name.match(/[\u4e00-\u9fa5]/g).join("").slice(-2)!=='以上'){
                    this.setChlidformValidate.setdanwei = params.row.child_name.match(/[\u4e00-\u9fa5]/g).join("");
                }else{
                    this.setChlidformValidate.setdanwei = params.row.child_name.match(/[\u4e00-\u9fa5]/g).join("").split('以')[0]
                }

            },
            setChlidOk() {
                let childName='';
                this.$refs['setChlidformValidate'].validate((valid) => {
                    if (valid) {
                        if (this.setChlidformValidate.setlimit === '') {
                            childName = this.setChlidformValidate.setleast + this.setChlidformValidate.setdanwei + '以上'
                        } else {
                            childName = this.setChlidformValidate.setleast + "-" +
                                this.setChlidformValidate.setlimit + this.setChlidformValidate.setdanwei
                        }
                        if(this.setChlidformValidate.setlimit!==''){
                            if (parseInt(this.setChlidformValidate.setleast) >=
                                parseInt(this.setChlidformValidate.setlimit)) {
                                this.$Message.warning('下限不能大于或等于上限');
                            } else {
                                if (/^[\u4e00-\u9fa5]+$/gi.test(this.setChlidformValidate.setdanwei)) {
                                    this.$http.post(api_param.apiurl + '/settingqujian/editchild',
                                        {
                                            qujian_id: this.sectionNameData[inx].qujian_id,
                                            child_id: this.sectionDetailData[inx2].child_id,
                                            child_name: childName,
                                            min: this.setChlidformValidate.setleast,
                                            max: this.setChlidformValidate.setlimit,
                                            // sort:''
                                        },
                                        {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                                    ).then(function (response) {
                                        // 这里是处理正确的回调
                                        if (response.data.code == 200) {
                                            this.$refs['setChlidformValidate'].resetFields();
                                            this.xiugaiModel = false;
                                            this.$Message.success(response.data.message);
                                            this.getchildlist();
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
                                }else{
                                    this.$Message.warning('单位框只能输入汉字');
                                }
                            }
                        }else{
                            if (/^[\u4e00-\u9fa5]+$/gi.test(this.setChlidformValidate.setdanwei)) {
                                this.$http.post(api_param.apiurl + '/settingqujian/editchild',
                                    {
                                        qujian_id: this.sectionNameData[inx].qujian_id,
                                        child_id: this.sectionDetailData[inx2].child_id,
                                        child_name: childName,
                                        min: this.setChlidformValidate.setleast,
                                        max: this.setChlidformValidate.setlimit,
                                        // sort:''
                                    },
                                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                                ).then(function (response) {
                                    // 这里是处理正确的回调
                                    if (response.data.code == 200) {
                                        this.$refs['setChlidformValidate'].resetFields();
                                        this.xiugaiModel = false;
                                        this.$Message.success(response.data.message);
                                        this.getchildlist();
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
                            }else{
                                this.$Message.warning('单位框只能输入汉字');
                            }
                        }

                    }
                });
            },
            setChlidCancel() {
                this.$refs['setChlidformValidate'].resetFields();
                this.xiugaiModel = false;
            },
            //    删除普通配置子配置
            removeChild(params) {
                this.$Modal.confirm({
                    title: '删除部门',
                    content: '确定要删除吗',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + '/settingqujian/delchild',
                            {
                                qujian_id: this.sectionNameData[inx].qujian_id,
                                child_id: this.sectionDetailData[inx2].child_id,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.getchildlist();
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
                this.getqujianlist();
                this.sectionDetailData=''
            },
            //清空
            clearSearch() {
                this.guanjianzi = '';
                this.getqujianlist();
                this.sectionDetailData=''
            },



        },

        computed: {}
    }
</script>