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
            <Row :gutter="5">
<!--                <Col :lg="2" :md="2">-->
<!--                <Select placeholder="房源类别" v-model="searchData.sale_type">-->
<!--                    <Option value="0">全部</Option>-->
<!--                    <Option value="2">二手房出售</Option>-->
<!--                    <Option value="1">二手房出租</Option>-->
<!--                    <Option value="3">二手房高端</Option>-->
<!--                </Select>-->
<!--                </Col>-->
                <Col :lg="2" :md="2">
                <Select placeholder="房源状态" v-model="searchData.house_status" @on-change="changehs">
                    <Option value="1">有效</Option>
                    <Option value="0">无效</Option>
                    <Option value="2">其他</Option>
                    <Option value="3">撤单</Option>
                    <Option value="4">成交</Option>
                </Select>
                </Col>
                <Col :lg="2" :md="2">
                <Select placeholder="委托状态" v-model="searchData.hw_status" @on-change="changehws">
                    <Option value="1">正常</Option>
                    <Option value="9">失效</Option>
                </Select>
                </Col>
            </Row>
            <Row :gutter="5" style="margin-top:10px">
                <Col :lg="2" :md="2">
                <Cascader :data="settings.alldepartlist" :value.sync="searchData.departpath" filterable change-on-select
                          @on-change="changeDepart" placeholder="选择部门"></Cascader>
                </Col>
                <Col :lg="2" :md="2">
                <Select v-model="searchData.u_id" :transfer="true" placeholder="选择员工" @on-change="changeperson">
                    <Option v-for="item in users" :value="item.u_id" :key="item.u_id">{{item.u_name}}</Option>
                </Select>
                </Col>
                <Col :lg="4" :md="4">
                <DatePicker type="daterange" :value="searchData.daterange" @on-change="selectDate" format="yyyy-MM-dd HH:mm"
                            placeholder="选择时间段" style="width: 100%"></DatePicker>
                </Col>
                <Col :lg="2" :md="2">
                <Cascader :data="settings.villages" v-model="searchData.villages" placeholder="区域选择" @on-change="selectArea"></Cascader>
                </Col>
                <Col :lg="2" :md="2">
                <Input placeholder="单元、房号" v-model="searchData.danyuan" @on-change="changedanyuan"></Input>
                </Col>
                <Col :lg="3" :md="3">
                <Button type="primary" @click="doSearch">查询</Button>
                <Button type="primary" @click="doClear" :disabled="isDisable">清空</Button>
                </Col>
            </Row>
            <Row :gutter="5" style="margin-top:10px">
                <Col :lg="24" :md="24" class="margin-10px">
                <RadioGroup v-model="daikan" @on-change="daikan_s">
                    <Radio label="3">三天有带看</Radio>
                    <Radio label="7">七天有带看</Radio>
                    <Radio label="15">十五天有带看</Radio>
                </RadioGroup>
                <RadioGroup v-model="genjin" @on-change="genjin_s">
                    <Radio label="3">三天有跟进</Radio>
                    <Radio label="7">七天有跟进</Radio>
                    <Radio label="15">十五天有跟进</Radio>
                </RadioGroup>
                <Dropdown trigger="click" @on-click='paixu'>
                    <Button type="primary">
                        <a href="javascript:void(0)" style="color: #fff"> 条件排序
                            <Icon type="arrow-down-b"></Icon>
                        </a>
                    </Button>
                    <DropdownMenu slot="list">
                        <DropdownItem name="1">按跟进次数升序</DropdownItem>
                        <DropdownItem name="2">按跟进次数降序</DropdownItem>
                        <DropdownItem name="3">按带看次数升序</DropdownItem>
                        <DropdownItem name="4">按带看次数降序</DropdownItem>
                        <DropdownItem name="5">按录入时间升序</DropdownItem>
                        <DropdownItem name="6">按录入时间降序</DropdownItem>
                    </DropdownMenu>
                </Dropdown>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" class="marTop10px">
        <Card>
            <Table border :columns="chColumns" :data="chData"></Table>
            <div style="margin: 10px;overflow: hidden">
            <div style="float: right;">
                <Page :total="pageCount" :current="page" @on-change="changePage" :show-total="true"
                      :page-size="pageSize"></Page>
            </div>
        </div>
        </Card>
        </Col>
        <!--失效-->
        <Modal v-model="shixiao" title="失效" :mask-closable="false">
            <div slot="header">
                <a class="ivu-modal-close" @click="shixiaoModalCancel" style="display: block!important"><i
                        class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                <div class="ivu-modal-header-inner">失效</div>
            </div>
            <div slot="footer">
                <Button type="text" size="large" @click="shixiaoModalCancel">取消</Button>
                <Button type="primary" size="large" @click="shixiaoModalOk">确定</Button>
            </div>
            <Form ref="shixiaoData" :model="shixiaoData" :rules="shixiaoValidate" :label-width="80">

                <Row>
                    <Col :lg="24" :md="24">
                    <FormItem label="失效原因" prop="shixiaoyuanyin">
                        <Select placeholder="失效原因" :transfer="true" v-model="shixiaoData.shixiaoyuanyin" >
                            <Option v-for="item in settings.weituoshixiao" :value="item.child_name" :key="item.child_name">{{ item.child_name }}</Option>
                        </Select>
                    </FormItem>
                    </Col>
                </Row>
                <Row>
                    <Col :lg="24" :md="24">
                    <FormItem label="备注" prop="shixiaoBeizhu">
                        <Input  v-model="shixiaoData.shixiaoBeizhu" type="textarea" :rows="4" placeholder="请输入失效备注"></Input>
                    </FormItem>
                    </Col>
                </Row>
            </Form>
        </Modal>
    </Row>
</template>

<script>
    export default {
        name: 'houseWeituo',
        data () {
            return {
                isDisable: true,
                shixiao:false,
                pageSize: 10,
                page: 1,
                pageCount: 0,
                searchData: {},
                settings: {},
                users: [],
                daikan: '',
                genjin: '',
                totallog: 0,
                currentpage: 1,
                keylogdata: [],
                shixiaoValidate: {//表单验证
                    shixiaoyuanyin: [{required: true, message: '请选择失效原因', trigger: 'change'}],
                    shixiaoBeizhu: [{required: true, message: '请选择失效原因', trigger: 'blur'}]
                },
                shixiaoData:{
                    shixiaoyuanyin:'',
                    shixiaoBeizhu:'',
                },
                chColumns:
                    [
                        {
                            title: '标签',
                            key: 'biaoqian',
                            fixed: 'left',
                            width: 128,
                            align: 'center',
                            render: (h, params) => {
                                let color = ['blue', 'red', 'yellow', 'green', 'orange'];

                                let ret = [];
                                if (params.row.genjincishu) {
                                    ret.push(h('Tag', {props: {color: 'green'}}, '跟(' + params.row.genjincishu + ')'));
                                }
                                if (params.row.daikancishu) {
                                    ret.push(h('Tag', {props: {color: 'yellow'}}, '带(' + params.row.daikancishu + ')'));
                                }
                                return h('div', ret);
                            }
                        },
                        {
                            title: '房源类型',
                            key: 'sale_type',
                            align: 'center',
                            width: 88,
                            render: (h, params) => {
                                let type = params.row.sale_type;
                                let texts = '';
                                if(type == '2'){
                                    texts = '出售';
                                }else if(type == '1'){
                                    // texts = '出租';
                                    texts = '出售';
                                }else if(type == '3'){
                                    texts = '出售';
                                    // texts = '高端';
                                }else{
                                    return '未知';
                                }
                                return h('div', {props: {},},texts)

                            }
                        },
                        {
                            title: '委托状态',
                            key: 'hw_status',
                            width: 88,
                            align: 'center',
                            render: (h, params) => {
                                let texts = '';
                                if (params.row.hw_status == '1') {
                                    texts = '有效';
                                }
                                if (params.row.hw_status == '9') {
                                    texts = '失效';
                                }
                                return h('div', {props: {},},texts)
	                        }
                        },
                        {
                            title: '房源状态',
                            key: 'house_status_text',
                            width: 88,
                            align: 'center'
                        },
                        {
                            title: '片区',
                            key: 'dts_name',
                            width: 108,
                            align: 'center'
                        },
                        {
                            title: '小区',
                            key: 'village_name',
                            width: 88,
                            align: 'center'
                        },
                        // {
                        //     title: '座栋',
                        //     key: 'loudong_name',
                        //     width: 88,
                        //     align: 'center'
                        // },
                        // {
                        //     title: '单元',
                        //     key: 'danyuan_name',
                        //     width: 88,
                        //     align: 'center'
                        // },
                        // {
                        //     title: '房号',
                        //     key: 'fanghao_name',
                        //     width: 88,
                        //     align: 'center'
                        // },
                        {
                            title: '房源编号',
                            key: 'house_sn',
                            align: 'center',
                            width: 148,
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
                                                    saleType: params.row.sale_type
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
                            title: '开始时间',
                            key: 'hw_start_time',
                            align: 'center',
                            width: 128
                        },
                        {
                            title: '结束时间',
                            key: 'hw_end_time',
                            align: 'center',
                            width: 128
                        },
                        {
                            title: '添加时间',
                            key: 'ctime',
                            align: 'center',
                            width: 128
                        },
                        {
                            title: '带看日期',
                            key: 'daikanshijian',
                            align: 'center',
                            width: 148
                        },
                        {
                            title: '维护人跟进日期',
                            key: 'weihurengenjin',
                            align: 'center',
                            width: 128
                        },
                        {
                            title: '全员跟进日期',
                            key: 'quanyuangenjin',
                            align: 'center',
                            width: 128
                        },
                        {
                            title: '委托部门',
                            key: 'd_name',
                            align: 'center',
                            width: 128
                        },
                        {
                            title: '委托人',
                            key: 'u_name',
                            align: 'center',
                            width: 128
                        },
                        {
                            title: '委托编号',
                            key: 'hw_sn',
                            align: 'center',
                            width: 128
                        },
                        {
                            title: '失效时间',
                            key: 'hw_shixiaoshijian',
                            align: 'center',
	                        width: 128
                        },
                        {
                            title: '失效操作人',
                            key: 'hw_invalid_uname',
                            align: 'center',
	                        width: 128
                        },
                        {
                            title: '失效原因',
                            key: 'hw_invalid_reason',
                            align: 'center',
	                        width: 128
                        },
//                        {
//                            title: '失效操作时间',
//                            key: 'utime',
//                            align: 'center',
//                            render: (h, params) => {
//                                let texts = '';
//                                if(params.row.hw_invalid_uname == ''){
//                                    texts = '';
//                                }else {
//                                    texts = params.row.utime;
//                                }
//                                return h('div', {props: {},},texts)
//                            }
//                        },
                        {
                            title: '操作',
                            key: 'action',
                            align: 'center',
                            fixed: 'right',
                            width: 110,
                            render: (h, params) => {
                                var ret=[];
                                if(params.row.hw_status != 9) {
                                    ret.push(h('Button', {
                                        props: {
                                            type: 'error',
                                            size: 'small'
                                        },
                                        style: {
                                            marginRight: '5px'
                                        },
                                        on: {
                                            click: () => {
                                                this.hw_id = params.row.hw_id;
                                                this.house_id = params.row.house_uuid;
                                                this.house_sn = params.row.house_sn;
                                                this.shixiao = true;
                                            }
                                        }
                                    },'失效'));
                                }
                                return h('div',ret);
                            }
                        }
                    ],
                chData: [],
                hw_id: '',
                house_id:'',
                house_sn:'',
            };
        },
        created () {
            this.getSetting();
            this.getIndex();
        },
        methods: {
            daikan_s(){
                this.genjin = '';
                this.page=1;
                this.getIndex()
                this.isDisable=false;
            },
            paixu (name) {
                this.searchData.paixu = name;
                this.pageCurrent=1;
                this.getIndex();
                this.isDisable=false;
            },
            genjin_s(){
                this.page=1;
                this.daikan = '';
                this.getIndex()
                this.isDisable=false;
            },
            getSetting () {//获取配置项目
                this.$http.get(api_param.apiurl + 'house_weituo/getsetting', {
                    params: {},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调

                        for (let i in response.data.data) {
                            this.$set(this.settings, i, response.data.data[i]);
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
                    this.$Message.error('你的网络开小差了^—^');
                });
            },
            getIndex () {
                this.searchData.page = this.page;
                this.searchData.daikan = this.daikan;
                this.searchData.genjin = this.genjin;
                let action = 'house_weituo/getindex';
                this.$http.post(api_param.apiurl + action,
                    this.searchData,
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.chData = response.data.data.list;
                        this.pageCount = parseInt(response.data.data.count);
                        this.$Message.success('获取成功');
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
            changehs(){
                this.isDisable=false;
            },
            changehws(){
                this.isDisable=false;
            },

            //修改部门
            changeDepart (value, selectedData) {
                let d_id = value.pop();
                this.users = this.settings.users[d_id];
                this.isDisable=false;
            },
            changeperson(value, selectedData){
                this.isDisable=false;
            },
            changeJiechuDepart (value, selectedData) {
                let d_id = value.pop();
                this.jiechu_users = this.settings.users[d_id];
                this.isDisable=false;
            },

            //搜索
            selectDate (date) { //选择日期回调
                this.searchData.daterange = date;
                this.isDisable=false;
            },
            selectArea (selectedData) { //状态选择
                this.mianji = selectedData.split('-');
                this.isDisable=false;
            },
            changedanyuan(){
                this.isDisable=false;
            },
            doSearch () {
                this.page = 1;
                this.getIndex();
            },
            doClear () {
                this.isDisable=true;
                this.page=1;
                this.searchData.sale_type = '';
                this.searchData.house_status = '';
                this.searchData.hw_status = '';
                this.searchData.departpath = [];
                this.searchData.u_id = '';
                this.searchData.villages = '';
                this.searchData.danyuan = '';
                this.searchData.daterange = '';
                this.users = [];
                this.daikan='';
                this.genjin='';
                this.getIndex();
            },

            //分页
            changelogPage (page) {
                this.lpage = page;
                this.keyLog();
            },
            changePage (page) {
                this.page = page;
                this.getIndex();
            },
            shixiaoModalCancel(){
                this.shixiao= false;
                this.$refs['shixiaoData'].resetFields();
                this.shixiaoData.shixiaoyuanyin = '';
                this.shixiaoData.shixiaoBeizhu = '';
            },
            shixiaoModalOk(){
                this.$refs['shixiaoData'].validate((valid) => {
                    if (valid) {
                        this.shixiaoData.hw_id = this.hw_id;
                        this.shixiaoData.hk_status =9;
                        this.shixiaoData.house_id =this.house_id;
                        this.shixiaoData.house_sn =this.house_sn;
                        this.$http.post(api_param.apiurl + 'house_weituo/shixiao',
                            this.shixiaoData,
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.shixiao=false;
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
                            this.$Message.error('网络异常');
                        })
                    }
                })
            },
        },
    };
</script>
