<style lang="less">
    @import "sale.less";
</style>
<template>
    <Modal v-model="editRoom" :title="roommodaltitle" width="960" :mask-closable="false">
        <div slot="header">
            <a class="ivu-modal-close" @click="modalCancel" style="display: block!important">
                <i class="ivu-icon ivu-icon-ios-close-empty"></i>
            </a>
            <div class="ivu-modal-header-inner">{{roommodaltitle}}</div>
        </div>
        <div slot="footer">
            <Button type="text" size="large" @click="modalCancel">取消</Button>
            <Button type="primary" size="large" @click="modalOk">确定</Button>
        </div>
        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
            <Row>
                <Col :lg="24" :md="24" class="formItemClass">
                    <span>基本信息</span>
                </Col>
            </Row>

            <Row>
                <!--<Col :lg="6" :md="6">-->
                <!--<FormItem label="片区" ref="dts_name" prop="dts">-->
                    <!--<Cascader :data="settings.dts" v-model="formValidate.dts" filterable @on-change="changeDts"></Cascader>-->
                <!--</FormItem>-->
                <!--</Col>-->
                <!--<Col :lg="6" :md="6">-->
                <!--<FormItem label="小区" ref="village_name" prop="village">-->
                    <!--<Select placeholder="请选择" :filterable="true"  :transfer="true" v-model="formValidate.village" @on-change="changeVillage">-->
                        <!--<Option v-for="item in village" :value="item.village_id" :key="item.village_name">{{ item.village_name }}</Option>-->
                    <!--</Select>-->
                <!--</FormItem>-->
                <!--</Col>-->
                <!--<Col :lg="6" :md="6">-->
                <!--<Row class="formLoudongRow">-->
                    <!--<Col :lg="6" :md="6" class="formLoudong">-->
                    <!--门牌号</Col>-->
                    <!--<Col :lg="6" :md="6">-->
                    <!--<FormItem prop="loudong_name" ref="loudong_name">-->
                        <!--<Input placeholder="座栋" v-model="formValidate.loudong_name"></Input>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                    <!--<Col :lg="6" :md="6">-->
                    <!--<FormItem prop="danyuan_name" ref="danyuan_name">-->
                        <!--<Input placeholder="单元" v-model="formValidate.danyuan_name"></Input>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                    <!--<Col :lg="6" :md="6">-->
                    <!--<FormItem prop="fanghao_name" ref="fanghao_name">-->
                        <!--<Input placeholder="房号" v-model="formValidate.fanghao_name"></Input>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                <!--</Row>-->
                <!--</Col>-->
            </Row>
            <Row>
                <Col :lg="16" :md="16">
                    <FormItem label="房源标题" ref="house_title" prop="house_title">
                        <Input placeholder="" v-model="formValidate.house_title"></Input>
                    </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                    <Row class="formLoudongRow">
                        <Col :lg="4" :md="4" class="formLoudong">
                            业主姓名</Col>
                        <Col :lg="10" :md="10">
                            <FormItem prop="customer_name" ref="customer_name">
                                <Input placeholder="" v-model="formValidate.customer_name"></Input>
                            </FormItem>
                        </Col>
                        <Col :lg="10" :md="10">
                            <FormItem label="" prop="customer_sex" ref="customer_sex">
                                <Select placeholder="请选择" :transfer="true" v-model="formValidate.customer_sex">
                                    <Option v-for="item in settings.customer_sex" :value="item" :key="item">{{ item }}</Option>
                                </Select>
                            </FormItem>
                        </Col>
                    </Row>
                </Col>
                <Col :lg="24" :md="24" class="formItemClass">
                    <span>房源主要信息</span>
                </Col>

                <Col :lg="6" :md="6">
                <FormItem label="房源售价" prop="sell_price" ref="sell_price">
                    <Input  placeholder="" v-model="formValidate.sell_price">
                    <span slot="append">万元</span>
                    </Input>
                </FormItem>
                </Col>

                <Col :lg="6" :md="6">
                <FormItem label="付款方式" prop="fukuanfangshi" ref="fukuanfangshi">
                    <Select placeholder="" :transfer="true" v-model="formValidate.fukuanfangshi">
                        <Option v-for="item in settings.fukuanfangshi" :value="item" :key="item">{{ item }}</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="是否出佣" prop="">
                    <Select placeholder="" :transfer="true" v-model="formValidate.chuyong">
                        <Option value="1">是</Option>
                        <Option value="0">否</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="使用面积" prop="jianzhumianji" ref="jianzhumianji">
                    <Input  placeholder="" v-model="formValidate.jianzhumianji">
                    <span slot="append">平方</span>
                    </Input>
                </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="6" :md="6">
                <FormItem label="产证面积" prop="shiyongmianji" ref="shiyongmianji">
                    <Input  placeholder="" v-model="formValidate.shiyongmianji">
                    <span slot="append">平方</span>
                    </Input>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <Row class="formLoudongRow">
                    <Col :lg="6" :md="6" class="formLoudong">房源楼层</Col>
                    <Col :lg="8" :md="8">
                    <FormItem prop="louceng_now" ref="louceng_now">
                        <Input  placeholder="" v-model="formValidate.louceng_now">
                        <span slot="append">楼</span>
                        </Input>
                    </FormItem>
                    </Col>
                    <Col :lg="2" :md="2">
                    <span style="font-size: 20px">/</span>
                    </Col>
                    <Col :lg="8" :md="8">
                    <FormItem prop="louceng_total" ref="louceng_total">
                        <Input  placeholder="" v-model="formValidate.louceng_total">
                        <span slot="append">楼</span>
                        </Input>
                    </FormItem>
                    </Col>
                </Row>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="房源朝向" prop="chaoxiang" ref="chaoxiang">
                    <Select placeholder="" :transfer="true" v-model="formValidate.chaoxiang">
                        <Option v-for="item in settings.chaoxiang" :value="item" :key="item">{{ item }}</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="房源装修" prop="zhuangxiu" ref="zhuangxiu">
                    <Select placeholder="" :transfer="true" v-model="formValidate.zhuangxiu">
                        <Option v-for="item in settings.zhuangxiu" :value="item" :key="item">{{ item }}</Option>
                    </Select>
                </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="12" :md="12">
                <Row class="formLoudongRow">
                    <Col :lg="4" :md="4" class="formLoudong"> 房源户型
                    </Col>
                    <Col :lg="4" :md="4">
                    <FormItem prop="huxing_shi" ref="huxing_shi">
                        <Input  placeholder="室" v-model.number="formValidate.huxing_shi"></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="4" :md="4">
                    <FormItem prop="huxing_ting" ref="huxing_ting">
                        <Input  placeholder="厅" v-model.number="formValidate.huxing_ting"></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="4" :md="4">
                    <FormItem prop="huxing_wei" ref="huxing_wei">
                        <Input  placeholder="卫" v-model.number="formValidate.huxing_wei"></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="4" :md="4">
                    <FormItem prop="huxing_chu" ref="huxing_chu">
                        <Input  placeholder="厨" v-model.number="formValidate.huxing_chu"></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="4" :md="4">
                    <FormItem prop="huxing_yangtai" ref="huxing_yangtai">
                        <Input  placeholder="阳台" v-model.number="formValidate.huxing_yangtai"></Input>
                    </FormItem>
                    </Col>
                </Row>
                </Col>

                <Col :lg="12" :md="12">
                <FormItem label="推荐标签" prop="house_tuijian_tag" ref="house_tuijian_tag">
                    <Select v-model="formValidate.house_tuijian_tag" multiple>
                        <Option v-for="item in settings.house_tuijian_tag" :value="item" :key="item">{{ item }}</Option>
                    </Select>
                </FormItem>
                </Col>

            </Row>

            <Row>
                <Col :lg="6" :md="6">
                <FormItem label="房源现状" prop="xianzhuang" ref="xianzhuang">
                    <Select v-model="formValidate.xianzhuang" :transfer="true">
                        <Option v-for="item in settings.xianzhuang" :value="item" :key="item">{{ item }}</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                    <FormItem label="所属学区" prop="house_school" ref="house_school" :transfer="true">
                        <Input placeholder="所属学区" v-model="formValidate.house_school"></Input>
                    </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="房屋类型" prop="fangwuleixing" ref="fangwuleixing" :transfer="true">
                    <Select placeholder="" :transfer="true" v-model="formValidate.fangwuleixing">
                        <Option v-for="item in settings.fangwuleixing" :value="item" :key="item">{{ item }}</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="建筑结构" prop="jianzhujiegou" ref="jianzhujiegou" :transfer="true">
                    <Select placeholder="" :transfer="true" v-model="formValidate.jianzhujiegou">
                        <Option v-for="item in settings.jianzhujiegou" :value="item" :key="item">{{ item }}</Option>
                    </Select>
                </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="24" :md="24">
                <Row>
                    <Col :lg="6" :md="6">
                        <FormItem label="建筑年代" prop="jianzaoniandai" ref="jianzaoniandai" :transfer="true">
                            <Input placeholder="" v-model="formValidate.jianzaoniandai">
                                <span slot="append">年</span>
                            </Input>
                        </FormItem>
                    </Col>
                    <Col :lg="6" :md="6">
                    <FormItem label="产权性质" prop="chanquanxingzhi" ref="chanquanxingzhi">
                        <Select placeholder="" :transfer="true" v-model="formValidate.chanquanxingzhi">
                            <Option v-for="item in settings.chanquanxingzhi" :value="item" :key="item">{{ item }}
                            </Option>
                        </Select>
                    </FormItem>
                    </Col>
                    <Col :lg="6" :md="6">
                    <FormItem label="产证日期" prop="chanzhengriqi" ref="chanzhengriqi">
                        <DatePicker type="date" placeholder="选择日期" :value="formValidate.chanzhengriqi" format="yyyy-MM-dd" @on-change="formValidate.chanzhengriqi=$event"></DatePicker>
                    </FormItem>
                    </Col>
                    <Col :lg="6" :md="6">
                    <FormItem label="产权年限" prop="chanquannianxian" ref="chanquannianxian">
                        <Select placeholder="" :transfer="true" v-model="formValidate.chanquannianxian">
                            <Option v-for="item in settings.chanquannianxian" :value="item" :key="item">{{ item }}
                            </Option>
                        </Select>
                    </FormItem>
                    </Col>

                    <Col :lg="24" :md="24">
                        <Row>
                            <Col :lg="6" :md="6">
                                <FormItem label="房源来源" prop="laiyuan" ref="laiyuan">
                                    <Select placeholder="" :transfer="true" v-model="formValidate.laiyuan">
                                        <Option v-for="item in settings.laiyuan" :value="item" :key="item">{{ item }}</Option>
                                    </Select>
                                </FormItem>
                            </Col>
                            <Col :lg="6" :md="6">
                                <FormItem label="看房方式" prop="kanfangfangshi" ref="kanfangfangshi">
                                    <Select placeholder="" :transfer="true" v-model="formValidate.kanfangfangshi">
                                        <Option v-for="item in settings.kanfangfangshi" :value="item" :key="item">{{ item }}</Option>
                                    </Select>
                                </FormItem>
                            </Col>
                            <Col :lg="12" :md="12">
                            <FormItem label="房源配套" prop="peitao" ref="peitao">
                                <Select v-model="formValidate.peitao" multiple>
                                    <Option v-for="item in settings.peitao" :value="item" :key="item">{{ item }}</Option>
                                </Select>
                            </FormItem>
                            </Col>
                        </Row>
                    </Col>
                    <Col :lg="24" :md="24">
                        <FormItem label="标签选择" prop="house_tag" ref="house_tag">
                            <CheckboxGroup v-model="formValidate.house_tag" :transfer="true">
                                <Checkbox v-for="item in settings.chushoutag" :label="item"></Checkbox>
                            </CheckboxGroup>
                        </FormItem>
                    </Col>
                </Row>
                </Col>
            </Row>
            <Row>
                <Col :lg="24" :md="24">
                <FormItem label="备注说明" prop="mark">
                    <Input type="textarea" placeholder="" v-model="formValidate.mark"></Input>
                </FormItem>
                </Col>
            </Row>
        </Form>
    </Modal>
</template>
<script>
    export default {
        name: 'editDetails',
        props: ['editRoom', 'roommodaltitle', 'settings', 'formValidate'],
        data() {

	        const validateDts = (rule, value, callback) => {//验证片区必填
		        if (!value) {
			        callback('请选择片区!');
		        }
		        if (value.length < 2) {
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
                house_make_value: [],
                //				formValidate: {
                //					house_make: [],
                //					house_make_lable: [],
                //					house_tuijian_tag: [],//房源推荐标签
                //					peitao: [],//房源推荐标签
                //				},//绑定用的数据
                data: [],
                ruleValidate: {
	                dts: [{required: true, validator: validateDts, trigger: 'change'}],
	                village: [{required: true, message: '选择小区', trigger: 'change'}],
                    loudong_name: [{
                        required: true,
                        message: '填写楼栋',
                        trigger: 'blur'
                    }],
                    danyuan_name: [{
                        required: true,
                        message: '填写单元',
                        trigger: 'blur'
                    }],
                    customer_name: [{
                        required: true,
                        message: '填写姓名',
                        trigger: 'blur'
                    }],
                    customer_sex: [{
                        required: true,
                        message: '选择性别',
                        trigger: 'change'
                    }],
                    customer_phone: [{
                        required: true,
                        validator: validatePhone,
                        trigger: 'blur'
                    }],
                    customer_type: [{
                        required: true,
                        message: '请选择类别',
                        trigger: 'change'
                    }],
                    fanghao_name: [{
                        required: true,
                        message: '填写房号',
                        trigger: 'blur'
                    }],
                    house_title: [{
                        required: true,
                        message: '请填写房源标题',
                        trigger: 'blur'
                    }],
                    jianzhumianji: [{
                        required: true,
                        validator: validateNumber,
                        trigger: 'blur'
                    }],
                    sell_price: [{
                        required: true,
                        validator: validateNumber,
                        trigger: 'blur'
                    }],
                    shiyongmianji: [{
                        required: true,
                        validator: validateNumber,
                        trigger: 'blur'
                    }],
                    louceng_now: [{
                        required: true,
                        validator: validateNumber,
                        trigger: 'blur'
                    }],
                    louceng_total: [{
                        required: true,
                        validator: validateNumber,
                        trigger: 'blur'
                    }],
                    chaoxiang: [{
                        required: true,
                        message: '请选择朝向',
                        trigger: 'change'
                    }],
                    zhuangxiu: [{
                        required: true,
                        message: '请选择装修',
                        trigger: 'change'
                    }],
                    huxing_shi: [{
                        required: true,
                        type: "number",
                        message: '请填写室',
                        trigger: 'blur'
                    }],
                    huxing_ting: [{
                        required: true,
                        validator: validateNumber,
                        trigger: 'blur'
                    }],
                    huxing_wei: [{
                        required: true,
                        validator: validateNumber,
                        trigger: 'blur'
                    }],
                    huxing_chu: [{
                        required: true,
                        validator: validateNumber,
                        trigger: 'blur'
                    }],
                    huxing_yangtai: [{
                        required: true,
                        validator: validateNumber,
                        trigger: 'blur'
                    }],
                    house_tuijian_tag: [{
                        required: true,
                        type: 'array',
                        message: '请选择标签',
                        trigger: 'change'
                    }],
                    peitao: [{
                        required: true,
                        type: 'array',
                        message: '请选择配套',
                        trigger: 'change'
                    }],
                    xianzhuang: [{
                        required: true,
                        message: '请选择现状',
                        trigger: 'change'
                    }],
                    fangwuleixing: [{
                        required: true,
                        message: '请选择房屋类型',
                        trigger: 'change'
                    }],
                    jianzhujiegou: [{
                        required: true,
                        message: '请选择建筑结构',
                        trigger: 'change'
                    }],
                    house_school: [{
                        required: true,
                        message: '请填写所属学区',
                        trigger: 'blur'
                    }],
                    jianzaoniandai: [{
                        required: true,
                        message: '请选择建筑年代',
                        trigger: 'change'
                    }],
                    chanquanxingzhi: [{
                        required: true,
                        message: '请选择产权性质',
                        trigger: 'change'
                    }],
                    chanzhengriqi: [{
                        required: true,
                        message: '请选择产证日期',
                        trigger: 'change'
                    }],
                    chanquannianxian: [{
                        required: true,
                        message: '请选择产权年限',
                        trigger: 'change'
                    }],
                    fangyuanshuifei: [{
                        required: true,
                        message: '请选择房源税费',
                        trigger: 'change'
                    }],
                    house_tag: [{
                        required: true,
                        type: 'array',
                        message: '请选择房源标签',
                        trigger: 'change'
                    }],
                    kanfangfangshi: [{
                        required: true,
                        message: '请选择看房方式',
                        trigger: 'change'
                    }],
                    laiyuan: [{
                        required: true,
                        message: '请选择来源',
                        trigger: 'change'
                    }],
                    weituobianhao: [{
                        required: true,
                        message: '请填写委托编号',
                        trigger: 'blur'
                    }],
                    low_sell_price: [{
                        required: true,
                        message: '请填写净价',
                        trigger: 'blur'
                    }],
                    yaoshi_dian: [{
                        required: true,
                        message: '请选择钥匙店',
                        trigger: 'change'
                    }],
                    hk_shouju: [{
                        required: true,
                        message: '请填写钥匙收据号',
                        trigger: 'blur'
                    }],
                    // mark: [{
                    //     required: true,
                    //     message: '请填写备注',
                    //     trigger: 'blur'
                    // }],
                },
                village:[],
            };
        },
        methods: {
            setMakes() { //设置自定义参数
                let house_validate = {};
                if (this.formValidate.sale_type == '2') {
                    house_validate = this.settings.sell_house_validata;
                }
                if (this.formValidate.sale_type == '1') {
                    house_validate = this.settings.rental_house_validata;
                }
                if (this.formValidate.sale_type == '3') {
                    house_validate = this.settings.hight_house_validata;
                }
                for (let i in house_validate) {
                    if (this.ruleValidate.hasOwnProperty(house_validate[i])) {
                        this.$set(this.ruleValidate, house_validate[i], []);

                    }
                    this.$nextTick(function () {
                        this.$refs[house_validate[i]].isRequired = false;
                    })
                }

                this.village = this.formValidate.villageList;
            },
	        changeVillage(value) {//设置小区信息
		        this.formValidate.village_id =value;
		        for(let i in this.village){
			        if(this.village[i].village_id==value){
				        this.formValidate.village_name =this.village[i].village_name;
			        }
		        }
	        },
            changeHouseMakes(index, lable, sort) {
                this.$set(this.formValidate.house_make, index, {
                    'value': this.house_make_value[index],
                    'lable': lable,
                    'sort': sort
                })

            },
            modalOk() {
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        let action = 'house/edit';
                        this.$http.post(api_param.apiurl + action,
                            this.formValidate, {
                                emulateJSON: true,
                                headers: {
                                    "X-Access-Token": api_param.XAccessToken
                                }
                            }
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.formValidate.village = {};
                                this.editRoom = false;
                                this.$Message.success(response.data.message);
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
                            this.$Message.error('网络异常');
                        })
                    }
                });


            },
            modalCancel() {
                this.formValidate.dts_id = this.formValidate.tmp_dts_id;
                this.formValidate.dts_name = this.formValidate.tmp_dts_name;
                this.formValidate.village_id = this.formValidate.tmp_village_id;
                this.formValidate.village_name = this.formValidate.tmp_village_name;
                this.$emit('resetModal');
                //this.$refs['formValidate'].resetFields();
                this.addHouseModal = false;
            },
            selectDate(date) { //选择日期回调
                this.formValidate.zfqixian = date;
                console.log(date);
                console.log(this.formValidate.zfqixian);
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
			        this.$Message.error('你的网络开小差了^—^');
		        })
	        }
        }
    };
</script>
