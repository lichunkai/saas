<style lang="less">
    @import "roomdetails.less";
</style>
<style scoped>
    .demo-upload-list{
        display: inline-block;
        width: 110px;
        height: 110px;
        text-align: center;
        line-height: 110px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,.2);
        margin-right: 4px;
    }
    .demo-upload-list img{
        width: 100%;
        height: 100%;
    }
    .demo-upload-list-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .demo-upload-list:hover .demo-upload-list-cover{
        display: block;
    }
    .demo-upload-list-cover i{
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
    .imgTitle{
        text-align: center;
        font-size: 20px;
        color: #919191;
        bottom: 30px;
    }
</style>

<template>
    <Row>
        <Col :lg="18" :md="18" class="editBorder alignCenter">
        <Row>
            <Col :lg="24" :md="24">
            <Row>
                <Col :lg="4" :md="4" offset="20">
                <Row type="flex" justify="end">
                    <Col>
                    <Button type="primary" @click="download">图片下载</Button>
                    </Col>
                </Row>
                </Col>
            </Row>
            </Col>
            <Col :lg="24" :md="24" class="imageBox">
            <Row :gutter="40">
                <Col :lg="8" :md="8" v-for="setting in settings.house_other_imgs">
                <div class="demo-upload-list" v-for="img in data.other_images[setting.type]">
                    <img :src="imgurl + img.hi_url">
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-eye-outline" @click.native="handleView(img.hi_url)"></Icon>
                        <Icon type="ios-trash-outline" @click.native="handleRemove(img.hi_url)"></Icon>
                    </div>
                    <Progress v-if="showProgress[setting.type]" :percent="percentage[setting.type]" hide-info></Progress>
                </div>

                <Upload
                        ref="upload"
                        :headers="{'X-Access-Token':xtoken}"
                        :show-upload-list="false"
                        :data="{'hi_type':setting.type,'hi_is_cover':setting.is_cover}"
                        :on-success="handleSuccess"
                        :format="['jpg','jpeg','png','gif']"
                        :max-size="10240"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        multiple
                        type="drag"
                        :action="uploadurl"
                        style="display: inline-block;width:108px;">
                    <div style="width: 108px;height:108px;line-height: 108px;">
                        <Icon type="camera" size="20"></Icon>
                    </div>
                </Upload>
                <p>{{setting.name}}</p>
                </Col>


                <Col :lg="24" :md="24">
                <br><br>
                <Button type="primary" style="width: 200px" @click="saveImg">保存</Button>
                <br>
                <br>
                </Col>

                <Modal title="查看大图" v-model="visible">
                    <div slot="header">
                        <a class="ivu-modal-close" @click="visible = false" style="display: block!important">
                            <i class="ivu-icon ivu-icon-ios-close-empty"></i>
                        </a>
                        <div class="ivu-modal-header-inner">查看大图</div>
                    </div>
                    <Carousel v-if="visible" v-model="imgIndex" dots="none" value="0">
                        <CarouselItem v-for="imgs in data.other_images_list">
                            <p  v-if="imgs.hi_url" class="imgTitle">{{imgs.hi_type |typeTxt }}</p>
                            <img v-if="imgs.hi_url" :src="imgurl + imgs.hi_url" style="width: 100%">
                        </CarouselItem>
                    </Carousel>
                </Modal>
            </Row>
            </Col>
        </Row>
        </Col>
    </Row>


</template>

<script>
	export default {
		name: 'editRelevant',
		props: ['data','settings'],
		components: {},
		data () {
			return {
				visible:false,
				uploadurl: api_param.apiurl + 'site/upload',
				imgurl: api_param.imgurl,
				xtoken: api_param.XAccessToken,
				showProgress:[],
				percentage:[],
                imgIndex:0,
			};
		},
		methods: {

			//下载图片
			download() {
				let downloadimages = [];
				for (let i in this.data.other_images_list) {

					if (this.data.other_images_list[i] !== false && this.settings.house_other_imgs[this.data.other_images_list[i].hi_type-12] && this.data.other_images_list[i].hi_id) {
						let urlarr = this.data.other_images_list[i].hi_url.split('.');
						downloadimages.push({'image_src': api_param.imgurl + this.data.other_images_list[i].hi_url, 'image_name': this.settings.house_other_imgs[this.data.other_images_list[i].hi_type-12].name + this.data.other_images_list[i].hi_id + '.' + urlarr[1]});
					}
				}
				if (downloadimages.length <= 0) {
					this.$Message.success('没有图片可以下载');
					return false;
				}
				this.$http.post(api_param.apiurl + 'site/download',
					{
						'images': JSON.stringify(downloadimages),
					},
					{emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
				).then(function (response) {
					// 这里是处理正确的回调
					if (response.data.code == 200) {
						window.location.href = response.data.data;
					} else if (response.data.code == 401) {
						this.$store.commit('logout', this);
						this.$store.commit('clearOpenedSubmenu');
						this.$router.push({
							name: 'login'
						});
					}
				}, function (response) {
					// 这里是处理错误的回调
					console.log(response);
				})
			},

			//查看大图片
			//通过比对url来确定图片的索引 imgIndex
			handleView(url) { //查看大图
				for (let i in this.data.other_images_list) {
					if (this.data.other_images_list[i].hi_url == url) {
						this.imgIndex =parseInt(i);
					}
				}
				this.visible = true;
			},


			handleRemove(url) { //删除事件
				for (let i in this.data.other_images_list) {
					if (this.data.other_images_list[i].hi_url == url) {
						//删除images
						for(let img in this.data.other_images[this.data.other_images_list[i].hi_type]){
							if(this.data.other_images[this.data.other_images_list[i].hi_type][img].hi_url ==url){
								this.data.other_images[this.data.other_images_list[i].hi_type].splice(img,1);
							}
						}
						//删除imglist
						this.data.other_images_list.splice(i,1);
					}
				}
			},

			handleSuccess(res, file, filelist) {//上传成功
				let type = res.data.data.hi_type
				let tmp = {
					'hi_type': type,
					'hi_url': res.data.url,
					'hi_is_cover': res.data.data.hi_is_cover,
				};
				this.$set(this.data.other_images[type], this.data.other_images[type].length, tmp);
				this.$set(this.data.other_images_list, this.data.other_images_list.length, tmp);
			},
			handleFormatError(file) {
				this.$Notice.warning({
					title: '文件上传失败',
					desc: '文件 ' + file.name + ' 类型错误, 请选择 gif, jpg or png.'
				});
			},
			handleMaxSize(file) {
				this.$Notice.warning({
					title: '文件上传失败',
					desc: '文件  ' + file.name + ' 大小必须小于 10M.'
				});
			},

			saveImg() {
				let images = [];
				//判断图片是否全部上传
				for (let i in this.data.other_images) {
					if (i > 11&&i<17) {
						if(this.data.other_images[i].length>0){
							images[i] = this.data.other_images[i];
						}
					}
				}

				let action = 'house/imgupload';
				this.$http.post(api_param.apiurl + action, {'images': images, 'house_id': this.data.house_id},
					{emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
				).then(function (response) {
					// 这里是处理正确的回调
					if (response.data.code == 200) {
						this.$Message.success('保存成功');
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
			}
		},
		filters: {
			typeTxt: function (value) {
				let typeTxt='';
				switch(value){
					case '1':typeTxt='封面';break;
					case '2':typeTxt='楼栋号';break;
					case '3':typeTxt='房号';break;
					case '4':typeTxt='主卧';break;
					case '5':typeTxt='次卧';break;
					case '6':typeTxt='客厅';break;
					case '7':typeTxt='餐厅';break;
					case '8':typeTxt='阳台';break;
					case '9':typeTxt='卫生间';break;
					case '10':typeTxt='小区外观';break;
					case '11':typeTxt='户型图';break;
					case '12':typeTxt='委托合同';break;
					case '13':typeTxt='独家合同';break;
					case '14':typeTxt='产证图片';break;
					case '15':typeTxt='身份证正面';break;
					case '16':typeTxt='身份证反面';break;
					case '17':typeTxt='其他';break;
					default :typeTxt='其他';break;
				}
				return typeTxt;
			}
		}
	};
</script>
