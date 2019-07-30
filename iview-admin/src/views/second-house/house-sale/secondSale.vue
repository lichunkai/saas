<style lang="less">
    @import "sale.less";
</style>

<template>
    <Row>
        <Col :lg="24" :md="24">
            <Card>
                <Row>
                    <Col :lg="24" :md="24">
                        <Row :gutter="5">
                            <Col :lg="2" :md="2">
                                <Cascader :data="settings.dts" v-model="searchData.dts_id" placeholder="片区选择" filterable
                                          change-on-select @on-change="changeDts"></Cascader>
                            </Col>
                            <Col :lg="3" :md="3">
                                <Select placeholder="小区选择" :transfer="true" :filterable="true"
                                        v-model="searchData.village_id">
                                    <Option v-for="item in village" :value="item.village_id" :key="item.village_name">{{
                                        item.village_name }}
                                    </Option>
                                </Select>
                            </Col>
                            <Col :lg="2" :md="2">
                                <Input v-model="searchData.loudong_name" placeholder="座栋"></Input>
                            </Col>
                            <Col :lg="2" :md="2">
                                <Input v-model="searchData.danyuan_name" placeholder="单元"></Input>
                            </Col>
                            <Col :lg="2" :md="2">
                                <Input v-model="searchData.fanghao_name" placeholder="房号"></Input>
                            </Col>
                            <Col :lg="4" :md="4">
                                <Button type="primary" @click="doSearch">搜索</Button>
                                <Button type="primary" @click="clearSearch">清空</Button>
                            </Col>
                            <Col :lg="4" :md="4">
                                <Row type="flex" justify="end">
                                    <Col>
                                        <Button type="warning">平均售价:<span><strong
                                                style="font-size: 18px">  {{avgprice}}</strong> 元/㎡</span>
                                        </Button>
                                    </Col>
                                </Row>
                            </Col>
                            <Col :lg="5" :md="5">
                                <Row type="flex" justify="end">
                                    <Col>
                                        <Button type="primary" @click="addHouse">新增出售房源</Button>
                                        <secondSaleAdd ref="secondSaleAdd" :addHouseModal="addHouseModal" @getIndex="getIndex" :settings="settings" :formValidate="formValidate"
                                                       :ruleValidate="settings.ruleValidate" v-on:resetModal="resetModal"></secondSaleAdd>
                                    </Col>
                                </Row>
                            </Col>
                        </Row>
                    </Col>
                    <Col :lg="24" :md="24">
                        <Row :gutter="5">
                            <Col :lg="2" :md="2">
                                <Input v-model="searchData.huxing_shi" placeholder="室"></Input>
                            </Col>
                            <Col :lg="2" :md="2">
                                <Input v-model="searchData.huxing_ting" placeholder="厅"></Input>
                            </Col>
                            <Col :lg="2" :md="2">
                                <Input v-model="searchData.huxing_wei" placeholder="卫"></Input>
                            </Col>
                            <Col :lg="2" :md="2">
                                <Input v-model="searchData.huxing_chu" placeholder="厨"></Input>
                            </Col>

                            <Col :lg="3" :md="3">
                                <Input v-model="searchData.keyword" placeholder="房源编号、电话"></Input>
                            </Col>
                        </Row>

                    </Col>
                    <Col :lg="24" :md="24" class="margin-10px">
                        <ul class="saleState">
                            <p>状态</p>　
                            <li v-for="(item,index) in items" @click="selectStyle (item, index)"
                                :class="{'state':item.active}">
                                 <span>{{item.select}}</span>
                            </li>
                        </ul>
                    </Col>
                    <Col :lg="24" :md="24" class="margin-10px">
                        <Checkbox-group v-model="searchTag" @on-change="changeSearchTag">
                            <Checkbox label="myshare">我的共享盘</Checkbox>
                            <Checkbox label="companyshare">我的共享盘</Checkbox>
                            <Checkbox label="youyaoshi">有钥匙</Checkbox>
                            <Checkbox label="wuyaoshi">无钥匙</Checkbox>
                            <Checkbox label="sanrixinshang">三日新上</Checkbox>
                            <Checkbox label="dujia">独家</Checkbox>
                            <Checkbox label="xuequfang">学区房</Checkbox>
                            <Checkbox label="fengpan">封盘路径</Checkbox>
                            <Checkbox label="jiqie">急切</Checkbox>
                            <Checkbox label="main">主推</Checkbox>
                            <Checkbox label="daikan">有带看</Checkbox>
                            <Checkbox label="genjin">有跟进</Checkbox>
                        </Checkbox-group>
                    </Col>
                    <Col :lg="24" :md="24" class="margin-10px">
                        <Row :gutter="5">
                            <Col :lg="2" :md="2">
                                <Select v-model="searchData.fydj" placeholder="房源等级" :transfer="true"
                                        @on-change="doSearch">
                                    <Option v-for="(item,index) in settings.fydj" :value="item" :key="index"
                                            v-if="item != 'D级'">{{
                                        item}}
                                    </Option>
                                </Select>
                            </Col>
                            <Col :lg="2" :md="2">
                                <Select v-model="searchData.sell_jgqj" placeholder="价格区间" :transfer="true"
                                        @on-change="doSearch">
                                    <Option v-for="(item,index) in settings.jgqj" :value="item.min+'-'+item.max"
                                            :key="index">{{
                                        item.child_name }}
                                    </Option>
                                </Select>
                            </Col>
                            <Col :lg="2" :md="2">
                                <Select v-model="searchData.mjqj" placeholder="面积区间" :transfer="true"
                                        @on-change="doSearch">
                                    <Option v-for="(item,index) in settings.mjqj" :value="item.min+'-'+item.max"
                                            :key="index">{{
                                        item.child_name }}
                                    </Option>
                                </Select>
                            </Col>
                            <Col :lg="2" :md="2">
                                <Select v-model="searchData.xuequ" placeholder="学区选择" :transfer="true" @on-change="doSearch">
                                    <Option v-for="(item,index) in settings.xuequ" :value="item.s_id" :key="index">{{item.s_name }}
                                    </Option>
                                </Select>
                            </Col>
                            <Col :lg="2" :md="2">
                                <Select placeholder="来源" :transfer="true" v-model="searchData.laiyuan"
                                        @on-change="doSearch">
                                    <Option v-for="item in settings.laiyuan" :value="item" :key="item">{{ item }}
                                    </Option>
                                </Select>
                            </Col>

                            <Col :lg="2" :md="2" v-if=" settings.isSelfRange == '0' ">
                                <Cascader :data="settings.departlist" placeholder="部门管理" v-model="searchData.departpath"
                                          filterable
                                          change-on-select @on-change="changeSearchDepart" :transfer="true"></Cascader>
                            </Col>

                            <Col :lg="2" :md="2" v-if=" settings.isSelfRange == '0' ">
                                <Select v-model="searchData.u_id" placeholder="用户管理" :transfer="true"
                                        @on-change="doSearch">
                                    <Option v-for="item in users" :value="item.u_id" :key="item.u_id">{{item.u_name}}
                                    </Option>
                                </Select>
                            </Col>
                            <Col :lg="3" :md="3">
                                <DatePicker type="daterange" :value="searchData.daterange" @on-change="selectDaterange"
                                            placeholder="起始时间"></DatePicker>
                            </Col>
                        </Row>
                    </Col>
                    <Col :lg="24" :md="24" class="margin-10px">
                        <Row :gutter="10" type="flex" justify="space-between">
                            <Col :lg="12" :md="12">
                                <div>
                                    <Button type="primary" @click="exportData">导出房源
                                    </Button>
                                    <Button type="primary" @click="alldel" v-if="settings.topbutton.sellalldel == 1">
                                        房源删除
                                    </Button>
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
                                </div>
                            </Col>
                            <Col :lg="12" :md="12">
                                <Row type="flex" justify="end">
                                    <Col>
                                        <Button type="ghost" @click="customList = true">自定义列表</Button>
                                        <Modal v-model="customList" title="自定义列表">
                                            <div slot="header">
                                                <a class="ivu-modal-close" @click="customList = false"
                                                   style="display: block!important"><i
                                                        class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                                                <div class="ivu-modal-header-inner">自定义列表</div>
                                            </div>
                                            <div class="CheckboxList">
                                                <Checkbox-group v-model="tableColumnsChecked"
                                                                @on-change="changeTableColumns">
                                                    <Checkbox label="biaoqian">标签</Checkbox>
                                                    <Checkbox label="house_sn">房源编号</Checkbox>
                                                    <Checkbox label="house_status">状态</Checkbox>
                                                    <Checkbox label="dts_name">片区</Checkbox>
                                                    <Checkbox label="village_name">小区</Checkbox>
                                                    <Checkbox label="chaoxiang">朝向</Checkbox>
                                                    <Checkbox label="louceng">楼层</Checkbox>
                                                    <Checkbox label="fangxing">房型</Checkbox>
                                                    <Checkbox label="zhuangxiu">装修</Checkbox>
                                                    <Checkbox label="jianzhumianji">使用面积</Checkbox>
                                                    <Checkbox label="shiyongmianji">产证面积</Checkbox>
                                                    <Checkbox label="chanquanxingzhi">产权性质</Checkbox>
                                                    <Checkbox label="jianzaoniandai">建筑年代</Checkbox>
                                                    <Checkbox label="sell_price">售价</Checkbox>
                                                    <Checkbox label="unit_price">单价</Checkbox>
                                                    <Checkbox label="dengji">等级</Checkbox>
                                                    <Checkbox label="ctime">录入日期</Checkbox>
                                                    <Checkbox label="utime">修改日期</Checkbox>
                                                    <Checkbox label="zuihougenjin">全员最后跟进</Checkbox>
                                                    <Checkbox label="tianjiaren">录入人</Checkbox>
                                                    <Checkbox label="weihurengenjin">维护人最后跟进</Checkbox>
                                                    <Checkbox label="weihuren">维护人</Checkbox>
                                                    <Checkbox label="mark">备注</Checkbox>
                                                    <!--<Checkbox label="weituobianhao">委托编号</Checkbox>-->
                                                    <Checkbox label="bumen">部门</Checkbox>
                                                    <Checkbox label="fukuanfangshi">付款方式</Checkbox>
                                                    <Checkbox label="chuyong">是否出佣</Checkbox>
                                                </Checkbox-group>
                                            </div>
                                            <div slot="footer">
                                                <Row type="flex" justify="space-between">
                                                    <Button type="ghost" size="large" @click="resetColumns">恢复默认
                                                    </Button>
                                                    <Button type="primary" size="large" @click="saveColumns">保存</Button>
                                                </Row>
                                            </div>
                                        </Modal>
                                    </Col>
                                </Row>
                            </Col>
                        </Row>
                    </Col>
                </Row>
            </Card>
        </Col>
        <Col :lg="24" :md="24" class="margin-10px">
            <Card>
                <Table :data="houseList" ref="houseTable" :columns="tableColumns2" border
                       @on-selection-change="selectionok" :ellipsis="true"></Table>
                <div style="margin: 10px;overflow: hidden">
                    <div style="float: right;">
                        <Page :total="pageTotal" :current="pageCurrent" @on-change="changePage" show-total
                              :page-size="pageSize"></Page>
                    </div>
                </div>
            </Card>
        </Col>
    </Row>
</template>

<script>
    import Vue from 'vue';
    import Cookies from 'js-cookie';
    import secondSaleAdd from './secondSaleAdd.vue';
    import roomDetails from '../component/roomdetails.vue';

    export default {
        name: 'secondSale_index',
        components: {
            secondSaleAdd,
            roomDetails
        },
        data() {
            return {
                pageSize: 10,
                village: [],
                exportvillage: [],
                fruit: [],
                formValidate: {
                    dts:[],
                    village:[],
                    fruit: [],
                    sale_type: 2,
                    tuijianbiaoqian: [],
                    village_name: '',
                    customer_name: '',
                    customer_sex: '',
                    customer_phone: '',
                    customer_type: '本人',
                    house_title: '',
                    house_level: '',
                    house_tag: [],
                    house_tuijian_tag:[],
                    peitao:[],
                    house_school:'',
                    sell_price: '',
                    jianzhumianji: '',
                    huxing_shi: '',
                    huxing_ting: '',
                    huxing_wei: '',
                    huxing_chu: '',
                    huxing_yangtai: '',
                    shiyongmianji: '',
                    louceng_now: '',
                    louceng_total: '',
                    chaoxiang: '',
                    tihu_ti: '',
                    tihu_hu: '',
                    zhuangxiu: '',
                    xianzhuang: '',
                    fangwuleixing: '',
                    jianzhujiegou: '',
                    jianzaoniandai: '',
                    chanquanxingzhi: '',
                    chanzhengriqi: '',
                    chanquannianxian: '',
                    fangyuanshuifei: '',
                    kanfangfangshi: '',
                    laiyuan: '',
                    // weituobianhao: '',
                    fukuanfangshi: '',
                    yaoshi_dian: '',
                    low_sell_price: '',
                    mark: '',
                    yiban_image: '',
                    dujia_image: ''
                },
                ruleValidate: {
                    sell_price: [
                        {required: true, message: '请输入售价', trigger: 'blur'}
                    ],
                    jianzhumianji: [
                        {required: true, message: '请输入售价', trigger: 'blur'}
                    ],
                    huxing_shi: [
                        {required: true, message: '请输入户型', trigger: 'blur'}
                    ],
                    huxing_ting: [
                        {required: true, message: '请输入户型', trigger: 'blur'}
                    ],
                    huxing_wei: [
                        {required: true, message: '请输入户型', trigger: 'blur'}
                    ],
                    huxing_chu: [
                        {required: true, message: '请输入户型', trigger: 'blur'}
                    ],
                    huxing_yangtai: [
                        {required: true, message: '请输入户型', trigger: 'blur'}
                    ],
                    customer_phone: [
                        {required: true, message: '请输入手机号码', trigger: 'blur'},
                        {
                            validator(rule, value, callback, source, options) {
                                let errors = [];
                                if (!/^(13[0-9]|14[5-9]|15[012356789]|166|17[0-8]|18[0-9]|19[8-9])[0-9]{8}$/.test(value)) {
                                    callback('请输入正确的手机号码!');
                                }
                                callback(errors);
                            }
                        }
                    ]
                },
                settings: {
                    topbutton: [],
                },
                //均价
                avgprice: '',
                //配置项目
                addHouseModal: false,  //添加房源标签
                newZhuanyi: false,//信息批量转移
                saleMore: false,//更多信息
                xuequfang: false,//学区房
                pageTotal: 0,
                pageCurrent: 1,
                customList: false,

                exportUrl: api_param.apiurl + 'house/export?token=' + api_param.XAccessToken,
                // 新增智能搜索
                value3: '',
                data3: ['Steve Jobs', 'Stephen Gary Wozniak', 'Jonathan Paul Ive'],
                active: false,
                items: [
                    {select: '全部', key: 'statustext', value: 'all'},
                    {select: '共享盘', key: 'statustext', value: 'share'},
                    {select: '公盘', key: 'statustext', value: 'public'},
                    {select: '成交', key: 'statustext', value: 'deal'},
                    {select: '失效', key: 'statustext', value: 'invalid'}
                ],
                recommend: [
                    {
                        value: '唯一住房',
                        label: '唯一住房'
                    },
                    {
                        value: '满5唯一',
                        label: '满5唯一'
                    },
                    {
                        value: '地铁',
                        label: '地铁'
                    },
                    {
                        value: '满2唯一',
                        label: '满2唯一'
                    },
                    {
                        value: '到访',
                        label: '到访'
                    },
                    {
                        value: '是否有贷款',
                        label: '是否有贷款'
                    },
                    {
                        value: '地铁房',
                        label: '地铁房'
                    },
                    {
                        value: '未到访',
                        label: '未到访'
                    },
                    {
                        value: '独家',
                        label: '独家'
                    },
                    {
                        value: '学区房',
                        label: '学区房'
                    },
                    {
                        value: '环境好',
                        label: '环境好'
                    },
                    {
                        value: '配套设施齐全',
                        label: '配套设施齐全'
                    }
                ],
                actuality: [
                    {
                        value: '空置',
                        label: '空置'
                    },
                    {
                        value: '在用',
                        label: '在用'
                    },
                    {
                        value: '回迁房',
                        label: '回迁房'
                    },
                    {
                        value: '全新',
                        label: '全新'
                    }
                ],
                model1: '',
                houseList: [],
                // 表格属性
                tableColumns2: [],
                tableColumnsChecked: ['biaoqian', 'house_sn', 'house_status', 'dts_name', 'village_name', 'chaoxiang', 'sell_price', 'unit_price', 'louceng', 'fangxing',
                    'zhuangxiu', 'jianzhumianji', 'tianjiaren', 'weihuren', 'ctime', 'utime'],
                defaultColumnsChecked: ['biaoqian', 'house_sn', 'house_status', 'dts_name', 'village_name', 'chaoxiang', 'sell_price', 'unit_price', 'louceng', 'fangxing',
                    'zhuangxiu', 'jianzhumianji', 'shiyongmianji', 'tianjiaren', 'weihuren', 'ctime', 'utime'],
                searchData: {
                    dts_id: []
                },
                searchTag: [],
                users: [],
                //新加的
                addJiuModal: false,
                jiuData: {
                    peitao: [],
                },
            };
        },
        created() {
            this.getSetting();
            this.getIndex();
            this.changeTableColumns();
            this.searchData.dts_id = [];
            this.getDts(Cookies.get('dts_id'), 1);
        },

        methods: {
            getSetting() {//获取配置项目
                this.$http.get(api_param.apiurl + 'house/getsetting', {
                    params: {
                        sale_type: 2
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调
                        for (let i in response.data.data) {
                            this.$set(this.settings, i, response.data.data[i])
                        }
                        if (response.data.data.customcolumns[5] && response.data.data.customcolumns[5] != null) {
                            this.tableColumnsChecked = response.data.data.customcolumns[5];
                        }
                        this.changeTableColumns();
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
            getIndex() {
                this.addHouseModal = false;
                this.searchData.page = this.pageCurrent;
                this.searchData.sale_type = 2;

                this.$http.post(api_param.apiurl + 'house/getindex',
                    this.searchData,
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.houseList = response.data.data.list;
                        this.pageTotal = parseInt(response.data.data.count);
                        this.avgprice = response.data.data.alavg;
                        window.localStorage.setItem('secondSale_index', JSON.stringify(this.searchData));
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
                })
            },
            addHouse() {//新增房源
                this.$refs.secondSaleAdd.setMakes();
                this.addHouseModal = false;
                this.addHouseModal = true;

            },
            selectionok(selection) {
                this.selection = selection;
                // console.log(this.selection);
            },
            alldel() {
                this.$http.post(api_param.apiurl + 'house/alldel',
                    {
                        'selection': this.selection,
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.selection = [];
                        this.$Message.success(response.data.message);
                        this.getIndex();
                    } else if (response.data.code == 400) {
                        this.$Message.success(response.data.message);
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    //console.log(response)
                })
            },
            paixu(name) {
                this.searchData.paixu = name;
                this.pageCurrent = 1;
                this.getIndex();
            },
            selectArea(selectedData) {
                this.formAvgValidate.area = selectedData.value;
            },
            selectTime(selectedData) {
                this.formAvgValidate.dealtime = selectedData;
            },
            changePage(page) {//分页
                this.pageCurrent = page;
                this.getIndex();
            },
            //状态
            selectStyle(item, index) {
                let _this = this;
                this.$nextTick(function () {
                    this.items.forEach(function (item) {
                        Vue.set(item, 'active', false);
                        Vue.delete(_this.searchData, item.key);
                    });
                    Vue.set(item, 'active', true);
                    Vue.set(_this.searchData, item.key, item.value);
                    _this.pageCurrent = 1;
                    _this.getIndex();
                });
            },

            // 表格属性
            getTable2Columns(type) {
                const table2ColumnList = {
                    index: {
                        type: 'selection',
                        fixed: 'left',
                        width: 60,
                        align: 'center'
                    },
                    biaoqian: {
                        title: '标签',
                        key: 'biaoqian',
                        fixed: 'left',
                        width: 150,
                        align: 'center',
                        render: (h, params) => {
                            let ret = [];
                            if (params.row.house_tag_ji) {
                                ret.push(h('Tag', {props: {color: 'blue'}}, '急'))
                            }
                            if (params.row.house_tag_du) {
                                ret.push(h('Tag', {props: {color: 'green'}}, '独'))
                            }
                            if (params.row.house_tag_quan) {
                                ret.push(h('Tag', {props: {color: 'red'}}, '全'))
                            }
                            if (params.row.house_tag_yao) {
                                ret.push(h('Tag', {props: {color: 'yellow'}}, '钥'))
                            }
                            if (params.row.house_tag_images) {
                                ret.push(h('Tag', {props: {color: 'red'}}, '图'))
                            }
                            if (params.row.house_tag_geng) {
                                ret.push(h('Tag', {props: {color: 'green'}}, '跟(' + params.row.house_tag_geng_num + ')'))
                            }
                            if (params.row.house_tag_dai) {
                                ret.push(h('Tag', {props: {color: 'blue'}}, '带(' + params.row.house_tag_dai_num + ')'))
                            }
                            if (params.row.house_tag_qianpei) {
                                ret.push(h('Tag', {props: {color: 'purple'}}, '签赔'))
                            }
                            return h('div', ret)
                        }
                    },
                    house_sn: {
                        title: '房源编号',
                        key: 'house_sn',
                        fixed: 'left',
                        width: 150,
                        align: 'center',
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
                                            if (params.row.house_uuid) {
                                                let argu = {
                                                    houseId: params.row.house_uuid,
                                                    saleType: 2
                                                };
                                                this.$router.push({
                                                    name: 'roomDetails',
                                                    params: argu
                                                });
                                            } else {
                                                this.$Message.warning('参数错误');
                                            }
                                        }
                                    }
                                })]);
                        }
                    },
                    house_status: {
                        title: '状态',
                        key: 'house_status_text',
                        width: 88,
                        align: 'center'
                    },
                    dts_name: {
                        title: '片区',
                        key: 'dts_name',
                        width: 88,
                        align: 'center'
                    },
                    village_name: {
                        title: '小区',
                        key: 'village_name',
                        width: 88,
                        align: 'center'
                    },
                    chaoxiang: {
                        title: '朝向',
                        key: 'chaoxiang',
                        width: 88,
                        align: 'center'
                    },
                    sell_price: {
                        title: '售价(万)',
                        key: 'sell_price',
                        width: 88,
                        align: 'center'
                    },
                    unit_price: {
                        title: '单价',
                        key: 'unit_price',
                        width: 88,
                        align: 'center',
                        render: (h, params) => {
                            let ret = 0;
                            if (params.row.jianzhumianji != 0 || params.row.jianzhumianji != null) {
                                ret = ((params.row.sell_price / params.row.jianzhumianji) * 10000).toFixed(2);
                            }
                            return h('div', ret)
                        }
                    },
                    louceng: {
                        title: '楼层',
                        key: 'louceng',
                        width: 88,
                        align: 'center'
                    },
                    fangxing: {
                        title: '房型',
                        key: 'fangxing',
                        width: 128,
                        align: 'center'
                    },
                    zhuangxiu: {
                        title: '装修',
                        key: 'zhuangxiu',
                        width: 88,
                        align: 'center'
                    },
                    jianzhumianji: {
                        title: '使用面积',
                        key: 'jianzhumianji',
                        width: 88,
                        align: 'center'
                    },
                    shiyongmianji: {
                        title: '产证面积',
                        key: 'shiyongmianji',
                        width: 88,
                        align: 'center'
                    },
                    chanquanxingzhi: {
                        title: '产权性质',
                        key: 'chanquanxingzhi',
                        width: 88,
                        align: 'center'
                    },
                    jianzaoniandai: {
                        title: '建筑年代',
                        key: 'jianzaoniandai',
                        width: 88,
                        align: 'center'
                    },
                    dengji: {
                        title: '等级',
                        key: 'house_level',
                        width: 88,
                        align: 'center'
                    },
                    ctime: {
                        title: '录入日期',
                        key: 'ctime',
                        width: 128,
                        align: 'center'
                    },
                    utime: {
                        title: '修改日期',
                        key: 'utime',
                        width: 128,
                        align: 'center'
                    },
                    zuihougenjin: {
                        title: '全员最后跟进',
                        key: 'zuihougenjin',
                        width: 128,
                        align: 'center'
                    },
                    weihurengenjin: {
                        title: '维护人最后跟进',
                        key: 'weihurengenjin',
                        width: 128,
                        align: 'center'
                    },
                    tianjiaren: {
                        title: '录入人',
                        key: 'tianjiaren',
                        width: 88,
                        align: 'center'
                    },
                    weihuren: {
                        title: '维护人',
                        key: 'weihuren',
                        width: 88,
                        align: 'center'
                    },
                    mark: {
                        title: '备注',
                        key: 'mark',
                        width: 148,
                        align: 'center'
                    },
                    bumen: {
                        title: '部门',
                        key: 'bumen',
                        width: 88,
                        align: 'center'
                    },
                    fukuanfangshi: {
                        title: '付款方式',
                        key: 'fukuanfangshi',
                        width: 88,
                        align: 'center'
                    },
                    chuyong: {
                        title: '是否出佣',
                        key: 'chuyong',
                        width: 88,
                        align: 'center',
                        render: (h, params) => {
                            let ret = '否';
                            if (params.row.chuyong == '1') {
                                ret = '是';
                            }
                            return h('div', ret);
                        }
                    }
                };

                let data = [table2ColumnList.index];

                if (type == 1) {
                    this.tableColumnsChecked.forEach(col => data.push(table2ColumnList[col]));
                } else {
                    this.tableColumnsChecked = this.defaultColumnsChecked;
                    this.defaultColumnsChecked.forEach(col => {
                        data.push(table2ColumnList[col]);
                    });
                }
                // this.tableColumnsChecked.forEach(col => data.push(table2ColumnList[col]));
                return data;
            },
            changeTableColumns() {
                this.tableColumns2 = this.getTable2Columns(1);
            },
            toggleFav(index) {
                this.tableData2[index].fav = this.tableData2[index].fav === 0 ? 1 : 0;
            },
            exportData(){
                let url= this.exportUrl;
                for (let i in this.searchData) {
                    url += '&'+i+'='+this.searchData[i];
                }
                window.open(url,'_blank');
            },
            doSearch() {
                this.pageCurrent = 1;
                this.getIndex();
            },
            clearSearch() {
                for (let i in this.searchData) {
                    if (i == 'dts_id') {
                        this.$set(this.searchData, i, [])
                    } else {
                        this.$set(this.searchData, i, '')
                    }
                }
                this.items.forEach(function (item) {
                    Vue.set(item, 'active', false);
                });
                this.searchTag = [];
                this.pageCurrent = 1;
                this.getIndex();
            },

            changeSearch(key, value) {
                this.$set(this.searchData, key, value);
                this.pageCurrent = 1;
                this.getIndex();
            },
            changeSearchTag() {
                this.$set(this.searchData, 'myshare', '');
                this.$set(this.searchData, 'companyshare', '');
                this.$set(this.searchData, 'is_yaoshi', '');
                this.$set(this.searchData, 'dujia', '');
                this.$set(this.searchData, 'sanrixinshang', '');
                this.$set(this.searchData, 'xuequfang', '');
                this.$set(this.searchData, 'is_fengpan', '');
                this.$set(this.searchData, 'jiqie', '');
                this.$set(this.searchData, 'main', '');
                this.$set(this.searchData, 'daikan', '');
                this.$set(this.searchData, 'genjin', '');
                for (let i in this.searchTag) {
                    switch (this.searchTag[i]) {
                        case 'myshare':
                            this.$set(this.searchData, 'myshare', 'true');
                            break;
                        case 'companyshare':
                            this.$set(this.searchData, 'companyshare', 'true');
                            break;
                        case 'youyaoshi':
                            this.$set(this.searchData, 'is_yaoshi', 'true');
                            break;
                        case 'wuyaoshi':
                            this.$set(this.searchData, 'is_yaoshi', 'false');
                            break;
                        case 'dujia':
                            this.$set(this.searchData, 'dujia', 'true');
                            break;
                        case 'xuequfang':
                            this.$set(this.searchData, 'xuequfang', 'true');
                            break;
                        case 'fengpan':
                            this.$set(this.searchData, 'is_fengpan', 'true');
                            break;
                        case 'sanrixinshang':
                            this.$set(this.searchData, 'sanrixinshang', 'true');
                            break;
                        case 'jiqie':
                            this.$set(this.searchData, 'jiqie', 'true');
                            break;
                        case 'main':
                            this.$set(this.searchData, 'main', 'true');
                            break;
                        case 'daikan':
                            this.$set(this.searchData, 'daikan', 'true');
                            break;
                        case 'genjin':
                            this.$set(this.searchData, 'genjin', 'true');
                            break;
                    }
                }
                this.pageCurrent = 1;
                this.getIndex();
            },
            //搜索部门
            changeSearchDepart(value, selectedData) {
                this.searchData.departpath = value;
                let d_id = value[value.length - 1];
                this.users = this.settings.users[d_id];
                this.pageCurrent = 1;
                this.getIndex();
            },
            selectDaterange(date) {
                this.searchData.daterange = date;
                this.pageCurrent = 1;
                this.getIndex();
            },

            resetModal() {
                this.addHouseModal = false;
            },
            resetColumns() {
                this.tableColumns2 = this.getTable2Columns(2);
                this.$http.post(api_param.apiurl + 'ordersell/customcolumns',
                    {
                        'module': 5,
                        'columns': JSON.stringify(this.defaultColumnsChecked),
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.customList = false;
                        this.$Message.success('保存成功');
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    //console.log(response)
                })
            },
            saveColumns() {
                this.$http.post(api_param.apiurl + 'ordersell/customcolumns',
                    {
                        'module': 5,
                        'columns': JSON.stringify(this.tableColumnsChecked),
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.customList = false;
                        this.$Message.success('保存成功');
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    //console.log(response)
                })
            },


            changeDts(value, selectedData) {
                this.searchData.dts_id = value;
                let dts_id = selectedData[1].value;
                this.getDts(dts_id, 1);
            },
            getDts(dts_id, type) {
                if (dts_id) {
                    this.$http.get(api_param.apiurl + 'village/getvillage', {
                        params: {
                            dts_id: dts_id
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                        if (response.data.code == 200) {// 这里是处理正确的回调
                            if (type == 1) {
                                this.village = response.data.data.list;
                            } else if (type == 2) {
                                this.exportvillage = response.data.data.list;
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
                        //this.$Message.error('你的网络开小差了^—^');
                    })
                }

            }

        }
    };
</script>
