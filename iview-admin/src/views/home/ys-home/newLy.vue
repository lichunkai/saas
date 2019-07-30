<template>
	<Row class="mui-newly">
		<Col :lg="2" :md="4">
		<div class="mui-newly-infor">快速新建</div>
		</Col>
		<Col :lg="2" :md="2" :gutter="10">
		<div class="uesr-icon">
			<Icon type="person-add" class="user-icon-i color-1"></Icon>
			<p>录用户</p>
		</div>
		</Col>
		<Col :lg="2" :md="2">
		<div class="uesr-icon">
			<Icon type="home" class="user-icon-i color-2"></Icon>
			<p>录房源</p>
		</div>
		</Col>
		<Col :lg="2" :md="2">
		<div class="uesr-icon">
			<Icon type="compose" class="user-icon-i color-3"></Icon>
			<p>写跟进</p>
		</div>
		</Col>
		<Col :lg="2" :md="2">
		<div class="uesr-icon">
			<Icon type="ios-pricetags" class="user-icon-i color-4"></Icon>
			<p>录成交</p>
		</div>
		</Col>
	</Row>
</template>

<script>
	export default {
		name: 'ysHome',
		data() {
			return {

			}
		}
	}
</script>

<style scoped>
	/*快速新建*/	
	.mui-newly {
		height: 60px;
		text-align: center;
		border-radius: 4px;
		background-color: #fff;
	}
	
	.mui-newly-infor {
		background-color: #1E9FFF;
		line-height: 60px;
		color: #fff;
		font-size: 16px;
	}
	.uesr-icon{
		padding: 9px;
		cursor: pointer;
	}
	.uesr-icon:hover{
		background-color: #f1f1f1;
		transition: all 0.5s;
	}
	.user-icon-i{
		font-size: 24px !important;
	}
	.color-1{
		color: #19be6b;
	}
	.color-2{
		color: #ff9900;
	}
	.color-3{
		color: #2d8cf0;
	}
	.color-4{
		color: #ed3f14;
	}
</style>