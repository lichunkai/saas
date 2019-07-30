<style scoped>
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
		border-top: 0px solid #e9eaec !important;
		padding: 12px 18px;
		text-align: right;
	}
	/*.payok{
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
	}*/
	.payok {
		width: 128px;
		height: 128px;
		margin: 0px auto;
		overflow: hidden;
	}
	
	.payok img {
		width: 100%;
		height: 100%;
		display: block;
	}
	
	.payok-p {
		text-align: center;
		margin-bottom: 20px;
		line-height: 24px;
	}
	
	.paybtn {
		text-align: center;
		margin: 10px auto;
	}
	
	.imgboxmain {
		width: 148px;
		height: 148px;
		margin: 20px auto;
		overflow: hidden;
		border: #f5f5f5 1px solid;
		border-radius: 3px;
	}
	
	.imgboxmain img {
		width: 100%;
		height: 100%;
		display: block;
	}
	
	.imgboxmain-p {
		text-align: center;
		margin-bottom: 20px;
		line-height: 24px;
	}
	
	.tabzhongxin {
		font-size: 18px;
		font-weight: bold;
		padding: 20px;
		text-align: center;
	}
	
	.tabboxtext {
		padding: 20px 40px 40px;
		overflow: hidden;
	}
	
	.tabboxmain {
		border: #e5e5e5 1px solid;
		overflow: hidden;
	}
	
	.tabboxmain1 {
		border: #e5e5e5 1px solid;
		overflow: hidden;
	}
	
	.tabname {
		line-height: 36px;
		font-size: 14px;
		font-weight: bold;
	}
	
	.tabname1 {
		line-height: 36px;
		font-size: 14px;
		font-weight: bold;
	}
	
	.tabname,
	.tabname1,
	.tabname-main,
	.tabname-main1 {
		display: flex;
		float: left;
		width: 100%;
		overflow: hidden;
		text-align: center;
		display: table;
	}
	
	.tabname li,
	.tabname-main li {
		width: 20%;
		padding: 10px 0;
		line-height: 32px;
		border-right: #e5e5e5 1px solid;
		display: table-cell;
		vertical-align: middle;
	}
	
	.tabname1 li,
	.tabname-main1 li {
		width: 16.5%;
		padding: 10px 0;
		line-height: 32px;
		border-right: #e5e5e5 1px solid;
		display: table-cell;
		vertical-align: middle;
	}
	
	.tabname-main1 {
		border-top: #e5e5e5 1px solid;
	}
	
	.tabname1 li:last-child,
	.tabname-main1 li:last-child {
		border-right: #ff0000 0px solid;
	}
	
	.tabxieyi {
		padding: 15px 40px;
		overflow: hidden;
	}
	
	.tabpay span {
		color: #ff0000;
		font-weight: bold;
	}
	
	.tabpay {
		text-align: center;
		background-color: #f5f5f5;
		overflow: hidden;
		line-height: 58px;
	}
	
	.tabxuanzhong {
		position: absolute;
		bottom: 0;
		right: 0;
		width: 16px;
		height: 16px;
	}
	
	.tabweixin {
		width: 32px;
		height: 32px;
		display: block;
		margin-right: 10px;
		float: left;
	}
	
	.tabkaipiao {
		overflow: hidden;
		margin: 20px 40px;
	}
	
	.tabbox {
		border: #2d8cf0 1px solid;
		width: 128px;
		margin: 15px 40px;
		border-radius: 3px;
		padding: 5px 10px;
		line-height: 32px;
		overflow: hidden;
		position: relative;
	}
	
	.tabmain {
		background: #f5f5f5;
		border-left: #ff0000 1px solid;
		padding-left: 10px;
		line-height: 38px;
	}
	
	.tabtext {
		color: #999;
		margin-bottom: 10px;
	}
	
	.company-detal {
		line-height: 32px;
	}
	
	.addNewNumber {
		cursor: pointer;
		color: #57a3f3;
		text-decoration: underline;
		overflow: hidden;
	}
	
	.company-detal {
		line-height: 36px;
	}
	
	.comp-left {
		text-align: right;
		padding-right: 10px;
	}
	
	.comp-num {
		color: #ff0000;
	}
	
	.comp-text {
		color: #999;
	}
</style>
<template>
	<Card>
		<Row>

			<Col :lg="24" :md="24" style="margin-top: 10px;">
			<Card>
				<p slot="title">
					<Icon type="ios-film-outline"></Icon>
					公司信息
				</p>
				<Row>
					<Col :lg="24" :md="24">
					<Row class="company-detal">
						<Col :lg="6" :md="6">
						<Row>
							<Col :lg="12" :md="12" class="comp-left"> 当前版本：
							</Col>
							<Col :lg="12" :md="12">
							<strong>{{company_store.contract_version}}</strong></Col>
						</Row>
						</Col>
						<Col :lg="6" :md="6">
						<Row>
							<Col :lg="12" :md="12" class="comp-left"> 公司名称：
							</Col>
							<Col :lg="12" :md="12"> {{company.company_title}}
							</Col>
						</Row>
						</Col>
						<Col :lg="6" :md="6">
						<Row>
							<Col :lg="12" :md="12" class="comp-left"> 所有城市：
							</Col>
							<Col :lg="12" :md="12"> {{company.province_title}}-{{company.city_title}}
							</Col>
						</Row>
						</Col>
						<Col :lg="24" :md="24">
						<Row>
							<Col :lg="3" :md="3" class="comp-left"> 合作电话：
							</Col>
							<Col :lg="18" :md="18">
							<Row>
								<Col :lg="24" :md="24" v-for="v in company.companyPhone ">
								<Row>
									<Col :lg="6" :md="6"> 姓名：{{v.occ_name}}
									</Col>
									<Col :lg="10" :md="10"> 电话：{{v.occ_tel}}
									</Col>
									<Col :lg="6" :md="6">
									<span @click="addtel(v)" style="margin-left:10px" class="addNewNumber">修改</span>
									<span @click="remove(v)" style="margin-left:10px" class="addNewNumber">删除</span>
									</Col>
								</Row>
								</Col>
								<Col :lg="24" :md="24">
								<Row>
									<Col :lg="3" :md="3">
									<span @click="addtel(1)" class="addNewNumber">新增</span></Col>
								</Row>
								</Col>
							</Row>
							</Col>
						</Row>
						</Col>
					</Row>
					</Col>
				</Row>
			</Card>
			</Col>
			<Col :lg="24" :md="24" style="margin-top: 10px;">
			<Card>
				<p slot="title">
					<Icon type="ios-film-outline"></Icon>
					门店信息
				</p>
				<a href="#" slot="extra">
					<Button type="success" @click="adddepart">新增</Button>
				</a>
				<Row>
					<Col :lg="24" :md="24">
					<Table border :columns="storeColumns" :data="company.contract"></Table>
					</Col>
				</Row>
			</Card>
			</Col>
			<Col :lg="24" :md="24" style="margin-top: 10px">
			<Card>
				<p slot="title">
					<Icon type="ios-film-outline"></Icon>
					套餐信息
				</p>
				<Row>
					<Col :lg="8" :md="8" style="border-right: #e5e5e5 1px solid; box-sizing: border-box">
					<Row class="company-detal">
						<Col :lg="24" :md="24" class="tabzhongxin"> 门店版 - 尊享权益</Col>
						<Col :lg="24" :md="24">
						<Row>
							<Col :lg="12" :md="12" class="comp-left"> 每店可查看采集电话：
							</Col>
							<Col :lg="12" :md="12" class="comp-num"> 200
							</Col>
							<Col :lg="12" :md="12" class="comp-left"> 每店可导入采集房源：
							</Col>
							<Col :lg="12" :md="12" class="comp-num"> 150
							</Col>
							<Col :lg="12" :md="12" class="comp-left"> 新房项目报备：
							</Col>
							<Col :lg="12" :md="12" class="comp-num"> 不限
							</Col>
							<Col :lg="12" :md="12" class="comp-left"> 外网合作房源：
							</Col>
							<Col :lg="12" :md="12" class="comp-num"> 待定
							</Col>
							<!--<Col :lg="24" :md="24" style="text-align: center;margin-top: 10px">-->
							<!--<Button type="error">立即充值</Button>-->
							<!--</Col>-->
						</Row>
						</Col>
					</Row>
					</Col>
					<Col :lg="8" :md="8" style="border-right: #e5e5e5 1px solid; box-sizing: border-box">
					<Row class="company-detal">
						<Col :lg="24" :md="24" class="tabzhongxin"> 专业版 - 尊享权益</Col>
						<Col :lg="24" :md="24">
						<Row>
							<Col :lg="12" :md="12" class="comp-left"> 每店可查看采集电话：
							</Col>
							<Col :lg="12" :md="12" class="comp-num"> 不限
							</Col>
							<Col :lg="12" :md="12" class="comp-left"> 每店可导入采集房源：
							</Col>
							<Col :lg="12" :md="12" class="comp-num"> 不限
							</Col>
							<Col :lg="12" :md="12" class="comp-left"> 新房项目报备：
							</Col>
							<Col :lg="12" :md="12" class="comp-num"> 不限
							</Col>
							<Col :lg="12" :md="12" class="comp-left"> 外网合作房源：
							</Col>
							<Col :lg="12" :md="12" class="comp-num"> 待定
							</Col>
							<Col :lg="12" :md="12" class="comp-left"> 专属小程序 ：</Col>
							<Col :lg="12" :md="12" class="comp-num"> 门店版
							</Col>
							<Col :lg="12" :md="12" class="comp-left"> 专属公众号 ：</Col>
							<Col :lg="12" :md="12" class="comp-num"> 门店版
							</Col>
							<Col :lg="12" :md="12" class="comp-left"> 专属房产网站 ：</Col>
							<Col :lg="12" :md="12" class="comp-num"> 门店版
							</Col>
							<Col :lg="24" :md="24" style="text-align: center;margin-top: 10px" v-if="this.store_type==2 && this.company_store.contract_version=='门店版'">
							<Button type="error" @click="upgradeModal">立即升级</Button>
							</Col>
						</Row>
						</Col>
					</Row>
					</Col>
					<Col :lg="8" :md="8">
					<Row class="company-detal">
						<Col :lg="24" :md="24" class="tabzhongxin"> 私有云 - 独家定制</Col>
						<Col :lg="24" :md="24">
						<Row style="text-align: center">
							<Col :lg="24" :md="24"> 专业版所有功能
							</Col>
							<Col :lg="24" :md="24"> 企业定制组件
							</Col>
							<Col :lg="24" :md="24"> 个性服务定制
							</Col>
							<Col :lg="24" :md="24"> 阿里云/腾讯云双重数据安全加密
							</Col>
							<Col :lg="24" :md="24"> 企业私有部署
							</Col>
							<Col :lg="24" :md="24" style="text-align: center;margin-top: 10px">
							<Button type="error">立即咨询</Button>
							</Col>
						</Row>
						</Col>
					</Row>
					</Col>
				</Row>
			</Card>
			</Col>

			<!--新增对外合作人-->
			<Modal v-model="addNumber" title="新增对外合作人" width="480" :label-width="80" :closable="false" :mask-closable="false" @on-ok="handleSubmit('formValidate')" @on-cancel="handleReset('formValidate')">
				<div slot="header">
					<a class="ivu-modal-close" @click="modalCancel" style="display: block!important;"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
					<div class="ivu-modal-header-inner">新增对外合作人</div>
				</div>
				<div slot="footer">
					<Button type="text" size="large" @click="modalCancel">取消</Button>
					<Button type="primary" size="large" @click="modalOk">确定</Button>
				</div>

				<Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="66">
					<FormItem label="姓名" prop="occ_name">
						<Input placeholder="" v-model="formValidate.occ_name"></Input>
					</FormItem>
					<FormItem label="电话" prop="occ_tel">
						<Input placeholder="" v-model="formValidate.occ_tel"></Input>
					</FormItem>
				</Form>
			</Modal>

			<!--新增门店-->
			<Modal v-model="departmodal" :title="departmodeltitle" :mask-closable="false">
				<div slot="header">
					<a class="ivu-modal-close" @click="modalDepartCancel"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
					<div class="ivu-modal-header-inner">{{departmodeltitle}}</div>
				</div>
				<div slot="footer">
					<Button type="text" size="large" @click="modalDepartCancel">取消</Button>
					<Button type="primary" size="large" @click="modalDepartOk">确定</Button>
				</div>
				<Form ref="formDepartValidate" :model="formDepartValidate" :rules="ruleDepartValidate" :label-width="80" style="padding: 16px 40px;">
					<FormItem label="部门名称" prop="d_name">
						<Input v-model="formDepartValidate.d_name" placeholder="请输入部门名称"></Input>
					</FormItem>
					<FormItem label="部门类型" prop="d_type">
						<Select v-model="formDepartValidate.d_type" :transfer="false" disabled>
							<Option v-for="(item,index) in company.departtype" :value="index" :key="index">{{ item }}
							</Option>
						</Select>
					</FormItem>
					<FormItem label="部门所属" prop="d_pid" v-if="formDepartValidate.d_name !='总经办'">
						<!--部门只选择到部门一级-->
						<Cascader :data="company.departchoose" :value.sync="departvalue" change-on-select @on-change="handleChange" :transfer="false"></Cascader>
					</FormItem>
					<FormItem label="电话" prop="d_phone">
						<Input v-model="formDepartValidate.d_phone" placeholder="请输入联系电话"></Input>
					</FormItem>
					<FormItem label="部门区域" prop="d_district">
						<Select v-model.sync="formDepartValidate.d_district" :transfer="false" filterable>
							<Option v-for="(item,index) in company.district" :value="item.dts_id" :key="item.dts_id">{{ item.dts_name }}
							</Option>
						</Select>
					</FormItem>
					<FormItem label="地址" prop="d_address">
						<Input v-model="formDepartValidate.d_address" placeholder="请输入部门地址"></Input>
					</FormItem>
					<FormItem label="部门主管" prop="d_principal_id">
						<!--显示部门的所有主管-->
						<Select v-model.sync="formDepartValidate.d_principal_id" :label-in-value="true" @on-change="selectChange" :transfer="false" filterable>
							<Option v-for="item in principalchoose" :value="item.u_id" :key="item.u_id">{{item.u_name }} </Option>
						</Select>
					</FormItem>
					<FormItem label="签约套餐" prop="contract_version" v-if="!formDepartValidate.d_id">
						<!--显示部门的所有主管-->
						<Select v-model="formDepartValidate.contract_version" :transfer="false" disabled>
							<Option value="门店版">门店版(800元) </Option>
							<Option value="专业版">专业版(3800元) </Option>
						</Select>
					</FormItem>
					<FormItem label="" prop="agreement" v-if="!formDepartValidate.d_id">
						<!--<Checkbox v-model="formValidate.agreement">我已阅读并同意《宜居客房产系统收费产品用户使用协议》</Checkbox>-->
						<Checkbox-group v-model="formDepartValidate.agreement">
							<Checkbox label="协议">我已阅读并同意《宜居客房产系统收费产品用户使用协议》</Checkbox>
						</Checkbox-group>
					</FormItem>
				</Form>
			</Modal>

			<!--微信支付-->
			<Modal v-model="addPay" title="微信支付" width="480" :label-width="80" :closable="false" :mask-closable="false">
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

			<!--微信支付信息确认-->
			<Modal v-model="prePay" title="支付信息确认" width="480" :label-width="80" :mask-closable="false" :transfer="false">
				<div slot="header">
					<a class="ivu-modal-close" @click="modalPrepayCancel" style="display: block!important;"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
					<div class="ivu-modal-header-inner">支付信息确认</div>
				</div>
				<div slot="footer" style="text-align: center;" >
					<Button type="info" @click="renewalsPay" style="width: 140px;">去支付</Button>
				</div>
				<Row class="prePay-main">
					<Col :lg="6" :md="6" offset="6">当前版本：</Col>
					<Col :lg="6" :md="6">
					<strong>{{company_store.contract_version}}</strong></Col>
					</Row>
				<Row class="prePay-main">
					<Col :lg="6" :md="6" offset="6">充值门店：</Col>
					<Col :lg="6" :md="6">
					<strong>{{store_name}}</strong></Col>
				</Row>
				<Row class="prePay-main">
					<Col :lg="6" :md="6" offset="6">充值金额：</Col>
					<Col :lg="6" :md="6">
					<strong>{{pay_money}}元</strong></Col>
					</Row>
				<Row class="prePay-main">
					<Col :lg="6" :md="6" offset="6">使用年限：</Col>
					<Col :lg="6" :md="6">
					<strong>1年</strong></Col>
				</Row>
				<Row class="prePay-main">
					<Col :lg="18" :md="18" offset="3">
					<Checkbox v-model="agreement">我已阅读并同意《宜居客房产系统收费产品用户使用协议》</Checkbox>
					</Col>
				</Row>
			</Modal>

			<!--升级套餐信息确认-->
			<Modal v-model="upgrade" title="升级套餐信息确认" width="480" :label-width="80" :mask-closable="false" :transfer="false">
				<div slot="header">
					<a class="ivu-modal-close" @click="modalUpgradeCancel" style="display: block!important;"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
					<div class="ivu-modal-header-inner">支付信息确认</div>
				</div>
				<div slot="footer" style="text-align: center;">
					<Button type="info" @click="upgradePay" style="width: 140px;">去支付</Button>
				</div>
				<Row class="prePay-main">
					<Col :lg="6" :md="6" offset="6">当前版本：</Col>
					<Col :lg="6" :md="6">
					<strong>门店版</strong></Col>
					</Row>
				<Row class="prePay-main">
					<Col :lg="6" :md="6" offset="6">升级版本：</Col>
					<Col :lg="6" :md="6">
					<strong>专业版</strong></Col>
				</Row>
				<Row class="prePay-main">
					<Col :lg="6" :md="6" offset="6">门店数量：</Col>
					<Col :lg="6" :md="6">
					<strong>{{storeNum}}个</strong></Col>
					</Row>
				<Row class="prePay-main">
					<Col :lg="6" :md="6" offset="6">使用年限：</Col>
					<Col :lg="6" :md="6">
					<strong>1年</strong></Col>
				</Row>
				<Row class="prePay-main">
					<Col :lg="6" :md="6" offset="6">充值金额：</Col>
					<Col :lg="6" :md="6">
					<strong>{{storeNum*(3800-800)}}元</strong></Col>
				</Row>
				<Row class="prePay-main">
					<Col :lg="18" :md="18" offset="3">
					<Checkbox v-model="agreement">我已阅读并同意《宜居客房产系统收费产品用户使用协议》</Checkbox>
					</Col>
				</Row>
			</Modal>

			<!--申请开票-->
			<Modal v-model="addPaypiao" title="发票信息" width="480" :label-width="80" :closable="false" :mask-closable="false">
				<div slot="header">
					<a class="ivu-modal-close" @click="modalPiaoCancel" style="display: block!important;"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
					<div class="ivu-modal-header-inner">发票信息</div>
				</div>
				<div slot="footer">
					<Button type="text" size="large" @click="modalPiaoCancel">取消</Button>
					<Button type="primary" size="large" @click="modalPiaoOk">确定</Button>
				</div>
				<Row>
					<Col :lg="24" :md="24">
					<Form ref="formPiaoValidate" :model="formPiaoValidate" :rules="rulePiaoValidate" :label-width="88">
						<Row>
							<Col :lg="12" :md="12">
							<FormItem label="单位名称" prop="name">
								<Input type="text" v-model="formPiaoValidate.name" placeholder="单位名称与营业执照一致"></Input>
							</FormItem>
							</Col>
							<Col :lg="12" :md="12">
							<FormItem label="纳税人识别号" prop="number">
								<Input type="text" v-model="formPiaoValidate.number" placeholder="核对纳税人识别号信息"></Input>
							</FormItem>
							</Col>
							<Col :lg="12" :md="12">
							<FormItem label="公司地址" prop="address">
								<Input type="text" v-model="formPiaoValidate.address" placeholder="正确输入地址"></Input>
							</FormItem>
							</Col>
							<Col :lg="12" :md="12">
							<FormItem label="联系电话" prop="phone">
								<Input type="text" v-model="formPiaoValidate.phone" placeholder="输入联系电话"></Input>
							</FormItem>
							</Col>
						</Row>
					</Form>
					</Col>
				</Row>
			</Modal>
		</Row>
	</Card>
</template>

<script>
	export default {
		name: "company",
		data() {
			const validateDidCheck = (rule, value, callback) => {
				if(value == this.current_d_id) {
					callback(new Error('部门归属不可为自身!'));
				} else {
					callback();
				}
			};
			return {
				storeColumns: [
				    {
                        title: '门店',
                        key: 'd_name',
                        align: 'center',
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.contract_money == 0) {
                                texts = params.row.d_name+'（试用）';
                            } else {
                                texts = params.row.d_name;
                            }
                            return h('div', {props: {},},texts)
                        }
					},
					{
						title: '套餐',
						key: 'contract_version',
						align: 'center'
					},
					{
						title: '开通时间',
						key: 'contract_start',
						align: 'center'
					},
					{
						title: '到期时间',
						key: 'contract_end',
						align: 'center'
					},
					{
						title: '操作',
						key: 'action',
						width: 150,
						align: 'center',
						render: (h, params) => {
							let text = '';
							if(params.row.contract_money == 0) {
								text = '充值';
							} else {
								text = '续费';
							}
							return h('div', [
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
											this.store_id = params.row.depart_store_id,
											//this.contract_version = params.row.contract_version,
											this.pay_money = this.company_store.contract_version == '门店版' ? 800 : 3800,
											this.store_name = params.row.d_name,
											this.prePay = true;
										}
									}
								}, text)
							]);
						}
					}
				],
				//公司信息
				addNumber: false,
				formValidate: {
					occ_name: '',
					occ_tel: '',
				},
				company: [],
				ruleValidate: {
					occ_tel: [{
							required: true,
							message: '请输入手机号码',
							trigger: 'blur'
						},
						{
							validator(rule, value, callback, source, options) {
								var errors = [];
								if(!/^1[345789]\d{9}$/.test(value)) {
									callback('请输入正确的手机号码!');
								}
								callback(errors);

							}
						}
					],
					occ_name: [{
						required: true,
						message: '请输入姓名',
						trigger: 'blur'
					}]
				},
				storeNum: 0, //门店数量
				//部门弹窗验证
				departmodal: false,
				district: [], //区域类型
				departtype: [],
				departvalue: [], //所属部门级联
				departchoose: [],
				principalchoose: [],
				current_d_id: '',
				formDepartValidate: {
					d_id: '',
					d_name: '',
					d_type: 2,
					d_level: 1,
					d_pid: '',
					d_pid_name: '',
					d_phone: '',
					d_district: '',
					d_address: '',
					d_principal: '',
					d_principal_id: '',
					contract_version: '',
					agreement: ['协议'],
					amount: ''
				},
				ruleDepartValidate: {
					d_name: [{
						required: true,
						message: '请输入部门名',
						trigger: 'blur'
					}],
					d_type: [{
						required: true,
						message: '请选择部门类型',
						trigger: 'change'
					}, ],
					d_phone: [{
							required: true,
							message: '请输入电话或手机号',
							trigger: 'blur'
						},
						{
							validator(rule, value, callback, source, options) {
								var errors = [];
								if(!/^(13[0-9]|14[5-9]|15[012356789]|166|17[0-8]|18[0-9]|19[8-9])[0-9]{8}$/.test(value)) {
									callback('请输入正确的电话或手机号');
								}
								callback(errors);
							}
						}
					],
					d_pid: [{
						required: true,
						validator: validateDidCheck,
						trigger: 'blur'
					}],
					d_district: [{
						required: true,
						message: '请选择部门区域',
						trigger: 'blur'
					}, ],
					contract_version: [{
						required: true,
						message: '请选择签约套餐',
						trigger: 'change'
					}, ],
					agreement: [{
						required: true,
						type: 'array',
						min: 1,
						message: '请先阅读并同意用户使用协议',
						trigger: 'change'
					}, ],
				},
				//支付
				prePay: false,
				upgrade: false,
				addPay: false,
				addPaypiao: false,
                store_type: 0,
				store_id: 0,
				contract_version: '',
				store_name: '',
				pay_money: '',
				agreement: true,
				order_sn: '',
                company_store:{
                    contract_version: '',
                },
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
				formPiaoValidate: {
					name: '',
					number: '',
					address: '',
					phone: '',
				},
				rulePiaoValidate: {
					name: [{
						required: true,
						message: '请输入单位名称',
						trigger: 'blur'
					}],
					number: [{
						required: true,
						message: '请输入纳税人识别号',
						trigger: 'blur'
					}],
					address: [{
						required: true,
						message: '请输入公司地址',
						trigger: 'blur'
					}],
					phone: [{
							required: true,
							message: '请输入手机号码',
							trigger: 'blur'
						},
						{
							validator(rule, value, callback, source, options) {
								var errors = [];
								if(!/^1[345789]\d{9}$/.test(value)) {
									callback('请输入正确的手机号码!');
								}
								callback(errors);

							}
						}
					],
				},
			};
		},
		created: function() {
			this.getIndex();
		},
		methods: {
			//添加号码
			getIndex() { //列表页
				this.$http.get(api_param.apiurl + 'company/index', {
					params: {
						d_id: this.searchkey
					},
					headers: {
						"X-Access-Token": api_param.XAccessToken
					}
				}).then(function(response) {
					// 这里是处理正确的回调
					if(response.data.code == 200) {
						this.company = response.data.data.company
						this.storeNum = response.data.data.company.contract.length;
						if(this.storeNum >0){
                            if(this.storeNum == 1 && response.data.data.company.contract[0].contract_money == 0){
                                this.store_type = 1; //一个试用门店
                                this.$set(this.company_store,'contract_version',response.data.data.company.contract[0].contract_version);
                            }else if(this.storeNum == 1 && response.data.data.company.contract[0].contract_money > 0){
                                this.store_type = 2; //一个试用门店,以开通
                                this.$set(this.company_store,'contract_version',response.data.data.company.contract[0].contract_version);
                            }else{
                                this.store_type = 2;//多个门店都开通//多个门店都开通
                                response.data.data.company.contract.forEach((item,index)=>{
                                    if(item.contract_money == 0 ){
										this.store_type = 3; //多个门店，试用门店未开通,其余以开通
									}else{
										this.$set(this.company_store,'contract_version',item.contract_version);
									}
                            	});
                            }
						}else{
                            this.store_type = 1;
                            this.$set(this.company_store,'contract_version','门店版');
						}
                        //console.log(this.store_type);console.log( this.company_store.contract_version);
					} else if(response.data.code == 401) {
						this.$store.commit('logout', this);
						this.$store.commit('clearOpenedSubmenu');
						this.$router.push({
							name: 'login'
						});
					} else {
						this.$Message.warning(response.data.message);
					}
					//console.log(this.departchoose);
				}, function(response) {
					// 这里是处理错误的回调
					console.log(response)
				})
			},
			addtel(data) {
				if(data == 1) {
					this.formValidate.occ_id = ''
					this.addNumber = true;
				} else {
					this.formValidate.occ_name = data.occ_name;
					this.formValidate.occ_tel = data.occ_tel;
					this.formValidate.occ_id = data.occ_id;
					this.addNumber = true;
				}
			},
			modalCancel() {
				this.addNumber = false;
				this.$refs['formValidate'].resetFields(); //清空form规则检查
				this.getIndex()
			},
			modalOk() {
				var data = {
					occ_name: this.formValidate.occ_name,
					occ_tel: this.formValidate.occ_tel,
					occ_id: this.formValidate.occ_id,
				};
				this.$refs['formValidate'].validate((valid) => {
					if(valid) {
						this.$http.post(api_param.apiurl + '/company/addtel',
							data, {
								emulateJSON: true,
								headers: {
									'X-Access-Token': api_param.XAccessToken
								}
							}
						).then(function(response) {
							// 这里是处理正确的回调
							this.$Message.success(response.data.message);
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
			remove(params) {
				this.$Modal.confirm({
					title: '删除联系人',
					content: '确定要删除吗',
					okText: '确定',
					cancelText: '取消',
					onOk: () => {
						this.$http.post(api_param.apiurl + '/company/deltel', {
							occ_id: params.occ_id
						}, {
							emulateJSON: true,
							headers: {
								'X-Access-Token': api_param.XAccessToken
							}
						}).then(function(response) {
							// 这里是处理正确的回调
							if(response.data.code == 200) {
								this.$Message.success(response.data.message);
								this.getIndex()
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
							this.$Message.warning('删除失败');
						});
					}
				});
			},
			//添加门店
			adddepart() {
				this.$refs['formDepartValidate'].resetFields();
				this.departmodeltitle = '新增部门';
				this.formDepartValidate.d_id = '';
				this.formDepartValidate.d_name = '';
				this.formDepartValidate.d_type = '2';
				this.formDepartValidate.d_pid = '';
				this.formDepartValidate.d_pid_name = '';
				this.formDepartValidate.d_phone = '';
				this.formDepartValidate.d_district = '';
				this.formDepartValidate.d_address = '';
				this.formDepartValidate.d_principal = '';
				this.formDepartValidate.d_principal_id = '';
				this.formDepartValidate.contract_version = this.company_store.contract_version;
				this.current_d_id = '';
				this.departvalue = [];
				this.departmodal = true;
				console.log(this.formDepartValidate);
			},
			modalDepartOk() {
				this.$refs['formDepartValidate'].validate((valid) => {
					if(valid) {
						this.addStorePay();
					}
				});
			},
			modalDepartCancel() {
				this.departmodal = false;
				//清空form规则检查
				this.$refs['formDepartValidate'].resetFields();
			},
			handleChange(value, selectedData) { //级联选择回调
				this.formDepartValidate.d_pid = value[value.length - 1];
				this.formDepartValidate.d_pid_name = selectedData[value.length - 1]['label'];
				this.formDepartValidate.d_level = parseInt(value.length) + 1;
				this.getUser(this.formDepartValidate.d_pid);

			},
			getUser(d_id) { //获取主管人选
				this.$http.post(api_param.apiurl + 'depart/getuser', {
					'd_id': d_id
				}, {
					emulateJSON: true,
					headers: {
						"X-Access-Token": api_param.XAccessToken
					}
				}).then(function(response) {
					// 这里是处理正确的回调
					if(response.data.code == 200) {
						this.principalchoose = response.data.data;
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
			},
			//升级门店套餐
			upgradeModal() {
				this.upgrade = true;
			},
			modalUpgradeCancel() {
				this.upgrade = false;
				this.contract_version = '';
				this.store_name = '';
				this.pay_money = 0;
				this.agreement = true;
			},
			upgradePay() {
				this.querycount = 0;
				this.stopquery = 0;
				if(this.agreement == false) {
					this.$Message.warning('请先阅读并同意用户使用协议!');
					return false;
				}
				this.$http.post(api_param.apiurl + 'payment/unified', {
					'order_title': '宜居客房产系统升级到专业版',
					'contract_version': '专业版',
					'amount': 3000 * this.storeNum,
					'upgrade': 1,
				}, {
					emulateJSON: true,
					headers: {
						"X-Access-Token": api_param.XAccessToken
					}
				}).then(function(response) {
					// 这里是处理正确的回调
					if(response.data.code == 200) {
					    this.is_upgrade = 1;//判断是否为升级
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
				//this.addPay = true;
			},
			//微信支付
			modalPrepayCancel() {
				this.prePay = false;
				this.store_id = 0;
				this.contract_version = '';
				this.store_name = '';
				this.pay_money = 0;
				this.agreement = true;
			},
			modalPayCancel() {
				this.addPay = false;
				this.querycount = -1;
				this.stopquery = 1;
				this.$set(this.paystatus, 'status', 1);
				this.$set(this.paymessage, 'data', '');
				this.modalDepartCancel();
				this.modalUpgradeCancel();
				this.modalPrepayCancel();
				this.getIndex();
			},
			addStorePay() {
				this.querycount = 0;
				this.stopquery = 0;
				this.formDepartValidate.d_pid = this.formDepartValidate.d_pid == undefined ? 0 : this.formDepartValidate.d_pid;
				this.formDepartValidate.d_pid_name = this.formDepartValidate.d_pid == 0 ? '' : this.formDepartValidate.d_pid_name;
				this.formDepartValidate.amount = this.formDepartValidate.contract_version == '门店版' ? 800 : 3800;
				this.$http.post(api_param.apiurl + 'payment/unified', {
					'd_id': this.formDepartValidate.d_id,
					'd_name': this.formDepartValidate.d_name,
					'd_type': this.formDepartValidate.d_type,
					'd_level': this.formDepartValidate.d_level,
					'd_pid': this.formDepartValidate.d_pid,
					'd_pid_name': this.formDepartValidate.d_pid_name,
					'd_phone': this.formDepartValidate.d_phone,
					'd_district': this.formDepartValidate.d_district,
					'd_address': this.formDepartValidate.d_address,
					'd_principal': this.formDepartValidate.d_principal,
					'd_principal_id': this.formDepartValidate.d_principal_id,
					'order_title': '宜居客房产系统' + this.formDepartValidate.contract_version,
					'contract_version': this.formDepartValidate.contract_version,
					'amount': this.formDepartValidate.amount
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
			renewalsPay() {
				this.querycount = 0;
				this.stopquery = 0;
				if(this.agreement == false) {
					this.$Message.warning('请先阅读并同意用户使用协议!!');
					return false;
				}
				this.$http.post(api_param.apiurl + 'payment/unified', {
					'order_title': '宜居客房产系统' + this.company_store.contract_version,
					'contract_version': this.company_store.contract_version,
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
			//开票
			openPaypiao() {
				this.formPiaoValidate.name = this.company.invoice_name;
				this.formPiaoValidate.number = this.company.invoice_number;
				this.formPiaoValidate.address = this.company.invoice_address;
				this.formPiaoValidate.phone = this.company.invoice_phone;
				this.addPaypiao = true;
			},
			modalPiaoOk() {
				this.$refs['formPiaoValidate'].validate((valid) => {
					if(valid) {
						this.$http.post(api_param.apiurl + '/payment/addinvoice', {
							'order_sn': this.order_sn,
							'name': this.formPiaoValidate.name,
							'number': this.formPiaoValidate.number,
							'address': this.formPiaoValidate.address,
							'phone': this.formPiaoValidate.phone
						}, {
							emulateJSON: true,
							headers: {
								'X-Access-Token': api_param.XAccessToken
							}
						}).then(function(response) {
							// 这里是处理正确的回调
							this.$Message.success(response.data.message);
							this.modalPiaoCancel();
						}, function(response) {
							// 这里是处理错误的回调
							this.modalPiaoCancel();
							this.$Message.warning('更新失败');
						});
					}
				});
			},
			modalPiaoCancel() {
				this.addPaypiao = false;
				this.$refs['formPiaoValidate'].resetFields(); //清空form规则检查
			},
		}
	}
</script>

