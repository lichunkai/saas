<style scoped>
    @import "bargain.less";

    .marginBottom {
        margin-bottom: 10px;
    }
	.demo-upload-list{
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
		box-shadow: 0 1px 1px rgba(0,0,0,.2);
		margin-right: 4px;
	}
	.demo-upload-list img{
		width: 100%;
		height: 100%;
	}
	.demo-upload-list-cover{
		display: none;
		position: absolute;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		background: rgba(0,0,0,.6);
	}
	.demo-upload-list:hover .demo-upload-list-cover{
		display: block;
	}
	.demo-upload-list-cover i{
		color: #fff;
		font-size: 20px;
		cursor: pointer;
		margin: 0 2px;
	}
</style>
<template>
    <Row>
        <Col :lg="24" :md="24" class="marginBottom">
        <Card>
            <Row class="marginBottom" :gutter="5">
                <Col :lg="2" :md="2">
                <Select v-model="order_status_key" placeholder="成交状态" :transfer="true">
                    <Option v-for="(item,index) in order_status" :value="item.child_name" :key="index">{{
                        item.child_name }}
                    </Option>
                </Select>
                </Col>
                <!--<Col :lg="2" :md="2">-->
                <!--<Select v-model="house_use_key" placeholder="房源用途" :transfer="true">-->
                    <!--<Option v-for="(item,index) in house_use" :value="item.child_name" :key="index">{{ item.child_name-->
                        <!--}}-->
                    <!--</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <!--<Col :lg="2" :md="2">-->
                <!--<Select v-model="order_timechoose_key" placeholder="时间类型" :transfer="true">-->
                <!--<Option v-for="(item,index) in order_timechoose" :value="item.child_name" :key="index">{{ item.child_name }}</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <Col :lg="3" :md="3">
                <DatePicker type="daterange" :value="daterange" @on-change="selectDate" format="yyyy/MM/dd"
                            placeholder="选择时间段" :transfer="true" style="width:100%"></DatePicker>
                </Col>
                <Col :lg="2" :md="2">
                <Select v-model="mjqj_key" placeholder="面积" @on-change="selectArea" :transfer="true">
                    <Option v-for="(item,index) in mjqj" :value="item.min+'-'+item.max" :key="index">{{ item.child_name
                        }}
                    </Option>
                </Select>
                </Col>
                <Col :lg="2" :md="2">
                <Select v-model="jgqj_key" placeholder="价格" @on-change="selectPrice" :transfer="true">
                    <Option v-for="(item,index) in jgqj" :value="item.min+'-'+item.max" :key="index">{{ item.child_name
                        }}
                    </Option>
                </Select>
                </Col>
                <!--<Col :lg="2" :md="2">-->
                <!--<Select v-model="order_usertype_key" placeholder="部门用户类型">-->
                <!--<Option v-for="(item,index) in order_status" :value="item.child_name" :key="index">{{ item.child_name }}</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <!--<Col :lg="2" :md="2">-->
                <!--<Select v-model="model6" placeholder="选择部门">-->
                <!--<Option v-for="(item,index) in order_status" :value="item.child_name" :key="index">{{ item.child_name }}</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <Col :lg="4" :md="4">
                <Input v-model="keyword" placeholder="合同编号，房源编号、客源编号"></Input>
                </Col>
                <Col :lg="3" :md="3">
                <Button type="primary" @click="doSearch">查询</Button>
                <Button type="primary" @click="clearSearch">清空</Button>
                </Col>
                <Col :lg="8" :md="8">
                <Row type="flex" justify="end">
                    <Col>
					<Button type="warning" @click="bargain = true">新增成交</Button>
					<Modal v-model="bargain" title="新增成交" width="960">
						<div slot="header">
							<a class="ivu-modal-close" @click="modalCancel" style="display: block!important"><i
									class="ivu-icon ivu-icon-ios-close-empty"></i></a>
							<div class="ivu-modal-header-inner">新增成交</div>
						</div>
						<div slot="footer">
							<Button type="text" size="large" @click="modalCancel">取消</Button>
							<Button type="primary" :disabled="isDisable" size="large" @click="modalOk">确定</Button>
						</div>
<!--						<Form :model="" :label-width="80">-->
						<Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
							<Row>
								<Col :lg="24" :md="24" class="formItemClass">
									<span>业主/客户信息</span>
								</Col>
							</Row>
							<Row>
								<Col :lg="8" :md="8">
									<FormItem label="房源编号" prop="owner_sn">
										<Input placeholder="请选择房源编号" v-model="formValidate.owner_sn" readonly  disabled="true">
											<span slot="append" @click="choosehouse">选择房源</span>
										</Input>
									</FormItem>
								</Col>
								<Col :lg="8" :md="8">
									<FormItem label="业主电话" prop="owner_phone">
										<Input placeholder="请输入业主电话" v-model="formValidate.owner_phone"></Input>
									</FormItem>
								</Col>
								<Col :lg="8" :md="8">
									<FormItem label="业主姓名" prop="owner_name">
										<Input placeholder="请输入业主姓名" v-model="formValidate.owner_name">
											<Select slot="append" style="width: 88px" :transfer="true"  v-model="formValidate.owner_sex">
												<Option value="先生">先生</Option>
												<Option value="女士">女士</Option>
											</Select>
										</Input>
									</FormItem>
								</Col>
							</Row>
							<Row>
								<Col :lg="8" :md="8">
									<FormItem label="客源编号" prop="customer_sn">
										<Input placeholder="请选择客源编号" v-model="formValidate.customer_sn" readonly disabled="true">
											<span slot="append" type="ghost" @click="chooseCustomer" >选择客源</span>
										</Input>
									</FormItem>
								</Col>
								<Col :lg="8" :md="8">
									<FormItem label="客户电话" prop="customer_phone">
										<Input placeholder="请输入客户电话" v-model="formValidate.customer_phone"></Input>
									</FormItem>
								</Col>
								<Col :lg="8" :md="8">
									<FormItem label="客户姓名" prop="customer_name">
										<Input placeholder="请输入客户姓名" v-model="formValidate.customer_name">
											<Select slot="append" style="width: 88px" :transfer="true" v-model="formValidate.customer_sex">
												<Option value="先生">先生</Option>
												<Option value="女士">女士</Option>
											</Select>
										</Input>
									</FormItem>
								</Col>
							</Row>


							<Row>
								<Col :lg="24" :md="24" class="formItemClass">
									<span>成交/佣金信息</span>
								</Col>
							</Row>
							<Row>
								<Col :lg="8" :md="8">
									<FormItem  label="成交类型" prop="order_deal_type">
										<Select :transfer="true" v-model="formValidate.order_deal_type">
											<Option value="1">公司自有</Option>
											<Option value="2">合作房源</Option>
											<Option value="3">合作客源</Option>
											<Option value="4">代办过户</Option>
										</Select>
									</FormItem>
								</Col>
								<!--								<Col :lg="8" :md="8">-->
								<!--									<FormItem label="部门选择">-->
								<!--										<Cascader :data="bumenData" v-model="bumenValue" :transfer="true"></Cascader>-->
								<!--									</FormItem>-->
								<!--								</Col>-->
								<Col :lg="8" :md="8">
									<FormItem label="成交日期" ref="order_deal_date" prop="order_deal_date">
										<DatePicker type="date" placeholder="请选择成交日期" :value="formValidate.order_deal_date" style="width: 100%" :transfer="true" format="yyyy-MM-dd" @on-change="formValidate.order_deal_date=$event"></DatePicker>
									</FormItem>
								</Col>
								<Col :lg="8" :md="8">
									<FormItem label="成交价格" prop="order_price">
										<Input placeholder="请输入成交价格" v-model="formValidate.order_price">
											<span slot="append">万元</span>
										</Input>
									</FormItem>
								</Col>
							</Row>
							<Row>
								<Col :lg="8" :md="8">
									<FormItem label="业主应收佣金" prop="order_owner_commission">
										<Input placeholder="请输入业主应收佣金" v-model="formValidate.order_owner_commission">
											<span slot="append">元</span>
										</Input>
									</FormItem>
								</Col>
								<Col :lg="8" :md="8">
									<FormItem label="客户应收佣金" prop="order_customer_commission">
										<Input placeholder="请输入客户应收佣金" v-model="formValidate.order_customer_commission" >
											<span slot="append">元</span>
										</Input>
									</FormItem>
								</Col>
								<Col :lg="8" :md="8">
									<FormItem label="杂项收入" prop="order_zaxiang_commission">
										<Input placeholder="请输入杂项收入" v-model="formValidate.order_zaxiang_commission">
											<Select slot="append" style="width: 88px" :transfer="true" v-model="formValidate.order_zaxiang_type">
												<Option value="1">评估费</Option>
												<Option value="0">垫资费</Option>
											</Select>
										</Input>
									</FormItem>
								</Col>

							</Row>
							<Row>
								<Col :lg="8" :md="8">
									<FormItem label="成交部门" ref="order_deal_did" prop="order_deal_did">
										<!--										<Input placeholder="请填写成交人姓名" v-model="formValidate.order_deal_user"></Input>-->
										<Cascader :data="peizhi.departlist" v-if="peizhi.departlist" trigger="click" filterable change-on-select
												  v-model="formValidate.order_deal_did" @on-change="changeDepart" placeholder="部门选择"></Cascader>
									</FormItem>
								</Col>
								<Col :lg="8" :md="8">
									<FormItem label="成交人" ref="order_deal_user" prop="order_deal_user">
										<Select v-model="formValidate.order_deal_user" v-if="peizhi.departlist" :transfer="true" placeholder="人员选择" @on-change="changeuser">
											<Option v-for="v in users" :value="v.u_id" :key="v.u_id">{{v.u_name}}</Option>
										</Select>
									</FormItem>
								</Col>
								<Col :lg="8" :md="8">
									<FormItem label="权证人">
										<Input placeholder="请输入权证人姓名" v-model="formValidate.order_property_username" >
										</Input>
										<!--										<Select :transfer="true" v-model="formValidate.quanzhengren">-->
										<!--											<Option value="beijing">张三</Option>-->
										<!--											<Option value="shanghai">李四</Option>-->
										<!--										</Select>-->
									</FormItem>
								</Col>
							</Row>

<!--							<Row>-->
<!--								<Col :lg="24" :md="24" class="formItemClass">-->
<!--									<span>房源信息</span>-->
<!--								</Col>-->
<!--							</Row>-->
<!--							-->
<!--							<Row>-->
<!--								<Col :lg="8" :md="8">-->
<!--									<FormItem label="证件编号" >-->
<!--										<Input placeholder="请选择证件编号" v-model="formValidate.owner_idno"></Input>-->
<!--									</FormItem>-->
<!--								</Col>-->
<!--								<Col :lg="16" :md="16">-->
<!--									<FormItem label="联系地址" >-->
<!--										<Input placeholder="请输入联系地址" v-model="formValidate.owner_address">-->
<!--											-->
<!--										</Input>-->
<!--									</FormItem>-->
<!--								</Col>-->
<!--							</Row>-->
<!--							<Row>-->
<!--								<Col :lg="24" :md="24" class="formItemClass">-->
<!--									<span>客源信息</span>-->
<!--								</Col>-->
<!--							</Row>-->
<!--							-->
<!--							<Row>-->
<!--								<Col :lg="8" :md="8">-->
<!--									<FormItem label="证件编号" >-->
<!--										<Input placeholder="请选择证件编号" v-model="formValidate.customer_idno"></Input>-->
<!--									</FormItem>-->
<!--								</Col>-->
<!--								<Col :lg="16" :md="16">-->
<!--									<FormItem label="联系地址" >-->
<!--										<Input placeholder="请输入联系地址" v-model="formValidate.customer_address"></Input>-->
<!--									</FormItem>-->
<!--								</Col>-->
<!--							</Row>-->
							<Row>
								<Col :lg="24" :md="24" class="formItemClass">
									<span>图片信息</span>
								</Col>
							</Row>
							<Row>
								<Col :lg="4" :md="4" style="text-align: center" >
									<div class="demo-upload-list"  v-for="item in uploadList" >
										<template>
											<img :src="item.url">
											<div class="demo-upload-list-cover">
												<Icon type="ios-eye-outline" @click.native="handleView(item.name)"></Icon>
												<Icon type="ios-trash-outline" @click.native="handleRemove(item)"></Icon>
											</div>
										</template>
									</div>
									<Upload
											ref="upload"
											:headers="{'X-Access-Token':xtoken}"
											:show-upload-list="false"
											:default-file-list="defaultList"
											v-if="uploadList.length<1"
											:data="{'hi_type':30,'hi_is_cover':0}"
											:on-success="handleSuccess"
											:format="['jpg','jpeg','png']"
											:max-size="2048"
											:on-format-error="handleFormatError"
											:on-exceeded-size="handleMaxSize"
											:before-upload="handleBeforeUpload"
											multiple
											type="drag"
											:action="uploadurl"
											style="display: inline-block;width:120px;">
										<div style="width: 120px;height:120px;line-height: 120px;">
											<Icon type="ios-camera" size="20"></Icon>
										</div>
									</Upload>
									<div style="margin-top: 5px">合同确认书</div>
								</Col>
								<Col  :lg="4" :md="4" style="text-align: center">
									<div class="demo-upload-list"  v-for="item in yjuploadList" >
										<template>
											<img :src="item.url">
											<div class="demo-upload-list-cover">
												<Icon type="ios-eye-outline" @click.native="handleView(item.name)"></Icon>
												<Icon type="ios-trash-outline" @click.native="handleRemove1(item)"></Icon>
											</div>
										</template>
									</div>
									<Upload
											ref="upload"
											:headers="{'X-Access-Token':xtoken}"
											:show-upload-list="false"
											:default-file-list="defaultList"
											v-if="yjuploadList.length<1"
											:data="{'hi_type':31,'hi_is_cover':0}"
											:on-success="handleSuccess1"
											:format="['jpg','jpeg','png']"
											:max-size="2048"
											:on-format-error="handleFormatError"
											:on-exceeded-size="handleMaxSize"
											:before-upload="handleBeforeUpload"
											multiple
											type="drag"
											:action="uploadurl"
											style="display: inline-block;width:120px;">
										<div style="width: 120px;height:120px;line-height: 120px;">
											<Icon type="ios-camera" size="20"></Icon>
										</div>
									</Upload>
									<div style="margin-top: 5px">佣金确认书</div>
								</Col>
								<Modal title="查看大图" v-model="visible" :transfer="false">
									<img :src="imgurl + imgName" v-if="visible" style="width: 100%">
								</Modal>
							</Row>
						</Form>
					</Modal>
                    <Button type="primary" v-if="topbutton.export == 1">
						<a :href="exporturl" target="_blank" style="color:#fff">导出</a></Button>
                    <Button type="primary" @click="showList = true">自定义</Button>
                    <Modal v-model="showList" title="自定义列表">
                        <p slot="header">
                            <span>自定义列表</span>
                        </p>
                        <div class="CheckboxList">
                            <CheckboxGroup v-model="tableColumnsChecked" @on-change="changeTableColumns">
								<Checkbox label="order_sn"> 成交编号</Checkbox>
                                <Checkbox label="order_status">订单状态</Checkbox>
								<Checkbox label="house_district">区域</Checkbox>
								<Checkbox label="house_name">小区</Checkbox>
								<Checkbox label="house_area">面积</Checkbox>
								<Checkbox label="order_deal_date">成交日期</Checkbox>
								<Checkbox label="order_deal_department"> 成交部门</Checkbox>
								<Checkbox label="order_deal_username">成交人</Checkbox>
                                <Checkbox label="order_deal_type">交易类型</Checkbox>
								<Checkbox label="order_price">成交价</Checkbox>
                                <Checkbox label="house_sn">房源编号</Checkbox>
                                <Checkbox label="customer_sn">客源编号</Checkbox>
								<Checkbox label="customer_name">客户姓名</Checkbox>
								<Checkbox label="owner_name">业主姓名</Checkbox>
								<Checkbox label="c_user">房源录入人</Checkbox>
								<Checkbox label="u_user">房源维护人</Checkbox>
                                <Checkbox label="contract_sn">成交合同编号</Checkbox>

                            </CheckboxGroup>
                        </div>
                        <div slot="footer">
                            <Row type="flex" justify="space-between">
                                <Col>
                                <Button type="ghost" size="large" @click="resetColumns">恢复默认</Button>
                                </Col>
                                <Col>
                                <Button type="primary" size="large" @click="saveColumns">保存</Button>
                                </Col>
                            </Row>
                        </div>
                    </Modal>
                    </Col>
                </Row>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24">
			<Card>
				<Table border :columns="tableColumns" :data="listData" script></Table>
				<div style="margin: 10px;overflow: hidden">
					<div style="float: right;">
						<Page :total="totalnum" :current="currentpage" @on-change="changePage" show-total></Page>
					</div>
				</div>
			</Card>
        </Col>
		<Modal v-model="houseModal" title="选择房源" :transfer="true" width="960" :mask-closable="false" style="z-index: 9999">
			<div slot="header">
				<a class="ivu-modal-close" @click="dealhouseModalCancel" style="display: block!important"><i
						class="ivu-icon ivu-icon-ios-close-empty"></i></a>
				<div class="ivu-modal-header-inner">选择房源</div>
			</div>
			<div slot="footer">
				<Button type="text" size="large" @click="houseModalCancel">取消</Button>
				<Button type="primary" size="large" @click="houseModalOk">确定</Button>
			</div>
			<Row :gutter="5">
<!--				<Col :lg="4" :md="4">-->
<!--					<Cascader :data="settings.dts" v-model="searchData.dts_id" placeholder="片区选择" filterable-->
<!--							  change-on-select @on-change="changeDts"></Cascader>-->
<!--				</Col>-->
<!--				<Col :lg="3" :md="3">-->
<!--					<Select placeholder="小区选择" :transfer="true" :filterable="true"-->
<!--							v-model="searchData.village_id">-->
<!--						<Option v-for="item in village" :value="item.village_id" :key="item.village_name">{{-->
<!--							item.village_name }}-->
<!--						</Option>-->
<!--					</Select>-->
<!--				</Col>-->
				<Col :lg="6" :md="6">
					<Input placeholder="小区名称、业主电话、业主姓名" v-model="searchData.housekeyword"></Input>
				</Col>
				<Col :lg="2" :md="2">
					<Input v-model="searchData.loudong_name" placeholder="座栋"></Input>
				</Col>
				<Col :lg="2" :md="2">
					<Input v-model="searchData.danyuan_name" placeholder="单元"></Input>
				</Col>
				<Col :lg="2" :md="2">
					<Input v-model="searchData.fanghao_name" placeholder="房号"></Input>
				</Col>

				<Col :lg="5" :md="5">
					<Button type="primary" @click="housedoSearchs">查询</Button>
					<Button type="primary" @click="houseclearSearch">清空</Button>
				</Col>

			</Row>
			<Row>
				<Col :lg="24" :md="24" style="margin-top: 10px">
					<Table :columns="houseColumns" :data="houseList" border script
						   @on-selection-change="houseselectionok"></Table>
					<div style="margin: 10px;overflow: hidden">
						<div style="float: right;">
							<Page :total="hpageTotal" :current="hpageCurrent" @on-change="changehousePage" show-total></Page>
						</div>
					</div>
				</Col>
			</Row>
		</Modal>
		<Modal v-model="customerModal" title="选择客源" :transfer="true" width="960" :mask-closable="false" style="z-index: 9999">
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
					<Button type="primary" @click="doSearchs">查询</Button>
				</Col>
			</Row>
			<Row>
				<Col :lg="24" :md="24" style="margin-top: 10px">
					<Table :columns="customerColumns" :data="customerList" border script
						   @on-selection-change="selectionok"></Table>
					<div style="margin: 10px;overflow: hidden">
						<div style="float: right;">
							<Page :total="cpageTotal" :current="pageCurrent" @on-change="changecustomerPage" show-total></Page>
						</div>
					</div>
				</Col>
			</Row>
		</Modal>
    </Row>

</template>

<script>
    export default {
        name: 'bargainSell',

        data () {
			const validatePhone = (rule, value, callback) => {
				var errors = [];
				if (!/^(13[0-9]|14[5-9]|15[012356789]|166|17[0-8]|18[0-9]|19[8-9])[0-9]{8}$/.test(value)) {
					callback('请输入正确的手机号码!');
				}
				callback(errors);
			};
            return {
				searchData:{
					hpageCurrent:0
				},
				village: [],
				housekeyword:'',
				cpageTotal:0,
				hpageTotal:0,
				hpageCurrent:1,
				//成交
				houseList:[],
				houseModal:false,
				houseselection:'',
				houseColumns: [
					{
						type: 'selection',
						width: 60,
						align: 'center'
					},
					{
						title: '状态',
						key: 'house_status',
						align: 'center'
					},
					{
						title: '房源编号',
						key: 'house_sn',
						align: 'center',
						width: 240
					},
					{
						title: '片区名称',
						key: 'dts_name',
						align: 'center'
					},
					{
						title: '小区名称',
						key: 'village_name',
						align: 'center'
					},
					{
						title: '建筑面积',
						key: 'jianzhumianji',
						align: 'center'

					}
				],
				peizhi: [],
				settings: {
					'dts':[]
				},
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
				customerModal: false,

				defaultList: [],
				imgName: '',
				house_imgs:[],
				xtoken: api_param.XAccessToken,
				imgurl: api_param.imgurl,
				uploadurl: api_param.apiurl + 'site/upload',
				visible: false,
				uploadList: [],
				yjuploadList:[],
				isDisable: false,
				users:[],
				d_id:'',
				formValidate: {
					order_deal_type:'',
					order_deal_date:'',
					order_deal_user:'',
					order_price:'',
					order_owner_commission:'',
					order_customer_commission:'',
					order_zaxiang_commission:'',
					owner_sn:'',
					owner_phone:'',
					owner_name:'',
					owner_sex:'先生',
					customer_sn:'',
					customer_sex:'先生',
					customer_phone:'',
					customer_name:'',
					order_deal_did:[],

				},
				ruleValidate: {
					order_deal_type: [{required: true, message: '请选择成交类型', trigger: 'change'}],
					order_deal_date: [{required: true, message: '请选择成交日期', trigger: 'blur'}],
					order_deal_user: [{required: true, message: '请填写成交人', trigger: 'blur'}],
					order_price: [{required: true, message: '请输入成交价格', trigger: 'blur'}],
					order_owner_commission:[{required: true, message: '请输入业主应收佣金', trigger: 'blur'}],
					order_customer_commission:[{required: true, message: '请输入客户应收佣金', trigger: 'blur'}],
					order_zaxiang_commission:[{required: true, message: '请输入杂项收入', trigger: 'change'}],
					owner_sn:[{required: true, message: '请选择房源编号', trigger: 'blur'}],
					owner_phone:[{required: true, validator: validatePhone, trigger: 'blur'}],
					owner_name:[{required: true, message: '请输入业主姓名', trigger: 'blur'}],
					customer_sn:[{required: true, message: '请选择客源编号', trigger: 'blur'}],
					customer_phone:[{required: true, validator: validatePhone, trigger: 'blur'}],
					customer_name: [{required: true, message: '请输入客户姓名', trigger: 'blur'}],
				},
				bargain:false,
				
				
				
				
                // 条件
                order_status: '',
                order_status_key: '',
                house_use: '',
                house_use_key: '',
                order_timechoose: '',
                order_timechoose_key: '',
                order_usertype: '',
                order_usertype_key: '',
                jgqj: '',
                jgqj_key: '',
                jiage: [],
                mjqj: '',
                mjqj_key: '',
                mianji: [],
                daterange: '',
                keyword: '',
                showList: false,
                topbutton: 0,
                exporturl: api_param.apiurl + 'ordersell/export?token=' + api_param.XAccessToken,
                totalnum: 0,
                currentpage: 1,
                listData: [],
                // 表格属性
                tableColumns: [], //
                tableColumnsChecked: ['order_sn','order_status', 'house_district',
                    'house_name', 'house_area', 'order_deal_date','order_deal_department','order_deal_username','order_deal_type','order_price',
					'house_sn','customer_sn','customer_name',,'owner_name','c_user','u_user','contract_sn'],
                defaultColumnsChecked: ['order_sn','order_status','house_district',
                    'house_name', 'house_area', 'order_deal_date','order_deal_department','order_deal_username', 'order_deal_type','order_price',
					'house_sn','customer_sn','customer_name','owner_name','c_user','u_user','contract_sn']
            };
        },
        created () {
            this.getSetting();
            this.getIndex();
        },

        methods: {
			// changeDts(value, selectedData) {
			// 	this.searchData.dts_id = value;
			// 	let dts_id = selectedData[1].value;
			// 	this.getDts(dts_id, 1);
			// },
			// getDts(dts_id, type) {
			// 	if (dts_id) {
			// 		this.$http.get(api_param.apiurl + 'village/getvillage', {
			// 			params: {
			// 				dts_id: dts_id
			// 			},
			// 			headers: {'X-Access-Token': api_param.XAccessToken}
			// 		}).then(function (response) {
			// 			if (response.data.code == 200) {// 这里是处理正确的回调
			// 				if (type == 1) {
			// 					this.village = response.data.data.list;
			// 				} else if (type == 2) {
			// 					this.exportvillage = response.data.data.list;
			// 				}
			// 			} else if (response.data.code == 401) {
			// 				this.$store.commit('logout', this);
			// 				this.$store.commit('clearOpenedSubmenu');
			// 				this.$router.push({
			// 					name: 'login'
			// 				});
			// 			} else {
			// 				this.$Message.warning(response.data.message);
			// 			}
			// 		}, function (response) {
			// 			this.$Message.error('你的网络开小差了^—^');
			// 		})
			// 	}
			// },
			getIndex () { // 列表页
				this.$http.get(api_param.apiurl + 'ordersell/getindex', {
					params: {
						order_status: this.order_status_key,
						house_use: this.house_use_key,
						order_timechoose: this.order_timechoose_key,
						daterange: this.daterange,
						house_area: this.mianji,
						order_price: this.jiage,
						order_usertype_key: this.order_usertype_key,
						kw: this.keyword,
						page: this.currentpage
					},
					headers: {'X-Access-Token': api_param.XAccessToken}
				}).then(function (response) {
					// 这里是处理正确的回调
					if (response.data.code == 200) {
						this.totalnum = parseInt(response.data.data.totalnum);
						this.listData = response.data.data.listdata;
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
				});
			},
			getSetting () { // 获取下拉菜单
				this.$http.get(api_param.apiurl + 'ordersell/getsetting', {
					params: {},
					headers: {'X-Access-Token': api_param.XAccessToken}
				}).then(function (response) {
					// 这里是处理正确的回调
					if (response.data.code == 200) {
						this.order_status = response.data.data.order_status;
						this.house_use = response.data.data.house_use;
						this.order_timechoose = response.data.data.order_timechoose;
						this.order_usertype = response.data.data.order_usertype;
						this.jgqj = response.data.data.jgqj;
						this.mjqj = response.data.data.mjqj;
						this.topbutton = response.data.data.topbutton;
						// this.house_imgs = response.data.data.house_other_imgs;
						if (response.data.data.customcolumns && response.data.data.customcolumns != null) {
							this.tableColumnsChecked = response.data.data.customcolumns;
						}
						this.peizhi = response.body.data.peizhi;
						this.settings.dts = response.data.data.dts;
						// console.log(dts);
						this.changeTableColumns();
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
				});
			},
			houseclearSearch(){
				for (let i in this.searchData) {
					if (i == 'dts_id') {
						this.$set(this.searchData, i, [])
					} else {
						this.$set(this.searchData, i, '')
					}
				}

				this.choosehouse();
			},
			housedoSearchs(){
				this.searchData.hpageCurrent = 1;
				this.choosehouse();
			},
			choosehouse() {
				this.houseModal = true;
				this.$http.post(api_param.apiurl + 'ordersell/houselist',
						this.searchData,
						{emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
				).then(function (response) {
					// 这里是处理正确的回调
					if (response.data.code == 200) {
						this.houseModal = true;
						this.houseList = response.data.data.listdata;
						this.hpageTotal = parseInt(response.data.data.totalnum);
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
			changehousePage(page){
				this.searchData.hpageCurrent = page;
				this.hpageCurrent = page;
				this.choosehouse();
			},
			houseModalCancel() {
				this.houseselection = '';
				this.houseModal = false;
			},
			dealhouseModalCancel(){
				this.houseselection = '';
				this.houseModal = false;
			},
			houseselectionok(selection) {
				this.houseselection = selection;
			},
			houseModalOk(){
				if (this.houseselection.length == 1) {
					this.formValidate.owner_sn = this.houseselection[0].house_sn;
					this.houseModal = false;
				} else if (this.selection.length > 1) {
					this.$Message.warning('最多选择1个');
				} else {
					this.$Message.warning('最少选择1个');
				}
			},
			doSearchs(){
				this.currentpage = 1;
				this.chooseCustomer();
			},
			changeuser(value,label){
				this.currentpage1 =1;
				// this.getIndex(1);
			},
			changeDepart (value, selectedData) {
				this.formValidate.bumen = selectedData;
				this.formValidate.d_id = value.pop();
				this.users = this.peizhi.users[this.formValidate.d_id];
				// console.log(this.users);
				// this.currentpage1 =1;
				// this.getIndex(1);
			},
			changecustomerPage(page){
				this.pageCurrent = page;
				this.chooseCustomer();
			},
			chooseCustomer() {
				this.customerModal = true;
				this.$http.post(api_param.apiurl + 'ordersell/customerlist',
						{
							'page': this.pageCurrent,
							// 'status': this.customerStatus,
							'type': 2,
							'kw': this.keyword,
						},
						{emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
				).then(function (response) {
					// 这里是处理正确的回调
					if (response.data.code == 200) {
						this.customerModal = true;
						this.customerList = response.data.data.listdata;
						this.cpageTotal = parseInt(response.data.data.totalnum);
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
			selectionok(selection) {
				this.selection = selection;
			},
			dealModalCancel() {
				this.selection = '';
				this.customerModal = false;
			},
			customerModalCancel() {
				this.selection = '';
				this.customerModal = false;
			},
			customerModalOk() {
				if (this.selection.length == 1) {
					this.formValidate.customer_sn = this.selection[0].xuqiubianhao;
					this.formValidate.customer_phone = this.selection[0].tel_phone;
					this.formValidate.customer_name = this.selection[0].customer_name;
					this.formValidate.customer_sex = this.selection[0].customer_sex;
					// console.log(this.formValidate.customer_sex);
					this.customerModal = false;
				} else if (this.selection.length > 1) {
					this.$Message.warning('最多选择1个');
				} else {
					this.$Message.warning('最少选择1个');
				}
			},
			handleView (name) {
				this.imgName = name;
				this.visible = true;
			},
			handleRemove (file) {
				this.uploadList = [];
			},
			handleRemove1(file) {
				this.yjuploadList = [];
			},

			handleSuccess (res, file, filelist) {
				file.url = api_param.imgurl + res.data.url;
				file.name = res.data.url;
				this.uploadList.push({'name': file.name, 'url': file.url});
				const fileList = this.$refs.upload.fileList;
				this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
				// console.log(this.uploadList);
			},
			handleSuccess1 (res, file, filelist) {
				file.url = api_param.imgurl + res.data.url;
				file.name = res.data.url;
				this.yjuploadList.push({'name': file.name, 'url': file.url});
				const fileList = this.$refs.upload.fileList;
				this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
				// console.log(this.uploadList);
			},
			handleFormatError (file) {
				this.$Notice.warning({
					title: '文件上传失败',
					desc: '文件 ' + file.name + ' 类型错误, 请选择 gif, jpg or png.'
				});
			},

			handleMaxSize (file) {
				this.$Notice.warning({
					title: '文件上传失败',
					desc: '文件  ' + file.name + ' 大小必须小于 10M.'
				});
			},
			handleBeforeUpload () {
				const check = this.uploadList.length < 2;
				if (!check) {
					this.$Notice.warning({
						title: '最多上传2张图片'
					});
				}
				return check;
			},

			modalCancel(){
				this.$emit('resetModal');
				// this.formValidate.village = {};
				this.$refs['formValidate'].resetFields();
				this.uploadList = [];
				this.yjuploadList= [];
				this.bargain = false;
			},
			modalOk() {

				if(this.uploadList.length == 1){
					this.formValidate.hetong_image = this.uploadList[0]['name'];
				}else{
					this.$Message.warning('请上传合同确认书');
					return;
				}
				if(this.yjuploadList.length == 1){
					this.formValidate.yongjin_image = this.yjuploadList[0]['name'];
				}else{
					this.$Message.warning('请上传佣金确认书');
					return;
				}

				this.$refs['formValidate'].validate((valid) => {
					if (valid) {
						this.isDisable = true;
						let action = 'ordersell/add';
						this.$http.post(api_param.apiurl + action,
								this.formValidate,
								{emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
						).then(function (response) {
							// 这里是处理正确的回调
							if (response.data.code == 200) {
								this.isDisable = false;
								this.$refs['formValidate'].resetFields();
								this.uploadList = [];
								this.yjuploadList= [];
								this.bargain = false;
								this.getIndex();
							} else if (response.data.code == 401) {
								this.$store.commit('logout', this);
								this.$store.commit('clearOpenedSubmenu');
								this.$router.push({
									name: 'login'
								});
							} else {
								this.$Message.warning(response.data.message);
								this.newsModal = false;
								this.isDisable = false;
							}
						}, function (response) {
							// 这里是处理错误的回调
							console.log(response);

						})
					}
				});
			},

            // 搜索
            selectDate (date) { // 选择日期回调
                this.daterange = date;
            },
            selectArea (selectedData) { // 状态选择
                this.mianji = selectedData.split('-');
            },
            selectPrice (selectedData) { // 状态选择
                this.jiage = selectedData.split('-');
            },
            doSearch () {
                this.currentpage = 1;
                this.getIndex();
            },
            clearSearch () {
                this.order_status_key = '';
                this.house_use_key = '';
                this.order_timechoose_key = '';
                this.order_usertype_key = '';
                this.jgqj_key = '';
                this.jiage = [];
                this.mjqj_key = '';
                this.mianji = [];
                this.daterange = '';
                this.keyword = '';
                this.getIndex();
            },
            // 分页
            changePage (page) {
                this.currentpage = page;
                this.getIndex();
            },
            // 表格属性
            getTableColumns (type) {
                const tableColumnList = {
                    index: {
						title: '编号',
                        type: 'index',
                        width: 60,
                        align: 'center',
                    },
                    order_sn: {
                        title: '成交编号',
                        key: 'order_sn',
                        width: 150,
                        align: 'center',
                        render: (h, params) => {
                            let ret =[];
                            if(params.row.is_jump == 1 && params.row.order_status_control !='驳回' && params.row.order_status !='审核中'){
                                ret.push(h('div', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    style: {
                                        color: '#2d8cf0',
                                        textDecoration: 'underline',
                                        cursor: 'pointer'
                                    },
                                    domProps: {
                                        innerHTML: params.row.order_sn
                                    },
                                    on: {
                                        click: () => {
                                            let argu = {orderId: params.row.order_id};
                                            this.$router.push({
                                                name: 'bargainDetails',
                                                params: argu
                                            });
                                        }
                                    }
                                }));
                            }else{
                                ret.push(h('div', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    style: {
                                        //color: '#2d8cf0',
                                        //textDecoration: 'underline',
                                        cursor: 'pointer'
                                    },
                                    domProps: {
                                        innerHTML: params.row.order_sn
                                    }
                                }));
                            }
                            return h('div', ret);
                        }
                    },
                    order_status: {
                        title: '订单状态',
                        key: 'order_status',
                        width: 88,
                        align: 'center'
                    },
                    order_price: {
                        title: '成交价',
                        key: 'order_price',
                        width: 88,
                        align: 'center'
                    },
                    order_deal_date: {
                        title: '成交日期',
                        key: 'order_deal_date',
                        width: 88,
                        align: 'center'
                    },
					order_deal_department: {
						title: '成交部门',
						key: 'order_deal_department',
						width: 150,
						align: 'center'
					},
					order_deal_username: {
						title: '成交人',
						key: 'order_deal_username',
						width: 88,
						align: 'center'
					},
                    house_sn: {
                        title: '房源编号',
                        key: 'owner_sn',
                        width: 128,
                        align: 'center'
                    },
                    customer_sn: {
                        title: '客源编号',
                        key: 'customer_sn',
                        width: 128,
                        align: 'center'
                    },
					customer_name: {
						title: '客户姓名',
						key: 'customer_name',
						width: 88,
						align: 'center'
					},
                    order_deal_type: {
                        title: '交易类型',
                        key: 'order_deal_type',
                        width: 128,
                        align: 'center',
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.order_deal_type == 1){
                                texts = '本公司自有';
                            }else if(params.row.order_deal_type == 2){
                                texts = '合作房源';
                            }else if(params.row.order_deal_type == 3){
                                texts = '合作客源';
                            }else if(params.row.order_deal_type == 4){
                                texts = '代办过户';
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    house_district: {
                        title: '区域',
                        key: 'dts_name',
                        width: 88,
                        align: 'center'
                    },
                    house_name: {
                        title: '小区',
                        key: 'house_name',
                        width: 88,
                        align: 'center'
                    },
                    house_area: {
                        title: '面积',
                        key: 'house_area',
                        width: 88,
                        align: 'center'
                    },
                    c_user: {
                        title: '房源录入人',
                        key: 'c_user',
                        width: 88,
                        align: 'center'
                    },
                    u_user: {
                        title: '房源维护人',
                        key: 'u_user',
                        width: 88,
                        align: 'center'
                    },
                    owner_name: {
                        title: '业主姓名',
                        key: 'owner_name',
                        width: 88,
                        align: 'center'
                    },
					contract_sn: {
						title: '成交合同编号',
						key: 'contract_sn',
						align: 'center',
						width: 128,
					}
                };

                let data = [tableColumnList.index];
                console.log(data);
                if (type == 1) {
                    this.tableColumnsChecked.forEach(col => data.push(tableColumnList[col]));
                } else {
                    this.tableColumnsChecked = this.defaultColumnsChecked;
                    this.defaultColumnsChecked.forEach(col => {
                        data.push(tableColumnList[col]);
                    });
                }

                return data;
            },
            changeTableColumns () {
                this.tableColumns = this.getTableColumns(1);
            },
            resetColumns () {
                this.tableColumns = this.getTableColumns(2);
                this.$http.post(api_param.apiurl + 'ordersell/customcolumns',
                    {
                        'module': 2,
                        'columns': JSON.stringify(this.defaultColumnsChecked)
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.showList = false;
                        this.$Message.success('保存成功');
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            },
            saveColumns () {
                this.$http.post(api_param.apiurl + 'ordersell/customcolumns',
                    {
                        'module': 2,
                        'columns': JSON.stringify(this.tableColumnsChecked)
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.showList = false;
                        this.$Message.success('保存成功');
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            },

            toggleFav (index) {
                this.listData[index].fav = this.listData[index].fav === 0 ? 1 : 0;
            }
        },
        mounted () {
			// this.uploadList = this.$refs.upload.fileList;
            this.changeTableColumns();

        },
		filters: {
			typeTxt: function (value) {
				let typeTxt='';
				switch(value){
					case '1':typeTxt='封面';break;
					case '2':typeTxt='楼栋号';break;
					case '3':typeTxt='房号';break;
					case '4':typeTxt='主卧';break;
					case '5':typeTxt='次卧';break;
					case '6':typeTxt='客厅';break;
					case '7':typeTxt='餐厅';break;
					case '8':typeTxt='阳台';break;
					case '9':typeTxt='卫生间';break;
					case '10':typeTxt='小区外观';break;
					case '11':typeTxt='户型图';break;
					case '12':typeTxt='委托合同';break;
					case '13':typeTxt='独家合同';break;
					case '14':typeTxt='产证图片';break;
					case '15':typeTxt='身份证正面';break;
					case '16':typeTxt='身份证反面';break;
					case '17':typeTxt='其他';break;
					default :typeTxt='其他';break;
				}
				return typeTxt;
			}
		}
    };
</script>
