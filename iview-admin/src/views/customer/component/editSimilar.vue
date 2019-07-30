<style scoped>

</style>
<template>
    <Row>
        <Col :lg="18" :md="18" class="editBorder">
        <!--<Row :gutter="10">-->
            <!--<Col :lg="24" :md="24">-->
            <!--<Row>-->
                <!--<Col :lg="4" :md="4">-->
                <!--<Cascader :data="this.peizhi.villages" v-model="xqqy" placeholder="需求区域"></Cascader>-->
                <!--</Col>-->
                <!--<Col :lg="2" :md="2">-->
                <!--<Select v-model="selJiaGe" placeholder="价格区间"  :transfer="true">-->
                    <!--<Option v-for="item in xuqiujiage" :value="item.value" :key="item.value">{{ item.label }}</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <!--<Col :lg="2" :md="2">-->
                <!--<Select v-model="mianji" placeholder="面积区间"  :transfer="true">-->
                    <!--<Option v-for="item in xuqiumianji" :value="item.value" :key="item.value">{{ item.label }}</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <!--<Col :lg="3" :md="3">-->
                <!--<DatePicker v-model="sousuo.shijian" type="daterange" placeholder="起始时间" @on-change="changeshijian"></DatePicker>-->
                <!--</Col>-->
                <!--<Col :lg="3" :md="3">-->
                <!--<Button type="primary" @click="sousuoqu">搜索</Button>-->
                <!--</Col>-->
            <!--</Row>-->
            <!--</Col>-->
        <!--</Row>-->
        <Row :gutter="10" style="margin-top: 10px">
            <Col>
            <Table :columns="columns1" :data="tableData2" border script height="560"> </Table>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: "editSimilar",
        props: ['customer', 'peizhi'],
        data() {
            return {
                tableData2:[],
                xuqiumianji: [],
                xuqiujiage: [],
                mianji:'',
                xqqy:'',
                selJiaGe:'',
                s:'',
                sousuo:{
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
                    shijian:[],
                    huxing:'',
                    louceng:'',
                    fangling:'',
                    bmyhlx:'',
                },
                columns1: [
                    {
                        title: '状态',
                        key: 'zhuangtai',
                        align:'center'
                    },
                    {
                        title: '客源编码',
                        key: 'hetongbianhao',
                        align:'center',
                        width: 120,
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
                                            let argu = {customer_uuid: params.row.customer_uuid,customer_type:this.$route.params.customer_type};
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
                        title: '区域',
                        key: 'xuqiuquyu',
                        align:'center'
                    },
                    {
                        title: '需求价格',
                        key: 'jiage',
                        align:'center'
                    },
                    {
                        title: '需求面积',
                        key: 'mianji',
                        align:'center'
                    },
                    {
                        title: '户型',
                        key: 'huxing',
                        align:'center'
                    },
                    {
                        title: '维护人',
                        key: 'weihuren',
                        align:'center'
                    }
                ]
            }
        },created: function () {
            this.getIndex();
        },
        methods: {
            sousuoqu(){
                this.s=1;
              this.getIndex();
            },
            getIndex() {
                if(this.s==1){
                    var data={
                        page: this.currentpage1,
                        customer_type: this.$route.params.customer_type,
                        sszhuangtai:this.sszhuangtai,
                        ssxz:this.ssxz,
                        xqqy:this.xqqy,
                        ssk:this.ssk,
                        xuqiujiage_min:this.sousuo.xuqiujiage_min,
                        xuqiujiage_max:this.sousuo.xuqiujiage_max,
                        xuqiumianji_min:this.sousuo.xuqiumianji_min,
                        xuqiumianji_max:this.sousuo.xuqiumianji_max,
                        d_id:this.sousuo.d_id,
                        user:this.sousuo.user,
                        shijian:this.sousuo.shijian,
                        bmyhlx:this.sousuo.bmyhlx,
                        xuqiuhuxing_min:this.sousuo.xuqiuhuxing_min,
                        xuqiuhuxing_max:this.sousuo.xuqiuhuxing_max,
                        xuqiulouceng_min:this.sousuo.xuqiulouceng_min,
                        xuqiulouceng_max:this.sousuo.xuqiulouceng_max,
                        xuqiufangling_min:this.sousuo.xuqiufangling_min,
                        xuqiufangling_max:this.sousuo.xuqiufangling_max,
                    }
                }else{
                    var data={
                        page: this.currentpage1,
                        customer_type: this.$route.params.customer_type,
                        d_id:this.sousuo.d_id,
                        xuqiujiage_min:this.customer.xuqiujiage_min,
                        xuqiujiage_max:this.customer.xuqiujiage_max,
                        xuqiumianji_min:this.customer.xuqiumianji_min,
                        xuqiumianji_max:this.customer.xuqiumianji_max,
                        xqqy:[this.customer.dts_id,this.customer.rn_id],
                        butong:this.$route.params.customer_uuid,
                    }
                }
                this.$http.get(api_param.apiurl + '/customer/index',
                    {
                        params:data,
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum1 = response.body.data.count;
                        this.tableData2 = response.body.data.list;
                        if(this.peizhi.length==0){
                            this.peizhi = response.body.data.peizhi;
                        }
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
                       // console.log(xuqiumianji);
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
            }
        }
    }
</script>
