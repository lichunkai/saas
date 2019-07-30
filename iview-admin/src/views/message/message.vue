<style lang="less">
    @import './message.less';
</style>

<template>
    <div class="message-main-con">
        <div class="message-mainlist-con">
            <div>
                <Button @click="setCurrentMesType('unread')" size="large" long type="text">
                    <transition name="mes-current-type-btn">
                        <Icon v-show="currentMessageType === 'unread'" type="checkmark"></Icon>
                    </transition>
                    <span class="mes-type-btn-text">未读消息</span>
                    <Badge class="message-count-badge-outer" class-name="message-count-badge"
                           :count="unreadCount"></Badge>
                </Button>
            </div>
            <div>
                <Button @click="setCurrentMesType('hasread')" size="large" long type="text">
                    <transition name="mes-current-type-btn">
                        <Icon v-show="currentMessageType === 'hasread'" type="checkmark"></Icon>
                    </transition>
                    <span class="mes-type-btn-text">已读消息</span>
                    <Badge class="message-count-badge-outer" class-name="message-count-badge"
                           :count="hasreadCount"></Badge>
                </Button>
            </div>
            <!--<div>-->
                <!--<Button @click="setCurrentMesType('recyclebin')" size="large" long type="text">-->
                    <!--<transition name="mes-current-type-btn">-->
                        <!--<Icon v-show="currentMessageType === 'recyclebin'" type="checkmark"></Icon>-->
                    <!--</transition>-->
                    <!--<span class="mes-type-btn-text">回收站</span>-->
                    <!--<Badge class="message-count-badge-outer" class-name="message-count-badge"-->
                           <!--:count="recyclebinCount"></Badge>-->
                <!--</Button>-->
            <!--</div>-->
        </div>
        <div class="message-content-con">
            <Card>
                <transition name="view-message">
                    <div v-if="showMesTitleList" class="message-title-list-con">
                        <Table ref="messageList" :columns="mesTitleColumns" :data="currentMesList"  :no-data-text="noDataText"></Table>
                    </div>
                </transition>
            </Card>
            <transition name="back-message-list">
                <div v-if="!showMesTitleList" class="message-view-content-con">
                    <div class="message-content-top-bar">
                        <span class="mes-back-btn-con"><Button type="text" @click="backMesTitleList"><Icon
                                type="chevron-left"></Icon>&nbsp;&nbsp;返回</Button></span>
                        <h3 class="mes-title">{{ mes.title }}</h3>
                    </div>
                    <p class="mes-time-con">
                        <Icon type="android-time"></Icon>&nbsp;&nbsp;{{ mes.time }}
                    </p>
                    <div class="message-content-body">
                        <p class="message-content">{{ mes.content }}</p>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'message_index',
        data() {
            const markAsreadBtn = (h, params) => {
                return h('Button', {
                    props: {
                        size: 'small'
                    },
                    on: {
                        click: () => {
	                        this.$http.post(api_param.apiurl + '/notify/read',
                                {'Notify_id':params.row.n_id},
		                        {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
	                        ).then(function (response) {
		                        // 这里是处理正确的回调
		                        if (response.data.code == 200) {
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
	                        });


//                            this.hasreadMesList.unshift(this.currentMesList.splice(params.index, 1)[0]);
//                            this.$store.commit('setMessageCount', this.unreadMesList.length);
                        }
                    }
                }, '标为已读');
            };
            const deleteMesBtn = (h, params) => {
                return h('Button', {
                    props: {
                        size: 'small',
                        type: 'error'
                    },
                    on: {
                        click: () => {
	                        this.$http.post(api_param.apiurl + '/notify/del',
		                        {'Notify_id':params.row.n_id},
		                        {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
	                        ).then(function (response) {
		                        // 这里是处理正确的回调
		                        if (response.data.code == 200) {
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
	                        });
                        }
                    }
                }, '删除');
            };
            const restoreBtn = (h, params) => {
                return h('Button', {
                    props: {
                        size: 'small'
                    },
                    on: {
                        click: () => {
                            this.hasreadMesList.unshift(this.recyclebinList.splice(params.index, 1)[0]);
                        }
                    }
                }, '还原');
            };
            return {
                currentMesList: [],
                unreadMesList: [],
                hasreadMesList: [],
                recyclebinList: [],
                currentMessageType: '',
                showMesTitleList: true,
                unreadCount: 0,
                hasreadCount: 0,
                recyclebinCount: 0,
                noDataText: '暂无未读消息',
                mes: {
                    title: '',
                    time: '',
                    content: ''
                },
                mesTitleColumns: [
                    // {
                    //     type: 'selection',
                    //     width: 50,
                    //     align: 'center'
                    // },
                    {
                        title: '　标题',
                        key: 'title',
                        align: 'left',
                        ellipsis: true,
                        render: (h, params) => {
                            return h('a', {
                                on: {
                                    click: () => {
                                        this.showMesTitleList = false;
                                        this.mes.title = params.row.n_title;
                                        this.mes.time = params.row.n_time;
	                                    this.mes.content = params.row.n_content;

                                    }
                                }
                            }, params.row.n_title);
                        }
                    },
                    {
                        title: '通知时间',
                        key: 'n_time',
                        align: 'center',
                        width: 180,
                        render: (h, params) => {
                            return h('span', [
                                h('Icon', {
                                    props: {
                                        type: 'android-time',
                                        size: 12
                                    },
                                    style: {
                                        margin: '0 5px'
                                    }
                                }),
                                h('span', {
                                    props: {
                                        type: 'android-time',
                                        size: 12
                                    }
                                }, params.row.n_time)
                            ]);
                        }
                    },
                    {
                        title: '操作',
                        key: 'asread',
                        align: 'center',
                        width: 100,
                        render: (h, params) => {
                            if (this.currentMessageType === 'unread') {
                                return h('div', [
                                    markAsreadBtn(h, params)
                                ]);
                            } else if (this.currentMessageType === 'hasread') {
                                return h('div', [
                                    deleteMesBtn(h, params)
                                ]);
                            }
                        }
                    }
                ]
            };
        },
        created(){
            this.defaultMessageList();
        },
        methods: {
            backMesTitleList() {
                this.showMesTitleList = true;
            },
            defaultMessageList(){
                this.$http.get(api_param.apiurl + 'notify/getindex', {
                    params: {},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.hasreadMesList = response.data.data.hasreadMesList;
                        this.unreadMesList =response.data.data.unreadMesList;
                        this.unreadCount = this.unreadMesList.length;
                        this.hasreadCount = this.hasreadMesList.length;
                        this.showMesTitleList = true;
                        this.currentMessageType = 'unread';
                        this.noDataText = '暂无未读消息';
                        this.currentMesList = this.unreadMesList;
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                })
            },
            setCurrentMesType(type) {
                if (this.currentMessageType !== type) {
                    this.showMesTitleList = true;
                }
                this.currentMessageType = type;
                let n_is_read = '';
                if (type === 'unread') {
                    this.noDataText = '暂无未读消息';
                    this.currentMesList = this.unreadMesList;
                } else if (type === 'hasread') {
                    this.noDataText = '暂无已读消息';
                    this.currentMesList = this.hasreadMesList;
                }
            },

            getIndex(){
	            this.$http.get(api_param.apiurl + 'notify/getindex', {
		            params: {},
		            headers: {'X-Access-Token': api_param.XAccessToken}
	            }).then(function (response) {
		            // 这里是处理正确的回调
		            if (response.data.code == 200) {

			            this.hasreadMesList = response.data.data.hasreadMesList;
			            this.unreadMesList =response.data.data.unreadMesList;

			            this.unreadCount = this.unreadMesList.length;
			            this.hasreadCount = this.hasreadMesList.length;
			            this.setCurrentMesType(this.currentMessageType);
		            } else if (response.data.code == 401) {
			            this.$store.commit('logout', this);
			            this.$router.push({
				            name: 'login'
			            });
		            } else {
			            this.$Message.warning(response.data.message);
		            }
	            }, function (response) {
		            // 这里是处理错误的回调
		            console.log(response);
	            })
            },
//            getContent(index) {
//                // you can write ajax request here to get message content
//                let mesContent = '';
//
//                this.mes.content = mesContent;
//            }
        },
        mounted() {
        	this.getIndex();

        },
        watch: {
//            unreadMesList(arr) {
//                this.unreadCount = arr.length;
//            },
//            hasreadMesList(arr) {
//                this.hasreadCount = arr.length;
//            },
//            recyclebinList(arr) {
//                this.recyclebinCount = arr.length;
//            }
        }
    };
</script>

