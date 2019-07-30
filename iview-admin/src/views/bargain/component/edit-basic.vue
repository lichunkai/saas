<style lang="less">
    @import "roomdetails.less";

    .demo-upload-list {
        display: inline-block;
        width: 108px;
        height: 108px;
        text-align: center;
        line-height: 108px;
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

<template>
    <Row :gutter="10">
        <Col :lg="24" :md="24" style="padding-bottom: 30px;">
        <Row>
            <Col :lg="12" :md="12" style="line-height: 32px;">
            房源地址：<span>{{formDealValidate.houseinfo.dts_name}}</span>
            <span>{{formDealValidate.houseinfo.village_name}}</span>
            <span>{{formDealValidate.houseinfo.loudong_name}}栋{{formDealValidate.houseinfo.danyuan_name}}单元{{formDealValidate.houseinfo.fanghao_name}}室</span>
            </Col>
            <Col :lg="12" :md="12">
            <Row type="flex" justify="end" :gutter="5">
                <Col>
                <Button type="primary" @click="showDealModal">修改成交信息</Button>
                </Col>
                <Col v-if="formDealValidate.order_status == '进行中'">
                <Button type="primary" @click="showStatusModal">修改成交状态</Button>
                </Col>
            </Row>
            </Col>
        </Row>
        </Col>
        <Col :lg="24" :md="24" class="basicStrong">
            <Row>
				<Col :lg="12" :md="12">
					<Row>
						<Col :lg="24" :md="24"><div class="rmain-title">房源信息展示</div></Col>
					    <Col :lg="24" :md="24">
					    <Row>
					        <Col :lg="8" :md="8" class="rmain">
					        <div>房屋户型:</div>
					        <div>
					            <span v-if="formDealValidate.houseinfo.huxing_shi">{{formDealValidate.houseinfo.huxing_shi}}室</span>
					            <span v-if="formDealValidate.houseinfo.huxing_ting">{{formDealValidate.houseinfo.huxing_ting}}厅</span>
					            <span v-if="formDealValidate.houseinfo.huxing_chu">{{formDealValidate.houseinfo.huxing_chu}}厨</span>
					            <span v-if="formDealValidate.houseinfo.huxing_wei">{{formDealValidate.houseinfo.huxing_wei}}卫</span>
					            <span v-if="formDealValidate.houseinfo.yangtai">{{formDealValidate.houseinfo.yangtai}}阳台</span>
					        </div>
					        </Col>
					        <Col :lg="8" :md="8"  class="rmain">
					        <div>房屋面积:</div>
					        <div>{{formDealValidate.houseinfo.jianzhumianji}}平米</div>
					        </Col>
					        <Col :lg="8" :md="8"  class="rmain">
					        <div>房屋楼层:</div>
					        <div>
					            {{formDealValidate.houseinfo.louceng_now}}/{{formDealValidate.houseinfo.louceng_total}}
					        </div>
					        </Col>
					        <Col :lg="8" :md="8"  class="rmain">
					        <div>房屋朝向:</div>
					        <div>{{formDealValidate.houseinfo.chaoxiang}}</div>
					        </Col>
					        <Col :lg="8" :md="8"  class="rmain">
					        <div>房屋装修:</div>
					        <div>{{formDealValidate.houseinfo.zhuangxiu}}</div>
					        </Col>
							<Col :lg="8" :md="8"  class="rmain">
							<div>建筑年代:</div>
							<div>2018-05-50</div>
							</Col>
							<Col :lg="8" :md="8"  class="rmain">
							<div>建筑结构:</div>
							<div>钢结构</div>
							</Col>
					        <Col :lg="8" :md="8"  class="rmain">
					        <div>产权性质:</div>
					        <div>{{formDealValidate.houseinfo.chanquanxingzhi}}</div>
					        </Col>
							<Col :lg="8" :md="8"  class="rmain">
							<div>产证日期:</div>
							<div>2018-08-12</div>
							</Col>
							<Col :lg="8" :md="8"  class="rmain">
							<div>录入时间:</div>
							<div>2018-08-12 14:50:21</div>
							</Col>
					    </Row>
					    </Col>
						
					</Row>
				</Col>
				<Col :lg="12" :md="12">
					<Row>
						<Col :lg="24" :md="24"><div class="rmain-title">房/客源信息展示</div></Col>
						<Col :lg="12" :md="12" class="rmain">
							<div>协议编号:</div>
					        <div>{{formDealValidate.agreement_sn}}</div>
						</Col>
						<Col :lg="12" :md="12" class="rmain">
							<div>合同编号:</div>
						    <div>{{formDealValidate.order_sn}}</div>
						</Col>
						<Col :lg="12" :md="12" class="rmain">
							<div>房源编号:</div>
						    <div><a :href="formDealValidate.room_url">{{formDealValidate.owner_sn}}</a></div>
						</Col>
						<Col :lg="12" :md="12" class="rmain">
							<div>客源编号:</div>
						    <div><a :href="formDealValidate.customer_url">{{formDealValidate.customer_sn}}</a></div>
						</Col>
						<Col :lg="12" :md="12" class="rmain">
							<div>交易类型:</div>
						    <div>买卖</div>
						</Col>
						<Col :lg="12" :md="12" class="rmain">
							<div>成交人:</div>
						    <div>{{formDealValidate.order_deal_username}}</div>
						</Col>
						<Col :lg="12" :md="12" class="rmain">
							<div>成交时间:</div>
						    <div>{{formDealValidate.show_order_deal_date}}</div>
						</Col>
						<Col :lg="12" :md="12" class="rmain">
							<div>权证人:</div>
						    <div>{{formDealValidate.order_property_username}}</div>
						</Col>
					</Row>
				</Col>
			</Row>
        </Col>
        <Col :lg="24" :md="24" class="basicStrong">
			<Row>
				<Col :lg="12" :md="12">
					<Row>
						<Col :lg="24" :md="24"><div class="rmain-title">成交信息展示</div></Col>
						<Col :lg="24" :md="24">
						<Row>
						    <Col :lg="24" :md="24" class="rmain">
						    <div>成交价:</div>
						    <div>{{formDealValidate.order_price}}万元</div>
						    </Col>
						    <Col :lg="8" :md="8" class="rmain">
						    <div>业主佣金:</div>
						    <div>{{formDealValidate.order_owner_commission}}元</div>
						    </Col>
							<Col :lg="8" :md="8" class="rmain">
							<div>实收佣金:</div>
							<div>****元</div>
							</Col>
							<Col :lg="8" :md="8" class="rmain">
							<div>未收佣金:</div>
							<div>****元</div>
							</Col>
						    <Col :lg="8" :md="8" class="rmain">
						    <div>客户佣金:</div>
						    <div>{{formDealValidate.order_customer_commission}}元</div>
						    </Col>
							<Col :lg="8" :md="8" class="rmain">
							<div>实收佣金:</div>
							<div>****元</div>
							</Col>
							<Col :lg="8" :md="8" class="rmain">
							<div>未收佣金:</div>
							<div>****元</div>
							</Col>
							<Col :lg="8" :md="8" class="rmain">
							<div>杂项收入:</div>
							<div>{{formDealValidate.order_zaxiang_commission}}元</div>
							</Col>
							<Col :lg="8" :md="8" class="rmain">
							<div>实收杂项:</div>
							<div>****元</div>
							</Col>
							<Col :lg="8" :md="8" class="rmain">
							<div>未收杂项:</div>
							<div>****元</div>
							</Col>
						</Row>
						</Col>
					</Row>
				</Col>
				<Col :lg="12" :md="12">
					<Row>
						<Col :lg="24" :md="24"><div class="rmain-title">业主/客户信息展示</div></Col>
						<Col :lg="12" :md="12">
							<Row>
								<Col :lg="24" :md="24" class="rmain">
									<div>业主姓名:</div>
								    <div>{{formDealValidate.owner_name}}</div>
								</Col>
								<Col :lg="24" :md="24" class="rmain">
<!--									<div>证件号码:</div>-->
<!--								    <div> {{formDealValidate.owner_idno}}</div>-->
								</Col>
								<Col :lg="24" :md="24" class="rmain">
									<div>联系方式:</div>
								    <div>
<!--										<p v-for="v  in this.formDealValidate.jiafang ">-->
<!--								            <span>{{v.phone}}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>{{v.relation}}</span>-->
<!--										</p>-->
                                        <div> {{formDealValidate.owner_phone}}</div>
								    </div>
<!--									<div style="margin-left: 20px;"><p @click="showPhoneModal(1)" class="addNewNumber">新增</p></div>-->
								</Col>
								<Col :lg="24" :md="24" class="rmain">
<!--									<div>联系地址:</div>-->
<!--								    <div>{{formDealValidate.owner_address}}</div>-->
								</Col>
							</Row>
						</Col>
						<Col :lg="12" :md="12">
							<Row>
								<Col :lg="24" :md="24" class="rmain">
									<div>客户姓名:</div>
								    <div>{{formDealValidate.customer_name}}</div>
								</Col>
								<Col :lg="24" :md="24" class="rmain">
<!--									<div>证件号码:</div>-->
<!--								    <div>{{formDealValidate.customer_idno}}</div>-->
								</Col>
								<Col :lg="24" :md="24" class="rmain">
									<div>联系方式:</div>
								    <div>
<!--										<p v-for="v  in this.formDealValidate.yifang ">-->
<!--										    <span>{{v.phone}}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>{{v.relation}}</span></p>-->
                                        <div>{{formDealValidate.customer_phone}}</div>
								    </div>
<!--									<div style="margin-left: 20px;"><p @click="showPhoneModal(2)" class="addNewNumber">新增</p></div>-->
								</Col>
								<Col :lg="24" :md="24" class="rmain">
<!--									<div>联系地址:</div>-->
<!--								    <div>{{formDealValidate.customer_address}}</div>-->
								</Col>
							</Row>
						</Col>
					</Row>
				</Col>
			</Row>
        </Col>
        <Modal v-model="changeStatus" title="变更状态">
            <div slot="footer">
                <Button type="text" size="large" @click="statusModalCancel">取消</Button>
                <Button type="primary" size="large" @click="statusModalOk">确定</Button>
            </div>
            <Form ref="formStatusValidate" :model="formStatusValidate" :rules="ruleStatusValidate" :label-width="80">
                <FormItem label="原状态" prop="">
                    <Input disabled v-model="formDealValidate.order_status"></Input>
                </FormItem>
                <FormItem label="现状态" prop="status">
                    <Select placeholder="" :transfer="true" v-model="formStatusValidate.status">
                        <Option v-for="(item,index) in formDealValidate.orderstatus" :value="item.child_name">
                            {{item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="变更理由" prop="remark">
                    <Input placeholder="" v-model="formStatusValidate.remark"></Input>
                </FormItem>
            </Form>
        </Modal>
        <Modal v-model="addPhone" title="新增电话" width="480" :mask-closable="false">
            <div slot="footer">
                <Button type="text" size="large" @click="phoneModalCancel">取消</Button>
                <Button type="primary" size="large" @click="phoneModalOk">确定</Button>
            </div>
            <Form ref="formPhoneValidate" :model="formPhoneValidate" :rules="rulePhoneValidate" :label-width="80">
                <FormItem label="电话" prop="phone">
                    <Input v-model="formPhoneValidate.phone" placeholder="输入联系方式"></Input>
                </FormItem>
                <FormItem label="关系" prop="relation">
                    <Select placeholder="" v-model="formPhoneValidate.relation" :transfer="true">
                        <Option value="本人">本人</Option>
                        <Option value="配偶">配偶</Option>
                        <Option value="朋友">朋友</Option>
                        <Option value="委托人">委托人</Option>
                    </Select>
                </FormItem>
            </Form>
        </Modal>
        <Modal :title="orderModelTitle" v-model="editDeal" width="960" class="rdeal" :mask-closable="false">
            <div slot="footer">
                <Button type="text" size="large" @click="dealModalCancel">取消</Button>
                <Button type="primary" size="large" @click="dealModalOk">确定</Button>
            </div>
            <Form ref="formDealValidate" :model="formDealValidate" :rules="ruleDealValidate" :label-width="80">
                <Row>
                    <Col :lg="24" :md="24" class="formItemClass" style=" background-color: #e3e8ee;color: #666;font-size: 14px;padding-left: 20px !important;line-height: 38px;
    margin-bottom: 20px;">
                        <span>业主/客户信息</span>
                    </Col>
                </Row>
                <Row>
                    <Col :lg="8" :md="8">
                        <FormItem label="房源编号" prop="owner_sn">
                            <Input placeholder="" v-model="formDealValidate.owner_sn" disabled="true" readonly></Input>
                        </FormItem>
                    </Col>
                    <Col :lg="8" :md="8">
                        <FormItem label="业主电话" prop="owner_phone">
                            <Input placeholder="" v-model="formDealValidate.owner_phone"></Input>
                        </FormItem>
                    </Col>
                    <Col :lg="8" :md="8">
                        <Row>
                            <Col :lg="16" :md="16">
                                <FormItem label="业主姓名" prop="owner_name">
                                    <Input placeholder="" v-model="formDealValidate.owner_name"></Input>
                                </FormItem>
                            </Col>
                            <Col :lg="7" :md="7" class="ownerSex">
                                <FormItem prop="owner_sex">
                                    <Select v-model="formDealValidate.owner_sex" :transfer="true">
                                        <Option value="先生">先生</Option>
                                        <Option value="女士">女士</Option>
                                    </Select>
                                </FormItem>
                            </Col>
                        </Row>
                    </Col>
                </Row>
                <Row>
                    <Col :lg="8" :md="8">

                        <FormItem label="客户编号" prop="customer_sn">
                            <Input placeholder="" v-model="formDealValidate.customer_sn" disabled="true" readonly></Input>
                        </FormItem>

<!--                            <Col :lg="7" :md="7" offset="1">-->
<!--                                <Button type="ghost" @click="chooseCustomer">选择客源</Button>-->
<!--                            </Col>-->

                    </Col>
                    <Col :lg="8" :md="8">
                        <FormItem label="客户电话" prop="customer_phone">
                            <Input placeholder="" v-model="formDealValidate.customer_phone"></Input>
                        </FormItem>
                    </Col>
                    <Col :lg="8" :md="8">
                        <Row>
                            <Col :lg="16" :md="16">
                                <FormItem label="客户姓名" prop="customer_name">
                                    <Input placeholder="" v-model="formDealValidate.customer_name"></Input>
                                </FormItem>
                            </Col>
                            <Col :lg="7" :md="7" class="ownerSex">
                                <FormItem prop="customer_sex">
                                    <Select v-model="formDealValidate.customer_sex" :transfer="true">
                                        <Option value="先生">先生</Option>
                                        <Option value="女士">女士</Option>
                                    </Select>
                                </FormItem>
                            </Col>
                        </Row>
                    </Col>
                </Row>

                <Row>
                    <Col :lg="24" :md="24" class="formItemClass" style=" background-color: #e3e8ee;color: #666;font-size: 14px;padding-left: 20px !important;line-height: 38px;
    margin-bottom: 20px;">
                        <span>成交/佣金信息</span>
                    </Col>
                </Row>

                <Row>
                    <Col :lg="24" :md="24">
                    <row>
                        <Col :lg="8" :md="8">
                        <FormItem label="交易类型" prop="order_deal_type">
                            <Select placeholder="" v-model="formDealValidate.order_deal_type"  :transfer="true">
                                <Option value="1" >本公司自有</Option>
                                <Option value="2" >合作房源</Option>
                                <Option value="3" >合作客源</Option>
                                <Option value="4" >代办过户</Option>
                            </Select>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                            <FormItem label="成交日期" prop="order_deal_date">
                                <DatePicker type="date" :value="formDealValidate.order_deal_date" format="yyyy-MM-dd"
                                            style="width: 100%"
                                            @on-change="formDealValidate.order_deal_date=$event" disabled></DatePicker>
                            </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                            <Row>
                                <FormItem label="成交价格" prop="order_price">
                                    <Input v-model="formDealValidate.order_price" placeholder="" >
                                        <span slot="append">元/月</span>
                                    </Input>
                                </FormItem>
                            </Row>
                            <!--                        <Row v-else>-->
                            <!--                            <Col :lg="18" :md="18">-->
                            <!--                            <FormItem label="成交价格" prop="order_price">-->
                            <!--                                <Input v-model="formDealValidate.order_price" placeholder="" ><span-->
                            <!--                                    slot="append">万元</span></Input>-->
                            <!--                            </FormItem>-->
                            <!--                            </Col>-->
                            <!--                            <Col :lg="6" :md="6" class="daikuan">-->
                            <!--                            <FormItem prop="order_loan">-->
                            <!--                                <Checkbox v-model="formDealValidate.order_loan" prop="order_loan">有贷款</Checkbox>-->
                            <!--                            </FormItem>-->
                            <!--                            </Col>-->
                            <!--                        </Row>-->
                        </Col>
                    </row>
                    </Col>
                    <Col :lg="24" :md="24">
                        <Row>

                            <Col :lg="8" :md="8">
                                <FormItem label="业主应收佣金" prop="order_owner_commission">
                                    <Input v-model="formDealValidate.order_owner_commission" placeholder="" ><span
                                            slot="append">元</span></Input>
                                </FormItem>
                            </Col>
                            <Col :lg="8" :md="8">
                                <FormItem label="客户应收佣金" prop="order_customer_commission">
                                    <Input v-model="formDealValidate.order_customer_commission" placeholder=""
                                    ><span slot="append">元</span></Input>
                                </FormItem>
                            </Col>
                            <Col :lg="8" :md="8">
                                <FormItem label="杂项收入佣金" prop="order_zaxiang_commission">
                                    <Input v-model="formDealValidate.order_zaxiang_commission" placeholder=""
                                    ><span slot="append">元</span></Input>
                                </FormItem>
                            </Col>
                        </Row>
                    </Col>
                    <Col :lg="24" :md="24">
                    <Row>

                        <Col :lg="8" :md="8">
                        <FormItem label="成交人部门" prop="d_id">
                            <Cascader :data="formDealValidate.departchoose"
                                      v-model="departcascader.chengjiao" change-on-select
                                      @on-change="cjhandleChange" disabled></Cascader>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem label="成交人" prop="order_deal_user">
                            <Select placeholder="" v-model="formDealValidate.order_deal_user" :label-in-value="true"
                                    @on-change="selectDealuser" disabled>
                                <Option v-for="(item,index) in formDealValidate.cjuser" :value="item.u_id"
                                        :key="item.u_id">{{item.u_name}}
                                </Option>
                            </Select>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8" v-if="formDealValidate.order_type!=1">
                            <FormItem label="权证人" prop="order_property_user">
                                <!--                            <Select placeholder="" v-model="formDealValidate.order_property_user" :label-in-value="true"-->
                                <!--                                    @on-change="selectPropertyuser">-->
                                <!--                                <Option v-for="(item,index) in formDealValidate.qzuser" :value="item.u_id"-->
                                <!--                                        :key="item.u_id">{{item.u_name}}-->
                                <!--                                </Option>-->
                                <!--                            </Select>-->
                                <Input v-model="formDealValidate.order_property_user" placeholder=""
                                ></Input>
                            </FormItem>
                        </Col>
                    </Row>
                    </Col>

<!--                    <Col :lg="24" :md="24">-->
<!--                    <Row>-->

<!--                        <Col :lg="8" :md="8" v-if="formDealValidate.order_type==1">-->
<!--                        <FormItem label="开始时间" prop="order_start_time">-->
<!--                            <DatePicker type="date" :value="formDealValidate.order_start_time" format="yyyy-MM-dd"-->
<!--                                        @on-change="formDealValidate.order_start_time=$event"></DatePicker>-->
<!--                        </FormItem>-->
<!--                        </Col>-->
<!--                        <Col :lg="8" :md="8" v-if="formDealValidate.order_type==1">-->
<!--                        <FormItem label="结束时间" prop="order_end_time">-->
<!--                            <DatePicker type="date" :value="formDealValidate.order_end_time" format="yyyy-MM-dd"-->
<!--                                        @on-change="formDealValidate.order_end_time=$event"></DatePicker>-->
<!--                        </FormItem>-->
<!--                        </Col>-->
<!--                        <Col :lg="8" :md="8" v-if="formDealValidate.order_type!=1">-->
<!--                        <FormItem label="权证部门" prop="d_id">-->
<!--                            <Cascader :data="formDealValidate.departchoose"-->
<!--                                      v-model="departcascader.quanzheng" change-on-select-->
<!--                                      @on-change="qzhandleChange"></Cascader>-->
<!--                        </FormItem>-->
<!--                        </Col>-->


<!--                    </Row>-->
<!--                    </Col>-->
<!--                    <Col :lg="24" :md="24">-->
<!--                    <Row>-->
<!--                        <Col :lg="8" :md="8">-->
<!--                        <FormItem label="土地证号" prop="house_land_certificate">-->
<!--                            <Input v-model="formDealValidate.house_land_certificate" placeholder=""></Input>-->
<!--                        </FormItem>-->
<!--                        </Col>-->
<!--                        <Col :lg="8" :md="8">-->
<!--                        <FormItem label="产权证号" prop="house_property_certificate">-->
<!--                            <Input v-model="formDealValidate.house_property_certificate" placeholder=""></Input>-->
<!--                        </FormItem>-->
<!--                        </Col>-->
<!--                        <Col :lg="8" :md="8">-->
<!--                        <FormItem label="交易类型" prop="order_deal_type">-->
<!--                            <Select placeholder="" v-model="formDealValidate.order_deal_type"  :transfer="true">-->
<!--                                <Option value="1" >本公司自有</Option>-->
<!--                                <Option value="2" >合作房源</Option>-->
<!--                                <Option value="3" >合作客源</Option>-->
<!--                                <Option value="4" >代办过户</Option>-->
<!--                            </Select>-->
<!--                        </FormItem>-->
<!--                        </Col>-->
<!--                    </Row>-->
<!--                    </Col>-->
<!--                    <Col :lg="24" :md="24">-->
<!--                    <Row>-->
<!--                        <Col :lg="24" :md="24">-->
<!--                        <FormItem label="补充条款" prop="order_addition_terms">-->
<!--                            <Input type="textarea" :rows="2" placeholder=""-->
<!--                                   v-model="formDealValidate.order_addition_terms"></Input>-->
<!--                        </FormItem>-->
<!--                        </Col>-->
<!--                    </Row>-->
<!--                    </Col>-->

<!--                    <Col :lg="24" :md="24">-->
<!--                    <Row>-->
<!--                        <Col :lg="8" :md="8">-->
<!--                        <FormItem label="甲方编号" prop="owner_sn">-->
<!--                            <Input placeholder="" v-model="formDealValidate.owner_sn"></Input>-->
<!--                        </FormItem>-->
<!--                        </Col>-->
<!--                        <Col :lg="8" :md="8">-->
<!--                        <FormItem label="业主电话" prop="owner_phone">-->
<!--                            <Input placeholder="" v-model="formDealValidate.owner_phone"></Input>-->
<!--                        </FormItem>-->
<!--                        </Col>-->
<!--                        <Col :lg="8" :md="8">-->
<!--                        <Row>-->
<!--                            <Col :lg="16" :md="16">-->
<!--                            <FormItem label="甲方姓名" prop="owner_name">-->
<!--                                <Input placeholder="" v-model="formDealValidate.owner_name"></Input>-->
<!--                            </FormItem>-->
<!--                            </Col>-->
<!--                            <Col :lg="7" :md="7" class="ownerSex">-->
<!--                            <FormItem prop="owner_sex">-->
<!--                                <Select v-model="formDealValidate.owner_sex" :transfer="true">-->
<!--                                    <Option value="先生">先生</Option>-->
<!--                                    <Option value="女士">女士</Option>-->
<!--                                </Select>-->
<!--                            </FormItem>-->
<!--                            </Col>-->
<!--                        </Row>-->
<!--                        </Col>-->
<!--                    </Row>-->
<!--                    </Col>-->
<!--                    <Col :lg="24" :md="24">-->
<!--                    <Row>-->
<!--                        <Col :lg="8" :md="8">-->
<!--                        <FormItem label="证件号码" prop="owner_idno">-->
<!--                            <Input placeholder="" v-model="formDealValidate.owner_idno"></Input>-->
<!--                        </FormItem>-->
<!--                        </Col>-->
<!--                        <Col :lg="16" :md="16">-->
<!--                        <FormItem label="联系地址" prop="owner_address">-->
<!--                            <Input placeholder="" v-model="formDealValidate.owner_address"></Input>-->
<!--                        </FormItem>-->
<!--                        </Col>-->
<!--                    </Row>-->
<!--                    </Col>-->
<!--                    <Col :lg="24" :md="24">-->
<!--                    <Row>-->
<!--                        <Col :lg="8" :md="8">-->
<!--                        <Row>-->
<!--                            <Col :lg="16" :md="16">-->
<!--                            <FormItem label="乙方编号" prop="customer_sn">-->
<!--                                <Input placeholder="" v-model="formDealValidate.customer_sn" readonly></Input>-->
<!--                            </FormItem>-->
<!--                            </Col>-->
<!--                            <Col :lg="7" :md="7" offset="1">-->
<!--                            <Button type="ghost" @click="chooseCustomer">选择客源</Button>-->
<!--                            </Col>-->
<!--                        </Row>-->
<!--                        </Col>-->
<!--                        <Col :lg="8" :md="8">-->
<!--                        <FormItem label="客户电话" prop="customer_phone">-->
<!--                            <Input placeholder="" v-model="formDealValidate.customer_phone"></Input>-->
<!--                        </FormItem>-->
<!--                        </Col>-->
<!--                        <Col :lg="8" :md="8">-->
<!--                        <Row>-->
<!--                            <Col :lg="16" :md="16">-->
<!--                            <FormItem label="乙方姓名" prop="customer_name">-->
<!--                                <Input placeholder="" v-model="formDealValidate.customer_name"></Input>-->
<!--                            </FormItem>-->
<!--                            </Col>-->
<!--                            <Col :lg="7" :md="7" class="ownerSex">-->
<!--                            <FormItem prop="customer_sex">-->
<!--                                <Select v-model="formDealValidate.customer_sex" :transfer="true">-->
<!--                                    <Option value="先生">先生</Option>-->
<!--                                    <Option value="女士">女士</Option>-->
<!--                                </Select>-->
<!--                            </FormItem>-->
<!--                            </Col>-->
<!--                        </Row>-->
<!--                        </Col>-->
<!--                    </Row>-->
<!--                    </Col>-->
<!--                    <Col :lg="24" :md="24">-->
<!--                    <Row>-->
<!--                        <Col :lg="8" :md="8">-->
<!--                        <FormItem label="证件号码" prop="customer_idno">-->
<!--                            <Input placeholder="" v-model="formDealValidate.customer_idno"></Input>-->
<!--                        </FormItem>-->
<!--                        </Col>-->
<!--                        <Col :lg="16" :md="16">-->
<!--                        <FormItem label="联系地址" prop="customer_address">-->
<!--                            <Input placeholder="" v-model="formDealValidate.customer_address"></Input>-->
<!--                        </FormItem>-->
<!--                        </Col>-->
<!--                    </Row>-->
<!--                    </Col>-->
                    <!--<div style="width: 100%;height: 2px;background-color: #e5e5e5;margin-bottom: 20px"></div>-->
                    <!--<Col :lg="24" :md="24">-->
                    <!--<Row>-->
                        <!--<Col :lg="8" :md="8">-->
                        <!--<FormItem label="部门选择" prop="">-->
                            <!--<Cascader :data="formDealValidate.departchoose"-->
                                      <!--v-model="departcascader.liandong" change-on-select-->
                                      <!--:transfer="true" disabled></Cascader>-->
                        <!--</FormItem>-->
                        <!--</Col>-->
                        <!--<Col :lg="8" :md="8">-->
                        <!--<FormItem label="划成人" prop="">-->
                            <!--<Select placeholder="" v-model="formDealValidate.order_linkage_user" :label-in-value="true"-->
                                    <!--placeholder="联动划成人" :transfer="true" disabled>-->
                                <!--<Option v-for="(item,index) in formDealValidate.lduser" :value="item.u_id"-->
                                        <!--:key="item.u_id">{{item.u_name}}-->
                                <!--</Option>-->
                            <!--</Select>-->
                        <!--</FormItem>-->
                        <!--</Col>-->
                        <!--<Col :lg="8" :md="8">-->
                        <!--<FormItem label="比例" prop="">-->
                            <!--<Input v-model="formDealValidate.order_linkage_per" placeholder="联动划成比例"-->
                                   <!--disabled>-->
                            <!--<span slot="append">%</span>-->
                            <!--</Input>-->
                        <!--</FormItem>-->
                        <!--</Col>-->
                    <!--</Row>-->
                    <!--</Col>-->
                    <!--<Col :lg="24" :md="24">-->
                    <!--<Row>-->
                        <!--<Col :lg="8" :md="8">-->
                        <!--<FormItem label="部门选择" prop="">-->
                            <!--<Cascader :data="formDealValidate.departchoose"-->
                                      <!--v-model="departcascader.xieshang" change-on-select-->
                                      <!--:transfer="true" disabled></Cascader>-->
                        <!--</FormItem>-->
                        <!--</Col>-->
                        <!--<Col :lg="8" :md="8">-->
                        <!--<FormItem label="划成人" prop="">-->
                            <!--<Select placeholder="" v-model="formDealValidate.order_consult_user" :label-in-value="true"-->
                                    <!--:transfer="true" placeholder="协商划成人" disabled>-->
                                <!--<Option v-for="(item,index) in formDealValidate.xsuser" :value="item.u_id"-->
                                        <!--:key="item.u_id">{{item.u_name}}-->
                                <!--</Option>-->
                            <!--</Select>-->
                        <!--</FormItem>-->
                        <!--</Col>-->
                        <!--<Col :lg="8" :md="8">-->
                        <!--<FormItem label="比例" prop="">-->
                            <!--<Input v-model="formDealValidate.order_consult_per" placeholder="协商划成比例"-->
                                   <!--disabled>-->
                            <!--<span slot="append">%</span>-->
                            <!--</Input>-->
                        <!--</FormItem>-->
                        <!--</Col>-->
                    <!--</Row>-->
                    <!--</Col>-->
                    <!--<Col :lg="24" :md="24">-->
                    <!--<Row>-->
                        <!--<Col :lg="8" :md="8">-->
                        <!--<FormItem label="过户流程" prop="order_transfer_id">-->
                            <!--<Select placeholder="" v-model="formDealValidate.order_transfer_id"-->
                                    <!--@on-change="selectProcess" :label-in-value="true" :transfer="true"-->
                                    <!--placeholder="过户流程" disabled>-->
                                <!--<Option v-for="(item,index) in formDealValidate.process" :value="item.transfer_id"-->
                                        <!--:key="item.transfer_name">{{item.transfer_name}}-->
                                <!--</Option>-->
                            <!--</Select>-->
                        <!--</FormItem>-->
                        <!--</Col>-->
                        <!--<Col :lg="8" :md="8">-->
                        <!--<FormItem label="合同编号" prop="contract_sn">-->
                            <!--<Select placeholder="" v-model="formDealValidate.contract_sn"  :label-in-value="true" :transfer="true" disabled>-->
                                <!--<Option  :value="formDealValidate.contract_sn" >{{formDealValidate.contract_name}}【{{formDealValidate.contract_sn}}】-->
                                <!--</Option>-->
                            <!--</Select>-->
                        <!--</FormItem>-->
                        <!--</Col>-->
                    <!--</Row>-->
                    <!--</Col>-->
                </Row>
                <Col :lg="25" :md="25">
                <div style="width: 100%;height: 1px;background-color: #e9eaec;margin-bottom: 20px"></div>
                </Col>
                <Row>
                    <Col :lg="24" :md="24" class="formItemClass"  style=" background-color: #e3e8ee;color: #666;font-size: 14px;padding-left: 20px !important;line-height: 38px;
    margin-bottom: 20px;">
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
                    <Table :columns="customerColumns" :data="customerList" border script
                           @on-selection-change="selectionok"></Table>
                    <div style="margin: 10px;overflow: hidden">
                        <div style="float: right;">
                            <Page :total="pageTotal" :current="pageCurrent" @on-change="changePage"></Page>
                        </div>
                    </div>
                    </Col>
                </Row>
            </Modal>
        </Modal>
    </Row>
</template>

<script>
    export default {
        name: 'editBasic',
        components: {},
        props: ['formDealValidate'],
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
                defaultList: [],
                imgName: '',
                visible: false,
                uploadList: [],
                yjuploadList:[],
                notifytime: {
                    disabledDate(datetime) {
                        return datetime && datetime.valueOf() < Date.now();
                    }
                },
                departcascader:{
                     chengjiao:[],
                     quanzheng:[],
                },
                contractModal: false,//签约值
                successshow:0,
                editDeal: false, // 买卖成交信息
                customerModal: false, // 选择客源
                changeStatus: false,
                addPhone: false,
                addGenJin: false,
                canuse: false,
                gjuser: [],
                //                cjuser:[],
                //                qzuser:[],
                formStatusValidate: {
                    status: '',
                    remark: ''
                },
                ruleStatusValidate: {
                    status: [
                        {required: true, message: '请选择状态', trigger: 'change'}
                    ],
                    remark: [
                        {required: true, message: '请输入变更理由', trigger: 'blur'}
                    ]
                },
                formPhoneValidate: {
                    type: '',
                    phone: '',
                    relation: ''
                },
                rulePhoneValidate: {
                    phone: [
                        {required: true, message: '请输入电话或手机号', trigger: 'blur'},
                        {
                            validator(rule, value, callback, source, options) {
                                var errors = [];
                                if (!/^1[345789]\d{9}$/.test(value) && !/^0\d{2,3}-\d{7,8}$/.test(value)) {
                                    callback('请输入正确的电话或手机号');
                                }
                                callback(errors);
                            }
                        }
                    ],
                    relation: [
                        {required: true, message: '请选择关系', trigger: 'change'}
                    ]

                },
                formGenjinValidate: {
                    of_type: '',
                    of_content: '',
                    d_id: '',
                    of_notify_user: '',
                    of_notify_time: '',
                    of_notify_content: ''
                },
                departpath: [],
                ruleGenjinValidate: {
                    of_type: [
                        {required: true, message: '请选择跟进方式', trigger: 'change'}
                    ],
                    of_content: [
                        {required: true, message: '请填写跟进内容', trigger: 'blur'}
                    ],
                    d_id: [
                        {required: true, message: '请选择部门', trigger: 'change'}
                    ],
                    of_notify_user: [
                        {required: true, message: '请选择提醒人', trigger: 'change'}
                    ],
                    of_notify_time: [
                        {required: true, message: '请选择提醒时间', trigger: 'change'}
                    ],
                    of_notify_content: [
                        {required: true, message: '请填写通知内容', trigger: 'blur'}
                    ]
                },
                orderModelTitle: '',
                ruleDealValidate: {
                    order_deal_date: [
                        {required: true, message: '请选择成交时间', trigger: 'change'}
                    ],
                    order_deal_user: [
                        {required: true, message: '请选择成交人', trigger: 'change'}
                    ],
                    order_price: [
                        {required: true, validator: validateNumber, trigger: 'blur'}
                    ],
                    order_owner_commission: [
                        {required: true, validator: validateNumber, trigger: 'blur'}
                    ],
                    order_customer_commission: [
                        {required: true, validator: validateNumber, trigger: 'blur'}
                    ],
                    //                    house_land_certificate:  [
                    //                        {required: true, message: '请填写土地证号', trigger: 'blur'}
                    //                    ],
                    //                    house_property_certificate: [
                    //                        {required: true, message: '请填写产权证号', trigger: 'blur'}
                    //                    ],
                    order_start_time: [
                        {required: true, message: '请输入租房开始时间', trigger: 'change'}
                    ],
                    order_end_time: [
                        {required: true, message: '请输入租房结束时间', trigger: 'change'}
                    ],
                    order_property_user: [
                        {required: true, message: '请选择产权人', trigger: 'change'}
                    ],
                    owner_sn: [
                        {required: true, message: '请填写产权证号', trigger: 'blur'}
                    ],
                    order_deal_type: [
                        {required: true, message: '请选择交易类型', trigger: 'change'}
                    ],
                    owner_phone: [
                        {required: true, message: '请填写甲方手机号', trigger: 'blur'},
                        {
                            validator(rule, value, callback, source, options) {
                                var errors = [];
                                if (!/^(1[345789]\d{9})$/.test(value)) {
                                    callback('请填写正确的手机号');
                                }
                                callback(errors);
                            }
                        }
                    ],
                    owner_name: [
                        {required: true, message: '请填写甲方姓名', trigger: 'blur'}
                    ],
                    //                    owner_idno: [
                    //                        {required: true, message: '请填写甲方证件号', trigger: 'blur'}
                    //                    ],
                    //                    owner_address: [
                    //                        {required: true, message: '请填写甲方地址', trigger: 'blur'}
                    //                    ],
                    customer_sn: [
                        {required: true, message: '请选择乙方编号', trigger: 'blur'}
                    ],
                    customer_phone: [
                        {required: true, message: '请填写乙方手机号', trigger: 'blur'},
                        {
                            validator(rule, value, callback, source, options) {
                                var errors = [];
                                if (!/^(1[345789]\d{9})$/.test(value)) {
                                    callback('请填写正确的手机号');
                                }
                                callback(errors);
                            }
                        }
                    ],
                    customer_name: [
                        {required: true, message: '请填写乙方姓名', trigger: 'blur'}
                    ]
                    //                    customer_idno: [
                    //                        {required: true, message: '请填写乙方证件号', trigger: 'blur'}
                    //                    ],
                    //                    customer_address: [
                    //                        {required: true, message: '请填写乙方地址', trigger: 'blur'}
                    //                    ],
                },
                // genJinColumns: [
                //     {
                //         title: '跟进时间',
                //         key: 'ctime',
                //         align: 'center',
                //         width: 138
                //     },
                //     {
                //         title: '跟进方式',
                //         key: 'of_type',
                //         align: 'center',
                //     },
                //     {
                //         title: '跟进人',
                //         key: 'u_name',
                //         align: 'center'
                //     },
                //     {
                //         title: '所在部门',
                //         key: 'd_name',
                //         align: 'center'
                //     },
                //     {
                //         title: '跟进内容',
                //         key: 'of_content',
                //         align: 'center',
                //     }
                // ],
                // 客户列表
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

                // 签约值图片上传
                xtoken: api_param.XAccessToken,
                uploadurl: api_param.apiurl + 'site/upload',
                imgurl: api_param.imgurl,
                defaultList: [],
                imgName: '',
                visible: false,
                uploadList: []
            };
        },
        methods: {
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
            //签约值
            showContractModal(){
                this.contractModal = true;
            },
            contractModalCancel() {
                this.contractModal = false;
            },
            contractModalOk(){
                this.$http.post(api_param.apiurl + 'ordersell/contractimage',
                    {
                        'order_id': this.formDealValidate.order_id,
                        'contract_image': this.formDealValidate.contract_image
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.contractModal = false;
                        if(response.data.data == 4){
                            this.successshow = 1;
                        }else{
                            this.successshow = 0;
                        }
                        this.$Message.success(response.data.message);
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else if (response.data.code == 403) {
                        this.$Message.warning(response.data.message);
                        this.contractModal = false;
                        this.$store.commit('removeTag', 'bargainDetails');
                        this.$router.push({name: 'bargainSell_index',})
                    }else{
                        this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            },
            // 签约值
            handleView(name) {
                this.imgName = name;
                this.visible = true;
            },
            handleRemove(file) {
                let key = this.formDealValidate.contract_image.indexOf(file);
                this.formDealValidate.contract_image[key]['url'] = '';
            },
            handleSuccess(res, file) {
                let key = res.data.data.key;
                this.$set(this.formDealValidate.contract_image[key],'url',res.data.url);
            },
            handleFormatError(file) {
                this.$Message.warning('文件格式不正确，请选择JPG或PNG');
            },
            handleMaxSize(file) {
                this.$Message.warning('文件  ' + file.name + ' 超过 2M.');
            },
            handleBeforeUpload() {
                const check = this.uploadList.length < 4;
                if (!check) {
                    this.$Message.warning('最多上传4张图片');
                }
                return check;
            },
            // 变更状态
            showStatusModal() {
                this.$refs['formStatusValidate'].resetFields();
                this.changeStatus = true;
            },
            statusModalCancel() {
                this.changeStatus = false;
                // 清空form规则检查
                this.$refs['formStatusValidate'].resetFields();
            },
            statusModalOk() {
                this.$refs['formStatusValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'ordersell/changestatus',
                            {
                                'order_id': this.formDealValidate.order_id,
                                'order_status': this.formStatusValidate.status,
                                'order_status_remark': this.formStatusValidate.remark
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$set(this.formDealValidate, 'order_status', this.formStatusValidate.status);
                                this.$refs['formStatusValidate'].resetFields();
                                this.changeStatus = false;
                                this.$Message.success(response.data.message);
                            } else if (response.data.code == 401) {
                                this.$store.commit('logout', this);
                                this.$store.commit('clearOpenedSubmenu');
                                this.$router.push({
                                    name: 'login'
                                });
                            } else if (response.data.code == 403) {
                                this.$Message.warning(response.data.message);
                                this.changeStatus = false;
                                this.$store.commit('removeTag', 'bargainDetails');
                                this.$router.push({name: 'bargainSell_index',})
                            }else{
                                this.$Message.warning(response.data.message);
                            }
                        }, function (response) {
                            // 这里是处理错误的回调
                            console.log(response);
                        });
                    }
                });
            },
            // 添加联系方式
            showPhoneModal(type) {
                this.$refs['formPhoneValidate'].resetFields();
                this.formPhoneValidate.type = type;
                this.addPhone = true;
            },
            phoneModalCancel() {
                this.addPhone = false;
                // 清空form规则检查
                this.$refs['formPhoneValidate'].resetFields();
            },
            phoneModalOk() {
                this.$refs['formPhoneValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'ordersell/addphone',
                            {
                                'order_id': this.formDealValidate.order_id,
                                'type': this.formPhoneValidate.type,
                                'phone': this.formPhoneValidate.phone,
                                'relation': this.formPhoneValidate.relation
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.addPhone = false;
                                let phone = this.formPhoneValidate.phone.substr(0, 3) + '****' + this.formPhoneValidate.phone.substr(7);
                                if (this.formPhoneValidate.type == 1) {
                                    let type = this.formDealValidate.jiafang.length;
                                    this.$set(this.formDealValidate.jiafang, type, {
                                        'phone': phone,
                                        'relation': this.formPhoneValidate.relation
                                    });
                                } else {
                                    let type = this.formDealValidate.yifang.length;
                                    this.$set(this.formDealValidate.yifang, type, {
                                        'phone': phone,
                                        'relation': this.formPhoneValidate.relation
                                    });
                                }
                                this.$Message.success(response.data.message);
                            } else if (response.data.code == 401) {
                                this.$store.commit('logout', this);
                                this.$store.commit('clearOpenedSubmenu');
                                this.$router.push({
                                    name: 'login'
                                });
                            } else if (response.data.code == 403) {
                                this.$Message.warning(response.data.message);
                                this.addPhone = false;
                                this.$store.commit('removeTag', 'bargainDetails');
                                this.$router.push({name: 'bargainSell_index',})
                            }else{
                                this.$Message.warning(response.data.message);
                            }
                        }, function (response) {
                            // 这里是处理错误的回调
                            console.log(response);
                        });
                    }
                });
            },
            // 查看电话
            showPhone() {
                this.$http.post(api_param.apiurl + 'ordersell/showphone',
                    {
                        'order_id': this.formDealValidate.order_id
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.formDealValidate.jiafang = [];
                        if (response.data.data.jiafang) {
                            response.data.data.jiafang.forEach((item, index) => {
                                this.$set(this.formDealValidate.jiafang, index, item);
                            });
                        }

                        this.formDealValidate.yifang = [];
                        if (response.data.data.yifang) {
                            response.data.data.yifang.forEach((item, index) => {
                                this.$set(this.formDealValidate.yifang, index, item);
                            });
                        }
                        this.canuse = true;
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else if (response.data.code == 403) {
                        this.$Message.warning(response.data.message);
                        this.$store.commit('removeTag', 'bargainDetails');
                        this.$router.push({name: 'bargainSell_index',})
                    }else{
                        this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            },
            // 跟进列表删除
            delGenjinList(id, index) {
                this.$http.post(api_param.apiurl + 'ordersell/delgenjin',
                    {
                        'of_id': id
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.formDealValidate.genjin.splice(index, 1);
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else if (response.data.code == 403) {
                        this.$Message.warning(response.data.message);
                        this.$store.commit('removeTag', 'bargainDetails');
                        this.$router.push({name: 'bargainSell_index',})
                    }else{
                        this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            },
            // 提醒人级联回调
            gjhandleChange(value, selectedData) {
                this.formGenjinValidate.d_id = value[value.length - 1];
                this.$http.post(api_param.apiurl + 'ordersell/getuser',
                    {
                        'd_id': this.formGenjinValidate.d_id
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.gjuser = response.data.data;
                        // console.log(this.departuser);
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
            // 成交人级联回调
            cjhandleChange(value, selectedData) {
                this.formDealValidate.order_deal_did = value[value.length - 1];
                this.$http.post(api_param.apiurl + 'ordersell/getuser',
                    {
                        'd_id': this.formDealValidate.order_deal_did
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.formDealValidate.cjuser = response.data.data;
                        // console.log(this.departuser);
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
            // 权证人级联回调
            qzhandleChange(value, selectedData) {
                this.formDealValidate.order_property_did = value[value.length - 1];
                this.$http.post(api_param.apiurl + 'ordersell/getuser',
                    {
                        'd_id': this.formDealValidate.order_property_did
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.formDealValidate.qzuser = response.data.data;
                        // console.log(this.departuser);
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
            choosetime(datetime) {
                this.formGenjinValidate.of_notify_time = datetime;
            },
            // 跟进
            showGenjinModal() {
                this.$refs['formGenjinValidate'].resetFields();
                this.addGenJin = true;
            },
            genjinModalCancel() {
                this.addGenJin = false;
                // 清空form规则检查
                this.formGenjinValidate.of_notify_time = '';
                this.departpath = [];
                this.$refs['formGenjinValidate'].resetFields();
            },
            genjinModalOk() {
                // console.log(this.formGenjinValidate.of_notify_time);
                this.$refs['formGenjinValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'ordersell/addgenjin',
                            {
                                'order_id': this.formDealValidate.order_id,
                                'of_type': this.formGenjinValidate.of_type,
                                'of_content': this.formGenjinValidate.of_content,
                                'd_id': this.formGenjinValidate.d_id,
                                'of_notify_user': this.formGenjinValidate.of_notify_user,
                                'of_notify_time': this.formGenjinValidate.of_notify_time,
                                'of_notify_content': this.formGenjinValidate.of_notify_content
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.formDealValidate.genjin = [];
                                response.data.data.genjin.forEach((item, index) => {
                                    this.$set(this.formDealValidate.genjin, index, item);
                                });
                                this.addGenJin = false;
                                // 清空form规则检查
                                this.formGenjinValidate.d_id = '';
                                this.$refs['formGenjinValidate'].resetFields();
                            } else if (response.data.code == 401) {
                                this.$store.commit('logout', this);
                                this.$store.commit('clearOpenedSubmenu');
                                this.$router.push({
                                    name: 'login'
                                });
                            } else if (response.data.code == 403) {
                                this.$Message.warning(response.data.message);
                                this.addGenJin = false;
                                this.$store.commit('removeTag', 'bargainDetails');
                                this.$router.push({name: 'bargainSell_index',})
                            }else{
                                this.$Message.warning(response.data.message);
                            }
                        }, function (response) {
                            // 这里是处理错误的回调
                            console.log(response);
                        });
                    }
                });
            },
            // 成交
            selectDealuser(selectedData) { // 成交人选择
                // console.log(selectedData);
                this.formDealValidate.order_deal_user = selectedData.value;
                this.formDealValidate.order_deal_user_name = selectedData.label;
            },
            selectPropertyuser(selectedData) { // 权证人选择
                this.formDealValidate.order_property_user = selectedData.value;
                this.formDealValidate.order_property_user_name = selectedData.label;
            },
            showDealModal() {
                // this.$refs['formDealValidate'].resetFields();
                if (this.formDealValidate.order_type == 2) {
                    this.orderModelTitle = '修改买卖成交信息';
                } else if (this.formDealValidate.order_type == 1) {
                    this.orderModelTitle = '修改租赁成交信息';
                } else if (this.formDealValidate.order_type == 3) {
                    this.orderModelTitle = '修改高端成交信息';
                }
                this.$set(this.departcascader,'chengjiao',this.formDealValidate.deal_departpath);
                this.$set(this.departcascader,'quanzheng',this.formDealValidate.property_departpath);
                this.$set(this.departcascader,'liandong',this.formDealValidate.linkage_departpath);
                this.$set(this.departcascader,'xieshang',this.formDealValidate.consult_departpath);
                //console.log(this.departcascader);
                this.editDeal = true;
            },
            dealModalCancel() {
                this.editDeal = false;
                // 清空form规则检查
                // this.$refs['formDealValidate'].resetFields();
            },
            dealModalOk() {
                this.$refs['formDealValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'ordersell/edit',
                            {
                                'order_id': this.formDealValidate.order_id,
                               'order_deal_date': this.formDealValidate.order_deal_date,
//                                'order_deal_did': this.formDealValidate.order_deal_did,
//                                'order_deal_user': this.formDealValidate.order_deal_user,
//                                'order_deal_user_name': this.formDealValidate.order_deal_user_name,
                                'agreement_sn': this.formDealValidate.agreement_sn,
                                'order_price': this.formDealValidate.order_price,
                                'order_loan': this.formDealValidate.order_loan,
                                'order_owner_commission': this.formDealValidate.order_owner_commission,
                                'order_customer_commission': this.formDealValidate.order_customer_commission,
                                'house_land_certificate': this.formDealValidate.house_land_certificate,
                                'house_property_certificate': this.formDealValidate.house_property_certificate,
                                'order_property_did': this.formDealValidate.order_property_did,
                                'order_property_user': this.formDealValidate.order_property_user,
                                'order_property_user_name': this.formDealValidate.order_property_user_name,
                                'order_deal_type':this.formDealValidate.order_deal_type,
                                'order_addition_terms': this.formDealValidate.order_addition_terms,
                                'order_start_time': this.formDealValidate.order_start_time,
                                'order_end_time': this.formDealValidate.order_end_time,
                                'owner_sn': this.formDealValidate.owner_sn,
                                'owner_phone': this.formDealValidate.owner_phone,
                                'owner_name': this.formDealValidate.owner_name,
                                'owner_sex': this.formDealValidate.owner_sex,
                                'owner_idno': this.formDealValidate.owner_idno,
                                'owner_address': this.formDealValidate.owner_address,
                                'customer_sn': this.formDealValidate.customer_sn,
                                'customer_phone': this.formDealValidate.customer_phone,
                                'customer_name': this.formDealValidate.customer_name,
                                'customer_sex': this.formDealValidate.customer_sex,
                                'customer_idno': this.formDealValidate.customer_idno,
                                'customer_address': this.formDealValidate.customer_address
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.editDeal = false;
                                // 清空form规则检查
                                // this.$refs['formDealValidate'].resetFields();
                                this.$emit('getInfo');
                            } else if (response.data.code == 401) {
                                this.$store.commit('logout', this);
                                this.$store.commit('clearOpenedSubmenu');
                                this.$router.push({
                                    name: 'login'
                                });
                            } else if (response.data.code == 403) {
                                this.$Message.warning(response.data.message);
                                this.editDeal = false;
                                this.$store.commit('removeTag', 'bargainDetails');
                                this.$router.push({name: 'bargainSell_index',})
                            }else{
                                this.$Message.warning(response.data.message);
                            }
                        }, function (response) {
                            // 这里是处理错误的回调
                            console.log(response);
                        });
                    }
                });
            },
            // 选择客源
            chooseCustomer() {
                this.$http.post(api_param.apiurl + 'ordersell/customerlist',
                    {
                        'page': this.pageCurrent,
                        'status': this.customerStatus,
                        'type': this.formDealValidate.houseinfo.sale_type,
                        'kw': this.keyword
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.customerModal = true;
                        this.customerList = response.data.data.listdata;
                        this.pageTotal = response.data.data.totalnum;
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
            selectChange(selectedData) {
                this.customerStatus = selectedData;
            },
            doSearch() {
                this.currentpage = 1;
                this.chooseCustomer();
            },
            customerModalCancel() {
                this.selection = '';
                this.customerModal = false;
            },
            selectionok(selection) {
                this.selection = selection;
            },
            customerModalOk() {
                if (this.selection.length == 1) {
                    this.formDealValidate.customer_sn = this.selection[0].xuqiubianhao;
                    this.formDealValidate.customer_phone = this.selection[0].tel_phone;
                    this.formDealValidate.customer_name = this.selection[0].customer_name;
                    this.formDealValidate.customer_sex = this.selection[0].customer_sex;
                    this.customerModal = false;
                } else if (this.selection.length > 1) {
                    this.$Message.warning('最多选择1个');
                } else {
                    this.$Message.warning('最少选择1个');
                }
            },
            changePage(page) { // 分页
                this.pageCurrent = page;
                this.chooseCustomer();
            },
        },
    };
</script>
