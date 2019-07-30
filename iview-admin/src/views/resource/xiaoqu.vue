<style lang="less">
</style>

<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="5">
                <Col :lg="2" :md="2">
                <Cascader :data="pianquData" v-model="pianquDataModel"></Cascader>
                </Col>
                <Col :lg="2" :md="2">
                <Input placeholder="小区名搜索" v-model="guanjianzi"></Input>
                </Col>
                <Col :lg="3" :md="3">
                <Button type="primary"  @click="doSearch">查询</Button>
                <Button type="primary" @click="clearSearch">清空</Button>
                </Col>
                <Col :lg="2" :md="2" offset="15">
                <Row type="flex" justify="end">
                    <Col>
                    <Button type="primary" @click="xiaoquAdd">新增</Button>
                    <Modal v-model="xiaoquAddModal" title="新增小区" :mask-closable="false">
                        <div slot="header">
                            <a class="ivu-modal-close" @click="addCancel" style="display: block!important;"><i
                                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                            <div class="ivu-modal-header-inner">新增小区</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="addCancel">取消</Button>
                            <Button type="primary" size="large" @click="addOk">确定</Button>
                        </div>
                        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
                            <FormItem label="小区名称" prop="name">
                                <Input placeholder="" v-model="formValidate.name"></Input>
                            </FormItem>
                            <FormItem label="所属片区" prop="quyu">
                                <!--<al-cascader v-model="formValidate.quyu" level="2"/>-->
                                <Cascader :data="pianquData" v-model="formValidate.quyu" ></Cascader>
                            </FormItem>
                            <FormItem label="小区地址" prop="address">
                                <Input placeholder="" v-model="formValidate.address"></Input>
                            </FormItem>
                            <FormItem label="出售均价" prop="averagePrice">
                                <Input placeholder="" v-model="formValidate.averagePrice"><span slot="append">元/㎡</span></Input>
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
            <Row>
                <Col>
                <Table border :columns="xiaoquColumns" :data="xiaoquData" @on-row-click="clickRow"></Table>
                <Modal v-model="xiaoquset" title="修改小区" :mask-closable="false">
                <div slot="header">
                    <a class="ivu-modal-close" @click="settingCancel" style="display: block!important;"><i
                            class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                    <div class="ivu-modal-header-inner">修改小区</div>
                </div>
                <div slot="footer">
                    <Button type="text" size="large" @click="settingCancel">取消</Button>
                    <Button type="primary" size="large" @click="settingOk">确定</Button>
                </div>
                <Form ref="setformValidate" :model="setformValidate" :rules="setruleValidate" :label-width="80">
                    <FormItem label="小区名称" prop="name">
                        <Input v-model="setformValidate.name" ></Input>
                    </FormItem>
                    <FormItem label="所属片区">
                        <Cascader :data="pianquData" v-model="setformValidate.quyu"></Cascader>
                    </FormItem>
                    <FormItem label="小区地址" prop="address">
                        <Input type="text" v-model="setformValidate.address" ></Input>
                    </FormItem>

                    <FormItem label="出售均价" prop="averagePrice">
                        <Input type="text" v-model="setformValidate.averagePrice" ><span slot="append">元/平方米</span></Input>
                    </FormItem>
                </Form>
            </Modal>
                <Row style="margin-top: 10px" type="flex" justify="end">
                    <Col>
                    <Page :total="totalnum1" :current="currentpage1" @on-change="changePage1" :page-size="pageSize"
                          show-total  ></Page>
                    </Col>
                </Row>
                </Col>
            </Row>
        </Card>
        </Col>
    </Row>
</template>

<script>
    // import iviewArea from 'iview-area';
    let inx='';
    export default {
        name: 'xiaoqu',
        data() {
            return {
                pianquDataModel:[],
                formValidate:{
                    name:'',
                    quyu:[],
                    address:'',
                    averagePrice:''
                },
                ruleValidate:{
                    name: [
                        {required: true, message: '请输入小区名称', trigger: 'blur'}
                    ],
                    address: [
                        {required: true, message: '请输入小区地址', trigger: 'blur'}
                    ],
                    averagePrice: [
                        {required: true, message: '请输入出售均价', trigger: 'blur'}
                    ],
                },

                guanjianzi:'',
                totalnum1:0,
                currentpage1:1,
                pageSize: 10,
//        片区搜索
                cityLists: [
                    {
                        value: 'wuzhong',
                        label: '吴中区'
                    },
                    {
                        value: 'yuanqu',
                        label: '园区'
                    },],
                cityModel: '',
//                新增
                xiaoquAddModal: false,
                //修改
                xiaoquset:false,
                setformValidate:{
                    name:'',
                    quyu:[],
                    address:'',
                    averagePrice:'',
                },
                setruleValidate:{
                    name: [
                        {required: true, message: '请输入小区名称', trigger: 'blur'}
                    ],
                    address: [
                        {required: true, message: '请输入小区地址', trigger: 'blur'}
                    ],
                    // averagePrice: [
                    //     {required: true, message: '请输入出售均价', trigger: 'blur'}
                    // ],
                },

//        表格
                xiaoquColumns: [
                    {
                        title: '小区名称',
                        key: 'village_name',
                        align: 'center',

                    },
                    {
                        title: '所属行政区',
                        key: 'area_name',
                        align: 'center',
                    },
                    {
                        title: '所属片区',
                        key: 'dts_name',
                        align: 'center',
                    },

                    {
                        title: '出售均价',
                        key: 'village_price',
                        align: 'center',
                    },
                    {
                        title: '小区地址',
                        key: 'village_address',
                        align: 'center',
                    },
                    {
                        title: '操作',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                        	// if(params.row.village_status==1){
		                        return h('div', [
			                        h('Button', {
				                        props: {
					                        type: 'info',
					                        size: 'small'
				                        },
				                        style: {
					                        marginRight: '5px'
				                        },
				                        on: {
					                        click: () => {
						                        let argu = {
							                        projId: params.row.village_id,
							                        projName: params.row.village_name,
                                                    // projNumber: params.row.village_name
						                        };
						                        this.$router.push({
							                        name: 'loudong',
							                        params: argu
						                        });
					                        }
				                        }
			                        }, '楼盘字典'),
									h('Button', {
									    props: {
									        type: 'warning',
									        size: 'small'
									    },
									    style: {
									        marginRight: '5px'
									    },
									    on: {
									        click: () => {
									            let argu = {
									                projId: params.row.village_id,
									            };
									            this.$router.push({
									                name: 'xiaoqudetails',
									                params: argu
									            });
									        }
									    }
									}, '小区详情'),
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
						                        this.compile(params)
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
						                        this.remove(params);
					                        }
				                        }
			                        }, '删除')
		                        ])
                            // }else{
		                    //     return h("div",{style: { color: '#ddd'}},'系统参数无法修改或者删除')
                            // }


                        }
                    }
                ],
                xiaoquData: [],
                pianquData:[]
            }
        },
        created:function(){
            this.getxiaoqulist();
        },
        methods: {
            clickRow(data,index){
                inx=index;
            },
            //小区列表
            changePage1 (page) {
                this.currentpage1 = page;
                this.getxiaoqulist();
            },
            getxiaoqulist(){
                this.$http.get(api_param.apiurl + 'village/index',
                    {
                        params: {
                            page: this.currentpage1,
                            kw:this.guanjianzi,
                            dts_id:this.pianquDataModel[1],
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if(response.data.code==200){
                        this.totalnum1 = parseInt(response.body.data.count);
                        this.xiaoquData = response.body.data.list;
                        this.pianquData=response.body.data.district;
                    }else if(response.data.code==401){
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    }else{
                        this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            },
            //小区修改
            compile (params) {
                this.xiaoquset=true;
                this.setformValidate.name=params.row.village_name;
                this.setformValidate.address=params.row.village_address;
                this.setformValidate.averagePrice=params.row.village_price;
                this.setformValidate.quyu=[params.row.area_id,params.row.dts_id];
            },
            settingOk(){
                if(this.setformValidate.quyu.length===2){
                this.$refs['setformValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/village/edit',
                            {
                                'dts_id': this.setformValidate.quyu[1],
                                'village_price': this.setformValidate.averagePrice,
                                'village_address':  this.setformValidate.address,
                                'village_name':  this.setformValidate.name,
                                'village_id': this.xiaoquData[inx].village_id,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if(response.data.code==200){
                                this.$refs['setformValidate'].resetFields();
                                this.xiaoquset = false;
                                this.$Message.success(response.data.message);
                                this.getxiaoqulist();
                            }else if(response.data.code==401){
                                this.$store.commit('logout', this);
                                this.$store.commit('clearOpenedSubmenu');
                                this.$router.push({
                                    name: 'login'
                                });
                            }else{
                                this.$Message.warning(response.data.message);
                            }

                        }, function (response) {
                            // 这里是处理错误的回调
                            this.$Message.warning('更新失败');
                        });
                    }
                });
                }else{
                    this.$Message.warning('请选择行政区下的片区');
                }
            },
            settingCancel(){
                this.xiaoquset=false;
                this.$refs['setformValidate'].resetFields();
            },
            //小区增加
            xiaoquAdd(){
              this.xiaoquAddModal=true;
            },
            addOk(){
                if(this.formValidate.quyu.length==2){
                    this.$refs['formValidate'].validate((valid) => {
                        if (valid) {
                            this.$http.post(api_param.apiurl + '/village/add',
                                {
                                    dts_id:this.formValidate.quyu[1],
                                    village_price:this.formValidate.averagePrice,
	                                village_address:this.formValidate.address,
	                                village_name:this.formValidate.name
                                },
                                {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                            ).then(function (response) {
                                console.log(response);
                                // 这里是处理正确的回调
                                if(response.data.code==200){
                                    this.$refs['formValidate'].resetFields();
                                    this.xiaoquAddModal = false;
                                    this.$Message.success(response.data.message);
                                    this.getxiaoqulist();
                                }else if(response.data.code==401){
                                    this.$store.commit('logout', this);
                                    this.$store.commit('clearOpenedSubmenu');
                                    this.$router.push({
                                        name: 'login'
                                    });
                                }else{
                                    this.$Message.warning(response.data.message)
                                }
                            }, function (response) {
                                // 这里是处理错误的回调
                                this.$Message.warning('更新失败');
                            });
                        }
                    });
                }else{
                    this.$Message.warning('请选择行政区下的片区');
                }

            },
            addCancel(){
                this.$refs['formValidate'].resetFields();
                this.xiaoquAddModal = false;
            },
            //小区删除、
            remove(params){
                this.$Modal.confirm({
                    title: '删除小区',
                    content: '确定要删除吗',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + '/village/del',
                            {
	                            village_id:params.row.village_id,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if(response.data.code==200){
                                this.$Message.success(response.data.message);
                                this.getxiaoqulist();
                            }else if(response.data.code==401){
                                this.$store.commit('logout', this);
                                this.$store.commit('clearOpenedSubmenu');
                                this.$router.push({
                                    name: 'login'
                                });
                            }else{
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
                this.getxiaoqulist();
            },
            //清空
            clearSearch () {
                this.guanjianzi = '';
                this.pianquDataModel='';
                this.getxiaoqulist();
            },

        },
        computed: {}
    }
</script>
