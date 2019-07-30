<style scoped>

</style>
<template>
    <Row>
        <Col :lg="18" :md="18" class="editBorder">
        <!--<Row :gutter="10">-->
            <!--<Col :lg="24" :md="24">-->
            <!--<Row>-->
                <!--<Col :lg="5" :md="5">-->
                <!--<Cascader :data="data" v-model="value1" placeholder="区域"></Cascader>-->
                <!--</Col>-->
                <!--<Col :lg="3" :md="3">-->
                <!--<Select placeholder="" :transfer="true" placeholder="面积">-->
                    <!--<Option value="zhonghai">0-30平</Option>-->
                    <!--<Option value="xinghai">30-50平</Option>-->
                    <!--<Option value="xingshu">50-70平</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <!--<Col :lg="3" :md="3">-->
                <!--<Select placeholder="" :transfer="true" placeholder="总价">-->
                    <!--<Option value="zhonghai">0-80万</Option>-->
                    <!--<Option value="xinghai">80-120万</Option>-->
                    <!--<Option value="xingshu">120-150万</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <!--<Col :lg="3" :md="3">-->
                <!--<Select placeholder="" :transfer="true" placeholder="厅室">-->
                    <!--<Option value="zhonghai">1-1室</Option>-->
                    <!--<Option value="xinghai">2-1室</Option>-->
                    <!--<Option value="xingshu">3-1室</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <!--<Col :lg="3" :md="3">-->
                <!--<Select placeholder="" :transfer="true" placeholder="楼层">-->
                    <!--<Option value="zhonghai">1-4层</Option>-->
                    <!--<Option value="xinghai">5-8层</Option>-->
                    <!--<Option value="xingshu">8-12层</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <!--<Col :lg="3" :md="3">-->
                <!--<Button type="primary">查询</Button>-->
                <!--</Col>-->
            <!--</Row>-->
            <!--</Col>-->
        <!--</Row>-->
        <Row :gutter="10" style="margin-top: 10px">
            <Col>
            <Table :columns="columns1" :data="houseData" border script height="560"></Table>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
	export default {
		name: "editSimilar",
		props: ['houseData'],
		data() {
			return {
				houseList:[],
				searchData:{},
				pageCurrent: 1,
				columns1: [
					{
						title: '状态',
						key: 'house_status',
						align:'center'
					},
					{
						title: '房源编码',
						key: 'house_sn',
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
									innerHTML: params.row.house_sn
								},
								on: {
									click: () => {
                                        let argu = {
                                            houseId: params.row.house_uuid,
                                            saleType: 2
                                        };
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
						title: '小区',
						key: 'village_name',
						align:'center'
					},
					{
						title: '面积',
						key: 'jianzhumianji',
						align:'center'
					},
					{
						title: '户型',
						key: 'fangxing',
						align:'center'
					},
					{
						title: '价格',
						key: 'sell_price',
						align:'center',
						render: (h, params) => {
							if(params.row.order_type!=1){

								return h('div', {
									props: {
										type: 'text',
										size: 'small'
									},
									domProps: {
										innerHTML: params.row.sell_price+' 万'
									},
								})

                            }else{

								return h('div', {
									props: {
										type: 'text',
										size: 'small'
									},
									domProps: {
										innerHTML: params.row.rent_price
									},
								})
                            }
                        }
					},
					{
						title: '朝向',
						key: 'chaoxiang',
						align:'center'
					},
					{
						title: '楼层',
						key: 'louceng',
						align:'center'
					}
				],

			}
		},
	}
</script>
