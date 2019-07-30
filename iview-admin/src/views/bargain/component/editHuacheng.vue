<style scoped>
    .ivu-form-item {
        margin-bottom: 15px !important;
    }
</style>
<template>
    <Row :gutter="5">
        <Col :lg="16" :md="16">
        <Card>
            <p slot="title">佣金分成</p>
            <Table :columns="commissionColumns" :data="divideList.perlist" border script></Table>
        </Card>
        </Col>
        <Col :lg="16" :md="16" style="margin-top: 10px">
        <Card>
            <p slot="title">佣金分成记录</p>
            <Table :columns="divideColumns" :data="divideList.loglist" border script height="232"></Table>
           </Card>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'editHuacheng',
        props: ['divideList'],
        data () {
            return {
                commissionColumns: [
                    {
                        title: '分成人',
                        key: 'fenchengren',
                        align: 'center',
                        render: (h, params) => {
                            let texts = params.row.depart_name+'-'+params.row.user_name;
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '分成缘由',
                        key: 'reason',
                        align: 'center'

                    },
                    {
                        title: '分成比例',
                        key: 'divide_per',
                        align: 'center',
                        render: (h, params) => {
                            let texts = params.row.divide_per+'%';
                            return h('div', {
                                props: {},
                            },texts)
                        }
                    },
                    {
                        title: '实收',
                        key: 'total',
                        align: 'center'
                    },
                    {
                        title: '业绩',
                        key: 'money',
                        align: 'center'
                    }
                ],
                divideColumns: [
                    {
                        title: '分成人',
                        key: 'fenchengren',
                        align: 'center',
                        render: (h, params) => {
                            let texts = params.row.depart_name+'-'+params.row.user_name;
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '所属公司',
                        key: 'company_short_title',
                        align: 'center',
                    },
                    {
                        title: '分成类型',
                        key: 'cost_purpose',
                        align: 'center',
                        render: (h, params) => {
                            let texts = '';
                            if(params.row.cost_purpose ==1){
                                texts = '收入';
                            }else if(params.row.cost_purpose ==2){
                                texts = '支出';
                            }
                            return h('div', {props: {},},texts)
                        }
                    },
                    {
                        title: '分成缘由',
                        key: 'reason',
                        align: 'center'

                    },
                    {
                        title: '分成比例',
                        key: 'divide_per',
                        align: 'center',
                        render: (h, params) => {
                            let texts = params.row.divide_per+'%';
                            return h('div', {
                                props: {},
                            },texts)
                        }
                    },
                    {
                        title: '收取日期',
                        key: 'cost_day',
                        align: 'center'
                    },
                    {
                        title: '实收',
                        key: 'cost_money',
                        align: 'center'
                    },
                    {
                        title: '业绩',
                        key: 'divide_money',
                        align: 'center'
                    }
                ],
            };
        }
    };
</script>