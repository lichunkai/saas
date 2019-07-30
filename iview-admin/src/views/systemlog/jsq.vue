<style lang="less">
    .ivu-modal-close {
        display: none!important;
    }
</style>

<template>
    <div>
        <!--<Button type="success" @click="jisuanqi = true">计算器</Button>-->
        <Modal v-model="jisuanqi" width="960" closable="false" title="房贷计算器" :mask-closable="false">
            <div slot="footer">
                <Button type="primary" @click="jisuanqi = false">关闭</Button>
            </div>
            <Tabs type="card" :animated="false">
                <TabPane label="贷款计算器">
                    <Row :gutter="20">
                        <Col :lg="10" :md="10">
                        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="100">
                            <FormItem label="贷款类别：" prop="loan_type">
                                <Select v-model="formValidate.loan_type">
                                    <Option v-for="item in loanType" :value="item.value" :key="item.value">{{ item.label }}
                                    </Option>
                                </Select>
                            </FormItem>
                            <FormItem label="贷款年限：" prop="mortgage_year">
                                <Select v-model="formValidate.mortgage_year" :transfer="false" @on-change="selsectChangYear">
                                    <Option v-for="item in anjie2" :value="item.value" :key="item.value">{{ item.label }}
                                    </Option>
                                </Select>
                            </FormItem>
                            <!--<FormItem label="计算方式：" v-if="formValidate.loan_type!=='组合贷款'" prop="Calculation_type">-->
                            <!--<Select v-model="formValidate.Calculation_type">-->
                            <!--<Option v-for="item in CalculationType" :value="item.value" :key="item.value">{{-->
                            <!--item.label-->
                            <!--}}-->
                            <!--</Option>-->
                            <!--</Select>-->
                            <!--</FormItem>-->
                            <FormItem label="房屋总价：" prop="loan_total" v-if="formValidate.loan_type!=='组合贷款'">
                                <Input v-model="formValidate.loan_total" placeholder="请输入贷款总额" :value="loan_total">
                                <span slot="append">万</span>
                                </Input>
                            </FormItem>
                            <FormItem label="贷款成数：" v-if="formValidate.loan_type!=='组合贷款'" prop="mortgage_number">
                                <Select v-model="formValidate.mortgage_number">
                                    <Option v-for="item in anjie1" :value="item.value" :key="item.value">{{ item.label }}
                                    </Option>
                                </Select>
                            </FormItem>
                            <FormItem label="商贷利率：" v-if="formValidate.loan_type=='组合贷款' || formValidate.loan_type=='商业贷款'" prop="business_interestRate">
                                <Row>
                                    <Col :lg="16" :md="16">
                                    <Select v-model="formValidate.business_interestRate" @on-change="selectChange_s">
                                        <Option v-for="item in businessInterestRate" :value="item.value" :key="item.value">{{ item.label }}
                                        </Option>
                                    </Select>
                                    </Col>
                                    <Col :lg="8" :md="8">
                                    <Input v-model="formValidate.business_interestRateValue">
                                    <span slot="append">%
                                    </span>
                                    </Input>
                                    </Col>
                                </Row>
                            </FormItem>
                            <FormItem label="公积金利率：" v-if="formValidate.loan_type=='组合贷款' || formValidate.loan_type=='公积金贷款'" prop="accumulationFund_lilv">
                                <Row>
                                    <Col :lg="16" :md="16">
                                    <Select v-model="formValidate.accumulationFund_lilv" @on-change="selectChange_g">
                                        <Option v-for="item in accumulationFundLilv" :value="item.value" :key="item.value">{{ item.label }}
                                        </Option>
                                    </Select>
                                    </Col>
                                    <Col :lg="8" :md="8">
                                    <Input v-model="formValidate.accumulationFund_lilvValue">
                                    <span slot="append">%</span>
                                    </Input>
                                    </Col>
                                </Row>
                            </FormItem>
                            <!--<FormItem label="房屋单价："-->
                            <!--v-if="formValidate.Calculation_type=='根据面积、单价计算' && loan_type!=='组合贷款'"-->
                            <!--prop="house_price">-->
                            <!--<Input v-model="formValidate.house_price" placeholder="请输入房屋单价"><span slot="append">元/平方米</span></Input>-->
                            <!--</FormItem>-->
                            <!--<FormItem label="房屋面积："-->
                            <!--v-if="formValidate.Calculation_type=='根据面积、单价计算' && loan_type!=='组合贷款'"-->
                            <!--prop="house_m">-->
                            <!--<Input v-model="formValidate.house_m" placeholder="请输入房屋面积"><span-->
                            <!--slot="append">平方米</span></Input>-->

                            <!--</FormItem>-->

                            <FormItem label="商业贷款额：" v-if="formValidate.loan_type=='组合贷款'" prop="business_total">
                                <Input v-model="formValidate.business_total" placeholder="请输入商业贷款额">
                                <span slot="append">万</span>
                                </Input>
                            </FormItem>

                            <FormItem label="公积金贷款额：" v-if="formValidate.loan_type=='组合贷款'" prop="accumulationFund_loan">
                                <Input v-model="formValidate.accumulationFund_loan" placeholder="请输入公积金贷款额">
                                <span slot="append">万</span>
                                </Input>
                            </FormItem>
                            <!--<FormItem label="贷款利率：" v-if="formValidate.loan_type!=='组合贷款'" prop="loan_interestRate">-->
                            <!--<Row>-->
                            <!--<Col :lg="18" :md="18">-->
                            <!--<Select v-model="formValidate.loan_interestRate" @on-change="selectChange_d">-->
                            <!--<Option v-for="item in loanInterestRate" :value="item.value" :key="item.value" >-->
                            <!--{{ item.label }}</Option>-->
                            <!--</Select>-->
                            <!--</Col>-->
                            <!--<Col :lg="6" :md="6">-->
                            <!--<Input v-model="formValidate.loan_interestRateValue" style="width: 70px"><span-->
                            <!--slot="append">%</span>-->
                            <!--</Input>-->
                            <!--</Col>-->
                            <!--</Row>-->
                            <!--</FormItem>-->
                            <FormItem label="还款方式：" prop="repayment_type">
                                <RadioGroup v-model="repayment_type">
                                    <Radio label="等额本息"></Radio>
                                    <Radio label="等额本金"></Radio>
                                </RadioGroup>
                            </FormItem>
                            <FormItem>
                                <Button type="primary" @click="handleSubmit">开始计算</Button>
                                <Button type="ghost" @click="handleReset" style="margin-left: 8px">清空填写</Button>
                            </FormItem>
                            <br/>
                            <br/>
                            <br/>
                            <br/>
                        </Form>
                        </Col>
                        <!--计算结果-->
                        <Col :lg="14" :md="14">
                        <Row>
                            <Col :lg="12" :md="12">
                            <span>贷款总额： {{f_daikuan}}</span>
                            </Col>
                            <Col :lg="12" :md="12">
                            <span>利息总额： {{f_lixi}}</span>
                            </Col>
                            <Col :lg="12" :md="12" style="margin-top: 10px;padding:0 10px" v-if="repayment_type!=='等额本金' && formValidate.loan_type!=='组合贷款'">
                            <span>首付： {{f_shoufu}}</span>
                            </Col>
                            <Col :lg="12" :md="12" style="margin-top: 10px" v-if="repayment_type!=='等额本金'">
                            <span>月供： {{f_yuegong}}</span>
                            </Col>
                            <Col :lg="12" :md="12" style="margin-top: 10px;padding:0 10px" v-if="repayment_type=='等额本金'">
                            <span>首月月供： {{f_fristyuegong}}</span>
                            </Col>
                            <Col :lg="12" :md="12" style="margin-top: 10px" v-if="repayment_type=='等额本金'">
                            <span>每月递减： {{f_dijian}}</span>
                            </Col>
                            <Col :lg="12" :md="12" style="margin-top: 10px">
                            <span>还款总额： {{f_huankuan}}</span>
                            </Col>
                            <Col :lg="24" :md="24" style="margin-top: 10px">
                            <Table :columns="columns1" :data="data2" border height="350"></Table>
                            </Col>
                        </Row>
                        </Col>
                    </Row>
                </TabPane>
                <TabPane label="税款计算器">
                    <Row :gutter="40">
                        <Col :lg="12" :md="12">
                        <Form ref="formValidate2" :model="formValidate2" :rules="ruleValidate2" :label-width="120">
                            <FormItem label="房屋：" prop="house_type">
                                <RadioGroup v-model="house_type" @on-change="cutOrdHouse">
                                    <Radio label="新房"></Radio>
                                    <Radio label="二手房"></Radio>
                                </RadioGroup>
                            </FormItem>
                            <FormItem label="房屋面积：" prop="house_m2">
                                <Input placeholder="请输入房屋面积" v-model="formValidate2.house_m2">
                                <span slot="append">平方米</span>
                                </Input>

                            </FormItem>
                            <FormItem label="房屋单价：" prop="house_price2" v-if="house_type=='新房'">
                                <Input placeholder="请输入房屋单价" v-model="formValidate2.house_price2">
                                <span slot="append">元/平方米</span>
                                </Input>

                            </FormItem>
                            <FormItem label="房屋总价：" v-if="house_type=='二手房'" prop="house_total">
                                <Input placeholder="请输入房屋总价" v-model="formValidate2.house_total">
                                <span slot="append">万元</span>
                                </Input>

                            </FormItem>
                            <FormItem label="计征方式：" v-if="house_type=='二手房'" prop="Levy_type">
                                <Select v-model="formValidate2.Levy_type">
                                    <Option v-for="item in jizheng" :value="item.value" :key="item.value">{{ item.label }}
                                    </Option>
                                </Select>
                            </FormItem>
                            <FormItem label="交易差额：" v-if="house_type=='二手房' && formValidate2.Levy_type=='差价'" prop="house_Oprice">
                                <Input placeholder="请输入交易差额" v-model="formValidate2.house_Oprice">
                                <span slot="append">万元</span>
                                </Input>

                            </FormItem>

                            <FormItem label="房产性质：" v-if="house_type=='二手房'" prop="houseProperty_type">
                                <Select v-model="formValidate2.houseProperty_type">
                                    <Option v-for="item in xingzhi" :value="item.value" :key="item.value">{{ item.label }}
                                    </Option>
                                </Select>
                            </FormItem>
                            <FormItem label="房产购置满5年：" v-if="house_type=='二手房'" prop="houseProperty_year">
                                <RadioGroup v-model="houseProperty_year" @on-change="house_year">
                                    <Radio label="满5年"></Radio>
                                    <Radio label="满2年"></Radio>
                                    <Radio label="不满2年"></Radio>
                                </RadioGroup>
                            </FormItem>
                            <FormItem label="买房家庭首次购房：" prop="fristBuyHouse">
                                <RadioGroup v-model="fristBuyHouse">
                                    <Radio label="是"></Radio>
                                    <Radio label="否"></Radio>
                                </RadioGroup>
                            </FormItem>
                            <FormItem label="卖方家庭唯一住房：" v-if="house_type=='二手房'" prop="onlyHouse">
                                <RadioGroup v-model="onlyHouse">
                                    <Radio label="是"></Radio>
                                    <Radio label="否"></Radio>
                                </RadioGroup>
                            </FormItem>
                            <FormItem label=''>
                                <Button type="primary" @click="handleSubmit2">开始计算</Button>
                                <Button type="ghost" @click="handleReset2" style="margin-left: 8px">清空填写</Button>
                            </FormItem>
                        </Form>
                        </Col>
                        <!--新房时显示的计算结果-->
                        <!--<transition enter-active-class="animated zoomInLeft" leave-active-class="animated zoomOutRight">-->
                        <Col :lg="12" :md="12" v-show="isNewShow">
                        <Row>
                            <Col :lg="12" :md="12" style="margin-bottom: 10px"> 房款总款：{{fangkuan}}
                            </Col>
                            <Col :lg="12" :md="12" style="margin-bottom: 10px"> 合计：{{heji}}
                            </Col>
                            <Col :lg="12" :md="12" style="margin-bottom: 10px"> 契税：{{qishui}}
                            </Col>
                            <Col :lg="12" :md="12" style="margin-bottom: 10px"> 工本费：{{gongben}}
                            </Col>
                            <Col :lg="12" :md="12"> 权属登记费：{{dengji}}
                            </Col>
                            <Col :lg="12" :md="12"> 维修基金：{{weixiu}}
                            </Col>
                        </Row>
                        </Col>
                        <!--</transition>-->
                        <!--二手房时显示的计算结果-->
                        <Col :lg="12" :md="12" v-show="isOrdShow">
                        <Row>
                            <Col :lg="12" :md="12" style="margin-bottom: 10px"> 契税：{{qishui2}}
                            </Col>
                            <Col :lg="12" :md="12" style="margin-bottom: 10px"> 个人所得税：{{geren2}}
                            </Col>
                            <Col :lg="12" :md="12"> 增值税：{{zengzhi2}}
                            </Col>
                            <Col :lg="12" :md="12" style="margin-bottom: 10px"> 总计：
                            <span>{{heji2}}</span>
                            </Col>
                        </Row>
                        </Col>
                    </Row>
                </TabPane>
            </Tabs>
        </Modal>
    </div>
</template>

<script>
    //利率
    let slv = [{
            value: '4.9',
            label: '基准利率'
        },
        {
            value: '3.43',
            label: '7折'
        },
        {
            value: '4.165',
            label: '85折'
        },
        {
            value: '4.312',
            label: '88折'
        },
        {
            value: '4.41',
            label: '9折'
        },
        {
            value: '4.655',
            label: '95折'
        },
        {
            value: '5.145',
            label: '1.05倍'
        },
        {
            value: '5.39',
            label: '1.1倍'
        },
        {
            value: '5.88',
            label: '1.2倍'
        },
        {
            value: '6.37',
            label: '1.3倍'
        }
    ];
    let glv = [{
            value: '3.25',
            label: '基准利率'
        },
        {
            value: '2.275',
            label: '7折'
        },
        {
            value: '2.762',
            label: '85折'
        },
        {
            value: '2.86',
            label: '88折'
        },
        {
            value: '2.925',
            label: '9折'
        },
        {
            value: '3.087',
            label: '95折'
        },
        {
            value: '3.413',
            label: '1.05倍'
        },
        {
            value: '3.575',
            label: '1.1倍'
        },
        {
            value: '3.9',
            label: '1.2倍'
        },
        {
            value: '4.225',
            label: '1.3倍'
        }
    ];
    //贷款期限
    let loanYear = '';
    export default {
        name: 'jisuanqi',
        data() {
            return {
                //贷款计算
                f_fristyuegong: '',
                f_dijian: '',
                f_daikuan: '',
                f_lixi: '',
                f_shoufu: '',
                f_yuegong: '',
                f_huankuan: '',
                //二手房税费计算
                gbyinhua2: '',
                yinhua2: '',
                qishui2: '',
                zengzhi2: '',
                geren2: '',
                dijia2: '',
                heji2: '',
                //新房税费计算结果
                fangkuan: '',
                gongben: '',
                dengji: '',
                weixiu: '',
                heji: '',
                qishui: '',
                isNewShow: false,
                isOrdShow: false,
                columns1: [{
                        title: '#',
                        width: 60,
                        key: 'number',
                        align: 'center'
                    },
                    {
                        title: '月供(元）',
                        key: 'monthFor',
                        align: 'center'
                    },
                    {
                        title: '偿还利息(元)',
                        key: 'Interest',
                        align: 'center'
                    },
                    {
                        title: '偿还本金(元)',
                        key: 'reimbursementPrincipal',
                        align: 'center'
                    },
                    {
                        title: '剩余本金(元)',
                        key: 'residualPrincipal',
                        align: 'center'
                    }
                ],
                data2: [
                    // {
                    //     number: '1 ',
                    //     monthFor: '',
                    //     Interest: '',
                    //     reimbursementPrincipal: '',
                    //     residualPrincipal: '',
                    // }
                ],
                jizheng: [

                    {
                        value: '总价',
                        label: '总价'
                    },
                    {
                        value: '差价',
                        label: '差价'
                    },
                ],
                fiveYears: "满5年",
                xingzhi: [{
                        value: '普通住宅',
                        label: '普通住宅'
                    },
                    {
                        value: '非普通住宅',
                        label: '非普通住宅'
                    },
                    {
                        value: '经济适用房',
                        label: '经济适用房'
                    }
                ],
                modelXZ: '普通住宅',
                modelJZ: '总价',
                loanType: [{
                        value: '商业贷款',
                        label: '商业贷款'
                    },
                    {
                        value: '公积金贷款',
                        label: '公积金贷款'
                    },
                    {
                        value: '组合贷款',
                        label: '组合贷款'
                    }
                ],
                loan_type: '商业贷款',
                // CalculationType: [
                //     {
                //         value: '根据面积、单价计算',
                //         label: '根据面积、单价计算'
                //     },
                //     {
                //         value: '根据贷款总额计算',
                //         label: '根据贷款总额计算'
                //     }
                // ],
                // Calculation_type: '根据面积、单价计算',
                anjie1: [{
                        value: '0.8',
                        label: '8成'
                    },
                    {
                        value: '0.75',
                        label: '7.5成'
                    },
                    {
                        value: '0.7',
                        label: '7成'
                    },
                    {
                        value: '0.65',
                        label: '6.5成'
                    },
                    {
                        value: '0.6',
                        label: '6成'
                    },
                    {
                        value: '0.55',
                        label: '5.5成'
                    },
                    {
                        value: '0.5',
                        label: '5成'
                    },
                    {
                        value: '0.45',
                        label: '4.5成'
                    },
                    {
                        value: '0.4',
                        label: '4成'
                    },
                    {
                        value: '0.35',
                        label: '3.5成'
                    },
                    {
                        value: '0.3',
                        label: '3成'
                    },
                    {
                        value: '0.25',
                        label: '2.5成'
                    },
                    {
                        value: '0.2',
                        label: '2成'
                    }
                ],
                mortgage_number: '8成',
                anjie2: [{
                        value: '360',
                        label: '30年（360期）'
                    },
                    {
                        value: '348',
                        label: '29年（348期）'
                    },
                    {
                        value: '336',
                        label: '28年（336期）'
                    },
                    {
                        value: '324',
                        label: '27年（324期）'
                    },
                    {
                        value: '312',
                        label: '26年（312期）'
                    },
                    {
                        value: '300',
                        label: '25年（300期）'
                    },
                    {
                        value: '288',
                        label: '24年（288期）'
                    },
                    {
                        value: '276期',
                        label: '23年（276期）'
                    },
                    {
                        value: '264期',
                        label: '22年（264期）'
                    },
                    {
                        value: '252',
                        label: '21年（252期）'
                    },
                    {
                        value: '240',
                        label: '20年（240期）'
                    },
                    {
                        value: '228',
                        label: '19年（228期）'
                    },
                    {
                        value: '216',
                        label: '18年（216期）'
                    },
                    {
                        value: '204',
                        label: '17年（204期）'
                    },
                    {
                        value: '192',
                        label: '16年（192期）'
                    },
                    {
                        value: '180',
                        label: '15年（180期）'
                    },
                    {
                        value: '14年（168期）',
                        label: '14年（168期）'
                    },
                    {
                        value: '156',
                        label: '13年（156期）'
                    },
                    {
                        value: '144',
                        label: '12年（144期）'
                    },
                    {
                        value: '132',
                        label: '11年（132期）'
                    },
                    {
                        value: '120',
                        label: '10年（120期）'
                    },
                    {
                        value: '108',
                        label: '9年（108期）'
                    },
                    {
                        value: '96',
                        label: '8年（96期）'
                    },
                    {
                        value: '84',
                        label: '7年（84期）'
                    },
                    {
                        value: '72',
                        label: '6年（72期）'
                    },
                    {
                        value: '60',
                        label: '5年（60期）'
                    },
                    {
                        value: '48',
                        label: '4年（48期）'
                    },
                    {
                        value: '36',
                        label: '3年（36期）'
                    },
                    {
                        value: '24',
                        label: '2年（24期）'
                    },
                    {
                        value: '12',
                        label: '1年（12期）'
                    }
                ],
                mortgage_year: '25年（300期）',
                // loanInterestRate: lv,
                businessInterestRate: slv,
                accumulationFundLilv: glv,
                // loan_interestRate: '基准利率',
                accumulationFund_lilv: '基准利率',
                // businessInterestRate: '基准利率',
                accumulationFund_lilvValue: "3.25",
                business_interestRateValue: "4.9",
                fristMF: "是",

                jisuanqi: false,

                formValidate: {
                    loan_type: [],
                    Calculation_type: [],
                    loan_total: '',
                    business_total: '',
                    business_interestRate: [],
                    accumulationFund_loan: '',
                    accumulationFund_lilv: [],
                    house_price: '',
                    house_m: '',
                    mortgage_number: [],
                    mortgage_year: [],
                    // loan_interestRate: '',

                    // loan_interestRateValue:''
                },
                repayment_type: "等额本息",
                houseProperty_year: '满5年',
                fristBuyHouse: '是',
                onlyHouse: '是',
                //                税费计算器
                house_type: "新房",
                formValidate2: {

                    Levy_type: [],
                    houseProperty_type: [],
                    house_price2: '',
                    house_m2: '',
                    house_total: '',
                    house_Oprice: '',
                }

            }

        },

        methods: {
            house_year() {
                if (this.houseProperty_year === '不满2年') {
                    this.jizheng = [];
                    this.$set(this.jizheng, 0, {
                        value: '总价',
                        label: '总价'
                    });
                }
            },
            numberChange(a) {
                console.log(a);
            },
            //选择框改变后面input框值改变
            selectChange_s(value) {
                this.formValidate.business_interestRateValue = value;
            },
            selectChange_g(value) {
                this.formValidate.accumulationFund_lilvValue = value;
            },
            // selectChange_d(value){
            //     this.formValidate.loan_interestRateValue=value;
            // },
            selsectChangYear(value) {
                loanYear = value
            },
            // 房贷计算
            handleSubmit() {
                //          等额本息
                /**
                 * 每月月供额=〔贷款本金×月利率×(1＋月利率)＾还款月数〕÷〔(1＋月利率)＾还款月数-1〕
                 *每月应还利息=贷款本金×月利率×〔(1+月利率)^还款月数-(1+月利率)^(还款月序号-1)〕÷〔(1+月利率)^还款月数-1〕
                 *每月应还本金=贷款本金×月利率×(1+月利率)^(还款月序号-1)÷〔(1+月利率)^还款月数-1〕
                 *总利息=还款月数×每月月供额-贷款本金
                 **/
                if (this.repayment_type === '等额本息') {
                    //商业贷款
                    if (this.formValidate.loan_type === '商业贷款') {

                        //贷款本金
                        this.f_daikuan = this.formValidate.loan_total * 10000 * this.formValidate.mortgage_number;
                        //月供              每月月供额=〔贷款本金×月利率×(1＋月利率)＾还款月数〕÷〔(1＋月利率)＾还款月数-1〕
                        this.f_yuegong = (this.f_daikuan * (this.formValidate.business_interestRateValue / 1200) * Math
                                .pow((1 + this.formValidate.business_interestRateValue / 1200), loanYear)) /
                            (Math.pow((1 + this.formValidate.business_interestRateValue / 1200), (loanYear)) - 1);
                        //总利息
                        this.f_lixi = loanYear * this.f_yuegong - this.f_daikuan;
                        this.f_huankuan = this.f_daikuan + this.f_lixi;
                        //列表
                        this.data2 = [];
                        for (let i = 0; i < loanYear; i++) {
                            let residualPrincipal = this.f_daikuan;
                            let reimbursementPrincipal =
                                this.f_daikuan * this.formValidate.business_interestRateValue / 1200 *
                                Math.pow((1 + this.formValidate.business_interestRateValue / 1200), (i)) /
                                (Math.pow((1 + this.formValidate.business_interestRateValue / 1200), (loanYear)) - 1);
                            if (i == 0) {
                                residualPrincipal = this.f_daikuan - reimbursementPrincipal;
                            } else if (i == loanYear - 1) {
                                residualPrincipal = 0.00;
                            } else {
                                residualPrincipal = this.data2[i - 1].residualPrincipal - reimbursementPrincipal;
                            }
                            this.$set(this.data2, i, {
                                number: i + 1,
                                monthFor: (this.f_yuegong).toFixed(2),
                                Interest: //每月应还利息=贷款本金×月利率×〔(1+月利率)^还款月数-(1+月利率)^(还款月序号-1)〕÷〔(1+月利率)^还款月数-1〕
                                    ((this.f_daikuan * this.formValidate.business_interestRateValue / 1200 *
                                        (Math.pow((1 + this.formValidate.business_interestRateValue / 1200),
                                                loanYear) -
                                            Math.pow((1 + this.formValidate.business_interestRateValue /
                                                1200), (i))) /
                                        (Math.pow((1 + this.formValidate.business_interestRateValue / 1200),
                                            (loanYear)) - 1)
                                    )).toFixed(2),
                                reimbursementPrincipal: //每月应还本金=贷款本金×月利率×(1+月利率)^(还款月序号-1)÷〔(1+月利率)^还款月数-1〕
                                    (this.f_daikuan * this.formValidate.business_interestRateValue / 1200 *
                                        Math.pow((1 + this.formValidate.business_interestRateValue / 1200), (i)) /
                                        (Math.pow((1 + this.formValidate.business_interestRateValue / 1200), (
                                            loanYear)) - 1)
                                    ).toFixed(2),
                                residualPrincipal: (residualPrincipal).toFixed(2)
                                    // (this.f_daikuan-this.f_daikuan*this.formValidate.business_interestRateValue/1200*
                                    //     Math.pow((1+this.formValidate.business_interestRateValue/1200),(i))/
                                    //     (Math.pow((1+this.formValidate.business_interestRateValue/1200),(loanYear))-1)
                                    // ).toFixed(2)
                                    ,
                            });
                        }
                        //结果显示
                        this.f_huankuan = (this.f_huankuan / 10000).toFixed(2) + "万";
                        this.f_shoufu = (this.formValidate.loan_total * 10000 - this.f_daikuan) / 10000 + "万";
                        this.f_daikuan = this.f_daikuan / 10000 + "万";
                        this.f_yuegong = (this.f_yuegong).toFixed(2) + "元";
                        this.f_lixi = (this.f_lixi / 10000).toFixed(2) + "万";

                    }
                    //公积金贷款
                    else if (this.formValidate.loan_type === '公积金贷款') {
                        this.f_daikuan = this.formValidate.loan_total * 10000 * this.formValidate.mortgage_number;
                        this.f_yuegong = (this.f_daikuan * (this.formValidate.accumulationFund_lilvValue / 1200) * Math
                                .pow((1 + this.formValidate.accumulationFund_lilvValue / 1200), loanYear)) /
                            (Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200), (loanYear)) - 1);
                        this.f_lixi = loanYear * this.f_yuegong - this.f_daikuan;

                        //列表
                        this.data2 = [];
                        for (let i = 0; i < loanYear; i++) {
                            let residualPrincipal = this.f_daikuan;
                            let reimbursementPrincipal =
                                this.f_daikuan * this.formValidate.accumulationFund_lilvValue / 1200 *
                                Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200), (i)) /
                                (Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200), (loanYear)) - 1);
                            if (i == 0) {
                                residualPrincipal = this.f_daikuan - reimbursementPrincipal;
                            } else if (i == loanYear - 1) {
                                residualPrincipal = 0.00;
                            } else {
                                residualPrincipal = this.data2[i - 1].residualPrincipal - reimbursementPrincipal;
                            }
                            this.$set(this.data2, i, {
                                number: i + 1,
                                monthFor: (this.f_yuegong).toFixed(2),
                                Interest: //每月应还利息=贷款本金×月利率×〔(1+月利率)^还款月数-(1+月利率)^(还款月序号-1)〕÷〔(1+月利率)^还款月数-1〕
                                    ((this.f_daikuan * this.formValidate.accumulationFund_lilvValue / 1200 *
                                        (Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200),
                                                loanYear) -
                                            Math.pow((1 + this.formValidate.accumulationFund_lilvValue /
                                                1200), (i))) /
                                        (Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200),
                                            (loanYear)) - 1)
                                    )).toFixed(2),
                                reimbursementPrincipal: //贷款本金×月利率×(1+月利率)^(还款月序号-1)÷〔(1+月利率)^还款月数-1〕
                                    ((this.f_daikuan * this.formValidate.accumulationFund_lilvValue / 1200 *
                                        Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200),
                                            (i)) /
                                        (Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200),
                                            (loanYear)) - 1)
                                    )).toFixed(2),
                                residualPrincipal: (residualPrincipal).toFixed(2)
                                // (this.f_daikuan-((this.f_daikuan*this.formValidate.accumulationFund_lilvValue/1200*
                                //     Math.pow((1+this.formValidate.accumulationFund_lilvValue/1200),(i))/
                                //     (Math.pow((1+this.formValidate.accumulationFund_lilvValue/1200),(loanYear))-1)
                                // )*(i+1))).toFixed(2),
                            });
                        }
                        //结果显示
                        this.f_huankuan = ((this.f_daikuan + this.f_lixi) / 10000).toFixed(2) + "万";
                        this.f_shoufu = (this.formValidate.loan_total * 10000 - this.f_daikuan) / 10000 + "万";

                        this.f_daikuan = this.f_daikuan / 10000 + "万";
                        this.f_yuegong = (this.f_yuegong).toFixed(2) + "元";
                        this.f_lixi = (this.f_lixi / 10000).toFixed(2) + "万";
                    }
                    //组合贷款
                    else if (this.formValidate.loan_type === '组合贷款') {
                        this.f_daikuan = this.formValidate.business_total * 10000 + this.formValidate.accumulationFund_loan *
                            10000;
                        this.f_yuegong = this.formValidate.business_total * 10000 *
                            (this.formValidate.business_interestRateValue / 1200) * Math.pow((1 + this.formValidate.business_interestRateValue /
                                1200), loanYear) /
                            (Math.pow((1 + this.formValidate.business_interestRateValue / 1200), (loanYear)) - 1) +
                            this.formValidate.accumulationFund_loan * 10000 *
                            (this.formValidate.accumulationFund_lilvValue / 1200) * Math.pow((1 + this.formValidate.accumulationFund_lilvValue /
                                1200), loanYear) /
                            (Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200), (loanYear)) - 1);
                        this.f_lixi = loanYear * this.f_yuegong - this.f_daikuan;
                        //列表
                        this.data2 = [];
                        for (let i = 0; i < loanYear; i++) {
                            let residualPrincipal = this.f_daikuan;
                            let reimbursementPrincipal =
                                (this.formValidate.accumulationFund_loan * 10000 * this.formValidate.accumulationFund_lilvValue /
                                    1200 *
                                    Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200), (i)) /
                                    (Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200), (loanYear)) -
                                        1)
                                ) + (this.formValidate.business_total * 10000 * this.formValidate.business_interestRateValue /
                                    1200 *
                                    Math.pow((1 + this.formValidate.business_interestRateValue / 1200), (i)) /
                                    (Math.pow((1 + this.formValidate.business_interestRateValue / 1200), (loanYear)) -
                                        1)
                                );
                            if (i == 0) {
                                residualPrincipal = this.f_daikuan - reimbursementPrincipal;
                            } else if (i == loanYear - 1) {
                                residualPrincipal = 0.00;
                            } else {
                                residualPrincipal = this.data2[i - 1].residualPrincipal - reimbursementPrincipal;
                            }
                            this.$set(this.data2, i, {
                                number: i + 1,
                                monthFor: (this.f_yuegong).toFixed(2),
                                Interest: //每月应还利息=贷款本金×月利率×〔(1+月利率)^还款月数-(1+月利率)^(还款月序号-1)〕÷〔(1+月利率)^还款月数-1〕
                                    ((this.formValidate.business_total * 10000 * this.formValidate.business_interestRateValue /
                                        1200 *
                                        (Math.pow((1 + this.formValidate.business_interestRateValue / 1200),
                                                loanYear) -
                                            Math.pow((1 + this.formValidate.business_interestRateValue /
                                                1200), (i))) /
                                        (Math.pow((1 + this.formValidate.business_interestRateValue / 1200),
                                            (loanYear)) - 1)
                                    ) + (this.formValidate.accumulationFund_loan * 10000 * this.formValidate
                                        .accumulationFund_lilvValue / 1200 *
                                        (Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200),
                                                loanYear) -
                                            Math.pow((1 + this.formValidate.accumulationFund_lilvValue /
                                                1200), (i))) /
                                        (Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200),
                                            (loanYear)) - 1)
                                    )).toFixed(2),
                                reimbursementPrincipal: //贷款本金×月利率×(1+月利率)^(还款月序号-1)÷〔(1+月利率)^还款月数-1〕
                                    ((this.formValidate.accumulationFund_loan * 10000 * this.formValidate.accumulationFund_lilvValue /
                                        1200 *
                                        Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200),
                                            (i)) /
                                        (Math.pow((1 + this.formValidate.accumulationFund_lilvValue / 1200),
                                            (loanYear)) - 1)
                                    ) + (this.formValidate.business_total * 10000 * this.formValidate.business_interestRateValue /
                                        1200 *
                                        Math.pow((1 + this.formValidate.business_interestRateValue / 1200),
                                            (i)) /
                                        (Math.pow((1 + this.formValidate.business_interestRateValue / 1200),
                                            (loanYear)) - 1)
                                    )).toFixed(2),
                                residualPrincipal: (residualPrincipal).toFixed(2)
                                // ((this.formValidate.accumulationFund_loan*10000-((this.f_daikuan*this.formValidate.accumulationFund_lilvValue/1200*
                                //     Math.pow((1+this.formValidate.accumulationFund_lilvValue/1200),(i))/
                                //     (Math.pow((1+this.formValidate.accumulationFund_lilvValue/1200),(loanYear))-1)
                                // )*(i+1)))+(this.formValidate.business_total*10000-((this.f_daikuan*this.formValidate.business_interestRateValue/1200*
                                //         Math.pow((1+this.formValidate.business_interestRateValue/1200),(i))/
                                //         (Math.pow((1+this.formValidate.business_interestRateValue/1200),(loanYear))-1)
                                //     )*(i+1)))
                                // ).toFixed(2),
                            });
                        }
                        //结果显示
                        this.f_huankuan = ((this.f_daikuan + this.f_lixi) / 10000).toFixed(2) + "万";

                        this.f_daikuan = this.f_daikuan / 10000 + "万";
                        this.f_yuegong = (this.f_yuegong).toFixed(2) + "元";
                        this.f_lixi = (this.f_lixi / 10000).toFixed(2) + "万";
                    }


                }
                //            等额本金
                else {
                    /**
                     * 等额本金还款法:
                     *   每月月供额=(贷款本金÷还款月数)+(贷款本金-已归还本金累计额)×月利率
                     *   每月应还本金=贷款本金÷还款月数
                     *   每月应还利息=剩余本金×月利率=(贷款本金-已归还本金累计额)×月利率
                     *   每月月供递减额=每月应还本金×月利率
                     *   总利息=〔(总贷款额÷还款月数+总贷款额×月利率)+总贷款额÷还款月数×(1+月利率)〕÷2×还款月数-总贷款额
                     **/

                    //商业贷款
                    if (this.formValidate.loan_type === '商业贷款') {
                        // let yuehuanbenjin=parseInt(this.f_daikuan/loanYear);
                        this.f_daikuan = this.formValidate.loan_total * 10000 * this.formValidate.mortgage_number;
                        this.f_fristyuegong = (this.f_daikuan / loanYear) + this.f_daikuan * this.formValidate.business_interestRateValue /
                            1200;
                        this.f_lixi = ((this.f_daikuan / loanYear + this.f_daikuan * this.formValidate.business_interestRateValue /
                                    1200) +
                                this.f_daikuan / loanYear * (1 + this.formValidate.business_interestRateValue / 1200)) /
                            2 * loanYear - this.f_daikuan;
                        this.f_huankuan = (this.f_daikuan + this.f_lixi) / 10000;
                        this.f_dijian = this.f_daikuan / loanYear * this.formValidate.business_interestRateValue / 1200;

                        //列表
                        this.data2 = [];
                        for (let i = 0; i < loanYear; i++) {
                            this.$set(this.data2, i, {
                                number: i + 1,
                                monthFor: (this.f_fristyuegong - this.f_dijian * i).toFixed(2),
                                Interest: (
                                    (this.f_daikuan - (this.f_daikuan / loanYear) * i) * (this.formValidate
                                        .business_interestRateValue / 1200)).toFixed(2),
                                reimbursementPrincipal: (this.f_daikuan / loanYear).toFixed(2),
                                residualPrincipal: (this.f_daikuan - (this.f_daikuan / loanYear) * (i + 1)).toFixed(
                                    2),
                            });
                        }
                        //结果显示
                        this.f_daikuan = this.f_daikuan / 10000 + "万";
                        this.f_fristyuegong = (this.f_fristyuegong).toFixed(2) + "元";
                        this.f_lixi = (this.f_lixi / 10000).toFixed(2) + "万";
                        this.f_dijian = (this.f_dijian).toFixed(2) + "元";
                        this.f_huankuan = (this.f_huankuan).toFixed(2) + "万";
                    }
                    //公积金贷款
                    else if (this.formValidate.loan_type === '公积金贷款') {
                        this.f_daikuan = this.formValidate.loan_total * 10000 * this.formValidate.mortgage_number;
                        this.f_fristyuegong = (this.f_daikuan / loanYear) + this.f_daikuan * this.formValidate.accumulationFund_lilvValue /
                            1200;
                        this.f_lixi = ((this.f_daikuan / loanYear + this.f_daikuan * this.formValidate.accumulationFund_lilvValue /
                                    1200) +
                                this.f_daikuan / loanYear * (1 + this.formValidate.accumulationFund_lilvValue / 1200)) /
                            2 * loanYear - this.f_daikuan;
                        this.f_huankuan = (this.f_daikuan + this.f_lixi) / 10000;
                        this.f_dijian = (this.f_daikuan / loanYear) * (this.formValidate.accumulationFund_lilvValue /
                            1200);
                        //列表
                        this.data2 = [];
                        for (let i = 0; i < loanYear; i++) {
                            this.$set(this.data2, i, {
                                number: i + 1,
                                monthFor: (this.f_fristyuegong - this.f_dijian * i).toFixed(2),
                                Interest: (
                                    (this.f_daikuan - (this.f_daikuan / loanYear) * i) * (this.formValidate
                                        .accumulationFund_lilvValue / 1200)).toFixed(2),
                                reimbursementPrincipal: (this.f_daikuan / loanYear).toFixed(2),
                                residualPrincipal: (this.f_daikuan - (this.f_daikuan / loanYear) * (i + 1)).toFixed(
                                    2),
                            });
                        }
                        //结果显示
                        this.f_daikuan = this.f_daikuan / 10000 + "万";
                        this.f_fristyuegong = (this.f_fristyuegong).toFixed(2) + "元";
                        this.f_lixi = (this.f_lixi / 10000).toFixed(2) + "万";
                        this.f_huankuan = (this.f_huankuan).toFixed(2) + "万";
                        this.f_dijian = (this.f_dijian).toFixed(2) + "元";
                    }
                    //组合贷款
                    else if (this.formValidate.loan_type === '组合贷款') {
                        //贷款总额
                        this.f_daikuan = this.formValidate.business_total * 10000 + this.formValidate.accumulationFund_loan *
                            10000;
                        //首月月供
                        this.f_fristyuegong = (this.formValidate.business_total * 10000 / loanYear) + this.formValidate
                            .business_total * 10000 * this.formValidate.business_interestRateValue / 1200 +
                            (this.formValidate.accumulationFund_loan * 10000 / loanYear) + this.formValidate.accumulationFund_loan *
                            10000 * this.formValidate.accumulationFund_lilvValue / 1200;
                        //总利息               总利息=〔(总贷款额÷还款月数+总贷款额×月利率)+总贷款额÷还款月数×(1+月利率)〕÷2×还款月数-总贷款额
                        this.f_lixi = ((this.formValidate.business_total * 10000 / loanYear + this.formValidate.business_total *
                                    10000 * this.formValidate.business_interestRateValue / 1200) +
                                this.formValidate.business_total * 10000 / loanYear * (1 + this.formValidate.business_interestRateValue /
                                    1200)) / 2 * loanYear - this.formValidate.business_total * 10000 +
                            ((this.formValidate.accumulationFund_loan * 10000 / loanYear + this.formValidate.accumulationFund_loan *
                                    10000 * this.formValidate.accumulationFund_lilvValue / 1200) +
                                this.formValidate.accumulationFund_loan * 10000 / loanYear * (1 + this.formValidate.accumulationFund_lilvValue /
                                    1200)) / 2 * loanYear - this.formValidate.accumulationFund_loan * 10000;
                        //总还款
                        this.f_huankuan = (this.f_daikuan + this.f_lixi) / 10000;
                        //每月递减
                        this.f_dijian = (this.formValidate.business_total * 10000 / loanYear * this.formValidate.business_interestRateValue /
                                1200) +
                            (this.formValidate.accumulationFund_loan * 10000 / loanYear * this.formValidate.accumulationFund_lilvValue /
                                1200);
                        //列表
                        this.data2 = [];
                        for (let i = 0; i < loanYear; i++) {
                            this.$set(this.data2, i, {
                                number: i + 1,
                                monthFor: (this.f_fristyuegong - this.f_dijian * i).toFixed(2),
                                Interest: (
                                    (this.formValidate.accumulationFund_loan * 10000 - (this.formValidate.accumulationFund_loan *
                                        10000 / loanYear) * i) * (this.formValidate.accumulationFund_lilvValue /
                                        1200) +
                                    (this.formValidate.business_total * 10000 - (this.formValidate.business_total *
                                        10000 / loanYear) * i) * (this.formValidate.business_interestRateValue /
                                        1200)).toFixed(2),
                                reimbursementPrincipal: (this.f_daikuan / loanYear).toFixed(2),
                                residualPrincipal: (this.f_daikuan - (this.f_daikuan / loanYear) * (i + 1)).toFixed(
                                    2),
                            });
                        }
                        //结果显示
                        this.f_daikuan = this.f_daikuan / 10000 + "万";
                        this.f_fristyuegong = (this.f_fristyuegong).toFixed(2) + "元";
                        this.f_lixi = (this.f_lixi / 10000).toFixed(2) + "万";
                        this.f_dijian = (this.f_dijian).toFixed(2) + "元";
                        this.f_huankuan = (this.f_huankuan).toFixed(2) + "万";

                    }
                    // {
                    //     number: '1 ',
                    //         monthFor: '',
                    //     Interest: '',
                    //     reimbursementPrincipal: '',
                    //     residualPrincipal: '',
                    // }


                }
            },
            // 清空按钮
            handleReset() {
                this.$refs['formValidate'].resetFields();
            },

            //          税费计算

            //切换新房二手房 清除填过的数据
            // cutOrdHouse(){
            //   if(this.house_type === '二手房'){
            //       this.formValidate2.house_m2='';
            //       this.formValidate2.house_price2='';
            //       this.isNewShow=false;
            //       // this.fristBuyHouse='';
            //       this.fangkuan='';
            //       this.gongben='';
            //       this.dengji='';
            //       this.weixiu='';
            //       this.heji='';
            //       this.qishui='';
            //   }else if(this.house_type === '新房'){
            //       this.isOrdShow=false;
            //       this.gbyinhua2='';
            //       this.yinhua2='';
            //       this.qishui2='';
            //       this.zengzhi2='';
            //       this.geren2='';
            //       this.dijia2='';
            //       this.heji2='';
            //       this.houseProperty_year='';
            //       this.fristBuyHouse='';
            //       this.formValidate2.house_m2='';
            //       this.formValidate2.house_price2='';
            //       this.onlyHouse='';
            //       this.formValidate2.house_total='';
            //       this.formValidate2.house_Oprice='';
            //       this.formValidate2.Levy_type='';
            //       this.formValidate2.houseProperty_type='';
            //   }
            // },
            //计算按钮
            handleSubmit2() {
                //              新房税费计算
                if (this.house_type === '新房') {
                    this.isNewShow = true;
                    this.fangkuan = this.formValidate2.house_m2 * this.formValidate2.house_price2;
                    //                    契税
                    this.gongben = 5;
                    this.dengji = 80;
                    if (this.fristBuyHouse === '否') {
                        this.qishui = this.fangkuan * 0.03;
                    } else if (this.fristBuyHouse === '是') {
                        if (this.formValidate2.house_m2 < 90) {
                            this.qishui = this.fangkuan * 0.01
                        } else if (90 <= this.formValidate2.house_m2 <= 144) {
                            this.qishui = this.fangkuan * 0.015
                        } else if (this.formValidate2.house_m2 > 144) {
                            this.qishui = this.fangkuan * 0.03
                        }
                    }
                    //     结果显示
                    //                   维修基金
                    this.weixiu = this.fangkuan * 0.03;
                    //                  合计
                    this.heji = (this.gongben + this.dengji + this.qishui + this.weixiu) + "元";
                    this.gongben = 5 + '元';
                    this.dengji = 80 + '元';
                    this.fangkuan = this.fangkuan / 10000 + "万";
                    this.qishui = this.qishui + "元";
                    this.weixiu = this.weixiu + "元";

                }
                //              二手房税费计算
                else if (this.house_type === '二手房') {
                    this.isOrdShow = true;
                    //总价方式计算
                    if (this.formValidate2.Levy_type === '总价') {
                        //                   契税
                        if (this.formValidate2.house_m2 < 90) {
                            if (this.houseProperty_year === '不满2年') {
                                this.qishui2 = this.formValidate2.house_total * 10000 * 0.009467;
                            } else {
                                this.qishui2 = this.formValidate2.house_total * 10000 * 0.01;
                            }
                        } else {
                            if (this.houseProperty_year === '不满2年') {
                                if (this.fristBuyHouse === '是') {
                                    this.qishui2 = this.formValidate2.house_total * 10000 * 0.0142;
                                } else {
                                    this.qishui2 = this.formValidate2.house_total * 10000 * 0.018933;
                                }
                            } else {
                                if (this.fristBuyHouse === '是') {
                                    this.qishui2 = this.formValidate2.house_total * 10000 * 0.015;
                                } else {
                                    this.qishui2 = this.formValidate2.house_total * 10000 * 0.02;
                                }
                            }
                        }
                        //                 契稅契税结束
                        //                        个人所得税
                        if (this.onlyHouse === '是') {
                            if (this.houseProperty_year === '不满2年') {
                                this.geren2 = this.formValidate2.house_total * 10000 * 0.009467;
                            } else if (this.houseProperty_year === '满2年') {
                                this.geren2 = this.formValidate2.house_total * 10000 * 0.01;
                            } else {
                                this.geren2 = 0;
                            }
                        } else {
                            if (this.houseProperty_year === '不满2年') {
                                this.geren2 = this.formValidate2.house_total * 10000 * 0.009467;
                            } else if (this.houseProperty_year === '满2年') {
                                this.geren2 = this.formValidate2.house_total * 10000 * 0.01;
                            } else {
                                this.geren2 = this.formValidate2.house_total * 10000 * 0.01;
                            }
                        }
                        //           个人所得税结束
                        //          增值税、
                        if (this.houseProperty_year === '不满2年') {
                            this.zengzhi2 = this.formValidate2.house_total * 10000 * 0.0533;
                        } else {
                            this.zengzhi2 = 0
                        }

                    }
                    //差价方式计算
                    else {
                        //个人所得税
                        if (this.onlyHouse === '是') {
                            if (this.houseProperty_year === '满5年') {
                                this.geren2 = 0;
                            } else {
                                this.geren2 = this.formValidate2.house_total * 10000 * 0.05;
                            }
                        } else {
                            this.geren2 = this.formValidate2.house_total * 10000 * 0.05;
                        }
                        //    契税
                        if (this.formValidate2.house_m2 < 90) {
                            this.qishui2 = this.formValidate2.house_total * 10000 * 0.01;
                        } else {
                            if (this.fristBuyHouse === '是') {
                                this.qishui2 = this.formValidate2.house_total * 10000 * 0.015;
                            } else {
                                this.qishui2 = this.formValidate2.house_total * 10000 * 0.02;
                            }
                        }
                        //    增值税
                        this.zengzhi2 = 0;

                    }
                    //    结果显示
                    // 合计
                    this.heji2 = (this.zengzhi2 + this.qishui2 + this.geren2) + "元";
                    this.qishui2 = this.qishui2 + "元";
                    this.geren2 = this.geren2 + "元";
                    this.zengzhi2 = this.zengzhi2 + "元";
                }


            },
            //清空按钮
            handleReset2() {
                this.houseProperty_year = '';
                this.fristBuyHouse = '';
                this.formValidate2.house_m2 = '';
                this.formValidate2.house_price2 = '';
                this.onlyHouse = '';
                this.formValidate2.house_total = '';
                this.formValidate2.house_Oprice = '';
                this.formValidate2.Levy_type = '';
                this.formValidate2.houseProperty_type = '';
                this.fangkuan = '';
                this.gongben = '';
                this.dengji = '';
                this.weixiu = '';
                this.heji = '';
                this.qishui = '';
                this.gbyinhua2 = '';
                this.yinhua2 = '';
                this.qishui2 = '';
                this.zengzhi2 = '';
                this.geren2 = '';
                this.dijia2 = '';
                this.heji2 = '';
            }
        }

    }
</script>
