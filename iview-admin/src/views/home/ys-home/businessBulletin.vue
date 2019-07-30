<template>
		<Table highlight-row :height="200" :columns="columns1" :show-header="false" :data="notice" @on-current-change="handleRowChange"></Table>
</template>

<script>
	
	
	
	export default {
		name: 'businessBulletin',
        props: ['notice'],
		data() {
			return {
				columns1: [
				    {
						title: '公告标题',
						key: 'notice_title'
					},
					{
						title: '部门',
						key: 'd_name',
						width: 100,
					},
					{
						title: '发布人',
						key: 'u_name',
						width: 100,
					}
				]
			}
		},
        methods: {
            handleRowChange(currentRow, oldCurrentRow){
                console.log(currentRow.notice_id);
                let argu = { order_id: currentRow.notice_id };
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