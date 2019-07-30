<template>
	<div class="Briefing">
		<Row class="mui-newly">
			<Col :md="8" :lg="4" :sm="12" :xs="12">
			<div class="uesr-icon">
				<span>{{statistics.new_customer}}</span>
				<p>
					<Icon type="person-add" class="user-icon-i color-1"></Icon>&nbsp;&nbsp;新增客户数</p>
			</div>
			</Col>
			<Col :md="8" :lg="4" :sm="12" :xs="12">
			<div class="uesr-icon">
				<span>{{statistics.new_house}}</span>
				<p>
					<Icon type="home" class="user-icon-i"></Icon>&nbsp;&nbsp;新增房源数</p>
			</div>
			</Col>
			<Col :md="8" :lg="4" :sm="12" :xs="12">
			<div class="uesr-icon">
				<span>{{statistics.contact}}</span>
				<p>
					<Icon type="compose" class="user-icon-i"></Icon>&nbsp;&nbsp;联系跟进次数</p>
			</div>
			</Col>
			<Col :md="8" :lg="4" :sm="12" :xs="12">
			<div class="uesr-icon">
				<span>{{statistics.contract_total}}</span>
				<p>
					<Icon type="compose" class="user-icon-i"></Icon>&nbsp;&nbsp;合同业绩</p>
			</div>
			</Col>
			<Col :md="8" :lg="4" :sm="12" :xs="12">
			<div class="uesr-icon">
				<span>{{statistics.receive_total}}</span>
				<p>
					<Icon type="compose" class="user-icon-i"></Icon>&nbsp;&nbsp;实收业绩</p>
			</div>
			</Col>
			<Col :md="8" :lg="4" :sm="12" :xs="12">
			<div class="uesr-icon">
				<span>{{statistics.earn}}</span>
				<p>
					<Icon type="compose" class="user-icon-i"></Icon>&nbsp;&nbsp;提成数</p>
			</div>
			</Col>
		</Row>
	</div>
</template>

<script>
	export default {
		name: 'myBriefing',
        props: ['statistics'],
		data() {
			return {
                name:'',
				time:'',
				department1: [{
						value: '1',
						label: '王立军'
					}
				],
				department2: [{
						value: '2',
						label: '田先明'
					}
				],
                dateList: [{
						value: '1',
						label: '今天'
					},
					{
						value: '2',
						label: '昨天'
					},
					{
						value: '3',
						label: '本周'
					},
					{
						value: '4',
						label: '上一月'
					},
					{
						value: '5',
						label: '本季度'
					},
					{
						value: '6',
						label: '上一季度'
					},
					{
						value: '7',
						label: '本年度'
					},
					{
						value: '8',
						label: '上一年度'
					}
				]
			}
		},
        methods: {
            gotoStatistics(){
                this.$emit('refreshstatistics',{'uid':this.name,'time':this.time});
            }
        }

	}
</script>

<style scoped>
	/*我的简报*/
	
	.Briefing {
		border-radius: 4px;
		background-color: #fff;
		padding: 20px 0;
	}
	
	.mui-newly {
		text-align: center;
		margin-top: 30px;
	}
	
	.mui-newly-infor {
		background-color: #1E9FFF;
		color: #fff;
		font-size: 18px;
	}
	
	.brief {
		font-size: 18px;
		text-align: center;
		font-weight: bold;
	}
	
	.uesr-icon {
		padding: 9px;
		cursor: pointer;
	}
	
	.uesr-icon span {
		display: block;
		font-size: 30px;
		color: #ff9900;
	}
	
	.uesr-icon p {
		font-size: 14px;
	}
	
	.user-icon-i {
		font-size: 18px !important;
	}
	.Briefing-bill{
		line-height: 32px;
		font-size: 14px;
		padding-left: 15px;
	}
	
	.Briefing-setUp{
		text-align: right;
		margin-right: 20px;
		cursor: pointer;
		font-size: 16px;
	}
</style>