<style lang="less">
    @import "../../styles/common.less";
    @import "home.less";
    .loading {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }


    .bigform .imgbox {
        width: 85% !important;
        margin: 0 auto;
    }

    .bigform .imgbox img {
        width: 100%;
    }

    .bigform .textbox {
        width: 90% !important;
        margin: 10px auto;
    }

    .bigform .ivu-modal-body {
        max-height: 560px;
        overflow-y: auto;
    }

    .bigform .ivu-modal-footer {
        border: 0 !important;
        padding: 12px 18px;
    }
	.example-text .ivu-card-body{
		padding: 10px !important
	}
	.Carousel .ivu-carousel-item{
		width: 100%;
		height: 200px !important
	}
	.Carousel .ivu-carousel-item img{
		width: 100%;
		height: 100%;
		display: block
	}
	.gonggaomain .ivu-table-body{
		height: 168px;
		overflow-y: auto
	}
</style>

<template>
    <div class="home-main">
        <Row :gutter="10">
            <Col :lg="3" :md="3">
                <Modal v-model="bigform" width="960" class="bigform" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="modalCancel" style="display: block!important"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">{{notice.title}}</div>
                    </div>
                    <div class="imgbox"><img :src="notice.image"></div>
                    <div class="textbox" v-html="notice.content">
                    </div>
                    <div slot="footer">

                    </div>
                </Modal>

            </Col>
        </Row>
        <Row :gutter="5" class="margin-top-5">
            <Col :md="16" :lg="16">
				<Row>
					<Col :md="24" :lg="24">
						<Row :gutter="10">
						    <Col :md="8" :lg="8">
						        <Card>
						            <p slot="title">
						                <i class="ivu-icon ivu-icon-icon iconfont icon-fangyuanzhuangtai"></i>
						                房源
						            </p>
						            <Row>
						                <Col :lg="3" :md="3">
						                    <p style="font-size:24px;margin-top:10px;font-weight: bold;">{{monthmount.csHouseCount}}</p>
						                    <p style="font-size:16px;color:#666;width: 175px;margin-top:10px;">当月出售房源统计</p>
						                </Col>
						            </Row>
						        </Card>
						    </Col>
							<Col :md="8" :lg="8">
								<Card>
								    <p slot="title">
								        <Icon type="ios-people-outline" ></Icon>
								        客源
								    </p>
								    <Row>
								        <Col :lg="3" :md="3" >
								            <p style="font-size:24px;margin-top:10px;font-weight: bold;">{{monthmount.mmCustCount}}</p>
								            <p style="font-size:16px;color:#666;width: 175px;margin-top:10px;">当月买卖客源统计</p>
								        </Col>
								    </Row>
								</Card>
							</Col>
							<Col :md="8" :lg="8">
								<Card>
								    <p slot="title">
								        <i class="ivu-icon ivu-icon-icon iconfont icon-qian1"></i>
								        成交
								    </p>
								    <Row>
								        <Col :lg="3" :md="3" >
								            <p style="font-size:24px;margin-top:10px;font-weight: bold;">{{monthmount.mmOrderSellCount}}</p>
								            <p style="font-size:16px;color:#666;width: 175px;margin-top:10px;">当月成交金额统计</p>
								        </Col>
								    </Row>
								</Card>
							</Col>
						</Row>
					</Col>
					<Col :md="24" :lg="24">
						<Card style="margin-top:10px;">
							<p slot="title" class="card-title">
								<Icon type="arrow-graph-up-left"></Icon>
								我的简报
							</p>
					            <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="60" slot="extra" style="width: 680px;">
									<FormItem  prop="city" style="margin-right: 50px;">
										<Row :gutter="5">
									        <Col :lg="7" :md="7">
									            <Cascader :data="departData" trigger="click"
									                      v-model="sousuo.bumen" @on-change="changeDepart" :clearable="false"
									                      placeholder="部门选择" change-on-select></Cascader>
									        </Col>
									        <Col :lg="7" :md="7">
									            <Select v-model="sousuo.user"  :transfer="true" placeholder="人员选择"
									                    :clearable="true">
									                <Option v-for="v in sousuo.users" :value="v.u_id" :key="v.u_id">{{v.u_name}}
									                </Option>
									            </Select>
									        </Col>
									        <Col :lg="8" :md="8">
									            <FormItem  prop="city">
									                <DatePicker type="daterange" show-week-numbers placement="bottom-end"
												
									                            placeholder="日期选择" :value="defaultDaterange" style="width: 100%"
									                            @on-change="changeDaterange"></DatePicker>
									            </FormItem>
									        </Col>
									        <Col :lg="2" :md="2">
									            <Button type="success" @click="searchAddmount">查询</Button>
									        </Col>
									    </Row>
									</FormItem>
								</Form>
							<Row type="flex" justify="space-between" class="example-text">
								<Col :lg="24" :md="24">
									<Card shadow>
										<example :seriesdata="series" style="height: 316px;"/>
									</Card>
								</Col>
							</Row>
						</Card>
					</Col>
				</Row>
			</Col>
            <Col :lg="8" :md="8" class="homeLists">
                <Row>
                    <Col :lg="24" :md="24">
                        <Card>
                            <p slot="title" class="card-title">
                                <Icon type="flash"></Icon>
                                公司快讯<!--{{ loadingType }}-->
                            </p>
                            <a href="#" slot="extra" @click="openRouter('gongsikuaixun')">
                                <Icon type="ios-loop-strong"></Icon>
                                更多
                            </a>

                            <Carousel :autoplay="autoplay" v-model="value2" class="Carousel">
                                <CarouselItem v-for="item in this.newsTopData">
                                    <img :src="item.src" @click="testclick(item)" @mouseover="testmouseover" @mouseout="testmouseout">
                                </CarouselItem>
                            </Carousel>
                        </Card>
                    </Col>
                    <Col :lg="24" :md="24" style="margin-top: 10px" class="gonggaomain">
                        <Card>
                            <p slot="title" class="card-title">
                                <Icon type="volume-high"></Icon>
                                公司公告
                            </p>
                            <a href="#" slot="extra" @click="openRouter('gongsigonggao')">
                                <Icon type="ios-loop-strong"></Icon>
                                更多
                            </a>
                            <Table :columns="noticeColumns" :data="noticeData" border :height="height" height="208"></Table>
                            <Modal v-model="xiangqing2" width="960px" :title='xiangqing2title'>
                                <div slot="footer">
                                    <Button type="primary" size="large" @click="closexiangqing2">关闭</Button>
                                </div>
                                <Row :gutter="40">
                                    <Col :lg="24" :md="24">
                                        <div v-html="xiangqing2cont" style="padding: 0 15px"></div>
                                    </Col>
                                </Row>
                            </Modal>
                        </Card>
                    </Col>
                </Row>
            </Col>
            <!--详情弹窗-->
            <Modal v-model="xiangqing" width="960px" title='快讯详情'>
                <div slot="footer">
                    <Button type="primary" size="large" @click="closexiangqing">关闭</Button>
                </div>
                <Row>
                    <Col :lg="24" :md="24">
                        <strong v-html="xiangqingtitle" style="font-size: 20px;"></strong>
                        <p v-html="ctime" style="margin-top: 5px"></p>
                    </Col>
                    <Col :lg="12" :md="12">
                        <div style="border-radius: 3px;border: #f5f5f5 1px solid;overflow:hidden;margin: 0 15px">
                            <img style="display: block;width: 100%" :src='xiangqingImg'/>
                        </div>
                    </Col>
                    <Col :lg="12" :md="12">
                        <div v-html="xiangqingcont"></div>
                    </Col>
                </Row>
            </Modal>
        </Row>
        <Row :gutter="5" class="margin-top-10 homeTab">
            <Col :lg="12" :md="12">
				<Card>
					<p slot="title" class="card-title">
						<Icon type="android-home"></Icon>
						主推房源
					</p>
					<a href="#" slot="extra" >
						<Cascader :data="this.fyxqqyList" v-model="fyxqqy" placeholder="区域选择" @on-change="changeFyXqqy" change-on-select></Cascader>
					</a>
					<Table :columns="csFyColumns" :data="csFyData" border script :height='height' height="280"></Table>
				</Card>
            </Col>
            <Col :lg="12" :md="12">
				<Card>
					<p slot="title" class="card-title">
						<Icon type="person-add"></Icon>
						主推客源
					</p>
					<a href="#" slot="extra">
						<Cascader :data="this.kyxqqyList" v-model="kyxqqy" placeholder="需求区域" @on-change="changeKyXqqy" change-on-select></Cascader>
					</a>
					<Table :columns="mmkyColumns" :data="mmkyData" border script :height='height' height="280"></Table>
				</Card>
            </Col>
        </Row>
	</div>
</template>

<script>
    import Cookies from 'js-cookie';
    import inforCard from './components/inforCard.vue';
    import CountTo from '../my-components/count-to/CountTo.vue';
    //import countUp from './components/countUp.vue';
    import roomDetails from '../second-house/component/roomdetails.vue';
    //import customerEtails from '../customer/component/customerEtails.vue';
    import Example from './example.vue';
    export default {
        name: 'home',
        components: {
            inforCard,
            //countUp,
            roomDetails,
            CountTo,
            Example,
        },
        data() {
            return {
            	monthmount:{
					csHouseCount:'',
					mmCustCount:'',
					mmOrderSellCount:''
				},
                series:{
                    fyseries:[],
                    kyseries:[],
                    cjseries:[],
					keyseries:[],
					dujiaseries:[],
                    legend:[],
                    days:[],
                },
                // legend:[],
                // days:[],
                bigform: false,
                loadingFromRouter: '',
                length:'',
                noticeList:'',
                closetime:0,
                notice:{
                    title:'',
                    image:'',
                    content:''
                },
                houseCount: 0,
                czHouseCount: 0,
                csHouseCount: 0,
                gdHouseCount: 0,
                ysHouseCount: 0,
                djHouseCount: 0,
                ysdjhouseCount: 0,

                custCount: 0,
                mmCustCount: 0,
                zlCustCount: 0,
                gdCustCount: 0,

                orderSellCount: 0,
                mmOrderSellCount: 0,
                zlOrderSellCount: 0,
                gdOrderSellCount: 0,

                totalHouseDkCount: 0,
                gdhouseDkCount: 0,
                mmhouseDkCount: 0,
                czhouseDkCount: 0,

                totalCustDkCount: 0,
                mmCustDkCount: 0,
                zlCustDkCount: 0,
                gdCustDkCount: 0,

                xiangqing2: false,
                xiangqing2title: '',
                xiangqing2cont: '',

                autoplay: true,

                xiangqing: false,
                xiangqingtitle: '',
                xiangqingImg: '',
                xiangqingcont: '',
                ctime: '',
                topData: [],

                //loadingType: window.localStorage.getItem('loadingType'),
                defaultDaterange: [],
                peizhi: [],
                fyxqqyList: [],
                kyxqqyList: [],
                fyxqqy: [],
                kyxqqy: [],
                fytype: 0,
                kytype: 0,
                kdData: '0000-00-00',
                sousuo: {
                    bumen: [],
                    users: [],
                    user: Cookies.get('uid'),
                    d_id: Cookies.get('u_depart_id')
                },
                role_type: Cookies.get('role_type'),
                departData: [],  // 部门
                departkey: '',
                userList: [{
                    value: '1',
                    label: '测试'
                }],    // 用户
                selectUid: '',
                aData: [],
                // 主推房源
                setCount: {
                    leibie: '',
                    ziduan: '',
                    tianxie: [],
                    danwei: '',
                    shunxu: '',
                },
                count: {
                    createUser: 0,
                    visit: 0,
                    collection: 0,
                    transfer: 0
                },
                // 我的简报
                formValidate: {
                    city: '',
                },
                ruleValidate: {},
                height: {},
                // 图片轮播
                value2: 0,
                // 公司公告
                noticeColumns: [
                    {
                        type: 'index',
                        width: 50,
                        align: 'center'
                    },

                    {
                        title: '类型',
                        key: 'notice_type',
                        align: 'center',
                        width: 88
                    },
                    {
                        title: '标题',
                        key: 'notice_title',
                        align: 'left'
                    },
                    {
                        title: '发布时间',
                        key: 'ctime',
                        align: 'center'
                    },
                    {
                        title: '操作',
                        key: 'house_sn',
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
                                        innerHTML: '查看详情'
                                    },
                                    on: {
                                        click: () => {
                                            // this.$Modal.info({
                                            //     title: params.row.notice_title,
                                            //     content: params.row.notice_content
                                            // });
                                            this.xiangqing2 = true;
                                            this.xiangqing2title = params.row.notice_title;
                                            this.xiangqing2cont = params.row.notice_content;
                                        }
                                    }
                                })]);
                        }
                    }
                ],
                newsData: [],
                newsTopData: [],
                noticeData: [
                    {
                        neirong: '关于成交房屋的通知',
                        date: '2016-10-03'
                    }],
                // 主推房源
                csFyData: [],
                czFyData: [],
                gdFyData: [],
                csFyColumns: [
                    {
                        title: '房源编号',
                        key: 'house_sn',
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
                                            let argu = {
                                                houseId: params.row.house_uuid,
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
                        title: '小区',
                        key: 'village_name',
                        align: 'center'
                    },
                    {
                        title: '面积',
                        key: 'jianzhumianji',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', params.row.jianzhumianji + '㎡');
                        }
                    },
                    {
                        title: '售价',
                        key: 'sell_price',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', params.row.sell_price + '万');
                        }
                    },
                    {
                        title: '发布店',
                        key: 'dian',
                        align: 'center'
                    },
                    {
                        title: '发布时间',
                        key: 'ctime',
                        align: 'center'
                    }
                ],
                czFyColumns: [
                    {
                        title: '房源编号',
                        key: 'house_sn',
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
                                            let argu = {
                                                houseId: params.row.house_uuid,
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
                        title: '小区',
                        key: 'village_name',
                        align: 'center'
                    },
                    {
                        title: '面积',
                        key: 'jianzhumianji',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', params.row.jianzhumianji + '㎡');
                        }
                    },
                    {
                        title: '租价',
                        key: 'rent_price',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', params.row.rent_price + '元');
                        }
                    },
                    {
                        title: '发布店',
                        key: 'dian',
                        align: 'center'
                    },
                    {
                        title: '发布时间',
                        key: 'ctime',
                        align: 'center'
                    }
                ],
                pushColumns: [
                    {
                        title: '房源编号',
                        key: 'fangyuanbianhao',
                        align: 'center'
                    },
                    {
                        title: '小区',
                        key: 'name',
                        align: 'center'
                    },
                    {
                        title: '面积',
                        key: 'mianji',
                        align: 'center'
                    },
                    {
                        title: '售价',
                        key: 'shoujia',
                        align: 'center'
                    },
                    {
                        title: '发布店',
                        key: 'faburen',
                        align: 'center'
                    },
                    {
                        title: '发布时间',
                        key: 'fabushijian',
                        align: 'center'
                    }
                ],
                pushData: [],
                mmkyData: [],
                // 主推客源
                mmkyColumns: [
                    {
                        title: '客源编号',
                        key: 'xuqiubianhao',
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
                                            let argu = {customer_id: params.row.customer_uuid, customer_type: 0};
                                            this.$router.push({
                                                name: 'customerEtails',
                                                params: argu
                                            });
                                        }
                                    }
                                })
                            ]);
                        }
                    },
                    {
                        title: '需求区域',
                        key: 'xuqiuquyu',
                        align: 'center'
                    },
                    {
                        title: '需求价格',
                        key: 'jiage',
                        align: 'center'
                    },
                    {
                        title: '需求面积',
                        key: 'mianji',
                        align: 'center'
                    },
                    {
                        title: '主推店',
                        key: 'bumen',
                        align: 'center'
                    },
                    {
                        title: '发布时间',
                        key: 'ctime',
                        align: 'center'
                    }
                ],
                zlkyData: [],
                gdkyData: [],
                projectColumns: [
                    {
                        type: 'index',
                        width: 60,
                        align: 'center'
                    },
                    {
                        title: '项目名称',
                        key: 'house_name',
                        align: 'center',
                    },
                    {
                        title: '项目区域',
                        key: 'province',
                        align: 'center',
                    },
                    {
                        title: '项目地址',
                        key: 'detailed_address',
                        align: 'center',
                        width: 180
                    },
                    {
                        title: '销售面积',
                        key: 'keshoumianji',
                        align: 'center',
                    },
                    {
                        title: '销售单价',
                        key: 'average_price',
                        align: 'center',
                    },
                    {
                        title: '交付标准',
                        key: 'jiaofubiaozhun',
                        align: 'center',
                    },
                    {
                        title: '物业类型',
                        key: 'wuyeleixing',
                        align: 'center',
                    },
                    {
                        title: '代理结束',
                        key: 'dailijieshu',
                        align: 'center',
                    },
                    {
                        title: '销售状态',
                        key: 'status',
                        align: 'center',
                    },
                    {
                        title: '有效客户',
                        key: 'youxiaokehu',
                        align: 'center',
                    },
                ],
                /*projectData: [
                    {
                        house_name: '中海国际',
                        province: '江苏省',
                        detailed_address: '吴中大道88号',
                        keshoumianji: '110-125',
                        average_price: '18657',
                        jiaofubiaozhun: '毛坯',
                        wuyeleixing: '别墅',
                        dailijieshu: '2018-10-03',
                        status: '在售',
                        youxiaokehu: 18,
                    },
                ],*/
            };
        },
        created: function () {
            this.openModal();
            this.searchAddmount();
            this.getDeptList();
            this.getUserList();
            this.getxiaoqulist();
            this.getNewsList();
            this.getNoticeList();

            this.searchFangyuan();
            this.searchCzFangyuan();
            this.searchGdFangyuan();

            this.searchKeyuan();
            this.searchZlKeyuan();
            this.searchGdKeyuan();

            this.getgskxIndex();
            this.getprojectData();  // 项目列表
			this.getMounthmount();
        },
        computed: {

        },

        methods: {
			getMounthmount() {
				this.$http.get(api_param.apiurl + 'site/dealmount', {
					params: {},
					headers: {'X-Access-Token': api_param.XAccessToken}
				}).then(function (response) {
					// 这里是处理正确的回调
					if (response.data.code == 200) {
						this.monthmount.csHouseCount = response.data.data.csHouseCount;
						this.monthmount.mmCustCount = response.data.data.mmCustCount;
						this.monthmount.mmOrderSellCount = response.data.data.mmOrderSellCount;
						// console.log();
					} else if (response.data.code == 401) {
						this.$store.commit('logout', this);
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
            openModal(){
                // this.loadingFromRouter = window.localStorage.getItem('loadingFromRouter');

                    this.$http.post(api_param.apiurl + 'site/noticepop',
                        {},
                        {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                    ).then(function (response) {
                        // 这里是处理正确的回调
                        // if (response.data.code == 200) {
                        //     this.length = response.data.data.length-1;
                        //     this.closetime = 0;
                        //     this.noticeList = response.data.data;
                        //     this.notice.title = response.data.data[0].title;
                        //     if(response.data.data[0].image){
                        //         this.$set(this.notice,'image',api_param.imgurl+response.data.data[0].image);
                        //     }else{
                        //         this.$set(this.notice,'image','');
                        //     }
                        //     this.notice.content = response.data.data[0].content;
                        //     this.bigform = true;
                        //
                        // } else if (response.data.code == 401) {
                        //     this.$store.commit('logout', this);
                        //     this.$store.commit('clearOpenedSubmenu');
                        //     this.$router.push({
                        //         name: 'login'
                        //     });
                        // }
                    }, function (response) {
                        // 这里是处理错误的回调
                        this.$Message.error('网络异常');
                    })

            },
            modalCancel(){
                if(this.closetime < this.length){
                    this.closetime = this.closetime + 1;
                    this.$set(this.notice,'title',this.noticeList[this.closetime].title);
                    if(this.noticeList[this.closetime].image){
                        this.$set(this.notice,'image',api_param.imgurl+this.noticeList[this.closetime].image);
                    }else{
                        this.$set(this.notice,'image','');
                    }
                    this.$set(this.notice,'content',this.noticeList[this.closetime].content);
                }else{
                    this.bigform = false;
                }

            },
            testmouseover() {
                this.autoplay = false;
            },
            testmouseout() {
                this.autoplay = true;
            },

            testclick(data) {
                // console.log(data);
                this.xiangqing = true;
                this.xiangqingtitle = data.title;
                this.xiangqingImg = data.src;
                this.xiangqingcont = data.cont;
                this.ctime = data.ctime;
            },
            closexiangqing() {
                this.xiangqing = false;
            },
            closexiangqing2() {
                this.xiangqing2 = false;
            },
            getgskxIndex() { //列表页
                this.$http.get(api_param.apiurl + 'news/getindex', {
                    params: {
                        // type: this.type,
                        // daterange: this.daterange,
                        // kw: this.keyword,
                        // page: this.currentpage
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        // this.totalnum = response.data.data.totalnum;
                        // this.listData = response.data.data.newslist;
                        for (let i in response.data.data.newslist) {
                            if (response.data.data.newslist[i].is_top === '1') {
                                this.topData.push(response.data.data.newslist[i])
                            }
                        }
                        if (this.topData.length === 3) {
                            this.topData.push(response.data.data.newslist[3])
                        } else if (this.topData.length === 2) {
                            this.topData.push(response.data.data.newslist[2]);
                            this.topData.push(response.data.data.newslist[3]);
                        } else if (this.topData.length === 1) {
                            this.topData.push(response.data.data.newslist[1]);
                            this.topData.push(response.data.data.newslist[2]);
                            this.topData.push(response.data.data.newslist[3]);
                        } else if (this.topData.length === 0) {
                            this.topData.push(response.data.data.newslist[0]);
                            this.topData.push(response.data.data.newslist[1]);
                            this.topData.push(response.data.data.newslist[2]);
                            this.topData.push(response.data.data.newslist[3]);
                        }
                        for (let i in this.topData) {
                            let image = '';
                            if(this.topData[i] != undefined){
                                image = api_param.imgurl + this.topData[i].news_images;
                                this.newsTopData.push({
                                    'src': image,
                                    'title': this.topData[i].news_title,
                                    'cont': this.topData[i].news_content,
                                    'ctime': this.topData[i].utime,
                                });
                            }
                        }
                        // console.log(this.topData);
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


            // goHome() {
            //     this.$router.push({
            //         name: 'loading'
            //     });
            // },
            openRouter(routerKey) {
                this.$router.push({
                    name: routerKey
                });
            },
            // 变更房源区域
            changeFyXqqy(value, selectedData) {
                let fyxqqy = [];
                for (let i in selectedData) {
                    fyxqqy.push(selectedData[i].value);
                }
                this.fyxqqy = fyxqqy;
                this.searchFangyuan();
                this.searchCzFangyuan();
                this.searchGdFangyuan();
            },
            // 变更客源区域
            changeKyXqqy(value, selectedData) {
                let kyxqqy = [];
                for (let i in selectedData) {
                    kyxqqy.push(selectedData[i].value);
                }
                this.kyxqqy = kyxqqy;
                this.searchKeyuan();
                this.searchZlKeyuan();
                this.searchGdKeyuan();
            },
            // 变更部门
            changeDepart(value, selectedData) {
                this.sousuo.bumen = selectedData;
                this.sousuo.d_id = value.pop();
                this.sousuo.users = this.peizhi.users[this.sousuo.d_id];
            },
            // 获取部门
            getDeptList() {
                this.$http.get(api_param.apiurl + 'site/getdepttree', {
                    params: {},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.departData = response.data.data.departlist
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
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
            // 搜索部门用户
            searchUserList(value, selectedData) {
                this.departkey = value[value.length - 1];
                //alert(this.departkey);
                this.$http.get(api_param.apiurl + 'site/getstaff', {
                    params: {
                        did: this.departkey
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        //console.log(response.data);
                        this.userList = response.data.data;
                        //console.log(this.userList);
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
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
            // 获取用户列表
            getUserList() {
                this.$http.get(api_param.apiurl + 'site/getstaff', {
                    params: {
                        did: ''
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        //console.log(response.data);
                        this.userList = response.data.data;
                        //console.log(this.userList);
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
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
            // 修改日期
            changeDaterange(daterange) {
                this.defaultDaterange = daterange
            },
            // 获取新增房源数/新增客户数/新增成交数
            searchAddmount() {
                //console.log(daterange);
                //console.log(Cookies.get('uid'));
                this.$http.get(api_param.apiurl + 'site/getaddmount', {
                    params: {
                        did: this.sousuo.bumen,
                        uid: this.sousuo.user,
                        daterange: this.defaultDaterange,
                        systype: this.loadingType
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        let data = response.data.data;
                        this.series.fyseries = data.fyseries;
                        this.series.kyseries = data.kyseries;
                        this.series.cjseries = data.cjseries;
						this.series.keyseries = data.keyseries;
						this.series.dujiaseries = data.dujiaseries;
						this.series.fydkseries = data.fydkseries;
						this.series.kydkseries = data.kydkseries;
                        this.series.legend = data.legend;
                        this.series.days = data.days;
                        this.count.transfer = parseInt(data.houseCount);
                        this.count.collection = parseInt(data.custCount);
                        this.count.visit = parseInt(data.orderSellCount);
                        this.kdData = data.orderLastDealData;
                        this.defaultDaterange = data.defaultDaterange;

                        this.houseCount = parseInt(data.czHouseCount) + parseInt(data.csHouseCount) + parseInt(data.gdHouseCount);//data.houseCount;
                        this.czHouseCount = parseInt(data.czHouseCount);
                        this.csHouseCount = parseInt(data.csHouseCount);
                        this.gdHouseCount = parseInt(data.gdHouseCount);
                        this.ysHouseCount = parseInt(data.ysHouseCount);
                        this.djHouseCount = parseInt(data.djHouseCount);
                        this.ysdjhouseCount = parseInt(data.ysHouseCount) + parseInt(data.djHouseCount);

                        //this.custCount = data.custCount;
                        this.custCount = parseInt(data.mmCustCount) + parseInt(data.zlCustCount) + parseInt(data.gdCustCount);
                        this.mmCustCount = parseInt(data.mmCustCount);
                        this.zlCustCount = parseInt(data.zlCustCount);
                        this.gdCustCount = parseInt(data.gdCustCount);

                        //this.orderSellCount = data.orderSellCount;
                        this.orderSellCount = parseInt(data.mmOrderSellCount) + parseInt(data.zlOrderSellCount) + parseInt(data.gdOrderSellCount);
                        this.mmOrderSellCount = parseInt(data.mmOrderSellCount);
                        this.zlOrderSellCount = parseInt(data.zlOrderSellCount);
                        this.gdOrderSellCount = parseInt(data.gdOrderSellCount);

                        this.totalCustDkCount = parseInt(data.mmCustDkCount) + parseInt(data.zlCustDkCount) + parseInt(data.gdCustDkCount);
                        this.mmCustDkCount = parseInt(data.mmCustDkCount);
                        this.zlCustDkCount = parseInt(data.zlCustDkCount);
                        this.gdCustDkCount = parseInt(data.gdCustDkCount);

                        this.totalHouseDkCount = parseInt(data.gdhouseDkCount) + parseInt(data.mmhouseDkCount) + parseInt(data.czhouseDkCount);
                        this.gdhouseDkCount = parseInt(data.gdhouseDkCount);
                        this.mmhouseDkCount = parseInt(data.mmhouseDkCount);
                        this.czhouseDkCount = parseInt(data.czhouseDkCount);

                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
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
            // 获取快讯
            getNewsList() {
                this.$http.get(api_param.apiurl + 'news/getindex',
                    {
                        params: {
                            pagesize: 4
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        let newsData = response.data.data.newslist;
                        for (let i in newsData) {
                            let image = api_param.imgurl + newsData[i].news_images;
                            this.newsData.push({'src': image});
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
                });
            },
            // 获取公告
            getNoticeList() {
                this.$http.get(api_param.apiurl + 'notice/getindex',
                    {
                        params: {
                            page: 1,
                            daterange: ''
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.noticeData = response.data.data.noticelist;
                        console.log(this.noticeData);

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
                });
            },
            // 主推房源
            searchFangyuan() {
                //alert(api_param.XAccessToken);
                let data = {
                    page: 1,
                    sale_type: 2,
                    main: 1,
                    dts_id: this.fyxqqy
                };
                this.$http.post(api_param.apiurl + '/house/getindex',
                    data,
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.csFyData = response.data.data.list;
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
                    //console.log(response)
                    // this.modalCancel();
                    this.$Message.warning('更新失败');
                });
            },
            // 主推出租房源
            searchCzFangyuan() {
                let data = {
                    page: 1,
                    sale_type: 1,
                    main: 1,
                    dts_id: this.fyxqqy
                };
                this.$http.post(api_param.apiurl + '/house/getindex',
                    data,
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.czFyData = response.data.data.list;
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
                    //console.log(response)
                    // this.modalCancel();
                    this.$Message.warning('更新失败');
                });
            },
            // 主推高端房源
            searchGdFangyuan() {
                let data = {
                    page: 1,
                    sale_type: 3,
                    main: 1,
                    dts_id: this.fyxqqy
                };
                this.$http.post(api_param.apiurl + '/house/getindex',
                    data,
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.gdFyData = response.data.data.list;
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
                    //console.log(response)
                    // this.modalCancel();
                    this.$Message.warning('更新失败');
                });
            },
            // 主推客源
            searchKeyuan() {
                this.$http.get(api_param.apiurl + '/customer/index',
                    {
                        params: {
                            page: 1,
                            customer_type: 0,
                            xqqy: this.kyxqqy,
                            zhutui: 1,
                            d_id: ''
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.mmkyData = response.data.data.list;
                        if (Cookies.get('hasPeizhi')) {
                            if (this.peizhi.length == 0) {
                                this.peizhi = response.body.data.peizhi;
                                this.fyxqqyList = this.peizhi.villages;
                                this.kyxqqyList = this.peizhi.villages;
                                //this.departData = this.peizhi.benzu;
                                //console.log('departData');
                                //console.log(this.departData);
                            }
                        } else {
                            Cookies.set('hasPeizhi', true);
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
            // 主推租赁客源
            searchZlKeyuan() {
                this.$http.get(api_param.apiurl + '/customer/index',
                    {
                        params: {
                            page: 1,
                            customer_type: 1,
                            xqqy: this.kyxqqy,
                            zhutui: 1,
                            d_id: ''
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.zlkyData = response.data.data.list;
                        if (Cookies.get('hasPeizhi')) {
                            if (this.peizhi.length == 0) {
                                this.peizhi = response.body.data.peizhi;
                                this.fyxqqyList = this.peizhi.villages;
                                this.kyxqqyList = this.peizhi.villages;
                            }
                        } else {
                            Cookies.set('hasPeizhi', true);
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
            // 主推租赁客源
            searchGdKeyuan() {
                this.$http.get(api_param.apiurl + '/customer/index',
                    {
                        params: {
                            page: 1,
                            customer_type: 2,
                            xqqy: this.kyxqqy,
                            zhutui: 1,
                            d_id: ''
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.gdkyData = response.data.data.list;
                        if (Cookies.get('hasPeizhi')) {
                            if (this.peizhi.length == 0) {
                                this.peizhi = response.body.data.peizhi;
                                this.fyxqqyList = this.peizhi.villages;
                                this.kyxqqyList = this.peizhi.villages;
                            }
                        } else {
                            Cookies.set('hasPeizhi', true);
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
            // 主推房源
            getxiaoqulist() {
                this.$http.get(api_param.apiurl + 'district_region/index',
                    {
                        params: {
                            page: 1,
                            kw: 1
                        },
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        // this.totalnum1 = response.body.data.count;
                        // this.xiaoquData = response.body.data.list;
                        // console.log(this.xiaoquData);
                        this.aData = response.body.data.district;
                        // for (let i = 0; i < this.xiaoquData.length; i++) {
                        //     this.xiaoquData[i]['xiaoqumingcheng'] =this.xiaoquData[i].rn_name;
                        //     this.xiaoquData[i]['suoshuquyu'] =this.xiaoquData[i].district.dt_city_name+this.xiaoquData[i].district.dt_area_name;
                        //     this.xiaoquData[i]['suoshupianqu'] =this.xiaoquData[i].district_slice.dts_name;
                        //     this.xiaoquData[i]['chushoujunjia'] =this.xiaoquData[i].rn_price;
                        //     this.xiaoquData[i]['xiaoqudizhi'] =this.xiaoquData[i].rn_address;
                        // }
                        //console.log( this.aData)

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
                });
            },
            // 主推项目列表
            getprojectData() {
                this.$http.get(api_param.apiurl + 'yshouse/houselistcount', {
                    params: {
                        zhutui: 1,
                        pagesize: 100
                    }, headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // console.log(response);
                    // 这里是处理正确的回调
                    this.projectData = response.body.data.list;
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            }
        }


    };
</script>
