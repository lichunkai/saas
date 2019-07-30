<style lang="less">
    .liucheng .ivu-modal-body{
        max-height: 560px !important;
        overflow-y: auto !important;
    }
</style>

<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row type="flex" justify="end">
                <Col>
                <Button type="primary" @click="addTransfer">新增</Button>
                <Modal v-model="transferModal" title="新增流程名称" width="360" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="transferModalCancel"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">新增流程名称</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="transferModalCancel">取消</Button>
                        <Button type="primary" size="large" @click="transferModalOk">确定</Button>
                    </div>
                    <Form ref="formNameValidate" :model="formNameValidate" :rules="ruleNameValidate" :label-width="80">
                        <FormItem label="流程名称" prop="name">
                            <Input v-model="formNameValidate.name" placeholder="流程名称"></Input>
                        </FormItem>
                    </Form>
                </Modal>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Table :columns="transferColumns" :data="transferData" border script></Table>
            <!--业主材料明细-->
            <Modal v-model="ownerModal" title="业主材料明细" :mask-closable="false">
                <div slot="header">
                    <a class="ivu-modal-close" @click="ownerModalCancel"><i
                            class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                    <div class="ivu-modal-header-inner">业主材料明细</div>
                </div>
                <div slot="footer">
                    <Button type="text" size="large" @click="ownerModalCancel">取消</Button>
                    <Button type="primary" size="large" @click="ownerModalOk">确定</Button>
                </div>
                <Form ref="formOwnerValidate" :model="formOwnerValidate" style="text-align: center;line-height: 32px">
                    <Row>
                        <Col :lg="24" :md="24">
                        <strong>明细名称</strong>
                        </Col>
                    </Row>
                    <Row :gutter="10" v-if="">
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formOwnerValidate.name0" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formOwnerValidate.name1" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formOwnerValidate.name2" placeholder=""></Input>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row :gutter="10">
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formOwnerValidate.name3" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formOwnerValidate.name4" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formOwnerValidate.name5" placeholder=""></Input>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row :gutter="10">
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formOwnerValidate.name6" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formOwnerValidate.name7" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formOwnerValidate.name8" placeholder=""></Input>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row :gutter="10">
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formOwnerValidate.name9" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formOwnerValidate.name10" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formOwnerValidate.name11" placeholder=""></Input>
                        </FormItem>
                        </Col>
                    </Row>
                </Form>
            </Modal>
            <!--客户材料明细-->
            <Modal v-model="customerModal" title="客户材料明细" :mask-closable="false">
                <div slot="header">
                    <a class="ivu-modal-close" @click="customerModalCancel"><i
                            class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                    <div class="ivu-modal-header-inner">客户材料明细</div>
                </div>
                <div slot="footer">
                    <Button type="text" size="large" @click="customerModalCancel">取消</Button>
                    <Button type="primary" size="large" @click="customerModalOk">确定</Button>
                </div>
                <Form ref="formCustomerValidate" :model="formCustomerValidate" style="text-align: center;line-height: 32px">
                    <Row>
                        <Col :lg="24" :md="24">
                        <strong>明细名称</strong>
                        </Col>
                    </Row>
                    <Row :gutter="10">
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formCustomerValidate.name0" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formCustomerValidate.name1" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formCustomerValidate.name2" placeholder=""></Input>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row :gutter="10">
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formCustomerValidate.name3" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formCustomerValidate.name4" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formCustomerValidate.name5" placeholder=""></Input>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row :gutter="10">
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formCustomerValidate.name6" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formCustomerValidate.name7" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formCustomerValidate.name8" placeholder=""></Input>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row :gutter="10">
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formCustomerValidate.name9" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formCustomerValidate.name10" placeholder=""></Input>
                        </FormItem>
                        </Col>
                        <Col :lg="8" :md="8">
                        <FormItem prop="">
                            <Input v-model="formCustomerValidate.name11" placeholder=""></Input>
                        </FormItem>
                        </Col>
                    </Row>
                </Form>
            </Modal>
            <!--过户流程-->
            <Modal v-model="processModal" title="过户流程设置" :mask-closable="false" class="liucheng" width="360">
                <div slot="header">
                    <a class="ivu-modal-close" @click="processModalCancel"><i
                            class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                    <div class="ivu-modal-header-inner">过户流程设置</div>
                </div>
                <div slot="footer">
                    <Button type="text" size="large" @click="processModalCancel">取消</Button>
                    <Button type="primary" size="large" @click="processModalOk">确定</Button>
                </div>
                <Form ref="formProcessValidate" :model="formProcessValidate" :label-width="66">
                    <div v-for="(item,idx) in process">
                    <Row :gutter="10">
                        <Col :lg="24" :md="24">
                        <FormItem label="进度名称" prop="">
                            <FormItem prop="">
                                <Select v-model="formProcessValidate.name[idx]" placeholder="" :transfer="true">
                                    <Option :value="val" v-for="(val,index) in process">{{val}}</Option>
                                </Select>
                            </FormItem>
                        </FormItem>
                        </Col>
                        <!--<Col :lg="12" :md="12">-->
                        <!--<FormItem prop="">-->
                            <!--<Input v-model="formProcessValidate.day[idx]" placeholder="" number="true"><span slot="append">天</span></Input>-->
                        <!--</FormItem>-->
                        <!--</Col>-->
                    </Row>
                    </div>
                </Form>
            </Modal>
        </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'srLiucheng',
        data() {
            return {
                transfer_id:'',
                process:[],
                transferModal: false, //添加过户流程
                formNameValidate: {
                    name: '',
                },
                ruleNameValidate: {
                    name:[{required: true, message: '请输入过户流程名称', trigger: 'blur'}],
                },
                formOwnerValidate:{},
                formCustomerValidate:{},
                formProcessValidate:{
                    name:[]
                },

                ownerModal: false, //业主材料
                customerModal: false, //客户材料
                processModal: false, //过户流程配置

                transferColumns: [
                    {
                        title: '名称',
                        key: 'transfer_name',
                        align: 'center'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            let ret = [];
                            if (true) {
                                ret.push(h('a', {
                                    props: {},
                                    style: {
                                        marginRight: '10px'
                                    },
                                    on: {
                                        click: () => {
                                            this.editOwnerMaterials(params.row);
                                        }
                                    }
                                }, '业主材料明细'));
                            }
                            if (true) {
                                ret.push(h('a', {
                                    props: {},
                                    style: {
                                        marginRight: '10px'
                                    },
                                    on: {
                                        click: () => {
                                            this.editCustomerMaterials(params.row);
                                        }
                                    }
                                }, '客户材料明细'));
                            }
                            if (true) {
                                ret.push(h('a', {
                                    props: {},
                                    style: {
                                        marginRight: '10px'
                                    },
                                    on: {
                                        click: () => {
                                            this.editProcess(params.row);
                                        }
                                    }
                                }, '过户流程'));
                            }
                            if (true) {
                                ret.push(h('a', {
                                    props: {},
                                    style: {
                                        marginRight: '10px'
                                    },
                                    on: {
                                        click: () => {
                                            this.delTransfer(params.row);
                                        }
                                    }
                                }, '删除'))
                            }
                            return h('div', ret)
                        }
                    }
                ],
                transferData: []
            };
        },
        created () {
            this.getIndex();
        },
        methods: {
            //获取列表
            getIndex(){
                this.$http.get(api_param.apiurl + '/settingtransfer/getlist',
                    {
                        params: {},
                        headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.transferData = response.data.data.list;
                        this.process = response.data.data.process;
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
            },
            //添加过户流程
            addTransfer(){
                this.transferModal = true;
            },
            transferModalCancel(){
                this.$refs['formNameValidate'].resetFields();
                this.$set(this.formNameValidate,'name','');
                this.transferModal = false;
            },
            transferModalOk(){
                this.$refs['formNameValidate'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'settingtransfer/add',
                            {
                                transfer_id: '',
                                transfer_name: this.formNameValidate.name
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['formNameValidate'].resetFields();
                                this.$set(this.formNameValidate,'name','');
                                this.transferModal = false;
                                this.$Message.success('添加成功');
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
                            console.log(response)
                        })
                    }
                });
            },
            //删除过户流程
            delTransfer(data){
                this.$Modal.confirm({
                    title: '删除方案',
                    content: '确定要删除吗',
                    okText: '确定',
                    cancelText: '取消',
                    onOk: () => {
                        this.$http.post(api_param.apiurl + '/settingtransfer/del',
                        {
                            transfer_id: data.transfer_id
                        },
                        {
                            emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}
                        }).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
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
            //业主材料明细
            editOwnerMaterials(data){
                let ownerdata = [];
                this.$http.post(api_param.apiurl + '/settingtransfer/detail',
                    {
                        transfer_id: data.transfer_id
                    },
                    {
                        emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        ownerdata = response.data.data.transfer_owner_materials;
                        this.formOwnerValidate = {};
                        if(ownerdata !== null){
                            ownerdata.forEach((item,index)=>{
                                this.$set(this.formOwnerValidate,'name'+index,item.materials_name);
                            });
                        }
                        this.transfer_id = data.transfer_id;
                        this.ownerModal = true;
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
            ownerModalCancel(){
                this.ownerModal = false;
            },
            ownerModalOk(){
                this.$http.post(api_param.apiurl + '/settingtransfer/edit',
                    {
                        transfer_id: this.transfer_id,
                        ownerdata: this.formOwnerValidate
                    },
                    {
                        emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.$Message.success(response.data.message);
                        this.ownerModal = false;
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
            //客户材料明细
            editCustomerMaterials(data){
                let customerdata = [];
                this.$http.post(api_param.apiurl + '/settingtransfer/detail',
                    {
                        transfer_id: data.transfer_id
                    },
                    {
                        emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        customerdata = response.data.data.transfer_customer_materials;
                        this.formCustomerValidate = {};
                        if(customerdata !== null){
                            customerdata.forEach((item,index)=>{
                                this.$set(this.formCustomerValidate,'name'+index,item.materials_name);
                        });
                        }
                        this.transfer_id = data.transfer_id;
                        this.customerModal = true;
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
            customerModalCancel(){
                this.customerModal = false;
            },
            customerModalOk(){
                this.$http.post(api_param.apiurl + '/settingtransfer/edit',
                    {
                        transfer_id: this.transfer_id,
                        customerdata: this.formCustomerValidate
                    },
                    {
                        emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.$Message.success(response.data.message);
                        this.customerModal = false;
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
            //过户流程
            editProcess(data){
                let processdata = [];
                this.$http.post(api_param.apiurl + '/settingtransfer/detail',
                    {
                        transfer_id: data.transfer_id
                    },
                    {
                        emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        processdata = response.data.data.transfer_process;
                        this.formProcessValidate = {name:[]};
                        if(processdata !== null){
                            processdata.forEach((item,index)=>{
                                this.$set(this.formProcessValidate.name,index,item.name);
                            });
                        }
                        this.transfer_id = data.transfer_id;
                        this.processModal = true;
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
            processModalCancel(){
                this.formProcessValidate = {name:[]},
                this.processModal = false;
            },
            processModalOk(){
                if(this.checkProcess()){
                    this.$Message.warning('进程选择重复');
                    return false;
                }
                this.$http.post(api_param.apiurl + '/settingtransfer/edit',
                    {
                        transfer_id: this.transfer_id,
                        processdata: this.formProcessValidate
                    },
                    {
                        emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}
                    }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.formProcessValidate = {name:[]},
                        this.$Message.success(response.data.message);
                        this.processModal = false;
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
            checkProcess(){
                let old_length = this.formProcessValidate.name.length;
                let newarr = [];
                for(let i=0;i<old_length;i++) {
                    let items=this.formProcessValidate.name[i];
                    //判断元素是否存在于new_arr中，如果不存在则插入到new_arr的最后
                    if(newarr.indexOf(items)==-1) {
                        newarr.push(items);
                    }
                }
                if(newarr.length < old_length){
                    return true;
                }
                return false;
            }
        }
    };
</script>