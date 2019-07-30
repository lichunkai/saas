<style lang="less">
    .divP input {
        width: 28px;
        text-indent: 2px;
    }

    .divP {
        width: 100%;
        margin: 2px 0;
        overflow: hidden;
    }

    .taps {
        margin: 10px 0;
        color: #666;
        overflow: hidden;
    }

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
    <Row class="fangyuancounter">
        <Col :lg="24" :md="24">
        <Card>
            <Row type="flex" justify="end">
                <Col :lg="1" :md="1">
                <Button type="primary" @click="dengAddBtn">设置</Button>
                <Modal v-model="dengAdd" title="房源等级设置" width="1080" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="setCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">房源等级设置</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="setCancel">取消</Button>
                        <Button type="primary" size="large" @click="setOk">确定</Button>
                    </div>
                    <Card>
                        <Tabs :animated="false" v-model="setTabs" @on-click="clickSetTab">
                            <TabPane label="二手房出售" icon="social-skype">
                                <Form ref="xiugaiformValidate" :model="xiugaiformValidate" :rules="xiugairuleValidate"
                                      :label-width="90">
                                    <div class="tableFang">
                                        <Row type="flex" justify="space-between" class="borderFang">
                                            <Col :lg="3" :md="3">
                                            名称</Col>
                                            <Col :lg="3" :md="3">
                                                共享盘跟进周期</Col>
                                            <Col :lg="3" :md="3">
                                                共享盘带看周期</Col>
                                            <Col :lg="3" :md="3">
                                                店公盘跟进周期</Col>
                                            <Col :lg="3" :md="3">
                                                店公盘带看周期</Col>
                                            <Col :lg="4" :md="4">
                                                公司公盘跟进周期</Col>
                                            <Col :lg="4" :md="4">
                                                公司公盘带看周期</Col>
                                        </Row>
                                        <Row v-for="(item,index) in this.dengData" type="flex" justify="space-between"
                                             class="borderFang">
                                            <Col :lg="3" :md="3">
                                            {{item.keyuandengji}}</Col>
                                            <Col :lg="3" :md="3">
                                            <p><input style="width: 50px" v-model="xiugaiformValidate.ch_private_genjin[index]" type="text"/>天</p>
                                            </Col>
                                            <Col :lg="3" :md="3">
                                            <p><input style="width: 50px" v-model="xiugaiformValidate.ch_private_visit[index]" type="text"/>天</p>
                                            </Col>
                                            <Col :lg="3" :md="3">
                                            <p><input style="width: 50px" v-model="xiugaiformValidate.ch_store_genjin[index]" type="text"/>天</p>
                                            </Col>
                                            <Col :lg="3" :md="3">
                                            <p><input style="width: 50px" v-model="xiugaiformValidate.ch_store_visit[index]" type="text"/>天</p>
                                            </Col>
                                            <Col :lg="4" :md="4">
                                                <p><input style="width: 50px" v-model="xiugaiformValidate.ch_company_genjin[index]" type="text"/>天</p>
                                            </Col>
                                            <Col :lg="4" :md="4">
                                                <p><input style="width: 50px" v-model="xiugaiformValidate.ch_company_visit[index]" type="text"/>天</p>
                                            </Col>
                                        </Row>
                                    </div>
                                </Form>
                            </TabPane>
<!--                            <TabPane label="二手房出租" icon="ios-paw">-->
<!--                                <Form ref="xiugaiformValidate" :model="xiugaiformValidate" :rules="xiugairuleValidate"-->
<!--                                      :label-width="90">-->
<!--                                    <div class="tableFang">-->
<!--                                        <Row type="flex" justify="space-between" class="borderFang">-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            房源等级</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            类型</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            私客回访周期</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            公客回访周期</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            私客带看周期</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            公客带看周期</Col>-->
<!--                                        </Row>-->
<!--                                        <Row v-for="(item,index) in this.dengData" type="flex" justify="space-between"-->
<!--                                             class="borderFang">-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            {{item.keyuandengji}}</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            {{item.keyuanleixing}}</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            <p style="display: none;">>全员：<input style="width: 50px"-->
<!--                                                                                 v-model="xiugaiformValidate.test1[index]"-->
<!--                                                                                 type="text"/>天</p>-->
<!--                                            <p>维护人：<input style="width: 50px" v-model="xiugaiformValidate.test1_1[index]"-->
<!--                                                          type="text"/>天</p>-->
<!--                                            </Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            <p>全员：<input style="width: 50px" v-model="xiugaiformValidate.test2[index]"-->
<!--                                                         type="text"/>天</p>-->
<!--                                            <p style="display: none;">>护人：<input style="width: 50px;display:none;"-->
<!--                                                                                 v-model="xiugaiformValidate.test2_2[index]"-->
<!--                                                                                 type="text"/>天</p>-->
<!--                                            </Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            <p style="display: none;">>全员：<input style="width: 50px;display:none;"-->
<!--                                                                                 v-model="xiugaiformValidate.test3[index]"-->
<!--                                                                                 type="text"/>天</p>-->
<!--                                            <p>维护人：<input style="width: 50px" v-model="xiugaiformValidate.test3_3[index]"-->
<!--                                                          type="text"/>天</p>-->
<!--                                            </Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            <p>全员：<input style="width: 50px" v-model="xiugaiformValidate.test4[index]"-->
<!--                                                         type="text"/>天</p>-->
<!--                                            <p style="display: none;">>维护人：<input style="width: 50px;display:none;"-->
<!--                                                                                  v-model="xiugaiformValidate.test4_4[index]"-->
<!--                                                                                  type="text"/>天</p>-->
<!--                                            </Col>-->
<!--                                        </Row>-->
<!--                                    </div>-->
<!--                                </Form>-->
<!--                            </TabPane>-->
<!--                            <TabPane label="二手房高端" icon="ios-paw">-->
<!--                                <Form ref="xiugaiformValidate" :model="xiugaiformValidate" :rules="xiugairuleValidate"-->
<!--                                      :label-width="90">-->
<!--                                    <div class="tableFang">-->
<!--                                        <Row type="flex" justify="space-between" class="borderFang">-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            房源等级</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            类型</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            私客回访周期</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            公客回访周期</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            私客带看周期</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            公客带看周期</Col>-->
<!--                                        </Row>-->
<!--                                        <Row v-for="(item,index) in this.dengData" type="flex" justify="space-between"-->
<!--                                             class="borderFang">-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            {{item.keyuandengji}}</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            {{item.keyuanleixing}}</Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            <p style="display: none;">>全员：<input style="width: 50px;display:none;"-->
<!--                                                                                 v-model="xiugaiformValidate.test1[index]"-->
<!--                                                                                 type="text"/>天</p>-->
<!--                                            <p>维护人：<input style="width: 50px" v-model="xiugaiformValidate.test1_1[index]"-->
<!--                                                          type="text"/>天</p>-->
<!--                                            </Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            <p>全员：<input style="width: 50px" v-model="xiugaiformValidate.test2[index]"-->
<!--                                                         type="text"/>天</p>-->
<!--                                            <p style="display: none;">>维护人：<input style="width: 50px;display:none;"-->
<!--                                                                                  v-model="xiugaiformValidate.test2_2[index]"-->
<!--                                                                                  type="text"/>天</p>-->
<!--                                            </Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            <p style="display: none;">>全员：<input style="width: 50px;display:none;"-->
<!--                                                                                 v-model="xiugaiformValidate.test3[index]"-->
<!--                                                                                 type="text"/>天</p>-->
<!--                                            <p>维护人：<input style="width: 50px" v-model="xiugaiformValidate.test3_3[index]"-->
<!--                                                          type="text"/>天</p>-->
<!--                                            </Col>-->
<!--                                            <Col :lg="4" :md="4">-->
<!--                                            <p>全员：<input style="width: 50px" v-model="xiugaiformValidate.test4[index]"-->
<!--                                                         type="text"/>天</p>-->
<!--                                            <p style="display: none;">>维护人：<input style="width: 50px;display:none;"-->
<!--                                                                                  v-model="xiugaiformValidate.test4_4[index]"-->
<!--                                                                                  type="text"/>天</p>-->
<!--                                            </Col>-->

<!--                                        </Row>-->
<!--                                    </div>-->
<!--                                </Form>-->
<!--                            </TabPane>-->
                        </Tabs>
                    </Card>
                </Modal>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Tabs :animated="false" v-model="Tabs" @on-click="clickTab">
                <TabPane label="二手房出售" icon="social-skype">
                    <Table :columns="shouColumns" :data="Data" border stripe></Table>
                </TabPane>
<!--                <TabPane label="二手房出租" icon="ios-paw">-->
<!--                    <Table :columns="shouColumns" :data="Data" border stripe></Table>-->
<!--                </TabPane>-->
<!--                <TabPane label="二手房高端" icon="ios-paw">-->
<!--                    <Table :columns="shouColumns" :data="Data" border stripe></Table>-->
<!--                </TabPane>-->
            </Tabs>

            <div class="taps">
                Taps:私房 超过回访期 跳公房,请及时查看房源状态！
            </div>
        </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'fangyuandengji',
        data() {
            return {
                xiugaiformValidate: {
                    ch_private_genjin: [],
                    ch_private_visit: [],
                    ch_store_genjin: [],
                    ch_store_visit: [],
                    ch_company_genjin: [],
                    ch_company_visit: []
                },
                setTabs: '',
                Tabs: '',
                dengAdd: false,
                dengData: [],
//              出售表格
                shouColumns: [
                    {
                        title: '名称',
                        key: 'fangyuandengji',
                        align: 'center',
                    },
                    {
                        title: '共享盘跟进周期',
                        key: 'ch_private_genjin',
                        align: 'center',
                    },
                    {
                        title: '共享盘带看周期',
                        key: 'ch_private_visit',
                        align: 'center',
                    },
                    {
                        title: '店公盘跟进周期',
                        key: 'ch_store_genjin',
                        align: 'center',
                    },
                    {
                        title: '店公盘带看周期',
                        key: 'ch_store_visit',
                        align: 'center',
                    },
                    {
                        title: '公司公盘跟进周期',
                        key: 'ch_company_genjin',
                        align: 'center',
                    },
                    {
                        title: '公司公盘带看周期',
                        key: 'ch_company_visit',
                        align: 'center',
                    }
                ],
                Data: [],
            }
        },
        created: function () {
            this.getIndex();
            this.Tabs = 0;
        },
        methods: {
            clickTab() {
                this.getIndex();
            },
            clickSetTab() {
                this.getSetIndex();
                // sessionStorage.setItem("inputValue", JSON.stringify(this.xiugaiformValidate));
                // console.log(JSON.parse(sessionStorage.getItem("inputValue")));
                // this.xiugaiformValidate=JSON.parse(sessionStorage.getItem("inputValue"))
            },
            //    列表
            getIndex() {
                this.$http.get(api_param.apiurl + '/class_house/index',
                    {
                        params: {ch_type: this.Tabs},
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        console.log(response);
                        this.Data = response.body.data.list;
                        for (let i = 0; i < this.Data.length; i++) {
                            this.Data[i]['fangyuandengji'] = this.Data[i].ch_name;
                            if (this.Data[i].ch_type == 0) {
                                this.Data[i]['fangyuanleixing'] = '二手房出售'
                            } else if (this.Data[i].ch_type == 1) {
                                // this.Data[i]['fangyuanleixing'] = '二手房出租'
                                this.Data[i]['fangyuanleixing'] = '二手房出售'
                            } else {
                                // this.Data[i]['fangyuanleixing'] = '二手房高端'
                                this.Data[i]['fangyuanleixing'] = '二手房出售'
                            }
                            //私客全员、公客维护人在这里加！！！！
                            this.Data[i]['ch_private_genjin'] = this.Data[i].ch_private_genjin + "天";
                            this.Data[i]['ch_private_visit'] = this.Data[i].ch_private_visit + "天";
                            this.Data[i]['ch_store_genjin'] = this.Data[i].ch_store_genjin + "天";
                            this.Data[i]['ch_store_visit'] = this.Data[i].ch_store_visit + "天";
                            this.Data[i]['ch_company_genjin'] = this.Data[i].ch_company_genjin + "天";
                            this.Data[i]['ch_company_visit'] = this.Data[i].ch_company_visit + "天";

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
                this.$http.get(api_param.apiurl + '/class_house/index',
                    {
                        params: {ch_type: this.setTabs},
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        //设置按钮
                        this.dengData = response.body.data.list;
                        for (let i = 0; i < this.dengData.length; i++) {
                            this.dengData[i]['keyuandengji'] = this.dengData[i].ch_name;
                            if (this.dengData[i].ch_type == 0) {
                                this.dengData[i]['keyuanleixing'] = '二手房出售'
                            } else if (this.dengData[i].ch_type == 1) {
                                // this.dengData[i]['keyuanleixing'] = '二手房出租'
                                this.dengData[i]['keyuanleixing'] = '二手房出售'
                            } else {
                                // this.dengData[i]['keyuanleixing'] = '二手房高端'
                                this.dengData[i]['keyuanleixing'] = '二手房出售'
                            }
                            this.xiugaiformValidate.ch_private_genjin[i] = this.dengData[i].ch_private_genjin;
                            this.xiugaiformValidate.ch_private_visit[i] = this.dengData[i].ch_private_visit;
                            this.xiugaiformValidate.ch_store_genjin[i] = this.dengData[i].ch_store_genjin;
                            this.xiugaiformValidate.ch_store_visit[i] = this.dengData[i].ch_store_visit;
                            this.xiugaiformValidate.ch_company_genjin[i] = this.dengData[i].ch_company_genjin;
                            this.xiugaiformValidate.ch_company_visit[i] = this.dengData[i].ch_company_visit;
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
                let chId = [];
                for (let i = 0; i < this.dengData.length; i++) {
                    chId.push(this.dengData[i].ch_id)
                }
                this.$http.post(api_param.apiurl + '/class_house/edit',
                    {
                        ch_private_genjin: this.xiugaiformValidate.ch_private_genjin,
                        ch_private_visit: this.xiugaiformValidate.ch_private_visit,
                        ch_store_genjin: this.xiugaiformValidate.ch_store_genjin,
                        ch_store_visit: this.xiugaiformValidate.ch_store_visit,
                        ch_company_genjin: this.xiugaiformValidate.ch_company_genjin,
                        ch_company_visit: this.xiugaiformValidate.ch_company_visit,
                        ch_id: chId
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
