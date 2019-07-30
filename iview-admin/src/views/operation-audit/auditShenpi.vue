<template>
    <Row>
        <Col :lg="24" :md="24">
        <Card>
            <Row :gutter="5">
                <Col :lg="2" :md="2">
                <Select v-model="search.v_service_type" :transfer="true" placeholder="业务类型">
                    <Option v-for="item in settings.v_service_type" :value="item.value" :key="item.value">{{ item.label }}</Option>
                </Select>
                </Col>
                <!--<Col :lg="2" :md="2">-->
                <!--<Select v-model="search.v_type" :transfer="true" placeholder="审核类型">-->
                    <!--<Option v-for="item in settings.v_type" :value="item.label" :key="item.label">{{ item.label }}</Option>-->
                <!--</Select>-->
                <!--</Col>-->
                <Col :lg="2" :md="2">
                <Select v-model="search.v_status" :transfer="true" placeholder="审核状态">
                    <Option  value="0" >待审核</Option>
                    <Option  value="1" >通过</Option>
                    <Option  value="9" >驳回</Option>
                </Select>
                </Col>
                <Col :lg="2" :md="2">
                <Cascader  placeholder="申请人部门" :data="settings.departlist" :value="search.departpath"  :v-model="search.departpath" filterable change-on-select @on-change="changeDepart"  :transfer="true"></Cascader>
                </Col>
                <Col :lg="2" :md="2">
                <Select v-model="search.v_post_user" placeholder="申请人" :transfer="true">
                    <Option v-for="item in users" :value="item.u_id" :key="item.u_id">{{item.u_name}}</Option>
                </Select>
                </Col>
                <Col :lg="2" :md="2">
                <DatePicker type="date" placeholder="申请日期-起" :value="search.ctime_start" format="yyyy-MM-dd HH:mm" @on-change="search.ctime_start=$event" :transfer="true"></DatePicker>
                </Col>
                <Col :lg="2" :md="2">
                <DatePicker type="date" placeholder="申请日期-止" :value="search.ctime_end" format="yyyy-MM-dd HH:mm" @on-change="search.ctime_end=$event" :transfer="true"></DatePicker>
                </Col>
                <Col :lg="2" :md="2">
                <DatePicker type="date" placeholder="审核日期-起" :value="search.utime_start" format="yyyy-MM-dd HH:mm" @on-change="search.utime_start=$event" :transfer="true"></DatePicker>
                </Col>
                <Col :lg="2" :md="2">
                <DatePicker type="date" placeholder="审核日期-止" :value="search.utime_end" format="yyyy-MM-dd HH:mm" @on-change="search.utime_end=$event" :transfer="true"></DatePicker>
                </Col>
                <Col :lg="3" :md="3">
                <Button type="primary" @click="doSearch">查询</Button>
                <Button type="primary" @click="clearSearch">清空</Button>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24" style="margin-top: 10px">
        <Card>
            <Table :columns="shenpiColumns" :data="shenpiData" border script></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="pageTotal" :current="page" @on-change="changePage"  show-total :page-size="pageSize"></Page>
                    <!--<Page :total="totalnum" :current="page" @on-change="changePage" show-total ></Page>-->
                </div>
            </div>
        </Card>
        </Col>
    </Row>
</template>

<script>
	export default {
		name: "auditShenpi",
        data: function () {
            return {
                search: {
	                v_status:'0'
                },
                settings: {},
                page: 1,
                pageTotal: 0,
                pageSize:10,
                v_reject_reason: '',

                formItem: {
                    select: '',
                    date: '',
                },
                shenpiColumns: [
                    {
                        title: '业务类型',
                        key: 'v_service_type_text',
                        width: 88,
                        align: 'center'
                    },
                    {
                        title: '审核类型',
                        key: 'v_type',
                        width: 108,
                        align: 'center'
                    },
                    {
                        title: '审核状态',
                        key: 'v_status_text',
                        width: 108,
                        align: 'center'
                    },

                    // {
                    //     title: '处理方式',
                    //     key: 'v_end_reason',
                    //     width: 108,
                    //     align: 'center'
                    // },
                    {
                        title: '申请人',
                        key: 'post_user',
                        width: 128,
                        align: 'center'
                    },
                    {
                        title: '申请时间',
                        key: 'ctime',
                        width: 128,
                        align: 'center'
                    },
                    {
                        title: '申请内容',
                        key: 'v_content',
                        width: 200,
                        align: 'center'
                    },
                    {
                        title: '资源编号',
                        key: 'v_service_sn',
                        width: 128,
                        align: 'center',
                        render: (h, params) => {
                            return h('Button', {
                                props: {
                                    type: 'text',
                                    size: 'small'
                                },
                                style: {
                                    color: '#2d8cf0',
                                    textDecoration: 'underline',
                                },
                                domProps: {
                                    innerHTML: params.row.v_service_sn
                                },
                                on: {

                                    click: () => {
                                        if (params.row.v_service_type == 1) { console.log(params.row.v_service_id);
                                            if(params.row.v_service_id){
                                                let argu = {
                                                    houseId: params.row.v_service_id,
                                                    saleType: 2
                                                };
                                                this.$router.push({
                                                    name: 'roomDetails',
                                                    params: argu
                                                });
                                            }else{
                                                this.$Message.warning('参数错误');
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    },
                    {
                        title: '审核人',
                        key: 'end_user',
                        width: 128,
                        align: 'center'
                    },
                    {
                        title: '实际审核人',
                        key: 'shijishenheren',
                        width: 128,
                        align: 'center'
                    },
                    {
                        title: '审核时间',
                        key: 'utime',
                        width: 138,
                        align: 'center'
                    },
                    {
                        title: '驳回原因',
                        key: 'v_reject_reason',
                        width: 148,
                        align: 'center'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            if (params.row.v_status == 0) {
                                return h('div', [
                                    h('Button', {
                                        props: {
                                            type: 'primary',
                                            size: 'small'
                                        },
                                        style: {
                                            marginRight: '5px'
                                        },
                                        on: {
                                            click: () => {
                                                this.$http.post(api_param.apiurl + 'workverify/pass',
                                                    {'v_id': params.row.v_id},
                                                    {
                                                        emulateJSON: true,
                                                        headers: {"X-Access-Token": api_param.XAccessToken}
                                                    }
                                                ).then(function (response) {
                                                    // 这里是处理正确的回调
                                                    if (response.data.code == 200) {
                                                        this.$Message.success('操作成功');
                                                        this.getIndex();

                                                    } else if (response.data.code == 401) {
                                                        this.$store.commit('logout', this);
                                                        this.$store.commit('clearOpenedSubmenu');
                                                        this.$router.push({
                                                            name: 'login'
                                                        });
                                                    } else {
                                                        this.$Message.warning(response.data.message);
                                                    }
                                                }, function (response) {
                                                    // 这里是处理错误的回调
                                                    this.$Message.warning('网络异常');
                                                })
                                            }
                                        }
                                    }, '确认'),
                                    h('Button', {
                                        props: {
                                            type: 'error',
                                            size: 'small'
                                        },
                                        on: {
                                            click: () => {
                                                this.$Modal.confirm({
                                                    render: (h) => {
                                                        return h('Input', {
                                                            props: {
                                                                value: this.v_reject_reason,
                                                                autofocus: true,
                                                                placeholder: '请输入驳回理由'
                                                            },
                                                            on: {
                                                                input: (val) => {
                                                                    this.v_reject_reason = val;
                                                                }
                                                            }
                                                        })
                                                    },
                                                    onOk: () => {
                                                        let data = {
                                                            'v_reject_reason': this.v_reject_reason,
                                                            'v_id': params.row.v_id
                                                        };
                                                        this.$http.post(api_param.apiurl + 'workverify/reject',
                                                            data,
                                                            {
                                                                emulateJSON: true,
                                                                headers: {"X-Access-Token": api_param.XAccessToken}
                                                            }
                                                        ).then(function (response) {
                                                            // 这里是处理正确的回调
                                                            if (response.data.code == 200) {
                                                                this.$Message.success('操作成功');
                                                                this.getIndex();

                                                            } else if (response.data.code == 401) {
                                                                this.$store.commit('logout', this);
                                                                this.$store.commit('clearOpenedSubmenu');
                                                                this.$router.push({
                                                                    name: 'login'
                                                                });
                                                            } else {
                                                                this.$Message.warning(response.data.message);
                                                            }
                                                        }, function (response) {
                                                            // 这里是处理错误的回调
                                                            this.$Message.warning('网络异常');
                                                        })

                                                    },
                                                })
                                            }
                                        }
                                    }, '驳回')
                                ]);
                            }

                        }
                    }
                ],
                shenpiData: [],
                users: [],
            }
        },
		created(){
			this.getSetting();
			this.getIndex();
		},
		methods:{

			//获取数据
			getIndex(){
				this.search.page = this.page;
				this.$http.post(api_param.apiurl + 'workverify/getindex',
					this.search,
					{emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
				).then(function (response) {
					// 这里是处理正确的回调
					if (response.data.code == 200) {
						this.$Message.success('获取成功');
						this.shenpiData = response.data.data.list;
						this.pageTotal = parseInt(response.data.data.count);

					} else if (response.data.code == 401) {
						this.$store.commit('logout', this);
						this.$store.commit('clearOpenedSubmenu');
						this.$router.push({
							name: 'login'
						});
					} else {
						this.$Message.warning(response.data.message);
					}
				}, function (response) {
					// 这里是处理错误的回调
					this.$Message.warning('网络异常');
				})
			},

			//获取配置文件
			getSetting(){//获取配置项目
				this.$http.get(api_param.apiurl + 'workverify/getsetting', {
					params: {},
					headers: {'X-Access-Token': api_param.XAccessToken}
				}).then(function (response) {
					if (response.data.code == 200) {// 这里是处理正确的回调
						for(let i in response.data.data){
							this.$set(this.settings,i,response.data.data[i])
						}
					} else if (response.data.code == 401) {
						this.$store.commit('logout', this);
						this.$store.commit('clearOpenedSubmenu');
						this.$router.push({
							name: 'login'
						});
					} else {
						this.$Message.warning(response.data.message);
					}
				}, function (response) {
					this.$Message.error('你的网络开小差了^—^');
				})
			},

			doSearch(){
				this.page = 1;
				this.getIndex();
			},
			clearSearch(){
                this.page = 1;
				this.search = {};
				this.search.departpath = [];
				this.getIndex();
			},
			//时间格式化
			formatDate (date) {
				const y = date.getFullYear();
				let m = date.getMonth() + 1;
				m = m < 10 ? '0' + m : m;
				let d = date.getDate();
				d = d < 10 ? ('0' + d) : d;
				return y + '-' + m + '-' + d;
			},
			//分页
			changePage (page) {
				this.page = page;
				this.getIndex();
			},

			changeDepart (value, selectedData) {
				let d_id = value[value.length - 1];
				this.users = this.settings.users[d_id];
			},



		},
	}
</script>
