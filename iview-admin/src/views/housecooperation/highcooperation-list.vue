<style scoped>
    .margin {
        margin-top: 10px;
    }
    .orderEditTitle {
        font-weight: bold;
        font-size: 14px;
        overflow: hidden;
        clear: both;
        line-height: 44px;
    }

    .orderEditCountent {
        overflow: hidden;
        margin: 5px 0;
    }

    .orderEditCountent p {
        width: 35%;
        float: left;
        text-align: right;
        color: #999;
    }

    .orderEditCountent span {
        width: 65%;
        float: left;
        display: block;
    }

    .orderEditMain {
        width: 100%;
    }

    .orderEditMain li {
        width: 20%;
        float: left;
    }

    .projectGood ul li {
        width: 100%;
        overflow: hidden;
        margin: 10px 0;
    }

    .projectGood strong {
        float: left;
        width: 20%;
    }

    .projectGood .rightGood {
        float: left;
        width: 80%;
        line-height: 20px;
    }
</style>

<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="10">
                <Col :lg="3" :md="3">
                <Cascader :data="settings.district"  v-model="quyu" placeholder="区域选择" filterable change-on-select @on-change="changeDts"></Cascader>
                </Col>
                <Col :lg="3" :md="3">
                <Select placeholder="小区选择" :transfer="true" v-model="xiaoqu">
                    <Option v-for="item in village" :value="item.village_id" :key="item.village_name">{{ item.village_name }}</Option>
                </Select>
                </Col>
                <Col :lg="2" :md="2">
                <Select placeholder="状态" v-model="zhuangtai" @on-change="doSearch">
                    <Option value="1">正常</Option>
                    <Option value="2">屏蔽</Option>
                </Select>
                </Col>
                <Col :lg="2" :md="2">
                <Select placeholder="户型" v-model="huxing">
                    <Option value="0">全部</Option>
                    <Option value="1">1室</Option>
                    <Option value="2">2室</Option>
                    <Option value="3">3室</Option>
                    <Option value="4">4室</Option>
                    <Option value="5">5室以上</Option>
                </Select>
                </Col>
                <Col :lg="2" :md="2">
                <Select v-model="price" placeholder="价格区间" :transfer="true" @on-change="doSearch">
                    <Option v-for="(item,index) in settings.jgqj" :value="item.min+'-'+item.max" :key="index">{{
                        item.child_name }}
                    </Option>
                </Select>
                </Col>
                <Col :lg="2" :md="2">
                <Select v-model="area" placeholder="面积区间" :transfer="true" @on-change="doSearch">
                    <Option v-for="(item,index) in settings.mjqj" :value="item.min+'-'+item.max" :key="index">{{
                        item.child_name }}
                    </Option>
                </Select>
                </Col>
                <Col :lg="3" :md="3">
                <Input v-model="kw" placeholder="房源名称，小区名，片区名，编号"></Input>
                </Col>
                <Col :lg="3" :md="3">
                <Button type="primary" @click="doSearch">查询</Button>
                <Button type="primary" @click="clearSearch">清空</Button>
                </Col>
            </Row>
            <Modal title="屏蔽房源" v-model="pingbi">
                <div slot="footer">
                    <Button type="text" size="large" @click="modalCancel">取消</Button>
                    <Button type="primary" size="large" @click="modalOk" :disabled="isDisable">确定</Button>
                </div>
                <Row>
                    <Select placeholder="" :transfer="true"   v-model="shezhi.zhuangtai" >
                        <Option    value="正常"  key="正常">正常</Option>
                        <Option    value="屏蔽" key="屏蔽">屏蔽</Option>
                    </Select>
                </Row>
                <Row style="margin-top:10px ">
                    <Input type="textarea" :rows="4"  placeholder="请输入原因"  v-model="shezhi.yuanyin"/>
                    <p style="color: red;font-size: 12px;margin-top: 10px;text-align: right">温馨提示：屏蔽之后，此合作房源将不会在您的网站和小程序上展示</p>
                </Row>
            </Modal>

            <Modal v-model="xiangqing" title="房源详情" ok-text="确认" cancel-text="取消" width="960">
                <p class="orderEditTitle">基本信息</p>
                <Row class="orderEditMain">
                    <Col :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>房源标题:&nbsp;&nbsp;</p><span>{{housedetail.house_title}}</span></div>
                    </Col>
                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>房源编号:&nbsp;&nbsp;</p><span>{{housedetail.house_sn}}</span></div>
                    </Col>
                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>区域:&nbsp;&nbsp;</p>
                        <span>{{housedetail.dts_name}}-{{housedetail.village_name}}</span>
                    </div>
                    </Col>
                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>建筑面积:&nbsp;&nbsp;</p><span>{{housedetail.jianzhumianji}}</span></div>
                    </Col>
                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>使用面积:&nbsp;&nbsp;</p><span>{{housedetail.shiyongmianji}}</span></div>
                    </Col>

                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>装修:&nbsp;&nbsp;</p><span>{{housedetail.zhuangxiu}}</span></div>
                    </Col>
                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>配套:&nbsp;&nbsp;</p><span>{{housedetail.peitao}}</span></div>
                    </Col>
                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>房屋户型:&nbsp;&nbsp;</p><span>{{housedetail.huxing_shi}}室{{housedetail.huxing_ting}}厅{{housedetail.huxing_wei}}卫</span></div>
                    </Col>
                    <Col v-if="housedetail.sale_type!=1"  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>售价:&nbsp;&nbsp;</p><span>{{housedetail.sell_price}}</span></div>
                    </Col>
                    <Col v-else  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>租价:&nbsp;&nbsp;</p><span>{{housedetail.rent_price}}</span></div>
                    </Col>
                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>房源标签:&nbsp;&nbsp;</p><span>{{housedetail.house_tag}}</span></div>
                    </Col>
                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>建造年代:&nbsp;&nbsp;</p><span>{{housedetail.jianzaoniandai}}年</span></div>
                    </Col>
                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>产权性质:&nbsp;&nbsp;</p><span>{{housedetail.chanquanxingzhi}}</span></div>
                    </Col>
                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>产权年限:&nbsp;&nbsp;</p><span>{{housedetail.chanquannianxian}}</span></div>
                    </Col>
                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>产证日期:&nbsp;&nbsp;</p><span>{{housedetail.chanzhengriqi}}</span></div>
                    </Col>

                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>房源税费:&nbsp;&nbsp;</p><span>{{housedetail.fangyuanshuifei}}</span></div>
                    </Col>
                    <Col  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>看房方式:&nbsp;&nbsp;</p><span>{{housedetail.kanfangfangshi}}</span></div>
                    </Col>
                    <Col v-if="housedetail.is_yaoshi==1"  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>钥匙:&nbsp;&nbsp;</p><span>有</span></div>
                    </Col>
                    <Col v-else  :lg="6" :ma="6">
                    <div class="orderEditCountent"><p>钥匙:&nbsp;&nbsp;</p><span>无</span></div>
                    </Col>
                </Row>
                <Row>
                    <p class="orderEditTitle">联系电话</p>
                    <Col :lg="6" :md="6" class="orderEditCountent" v-for="item in housedetail.company_tel"><p>{{item.occ_name}}:&nbsp;&nbsp;</p><span>{{item.occ_tel}}</span></Col>
                </Row>
                <Row>
                    <div>
                        <p class="orderEditTitle">项目图片</p>
                        <div class="demo-upload-list" v-for="item in housedetail.house_img" style="float: left;margin-left:10px ">
                            <template>
                                <img :src="imgurl+item.hi_url">
                                <div class="demo-upload-list-cover">
                                    <Icon type="ios-eye-outline" @click.native="handleView(imgurl+item.hi_url)"></Icon>
                                </div>
                            </template>
                        </div>

                    </div>
                </Row>
                <Modal title="图片详情" v-model="visible"  :transfer="false">
                    <!-- v-if="visible" -->
                    <img :src=" imgName " style="width: 100%">
                </Modal>
            </Modal>

        </Card>
        </Col>
        <Col :lg="24" :md="24" class="margin">
        <Card>
            <Table :data="houseList" :columns="houseColumns" stripe border></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="totalnum" :current="currentpage" @on-change="changePage" show-total></Page>
                </div>
            </div>
        </Card>
        </Col>
    </Row>
</template>

<script>
    import Cookies from 'js-cookie';
    export default {
        name: 'highcooperation_index',
        components: {},
        data() {
            return {
                isDisable:false,
                kw:'',
                quyu: [],
                area: '',
                huxing: '',
                price: '',
                xiaoqu:'',
                village:[],
                zhuangtai:'',
                imgurl:api_param.imgurl,
                visible:false,
                housedetail:[],
                xiangqing:false,
                pingbi:false,
                shezhi:{
                    zhuangtai:'',
                    cooperation_id:'',
                    company_id:'',
                    house_id:'',
                    yuanyin:''
                },
                totalnum: 0,
                imgName:'',
                currentpage: 1,
                settings:[],
                searchdata:[],
                houseList:[],
                //表格
                houseColumns: [
                    {
                        type: 'index',
                        width: 60,
                        align: 'center'
                    },
                    {
                        title: '房源编号',
                        key: 'house_sn',
                        align: 'center'
                    },
                    {
                        title: '房源标题',
                        key: 'house_title',
                        align: 'center'
                    },
                    {
                        title: '片区',
                        key: 'dts_name',
                        align: 'center'
                    },
                    {
                        title: '小区',
                        key: 'village_name',
                        align: 'center'
                    },
                    {
                        title: '房屋面积（㎡）',
                        key: 'jianzhumianji',
                        align: 'center'
                    },
                    {
                        title: '房屋户型',
                        key: 'huxing',
                        align: 'center',
                        render: (h, params) => {
                            let huxing = params.row.huxing_shi+'室'+params.row.huxing_ting+'厅'+params.row.huxing_wei+'卫';
                            return h('div', {props: {},},huxing);
                        }
                    },
                    {
                        title: '房屋价格(万元)',
                        key: 'sell_price',
                        align: 'center'
                    },
                    {
                        title: '楼层',
                        key: 'louceng',
                        align: 'center',
                        render: (h, params) => {
                            let huxing = params.row.louceng_now+'/'+params.row.louceng_total;
                            return h('div', {props: {},},huxing);
                        }
                    },
                    {
                        title: '房屋类型',
                        key: 'fangwuleixing',
                        align: 'center'
                    },
                    {
                        title: '装修',
                        key: 'zhuangxiu',
                        align: 'center'
                    },
                    {
                        title: '状态',
                        key: 'is_blacklist',
                        align: 'center',
                        render: (h, params) => {
                            let is_blacklist
                            if(params.row.is_blacklist==1){
                                is_blacklist ='已屏蔽'
                                return h('Button', {props: {  type: 'error',
                                        size: 'small'},},is_blacklist);
                            }else {
                                is_blacklist ='正常'
                                return h('div', {props: {  },},is_blacklist);
                            }


                        },
                    },
                    {
                        title: '更新时间',
                        key: 'utime',
                        align: 'center'
                    },{
                        title: '操作',
                        key: 'projCaozuo',
                        align: 'center',
                        width: 180,
                        render: (h, params) => {
                            let ret = [];
                            ret.push(h('Button', {
                                props: {
                                    type: 'primary',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px',
                                    marginTop:'5px'
                                },
                                on: {
                                    click: () => {
                                        if(params.row.is_blacklist==1){
                                            this.shezhi.zhuangtai='屏蔽'
                                        }else {
                                            this.shezhi.zhuangtai='正常'
                                        }
                                        this.shezhi.cooperation_id=params.row.cooperation_id;
                                        this.shezhi.company_id=params.row.company_id;
                                        if(params.row.blacklist.length>0){
                                            this.shezhi.yuanyin=params.row.blacklist[0].reason;
                                        }
                                        this.pingbi=true;
                                    }
                                }
                            }, '屏蔽'));
                            ret.push(h('Button', {
                                props: {
                                    type: 'primary',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px',
                                    marginTop:'5px'
                                },
                                on: {
                                    click: () => {
                                        this.housedetail=params.row
                                        this.xiangqing=true

                                    }
                                }
                            }, '查看'));
                            return h('div', ret);
                        }
                    }

                ],
            };
        },
        created() {
            this.quyu= [Cookies.get('area_id'),Cookies.get('dts_id')];
            this.getSetting();
            this.getIndex();
        },
        methods: {
            getSetting() { //获取下拉菜单
                this.$http.get(api_param.apiurl + 'housecooperation/getsetting', {
                    params: {},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.settings = response.data.data;
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
            handleView(name) {
                this.imgName = name;
                this.visible = true;
            },
            changeDts(value, selectedData){
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
            modalOk(){
                if(!this.shezhi.yuanyin){
                    this.$Message.warning('原因不能为空！');
                    return
                }
                this.isDisable=true;
                this.$http.post(api_param.apiurl + '/housecooperation/blacklist',
                    {
                        'zhuangtai':this.shezhi.zhuangtai,
                        'yuanyin':this.shezhi.yuanyin,
                        'cooperation_id': this.shezhi.cooperation_id,
                        'company_id': this.shezhi.company_id,

                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    this.pingbi=false;
                    this.shezhi.yuanyin='';
                    this.$Message.success(response.data.message);
                    setTimeout(() => {
                        this.isDisable=false;
                    }, 1000)
                    this.getIndex();
                    //  this.modalCancel();
                }, function (response) {
                    // 这里是处理错误的回调
                    //console.log(response)
                    // this.modalCancel();
                    this.$Message.warning('更新失败');
                    setTimeout(() => {
                        this.isDisable=false;
                    }, 1000)
                });
            },
            modalCancel(){
                this.pingbi=false;
                this.shezhi.yuanyin='';
            },
            getIndex() { //列表页
                this.$http.get(api_param.apiurl + 'housecooperation/index', {
                    params: {
                        kw: this.kw,
                        quyu: this.quyu,
                        area: this.area,
                        huxing: this.huxing,
                        price: this.price,
                        page: this.currentpage,
                        xiaoqu: this.xiaoqu,
                        zhuangtai: this.zhuangtai,
                        house_type: 3,
                    },
                    headers: {
                        'X-Access-Token': api_param.XAccessToken
                    }
                }).then(function (response) {
                    // 这里是处理正确的回调
                    this.totalnum = parseInt(response.data.data.total);
                    this.houseList = response.data.data.houseList;
                    //console.log(this.userList);
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                })
            },
            doSearch() {
                this.currentpage = 1;
                this.getIndex();
            },
            clearSearch() {
                this.kw = '';
                this.quyu = 0;
                this.zhuangtai ='';
                this.area = '';
                this.huxing = '';
                this.price = '';
                this.xiaoqu='';
                this.currentpage = 1;
                this.getIndex();
            },
            //分页
            changePage(page) {
                this.currentpage = page;
                this.getIndex();
            },
        }
    }
</script>
