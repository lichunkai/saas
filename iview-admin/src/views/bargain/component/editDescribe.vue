<style scoped>
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
    <Row>
        <Col :lg="18" :md="18" class="editBorder">
        <Row :gutter="10" style="margin-top: 10px">
            <Col :lg="24" :md="24">
            <Row>
                <Col :lg="6" :md="6">
                <Button type="text">代收付款记录</Button>
                </Col>
                <Col :lg="18" :md="18">
                <Row type="flex" justify="end">
                    <Col>
                    <Button type="primary" @click="addCollection">新增</Button>
                    <!--<Button type="primary">导出当前页</Button>-->
                    <Modal v-model="addDescribe" title="新增代收付款" :mask-closable="false">
                        <div slot="header">
                            <a class="ivu-modal-close" @click="collectionCancel"><i
                                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                            <div class="ivu-modal-header-inner">新增代收付款</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="collectionCancel">取消</Button>
                            <Button type="primary" size="large" @click="collectionOk">确定</Button>
                        </div>
                        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
                            <Row>
                                <Col :lg="12" :md="12">
                                <FormItem label="票据号" prop="collection_no">
                                    <Input v-model="formValidate.collection_no" placeholder=""></Input>
                                </FormItem>
                                </Col>
                                <Col :lg="12" :md="12">
                                <FormItem label="代收付款" prop="collection_type">
                                    <Select placeholder="" v-model="formValidate.collection_type">
                                        <Option value="1">代收款</Option>
                                        <Option value="2">代付款</Option>
                                    </Select>
                                </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <Col :lg="12" :md="12">
                                <FormItem label="费用类型" prop="collection_purpose">
                                    <Select placeholder="" v-model="formValidate.collection_purpose">
                                        <Option :value="item.child_name" v-for="(item,index) in collectionList.yongtu">{{item.child_name}}</Option>
                                    </Select>
                                </FormItem>
                                </Col>
                                <Col :lg="12" :md="12">
                                <FormItem label="金额" prop="collection_money">
                                    <Input v-model="formValidate.collection_money" >
                                    <span slot="append">元</span>
                                    </Input>
                                </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <Col :lg="12" :md="12">
                                <FormItem label="收取方/代付方" prop="collection_payer">
                                    <Select placeholder="" v-model="formValidate.collection_payer">
                                        <Option value="1">业主</Option>
                                        <Option value="2">客户</Option>
                                    </Select>
                                </FormItem>
                                </Col>
                                <Col :lg="12" :md="12">
                                <FormItem label="收费日期" prop="collection_day">
                                    <DatePicker type="date" :value="formValidate.collection_day" @on-change="formValidate.collection_day=$event" format="yyyy-MM-dd" placeholder="" :transfer="true"></DatePicker>
                                </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <Col :lg="12" :md="12">
                                <FormItem label="支付方式" prop="collection_way">
                                    <Select placeholder="" v-model="formValidate.collection_way">
                                        <Option :value="item.child_name" v-for="(item,index) in collectionList.way">{{item.child_name}}</Option>
                                    </Select>
                                </FormItem>
                                </Col>
                            </Row>
                            <Row>
                                <Col :lg="12" :md="12">
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
                    </Col>
                </Row>
                </Col>
            </Row>
            </Col>
            <Col :lg="24" :md="24" style="margin-top: 10px">
            <Table :columns="collectionColumns" :data="collectionList.list" height="560" border script></Table>
            </Col>
            <Col :lg="24" :md="24" style="margin-top: 10px;color: #FF0000">
            <Row>
                <Col :lg="3" :md="3">
                代收款：<span v-if="collectionList.daishou.num">{{collectionList.daishou.num}}</span><span v-else>0</span>
                </Col>
                <Col :lg="3" :md="3">
                待付款：<span  v-if="collectionList.daifu.num">{{collectionList.daifu.num}}</span><span v-else>0</span>
                </Col>
            </Row>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: "editDescribe",
        props: ['collectionList','orderId'],
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
                addDescribe: false,
                formValidate:{
                    collection_no:'',
                    collection_type:'',
                    collection_purpose:'',
                    collection_way:'',
                    collection_money:'',
                    collection_payer:'',
                    collection_day:'',
                    collection_image:'',
            },
                ruleValidate:{
                    collection_no:[{required: true, message: '请填写票据号', trigger: 'blur'}],
                    collection_type:[{required: true, message: '请选择代收付款类型', trigger: 'change'}],
                    collection_purpose:[{required: true, message: '请选择费用用途', trigger: 'change'}],
                    collection_money:[{required: true,validator: validateNumber, trigger: 'blur'}],
                    collection_payer:[{required: true, message: '请选择收取方/代付方', trigger: 'change'}],
                    collection_day:[{required: true, message: '请选择操作日期', trigger: 'change'}],
                    collection_way:[{required: true, message: '请选择支付方式', trigger: 'change'}],
                },
                collectionColumns: [
                    {
                        title: '票据号',
                        key: 'collection_no',
                        align: 'center'
                    },
                    {
                        title: '代收付款',
                        key: 'collection_type',
                        align: 'center',
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.collection_type == 1){
                                texts = '代收款';
                            }else{
                                texts = '代付款';
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '费用类型',
                        key: 'collection_purpose',
                        align: 'center'
                    },
                    {
                        title: '支付方式',
                        key: 'collection_way',
                        align: 'center'
                    },
                    {
                        title: '费用金额',
                        key: 'collection_money',
                        align: 'center'
                    },
                    {
                        title: '收取方/代付方',
                        key: 'collection_payer',
                        align: 'center',
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.collection_payer == 1){
                                texts = '业主';
                            }else{
                                texts = '客户';
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '操作人',
                        key: 'u_name',
                        align: 'center',
                    },
                    {
                        title: '操作日期',
                        key: 'collection_day',
                        align: 'center'
                    }
                ],
                collectionList: [],
                // 图片上传
                xtoken: api_param.XAccessToken,
                uploadurl: api_param.apiurl + 'site/upload',
                imgurl: api_param.imgurl,
                defaultList: [],
                imgName: '',
                visible: false,
                uploadList: []
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
            //添加代收付款
            addCollection(){
                this.addDescribe = true;
            },
            collectionCancel(){
                this.$refs['formValidate'].resetFields();
                this.uploadList = [];
                this.addDescribe = false;
            },
            collectionOk(){
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        this.formValidate.order_id = this.orderId;
                        this.formValidate.collection_image = [];
                        if(this.uploadList.length >=1){
                            this.uploadList.forEach((item,index)=>{
                                this.formValidate.collection_image.push(item.name);
                            })
                        }
                        this.$http.post(api_param.apiurl + 'ordersell/addcollection',
                            {
                                data: this.formValidate
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['formValidate'].resetFields();
                                this.addDescribe = false;
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
                                this.addDescribe = false;
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