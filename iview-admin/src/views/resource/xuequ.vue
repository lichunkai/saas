<style lang="less">

</style>

<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="10">
                <Col :lg="4" :md="4">
                <Input v-model="guanjianzi" placeholder="学校名称、学校地址"></Input>
                </Col>
                <Col :lg="4" :md="4">
                <Button type="primary" @click="doSearch">查询</Button>
                <Button type="primary" @click="clearSearch">清空</Button>
                </Col>

                <Col :lg="16" :md="16" >
                <Row type="flex" justify="end">
                    <Col>
                    <Button type="primary" @click="addSchool">新增学校</Button>
                    <Modal v-model="addSchoolModal" title="新增学校" :mask-closable="false">
                        <div slot="header">
                            <a class="ivu-modal-close" @click="addCancel" style="display: block!important;"><i
                                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                            <div class="ivu-modal-header-inner">新增学校</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="addCancel">取消</Button>
                            <Button type="primary" size="large" @click="addOk">确定</Button>
                        </div>
                        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
                            <FormItem label="学校名称" prop="name">
                                <Input v-model="formValidate.name"></Input>
                            </FormItem>
                            <FormItem label="学校地址" prop="address">
                                <Input v-model="formValidate.address"></Input>
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
                <Col :lg="12" :md="12">
                <Table :columns="school" :data="schoolData" @on-row-click="rowClick1" border stripe highlight-row></Table>
                <!--修改-->
                <Modal v-model="amendSchoolModal" title="修改学校" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="amendCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">修改学校</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="amendCancel">取消</Button>
                        <Button type="primary" size="large" @click="amendOk">确定</Button>
                    </div>
                    <Form ref="formValidateAmend" :model="formValidateAmend" :rules="ruleValidateAmend" :label-width="80">
                        <FormItem label="学校名称" prop="s_name">
                            <Input v-model="formValidateAmend.s_name"></Input>
                        </FormItem>
                        <FormItem label="学校地址" prop="s_address">
                            <Input v-model="formValidateAmend.s_address"></Input>
                        </FormItem>
                    </Form>
                </Modal>
                <!--新增周边小区-->
                <Modal v-model="addxiaoquModal" title="新增周边小区" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="addxiaoquCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">新增周边小区</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="addxiaoquCancel">取消</Button>
                        <Button type="primary" size="large" @click="addxiaoquOk">确定</Button>
                    </div>
                    <Form ref="addxiaoquformValidate" :model="addxiaoquformValidate" :rules="addxiaoquruleValidate" :label-width="80">
                        <FormItem label="片区" prop="xiaoqu">
                            <Cascader :data="DtsData" v-model="addxiaoquformValidate.pianqu"  @on-change="changeDts"></Cascader>
                        </FormItem>
                        <FormItem label="小区" prop="xiaoqu">
                            <Select  :transfer="true" v-model="addxiaoquformValidate.xiaoqu" >
                                <Option v-for="item in village" :value="item.village_id" :key="item.village_name">{{ item.village_name }}</Option>
                            </Select>
                        </FormItem>
                        <FormItem label="备注" prop="beizhu">
                            <Input v-model="addxiaoquformValidate.beizhu"></Input>
                        </FormItem>
                    </Form>
                </Modal>
                <Row type="flex" justify="end" style="margin-top: 10px">
                    <Col>
                    <Page :total="totalnum1" :current="currentpage1" @on-change="changePage1" :page-size="pageSize"
                          show-total></Page>
                    </Col>
                </Row>
                </Col>
                <Col :lg="12" :md="12">
                <Table :columns="housing" :data="housingData" @on-row-click="rowClick2" border stripe ></Table>
                <!--修改-->
                <Modal v-model="setxiaoquModal" title="修改周边小区" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="setxiaoquCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">修改周边小区</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="setxiaoquCancel">取消</Button>
                        <Button type="primary" size="large" @click="setxiaoquOk">确定</Button>
                    </div>
                    <Form ref="setxiaoquformValidate" :model="setxiaoquformValidate" :rules="setxiaoquruleValidate"
                          :label-width="80">
                        <FormItem label="片区" prop="xiaoqu">
                            <Cascader :data="DtsData" v-model="setxiaoquformValidate.pianqu" @on-change="changeDts"></Cascader>
                        </FormItem>
                        <FormItem label="小区" prop="xiaoqu">
                            <Select  :transfer="true" v-model="setxiaoquformValidate.xiaoqu" >
                                <Option v-for="item in village" :value="item.village_id" :key="item.village_name">{{ item.village_name }}</Option>
                            </Select>
                        </FormItem>
                        <FormItem label="备注" prop="beizhu">
                            <Input v-model="setxiaoquformValidate.beizhu"></Input>
                        </FormItem>
                    </Form>
                </Modal>
                <Row type="flex" justify="end" style="margin-top: 10px">
                    <Col>
                    <Page :total="totalnum2" :current="currentpage2" @on-change="changePage2" :page-size="pageSize"
                          show-total></Page>
                    </Col>
                </Row>
                </Col >
            </Row>
        </Card>
        </Col>
    </Row>
</template>

<script>
    let inx = '';
    let inx2 = '';
    export default {
        name: 'xuequ',
        data() {
            return {
	            DtsData:[],
                guanjianzi:'',
                totalnum1: 0,
                totalnum2: 0,
                currentpage1: 1,
                currentpage2: 1,
                pageSize: 10,
                village:'',
                addSchoolModal: false,
                amendSchoolModal: false,
                addxiaoquModal:false,
                setxiaoquModal:false,
                addxiaoquformValidate:{
                    xiaoqu:'',
	                pianqu:[],
                    beizhu:''
                },
                addxiaoquruleValidate:{},
                setxiaoquformValidate:{
                    xiaoqu:'',
                    pianqu:[],
                    beizhu:''
                },
                setxiaoquruleValidate:{},
                formValidate: {
                    name: "",
                    address: ''
                },
                formValidateAmend: {
                    s_name: "",
                    s_address: ''
                },
                ruleValidateAmend:{},
                ruleValidate: {
                    name: [
                        {required: true, message: '请输入学校名称', trigger: 'blur'}
                    ],
                    address: [
                        {required: true, message: '请输入学校地址', trigger: 'blur'}
                    ],
                },
                school: [
                    {
                        title: '学校名称',
                        key: 'schoolName',
                        align: 'center'
                    },
                    {
                        title: '学校地址',
                        key: 'schoolAddree',
                        align: 'center'

                    },
                    {
                        title: '操作',
                        key: 'caozuo',
                        align: 'center',
                        width:258,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px',
                                        color: '#1877a8'
                                    },
                                    on: {
                                        click: () => {
                                            this.addxiaoqu(params);
                                        }
                                    }
                                }, '新增周边小区'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px',
                                        color: '#1877a8'
                                    },
                                    on: {
                                        click: () => {
                                            this.amend(params)
                                        }
                                    }
                                }, '修改学校名称'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px',
                                        color: '#1877a8'
                                    },
                                    on: {
                                        click: () => {
                                            this.removeSchool(params)
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                housing: [
                    {
                        title: '小区名称',
                        key: 'leixing',
                        align: 'center'
                    },
                    {
                        title: '备注',
                        key: 'beizhu',
                        align: 'center',
                    },
                    {
                        title: '操作',
                        key: 'caozuo',
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
                                        color: '#1877a8'
                                    },
                                    on: {
                                        click: () => {
                                            this.xiugaichild(params);
                                        }
                                    }
                                }, '修改'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px',
                                        color: '#1877a8'
                                    },
                                    on: {
                                        click: () => {
                                            this.removechild(params);
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                schoolData: [],
                housingData:[]

            }
        },
        created: function () {
            this.getxuequliset();
            this.getxuequchildlist()
        },
        methods: {
            rowClick1(data, index) {
                inx = index;
                this.getxuequchildlist();
            },
            rowClick2(data, index) {
                inx2 = index;
                this.setxiaoquformValidate.pianqu=[data.area_id,data.dts_id];
                this.setxiaoquformValidate.xiaoqu=data.rn_id;
            },
            //新增学校
            addSchool() {
                this.addSchoolModal = true;
            },
            addOk() {
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/school/add',
                            {
                                s_name: this.formValidate.name,
                                s_address: this.formValidate.address
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            console.log(response);
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['formValidate'].resetFields();
                                this.addSchoolModal = false;
                                this.$Message.success(response.data.message);
                                this.getxuequliset();
                            } else if (response.data.code == 401) {
                                this.$store.commit('logout', this);
                                this.$store.commit('clearOpenedSubmenu');
                                this.$router.push({
                                    name: 'login'
                                });
                            } else {
                                this.$Message.warning(response.data.message)
                            }
                        }, function (response) {
                            // 这里是处理错误的回调
                            this.$Message.warning('更新失败');
                        });
                    }
                });
            },
            addCancel() {
                this.$refs['formValidate'].resetFields();
                this.addSchoolModal = false;
            },
            //    学区列表
            changePage1(page) {
                this.currentpage1 = page;
                this.getxuequliset();
            },
            getxuequliset(){
                this.$http.get(api_param.apiurl + '/school/index',
                    {
                        params: {page: this.currentpage1, kw:this.guanjianzi},
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum1 = parseInt(response.body.data.count);
                        this.schoolData = response.body.data.list;
                        this.DtsData = response.body.data.dts;
                        // this.xiaoquData=response.body.data.villages;
                        // console.log(response.body.data.villages);
                        // let ii=0;
                        // for(let i in response.body.data.villages){
                        //     if(response.body.data.villages[i].hasOwnProperty('children')){
                        //         this.$set(this.xiaoquData,ii,response.body.data.villages[i]);
                        //         ii++;
                        //     }
                        // }
                        for (let i = 0; i < this.schoolData.length; i++) {
                            this.schoolData[i]['schoolName'] = this.schoolData[i].s_name;
                            this.schoolData[i]['schoolAddree'] = this.schoolData[i].s_address
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
            //    修改学校
            amend(params) {
                this.amendSchoolModal = true;
                this.formValidateAmend.s_name = params.row.s_name;
                this.formValidateAmend.s_address = params.row.s_address;
            },
            amendOk() {
                this.$refs['formValidateAmend'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/school/edit',
                            {
                                s_name: this.formValidateAmend.s_name,
                                s_address: this.formValidateAmend.s_address,
                                s_id: this.schoolData[inx].s_id
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            console.log(response);
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['formValidateAmend'].resetFields();
                                this.amendSchoolModal = false;
                                this.$Message.success(response.data.message);
                                this.getxuequliset();
                            } else if (response.data.code == 401) {
                                this.$store.commit('logout', this);
                                this.$store.commit('clearOpenedSubmenu');
                                this.$router.push({
                                    name: 'login'
                                });
                            } else {
                                this.$Message.warning(response.data.message)
                            }
                        }, function (response) {
                            // 这里是处理错误的回调
                            this.$Message.warning('更新失败');
                        });
                    }
                });
            },
            amendCancel() {
                this.$refs['formValidateAmend'].resetFields();
                this.amendSchoolModal = false;
            },
            //    删除学校
            removeSchool(params) {
                this.$Modal.confirm({
                    title: '删除部门',
                    content: '确定要删除吗',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + '/school/del',
                            {
                                s_id: params.row.s_id,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.getxuequliset();
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
            doSearch(){
                this.currentpage1 = 1;
                this.getxuequliset();
                this.housingData=''
            },
            //清空
            clearSearch () {
                this.guanjianzi = '';
                this.getxuequliset();
                this.housingData=''
            },

            //    新增周边小区
            addxiaoqu(params) {
                this.addxiaoquModal = true;
                this.addxiaoquformValidate.xiaoqu='';
                this.addxiaoquformValidate.beizhu='';
                console.log(params)
            },
            addxiaoquOk() {
                if(this.addxiaoquformValidate.xiaoqu){
                    this.$refs['addxiaoquformValidate'].validate((valid) => {
                        if (valid) {
                            this.$http.post(api_param.apiurl + '/school_district/add',
                                {
                                    rn_id:this.addxiaoquformValidate.xiaoqu,
                                    s_id: this.schoolData[inx].s_id,
                                    beizhu: this.addxiaoquformValidate.beizhu
                                },
                                {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                            ).then(function (response) {
                                console.log(response);
                                // 这里是处理正确的回调
                                if (response.data.code == 200) {
                                    this.$refs['addxiaoquformValidate'].resetFields();
                                    this.addxiaoquModal = false;
                                    this.$Message.success(response.data.message);
                                    this.getxuequchildlist();
                                } else if (response.data.code == 401) {
                                    this.$store.commit('logout', this);
                                    this.$store.commit('clearOpenedSubmenu');
                                    this.$router.push({
                                        name: 'login'
                                    });
                                } else {
                                    this.$Message.warning(response.data.message)
                                }
                            }, function (response) {
                                // 这里是处理错误的回调
                                this.$Message.warning('更新失败');
                            });
                        }
                    });
                }else{
                    this.$Message.warning('请选择小区');
                }

            },
            addxiaoquCancel() {
                this.$refs['addxiaoquformValidate'].resetFields();
                this.addxiaoquModal = false;
            },
            //    小区列表
            changePage2(page) {
                this.currentpage2 = page;
                this.getxuequchildlist();
            },
            getxuequchildlist() {
                this.$http.get(api_param.apiurl + '/school_district/index',
                    {
                        params: {page: this.currentpage2, s_id: this.schoolData[inx].s_id},
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum2 = parseInt(response.body.data.count);
                        this.housingData = response.body.data.list;
                        console.log(this.housingData);
                        for (let i = 0; i < this.housingData.length; i++) {
                            this.housingData[i]['leixing'] = this.housingData[i].village_name;
                            this.housingData[i]['beizhu'] = this.housingData[i].beizhu
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
            xiaoquDataChange(value, selectedData){
                let dts = selectedData[1].value;
                this.$http.get(api_param.apiurl + 'village/getvillage', {
                    params: {
                        dts_id: dts
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调
                        this.addxiaoquformValidate.xiaoqu=''
                        this.formValidate.village=''
                        this.village = response.data.data.list;
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
            //    小区修改
            xiugaichild(params){
                this.setxiaoquModal = true;

                this.getDts(params.row.dts_id);
	            this.setxiaoquformValidate.beizhu=params.row.beizhu;
	            this.setxiaoquformValidate.xiaoqu=params.row.rn_id;
                //     this.setxiaoquformValidate.xiaoqu[1]];
            },
            setxiaoquOk(){
                if(this.setxiaoquformValidate.xiaoqu){
                    this.$refs['setxiaoquformValidate'].validate((valid) => {
                        if (valid) {
                            this.$http.post(api_param.apiurl + '/school_district/edit',
                                {
                                    rn_id:this.setxiaoquformValidate.xiaoqu,
                                    s_id:this.schoolData[inx].s_id,
                                    sd_id: this.housingData[inx2].sd_id,
                                    beizhu: this.setxiaoquformValidate.beizhu
                                },
                                {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                            ).then(function (response) {
                                // 这里是处理正确的回调
                                if (response.data.code == 200) {
                                    this.$refs['addxiaoquformValidate'].resetFields();
                                    this.setxiaoquModal = false;
                                    this.$Message.success(response.data.message);
                                    this.getxuequchildlist();
                                } else if (response.data.code == 401) {
                                    this.$store.commit('logout', this);
                                    this.$store.commit('clearOpenedSubmenu');
                                    this.$router.push({
                                        name: 'login'
                                    });
                                } else {
                                    this.$Message.warning(response.data.message)
                                }
                            }, function (response) {
                                // 这里是处理错误的回调
                                this.$Message.warning('更新失败');
                            });
                        }
                    });
                }else{
                    this.$Message.warning('请选择小区');
                }
            },
            setxiaoquCancel() {
                this.$refs['setxiaoquformValidate'].resetFields();
                this.setxiaoquModal = false;
            },
            //    删除小区
            removechild(params) {
                console.log(params);
                this.$Modal.confirm({
                    title: '删除部门',
                    content: '确定要删除吗',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + '/school_district/del',
                            {
                                sd_id: params.row.sd_id,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.getxuequchildlist();
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

	        changeDts(value, selectedData) {
		        let dts = selectedData[1].value;
		        this.formValidate.dts_id = selectedData[1].value;
		        this.formValidate.dts_name = selectedData[1].label;
		        this.getDts(dts)
	        },
            getDts(dts){
	            this.$http.get(api_param.apiurl + 'village/getvillage', {
		            params: {
			            dts_id: dts
		            },
		            headers: {'X-Access-Token': api_param.XAccessToken}
	            }).then(function (response) {
		            if (response.data.code == 200) {// 这里是处理正确的回调
			            this.village = response.data.data.list;
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
            }

        },
        computed: {}
    };
</script>
