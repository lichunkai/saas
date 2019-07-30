<style scoped>
    @import "customerEtails.less";
</style>

<template>
    <Row class="editHight">
        <Col :lg="24" :md="24" style="padding-bottom: 10px">
        <Card>
            <Row>
                <Col class="roomCol">
                <div class="roomHeader" @click="edit_customer">
                    <p>
                        <i class="icon iconfont icon-liebiao" style="font-size: 27px"></i>
                    </p>
                    <span>修改信息</span>
                </div>
                <editDetails :editRoom="editRoom" :formValidate="formValidate"
                             :peizhi="peizhi"
                             v-on:resetModal="resetModal"></editDetails>
                </Col>
                <Col class="roomCol">
                <div class="roomHeader" @click="rChange =true">
                    <p>
                        <i class="icon iconfont icon-shalou" style="font-size: 24px"></i>
                    </p>
                    <span>变更状态</span>
                </div>
                <Modal v-model="rChange" title="变更状态" :closable="false" :mask-closable="false"
                       @on-ok="handleSubmit('zhuangtai')" @on-cancel="handleReset('zhuangtai')">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="StatusModalCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">变更状态</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="StatusModalCancel">取消</Button>
                        <Button type="primary" size="large" @click="StatusModalOk">确定</Button>
                    </div>
                    <Form ref="zhuangtai" :model="zhuangtai" :rules="zhuangtaidate" :label-width="80">
                        <FormItem label="原状态" prop="dangqian">
                            <Input v-model="zhuangtai.dangqian" disabled="disabled"></Input>
                        </FormItem>
                        <FormItem label="现状态" prop="biangeng">
                            <Select placeholder="" :transfer="true" v-model="zhuangtai.biangeng">
                                <Option v-for="item in JSON.parse( this.peizhi.zhuangtai[0].base_desp)"
                                        :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                                </Option>
                            </Select>
                        </FormItem>
                        <FormItem label="变更理由" prop="biangengliyou">
                            <Input placeholder="" v-model="zhuangtai.biangengliyou"></Input>
                        </FormItem>
                    </Form>
                </Modal>
                </Col>
                <Col class="roomCol">
                <div class="roomHeader" @click="gongkesike" v-if="customer.customer_private=='公客'">
                    <p>
                        <i class="icon iconfont icon-tianjiakehu" style="font-size: 27px"></i>
                    </p>
                    <span>收为私客</span>
                </div>
                <div class="roomHeader" @click="gongkesike" v-if="customer.customer_private=='私客'">
                    <p>
                        <i class="icon iconfont icon-kehu" style="font-size: 27px"></i>
                    </p>
                    <span>踢入公客</span>
                </div>
                </Col>
                <Col class="roomCol" v-if="peizhi.topbutton.swzt=='1'">
                <div class="roomHeader" @click="sheweizhutui" v-if="customer.zhutui=='1'">
                    <p>
                        <i class="icon iconfont icon-quxiao" style="font-size: 24px"></i>
                    </p>
                    <span>取消主推</span>
                </div>
                <div class="roomHeader" @click="sheweizhutui" v-if="customer.zhutui=='0'">
                    <p>
                        <i class="icon iconfont icon-tuijian" style="font-size: 24px"></i>
                    </p>
                    <span>设为主推</span>
                </div>
                </Col>
                <Col class="roomCol">
                <div class="roomHeader" @click="showfollowgo">
                    <p>
                        <i class="icon iconfont icon-xie" style="font-size: 24px"></i>
                    </p>
                    <span>写跟进</span>
                </div>
                <div v-if="telrs">
                    <Modal v-model="showfollow" title="写跟进" :closable="false" :mask-closable="false"
                           @on-ok="handleSubmit('genjin')" @on-cancel="handleReset('genjin')"  >
                        <div slot="footer">
                            <Button type="primary" size="large" @click="modalOk" :disabled="isDisable">确定</Button>
                        </div>
                        <Form ref="genjin" :model="genjin" :rules="genjindate" :label-width="80">
                            <Row>
                                <Col :lg="12" :md="12" >
                                <FormItem label="联系电话" prop='tel_phone' >
                                    <p v-for="v  in this.tel " v-model="v.tel_phone"><span>{{v.tel_phone}}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>{{v.tel_type}}</span>
                                    </p>
                                </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <Col :lg="12" :md="12">
                                <FormItem label="跟进方式" prop="genjinfangshi">
                                    <Select placeholder="" v-model="genjin.genjinfangshi" :transfer="true">
                                        <Option v-for="item in JSON.parse( this.peizhi.genjinfangshi[0].base_desp)"
                                                :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                                        </Option>
                                    </Select>
                                </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <Col :lg="24" :md="24">
                                <FormItem label="跟进内容" prop="genjinneirong">
                                    <Input type="textarea" :autosize="{minRows: 4,maxRows: 6}" placeholder=""
                                           v-model="genjin.genjinneirong"></Input>
                                </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <Col :lg="12" :md="12">
                                <FormItem label="选择部门" prop="bumen">
                                    <Cascader :data="this.peizhi.departlist" trigger="click" filterable change-on-select
                                              v-model="genjin.bumen" @on-change="changeDepart"
                                              :transfer="true"></Cascader>
                                </FormItem>
                                </Col>
                                <Col :lg="12" :md="12">
                                <FormItem label="跟进人" prop="user">
                                    <Select v-model="genjin.user" placeholder="" :transfer="true">
                                        <Option v-for="v in genjin.users" :value="v.u_id" :key="v.u_id">{{v.u_name}}
                                        </Option>
                                    </Select>
                                </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <Col :lg="12" :md="12">
                                <FormItem label="提醒时间" prop="tixingshijian_x">
                                    <DatePicker type="date" @on-change="setPublishTime" v-model="genjin.tixingshijian_x"
                                                format="yyyy-MM-dd" :transfer="true"></DatePicker>
                                </FormItem>
                                </Col>
                                <Col :lg="24" :md="24">
                                <FormItem label="提醒内容" prop="tixingneirong">
                                    <Input type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder=""
                                           v-model="genjin.tixingneirong"></Input>
                                </FormItem>
                                </Col>
                            </Row>
                        </Form>
                    </Modal>
                </div>
                <div v-if="!telrs">
                    <Modal v-model="showfollow" title="写跟进" :closable="true" :mask-closable="false"
                           @on-ok="handleSubmit('genjin')" @on-cancel="handleReset('genjin')"  >
                        <div slot="header">
                            <a class="ivu-modal-close" @click="modalCancel"><i
                                    class="ivu-icon ivu-icon-ios-close-empty" style="display: block!important;"></i></a>
                            <div class="ivu-modal-header-inner">写跟进</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="modalCancel" >取消</Button>
                            <Button type="primary" size="large" @click="modalOk" :disabled="isDisable">确定</Button>
                        </div>
                        <Form ref="genjin" :model="genjin" :rules="genjindate" :label-width="80">
                            <Row>
                                <Col :lg="12" :md="12">
                                <FormItem label="跟进方式" prop="genjinfangshi">
                                    <Select placeholder="" v-model="genjin.genjinfangshi" :transfer="true">
                                        <Option v-for="item in JSON.parse( this.peizhi.genjinfangshi[0].base_desp)"
                                                :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                                        </Option>
                                    </Select>
                                </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <Col :lg="24" :md="24">
                                <FormItem label="跟进内容" prop="genjinneirong">
                                    <Input type="textarea" :autosize="{minRows: 4,maxRows: 6}" placeholder=""
                                           v-model="genjin.genjinneirong"></Input>
                                </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <Col :lg="12" :md="12">
                                <FormItem label="选择部门" prop="bumen">
                                    <Cascader :data="this.peizhi.departlist" trigger="click" filterable change-on-select
                                              v-model="genjin.bumen" @on-change="changeDepart"
                                              :transfer="true"></Cascader>
                                </FormItem>
                                </Col>
                                <Col :lg="12" :md="12">
                                <FormItem label="跟进人" prop="user">
                                    <Select v-model="genjin.user" placeholder="" :transfer="true">
                                        <Option v-for="v in genjin.users" :value="v.u_id" :key="v.u_id">{{v.u_name}}
                                        </Option>
                                    </Select>
                                </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <Col :lg="12" :md="12">
                                <FormItem label="提醒时间" prop="tixingshijian_x">
                                    <DatePicker type="date" @on-change="setPublishTime" v-model="genjin.tixingshijian_x"
                                                format="yyyy-MM-dd" :transfer="true"></DatePicker>
                                </FormItem>
                                </Col>
                                <Col :lg="24" :md="24">
                                <FormItem label="提醒内容" prop="tixingneirong">
                                    <Input type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder=""
                                           v-model="genjin.tixingneirong"></Input>
                                </FormItem>
                                </Col>
                            </Row>
                        </Form>
                    </Modal>
                </div>

                </Col>
                <Col class="roomCol">
                <div class="roomHeader" @click="daiKan">
                    <p>
                        <i class="icon iconfont icon-daikan" style="font-size: 27px"></i>
                    </p>
                    <span>写带看</span>
                </div>
                <editDaikan1 :rDaikan="rDaikan" :buyDealtitle="buyDealtitle" :peizhi="peizhi"
                            v-on:resetok1="resetok1"></editDaikan1>
                </Col>
                <!--<Col class="roomCol">-->
                <!--<div class="roomHeader" @click="rBuyDeal">-->
                    <!--<p>-->
                        <!--<i class="icon iconfont icon-jine1" style="font-size: 30px"></i>-->
                    <!--</p>-->
                    <!--<span>意向金</span>-->
                <!--</div>-->
                <!--<editDeal :rDeal="rDeal" :buyDealtitle="buyDealtitle" v-on:resetok="resetok" :customer="customer"-->
                          <!--:peizhi="peizhi"></editDeal>-->
                <!--</Col>-->
                <Col class="roomCol">
                <div class="roomHeader" @click="rFengpan = true" v-if="customer.customer_private=='公客'">
                    <p>
                        <i class="icon iconfont icon-renzhengfengsuo" style="font-size: 24px"></i>
                    </p>
                    <span>申请封盘</span>
                </div>
                <Modal v-model="rFengpan" title="申请封盘" width="360">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="FengpanModalCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">申请封盘</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="FengpanModalCancel">取消</Button>
                        <Button type="primary" size="large" @click="FengpanModalOk">确定</Button>
                    </div>
                    <Form ref="sqfp" :model="sqfp" :rules="sqfpdate" :label-width="80">
                        <Row>
                            <Col :lg="18" :md="18">
                            <FormItem label="申请封盘" prop="">
                                封路径
                            </FormItem>
                            </Col>
                            <Col :lg="18" :md="18">
                            <FormItem label="封盘原因" prop="fengpanyuanyin">
                                <Select placeholder="" :transfer="true" v-model="sqfp.fengpanyuanyin">
                                    <Option v-for="item in JSON.parse( this.peizhi.fengpanyuanyin[0].base_desp)"
                                            :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                                    </Option>
                                </Select>
                            </FormItem>
                            </Col>
                        </Row>
                        <FormItem label="备注" prop="beizhu">
                            <Input type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder=""
                                   v-model="sqfp.beizhu"></Input>
                        </FormItem>
                    </Form>
                </Modal>
                </Col>
                <Col class="roomCol">
                <a :href="last_url" class="roomHeader" v-if="last_url">
                    <p>
                        <i class="icon iconfont icon-kuaituishangyige" style="font-size: 27px"></i>
                    </p>
                    <span>上一条</span>
                </a>
                </Col>
                <Col class="roomCol">
                <a :href="next_url" class="roomHeader" v-if="next_url">
                    <p>
                        <i class="icon iconfont icon-kuaijin" style="font-size: 27px"></i>
                    </p>
                    <span>下一条</span>
                </a>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" style="background: #fff;padding:16px;">
        <Card>
            <Tabs type="card" :animated="false" v-model="tabskey.key">
                <TabPane label="基本信息">
                    <editBasic :customer="customer" :peizhi="peizhi" :teltrue="teltrue" v-on:follow_tel="follow_tel"
                               v-on:resetok="resetok"></editBasic>
                </TabPane>
                <TabPane label="匹配房源">
                    <editCustomers ref="fangyuanpipei" :customer="customer" :peizhi="peizhi"
                                   v-on:resetok="resetok"></editCustomers>
                </TabPane>
                <TabPane label="匹配采集房源">
                    <houseCaiji ref="pipeicaiji" :customer="customer" :peizhi="peizhi" v-on:resetok="resetok"></houseCaiji>
                </TabPane>
                <TabPane label="匹配合作房源">
                    <houseHezuo ref="pipeihezuo" :customer="customer" :peizhi="peizhi" v-on:resetok="resetok"></houseHezuo>
                </TabPane>
                <TabPane label="相似客户">
                    <editSimilar ref="xiangshikehu" :customer="customer" :peizhi="peizhi"
                                 v-on:resetok="resetok"></editSimilar>
                </TabPane>
                <TabPane label="客源跟进">
                    <editGenjin ref="mygenjin"></editGenjin>
                </TabPane>
                <TabPane label="带看记录">
                    <editSee ref="mydaikan"></editSee>
                </TabPane>
                <TabPane label="客源日志">
                    <editRizhi ref="keyuanrizi"></editRizhi>
                </TabPane>


            </Tabs>
        </Card>
        </Col>
    </Row>
</template>

<script>
    import editDetails from './edit-details.vue';
    import editDeal from './edit-deal.vue';
    import editBasic from './edit-basic.vue';
    import editCustomers from './editCustomers.vue';
    import editGenjin from './editGenjin.vue';
    import editSee from './editSee.vue';
    import editSimilar from './editSimilar.vue';
    import editDaikan1 from './editDaikan1.vue';
    import editRizhi from './editRizhi.vue';
    export default {
        name: 'customerEtails1',
        components: {
            editDetails,//修改信息
            editDeal,//买卖成交
            editBasic,//基本信息
            editCustomers,//匹配房源
            editGenjin,//房源跟进
            editSee,//带看记录
            editSimilar,//相似客源
            editDaikan1,//带看
            editRizhi,//客源日志
        },
        data () {
            return {
                isDisable: false,
                last_url: '',
                next_url: '',
                telrs: false,
                tel: [],
                editRoom: false,
                customer: [],
                peizhi: [],
                usermodaltitle: '',
                rChange: false,
                showfollow: false,
                editDeal: false,
                buyDealtitle: '',
                rFengpan: false,
                teltrue: '',
                rReport: false,
                rDaikan: false,
                rDeal: false,
                tabskey:{
                    key:0,
                },
                zhuangtai: {
                    dangqian: '',
                    biangeng: '',
                    biangengliyou: '',
                }, zhuangtaidate: {
                    biangeng: [
                        {required: true, message: '请选择变更状态', trigger: 'blur'}
                    ],
                    biangengliyou: [
                        {required: true, message: '变更理由不能为空', trigger: 'blur'}
                    ]
                },
                sqfp: {
                    fengpanyuanyin: '',
                    beizhu: '',
                }, sqfpdate: {
                    fengpanyuanyin: [
                        {required: true, message: '请选择封盘原因', trigger: 'blur'}
                    ],
                    beizhu: [
                        {required: true, message: '请选择封盘备注', trigger: 'blur'}
                    ]
                },
                genjin: {
                    genjinfangshi: '',
                    genjinneirong: '',
                    bumen: '',
                    tixingshijian: '',
                    tixingshijian_x: '',
                    tixingneirong: '',
                    user: '',
                    users: '',
                },
                teltrue:'',
                formValidate: {
                    title: '修改信息',
                    customer_private: '',
                    customer_name: '',
                    customer_uuid: '',
                    customer_type: '',
                    customer_sex: '',
                    tiaojianbiaoqian: [],
                    xuqiubianhao: '',
                    xuqiuquyu: [],
                    dengji: '',
                    village:'',
                    village_list:[],
                    duoxuanbiaoqian: [],
                    yongtu: '',
                    xuqiuhuxing: '',
                    xuqiuhuxing_min: '',
                    xuqiuhuxing_max: '',
                    xuqiujiage: '',
                    xuqiujiage_min: '',
                    xuqiujiage_max: '',
                    xuqiumianji: '',
                    xuqiumianji_min: '',
                    xuqiumianji_max: '',
                    xuqiulouceng: '',
                    xuqiulouceng_min: '',
                    xuqiulouceng_max: '',
                    daikancishu: [],
                    xuqiufangling: '',
                    xuqiufangling_min: '',
                    xuqiufangling_max: '',
                    chaoxiang: '',
                    zhuangxiu: '',
                    peitao: [],
                    xuqiuyuanying: '',
                    xuqiu_tag: '',
                    fangwuleixing: '',
                    goutongjieduan: '',
                    kehulaiyuan: '',
                    xiaofeilinian: [],
                    guoji: '',
                    minzu: [],
                    zhengjianhaoma: '',
                    email: '',
                    qq: '',
                    weixin: '',
                    jiaotonggongju: '',
                    chexing: '',
                    mark: '',
                    core_mark: '',
                    zhuangtai: '',
                    tel_phone: '',
                    tel_type: '',
                }, genjindate: {
                    genjinneirong: [
                        {required: true, message: '请输入跟进内容', trigger: 'blur'}
                    ],
                    genjinfangshi: [
                        {required: true, message: '请选择跟进方式', trigger: 'blur'}
                    ],
                    bumen: [
                        {type: 'array', required: true, message: '请选择部门', trigger: 'blur'}
                    ]/*,
                    user: [
                        {required: true, message: '请选择跟进人', trigger: 'blur'}
                    ],
                    tixingshijian_x: [
                        {type: 'array',required: true, message: '请选择时间', trigger: 'blur'}
                    ],
                    tixingneirong: [
                        { required: true, message: '请输入提醒内容', trigger: 'blur'}
                    ],*/
                }
            };
        },
        created: function () {
            this.getIndex();
        },
        methods: {
            StatusModalCancel () {//修改取消
                this.rChange = false;
            },
            gongkesike () {
                var data = {
                    customer_private: this.customer.customer_private,
                    customer_uuid: this.customer.customer_uuid
                };
                this.$http.post(api_param.apiurl + 'customer/gksk',
                    data,
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.$Message.success(response.data.message);
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
                    this.$Message.error('网络异常');
                });
            },
            follow_tel(data) {
                this.tel = data.tel
                this.telrs = data.telrs
                this.showfollow = true
            },
            sheweizhutui() {
                var data = {
                    zhutui: this.customer.zhutui,
                    customer_uuid: this.customer.customer_uuid
                };
                this.$http.post(api_param.apiurl + 'customer/swzt',
                    data,
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.$Message.success(response.data.message);
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
                    this.$Message.error('网络异常');
                });
            }
            ,
            //封盘取消
            FengpanModalCancel () {//修改取消
                this.rFengpan = false;
            },	        //封盘确定
            FengpanModalOk () {
                this.$refs['sqfp'].validate((valid) => {
                    if (valid) {
                        var data = {
                            fengpanyuanyin: this.sqfp.fengpanyuanyin,
                            beizhu: this.sqfp.beizhu,
                            customer_uuid: this.customer.customer_uuid,
                            xuqiubianhao: this.customer.xuqiubianhao
                        };
                        this.$http.post(api_param.apiurl + 'customer/fengpan',
                            data,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.getIndex();
                                this.rFengpan = false;
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
                            this.$Message.error('网络异常');
                        });
                    }
                });
            },
            StatusModalOk () {//修改状态
                var data = {
                    customer_status: this.zhuangtai.biangeng,
                    content: this.zhuangtai.biangengliyou,
                    customer_uuid: this.customer.customer_uuid,
                    customer_sn: this.customer.xuqiubianhao
                };
                this.$refs['zhuangtai'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'customer/setstatus',
                            data,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.rChange = false;
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
                            this.$Message.error('网络异常');
                        });
                    }
                });

            },
            changeDepart (value, selectedData) {
                this.genjin.bumen = selectedData;
                var d_id = value.pop();
                this.genjin.users = this.peizhi.users[d_id];
            },
            resetok () {
                this.rDeal = false;
                this.getIndex();
            },
            resetModal () {
                this.editRoom = false;
                this.getIndex();
            },
            resetok1 () {
                this.rDaikan = false;
                this.$refs.mydaikan.getIndex();
            }, setPublishTime (date) {
                this.genjin.tixingshijian = date;
                this.genjin.tixingshijian_x=[date];
                console.log(this.genjin.tixingshijian_x)
            }, edit_customer () {
                if (this.customer.dts_id && this.customer.rn_id) {
                    this.formValidate.xuqiuquyu = [this.customer.dts_id, this.customer.rn_id];
                    let dts = this.customer.rn_id;
                    this.$http.get(api_param.apiurl + 'village/getvillage', {
                        params: {
                            dts_id: dts
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                        if (response.data.code == 200) {// 这里是处理正确的回调
                            this.formValidate.village_list = response.data.data.list;
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
                if (this.customer.dts_id && !this.customer.rn_id) {
                    this.formValidate.xuqiuquyu = [this.customer.dts_id];
                }

                this.formValidate.yongtu = this.customer.yongtu;
                this.formValidate.village = this.customer.village;
                this.formValidate.customer_name = this.customer.customer_name;
                this.formValidate.customer_sex = this.customer.customer_sex;
                this.formValidate.xuqiuyuanying = this.customer.xuqiuyuanying;
                this.formValidate.fangwuleixing = this.customer.fangwuleixing;
                this.formValidate.goutongjieduan = this.customer.goutongjieduan;
                this.formValidate.kehulaiyuan = this.customer.kehulaiyuan;
                this.formValidate.guoji = this.customer.guoji;
                this.formValidate.minzu = this.customer.minzu;
                this.formValidate.mark = this.customer.mark;
                this.formValidate.core_mark = this.customer.core_mark;
                this.formValidate.zhengjianhaoma = this.customer.zhengjianhaoma;
                this.formValidate.email = this.customer.email;
                this.formValidate.customer_uuid = this.customer.customer_uuid;
                this.formValidate.qq = this.customer.qq;
                this.formValidate.weixin = this.customer.weixin;
                this.formValidate.jiaotonggongju = this.customer.jiaotonggongju;
                this.formValidate.chexing = this.customer.chexing;
                this.formValidate.xuqiuhuxing = this.customer.xuqiuhuxing_min + ';' + this.customer.xuqiuhuxing_max;
                this.formValidate.xuqiujiage = this.customer.xuqiujiage_min + ';' + this.customer.xuqiujiage_max;
                this.formValidate.xuqiumianji = this.customer.xuqiumianji_min + ';' + this.customer.xuqiumianji_max;
                this.formValidate.xuqiulouceng = this.customer.xuqiulouceng_min + ';' + this.customer.xuqiulouceng_max;
                this.formValidate.xuqiufangling = this.customer.xuqiufangling_min + ';' + this.customer.xuqiufangling_max;
                this.formValidate.xuqiuhuxing_min=this.customer.xuqiuhuxing_min;
                this.formValidate.xuqiuhuxing_max=this.customer.xuqiuhuxing_max;
                this.formValidate.xuqiujiage_min=this.customer.xuqiujiage_min;
                this.formValidate.xuqiujiage_max=this.customer.xuqiujiage_max;
                this.formValidate.xuqiumianji_min=this.customer.xuqiumianji_min;
                this.formValidate.xuqiumianji_max=this.customer.xuqiumianji_max;
                this.formValidate.xuqiulouceng_min=this.customer.xuqiulouceng_min;
                this.formValidate.xuqiulouceng_max=this.customer.xuqiulouceng_max;
                this.formValidate.xuqiufangling_min=this.customer.xuqiufangling_min;
                this.formValidate.xuqiufangling_max=this.customer.xuqiufangling_max;
                this.formValidate.chaoxiang = this.customer.chaoxiang;
                this.formValidate.zhuangxiu = this.customer.zhuangxiu;
                var arr = this.customer.tiaojianbiaoqian.split(';');
                this.formValidate.tiaojianbiaoqian = arr;
                arr = this.customer.peitao.split(';');
                this.formValidate.peitao = arr;
                arr = this.customer.duoxuanbiaoqian.split(';');
                this.formValidate.duoxuanbiaoqian = arr;
                arr = this.customer.xiaofeilinian.split(';');
                this.formValidate.xiaofeilinian = arr;
                this.editRoom = true;
            },
            //修改信息
            getIndex () {
                this.$http.get(api_param.apiurl + '/customer/getcustomer',
                    {
                        params: {
                            customer_uuid: this.$route.params.customer_uuid,
                            customer_type: this.$route.params.customer_type
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.$set(this.tabskey,'key',0);//tabs标签页
                        this.customer = response.body.data.list;
                        this.zhuangtai.dangqian = this.customer.zhuangtai;
                        this.peizhi = response.body.data.peizhi;
                        this.teltrue=true;
                        if (response.data.data.is_last) {
                            this.last_url = '/#/customerEtails/' + response.data.data.is_last.customer_uuid + '/' + response.data.data.is_last.customer_type;
                        } else {
                            this.last_url = '';
                        }
                        if (response.data.data.is_next) {
                            this.next_url = '/#/customerEtails/' + response.data.data.is_next.customer_uuid + '/' + response.data.data.is_next.customer_type;
                        } else {
                            this.next_url = '';
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
            }, modalOk () {

                var data = {
                    genjinfangshi: this.genjin.genjinfangshi,
                    tixing_uid: this.genjin.user,
                    bumen_id: this.genjin.bumen,
                    tixing_time: this.genjin.tixingshijian,
                    genjinneirong: this.genjin.genjinneirong,
                    customer_uuid: this.$route.params.customer_uuid,
                    tixingneirong: this.genjin.tixingneirong,

                };
                this.$refs['genjin'].validate((valid) => {
                    if (valid) {
                        this.isDisable=true;
                        this.$http.post(api_param.apiurl + '/customer_follow/add',
                            data,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            this.$Message.success(response.data.message);
                            this.getIndex();
                            this.modalCancel();
                        }, function (response) {
                            // 这里是处理错误的回调
                            //console.log(response)
                            this.modalCancel();
                            this.$Message.warning('更新失败');
                        });
                    }
                });
            }, modalCancel () {
                this.showfollow = false;
                this.$refs['genjin'].resetFields();//清空form规则检查
                this.genjin.tixingshijian_x=[];
                this.genjin.bumen='';
                setTimeout(() => {
                    this.isDisable = false;
                }, 1000);
                this.tel = []
                this.telrs = false
                this.$refs.mygenjin.getIndex();
            },
            //买卖成交
            rBuyDeal () {
                this.buyDealtitle = '买卖成交';
                this.rDeal = true;
            },
            //带看
            daiKan () {
                this.buyDaikantitle = '带看';
                this.rDaikan = true;
            }, showfollowgo () {
                this.showfollow = true;
            }
        }, watch: {
            '$route.params.customer_uuid' (to, from) {
                if (this.$route.params.customer_uuid) {
                    this.getIndex();
                    this.$refs.mydaikan.getIndex();
                    this.$refs.mygenjin.getIndex();
                    this.$refs.xiangshikehu.getIndex();
                    this.$refs.fangyuanpipei.getIndex();
                    this.$refs.keyuanrizi.getIndex();
	                this.$refs.pipeicaiji.getIndex();
	                this.$refs.pipeihezuo.getIndex();
                }
            }
        }
    };
</script>
