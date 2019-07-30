<style lang="less" scoped>
	.ivu-select-selection {
		width: 65px;
	}
	
	.ivu-select {
		width: 65px !important;
	}
	
	.ivu-select-item {
		padding: 8px 10px;
	}
	
</style>

<template>

	<Select v-model="Unlimited" size="small" style="width:100px" placeholder="不限" :transfer='false'>
		<Option v-for="item in options" :value="item.value" :key="item.value">{{ item.label }}</Option>
	</Select>

</template>

<script>
	export default {
		name: 'roleCompontents',
        data: function () {
            return {
                options: [{
                    value: 'jingzhi',
                    label: '禁止'
                },
                    {
                        value: 'benren',
                        label: '本人'
                    },
                    {
                        value: 'benzu',
                        label: '本组'
                    },
                    {
                        value: 'bendian',
                        label: '本店'
                    },
                    {
                        value: 'benqu',
                        label: '本区'
                    },
                    {
                        value: 'suoyou',
                        label: '所有'
                    },
                    {
                        value: 'bendaqu',
                        label: '本大区'
                    }
                ],
                Unlimited: '',
            }
        },
		methods: {

		},
		computed: {

		}
	};
</script>