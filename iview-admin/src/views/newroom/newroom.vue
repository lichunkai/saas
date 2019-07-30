<template>
	<Row>
		<Col :lg="24" :md="24">
		<Card>
			<Row :gutter="5">
				<Col :lg="4" :md="4">
				<Input v-model="ss.keyword" placeholder="请输入项目名"></Input>
				</Col>
				<Col :lg="3" :md="3">
				<Button type="primary" @click="getIndex">搜索</Button>
				<Button type="primary" @click="qk">清空</Button>
				</Col>
			</Row>
		</Card>
		</Col>
		<Col :lg="24" :md="24" style="margin-top: 10px">
		<Card>
			<Table :columns="newroomColumns" :data="newroomData" border srcipt></Table>

			<!--项目报备-->
			<Modal v-model="newbaobei" title="报备新房客户" @on-ok="ok" @on-cancel="cancel">
				<div slot="footer">
					<Button type="text" size="large" @click="qxbb">取消</Button>
					<Button type="primary" size="large" @click="bb">确定</Button>
				</div>
				<Form ref="formValidate" :rules="ruleValidate" :label-width="75">

					<Row>
						<Col :lg="24" :md="24">
						<Row>
							<Col :lg="12" :md="12">
							<FormItem label="项目报备：" prop="city">
								{{formValidate.name}}
							</FormItem>
							</Col>
							<Col :lg="12" :md="12">
							<Button type="primary" @click="chooseCustomer">选择客源</Button>
							</Col>
						</Row>

						</Col>

						<Col :lg="24" :md="24">
						<Row>
							<Col :lg="12" :md="12">
							<FormItem label="客户姓名：" prop="" v-if="formValidate.xz">
								{{formValidate.customer_name}}
							</FormItem>
							<FormItem label="客户姓名：" prop="" v-else>
								未选择
							</FormItem>
							</Col>
							<Col :lg="12" :md="12">
							<FormItem label="客户电话：" prop="tel2">
								{{formValidate.tel_true}}
							</FormItem>
							</Col>
						</Row>
						</Col>
						<Col :lg="24" :md="24">
						<FormItem label="报备电话：" prop="tel2">
							<Input v-model="formValidate.tel" placeholder=""></Input>
						</FormItem>
						</Col>
						<Col :lg="24" :md="24">
							电话号码规则：{{formValidate.baobeishuoming}}
						</Col>

					</Row>

				</Form>
				<Modal v-model="customerModal" title="选择客源" :transfer="false" width="960" :mask-closable="false">
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
						<Table :columns="customerColumns" :data="customerList" border script @on-selection-change="selectionok" height="360"></Table>
						<!--<div style="margin: 10px;overflow: hidden">
							<div style="float: right;">
								<Page :total="pageTotal" :current="pageCurrent" @on-change="changePage"></Page>
							</div>
						</div>-->
						</Col>
					</Row>
				</Modal>
			</Modal>
			<!--项目详情-->

		</Card>
		</Col>
	</Row>
</template>

<script>
	import Cookies from 'js-cookie';

	export default {
		name: 'newroom_index',
		data() {
			return {
				ss: {
					name: ''
				},
				formValidate: {
					xz: false,
					tel_rule: '',
					tel_true: '',
					baobeishuoming: '',
					name: '',
					house_id: '',
					realname: '',
					customer_name: '',
					uniacid: '',
					customer_sn: '',
					sex: '',
					tel: '',
				},
				customerModal: false,
				// 客户列表
				pageTotal: 0,
				pageCurrent: 1,
				keyword: '',
				phone: 'apple',
				selection: [],
				users: [],
				newdetails: false,
				newbaobei: false,
				customerList: [],
				customerColumns: [{
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
				newroomColumns: [
				    {
						title: '项目名称',
						key: 'name',
						align: 'center'
					},
					{
						title: '区域',
						key: 'province',
						align: 'center',
						render: (h, params) => {
							var province = params.row.province + '/' + params.row.city + '/' + params.row.district;
							return h('div', province);
						}
					},
                    {
                        title: '项目经理电话',
                        key: 'phone',
                        align: 'center'
                    },
					{
						title: '均价',
						key: 'format_price',
						align: 'center'
					},

					{
						title: '经纪人佣金',
						key: 'commission',
						align: 'center'
					},
					{
						title: '开盘日期',
						key: 'opentime',
						align: 'center'
					},
					{
						title: '操作',
						key: 'action',
						align: 'center',
						render: (h, params) => {
							var ret = [];
							ret.push(
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
											let argu = {
												id: params.row.id,
												uniacid: params.row.uniacid
											};
											this.$router.push({
												name: 'newroomDetails',
												params: argu
											});
										}
									}
								}, '项目详情')
							);
							if(params.row.isContract == 0) {
								ret.push(
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
												this.qianyue(params.row);

											}
										}
									}, '申请签约')
								);
							}
							if(params.row.isContract == 1) {
								ret.push(
									h('Button', {
										props: {
											type: 'primary',
											size: 'small'
										},
										on: {
											click: () => {
												this.formValidate.name = params.row.name
												if(params.row.tel_rule == 2) {
													this.formValidate.baobeishuoming = '电话号码可以前三后四，中间用星号替换（注您也可用全号）'
												} else {
													this.formValidate.baobeishuoming = '电话号码必须是全号，不能用星号替换'
												}
												this.formValidate.tel_rule = params.row.tel_rule
												this.formValidate.house_id = params.row.id
												this.formValidate.uniacid = params.row.uniacid
												this.newbaobei = true;
											}
										}
									}, '报备')
								);
							}
							return h('div', ret);
						}
					}
				],
				newroomData: []
			};
		},
		created() {
			this.getUser();
			this.getSetting();
		},
		methods: {
			qk() {
				this.ss.keyword = ''
				this.getIndex()
			},
			qxbb() {
				this.formValidate.customer_name = ''
				this.formValidate.tel = ''
				this.formValidate.tel_true = ''
				this.formValidate.house_id = ''
				this.newbaobei = false;
			},
			bb() {
				if(!this.formValidate.tel) {
					this.$Message.warning('客户报备的电话号码不能为空！');
					return
				}
				var use = /^(13[0-9]|14[5-9]|15[012356789]|166|17[0-8]|18[0-9]|19[8-9])[0-9]{8}$/;
				var tel = this.formValidate.tel;
				if(this.formValidate.tel_rule == 2) {
					tel = tel.replace('****', '0000');
				}
				if(!use.test(tel) && this.formValidate.tel_rule == 2) {
					this.$Message.warning('电话号码不属于前三后四，中间星号');
					return
				}
				if(!use.test(tel)) {
					this.$Message.warning('请输入有效电话号码！');
					return
				}
				this.$http.post(api_param.newHouseUrl + '/web/index.php?c=site&a=entry&act=recommendcustomer&do=customer_api&m=superman_house', {
					token: this.$md5(api_param.newHouseKey + api_param.newHouseTime),
					tokenTime: api_param.newHouseTime,
					house_id: this.formValidate.house_id,
					tel_rule: this.formValidate.tel_rule,
					realname: this.formValidate.customer_name,
					bianhao: this.formValidate.customer_sn,
					tel: this.formValidate.tel,
					sex: this.formValidate.sex,
					partnerid: this.users.u_id,
					auth_rid: this.users.auth_rid,
					auth_sid: this.users.auth_sid,
					auth_aid: this.users.auth_aid,
					auth_baid: this.users.auth_baid,
					u_tel: this.users.u_account,
					u_name: this.users.u_name,
					uniacid: this.formValidate.uniacid,
					company_id: this.users.company_id,
				}, {
					emulateJSON: true
				}).then(function(response) {
					// 这里是处理正确的回调
					this.$Message.success(response.data.message);
					this.qxbb()
				}, function(response) {
					// 这里是处理错误的回调
					console.log(response);
				});
			},
			customerModalCancel() {
				this.selection = '';
				this.customerModal = false;
			},
			selectionok(selection) {
				this.selection = selection;
			},
			changePage(page) { // 分页
				this.pageCurrent = page;
				this.chooseCustomer();
			},
			customerModalOk() {
				if(this.selection.length == 1) {
					this.formValidate.customer_sn = this.selection[0].xuqiubianhao;
					this.formValidate.tel = this.selection[0].tel_phone;
					this.formValidate.tel_true = this.selection[0].tel_phone;
					this.formValidate.customer_name = this.selection[0].customer_name;
					if(this.selection[0].customer_sex == '先生') {
						this.formValidate.sex = 1
					} else {
						this.formValidate.sex = 2
					}
					if(this.formValidate.tel_rule == 2) {
						if(this.formValidate.tel) {
							this.formValidate.tel = this.formValidate.tel.substring(0, 3) + "****" + this.formValidate.tel.substring(7, 11);
						}
					}
					this.customerModal = false;
				} else if(this.selection.length > 1) {
					this.$Message.warning('最多选择1个');
				} else {
					this.$Message.warning('最少选择1个');
				}
				this.formValidate.xz = true;
			},
			chooseCustomer() {
				this.$http.post(api_param.apiurl + 'ordersell/yscustomerlist', {
					'page': this.pageCurrent,
					'kw': this.keyword
				}, {
					emulateJSON: true,
					headers: {
						'X-Access-Token': api_param.XAccessToken
					}
				}).then(function(response) {
					// 这里是处理正确的回调
					if(response.data.code == 200) {
						this.customerModal = true;
						this.customerList = response.data.data.listdata;
						this.pageTotal = response.data.data.totalnum;
					} else if(response.data.code == 401) {
						this.$Message.error('登录超时');
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
				});
			},
			qianyue(params) {
				this.$http.post(api_param.newHouseUrl + '/web/index.php?c=site&a=entry&act=qianyue&do=house_api&m=superman_house', {
					token: this.$md5(api_param.newHouseKey + api_param.newHouseTime),
					tokenTime: api_param.newHouseTime,
					house_id: params.id,
					uniacid: params.uniacid,
					company_id: this.users.company_id,
					organization: this.users.company.company_title,
					contact: this.users.company.phone,
					name: this.users.u_name,
				}, {
					emulateJSON: true
				}).then(function(response) {
					// 这里是处理正确的回调
					this.$Message.success(response.data.message);
					this.getUser()
				}, function(response) {
					// 这里是处理错误的回调
					console.log(response);
				});
			},
			getUser() {
				this.$http.get(api_param.apiurl + 'user_deploy/getindex', {
					params: {},
					headers: {
						'X-Access-Token': api_param.XAccessToken
					}
				}).then(function(response) {
					// 这里是处理正确的回调
					this.users = response.body.data;
					this.getIndex();
				}, function(response) {
					// 这里是处理错误的回调
					console.log(response);
				});
			},
			getSetting() {
				this.$http.get(api_param.newHouseUrl + '/web/index.php?c=site&a=entry&act=setting&do=house_api&m=superman_house', {
					params: {
						token: this.$md5(api_param.newHouseKey + api_param.newHouseTime),
						tokenTime: api_param.newHouseTime,
					},
					headers: {}
				}).then(function(response) {
					console.log(response);
					// 这里是处理正确的回调
					this.tableData = response.body.data.list;
					this.tableNum = response.body.data.count;
					this.addbutton = response.body.data.addbutton;
				}, function(response) {
					// 这里是处理错误的回调
					console.log(response);
				});
			},
			getIndex() {
				this.$http.get(api_param.newHouseUrl + '/web/index.php?c=site&a=entry&act=gethouselist&do=house_api&m=superman_house', {
					params: {
						token: this.$md5(api_param.newHouseKey + api_param.newHouseTime),
						tokenTime: api_param.newHouseTime,
						company_id: this.users.company_id,
						keyword: this.ss.keyword,
					},
					headers: {}
				}).then(function(response) {
					// 这里是处理正确的回调
					this.newroomData = response.body.data.list;
					this.tableNum = response.body.data.count;
				}, function(response) {
					// 这里是处理错误的回调
					console.log(response);
				});
			}
		}
	};
</script>

<style scoped>

</style>
