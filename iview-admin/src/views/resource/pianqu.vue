<style lang="less">
    @import "layout.less";
</style>

<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="10">
                <Col :lg="3" :md="3">
                    <Cascader :data="wholearea" v-model="cityData"  placeholder="地区选择" filterable change-on-select  ></Cascader>
                </Col>
                <Col :lg="3" :md="3">
                    <Select placeholder="行政区选择" v-model="area_value" :transfer="true" :filterable="true" @on-change="changeDts" :label-in-value="true">
                        <Option v-for="item in columns1Data" :value="item.ar_id" :key="item.ar_id">{{ item.ar_name }}</Option>
                    </Select>
                </Col>
                <Col :lg="4" :md="4">
                    <Button type="primary" @click="doSearch" >搜索</Button>
                </Col>
                <Col :lg="14" :md="14">
                    <Row type="flex" justify="end">
                        <Col>
                        <Button type="primary"  @click="addPianquModal">新增片区</Button>
                        </Col>
                    </Row>
                </Col>
            </Row>
        </Card>
            </Col>
            <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Modal v-model="addPianqu" title="新增片区" :mask-closable="false">
                <div slot="header">
                    <a class="ivu-modal-close" @click="modalCancel_pianqu" style="display: block!important;"><i
                            class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                    <div class="ivu-modal-header-inner">新增片区</div>
                </div>
                <div slot="footer">
                    <Button type="text" size="large" @click="modalCancel_pianqu">取消</Button>
                    <Button type="primary" size="large" @click="modalOk_pianqu">确定</Button>
                </div>
                <Form ref="addDistrict" :model="addDistrict" :rules="ruleValidate" :label-width="80">
                    <FormItem label="所属区域">
                        <Input type="text" v-model="addDistrict.quyu"  readonly disabled="disabled"></Input>
                    </FormItem>
                    <FormItem label="片区名称" prop="name">
                        <Input type="text" v-model="addDistrict.name"></Input>
                    </FormItem>
                    <FormItem label="片区地址" prop="address">
                        <Input type="text" v-model="addDistrict.address"></Input>
                    </FormItem>
                </Form>
            </Modal>
            <Row :gutter="20">
                <Col :lg="24" :md="24">
                <Table :columns="columns2" :data="columns2Data" @on-row-click="rowClick2"
                        border stripe></Table>
                <Row style="margin-top: 10px" type="flex" justify="end">
                    <Col>
                <Page :total="totalnum2" :current="currentpage2" @on-change="changePage2" :page-size="pageSize"
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
    import iviewArea from 'iview-area';

    export default {
        name: 'pianqu_index',
        components: {},
        data() {
            return {
                cityData:['jiangsu','suzhou'],
                area_value:0,
                area_name:'',
                wholearea: [ {
                    value: 'jiangsu',
                    label: '江苏',
                    children: [
                        {
                            value: 'suzhou',
                            label: '苏州',

                        }
                    ],
                }],

                searchData: {
                    dts_id: []
                },
                settings: {
                    topbutton:[],
                },
                //配置项目
                //增加片区
                addDistrict:{
                    id:0,
                    quyu:'',
                    name:'',
                    address:''
                },
                ruleValidate: {
                    name: [
                        {required: true, message: '请输入片区名称', trigger: 'blur'}
                    ],
                    address: [
                        {required: true, message: '请输入片区地址', trigger: 'blur'}
                    ],
                },
                xiugaiDistrict:{
                    quyu:'',
                    name:'',
                    address:''
                },
                xiugaiDistrictRules:{
                    name: [
                        {required: true, message: '请输入片区名称', trigger: 'blur'}
                    ],
                    address: [
                        {required: true, message: '请输入片区地址', trigger: 'blur'}
                    ],
                },
                //增加行政区
                addAdministrative:{
                    res1:[],
                    dt_province_id: '',
                    dt_province_name: '',
                    dt_city_id: '',
                    dt_city_name: '',
                    dt_area_id: '',
                    dt_area_name: '',
                },
                addAdministrativeRules:{},
                xiugaiAdministrative:{
                    res2:[]
                },
                xiugaiAdministrativeRules:{},
                xiugaixingzheng:false,
                addxingzheng:false,
                addPianqu: false,
                xiugaiPianqu: false,
                columns1: [
                    {
                        title: "行政区",
                        key: 'ar_name',
                        align: 'center'
                    },
                    // {
                    //     title: "操作",
                    //     key: 'schoolName',
                    //     align: 'center',
                    //     render: (h, params) => {
                    //         return h('div', [
                    //             h('a', {
                    //                 props: {
                    //                     type: 'primary',
                    //                     size: 'small'
                    //                 },
                    //                 on: {
                    //                     click: () => {
                    //                         this.addpianqu();
                    //                     }
                    //                 }
                    //             }, '新增片区')
                    //         ]);
                    //     }
                    //
                    // }
                ],
                columns1Data:[],
                totalnum1:0,
                currentpage1:1,
                pageSize: 10,
                columns2: [
                    {
                        title: "片区",
                        key: 'pianqu',
                        align: 'center'
                    },
                    {
                        title: "地址",
                        key: 'address',
                        align: 'center'
                    },
                    {
                        title: "操作",
                        key: 'schoolName',
                        align: 'center',
                        render: (h, params) => {
                        	if(params.row.dts_status==1){
		                        return h("div", [
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
						                        this.xiugaiPianqu=true;
						                        this.xiugaiDistrict.quyu=this.columns1Data[inx].ar_name;
						                        this.xiugaiDistrict.name=params.row.pianqu;
						                        this.xiugaiDistrict.address=params.row.address;
					                        }
				                        }
			                        }, '修改'),
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
						                        this.remove2(params)
					                        }
				                        }
			                        }, '删除')
		                        ])
                            }else {
		                        return h("div",{style: { color: '#ddd'}},'系统参数无法修改或者删除')
                            }

                        }

                    }
                ],
                columns2Data: [],
                totalnum2:0,
                currentpage2:1,
                ar_id:0,
            }
        },
        created: function () {
            this.getOrganizational();
            this.getpianqu();

        },
        methods: {
            doSearch(){
                this.getpianqu();
            },
            changeDts(selectData){
                this.area_value = selectData.value;
                this.area_name = selectData.label;
            },
            addPianquModal(){
                this.addDistrict.id = this.area_value;
                this.addDistrict.quyu = this.area_name;
                this.addPianqu = true;
            },
            // 行政区列表
            getOrganizational () {
                this.$http.get(api_param.apiurl + 'district/areaindex',
                    {
                        params: {
                            page: this.currentpage1,
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if(response.data.code==200){
                        this.cityData = [{value: 'jiangsu', label: '江苏', children: [{value: 'suzhou', label: '苏州'}]}];//response.body.data.cityData
                        // this.columns1Data = response.body.data.arealist;
                        this.area_value = response.body.data.list[0].ar_id;//response.body.data.area_value
                        this.area_name = response.body.data.list[0].ar_name;//response.body.data.area_name
                        this.totalnum1 = parseInt(response.body.data.count);
                        this.columns1Data = response.body.data.list;
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
            changePage1 (page) {
                this.currentpage1 = page;
                this.getOrganizational();
            },

            //片区列表
            getpianqu () {
                this.$http.get(api_param.apiurl + '/district/dtsindex',
                    {
                        params: {
                            ar_id:this.area_value
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if(response.data.code==200){
                        this.totalnum2 = parseInt(response.body.data.count);
                        this.columns2Data = response.body.data.list;
                        for (let i = 0; i < this.columns2Data.length; i++) {
                            this.columns2Data[i]['pianqu'] =this.columns2Data[i].dts_name;
                            this.columns2Data[i]['address'] =this.columns2Data[i].area_name+this.columns2Data[i].dts_address
                        }
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
            },
            changePage2 (page) {
                this.currentpage2 = page;
                this.getpianqu();
            },
            // 增加片区
            addpianqu(index){
                this.addPianqu = true;
                this.addDistrict.quyu = '';
                this.addDistrict.name = '';
                this.addDistrict.address = '';
            },
            modalOk_pianqu(){
                this.$refs['addDistrict'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/district/add',
                            {
                                'area_id': this.columns1Data[inx].ar_id,
                                'area_name': this.columns1Data[inx].ar_name,
                                'dts_name': this.addDistrict.name,
                                'dts_address': this.addDistrict.address,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if(response.data.code==200){
                                this.$refs['addDistrict'].resetFields();
                                this.addPianqu = false;
                                this.$Message.success(response.data.message);
                                this.getpianqu();
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
            },
            modalCancel_pianqu(){
                this.addPianqu = false;
                //清空form规则检查
                this.$refs['addDistrict'].resetFields();
            },
            //删除片区
            remove2 (params) {
                this.$Modal.confirm({
                    title: '删除片区',
                    content: '确定要删除吗',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + '/district/del',
                            {
                                'dts_id': params.row.dts_id,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if(response.data.code==200){
                                this.$Message.success(response.data.message);
                                this.getpianqu();
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
            //修改片区
            xiugaiOk_pianqu () {
                this.$refs['xiugaiDistrict'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'district/edit',
                            {
                                'dts_id':this.columns2Data[inx2].dts_id,
                                'dts_name':this.xiugaiDistrict.name ,
                                'dts_address':this.xiugaiDistrict.address,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if(response.data.code==200){
                                this.$refs['xiugaiDistrict'].resetFields();
                                this.xiugaiPianqu = false;
                                this.$Message.success(response.data.message);
                                this.getpianqu();
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
            },
            xiugaiCancel_pianqu(){
                this.xiugaiPianqu = false;
                //清空form规则检查
                this.$refs['xiugaiDistrict'].resetFields();
            },
            //点击行政区表格
            // rowClick1 (data, index) {
            //     inx=index;
            //     this.addDistrict.quyu = this.columns1Data[index]['ar_name'];
            //     this.getpianqu();
            // },
            //点击片区表格
            rowClick2 (data, index) {
                inx2=index;
            }
        }
    };
</script>