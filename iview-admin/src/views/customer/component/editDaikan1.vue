<style>
    .demo-upload-list {
        display: inline-block;
        width: 60px;
        height: 60px;
        text-align: center;
        line-height: 60px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .2);
        margin-right: 4px;
    }

    .demo-upload-list img {
        width: 100%;
        height: 100%;
    }

    .demo-upload-list-cover {
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, .6);
    }

    .demo-upload-list:hover .demo-upload-list-cover {
        display: block;
    }

    .demo-upload-list-cover i {
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
</style>
<template>
    <Modal v-model="rDaikan" title="写带看" :buyDealtitle="buyDealtitle" :closable="false" width="640">
        <div slot="header">
            <a class="ivu-modal-close" @click="daikanCancel" style="display: block!important;"><i
                    class="ivu-icon ivu-icon-ios-close-empty" ></i></a>
            <div class="ivu-modal-header-inner">写带看</div>
        </div>
        <div slot="footer">
            <Button type="text" size="large" @click="daikanCancel">取消</Button>
            <Button type="primary" size="large" @click="daikanOk" :disabled="isDisable">确定</Button>
        </div>
        <Form ref="daikan" :model="daikan"  :label-width="60">
            <Row>
                <Col :lg="12" :md="12">
                <FormItem label="带看房源" prop="">
                    <Button type="primary" @click="ejectRoom">二手房选择</Button>
                    <Modal v-model="ejectRoomshow" title="二手房源" :transfer="false" width="960">
                        <div slot="header">
                            <a class="ivu-modal-close" @click="xuanzefangyuanCancel" style="display: block!important;"><i
                                    class="ivu-icon ivu-icon-ios-close-empty" ></i></a>
                            <div class="ivu-modal-header-inner">二手房源</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="xuanzefangyuanCancel">取消</Button>
                            <Button type="primary" size="large" @click="xuanzefangyuanOk">确定</Button>
                        </div>
                        <Row :gutter="5">
                            <Col :lg="6" :md="6">
                            <Input placeholder="房源编号、电话等" v-model="keyword" ></Input>
                            </Col>
                            <Col :lg="3" :md="3">
                            <Button type="primary" @click="gethouse">查询</Button>
                            </Col>
                        </Row>
                        <Row>
                            <Col :lg="24" :md="24" style="margin-top: 10px">
                            <Table :columns="daikanColumns" :data="daikanData" border script
                                   @on-selection-change="selectionok"></Table>
                            <div style="margin: 10px;overflow: hidden">
                                <div style="float: right;">
                                    <Page :total="pageTotal" :current="pageCurrent" @on-change="changePage"></Page>
                                </div>
                            </div>
                            </Col>
                        </Row>
                    </Modal>
                </FormItem>
                </Col>
            </Row>
            <Row :gutter="10">
                <Col :lg="16" :md="16">
                <div style="margin-bottom: 5px">
                    <strong>带看房源</strong>
                </div>
                </Col>
            </Row>
            <Row>
                <Col :lg="24" :md="24" style="margin-bottom: 10px">
                <Table :columns="daikanColumnsList" :data="daikanDataList" border script></Table>
                </Col>
            </Row>
            <Row>
                <Col :lg="24" :md="24">
                <FormItem label="客户评价" prop="kehupingjia"  :rules="{required: true, message: '客户评价不能为空', trigger: 'blur'}">
                    <Input type="textarea" :autosize="{minRows: 2,maxRows: 5}"  v-model="daikan.kehupingjia"  placeholder=""></Input>
                </FormItem>
                </Col>
            </Row>
        </Form>
    </Modal>
</template>
<script>
    export default {
        name: 'editDaikan1',
        props: ['rDaikan', 'buyDealtitle'],
        data () {
            return {
                isDisable: false,
                nameList: [
                    {
                        value: 'xiansheng',
                        label: '先生'
                    },
                    {
                        value: 'nvshi',
                        label: '女士'
                    },],
                nameModel: '',
                selection: [],
                rDaikan:'',
                sfxz:'',
                keyword:'',
                daikan:{
                    kehupingjia:''
                },
                //房源信息弹出
                ejectRoomshow: false,
                houseList: [],
                pageTotal: '',
                pageCurrent: 1,
                //二手房源页面展示
                daikanColumnsList: [
                    {
                        title: '房源编号',
                        key: 'house_sn',
                        align: 'center',
                        width: 120
                    },
                    {
                        title: '小区',
                        key: 'village_name',
                        align: 'center',
                        width: 120
                    },
                    {
                        title: '座栋',
                        key: 'loudong_name',
                        align: 'center'
                    },
                    {
                        title: '面积',
                        key: 'jianzhumianji',
                        align: 'center'
                    },
                    {
                        title: '出租价格',
                        key: 'rent_price',
                        align: 'center'
                    },
                    {
                        title: '状态',
                        key: 'xianzhuang',
                        align: 'center'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            for (let j = 0; j < this.daikanDataList.length; j++) {
                                                if (this.daikanDataList[j].house_id == params.row.house_id) {
                                                    this.daikanDataList.splice(j, 1);
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }, '删除'),
                            ]);
                        }
                    }
                ],
                daikanDataList: [],
                //二手房源多选
                daikanColumns: [
                    {
                        type: 'selection',
                        width: 60,
                        align: 'center'
                    },
                    {
                        title: '状态',
                        key: 'house_status',
                        align: 'center'
                    },
                    {
                        title: '房源编号',
                        key: 'house_sn',
                        align: 'center',
                        width: 120
                    },
                    {
                        title: '小区',
                        key: 'village_name',
                        align: 'center'
                    },
                    {
                        title: '座栋',
                        key: 'loudong_name',
                        align: 'center'
                    },
                    {
                        title: '门牌号',
                        key: 'fanghao_name',
                        align: 'center'
                    },
                    {
                        title: '业主',
                        key: 'customer_name',
                        align: 'center'
                    },
                    {
                        title: '房型',
                        key: 'fangxing',
                        align: 'center'
                    },
                    {
                        title: '面积',
                        key: 'jianzhumianji',
                        align: 'center'
                    },
                    {
                        title: '出租价格',
                        key: 'rent_price',
                        align: 'center'
                    },
                    {
                        title: '状态',
                        key: 'xianzhuang',
                        align: 'center'
                    },
                    {
                        title: '维护人',
                        key: 'weihuren',
                        align: 'center'
                    },
                    {
                        title: '部门',
                        key: 'bumen',
                        align: 'center'
                    }
                ],
                daikanData: [],
                sale_type:'',
            };
        },
        methods: {
            gethouse () {
                if(this.$route.params.customer_type==0){
                    this.sale_type = 2;
                }
                if(this.$route.params.customer_type==1){
                    this.sale_type = 1;
                }
                if(this.$route.params.customer_type==2){
                    this.sale_type = 3;
                }
                this.$http.post(api_param.apiurl + 'house/getindex',
                    {
                        'page': this.pageCurrent,
                        'keyword': this.keyword,
                        'sale_type' :this.sale_type,
//			            'u_status': status,
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.daikanData = response.data.data.list;
                        this.pageTotal = response.data.data.count;
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
            }, ejectRoom () {
                this.ejectRoomshow = true;
                this.gethouse();
            },
            xuanzefangyuanOk () {
                //console.log(response)

                for (var i = 0; i < this.selection.length; i++) {
                    var qc=this.array_contain(this.daikanDataList,this.selection[i]);
                        if(qc){
                            this.daikanDataList.push(this.selection[i]);
                        }
                }
                this.selection=[];
                this.ejectRoomshow = false;

            },daikanOk(){

                if(this.daikanDataList.length==0){
                    this.$Message.warning('请选择房源');
                    this.isDisable=false;
                }
                if(this.daikan.kehupingjia!='' && this.daikanDataList.length>0){
                    this.isDisable=true;
                    var data={
                        d_pingjia:this.daikan.kehupingjia,
                        house_uuid:this.daikanDataList,
                        customer_uuid:  this.$route.params.customer_uuid,
                    };
                    this.$http.post(api_param.apiurl + '/customer_daikan/add',
                        data,
                        {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                    ).then(function (response) {
                        // 这里是处理正确的回调
                        this.$Message.success(response.data.message);
                        this.daikanCancel();


                    }, function (response) {
                        // 这里是处理错误的回调
                        //console.log(response)
                        // this.modalCancel();
                        this.$Message.warning('更新失败');
                    });
                }
            },daikanCancel(){
                this.daikanDataList=[];
                this.daikan.kehupingjia='';
                this.rDaikan=false;
                setTimeout(() => {
                    this.isDisable=false;
                }, 1000)
                this.$emit('resetok1');

            },array_contain(array, obj){
                //去重
                for (var i = 0; i < array.length; i++){
                    if (array[i].house_uuid== obj.house_uuid)
                        return false;
                }
                return true;
            },
            changePage (page) {//分页
                this.pageCurrent = page;
                this.gethouse();
            },
            selectionok (selection) {
                this.selection = selection;
            },
            xuanzefangyuanCancel () {
                this.ejectRoomshow = false;
            }
        },
        mounted () {

        }

    }
    ;
</script>
