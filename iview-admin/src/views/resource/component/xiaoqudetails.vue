<style scoped>
.xiaoqumain{
	line-height: 38px;
	border-bottom: #f5f5f5 1px solid;
	padding-bottom: 20px;
	margin-bottom: 20px;
}
.xiaoqumain:last-child{
	border-bottom: #f5f5f5 0px solid;
}
.xiaoqumain-line{
	display: flex;
	margin: 10px 0;
}
.xiaoqumain-line span:first-child{
	color: #666;
}
</style>

<template>
    <Card>
		<Tabs type="card" :animated="false">
		    <TabPane label="基本信息">
				<Row>
					<Col :lg="24" :md="24" class="xiaoqumain">
						<Row>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>小区名称：</span>
								<span><span>苏州 - 吴中 - 横泾</span></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>所属片区：</span>
								<span>苏州 - 吴中 - 横泾</span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>所属学区：</span>
								<span><Input placeholder="" value="三香实验小学" /></span>
							</Col>
						</Row>
					</Col>
					<Col :lg="24" :md="24" class="xiaoqumain">
						<Row>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>楼栋总数：</span>
								<span><Input placeholder="" value="61"><span slot="append">幢</span></Input></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>房屋总数：</span>
								<span><Input placeholder="" value="2571"><span slot="append">户</span></Input></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>有无电梯：</span>
								<span><Input placeholder="" value="有" /></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>容&nbsp;&nbsp;积&nbsp;&nbsp;率：</span>
								<span><Input placeholder="" value="3"><span slot="append">%</span></Input></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>绿&nbsp;&nbsp;化&nbsp;&nbsp;率：</span>
								<span><Input placeholder="" value="25"><span slot="append">%</span></Input></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>占地面积：</span>
								<span><Input placeholder="" value="16589"><span slot="append">㎡</span></Input></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>建筑面积：</span>
								<span><Input placeholder="" value="25698"><span slot="append">㎡</span></Input></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>建造年代：</span>
								<span><Input placeholder="" value="1994"><span slot="append">年</span></Input></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>小区均价：</span>
								<span><Input placeholder="" value="15896.00"><span slot="append">元/㎡</span></Input></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>车位总数：</span>
								<span><Input placeholder="" value="2586"><span slot="append">个</span></Input></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>车位比：</span>
								<span><Input placeholder="" value="2586"><span slot="append">个</span></Input></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>供电类型：</span>
								<span><Input placeholder="" value="民电" /></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>供水类型：</span>
								<span><Input placeholder="" value="民水" /></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>供气方式：</span>
								<span><Input placeholder="" value="民气" /></span>
							</Col>



						</Row>
					</Col>
					<Col :lg="24" :md="24" class="xiaoqumain">
						<Row>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>物&nbsp;&nbsp;业&nbsp;&nbsp;费：</span>
								<span><Input placeholder="" value="2.80"><span slot="append">元/月</span></Input></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>物业公司：</span>
								<span><Input placeholder="" value="苏州万科物业有限公司" /></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>物业地址：</span>
								<span><Input placeholder="" value="41幢101室" /></span>
							</Col>
							<Col :lg="6" :md="6" class="xiaoqumain-line">
								<span>物业电话：</span>
								<span><Input placeholder="" value="0512-68655088" /></span>
							</Col>
						</Row>
					</Col>
					<Col :lg="24" :md="24" class="xiaoqumain">
						<Row>
							<Col :lg="12" :md="12">
								<span>小区备注：</span>
								<span><Input type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入小区备注"></Input></span>
							</Col>
						</Row>
					</Col>
					<Col :lg="24" :md="24" class="xiaoqumain">
						<Row>
							<Col :lg="12" :md="12">
								<span>小区简介：</span>
								<span><Input type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入小区简介"></Input></span>
							</Col>
						</Row>
					</Col>
					<Col :lg="24" :md="24">
						<Button type="primary">保存</Button>
					</Col>
				</Row>
			</TabPane>
		    <TabPane label="周边配套">标签二的内容</TabPane>
		    <TabPane label="小区相册">标签三的内容</TabPane>
			<Button slot="extra" type="primary">楼盘字典</Button>
		</Tabs>
	</Card>
</template>

<script>
    export default {
        name: 'xiaoqudetails',
        data () {
            return {
                
            };
        },
		created: function () {
			this.getxiaoquDetail();              //定义方法

		},
        methods: {
			getxiaoquDetail() {
				console.log(this.$route.params.projId);
				// this.$http.get(api_param.apiurl + 'building/buildinglist', {
				// 	params: {
				// 		h_id: this.$route.params.projId
				// 	},
				// 	headers: {'X-Access-Token': api_param.XAccessToken}
				// }).then(function (response) {
				// 	// 这里是处理正确的回调
				// 	this.loudonglist = response.data.data.list;
				// 	this.keshou = '可售' + response.data.data.keshou_count;
				// 	this.yishou = '已售' + response.data.data.yishou_count;
				// 	var arr = [];
				// 	var max = 0;
				// 	for (var i = 0; i < this.loudonglist.length; i++) {
				// 		var l = this.loudonglist[i]['label'];
				// 		max = (max < parseInt(l)) ? parseInt(l) : max;
				// 		arr.push('' + l + '');
				// 	}
				// 	if (max > 5) {
				// 		var yu = max % 5;
				// 		var zheng = parseInt(max / 5);
				// 	}
				//
				// 	if (yu > 0 && zheng > 0) {
				// 		this.loudongbq_max = this.loudongbq_max * zheng + 5;
				// 	} else if (zheng > 0) {
				// 		this.loudongbq_max = this.loudongbq_max * zheng;
				// 	}
				// 	var arr1 = [];
				// 	if (this.loudongbq_max > 5) {
				// 		this.loudongbq = [];
				// 		for (var i = 1; i <= this.loudongbq_max; i++) {
				// 			arr1.push('' + i + '');
				// 			this.loudongbq.push({label: '' + i + ''});
				// 		}
				// 	}
				// 	this.checkAllGroup = arr;
				// 	if (arr1.length > 0) {
				// 		this.checkgo = arr1;
				// 	}
				// }, function (response) {
				// 	// 这里是处理错误的回调
				// 	console.log(response);
				// });
			}
        }, watch: {
			'$route.params.projId'(to, from) {
				// if (this.$route.params.projId) {
				// 	this.modalCancel();
				// }
			}
		}
    };
</script>

