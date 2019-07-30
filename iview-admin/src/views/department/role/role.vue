<style lang="less">
    @import "role.less";
</style>

<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row type="flex" justify="end">
                <Col>
                <div v-if="topbutton.add == 1">
                <Button @click="addRole" type='primary'>新增</Button>
                </div>
                <Modal v-model="addrole" width="1160" :title="rolemodaltitle" :closable="false"  :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="modalCancel" style="display: block!important"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">{{rolemodaltitle}}</div>
                    </div>
                    <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
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
                        <div style="margin-top: 20px">
                            <Row>
                                <Col :lg="8" :md="8">
                                <FormItem label="角色名称" prop="role_name">
                                    <Input v-model="formValidate.role_name" size="large" placeholder="请输入角色名称"></Input>
                                </FormItem>
                                </Col>
                                <Col :lg="8" :md="8">
                                <FormItem label="角色类型" prop="role_type">
                                    <Select v-model.sync="formValidate.role_type" placeholder='请选择'
                                            style="width: 205px !important;text-align: left;" :transfer="true">
                                        <Option v-for="(item,index) in roletypes" :value="index" :key="index">{{item}}
                                        </Option>
                                    </Select>
                                </FormItem>
                                </Col>
                                <Col :lg="8" :md="8">
                                <FormItem label="备注信息" prop="role_desp">
                                    <Input v-model="formValidate.role_desp" type="textarea"
                                           :autosize="{minRows: 1,maxRows: 2}" placeholder="简要输入备注信息"></Input>
                                </FormItem>
                                </Col>
                            </Row>
                        </div>
                    </Form>
                    <div slot="footer">
                        <Button type="text" size="large" @click="modalCancel">取消</Button>
                        <Button type="primary" size="large" @click="modalOk">确定</Button>
                    </div>
                </Modal>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Row>
                <Col>
                <Table border :columns="roleTitle" :data="roleList" :stripe="true"></Table>
                </Col>
            </Row>
        </Card>
        </Col>
    </Row>

</template>

<script>
    import roleCompontents from './role-compontents.vue';

    export default {
        name: 'role_index',
        components: {
            roleCompontents
        },
        data() {
            return {
                //列表
                roleTitle: [
                    {type: 'index', width: 60, align: 'center'},
                    {title: '角色名称', key: 'role_name', align: 'center'},
                    {title: '角色说明', key: 'role_desp', align: 'center'},
                    {
                        title: '操作',
                        key: 'operation',
                        align: 'center',
                        render: (h, params) => {
                            let ret =[];

                            if(params.row.role_name!="总经理"){
                                if(params.row.button.edit == 1){
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
                                                this.addrole = true;
                                                this.rolemodaltitle = '编辑角色';
                                                this.formValidate.role_id = params.row.role_id;
                                                this.formValidate.role_name = params.row.role_name;
                                                this.formValidate.role_type = params.row.role_type;
                                                this.formValidate.role_desp = params.row.role_desp;
                                                this.purviewdata = params.row.rolepurview;
                                                //console.log(this.purviewdata);
                                            }
                                        }
                                    }, '编辑'));
                                }
                                if(params.row.button.del == 1){
                                    ret.push(h('Button', {
                                        props: {
                                            type: 'error',
                                            size: 'small'
                                        },
                                        on: {
                                            click: () => {
                                                this.deleteRole(params.row.role_id);
                                            }
                                        }
                                    }, '删除'));
                                }
                            }

                            return h('div', ret);
                        }
                    }
                ],
                roleList: [],
                //新增编辑
                addrole: false,
                rolemodaltitle: '',
                purviews: [], //所有的功能点
                roletypes: [], //角色类型的下拉
                dataauths: [], //数据权限的下拉选择
                purviewdata: [],
                tmppurviewdata: [],
                showtab: '',
                topbutton:'',
                //表单验证
                formValidate: {
                    role_id: '',
                    role_name: '',
                    role_type: '',
                    role_desp: '',
                },
                ruleValidate: {
                    role_name: [
                        {required: true, message: '请输入角色名称', trigger: 'blur'}
                    ],
                    role_type: [
                        {required: true, message: '请选择角色类别', trigger: 'change'}
                    ]
                },
            };
        },
        created() {
            this.getIndex()
        },
        methods: {
            getIndex() { //列表页
                this.$http.get(api_param.apiurl + 'role/rolelist', {
                    params: {id: this.searchkey}, headers: {"X-Access-Token": api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    this.roleList = response.data.data.rolelist;
                    this.purviews = response.data.data.purview;
                    this.roletypes = response.data.data.roletype;
                    this.dataauths = response.data.data.dataauth;
                    this.purviewdata = response.data.data.purviewdata;
                    this.tmppurviewdata = response.data.data.purviewdata;
                    this.showtab = response.data.data.showtab;
                    this.topbutton = response.data.data.topbutton;
                    //console.log(this.purviews);
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response)
                })
            },
            addRole() {
                this.addrole = true;
                this.formValidate.role_id = '';
                this.rolemodaltitle = '新增角色';
            },
            modalOk() {
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'role/updaterole',
                            {
                                'role_id': this.formValidate.role_id,
                                'role_name': this.formValidate.role_name,
                                'role_type': this.formValidate.role_type,
                                'role_desp': this.formValidate.role_desp,
                                'auths': this.purviewdata,
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            this.addrole = false;
                            this.$refs['formValidate'].resetFields();
                            this.$Message.success('更新成功');
                            this.getIndex();
                        }, function (response) {
                            // 这里是处理错误的回调
                            //console.log(response)
                            this.$Message.warning('更新失败');
                        })
                    }
                });
            },
            modalCancel() {
                this.addrole = false;
                this.purviewdata = this.tmppurviewdata; //将默认的权限值赋值回来
                this.$refs['formValidate'].resetFields();//清空form规则检查
            },
            deleteRole(id) {  //删除
                this.$Modal.confirm({
                    title: '删除角色',
                    content: '确定要删除该角色吗？',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + 'role/delrole',
                            {'id': id},
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            this.$Message.success(response.data.message);
                            this.getIndex();
                        }, function (response) {
                            // 这里是处理错误的回调
                            this.$Message.warning(response.data.message);
                        })
                    }

                })
            }
        },
        computed: {}
    };
</script>