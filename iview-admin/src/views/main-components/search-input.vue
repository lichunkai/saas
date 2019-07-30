<template>
	<div class="searchInput">
		<Input v-model="value1" placeholder="请输入关键字，如客户姓名、客户电话等">
			<Select v-model="select1" :data="data" slot="prepend" style="width: 75px">
				<Option value="house">房源</Option>
				<Option value="buy">求购</Option>
				<Option value="rent">求租</Option>
			</Select>
			<Button slot="append">搜索</Button>
		</Input>
	</div>
</template>

<script>
	export default {
		name: 'searchInput',
		data() {
			return {
				value1: '',
				select1:'house',
			}
		},
		methods: {
			
		}
	};
</script>
<style scoped>
	
</style>