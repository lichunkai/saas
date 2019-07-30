<template>
    <Row>
        <!--按钮-->
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="5">
                <!--状态-->
                <Col :lg="2" :md="2">
                <Select v-model="searchdata.type" placeholder="记录类型" :transfer="true">
                    <Option value="1" label="代收款"></Option>
                    <Option value="2" label="代付款"></Option>
                    <Option value="3" label="意向金"></Option>
                </Select>
                </Col>
                <!--状态-->
                <Col :lg="2" :md="2">
                <Select v-model="searchdata.status" placeholder="收取状态" :transfer="true">
                    <Option value="1" label="已确认"></Option>
                    <Option value="0" label="待确认"></Option>
                    <Option value="2" label="已驳回"></Option>
                </Select>
                </Col>
                <!--日期类型-->
                <Col :lg="2" :md="2">
                <Select v-model="searchdata.datetype" placeholder="日期类型" :transfer="true">
                    <Option value="1" label="收取时间"></Option>
                    <Option value="2" label="操作时间"></Option>
                    <Option value="3" label="成交时间"></Option>
                </Select>
                </Col>
                <!--收取时间段-->
                <Col :lg="4" :md="4">
                <DatePicker type="daterange" :value="searchdata.daterange" @on-change="handleChange" format="yyyy/MM/dd"
                            placeholder="选择时间段" :transfer="true" style="width:100%"></DatePicker>
                </Col>
                <!--结算方式-->
                <Col :lg="2" :md="2">
                <Select v-model="searchdata.way" placeholder="结算方式" :transfer="true">
                    <Option :value="item.child_name" :label="item.child_name" v-for="(item,index) in way"></Option>
                </Select>
                </Col>
                <!--收取方-->
                <Col :lg="2" :md="2">
                <Select v-model="searchdata.payer" placeholder="付费人/收取人" :transfer="true">
                    <Option value="1" label="业主"></Option>
                    <Option value="2" label="客户"></Option>
                </Select>
                </Col>

                <!--关键字查询-->
                <Col :lg="2" :md="2">
                <Input v-model="searchdata.kw" placeholder="关键字查询"></Input>
                </Col>
                <!--查询-->
                <Col :lg="5" :md="5">
                <Button type="primary" @click="doSearch">查询</Button>
                <Button type="primary" @click="clearSearch">清空</Button>
                </Col>
            </Row>
        </Card>
        </Col>
        <!--表格-->
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Table border :columns="columns" :data="listData"></Table>
            <Row>
                <Col :lg="12" :md="12" style="line-height: 52px;color: #FF0000">
                <span>实应收合计：{{yingshou}}元</span>&nbsp;&nbsp;&nbsp;&nbsp;
                <span>已收合计：{{shishou}}元</span>
                </Col>
                <Col :lg="12" :md="12">
                <Row type="flex" justify="end">
                    <Col>
                    <div style="margin: 10px;overflow: hidden">
                        <div style="float: right;">
                            <Page :total="totalnum" :current="currentpage" @on-change="changePage"></Page>
                        </div>
                    </div>
                    </Col>
                </Row>
                </Col>
            </Row>
        </Card>
        </Col>
        <!--MODAL-->
        <Modal v-model="rejectModal" :mask-closable="false">
            <div slot="header">
                <a class="ivu-modal-close" @click="rejectModalCancel"><i
                        class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                <div class="ivu-modal-header-inner">佣金驳回</div>
            </div>
            <div slot="footer">
                <Button type="text" size="large" @click="rejectModalCancel">取消</Button>
                <Button type="primary" size="large" @click="rejectModalOk">确定</Button>
            </div>
            <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="100">
                <Row>
                    <Col :lg="24" :md="24">
                    <FormItem prop="reason" label="驳回原因">
                        <Input type="text" v-model="formValidate.reason" placeholder=""></Input>
                    </FormItem>
                    </Col>
                </Row>
            </Form>
        </Modal>
        <Modal title="查看凭据" v-model="visibleModal">
            <div slot="header">
                <a class="ivu-modal-close" @click="visibleModal = false" style="display: block!important">
                    <i class="ivu-icon ivu-icon-ios-close-empty"></i>
                </a>
                <div class="ivu-modal-header-inner">查看凭据</div>
            </div>
            <Carousel v-if="visibleModal"  dots="none" value="0">
                <CarouselItem v-for="image in imageData.list">
                    <img v-if="image" :src="imgurl + image" style="width: 100%">
                </CarouselItem>
            </Carousel>
        </Modal>
    </Row>
</template>

<script>
    export default {
        name: 'payDaishoukuan',
        data() {
            return {
                searchdata: {
                    type: '',
                    status: '',
                    datetype: '',
                    daterange: [],
                    way:'',
                    payer:'',
                    kw: ''
                },
                listData:[],
                totalnum:'',
                currentpage: 1,
                yingshou:'',
                shishou:'',
                way:'',
                rejectModal: false, // 驳回弹窗
                visibleModal:false,
                imgurl: api_param.imgurl,
                imageData:{
                    list:[]
                },

                formValidate: {
                    id:'',
                    reason: ''
                },
                ruleValidate: {
                    reason: [{required: true, message: '请填写驳回原因', trigger: 'blur'}]
                },
                columns: [
                    {
                        title: '类型',
                        key: 'collection_type',
                        align: 'center',
                        width: 88,
                        fixed: 'left',
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.collection_type == 1){
                                texts = '代收款';
                            }else if(params.row.collection_type == 2){
                                texts = '代付款';
                            }else if(params.row.collection_type == 3){
                                texts = '意向金';
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '成交/房源编号',
                        key: 'order_sn',
                        align: 'center',
                        width: 120,
                        fixed: 'left',
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.collection_type == 1 || params.row.collection_type == 2){
                                texts = params.row.order_sn;
                            }else if(params.row.collection_type == 3){
                                texts = params.row.house_sn;
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '录入人',
                        key: 'cname',
                        align: 'center',
                        width: 100
                    },

                    {
                        title: '状态',
                        key: 'collection_status',
                        align: 'center',
                        width: 100,
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.collection_status == 0){
                                texts = '待确认';
                            }else if(params.row.collection_status == 1){
                                texts = '已确认';
                            }else if(params.row.collection_status == 2){
                                texts = '已驳回';
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '操作人',
                        key: 'uname',
                        align: 'center',
                        width: 100
                    },
                    {
                        title: '票据编号',
                        key: 'collection_no',
                        align: 'center',
                        width: 100
                    },
                    {
                        title: '费用凭据',
                        key: 'collection_image',
                        align: 'center',
                        width: 100,
                        render: (h, params) => {
                            if(params.row.collection_image.length > 0){
                                return h('div', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    style: {
                                        color: '#2d8cf0',
                                        cursor: 'pointer'
                                    },
                                    domProps: {
                                        innerHTML: '查看'
                                    },
                                    on: {
                                        click: () => {
                                            this.visibleModal = true;
                                            this.$set(this.imageData,'list',params.row.collection_image);
                                            console.log(this.imageData);
                                        }
                                    }
                                })
                            }
                        }
                    },
                    {
                        title: '费用用途',
                        key: 'collection_purpose',
                        align: 'center',
                        width: 100
                    },
                    {
                        title: '金额',
                        key: 'collection_money',
                        align: 'center',
                        width: 100
                    },
                    {
                        title: '付费人/收取人',
                        key: 'collection_payer',
                        align: 'center',
                        width: 100,
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.collection_payer == 1){
                                texts = '业主';
                            }else if(params.row.collection_payer == 2){
                                texts = '客户';
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '收取时间',
                        key: 'collection_day',
                        align: 'center',
                        width: 100
                    },
                    {
                        title: '结算方式',
                        key: 'collection_way',
                        align: 'center',
                        width: 100
                    },
                    {
                        title: '驳回理由',
                        key: 'collection_reason',
                        align: 'center',
                        width: 100
                    },
                    {
                        title: '操作时间',
                        key: 'utime',
                        align: 'center',
                        width: 150
                    },
                    {
                        title: '成交时间',
                        key: 'order_deal_date',
                        align: 'center',
                        width: 150,
                    },
                    {
                        title: '备注',
                            key: 'beizhu',
                        align: 'center',
                        width: 150
                    },
                    {
                        title: '操作',
                        key: 'caozuo',
                        width: 120,
                        align: 'center',
                        fixed: 'right',
                        render: (h, params) => {
                            let ret = [];
                            if(params.row.collection_status == 0){
                                ret.push(h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px',
                                        color: '#1877a8'
                                    },
                                    on: {
                                        click: () => {
                                            this.formValidate.id = params.row.collection_id;
                                            this.sureCollection();
                                        }
                                    }
                                }, '确认'));
                            }
                            if(params.row.collection_status == 0){
                                ret.push(h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px',
                                        color: '#1877a8'
                                    },
                                    on: {
                                        click: () => {
                                            this.formValidate.id = params.row.collection_id
                                            this.rejectModal = true;
                                        }
                                    }
                                }, '驳回'));
                            }
                            return h('div', ret)
                        }
                    }
                ],
            };
        },
        created() {
            this.getSetting();
            this.getIndex();
        },
        methods:{
            getSetting(){
                this.$http.get(api_param.apiurl + 'financecollection/getsetting', {
                    params: {},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.way = response.data.data.way;
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
                    console.log(response)
                })
            },
            getIndex() { //列表页
                this.$http.get(api_param.apiurl + 'financecollection/getlist', {
                    params: {
                        type: this.searchdata.type,
                        status: this.searchdata.status,
                        datetype: this.searchdata.datetype,
                        daterange: this.searchdata.daterange,
                        way: this.searchdata.way,
                        payer: this.searchdata.payer,
                        kw: this.searchdata.kw,
                        page: this.currentpage
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum = response.data.data.totalnum;
                        this.listData = response.data.data.list;
                        this.yingshou = response.data.data.yingshou;
                        this.shishou = response.data.data.shishou;
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        this.$Message.warning(response.data.message);
                    }
                    //console.log(this.purviews);
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response)
                })
            },
            //搜索
            handleChange(date) { //选择日期回调
                this.searchdata.daterange = date;
                //console.log(this.month);
            },
            doSearch() {
                this.currentpage = 1;
                this.getIndex();
            },
            clearSearch() {
                this.searchdata.type = '';
                this.searchdata.status = '';
                this.searchdata.datetype = '';
                this.searchdata.daterange = [];
                this.searchdata.way = '';
                this.searchdata.payer = '';
                this.searchdata.kw = '';
                this.getIndex();
            },
            //分页
            changePage(page) {
                this.currentpage = page;
                this.getIndex();
            },
            sureCollection(){
                this.$Modal.confirm({
                    title: '确定收款',
                    content: '确定要执行此操作吗？',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + 'financecollection/updatestatus',
                        {
                            id: this.formValidate.id,
                            status:1
                        },
                        {
                            emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}
                        }).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
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
                            console.log(response);
                        })
                    }
                })
            },
            rejectModalCancel(){
                this.$refs['formValidate'].resetFields();
                this.rejectModal = false;
            },
            rejectModalOk(){
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'financecollection/updatestatus',
                            {
                                id: this.formValidate.id,
                                status:2,
                                reason:this.formValidate.reason,
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['formValidate'].resetFields();
                                this.rejectModal = false;
                                this.$Message.success('操作成功');
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
                            console.log(response)
                        })
                    }
                });
            },
        }
    };
</script>

<style scoped>

</style>