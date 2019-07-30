<style lang="less">
    .marTop10px {
        margin-top: 10px;
    }

    .marTop10px .ivu-table-cell {
        padding: 0 !important;
    }
</style>
<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="10">
                <Col :lg="2" :md="2">
                <Select placeholder="客源类型" @on-change="getIndex" v-model="customer_type">
                    <Option value="quanbu">全部</Option>
                    <Option value="0">求购</Option>
                    <Option value="1">求租</Option>
                    <Option value="2">高端</Option>
                </Select>
                </Col>
                <Col :lg="2" :md="2">
                <Select placeholder="意向金状态" @on-change="getIndex" v-model="x_zhuangtai">
                    <Option value="0">待确认</Option>
                    <Option value="2">已确认</Option>
                    <Option value="3">已退定</Option>
                    <Option value="1">已支出</Option>
                    <Option value="4">已驳回</Option>
                    <!--<Option value="yizhuanyong">已转佣</Option>-->
                </Select>
                </Col>
                <Col :lg="2" :md="2">
                <Cascader :data="this.peizhi.benzu" trigger="click" filterable change-on-select
                          v-model="sousuo.bumen" @on-change="changeDepart" placeholder="部门选择"></Cascader>
                </Col>
                <Col :lg="2" :md="2">
                <Select v-model="sousuo.user" placeholder="" :transfer="true" placeholder="经办人选择"
                        @on-change="changeuser">
                    <Option v-for="v in sousuo.users" :value="v.u_id" :key="v.u_id">{{v.u_name}}
                    </Option>
                </Select>
                </Col>
                <Col :lg="2" :md="2">
                <DatePicker v-model="shijian" type="daterange" placeholder="起始时间"
                            @on-change="changeshijian"></DatePicker>
                </Col>

                <Col :lg="3" :md="3">
                <Input placeholder="编号、房号" v-model="sousuo.bianhao"></Input>
                </Col>
                <Col :lg="3" :md="3">
                <Button type="primary" @click="getIndex">查询</Button>
                <Button type="primary"  @click="qkym">清空</Button>
                </Col>
                <Col :lg="3" :md="3" offset="5">
                <Row type="flex" justify="end">
                    <!--<Col>-->
                    <!--<Button type="primary">导出当前页</Button>-->
                    <!--</Col>-->
                </Row>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" class="marTop10px">
        <Card>
            <Table border :columns="chColumns" :data="xiadingData"
                   style="max-height: 520px"></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="pageTotal" :current="pageCurrent" @on-change="changePage" :page-size="pageSize"
                          show-total>
                    </Page>
                </div>
            </div>
        </Card>
        </Col>
        <Modal v-model="zhichu" title="意向金支出" :closable="false" :mask-closable="false"
               @on-ok="handleSubmit('formItem')" @on-cancel="handleReset('formItem')">
            <div slot="header">
                <a class="ivu-modal-close" @click="FengpanModalCancel" style="display: block!important;"><i
                        class="ivu-icon ivu-icon-ios-close-empty" ></i></a>
                <div class="ivu-modal-header-inner">意向金支出</div>
            </div>
            <div slot="footer">
                <Button type="text" size="large" @click="FengpanModalCancel">取消</Button>
                <Button type="primary" size="large" @click="FengpanModalOk">确定</Button>
            </div>
            <Form :model="formItem" ref="formItem" :label-width="80">
                <FormItem label="支出协议书" prop="x_xys" :rules="{required: true, message: '支出协议书不能为空', trigger: 'blur'}">
                    <Input v-model="formItem.x_xys" placeholder="支出协议书编号"></Input>
                </FormItem>
                <FormItem label="备注">
                    <Input v-model="formItem.x_zcbz" type="textarea" :autosize="{minRows: 2,maxRows: 5}"></Input>
                </FormItem>
            </Form>
        </Modal>
    </Row>
</template>

<script>
    export default {
        name: 'customerMoney',
        data () {
            return {
                zhichu: false,
                customer_type: '',
                x_zhuangtai: '',
                shijian: [],
                sousuo: {
                    bumen: [],
                    users: [],
                    user: '',
                    d_id: '',
                    bianhao:'',
                },
                peizhi: [],
                formItem: {
                    x_id: '',
                    x_zhuangtai: '',
                    x_xys: '',
                    x_zcbz: '',
                },
                xiadingData: [],
                pageTotal: 0,
                pageSize: 10,
                pageCurrent: 1,
                chColumns: [

                    {
                        title: '客源类型',
                        key: 'customer_type_name',
                        fixed: 'left',
                        width: 88,
                        align: 'center'
                    },
                    {
                        title: '状态',
                        key: 'x_zhuangtai',
                        width: 88,
                        align: 'center'
                    },
                    {
                        title: '客户姓名',
                        key: 'customer_name',
                        width: 88,
                        align: 'center'
                    },
                    {
                        title: '客源编号',
                        key: 'xuqiubianhao',
                        align: 'center',
                        width: 128,
                        render: (h, params) => {
                            return h('div', [
                                h('a', {
                                    props: {
                                        type: 'warning',
                                        size: 'small'
                                    },
                                    style: {
                                        textDecoration: 'underline',
                                    },
                                    domProps: {
                                        innerHTML: params.row.xuqiubianhao
                                    },
                                    on: {
                                        click: () => {
                                            let argu = {customer_id: params.row.customer_uuid,customer_type:params.row.customer_type};
                                            this.$router.push({
                                                name: 'customerEtails',
                                                params: argu
                                            });
                                        }
                                    }
                                })]);
                        }
                    },
                    {
                        title: '房源编号',
                        key: 'house_sn',
                        align: 'center',
                        width: 128,
                        render: (h, params) => {
                            return h('div', [
                                h('a', {
                                    props: {
                                        type: 'warning',
                                        size: 'small'
                                    },
                                    style: {
                                        textDecoration: 'underline',
                                    },
                                    domProps: {
                                        innerHTML: params.row.house_sn
                                    },
                                    on: {
                                        click: () => {
                                            let argu = {
                                                houseId: params.row.house_uuid,
                                                saleType: 3
                                            };
                                            this.$router.push({
                                                name: 'roomDetails',
                                                params: argu
                                            });
                                        }
                                    }
                                })]);
                        }
                    },
                    {
                        title: '下定时间',
                        key: 'x_ctime',
                        align: 'center',
                        width:118
                    },
                    {
                        title: '经办人',
                        key: 'u_name',
                        width: 88,
                        align: 'center'
                    },
                    {
                        title: '区域',
                        key: 'quyu',
                        align: 'center',
                        width: 128,
                    },
                    {
                        title: '座栋',
                        key: 'zuodong',
                        align: 'center',
                        width: 88,
                    },
                    {
                        title: '房号',
                        key: 'fanghao_name',
                        align: 'center',
                        width: 88,
                    },
                    {
                        title: '意向金金额',
                        key: 'xiadingjine',
                        align: 'center',
                        width: 88,
                    },
                    {
                        title: '预计成交金额',
                        key: 'yujijine',
                        width: 88,
                        align: 'center'
                    },
                    {
                        title: '预计成交时间',
                        key: 'yujichengjiao',
                        width: 128,
                        align: 'center'
                    },
                    {
                        title: '票据编号',
                        key: 'piaojuhao',
                        width: 88,
                        align: 'center'
                    },
                    {
                        title: '协议书',
                        key: 'x_xys',
                        width: 88,
                        align: 'center'
                    },
                    {
                        title: '意向金编号',
                        key: 'chengjiaobianhao',
                        align: 'center',
                        width: 148,
                    },
                    {
                        title: '备注',
                        key: 'x_beizhu',
                        width: 128,
                        align: 'center',
                    },
                    {
                        title: '支出备注',
                        key: 'x_zcbz',
                        width: 88,
                        align: 'center',
                    },
                    {
                        title: '操作',
                        key: 'action',
                        fixed: 'right',
                        align: 'center',
                        width: 100,
                        render: (h, params) => {
                            let ret = [];
                            if (params.row.x_zhuangtai == '待确认') {
                                ret.push(h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.queren(params.row.x_id, 2);
                                        }
                                    }
                                }, '确认'));
                            }
                            if (params.row.x_zhuangtai == '确认') {
                                ret.push(h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.queren(params.row.x_id, 3);
                                        }
                                    }
                                }, '退定'));
                                ret.push(h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.queren(params.row.x_id, 1);
                                        }
                                    }
                                }, '支出'));
                            }
                            return h('div', ret);
                        }
                    }
                ],
                xiadingData: []
            };
        },
        created: function () {
            this.getIndex();
        }
        , methods: {
            changeshijian (value, label) {
                this.shijian = value;
                this.getIndex();
            },qkym(){
            this.sousuo.d_id='';
            this.sousuo.user='';
            this.sousuo.bianhao='';
            this.shijian=[];
            this.x_zhuangtai='';
            this.customer_type='';
            this.sousuo.bumen = [];
            this.getIndex();
             },
            changeuser (value, label) {
                this.getIndex();
            },
            changeDepart (value, selectedData) {
                this.sousuo.bumen = selectedData;
                this.sousuo.d_id = value.pop();
                this.sousuo.users = this.peizhi.users[this.sousuo.d_id];
                this.getIndex();
            },
            queren (x_id, x_zhuangtai) {
                if (x_zhuangtai != 1) {
                    var data = {x_id: x_id, x_zhuangtai: x_zhuangtai};

                    this.$http.post(api_param.apiurl + '/customer_xiading/edit',
                        data,
                        {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                    ).then(function (response) {
                        // 这里是处理正确的回调
                        this.getIndex();
                        //  this.modalCancel();
                    }, function (response) {
                        // 这里是处理错误的回调
                        //console.log(response)
                        // this.modalCancel();
                        this.$Message.warning('更新失败');
                    });
                } else {
                    this.zhichu = true;
                    this.formItem.x_id = x_id;
                    this.formItem.x_zhuangtai = x_zhuangtai;
                }

            },
            FengpanModalCancel () {//修改取消
                this.zhichu = false;
            },
            FengpanModalOk () {
                this.$refs['formItem'].validate((valid) => {
                    if (valid) {
                        var data = {
                            x_id: this.formItem.x_id,
                            x_zhuangtai: this.formItem.x_zhuangtai,
                            x_xys: this.formItem.x_xys,
                            x_zcbz: this.formItem.x_zcbz,
                        };
                        this.$http.post(api_param.apiurl + '/customer_xiading/edit',
                            data,
                            {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.getIndex();
                                this.zhichu = false;
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
            getIndex () {
                this.$http.post(api_param.apiurl + 'customer_xiading/getindex',
                    {
                        'page': this.pageCurrent,
                        'customer_type': this.customer_type,
                        'x_zhuangtai': this.x_zhuangtai,
                        'd_id': this.sousuo.d_id,
                        'user': this.sousuo.user,
                        'shijian': this.shijian,
                        'bianhao': this.sousuo.bianhao,
//			            'u_status': status,
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.xiadingData = response.data.data.list;

                        if (this.peizhi.length == 0) {
                            this.peizhi = response.body.data.peizhi;
                        }
                        this.pageTotal = parseInt(response.data.data.count);
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
            changePage (page) {
                this.pageCurrent = page;
                this.getIndex();
            },
        }
    };
</script>
