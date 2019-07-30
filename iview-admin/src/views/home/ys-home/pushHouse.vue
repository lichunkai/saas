<template>

	<Table highlight-row  :height="200" :show-header="false" :columns="columns1" :data="house" @on-current-change="handleRowChange"></Table>

</template>

<script>
	export default {
		name: 'pushHouse',
        props: ['house'],
		data() {
			return {
				columns1: [
					{
						title: '房源',
						key: 'house_title',
					},
					{
						title: '面积',
						key: 'house_area',
					},
					{
						title: '价格',
						key: 'house_price',
					},
					{
						title: '日期',
						key: 'ctime',
						sortable: true
					}
				]
			}
		},
        methods: {
            handleRowChange(currentRow, oldCurrentRow){
                console.log(currentRow.house_id);
                let argu = { order_id: currentRow.house_id };
                this.$router.push({
                    name: 'order-info',
                    params: argu
                });
            }
		}
	}
</script>

<style scoped>
	/*业绩公告*/
</style>