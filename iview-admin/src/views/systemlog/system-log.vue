<style scoped>
    .margin {
        margin-top: 10px;
    }
</style>

<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="10">
                <Col :lg="3" :md="3">
                <Input v-model="name" placeholder="用户姓名"></Input>
                </Col>
                <Col :lg="1" :md="1">
                <Button type="primary" @click="doSearch">查询</Button>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" class="margin">
        <Card>
            <Table :data="logList" :columns="logColumns" stripe border></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="totalnum" :current="currentpage" @on-change="changePage" show-total></Page>
                </div>
            </div>
        </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'agent',
        components: {},
        data() {
            return {
                //系统日志表格
                logColumns: [{
                        type: 'index',
                        width: 60,
                        align: 'center'
                    },
                    {
                        title: '姓名',
                        key: 'log_uname',
                        align: 'center'
                    },
                    {
                        title: '操作内容',
                        key: 'log_desp',
                        align: 'center'
                    },
                    {
                        title: 'IP',
                        key: 'log_ip',
                        align: 'center'
                    },
                    {
                        title: '操作时间',
                        key: 'ctime',
                        align: 'center'
                    }
                ],
                logList: [],
                totalnum: 0,
                currentpage: 1,
                name: ''
            };
        },
        created() {
            this.getIndex();
        },
        methods: {
            getIndex() { //列表页
                this.$http.get(api_param.apiurl + 'systemlog/loglist', {
                    params: {
                        name: this.name,
                        page: this.currentpage,
                    },
                    headers: {
                        'X-Access-Token': api_param.XAccessToken
                    }
                }).then(function (response) {
                    // 这里是处理正确的回调
                    this.totalnum = parseInt(response.data.data.totalnum);
                    this.logList = response.data.data.loglist;
                    //console.log(this.userList);
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                })
            },
            doSearch() {
                this.currentpage = 1;
                this.getIndex();
            },
            //分页
            changePage(page) {
                this.currentpage = page;
                this.getIndex();
            },
        }
    }
</script>
