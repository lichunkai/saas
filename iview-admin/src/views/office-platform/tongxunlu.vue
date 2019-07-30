
<style scoped>
    .marginLeft{
        margin-left: 10px;
    }
    .marginRight{
        margin-right: 10px;
    }
    .marginBottom{
        margin-bottom: 10px;
    }
</style>
<template>
    <Row>
        <Col :lg="24" :md="24" class="marginBottom">
           <Card>
               <Row :gutter="10">
                   <Col :lg="3" :md="3">
                   <Cascader :data="departData" :value.sync="departkey" filterable change-on-select
                             @on-change="searchDepart"></Cascader>
                   </Col>
                   <Col :lg='2' :md='2' v-if="showstatus == 1">
                   <Select v-model="statuskey" :label-in-value="true" placeholder="状态选择">
                       <Option v-for="item in status" :value="item.value" :key="item.value">{{ item.label }}</Option>
                   </Select>
                   </Col>
                   <Col :lg="3" :md="3">
                   <Input v-model="keyword" placeholder="员工名称"></Input>
                   </Col>
                   <Col :lg="4" :md="4">
                   <Button type="primary" @click="doSearch">查询</Button>
                   <Button type="primary" @click="clearSearch">清空</Button>
                   </Col>
                   <Col :lg="2" :md="2" offset="10">
                   <Row type="flex" justify="end">
                       <Col>
                       <Button type="primary" v-if="topbutton.export == 1"><a :href="exporturl" target="_blank" style="color:#fff" >导出</a></Button>
                       </Col>
                   </Row>
                   </Col>
               </Row>
           </Card>
        </Col>
        <Col :lg="24" :md="24">
        <Card>
            <Table :columns="columns" :data="listData" border ></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="totalnum" :current="currentpage" :page-size="10" @on-change="changePage" show-total></Page>
                </div>
            </div>
        </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'tongxunlu',
        data(){
            return {
                departData: [], //选择部门
                departkey: [],
                status: [{value: '1', label: '在职'}, {value: '2', label: '休假'}, {value: '3', label: '锁定'}, {value: '4', label: '离职'}, {value: '5', label: '开除'}],
                statuskey: '',
                keyword: '',
                topbutton: [], //顶部按钮
                exporturl:api_param.apiurl + 'maillist/export?token='+api_param.XAccessToken,
                totalnum: 0,
                currentpage: 1,
                showstatus:0,
                columns: [
                    {
                        title: '姓名',
                        key: 'u_name',
                        align:'center'
                    },
                    {
                        title: '员工编号',
                        key: 'u_employee_id',
                        align:'center'
                    },
                    {
                        title: '性别',
                        key: 'u_sex_text',
                        align:'center'
                    },
                    {
                        title: '电话',
                        key: 'u_phone',
                        align:'center'
                    },
                    {
                        title: '职务',
                        key: 'role_name',
                        align:'center'
                    },
                    {
                        title: '状态',
                        key: 'u_status_text',
                        align:'center'
                    },
                    {
                        title: '入职时间',
                        key: 'ctime',
                        align:'center'
                    }
                ],
                listData:[]
            }
        },
        created () {
            this.getOptions();
            this.getIndex()
        },
        methods: {
            getOptions(){ //获取下拉菜单
                this.$http.get(api_param.apiurl + 'maillist/getsetting', {
                    params: {},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.departData = response.data.data.departlist,
                        this.topbutton = response.data.data.topbutton
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
            getIndex(){ //列表页
                this.$http.get(api_param.apiurl + 'maillist/getindex', {
                    params: {
                        did: this.departkey,
                        sid:this.statuskey,
                        kw: this.keyword,
                        page: this.currentpage
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.showstatus = response.data.data.showstatus;
                        this.totalnum = parseInt(response.data.data.totalnum);
                        this.listData = response.data.data.userlist;
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
            //搜索
            searchDepart(value, selectedData){
                this.departkey = value[value.length - 1];
            },
            doSearch(){
                this.currentpage = 1;
                this.getIndex();
            },
            clearSearch(){
                this.departkey = '';
                this.rolekey = '';
                this.statuskey = '';
                this.keyword = '';
                this.getIndex();
            },
            //分页
            changePage (page) {
                this.currentpage = page;
                this.getIndex();
            },
        },
    };
</script>
