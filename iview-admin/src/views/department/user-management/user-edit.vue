<style lang="less">
    .demo-upload-list {
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
    .imgTitle{
        text-align: center;
        font-size: 20px;
        color: #919191;
        bottom: 30px;
    }
</style>

<template>
    <Modal v-model="editUser" ok-text="确认" cancel-text="取消" :mask-closable="false">
        <div slot="header">
            <a class="ivu-modal-close" @click="modalCancel" style="display: block!important;"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
            <div class="ivu-modal-header-inner">{{usermodaltitle}}</div>
        </div>
        <div slot="footer">
            <Button type="text" size="large" @click="modalCancel">取消</Button>
            <Button type="primary" size="large" @click="modalOk">确定</Button>
        </div>
        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
            <Row>
                <Col span="12">
                <FormItem label="员工名称" prop="u_name">
                    <Input v-model="formValidate.u_name"></Input>
                </FormItem>
                </Col>
                <Col span="12">
                <FormItem label="手机号码" prop="u_phone">
                    <Input v-model="formValidate.u_phone"></Input>
                </FormItem>
                </Col>
                <Col span="12">
                <FormItem label="员工微信" prop="u_wx">
                    <Input v-model="formValidate.u_wx"></Input>
                </FormItem>
                </Col>
                <Col span="12">
                <FormItem label="门店地址" prop="u_address">
                    <Input v-model="formValidate.u_address"></Input>
                </FormItem>
                </Col>
                <Col span="12">
                <FormItem label="员工编号" prop="u_employee_prefix">
                    <Input  v-model="formValidate.u_employee_no" readonly>
                    <Select v-model="formValidate.u_employee_prefix" slot="prepend" :label-in-value="true" style="width: 80px" @on-change="searchPrefix" :transfer="true">
                        <Option v-for="(item,index) in prefix" :value="item">{{item}}</Option>
                    </Select>
                    </Input>
                </FormItem>
                </Col>
                <Col span="12" v-if="formValidate.u_uuid == ''">
                <FormItem label="登录密码" prop="u_password">
                <Input v-model="formValidate.u_password" type="password" ></Input>
                </FormItem>
                </Col>
                <Col span="12">
                <FormItem label="员工性别" prop="u_sex">
                    <Select v-model.sync="formValidate.u_sex" :label-in-value="true" :transfer="true">
                        <Option value="1">男</Option>
                        <Option value="2">女</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col span="12">
                <FormItem label="员工状态" prop="u_status">
                    <Select v-model.sync="formValidate.u_status" :label-in-value="true" @on-change="checkUserinfo" :transfer="true">
                        <Option value="1">在职</Option>
                        <Option value="2">休假</Option>
                        <Option value="3">锁定</Option>
                        <Option value="4">离职</Option>
                        <Option value="5">开除</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col span="12">
                <FormItem label="入职时间" prop="u_entry_time">
                    <DatePicker :value="formValidate.u_entry_time" type="date" format="yyyy-MM-dd" @on-change="formValidate.u_entry_time=$event" :transfer="true"></DatePicker>
                </FormItem>
                </Col>

                <Col span="12">
                <FormItem label="出生日期" prop="u_birthday_time">
                    <DatePicker :value="formValidate.u_birthday_time" type="date" format="yyyy-MM-dd" @on-change="formValidate.u_birthday_time=$event" :transfer="true"></DatePicker>
                </FormItem>
                </Col>

                <Col span="12">
                <FormItem label="用户角色" prop="u_role_id">
                    <Select v-model.sync="formValidate.u_role_id" :label-in-value="true" @on-change="searchRole" :transfer="true">
                        <Option v-for="item in userRoles" :value="item.value" :key="item.value">{{item.label }}</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col span="12">
                <FormItem label="所属部门" prop="u_dept_id">
                    <Cascader :data="departData" :value.sync="formValidate.departpath" filterable change-on-select @on-change="searchDepart"  :transfer="true"></Cascader>
                </FormItem>
                </Col>
            </Row>
            <Row>
            	<Col :lg="24" :md="24">

            		<FormItem label="头像上传" >
            			<p style="color: #999;">（上传个人图像，便于生成电子名片。请上传证件照(335*300的照片，否则无法正常显示)。）</p>
            		</FormItem>
            	</Col>
                <Col :lg="24" :md="24">
                	<FormItem label="">
                <div class="demo-upload-list" v-if="formValidate.is_touxiang" >
                    <img :src="imgurl+formValidate.u_head_url">
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-eye-outline" @click.native="handleView()"></Icon>
                        <Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
                    </div>
                </div>

                <Upload
                        ref="upload"
                        :headers="{'X-Access-Token':xtoken}"
                        :show-upload-list="false"
                        :on-success="handleSuccess"
                        :format="['jpg','jpeg','png','gif']"
                        :max-size="10240"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        multiple
                        type="drag"
                        :action="uploadurl"
                        style="display: inline-block;width:108px;">
                    <div style="width: 108px;height:108px;line-height: 108px;">
                        <Icon type="camera" size="20"></Icon>
                    </div>
                </Upload>
                </FormItem>
                </Col>

            </Row>
        </Form>
        <Modal title="查看大图" v-model="visible" :transfer="false">
            <img :src="imgurl+ formValidate.u_head_url" v-if="visible"
                 style="width: 100%">
        </Modal>
    </Modal>
</template>

<script>
    export default {
        name: 'newEdit',
        props: ['editUser', 'usermodaltitle', 'formValidate', 'departData','tichengData','prefix', 'userRoles','userRanks', 'status'],
        data() {
            const validateMobileCheck = (rule, value, callback) => {
                    if (!/^1[345789]\d{9}$/.test(value)) {
                        callback(new Error('请输入正确的手机号码!'));
                    } else {

                        this.$http.post(api_param.apiurl + 'user/checkmobile',
                            {
                                'mobile': value,
                                'u_uuid':this.formValidate.u_uuid,
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                callback(new Error('手机号已存在!'));
                            } else if (response.data.code == 401) {
                                this.$store.commit('logout', this);
                                this.$store.commit('clearOpenedSubmenu');
                                this.$router.push({
                                    name: 'login'
                                });
                            }else{
                                callback();
                            }
                        }, function (response) {
                            // 这里是处理错误的回调
                            console.log(response)
                        })
                    }
            };
            return {
                data:{},
                visible:false,
                checkmobile : '',
                imgurl: api_param.imgurl,
                is_touxiang:false,
                xtoken: api_param.XAccessToken,
                uploadurl: api_param.apiurl + 'site/upload',
                formValidate: {
                    u_uuid: '',
                    u_name: '',
                    u_sex: '',
                    u_phone: '',
                    u_password: '',
                    u_employee_prefix: '',
                    u_employee_no: '',
                    u_dept_id: '',
                    u_role_id: '',
                    u_status:'',
                    u_entry_time:'',
                    u_birthday_time: '',
                },
                ruleValidate: {
                    u_name: [
                        {required: true, message: '请输入用户名', trigger: 'blur'}
                    ],
                    u_sex: [
                        {required: true, message: '请选择性别', trigger: 'change'}
                    ],
                    u_phone: [
                        {required: true, message: '请输入电话', trigger: 'blur'},
                        {validator: validateMobileCheck, trigger: 'blur'}
                    ],
                    u_employee_prefix: [
                        {required: true, message: '请选择编号前缀', trigger: 'change'},
                    ],
//                    u_employee_id: [
//                        {required: true, message: '请输入编号', trigger: 'blur'},
//                        {validator: validateNoCheck, trigger: 'blur'}
//                    ],
                    u_password: [
                        {required: true, message: '请输入密码', trigger: 'blur'},
                    ],
                    u_dept_id: [
                        {required: true, message: '请选择部门', trigger: 'change'},
                    ],
                    u_role_id: [
                        {required: true, message: '请选择用户角色', trigger: 'change'},
                    ],
                    u_entry_time: [
                        {required: true, message: '请输入入职时间', trigger: 'change'},
                    ],
                    u_status: [
                        {required: true, message: '请选择用户状态', trigger: 'change'},
                    ]
                },
            };
        },
        methods: {
            handleView(){
                this.visible=true
            },
            handleRemove(){
                this.$emit('set_u_head_url','')
                this.formValidate.is_touxiang=false
            },
            handleSuccess(res, file, filelist) {//上传成功
                this.$emit('set_u_head_url',res.data.url)
                this.formValidate.u_head_url=res.data.url
                this.formValidate.is_touxiang=true

            },
            handleFormatError(file) {
                this.$Notice.warning({
                    title: '文件上传失败',
                    desc: '文件 ' + file.name + ' 类型错误, 请选择 gif, jpg or png.'
                });
            },
            handleMaxSize(file) {
                this.$Notice.warning({
                    title: '文件上传失败',
                    desc: '文件  ' + file.name + ' 大小必须小于 10M.'
                });
            },
            setEntrytime(event){
                this.formValidate.u_entry_time=event;
            },
            searchDepart(value, selectedData){
                this.formValidate.u_dept_id = value[value.length - 1];//console.log(this.formValidate.depart);
            },
            searchticheng(value, selectedData){
                this.formValidate.ticheng_id = value[value.length - 1];//console.log(this.formValidate.depart);
                this.formValidate.ticheng = value;//console.log(this.formValidate.depart);
            },
            searchRole (selectedData) { //角色选择
                this.formValidate.u_role_id = selectedData.value;//console.log(this.formValidate.role);
            },
            searchRank (selectedData) { //职级选择
                this.formValidate.u_rank_id = selectedData.value;//console.log(this.formValidate.role);
            },
            //根据前缀获取标号
            searchPrefix(selectedData){
                this.formValidate.u_employee_prefix = selectedData.value;
            },
            checkUserinfo(selectData){ //检查用户数据
                if(selectData.value == 4 || selectData.value == 5){
                    this.$http.post(api_param.apiurl + 'user/checkuserinfo',
                        {
                            'u_uuid': this.formValidate.u_uuid,
                        },
                        {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                    ).then(function (response) {
                        // 这里是处理正确的回调
                        if (response.data.code == 200) {
                            this.formValidate.u_status = '';
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
                        console.log(response)
                    })
                }else{
                    this.formValidate.u_status = selectData.value;
                }
            },
            modalOk() {
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        let action = 'user/add';
                        if(this.formValidate.u_uuid){
                            action = 'user/edit';
                        }
                        if(this.formValidate.u_status){
                            this.$http.post(api_param.apiurl + action,
                                {
                                    'u_uuid': this.formValidate.u_uuid,
                                    'u_name': this.formValidate.u_name,
                                    'u_sex': this.formValidate.u_sex,
                                    'u_phone': this.formValidate.u_phone,
                                    'u_password': this.formValidate.u_password,
                                    'u_employee_prefix': this.formValidate.u_employee_prefix,
                                    'u_employee_no': this.formValidate.u_employee_no,
                                    'u_dept_id': this.formValidate.u_dept_id,
                                    'u_role_id': this.formValidate.u_role_id,
                                    'u_head_url': this.formValidate.u_head_url,
                                    'u_wx': this.formValidate.u_wx,
                                    'u_address': this.formValidate.u_address,
                                    'u_entry_time': this.formValidate.u_entry_time,
                                    'u_birthday_time': this.formValidate.u_birthday_time,
                                    'u_status':this.formValidate.u_status,
                                },
                                {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                            ).then(function (response) {
                                // 这里是处理正确的回调
                                if (response.data.code == 200) {
                                    this.editUser = false;
                                    this.formValidate.ticheng=[];
                                    this.$refs['formValidate'].resetFields();
                                    this.formValidate.images='';
                                    this.$emit('getIndex');
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
                        }
                    }
                });
            },
            modalCancel(){
                this.$refs['formValidate'].resetFields();//清空form规则检查
                this.editUser = false;
                this.formValidate.ticheng=[];
                this.formValidate.images='';
                this.is_touxiang=false;
                this.$emit('resetModal');
            },
        },
        computed: {}
    };
</script>
