<style lang="less">
    @import "roomdetails.less";
</style>

<template>
    <Row>
        <Col :lg="24" :md="24" style="background: #fff;padding:16px;">
        <Card>
            <Tabs type="card" :animated="false" value="jibenxinxi">
                <TabPane label="基本信息" name="jibenxinxi">
                    <editBasic :formDealValidate="order_data" @getInfo="getInfo"></editBasic>
                </TabPane>
                <TabPane label="佣金记录" name="yongjinjilu">
                    <editMoney :commissionList="order_data.cost" :orderId="order_data.order_id" @getInfo="getInfo"></editMoney>
                </TabPane>
                <TabPane label="佣金划成" name="zhongjiehuacheng">
                    <editHuacheng :divideList="order_data.divide" ></editHuacheng>
                </TabPane>
                <TabPane label="文件扫描件" name="wenjiansaomiao">
                    <editImage :data="order_data"></editImage>
                </TabPane>
            </Tabs>
        </Card>
        </Col>
    </Row>
</template>

<script>
    import editBasic from './edit-basic.vue';
    import editImage from './edit-image.vue';
    import editMoney from './editMoney.vue';
    import editDescribe from './editDescribe.vue';
    import editHuacheng from './editHuacheng.vue'

    export default {
        name: 'roomDetails',
        components: {
            editBasic,//基本信息
            editImage,//房屋图片
            editMoney,//佣金记录
            editDescribe,//代收款记录
            editHuacheng,//中介佣金划成
        },
        data() {
            return {
                order_data:[],
                editRoom: false,
                usermodaltitle: '',
                rChange: false,
                rFollowup: false,
                rRemind: false,
                editDeal: false,
                buyDealtitle: '',
                rFengpan: false,
                rReport: false,
            };
        },
        created () {
            this.getInfo();
        },
        methods: {

            getInfo(){ //列表页
                this.$http.get(api_param.apiurl + 'ordersell/getinfo', {
                    params: {
                        order_id: this.$route.params.orderId,
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        for(let i in response.data.data){
                            this.$set(this.order_data,i,response.data.data[i])
                        }
                        this.order_data.show_order_deal_date = response.data.data.order_deal_date;
                        this.order_data.room_url ='';
                        if(response.data.data.houseinfo.house_id){
                            this.order_data.room_url = "/#/roomDetails/"+response.data.data.houseinfo.sale_type+"/"+response.data.data.houseinfo.house_uuid;
                        }
                        this.order_data.customer_url ='';
                        if(response.data.data.customerinfo.customer_id){
                            if(response.data.data.customerinfo.customer_type == 0){
                                this.order_data.customer_url = "/#/customerEtails/"+response.data.data.customerinfo.customer_uuid+"/"+response.data.data.customerinfo.customer_type;
                            }else if(response.data.data.customerinfo.customer_type == 1){
                                this.order_data.customer_url = "/#/customerEtails1/"+response.data.data.customerinfo.customer_uuid+"/"+response.data.data.customerinfo.customer_type;
                            }else if(response.data.data.customerinfo.customer_type == 2){
                                this.order_data.customer_url = "/#/customerEtails2/"+response.data.data.customerinfo.customer_uuid+"/"+response.data.data.customerinfo.customer_type;
                            }
                        }
                        console.log(this.order_data);
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        this.$Message.warning(response.data.message);
                        this.$store.commit('removeTag', 'bargainDetails');
                        this.$router.push({name: 'bargainSell_index',})
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response)
                })
            },
        },
        watch:{
            '$route.params.orderId'(to, from){
                if(to !== undefined){
                    this.getInfo();
                }
            }
        }
    };
</script>
