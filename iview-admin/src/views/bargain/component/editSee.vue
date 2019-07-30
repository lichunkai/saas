<style scoped>

</style>
<template>
    <Row>
        <Col :lg="18" :md="18" class="editBorder">
        <Row :gutter="10" style="margin-top: 10px">
            <Col :lg="24" :md="24">
            <Button type="text">带看记录</Button>
            </Col>
            <Col :lg="24" :md="24">
            <Table :columns="columns1" :data="data1" border script></Table>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: "editSee",
        data() {
            return {
                columns1: [
                    {
                        title: '带看时间',
                        key: 'daikanshijian',
                        align: 'center'
                    },
                    {
                        title: '需求编号',
                        key: 'xuqiubianhao',
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
                                    innerHTML: 'CS-2018-0001'
                                },
                                on: {
                                    click: () => {

                                    }
                                }
                            });
                        }
                    },
                    {
                        title: '带看人',
                        key: 'daikanren',
                        align: 'center'
                    },
                    {
                        title: '所在部门',
                        key: 'suozaibumen',
                        align: 'center'
                    },
                    {
                        title: '带看内容',
                        key: 'daikanneirong',
                        align: 'center',
                    }
                ],
                data1: [
                    {
                        daikanshijian: '2018-04-02 12:46',
                        daikanren: '王晓光',
                        suozaibumen: '万科新区店',
                        daikanneirong:'房源查看'
                    },{
                        daikanshijian: '2018-04-02 12:46',
                        daikanren: '王晓光',
                        suozaibumen: '万科新区店',
                        daikanneirong:'房源查看'
                    },{
                        daikanshijian: '2018-04-02 12:46',
                        daikanren: '王晓光',
                        suozaibumen: '万科新区店',
                        daikanneirong:'房源查看'
                    },{
                        daikanshijian: '2018-04-02 12:46',
                        daikanren: '王晓光',
                        suozaibumen: '万科新区店',
                        daikanneirong:'房源查看'
                    },{
                        daikanshijian: '2018-04-02 12:46',
                        daikanren: '王晓光',
                        suozaibumen: '万科新区店',
                        daikanneirong:'房源查看'
                    },{
                        daikanshijian: '2018-04-02 12:46',
                        daikanren: '王晓光',
                        suozaibumen: '万科新区店',
                        daikanneirong:'房源查看'
                    },]
            }
        }
    }
</script>