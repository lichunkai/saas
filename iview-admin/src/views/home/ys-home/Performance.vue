<template>

	<Table :height="200" stripe :show-header="false" :columns="columns1" :data="personal"></Table>

</template>

<script>
	export default {
		name: 'Performance',
        props: ['personal'],
		data() {
			return {
				columns1: [{
						title: '姓名',
						key: 'name',
						align:'center'
					},
					{
						title: '业绩',
						key: 'total',
						align:'center'
					}
				]
			}
		}
	}
</script>

<style scoped>
	/*业绩公告*/
</style>