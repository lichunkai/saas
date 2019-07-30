<style scoped>
    .margin {
        margin-top: 10px;
    }
</style>

<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="10">
                <Col :lg="3" :md="3">
                <Cascader :data="settings.district" :value.sync="quyu" filterable change-on-select
                          @on-change="searchChange" :transfer="false"></Cascader>
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
                <Input v-model="kw" placeholder="房源名称，小区名，片区名，备注关键词"></Input>
                </Col>
                <Col :lg="3" :md="3">
                <Button type="primary" @click="doSearch">查询</Button>
                <Button type="primary" @click="clearSearch">清空</Button>
                </Col>
                <!--<Col :lg="3" :md="3">
                <Row type="flex" justify="end">
                <Col>
                <Button type="primary" @click="importHouse">导入房源</Button>
                </Col>
                </Row>
                </Col>-->
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" class="margin">
        <Card>
            <Table :data="houseList" :columns="houseColumns" stripe border  @on-selection-change="selectionok"></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="totalnum" :current="currentpage" @on-change="changePage" show-total></Page>
                </div>
                <div style="float: left;overflow: hidden;line-height: 33px">
                    <p>注：本系统所有房源均来自网络公开数据，仅供参考~</p>
                </div>
            </div>
        </Card>
        </Col>
        <secondSaleAdd ref="secondSaleAdd" :addHouseModal="addHouseModal" :settings="houseSettings" :formValidate="formValidate" v-on:resetModal="resetModal">
        </secondSaleAdd>
        <Modal title="查看图片" v-model="visibleModal">
            <div slot="header">
                <a class="ivu-modal-close" @click="visibleModal = false" style="display: block!important">
                    <i class="ivu-icon ivu-icon-ios-close-empty"></i>
                </a>
                <div class="ivu-modal-header-inner">查看图片</div>
            </div>
            <Carousel v-if="visibleModal"  dots="none" v-model="startimg">
                <CarouselItem v-for="image in imageData.list">
                    <img v-if="image" :src="image" style="width: 100%">
                </CarouselItem>
            </Carousel>
        </Modal>
        <Modal title="查看备注" v-model="markModal">
            <div slot="header">
                <a class="ivu-modal-close" @click="markModal = false" style="display: block!important">
                    <i class="ivu-icon ivu-icon-ios-close-empty"></i>
                </a>
                <div class="ivu-modal-header-inner">查看备注</div>
            </div>
            <div class="textbox" v-html="markData"></div>
        </Modal>
        <Modal title="查看联系方式" v-model="phoneModal">
            <div slot="header">
                <a class="ivu-modal-close" @click="phoneModal = false" style="display: block!important">
                    <i class="ivu-icon ivu-icon-ios-close-empty"></i>
                </a>
                <div class="ivu-modal-header-inner">查看联系方式</div>
            </div>
            <div class="textbox" v-html="phoneData"></div>
        </Modal>
    </Row>
</template>

<script>
    import Vue from 'vue';
    import Cookies from 'js-cookie';
    import secondSaleAdd from '../second-house/house-sale/secondSaleAdd.vue';

    export default {
        name: 'sellcollect_index',
        components: {
            secondSaleAdd
        },
        data() {
            return {
                kw:'',
                quyu: [],
                area: '',
                huxing: '',
                price: '',
                totalnum: 0,
                currentpage: 1,
                settings:[],
                houseSettings:[],
                searchdata:[],
                houseList:[],
                startimg:0,
                visibleModal:false,
                imageData:[],
                markModal:false,
                markData:'',
                phoneModal:false,
                phoneData:'',
                //表格
                houseColumns: [
                    /*{
                        type: 'selection',
                        fixed: 'left',
                        width: 60,
                        align: 'center'
                    },*/
                    {
                        type: 'index',
                        width: 60,
                        align: 'center'
                    },
                    {
                        title: '房源标题',
                        key: 'house_title',
                        align: 'center'
                    },
                    {
                        title: '区域',
                        key: 'area_name',
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
                        title: '来源',
                        key: 'form',
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
                                        innerHTML: '来源'
                                    },
                                    on: {
                                        click: () => {
                                            window.open(params.row.house_url, "_blank");
                                        }
                                    }
                                })
                            ]);
                        }
                    },
                    /*{
                        title: '姓名',
                        key: 'customer_name',
                        align: 'center'
                    },
                    {
                        title: '联系方式',
                        key: 'customer_phone',
                        align: 'center'
                    },*/
                    {
                        title: '发布时间',
                        key: 'ctime',
                        align: 'center'
                    },
                    {
                        title: '操作',
                        key: 'caozuo',
                        align: 'center',
                        render: (h, params) => {
                            let ret = [];
                            ret.push(h('Button', {
                                props: {
                                    type: 'text',
                                    size: 'small'
                                },
                                style: {
                                    color: '#1877a8'
                                },
                                on: {
                                    click: () => {
                                        let image_arr = params.row.house_img.split(';');
                                        this.markModal = false;
                                        this.visibleModal = true;
                                        this.$set(this.imageData,'list',image_arr);
                                    }
                                }
                            }, '图片'));
                            ret.push(h('Button', {
                                props: {
                                    type: 'text',
                                    size: 'small'
                                },
                                style: {
                                    color: '#1877a8'
                                },
                                on: {
                                    click: () => {
                                        this.visibleModal = false;
                                        this.markModal = true;
                                        this.markData = params.row.mark;
                                    }
                                }
                            }, '备注'));
                            ret.push(h('Button', {
                                props: {
                                    type: 'text',
                                    size: 'small'
                                },
                                style: {
                                    color: '#1877a8'
                                },
                                on: {
                                    click: () => {
                                         this.$http.get(api_param.apiurl + 'housecollect/gettel', {
                                             params: {
                                                 house_id: params.row.house_id,
                                                 sale_type: 2,
                                             },
                                             headers: {'X-Access-Token': api_param.XAccessToken}
                                         }).then(function (response) {
                                             if (response.data.code == 200) {// 这里是处理正确的回调
                                                 this.phoneModal = true;
                                                 this.phoneData = params.row.customer_name + '<br/>' + params.row.customer_phone;
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
                                    }
                                }
                            }, '查看电话'));
                            // ret.push(h('Button', {
                            //     props: {
                            //         type: 'text',
                            //         size: 'small'
                            //     },
                            //     style: {
                            //         color: '#1877a8'
                            //     },
                            //     on: {
                            //         click: () => {
                            //              this.$http.get(api_param.apiurl + 'housecollect/getimportcount', {
                            //                  params: {house_id: params.row.house_id},
                            //                  headers: {'X-Access-Token': api_param.XAccessToken}
                            //              }).then(function (response) {
                            //                  if (response.data.code == 200) {// 这里是处理正确的回调
                            //                      this.addHouseModal = true;
                            //                      this.formValidate.caijihouse_id = params.row.house_id;
                            //                      for(let item in this.initForm){
                            //                          this.formValidate[item] = this.initForm[item];
                            //                      }
                            //                      for(let item in this.formValidate){
                            //                          //alert(item);
                            //                          if(params.row[item]){
                            //                              this.formValidate[item] = params.row[item];
                            //                          }
                            //                      }
                            //                  } else if (response.data.code == 401) {
                            //                      this.$store.commit('logout', this);
                            //                      this.$store.commit('clearOpenedSubmenu');
                            //                      this.$router.push({
                            //                          name: 'login'
                            //                      });
                            //                  } else {
                            //                      this.$Message.warning(response.data.message);
                            //                  }
                            //              }, function (response) {
                            //                  this.$Message.error('你的网络开小差了^—^');
                            //              })
                            //         }
                            //     }
                            // }, '一键导入'));
                        return h('div', ret);
                    }
                    }
                ],
                addHouseModal: false,  //添加房源标签
                initForm: {}, // 初始化表单
                formValidate: {
                    sale_type:2,
                    fruit: [],
                    dts: [],
                    village: [],
                    is_import: 1,
                    caijihouse_id: '',
                    tuijianbiaoqian: [],
                    village_name: '',
//                    loudong_name: '',
//                    danyuan_name: '',
//                    fanghao_name: '',
                    customer_name: '',
                    customer_sex: '',
                    customer_phone: '',
                    customer_type: '',
                    house_title: '',
                    house_tag: [],
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

                }
            };
        },
        created() {
            this.initForm = this.formValidate;
            this.getHouseSetting();
            this.getSetting();
            this.getIndex();
        },
        methods: {
            getHouseSetting() { // 获取配置
                this.$http.get(api_param.apiurl + 'house/getsetting', {
                    params: {
                        sale_type: 2
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    if (response.data.code == 200) {// 这里是处理正确的回调
                        for (let i in response.data.data) {
                            this.$set(this.houseSettings, i, response.data.data[i])
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
                })
            },
            resetModal() {
                this.addHouseModal = false;
            },
            getSetting() { //获取下拉菜单
                this.$http.get(api_param.apiurl + 'housecollect/getsetting', {
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
            getIndex() { //列表页
                this.$http.get(api_param.apiurl + 'housecollect/sellcollectlist', {
                    params: {
                        kw: this.kw,
                        quyu: this.quyu,
                        area: this.area,
                        huxing: this.huxing,
                        price: this.price,
                        page: this.currentpage,
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
            selectionok(selection) {
                this.selection = selection;
                // console.log(this.selection);
            },
            // 导入房源
            importHouse() {
                if(this.selection == undefined){
                    alert('请选择需要导入的房源。');
                    return;
                }
                this.$http.post(api_param.apiurl + 'housecollect/import',
                    {
                        'selection': this.selection,
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.selection = [];
                        this.$Message.success(response.data.message);
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
            doSearch() {
                this.currentpage = 1;
                this.getIndex();
            },
            clearSearch() {
                this.kw = '';
                this.quyu = 0;
                this.area = '';
                this.huxing = '';
                this.price = '';
                this.currentpage = 1;
                this.getIndex();
            },
            //分页
            changePage(page) {
                this.currentpage = page;
                this.getIndex();
            },
            searchChange(value, selectedData) {
                this.quyu = [value[value.length - 1]];
            },
        }
    }
</script>
