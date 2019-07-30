<style scoped>

</style>
<template>
    <Row>
        <Col :lg="18" :md="18" class="editBorder">
        <Row :gutter="10">
            <Col :lg="24" :md="24">
            <Row>
                <Col :lg="5" :md="5">
                <Cascader :data="data" v-model="value1" placeholder="区域"></Cascader>
                </Col>
                <Col :lg="3" :md="3">
                <Select placeholder="" :transfer="true" placeholder="面积">
                    <Option value="zhonghai">0-30平</Option>
                    <Option value="xinghai">30-50平</Option>
                    <Option value="xingshu">50-70平</Option>
                </Select>
                </Col>
                <Col :lg="3" :md="3">
                <Select placeholder="" :transfer="true" placeholder="总价">
                    <Option value="zhonghai">0-80万</Option>
                    <Option value="xinghai">80-120万</Option>
                    <Option value="xingshu">120-150万</Option>
                </Select>
                </Col>
                <Col :lg="3" :md="3">
                <Select placeholder="" :transfer="true" placeholder="厅室">
                    <Option value="zhonghai">1-1室</Option>
                    <Option value="xinghai">2-1室</Option>
                    <Option value="xingshu">3-1室</Option>
                </Select>
                </Col>
                <Col :lg="3" :md="3">
                <Select placeholder="" :transfer="true" placeholder="楼层">
                    <Option value="zhonghai">1-4层</Option>
                    <Option value="xinghai">5-8层</Option>
                    <Option value="xingshu">8-12层</Option>
                </Select>
                </Col>
                <Col :lg="3" :md="3">
                <Button type="primary">查询</Button>
                </Col>
            </Row>
            </Col>
        </Row>
        <Row :gutter="10" style="margin-top: 10px">
            <Col>
            <Table :columns="columns1" :data="data1" border script></Table>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: "editSimilar",
        data() {
            return {
                columns1: [
                    {
                        title: '状态',
                        key: 'zhuangtai',
                        align:'center'
                    },
                    {
                        title: '房源编码',
                        key: 'fangyuanbianma',
                        align:'center'
                    },
                    {
                        title: '区域',
                        key: 'quyu',
                        align:'center'
                    },
                    {
                        title: '需求面积',
                        key: 'xuqiumianji',
                        align:'center'
                    },
                    {
                        title: '需求价格',
                        key: 'xuqiujiage',
                        align:'center'
                    },
                    {
                        title: '户型',
                        key: 'huxing',
                        align:'center'
                    },
                    {
                        title: '楼层',
                        key: 'louceng',
                        align:'center'
                    },
                    {
                        title: '维护人',
                        key: 'weihuren',
                        align:'center'
                    }
                ],
                data1: [
                    {
                        zhuangtai: '出售',
                        fangyuanbianma: 'CS-2018-0001',
                        quyu: '吴中区',
                        xuqiumianji: '125㎡',
                        xuqiujiage:'258万',
                        huxing: '3-1-1室',
                        louceng: '4F',
                        weihuren: '王晓光',
                    },{
                        zhuangtai: '出售',
                        fangyuanbianma: 'CS-2018-0001',
                        quyu: '吴中区',
                        xuqiumianji: '125㎡',
                        xuqiujiage:'258万',
                        huxing: '3-1-1室',
                        louceng: '4F',
                        weihuren: '王晓光',
                    },{
                        zhuangtai: '出售',
                        fangyuanbianma: 'CS-2018-0001',
                        quyu: '吴中区',
                        xuqiumianji: '125㎡',
                        xuqiujiage:'258万',
                        huxing: '3-1-1室',
                        louceng: '4F',
                        weihuren: '王晓光',
                    },{
                        zhuangtai: '出售',
                        fangyuanbianma: 'CS-2018-0001',
                        quyu: '吴中区',
                        xuqiumianji: '125㎡',
                        xuqiujiage:'258万',
                        huxing: '3-1-1室',
                        louceng: '4F',
                        weihuren: '王晓光',
                    },{
                        zhuangtai: '出售',
                        fangyuanbianma: 'CS-2018-0001',
                        quyu: '吴中区',
                        xuqiumianji: '125㎡',
                        xuqiujiage:'258万',
                        huxing: '3-1-1室',
                        louceng: '4F',
                        weihuren: '王晓光',
                    },{
                        zhuangtai: '出售',
                        fangyuanbianma: 'CS-2018-0001',
                        quyu: '吴中区',
                        xuqiumianji: '125㎡',
                        xuqiujiage:'258万',
                        huxing: '3-1-1室',
                        louceng: '4F',
                        weihuren: '王晓光',
                    },]
            }
        }
    }
</script>