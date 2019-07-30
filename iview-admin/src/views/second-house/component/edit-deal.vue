<style>
    @import "roomdetails.less";
</style>
<template>
    <Modal v-model="rDeal" :title="buyDealtitle" :mask-closable="false" width="960">
        <div slot="header">
            <a class="ivu-modal-close" @click="dealModalCancel" style="display: block!important"><i
                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
            <div class="ivu-modal-header-inner">{{buyDealtitle}}</div>
        </div>
        <div slot="footer">
            <Button type="text" size="large" @click="dealModalCancel">取消</Button>
            <Button type="primary" size="large" @click="dealModalOk">确定</Button>
        </div>
        <Form ref="formDealValidate" :model="formDealValidate" :rules="ruleDealValidate" :label-width="80">
            <Row>
                <Col :lg="24" :md="24">
                <Row>
                    <Col :lg="8" :md="8">
                    <FormItem label="成交日期" prop="order_deal_date">
                        <DatePicker :value="formDealValidate.order_deal_date" type="date" format="yyyy-MM-dd"
                                    @on-change="formDealValidate.order_deal_date=$event"></DatePicker>
                    </FormItem>
                    </Col>
                    <Col :lg="8" :md="8">
                    <FormItem label="成交部门" prop="d_id">
                        <Cascader :data="departlist" :value.sync="formDealValidate.deal_departpath" change-on-select
                                  @on-change="cjhandleChange"></Cascader>
                    </FormItem>
                    </Col>
                    <Col :lg="8" :md="8">
                    <FormItem label="成交人" prop="order_deal_user">
                        <Select placeholder="" v-model="formDealValidate.order_deal_user" :label-in-value="true"
                                @on-change="selectDealuser">
                            <Option v-for="(item,index) in cjuser" :value="item.u_id" :key="item.u_id">{{item.u_name}}
                            </Option>
                        </Select>
                    </FormItem>
                    </Col>
                </Row>
                </Col>
                <Col :lg="24" :md="24">
                <Row>
                    <Col :lg="8" :md="8">
                    <FormItem label="协议编号" prop="agreement_sn">
                        <Input v-model="formDealValidate.agreement_sn" placeholder=""></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="8" :md="8">
                    <Row v-if="formDealValidate.order_type==1">
                        <FormItem label="成交价格" prop="order_price">
                            <Input v-model="formDealValidate.order_price" placeholder="" >
                            <span slot="append">元/月</span>
                            </Input>
                        </FormItem>
                    </Row>
                    <Row v-else>
                        <Col :lg="18" :md="18">
                        <FormItem label="成交价格" prop="order_price">
                            <Input v-model="formDealValidate.order_price" placeholder="" ><span
                                slot="append">万元</span></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="6" :md="6" class="daikuan">
                        <FormItem prop="order_loan">
                            <Checkbox v-model="formDealValidate.order_loan" prop="order_loan">有贷款</Checkbox>
                        </FormItem>
                        </Col>
                    </Row>
                    </Col>
                    <Col :lg="8" :md="8">
                    <FormItem label="业主应收佣金" prop="order_owner_commission">
                        <Input v-model="formDealValidate.order_owner_commission" placeholder="" ><span
                            slot="append">元</span></Input>
                    </FormItem>
                    </Col>
                </Row>
                </Col>
                <Col :lg="24" :md="24">
                <Row>
                    <Col :lg="8" :md="8">
                    <FormItem label="客户应收佣金" prop="order_customer_commission">
                        <Input v-model="formDealValidate.order_customer_commission" placeholder="" ><span
                            slot="append">元</span></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="8" :md="8" v-if="formDealValidate.order_type==1">
                    <FormItem label="开始时间" prop="order_start_time">
                        <DatePicker type="date" :value="formDealValidate.order_start_time" format="yyyy-MM-dd"
                                    @on-change="formDealValidate.order_start_time=$event"></DatePicker>
                    </FormItem>
                    </Col>
                    <Col :lg="8" :md="8" v-if="formDealValidate.order_type==1">
                    <FormItem label="结束时间" prop="order_end_time">
                        <DatePicker type="date" :value="formDealValidate.order_end_time" format="yyyy-MM-dd"
                                    @on-change="formDealValidate.order_end_time=$event"></DatePicker>
                    </FormItem>
                    </Col>
                    <Col :lg="8" :md="8" v-if="formDealValidate.order_type!=1">
                    <FormItem label="权证部门" prop="d_id">
                        <Cascader :data="departlist" :value.sync="formDealValidate.property_departpath" change-on-select
                                  @on-change="qzhandleChange"></Cascader>
                    </FormItem>
                    </Col>
                    <Col :lg="8" :md="8" v-if="formDealValidate.order_type!=1">
                    <FormItem label="权证人" prop="order_property_user">
                        <Select placeholder="" v-model="formDealValidate.order_property_user" :label-in-value="true"
                                @on-change="selectPropertyuser">
                            <Option v-for="(item,index) in qzuser" :value="item.u_id" :key="item.u_id">{{item.u_name}}
                            </Option>
                        </Select>
                    </FormItem>
                    </Col>
                </Row>
                </Col>
                <Col :lg="24" :md="24">
                <Row>
                    <Col :lg="8" :md="8">
                    <FormItem label="土地证号" prop="house_land_certificate">
                        <Input v-model="formDealValidate.house_land_certificate" placeholder=""></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="8" :md="8">
                    <FormItem label="产权证号" prop="house_property_certificate">
                        <Input v-model="formDealValidate.house_property_certificate" placeholder=""></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="8" :md="8">
                    <FormItem label="交易类型" prop="order_deal_type">
                        <Select placeholder="" v-model="formDealValidate.order_deal_type"  :transfer="true">
                            <Option value="1" >本公司自有</Option>
                            <Option value="2" >合作房源</Option>
                            <Option value="3" >合作客源</Option>
                            <Option value="4" >代办过户</Option>
                        </Select>
                    </FormItem>
                    </Col>
                </Row>
                </Col>
                <Col :lg="24" :md="24">
                <Row>
                    <Col :lg="24" :md="24">
                    <FormItem label="补充条款" prop="order_addition_terms">
                        <Input type="textarea" :rows="2" placeholder=""
                               v-model="formDealValidate.order_addition_terms"></Input>
                    </FormItem>
                    </Col>
                </Row>
                </Col>
                <Col :lg="24" :md="24">
                <div style="width: 100%;height: 1px;background-color: #e5e5e5;margin-bottom: 20px"></div>
                </Col>
                <Col :lg="24" :md="24">
                <Row>
                    <Col :lg="8" :md="8">
                    <FormItem label="甲方编号" prop="owner_sn">
                        <Input placeholder="" v-model="formDealValidate.owner_sn" readonly></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="8" :md="8">
                    <FormItem label="业主电话" prop="owner_phone">
                        <Input placeholder="" v-model="formDealValidate.owner_phone" ></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="8" :md="8">
                    <Row>
                        <Col :lg="16" :md="16">
                        <FormItem label="甲方姓名" prop="owner_name">
                            <Input placeholder="" v-model="formDealValidate.owner_name" ></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="7" :md="7" class="ownerSex">
                        <FormItem prop="owner_sex">
                            <Select v-model="formDealValidate.owner_sex" :transfer="true" >
                                <Option value="先生">先生</Option>
                                <Option value="女士">女士</Option>
                                <Option value="公司">公司</Option>
                            </Select>
                        </FormItem>
                        </Col>
                    </Row>
                    </Col>
                </Row>
                </Col>
                <Col :lg="24" :md="24">
                <Row>
                    <Col :lg="8" :md="8">
                    <FormItem label="证件号码" prop="owner_idno">
                        <Input placeholder="" v-model="formDealValidate.owner_idno"></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="16" :md="16">
                    <FormItem label="联系地址" prop="owner_address">
                        <Input placeholder="" v-model="formDealValidate.owner_address"></Input>
                    </FormItem>
                    </Col>
                </Row>
                </Col>
                <Col :lg="24" :md="24">
                <Row>
                    <Col :lg="8" :md="8">
                    <Row>
                        <Col :lg="16" :md="16">
                        <FormItem label="乙方编号" prop="customer_sn">
                            <Input placeholder="" v-model="formDealValidate.customer_sn" readonly></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="7" :md="7" offset="1">
                        <Button type="ghost" @click="chooseCustomer">选择客源</Button>
                        </Col>
                    </Row>
                    </Col>
                    <Col :lg="8" :md="8">
                    <FormItem label="客户电话" prop="customer_phone">
                        <Input placeholder="" v-model="formDealValidate.customer_phone"></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="8" :md="8">
                    <Row>
                        <Col :lg="16" :md="16">
                        <FormItem label="乙方姓名" prop="customer_name">
                            <Input placeholder="" v-model="formDealValidate.customer_name"></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="7" :md="7" class="ownerSex">
                        <FormItem prop="customer_sex">
                            <Select v-model="formDealValidate.customer_sex" :transfer="true">
                                <Option value="先生">先生</Option>
                                <Option value="女士">女士</Option>
                                <Option value="公司">公司</Option>
                            </Select>
                        </FormItem>
                        </Col>
                    </Row>
                    </Col>
                </Row>
                </Col>
                <Col :lg="24" :md="24">
                <Row>
                    <Col :lg="8" :md="8">
                    <FormItem label="证件号码" prop="customer_idno">
                        <Input placeholder="" v-model="formDealValidate.customer_idno"></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="16" :md="16">
                    <FormItem label="联系地址" prop="customer_address">
                        <Input placeholder="" v-model="formDealValidate.customer_address"></Input>
                    </FormItem>
                    </Col>
                </Row>
                </Col>
                <!--<div style="width: 100%;height: 1px;background-color: #e5e5e5;margin-bottom: 20px;overflow: hidden"></div>-->
                <!--<Col :lg="24" :md="24">-->
                <!--<Row>-->
                    <!--<Col :lg="8" :md="8">-->
                    <!--<FormItem label="部门选择" prop="d_id">-->
                        <!--<Cascader :data="departlist" :value.sync="formDealValidate.linkage_departpath" change-on-select-->
                                  <!--@on-change="ldhandleChange" :transfer="true"></Cascader>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                    <!--<Col :lg="8" :md="8">-->
                    <!--<FormItem label="划成人" prop="order_linkage_user">-->
                        <!--<Select placeholder="" v-model="formDealValidate.order_linkage_user" :label-in-value="true"-->
                                <!--@on-change="selectLinkageuser" placeholder="联动划成人" :transfer="true">-->
                            <!--<Option v-for="(item,index) in lduser" :value="item.u_id" :key="item.u_id">{{item.u_name}}-->
                            <!--</Option>-->
                        <!--</Select>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                    <!--<Col :lg="8" :md="8">-->
                    <!--<FormItem label="比例" prop="order_linkage_per">-->
                        <!--<Input v-model="formDealValidate.order_linkage_per"  placeholder="联动划成比例">-->
                        <!--<span slot="append">%</span>-->
                        <!--</Input>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                <!--</Row>-->
                <!--</Col>-->
                <!--<Col :lg="24" :md="24">-->
                <!--<Row>-->
                    <!--<Col :lg="8" :md="8">-->
                    <!--<FormItem label="部门选择" prop="d_id">-->
                        <!--<Cascader :data="departlist" :value.sync="formDealValidate.consult_departpath" change-on-select-->
                                  <!--@on-change="xshandleChange" :transfer="true"></Cascader>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                    <!--<Col :lg="8" :md="8">-->
                    <!--<FormItem label="划成人" prop="order_consult_user">-->
                        <!--<Select placeholder="" v-model="formDealValidate.order_consult_user" :label-in-value="true"-->
                                <!--@on-change="selectConsultuser" :transfer="true" placeholder="协商划成人">-->
                            <!--<Option v-for="(item,index) in xsuser" :value="item.u_id" :key="item.u_id">{{item.u_name}}-->
                            <!--</Option>-->
                        <!--</Select>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                    <!--<Col :lg="8" :md="8">-->
                    <!--<FormItem label="比例" prop="order_consult_per">-->
                        <!--<Input v-model="formDealValidate.order_consult_per"  placeholder="协商划成比例">-->
                        <!--<span slot="append">%</span>-->
                        <!--</Input>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                <!--</Row>-->
                <!--</Col>-->

                <!--<Col :lg="24" :md="24">-->
                <!--<Row>-->
                    <!--<Col :lg="8" :md="8">-->
                    <!--<FormItem label="过户流程" prop="order_transfer_id">-->
                        <!--<Select placeholder="" v-model="formDealValidate.order_transfer_id" @on-change="selectProcess" :label-in-value="true" :transfer="true" placeholder="过户流程">-->
                            <!--<Option v-for="(item,index) in process" :value="item.transfer_id" :key="item.transfer_name">{{item.transfer_name}}-->
                            <!--</Option>-->
                        <!--</Select>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                    <!--<Col :lg="8" :md="8">-->
                    <!--<FormItem label="合同编号" prop="contract_sn">-->
                        <!--<Select placeholder="" v-model="formDealValidate.contract_sn" @on-change="selectContract" :label-in-value="true" :transfer="true" placeholder="合同编号">-->
                            <!--<Option v-for="(item,index) in formDealValidate.contractlist" :value="item.bianhao" :key="item.bianhao">{{item.mingcheng}}【{{item.bianhao}}】-->
                            <!--</Option>-->
                        <!--</Select>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                <!--</Row>-->
                <!--</Col>-->

            </Row>
        </Form>
        <Modal v-model="customerModal" title="选择客源" :transfer="false" width="960" :mask-closable="false">
            <div slot="header">
                <a class="ivu-modal-close" @click="dealModalCancel" style="display: block!important"><i
                        class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                <div class="ivu-modal-header-inner">选择客源</div>
            </div>
            <div slot="footer">
                <Button type="text" size="large" @click="customerModalCancel">取消</Button>
                <Button type="primary" size="large" @click="customerModalOk">确定</Button>
            </div>
            <Row :gutter="5">
                <!--<Col :lg="3" :md="3">-->
                <!--<Select placeholder="状态选择" :transfer="true" v-model="customerStatus" @on-change="selectChange">-->
                    <!--<Option value="">有效全部</Option>-->
                    <!--<Option value="私客">有效私客</Option>-->
                    <!--<Option value="公客">有效公客</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <Col :lg="6" :md="6">
                <Input placeholder="客户电话、姓名搜索" v-model="keyword"></Input>
                </Col>
                <Col :lg="3" :md="3">
                <Button type="primary" @click="doSearch">查询</Button>
                </Col>
            </Row>
            <Row>
                <Col :lg="24" :md="24" style="margin-top: 10px">
                <Table :columns="customerColumns" :data="customerList" border script
                       @on-selection-change="selectionok"></Table>
                <div style="margin: 10px;overflow: hidden">
                    <div style="float: right;">
                    </div>
                </div>
                </Col>
            </Row>
        </Modal>
        </Form>
    </Modal>
</template>
<script>
    export default {
        name: 'editDeal',
        props: ['rDeal', 'buyDealtitle', 'departlist','process', 'formDealValidate'],
        data() {
            const validateNumber = (rule, value, callback) => {
                var errors = [];
                if(!value){
                    callback('请输入数字');
                }
                if (!/^\d+(?=\.{0,1}\d+$|$)/.test(value)) {
                    callback('请输入正确的数字');
                }
                callback(errors);
            };
            return {
                customerModal: false,
                cjuser: [],
                qzuser: [],
                lduser: [],
                xsuser: [],
                houseData: {
                    owner_sn: '',
                    owner_phone: '',
                    owner_name: '',
                    owner_sex: '',
                    house_name: '',
                    house_type: '',
                    house_district: '',
                    house_building: '',
                    house_unit: '',
                    house_door: '',
                    house_area: '',
                    order_type: '',
                },
                ruleDealValidate: {
                    order_deal_date: [
                        {required: true, message: '请选择成交时间', trigger: 'change'}
                    ],
                    order_deal_user: [
                        {required: true, message: '请先选择成交部门，然后选择成交人', trigger: 'change'}
                    ],
                    order_linkage_per: [
                        {validator: validateNumber, trigger: 'change'},
                        {
                            validator(rule, value, callback, source, options) {
                                var errors = [];
                                if (value >= 100) {
                                    callback('填写分成百分比小于100');
                                }
                                callback(errors);

                            }
                        }
                    ],
                    order_consult_per: [
                        {validator: validateNumber, trigger: 'change'},
                        {
                            validator(rule, value, callback, source, options) {
                                var errors = [];
                                if (value >= 100) {
                                    callback('填写分成百分比小于100');
                                }
                                callback(errors);

                            }
                        }
                    ],
//                    agreement_sn: [
//                        {required: true, message: '请填写协议编号', trigger: 'blur'}
//                    ],
                    order_price: [
                        {required: true, validator: validateNumber, trigger: 'blur'}
                    ],
                    order_owner_commission: [
                        {required: true, validator: validateNumber, trigger: 'blur'}
                    ],
                    order_customer_commission: [
                        {required: true, validator: validateNumber, trigger: 'blur'}
                    ],
                    order_start_time: [
                        {required: true, message: '请输入租房开始时间', trigger: 'change'},
                    ],
                    order_end_time: [
                        {required: true, message: '请输入租房结束时间', trigger: 'change'},
                    ],
                    order_property_user: [
                        {required: true, message: '请先选择权证部门，然后选择产权人', trigger: 'change'}
                    ],
                    order_deal_type: [
                        {required: true, message: '请选择交易类型', trigger: 'change'}
                    ],

                    owner_name: [
                        {required: true, message: '请填写甲方姓名', trigger: 'blur'}
                    ],
                    customer_sn: [
                        {required: true, message: '请选择乙方编号', trigger: 'blur'}
                    ],
                    customer_phone: [
                        {required: true, message: '请填写乙方手机号', trigger: 'blur'},
                        {
                            validator(rule, value, callback, source, options) {
                                var errors = [];
                                if (!/^(1[345789]\d{9})$/.test(value)) {
                                    callback('请填写正确的手机号');
                                }
                                callback(errors);

                            }
                        }
                    ],
                    customer_name: [
                        {required: true, message: '请填写乙方姓名', trigger: 'blur'}
                    ],
//                    order_transfer_id:[{required: true, message: '请选择过户流程方案', trigger: 'change'}],
//                    contract_sn:[{required: true, message: '请选择成交合同', trigger: 'change'}],
                },
                //选择客源
                pageTotal: 0,
                pageCurrent: 1,
                customerStatus: '',
                keyword: '',
                selection: '',
                customerList: [],
                customerColumns: [
                    {
                        type: 'selection',
                        width: 60,
                        align: 'center'
                    },
                    {
                        title: '状态',
                        key: 'customer_private',
                        align: 'center'
                    },
                    {
                        title: '客源编号',
                        key: 'xuqiubianhao',
                        align: 'center',
                        width: 240
                    },
                    {
                        title: '姓名',
                        key: 'customer_name',
                        align: 'center'
                    },
                    {
                        title: '手机号',
                        key: 'tel_phone',
                        align: 'center'
                    },
                    {
                        title: '需求类型',
                        key: 'customer_type',
                        align: 'center'

                    }
                ],
            };
        },
        methods: {
            //成交人级联回调
            cjhandleChange(value, selectedData) {
                this.formDealValidate.order_deal_did = value[value.length - 1];
                this.formDealValidate.order_deal_dname = selectedData[selectedData.length - 1].label;
                this.$http.post(api_param.apiurl + 'ordersell/getuser',
                    {
                        'd_id': this.formDealValidate.order_deal_did,
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.cjuser = response.data.data;
                        //console.log(this.departuser);
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
            //权证人级联回调
            qzhandleChange(value, selectedData) {
                this.formDealValidate.order_property_did = value[value.length - 1];
                this.$http.post(api_param.apiurl + 'ordersell/getuser',
                    {
                        'd_id': this.formDealValidate.order_property_did,
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.qzuser = response.data.data;
                        //console.log(this.departuser);
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
            //联动划成级联回调
            ldhandleChange(value, selectedData) {
                this.formDealValidate.order_linkage_did = value[value.length - 1];
                this.formDealValidate.order_linkage_dname = selectedData[selectedData.length - 1].label;
                this.$http.post(api_param.apiurl + 'ordersell/getuser',
                    {
                        'd_id': this.formDealValidate.order_linkage_did,
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.lduser = response.data.data;
                        //console.log(this.departuser);
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
            //协商划成级联回调
            xshandleChange(value, selectedData) {
                this.formDealValidate.order_consult_did = value[value.length - 1];
                this.formDealValidate.order_consult_dname = selectedData[selectedData.length - 1].label;
                this.$http.post(api_param.apiurl + 'ordersell/getuser',
                    {
                        'd_id': this.formDealValidate.order_consult_did,
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.xsuser = response.data.data;
                        //console.log(this.departuser);
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
            //成交
            selectDealuser(selectedData) { //成交人选择
                //console.log(selectedData);
                this.formDealValidate.order_deal_user = selectedData.value;
                this.formDealValidate.order_deal_username = selectedData.label;
            },
            selectPropertyuser(selectedData) { //权证人选择
                this.formDealValidate.order_property_user = selectedData.value;
                this.formDealValidate.order_property_username = selectedData.label;
            },
//            selectLinkageuser(selectedData){  //联动划成人
//                this.formDealValidate.order_linkage_user = selectedData.value;
//                this.formDealValidate.order_linkage_username = selectedData.label;
//            },
//            selectConsultuser(selectedData){  //协商划成人
//                this.formDealValidate.order_consult_user = selectedData.value;
//                this.formDealValidate.order_consult_username = selectedData.label;
//            },
//            selectProcess(selectedData){  //过户流程
//                this.formDealValidate.order_transfer_id = selectedData.value;
//                this.formDealValidate.order_transfer_process = selectedData.label;
//            },
//            selectContract(selectedData){  //成交合同选择
//                this.formDealValidate.contract_sn = selectedData.value;
//                var labelarr = selectedData.label.split('【');console.log(labelarr);
//                this.formDealValidate.contract_name = labelarr[0];
//            },
            dealModalCancel() {
                for (let i in this.houseData) {
                    this.$set(this.houseData, i, this.formDealValidate[i])
                }
                ;
                this.$refs['formDealValidate'].resetFields();
                for (let i in this.houseData) {
                    this.$set(this.formDealValidate, i, this.houseData[i])
                }
                this.formDealValidate.order_loan = false;
                this.formDealValidate.deal_departpath = [];
                this.formDealValidate.property_departpath = [];
//                this.formDealValidate.linkage_departpath = [];
//                this.formDealValidate.consult_departpath = [];
                //this.rDeal = false;
                this.$emit('resetModal');
                //清空form规则检查
                //this.$refs['formDealValidate'].resetFields();
            },
            dealModalOk() {
                this.$refs['formDealValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'house/adddeal',
                            {
                                'order_type': this.formDealValidate.order_type,
                                'order_deal_date': this.formDealValidate.order_deal_date,
                                'order_deal_did': this.formDealValidate.order_deal_did,
                                'depart_name': this.formDealValidate.order_deal_dname,
                                'order_deal_user': this.formDealValidate.order_deal_user,
                                'order_deal_username': this.formDealValidate.order_deal_username,
                                'agreement_sn': this.formDealValidate.agreement_sn,
                                'order_price': this.formDealValidate.order_price,
                                'order_loan': this.formDealValidate.order_loan == true ? 1 : 0,
                                'order_owner_commission': this.formDealValidate.order_owner_commission,
                                'order_customer_commission': this.formDealValidate.order_customer_commission,
                                'order_property_did': this.formDealValidate.order_property_did,
                                'order_property_user': this.formDealValidate.order_property_user,
                                'order_property_username': this.formDealValidate.order_property_username,
//                                'order_linkage_did': this.formDealValidate.order_linkage_did,
//                                'linkage_depart_name': this.formDealValidate.order_linkage_dname,
//                                'order_linkage_user': this.formDealValidate.order_linkage_user,
//                                'order_linkage_username': this.formDealValidate.order_linkage_username,
//                                'order_linkage_per': this.formDealValidate.order_linkage_per,
//                                'order_consult_did': this.formDealValidate.order_consult_did,
//                                'consult_depart_name': this.formDealValidate.order_consult_dname,
//                                'order_consult_user': this.formDealValidate.order_consult_user,
//                                'order_consult_username': this.formDealValidate.order_consult_username,
//                                'order_consult_per': this.formDealValidate.order_consult_per,
                                'order_deal_type': this.formDealValidate.order_deal_type,
                                'order_addition_terms': this.formDealValidate.order_addition_terms,
                                'order_start_time': this.formDealValidate.order_start_time,
                                'order_end_time': this.formDealValidate.order_end_time,
                                'house_name': this.formDealValidate.house_name,
                                'house_land_certificate': this.formDealValidate.house_land_certificate,
                                'house_property_certificate': this.formDealValidate.house_property_certificate,
                                'house_type': this.formDealValidate.house_type,
                                'dts_id': this.formDealValidate.dts_id,
                                'dts_name': this.formDealValidate.dts_name,
                                'village_id': this.formDealValidate.village_id,
                                'village_name': this.formDealValidate.village_name,
                                'house_building': this.formDealValidate.house_building,
                                'house_unit': this.formDealValidate.house_unit,
                                'house_door': this.formDealValidate.house_door,
                                'house_area': this.formDealValidate.house_area,
                                'owner_sn': this.formDealValidate.owner_sn,
                                'owner_phone': this.formDealValidate.owner_phone,
                                'owner_name': this.formDealValidate.owner_name,
                                'owner_sex': this.formDealValidate.owner_sex,
                                'owner_idno': this.formDealValidate.owner_idno,
                                'owner_address': this.formDealValidate.owner_address,
                                'customer_sn': this.formDealValidate.customer_sn,
                                'customer_phone': this.formDealValidate.customer_phone,
                                'customer_name': this.formDealValidate.customer_name,
                                'customer_sex': this.formDealValidate.customer_sex,
                                'customer_idno': this.formDealValidate.customer_idno,
                                'customer_address': this.formDealValidate.customer_address,
//                                'order_transfer_id': this.formDealValidate.order_transfer_id,
//                                'order_transfer_process': this.formDealValidate.order_transfer_process,
//                                'contract_sn':this.formDealValidate.contract_sn,
//                                'contract_name':this.formDealValidate.contract_name,
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                for (let i in this.houseData) {
                                    this.$set(this.houseData, i, this.formDealValidate[i])
                                }
                                ;
                                this.$refs['formDealValidate'].resetFields();
                                for (let i in this.houseData) {
                                    this.$set(this.formDealValidate, i, this.houseData[i])
                                }
                                this.formDealValidate.order_loan = false;
                                this.formDealValidate.deal_departpath = [];
                                this.formDealValidate.property_departpath = [];
//                                this.formDealValidate.linkage_departpath = [];
//                                this.formDealValidate.consult_departpath = [];

                                this.rDeal = false;
                                this.$emit('resetModal');
                                this.$emit('getDetail');
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
                    }
                });
            },
            //选择客源
            chooseCustomer() {
                this.$http.post(api_param.apiurl + 'ordersell/customerlist',
                    {
                        'page': this.pageCurrent,
                        'status': this.customerStatus,
                        'type': this.formDealValidate.order_type,
                        'kw': this.keyword,
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.customerModal = true;
                        this.customerList = response.data.data.listdata;
                        this.pageTotal = parseInt(response.data.data.totalnum);
                    } else if (response.data.code == 401) {
                        this.$Message.error('登录超时');
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({name: 'login'});
                    } else {
                        this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    this.$Message.error('网络异常');
                });
            },
            selectChange(selectedData) {
                this.customerStatus = selectedData;
            },
            doSearch() {
                this.currentpage = 1;
                this.chooseCustomer();
            },
            customerModalCancel() {
                this.selection = '';
                this.customerModal = false;
            },
            selectionok(selection) {
                this.selection = selection;
            },
            customerModalOk() {
                if (this.selection.length == 1) {
                    this.formDealValidate.customer_sn = this.selection[0].xuqiubianhao;
                    this.formDealValidate.customer_phone = this.selection[0].tel_phone;
                    this.formDealValidate.customer_name = this.selection[0].customer_name;
                    this.formDealValidate.customer_sex = this.selection[0].customer_sex;
                    this.customerModal = false;
                } else if (this.selection.length > 1) {
                    this.$Message.warning('最多选择1个');
                } else {
                    this.$Message.warning('最少选择1个');
                }
            },
            changePage(page) {//分页
                this.pageCurrent = page;
                this.chooseCustomer();
            },
        }

    };
</script>
