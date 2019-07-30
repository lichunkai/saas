<style lang="less">
	@import "customerEtails.less";
</style>

<template>
<Row :gutter="10">
	<Col :lg="18" :md="18">
		<Card>
			<Row>
				<Col :lg="24" :md="24" class="customerline">
				<Row style="border-bottom: #f5f5f5 1px solid;padding-bottom: 10px;margin-bottom: 10px;">
					<Col :lg="8" :md="8" class="R-header-title">
						<Row>
							<Col :lg="24" :md="24">
								<span class='c_name'>{{customer.customer_name}} </span><span> {{customer.customer_sex}}</span></div>
							</Col>
							<Col :lg="24" :md="24" >
								<span >客户状态:</span>
								<span > {{customer.zhuangtai}}</span>							
							</Col>
							<Col :lg="24" :md="24" >
								<span>客户等级:</span>
								<span> {{customer.kehudengji}}</span>
							</Col>
							<Col :lg="24" :md="24" v-if="customer.customer_private=='私客'">
								<span>维护人:{{customer.weihuren}}</span>
							</Col>
							<Col :lg="24" :md="24" v-else>
								<span>维护人:公客</span>
							</Col>
							<Col :lg="24" :md="24" >
								<span>录入人:{{customer.weihuren}}</span>
							</Col>
						</Row>		
					</Col>
					<Col :lg="16" :md="16">
							<Row>
									
							<Col :lg="20" :md="20">
								<Row>
									<Col :lg="12" :md="12" v-for="v in this.customer.tel ">
									<p ><span>联系方式: </span><span>{{v.tel_phone}}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>{{v.tel_type}}</span>
									</p>
									</Col>								
									<Modal v-model="addNumber" title="新增联系方式" width="480" :label-width="80" :closable="false" :mask-closable="false" @on-ok="handleSubmit('formValidate')" @on-cancel="handleReset('formValidate')">
										<div slot="header">
											<a class="ivu-modal-close" @click="modalCancel" style="display: block!important;"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
											<div class="ivu-modal-header-inner">新增联系方式</div>
										</div>
										<div slot="footer">
											<Button type="text" size="large" @click="modalCancel">取消</Button>
											<Button type="primary" size="large" @click="modalOk">确定</Button>
										</div>
										<Form ref="formValidate" :model="formValidate" :rules="ruleValidate">
											<FormItem label="电话" prop="tel_phone">
												<Input placeholder="" v-model="formValidate.tel_phone"></Input>
											</FormItem>
											<FormItem label="备注" prop="tel_type">
												<Select placeholder="" :transfer="true" v-model="formValidate.tel_type">
													<Option v-for="item in JSON.parse( this.peizhi.dianhuasuoshu[0].base_desp)" :value="item.child_name" :key="item.child_name">{{ item.child_name}}
													</Option>
												</Select>
											</FormItem>
										</Form>
									</Modal>
								</Row>
							</Col>
							<Col :lg='4' :md='4'>							
								<p @click="addtel" class="addNewNumber">新增</p>							
							</Col>
							<Col :lg="24" :md="24" style="margin-top: 10px" v-if="!this.peizhi.bendianbenzu">
							<Button type="primary" long @click="gettel" v-show="teltrue">查看电话</Button>
							</Col>
							</Row>
						</Col>	
					
				</Row>
				<Row style="border-bottom: #f5f5f5 1px solid;padding-bottom: 10px;margin-bottom: 10px;">
					<Col :lg="24" :md="24" class="R-header-title">
						<strong style="font-size:20px">[{{customer.xuqiubianhao}}]{{customer.customer_type}}{{customer.yongtu}}{{customer.xuqiujiage_min}}-{{customer.xuqiujiage_max}}万{{customer.xuqiumianji_min}}-{{customer.xuqiumianji_max}}平米</strong>
					</Col>
					<Col :lg="24" :md="24" class="R-header-tag">
						<Tag color="blue">{{customer.customer_type}}</Tag>
						<Tag color="green" v-if="customer.customer_private">{{customer.customer_private}}</Tag>
						<Tag color="red" v-if="customer.zhuangtai">{{customer.zhuangtai}}</Tag>
						<Tag color="yellow" v-if="customer.kehudengji!=0">{{customer.kehudengji}}</Tag>
					</Col>
					
							<Col :lg="8" :md="8">
							<span>需求区域:</span><span >{{customer.dts_name}}-{{customer.rn_name}}-{{customer.village_name}}</span></Col>
							<Col :lg="8" :md="8">
							<span>需求价格:</span><span >{{customer.xuqiujiage_min}}-{{customer.xuqiujiage_max}}万</span></Col>
							<Col :lg="8" :md="8">
							<span>需求面积:</span><span >{{customer.xuqiumianji_min}}-{{customer.xuqiumianji_max}}平米</span></Col>
							<Col :lg="8" :md="8">
							<span>需求户型:</span><span >{{customer.xuqiuhuxing_min}}-{{customer.xuqiuhuxing_max}}室</span></Col>						
							<Col :lg="8" :md="8">
								<span>录入时间:</span><span >{{customer.ctime}}</span></Col>	
				</Row>
				</Col>
				<Col :lg="24" :md="24" class="customerline">
				<Row>
					<Col :lg="8" :md="8">
					<span>需求楼层:</span><span class='ml10'>{{customer.xuqiulouceng_min}}/{{customer.xuqiulouceng_max}}</span>			
					</Col>
					<Col :lg="8" :md="8">
					<span>需求朝向:</span><span class='ml10'>{{customer.chaoxiang}}</span>					
					</Col>
					<Col :lg="8" :md="8">
					<span>需求房龄:</span><span class='ml10'>{{customer.xuqiufangling_min}}-{{customer.xuqiufangling_max}}年</span>					
					</Col>
					<Col :lg="8" :md="8">
					<span>装修需求:</span><span class='ml10'>{{customer.zhuangxiu}}</span>					
					</Col>
					<Col :lg="8" :md="8">
					<span>内部配套:</span><span class='ml10'>{{customer.peitao}}</span>					
					</Col>
					<Col :lg="8" :md="8">
					<span>房屋类型:</span><span class='ml10'>{{customer.fangwuleixing}}</span>					
					</Col>							
					<Col :lg="8" :md="8">
					<span>需求原因:</span><span class='ml10'>{{customer.xuqiuyuanying}}</span>
					</Col>				
					<Col :lg="8" :md="8">
					<span>特殊需求:</span><span class='ml10'>{{customer.tiaojianbiaoqian}}</span>				
					</Col>
					<Col :lg="8" :md="8">
					<span>客户来源:</span><span class='ml10'>{{customer.kehulaiyuan}}</span>				
					</Col>
					
				</Row>
				</Col>
				<Col :lg="24" :md="24" class="customerline">
				<Row>	
					<Col :lg="8" :md="8">
					<span>沟通阶段:</span><span class='ml10'>{{customer.goutongjieduan}}</span>				
					</Col>
					<Col :lg="8" :md="8">
					<span>购房资质:</span><span class='ml10'>{{customer.xiaofeilinian}}</span>				
					</Col>
					<Col :lg="8" :md="8">
					<span>证件号码:</span><span class='ml10'>{{customer.zhengjianhaoma}}</span>				
					</Col>
					<Col :lg="8" :md="8">
					<span>客户邮箱:</span><span class='ml10'>{{customer.email}}</span>
					</Col>
					<Col :lg="8" :md="8">
					<span>客户微信:</span><span class='ml10'>{{customer.weixin}}</span>			
					</Col>
					<Col :lg="8" :md="8">
					<span>交通工具:</span><span class='ml10'>{{customer.jiaotonggongju}}</span>			
					</Col>
					<Col :lg="8" :md="8">
					<span>客户车型:</span><span class='ml10'>{{customer.chexing}}</span>				
					</Col>
					<Col :lg='24' :md='24'>
						<span>备注说明:</span>
						<span class='ml10'>{{customer.mark}}</span>	
					</Col>
				</Row>
				</Col>
			</Row>
		</Card>
	</Col>
	<Col :lg='6' :md='6'>
		<Card>
			<Row>
				<Col :lg='24' :md='24' class="marb10">
					<span class="spantext">跟进记录</span>
				</Col>
				<Col :lg='24' :md='24'>
					<Timeline>
						<Timeline-item  v-for="(item,index) in genjinlist" v-if="index <=4">
							<p class="content" style="font-size: 14px;line-height: 24px;">
							于<span style="color: #2d8cf0;margin-left: 5px;">{{item.ctime}}</span>
							<span style="margin-left: 5px;">{{item.genjinren}}</span></p>
							<p style="font-size: 14px;line-height: 24px;">【{{item.genjinfangshi}}】<span style='margin-left: 5px;'>{{item.genjinneirong}}</span></p>
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
						<Timeline-item color="red"  v-for="(item,index) in daikanlist" v-if="index <=4">
							<p class="content" style="font-size: 14px;line-height: 24px;">
							于<span style="color: #ed3f14;margin-left: 5px;">{{item.ctime}}</span>
							<span style="margin-left: 5px;">{{item.daikanren}}</span></p>
							<p style="font-size: 14px;line-height: 24px;">【{{item.house_sn}}】<span style='margin-left: 5px;'>{{item.khpj}}</span></p>
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
	export default {
		name: 'editBasic',
		props: ['customer', 'peizhi', 'teltrue','genjinlist','daikanlist'],
		components: {},
		data() {
			return {
				customer: this.customer,
				addNumber: false,
				teltrue: true,
				telgenjin: false,
				formValidate: {
					tel_phone: '',
					tel_type: '',
				},				
				ruleValidate: {
					tel_phone: [{
							required: true,
							message: '请输入手机号码',
							trigger: 'blur'
						},
						{
							validator(rule, value, callback, source, options) {
								var errors = [];
								if (!/^1[345789]\d{9}$/.test(value)) {
									callback('请输入正确的手机号码!');
								}
								callback(errors);

							}
						}
					],
					tel_type: [{
						required: true,
						message: '请选择备注',
						trigger: 'blur'
					}]
				}
			};
		},
		methods: {
			addtel() {
				this.addNumber = true;
			},
			gettel() {
				//查看电话号码
				this.$http.get(api_param.apiurl + '/customer/gettel', {
					params: {
						customer_uuid: this.$route.params.customer_uuid,
						pagesize: 10000,
					},
					headers: {
						'X-Access-Token': api_param.XAccessToken
					}
				}).then(function(response) {
					// 这里是处理正确的回调
					if (response.data.code == 200) {
						if (response.body.data.list.tel) {
							this.teltrue = false;
						}
						this.customer.tel = response.body.data.list.tel;
						this.$emit('follow_tel', {
							'telrs': true,
							'tel': this.customer.tel
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
				}, function(response) {
					// 这里是处理错误的回调
					// console.log(response);
				});
			},			
			modalOk() {
				var data = {
					tel_phone: this.formValidate.tel_phone,
					tel_type: this.formValidate.tel_type,
					customer_uuid: this.customer.customer_uuid,
					customer_private: this.customer.customer_private,
				};
				this.$refs['formValidate'].validate((valid) => {
					if (valid) {
						this.$http.post(api_param.apiurl + '/customer/addtel',
							data, {
								emulateJSON: true,
								headers: {
									'X-Access-Token': api_param.XAccessToken
								}
							}
						).then(function(response) {
							// 这里是处理正确的回调
							this.$Message.success(response.data.message);
							this.get_genjinlist();
							this.modalCancel();
						}, function(response) {
							// 这里是处理错误的回调
							//console.log(response)
							this.modalCancel();
							this.$Message.warning('更新失败');
						});
					}
				});

			},
			modalCancel() {
				this.addNumber = false;
				this.$refs['formValidate'].resetFields(); //清空form规则检查
				this.$emit('resetok');
			}

		}
	};
</script>
