<style lang="less">
    .xiaDing .ivu-tag {
        height: auto;
    }

    .xiaDing p {
        line-height: 28px;
    }
    .demo-upload-list{
        display: inline-block;
        width: 110px;
        height: 110px;
        text-align: center;
        line-height: 110px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,.2);
        margin-right: 4px;
    }
    .demo-upload-list img{
        width: 100%;
        height: 100%;
    }
    .demo-upload-list-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .demo-upload-list:hover .demo-upload-list-cover{
        display: block;
    }
    .demo-upload-list-cover i{
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
</style>
<template>
    <Modal title="意向金" v-model="rDeal" :buyDealtitle="buyDealtitle" :closable="false" :mask-closable="false"
           class="rdeal "
           @on-ok="handleSubmit('xiading')" @on-cancel="handleReset('xiading')" width="640">
        <div slot="header">
            <a class="ivu-modal-close" @click="xiadingCancel" style="display: block!important;"><i
                    class="ivu-icon ivu-icon-ios-close-empty" ></i></a>
            <div class="ivu-modal-header-inner">意向金</div>
        </div>
        <div slot="footer">
            <Button type="text" size="large" @click="xiadingCancel">取消</Button>
            <Button type="primary" size="large" @click="xiadingOk" :disabled="isDisable">确定</Button>
        </div>
        <Form ref="xiading" :model="xiading" :rules="xiadingyanzheng" :label-width="100">
            <Row class="xiaDing">
                <Col :lg="10" :md="10">
                <Row>
                    <Col :lg="24" :md="24">
                    <p>客源编号：<span>{{customer.xuqiubianhao}}</span></p>
                    <p>客户姓名：<span>{{customer.customer_name}}</span></p>
                    </Col>
                    <Col :lg="24" :md="24" style="margin: 15px 0">
                    <Button type="primary" @click="ejectRoomxiadingshow">选择房源</Button>
                    <Modal v-model="ejectRoomxiading" title="二手房源" :transfer="false" width="960">
                        <div slot="header">
                            <a class="ivu-modal-close" @click="dealCancel" style="display: block!important;"><i
                                    class="ivu-icon ivu-icon-ios-close-empty" ></i></a>
                            <div class="ivu-modal-header-inner">二手房源</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="dealCancel">取消</Button>
                            <Button type="primary" size="large" @click="dealOk">确定</Button>
                        </div>
                        <Row :gutter="5">
                            <!-- <Col :lg="6" :md="6">
                            <Cascader :data="this.peizhi.villages" v-model="dts_id" placeholder="区域选择"  filterable
                                      change-on-select></Cascader>
                            </Col> -->
							 <Col :lg="6" :md="6">
							<Input placeholder="小区、房源编号、电话等" v-model="keyword" ></Input>
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
                           
                            <Col :lg="6" :md="6">
                            <Button type="primary" @click="gethouse">查询</Button>
                            <Button type="primary" @click="qkym">清空</Button>
                            </Col>
                        </Row>
                        <Row>
                            <Col :lg="24" :md="24" style="margin-top: 10px">
                            <Table :columns="xiadingColumns" highlight-row :data="xiadingData" border script
                                   @on-current-change="selectionok"></Table>
                            <div style="margin: 10px;overflow: hidden">
                                <div style="float: right;">
                                    <Page :total="pageTotal" :current="pageCurrent" @on-change="changePage"></Page>
                                </div>
                            </div>
                            </Col>
                        </Row>
                    </Modal>
                    </Col>
                    <Col :lg="24" :md="24">
                    <Tag  >
                        <p>房源编号：<span>{{selection_ok.house_sn}}</span></p>
                        <p>房源地址：<span>{{selection_ok.dts_name}} {{selection_ok.village_name}}</span></p>
                        <p>房源面积：<span>{{selection_ok.jianzhumianji}} 平方米</span></p>
                        <p>房源价格：<span>{{selection_ok.sell_price}} 万</span></p>
                    </Tag>
                    </Col>
                </Row>
                </Col>
                <Col :lg="14" :md="14">
                <Row>
                    <Col :lg="24" :md="24">
                    <FormItem label="下定金额" prop="xiadingjine" >
                        <Input v-model="xiading.xiadingjine"><span slot="append">元</span></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="24" :md="24">
                    <FormItem label="下定日期" prop="xiadingriqi_d"  :rules="{type:'array',required: true, message: '下定日期不能为空', trigger: 'blur'}">
                        <DatePicker type="date" @on-change="setPublishTime" v-model="xiading.xiadingriqi_d"
                                    format="yyyy-MM-dd" :transfer="true" style="width: 100%"></DatePicker>
                    </FormItem>
                    </Col>
                    <Col :lg="24" :md="24">
                    <FormItem label="选择部门" prop="bumen" :rules="{type:'array',required: true, message: '部门不能为空', trigger: 'blur'}">
                        <Cascader :data="this.peizhi.departlist" trigger="click" filterable change-on-select
                                  v-model="xiading.bumen" @on-change="changeDepart" :transfer="true"></Cascader>
                    </FormItem>
                    </Col>
                    <Col :lg="24" :md="24">
                    <FormItem label="经办人" prop="user" :rules="{required: true, message: '经办人不能为空', trigger: 'blur'}">
                        <Select v-model="xiading.user" placeholder="" :transfer="true">
                            <Option v-for="v in xiading.users" :value="v.u_id" :key="v.u_id">{{v.u_name}}
                            </Option>
                        </Select>
                    </FormItem>
                    </Col>
                    <Col :lg="24" :md="24">
                    <FormItem label="票据号" prop="piaojuhao" :rules="{required: true, message: '票据号不能为空', trigger: 'blur'}">
                        <Input v-model="xiading.piaojuhao"></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="24" :md="24">
                    <FormItem label="支付方式" prop="pay_way" :rules="{required: true, message: '支付方式不能为空', trigger: 'blur'}">
                        <Select placeholder="" v-model="xiading.pay_way">
                            <Option :value="item.child_name" v-for="(item,index) in peizhi.way">{{item.child_name}}</Option>
                        </Select>
                    </FormItem>
                    </Col>
                    <Col :lg="24" :md="24">
                    <FormItem label="预计成交金额" prop="yujijine" :rules="{required: true, message: '预计成交金额不能为空', trigger: 'blur'}">
                        <Input v-model="xiading.yujijine">

                            <span slot="append" v-if="this.$route.params.customer_type==1">元</span>
                        <span slot="append" v-else>万元</span>
                        </Input>
                    </FormItem>
                    </Col>
                    <Col :lg="24" :md="24">
                    <FormItem label="预计成交日期" prop="yujichengjiao_r" :rules="{type:'array',required: true, message: '预计成交日期不能为空', trigger: 'blur'}">
                        <DatePicker v-model="xiading.yujichengjiao_r" type="date" placeholder=""
                                    :transfer="true" @on-change="setPublishyujiTime" style="width: 100%"></DatePicker>
                    </FormItem>
                    </Col>
                </Row>
                </Col>
                <Col :lg="24" :md="24">
                <FormItem label="备注" prop="beizhu" :rules="{required: true, message: '备注不能为空', trigger: 'blur'}">
                    <Input type="textarea" v-model="xiading.beizhu" :autosize="{minRows: 2,maxRows: 5}"
                           placeholder=""></Input>
                </FormItem>
                </Col>
                <Col :lg="24" :md="24">
                <FormItem label="费用凭据" prop="">
                    <div class="demo-upload-list" v-for="item in uploadList">
                        <template>
                            <img :src="item.url">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-eye-outline" @click.native="handleView(item.name)"></Icon>
                                <Icon type="ios-trash-outline" @click.native="handleRemove(item)"></Icon>
                            </div>
                        </template>
                    </div>
                    <Upload ref="upload" :show-upload-list="false" :default-file-list="defaultList"
                            :headers="{'X-Access-Token':xtoken}"
                            :on-success="handleSuccess" :format="['jpg','jpeg','png']" :max-size="2048"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize" :before-upload="handleBeforeUpload" multiple
                            type="drag" :action="uploadurl" style="display: inline-block;width:120px;">
                        <div style="width: 120px;height:120px;line-height: 120px;">
                            <Icon type="camera" size="30"></Icon>
                        </div>
                    </Upload>
                    <Modal title="图片预览" v-model="visible" :transfer="false">
                        <img :src="imgurl + imgName" v-if="visible"
                             style="width: 100%">
                    </Modal>
                </FormItem>
                </Col>
            </Row>
        </Form>

    </Modal>
</template>
<script>
    import Cookies from 'js-cookie';
    export default {
        name: 'editDeal',
        props: ['rDeal', 'buyDealtitle', 'customer', 'peizhi'],
        data () {
            return {
                isDisable:false,
                show: true,
                ejectRoomxiading: false,
                selection: [],
                keyword: [],
                rDeal: '',
                pageTotal: '',
                pageCurrent: 1,
                selection_ok: '',
                /* dts_id:[Cookies.get('dts_id')], */
                searchData:{
                    loudong_name:'',
                    danyuan_name:'',
                    fanghao_name:'',
                },
                //二手房源
                xiadingColumns: [
                    {
                        title: '状态',
                        key: 'house_status',
                        align: 'center',
						 render: (h, params) => {
						    let ret = '';
						    if (params.row.house_status == '1') {
						        ret = '有效';
						    }else
							if (params.row.house_status == '2') {
							    ret = '成交';
							}
							if (params.row.house_status == '3') {
							    ret = '无效';
							}
						    return h('div', ret);
						}
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
                        title: '售价',
                        key: 'sell_price',
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
                xiading: {
                    xiadingjine: '',
                    xiadingriqi: '',
                    xiadingriqi_d: '',
                    bumen: '',
                    beizhu: '',
                    user: '',
                    users: '',
                    jingbanren: '',
                    piaojuhao: '',
                    yujijine: '',
                    yujichengjiao: '',
                    yujichengjiao_r: '',
                }, xiadingyanzheng: {
                    xiadingjine: [
                        {required: true, message: '请输入下定金额', trigger: 'blur'}
                    ]
                },
                xiadingData: [],
                // 图片上传
                xtoken: api_param.XAccessToken,
                uploadurl: api_param.apiurl + 'site/upload',
                imgurl: api_param.imgurl,
                defaultList: [],
                imgName: '',
                visible: false,
                uploadList: []
            };
        },
        methods: {

	    qkym(){
                this.searchData.loudong_name='';
                this.searchData.danyuan_name='';
                this.searchData.fanghao_name='';
                this.dts_id='';
                this.keyword='';
                this.gethouse();
            },

            // 图片上传
            handleView(name) {
                this.imgName = name;
                this.visible = true;
            },
            handleRemove(file) {
                const fileList = this.$refs.upload.fileList;
                this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
            },
            handleSuccess(res, file) {
                file.url = api_param.imgurl + res.data.url;
                file.name = res.data.url;
                this.uploadList.push({'name': file.name, 'url': file.url});
                const fileList = this.$refs.upload.fileList;
                this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
            },
            handleFormatError(file) {
                this.$Message.warning('文件格式不正确，请选择JPG或PNG');
            },
            handleMaxSize(file) {
                this.$Message.warning('文件  ' + file.name + ' 超过 2M.');
            },
            handleBeforeUpload() {
                const check = this.uploadList.length < 5;
                if (!check) {
                    this.$Message.warning('最多上传5张图片');
                }
                return check;
            },
            //修改信息
            modalOk () {
                this.editRoom = false;
                this.$emit('resetModal');
            }, changeDepart (value, selectedData) {
                this.xiading.bumen = selectedData;
                var d_id = value[value.length - 1];
                this.xiading.users = this.peizhi.users[d_id];
            }, gethouse () {
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
                        'loudong_name': this.searchData.loudong_name,
                        'danyuan_name': this.searchData.danyuan_name,
                        'fanghao_name': this.searchData.fanghao_name,
                        'dts_id': this.dts_id,
                        'sale_type' :this.sale_type,
//			            'u_status': status,
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.xiadingData = response.data.data.list;
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
            },setPublishTime (datetime) {
                this.xiading.xiadingriqi=datetime;
                this.xiading.xiadingriqi_d=[datetime];
            },
            setPublishyujiTime (datetime) {
                this.xiading.yujichengjiao = datetime;
                this.xiading.yujichengjiao_r = [datetime];
            },
            xiadingOk () {
                this.xiading.image = [];
                if(this.uploadList.length >=1){
                    this.uploadList.forEach((item,index)=>{
                        this.xiading.image.push(item.name);
                    })
                }
                var data = {
                    xiadingjine: this.xiading.xiadingjine,
                    xiadingriqi: this.xiading.xiadingriqi,
                    bumen: this.xiading.bumen,
                    beizhu: this.xiading.beizhu,
                    jingbanren_id: this.xiading.user,
                    piaojuhao: this.xiading.piaojuhao,
                    pay_way: this.xiading.pay_way,
                    yujijine: this.xiading.yujijine,
                    yujichengjiao: this.xiading.yujichengjiao,
                    house_uuid: this.selection_ok.house_uuid,
                    customer_uuid: this.$route.params.customer_uuid,
                    image:this.xiading.image,
                };
                if(this.selection_ok.house_uuid){
                    this.$refs['xiading'].validate((valid) => {
                        if (valid) {
                            this.isDisable=true;
                            this.$http.post(api_param.apiurl + '/customer_xiading/add',
                                data,
                                {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                            ).then(function (response) {
                                // 这里是处理正确的回调
                                this.xiadingCancel();
                                this.$Message.success(response.data.message);

                            }, function (response) {
                                // 这里是处理错误的回调
                                //console.log(response)
                                this.xiadingCancel();
                                this.$Message.warning('更新失败');
                            });
                        }
                    });
                }else{
                    this.$Message.warning('请选择下定房源');
                }


            },
            ejectRoomxiadingshow () {
                this.ejectRoomxiading = true;
                this.gethouse();
            }
            ,
            changePage (page) {//分页
                this.pageCurrent = page;
                this.gethouse();
            }
            ,
            selectionok (currentRow, oldCurrentRow) {
                this.selection = currentRow;
            }
            ,
            xiadingCancel () {
                this.rDeal = false;
                this.selection_ok =[];
                this.selection =[];
                this.$refs['xiading'].resetFields();//清空form规则检查
                setTimeout(() => {
                    this.isDisable=false;
                }, 1000)
                this.$emit('resetok');
            }
            ,
            //买卖成交
            dealOk () {
                this.selection_ok = this.selection;
                this.ejectRoomxiading = false;
            }
            ,
            dealCancel () {
                this.ejectRoomxiading = false;
            }
            ,
            handleClose () {
                this.show = false;
            }
        },
        mounted() {
            this.uploadList = this.$refs.upload.fileList;
        }

    }
    ;
</script>
