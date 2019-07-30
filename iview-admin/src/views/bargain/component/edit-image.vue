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
            <Row :gutter="40" style="text-align: center">

                <Col :lg="4" :md="4" v-for="setting in data.deal_imgs" style="margin: 10px">

                <div class="demo-upload-list" v-if="data.images[setting.type]">
                    <img :src="imgurl + data.images[setting.type].oi_url">
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-eye-outline" @click.native="handleView(data.images[setting.type].oi_url)"></Icon>
                        <Icon type="ios-trash-outline" @click.native="handleRemove(setting.type)"></Icon>
                    </div>
                    <Progress v-if="showProgress[setting.type]" :percent="percentage[setting.type]" hide-info></Progress>
                </div>
                <Upload v-else
                        ref="upload"
                        :headers="{'X-Access-Token':xtoken}"
                        :show-upload-list="false"
                        :data="{'oi_type':setting.type}"
                        :on-success="handleSuccess"
                        :format="['jpg','jpeg','png']"
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
                        <a class="ivu-modal-close" @click="visible = false" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">查看大图</div>
                    </div>
                    <img :src="imgurl + imgName" v-if="visible" style="width: 100%">
                </Modal>
            </Row>
            </Col>
        </Row>
        </Col>
    </Row>


</template>

<script>
    export default {
        name: 'editImage',
        props: ['data'],
        components: {},
        data () {
            return {
                visible:false,
                uploadurl: api_param.apiurl + 'site/upload',
                downloadurl: '',
                imgurl: api_param.imgurl,
                downloadimages:[],
                xtoken: api_param.XAccessToken,
                showProgress:[],
                percentage:[],
            };
        },
        created(){
            //this.download();
        },
        methods:{
            download(){
                let downloadimages=[];
                for(let i in this.data.images){
                    if(this.data.images[i] !== false){
                        let urlarr = this.data.images[i].oi_url.split('.');
                        downloadimages.push({'image_src':api_param.imgurl+this.data.images[i].oi_url,'image_name':this.data.deal_imgs[i-1].name+'.'+urlarr[1]});
                    }
                }
                this.$http.post(api_param.apiurl + 'site/download',
                    {
                        'images':  JSON.stringify(downloadimages),
                    },
                    {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        window.location.href=response.data.data;
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

            handleView (name) { //查看大图
                this.imgName = name;
                this.visible = true;
            },

            handleRemove (index) { //删除事件
                this.$set(this.data.images,index,false);
            },

            handleSuccess (res, file,filelist) {//上传成功
                let type = res.data.data.oi_type
                let tmp = {
                    'oi_type':type,
                    'oi_url':res.data.url,
//                    'house_id':this.data.house_id,
//                    'hi_is_cover':res.data.data.hi_is_cover,
                };
                this.$set(this.data.images,type,tmp);
            },
            handleFormatError (file) {
                this.$Notice.warning({
                    title: '文件上传失败',
                    desc: '文件 ' + file.name + ' 类型错误, 请选择 jpg png.'
                });
            },
            handleMaxSize (file) {
                this.$Notice.warning({
                    title: '文件上传失败',
                    desc: '文件  ' + file.name + ' 大小必须小于 10M.'
                });
            },

            saveImg(){
                let images=[];
                //判断图片是否全部上传
                for(let i in this.data.images){
//                    if(i<=11){
                        images[i] =this.data.images[i];
//                    }
//                    if(!this.data.images[i]&&i<=7){
//                        this.$Message.error('请完整上传7张图片');
//                        return false;
//                    }
                }
                let action = 'ordersell/imgupload';
                this.$http.post(api_param.apiurl + action,{'images':images,'order_id':this.data.order_id},
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
    };
</script>
