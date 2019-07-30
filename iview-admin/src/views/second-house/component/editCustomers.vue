<style scoped>
    @import "roomdetails.less";
</style>
<template>
    <Row>
        <Col :lg="18" :md="18" class="editBorder">
        <!--<Row :gutter="10">-->
            <!--<Col :lg="24" :md="24">-->
            <!--<Row>-->
                <!--<Col :lg="4" :md="4">-->
                <!--<Cascader :data="this.settings.villages" v-model="xqqy" placeholder="需求区域"></Cascader>-->
                <!--</Col>-->
                <!--<Col :lg="2" :md="2">-->
                <!--<Select v-model="selJiaGe" placeholder="价格区间"  :transfer="true">-->
                    <!--<Option v-for="item in xuqiujiage" :value="item.value" :key="item.value">{{ item.label }}</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <!--<Col :lg="2" :md="2">-->
                <!--<Select v-model="mianji" placeholder="面积区间"  :transfer="true">-->
                    <!--<Option v-for="item in xuqiumianji" :value="item.value" :key="item.value">{{ item.label }}</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <!--<Col :lg="3" :md="3">-->
                <!--<DatePicker v-model="sousuo.shijian" type="daterange" placeholder="起始时间" @on-change="changeshijian"></DatePicker>-->
                <!--</Col>-->
                <!--<Col :lg="3" :md="3">-->
                <!--<Button type="primary" @click="sousuoqu">搜索</Button>-->
                <!--</Col>-->
            <!--</Row>-->
            <!--</Col>-->
        <!--</Row>-->
        <Row :gutter="10" style="margin-top: 10px">
            <Col>
            <Table :columns="columns1" :data="cusData" border script height="560"></Table>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
	export default {
		name: "editSimilar",
		props: ['data', 'settings','cusData'],
		data() {
			return {
				tableData2:[],
				xuqiumianji: [],
				xuqiujiage: [],
				mianji:'',
				xqqy:'',
				selJiaGe:'',
				s:'',
				sousuo:{
					xuqiujiage_min:'',
					xuqiujiage_max:'',
					xuqiumianji_min:'',
					xuqiumianji_max:'',
					xuqiuhuxing_min:'',
					xuqiuhuxing_max:'',
					xuqiulouceng_min:'',
					xuqiulouceng_max:'',
					xuqiufangling_min:'',
					xuqiufangling_max:'',
					bumen:[],
					users:[],
					user:'',
					d_id:'',
					shijian:[],
					huxing:'',
					louceng:'',
					fangling:'',
					bmyhlx:'',
				},
				columns1: [
					{
						title: '状态',
						key: 'zhuangtai',
						align:'center'
					},
					{
						title: '客源编码',
						key: 'hetongbianhao',
						align:'center',
						width: 120,
						align: 'center',
						render: (h, params) => {
							return h('div', [
								h('div', {
									props: {
										type: 'error',
										size: 'small'
									},
									style: {
										color: '#2d8cf0',
										textDecoration: 'underline',
										cursor: 'pointer',
									},
									domProps: {
										innerHTML: params.row.xuqiubianhao,
									},
									on: {
										click: () => {
											let argu = {customer_id: params.row.customer_uuid,customer_type:this.$route.params.customer_type};
											this.$router.push({
												name: 'customerEtails',
												params: argu
											});
										}
									}
								})
							]);
						}
					},
					{
						title: '区域',
						key: 'xuqiuquyu',
						align:'center'
					},
					{
						title: '需求价格',
						key: 'jiage',
						align:'center'
					},
					{
						title: '需求面积',
						key: 'mianji',
						align:'center'
					},
					{
						title: '户型',
						key: 'huxing',
						align:'center'
					},
					{
						title: '维护人',
						key: 'weihuren',
						align:'center'
					}
				]
			}
		},
	}
</script>
