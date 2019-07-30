import Main from '@/views/Main.vue'

// 不作为Main组件的子页面展示的页面单独写，如下
export const loginRouter = {
	path: '/login',
	name: 'login',
	meta: {
		title: '宜居客房产管理系统 - 登录'
	},
	component: () =>
		import('@/views/login.vue')
}

export const page404 = {
	path: '/*',
	name: 'error-404',
	meta: {
		title: '404-页面不存在'
	},
	component: () =>
		import('@/views/error-page/404.vue')
}

export const page403 = {
	path: '/403',
	meta: {
		title: '403-权限不足'
	},
	name: 'error-403',
	component: () =>
		import('@//views/error-page/403.vue')
}

export const page500 = {
	path: '/500',
	meta: {
		title: '500-服务端错误'
	},
	name: 'error-500',
	component: () =>
		import('@/views/error-page/500.vue')
}

export const preview = {
	path: '/preview',
	name: 'preview',
	component: () =>
		import('@/views/form/article-publish/preview.vue')
}
export const locking = {
	path: '/locking',
	name: 'locking',
	component: () =>
		import('@/views/main-components/lockscreen/components/locking-page.vue')
}

// 作为Main组件的子页面展示但是不在左侧菜单显示的路由写在otherRouter里
export const otherRouter = {
	path: '/',
	name: 'otherRouter',
	redirect: '/home',
	component: Main,
	children: [{
			path: 'home',
			title: {
				i18n: 'home'
			},
			name: 'home_index',
			component: () =>
				import('@/views/home/home.vue')
		},
		{
			path: 'ownspace',
			title: '个人中心',
			name: 'ownspace_index',
			component: () =>
				import('@/views/own-space/own-space.vue')
		},
		{
			path: 'message',
			title: '消息中心',
			name: 'message_index',
			component: () =>
				import('@/views/message/message.vue')
		},
		{
			path: 'roomDetails/:saleType/:houseId',
			title: '房源信息',
			name: 'roomDetails',
			component: () =>
				import('@/views/second-house/component/roomdetails.vue')
		},
		{
			path: 'customerEtails/:customer_uuid/:customer_type',
			title: '买卖-客源信息',
			name: 'customerEtails',
			component: () =>
				import('@/views/customer/component/customerEtails.vue')
		},
		{
			path: 'customerEtails1/:customer_uuid/:customer_type',
			title: '租赁-客源信息',
			name: 'customerEtails1',
			component: () =>
				import('@/views/customer/component/customerEtails1.vue')
		},
		{
			path: 'customerEtails2/:customer_uuid/:customer_type',
			title: '高端-客源信息',
			name: 'customerEtails2',
			component: () =>
				import('@/views/customer/component/customerEtails2.vue')
		},
		{
			path: 'bargainDetails/:orderId',
			title: '成交信息',
			name: 'bargainDetails',
			component: () =>
				import('@/views/bargain/component/bargainDetails.vue')
		},
	]
}

// 作为Main组件的子页面展示并且在左侧菜单显示的路由写在appRouter里
export const appRouter = [
	{
		path: '/second-house',
		icon: 'icon iconfont icon-fangyuanzhuangtai',
		name: 'second-house',
		title: '房源',
		component: Main,
		children: [
			{
				path: 'secondSale_index',
				title: '房源',
				name: 'secondSale_index',
				component: () =>
					import('@/views/second-house/house-sale/secondSale.vue')
			}
		]
	},
	{
		path: '/customer',
		icon: 'icon iconfont icon-keyuan',
		name: 'customer',
		title: '客源',
		component: Main,
		children: [{
				path: 'customerSell_index',
				title: '客源',
				name: 'customerSell_index',
				component: () =>
					import('@/views/customer/customer-sell/customerSell.vue')
			}
		]
	},
	{
		path: '/bargain',
		icon: 'icon iconfont icon-qian1',
		name: 'bargain',
		title: '成交',
		component: Main,
		children: [{
				path: 'bargainSell_index',
				title: '成交',
				name: 'bargainSell_index',
				component: () =>
					import('@/views/bargain/bargain-sell/bargainSell.vue')
			}
		]
	},
	{
		path: '/ziyuanguanli',
		icon: 'icon iconfont icon-ziyuanguanli',
		name: 'ziyuanguanli',
		title: '资源管理',
		component: Main,
		children: [{
				path: 'houseKey',
				title: '钥匙委托',
				name: 'houseKey',
				component: () =>
					import('@/views/ziyuanguanli/houseKey.vue')
			}, {
				path: 'houseWeituo',
				title: '独家委托',
				name: 'houseWeituo',
				component: () =>
					import('@/views/ziyuanguanli/houseWeituo.vue')
			},
			{
				path: 'fyGenjin',
				title: '房源跟进',
				name: 'fyGenjin',
				component: () =>
					import('@/views/ziyuanguanli/fy-genjin.vue')
			},
            {
                path: 'kyGenjin',
                title: '客源跟进',
                name: 'kyGenjin',
                component: () =>
                    import('@/views/ziyuanguanli/ky-genjin.vue')
            },
            {
                path: 'kyDaikan',
                title: '客源带看',
                name: 'kyDaikan',
                component: () =>
                    import('@/views/ziyuanguanli/ky-daikan.vue')
            },
            {
                path: 'fyDaikan',
                title: '房源带看',
                name: 'fyDaikan',
                component: () =>
                    import('@/views/ziyuanguanli/fy-daikan.vue')
            }
		]
	},
	{
		path: '/housecollect',
		icon: 'icon iconfont icon-gongpanfangyuan_caijifangyuan',
		name: 'housecollect',
		title: '房源采集',
		component: Main,
		children: [{
			path: 'sellcollect_index',
			title: '买卖房源采集',
			name: 'sellcollect_index',
			component: () =>
				import('@/views/housecollect/sellcollect-list.vue')
		},
		]
	},
	{
		path: '/payFinance',
		icon: 'icon iconfont icon-bangong',
		name: 'payFinance',
		title: '财务审核',
		component: Main,
		children: [
			{
				path: 'srFencheng',
				title: '分成配置',
				name: 'srFencheng',
				component: () =>
					import('@/views/payFinance/sr-fencheng.vue')
			},
			{
				path: 'payYongjin',
				title: '佣金管理',
				name: 'payYongjin',
				component: () =>
					import('@/views/payFinance/pay-yongjin.vue')
			},
			{
				path: 'payFenchengmingxi',
				title: '佣金业绩明细',
				name: 'payFenchengmingxi',
				component: () =>
					import('@/views/payFinance/pay-fenchengmingxi.vue')
			},

		]
	},
	{
		path: '/operation-audit',
		icon: 'icon iconfont icon-shenhe',
		name: 'operation-audit',
		title: '审核管理',
		component: Main,
        children: [
            {
                path: 'auditShenpi',
                title: '业务审核',
                name: 'auditShenpi',
                component: () =>
                    import('@/views/operation-audit/auditShenpi.vue')
            },
			{
				path: 'payShenpi',
				title: '成交审核',
				name: 'payShenpi',
				component: () =>
					import('@/views/payFinance/pay-shenpi.vue')
			},
	        {
		        path: 'auditKaihu',
		        title: '软件授权',
		        name: 'auditKaihu',
		        component: () =>
			        import('@/views/operation-audit/auditKaihu.vue')
	        },
        ]
    },
	{
		path: '/office-platform',
		icon: 'icon iconfont icon-bangong',
		name: 'office-platform',
		title: '办公平台',
		component: Main,
		children: [{
			path: 'gongsigonggao',
			title: '公司公告',
			name: 'gongsigonggao',
			component: () =>
				import('@/views/office-platform/gongsigonggao.vue')
		}, {
			path: 'gongsikuaixun',
			title: '公司快讯',
			name: 'gongsikuaixun',
			component: () =>
				import('@/views/office-platform/gongsikuaixun.vue')
		}, {
			path: 'tongxunlu',
			title: '通讯录',
			name: 'tongxunlu',
			component: () =>
				import('@/views/office-platform/tongxunlu.vue')
		}]
	},
	{
		path: '/resource',
		icon: 'icon iconfont icon-ziyuanpeizhi',
		title: '资源配置',
		name: 'resource',
		component: Main,
		children: [{
			path: 'pianqu',
			title: '片区管理',
			name: 'pianqu',
			component: () =>
				import('@/views/resource/pianqu.vue')
		}, {
			path: 'xiaoqu-index',
			title: '小区字典',
			name: 'xiaoqu-index',
			component: () =>
				import('@/views/resource/xiaoqu.vue')
		},
		{
			path: 'loudong/:projId/:projName',
			title: '楼栋信息',
			name: 'loudong',
			component: () =>
				import('@/views/resource/component/loudong.vue')
		}, 
		{
			path: 'danyuan/:label/:bu_id/:h_id/:projName',
			title: '单元信息',
			name: 'danyuan',
			component: () =>
				import('@/views/resource/component/danyuan.vue')
		}, 
		{
			path: 'fanghao/:label/:el_id/:bu_id/:h_id/:projName/:dy',
			title: '房号信息',
			name: 'fanghao',
			component: () =>
				import('@/views/resource/component/fanghao.vue')
		},
		{
			path: 'xiaoqudetails/:projId',
			title: '小区详情',
			name: 'xiaoqudetails',
			component: () =>
				import('@/views/resource/component/xiaoqudetails.vue')
		}, 
		{
			path: 'xuequ-index',
			title: '学区配置',
			name: 'xuequ-index',
			component: () =>
				import('@/views/resource/xuequ.vue')
		}, {
			path: 'bitianxiang',
			title: '必填项设置',
			name: 'bitianxiang',
			component: () =>
				import('@/views/resource/bitianxiang.vue')
		},
		{
			path: 'fangyuandengji',
			title: '房源跳公',
			name: 'fangyuandengji',
			component: () =>
				import('@/views/resource/fangyuandengji.vue')
		},
		{
			path: 'keyuandengji',
			title: '客源跳公',
			name: 'keyuandengji',
			component: () =>
				import('@/views/resource/keyuandengji.vue')
		},
		]
	},
	{
		path: '/common',
		icon: 'icon iconfont icon-peizhi',
		title: '普通配置',
		name: 'common',
		component: Main,
		children: [{
			path: 'putongpeizhi-index',
			title: '普通配置',
			name: 'putongpeizhi-index',
			component: () =>
				import('@/views/common/putongpeizhi.vue')
		}, {
			path: 'qujianpeizhi',
			title: '区间配置',
			name: 'qujianpeizhi',
			component: () =>
				import('@/views/common/qujianpeizhi.vue')
		}, {
			path: 'juecepeizhi',
			title: '决策设置',
			name: 'juecepeizhi',
			component: () =>
				import('@/views/common/juecepeizhi.vue')
		}]
	},
	{
		path: '/department',
		icon: 'icon iconfont icon-guanlihoutai',
		title: '管理后台',
		name: 'admin',
		component: Main,
		children: [{
			path: 'management',
			title: '部门管理',
			name: 'department-index',
			component: () =>
				import('@/views/department/management/department.vue')
		}, {
			path: 'role',
			title: '角色管理',
			name: 'role',
			component: () =>
				import('@/views/department/role/role.vue')
		}, {
			path: 'user-management',
			title: '用户管理',
			name: 'user-management',
			component: () =>
				import('@/views/department/user-management/user-management.vue')
		},
			{
				path: 'company',
				title: '公司管理',
				name: 'company',
				component: () =>
					import('@/views/department/company/company.vue')
			},
		]
	},
	{
		path: '/systemlog',
		icon: 'icon iconfont icon-rizhi',
		name: 'systemlog',
		title: '系统日志',
		component: Main,
		children: [{
			path: 'systemlog_index',
			title: '系统日志',
			name: 'systemlog_index',
			component: () =>
				import('@/views/systemlog/system-log.vue')
		}, ]
	}
];

// 所有上面定义的路由都要写在下面的routers里
export const routers = [
	loginRouter,
	otherRouter,
	preview,
	locking,
	...appRouter,
	page500,
	page403,
	page404
];
