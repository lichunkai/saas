<style scoped>
    .ivu-form-item {
        margin-bottom: 15px !important;
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
    <Row :gutter="5">
        <Col :lg="16" :md="16">
        <Card>
            <p slot="title">
                佣金记录
            </p>
            <Button type="primary" @click="addCommission" slot="extra" style="margin-top: 4px">佣金收支</Button>
            <Row>
                <Col :lg="24" :md="24">
                <Table :columns="commissionColumns" :data="commissionList.list" border script height="520"></Table>
                </Col>
                <Col :lg="24" :md="24" style="margin-top: 10px;color: #FF0000">
                <Row>
                    <Col :lg="8" :md="8">
                    业主佣金收入：<span v-if="commissionList.yy_shouqu.num">{{commissionList.yy_shouqu.num}}</span><span v-else>0</span>-<span v-if="commissionList.yy_zhichu.num">{{commissionList.yy_zhichu.num}}</span><span v-else>0</span>=<span>{{commissionList.yy_shouqu.num-commissionList.yy_zhichu.num}}</span>
                    </Col>
                    <Col :lg="8" :md="8">
                    客户佣金收入：<span v-if="commissionList.yk_shouqu.num">{{commissionList.yk_shouqu.num}}</span><span v-else>0</span>-<span v-if="commissionList.yk_zhichu.num">{{commissionList.yk_zhichu.num}}</span><span v-else>0</span>=<span>{{commissionList.yk_shouqu.num-commissionList.yk_zhichu.num}}</span>
                    </Col>
                    <Col :lg="8" :md="8">
                    杂项收入：<span v-if="commissionList.z_shouqu.num">{{commissionList.z_shouqu.num}}</span><span v-else>0</span>-<span v-if="commissionList.z_zhichu.num">{{commissionList.z_zhichu.num}}</span><span v-else>0</span>=<span>{{commissionList.z_shouqu.num-commissionList.z_zhichu.num}}</span>
                    </Col>
                </Row>
                </Col>
            </Row>
            <Modal v-model="editMoney" title="新增佣金收支" :mask-closable="false">
                <div slot="header">
                    <a class="ivu-modal-close" @click="commissionCancel"><i
                            class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                    <div class="ivu-modal-header-inner">新增佣金收支</div>
                </div>
                <div slot="footer">
                    <Button type="text" size="large" @click="commissionCancel">取消</Button>
                    <Button type="primary" size="large" @click="commissionOk">确定</Button>
                </div>
                <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="88">
                    <Row :gutter="5">
                        <Col :lg="12" :md="12">
                        <FormItem label="费用类型" prop="cost_type">
                            <Select placeholder="" v-model="formValidate.cost_type" @on-change="changeProject">
                                <Option value="1">佣金收入</Option>
                                <Option value="2">杂项收入</Option>
                                <Option value="3">折佣收入</Option>
                            </Select>
                        </FormItem>
                        </Col>

                        <Col :lg="12" :md="12">
                        <FormItem label="费用项目" prop="cost_project">
                            <Select placeholder="" v-model="formValidate.cost_project">
                                <Option :value="item.child_name" v-for="(item,index) in projectList.data">{{item.child_name}}</Option>
                            </Select>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row :gutter="5">
                        <Col :lg="12" :md="12">
                        <FormItem label="费用用途" prop="cost_purpose">
                            <Select placeholder="" v-model="formValidate.cost_purpose">
                                <Option value="1">收入</Option>
                                <Option value="2">支出</Option>
                            </Select>
                        </FormItem>
                        </Col>
                        <Col :lg="12" :md="12">
                        <FormItem label="交费人" prop="cost_payer">
                            <Select placeholder="" v-model="formValidate.cost_payer">
                                <Option value="1">业主</Option>
                                <Option value="2">客户</Option>
                                <Option value="3">成交人</Option>
                            </Select>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row :gutter="5">
                        <Col :lg="12" :md="12">
                        <FormItem label="金额" prop="cost_money">
                            <Input v-model="formValidate.cost_money" >
                            <span slot="append">元</span>
                            </Input>
                        </FormItem>
                        </Col>
                        <Col :lg="12" :md="12">
                        <FormItem label="收费日期" prop="cost_day">
                            <DatePicker type="date" :value="formValidate.cost_day" @on-change="formValidate.cost_day=$event" format="yyyy-MM-dd" placeholder="" :transfer="true"></DatePicker>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col :lg="24" :md="24">
                        <FormItem label="备注" prop="cost_remark">
                            <Input v-model="formValidate.cost_remark"></Input>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row :gutter="5">
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
                                <div style="width: 108px;height:108px;line-height: 108px;">
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
        </Card>
        </Col>
        <Modal title="查看凭据" v-model="visibleModal">
            <div slot="header">
                <a class="ivu-modal-close" @click="visibleModal = false" style="display: block!important">
                    <i class="ivu-icon ivu-icon-ios-close-empty"></i>
                </a>
                <div class="ivu-modal-header-inner">查看凭据</div>
            </div>
            <Carousel v-if="visibleModal"  dots="none" value="0">
                <CarouselItem v-for="image in imageData.list">
                    <img v-if="image" :src="imgurl + image" style="width: 100%">
                </CarouselItem>
            </Carousel>
        </Modal>
    </Row>
</template>

<script>
    export default {
        name: "editMoney",
        props: ['commissionList','orderId'],
        data() {
            const validateNumber = (rule, value, callback) => {
                var errors = [];
                if(!value){
                    callback('请输入数字');
                }
                if (!/^\d+(?=\.{0,1}\d+$|$)/.test(value)) {
                    callback('请输入正确的数字');
                }
                callback(errors);
            };
            return {
                editMoney: false,
                visibleModal:false,
                imageData:{
                    list:[]
                },
                projectList:{
                    data:[]
                },
                formValidate:{
                    order_id:'',
                    cost_type:'',
                    cost_purpose:'',
                    cost_project:'',
                    cost_payer:'',
                    cost_money:'',
                    cost_day:'',
                    cost_image:[],
                    cost_remark:'',
                },
                ruleValidate:{
                    cost_type:[{required: true, message: '请选择费用类型', trigger: 'change'}],
                    cost_project:[{required: true, message: '请选择费用项目', trigger: 'change'}],
                    cost_purpose:[{required: true, message: '请选择费用用途', trigger: 'change'}],
                    cost_payer:[{required: true, message: '请选择交费人', trigger: 'change'}],
                    cost_money:[{required: true,validator: validateNumber, trigger: 'blur'}],
                    cost_day:[{required: true, message: '请选择缴费日期', trigger: 'change'}],
                },
                commissionColumns: [
                    {
                        title: '费用类型',
                        key: 'cost_type',
                        align: 'center',
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.cost_type == 1){
                                texts = '佣金收入';
                            }else if(params.row.cost_type == 2){
                                texts = '杂项收入';
                            }else if(params.row.cost_type == 3){
                                texts = '折佣收入';
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '费用项目',
                        key: 'cost_project',
                        align: 'center'
                    },
                    {
                        title: '收入/支出',
                        key: 'cost_purpose',
                        align: 'center',
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.cost_purpose == 1){
                                texts = '收入';
                            }else{
                                texts = '支出';
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '费用金额',
                        key: 'cost_money',
                        align: 'center',
                    },
                    {
                        title: '交费人',
                        key: 'cost_payer',
                        align: 'center',
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.cost_payer == 1){
                                texts = '业主';
                            }else if(params.row.cost_payer == 2){
                                texts = '客户';
                            }else if(params.row.cost_payer == 3){
                                texts = '成交人';
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '收取时间',
                        key: 'cost_day',
                        align: 'center',
                    },
                    {
                        title: '收取人',
                        key: 'u_name',
                        align: 'center',
                    },
                    {
                        title: '图片',
                        key: 'cost_image',
                        align: 'center',
                        width: 100,
                        render: (h, params) => {

                                return h('div', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    style: {
                                        color: '#2d8cf0',
                                        cursor: 'pointer'
                                    },
                                    domProps: {
                                        innerHTML: '查看'
                                    },
                                    on: {
                                        click: () => {
                                            this.visibleModal = true;
                                            this.$set(this.imageData,'list',JSON.parse(params.row.cost_image));
                                            // console.log(this.imageData);
                                        }
                                    }
                                })

                        }
                    },
                    {
                        title: '备注',
                        key: 'cost_remark',
                        align: 'center',
                    },
                    {
                        title: '收取状态',
                        key: 'cost_status',
                        align: 'center',
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.cost_status == 1){
                                texts = '已确认';
                            }else if(params.row.cost_status == 2){
                                texts = '已驳回';
                            }else{
                                texts = '未确认';
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '财务审核',
                        key: 'finance_status',
                        align: 'center',
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.cost_status == 1){
                                texts = '已确认';
                            }else if(params.row.cost_status == 2){
                                texts = '已驳回';
                            }else{
                                texts = '审核中';
                            }
                            return h('div', {props: {},},texts)
                        }
                    }
                ],
                commissionData: [],
                // 图片上传
                xtoken: api_param.XAccessToken,
                uploadurl: api_param.apiurl + 'site/upload',
                imgurl: api_param.imgurl,
                defaultList: [],
                imgName: '',
                visible: false,
                uploadList: [],
                listData:[]
            }
        },
        methods:{

            // 图片上传
            handleView(name) {
                this.imgName = name;
                this.visible = true;
            },
            handleRemove(file) {
                this.uploadList.splice(this.uploadList.indexOf(file), 1);
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
            //添加佣金
            addCommission(){
                this.editMoney = true;
            },
            changeProject(selectdata){
                if(selectdata == 1){
                    this.$set(this.projectList,'data',this.commissionList.yongjin);
                }else if(selectdata == 2){
                    this.$set(this.projectList,'data',this.commissionList.zaxiang);
                }else if(selectdata == 3){
                    this.$set(this.projectList,'data',this.commissionList.zheyong);
                }
            },
            commissionCancel(){
                this.$refs['formValidate'].resetFields();
                this.uploadList = [];
                this.editMoney = false;
            },
            commissionOk(){
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        this.formValidate.order_id = this.orderId;
                        this.formValidate.cost_image = [];
                        if(this.uploadList.length >=1){
                            this.uploadList.forEach((item,index)=>{
                                this.formValidate.cost_image.push(item.name);
                            })
                        }
                        this.$http.post(api_param.apiurl + 'ordersell/addcommission',
                            {
                                data: this.formValidate
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['formValidate'].resetFields();
                                this.uploadList = [];
                                let cost = response.data.data;
                                this.editMoney = false;
                                this.$Message.success('添加成功');
                                this.$emit('getInfo');
                            } else if (response.data.code == 401) {
                                this.$store.commit('logout', this);
                                this.$store.commit('clearOpenedSubmenu');
                                this.$router.push({
                                    name: 'login'
                                });
                            } else if (response.data.code == 403) {
                                this.$Message.warning(response.data.message);
                                this.editMoney = false;
                                this.$store.commit('removeTag', 'bargainDetails');
                                this.$router.push({name: 'bargainSell_index',})
                            }else{
                                this.$Message.warning(response.data.message);
                            }
                        }, function (response) {
                            // 这里是处理错误的回调
                            console.log(response)
                        })
                    }
                });
            },
        },
        mounted() {
            this.uploadList = this.$refs.upload.fileList;
        }
    }
</script>