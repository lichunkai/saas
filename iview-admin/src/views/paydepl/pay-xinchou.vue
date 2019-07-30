<template>
    <Row>
        <!--按钮-->
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="5">
                <!--部门员工-->
                <Col :lg="3" :md="3">
                <Cascader :data="this.peizhi.departlist" trigger="click" filterable change-on-select
                          v-model="sousuo.bumen" @on-change="changeDepart1" placeholder="部门选择"></Cascader>
                </Col>
                <Col :lg="2" :md="2">
                <Select v-model="sousuo.user" placeholder="" :transfer="true" placeholder="员工选择"
                        @on-change="changeuser">
                    <Option v-for="v in sousuo.users" :value="v.u_id" :key="v.u_id">{{v.u_name}}
                    </Option>
                </Select>
                </Col>
                <!--变动类型-->
                <Col :lg="2" :md="2">
                <Select v-model="biandongModel" placeholder="变动类型" :transfer="true">
                    <Option v-for="item in JSON.parse( this.peizhi.biandongleixing[0].base_desp)"
                            :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                    </Option>
                </Select>
                </Col>
                <!--日期-->
                <Col :lg="3" :md="3">
                <DatePicker v-model="shijianq" type="daterange" placeholder="起始时间"
                            @on-change="changeshijian"></DatePicker>
                </Col>

                <!--查询-->
                <Col :lg="4" :md="4">
                <Button type="primary" @click="searchCustomerList">查询</Button>
                <Button type="primary" @click="qkym">清空</Button>
                </Col>

                <Col :lg="10" :md="10">
                <Row type="flex" justify="end">
                    <Col>
                    <Button type="primary" @click="xz">新增</Button>
                    </Col>
                </Row>
                </Col>
            </Row>
        </Card>
        </Col>
        <!--表格-->
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Table border :columns="columns" :data="data"></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="totalnum" :current="currentpage" @on-change="changePage" :page-size="pageSize"
                          show-total>
                    </Page>
                </div>
            </div>
        </Card>
        </Col>
        <!--MODAL-->
        <!--薪酬变动-->
        <Modal v-model="xincoubiandong" title="薪酬变动">
            <div slot="header">
                <a class="ivu-modal-close" @click="modalCancel" style="display: block!important;"><i
                        class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                <div class="ivu-modal-header-inner">薪酬变动</div>
            </div>
            <div slot="footer">
                <Button type="text" size="large" @click="modalCancel">取消</Button>
                <Button type="primary" size="large" @click="modalOk">确定</Button>
            </div>
            <Form ref="formValidate" :model="formValidate" :label-width="80">
                <Row>
                    <Col :lg="12" :md="12">
                    <FormItem label="选择部门" prop="departlist" v-if="bianji==0">
                        <Cascader :data="this.peizhi.departlist" trigger="click" filterable change-on-select
                                  v-model="formValidate.bumen" @on-change="changeDepart" :transfer="true"></Cascader>
                    </FormItem>
                    <FormItem label="所属部门" prop="departlist" v-if="bianji==1">
                        {{formValidate.bumen}}
                    </FormItem>
                    </Col>
                    <Col :lg="12" :md="12">
                    <FormItem label="员工选择" prop="user" v-if="bianji==0">
                        <Select v-model="formValidate.user" placeholder="" :transfer="true">
                            <Option v-for="v in formValidate.users" :value="v.u_id" :key="v.u_id">{{v.u_name}}
                            </Option>
                        </Select>
                    </FormItem>
                    <FormItem label="员工选择" prop="user" v-if="bianji==1">
                        {{formValidate.renyuan}}
                    </FormItem>
                    </Col>
                </Row>
                <Row>
                    <Col :lg="12" :md="12">
                    <FormItem label="变动类型" prop="biandongleixing">
                        <Select v-model="formValidate.biandongleixing" placeholder="" :transfer="true">
                            <Option v-for="item in JSON.parse( this.peizhi.biandongleixing[0].base_desp)"
                                    :value="item.child_name" :key="item.child_name">{{ item.child_name}}
                            </Option>
                        </Select>
                    </FormItem>
                    </Col>
                    <Col :lg="12" :md="12">
                    <FormItem label="奖罚类型" prop="zhengjianleixing">
                        <Select v-model="formValidate.zhengjianleixing" placeholder="" :transfer="true">
                            <Option value="奖">奖</Option>
                            <Option value="罚">罚</Option>
                        </Select>
                    </FormItem>
                    </Col>
                </Row>
                <Row>
                    <Col :lg="12" :md="12">
                    <FormItem label="金额" prop="jine">
                        <Input v-model="formValidate.jine">
                        <span slot="append">元</span>
                        </Input>
                    </FormItem>
                    </Col>
                    <Col :lg="12" :md="12">
                    <FormItem label="日期" prop="biandongriqi_x">
                        <DatePicker type="date" @on-change="setPublishTime" v-model="formValidate.biandongriqi_x"
                                    format="yyyy-MM-dd" :transfer="true"></DatePicker>
                    </FormItem>
                    </Col>
                </Row>
                <Row>
                    <Col :lg="24" :md="24">
                    <FormItem label="备注" prop="textarea">
                        <Input v-model="formValidate.beizhu" type="textarea" placeholder=""></Input>
                    </FormItem>
                    </Col>
                </Row>
            </Form>
        </Modal>
    </Row>
</template>

<script>
    export default {
        name: "payXinchou",
        data() {
            return {
                xincoubiandong: false,//薪酬变动
                bumenValue: [],
                bumenData: [],
                biandongModel: '',
                bianji: 0,
                totalnum: 0,
                pageSize: 10,
                peizhi: [],
                shijianq: [],
                shijian: [],
                currentpage: 1,
                sousuo: {
                    bumen: [],
                    users: [],
                    user: '',
                    d_id: '',
                },
                formValidate: {
                    bumen: '',
                    user: '',
                    scb_id: '',
                    users: '',
                    biandongriqi: '',
                    zhengjianleixing: '',
                    jine: '',
                    beizhu: '',
                    biandongriqi: '',
                    biandongriqi_x: [],
                },
                biandongtype: [
                    {
                        value: '全勤奖',
                        label: '全勤奖'
                    }
                ],
                columns: [
                    {
                        title: '日期',
                        key: 'biandongriqi',
                        align: 'center'
                    },
                    {
                        title: '部门',
                        key: 'bumen',
                        align: 'center'
                    },
                    {
                        title: '人员',
                        key: 'renyuan',
                        align: 'center'
                    },
                    {
                        title: '变动类型',
                        key: 'biandongleixing',
                        align: 'center'
                    },
                    {
                        title: '增减类型',
                        key: 'zhengjianleixing',
                        align: 'center'
                    },
                    {
                        title: '金额',
                        key: 'jine',
                        align: 'center'
                    },
                    {
                        title: '备注',
                        key: 'beizhu',
                        align: 'center'
                    },

                    {
                        title: '操作',
                        key: 'caozuo',
                        align: 'center',
                        render: (h, params) => {
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
                                            this.xincoubiandong = true;
                                            this.bianji = 1;
                                            this.formValidate.biandongleixing = params.row.biandongleixing;
                                            this.formValidate.zhengjianleixing = params.row.zhengjianleixing;
                                            this.formValidate.bumen = params.row.bumen;
                                            this.formValidate.jine = params.row.jine;
                                            this.formValidate.renyuan = params.row.renyuan;
                                            this.formValidate.biandongriqi_x = [params.row.biandongriqi];
                                            this.formValidate.beizhu = params.row.beizhu;
                                            this.formValidate.scb_id = params.row.scb_id;
                                        }
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.removeChild1(params.row.scb_id);
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                data: [],


            }
        }, created: function () {
            this.getIndex();
        }, methods: {
            changeshijian(value, label) {
                this.shijian = value;
            },
            xz() {
                this.xincoubiandong = true;
                this.bianji = 0;
            },
            searchCustomerList() {
                this.getIndex();
            }, qkym() {
                this.sousuo.d_id = '';
                this.sousuo.bumen = [];
                this.sousuo.users = [];
                this.sousuo.user = '';
                this.shijian = [];
                this.shijianq = [];
                this.biandongModel = '';
                this.getIndex();
            },
            changeDepart1(value, selectedData) {
                this.sousuo.bumen = selectedData;
                this.sousuo.d_id = value.pop();
                this.sousuo.users = this.peizhi.users[this.sousuo.d_id];
            },
            changePage(page) {
                this.currentpage = page;
                this.getIndex();
            },
            removeChild1(scb_id) {
                this.$Modal.confirm({
                    title: '删除业绩方案',
                    content: '确定要删除吗',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + '/salary_config_biandong/del',
                            {
                                scb_id: scb_id,
                            },
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.getIndex();
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
                            this.$Message.warning('删除失败');
                        });
                    }
                });
            },
            modalOk() {
                var data = {
                    biandongriqi: this.formValidate.biandongriqi,
                    renyuan: this.formValidate.user,
                    bumen_id: this.formValidate.bumen,
                    scb_id: this.formValidate.scb_id,
                    jine: this.formValidate.jine,
                    beizhu: this.formValidate.beizhu,
                    biandongleixing: this.formValidate.biandongleixing,
                    zhengjianleixing: this.formValidate.zhengjianleixing,

                };
                if (this.formValidate.scb_id) {
                    var url = api_param.apiurl + '/salary_config_biandong/edit';
                } else {
                    var url = api_param.apiurl + '/salary_config_biandong/add';
                }

                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(url,
                            data,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            this.orgmodal = false;
                            this.$Message.success(response.data.message);
                            this.getIndex();
                            this.modalCancel();
                        }, function (response) {
                            // 这里是处理错误的回调
                            //console.log(response)
                            this.modalCancel();
                            this.$Message.warning('更新失败');
                        });
                    }
                });
            },
            changeDepart(value, selectedData) {
                this.formValidate.bumen = selectedData;
                var d_id = value.pop();
                this.formValidate.users = this.peizhi.users[d_id];
            }, setPublishTime(date) {
                this.formValidate.biandongriqi = date;
                this.formValidate.biandongriqi_x = [date];
            },
            modalCancel() {
                this.xincoubiandong = false;
                this.$refs['formValidate'].resetFields();//清空form规则检查
                this.formValidate.biandongriqi_x = [];
                this.formValidate.bumen = '';
            },
            getIndex() {
                this.$http.get(api_param.apiurl + '/salary_config_biandong/index',
                    {
                        params: {
                            page: this.currentpage,
                            pagesize: 10,
                            d_id: this.sousuo.d_id,
                            user: this.sousuo.user,
                            shijian: this.shijian,
                            biandongModel: this.biandongModel,
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    console.log(response);
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum = response.body.data.count;
                        this.data = response.body.data.list;
                        this.peizhi = response.body.data.peizhi;
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
        }
    }
</script>

<style scoped>

</style>
