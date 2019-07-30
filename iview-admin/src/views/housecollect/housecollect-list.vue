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
                <Select placeholder="委托类型" v-model="type">
                    <Option value="0">全部</Option>
                    <Option value="1">卖房</Option>
                    <Option value="2">买房</Option>
                    <Option value="3">出租</Option>
                    <Option value="4">租房</Option>
                </Select>
                </Col>
                <Col :lg="3" :md="3">
                <Input v-model="name" placeholder="小区名称"></Input>
                </Col>
                <Col :lg="3" :md="3">
                <Button type="primary" @click="doSearch">查询</Button>
                <Button type="primary" @click="clearSearch">清空</Button>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" class="margin">
        <Card>
            <Table :data="consignList" :columns="consignColumns" stripe border></Table>
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
        name: 'housecollect_index',
        components: {},
        data() {
            return {
                //表格
                consignColumns: [{
                        type: 'index',
                        width: 60,
                        align: 'center'
                    },
                    {
                        title: '委托类型',
                        key: 'type',
                        align: 'center'
                    },
                    {
                        title: '小区',
                        key: 'community_name',
                        align: 'center'
                    },
                    {
                        title: '房屋面积（㎡）',
                        key: 'area',
                        align: 'center',
                        render: (h, params) => {
                            let mianji = params.row.area + '㎡';
                            return h('div', {props: {},},mianji);
                        }
                    },
                    {
                        title: '房屋户型',
                        key: 'huxing',
                        align: 'center'
                    },
                    {
                        title: '房屋价格(万元)',
                        key: 'price',
                        align: 'center'
                    },
                    {
                        title: '姓名',
                        key: 'user_name',
                        align: 'center'
                    },
                    {
                        title: '联系方式',
                        key: 'user_phone',
                        align: 'center'
                    },
                    {
                        title: '委托时间',
                        key: 'ctime',
                        align: 'center'
                    }
                ],
                consignList: [],
                totalnum: 0,
                currentpage: 1,
                type:'',
                name: ''
            };
        },
        created() {
            this.getIndex();
        },
        methods: {
            getIndex() { //列表页
                this.$http.get(api_param.apiurl + 'consign/consignlist', {
                    params: {
                        type: this.type,
                        name: this.name,
                        page: this.currentpage,
                    },
                    headers: {
                        'X-Access-Token': api_param.XAccessToken
                    }
                }).then(function (response) {
                    // 这里是处理正确的回调
                    this.totalnum = parseInt(response.data.data.totalnum);
                    this.consignList = response.data.data.consignlist;
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
            clearSearch() {
                this.type = '';
                this.name = '';
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
