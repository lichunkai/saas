<style lang="less">
    @import "sale.less";
</style>
<template>
    <Modal v-model="addHouseModal" title="新增房源" width="960" :mask-closable="false">
        <div slot="header">
            <a class="ivu-modal-close" @click="modalCancel" style="display: block!important"><i
                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
            <div class="ivu-modal-header-inner">新增房源</div>
        </div>
        <div slot="footer">
            <Button type="text" size="large" @click="modalCancel">取消</Button>
            <Button type="primary" :disabled="isDisable" size="large" @click="modalOk">确定</Button>
        </div>
        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
            <Row>
                <Row>
                    <Col :lg="24" :md="24" class="formItemClass">
                        <span>基本信息</span>
                    </Col>
                </Row>
                <Col :lg="24" :md="24">
                    <Row>
                        <Col :lg="8" :md="8">
                            <FormItem label="房源片区" ref="dts_name" prop="dts">
                                <Cascader :data="settings.dts" v-model="formValidate.dts" filterable
                                          @on-change="changeDts"></Cascader>
                            </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                            <FormItem label="房源小区" ref="village_name" prop="village">
                                <Select placeholder="请选择"  :transfer="true"
                                        v-model="formValidate.village" @on-change="changeVillage">
                                    <Option v-for="item in village" :value="item.village_id" :key="item.village_name">{{
                                        item.village_name }}
                                    </Option>
                                </Select>
                            </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                            <Row class="formLoudongRow">
                                <Col :lg="6" :md="6" class="formLoudong">
                                    房源门牌
                                </Col>
                                <Col :lg="6" :md="6">
                                    <FormItem prop="loudong_name" ref="loudong_name">
                                        <Select placeholder="座栋" :filterable="true" :transfer="true" label-in-value
                                                v-model="formValidate.loudong_id" @on-change="changeLoudong">
                                            <Option v-for="item in loudongList" :value="item.bu_id" :key="item.bu_id">{{
                                                item.label }}
                                            </Option>
                                        </Select>
                                    </FormItem>
                                </Col>
                                <Col :lg="6" :md="6">
                                    <FormItem prop="danyuan_name" ref="danyuan_name">
                                        <Select placeholder="单元" :filterable="true" :transfer="true" label-in-value
                                                v-model="formValidate.danyuan_id" @on-change="changeDanyuan">
                                            <Option v-for="item in danyuanList" :value="item.el_id" :key="item.name">{{
                                                item.label }}
                                            </Option>
                                        </Select>
                                    </FormItem>
                                </Col>
                                <Col :lg="6" :md="6">
                                    <FormItem prop="fanghao_name" ref="fanghao_name">
                                        <Select placeholder="房号" :filterable="true" :transfer="true"
                                                v-model="formValidate.fanghao_name">
                                            <Option v-for="item in fanghaoList" :value="item.label" :key="item.name">{{
                                                item.label }}
                                            </Option>
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
                            <Row class="formLoudongRow">
                                <Col :lg="6" :md="6" class="formLoudong">
                                    <span style="color: #ff0000;font-size: 16px;margin-right: 4px;">*</span>业主姓名
                                </Col>
                                <Col :lg="10" :md="10">
                                    <FormItem prop="customer_name" ref="customer_name">
                                        <Input placeholder="" v-model="formValidate.customer_name"></Input>
                                    </FormItem>
                                </Col>
                                <Col :lg="8" :md="8">
                                    <FormItem label="" prop="customer_sex" ref="customer_sex">
                                        <Select placeholder="请选择" :transfer="true" v-model="formValidate.customer_sex">
                                            <Option v-for="item in settings.customer_sex" :value="item" :key="item">{{
                                                item }}
                                            </Option>
                                        </Select>
                                    </FormItem>
                                </Col>
                            </Row>
                        </Col>
                        <Col :lg="8" :md="8">
                            <Row class="formLoudongRow">
                                <Col :lg="6" :md="6" class="formLoudong">
                                    <span style="color: #ff0000;font-size: 16px;margin-right: 4px;">*</span>业主电话
                                </Col>
                                <Col :lg="10" :md="10">
                                    <FormItem prop="customer_phone" ref="customer_phone" class="marginRight">
                                        <Input placeholder="" v-model="formValidate.customer_phone"></Input>
                                    </FormItem>
                                </Col>
                                <Col :lg="8" :md="8">
                                    <FormItem prop="customer_type" ref="customer_type">
                                        <Select placeholder="请选择" :transfer="true"
                                                v-model="formValidate.customer_type">
                                            <Option v-for="item in settings.customer_type" :value="item" :key="item">{{
                                                item }}
                                            </Option>
                                        </Select>
                                    </FormItem>
                                </Col>
                            </Row>
                        </Col>
                    </Row>
                </Col>
            </Row>
            <Row>
                <Col :lg="24" :md="24">
                    <FormItem label="房源标题" ref="house_title" prop="house_title">
                        <Input placeholder="" v-model="formValidate.house_title"></Input>
                    </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="24" :md="24" class="formItemClass">
                    <span>房源主要信息</span>
                </Col>
            </Row>
            <Row>
                <Col :lg="8" :md="8">
                    <FormItem label="房源等级" prop="house_level" ref="house_level">
                        <Select placeholder="" :transfer="true" v-model="formValidate.house_level">
                            <Option v-for="(item,index) in settings.fydj" :value="item" :key="index">{{item}}</Option>
                        </Select>
                    </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                    <FormItem label="房源售价" prop="sell_price" ref="sell_price">
                        <Input placeholder="" v-model="formValidate.sell_price">
                            <span slot="append">万元</span></Input>
                    </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                    <FormItem label="房源净价" prop="low_sell_price" ref="low_sell_price">
                        <Input placeholder="" v-model="formValidate.low_sell_price"><span
                                slot="append">万元</span></Input>
                    </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="8" :md="8">
                    <FormItem label="是否出佣" prop="chuyong" ref="chuyong">
                        <Select placeholder="" :transfer="true" v-model="formValidate.chuyong">
                            <Option value="1">是</Option>
                            <Option value="0">否</Option>
                        </Select>
                    </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                    <FormItem label="付款方式" prop="fukuanfangshi" ref="fukuanfangshi">
                        <Select placeholder="" :transfer="true" v-model="formValidate.fukuanfangshi">
                            <Option v-for="item in settings.fukuanfangshi" :value="item" :key="item">{{ item }}</Option>
                        </Select>
                    </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                    <FormItem label="使用面积" prop="jianzhumianji" ref="jianzhumianji">
                        <Input placeholder="" v-model="formValidate.jianzhumianji"><span
                                slot="append">平方</span></Input>
                    </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="8" :md="8">
                    <FormItem label="产证面积" prop="shiyongmianji" ref="shiyongmianji">
                        <Input placeholder="" v-model="formValidate.shiyongmianji"><span
                                slot="append">平方</span></Input>
                    </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                    <Row class="formLoudongRow">
                        <Col :lg="6" :md="6" class="formLoudong">
                            <span style="color: #ff0000;font-size: 16px;margin-right: 4px;opacity: 0;">*</span>房源楼层
                        </Col>
                        <Col :lg="8" :md="8">
                            <FormItem prop="louceng_now" ref="louceng_now">
                                <Input placeholder="" v-model="formValidate.louceng_now"><span
                                        slot="append">楼</span></Input>
                            </FormItem>
                        </Col>
                        <Col :lg="2" :md="2">
                            <span style="font-size: 20px">/</span>
                        </Col>
                        <Col :lg="8" :md="8">
                            <FormItem prop="louceng_total" ref="louceng_total">
                                <Input placeholder="" v-model="formValidate.louceng_total"><span
                                        slot="append">楼</span></Input>
                            </FormItem>
                        </Col>
                    </Row>
                </Col>
                <Col :lg="8" :md="8">
                    <FormItem label="房源朝向" prop="chaoxiang" ref="chaoxiang">
                        <Select placeholder="" :transfer="true" v-model="formValidate.chaoxiang">
                            <Option v-for="item in settings.chaoxiang" :value="item" :key="item">{{ item }}</Option>
                        </Select>
                    </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="16" :md="16">
                    <Row class="formLoudongRow">
                        <Col :lg="4" :md="4" class="formLoudong">
                            房源户型
                        </Col>
                        <Col :lg="4" :md="4">
                            <FormItem prop="huxing_shi" ref="huxing_shi">
                                <Input placeholder="室" v-model="formValidate.huxing_shi"></Input>
                            </FormItem>
                        </Col>
                        <Col :lg="4" :md="4">
                            <FormItem prop="huxing_ting" ref="huxing_ting">
                                <Input placeholder="厅" v-model="formValidate.huxing_ting"></Input>
                            </FormItem>
                        </Col>
                        <Col :lg="4" :md="4">
                            <FormItem prop="huxing_wei" ref="huxing_wei">
                                <Input placeholder="卫" v-model="formValidate.huxing_wei"></Input>
                            </FormItem>
                        </Col>
                        <Col :lg="4" :md="4">
                            <FormItem prop="huxing_chu" ref="huxing_chu">
                                <Input placeholder="厨" v-model="formValidate.huxing_chu"></Input>
                            </FormItem>
                        </Col>
                        <Col :lg="4" :md="4">
                            <FormItem prop="huxing_yangtai" ref="huxing_yangtai">
                                <Input placeholder="阳台" v-model="formValidate.huxing_yangtai"></Input>
                            </FormItem>
                        </Col>
                    </Row>
                </Col>
                <Col :lg="8" :md="8">
                    <FormItem label="所属学区" prop="house_school" ref="house_school">
                        <Input placeholder="所属学区" v-model="formValidate.house_school"></Input>
                    </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="16" :md="16">
                    <FormItem label="房源配套" prop="peitao" ref="peitao">
                        <Select v-model="formValidate.peitao" multiple>
                            <Option v-for="item in settings.peitao" :value="item" :key="item">{{ item }}</Option>
                        </Select>
                    </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                    <FormItem label="房源现状" prop="xianzhuang" ref="xianzhuang">
                        <Select v-model="formValidate.xianzhuang" :transfer="true">
                            <Option v-for="item in settings.xianzhuang" :value="item" :key="item">{{ item }}</Option>
                        </Select>
                    </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="8" :md="8">
                    <FormItem label="房屋类型" prop="fangwuleixing" ref="fangwuleixing" :transfer="true">
                        <Select placeholder="" :transfer="true"
                                v-model="formValidate.fangwuleixing">
                            <Option v-for="item in settings.fangwuleixing" :value="item" :key="item">{{ item }}</Option>
                        </Select>
                    </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                    <FormItem label="建筑结构" prop="jianzhujiegou" ref="jianzhujiegou" :transfer="true">
                        <Select placeholder="" :transfer="true"
                                v-model="formValidate.jianzhujiegou">
                            <Option v-for="item in settings.jianzhujiegou" :value="item" :key="item">{{ item }}</Option>
                        </Select>
                    </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                    <FormItem label="建筑年代" prop="jianzaoniandai" ref="jianzaoniandai" :transfer="true">
                        <!--<Input placeholder="" v-model="formValidate.jianzaoniandai"><span-->
                                <!--slot="append">年</span></Input>-->
                        <DatePicker type="year" placeholder="选择日期" v-model="formValidate.jianzaoniandai" @on-change="formValidate.jianzaoniandai=$event"
                                    :transfer="true" style="width: 100%;"></DatePicker>
                    </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="8" :md="8">
                    <FormItem label="产权性质" prop="chanquanxingzhi" ref="chanquanxingzhi">
                        <Select placeholder="" :transfer="true"
                                v-model="formValidate.chanquanxingzhi">
                            <Option v-for="item in settings.chanquanxingzhi" :value="item" :key="item">{{ item }}
                            </Option>
                        </Select>
                    </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                    <FormItem label="产证日期" prop="chanzhengriqi" ref="chanzhengriqi">
                        <DatePicker type="date" placeholder="选择日期" :value="formValidate.chanzhengriqi"
                                    format="yyyy-MM-dd" @on-change="formValidate.chanzhengriqi=$event"
                                    :transfer="true" style="width: 100%;"></DatePicker>
                    </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                    <FormItem label="产权年限" prop="chanquannianxian" ref="chanquannianxian">
                        <Select placeholder="" :transfer="true"
                                v-model="formValidate.chanquannianxian">
                            <Option v-for="item in settings.chanquannianxian" :value="item" :key="item">{{ item }}
                            </Option>
                        </Select>
                    </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="16" :md="16">
                    <FormItem label="推荐标签" prop="house_tuijian_tag" ref="house_tuijian_tag">
                        <Select v-model="formValidate.house_tuijian_tag" multiple>
                            <Option v-for="item in settings.house_tuijian_tag" :value="item" :key="item">{{ item }}
                            </Option>
                        </Select>
                    </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                    <FormItem label="房屋装修" prop="zhuangxiu" ref="zhuangxiu">
                        <Select placeholder="" :transfer="true" v-model="formValidate.zhuangxiu">
                            <Option v-for="item in settings.zhuangxiu" :value="item" :key="item">{{ item }}</Option>
                        </Select>
                    </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="8" :md="8">
                    <FormItem label="看房方式" prop="kanfangfangshi" ref="kanfangfangshi">
                        <Select placeholder="" :transfer="true" v-model="formValidate.kanfangfangshi">
                            <Option v-for="item in settings.kanfangfangshi" :value="item" :key="item">{{ item }}
                            </Option>
                        </Select>
                    </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                    <FormItem label="房源来源" prop="laiyuan" ref="laiyuan">
                        <Select placeholder="" :transfer="true" v-model="formValidate.laiyuan">
                            <Option v-for="item in settings.laiyuan" :value="item" :key="item">{{ item }}</Option>
                        </Select>
                    </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="24" :md="24">
                    <FormItem label="标签选择" prop="house_tag" ref="house_tag">
                        <CheckboxGroup v-model="formValidate.house_tag" @on-chang="changeHousetag">
                            <Checkbox v-for="item in settings.chushoutag" :label="item"></Checkbox>
                        </CheckboxGroup>
                    </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="24" :md="24">
                    <FormItem label="备注说明" prop="mark">
                        <Input type="textarea" placeholder="" v-model="formValidate.mark"></Input>
                    </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="24" :md="24" class="formItemClass">
                    <span>图片信息</span>
                </Col>
            </Row>
            <Row>
                <Col :lg="4" :md="4" style="text-align: center">
                    <div class="demo-upload-list" v-for="item in uploadYibanList">
                        <template>
                            <img :src="item.url">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-eye-outline" @click.native="handleYibanView(item.name)"></Icon>
                                <Icon type="ios-trash-outline" @click.native="handleYibanRemove(item)"></Icon>
                            </div>
                        </template>
                    </div>
                    <Upload ref="uploadYiban" :show-upload-list="false" :default-file-list="defaultList" v-if="uploadYibanList.length<1"
                            :headers="{'X-Access-Token':xtoken}"
                            :on-success="handleYibanSuccess" :format="['jpg','jpeg','png']" :max-size="10240"
                            :on-format-error="handleYibanFormatError"
                            :on-exceeded-size="handleYibanMaxSize" :before-upload="handleYibanBeforeUpload" multiple
                            type="drag" :action="uploadurl" style="display: inline-block;width:120px;">
                        <div style="width: 120px;height:120px;line-height: 120px;">
                            <Icon type="camera" size="30"></Icon>
                        </div>
                    </Upload>
                    <div style="margin-top: 5px">一般委托书</div>
                </Col>
                <Col :lg="4" :md="4" style="text-align: center">
                    <div class="demo-upload-list" v-for="item in uploadDujiaList">
                        <template>
                            <img :src="item.url">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-eye-outline" @click.native="handleDujiaView(item.name)"></Icon>
                                <Icon type="ios-trash-outline" @click.native="handleDujiaRemove(item)"></Icon>
                            </div>
                        </template>
                    </div>
                    <Upload ref="uploadDujia" :show-upload-list="false" :default-file-list="defaultList" v-if="uploadDujiaList.length<1"
                            :headers="{'X-Access-Token':xtoken}"
                            :on-success="handleDujiaSuccess" :format="['jpg','jpeg','png']" :max-size="10240"
                            :on-format-error="handleDujiaFormatError"
                            :on-exceeded-size="handleDujiaMaxSize" :before-upload="handleDujiaBeforeUpload" multiple
                            type="drag" :action="uploadurl" style="display: inline-block;width:120px;">
                        <div style="width: 120px;height:120px;line-height: 120px;">
                            <Icon type="camera" size="30"></Icon>
                        </div>
                    </Upload>
                    <div style="margin-top: 5px">独家委托书</div>
                </Col>
                <Col :lg="7" :md="7" v-if="uploadDujiaList.length==1">
                    <FormItem label="独家委托书期限" prop="weituoqujian" ref="weituoqujian">
                        <DatePicker type="daterange" placeholder="选择日期区间" :value="formValidate.weituoqujian"
                                    format="yyyy-MM-dd" @on-change="formValidate.weituoqujian=$event"
                                    :transfer="true"></DatePicker>
                    </FormItem>
                    <FormItem label="签赔金" prop="qianpei" ref="qianpei">
                        <Input placeholder="请填写签赔金" v-model="formValidate.qianpei"></Input>
                    </FormItem>
                </Col>
                <Col :lg="4" :md="4" style="text-align: center" v-if="uploadDujiaList.length==1">
                    <div class="demo-upload-list" v-for="item in uploadqpList">
                        <template>
                            <img :src="item.url">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-eye-outline" @click.native="handleqpView(item.name)"></Icon>
                                <Icon type="ios-trash-outline" @click.native="handleqpRemove(item)"></Icon>
                            </div>
                        </template>
                    </div>
                    <Upload ref="uploadqp" :show-upload-list="false" :default-file-list="defaultList" v-if="uploadqpList.length<1"
                            :headers="{'X-Access-Token':xtoken}"
                            :on-success="handleqpSuccess" :format="['jpg','jpeg','png']" :max-size="10240"
                            :on-format-error="handleqpFormatError"
                            :on-exceeded-size="handleqpMaxSize" :before-upload="handleqpBeforeUpload" multiple
                            type="drag" :action="uploadurl" style="display: inline-block;width:120px;">
                        <div style="width: 120px;height:120px;line-height: 120px;">
                            <Icon type="camera" size="30"></Icon>
                        </div>
                    </Upload>
                    <div style="margin-top: 5px">签赔凭证</div>
                </Col>
                <Modal title="图片预览" v-model="visible" :transfer="false">
                    <div slot="footer"></div>
                    <img :src="imgurl + imgName" v-if="visible" style="width: 100%">
                </Modal>
            </Row>
        </Form>
    </Modal>
</template>
<script>
    export default {
        name: 'secondSaleAdd',
        props: ['addHouseModal', 'settings', 'formValidate'],
        data() {
            const validateDts = (rule, value, callback) => {//验证片区必填
                if (!value || value == undefined) {
                    callback('请选择片区!');
                }
                else if (value.length < 2) {
                    callback('请选择正确的片区!');
                }
                callback([]);
            };
            const validatePhone = (rule, value, callback) => {
                var errors = [];
                if (!/^(13[0-9]|14[5-9]|15[012356789]|166|17[0-8]|18[0-9]|19[8-9])[0-9]{8}$/.test(value)) {
                    callback('请输入正确的手机号码!');
                }
                callback(errors);
            };
            const validateLoudong = (rule, value, callback) => {
                var errors = [];
                if (!value) {
                    callback('填写楼栋');
                }
                if (!/^[a-zA-Z0-9]+$/.test(value)) {
                    callback("楼栋为数字或英文");
                }
                callback([]);
            };
            const validateDanyuan = (rule, value, callback) => {
                var errors = [];
                if (!value) {
                    callback('填写单元');
                }
                if (!/^[a-zA-Z0-9]+$/.test(value)) {
                    callback("单元为数字或英文");
                }
                callback([]);
            };
            const validateFanghao = (rule, value, callback) => {
                var errors = [];
                if (!value) {
                    callback('填写房号');
                }
                if (!/^[a-zA-Z0-9]+$/.test(value)) {
                    callback("房号为数字或英文");
                }
                callback([]);
            };
            const validateNumber = (rule, value, callback) => {
                var errors = [];
                if (!value) {
                    callback('请输入数字');
                }
                if (!/^\d+(?=\.{0,1}\d+$|$)/.test(value)) {
                    callback('请输入正确的数字');
                }
                callback(errors);
            };
            return {
                isDisable: false,
                house_make_value: [],
                village: [],
                //绑定用的数据
                data: [],
                loudongList:[],
                danyuanList:[],
                fanghaoList:[],
                ruleValidate: {
                    dts: [{required: true, validator: validateDts, trigger: 'change'}],
                    village: [{required: true, message: '请选择小区', trigger: 'change'}],
                    loudong_id: [{required: true, validator: validateLoudong, trigger: 'change'}],
                    danyuan_id: [{required: true, validator: validateDanyuan, trigger: 'change'}],
                    fanghao_name: [{required: true, validator: validateFanghao, trigger: 'change'}],
                    customer_name: [{required: true, message: '请填写姓名', trigger: 'blur'}],
                    customer_sex: [{required: true, message: '请选择性别', trigger: 'change'}],
                    customer_phone: [{required: true, validator: validatePhone, trigger: 'blur'}],
                    customer_type: [{required: true, message: '请选择类别', trigger: 'change'}],
                    house_title: [{required: true, message: '请填写房源标题', trigger: 'blur'}],
                    house_level: [{required: true, message: '请选择房源等级', trigger: 'change'}],
                    sell_price: [{required: true, validator: validateNumber, trigger: 'blur'}],
                    low_sell_price: [{required: true, validator: validateNumber, trigger: 'blur'}],
                    chuyong: [{required: true, message: '请选择是否出佣', trigger: 'change'}],
                    house_school: [{required: true, message: '请填写所属学区', trigger: 'blur'}],
                    //fukuanfangshi: [{required: true, message: '请选择付款方式', trigger: 'change'}],
                    shiyongmianji: [{required: true, validator: validateNumber, trigger: 'blur'}],
                    jianzhumianji: [{required: true, validator: validateNumber, trigger: 'blur'}],
                    louceng_now: [{required: true, validator: validateNumber, trigger: 'blur'}],
                    louceng_total: [{required: true, validator: validateNumber, trigger: 'blur'}],
                    chaoxiang: [{required: true, message: '请选择朝向', trigger: 'change'}],
                    huxing_shi: [{required: true, validator: validateNumber, trigger: 'blur'}],
                    huxing_ting: [{required: true, validator: validateNumber, trigger: 'blur'}],
                    huxing_wei: [{required: true, validator: validateNumber, trigger: 'blur'}],
                    huxing_chu: [{required: true, validator: validateNumber, trigger: 'blur'}],
                    huxing_yangtai: [{required: true, validator: validateNumber, trigger: 'blur'}],
                    house_tuijian_tag: [{required: true, type: 'array', message: '请选择标签', trigger: 'change'}],
                    peitao: [{required: true, type: 'array', message: '请选择配套', trigger: 'change'}],
                    xianzhuang: [{required: true, message: '请选择现状', trigger: 'change'}],
                    fangwuleixing: [{required: true, message: '请选择房屋类型', trigger: 'change'}],
                    jianzhujiegou: [{required: true, message: '请选择建筑结构', trigger: 'change'}],
                    jianzaoniandai: [{required: true, message: '请选择建筑年代', trigger: 'change'}],
                    chanquanxingzhi: [{required: true, message: '请选择产权性质', trigger: 'change'}],
                    chanzhengriqi: [{required: true, message: '请选择产证日期', trigger: 'change'}],
                    chanquannianxian: [{required: true, message: '请选择产权年限', trigger: 'change'}],
                    zhuangxiu: [{required: true, message: '请选择房屋装修', trigger: 'change'}],
                    kanfangfangshi: [{required: true, message: '请选择看房方式', trigger: 'change'}],
                    laiyuan: [{required: true, message: '请选择来源', trigger: 'change'}],
                    house_tag: [{required: true, type: 'array', message: '请选择房源标签', trigger: 'change'}],
                    // mark: [{required: true, message: '请填写备注说明', trigger: 'blur'}]
                },
                // 图片上传
                xtoken: api_param.XAccessToken,
                uploadurl: api_param.apiurl + 'site/upload',
                imgurl: api_param.imgurl,
                defaultList: [],
                imgName: '',
                visible: false,
                uploadYibanList: [],
                uploadDujiaList: [],
                uploadqpList:[]
            };
        },
        methods: {
            setMakes() {//设置自定义参数
                for (let i in this.settings.sell_house_validata) {
                    if (this.ruleValidate.hasOwnProperty(this.settings.sell_house_validata[i])) {
                        this.$set(this.ruleValidate, this.settings.sell_house_validata[i], []);
                    }
                    this.$nextTick(function () {
                        if (this.$refs[this.settings.sell_house_validata[i]] && this.$refs[this.settings.sell_house_validata[i]].hasOwnProperty('isRequired')) {
                            this.$refs[this.settings.sell_house_validata[i]].isRequired = false;
                        }
                    })
                }
            },
            changeVillage (value) {
                this.formValidate.village_id = value;
                for (let i in this.village) {
                    if (this.village[i].village_id == value) {
                        this.formValidate.village_name = this.village[i].village_name;
                    }
                }
                this.$http.get(api_param.apiurl + 'building/choosebuilding', {
                    params: {id: this.formValidate.village_id},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调
                        this.loudongList = response.data.data.list;
                        this.formValidate.house_school = '';
                        if(response.data.data.school){
                            this.formValidate.house_school = response.data.data.school;
                        }

                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        //this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    //this.$Message.error('你的网络开小差了^—^');
                })
            },
            changeLoudong(selectdata){
                this.formValidate.loudong_name = selectdata.label;
                this.$http.get(api_param.apiurl + 'building/chooseelement', {
                    params: {id: selectdata.value},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调
                        this.danyuanList = response.data.data.list;
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        //this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    //this.$Message.error('你的网络开小差了^—^');
                })
            },
            changeDanyuan(selectdata){
                this.formValidate.danyuan_name = selectdata.label;
                this.$http.get(api_param.apiurl + 'building/choosetower', {
                    params: {id: selectdata.value},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调
                        this.fanghaoList = response.data.data.list;
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        //this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    //this.$Message.error('你的网络开小差了^—^');
                })
            },
            changeHouseMakes(index, lable, sort) {
                this.$set(this.formValidate.house_make, index, {
                    'value': this.house_make_value[index],
                    'lable': lable,
                    'sort': sort
                })
            },
            changeHousetag(data){
            },
            modalOk() {
                this.formValidate.house_status = 1;
                this.formValidate.sale_type = 2;
                this.formValidate.house_private = 1;
                if(this.uploadYibanList.length == 1){
                    this.formValidate.yiban_image = this.uploadYibanList[0]['name']
                }
                if(this.uploadDujiaList.length == 1){
                    this.formValidate.dujia_image = this.uploadDujiaList[0]['name'];
                }
                if(this.uploadqpList.length == 1){
                    this.formValidate.qianpei_img = this.uploadqpList[0]['name'];
                }
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        this.isDisable = true
                        setTimeout(() => {
                            this.isDisable = false
                        }, 1000)

                        this.$http.post(api_param.apiurl + 'house/add',
                            this.formValidate,
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.isDisable = false
                                this.formValidate.dts = [];
                                this.formValidate.village = '';
                                this.formValidate.loudong_id = '';
                                this.formValidate.loudong_name = '';
                                this.formValidate.danyuan_id = '';
                                this.formValidate.danyuan_name = '';
                                this.uploadYibanList = [];
                                this.uploadDujiaList = [];
                                this.uploadqpList = [];
                                this.village = [];
                                this.$refs['formValidate'].resetFields();
                                this.addHouseModal = false;
                                this.$Message.success('添加成功');
                                this.$emit('getIndex');
                            } else if (response.data.code == 202) {
                                this.$Message.warning(response.data.message);
                                this.isDisable = false
                                this.formValidate.dts = [];
                                this.formValidate.village = '';
                                this.formValidate.loudong_id = '';
                                this.formValidate.loudong_name = '';
                                this.formValidate.danyuan_id = '';
                                this.formValidate.danyuan_name = '';
                                this.uploadYibanList = [];
                                this.uploadDujiaList = [];
                                this.uploadqpList = [];
                                this.village = [];
                                this.$refs['formValidate'].resetFields();
                                this.addHouseModal = false;
                                let argu = {
                                    houseId: response.data.data.house_uuid,
                                    saleType: 2
                                };
                                this.$router.push({
                                    name: 'roomDetails',
                                    params: argu
                                });
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
                        })
                    }
                });
            },
            modalCancel() {
                this.$emit('resetModal');
                this.formValidate.dts = [];
                this.formValidate.village = '';
                this.formValidate.loudong_id = '';
                this.formValidate.loudong_name = '';
                this.formValidate.danyuan_id = '';
                this.formValidate.danyuan_name = '';
                this.uploadYibanList = [];
                this.uploadDujiaList = [];
                this.uploadqpList = [];
                this.village = [];
                this.$refs['formValidate'].resetFields();
                this.addHouseModal = false;
            },
            changeDts(value, selectedData) {
                let dts = selectedData[1].value;
                this.formValidate.dts_id = selectedData[1].value;
                this.formValidate.dts_name = selectedData[1].label;
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
                    //this.$Message.error('你的网络开小差了^—^');
                })
            },
            // 一般委托书图片上传
            handleYibanView(name) {
                this.imgName = name;
                this.visible = true;
            },
            handleYibanRemove(file) {
                this.uploadYibanList = [];
            },
            handleYibanSuccess(res, file) {
                file.url = api_param.imgurl + res.data.url;
                file.name = res.data.url;
                this.uploadYibanList.push({'name': file.name, 'url': file.url});
                const fileList = this.$refs.uploadYiban.fileList;
                this.$refs.uploadYiban.fileList.splice(fileList.indexOf(file), 1);
            },
            handleYibanFormatError(file) {
                this.$Message.warning('文件格式不正确，请选择JPG或PNG');
            },
            handleYibanMaxSize(file) {
                this.$Message.warning('文件  ' + file.name + ' 超过 10M.');
            },
            handleYibanBeforeUpload() {
                const check = this.uploadYibanList.length < 1;
                if (!check) {
                    this.$Message.warning('最多上传1张图片');
                }
                return check;
            },
            // 独家委托书图片上传
            handleDujiaView(name) {
                this.imgName = name;
                this.visible = true;
            },
            handleDujiaRemove(file) {
                this.uploadDujiaList = [];
            },
            handleDujiaSuccess(res, file) {
                file.url = api_param.imgurl + res.data.url;
                file.name = res.data.url;
                this.uploadDujiaList.push({'name': file.name, 'url': file.url});
                const fileList = this.$refs.uploadDujia.fileList;
                this.$refs.uploadDujia.fileList.splice(fileList.indexOf(file), 1);
            },
            handleDujiaFormatError(file) {
                this.$Message.warning('文件格式不正确，请选择JPG或PNG');
            },
            handleDujiaMaxSize(file) {
                this.$Message.warning('文件  ' + file.name + ' 超过 10M.');
            },
            handleDujiaBeforeUpload() {
                const check = this.uploadDujiaList.length < 1;
                if (!check) {
                    this.$Message.warning('最多上传1张图片');
                }
                return check;
            },
            //签赔
            handleqpView(name) {
                this.imgName = name;
                this.visible = true;
            },
            handleqpRemove(file) {
                this.uploadqpList = [];
            },
            handleqpSuccess(res, file) {
                file.url = api_param.imgurl + res.data.url;
                file.name = res.data.url;
                this.uploadqpList.push({'name': file.name, 'url': file.url});
                const fileList = this.$refs.uploadqp.fileList;
                this.$refs.uploadqp.fileList.splice(fileList.indexOf(file), 1);
            },
            handleqpFormatError(file) {
                this.$Message.warning('文件格式不正确，请选择JPG或PNG');
            },
            handleqpMaxSize(file) {
                this.$Message.warning('文件  ' + file.name + ' 超过 10M.');
            },
            handleqpBeforeUpload() {
                const check = this.uploadqpList.length < 1;
                if (!check) {
                    this.$Message.warning('最多上传1张图片');
                }
                return check;
            },
        }
    };
</script>
<style>
    .demo-upload-list {
        display: inline-block;
        width: 120px;
        height: 120px;
        text-align: center;
        line-height: 120px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .2);
        margin-right: 4px;
    }

    .demo-upload-list img {
        width: 100%;
        height: 100%;
    }

    .demo-upload-list-cover {
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, .6);
    }

    .demo-upload-list:hover .demo-upload-list-cover {
        display: block;
    }

    .demo-upload-list-cover i {
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
</style>
