<template>

	<Table :height="200" :show-header="false" :columns="columns1" :data="shop"></Table>

</template>

<script>
	export default {
		name: 'Performance2',
        props: ['shop'],
		data() {
			return {
				columns1: [{
						title: '门店',
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