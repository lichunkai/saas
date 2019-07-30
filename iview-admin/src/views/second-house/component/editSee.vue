<style scoped>

</style>
<template>
    <Row>
        <Col :lg="18" :md="18" class="editBorder">
        <Row :gutter="10" style="margin-top: 10px">
            <Col :lg="24" :md="24">
            <Table :columns="columns1" :data="seeList" border script height="560"></Table>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
	export default {
		name: "editSee",
        props: ['seeList'],
		data() {
			return {
				columns1: [
					{
						title: '带看时间',
						key: 'ctime',
						align: 'center'
					},
//					{
//						title: '客源编号',
//						key: 'house_sn',
//						align: 'center',
//						render: (h, params) => {
//							return h('Button', {
//								props: {
//									type: 'text',
//									size: 'small'
//								},
//								style: {
//									color: '#2d8cf0',
//									textDecoration: 'underline',
//								},
//								domProps: {
//									innerHTML: params.row.house_sn,
//								},
//								on: {
//									click: () => {
//										let argu = {houseId: params.row.house_id};
//										this.$router.push({
//											name: 'roomDetails',
//											params: argu
//										});
//									}
//								}
//							});
//						}
//					},
					{
						title: '带看人',
						key: 'daikanren',
						align: 'center'
					},
					{
						title: '所在部门',
						key: 'bumen',
						align: 'center'
					},
					{
						title: '客户评价',
						key: 'd_pingjia',
						align: 'center',
					}
				],
				data1: []
			}
		}
	}
</script>
