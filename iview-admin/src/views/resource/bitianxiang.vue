<style lang="less">
    .marginBottom {
        margin-bottom: 10px;
    }

    .marginLeft {
        margin-left: 20px;
    }

    .changeColor {
        color: red !important;
    }
</style>

<template>
    <Row justify="center">
        <Col :lg="24" :md="24">
        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate">
            <Row type="flex" justify="center">
                <Col :lg="18" :md="18" v-for="(type, rsetting_type) in types" style="margin-bottom: 10px">
                    <Card :bordered="false">
                        <p slot="title">{{type}}必填项</p>
                        <!--<Button class="marginLeft" type="primary" slot="extra" @click="addOptionBtn(rsetting_type)">新增</Button>-->
                        <!--<Button class="marginLeft" type="primary" slot="extra" @click="copyOptionBtn(rsetting_type)">复制</Button>-->
                        <Row v-if="optionsList.hasOwnProperty(rsetting_type)">
                           <Col :lg="3" :md="3" v-for="(item, index) in optionsList[rsetting_type]['rsetting_options']" class="marginBottom">
                              <CheckboxGroup v-if="rsetting_type === 'sale_type_es0'" v-model="sale_type_es0">
                                 <Checkbox :label="item.option_value" :true-value="item.option_value" v-if="item.option_value ==='dts'" disabled >
                                     <span >{{item.option_text}}</span>
                                 </Checkbox>
								  <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='village_name'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='customer_name'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='loudong_name'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='danyuan_name'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='fanghao_name'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='customer_phone'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='sell_price'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='low_sell_price'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='chuyong'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='house_level'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='house_school'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='house_tag'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								 <Checkbox :label="item.option_value" :true-value="item.option_value" v-else>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
                              </CheckboxGroup>
                              <CheckboxGroup v-if="rsetting_type === 'sale_type_es1'" v-model="sale_type_es1">
                                 <Checkbox :label="item.option_value" :true-value="item.option_value">
                                     <span @click="modifyOptionBtn(rsetting_type, item.option_id, item.option_value, item.option_text)">{{item.option_text}}</span>
                                 </Checkbox>
                              </CheckboxGroup>
                              <CheckboxGroup v-if="rsetting_type === 'sale_type_es2'" v-model="sale_type_es2">
                                 <Checkbox :label="item.option_value" :true-value="item.option_value">
                                     <span @click="modifyOptionBtn(rsetting_type, item.option_id, item.option_value, item.option_text)">{{item.option_text}}</span>
                                 </Checkbox>
                              </CheckboxGroup>
                         <!--     <CheckboxGroup v-if="rsetting_type === 'customer_type0'" v-model="customer_type0">
                                 <Checkbox :label="item.option_value" :true-value="item.option_value">
                                     <span @click="modifyOptionBtn(rsetting_type, item.option_id, item.option_value, item.option_text)">{{item.option_text}}</span>
                                 </Checkbox>
                              </CheckboxGroup> -->
							   <CheckboxGroup v-if="rsetting_type === 'customer_type0'" v-model="customer_type0">
							     <Checkbox :label="item.option_value" :true-value="item.option_value" v-if="item.option_value ==='customer_name'" disabled>
							         <span >{{item.option_text}}</span>
							     </Checkbox>
								  <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='xuqiuquyu'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='kehudengji'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='tel_phone'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='xuqiujiage'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='xuqiumianji'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='tuijianbianqian'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='goutongjieduan'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
								   <Checkbox :label="item.option_value" :true-value="item.option_value" v-else-if="item.option_value ==='kehulaiyuan'" disabled>
								     <span >{{item.option_text}}</span>
								 </Checkbox>																  
								 <Checkbox :label="item.option_value" :true-value="item.option_value" v-else>
								     <span >{{item.option_text}}</span>
								 </Checkbox>
							  </CheckboxGroup>
                              <!--<CheckboxGroup v-if="rsetting_type === 'customer_type1'" v-model="customer_type1">-->
                                 <!--<Checkbox :label="item.option_value" :true-value="item.option_value">-->
                                     <!--<span @click="modifyOptionBtn(rsetting_type, item.option_id, item.option_value, item.option_text)">{{item.option_text}}</span>-->
                                 <!--</Checkbox>-->
                              <!--</CheckboxGroup>-->
                              <!--<CheckboxGroup v-if="rsetting_type === 'customer_type2'" v-model="customer_type2">-->
                                 <!--<Checkbox :label="item.option_value" :true-value="item.option_value">-->
                                     <!--<span @click="modifyOptionBtn(rsetting_type, item.option_id, item.option_value, item.option_text)">{{item.option_text}}</span>-->
                                 <!--</Checkbox>-->
                              <!--</CheckboxGroup>-->

                            <!--<CheckboxGroup v-if="rsetting_type === 'newhouse'" v-model="newhouse">-->
                                <!--<Checkbox :label="item.option_value" :true-value="item.option_value">-->
                                    <!--<span @click="modifyOptionBtn(rsetting_type, item.option_id, item.option_value, item.option_text)">{{item.option_text}}</span>-->
                                <!--</Checkbox>-->
                            <!--</CheckboxGroup>-->

                           </Col>
                        </Row>
                    </Card>
                </Col>
                <Col :lg="18" :md="18">
                <Card :bordered="false">
                    <Row type="flex" justify="center">
                        <Col><!--{{sale_type_es0}}-->
                        <Button type="primary" @click="saveCheckedOptions" lang> 保存</Button>
                        </Col>
                    </Row>
                </Card>
                </Col>
                <Col :lg="24" :md="24">
                <br><br>
                </Col>
            </Row>
        </Form>
        <Modal v-model="editOptionModal" title="编辑选项" :mask-closable="false">
            <div slot="header">
            <a class="ivu-modal-close" @click="saveOptionCancel" style="display: block!important;"><i
                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
            <div class="ivu-modal-header-inner">编辑选项</div>
        </div>
            <div slot="footer">
                <Button type="text" size="large" @click="saveOptionCancel">取消</Button>
                <Button type="primary" size="large" @click="saveOptionOk">确定</Button>
            </div>
            <Form ref="optionformValidate" :model="optionformValidate" :rules="optionruleValidate" :label-width="90">
                <FormItem label="选项值" prop="option_value">
                    <Input v-model="optionformValidate.option_value" placeholder=""></Input>
                </FormItem>
                <FormItem label="选项文本说明" prop="option_text">
                    <Input v-model="optionformValidate.option_text" placeholder=""></Input>
                </FormItem>
            </Form>
        </Modal>
        <Modal v-model="copyOptionModal" title="复制选项" :mask-closable="false">
            <div slot="header">
                <a class="ivu-modal-close" @click="saveCopyCancel" style="display: block!important;"><i
                        class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                <div class="ivu-modal-header-inner">复制选项</div>
            </div>
            <div slot="footer">
                <Button type="text" size="large" @click="saveCopyCancel">取消</Button>
                <Button type="primary" size="large" @click="saveCopyOk">确定</Button>
            </div>
            复制给：
            <select style="width: 150px" v-model="copyType">
                <option disabled value="">请选择</option>
                <option v-for="(type, index) in types" v-bind:value="index">
                     {{ type }} - 必填选项
                </option>
            </select>
        </Modal>
        </Col>
    </Row>
</template>

<script>
    let arr = [];
    export default {
        name: 'bitianxiang',
        data() {
            return {
                types: {
                    'sale_type_es0' : '二手房出售',
                    //'sale_type_es1' : '二手房出租',
                   // 'sale_type_es2' : '二手房高端',
                    'customer_type0' : '买卖客源',
                    //'customer_type1' : '租赁客源',
                },
                sale_type_es0:[],
                sale_type_es1:[],
                sale_type_es2:[],
                customer_type0:[],
                customer_type1:[],
                customer_type2:[],
                newhouse:[],
                selectRsetting_type:'',
                selectOption_id:'',
                saveRsetting_url:'',
                copyType:'',
                housetypes: {},
                customertypes: {},
                optionsList: {},
                optionformValidate: {
                    option_value:'',
                    option_text:''
                },
                optionruleValidate: {
                },
                formValidate: {},
                ruleValidate: {},
                editOptionModal: false,
                copyOptionModal: false
            }
        },
        created: function () {
            this.getOptions();
            this.gethousetypes();
            this.getcustomertypes();
        },
        methods: {
            //获取所有房源类型
            gethousetypes() {
                this.$http.get(api_param.apiurl + '/site/gethousetypes',
                    {
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code === 200) {
                        this.housetypes = response.body.data;
                    } else if (response.data.code === 401) {
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
                    this.$Message.warning('更新失败');
                });
            },
            //获取所有客源类型
            getcustomertypes() {
                this.$http.get(api_param.apiurl + '/site/getcustomertypes',
                    {
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code === 200) {
                        this.customertypes = response.body.data;
                        // this.$Message.success(response.data.message);
                    } else if (response.data.code === 401) {
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
                    this.$Message.warning('更新失败');
                });
            },

            //获取必填项列表
            getOptions() {
                arr = [];
                this.$http.get(api_param.apiurl + '/settingrequired/getlist',
                    {
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        //console.log(response);
                        let optionsList = response.body.data;
                        console.log(optionsList);
                        for(let item in optionsList){
                            let options = JSON.parse(optionsList[item].rsetting_options);
                            optionsList[item].rsetting_options = options;
                            if(optionsList[item].rsetting_desp && optionsList[item].rsetting_desp != ''){
                                this[item] = optionsList[item].rsetting_desp.split(',');
                                //console.log(optionsList[item].rsetting_desp.split(','));
                            }
                        }
                        //console.log(optionsList);
                        this.optionsList = optionsList;
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
                    this.$Message.warning('更新失败');
                });
            },
            //新增选项
            addOptionBtn(type){
                this.editOptionModal = true;
                this.selectRsetting_type = type;
                this.saveRsetting_url = 'addoption';
            },
            //修改选项
            modifyOptionBtn(type, option_id, option_value, option_text){
                this.editOptionModal = true;
                this.selectRsetting_type = type;
                this.saveRsetting_url = 'editoption';
                this.selectOption_id = option_id;
                this.optionformValidate.option_value = option_value;
                this.optionformValidate.option_text = option_text;
            },
            //保存选项信息
            saveOptionOk(){
                // alert(this.selectRsetting_type);
                this.$refs['optionformValidate'].validate((valid) => {
                    if (valid) {
                        let reqData = {
                            rsetting_type: this.selectRsetting_type,
                            option_id: this.selectOption_id,
                            option_value: this.optionformValidate.option_value,
                            option_text: this.optionformValidate.option_text,
                        };
                        this.$http.post(api_param.apiurl + 'settingrequired/' + this.saveRsetting_url,
                            reqData,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code === 200) {
                                this.$refs['optionformValidate'].resetFields();
                                this.$Message.success(response.data.message);
                                this.getOptions();
                                this.editOptionModal = false;
                            } else if (response.data.code === 401) {
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
                            this.$Message.warning('更新失败');
                        });
                    }
                })
            },
            saveOptionCancel(){
                this.$refs['optionformValidate'].resetFields();
                this.editOptionModal = false;
            },
            //复制选项
            copyOptionBtn(type){
                this.copyOptionModal = true;
                this.selectRsetting_type = type;
            },
            //保存复制
            saveCopyOk(){
                if(this.copyType === ''){
                    alert('请选择复制给谁');
                    return;
                }
                if(this.copyType === this.selectRsetting_type){
                    alert('不要选择相同的必填项复制');
                    return;
                }
                let reqData = {
                    src_type: this.selectRsetting_type,
                    dst_type: this.copyType,
                };
                this.$http.post(api_param.apiurl + 'settingrequired/copyoption',
                    reqData,
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code === 200) {
                        this.$Message.success(response.data.message);
                        this.getOptions();
                        this.copyOptionModal = false;
                    } else if (response.data.code === 401) {
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
                    this.$Message.warning('更新失败');
                });
            },
            saveCopyCancel(){
                this.copyOptionModal = false;
            },
            saveCheckedOptions(){
                let reqData = {};
                let arr = [];
                for(let i in this.types){
                    reqData[i] = this[i]
                    arr.push(i);
                }
                reqData.types = arr;
                this.$http.post(api_param.apiurl + 'settingrequired/edit',
                    reqData,
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code === 200) {
                        this.$Message.success(response.data.message);
                        this.getOptions();
                        this.copyOptionModal = false;
                    } else if (response.data.code === 401) {
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
                    this.$Message.warning('更新失败');
                });
            }
        },
        computed: {}

    };
</script>
