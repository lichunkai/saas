<style lang="less">
    @import "./management.less";
</style>

<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="10">
                <Col :lg="3" :md="3">
                <Cascader :data="departchoose" :value.sync="searchkey" filterable change-on-select
                          @on-change="searchChange" :transfer="false"></Cascader>
                </Col>
                <Col :lg="4" :md="4">
                <Button type="info" @click="getIndex">查询</Button>
                <Button type="info" @click="clearSearch">清空</Button>
                </Col>
                <Col :lg="2" :md="2" offset="15">
                <Row type="flex" justify="end">
                    <Col>
                    <div v-if="topbutton.add == 1">
                        <Button type="primary" @click="adddepart">新增</Button>
                    </div>
                    <Modal v-model="departmodal" :title="departmodeltitle" :mask-closable="false">
                        <div slot="header">
                            <a class="ivu-modal-close" @click="modalCancel"><i
                                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                            <div class="ivu-modal-header-inner">{{departmodeltitle}}</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="modalCancel">取消</Button>
                            <Button type="primary" size="large" @click="modalOk">确定</Button>
                        </div>
                        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80"
                              style="padding: 16px 40px;">
                            <FormItem label="部门名称" prop="d_name">
                                <Input v-model="formValidate.d_name" placeholder="请输入部门名称"></Input>
                            </FormItem>
                            <FormItem label="部门类型" prop="d_type">
                                <Select v-model.sync="formValidate.d_type" :transfer="false">
                                    <Option v-for="(item,index) in departtype" :value="index" :key="index">{{ item }}
                                    </Option>
                                </Select>
                            </FormItem>
                            <FormItem label="部门所属" prop="d_pid" v-if="formValidate.d_name !='总经办'">
                                <!--部门只选择到部门一级-->
                                <Cascader :data="departchoose" :value.sync="departvalue" change-on-select
                                          @on-change="handleChange" :transfer="false"></Cascader>
                            </FormItem>
                            <FormItem label="电话" prop="d_phone">
                                <Input v-model="formValidate.d_phone" placeholder="请输入联系电话"></Input>
                            </FormItem>
                            <FormItem label="部门区域" prop="d_district">
                                <Select v-model.sync="formValidate.d_district" :transfer="false" filterable>
                                    <Option v-for="(item,index) in district" :value="item.dts_id" :key="item.dts_id">{{
                                        item.dts_name }}
                                    </Option>
                                </Select>
                            </FormItem>
                            <FormItem label="地址" prop="d_address">
                                <Input v-model="formValidate.d_address" placeholder="请输入部门地址"></Input>
                            </FormItem>
                            <FormItem label="部门主管" prop="d_principal_id">
                                <!--显示部门的所有主管-->
                                <Select v-model.sync="formValidate.d_principal_id" :label-in-value="true" @on-change="selectChange" :transfer="false" filterable>
                                    <Option v-for="item in principalchoose" :value="item.u_id" :key="item.u_id">{{item.u_name }} </Option>
                                </Select>
                            </FormItem>
                            <!--<FormItem label="签约套餐" prop="contract_version" v-if="!formValidate.d_id && formValidate.d_type == 2">-->
                                <!--&lt;!&ndash;显示部门的所有主管&ndash;&gt;-->
                                <!--<Select v-model.sync="formValidate.contract_version" :transfer="false"  :disabled="is_disabled">-->
                                    <!--<Option  value="门店版">门店版(800元) </Option>-->
                                    <!--<Option  value="专业版">专业版(3800元) </Option>-->
                                <!--</Select>-->
                            <!--</FormItem>-->
                            <!--<FormItem label="" prop="agreement" v-if="!formValidate.d_id && formValidate.d_type == 2">-->
                                <!--&lt;!&ndash;<Checkbox v-model="formValidate.agreement">我已阅读并同意《宜居客房产系统收费产品用户使用协议》</Checkbox>&ndash;&gt;-->
                                <!--<Checkbox-group v-model="formValidate.agreement">-->
                                    <!--<Checkbox label="协议">我已阅读并同意《宜居客房产系统收费产品用户使用协议》</Checkbox>-->
                                <!--</Checkbox-group>-->
                            <!--</FormItem>-->
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
                <departmentTable :departlist="departlist" :contract_version="contract_version" v-on:editdepart="editdepart" v-on:deldepart="deldepart"  v-on:getIndex="getIndex"></departmentTable>
                </Col>
            </Row>
        </Card>
        </Col>
        <Modal v-model="addPay" title="微信支付" width="480" :label-width="80" :mask-closable="false" >
            <div slot="header">
                <a class="ivu-modal-close" @click="modalPayCancel" style="display: block!important;"><i
                        class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                <div class="ivu-modal-header-inner">微信支付</div>
            </div>
            <div slot="footer">
            </div>

            <!--微信支付-->
            <Row v-if="paystatus.status == 1">
                <Col :lg="24" :md="24">
                <div class="imgboxmain">
                    <img :src="qrcodeurl.url">
                </div>
                <p class="imgboxmain-p">扫描二维码立即支付</p>
                </Col>
            </Row>

            <!--申请开票-->
            <Row v-else-if="paystatus.status == 2">
                <Col :lg="24" :md="24">
                <div class="payok">
                    <img src="/src/images/payok.png">
                </div>
                <p class="payok-p">{{paymessage.data}}</p>
                <!--<div class="paybtn" v-if="afterpay.data == 1">-->
                    <!--<a @click="openPaypiao">申请开票</a>-->
                <!--</div>-->
                </Col>
            </Row>
        </Modal>
    </Row>

</template>

<script>
    import departmentTable from './department-table.vue';

    export default {
        name: 'department_index',
        components: {
            departmentTable,
        },
        data() {
            const validateDidCheck = (rule, value, callback) => {
                if (value == this.current_d_id) {
                    callback(new Error('部门归属不可为自身!'));
                } else {
                    callback();
                }
            };

            return {
                model1: '',
                topbutton: '',
                departmodal: false,
                departmodeltitle: '新增部门',
                departtype: [], //部门类型
                district: [],//区域类型
                departlist: [], //部门列表
                contract_version:'',//当前版本
                is_disabled:false,//select是否可用
                contract_storenum:'',//签约店个数
                searchkey: [], //查询条件
                departvalue: [], //所属部门级联
                departchoose: [],
                //principallist: [],//所属部门主管选择
                principalchoose: [],
                current_d_id: '',
                //部门弹窗验证
                formValidate: {
                    d_id: '',
                    d_name: '',
                    d_type: '',
                    d_level: 1,
                    d_pid: '',
                    d_pid_name: '',
                    d_phone: '',
                    d_district: '',
                    d_address: '',
                    d_principal: '',
                    d_principal_id: '',
                    contract_version: '',
                    agreement:['协议'],
                    amount:''
                },
                ruleValidate: {
                    d_name: [
                        {required: true, message: '请输入部门名', trigger: 'blur'}
                    ],
                    d_type: [
                        {required: true, message: '请选择部门类型', trigger: 'change'},
                    ],
                    d_phone: [
                        {required: true, message: '请输入电话或手机号', trigger: 'blur'},
                        {
                            validator(rule, value, callback, source, options) {
                                var errors = [];
                                if (!/^(13[0-9]|14[5-9]|15[012356789]|166|17[0-8]|18[0-9]|19[8-9])[0-9]{8}$/.test(value)) {
                                    callback('请输入正确的电话或手机号');
                                }
                                callback(errors);

                            }
                        }
                    ],
                    d_pid: [
                        {validator: validateDidCheck, trigger: 'blur'}
                    ],
                    d_district: [
                        {required: true, message: '请选择部门区域', trigger: 'blur'},
                    ],
                    // contract_version: [
                    //     {required: true, message: '请选择签约套餐', trigger: 'change'},
                    // ],
                    // agreement: [
                    //     {required: true,type: 'array',min: 1, message: '请先阅读并同意用户使用协议', trigger: 'change'},
                    // ],
                },
                principaldata: [], //主管数据
                //支付
                prePay:false,
                addPay: false,
                addPaypiao:false,
                store_id:0,
                store_name:'',
                pay_money:'',
                agreement:true,
                order_sn:'',
                paystatus: {
                    status: 1
                },
                paymessage:{
                    data:'',
                },
                afterpay:{
                    data:0,
                },
                qrurl:'',
                qrcodeurl:{
                    url:'',
                },
                querycount:0,
                stopquery:0,
            };
        },
        created() {
            this.getIndex()
        },
        methods: {
            getIndex() { //列表页
                this.$http.get(api_param.apiurl + 'depart/getindex', {
                    params: {d_id: this.searchkey}, headers: {"X-Access-Token": api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.departtype = response.data.data.departtype;
                        this.departlist = response.data.data.departlist;
                        this.departchoose = response.data.data.departchoose;
                        this.topbutton = response.data.data.topbutton;
                        this.district = response.data.data.district;
                        this.contract_version = response.data.data.contract_version;
                        this.contract_storenum = response.data.data.contract_storenum;
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        this.$Message.warning(response.data.message);
                    }
                    //console.log(this.departchoose);
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response)
                })
            },
            editdepart(params) {
                this.$refs['formValidate'].resetFields();
                this.departmodeltitle = '修改部门';
                this.formValidate.d_id = params.d_id;
                this.formValidate.d_name = params.d_name;
                this.formValidate.d_type = params.d_type;
                this.formValidate.d_level = params.d_level;
                this.formValidate.d_phone = params.d_phone;
                this.formValidate.d_district = params.d_district;
                this.formValidate.d_address = params.d_address;
                //归属部门
                this.departvalue = params.departpath;
                this.formValidate.d_pid = params.d_pid;
                this.formValidate.d_pid_name = params.d_pid_name;
                this.current_d_id = params.d_id;

                //部门主管
                this.getUser(params.d_pid);
                this.formValidate.d_principal = params.d_principal;
                this.formValidate.d_principal_id = params.d_principal_id;

                let _this = this;
                setTimeout(function () {
                    _this.departmodal = true;
                }, 300);
            },
            adddepart() {
                this.$refs['formValidate'].resetFields();
                this.departmodeltitle = '新增部门';
                this.formValidate.d_id = '';
                this.formValidate.d_name = '';
                this.formValidate.d_type = '';
                this.formValidate.d_pid = '';
                this.formValidate.d_pid_name = '';
                this.formValidate.d_phone = '';
                this.formValidate.d_district = '';
                this.formValidate.d_address = '';
                this.formValidate.d_principal = '';
                this.formValidate.d_principal_id = '';
                this.current_d_id = '';
                this.departvalue = [];
                if(this.contract_storenum != 0){
                    this.is_disabled = true;
                    this.formValidate.contract_version = this.contract_version;
                }else{
                    this.is_disabled = false;
                }
                let _this = this;
                setTimeout(function () {
                    _this.departmodal = true;
                }, 300);

            },
           deldepart(params) {
               console.log(params);
               this.$Modal.confirm({
                       title: '删除部门',
                       content: '确定要删除吗',
                       okText: '确定',
                       cancelText: '取消',
                       onOk: () => {
                       this.$http.post(api_param.apiurl + 'depart/del',
                           {
                               'd_id': params.d_id,
                           },
                           {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                       ).then(function (response) {
                           // 这里是处理正确的回调
                           if (response.data.code == 200) {
                               this.$Message.success('删除成功');
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
                       })
                   }

               })
           },
            modalOk() {
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        // let action = '';
                        // if (this.formValidate.d_id) {
                        //     action = 'depart/edit';
                        //     this.editDepart(action);
                        // } else {
                        //     this.gotoPay();
                        // }

                        let action = '';
                        if (this.formValidate.d_id) {
                            action = 'depart/edit';
                        } else {
                            action = 'depart/add';
                        }
                        this.editDepart(action);
                    }
                });
            },
            //部门数据添加
            editDepart(action){
                this.formValidate.d_pid = this.formValidate.d_pid == undefined ? 0 : this.formValidate.d_pid;
                this.formValidate.d_pid_name = this.formValidate.d_pid == 0 ? '' : this.formValidate.d_pid_name;
                this.$http.post(api_param.apiurl + action,
                    {
                        'd_id': this.formValidate.d_id,
                        'd_name': this.formValidate.d_name,
                        'd_type': this.formValidate.d_type,
                        'd_level': this.formValidate.d_level,
                        'd_pid': this.formValidate.d_pid,
                        'd_pid_name': this.formValidate.d_pid_name,
                        'd_phone': this.formValidate.d_phone,
                        'd_district': this.formValidate.d_district,
                        'd_address': this.formValidate.d_address,
                        'd_principal': this.formValidate.d_principal,
                        'd_principal_id': this.formValidate.d_principal_id,
                        //'sort': this.formValidate.sort,
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.$refs['formValidate'].resetFields();
                        this.departmodal = false;
                        this.$Message.success('更新成功');
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
                    console.log(response)
                })
            },
            modalCancel() {
                this.departmodal = false;
                //清空form规则检查
                this.$refs['formValidate'].resetFields();
            },
            handleChange(value, selectedData) { //级联选择回调
                this.formValidate.d_pid = value[value.length - 1];
                this.formValidate.d_pid_name = selectedData[value.length - 1]['label'];

                console.log(value.length);
                this.formValidate.d_level = parseInt(value.length) + 1;
                this.getUser(this.formValidate.d_pid);

            },
            //获取主管人选
            getUser(d_id){
                this.$http.post(api_param.apiurl + 'depart/getuser',
                    {
                        'd_id': d_id,
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.principalchoose = response.data.data;
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response)
                })
            },
            selectChange(selectedData) { //主管选择
                this.formValidate.d_principal = selectedData.label;
                this.formValidate.d_principal_id = selectedData.value;
            },
            searchChange(value, selectedData) {
                this.searchkey = [value[value.length - 1]];
            },
            clearSearch() {
                this.searchkey = [];
                this.getIndex();
            },
            //支付
            modalPrepayCancel(){
                this.prePay = false;
                this.store_id = 0;
                this.contract_version ='';
                this.store_name = '';
                this.pay_money = 0;
            },
            gotoPay(){
                this.querycount = 0;
                this.stopquery = 0;
                this.formValidate.d_pid = this.formValidate.d_pid == undefined ? 0 : this.formValidate.d_pid;
                this.formValidate.d_pid_name = this.formValidate.d_pid == 0 ? '' : this.formValidate.d_pid_name;
                this.formValidate.amount = this.formValidate.contract_version == '门店版' ? 800:3800,
                this.$http.post(api_param.apiurl + 'payment/unified',
                    {
                        'd_id': this.formValidate.d_id,
                        'd_name': this.formValidate.d_name,
                        'd_type': this.formValidate.d_type,
                        'd_level': this.formValidate.d_level,
                        'd_pid': this.formValidate.d_pid,
                        'd_pid_name': this.formValidate.d_pid_name,
                        'd_phone': this.formValidate.d_phone,
                        'd_district': this.formValidate.d_district,
                        'd_address': this.formValidate.d_address,
                        'd_principal': this.formValidate.d_principal,
                        'd_principal_id': this.formValidate.d_principal_id,
                        'order_title': '宜居客房产系统'+this.formValidate.contract_version,
                        'contract_version': this.formValidate.contract_version,
                        'amount':this.formValidate.amount
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.qrurl = response.data.data.qrurl;
                        this.$set(this.qrcodeurl,'url',api_param.apiurl +"payment/qrcode?url="+this.qrurl);
                        this.order_sn = response.data.data.order_sn;
                        this.addPay = true;
                        let _this = this;
                        setTimeout(function () {
                            _this.queryOrder();
                        }, 500);
                    } else if (response.data.code == 401) {
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
                    console.log(response)
                })
                this.addPay = true;
            },
            modalPayCancel(){
                this.addPay = false;
                this.querycount = -1;
                this.stopquery = 1;
                this.$set(this.paystatus,'status',1);
                this.$set(this.paymessage,'data','');
                this.modalCancel();
                this.getIndex();
            },
            queryOrder(){
                if(this.stopquery == 0){
                    this.$http.post(api_param.apiurl + 'payment/queryorder',
                        {
                            'order_sn': this.order_sn,
                            'count':this.querycount
                        },
                        {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                    ).then(function (response) {
                        // 这里是处理正确的回调
                        if (response.data.code == 200) {
                            if(response.data.data == 0){
                                this.$set(this.paystatus,'status',2);
                                this.$set(this.paymessage,'data',response.data.message);
                                this.$set(this.afterpay,'data',1);
                            }else if(response.data.data == 60){
                                this.$set(this.paystatus,'status',2);
                                this.$set(this.paymessage,'data',response.data.message);
                                this.$set(this.afterpay,'data',2);
                            }else if(response.data.data == -1){
                                this.addPay = false;
                                this.querycount = -1;
                                this.$set(this.paystatus,'status',1);
                                this.$set(this.paymessage,'data','');
                            }else{
                                this.querycount = response.data.data;
                                let _this = this;
                                setTimeout(function () {
                                    _this.queryOrder();
                                }, 500);
                            }
                        } else if (response.data.code == 204) {
                            this.querycount = response.data.data;
                            let _this = this;
                            setTimeout(function () {
                                _this.queryOrder();
                            }, 500);
                        }else if (response.data.code == 400) {
                            this.$set(this.paystatus,'status',2);
                            this.$set(this.paymessage,'data',response.data.message);
                            this.$set(this.afterpay,'data',2);
                        }else if (response.data.code == 401) {
                            this.$store.commit('logout', this);
                            this.$store.commit('clearOpenedSubmenu');
                            this.$router.push({
                                name: 'login'
                            });
                        }
                    }, function (response) {
                        // 这里是处理错误的回调
                        console.log(response)
                    })
                }else{
                    this.addPay = false;
                    this.querycount = -1;
                    this.stopquery = 1;
                    this.$set(this.paystatus,'status',1);
                    this.$set(this.paymessage,'data','');
                }
            },

        },
        computed: {}
    };
</script>