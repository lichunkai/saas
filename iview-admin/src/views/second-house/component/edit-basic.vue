<style lang="less">
	@import "roomdetails.less";
</style>

<template>
	<Row :gutter="10">
		<Col :lg="18" :md="18">
			<Row>
				<Col :lg="24" :md="24">
					<Card>
						<Row style="border-bottom: #f5f5f5 1px solid;">
							<Col :lg="20" :md="20">
								<Row>
									<Col :lg="24" :md="24" class="roomname">[{{data.house_sn}}] {{data.house_title}}</Col>
									<Col :lg="24" :md="24" class="roomtags">
										<Tag v-if="data.house_tag_ji" color="blue">急</Tag>
										<Tag v-if="data.house_tag_quan" color="green">全</Tag>
										<Tag v-if="data.house_tag_images" color="red">图</Tag>
										<Tag v-if="data.house_tag_yao" color="yellow">钥</Tag>
										<Tag v-if="data.house_tag_du" color="red">独</Tag>
										<Tag v-if="data.house_tag_geng" color="green">跟({{data.house_tag_geng_num}})</Tag>
										<Tag v-if="data.house_tag_dai" color="blue">带({{data.house_tag_dai_num}})</Tag>
										<Tag v-if="data.house_tag_qianpei" color="purple">签赔</Tag>
									</Col>
									<Col :lg="24" :md="24">
										<Row>
											<Col :lg="8" :md="8" class="roomlist">
												<sapn class="roomlistspan">房源区域：</sapn>
												<span>{{data.dts_name}}</span>
												<span>{{data.village_name}}</span>
											</Col>
											<Col :lg="8" :md="8" class="roomlist">
												<sapn class="roomlistspan">房源户型：</sapn>
												<sapn>{{data.fangxing}}</sapn>
											</Col>
											<Col :lg="8" :md="8" class="roomlist">
												<sapn class="roomlistspan">房源面积：</sapn>
												<sapn>{{data.jianzhumianji}}㎡</sapn>
											</Col>
										</Row>
										<Row>
											<Col :lg="8" :md="8" class="roomlist">
												<sapn class="roomlistspan">房源售价：</sapn>
												<span>{{data.sell_price}}万</span>
											</Col>
											<Col :lg="8" :md="8" class="roomlist">
												<sapn class="roomlistspan">房源单价：</sapn>
												<span>{{data.sell_price*10000/data.jianzhumianji | keepTwoNum}}元/㎡</span>
											</Col>
											<Col :lg="8" :md="8" class="roomlist">
												<sapn class="roomlistspan">录入时间：</sapn>
												<span>{{data.ctime}}</span>
											</Col>
										</Row>
									</Col>
								</Row>
							</Col>
							<Col :lg="4" :md="4">
								<div class="Calculator" @click="jisuanqi = true">
									<div class="Calculator-icon">
										<Icon type="ios-calculator"></Icon>
									</div>
									<div class="Calculator-tap">
										<span>房贷计算器</span>
									</div>
								</div>
								<Modal v-model="jisuanqi" width="960" :closable="false" title="房贷计算器" :mask-closable="false">
									<div slot="header">
										<a class="ivu-modal-close" @click="jisuanqi = false" style="display: block!important"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
										<div class="ivu-modal-header-inner">房贷计算器</div>
									</div>
									<div slot="footer">
										<Button type="primary" @click="jisuanqi = false">关闭</Button>
									</div>
									<edit-jsq></edit-jsq>
								</Modal>
							</Col>
						</Row>
						<Row style="padding-top: 10px;">
							<Col :lg="20" :md="20">
								<Row>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">使用面积：</sapn>
										<sapn>{{data.jianzhumianji}}㎡</sapn>
									</Col>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">产证面积：</sapn>
										<sapn>{{data.shiyongmianji}}㎡</sapn>
									</Col>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">房源楼层：</sapn>
										<sapn>{{data.louceng_now}}/{{data.louceng_total}}</sapn>
									</Col>
								</Row>
							</Col>
							<Col :lg="20" :md="20">
								<Row>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">房屋朝向：</sapn>
										<sapn>{{data.chaoxiang}}</sapn>
									</Col>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">房屋装修：</sapn>
										<sapn>{{data.zhuangxiu}}</sapn>
									</Col>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">房屋现状：</sapn>
										<sapn>{{data.xianzhuang}}</sapn>
									</Col>
								</Row>
							</Col>
							<Col :lg="20" :md="20">
								<Row>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">房屋类型：</sapn>
										<sapn>{{data.fangwuleixing}}</sapn>
									</Col>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">建筑结构：</sapn>
										<sapn>{{data.jianzhujiegou}}</sapn>
									</Col>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">建筑年代：</sapn>
										<sapn>{{data.jianzaoniandai}}</sapn>
									</Col>
								</Row>
							</Col>
							<Col :lg="20" :md="20">
								<Row>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">产权性质：</sapn>
										<sapn>{{data.chanquanxingzhi}}</sapn>
									</Col>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">产证日期：</sapn>
										<sapn>{{data.chanzhengriqi}}</sapn>
									</Col>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">产证年限：</sapn>
										<sapn>{{data.chanquannianxian}}</sapn>
									</Col>
								</Row>
							</Col>
							<Col :lg="20" :md="20">
								<Row>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">看房方式：</sapn>
										<sapn>{{data.kanfangfangshi}}</sapn>
									</Col>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">房屋来源：</sapn>
										<sapn>{{data.laiyuan}}</sapn>
									</Col>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">付款方式：</sapn>
										<sapn>{{data.fukuanfangshi}}</sapn>
									</Col>
								</Row>
							</Col>
							<Col :lg="20" :md="20">
								<Row>
									<Col :lg="8" :md="8" class="roomlist">
										<sapn class="roomlistspan">是否出佣：</sapn>
										<sapn v-if="data.chuyong == '0'">否</sapn>
										<sapn v-if="data.chuyong == '1'">是</sapn>
									</Col>
									<Col :lg="16" :md="16" class="roomlist">
										<sapn class="roomlistspan">房屋备注：</sapn>
										<sapn>{{data.mark}}</sapn>
									</Col>
								</Row>
							</Col>
						</Row>
					</Card>
					<Card style="margin-top: 10px;">
						<p slot="title">
							<Icon type="logo-angular" />
							业主+划成信息
						</p>
						<Row>
							<Col :lg="12" :md="12">
								<Row>
									<Col :lg="24" :md="24">
										<div class="fangyuan">
											<div class="fangyuan-name"><span>{{data.customer_name}}</span>先生</div>
											<div class="fangyuan-data" v-if="data.showdata == 0"><Button type="primary" @click="landNameClick = true">查看房源信息</Button></div>
										</div>
										<Modal v-model="landNameClick" title="查看房源信息" width="360" :mask-closable="false">
											<div slot="header">
												<a class="ivu-modal-close" @click="landNameClick = false" style="display: block!important"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
												<div class="ivu-modal-header-inner">查看房源信息</div>
											</div>
											<p>{{data.house_user.weihu.u_name}}</p>
											<p>{{data.house_user.weihu.u_phone}}</p>
										</Modal>
									</Col>
									<Col :lg="24" :md="24">
										<div class="new-list">
											<div class="new-item">房源楼栋:</div>
											<div class="new-list-items">{{data.loudong_name}}栋{{data.danyuan_name}}单元{{data.fanghao_name}}室</div>
										</div>
										<div class="new-list">
											<div class="new-item">净价:</div>
											<div class="new-list-items">{{data.low_sell_price}}万元</div>
											<div class="new-list-item">
												<Button type="text" @click="editPrice = true" v-if="data.showdata == 1">修改净价</Button>
											</div>
											<Modal v-model="editPrice" title="修改净价" width="480" :mask-closable="false">
												<div slot="header">
													<a class="ivu-modal-close" @click="lowPriceModalCancel" style="display: block!important"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
													<div class="ivu-modal-header-inner">修改净价</div>
												</div>
												<div slot="footer">
													<Button type="text" size="large" @click="lowPriceModalCancel">取消</Button>
													<Button type="primary" size="large" @click="lowPriceModalOk">确定</Button>
												</div>
												<Form ref="lowPrice" :model="lowPrice" :rules="lowPriceValidate" :label-width="80">
													<FormItem label="净价" prop="low_price">
														<Input placeholder="" v-model="lowPrice.low_price"></Input>
													</FormItem>
												</Form>
											</Modal>
										</div>
										<div class="new-list">
											<div class="new-item">业主电话:</div>
											<div class="new-list-items">
												<p><span>{{data.customer_phone}}</span><span>{{data.customer_type}}</span></p>
												<p v-for="item in data.house_phone"><span>{{item.hp_phone}}</span><span>{{item.hp_customer_type}}</span></p>
											</div>
											<div class="new-list-item"><Button type="text" @click="addNumber = true" v-if="data.showdata == 1">新增</Button></div>
											<Modal v-model="addNumber" title="新增业主电话" width="480" :mask-closable="false">
												<div slot="header">
													<a class="ivu-modal-close" @click="otherPhoneModalCancel" style="display: block!important"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
													<div class="ivu-modal-header-inner">新增业主电话</div>
												</div>
												<div slot="footer">
													<Button type="text" size="large" @click="otherPhoneModalCancel">取消</Button>
													<Button type="primary" size="large" @click="otherPhoneModalOk">确定</Button>
												</div>
												<Form ref="otherPhone" :model="otherPhone" :rules="otherPhoneValidate" :label-width="80">
													<FormItem label="电话" prop="hp_phone">
														<Input placeholder="" v-model="otherPhone.hp_phone"></Input>
													</FormItem>
													<FormItem label="备注" prop="hp_customer_type">
														<Select placeholder="请选择" :transfer="true" v-model="otherPhone.hp_customer_type">
															<Option v-for="item in settings.customer_type" :value="item" :key="item">{{ item }}</Option>
														</Select>
													</FormItem>
												</Form>
											</Modal>
										</div>
									</Col>
								</Row>
							</Col>
							<Col :lg="12" :md="12" style="border-left: #f5f5f5 1px solid; padding-left: 20px;">
								<div class="huacheng">
									<div class="huacheng-list">录入人：</div>
									<div class="huacheng-name" v-for="item in data.house_user.luru">
										<Button type="success" @click="editDivideUser(item)" v-if="item.canedit ==1">{{item.u_name}}</Button>
										<Button type="dashed" v-else>{{item.u_name}}</Button>
									</div>
								</div>
								<div class="huacheng">
									<div class="huacheng-list">维护人：</div>
									<div class="huacheng-name">
										<Button type="success" @click="editDivideUser(data.house_user.weihu)" v-if="data.house_user.weihu.canedit ==1">{{data.house_user.weihu.u_name}}</Button>
										<Button type="dashed"  v-else>{{data.house_user.weihu.u_name}}</Button>
									</div>
								</div>
								<div class="huacheng">
									<div class="huacheng-list">图片人：</div>
									<div class="huacheng-name">
										<Button type="success" @click="editDivideUser(data.house_user.tupian)" v-if="data.house_user.weihu.canedit ==1">{{data.house_user.tupian.u_name}}</Button>
										<Button type="dashed"  v-else>{{data.house_user.tupian.u_name}}</Button>
									</div>
								</div>
								<div class="huacheng">
									<div class="huacheng-list">钥匙人：</div>
									<div class="huacheng-name" v-for="item in data.house_user.yaoshi">
										<Button type="success" @click="editDivideUser(item)" v-if="item.canedit ==1">{{item.u_name}}</Button>
										<Button type="dashed" v-else>{{item.u_name}}</Button>
									</div>
								</div>
								<div class="huacheng">
									<div class="huacheng-list">一般委托：</div>
									<div class="huacheng-name" v-for="item in data.house_user.yiban">
										<Button type="success" @click="editDivideUser(item)" v-if="item.canedit ==1">{{item.u_name}}</Button>
										<Button type="dashed" v-else>{{item.u_name}}</Button>
									</div>
								</div>
								<div class="huacheng">
									<div class="huacheng-list">独家委托：</div>
									<div class="huacheng-name">
										<Button type="success" @click="editDivideUser(data.house_user.dujia)" v-if="data.house_user.dujia.canedit ==1">{{data.house_user.dujia.u_name}}</Button>
										<Button type="dashed" v-else>{{data.house_user.dujia.u_name}}</Button>
									</div>
								</div>
							</Col>
							<Modal v-model="editDivide" title="修改划成人员" width="480" :mask-closable="false">
								<div slot="header">
									<a class="ivu-modal-close" @click="editDivideModalCancel" style="display: block!important"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
									<div class="ivu-modal-header-inner">修改划成人员</div>
								</div>
								<div slot="footer">
									<Button type="text" size="large" @click="editDivideModalCancel">取消</Button>
									<Button type="primary" size="large" @click="editDivideModalOk">确定</Button>
								</div>
								<Form ref="editDivideForm" :model="editDivideForm" :rules="editDivideValidate" :label-width="80">
									<FormItem label="划成人" prop="lururen">
										<Input placeholder="" v-model="editDivideForm.devide_type" disabled></Input>
									</FormItem>
									<FormItem label="当前为" prop="old_user_id">
										<Input placeholder="" v-model="editDivideForm.old_user_name" disabled></Input>
									</FormItem>
									<FormItem label="调整后" prop="new_user_id">
										<Select placeholder="" :transfer="true" v-model="editDivideForm.new_user_id">
											<Option v-for="item in data.userlist" :value="item.u_id" :key="item.u_id">{{ item.u_name }}</Option>
										</Select>
									</FormItem>
								</Form>
							</Modal>
						</Row>
					</Card>
				</Col>
			</Row>
		</Col>
		<Col :lg='6' :md='6'>
			<Card>
			<Row>
				<Col :lg='24' :md='24' class="marb10">
					<span class="spantext">跟进记录</span>
				</Col>
				<Col :lg='24' :md='24'>
					<Timeline>
						<Timeline-item  v-for="(item,index) in followupList" v-if="index <=4">
							<p class="content" style="font-size: 14px;line-height: 24px;">
							于<span style="color: #2d8cf0;margin-left: 5px;">{{item.ctime}}</span>
							<span style="margin-left: 5px;">{{item.u_name}}</span></p>
							<p style="font-size: 14px;line-height: 24px;">【{{item.hf_type}}】<span style='margin-left: 5px;'>{{item.hf_content}}</span></p>
						</Timeline-item>
						<Timeline-item>
							<p class="time">查看更多</p>
						</Timeline-item>
					</Timeline>
				</Col>
				<Col :lg="24" :md="24" class=' mb10'>
					<div style="width: 100%;height: 1px;background-color: #e5e5e5;"></div>
				</Col>
			</Row>
			<Row>
				<Col :lg='24' :md='24' class="marb10" style="margin-top: 20px;">
					<span class="spantext1">带看记录</span>
				</Col>
				<Col :lg='24' :md='24'>
					<Timeline>
						<Timeline-item color="red" v-for="(item,index) in seeList" v-if="index <=4">
							<p class="content" style="font-size: 14px;line-height: 24px;">
							于<span style="color: #ed3f14;margin-left: 5px;">{{item.ctime}}</span>
							<span style="margin-left: 5px;">{{item.daikanren}}</span></p>
							<p style="font-size: 14px;line-height: 24px;">【{{item.bumen}}】<span style='margin-left: 5px;'>{{item.d_pingjia}}</span></p>
						</Timeline-item>
						<Timeline-item color="red">
							<p class="time">查看更多</p>
						</Timeline-item>
					</Timeline>
				</Col>
				<Col :lg="24" :md="24" class=' mb10'>
					<div style="width: 100%;height: 1px;background-color: #e5e5e5;"></div>
				</Col>
			</Row>
			</Card>
		</Col>
	</Row>
</template>

<script>
	import editJsq from './edit-jsq';
	export default {
		name: 'editBasic',
		components: {
			editJsq
		},
		props: ['data', 'settings','followupList','seeList'],
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
				jisuanqi: false,
				addNumber: false,
				editPrice: false,
				landNameClick: false,
				landName: {},
				AddPhone: false,
				dijia: '******',
				landNameValidate: {
					customer_name: [{
						required: true,
						message: '请填写客户姓名',
						trigger: 'blur'
					}],
					customer_sex: [{
						required: true,
						message: '请选择客户性别',
						trigger: 'change'
					}],
				},
				otherPhone: {
					'hp_customer_type': '',
				},
				otherPhoneValidate: {
					hp_phone: [{
						required: true,
						message: '请输入电话号码',
						trigger: 'blur'
					}, {
						validator(rule, value, callback, source, options) {
							let errors = [];
							if(!/^(13[0-9]|14[5-9]|15[012356789]|166|17[0-8]|18[0-9]|19[8-9])[0-9]{8}$/.test(value)) {
								callback('请输入正确的手机号码!');
							}
							callback(errors);

						}
					}],
					hp_customer_type: [{
						required: true,
						message: '请选择客户关系',
						trigger: 'change'
					}],
				},
				lowPriceValidate: {
					low_price: [{required: true,validator: validateNumber, trigger: 'blur'}]
				},
				lowPrice: {
                    low_price : '',
				},
				//修改划成人员
				editDivide:false,
				editDivideForm:{
					id:'',
					type:'',
					devide_type:'',
					old_user_id:'',
					new_user_id:''
				},
				editDivideValidate:{
					new_user_id: [{required: true, message: '请选择状态', trigger: 'change'}]
				}
			};
		},
		methods: {
			getDijia() {
				this.$http.get(api_param.apiurl + 'house/getdijia?house_uuid=' + this.data.house_uuid, {
					headers: {
						"X-Access-Token": api_param.XAccessToken
					}
				}).then(function(response) {
					// 这里是处理正确的回调
					if(response.data.code == 200) {
						if(this.data.order_type == 1) {
							this.dijia = response.data.data.low_rent_price;
						} else {
							this.dijia = response.data.data.low_sell_price;
						}

						this.$emit('getLogs');
					} else if(response.data.code == 401) {
						this.$store.commit('logout', this);
						this.$store.commit('clearOpenedSubmenu');
						this.$router.push({
							name: 'login'
						});
					} else {
						this.$Message.warning(response.data.message);
					}
				}, function(response) {
					// 这里是处理错误的回调
					this.$Message.error('网络异常');
				})
			},
			//跟进取消
			landNameModalCancel() { //修改取消
				this.landNameClick = false;
			},
			//跟进确定
			landNameModalOk() { //修改状态
				this.$refs['landName'].validate((valid) => {
					if(valid) {
						this.landName.house_uuid = this.data.house_uuid;
						this.landName.hosue_sn = this.data.hosue_sn;
						this.$http.post(api_param.apiurl + 'house/edithousecustomer',
							this.landName, {
								emulateJSON: true,
								headers: {
									"X-Access-Token": api_param.XAccessToken
								}
							}
						).then(function(response) {
							// 这里是处理正确的回调
							if(response.data.code == 200) {
								this.$Message.success(response.data.message);
								this.landNameClick = false;
								this.$emit('getDetail');
							} else if(response.data.code == 401) {
								this.$store.commit('logout', this);
								this.$store.commit('clearOpenedSubmenu');
								this.$router.push({
									name: 'login'
								});
							} else {
								this.$Message.warning(response.data.message);
							}
						}, function(response) {
							// 这里是处理错误的回调
							this.$Message.error('网络异常');
						})
					}
				})
			},

			//手机取消
			otherPhoneModalCancel() { //修改取消
				this.addNumber = false;
			},
			//手机确定
			otherPhoneModalOk() { //修改状态
				this.$refs['otherPhone'].validate((valid) => {
					if(valid) {
						this.otherPhone.house_uuid = this.data.house_uuid;
						this.otherPhone.hosue_sn = this.data.hosue_sn;
						this.$http.post(api_param.apiurl + 'house/addhousephone',
							this.otherPhone, {
								emulateJSON: true,
								headers: {
									"X-Access-Token": api_param.XAccessToken
								}
							}
						).then(function(response) {
							// 这里是处理正确的回调
							if(response.data.code == 200) {
								this.$Message.success(response.data.message);
								this.addNumber = false;
								this.$emit('getDetail');
							} else if(response.data.code == 401) {
								this.$store.commit('logout', this);
								this.$store.commit('clearOpenedSubmenu');
								this.$router.push({
									name: 'login'
								});
							} else {
								this.$Message.warning(response.data.message);
							}
						}, function(response) {
							// 这里是处理错误的回调
							this.$Message.error('网络异常');
						})
					}
				})
			},

			//低价取消
			lowPriceModalCancel() { //修改取消
				this.editPrice = false;
			},
			//低价确定
			lowPriceModalOk() { //修改状态
				this.$refs['lowPrice'].validate((valid) => {
					if(valid) {
						this.lowPrice.house_uuid = this.data.house_uuid;
						this.lowPrice.hosue_sn = this.data.hosue_sn;
						this.lowPrice.sale_type = this.data.sale_type;
						this.$http.post(api_param.apiurl + 'house/editlowprice',
							this.lowPrice, {
								emulateJSON: true,
								headers: {
									"X-Access-Token": api_param.XAccessToken
								}
							}
						).then(function(response) {
							// 这里是处理正确的回调
							if(response.data.code == 200) {
								this.$Message.success(response.data.message);
								this.editPrice = false;
								this.$emit('getLogs');
								this.dijia = this.lowPrice.low_price
							} else if(response.data.code == 401) {
								this.$store.commit('logout', this);
								this.$store.commit('clearOpenedSubmenu');
								this.$router.push({
									name: 'login'
								});
							} else {
								this.$Message.warning(response.data.message);
							}
						}, function(response) {
							// 这里是处理错误的回调
							this.$Message.error('网络异常');
						})
					}
				})
			},
			seePhone() {
				this.$Message.warning("请先写跟进");
			},
			//修改划成人员
			editDivideUser(user){
				this.editDivideForm.id =user.id;
				this.editDivideForm.type =user.type;
				this.editDivideForm.devide_type =user.devide_type;
				this.editDivideForm.old_user_id =user.user_id;
				this.editDivideForm.old_user_name =user.u_name;
				this.editDivide = true;
			},
			editDivideModalCancel(){
				this.$refs['editDivideForm'].resetFields();
				this.editDivide = false;
			},
			editDivideModalOk(){
				this.$refs['editDivideForm'].validate((valid) => {
					if (valid) {
						this.$http.post(api_param.apiurl + 'house/editdivideuser',
								this.editDivideForm,
								{emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
						).then(function (response) {
							// 这里是处理正确的回调
							if (response.data.code == 200) {
								this.editDivideModalCancel();
								this.$Message.success('修改成功');
								this.$emit('getDetail');
							}else if (response.data.code == 401) {
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
				})
			}

		}, watch: {
		'$route.params.houseId'(to, from) {
			this.dijia='******';

		}
	}

	};
</script>
