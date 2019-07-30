<style lang="less">
    @import "../customer-sell/customer.less";
</style>

<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row>
                <Col :lg="24" :md="24">
                <Row :gutter="5">
                    <Col :lg="2" :md="2">
                    <Cascader :data="this.peizhi.villages" v-model="xqqy" placeholder="区域选择" filterable
                              change-on-select @on-change="changeDts" ></Cascader>
                    </Col>
                    <Col :lg="2" :md="2">
                    <Select placeholder="小区选择" :transfer="true" v-model="xiaoqu">
                        <Option v-for="item in village" :value="item.village_id" :key="item.village_name">{{ item.village_name }}</Option>
                    </Select>
                    </Col>
                    <Col :lg="4" :md="4">
                    <Input v-model="ssk" placeholder="客户电话、客户编号、客户姓名" @on-change="clearb"></Input>
                    </Col>
                    <Col :lg="3" :md="3">
                    <Button type="primary" @click="searchCustomerList">搜索</Button>
                    <Button type="primary" @click="qkym"  id='clearb' v-bind:class='{clearb:active_b}' >清空</Button>
                    </Col>
                    <Col :lg="3" :md="3" offset="10">
                    <Row type="flex" justify="end">
                        <Col>
                        <Button type="primary" @click="add_customer">新增买卖客源</Button>
                        <customer-sell-add :peizhi="peizhi" :addHouse="addHouse" :formValidate="formValidate"
                                           v-on:resetModal="resetModal"></customer-sell-add>
                        </Col>
                    </Row>
                    </Col>
                </Row>
                </Col>
                <Col :lg="24" :md="24" class="margin-10px">
                <ul class="saleState">
                    <p>状态</p>　
                    <li v-for="(item,$index) in items" @click="selectStyle (item, $index)"
                        :class="{'state':item.active}">
                        {{item.select}}
                    </li>
                </ul>
                </Col>
                <Col :lg="24" :md="24" class="margin-10px">
                <Checkbox-group v-model="ssxz" @on-change="changessxz">
                    <Checkbox label="急切">急切</Checkbox>
                   <!-- <Checkbox label="封盘">封盘</Checkbox> -->
                    <Checkbox label="有跟进">有跟进</Checkbox>
                    <Checkbox label="有带看">有带看</Checkbox>
                    <Checkbox label="意向金">意向金</Checkbox>
                    <Checkbox label="7天未跟进" v-if="sszhuangtai=='有效(公客)' ||  sszhuangtai=='有效(私客)'">7天未跟进</Checkbox>
                    <Checkbox label="30天未带看">30天未带看</Checkbox>
                    <Checkbox label="学区">学区房</Checkbox>
                </Checkbox-group>
                </Col>
                <Col :lg="24" :md="24" class="margin-10px">
                <Row>
                    <Col :lg="2" :md="2" class='mr10'>
                    <Select v-model="formValidate.kehudengji" placeholder="客源等级" :transfer="true"  @on-change="cz" >
                       <!-- <Option v-for="(item,index) in peizhi.kehudengji" :value="item.value" :key="item.value">{{
                            label}}
                        </Option>	 -->
											
						<Option v-for="item in JSON.parse( this.peizhi.kehudengji[0].base_desp)" :value="item.child_name"
								:key="item.child_name">{{ item.child_name}}
						</Option>
											
                    </Select>
                    </Col>
                    <Col :lg="2" :md="2" class='mr10'>
                    <Select v-model="selJiaGe" placeholder="价格区间" @on-change="changeqjjg">
                        <Option v-for="item in xuqiujiage" :value="item.value" :key="item.value">{{ item.label }}</Option>
                    </Select>
                    </Col>
                    <Col :lg="2" :md="2" class='mr10'>
                    <Select v-model="mianji" placeholder="面积区间" @on-change="changemianji">
                        <Option v-for="item in xuqiumianji" :value="item.value" :key="item.value">{{ item.label }}</Option>
                    </Select>
                    </Col>
                    <Col :lg="2" :md="2" class='mr10'>
                    <Select v-model="formValidate.laiyuan" placeholder="来源" :transfer="true" @on-change="cz">
                        <Option v-for="item in kehulaiyuanList" :value="item.label" :key="item.label">
                            {{ item.label }}
                        </Option>
                    </Select>
                    </Col>
                    <Col :lg="2" :md="2" class='mr10'>
                    <!--<Button type="ghost" long class="saleMore" @click="saleMore = true">更多</Button>-->
                    </Col>
                    <Col :lg="2" :md="2" class='mr10'>
                    <Cascader :data="this.peizhi.benzu" v-if="this.peizhi.benzu" trigger="click" filterable change-on-select
                              v-model="sousuo.bumen" @on-change="changeDepart" placeholder="部门选择"></Cascader>
                    </Col>
                    <Col :lg="2" :md="2" class='mr10'>
                    <Select v-model="sousuo.user" v-if="this.peizhi.benzu" placeholder="" :transfer="true" placeholder="人员选择" @on-change="changeuser">
                        <Option v-for="v in sousuo.users" :value="v.u_id" :key="v.u_id">{{v.u_name}}
                        </Option>
                    </Select>
                    </Col>
                    <Col :lg="3" :md="3">
                    <DatePicker v-model="shijianq"  type="datetimerange" format="yyyy-MM-dd HH:mm" placeholder="起始时间" @on-change="changeshijian"></DatePicker>
                    </Col>
                </Row>
                </Col>
                <Col :lg="24" :md="24" class="margin-10px">
                <Row :gutter="10">
                    <Col :lg="12" :md="12">
                    <Button type="primary" @click="exportData" v-if="topbutton.sellexport == 1">导出客源</Button>
                    <Button type="primary" @click="alldel" v-if="topbutton.selldel == 1">客源删除</Button>
                    <!--<Button type="primary" @click="newZhuanyi = true">批量信息转移</Button>-->
                    <Modal v-model="exportHouse" title="导出客源" :mask-closable="false">
                        <div slot="header">
                            <a class="ivu-modal-close" @click="exportHouseCancel"><i
                                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                            <div class="ivu-modal-header-inner">导出客源</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="exportHouseCancel">取消</Button>
                            <Button type="primary" size="large" @click="exportHouseOk">导出</Button>
                        </div>
                        <Form ref="formExportValidate" :label-width="80">
                            <Row>
                                <Col :lg="24" :md="24">
                                <FormItem label="区域选择">
                                    <Cascader :data="this.peizhi.villages" v-model="formExportValidate.dts_id" placeholder="区域选择" filterable
                                              change-on-select :transfer="true"></Cascader>
                                </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <Col :lg="24" :md="24">
                                <FormItem label="价格区间">
                                    <Select v-model="formExportValidate.sell_jgqj" placeholder="价格区间" :transfer="true">
                                        <Option v-for="(item,index) in peizhi.jgqj" :value="item.min+'-'+item.max" :key="index">{{ item.child_name }}</Option>
                                    </Select>
                                </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <Col :lg="24" :md="24">
                                <FormItem label="面积区间">
                                    <Select v-model="formExportValidate.mjqj" placeholder="面积区间" :transfer="true">
                                        <Option v-for="(item,index) in peizhi.mjqj" :value="item.min+'-'+item.max" :key="index">{{ item.child_name }}</Option>
                                    </Select>
                                </FormItem>
                                </Col>
                            </Row>
                        </Form>

                    </Modal>

                    <Dropdown trigger="click"  @on-click='paixu'>
                        <Button type="primary">
                            <a href="javascript:void(0)" style="color: #fff"> 条件排序
                                <Icon type="arrow-down-b"></Icon>
                            </a>
                        </Button>
                        <DropdownMenu slot="list" >
                            <DropdownItem name="1">按跟进次数升序</DropdownItem>
                            <DropdownItem name="2">按跟进次数降序</DropdownItem>
                            <DropdownItem name="3">按带看次数升序</DropdownItem>
                            <DropdownItem name="4">按带看次数降序</DropdownItem>
                            <DropdownItem name="5">按录入时间升序</DropdownItem>
                            <DropdownItem name="6">按录入时间降序</DropdownItem>
                        </DropdownMenu>
                    </Dropdown>
                    </Col>
                    <Col :lg="12" :md="12">
                    <Row type="flex" justify="end">
                        <Col>
                        <Button type="ghost" @click="customList = true">自定义列表</Button>
                        <Modal v-model="customList" title="自定义列表">
                            <p slot="header">
                                <span>自定义列表</span>
                            </p>
                            <div class="CheckboxList">
                                <CheckboxGroup v-model="tableColumnsChecked" @on-change="changeTableColumns">
                                    <Checkbox label="biaoqian">标签</Checkbox>
                                    <Checkbox label="xuqiubianhao">客户编号</Checkbox>
                                    <Checkbox label="zhuangtai">状态</Checkbox>
                                    <Checkbox label="xuqiuyongtu"> 需求用途</Checkbox>
                                    <Checkbox label="kehuxingming">客户姓名</Checkbox>
                                    <Checkbox label="kehulaiyuan">客户来源</Checkbox>
                                    <Checkbox label="xuqiuquyu">需求区域</Checkbox>
                                    <Checkbox label="mianji">需求面积</Checkbox>
                                    <Checkbox label="jiage">需求价格</Checkbox>
                                    <Checkbox label="huxing">房屋户型</Checkbox>									
                                    <Checkbox label="goutongjieduan">沟通阶段</Checkbox>
                                    <Checkbox label="beizhu">备注</Checkbox>
                                    <Checkbox label="dengji">客源等级</Checkbox>
                                    <Checkbox label="lururiqi">录入日期</Checkbox>
                                    <Checkbox label="quanyuangenjin">全员最后跟进</Checkbox>
                                    <Checkbox label="zuihoudaikan">最后带看时间</Checkbox>
                                    <Checkbox label="weihurengenjin">维护人最后跟进</Checkbox>
                                    <Checkbox label="weihuren">维护人</Checkbox>
                                    <Checkbox label="bumen">部门</Checkbox>
                                </CheckboxGroup>
                            </div>
                            <div slot="footer">
                                <Row type="flex" justify="space-between">
                                    <Button type="ghost" size="large" @click="resetColumns">恢复默认</Button>
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
            <Row>
                <Col>
                <Table :data="tableData2" :columns="tableColumns2" border   @on-selection-change="selectionok"  ></Table>
                <div style="float: right;">
                    <Page :total="totalnum1" :current="currentpage1" @on-change="changePage1" :page-size="pageSize"
                          show-total>
                    </Page>
                </div>
                </Col>
            </Row>
        </Card>
        </Col>
    </Row>
</template>

<script>
    import Vue from 'vue';
    import customerSellAdd from '../customer-sell/customerSellAdd';
    import Cookies from 'js-cookie';

    export default {
        name: 'customerSell_index',
        components: {
            customerSellAdd,
        },
        data() {
            return {
				active_b:false,
                searchData:{},
                pageSize:10,
                ssxz:[],
                /* xqqy:[Cookies.get('dts_id')], */
				xqqy:'',
                xiaoqu:'',
                ssk:'',
                village:[],
                shijian:[],
                shijianq:[],
                mianji:'',
                currentpage1:1,
                kehulaiyuan:'',
                village_id:'',
                village_name:'',
                saleMore:false,//更多信息
                keyuanZhuanyi: false,//客源信息批量转移
                addHouse: false,  //添加房源
                customList: false,
                exportHouse:false, //导出选择框
                formExportValidate:{
                    dts_id:[],
                    aid:'',
                    vid:'',
                    sell_jgqj:'',
                    mjqj:'',
                },
                exportUrl:api_param.apiurl + 'customer/export?type=0&token='+api_param.XAccessToken,
				exportUrl1:api_param.apiurl + 'customer/exportn?type=0&token='+api_param.XAccessToken,
//                状态
                active: false,
                peizhi: [],
                items: [
                    {select: '不限'},
                    {select: '有效(全部)'},
                    {select: '有效(公客)'},
                    {select: '有效(私客)'},
                    {select: '无效'},                    
                    {select: '成交'},
                ],
//                均价时间选择
                saleTime: false,
                animal: '三个月',
                xuqiumianji: [],
                xuqiujiage: [],
                huxingList: [],
                loucengList: [],
                fanglingList:[],
                chaoxiangList:[],
                zhuangxiuList:[],
                peitaoList:[],
                kehulaiyuanList:[],
                selJiaGe: '',
                sszhuangtai:'',
//                价格区间
                cityList: [
                    {
                        value: 'New York',
                        label: '0-60万'
                    },
                    {
                        value: 'London',
                        label: '60-80万'
                    },
                    {
                        value: 'Sydney',
                        label: '80-100万'
                    }
                ],
                model1: '',
//                表格
                formValidate: {
                    title: '新增买卖客源',
                    sszhuangtai:'',
                    customer_private: '',
                    customer_name: '',
                    customer_type: '',
                    customer_sex: '',
                    tiaojianbiaoqian: [],
                    xuqiubianhao: '',
                    xuqiuquyu: [],
                    dengji: '',
                    village:'',
                    duoxuanbiaoqian: [],
                    yongtu: '',
                    xuqiuhuxing: '',
                    xuqiuhuxing_min: '',
                    xuqiuhuxing_max: '',
                    xuqiujiage: '',
                    xuqiujiage_min: '',
                    xuqiujiage_max: '',
                    xuqiumianji: '',
                    xuqiumianji_min: '',
                    xuqiumianji_max: '',
                    xuqiulouceng: '',
                    xuqiulouceng_min: '',
                    xuqiulouceng_max: '',
                    daikancishu: [],
                    xuqiufangling: '',
                    xuqiufangling_min: '',
                    xuqiufangling_max: '',
                    chaoxiang: '',
                    zhuangxiu: '',
                    peitao: [],
                    xuqiuyuanying: '',
                    xuqiu_tag: '',
                    fangwuleixing: '',
                    goutongjieduan: '',
                    kehulaiyuan: '',
                    xiaofeilinian: [],
                    guoji: '',
                    minzu: '',
                    zhengjianhaoma: '',
                    email: '',
                    qq: '',
                    weixin: '',
                    jiaotonggongju: '',
                    chexing: '',
                    mark: '',
					laiyuan: '',
					kehudengji:'',					
                    core_mark: '',
                    zhuangtai: '',
                    tel_phone: '',
                    tel_type: '',
                },
                ruleValidate:{},
                sousuo:{
                    kydj:'',
                    xuqiujiage_min:'',
                    xuqiujiage_max:'',
                    xuqiumianji_min:'',
                    xuqiumianji_max:'',
                    xuqiuhuxing_min:'',
                    xuqiuhuxing_max:'',
                    xuqiulouceng_min:'',
                    xuqiulouceng_max:'',
                    xuqiufangling_min:'',
                    xuqiufangling_max:'',
                    bumen:[],
                    users:[],
                    user:'',
                    d_id:'',
                    huxing:'',
                    louceng:'',
                    fangling:'',
                    bmyhlx:'',
                    paixu:'',
                },
                totalnum1:0,
                tableData2: [],
                topbutton:[],
                // 表格属性
                tableColumns2: [],
                tableColumnsChecked:
                    [ 'biaoqian','xuqiubianhao', 'zhuangtai', 'xuqiuyongtu', 'kehuxingming', 'kehulaiyuan', 'xuqiuquyu', 'mianji', 'jiage', 'huxing',
                        'goutongjieduan', 'beizhu', 'dengji', 'lururiqi', 'quanyuangenjin', 'zuihoudaikan', 'weihurengenjin',
                        'weihuren', 'bumen','lururen'],
                defaultColumnsChecked:
                    [ 'biaoqian','xuqiubianhao', 'zhuangtai', 'xuqiuyongtu', 'kehuxingming', 'kehulaiyuan', 'xuqiuquyu', 'mianji', 'jiage', 'huxing',
                        'goutongjieduan', 'beizhu', 'dengji', 'lururiqi', 'quanyuangenjin', 'zuihoudaikan', 'weihurengenjin',
                        'weihuren', 'bumen','lururen'],

            };
        },
        //保存之前的搜索条件
        // beforeRouteEnter(to, from, next){
        //     let customerSell_index = window.localStorage.getItem('customerSell_index');
        //     if(to.name != from.name && from.name !== null && customerSell_index !== null){
        //         next(vm => {
        //             vm.searchData = JSON.parse(customerSell_index);
        //             //vm.currentpage1 = vm.searchData.page;
        //             vm.getIndex(2);
        //         });
        //     }else {
        //         next(vm=>{
        //             window.localStorage.removeItem('customerSell_index');
        //     });
        //     }
        // },
        created: function () {
            //this.xqqy= [Cookies.get('area_id'),Cookies.get('dts_id')];
            this.getIndex(1);			
			//let xiaoq=[{'value':Cookies.get('dts_id')},{'value':Cookies.get('dts_id')}];
			let valuearea=[Cookies.get('dts_id')];
			this.changeDts(valuearea,xiaoq);
        },
        methods: {
			clearb(){
				console.log(this.ssk);
				if(this.ssk!=""){
						this.active_b=false;		
				}else{					
					if(this.xqqy.length==0){
						console.log('aaaaaa');
						this.active_b=true;
					}else{
						this.active_b=false;	
					}		
				}				
			},
            cz(){
                this.currentpage1=1;
                this.getIndex(1);
            },
//                状态
            selectionok (selection) {
                this.selection = selection;
                // console.log(this.selection);
            },
            alldel(){
                this.$http.post(api_param.apiurl + 'customer/alldel',
                    {
                        'selection': this.selection,
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.selection=[];
                        this.$Message.success(response.data.message);
                        this.getIndex(1);
                    }else if(response.data.code == 400){
                        this.$Message.success(response.data.message);
                    }else if (response.data.code == 401) {
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
            paixu(name){
                this.sousuo.paixu=name;
                this.currentpage1=1;
                this.getIndex(1);
            },
            resetColumns(){
                this.tableColumns2 = this.getTable2Columns(2);
                this.$http.post(api_param.apiurl + 'ordersell/customcolumns',
                    {
                        'module': 7,
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
                        'module': 7,
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
                    console.log(response)
                })
            },
            selectStyle(item, index) {
                this.$nextTick(function () {
                    this.items.forEach(function (item) {
                        Vue.set(item, 'active', false);
                    });
                    Vue.set(item, 'active', true);
                    this.sszhuangtai=item.select;
                    this.currentpage1 =1;
                    this.getIndex(1);
                });
            }, changeDepart (value, selectedData) {
                this.sousuo.bumen = selectedData;
                this.sousuo.d_id = value.pop();
                this.sousuo.users = this.peizhi.users[this.sousuo.d_id];
                this.currentpage1 =1;
                this.getIndex(1);
            },changeqjjg(value,label){
                var arr = value.split('-');
                this.sousuo.xuqiujiage_min=arr[0];
                this.sousuo.xuqiujiage_max=arr[1];
                this.currentpage1 =1;
                this.getIndex(1);
            },changehuxing(value,label){
                var arr = value.split('-');
                this.sousuo.xuqiuhuxing_min=arr[0];
                this.sousuo.xuqiuhuxing_max=arr[1];
                this.currentpage1 =1;
                this.getIndex(1);
            },changelouceng(value,label){
                var arr = value.split('-');
                this.sousuo.xuqiulouceng_min=arr[0];
                this.sousuo.xuqiulouceng_max=arr[1];
                this.currentpage1 =1;
                this.getIndex(1);
            },changebmyhlx(value,label){
                this.currentpage1 =1;
                this.getIndex(1);
            },changefangling(value,label){
                var arr = value.split('-');
                this.sousuo.xuqiufangling_min=arr[0];
                this.sousuo.xuqiufangling_max=arr[1];
                this.currentpage1 =1;
                this.getIndex(1);
            },changeuser(value,label){
                this.currentpage1 =1;
                this.getIndex(1);
            },changeshijian(value,label){
                this.currentpage1 =1;
                this.shijian=value;
                this.getIndex(1);
            },changemianji(value,label){
                var arr = value.split('-');
                this.sousuo.xuqiumianji_min=arr[0];
                this.sousuo.xuqiumianji_max=arr[1];
                this.currentpage1=1;
                this.getIndex(1);
            },qkym(){
				this.active_b=true;
                this.items.forEach(function (item) {
                    Vue.set(item, 'active', false);
                });
                this.sszhuangtai='';
                this.ssxz=[];
                this.ssk='';
                this.xiaoqu='';
                this.xqqy='';
                this.sousuo=[];
                this.shijian=[];
                this.selJiaGe='';
                this.shijianq=[];
				this.village=[];
                this.mianji='';
                this.formValidate.laiyuan='';
                this.currentpage1=1;
				this.formValidate.kehudengji='';
                this.getIndex(1);
            },
            //    列表分页
            changePage1(page) {
                this.currentpage1 = page;
                this.getIndex(1);
            },
            add_customer() {
                this.addHouse = true;
            }, resetModal() {
                this.addHouse = false;
                this.getIndex(1);
            },
            searchCustomerList() {
               this.getIndex(1);
            },
            getIndex(type) {
                if(type == 1){
                    this.searchData = {
                        page: this.currentpage1,
                        customer_type: 0,
                        sszhuangtai:this.sszhuangtai,
                        ssxz:this.ssxz,
                        xqqy:this.xqqy,
                        xiaoqu:this.xiaoqu,
                        ssk:this.ssk,
                        kydj:this.sousuo.kydj,
                        xuqiujiage_min:this.sousuo.xuqiujiage_min,
                        xuqiujiage_max:this.sousuo.xuqiujiage_max,
                        xuqiumianji_min:this.sousuo.xuqiumianji_min,
                        xuqiumianji_max:this.sousuo.xuqiumianji_max,
                        d_id:this.sousuo.d_id,
                        user:this.sousuo.user,
                        shijian:this.shijian,
                        bmyhlx:this.sousuo.bmyhlx,
                        xuqiuhuxing_min:this.sousuo.xuqiuhuxing_min,
                        xuqiuhuxing_max:this.sousuo.xuqiuhuxing_max,
                        xuqiulouceng_min:this.sousuo.xuqiulouceng_min,
                        xuqiulouceng_max:this.sousuo.xuqiulouceng_max,
                        xuqiufangling_min:this.sousuo.xuqiufangling_min,
                        xuqiufangling_max:this.sousuo.xuqiufangling_max,
                        laiyuan:this.formValidate.laiyuan,
						kehudengji:this.formValidate.kehudengji,
                        paixu:this.sousuo.paixu
                    }
                }
                this.$http.get(api_param.apiurl + '/customer/index',
                    {
                        params: this.searchData,
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum1 = parseInt(response.body.data.count);
                        this.tableData2 = response.body.data.list;
                        this.topbutton = response.body.data.topbutton;
                        window.localStorage.setItem('customerSell_index', JSON.stringify(this.searchData));
                        if(this.peizhi.length==0){
                            this.peizhi = response.body.data.peizhi;
                            // console.log(this.peizhi);
                        }

                        if(response.data.data.customcolumns!=null){
                            if(response.data.data.customcolumns[7] && response.data.data.customcolumns[7] != null){
                                this.tableColumnsChecked = response.data.data.customcolumns[7];
								this.tableColumnsChecked.push('lururen');
                            }
                            this.changeTableColumns();
                        }

                        this.formValidate.customer_sex=JSON.parse(this.peizhi.customer_sex[0].base_desp)[0].child_name;
                        this.formValidate.tel_type=JSON.parse(this.peizhi.dianhuasuoshu[0].base_desp)[0].child_name;
                        // 面积区间
                        this.xuqiumianji=[];
                        let xuqiumianji = JSON.parse(response.body.data.peizhi.xuqiumianji[0].qujian_desp);
                        if(xuqiumianji.length > 0){
                            for(var i in xuqiumianji){
                                this.xuqiumianji.push({
                                   'label': xuqiumianji[i].child_name,
                                   'value': xuqiumianji[i].min + '-' + xuqiumianji[i].max
                                });
                            }
                        }
                        this.xuqiujiage=[];
                        // 价格区间
                        let xuqiujiage = JSON.parse(response.body.data.peizhi.xuqiujiage[0].qujian_desp);
                        if(xuqiujiage.length > 0){
                            for(var i in xuqiujiage){
                                this.xuqiujiage.push({
                                   'label': xuqiujiage[i].child_name,
                                   'value': xuqiujiage[i].min + '-' + xuqiujiage[i].max
                                });
                            }
                        }
                        // 户型
                        this.huxingList=[];
                        let huxingList = JSON.parse(response.body.data.peizhi.huxing[0].qujian_desp);
                        if(huxingList.length > 0){
                            for(var i in huxingList){
                                this.huxingList.push({
                                   'label': huxingList[i].child_name,
                                   'value': huxingList[i].min + '-' + huxingList[i].max
                                });
                            }
                        }

                        // 楼层
                        this.loucengList=[];
                        let loucengList = JSON.parse(response.body.data.peizhi.louceng[0].qujian_desp);
                        if(loucengList.length > 0){
                            for(var i in loucengList){
                                this.loucengList.push({
                                   'label': loucengList[i].child_name,
                                   'value': loucengList[i].min + '-' + loucengList[i].max
                                });
                            }
                        }

                        // 朝向
                        this.chaoxiangList=[];
                        let chaoxiangList = JSON.parse(response.body.data.peizhi.chaoxiang[0].base_desp);
                        if(chaoxiangList.length > 0){
                            for(var i in chaoxiangList){
                                this.chaoxiangList.push({
                                   'label': chaoxiangList[i].child_name
                                });
                            }
                        }

                        // 装修
                        this.zhuangxiuList=[];
                        let zhuangxiuList = JSON.parse(response.body.data.peizhi.zhuangxiu[0].base_desp);
                        if(zhuangxiuList.length > 0){
                            for(var i in zhuangxiuList){
                                this.zhuangxiuList.push({
                                   'label': zhuangxiuList[i].child_name
                                });
                            }
                        }

                        // 客户来源
                        this.kehulaiyuanList=[];
                        let kehulaiyuanList = JSON.parse(response.body.data.peizhi.kehulaiyuan[0].base_desp);
                        if(kehulaiyuanList.length > 0){
                            for(var i in kehulaiyuanList){
                                this.kehulaiyuanList.push({
                                   'label': kehulaiyuanList[i].child_name
                                });
                            }
                        }

                        // 配套
                        this.peitaoList=[];
                        let peitaoList = JSON.parse(response.body.data.peizhi.peitao[0].base_desp);
                        if(peitaoList.length > 0){
                            for(var i in peitaoList){
                                this.peitaoList.push({
                                   'label': peitaoList[i].child_name
                                });
                            }
                        }

                        // 房龄
                        this.fanglingList=[];
                        let fanglingList = JSON.parse(response.body.data.peizhi.fangling[0].qujian_desp);
                        if(fanglingList.length > 0){
                            for(var i in fanglingList){
                                this.fanglingList.push({
                                   'label': fanglingList[i].child_name,
                                   'value': fanglingList[i].min + '-' + loucengList[i].max
                                });
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
                    // console.log(response);
                });
            },
            // 表格
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
                        align:'center',
                        render: (h, params) => {
                            let color=['blue','red','yellow','green','orange'];

                            let ret = [];
                            if (params.row.biaoqian.duoxuanbiaoqian) {
                                for(var i=0;i<params.row.biaoqian.duoxuanbiaoqian.length;i++){
                                    // let index = Math.floor((Math.random()*color.length));
                                    if(params.row.biaoqian.duoxuanbiaoqian[i]){
                                        ret.push(h('Tag', {props: {color:color[i] }}, params.row.biaoqian.duoxuanbiaoqian[i].substr(0,1)));
                                    }
                                }
                            }
                            if (params.row.biaoqian.genjincishu) {ret.push(h('Tag', {props: {color: 'green'}}, '跟('+params.row.biaoqian.genjincishu+')'))}
                            if (params.row.biaoqian.daikancishu) {ret.push(h('Tag', {props: {color: 'yellow'}}, '带('+params.row.biaoqian.daikancishu+')'))}
                            if (params.row.biaoqian.xiading) {ret.push(h('Tag', {props: {color: 'red'}}, '定'))}
                            if (params.row.biaoqian.is_fengpan) {ret.push(h('Tag', {props: {color: 'green'}}, '封'))}
                            return h('div', ret)
                        }
                    },
                    xuqiubianhao: {
                        title: '客源编号',
                        key: 'xuqiubianhao',
                        width: 150,
                        fixed: 'left',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('div', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    style: {
                                        color: '#2d8cf0',
                                        textDecoration: 'underline',
                                        cursor: 'pointer',
                                    },
                                    domProps: {
                                        innerHTML: params.row.xuqiubianhao,
                                    },
                                    on: {
                                        click: () => {
                                            if(params.row.customer_uuid){
                                                let argu = {customer_uuid: params.row.customer_uuid,customer_type:0};
                                                this.$router.push({
                                                    name: 'customerEtails',
                                                    params: argu
                                                });
                                            }else{
                                                this.$Message.warning('参数错误');
                                            }
                                        }
                                    }
                                })
                            ]);
                        }
                    },
                    zhuangtai: {
                        title: '状态',
                        key: 'zhuangtai',
                        width: 88,
                        align: 'center'
                    },
                    xuqiuyongtu: {
                        title: '需求用途',
                        key: 'yongtu',
                        width: 88,
                        align: 'center'
                    },
                    kehuxingming: {
                        title: '客户姓名',
                        key: 'customer_name',
                        width: 88,
                        align: 'center'
                    },
                    kehulaiyuan: {
                        title: '客户来源',
                        key: 'kehulaiyuan',
                        width: 88,
                        align: 'center'
                    },
                    xuqiuquyu: {
                        title: '需求区域',
                        key: 'xuqiuquyu',
                        width: 150,
                        align: 'center'
                    },
                    mianji: {
                        title: '面积',
                        key: 'mianji',
                        width: 88,
                        sortable: true,
                        align: 'center'
                    },
                    jiage: {
                        title: '价格',
                        key: 'jiage',
                        width: 88,
                        sortable: true,
                        align: 'center'
                    },
                    huxing: {
                        title: '户型',
                        key: 'huxing',
                        width: 88,
                        align: 'center'
                    },
                    goutongjieduan: {
                        title: '沟通阶段',
                        key: 'goutongjieduan',
                        width: 88,
                        align: 'center'
                    },
                    beizhu: {
                        title: '备注',
                        key: 'mark',
                        width: 148,
                        align: 'center'
                    },
                    dengji: {
                        title: '等级',
                        key: 'kehudengji',
                        width: 88,
                        align: 'center'
                    },
                    lururiqi: {
                        title: '录入日期',
                        key: 'ctime',
                        width: 128,
                        sortable: true,
                        align: 'center'
                    },
                    quanyuangenjin: {
                        title: '全员最后跟进',
                        key: 'quanyuangenjin',
                        width: 128,
                        sortable: true,
                        align: 'center'
                    },
                    zuihoudaikan: {
                        title: '最后带看时间',
                        key: 'daikanshijian',
                        width: 128,
                        sortable: true,
                        align: 'center'
                    },
                    weihurengenjin: {
                        title: '维护人最后跟进',
                        key: 'weihurengenjin',
                        width: 128,
                        sortable: true,
                        align: 'center'
                    },
                    weihuren: {
                        title: '维护人',
                        key: 'weihuren',
                        width: 88,
                        align: 'center'
                    },
                    bumen: {
                        title: '部门',
                        key: 'bumen',
                        width: 88,
                        align: 'center'
                    },
					lururen: {
					    title: '录入人',
					    key: 'lururen',
					    width: 88,
					    align: 'center'
					},
                };

                let data = [table2ColumnList.index];

                if(type == 1){
                    this.tableColumnsChecked.forEach(col => data.push(table2ColumnList[col]));
                }else{
                    this.tableColumnsChecked = this.defaultColumnsChecked;					
                    this.defaultColumnsChecked.forEach(col => {
                        data.push(table2ColumnList[col])
                    });					
                }
				console.log(this.tableColumnsChecked);
				console.log(this.defaultColumnsChecked);
                return data;
            },
            changeTableColumns() {
                this.tableColumns2 = this.getTable2Columns(1);
            },
            changeDts(value, selectedData){
				if(value.length==0){
					this.xiaoqu='';
					if(this.ssk==''){
						this.active_b=true;	
					}else{
						this.active_b=false;	
					}					
					return false;
				}
				this.active_b=false;
                let dts_id = selectedData[1].value;
                this.$http.get(api_param.apiurl + 'village/getvillage', {
                    params: {
                        dts_id: dts_id
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调
                        this.xiaoqu=''
                        this.village = response.data.data.list;
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
            changessxz() {
                this.currentpage1=1;
                this.getIndex(1);
            },
            toggleFav(index) {
                this.tableData2[index].fav = this.tableData2[index].fav === 0 ? 1 : 0;
            },
            //导出操作
            exportData(){	
				
				this.searchData = {
				    page: this.currentpage1,
				    customer_type: 0,
				    sszhuangtai:this.sszhuangtai,
				    ssxz:this.ssxz,
				    xqqy:this.xqqy,
				    xiaoqu:this.xiaoqu,
				    ssk:this.ssk,
				    kydj:this.sousuo.kydj,
				    xuqiujiage_min:this.sousuo.xuqiujiage_min,
				    xuqiujiage_max:this.sousuo.xuqiujiage_max,
				    xuqiumianji_min:this.sousuo.xuqiumianji_min,
				    xuqiumianji_max:this.sousuo.xuqiumianji_max,
				    d_id:this.sousuo.d_id,
				    user:this.sousuo.user,
				    shijian:this.shijian,
				    bmyhlx:this.sousuo.bmyhlx,
				    xuqiuhuxing_min:this.sousuo.xuqiuhuxing_min,
				    xuqiuhuxing_max:this.sousuo.xuqiuhuxing_max,
				    xuqiulouceng_min:this.sousuo.xuqiulouceng_min,
				    xuqiulouceng_max:this.sousuo.xuqiulouceng_max,
				    xuqiufangling_min:this.sousuo.xuqiufangling_min,
				    xuqiufangling_max:this.sousuo.xuqiufangling_max,
				    laiyuan:this.formValidate.laiyuan,
					kehudengji:this.formValidate.kehudengji,
				    paixu:this.sousuo.paixu
				}
				 let url= this.exportUrl1;				
				for (let i in this.searchData) {
				    url += '&'+i+'='+this.searchData[i];
				}
				console.log(url);
				//window.open(url,'_blank');
				//window.location.href=this.exportUrl1+"&params="+aaa;
				/* this.$http.get(api_param.apiurl + 'customer/exportn', {
				    params:this.searchData ,
				    headers: {'X-Access-Token': api_param.XAccessToken}
				}).then(function (response) {
				   /* if (response.data.code == 200) {// 这里是处理正确的回调
				        this.xiaoqu=''
				        this.village = response.data.data.list;
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
				})		 */	
            },
            exportHouseCancel(){
                this.exportHouse = false;
            },
            exportHouseOk(){
                console.log(this.formExportValidate.dts_id);
                let length = this.formExportValidate.dts_id.length;
                if(length == 1){
                    this.formExportValidate.aid = this.formExportValidate.dts_id[0];
                }else if(length == 2){
                    this.formExportValidate.aid = this.formExportValidate.dts_id[0];
                    this.formExportValidate.vid = this.formExportValidate.dts_id[1];
                }
                window.location.href=this.exportUrl+'&aid='+this.formExportValidate.aid+'&vid='+this.formExportValidate.vid+'&sprice='+this.formExportValidate.sell_jgqj+'&area='+this.formExportValidate.mjqj;
                this.exportHouse = false;
            }
        },
        mounted() {
            this.changeTableColumns();
        }
    };
</script>
