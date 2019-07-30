<style lang="less">
    @import "layout.less";

    .tableFang {
        border: #dddee1 1px solid;
        text-align: center;
    }

    .borderFang {
        border-bottom: #dddee1 1px solid;
    }

    .borderFang:last-child {
        border-bottom: none;
    }

    .borderFang .ivu-col {
        border-right: #dddee1 1px solid;
        height: 48px;
        line-height: 48px;
    }

    .borderFang input {
        height: 25px !important;
        text-indent: 5px;
    }

    .tableFang .ivu-col:last-child {
        border-right: none;
    }
</style>

<template>
    <Row class="keyuancounter">
        <Col :lg="24" :md="24" >
        <Card>
        <Row type="flex" justify="end">
            <Col :lg="1" :md="1">

                <Button type="primary" @click="dengAddBtn">设置</Button>

            </Col>
        </Row>
        </Card>
        <Modal v-model="dengAdd" title="客源等级设置" width="960" :mask-closable="false">
            <div slot="header">
                <a class="ivu-modal-close" @click="setCancel" style="display: block!important;"><i
                        class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                <div class="ivu-modal-header-inner">客源等级设置</div>
            </div>
            <div slot="footer">
                <Button type="text" size="large" @click="setCancel">取消</Button>
                <Button type="primary" size="large" @click="setOk">确定</Button>
            </div>
            <Card>
                <Tabs :animated="false" v-model="setTabs" @on-click="clickSetTab">
                    <TabPane label="买卖客源" icon="social-skype">
                        <Form ref="xiugaiformValidate" :model="xiugaiformValidate" :rules="xiugairuleValidate"
                              :label-width="90">
                            <div class="tableFang">
                                <Row type="flex" justify="space-between" class="borderFang">
                                    <!-- <Col :lg="4" :md="4">
                                    客源等级</Col>-->
                                    <Col :lg="4" :md="4">
                                    类型</Col> 
                                    <Col :lg="4" :md="4">
                                    私客跟进周期</Col>
                                    <Col :lg="4" :md="4">
                                    公客跟进周期</Col>
                                    <Col :lg="4" :md="4">
                                    私客带看周期</Col>
                                    <Col :lg="4" :md="4">
                                    公客带看周期</Col>
                                </Row>
                                <Row v-for="(item,index) in this.dengData" type="flex" justify="space-between" v-if="index<=0"
                                     class="borderFang">
                                   <!-- <Col :lg="4" :md="4">
                                    {{item.keyuandengji}}</Col> -->
                                    <Col :lg="4" :md="4">
                                    {{item.keyuanleixing}}</Col>
                                    <Col :lg="4" :md="4">
                                    <p style="display: none;">全员：<input style="width: 50px"
                                                                        v-model="xiugaiformValidate.test1[index]"
                                                                        type="text"/>天</p>
                                    <p>维护人：<input style="width: 50px" v-model="xiugaiformValidate.test1_1[index]"
                                                  type="text"/>天</p>
                                    </Col>
                                    <Col :lg="4" :md="4">
                                    <p>全员：<input style="width: 50px" v-model="xiugaiformValidate.test2[index]"
                                                 type="text"/>天</p>
                                    <p style="display: none;">维护人：<input style="width: 50px"
                                                                         v-model="xiugaiformValidate.test2_2[index]"
                                                                         type="text"/>天</p>
                                    </Col>
                                    <Col :lg="4" :md="4">
                                    <p style="display: none;">全员：<input style="width: 50px"
                                                                        v-model="xiugaiformValidate.test3[index]"
                                                                        type="text"/>天</p>
                                    <p>维护人：<input style="width: 50px" v-model="xiugaiformValidate.test3_3[index]"
                                                  type="text"/>天</p>
                                    </Col>
                                    <Col :lg="4" :md="4">
                                    <p>全员：<input style="width: 50px" v-model="xiugaiformValidate.test4[index]"
                                                 type="text"/>天</p>
                                    <p style="display: none;">维护人：<input style="width: 50px"
                                                                         v-model="xiugaiformValidate.test4_4[index]"
                                                                         type="text"/>天</p>
                                    </Col>

                                </Row>
                            </div>
                        </Form>
                    </TabPane>
<!--                    <TabPane label="租赁客源" icon="ios-paw">-->
<!--                        &lt;!&ndash;<Table :columns="dengColumns" :data="dengData" border stripe></Table>&ndash;&gt;-->
<!--                        <Form ref="xiugaiformValidate" :model="xiugaiformValidate" :rules="xiugairuleValidate"-->
<!--                              :label-width="90">-->
<!--                            <div class="tableFang">-->
<!--                                <Row type="flex" justify="space-between" class="borderFang">-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    客源等级</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    类型</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    私客回访周期</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    公客回访周期</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    私客带看周期</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    公客带看周期</Col>-->
<!--                                </Row>-->
<!--                                <Row v-for="(item,index) in this.dengData" type="flex" justify="space-between"-->
<!--                                     class="borderFang">-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    {{item.keyuandengji}}</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    {{item.keyuanleixing}}</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    <p style="display: none;">全员：<input style="width: 50px"  v-model="xiugaiformValidate.test1[index]"  type="text"/>天</p>-->
<!--                                    <p>维护人：<input style="width: 50px" v-model="xiugaiformValidate.test1_1[index]" type="text"/>天</p>-->
<!--                                    </Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    <p>全员：<input style="width: 50px" v-model="xiugaiformValidate.test2[index]" type="text"/>天</p>-->
<!--                                    <p style="display: none;">维护人：<input style="width: 50px"  v-model="xiugaiformValidate.test2_2[index]" type="text"/>天</p>-->
<!--                                    </Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    <p style="display: none;">全员：<input style="width: 50px" v-model="xiugaiformValidate.test3[index]" type="text"/>天</p>-->
<!--                                    <p>维护人：<input style="width: 50px" v-model="xiugaiformValidate.test3_3[index]"-->
<!--                                                  type="text"/>天</p>-->
<!--                                    </Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    <p>全员：<input style="width: 50px" v-model="xiugaiformValidate.test4[index]" type="text"/>天</p>-->
<!--                                    <p style="display: none;">维护人：<input style="width: 50px" v-model="xiugaiformValidate.test4_4[index]" type="text"/>天</p>-->
<!--                                    </Col>-->
<!--                                </Row>-->
<!--                            </div>-->
<!--                        </Form>-->
<!--                    </TabPane>-->
<!--                    <TabPane label="高端客源" icon="ios-paw">-->
<!--                        &lt;!&ndash;<Table :columns="dengColumns" :data="dengData" border stripe></Table>&ndash;&gt;-->
<!--                        <Form ref="xiugaiformValidate" :model="xiugaiformValidate" :rules="xiugairuleValidate"-->
<!--                              :label-width="90">-->
<!--                            <div class="tableFang">-->
<!--                                <Row type="flex" justify="space-between" class="borderFang">-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    客源等级</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    类型</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    私客回访周期</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    公客回访周期</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    私客带看周期</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    公客带看周期</Col>-->
<!--                                </Row>-->
<!--                                <Row v-for="(item,index) in this.dengData" type="flex" justify="space-between" class="borderFang">-->
<!--                                    <Col :lg="4" :md="4">{{item.keyuandengji}}</Col>-->
<!--                                    <Col :lg="4" :md="4">{{item.keyuanleixing}}</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                        <p style="display: none;">全员：<input style="width: 50px" v-model="xiugaiformValidate.test1[index]" type="text"/>天</p>-->
<!--                                        <p>维护人：<input style="width: 50px" v-model="xiugaiformValidate.test1_1[index]" type="text"/>天</p>-->
<!--                                    </Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                        <p>全员：<input style="width: 50px" v-model="xiugaiformValidate.test2[index]" type="text"/>天</p>-->
<!--                                        <p style="display: none;">维护人：<input style="width: 50px" v-model="xiugaiformValidate.test2_2[index]"  type="text"/>天</p>-->
<!--                                    </Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                        <p style="display: none;">全员：<input style="width: 50px"  v-model="xiugaiformValidate.test3[index]" type="text"/>天</p>-->
<!--                                        <p>维护人：<input style="width: 50px" v-model="xiugaiformValidate.test3_3[index]" type="text"/>天</p>-->
<!--                                    </Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                        <p>全员：<input style="width: 50px" v-model="xiugaiformValidate.test4[index]" type="text"/>天</p>-->
<!--                                        <p style="display: none;">维护人：<input style="width: 50px"  v-model="xiugaiformValidate.test4_4[index]" type="text"/>天</p>-->
<!--                                    </Col>-->
<!--                                </Row>-->
<!--                            </div>-->
<!--                        </Form>-->
<!--                    </TabPane>-->
<!--                    <TabPane label="新房" icon="ios-paw">-->
<!--                        <Form ref="xiugaiformValidate" :model="xiugaiformValidate" :rules="xiugairuleValidate"-->
<!--                              :label-width="90">-->
<!--                            <div class="tableFang">-->
<!--                                <Row type="flex" justify="space-between" class="borderFang">-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    客源等级</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    类型</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    私客回访周期</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    公客回访周期</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    私客带看周期</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                    公客带看周期</Col>-->
<!--                                </Row>-->
<!--                                <Row v-for="(item,index) in this.dengData" type="flex" justify="space-between" class="borderFang">-->
<!--                                    <Col :lg="4" :md="4">{{item.keyuandengji}}</Col>-->
<!--                                    <Col :lg="4" :md="4">{{item.keyuanleixing}}</Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                        <p style="display: none;">全员：<input style="width: 50px" v-model="xiugaiformValidate.test1[index]" type="text"/>天</p>-->
<!--                                        <p>维护人：<input style="width: 50px" v-model="xiugaiformValidate.test1_1[index]" type="text"/>天</p>-->
<!--                                    </Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                        <p>全员：<input style="width: 50px" v-model="xiugaiformValidate.test2[index]" type="text"/>天</p>-->
<!--                                        <p style="display: none;">维护人：<input style="width: 50px" v-model="xiugaiformValidate.test2_2[index]"  type="text"/>天</p>-->
<!--                                    </Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                        <p style="display: none;">全员：<input style="width: 50px"  v-model="xiugaiformValidate.test3[index]" type="text"/>天</p>-->
<!--                                        <p>维护人：<input style="width: 50px" v-model="xiugaiformValidate.test3_3[index]" type="text"/>天</p>-->
<!--                                    </Col>-->
<!--                                    <Col :lg="4" :md="4">-->
<!--                                        <p>全员：<input style="width: 50px" v-model="xiugaiformValidate.test4[index]" type="text"/>天</p>-->
<!--                                        <p style="display: none;">维护人：<input style="width: 50px"  v-model="xiugaiformValidate.test4_4[index]" type="text"/>天</p>-->
<!--                                    </Col>-->
<!--                                </Row>-->
<!--                            </div>-->
<!--                        </Form>-->
<!--                    </TabPane>-->
                </Tabs>
            </Card>
        </Modal>
        </Col>
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>

            <Row>
                <Col :lg="24" :md="24">
                <Tabs :animated="false" v-model="Tabs" @on-click="clickTab">
                    <TabPane label="买卖客源" icon="social-skype">
                        <Table :columns="Columns" :data="Data" border stripe></Table>
                    </TabPane>
<!--                    <TabPane label="租赁客源" icon="ios-paw">-->
<!--                        <Table :columns="Columns" :data="Data" border stripe></Table>-->
<!--                    </TabPane>-->
<!--                    <TabPane label="高端客源" icon="ios-paw">-->
<!--                        <Table :columns="Columns" :data="Data" border stripe></Table>-->
<!--                    </TabPane>-->
                    <!--<TabPane label="新房" icon="ios-paw">-->
                    <!--<Table :columns="Columns" :data="Data" border stripe></Table>-->
                    <!--</TabPane>-->

                </Tabs>
                </Col>
                <Col :lg="24" :md="24">
                <div class="taps">
                    Taps:私客 超过回访期 跳公客,请及时查看客源状态！
                </div>
                </Col>
            </Row>
        </Card>

        </Col>

    </Row>
</template>

<script>
    export default {
        name: 'keyuandengji',
        data() {
            return {
                xiugaiformValidate: {
                    test1: [],
                    test1_1: [],
                    test2: [],
                    test2_2: [],
                    test3: [],
                    test3_3: [],
                    test4: [],
                    test4_4: [],
                },
                xiugairuleValidate:{},
                Tabs: '',
                setTabs: '',
                dengAdd: false,
                dengData: [],
//              出售表格
                Columns: [
                    /* {
                        title: '客源等级',
                        key: 'keyuandengji',
                        align: 'center',
                    }, */
                    {
                        title: '类型',
                        key: 'keyuanleixing',
                        align: 'center',
                    },
                    {
                        title: '私客跟进周期',
                        key: 'sipanhuike',
                        align: 'center',
                    },
                    {
                        title: '公客跟进周期',
                        key: 'gongpanhuike',
                        align: 'center',
                    },
                    {
                        title: '私客带看周期',
                        key: 'sipandaikan',
                        align: 'center',
                    },
                    {
                        title: '公客带看周期',
                        key: 'gongpandaikan',
                        align: 'center',
                    }
                ],
                Data: [],
                dengjitiaojianData:[]
            }
        },
        created: function () {
            this.Tabs = 0;
            this.getIndex();
            // this.getdengjitiaojianIndex();

        },
        methods: {
            getdengjitiaojianIndex(){
                this.$http.get(api_param.apiurl + 'class_if/index',
                    { params:{ci_type: this.Tabs},
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response){
                    // 这里是处理正确的回调
                    if(response.data.code==200){
                        this.dengjitiaojianData=response.body.data.list;
                        console.log(this.dengjitiaojianData);
                    }else if(response.data.code==401){
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    }else{
                        this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                    this.$Message.warning('更新失败');
                });
            },

            clickTab() {
                this.getIndex();
                // this.getdengjitiaojianIndex();
            },
            clickSetTab() {
                this.getSetIndex();
            },
            //    列表
            getIndex() {
                this.$http.get(api_param.apiurl + '/class_customer/index',
                    {
                        params: {cc_type: this.Tabs},
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200){     
						this.Data=[];
                        this.Data.push(response.body.data.list[0]);						
                        for (let i = 0; i < this.Data.length; i++) {
                            this.Data[i]['keyuandengji']=this.Data[i].cc_name;
                            if (this.Data[i].cc_type == 0) {
                                this.Data[i]['keyuanleixing'] = '买卖客源'
                            } else if (this.Data[i].cc_type == 1) {
                                // this.Data[i]['keyuanleixing'] = '租赁客源'
                                this.Data[i]['keyuanleixing'] = '买卖客源'
                            } else {
                                // this.Data[i]['keyuanleixing'] = '高端客源'
                                this.Data[i]['keyuanleixing'] = '买卖客源'
                            }
                            //
                            this.Data[i]['sipanhuike'] = "维护人：" + this.Data[i].cc_private_creturn + "天";
                            this.Data[i]['gongpanhuike'] = "全员：" + this.Data[i].cc_public_return + "天";
                            this.Data[i]['sipandaikan'] = "维护人：" + this.Data[i].cc_private_clook + "天";
                            this.Data[i]['gongpandaikan'] = "全员：" + this.Data[i].cc_public_look + "天";
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
                    console.log(response);
                    this.$Message.warning('更新失败');
                });
            },
            getSetIndex() {
                this.$http.get(api_param.apiurl + '/class_customer/index',
                    {
                        params: {cc_type: this.setTabs},
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        //设置按钮
                        this.dengData = response.body.data.list;
                        for (let i = 0; i < this.dengData.length; i++) {
                            this.dengData[i]['keyuandengji'] = this.dengData[i].cc_name;
                            if (this.dengData[i].cc_type == 0) {
                                this.dengData[i]['keyuanleixing'] = '买卖客源'
                            } else if (this.dengData[i].cc_type == 1) {
                                // this.dengData[i]['keyuanleixing'] = '租赁客源'
                                this.dengData[i]['keyuanleixing'] = '买卖客源'
                            } else {
                                // this.dengData[i]['keyuanleixing'] = '高端客源'
                                this.dengData[i]['keyuanleixing'] = '买卖客源'
                            }
                            this.xiugaiformValidate.test1[i] = this.dengData[i].cc_private_return;
                            this.xiugaiformValidate.test1_1[i] = this.dengData[i].cc_private_creturn;
                            this.xiugaiformValidate.test2[i] = this.dengData[i].cc_public_return;
                            this.xiugaiformValidate.test2_2[i] = this.dengData[i].cc_public_creturn;
                            this.xiugaiformValidate.test3[i] = this.dengData[i].cc_private_look;
                            this.xiugaiformValidate.test3_3[i] = this.dengData[i].cc_private_clook;
                            this.xiugaiformValidate.test4[i] = this.dengData[i].cc_public_look;
                            this.xiugaiformValidate.test4_4[i] = this.dengData[i].cc_public_clook;
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
                    console.log(response);
                    this.$Message.warning('更新失败');
                });
            },
            //    设置
            dengAddBtn() {
                this.setTabs = 0;
                this.getSetIndex();
                this.dengAdd = true;
            },
            setOk() {
                // this.$refs['xiugaiformValidate'].validate((valid) => {
                //         if (valid) {
                let ccId = [];
                for (let i = 0; i < this.dengData.length; i++) {
                    ccId.push(this.dengData[i].cc_id)
                }
                this.$http.post(api_param.apiurl + '/class_customer/edit',
                    {
                        cc_private_return: this.xiugaiformValidate.test1,
                        cc_private_creturn: this.xiugaiformValidate.test1_1,
                        cc_public_return: this.xiugaiformValidate.test2,
                        cc_public_creturn: this.xiugaiformValidate.test2_2,
                        cc_private_look: this.xiugaiformValidate.test3,
                        cc_private_clook: this.xiugaiformValidate.test3_3,
                        cc_public_look: this.xiugaiformValidate.test4,
                        cc_public_clook: this.xiugaiformValidate.test4_4,
                        cc_id: ccId
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    console.log(response);
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.$refs['xiugaiformValidate'].resetFields();
                        this.dengAdd = false;
                        this.$Message.success(response.data.message);
                        this.getIndex();
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        this.$Message.warning(response.data.message)
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    this.$Message.warning('更新失败');
                });
                //         }
                // //     });
            },
            setCancel() {
                this.$refs['xiugaiformValidate'].resetFields();
                this.dengAdd = false;
            },

        },
        computed: {}
    }
</script>
