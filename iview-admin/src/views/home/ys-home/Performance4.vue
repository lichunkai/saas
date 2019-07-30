<template>

	<Table :height="200" :show-header="false" :columns="columns1" :data="last_shop"></Table>

</template>

<script>
	export default {
		name: 'Performance4',
        props: ['last_shop'],
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