<style scoped>
    @import "roomdetails.less";
</style>

<template>
    <Row>
        <Col :lg="24" :md="24" style="padding-bottom: 10px">
        <Card>
            <Row>
                <Col class="roomCol" v-if="data.buttonauth == 1">
                <div class="roomHeader" @click="addRoom">
                    <p>
                        <i class="icon iconfont icon-liebiao" style="font-size: 27px"></i>
                    </p>
                    <span>修改信息</span>
                </div>
                <editDetails :editRoom="editRoom" :formValidate.sync="data" :settings.sync="settings"
                             :roommodaltitle.sync="roommodaltitle" ref="editDetails" v-on:resetModal="resetModal"
                             v-on:getDetail="getDetail"></editDetails>
                </Col>
                <Col class="roomCol" v-if="data.buttonauth == 1">
                <div class="roomHeader" @click="rChange =true">
                    <p>
                        <i class="icon iconfont icon-shalou" style="font-size: 24px"></i>
                    </p>
                    <span>变更状态</span>
                </div>
                <Modal v-model="rChange" title="变更状态" :closable="true" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="StatusModalCancel" style="display: block!important"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">变更状态</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="StatusModalCancel">取消</Button>
                        <Button type="primary" :disabled="isDisable" size="large" @click="StatusModalOk">确定</Button>
                    </div>
                    <Form ref="changeStatus" :model="changeStatus" :rules="ruleStatusValidate" :label-width="80">
                        <FormItem label="原状态" prop="">
                            <Input placeholder="" readonly :value="data.house_status_text"></Input>
                        </FormItem>
                        <FormItem label="现状态" prop="house_status">
                            <Select placeholder="选择状态" v-model="changeStatus.house_status" :transfer="true">
                                <Option value="1">有效</Option>
                                <Option value="3">无效</Option>
                            </Select>
                        </FormItem>
                        <FormItem label="变更理由" prop="content">
                            <Input placeholder="变更理由" v-model="changeStatus.content"></Input>
                        </FormItem>
                    </Form>
                </Modal>
                </Col>
                <Col class="roomCol">
                <div class="roomHeader" v-if="data.house_private!=1 && data.setprivate==1" @click="setPrivate">
                    <p>
                        <i class="icon iconfont icon-fangyuan" style="font-size: 27px"></i>
                    </p>
                    <span>共享盘</span>
                </div>
                </Col>
                <Col class="roomCol" v-if="data.sale_type=='2' && data.buttonauth == 1 && settings.topbutton.setmain_sell=='1'">
                <div class="roomHeader" v-if="data.is_main === '0'" @click="setMain">
                    <p>
                        <i class="icon iconfont icon-zan" style="font-size: 27px"></i>
                    </p>
                    <span>设为主推</span>
                </div>
                <div class="roomHeader" v-else @click="setMain">
                    <p>
                        <i class="icon iconfont icon-shebeidabaolidequxiaoxuanzhong" style="font-size: 27px"></i>
                    </p>
                    <span>取消主推</span>
                </div>
                </Col>
                <Col class="roomCol">
                <div class="roomHeader" @click="changeFollowup">
                    <p>
                        <i class="icon iconfont icon-xie" style="font-size: 27px"></i>
                    </p>
                    <span>写跟进</span>
                </div>
                </Col>
                <Modal v-model="rFollowup" title="写跟进" :closable="true" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="FollowupModalCancel" style="display: block!important"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">写跟进</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="FollowupModalCancel">取消</Button>
                        <Button type="primary" size="large" @click="FollowupModalOk" :disabled="isDisable">确定</Button>
                    </div>
                    <Form ref="followup" :model="followup" :rules="followupValidate" :label-width="80">
                        <Row>
                            <Col :lg="24" :md="24">
                            <FormItem label="跟进方式" prop="hf_type">
                                <Select placeholder="" v-model="followup.hf_type" :transfer="true">
                                    <Option v-for="item in settings.gengjinfangshi" :value="item" :key="item">{{ item }}
                                    </Option>
                                </Select>
                            </FormItem>
                            </Col>
							<Col :lg="24" :md="24">
								<FormItem label="跟进内容" prop="hf_content">
								    <Input type="textarea" v-model="followup.hf_content" :autosize="{minRows: 4,maxRows: 6}"
								           placeholder=""></Input>
								</FormItem>
							</Col>
                        </Row>

                    </Form>
                </Modal>
                <Col class="roomCol" v-if="data.is_fengpan == 0 && data.private_user > 0">
                <div class="roomHeader" @click="rFengpan = true">
                    <p>
                        <i class="icon iconfont icon-renzhengfengsuo" style="font-size: 24px"></i>
                    </p>
                    <span>申请封盘</span>
                </div>
                <Modal v-model="rFengpan" title="申请封盘" :closable="true" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="FengpanModalCancel" style="display: block!important"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">申请封盘</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="FengpanModalCancel">取消</Button>
                        <Button type="primary" :disabled="isDisable" size="large" @click="FengpanModalOk">确定</Button>
                    </div>

                    <Form ref="fengpan" :rules="fengpanValidate" :model="fengpan" :label-width="80">
                        <Row>
                            <Col :lg="16" :md="16">
                            <FormItem label="申请封盘" prop="is_fengpan">
                                <Select placeholder="" :transfer="true" v-model="fengpan.is_fengpan">
                                    <Option value="1">意向金封盘</Option>
                                    <Option value="2">定金封盘</Option>
                                </Select>
                            </FormItem>
                            </Col>
                        </Row>
                        <Row>
                            <div style="margin-left: 82px;">
                                <div class="demo-upload-list" v-for="item in uploadFengpanList">
                                    <template>
                                        <img :src="item.url">
                                        <div class="demo-upload-list-cover">
                                            <Icon type="ios-eye-outline" @click.native="handleFengpanView(item.name)"></Icon>
                                            <Icon type="ios-trash-outline" @click.native="handleFengpanRemove(item)"></Icon>
                                        </div>
                                    </template>
                                </div>
                                <Upload ref="uploadFengpan" :show-upload-list="false" :default-file-list="defaultList" v-if="uploadFengpanList.length<1"
                                        :headers="{'X-Access-Token':xtoken}"
                                        :on-success="handleFengpanSuccess" :format="['jpg','jpeg','png']" :max-size="10240"
                                        :on-format-error="handleFengpanFormatError"
                                        :on-exceeded-size="handleFengpanMaxSize" :before-upload="handleFengpanBeforeUpload" multiple
                                        type="drag" :action="uploadurl" style="display: inline-block;width:120px;">
                                    <div style="width: 120px;height:120px;line-height: 120px;">
                                        <Icon type="camera" size="30"></Icon>
                                    </div>
                                </Upload>
                                <div style="margin-top: 10px;width: 100%;">封盘协议书</div>
                            </div>
                        </Row>
                        <p style="margin-left: 55px;margin-top: 20px;">Tips:1、意向金封盘24小时，定金封盘3天</p>
                        <p style="margin-left: 55px;margin-top: 5px;"><span style="opacity: 0;">Tips:</span>2、跨公司封盘审核，时间段内未审核，系统自动审核。</p>
                    </Form>
                </Modal>
                </Col>
                <Col class="roomCol">
                    <div class="roomHeader" @click="yibanweituoModal = true">
                        <p>
                            <i class="icon iconfont icon-dujiadaili" style="font-size: 27px"></i>
                        </p>
                        <span>一般委托</span>
                    </div>
                    <Modal v-model="yibanweituoModal" title="一般委托" :closable="true" :mask-closable="false">
                        <div slot="header">
                            <a class="ivu-modal-close" @click="yibanweituoModalCancel" style="display: block!important"><i
                                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                            <div class="ivu-modal-header-inner">一般委托</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="yibanweituoModalCancel">取消</Button>
                            <Button type="primary" :disabled="isDisable" size="large" @click="yibanweituoModalOk">确定</Button>
                        </div>

                        <Form :label-width="80" ref="yibanweituo" :rules="yibanweituoValidate" :model="yibanweituo">
                            <Row>
                                <Col :lg="24" :md="24">
                                    <FormItem label="委托编号" prop="hw_sn">
                                        <Input placeholder="" v-model="yibanweituo.hw_sn"></Input>
                                    </FormItem>
                                </Col>
                                <Col :lg="12" :md="12">
                                    <FormItem label="部门选择" prop="departpath">
                                        <Cascader :data="settings.alldepartlist" :value="yibanweituo.departpath" filterable change-on-select
                                                  @on-change="changeYibanWeituoDepart" :transfer="true"></Cascader>
                                    </FormItem>
                                </Col>
                                <Col :lg="12" :md="12">
                                    <FormItem label="委托人" prop="hw_u_id">
                                        <Select v-model="yibanweituo.hw_u_id" placeholder="" :transfer="true">
                                            <Option v-for="item in yibanweituo_users" :value="item.u_id" :key="item.u_id">
                                                {{item.u_name}}
                                            </Option>
                                        </Select>
                                    </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <div style="margin-left: 82px;">
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
                                </div>
                            </Row>
                        </Form>
                    </Modal>
                </Col>
                <Col class="roomCol" v-if="data.is_dujia =='0'">
<!--                    v-if="data.is_dujia =='0'"-->
                <div class="roomHeader" @click="dujiaweituoModal = true">
                    <p>
                        <i class="icon iconfont icon-dujiadaili" style="font-size: 27px"></i>
                    </p>
                    <span>独家委托</span>
                </div>
                <Modal v-model="dujiaweituoModal" title="独家委托" :closable="true" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="dujiaweituoModalCancel" style="display: block!important"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">独家委托</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="dujiaweituoModalCancel">取消</Button>
                        <Button type="primary" :disabled="isDisable" size="large" @click="dujiaweituoModalOk">确定</Button>
                    </div>

                    <Form :label-width="80" ref="dujiaweituo" :rules="dujiaweituoValidate" :model="dujiaweituo">
                        <Row>
                            <Col :lg="12" :md="12">
                            <FormItem label="委托编号" prop="hw_sn">
                                <Input placeholder="" v-model="dujiaweituo.hw_sn"></Input>
                            </FormItem>
                            </Col>
                            <Col :lg="12" :md="12">
                                <FormItem label="签赔金" prop="qianpei">
                                    <Input placeholder="" v-model="dujiaweituo.qianpei"></Input>
                                </FormItem>
                            </Col>
                            <Col :lg="12" :md="12">
                            <FormItem label="部门选择" prop="departpath">
                                <Cascader :data="settings.alldepartlist" :value="dujiaweituo.departpath" filterable change-on-select
                                          @on-change="changeDujiaWeituoDepart" :transfer="true"></Cascader>
                            </FormItem>
                            </Col>
                            <Col :lg="12" :md="12">
                            <FormItem label="委托人" prop="hw_u_id">
                                <Select v-model="dujiaweituo.hw_u_id" placeholder="" :transfer="true">
                                    <Option v-for="item in dujiaweituo_users" :value="item.u_id" :key="item.u_id">
                                        {{item.u_name}}
                                    </Option>
                                </Select>
                            </FormItem>
                            </Col>

                            <Col :lg="12" :md="12">
                            <FormItem label="开始时间" prop="hw_start_time">
                                <DatePicker type="date" :value="dujiaweituo.hw_start_time" format="yyyy-MM-dd"
                                            @on-change="dujiaweituo.hw_start_time=$event" placeholder="开始时间"
                                            :transfer="true"></DatePicker>
                            </FormItem>
                            </Col>
                            <Col :lg="12" :md="12">
                            <FormItem label="结束时间" prop="hw_end_time">
                                <DatePicker type="date" :value="dujiaweituo.hw_end_time" format="yyyy-MM-dd"
                                            @on-change="dujiaweituo.hw_end_time=$event" placeholder="结束时间"
                                            :transfer="true"></DatePicker>
                            </FormItem>
                            </Col>

                        </Row>
                        <Row>
                            <Col :lg="12" :md="12" style="text-align: center">
                                <div>
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
                                </div>
                            </Col>
                            <Col :lg="12" :md="12" style="text-align: center">
                            <div>
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
                            </div>
                            </Col>
                        </Row>
                    </Form>
                </Modal>
                </Col>

                <Col class="roomCol" v-if="data.is_dujia =='0'">
                <div class="roomHeader" @click="keyModel = true">
                    <p>
                        <i class="icon iconfont icon-yuechi" style="font-size: 27px"></i>
                    </p>
                    <span>钥匙委托</span>
                </div>
                <Modal v-model="keyModel" title="钥匙管理" :closable="true" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="keyModalCancel" style="display: block!important"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">钥匙管理</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="keyModalCancel">取消</Button>
                        <Button type="primary" :disabled="isDisable" size="large" @click="keyModalOk">确定</Button>
                    </div>

                    <Form :label-width="80" ref="formKeyValidate" :rules="rulesKeyValidate" :model="formKeyValidate">
                        <Row>
                            <Col :lg="24" :md="24">
                            <FormItem label="钥匙编号" prop="hk_num">
                                <Input placeholder="" v-model="formKeyValidate.hk_num"></Input>
                            </FormItem>
                            </Col>

                            <Col :lg="12" :md="12">
                            <FormItem label="部门选择" prop="departpath">
                                <Cascader :data="settings.alldepartlist" :value="formKeyValidate.departpath" filterable
                                          change-on-select @on-change="changeKeyDepart" :transfer="true"></Cascader>
                            </FormItem>
                            </Col>
                            <Col :lg="12" :md="12">
                            <FormItem label="委托人" prop="hk_deyaoshiren">
                                <Select v-model="formKeyValidate.hk_deyaoshiren" placeholder="" :transfer="true">
                                    <Option v-for="item in key_users" :value="item.u_id" :key="item.u_id">
                                        {{item.u_name}}
                                    </Option>
                                </Select>
                            </FormItem>
                            </Col>
                        </Row>
                        <Row>
                            <div style="margin-left: 82px;">
                                <div class="demo-upload-list" v-for="item in uploadYaoshiList">
                                    <template>
                                        <img :src="item.url">
                                        <div class="demo-upload-list-cover">
                                            <Icon type="ios-eye-outline" @click.native="handleYaoshiView(item.name)"></Icon>
                                            <Icon type="ios-trash-outline" @click.native="handleYaoshiRemove(item)"></Icon>
                                        </div>
                                    </template>
                                </div>
                                <Upload ref="uploadYaoshi" :show-upload-list="false" :default-file-list="defaultList" v-if="uploadYaoshiList.length<1"
                                        :headers="{'X-Access-Token':xtoken}"
                                        :on-success="handleYaoshiSuccess" :format="['jpg','jpeg','png']" :max-size="10240"
                                        :on-format-error="handleYaoshiFormatError"
                                        :on-exceeded-size="handleYaoshiMaxSize" :before-upload="handleYaoshiBeforeUpload" multiple
                                        type="drag" :action="uploadurl" style="display: inline-block;width:120px;">
                                    <div style="width: 120px;height:120px;line-height: 120px;">
                                        <Icon type="camera" size="30"></Icon>
                                    </div>
                                </Upload>
                                <div style="margin-top: 5px">钥匙协议书</div>
                            </div>
                        </Row>
                    </Form>
                </Modal>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" style="background: #fff;padding:16px;">
        <Card>
            <Tabs v-if="this.$route.params.saleType != 1 " v-model="tabskey.key" type="card" :animated="false">
                <TabPane label="基本信息">
                    <editBasic :data="data" :settings="settings" :followupList="followupList" :seeList="seeList" @getLogs="getLogs" @getDetail="getDetail"></editBasic>
                </TabPane>
                <TabPane label="房屋照片">
                    <editImage :data="data" :settings="settings"></editImage>
                </TabPane>
                <TabPane label="相关照片">
                    <editRelevant :data="data" :settings="settings"></editRelevant>
                </TabPane>
                <!--<TabPane label="房屋视频">开发中</TabPane>-->
                <TabPane label="匹配客户">
                    <editCustomers :data="data" :settings="settings" :cusData="cusData"></editCustomers>
                </TabPane>
                <TabPane label="房源跟进">
                    <editGenjin :data="data" :settings="settings" :followupList="followupList"
                                @getFollowup="getFollowup"></editGenjin>
                </TabPane>
                <TabPane label="带看记录">
                    <editSee ref="editSee" :data="data" :seeList="seeList"></editSee>
                </TabPane>
                <TabPane label="房源日志">
                    <editRizhi :data="data" :logList="logList"></editRizhi>
                </TabPane>
                <TabPane label="相似房源">
                    <editSimilar ref="editSimilar" :houseData="houseData"></editSimilar>
                </TabPane>
                <TabPane label="改价历史">
                    <editLishi :data="data" :updatePriceList="updatePriceList"></editLishi>
                </TabPane>
            </Tabs>
            <Tabs v-if="this.$route.params.saleType == 1 " v-model="tabskey.key" type="card" :animated="false">
                <TabPane label="基本信息">
                    <editBasic :data="data" :settings="settings" @getLogs="getLogs" @getDetail="getDetail"></editBasic>
                </TabPane>
                <TabPane label="房屋照片">
                    <editImage :data="data" :settings="settings"></editImage>
                </TabPane>
                <TabPane label="相关照片">
                    <editRelevant :data="data" :settings="settings"></editRelevant>
                </TabPane>
                <TabPane label="匹配客户">
                    <editCustomers :data="data" :settings="settings" :cusData="cusData"></editCustomers>
                </TabPane>
                <TabPane label="房源跟进">
                    <editGenjin :data="data" :settings="settings" :followupList="followupList"
                                @getFollowup="getFollowup"></editGenjin>
                </TabPane>
                <TabPane label="带看记录">
                    <editSee ref="editSee" :data="data"></editSee>
                </TabPane>
                <TabPane label="房源日志">
                    <editRizhi :data="data" :logList="logList"></editRizhi>
                </TabPane>
                <TabPane label="相似房源">
                    <editSimilar ref="editSimilar" :houseData="houseData"></editSimilar>
                </TabPane>
                <TabPane label="改价历史">
                    <editLishi :data="data" :updatePriceList="updatePriceList"></editLishi>
                </TabPane>

            </Tabs>
        </Card>
        </Col>
    </Row>
</template>

<script>
    import editDetails from './edit-details.vue';
    import editDeal from './edit-deal.vue';
    import editBasic from './edit-basic.vue';
    import editImage from './edit-image.vue';
    import editRelevant from './edit-relevant.vue';
    import editCustomers from './editCustomers.vue';
    import editGenjin from './editGenjin.vue';
    import editSee from './editSee.vue';
    import editSimilar from './editSimilar.vue';
    import editDescribe from './editDescribe.vue';
    import editLishi from './editLishi.vue';
    import editRizhi from './editRizhi.vue';

    export default {
        name: 'roomDetails',
        components: {
            editDetails,//修改信息
            editDeal,//买卖成交
            editBasic,//基本信息
            editImage,//房屋图片
            editRelevant,//相关图片
            editCustomers,//匹配客户
            editGenjin,//房源跟进
            editSee,//带看记录
            editSimilar,//相似房源
            editDescribe,//房源描述
            editLishi,//改价历史
            editRizhi,//房源日志
        },
        data () {
            return {
                isDisable: false,
                keyModel: false,//钥匙管理
                editRoom: false,
                usermodaltitle: '',
                rChange: false,
                rFollowup: false,
                rRemind: false,
                rDeal: false,
                buyDealtitle: '',
                rFengpan: false,
                rReport: false,
                last_url: '',
                next_url: '',
                house_tuijian_tag:[],
                seeList:[],
                peitao:[],
                data: {},
                settings: {
                    topbutton: [],
                    alldepartlist: [],
                    departlist: [],
                },
                roommodaltitle: '',
//                houseinfo:[],
                formDealValidate: {
                    order_type: '',
                    order_deal_date: '',
                    order_deal_did: '',
                    order_deal_dname: '',
                    order_deal_user: '',
                    order_deal_username: '',
                    agreement_sn: '',
                    order_price: '',
                    order_loan: '',
                    order_owner_commission: '',
                    order_customer_commission: '',
                    order_property_did: '',
                    order_property_user: '',
                    order_property_username: '',
                    order_start_time: '',
                    order_end_time: '',
                    house_name: '',
                    house_land_certificate: '',
                    house_property_certificate: '',
                    house_type: '',
                    dts_id: '',
                    dts_name: '',
                    village_id: '',
                    village_name: '',
                    house_building: '',
                    house_unit: '',
                    house_door: '',
                    house_area: '',
                    owner_sn: '',
                    owner_phone: '',
                    owner_name: '',
                    owner_idno: '',
                    owner_address: '',
                    customer_sn: '',
                    customer_phone: '',
                    customer_name: '',
                    customer_idno: '',
                    customer_address: '',
                    deal_departpath: [],
                    property_departpath: [],
                    linkage_departpath: [],
                    consult_departpath: [],
                    order_transfer_id: '',
                    order_transfer_process: '',
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

                followup: {
                    'hf_type': '',
                    'hf_content': '',
                    'departpath': [],
                },
                notify: {
                    'n_u_id': '',
                    'n_time': '',
                    'n_content': ''
                },

                followupList: [],
                logList: [],
                users: [],
                notify_users: [],
                changeStatus: {
                    house_status: '',
                    content: '',
                },
                ruleStatusValidate: {
                    house_status: [{required: true, message: '请选择状态', trigger: 'change'}],
                    content: [{required: true, message: '请填写变更理由', trigger: 'blur'}],
                },
                followupValidate: {//表单验证
                    hf_type: [{required: true, message: '请选择跟进方式', trigger: 'change'}],
                    hf_content: [{required: true, message: '请填写内容', trigger: 'blur'}],
                },
                notifyValidate: {//表单验证
                    n_u_id: [{required: true, message: '请选择提醒人', trigger: 'change'}],
                    n_time: [{required: true, message: '请选择提醒时间', trigger: 'change'}],
                    n_content: [{required: true, message: '请输入提醒内容', trigger: 'blur'}],
                },
                //封盘
                fengpan_users: [],
                uploadFengpanList:[],
                fengpan: {
                    is_fengpan: '',
                    fengpan_image:''
                },
                fengpanValidate: {//表单验证
                    is_fengpan: [{required: true, message: '请选择封盘类型', trigger: 'change'}],
                },
                //一般委托
                yibanweituoModal: false,
                yibanweituo_users: [],
                yibanweituo: {
                    hw_sn: '',
                    departpath: [],
                    hw_u_id:'',
                    weituo_type:1,
                    weituo_image: ''
                },
                yibanweituoValidate: {//表单验证
                    hw_u_id: [{required: true, message: '请选择委托人', trigger: 'change'}],
                },
                //独家委托
                dujiaweituoModal: false,
                dujiaweituo_users: [],
                dujiaweituo: {
                    hw_sn: '',
                    qianpei:'',
                    departpath: [],
                    hw_u_id:'',
                    weituo_type:2,
                    hw_start_time: '',
                    hw_end_time: '',
                    weituo_image: '',
                    qianpei_img:'',
                },
                dujiaweituoValidate: {//表单验证
                    hw_u_id: [{required: true, message: '请选择委托人', trigger: 'change'}],
                    hw_start_time: [{required: true, message: '请选择委托开始时间', trigger: 'change'}],
                    hw_end_time: [{required: true, message: '请选择委托结束时间', trigger: 'change'}]
                },
                uploadqpList:[],
                //钥匙管理
                uploadYaoshiList:[],
                key_users: [],
                formKeyValidate: {
                    hk_num: '',
                    departpath: [],
                    hk_deyaoshiren: '',
                    yaoshi_image: ''
                },
                rulesKeyValidate: { //钥匙管理
                    hk_num: [{required: true, message: '请填写钥匙编号', trigger: 'blur'}],
                    hk_deyaoshiren: [{required: true, message: '请选择收钥匙人', trigger: 'change'}],
                },

                describeList: [],
                updatePriceList: [],
                house_jiu: {
                    peitao: [],
                },
                cusData: [],
                cusNum: [],
                cusPeizhi: {},
                houseData: [],
                houseCount: [],
                sale_type: 2,
                tabskey: {
                    key: 0,
                },
            };
        },
        created () {
            this.getDetail();
            //this.getSetting();
            this.getFollowup();
            this.getSeelist();
            this.getLogs();
            this.getHouseDescribes();
        },
        methods: {
            //获取数据
            getDetail () {
                this.$http.get(api_param.apiurl + 'house/detail?house_id=' + this.$route.params.houseId, {
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {
                        this.$set(this.tabskey, 'key', 0);//tabs标签页
                        for (let i in response.data.data) {
                            if (i == 'huxing_shi' || i == 'huxing_ting' || i == 'huxing_wei' || i == 'huxing_chu' || i == 'huxing_yangtai' || i == 'louceng_now' || i == 'louceng_total') {
                                this.$set(this.data, i, parseInt(response.data.data[i]));
                            } else if (i == 'sell_price' && response.data.data['sell_price'] != null) {
                                this.$set(this.data, i, parseFloat(response.data.data[i]));
                            } else if (i == 'rent_price' && response.data.data['rent_price'] != null) {
                                this.$set(this.data, i, parseFloat(response.data.data[i]));
                            } else if (i == 'shiyongmianji' && response.data.data['shiyongmianji'] != null) {
                                this.$set(this.data, i, parseFloat(response.data.data[i]));
                            } else if (i == 'jianzhumianji' && response.data.data['jianzhumianji'] != null) {
                                this.$set(this.data, i, parseFloat(response.data.data[i]));
                            } else {
                                this.$set(this.data, i, response.data.data[i]);
                            }
                        }
                        this.$set(this.data, 'village', response.data.data['village_id']);
                        this.$set(this.data, 'villageList', response.data.data['village']);
                        this.$set(this.data, 'dts', [response.data.data['area_id'], response.data.data['dts_id']]);
                        this.$set(this.data, 'tmp_dts_id', response.data.data['dts_id']);
                        this.$set(this.data, 'tmp_dts_name', response.data.data['dts_name']);
                        this.$set(this.data, 'tmp_village_id', response.data.data['village_id']);
                        this.$set(this.data, 'tmp_village_name', response.data.data['village_name']);
                        if (response.data.data.house_jiu) {
                            this.house_jiu = response.data.data.house_jiu;
                        }

                        this.formDealValidate.owner_sn = response.data.data.house_sn;
                        this.formDealValidate.owner_phone = response.data.data.customer_phone_deal;
                        this.formDealValidate.owner_name = response.data.data.customer_name;
                        this.formDealValidate.owner_sex = response.data.data.customer_sex;
                        this.formDealValidate.house_name = response.data.data.village_name;
                        this.formDealValidate.house_type = response.data.data.fangwuleixing;
                        this.formDealValidate.dts_id = response.data.data.dts_id;
                        this.formDealValidate.dts_name = response.data.data.dts_name;
                        this.formDealValidate.village_id = response.data.data.village_id;
                        this.formDealValidate.village_name = response.data.data.village_name;
                        this.formDealValidate.house_building = response.data.data.loudong_name;
                        this.formDealValidate.house_unit = response.data.data.danyuan_name;
                        this.formDealValidate.house_door = response.data.data.fanghao_name;
                        this.formDealValidate.house_area = response.data.data.jianzhumianji;
                        this.formDealValidate.order_type = response.data.data.order_type;
//                    this.formDealValidate.contractlist = response.data.data.contractlist;

                        if (response.data.data.czstart_date != '' && response.data.data.czstart_date != null) {
                            this.$set(this.data, 'zfqixian', [response.data.data.czstart_date, response.data.data.czend_date]);
                        } else {
                            this.$set(this.data, 'zfqixian', []);
                        }

                        this.formDealValidate.chuyong = response.data.data.chuyong;
                        //console.log(this.formDealValidate.sale_type);
                        if (response.data.data.is_last) {
                            this.last_url = '/#/roomDetails/' + response.data.data.order_type + '/' + response.data.data.last_house_uuid;
                        }
                        if (response.data.data.is_next) {
                            this.next_url = '/#/roomDetails/' + response.data.data.order_type + '/' + response.data.data.next_house_uuid;
                        }

                        this.getCustomer();
                        this.getXiangshiHouse();
                        this.getSetting();
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        this.$Message.warning(response.data.message);
//                        this.$store.commit('removeTag', 'roomDetails');
//                        this.$router.push({name: 'secondSale_index',})
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            },
            //获取配置文件
            getSetting () {//获取配置项目
                this.$http.get(api_param.apiurl + 'house/getsetting', {
                    params: {
                        sale_type: this.$route.params.saleType
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调

                        for (let i in response.data.data) {
                            this.$set(this.settings, i, response.data.data[i]);
                        }
                        //console.log(this.settings.departlist);
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
                });
            },
            //获取跟进
            getFollowup () {
                this.$http.post(api_param.apiurl + 'house/getfollowups',
                    {'house_id': this.$route.params.houseId},
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.followupList = response.data.data.list;
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
                });
            },
            //带看
            getSeelist() {
                this.$http.get(api_param.apiurl + 'customer_daikan/index',
                    {
                        params: {
                            house_uuid: this.$route.params.houseId,
                            pagesize: 10000,
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.seeList = response.body.data.list;
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
                    // console.log(response);
                });
            },
            //获取日志
            getLogs () {
                this.$http.post(api_param.apiurl + 'house/getlogs',
                    {'house_id': this.$route.params.houseId},
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.logList = response.data.data;
                        let tmpNum = 0;
                        this.updatePriceList = [];
                        for (let i in this.logList) {
                            if (this.logList[i].hl_type == '11') {
                                this.$set(this.updatePriceList, tmpNum, this.logList[i]);
                                tmpNum++;
                            }
                        }
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
                });
            },
            //获取描述
            getHouseDescribes () {
                this.$http.post(api_param.apiurl + 'house/gethousedescribes',
                    {'house_id': this.$route.params.houseId},
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.describeList = response.data.data;
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
                });
            },
            //设置主推
            setMain () {
                let is_main = 1;
                if (this.data.is_main == 1) {
                    is_main = 0;
                }
                this.$http.post(api_param.apiurl + 'house/setmain',
                    {'house_uuid': this.data.house_uuid, 'is_main': is_main},
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.$Message.success(response.data.message);
                        this.getDetail();
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
                    this.$Message.error('网络');
                });
            },
            //共享盘
            setPrivate () {
                this.$http.post(api_param.apiurl + 'house/setprivate',
                    {
                        'house_uuid': this.data.house_uuid,
                        'house_sn': this.data.house_sn,
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.$Message.success(response.data.message);
                        this.getDetail();
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
                });
            },
            //修改部门
            changeDepart (value, selectedData) {
                let d_id = value[value.length - 1];
                this.users = this.settings.users[d_id];
                this.follow.d_id = d_id;
                this.follow.hf_depart_json = value;
                this.follow.departpath = value;
            },
            //通知修改部门
            changeNotifyDepart (value, selectedData) {
                let n_d_id = value[value.length - 1];
                this.notify_users = this.settings.users[n_d_id];
                //console.log(this.notify_users);
                this.notify.n_u_id = n_d_id;
                this.notify.departpath = value;
            },
            //封盘修改部门
            changeFengpanDepart (value, selectedData) {
                let f_d_id = value[value.length - 1];
                this.$http.get(api_param.apiurl + 'user/getuserlist', {
                    params: {d_id: f_d_id},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调
                        this.fengpan_users = response.data.data;
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
                this.fengpan.departpath = value;
            },
            //一般委托修改部门
            changeYibanWeituoDepart (value, selectedData) {
                let f_d_id = value[value.length - 1];
                this.$http.get(api_param.apiurl + 'user/getuserlist', {
                    params: {d_id: f_d_id},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调
                        this.yibanweituo_users = response.data.data;
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
                this.yibanweituo.departpath = value;
            },
            //独家委托修改部门
            changeDujiaWeituoDepart (value, selectedData) {
                let f_d_id = value[value.length - 1];
                this.$http.get(api_param.apiurl + 'user/getuserlist', {
                    params: {d_id: f_d_id},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调
                        this.dujiaweituo_users = response.data.data;
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
                this.dujiaweituo.departpath = value;
            },
            //钥匙修改部门
            changeKeyDepart (value, selectedData) {
                let f_d_id = value[value.length - 1];
                this.$http.get(api_param.apiurl + 'user/getuserlist', {
                    params: {d_id: f_d_id},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调
                        this.key_users = response.data.data;
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
                this.formKeyValidate.departpath = value;
            },
            //写跟进
            changeFollowup () {
                this.rFollowup = true;
            },
            //跟进取消
            FollowupModalCancel () {//修改取消
                this.isDisable = false;
                this.followup.hf_type = '';
                this.followup.hf_content = '';
                this.$refs['followup'].resetFields();
                this.rFollowup = false;
            },
            //跟进确定
            FollowupModalOk () {//修改状态
                this.$refs['followup'].validate((valid) => {
                    if (valid) {
                        this.isDisable = true;
                        setTimeout(() => {
                            this.isDisable = false;
                        }, 1000);

                        this.followup.house_id = this.data.house_uuid;
                        this.followup.sale_type = this.data.house_sale_type;
                        this.$http.post(api_param.apiurl + 'house/followup',
                            this.followup,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.FollowupModalCancel();
                                this.getFollowup();
                                if (this.data.customer_phone == '******') {
                                    this.$set(this.data, 'customer_phone', response.data.data.customer_phone);
                                    this.$set(this.data, 'house_phone', response.data.data.house_phone);
                                }
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
                        });
                    }
                });
            },
            //通知取消
            NotifyModalCancel () {//修改取消
                this.rRemind = false;
            },
            //通知确定
            NotifyModalOk () {//修改状态
                this.$refs['notify'].validate((valid) => {
                    if (valid) {
                        this.notify.house_id = this.data.house_id;
                        this.$http.post(api_param.apiurl + 'house/notify',
                            this.notify,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.rRemind = false;
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
                        });
                    }
                });
            },
            //封盘取消
            FengpanModalCancel () {//修改取消
                this.isDisable = false;
                this.fengpan.is_fengpan = '';
                this.uploadFengpanList = [];
                this.$refs['fengpan'].resetFields();
                this.rFengpan = false;
            },
            //封盘确定
            FengpanModalOk () {
                this.$refs['fengpan'].validate((valid) => {
                    this.isDisable = true;
                    setTimeout(() => {
                        this.isDisable = false;
                    }, 1000);
                    if (valid) {
                        this.fengpan.house_uuid = this.data.house_uuid;
                        this.fengpan.house_sn = this.data.house_sn;
                        if(this.uploadFengpanList.length < 1){
                            this.$Message.success('请上传图片');
                        }
                        this.fengpan.fengpan_image = this.uploadFengpanList[0]['name'];
                        this.$http.post(api_param.apiurl + 'house/fengpan',
                            this.fengpan,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.FengpanModalCancel();
                                this.getDetail();
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
                        });
                    }
                });
            },

            //一般委托
            yibanweituoModalOk () {
                this.$refs['yibanweituo'].validate((valid) => {
                    if (valid) {
                        this.isDisable = true;
                        this.yibanweituo.house_uuid = this.data.house_uuid;
                        this.yibanweituo.house_sn = this.data.house_sn;
                        if(this.uploadYibanList.length < 1){
                            this.$Message.success('请上传图片');
                        }
                        this.yibanweituo.weituo_image = this.uploadYibanList[0]['name'];
                        this.$http.post(api_param.apiurl + 'house/addweituo',
                            this.yibanweituo,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.yibanweituoModalCancel()
                                this.getDetail();
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
                        });
                    }
                });
            },
            yibanweituoModalCancel () {//修改取消
                this.isDisable = false;
                this.uploadYibanList = [];
                this.$refs['yibanweituo'].resetFields();
                this.yibanweituoModal = false;
            },
            //独家委托
            dujiaweituoModalOk () {
                this.$refs['dujiaweituo'].validate((valid) => {
                    if (valid) {
                        //this.isDisable = true;
                        this.dujiaweituo.house_uuid = this.data.house_uuid;
                        this.dujiaweituo.house_sn = this.data.house_sn;
                        if(this.dujiaweituo.qianpei>0){
                            if(this.uploadqpList.length == 1){
                                this.dujiaweituo.qianpei_img = this.uploadqpList[0]['name'];
                            }else{
                                this.$Message.warning('请上传签赔金图片');
                                return;
                            }
                        }

                        if(this.uploadDujiaList.length < 1){
                            this.$Message.warning('请上传独家委托图片');
                        }
                        this.dujiaweituo.weituo_image = this.uploadDujiaList[0]['name'];
                        this.$http.post(api_param.apiurl + 'house/addweituo',
                            this.dujiaweituo,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.yibanweituoModalCancel()
                                this.getDetail();
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
                        });
                    }
                });
            },
            dujiaweituoModalCancel () {//修改取消
                this.isDisable = false;
                this.uploadDujiaList = [];
                this.$refs['dujiaweituo'].resetFields();
                this.dujiaweituoModal = false;
            },

            //钥匙取消
            keyModalCancel () {//
                this.$refs['formKeyValidate'].resetFields();
                this.formKeyValidate.departpath = [];
                this.uploadYaoshiList = [];
                this.keyModel = false;
            },
            //钥匙确定
            keyModalOk () {
                this.$refs['formKeyValidate'].validate((valid) => {
                    if(this.uploadYaoshiList.length < 1){
                        this.$Message.success('请上传图片');
                    }
                    this.formKeyValidate.yaoshi_image = this.uploadYaoshiList[0]['name'];
                    if (valid) {
                        this.isDisable = true;
                        setTimeout(() => {
                            this.isDisable = false;
                        }, 1000);
                        this.$http.post(api_param.apiurl + 'house/addkey',
                            {
                                'house_uuid': this.data.house_uuid,
                                'hk_num': this.formKeyValidate.hk_num,
                                'hk_deyaoshiren': this.formKeyValidate.hk_deyaoshiren,
                                'hk_shouju': this.formKeyValidate.yaoshi_image
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.$refs['formKeyValidate'].resetFields();
                                this.formKeyValidate.departpath = [];
                                this.keyModel = false;
                                this.getDetail();
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
                        });
                    }
                });
            },

            //修改信息
            addRoom () {
                this.editRoom = false;
                this.editRoom = true;
                this.$refs.editDetails.setMakes();
                this.roommodaltitle = '修改房源';
            },
            resetModal () {
                //console.log(this.editRoom);
                this.roommodaltitle = '';
                this.editRoom = false;
                //买卖成交
                this.buyDealtitle = '';
                this.rDeal = false;
            },
            //买卖成交
            rBuyDeal () {
                this.buyDealtitle = '买卖成交';
                this.rDeal = true;
            },

            StatusModalCancel () {//修改取消
                this.$refs['changeStatus'].resetFields();
                this.changeStatus.house_status = '';
                this.changeStatus.contet = '';
                this.rChange = false;
                this.isDisable = false;
            },
            StatusModalOk () {//修改状态
                this.$refs['changeStatus'].validate((valid) => {
                    if (valid) {
                        this.isDisable = true;
                        this.changeStatus.house_uuid = this.data.house_uuid;
                        this.changeStatus.house_sn = this.data.house_sn;
                        this.$http.post(api_param.apiurl + 'house/setstatus',
                            this.changeStatus,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.getDetail();
                                this.rChange = false;
                            } else if (response.data.code == 401) {
                                this.$store.commit('logout', this);
                                this.$store.commit('clearOpenedSubmenu');
                                this.$router.push({
                                    name: 'login'
                                });
                            } else {
                                this.$Message.warning(response.data.message);
                            }
                            this.isDisable = false;
                        }, function (response) {
                            // 这里是处理错误的回调
                            this.$Message.error('网络异常');
                        });
                    }
                });
            },

            getCustomer () {
                var data = {
                    page: 1,
                    xuqiumianji_min: parseFloat(this.data.jianzhumianji) - 10,
                    xuqiumianji_max: parseFloat(this.data.jianzhumianji) + 10,
                };
                if (this.data.sale_type == '2') {
                    data.xuqiujiage_min = parseFloat(this.data.sell_price) - 10;
                    data.xuqiujiage_max = parseFloat(this.data.sell_price) + 10;
                    data.customer_type = 0;
                }


                this.$http.get(api_param.apiurl + '/customer/index',
                    {
                        params: data,
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.cusNum = response.body.data.count;
                        this.cusData = response.body.data.list;
                        this.cusPeizhi = response.body.data.peizhi;
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
                    // console.log(response);
                });
            },

            getXiangshiHouse () {
                let data = {
                    page: 1,
                    sale_type: this.data.order_type,
                    nohouseid: this.data.house_id
                };
                if (this.data.order_type != 1) {
                    let sell_jgqj_start = parseInt(this.data.low_sell_price) - 20;
                    let sell_jgqj_end = parseInt(this.data.low_sell_price) + 20;
                    data.sell_jgqj = sell_jgqj_start + '-' + sell_jgqj_end;
                    let mjqj_start = parseInt(this.data.jianzhumianji) - 10;
                    let mjqj_end = parseInt(this.data.jianzhumianji) + 10;
                    data.mjqj = mjqj_start + '-' + mjqj_end;
                } else {
                    let sell_jgqj_start = parseInt(this.data.rent_price) - 300;
                    let sell_jgqj_end = parseInt(this.data.rent_price) + 300;
                    data.rent_jgqj = sell_jgqj_start + '-' + sell_jgqj_end;
                    data.huxing_shi = this.data.huxing_shi;
                }

                this.$http.post(api_param.apiurl + 'house/getindex',
                    data,
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.houseCount = response.data.data.count;
                        this.houseData = response.data.data.list;
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
                    // console.log(response);
                });
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
            // 钥匙委托图片上传
            handleYaoshiView(name) {
                this.imgName = name;
                this.visible = true;
            },
            handleYaoshiRemove(file) {
                this.uploadDujiaList = [];
            },
            handleYaoshiSuccess(res, file) {
                file.url = api_param.imgurl + res.data.url;
                file.name = res.data.url;
                this.uploadYaoshiList.push({'name': file.name, 'url': file.url});
                const fileList = this.$refs.uploadYaoshi.fileList;
                this.$refs.uploadYaoshi.fileList.splice(fileList.indexOf(file), 1);
            },
            handleYaoshiFormatError(file) {
                this.$Message.warning('文件格式不正确，请选择JPG或PNG');
            },
            handleYaoshiMaxSize(file) {
                this.$Message.warning('文件  ' + file.name + ' 超过 10M.');
            },
            handleYaoshiBeforeUpload() {
                const check = this.uploadYaoshiList.length < 1;
                if (!check) {
                    this.$Message.warning('最多上传1张图片');
                }
                return check;
            },
            // 封盘委托图片上传
            handleFengpanView(name) {
                this.imgName = name;
                this.visible = true;
            },
            handleFengpanRemove(file) {
                this.uploadDujiaList = [];
            },
            handleFengpanSuccess(res, file) {
                file.url = api_param.imgurl + res.data.url;
                file.name = res.data.url;
                this.uploadFengpanList.push({'name': file.name, 'url': file.url});
                const fileList = this.$refs.uploadFengpan.fileList;
                this.$refs.uploadFengpan.fileList.splice(fileList.indexOf(file), 1);
            },
            handleFengpanFormatError(file) {
                this.$Message.warning('文件格式不正确，请选择JPG或PNG');
            },
            handleFengpanMaxSize(file) {
                this.$Message.warning('文件  ' + file.name + ' 超过 10M.');
            },
            handleFengpanBeforeUpload() {
                const check = this.uploadFengpanList.length < 1;
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
        },
        watch: {
            '$route.params.houseId' (to, from) {
                if (to && to != 'undefined') {
                    this.getDetail();
                    this.getFollowup();
                    this.getLogs();
                    this.getHouseDescribes();
                    this.getCustomer();
                    this.getXiangshiHouse();
                    this.$refs.editSee.getIndex();
                }
            }
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
