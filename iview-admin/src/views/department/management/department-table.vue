<style lang="less">
	.prePay-main {
		font-size: 14px;
		line-height: 36px;
	}
	
	.prePay-main strong {
		color: #ff0000;
	}
	
	.imgboxmain {
		text-align: center;
	}
	
	.imgboxmain-p {
		text-align: center;
	}
	
	.ivu-modal-footer {
		border-top: 0px solid #e9eaec;
		padding: 12px 18px;
		text-align: right;
	}
	.payok{
		text-align: center;
	}
	.payok-p{
		text-align: center;
		font-size: 18px;
	}
	.paybtn{
		text-align: center;
    font-size: 18px;
    margin-top: 10px;
    text-decoration: underline;
	}
</style>

<template>
	<div class="mui-table">
		<div>
			<Table border :columns="columns" :stripe='true' :show-header='true' :data="departlist"></Table>
		</div>
		<!--微信支付信息确认-->
		<Modal v-model="prePay" title="支付信息确认" width="480" :label-width="80" :mask-closable="false">
			<div slot="header">
				<a class="ivu-modal-close" @click="modalPrepayCancel" style="display: block!important;"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
				<div class="ivu-modal-header-inner">支付信息确认</div>
			</div>
			<div slot="footer" style="text-align: center;">
				<Button type="info" @click="gotoPay" style="width: 140px;">去支付</Button>
			</div>
			<Row class="prePay-main">
				<Col :lg="6" :md="6" offset="6"> 当前版本：
				</Col>
				<Col :lg="6" :md="6">
				<strong>{{contract_version}}</strong></Col>
			</Row>
			<Row class="prePay-main">
				<Col :lg="6" :md="6" offset="6"> 充值门店：
				</Col>
				<Col :lg="6" :md="6">
				<strong>{{store_name}}</strong></Col>
			</Row>
			<Row class="prePay-main">
				<Col :lg="6" :md="6" offset="6"> 充值金额：
				</Col>
				<Col :lg="6" :md="6">
				<strong>{{pay_money}}元</strong></Col>
			</Row>
			<Row class="prePay-main">
				<Col :lg="6" :md="6" offset="6"> 使用年限：

				</Col>
				<Col :lg="6" :md="6">
				<strong>1年</strong></Col>
			</Row>
			<Row class="prePay-main">
				<Col :lg="18" :md="18" offset="3">
				<Checkbox v-model="agreement">我已阅读并同意《宜居客房产系统收费产品用户使用协议》</Checkbox>
				</Col>
			</Row>
		</Modal>
		<Modal v-model="addPay" title="微信支付" width="480" :label-width="80" :mask-closable="false">
			<div slot="header">
				<a class="ivu-modal-close" @click="modalPayCancel" style="display: block!important;"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
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
	</div>
</template>

<script>
	export default {
		name: 'departmentTable',
		props: ['departlist','contract_version'],
		data() {
			return {
				departmentPopup: false,
				//支付
				prePay: false,
				addPay: false,
				addPaypiao: false,
				store_id: 0,
				contract_version: '',
				store_name: '',
				pay_money: '',
				agreement: true,
				order_sn: '',
				paystatus: {
					status: 1
				},
				paymessage: {
					data: '',
				},
				afterpay: {
					data: 0,
				},
				qrurl: '',
				qrcodeurl: {
					url: '',
				},
				querycount: 0,
				stopquery: 0,

				columns: [{
						title: '部门名称',
						key: 'd_show_name'
					},
					{
						title: '部门负责人',
						key: 'd_principal',
						align: 'center'
					},
					{
						title: '创建时间',
						key: 'ctime',
						align: 'center'
					},
					{
						title: '部门电话',
						key: 'd_phone',
						align: 'center'
					},
					{
						title: '区域',
						key: 'dts_name',
						align: 'center'
					},
					{
						title: '地址',
						key: 'd_address',
						align: 'center'
					},
					// {
					// 	title: '合同期限',
					// 	key: 'contract',
					// 	align: 'center',
					// 	render: (h, params) => {
					// 		let texts = '';
					// 		if(params.row.contract_start != null && params.row.contract_end != null) {
					// 			texts = params.row.contract_start + '/' + params.row.contract_end;
					// 		} else {
					// 			texts = '---';
					// 		}
					// 		return h('div', {
					// 			props: {},
					// 		}, texts)
					// 	}
					// },
					{
						title: '操作',
						key: 'button',
						align: 'center',
						render: (h, params) => {
							let ret = [];
							if(params.row.button.edit == 1&& params.row.d_name != '总经办') {
								ret.push(h('Button', {
									props: {
										type: 'success',
										size: 'small'
									},
									style: {
										marginRight: '5px'
									},
									on: {
										click: () => {
											this.$emit('editdepart', params.row);
										}
									}
								}, '编辑'));
							}
						   if (params.row.button.del == 1 && params.row.d_name != '总经办') {
							   ret.push(h('Button', {
								   props: {
									   type: 'error',
									   size: 'small'
								   },
								   style: {
									   marginRight: '5px'
								   },
								   on: {
									   click: () => {
										   this.$emit('deldepart', params.row);
									   }
								   }
							   }, '删除'));
						   }
							// if(params.row.d_type == 2) {
							// 	let paybutton = '';
							// 	if(params.row.contract_money != 0) {
							// 		paybutton = '续费';
							// 	} else {
							// 		paybutton = '充值';
							// 	}
							// 	ret.push(h('Button', {
							// 		props: {
							// 			type: 'primary',
							// 			size: 'small'
							// 		},
							// 		on: {
							// 			click: () => {
							// 				this.store_id = params.row.d_id,
							// 					//this.contract_version = params.row.contract_version,
							// 					this.pay_money = this.contract_version == '门店版' ? 800 : 3800,
							// 					this.store_name = params.row.d_name,
							// 					this.prePay = true;
							// 			}
							// 		}
							// 	}, paybutton));
							// }
							return h('div', ret);
						}
					}
				],
				//				修改部门
				formValidate: {
					name: '',
					mold: '',
					belongs: '',
					phone: '',
					address: '',
					director: ''
					//sort: ''
				},
			}
		},
		methods: {
			modalPrepayCancel() {
				this.prePay = false;
				this.store_id = 0;
				//this.contract_version = '';
				this.store_name = '';
				this.pay_money = 0;
			},
			gotoPay() {
				if(this.agreement == false) {
					this.$Message.warning('请先阅读并同意用户使用协议');
					return false;
				}
                this.querycount = 0;
                this.stopquery = 0;
				this.$http.post(api_param.apiurl + 'payment/unified', {
					'order_title': '宜居客房产系统门店版',
					'contract_version': this.contract_version,
					'store_id': this.store_id,
					'store_name': this.store_name,
					'amount': this.pay_money
				}, {
					emulateJSON: true,
					headers: {
						"X-Access-Token": api_param.XAccessToken
					}
				}).then(function(response) {
					// 这里是处理正确的回调
					if(response.data.code == 200) {
						this.qrurl = response.data.data.qrurl;
						this.$set(this.qrcodeurl, 'url', api_param.apiurl + "payment/qrcode?url=" + this.qrurl);
						this.order_sn = response.data.data.order_sn;
						this.addPay = true;
						let _this = this;
						setTimeout(function() {
							_this.queryOrder();
						}, 500);
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
					console.log(response)
				})
				this.addPay = true;
			},
			modalPayCancel() {
				this.addPay = false;
				this.querycount = -1;
				this.stopquery = 1;
				this.$set(this.paystatus, 'status', 1);
				this.$set(this.paymessage, 'data', '');
				this.modalPrepayCancel();
				this.$emit('getIndex');
			},
			queryOrder() {
				if(this.stopquery == 0) {
					this.$http.post(api_param.apiurl + 'payment/queryorder', {
						'order_sn': this.order_sn,
						'count': this.querycount
					}, {
						emulateJSON: true,
						headers: {
							"X-Access-Token": api_param.XAccessToken
						}
					}).then(function(response) {
						// 这里是处理正确的回调
						if(response.data.code == 200) {
							if(response.data.data == 0) {
								this.$set(this.paystatus, 'status', 2);
								this.$set(this.paymessage, 'data', response.data.message);
								this.$set(this.afterpay, 'data', 1);
							} else if(response.data.data == 60) {
								this.$set(this.paystatus, 'status', 2);
								this.$set(this.paymessage, 'data', response.data.message);
								this.$set(this.afterpay, 'data', 2);
							} else if(response.data.data == -1) {
								this.addPay = false;
								this.querycount = -1;
								this.$set(this.paystatus, 'status', 1);
								this.$set(this.paymessage, 'data', '');
							} else {
								this.querycount = response.data.data;
								let _this = this;
								setTimeout(function() {
									_this.queryOrder();
								}, 500);
							}
						} else if(response.data.code == 204) {
							this.querycount = response.data.data;
							let _this = this;
							setTimeout(function() {
								_this.queryOrder();
							}, 500);
						} else if(response.data.code == 400) {
							this.$set(this.paystatus, 'status', 2);
							this.$set(this.paymessage, 'data', response.data.message);
							this.$set(this.afterpay, 'data', 2);
						} else if(response.data.code == 401) {
							this.$store.commit('logout', this);
							this.$store.commit('clearOpenedSubmenu');
							this.$router.push({
								name: 'login'
							});
						}
					}, function(response) {
						// 这里是处理错误的回调
						console.log(response)
					})
				} else {
					this.addPay = false;
					this.querycount = -1;
					this.stopquery = 1;
					this.$set(this.paystatus, 'status', 1);
					this.$set(this.paymessage, 'data', '');
				}
			},
		}
	};
</script>