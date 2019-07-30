<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="5">
                <Col :lg="2" :md="2">
                <Select v-model="formItem.select" :transfer="true" placeholder="审核状态">
                    <Option value="daishenhe">待审核</Option>
                    <Option value="yishenhe">已审核</Option>
                    <Option value="yibohui">已驳回</Option>
                </Select>
                </Col>
                <Col :lg="2" :md="2">
                <Select v-model="formItem.select" :transfer="true" placeholder="申请人">
                    <Option value="beijing">组织架构</Option>
                </Select>
                </Col>
                <Col :lg="2" :md="2">
                <DatePicker type="date" placeholder="申请日期-起" v-model="formItem.date" :transfer="true"></DatePicker>
                </Col>
                <Col :lg="2" :md="2">
                <DatePicker type="date" placeholder="申请日期-止" v-model="formItem.date" :transfer="true"></DatePicker>
                </Col>
                <Col :lg="3" :md="3">
                <Input v-model="value" placeholder="关键字"></Input>
                </Col>
                <Col :lg="3" :md="3">
                <Button type="primary">查询</Button>
                <Button type="primary">清空</Button>
                </Col>
                <Col :lg="6" :md="6" offset="4">
                <Row type="flex" justify="end">
                    <Col>
                    <Button type="primary">批量驳回</Button>
                    <Button type="primary">批量审核</Button>
                    </Col>
                </Row>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Table :columns="shenpiColumns" :data="shenpiData" border script></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="100" :current="1" @on-change="changePage"></Page>
                </div>
            </div>
        </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: "auditFangyuanimg",
        data() {
            return {
                formItem: {
                    select:'',
                    date:'',
                },
                shenpiColumns: [
                    {
                        type: 'selection',
                        width: 60,
                        align: 'center'
                    },
                    {
                        title: '审核状态',
                        key: 'shenhezhuangtai',
                        align:'center'
                    },
                    {
                        title: '申请人',
                        key: 'shenqingren',
                        align:'center'
                    },
                    {
                        title: '申请时间',
                        key: 'shenqingshijian',
                        align:'center'
                    },
                    {
                        title: '资源编号',
                        key: 'ziyuanbianhao',
                        width: 120,
                        align: 'center',
                        render: (h, params) => {
                            return h('Button', {
                                props: {
                                    type: 'text',
                                    size: 'small'
                                },
                                style: {
                                    color: '#2d8cf0',
                                    textDecoration: 'underline',
                                },
                                domProps: {
                                    innerHTML: 'CSFY-2018-0001'
                                },
                                on: {
                                    click: () => {
                                        let argu = {order_id: params.row.order_id};
                                        this.$router.push({
                                            name: 'roomDetails',
                                            params: argu
                                        });
                                    }
                                }
                            });
                        }
                    },
                    {
                        title: '审核人',
                        key: 'shenheren',
                        align:'center'
                    },
                    {
                        title: '审核时间',
                        key: 'shenheshijian',
                        align:'center'
                    },
                    {
                        title: '驳回原因',
                        key: 'bohuiyuanying',
                        align:'center'
                    },
                    {
                        title: '图片',
                        key: 'tupian',
                        align:'center'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.show(params.index)
                                        }
                                    }
                                }, '确认'),
                                h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.remove(params.index)
                                        }
                                    }
                                }, '驳回')
                            ]);
                        }
                    }
                ],
                shenpiData: [
                    {
                        shenhezhuangtai: '待审核',
                        shenheren:'管理员',
                        shenheshijian:'2018-04-10 12:08',
                        shenqingren:'王晓光',
                        shenqingshijian:'2018-04-10 12:08',
                        bohuiyuanying: 'XXXXX',
                        tupian:'',
                    },{
                        shenhezhuangtai: '待审核',
                        shenheren:'管理员',
                        shenheshijian:'2018-04-10 12:08',
                        shenqingren:'王晓光',
                        shenqingshijian:'2018-04-10 12:08',
                        bohuiyuanying: 'XXXXX',
                        tupian:'',
                    },{
                        shenhezhuangtai: '待审核',
                        shenheren:'管理员',
                        shenheshijian:'2018-04-10 12:08',
                        shenqingren:'王晓光',
                        shenqingshijian:'2018-04-10 12:08',
                        bohuiyuanying: 'XXXXX',
                        tupian:'',
                    },{
                        shenhezhuangtai: '待审核',
                        shenheren:'管理员',
                        shenheshijian:'2018-04-10 12:08',
                        shenqingren:'王晓光',
                        shenqingshijian:'2018-04-10 12:08',
                        bohuiyuanying: 'XXXXX',
                        tupian:'',
                    },{
                        shenhezhuangtai: '待审核',
                        shenheren:'管理员',
                        shenheshijian:'2018-04-10 12:08',
                        shenqingren:'王晓光',
                        shenqingshijian:'2018-04-10 12:08',
                        bohuiyuanying: 'XXXXX',
                        tupian:'',
                    },]
            }
        }
    }
</script>

<style scoped>

</style>
