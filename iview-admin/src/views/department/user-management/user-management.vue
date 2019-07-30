<style lang="less">
@import "user.less";

</style>

<template>
    <div class="userment">
        <div :style="{marginButton:'10'}">
            <Card>
                <Row :gutter='5'>
                    <Col :lg='3' :md='3'>
                    <Cascader :data="departData" :value.sync="departkey" filterable change-on-select
                              @on-change="searchDepart"  placeholder="部门选择"></Cascader>
                    </Col>

                    <Col :lg='2' :md='2'>
                    <Select v-model.sync="rolekey" :label-in-value="true" @on-change="searchRole" placeholder="角色选择" :transfer="true">
                        <Option v-for="item in userRoles" :value="item.value" :key="item.value">{{ item.label }}
                        </Option>
                    </Select>
                    </Col>

                    <Col :lg='2' :md='2'>
                    <Select v-model="statuskey" :label-in-value="true" placeholder="状态选择" :transfer="true">
                        <Option v-for="item in status" :value="item.value" :key="item.value">{{ item.label }}</Option>
                    </Select>
                    </Col>

                    <Col :lg="3" :md="3">
                    <DatePicker type="daterange" :value="daterange" @on-change="selectDate" format="yyyy/MM/dd"
                                placeholder="选择时间段" :transfer="true" style="width:100%"></DatePicker>
                    </Col>

                    <Col :lg='3' :md='3'>
                    <Input v-model="keyword" placeholder="员工名称、员工编号、员工电话"></Input>
                    </Col>

                    <Col :lg='4' :md='4'>
                    <Button type="primary" @click="doSearch">查询</Button>
                    <Button type="primary" @click="clearSearch">清空</Button>
                    </Col>
                    <Col :lg='2' :md="2" offset="5">
                    <Row type="flex" justify="end">
                        <Col v-if="topbutton.add == 1">
                        <Button @click="addUser" type="primary">新增</Button>
                        </Col>
                    </Row>
                    </Col>
                </Row>
            </Card>
        </div>
        <Col style="margin-top: 10px">
        <Card>
            <Row>
                <Col>
                <Table :data="userList" :columns="tableColumns" stripe border></Table>
                <div style="margin: 10px;overflow: hidden">
                    <div style="float: right;">
                        <Page :total="totalnum" :current="currentpage" @on-change="changePage" show-total></Page>
                    </div>
                </div>
                </Col>
            </Row>
        </Card>
        </Col>
        <!--权限设置-->
        <Modal v-model="authUser" width="1160" :title="authmodaltitle" :mask-closable="false">
            <div slot="header">
                <a class="ivu-modal-close" @click="authmodalCancel"><i
                        class="ivu-icon ivu-icon-ios-close-empty" style="display: block!important;"></i></a>
                <div class="ivu-modal-header-inner">{{authmodaltitle}}</div>
            </div>
            <Form ref="authsValidate" :model="authsValidate" :rules="arulesValidate" :label-width="80">
                <Tabs type="card" class="list" :animated="false" :value="showtab">
                    <TabPane v-for="(purview,indexs) in purviews" :label="indexs" :name="indexs">
                        <ul class="rolemainul">
                            <li class="rolemain" v-for="(item,keys) in purview">
                                <div class="rolemainlist">
                                    <div class="rolewidth">{{item.p_name}}</div>
                                </div>
                                <div v-for="(value,key) in item.action" class="rolemainlist">
                                    <div class="rolewidth" v-if="value.p_type==0">
                                        <span>{{ value.p_name }}</span>
                                        <Select v-model.sync="purviewdata[value.p_id]" size="small"
                                                :label-in-value="true" style="width: 70px" :transfer="true">
                                            <Option v-for="(item,index) in dataauths" :value="index" :key="index">{{
                                                item }}
                                            </Option>
                                        </Select>
                                    </div>
                                    <div class="rolewidth" v-else-if="value.p_type==1">
                                        <Checkbox v-model.sync="purviewdata[value.p_id]">{{value.p_name}}</Checkbox>
                                    </div>
                                    <div class="rolewidth" v-else>
                                        <span>--</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </TabPane>
                </Tabs>
            </Form>
            <div slot="footer">
                <Button type="text" size="large" @click="authmodalCancel">取消</Button>
                <Button type="primary" size="large" @click="authmodalOk">确定</Button>
            </div>
        </Modal>
        <!--密码重置-->
        <Modal v-model="passwordUser" title="用户密码重置" width="360" :mask-closable="false">
            <div slot="header">
                <a class="ivu-modal-close" @click="resetModalCancel" style="display: block!important;"><i
                        class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                <div class="ivu-modal-header-inner">用户密码重置</div>
            </div>
            <div slot="footer">
                <Button type="text" size="large" @click="resetModalCancel">取消</Button>
                <Button type="primary" size="large" @click="resetModalOk">确定</Button>
            </div>
            <Form ref="resetPassword" :model="resetPassword" :rules="resetValidate" :label-width="80"
                  style="width: 300px">
                <FormItem label="用户姓名" prop="name">
                    <Input v-model="resetPassword.name" disabled></Input>
                </FormItem>
                <FormItem label="新密码" prop="password">
                    <Input type="password" v-model="resetPassword.password"></Input>
                </FormItem>
                <FormItem label="确认密码" prop="repassword">
                    <Input type="password" v-model="resetPassword.repassword"></Input>
                </FormItem>
            </Form>
        </Modal>
        <!--编辑-->
        <editUser :editUser="editUser" :usermodaltitle="usermodaltitle" :formValidate="formValidate"
                  :departData="departData" :prefix="prefix" :userRoles="userRoles" :userRanks="userRanks" :status="status"
                  v-on:resetModal="resetModal" v-on:set_u_head_url="set_u_head_url"
                  v-on:getIndex="getIndex"></editUser>
        <!--信息转移-->
        <Modal v-model="transfer" title="信息转移" width="640" :mask-closable="false">
            <div slot="header">
                <a class="ivu-modal-close" @click="transferModalCancel" style="display: block!important;"><i
                        class="ivu-icon ivu-icon-ios-close-empty" ></i></a>
                <div class="ivu-modal-header-inner">信息转移</div>
            </div>
            <div slot="footer">
                <Button type="text" size="large" @click="transferModalCancel">取消</Button>
                <Button type="primary" size="large" @click="transferModalOk">确定</Button>
            </div>
            <Form ref="formTransferValidate" :model="formTransferValidate" :rules="ruleTransferValidate" :label-width="100">
                <Row :gutter="10">
                    <!--<Col :lg="10" :md="10">-->
                    <!--<FormItem prop="" label="出售房源">-->
                        <!--<Select placeholder="转移类别" v-model="formTransferValidate.csfy_type" :transfer="true" >-->
                            <!--<Option v-for="item in transferType" :value="item" :key="item">{{item}} </Option>-->
                        <!--</Select>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                    <Col :lg="14" :md="14">
                    <FormItem  label="出售房源" class="">
                        <Cascader :data="departData" :value.sync="formTransferValidate.departpath" filterable change-on-select
                                  @on-change="csfyTransferDepart" placeholder="部门选择"></Cascader>
                    </FormItem>
                    </Col>
                    <Col :lg="10" :md="10">
                    <FormItem prop="" class="rightSelect">
                        <Select placeholder="人员选择" v-model="formTransferValidate.csfy_uid" :transfer="true" >
                            <Option v-for="item in formTransferValidate.csfy_user" :value="item.u_id" :key="item.u_id">{{item.u_name }} </Option>
                        </Select>
                    </FormItem>
                    </Col>
                </Row>
                <Row :gutter="10">
                    <!--<Col :lg="10" :md="10">-->
                    <!--<FormItem prop="" label="出租房源">-->
                        <!--<Select placeholder="转移类别" v-model="formTransferValidate.czfy_type" :transfer="true" >-->
                            <!--<Option v-for="item in transferType" :value="item" :key="item">{{item}} </Option>-->
                        <!--</Select>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                    <Col :lg="14" :md="14">
                    <FormItem  label="出租房源" class="">
                        <Cascader :data="departData" :value.sync="formTransferValidate.departpath" filterable change-on-select
                                  @on-change="czfyTransferDepart" placeholder="部门选择" ></Cascader>
                    </FormItem>
                    </Col>
                    <Col :lg="10" :md="10">
                    <FormItem prop="" class="rightSelect">
                        <Select placeholder="人员选择" v-model="formTransferValidate.czfy_uid" :transfer="true" >
                            <Option v-for="item in formTransferValidate.czfy_user" :value="item.u_id" :key="item.u_id">{{item.u_name }} </Option>
                        </Select>
                    </FormItem>
                    </Col>
                </Row>
                <Row :gutter="10">
                    <!--<Col :lg="10" :md="10">-->
                    <!--<FormItem prop="" label="高端房源">-->
                        <!--<Select placeholder="转移类别" v-model="formTransferValidate.gdfy_type" :transfer="true" >-->
                            <!--<Option v-for="item in transferType" :value="item" :key="item">{{item}} </Option>-->
                        <!--</Select>-->
                    <!--</FormItem>-->
                    <!--</Col>-->
                    <Col :lg="14" :md="14">
                    <FormItem label="高端房源" class="">
                        <Cascader :data="departData" :value.sync="formTransferValidate.departpath" filterable change-on-select
                                  @on-change="gdfyTransferDepart" placeholder="部门选择" ></Cascader>
                    </FormItem>
                    </Col>
                    <Col :lg="10" :md="10">
                    <FormItem prop="" class="rightSelect">
                        <Select placeholder="人员选择" v-model="formTransferValidate.gdfy_uid" :transfer="true" >
                            <Option v-for="item in formTransferValidate.gdfy_user" :value="item.u_id" :key="item.u_id">{{item.u_name }} </Option>
                        </Select>
                    </FormItem>
                    </Col>
                </Row>
                <Row :gutter="10">
                    <Col :lg="14" :md="14">
                    <FormItem label="买卖客源" prop="">
                        <Cascader :data="departData" :value.sync="formTransferValidate.departpath" filterable change-on-select
                                  @on-change="cskyTransferDepart" placeholder="部门选择" ></Cascader>
                    </FormItem>
                    </Col>
                    <Col :lg="10" :md="10">
                    <FormItem prop="" class="rightSelect">
                        <Select placeholder="人员选择" v-model="formTransferValidate.csky_uid" :transfer="true" >
                            <Option v-for="item in formTransferValidate.csky_user" :value="item.u_id" :key="item.u_id">{{item.u_name }} </Option>
                        </Select>
                    </FormItem>
                    </Col>
                </Row>
                <Row :gutter="10">
                    <Col :lg="14" :md="14">
                    <FormItem label="租赁客源" prop="">
                        <Cascader :data="departData" :value.sync="formTransferValidate.departpath" filterable change-on-select
                                  @on-change="czkyTransferDepart" placeholder="部门选择" ></Cascader>
                    </FormItem>
                    </Col>
                    <Col :lg="10" :md="10">
                    <FormItem prop="" class="rightSelect">
                        <Select placeholder="人员选择" v-model="formTransferValidate.czky_uid" :transfer="true" >
                            <Option v-for="item in formTransferValidate.czky_user" :value="item.u_id" :key="item.u_id">{{item.u_name }} </Option>
                        </Select>
                    </FormItem>
                    </Col>
                </Row>
                <Row :gutter="10">
                    <Col :lg="14" :md="14">
                    <FormItem label="高端客源" prop="">
                        <Cascader :data="departData" :value.sync="formTransferValidate.departpath" filterable change-on-select
                                  @on-change="gdkyTransferDepart" placeholder="部门选择" ></Cascader>
                    </FormItem>
                    </Col>
                    <Col :lg="10" :md="10">
                    <FormItem prop="" class="rightSelect">
                        <Select placeholder="人员选择" v-model="formTransferValidate.gdky_uid" :transfer="true">
                            <Option v-for="item in formTransferValidate.gdky_user" :value="item.u_id" :key="item.u_id">{{item.u_name }} </Option>
                        </Select>
                    </FormItem>
                    </Col>
                </Row>
            </Form>
        </Modal >

    </div>
</template>

<script>
    import editUser from './user-edit.vue'

    export default {
        name: 'userManagement',
        components: {
            editUser,   //编辑
        },
        data() {
            const validatePassCheck = (rule, value, callback) => {
                if (value !== this.resetPassword.password) {
                    callback(new Error('两次输入密码不一致!'));
                } else {
                    callback();
                }
            };
            return {
                //列表搜索选择
                departData: [], //选择部门
                tichengData: [], //提成方案
                departkey: [],
                transferType:[],
                userRoles: [], //选择角色
                rolekey: '',
                userRanks: [],
                rankList: [],
                rankkey: '',
                daterange:[],
                addbutton: '',
                status: [{value: '1', label: '在职'}, {value: '2', label: '休假'}, {value: '3', label: '锁定'}, {value: '4', label: '离职'}, {value: '5', label: '开除'}],
                statuskey: '1',
                keyword: '',
                topbutton: [], //顶部按钮
                prefix:[],//用户编号前缀
                //用户数据
                userList: [],
                totalnum: 0,
                currentpage: 1,
                checkitem: [],  //被选中数据
                editUser: false,  //新增编辑用户
                usermodeltitle: '',
                authsValidate: {},
                arulesValidate: {},
                formValidate: {
                    is_touxiang:false,
                    u_head_url:'',
                },
                //权限设置
                authUser: false,
                authmodaltitle: '',
                purviews: [], //所有的功能点
                dataauths: [], //数据权限的下拉选择
                purviewdata: [],
                tmppurviewdata: [],
                auth_id: '', //选中授权用户的id
                showtab: '',

                transferUser: false,  //用户转移
                passwordUser: false,  //重置密码
                locking: false,  //锁定用户
                deleteUser: false,  //删除用户

                //重置密码验证
                resetPassword: {
                    u_uuid: '',
                    name: '',
                    password: '',
                    repassword: '',
                },
                resetValidate: {
                    password: [
                        {required: true, message: '请输入新密码', trigger: 'blur'},
                        {type: 'string', min: 6, max: 10, message: '新密码长度6-10位', trigger: 'blur'}
                    ],
                    repassword: [
                        {required: true, message: '请再次输入新密码', trigger: 'blur'},
                        {validator: validatePassCheck, trigger: 'blur'}
                    ],
                },
                //带分页的表格
                tableColumns: [
                    {
                        title: '员工编号',
                        key: 'u_employee_id',
                        align: 'center'
                    },
                    {
                        title: '员工名称',
                        key: 'u_name',
                        align: 'center'
                    },
                    {
                        title: '电话',
                        key: 'u_phone',
                        align: 'center'
                    },
                    {
                        title: '性别',
                        key: 'u_sex_text',
                        align: 'center'
                    },
                    {
                        title: '所在部门',
                        key: 'd_name',
                        align: 'center'
                    },
                    {
                        title: '所属区域',
                        key: 'dts_name',
                        align: 'center'
                    },
                    {
                        title: '用户角色',
                        key: 'role_name',
                        align: 'center'
                    },
                    {
                        title: '状态',
                        key: 'u_status_text',
                        align: 'center'
                    },
                    {
                        title: '入职时间',
                        key: 'u_entry_time',
                        align: 'center',
                    },
                    {
                        title: '操作时间',
                        key: 'utime',
                        align: 'center',
                    },
                    {
                        title: '操作',
                        key: 'button',
                        width: 280,
                        align: 'center',
                        render: (h, params) => {
                            let ret = [];
                            // if (params.row.button.auth == 1 && params.row.u_status != 2 && params.row.u_status != 3 && params.row.u_status != 4 && params.row.u_status != 5) {
                            //     ret.push(h('Button', {
                            //         props: {
                            //             type: 'info',
                            //             size: 'small'
                            //         },
                            //         style: {
                            //             marginRight: '5px'
                            //         },
                            //         on: {
                            //             click: () => {
                            //                 this.authUser = true;
                            //                 this.authmodaltitle = '权限设置';
                            //                 this.auth_id = params.row.u_uuid;
                            //                 this.purviewdata = params.row.userpurview;
                            //             }
                            //         }
                            //     }, '权限设置'))
                            // }
                            if (params.row.button.transfer == 1 && params.row.u_status != 2 && params.row.u_status != 3 && params.row.u_status != 4 && params.row.u_status != 5) {
                                ret.push(h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.transfer = true;
                                            this.transfer_user = params.row.u_uuid;
                                        }
                                    }
                                }, '信息转移'))
                            }
                            if (params.row.button.resetpwd == 1 && params.row.u_status != 2 && params.row.u_status != 3 && params.row.u_status != 4 && params.row.u_status != 5) {
                                ret.push(h('Button', {
                                    props: {
                                        type: 'warning',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.$refs['resetPassword'].resetFields();
                                            this.resetPassword.name = params.row.u_name;
                                            this.resetPassword.u_uuid = params.row.u_uuid;
                                            let _this = this;
                                            setTimeout(function () {
                                                _this.passwordUser = true;
                                            }, 300);
                                        }
                                    }
                                }, '重置密码'))
                            }
                            if (params.row.button.locked == 1 && params.row.u_status != 2 && params.row.u_status != 3 && params.row.u_status != 4 && params.row.u_status != 5) {
                                ret.push(h('Button', {
                                    props: {
                                        type: 'warning',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.checkitem = params.row;
                                            this.handleUser('locked');
                                        }
                                    }
                                }, '锁定'))

                            }
                            if (params.row.button.edit == 1 && params.row.u_status != 2 && params.row.u_status != 3 && params.row.u_status != 4 && params.row.u_status != 5) {
                                ret.push(h('Button', {
                                    props: {
                                        type: 'success',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.formValidate.u_uuid = params.row.u_uuid;
                                            this.formValidate.u_name = params.row.u_name;
                                            this.formValidate.u_sex = params.row.u_sex;
                                            this.formValidate.u_phone = params.row.u_phone;
                                            this.formValidate.u_password = '';
                                            this.formValidate.u_employee_no = params.row.u_employee_no;
                                            this.formValidate.u_employee_prefix = params.row.u_employee_prefix;
                                            this.formValidate.u_dept_id = params.row.u_dept_id;
                                            this.formValidate.ticheng_id = params.row.ticheng_id;
                                            this.formValidate.departpath = params.row.departpath;
                                            this.formValidate.u_role_id = params.row.u_role_id;
                                            this.formValidate.u_rank_id = params.row.u_rank_id;
                                            this.formValidate.u_status = params.row.u_status;
                                            this.formValidate.u_wx = params.row.u_wx;
                                            this.formValidate.u_address = params.row.u_address;
                                            if(params.row.u_head_url){
                                                this.formValidate.u_head_url = params.row.u_head_url;
                                                this.formValidate.is_touxiang = true;
                                            }else {
                                                this.formValidate.u_head_url ='';
                                                this.formValidate.is_touxiang = false;
                                            }

                                            this.formValidate.u_entry_time = params.row.u_entry_time;
                                            this.formValidate.u_birthday_time = params.row.u_birthday_time;
                                            this.usermodaltitle = '修改用户';
                                            let _this = this;
                                            setTimeout(function () {
                                                _this.editUser = true;
                                            }, 300);
                                        }
                                    }
                                }, '编辑'))
                            }
                            if (params.row.button.activate == 1 && (params.row.u_status == 2 || params.row.u_status == 3|| params.row.u_status == 4|| params.row.u_status == 5)) {
                                ret.push(h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.activate(params.row.u_uuid);
                                        }
                                    }
                                }, '激活'))
                            }
                            return h('div', ret)
                        }
                    }
                ],
                //信息转移
                transfer: false,
                transfer_user:'',
                formTransferValidate:{
//                    csfy_type:'',
                    csfy_depart:'',
                    csfy_user:'',
                    csfy_uid:'',
//                    czfy_type:'',
                    czfy_depart:'',
                    czfy_user:'',
                    czfy_uid:'',
//                    gdfy_type:'',
                    gdfy_depart:'',
                    gdfy_user:'',
                    gdfy_uid:'',
                    csky_depart:'',
                    csky_user:'',
                    csky_uid:'',
                    czky_depart:'',
                    czky_user:'',
                    czky_uid:'',
                    gdky_depart:'',
                    gdky_user:'',
                    gdky_uid:'',
                    departpath:[],
                },
                ruleTransferValidate:{
//                    csfy_user:'',
//                    czfy_user:'',
//                    gdfy_user:'',
//                    csky_user:'',
//                    czky_user:'',
//                    gdky_user:'',
                },
            }
        },
        created() {
            this.getSetting();
            this.getIndex()
        },
        methods: {
            getIndex() { //列表页
                this.usermodaltitle = '';
                this.editUser = false;
                this.$http.get(api_param.apiurl + 'user/getindex', {
                    params: {
                        did: this.departkey,
                        rid: this.rolekey,
                        sid: this.statuskey,
                        daterange: this.daterange,
                        kw: this.keyword,
                        page: this.currentpage
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum = parseInt(response.data.data.totalnum);
                        this.userList = response.data.data.userlist;
                        this.purviews = response.data.data.purview;
                        this.dataauths = response.data.data.dataauth; //数据权限
                        this.purviewdata = response.data.data.purviewdata;
                        this.tmppurviewdata = response.data.data.purviewdata;
                        this.showtab = response.data.data.showtab;
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
            set_u_head_url(u_head_url){
                this.formValidate.u_head_url=u_head_url
            },
            getSetting() { //获取下拉菜单
                this.$http.get(api_param.apiurl + 'user/getsetting', {
                    params: {},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.departData = response.data.data.departlist,
                        this.userRoles = response.data.data.rolelist,
                        this.topbutton = response.data.data.topbutton,
                        this.prefix = response.data.data.prefix,
                        this.transferType = response.data.data.transfer
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
            //搜索
            searchDepart(value, selectedData) {
                this.departkey = value[value.length - 1];
            },
            searchRole(selectedData) { //角色选择
                this.rolekey = selectedData.value;
            },
            searchStatus(selectedData) { //状态选择
                this.statuskey = selectedData.value;
            },
            selectDate (date) { // 选择日期回调
                this.daterange = date;
            },
            doSearch() {
                this.currentpage = 1;
                this.getIndex();
            },
            clearSearch() {
                this.departkey = '';
                this.rolekey = '';
                this.statuskey = '';
                this.daterange = [];
                this.keyword = '';
                this.getIndex();
            },
            //时间格式化
            formatDate(date) {
                const y = date.getFullYear();
                let m = date.getMonth() + 1;
                m = m < 10 ? '0' + m : m;
                let d = date.getDate();
                d = d < 10 ? ('0' + d) : d;
                return y + '-' + m + '-' + d;
            },
            //分页
            changePage(page) {
                this.currentpage = page;
                this.getIndex();
            },
            //新增用户
            addUser() {
                this.$http.post(api_param.apiurl + 'user/getemployeeno',
                    {},
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.formValidate.u_head_url ='';
                        this.formValidate.is_touxiang = false;
                        this.$set(this.formValidate,'u_uuid','');
                        this.$set(this.formValidate,'u_name','');
                        this.$set(this.formValidate,'u_phone','');
                        this.$set(this.formValidate,'u_employee_prefix','SZXF');
                        this.$set(this.formValidate,'u_employee_no',response.data.data);
                        this.$set(this.formValidate,'u_password','');
                        this.$set(this.formValidate,'u_head_url','');
                        this.$set(this.formValidate,'u_wx','');
                        this.$set(this.formValidate,'u_address','');
                        this.$set(this.formValidate,'u_sex','');
                        this.$set(this.formValidate,'u_status','');
                        let date = new Date();
                        let entry_date = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
                        this.$set(this.formValidate,'u_entry_time',entry_date);
                        this.$set(this.formValidate,'u_birthday_time','');
                        this.$set(this.formValidate,'u_role_id','');
                        this.$set(this.formValidate,'u_dept_id','');
                        this.$set(this.formValidate,'departpath',[]);
                        this.usermodaltitle = '新增用户';
                        //console.log(this.formValidate.u_employee_no);
                        this.editUser = true;
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
            },
            //重置model
            resetModal() {
                this.editUser = false;
            },
            //锁定和删除的确认框操作
            handleUser(type) {
                let title = '锁定用户';
                let content = '确定要锁定该用户吗？';
                let url =  'user/locked'
                let status = 3;
                this.$Modal.confirm({
                    title: title,
                    content: content,
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + url,
                            {
                                'u_uuid': this.checkitem.u_uuid,
                                'u_status': status,
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.getIndex();
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
                        })
                    }

                })
            },
            //重置密码
            resetModalCancel() {
                this.passwordUser = false;
                //清空form规则检查
                this.$refs['resetPassword'].resetFields();
            },
            resetModalOk() {
                this.$refs['resetPassword'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'user/resetpwd',
                            {
                                'u_uuid': this.resetPassword.u_uuid,
                                'u_password': this.resetPassword.password,
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['resetPassword'].resetFields();
                                this.passwordUser = false;
                                this.$Message.success(response.data.message);
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
                        })
                    }
                });
            },
            authmodalOk() {
                this.$http.post(api_param.apiurl + 'user/auth',
                    {
                        'u_uuid': this.auth_id,
                        'auths': this.purviewdata,
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.authUser = false;
                        this.$Message.success(response.data.message);
                        this.getIndex();
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
                })
            },
            authmodalCancel() {
                this.authUser = false;
                this.purviewdata = this.tmppurviewdata; //将默认的权限值赋值回来
            },
            activate(uuid) {
                this.$Modal.confirm({
                    title: '激活用户',
                    content: '确定要激活该用户吗？',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + 'user/activate',
                            {
                                'u_uuid': uuid,
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$Message.success(response.data.message);
                                this.getIndex();
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
                        })
                    }

                })
            },

            //信息转移
            csfyTransferDepart(value){ //出售房源
                let d_id = value[value.length - 1];
                this.getUserlistByDepart(1,d_id);
            },
            czfyTransferDepart(value){ //出租房源
                let d_id = value[value.length - 1];
                this.getUserlistByDepart(2,d_id);
            },
            gdfyTransferDepart(value){ //高端房源
                let d_id = value[value.length - 1];
                this.getUserlistByDepart(3,d_id);
            },
            cskyTransferDepart(value){ //出售客源
                let d_id = value[value.length - 1];
                this.getUserlistByDepart(4,d_id);
            },
            czkyTransferDepart(value){ //出租客源
                let d_id = value[value.length - 1];
                this.getUserlistByDepart(5,d_id);
            },
            gdkyTransferDepart(value){ //高端客源
                let d_id = value[value.length - 1];
                this.getUserlistByDepart(6,d_id);
            },
            getUserlistByDepart(type,d_id){
                this.$http.post(api_param.apiurl + 'user/getuser_zy',
                    {
                        'd_id': d_id,
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        if(type == 1){
                            this.formTransferValidate.csfy_user = response.data.data;
                        }else if (type == 2){
                            this.formTransferValidate.czfy_user = response.data.data;
                        }else if (type == 3){
                            this.formTransferValidate.gdfy_user = response.data.data;
                        }else if (type == 4){
                            this.formTransferValidate.csky_user = response.data.data;
                        }else if (type == 5){
                            this.formTransferValidate.czky_user = response.data.data;
                        }else if (type == 6){
                            this.formTransferValidate.gdky_user = response.data.data;
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
                })
            },

            transferModalCancel() {
                this.transfer = false;
                //清空form规则检查
//                this.formTransferValidate.csfy_type = '';
//                this.formTransferValidate.czfy_type = '';
//                this.formTransferValidate.gdfy_type = '';
                this.formTransferValidate.csfy_uid = '';
                this.formTransferValidate.czfy_uid = '';
                this.formTransferValidate.gdfy_uid = '';
                this.formTransferValidate.csky_uid = '';
                this.formTransferValidate.czky_uid = '';
                this.formTransferValidate.gdky_uid = '';
                this.formTransferValidate.departpath =[];
            },
            transferModalOk() {
                let content ='';

                if(this.formTransferValidate.csfy_uid || this.formTransferValidate.czfy_uid || this.formTransferValidate.gdfy_uid || this.formTransferValidate.csky_uid || this.formTransferValidate.czky_uid || this.formTransferValidate.gdky_uid){
                    content = '确定要转移';
                    if(this.formTransferValidate.csfy_uid && this.formTransferValidate.csfy_uid !='' && this.formTransferValidate.csfy_uid != undefined){
                        content += '出售房源、';
                    }
                    if(this.formTransferValidate.czfy_uid && this.formTransferValidate.czfy_uid !='' && this.formTransferValidate.czfy_uid != undefined){
                        content += '租赁房源、';
                    }
                    if(this.formTransferValidate.gdfy_uid && this.formTransferValidate.gdfy_uid !='' && this.formTransferValidate.gdfy_uid != undefined){
                        content += '高端房源、';
                    }
                    if(this.formTransferValidate.csky_uid && this.formTransferValidate.csky_uid !='' && this.formTransferValidate.csky_uid != undefined){
                        content += '出售客源、';
                    }
                    if(this.formTransferValidate.czky_uid && this.formTransferValidate.czky_uid !='' && this.formTransferValidate.czky_uid != undefined){
                        content += '租赁客源、';
                    }
                    if(this.formTransferValidate.gdky_uid && this.formTransferValidate.gdky_uid !='' && this.formTransferValidate.gdky_uid != undefined){
                        content += '高端客源、';
                    }
                    content = content.substr(0,content.length-1)
                    content += '等信息到新用户吗？';
                }else{
                    this.$Message.warning('未选择转移数据的接收人员!');
                    return false;
                }

                this.$Modal.confirm({
                    title: '信息转移',
                    content: content,
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + 'user/transfer',
                            {
                                'u_uuid': this.transfer_user,
//                                'csfy_type': this.formTransferValidate.csfy_type,
//                                'czfy_type': this.formTransferValidate.czfy_type,
//                                'gdfy_type': this.formTransferValidate.gdfy_type,
                                'csfy_uid': this.formTransferValidate.csfy_uid,
                                'czfy_uid': this.formTransferValidate.czfy_uid,
                                'gdfy_uid': this.formTransferValidate.gdfy_uid,
                                'csky_uid': this.formTransferValidate.csky_uid,
                                'czky_uid': this.formTransferValidate.czky_uid,
                                'gdky_uid': this.formTransferValidate.gdky_uid,
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.transfer = false;
                                //清空form规则检查
//                                this.formTransferValidate.csfy_type = '';
//                                this.formTransferValidate.czfy_type = '';
//                                this.formTransferValidate.gdfy_type = '';
                                this.formTransferValidate.csfy_uid = '';
                                this.formTransferValidate.czfy_uid = '';
                                this.formTransferValidate.gdfy_uid = '';
                                this.formTransferValidate.csky_uid = '';
                                this.formTransferValidate.czky_uid = '';
                                this.formTransferValidate.gdky_uid = '';
                                this.formTransferValidate.departpath =[];
                                this.$Message.success(response.data.message);
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
                        })
                    }
                })
            },
        },
        computed: {}
    }
</script>
