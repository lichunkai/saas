<style lang="less">
    @import "./main.less";

</style>
<template>
    <div class="main" :class="{'main-hide-text': shrink}">
        <div class="sidebar-menu-con" :style="{width: shrink?'60px':'200px', overflow: shrink ? 'visible' : 'auto'}">
            <shrinkable-menu :shrink="shrink" @on-change="handleSubmenuChange" :theme="menuTheme"
                             :before-push="beforePush" :open-names="openedSubmenuArr" :menu-list="menuList" >
                <div slot="top" class="logo-con">
                <img v-show="!shrink" src="../images/logo.png" key="max-logo"/>
                <img v-show="shrink" src="../images/logo-min.png" key="min-logo"/>
            </div>
            </shrinkable-menu>
        </div>
        <div class="main-header-con" :style="{paddingLeft: shrink?'60px':'200px'}">
            <div class="main-header">
                <Row>
                    <Col :md="1" :lg="1">
                    <div class="navicon-con">
                        <Button :style="{transform: 'rotateZ(' + (this.shrink ? '-90' : '0') + 'deg)'}" type="text"
                                @click="toggleClick">
                            <Icon type="navicon" size="32"></Icon>
                        </Button>
                    </div>
                    </Col>
                    <Col :md="6" :lg="6">
                    <div class="header-middle-con">
                        <div class="main-breadcrumb">
                            <breadcrumb-nav :currentPath="currentPath"></breadcrumb-nav>
                        </div>
                    </div>
                    </Col>
                    <Col :md="10" :lg="10" offset="7">
                    <div class="header-avator-con">
                        <Row type="flex" justify="space-between">
                            <Col :lg="12" :md="8" >
                            <full-screen v-model="isFullScreen" @on-change="fullscreenChange"></full-screen>
                            <lock-screen ></lock-screen>
                            <message-tip v-model="mesCount"></message-tip>
                            <!--<theme-switch></theme-switch>-->
                            </Col>
							<Col :lg="6" :md="6">
								<div style="margin-top:22px;color: #2d8cf0;"><strong class="main-user-name">区域  {{ district }}</strong></div>
							</Col>
                            <Col :lg="6" :md="6">
                            
                            <div class="user-dropdown-menu-con">
                                <Row type="flex" justify="end" align="middle" class="user-dropdown-innercon">
                                    <Dropdown transfer trigger="click" @on-click="handleClickUserDropdown">
                                        <a href="javascript:void(0)">
                                            <span class="main-user-name">{{ userName }}</span>
                                            <Icon type="arrow-down-b"></Icon>
                                        </a>
                                        <DropdownMenu slot="list">
                                            <DropdownItem name="ownSpace">个人中心</DropdownItem>
                                            <DropdownItem name="loginout" divided>退出登录</DropdownItem>
                                        </DropdownMenu>
                                    </Dropdown>
                                    <Avatar :src="avatorPath" style="background: #619fe7;margin-left: 10px;"></Avatar>
                                </Row>
                            </div>
                            </Col>
                        </Row>
                    </div>

                    </Col>
                </Row>
            </div>
            <div class="tags-con">
                <tags-page-opened :pageTagsList="pageTagsList"></tags-page-opened>
            </div>
        </div>
        <div class="single-page-con" :style="{left: shrink?'60px':'200px'}">
            <div class="single-page">
                <keep-alive :include="cachePage">
                    <router-view></router-view>
                </keep-alive>
            </div>
        </div>
    </div>
</template>
<script>
	import shrinkableMenu from './main-components/shrinkable-menu/shrinkable-menu.vue';
	import tagsPageOpened from './main-components/tags-page-opened.vue';
	import breadcrumbNav from './main-components/breadcrumb-nav.vue';
	import fullScreen from './main-components/fullscreen.vue';
	import lockScreen from './main-components/lockscreen/lockscreen.vue';
	import messageTip from './main-components/message-tip.vue';
	import themeSwitch from './main-components/theme-switch/theme-switch.vue';
	import Cookies from 'js-cookie';
	import util from '@/libs/util.js';
	import searchInput from './main-components/search-input.vue';

	export default {
		components: {
			shrinkableMenu,
			tagsPageOpened,
			breadcrumbNav,
			fullScreen,
			lockScreen,
			messageTip,
			themeSwitch,
			searchInput,
		},
		data() {
			return {
				shrink: false,
				district: '',
				userName: '',
				isFullScreen: false,
				openedSubmenuArr: this.$store.state.app.openedSubmenuArr,
                messageCount:0,
			};
		},
		computed: {
			menuList() {
				return this.$store.state.app.menuList;
			},
			pageTagsList() {
				return this.$store.state.app.pageOpenedList; // 打开的页面的页面对象
			},
			currentPath() {
				return this.$store.state.app.currentPath; // 当前面包屑数组
			},
			avatorPath() {
				return localStorage.avatorImgPath;
			},
			cachePage() {
				return this.$store.state.app.cachePage;
			},
			lang() {
				return this.$store.state.app.lang;
			},
			menuTheme() {
				return this.$store.state.app.menuTheme;
			},
			mesCount() {
				return this.$store.state.app.messageCount;
			}
		},
		methods: {
			init() {
				let pathArr = util.setCurrentPath(this, this.$route.name);
				//this.$store.commit('updateMenulist');
				if (pathArr.length >= 2) {
					this.$store.commit('addOpenSubmenu', pathArr[1].name);
				}
				this.district = Cookies.get('district');
				this.userName = Cookies.get('user');
				//this.messageCount = messageCount.toString();
				this.checkTag(this.$route.name);

			},
			getMessageCount(){
				// 获取未读信息条数
				this.$http.get(api_param.apiurl + 'notify/getindex', {
					params: {},
					headers: {'X-Access-Token': api_param.XAccessToken}
				}).then(function (response) {
					// 这里是处理正确的回调
					if (response.data.code == 200) {
						let messageCount = response.data.data.unreadMesList.length;
						this.$store.commit('messageCount', messageCount);
						//this.messageCount =messageCount;
						for(let i in response.data.data.unreadMesList){
							let notify = response.data.data.unreadMesList[i];
							this.$Notice.success({
								title: notify.n_title,
								desc: notify.n_content,
								render: (h, params) => {
									return h('a', {
										on: {
											click: () => {
												this.$router.push({
													name: 'message_index'
												});
											}
										}
									}, notify.n_content);
								}
							});
						}



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
					console.log(response)
				})
			},


			getHasVerify(){
				// 获取未读信息条数
				this.$http.get(api_param.apiurl + 'workverify/hasverify', {
					headers: {'X-Access-Token': api_param.XAccessToken}
				}).then(function (response) {
					// 这里是处理正确的回调
					if (response.data.code == 200) {
						if(response.data.data.hasverify){
							this.$Notice.warning({
								title: "您有新的业务需要审核",
								desc: "您有新的业务需要审核",
								render: (h, params) => {
									return h('a', {
										on: {
											click: () => {
												this.$router.push({
													name: 'auditShenpi'
												});
											}
										}
									}, "您有新的业务需要审核");
								}
							});
						}



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
					console.log(response)
				})
			},


			toggleClick() {
				this.shrink = !this.shrink;
			},
			handleClickUserDropdown(name) {
				if (name === 'ownSpace') {
					util.openNewPage(this, 'ownspace_index');
					this.$router.push({
						name: 'ownspace_index'
					});
				} else if (name === 'loginout') {
					// 退出登录
					this.$store.commit('logout', this);
					this.$store.commit('clearOpenedSubmenu');
					this.$router.push({
						name: 'login'
					});
				}
			},
			checkTag(name) {
				let openpageHasTag = this.pageTagsList.some(item => {
					if (item.name === name) {
						return true;
					}
				});
				if (!openpageHasTag) { //  解决关闭当前标签后再点击回退按钮会退到当前页时没有标签的问题
					util.openNewPage(this, name, this.$route.params || {}, this.$route.query || {});
				}
			},
			handleSubmenuChange(val) {
				// console.log(val)
			},
			beforePush(name) {
				// if (name === 'accesstest_index') {
				//     return false;
				// } else {
				//     return true;
				// }
				return true;
			},
			fullscreenChange(isFullScreen) {
				// console.log(isFullScreen);
			}
		},
		watch: {
			'$route'(to) {
			    // if(to.name != 'department-index' || to.name != 'company'){
                //     console.log(to.name);
                //     console.log(Cookies.get('companyid'));
                //     console.log(Cookies.get('u_dept_id'));
                //     // 获取门店是否过期
                //     this.$http.post(api_param.apiurl + 'company/checkexpire',
                //         {
                //             'cid': Cookies.get('companyid'),
                //             'sid': Cookies.get('u_dept_id'),
                //         },
                //         {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}
                //     }).then(function (response) {
                //         // 这里是处理正确的回调
                //         if (response.data.code == 200) {
                //
                //         }else if(response.data.code == 400){
                //             this.$router.push({
                //                 name: 'company'
                //             });
                //         }else if (response.data.code == 401) {
                //             this.$store.commit('logout', this);
                //             this.$router.push({
                //                 name: 'login'
                //             });
                //         }
                //     }, function (response) {
                //         // 这里是处理错误的回调
                //         console.log(response)
                //     })
                //
                // }
				// this.$store.commit('setCurrentPageName', to.name);
				// let pathArr = util.setCurrentPath(this, to.name);
				// if (pathArr.length > 2) {
				// 	this.$store.commit('addOpenSubmenu', pathArr[1].name);
				// }
				// this.checkTag(to.name);
				// localStorage.currentPageName = to.name;
			},
			lang() {
				util.setCurrentPath(this, this.$route.name); // 在切换语言时用于刷新面包屑
			}
		},
		mounted() {
			this.init();
		},
		created() {
			// 显示打开的页面的列表
			this.$store.commit('setOpenedList');
			this.getMessageCount();
			this.getHasVerify();
		}
	};
</script>

<style scoped>
    .demo-auto-complete-item {
        padding: 4px 0;
        border-bottom: 1px solid #F6F6F6;
    }

    .demo-auto-complete-group {
        font-size: 12px;
        padding: 4px 6px;
    }

    .demo-auto-complete-group span {
        color: #666;
        font-weight: bold;
    }

    .demo-auto-complete-group a {
        float: right;
    }

    .demo-auto-complete-count {
        float: right;
        color: #999;
    }

    .demo-auto-complete-more {
        display: block;
        margin: 0 auto;
        padding: 4px;
        text-align: center;
        font-size: 12px;
    }
</style>