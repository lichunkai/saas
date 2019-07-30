<style scoped>

</style>
<template>
    <Row>
        <Col :lg="18" :md="18" class="editBorder">
        <Row :gutter="10" style="margin-top: 10px">
            <Col :lg="24" :md="24">
            <Row type="flex" justify="space-between">
                <Col>
                <Button type="text">房屋描述</Button>
                </Col>
                <Col>
                <Button type="primary" @click="addDescribeBtn">新增</Button>
                <Modal v-model="addDescribe" title="新增描述" width="960" :mask-closable="false">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="describeModalCancel" style="display: block!important"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">新增描述</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="describeModalCancel">取消</Button>
                        <Button type="primary" size="large" @click="describeModalOk" >确定</Button>
                    </div>
                    <Form ref="describe" :rules="describeValidate" :model="describe" :label-width="80">
                        <div>
                            <textarea id="describeEditor" v-model="describe.content"></textarea>
                        </div>
                    </Form>
                </Modal>

                <Modal v-model="detailDescribe" title="描述详情" width="960">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="detailDescribe = false" style="display: block!important"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">描述详情</div>
                    </div>
                    <div v-html="describeContent"></div>
                </Modal>
                </Col>
            </Row>
            </Col>
            <Col :lg="24" :md="24" style="margin-top: 10px">
            <Table :columns="columns1" :data="describeList" border script height="560"></Table>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
	import tinymce from 'tinymce';

	export default {
		name: "editDescribe",
		props: ['data', 'describeList'],
		data() {
			return {
				detailDescribe:false,
				describeContent:'',
				articleTitle: '',
				articleError: '',
				showLink: false,
				fixedLink: '',
				articlePath: '',
				articlePathHasEdited: false,
				editLink: false,
				editPathButtonType: 'ghost',
				editPathButtonText: '编辑',
				articleStateList: [{
					value: '草稿'
				}, {
					value: '等待复审'
				}],
				editOpenness: false,
				Openness: '公开',
				currentOpenness: '公开',
				topArticle: false,
				publishTime: '',
				publishTimeType: 'immediately',
				editPublishTime: false, // 是否正在编辑发布时间
				articleTagSelected: [], // 文章选中的标签
				articleTagList: [], // 所有标签列表
				classificationList: [],
				classificationSelected: [], // 在所有分类目录中选中的目录数组
				offenUsedClass: [],
				offenUsedClassSelected: [], // 常用目录选中的目录
				classificationFinalSelected: [], // 最后实际选择的目录
				publishLoading: false,
				addingNewTag: false, // 添加新标签
				newTagName: '', // 新建标签名

				addDescribe: false,
				describe: {},
				columns1: [
					{
						title: '描述时间',
						key: 'ctime',
						align: 'center'
					},
					{
						title: '描述人',
						key: 'u_name',
						align: 'center'
					},
					{
						title: '所在部门',
						key: 'd_name',
						align: 'center'
					},
					{
						title: '操作',
						key: 'action',
						align: 'center',
						render: (h, params) => {
							return h('div', [
								h('Button', {
									props: {
										type: 'success',
										size: 'small'
									},
									style: {
										marginRight: '5px'
									},
									on: {
										click: () => {
											this.describeContent = params.row.hd_content;
											this.detailDescribe = true;
										}
									}
								}, '查看详情'),
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
											this.describe.hd_id = params.row.hd_id;
											tinymce.activeEditor.setContent(params.row.hd_content);
											this.addDescribe = true;
										}
									}
								}, '编辑'),
							]);
						}
					}
				],
				describeValidate: {},

			}
		},
		methods: {
            addDescribeBtn(){
	            this.describe.hd_id = 0;
	            let tmpContent = "小区介绍：<br>\n" +
		            "推荐理由：<br>\n" +
		            "1、地段：<br>\n" +
		            "2、房型：<br>\n" +
		            "3、景观：<br>\n" +
		            "4、优势：<br>\n" +
		            "5、分析：<br>\n" +
		            "周边：<br>\n" +
		            "交通：<br>\n" +
		            "学校：<br>\n" +
		            "行情：<br>\n" +
		            "备注：<br>";
	            tinymce.activeEditor.setContent(tmpContent);
	            this.addDescribe = true;
            },
			//手机取消
			describeModalCancel() {//修改取消
				this.addDescribe = false;
			},
			//手机确定
			describeModalOk() {//修改状态
                console.log(this.describe);
				this.describe.house_id = this.data.house_uuid;
				this.describe.house_sn = this.data.house_sn;
				let tinymcecontent = tinymce.activeEditor.getContent();
				tinymcecontent = tinymcecontent.split('<body>');
				this.describe.hd_content = tinymcecontent[1].split('</body>')[0];
				let url = '';
				if(this.describe.hd_id&&this.describe.hd_id!=0){
                     url = 'house/edithousedescribe';
                }else{
					 url = 'house/sethousedescribe';
                }
				this.$http.post(api_param.apiurl + url,
					this.describe,
					{emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
				).then(function (response) {
					// 这里是处理正确的回调
					if (response.data.code == 200) {
						this.$Message.success(response.data.message);
						this.addDescribe = false;
						this.$emit('getHouseDescribes');
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
					this.$Message.error('网络异常');
				})
			},
		},
//		computed: {
//			completeUrl() {
//				let finalUrl = this.fixedLink + this.articlePath;
//				localStorage.finalUrl = finalUrl; // 本地存储完整文章路径
//				return finalUrl;
//			}
//		},
		mounted() {
			tinymce.init({
				selector: '#describeEditor',
				branding: false,
				elementpath: false,
				height: 320,
				language: 'zh_CN.GB2312',
				menubar: 'edit insert view format table tools',
				theme: 'modern',
				plugins: [
					'advlist autolink lists link image charmap print preview hr anchor pagebreak imagetools',
					'searchreplace visualblocks visualchars code fullscreen fullpage',
					'insertdatetime media nonbreaking save table contextmenu directionality',
					'emoticons paste textcolor colorpicker textpattern imagetools codesample'
				],
				toolbar1: ' newnote print fullscreen preview | undo redo | insert | styleselect | forecolor backcolor bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image emoticons media codesample',
				autosave_interval: '20s',
				image_advtab: true,
				table_default_styles: {
					width: '100%',
					borderCollapse: 'collapse'
				}
			});


		},
		destroyed() {
			tinymce.get('describeEditor').destroy();
		}
	}
</script>