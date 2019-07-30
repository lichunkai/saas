<style lang="less">
</style>
<template>
    <Modal v-model="editRoom" width="960" :closable="false" :mask-closable="false"
           @on-ok="handleSubmit('formValidate')" @on-cancel="handleReset('formValidate')">
        <div slot="header">
            <a class="ivu-modal-close" @click="modalCancel" style="display: block!important;"><i
                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
            <div class="ivu-modal-header-inner">{{formValidate.title}}</div>
        </div>
        <div slot="footer">
            <Button type="text" size="large" @click="modalCancel">取消</Button>
            <Button type="primary" size="large" @click="modalOk">确定</Button>
        </div>
        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
			<Row>
				<i-col span="24" class='caddIcol'>录入客源基本信息</i-col>				
			</Row>
            <Row :gutter="5">
                <Col :lg="8" :md="8">
                <FormItem label="客户姓名" prop="customer_name" v-if="btx('customer_name')"
                          :rules="{required: true, message: '姓名不能为空', trigger: 'blur'}">
                    <Row>
                        <Col :lg="14" :md="14">
                        <Input v-model="formValidate.customer_name" placeholder=""></Input>
                        </Col>
                        <Col :lg="10" :md="10">
                        <Select placeholder="" :transfer="true" v-model="formValidate.customer_sex">
                            <Option v-for="item in JSON.parse( this.peizhi.customer_sex[0].base_desp)"
                                    :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                            </Option>
                        </Select>
                        </Col>
                    </Row>
                </FormItem>
                <FormItem label="客户姓名" prop="customer_name" v-if="!btx('customer_name')">
                    <Row>
                        <Col :lg="14" :md="14">
                        <Input v-model="formValidate.customer_name" placeholder=""></Input>
                        </Col>
                        <Col :lg="10" :md="10">
                        <Select placeholder="" :transfer="true" v-model="formValidate.customer_sex">
                            <Option v-for="(item, index) in JSON.parse( this.peizhi.customer_sex[0].base_desp)"
                                    :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                            </Option>
                        </Select>
                        </Col>
                    </Row>
                </FormItem>
                </Col>
				 <Col :lg="8" :md="8">
					<FormItem label="客户等级" prop="kehudengji" v-if="btx('kehudengji')"
							  :rules="{required: true, message: '客户等级不能为空', trigger: 'change'}">
						<Select v-model="formValidate.kehudengji" :transfer="true">
							<Option v-for="item in JSON.parse( this.peizhi.kehudengji[0].base_desp)" :value="item.child_name"
									:key="item.child_name">{{ item.child_name}}
							</Option>
						</Select>
					</FormItem>
					<FormItem label="客户等级" prop="kehudengji" v-if="!btx('kehudengji')">
						<Select v-model="formValidate.kehudengji" :transfer="true">
							<Option v-for="item in JSON.parse( this.peizhi.kehudengji[0].base_desp)" :value="item.child_name"
									:key="item.child_name">{{ item.child_name}}
							</Option>
						</Select>
					</FormItem>
				</Col>
            </Row>
			<Row>
				<i-col span="24" class='caddIcol'>客源主要信息</i-col>				
			</Row>
            <Row>
                <Col :lg="8" :md="8">
                <FormItem label="区域选择" prop="xuqiuquyu" v-if="btx('xuqiuquyu')"
                          :rules="{type: 'array',required: true, message: '区域选择不能为空', trigger: 'change'}">
                    <Cascader :data="this.peizhi.villages" v-model="formValidate.xuqiuquyu"
                              @on-change="changeVillage"></Cascader>
                </FormItem>
                <FormItem label="区域选择" prop="xuqiuquyu" v-if="!btx('xuqiuquyu')">
                    <Cascader :data="this.peizhi.villages" v-model="formValidate.xuqiuquyu"
                              @on-change="changeVillage"></Cascader>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="小区选择" prop="village" v-if="btx('xuqiuquyu')"  :rules="{required: true, message: '小区不能为空', trigger: 'change'}">
                    <Select  :transfer="true" v-model="formValidate.village" v-if="btx('xuqiuquyu')"  >
                        <Option v-for="item in formValidate.village_list" :value="item.village_id" :key="item.village_id">{{ item.village_name }}</Option>
                    </Select>
                </FormItem>
                <FormItem label="小区选择" prop="village" v-if="!btx('xuqiuquyu')">
                    <Select  :transfer="true" v-model="formValidate.village"  >
                        <Option v-for="item in formValidate.village_list" :value="item.village_id" :key="item.village_id">{{ item.village_name }}</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="房屋用途" prop="yongtu" v-if="btx('yongtu')"
                          :rules="{required: true, message: '房屋用途不能为空', trigger: 'change'}">
                    <Select v-model="formValidate.yongtu" :transfer="true">
                        <Option v-for="item in JSON.parse( this.peizhi.yongtu[0].base_desp)" :value="item.child_name"
                                :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="房屋用途" prop="yongtu" v-if="!btx('yongtu')">
                    <Select v-model="formValidate.yongtu" :transfer="true">
                        <Option v-for="item in JSON.parse( this.peizhi.yongtu[0].base_desp)" :value="item.child_name"
                                :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="8" :md="8">
                <FormItem label="需求户型" prop="xuqiuhuxing" v-if="btx('xuqiuhuxing')"
                          :rules="{required: true, message: '需求户型不能为空', trigger: 'change'}">
                    <Select v-model="formValidate.xuqiuhuxing" :transfer="true" @on-change="xuqiuhuxing">
                        <Option v-for="item in JSON.parse( this.peizhi.huxing[0].qujian_desp)"
                                :value="item.min+';'+item.max" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="需求户型" prop="xuqiuhuxing" v-if="!btx('xuqiuhuxing')">
                    <Select v-model="formValidate.xuqiuhuxing" :transfer="true" @on-change="xuqiuhuxing">
                        <Option v-for="item in JSON.parse( this.peizhi.huxing[0].qujian_desp)"
                                :value="item.min+';'+item.max" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="需求价格" prop="xuqiujiage" v-if="btx('xuqiujiage')"
                          :rules="{required: true, message: '需求价格不能为空', trigger: 'change'}">
                    <Select v-model="formValidate.xuqiujiage" :transfer="true" @on-change="xuqiujiage">
                        <Option v-for="item in JSON.parse( this.peizhi.xuqiujiage[0].qujian_desp)"
                                :value="item.min+';'+item.max" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="需求价格" prop="xuqiujiage" v-if="!btx('xuqiujiage')">
                    <Select v-model="formValidate.xuqiujiage" :transfer="true" @on-change="xuqiujiage">
                        <Option v-for="item in JSON.parse( this.peizhi.xuqiujiage[0].qujian_desp)"
                                :value="item.min+';'+item.max" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="需求面积" prop="xuqiumianji" v-if="btx('xuqiumianji')"
                          :rules="{required: true, message: '需求面积不能为空', trigger: 'change'}">
                    <Select v-model="formValidate.xuqiumianji" :transfer="true" @on-change="xuqiumianji">
                        <Option v-for="item in JSON.parse( this.peizhi.xuqiumianji[0].qujian_desp)"
                                :value="item.min+';'+item.max" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="需求面积" prop="xuqiumianji" v-if="!btx('xuqiumianji')">
                    <Select v-model="formValidate.xuqiumianji" :transfer="true" @on-change="xuqiumianji">
                        <Option v-for="item in JSON.parse( this.peizhi.xuqiumianji[0].qujian_desp)"
                                :value="item.min+';'+item.max" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col>
            </Row>
            <Row>
                <Col :lg="8" :md="8">
                <FormItem label="需求楼层" prop="xuqiulouceng" v-if="btx('xuqiulouceng')"
                          :rules="{required: true, message: '需求楼层不能为空', trigger: 'change'}">
                    <Select v-model="formValidate.xuqiulouceng" :transfer="true" @on-change="xuqiulouceng">
                        <Option v-for="item in JSON.parse( this.peizhi.louceng[0].qujian_desp)"
                                :value="item.min+';'+item.max" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="需求楼层" prop="xuqiulouceng" v-if="!btx('xuqiulouceng')">
                    <Select v-model="formValidate.xuqiulouceng" :transfer="true" @on-change="xuqiulouceng">
                        <Option v-for="item in JSON.parse( this.peizhi.louceng[0].qujian_desp)"
                                :value="item.min+';'+item.max" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="房屋朝向" prop="chaoxiang" v-if="btx('chaoxiang')"
                          :rules="{required: true, message: '房屋朝向不能为空', trigger: 'change'}">
                    <Select v-model="formValidate.chaoxiang" :transfer="true">
                        <Option v-for="item in JSON.parse( this.peizhi.chaoxiang[0].base_desp)" :value="item.child_name"
                                :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="房屋朝向" prop="chaoxiang" v-if="!btx('chaoxiang')">
                    <Select v-model="formValidate.chaoxiang" :transfer="true">
                        <Option v-for="item in JSON.parse( this.peizhi.chaoxiang[0].base_desp)" :value="item.child_name"
                                :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="需求房龄" prop="xuqiufangling" v-if="btx('xuqiufangling')"
                          :rules="{required: true, message: '需求房龄不能为空', trigger: 'change'}">
                    <Select v-model="formValidate.xuqiufangling" :transfer="true" @on-change="xuqiufangling">
                        <Option v-for="item in JSON.parse( this.peizhi.fangling[0].qujian_desp)"
                                :value="item.min+';'+item.max" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="需求房龄" prop="xuqiufangling" v-if="!btx('xuqiufangling')">
                    <Select v-model="formValidate.xuqiufangling" :transfer="true" @on-change="xuqiufangling">
                        <Option v-for="item in JSON.parse( this.peizhi.fangling[0].qujian_desp)"
                                :value="item.min+';'+item.max" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="装修需求" prop="zhuangxiu" v-if="btx('zhuangxiu')"
                          :rules="{required: true, message: '装修不能为空', trigger: 'change'}">
                    <Select v-model="formValidate.zhuangxiu" :transfer="true">
                        <Option v-for="item in JSON.parse( this.peizhi.zhuangxiu[0].base_desp)" :value="item.child_name"
                                :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="装修需求" prop="zhuangxiu" v-if="!btx('zhuangxiu')">
                    <Select v-model="formValidate.zhuangxiu" :transfer="true">
                        <Option v-for="item in JSON.parse( this.peizhi.zhuangxiu[0].base_desp)" :value="item.child_name"
                                :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col>
				<Col :lg="12" :md="12">
				<FormItem label="内部配套" prop="peitao" v-if="btx('peitao')"
				          :rules="{type:'array',required: true, message: '内部配套不能为空', trigger: 'change'}">
				    <Select v-model="formValidate.peitao" multiple>
				        <Option v-for="item in JSON.parse( this.peizhi.peitao[0].base_desp)" :value="item.child_name"
				                :key="item.child_name">{{ item.child_name}}
				        </Option>
				    </Select>
				</FormItem>
				<FormItem label="内部配套" prop="peitao" v-if="!btx('peitao')">
				    <Select v-model="formValidate.peitao" multiple>
				        <Option v-for="item in JSON.parse( this.peizhi.peitao[0].base_desp)" :value="item.child_name"
				                :key="item.child_name">{{ item.child_name}}
				        </Option>
				    </Select>
				</FormItem>
				</Col>
            </Row>
			 <Col :lg="8" :md="8">
			<FormItem label="房屋类型" prop="fangwuleixing" v-if="btx('fangwuleixing')"
			          :rules="{required: true, message: '推荐标签不能为空', trigger: 'change'}">
			    <Select  :transfer="true" v-model="formValidate.fangwuleixing">
			        <Option v-for="item in JSON.parse( this.peizhi.fangwuleixing[0].base_desp)"
			                :value="item.child_name" :key="item.child_name">{{ item.child_name}}
			        </Option>
			    </Select>
			</FormItem>
			<FormItem label="房屋类型" prop="fangwuleixing" v-if="!btx('fangwuleixing')">
			    <Select  :transfer="true" v-model="formValidate.fangwuleixing">
			        <Option v-for="item in JSON.parse( this.peizhi.fangwuleixing[0].base_desp)"
			                :value="item.child_name" :key="item.child_name">{{ item.child_name}}
			        </Option>
			    </Select>
			</FormItem>
			</Col>
			<Row>
			    <Col :lg="12" :md="12">
			    <FormItem label="特殊需求" prop="tiaojianbiaoqian" v-if="btx('tuijianbianqian')"
			              :rules="{type: 'array',required: true, message: '特殊需求不能为空', trigger: 'blur'}">
			        <Select  multiple filterable v-model="formValidate.tiaojianbiaoqian">
			            <Option v-for="item in JSON.parse( this.peizhi.tuijianbiaoqian[0].base_desp)"
			                    :value="item.child_name" :key="item.child_name">{{ item.child_name}}
			            </Option>
			        </Select>
			    </FormItem>
			    <FormItem label="特殊需求" prop="tiaojianbiaoqian" v-if="!btx('tuijianbianqian')">
			        <Select  multiple filterable v-model="formValidate.tiaojianbiaoqian">
			            <Option v-for="item in JSON.parse( this.peizhi.tuijianbiaoqian[0].base_desp)"
			                    :value="item.child_name" :key="item.child_name">{{ item.child_name}}
			            </Option>
			        </Select>
			    </FormItem>
			    </Col>
			   
			</Row>
            <Row>                
                <Col :lg="24" :md="24">
                <FormItem label="需求原因" prop="xuqiuyuanying" v-if="btx('xuqiuyuanying')"
                          :rules="{required: true, message: '特殊需求不能为空', trigger: 'blur'}">
                    <Input v-model="formValidate.xuqiuyuanying" type="textarea"></Input>
                </FormItem>
                <FormItem label="需求原因" prop="xuqiuyuanying" v-if="!btx('xuqiuyuanying')">
                    <Input v-model="formValidate.xuqiuyuanying" type="textarea"></Input>
                </FormItem>
                </Col>
            </Row>		
            
            <Row>
                <Col :lg="24" :md="24">
                <FormItem label="标签选择" prop="duoxuanbiaoqian" v-if="btx('duoxuanbiaoqian')"
                          :rules="{type: 'array',required: true, message: '多选标签不能为空', trigger: 'change'}">
                    <CheckboxGroup v-model="formValidate.duoxuanbiaoqian">
                        <Checkbox v-for="item in JSON.parse( this.peizhi.duoxuanbiaoqian[0].base_desp)"
                                  :label="item.child_name"></Checkbox>
                    </CheckboxGroup>
                </FormItem>
                <FormItem label="" prop="duoxuanbiaoqian" v-if="!btx('duoxuanbiaoqian')">
                    <CheckboxGroup v-model="formValidate.duoxuanbiaoqian">
                        <Checkbox v-for="item in JSON.parse( this.peizhi.duoxuanbiaoqian[0].base_desp)"
                                  :label="item.child_name"></Checkbox>
                    </CheckboxGroup>
                </FormItem>
                </Col>
              <Row>
              	<i-col span="24" class='caddIcol'>客源次要信息</i-col>				
              </Row>
            </Row>
            <Row>
                <Col :lg="8" :md="8">
                <FormItem label="沟通阶段" prop="goutongjieduan" v-if="btx('goutongjieduan')"
                          :rules="{required: true, message: '沟通阶段不能为空', trigger: 'change'}">
                    <Select  :transfer="true" v-model="formValidate.goutongjieduan">
                        <Option v-for="item in JSON.parse( this.peizhi.goutongjieduan[0].base_desp)"
                                :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="沟通阶段" prop="goutongjieduan" v-if="!btx('goutongjieduan')">
                    <Select  :transfer="true" v-model="formValidate.goutongjieduan">
                        <Option v-for="item in JSON.parse( this.peizhi.goutongjieduan[0].base_desp)"
                                :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="客户来源" prop="kehulaiyuan" v-if="btx('kehulaiyuan')"
                          :rules="{required: true, message: '客户来源不能为空', trigger: 'change'}">
                    <Select placeholder="" :transfer="true" v-model="formValidate.kehulaiyuan">
                        <Option v-for="item in JSON.parse( this.peizhi.kehulaiyuan[0].base_desp)"
                                :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="客户来源" prop="kehulaiyuan" v-if="!btx('kehulaiyuan')">
                    <Select  :transfer="true" v-model="formValidate.kehulaiyuan">
                        <Option v-for="item in JSON.parse( this.peizhi.kehulaiyuan[0].base_desp)"
                                :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="购房资质" prop="xiaofeilinian" v-if="btx('xiaofeilinian')"  :rules="{type: 'array',required: true, message: '消费理念不能为空', trigger: 'change'}">
                    <Select  v-model="formValidate.xiaofeilinian" :transfer="true" multiple filterable>
                        <Option v-for="item in JSON.parse( this.peizhi.xiaofeilinian[0].base_desp)"
                                :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="购房资质" prop="xiaofeilinian" v-if="!btx('xiaofeilinian')">
                    <Select  v-model="formValidate.xiaofeilinian" :transfer="true" multiple filterable>
                        <Option v-for="item in JSON.parse( this.peizhi.xiaofeilinian[0].base_desp)"
                                :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col>
               <!-- <Col :lg="6" :md="6">
                <FormItem label="国籍" prop="guoji" v-if="btx('guoji')"
                          :rules="{required: true, message: '国籍不能为空', trigger: 'blur'}">
                    <Select placeholder="" v-model="formValidate.guoji" :transfer="true">
                        <Option v-for="item in JSON.parse( this.peizhi.guoji[0].base_desp)" :value="item.child_name"
                                :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="国籍" prop="guoji" v-if="!btx('guoji')">
                    <Select placeholder="" v-model="formValidate.guoji" :transfer="true">
                        <Option v-for="item in JSON.parse( this.peizhi.guoji[0].base_desp)" :value="item.child_name"
                                :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col> -->
            </Row>
            <Row>
                <!-- <Col :lg="6" :md="6">
                <FormItem label="民族" prop="minzu" v-if="btx('minzu')"
                          :rules="{required: true, message: '民族不能为空', trigger: 'blur'}">
                    <Select placeholder="" :transfer="true" v-model="formValidate.minzu">
                        <Option v-for="item in JSON.parse( this.peizhi.mingzu[0].base_desp)" :value="item.child_name"
                                :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="民族" prop="minzu" v-if="!btx('minzu')">
                    <Select placeholder="" :transfer="true" v-model="formValidate.minzu">
                        <Option v-for="item in JSON.parse( this.peizhi.mingzu[0].base_desp)" :value="item.child_name"
                                :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col> -->
                <Col :lg="8" :md="8">
                <FormItem label="证件号码" prop="zhengjianhaoma" v-if="btx('zhengjianhaoma')"
                          :rules="{required: true, message: '证件号码不能为空', trigger: 'blur'}">
                    <Input v-model="formValidate.zhengjianhaoma"></Input>
                </FormItem>
                <FormItem label="证件号码" prop="zhengjianhaoma" v-if="!btx('zhengjianhaoma')">
                    <Input v-model="formValidate.zhengjianhaoma"></Input>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="客户邮箱" prop="email" v-if="btx('email')"
                          :rules="{required: true,pattern:/^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/, message: '请输入正确的客户邮箱', trigger: 'blur'}">
                    <Input v-model="formValidate.email"></Input>
                </FormItem>
                <FormItem label="客户邮箱" prop="email" v-if="!btx('email')">
                    <Input v-model="formValidate.email"></Input>
                </FormItem>
                </Col>
                 <Col :lg="8" :md="8">
                <FormItem label="客户微信" prop="weixin" v-if="btx('weixin')"
                          :rules="{required: true, message: '客户微信不能为空', trigger: 'blur'}">
                    <Input v-model="formValidate.weixin"></Input>
                </FormItem>
                <FormItem label="客户微信" prop="weixin" v-if="!btx('weixin')">
                    <Input v-model="formValidate.weixin"></Input>
                </FormItem>
                </Col>
            </Row>
            <Row>
               
             <!--   <Col :lg="6" :md="6">
                <FormItem label="交通工具" prop="jiaotonggongju" v-if="btx('jiaotonggongju')"
                          :rules="{required: true, message: '交通工具不能为空', trigger: 'blur'}">
                    <Select placeholder="" :transfer="true" v-model="formValidate.jiaotonggongju">
                        <Option v-for="item in JSON.parse( this.peizhi.jiaotonggongju[0].base_desp)"
                                :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="交通工具" prop="jiaotonggongju" v-if="!btx('jiaotonggongju')">
                    <Select placeholder="" :transfer="true" v-model="formValidate.jiaotonggongju">
                        <Option v-for="item in JSON.parse( this.peizhi.jiaotonggongju[0].base_desp)"
                                :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                        </Option>
                    </Select>
                </FormItem>
                </Col>
                -->
				
				<Col :lg="16" :md="16">
				<FormItem label="交通工具" prop="jiaotonggongju" v-if="btx('jiaotonggongju')"  :rules="{required: true, message: '交通工具不能为空', trigger: 'change'}">
				    <Radio-group  v-model="formValidate.jiaotonggongju" >
				        <Radio label="自行车">							
				        	<span>自行车</span>
				        </Radio>
				        <Radio label="电动车">
				        	
				        	<span>电动车</span>
				        </Radio>
				        <Radio label="汽车">							
				        	<span>汽车</span>
				        </Radio>
				        <Radio label="步行">							
				        	<span>步行</span>
				        </Radio>
				        <Radio label="摩托车">							
				        	<span>摩托车</span>
				        </Radio>
				        <Radio label="公共交通">							
				        	<span>公共交通</span>
				        </Radio>
						 <Radio label="其他">							
							<span>其他</span>
						</Radio>	
				    </Radio-group>					
				</FormItem>
				<FormItem label="交通工具" prop="jiaotonggongju" v-if="!btx('jiaotonggongju')">
					 <RadioGroup v-model="formValidate.jiaotonggongju">
						<Radio label="自行车">							
							<span>自行车</span>
						</Radio>
						<Radio label="电动车">
							
							<span>电动车</span>
						</Radio>
						<Radio label="汽车">							
							<span>汽车</span>
						</Radio>
						<Radio label="步行">							
							<span>步行</span>
						</Radio>
						<Radio label="摩托车">							
							<span>摩托车</span>
						</Radio>
						<Radio label="公共交通">							
							<span>公共交通</span>
						</Radio>
						 <Radio label="其他">							
							<span>其他</span>
						</Radio>	
					</RadioGroup>				    
				</FormItem>				 
				</Col>
				 <Col :lg="8" :md="8">
				<FormItem label="客户车型" prop="chexing" v-if="btx('chexing')"
				          :rules="{required: true, message: '车型不能为空', trigger: 'blur'}">
				    <Input v-model="formValidate.chexing"></Input>
				</FormItem>
				<FormItem label="客户车型" prop="chexing"  v-if="!btx('chexing')">
				    <Input v-model="formValidate.chexing"></Input>
				</FormItem>
				</Col>
            </Row>
            <Row>
                <Col :lg="24" :md="24">
                <FormItem label="备注" prop="mark" v-if="btx('mark')"
                          :rules="{required: true, message: '备注不能为空', trigger: 'blur'}">
                    <Input type="textarea" placeholder="" v-model="formValidate.mark"></Input>
                </FormItem>
                <FormItem label="备注" prop="mark" v-if="!btx('mark')">
                    <Input type="textarea" placeholder="" v-model="formValidate.mark"></Input>
                </FormItem>
                </Col>
                <!-- <Col :lg="12" :md="12">
                <FormItem label="核心备注" prop="core_mark" v-if="btx('core_mark')"
                          :rules="{required: true, message: '备注不能为空', trigger: 'blur'}">
                    <Input type="textarea" placeholder="" v-model="formValidate.core_mark"></Input>
                </FormItem>
                <FormItem label="核心备注" prop="core_mark"  v-if="!btx('core_mark')">
                    <Input type="textarea" placeholder="" v-model="formValidate.core_mark"></Input>
                </FormItem>
                </Col> -->
            </Row>
        </Form>
    </Modal>
</template>
<script>
    export default {
        name: 'customerSellAdd',
        props: ['peizhi','editRoom','formValidate'],
        data() {
            return {
                ruleValidate:{
                    customer_name: [
                        {required: true, message: '请输入分销公司', trigger: 'blur'}
                    ],
                },
               
                cityList1: [
                    {
                        value: 'zhuzhai',
                        label: '住宅'
                    },
                    {
                        value: 'bieshu',
                        label: '别墅'
                    },
                    {
                        value: 'xiezilou',
                        label: '写字楼'
                    },
                    {
                        value: 'shangpu',
                        label: '商铺'
                    },
                    {
                        value: 'cangku',
                        label: '仓库'
                    },
                    {
                        value: 'changfang',
                        label: '厂房'
                    },
                    {
                        value: 'tudi',
                        label: '土地'
                    },
                    {
                        value: 'chewei',
                        label: '车位'
                    }
                ],
                cityList2: [
                    {
                        value: 'zhuzhai',
                        label: '住宅'
                    },
                    {
                        value: 'bieshu',
                        label: '别墅'
                    },
                    {
                        value: 'xiezilou',
                        label: '写字楼'
                    },
                    {
                        value: 'shangpu',
                        label: '商铺'
                    },
                    {
                        value: 'cangku',
                        label: '仓库'
                    },
                    {
                        value: 'changfang',
                        label: '厂房'
                    },
                    {
                        value: 'tudi',
                        label: '土地'
                    },
                    {
                        value: 'chewei',
                        label: '车位'
                    }
                ],
                model10: [],
                fruit: [],
            };
        },
        methods: {
            modalCancel () {
                this.addHouse = false;
                this.$refs['formValidate'].resetFields();//清空form规则检查
                this.$emit('resetModal');
            }, btx (value) {
                var t = this.peizhi.bitianxiang.indexOf(value);
                if (t != '-1') {
                    return true;
                }

            }
            ,xuqiumianji(value,label){
                var arr = value.split(';');
                this.formValidate.xuqiumianji_min=arr[0];
                this.formValidate.xuqiumianji_max=arr[1];
            },xuqiulouceng(value,label){
                var arr = value.split(';');
                this.formValidate.xuqiulouceng_min=arr[0];
                this.formValidate.xuqiulouceng_max=arr[1];
            },xuqiuhuxing(value,label){
                var arr = value.split(';');
                this.formValidate.xuqiuhuxing_min=arr[0];
                this.formValidate.xuqiuhuxing_max=arr[1];
            },xuqiujiage(value,label){
                var arr = value.split(';');
                this.formValidate.xuqiujiage_min=arr[0];
                this.formValidate.xuqiujiage_max=arr[1];
            },xuqiufangling(value,label){
                var arr = value.split(';');
                this.formValidate.xuqiufangling_min=arr[0];
                this.formValidate.xuqiufangling_max=arr[1];
            },
            changeVillage(value, selectedData){
                //设置小区信息
                this.formValidate.village_id = selectedData[1].value;
                this.formValidate.village_name = selectedData[1].label;
                let dts = selectedData[1].value;
                this.$http.get(api_param.apiurl + 'village/getvillage', {
                    params: {
                        dts_id: dts
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调
                        this.formValidate.village='';
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
            },
            modalOk(){
                var data = {
                    customer_uuid: this.formValidate.customer_uuid,
                    customer_name:this.formValidate.customer_name,
                    customer_sex:this.formValidate.customer_sex,
                    tiaojianbiaoqian: this.formValidate.tiaojianbiaoqian,
                    xuqiuquyu: this.formValidate.xuqiuquyu,
                    duoxuanbiaoqian: this.formValidate.duoxuanbiaoqian,
                    yongtu: this.formValidate.yongtu,
                    xuqiuhuxing:this.formValidate.xuqiuhuxing,
                    xuqiuhuxing_min: this.formValidate.xuqiuhuxing_min,
                    xuqiuhuxing_max: this.formValidate.xuqiuhuxing_max,
                    xuqiujiage_min: this.formValidate.xuqiujiage_min,
                    xuqiujiage_max: this.formValidate.xuqiujiage_max,
                    xuqiumianji_min: this.formValidate.xuqiumianji_min,
                    xuqiumianji_max: this.formValidate.xuqiumianji_max,
                    xuqiulouceng_min: this.formValidate.xuqiulouceng_min,
                    xuqiulouceng_max: this.formValidate.xuqiulouceng_max,
                    xuqiufangling_min: this.formValidate.xuqiufangling_min,
                    xuqiufangling_max: this.formValidate.xuqiufangling_max,
                    chaoxiang: this.formValidate.chaoxiang,
                    zhuangxiu: this.formValidate.zhuangxiu,
                    peitao: this.formValidate.peitao,
                    xuqiuyuanying:this.formValidate.xuqiuyuanying,
                    fangwuleixing: this.formValidate.fangwuleixing,
                    goutongjieduan: this.formValidate.goutongjieduan,
                    kehulaiyuan:this.formValidate.kehulaiyuan,
                    xiaofeilinian: this.formValidate.xiaofeilinian,
                    village: this.formValidate.village,
                    guoji: this.formValidate.guoji,
                    minzu: this.formValidate.minzu,
                    zhengjianhaoma: this.formValidate.zhengjianhaoma,
                    email: this.formValidate.email,
                    qq: this.formValidate.qq,
                    weixin: this.formValidate.weixin,
                    jiaotonggongju: this.formValidate.jiaotonggongju,
                    chexing:this.formValidate.chexing,
                    mark: this.formValidate.mark,
                    core_mark: this.formValidate.core_mark,
                    zhuangtai: this.formValidate.zhuangtai,
					kehudengji: this.formValidate.kehudengji,
                };
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + '/customer/edit',
                            data,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            this.$Message.success(response.data.message);
                             this.modalCancel();
                        }, function (response) {
                            // 这里是处理错误的回调
                            //console.log(response)
                            this.modalCancel();
                            this.$Message.warning('更新失败');
                        });
                    }
                });

            }
        }
    };
</script>
