<style scoped>

</style>
<template>
    <Row>
        <Col :lg="18" :md="18" class="editBorder">
        <Row :gutter="10" style="margin-top: 10px">
            <Col :lg="24" :md="24">
            <Table :columns="columns1" :data="updatePriceList" border script height="560"></Table>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: "editLishi",
	    props: ['data', 'updatePriceList'],
        data() {
            return {
	            columns1: [
		            {
			            title: '时间',
			            key: 'ctime',
			            align: 'center'
		            },
		            {
			            title: '类型',
			            key: 'hl_type_text',
			            align: 'center',
		            },
		            {
			            title: '操作人',
			            key: 'u_name',
			            align: 'center'
		            },
		            {
			            title: '所在部门',
			            key: 'd_name',
			            align: 'center'
		            },
		            {
			            title: '操作内容',
			            key: 'hl_content',
			            align: 'center',
		            }
	            ],

            }
        }
    }
</script>