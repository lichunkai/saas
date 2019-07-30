<template>

	<Table :height="200" :show-header="false" :columns="columns1" :data="last_personal"></Table>

</template>

<script>
	export default {
		name: 'Performance3',
        props: ['last_personal'],
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