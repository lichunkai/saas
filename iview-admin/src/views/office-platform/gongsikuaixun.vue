<template>
    <Row>
        <Col :lg="24" :md="24" class="marginBottom">
        <Card>
            <Row :gutter="5">
                <Col :lg="3" :md="3">
                <DatePicker type="daterange" :value="daterange" @on-change="handleChange" format="yyyy/MM/dd"
                            placeholder="选择时间段"></DatePicker>
                </Col>
                <Col :lg="4" :md="4">
                <Input v-model="keyword" placeholder="关键字"></Input>
                </Col>
                <Col :lg="3" :md="3">
                <Button type="primary" @click="doSearch">查询</Button>
                <Button type="primary" @click="clearSearch">清空</Button>
                </Col>
                <Col :lg="3" :md="3" offset="11">
                <Row type="flex" justify="end">
                    <Col>
                    <div v-if="topbutton.add == 1">
                    <Button type="primary" @click="addNews">新增</Button>
                    </div>
                    <Modal v-model="newsModal" width="960px" :mask-closable="false">
                        <div slot="header">
                            <a class="ivu-modal-close" @click="modalCancel"><i
                                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                            <div class="ivu-modal-header-inner">{{newsmodaltitle}}</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="modalCancel">取消</Button>
                            <Button type="primary" size="large" @click="modalOk">确定</Button>
                        </div>

                        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
                            <Row>
                                <Col :lg="4" :md="4" style="text-align: center">
                                <div class="demo-upload-list" v-for="item in uploadList">
                                    <template>
                                        <img :src="item.url">
                                        <div class="demo-upload-list-cover">
                                            <Icon type="ios-eye-outline" @click.native="handleView(item.name)"></Icon>
                                            <Icon type="ios-trash-outline" @click.native="handleRemove(item)"></Icon>
                                        </div>
                                    </template>
                                    <!--<template >-->
                                    <!--<Progress v-if="item.showProgress" :percent="item.percentage"-->
                                    <!--hide-info></Progress>-->
                                    <!--</template>-->
                                </div>
                                <Upload ref="upload" :show-upload-list="false" :default-file-list="defaultList"
                                        :headers="{'X-Access-Token':xtoken}"
                                        :on-success="handleSuccess" :format="['jpg','jpeg','png']" :max-size="10240"
                                        :on-format-error="handleFormatError"
                                        :on-exceeded-size="handleMaxSize" :before-upload="handleBeforeUpload" multiple
                                        type="drag" :action="uploadurl" style="display: inline-block;width:120px;">
                                    <div style="width: 120px;height:120px;line-height: 120px;">
                                        <Icon type="camera" size="30"></Icon>
                                    </div>
                                </Upload>
                                <div style="margin-top: 5px">图片上传</div>
                                <div style="margin-top: 5px">建议上传<span style="color:red">570*260</span>像素的图片</div>
                                <Modal title="图片预览" v-model="visible" :transfer="false">
                                    <img :src="imgurl + imgName" v-if="visible"
                                         style="width: 100%">
                                </Modal>
                                </Col>
                                <Col :lg="20" :md="20">
                                <Row>
                                    <Col :lg="24" :md="24">
                                    <Row>
                                        <Col :lg="18" :md="18">
                                        <FormItem label="标题" prop="news_title">
                                            <Input v-model="formValidate.news_title"></Input>
                                        </FormItem>
                                        </Col>
<!--                                        <Col :lg="6" :md="6">-->
<!--                                        <FormItem label="" prop="">-->
<!--                                            <Checkbox v-model="formValidate.is_pop">是否弹出</Checkbox>-->
<!--                                        </FormItem>-->
<!--                                        </Col>-->
                                    </Row>
                                    </Col>
                                    <Col :lg="24" :md="24">
                                    <FormItem label="正文" prop="notice_content">
                                        <textarea id="gongsikuaixun" v-model="formValidate.notice_content"></textarea>
                                    </FormItem>
                                    </Col>
                                </Row>
                                </Col>
                            </Row>
                        </Form>
                    </Modal>
                    </Col>
                </Row>
                </Col>
            </Row>
        </Card>
        </Col>
        <Col :lg="24" :md="24">
        <Card>
            <Table :columns="columns" :data="listData" border stripe highlight-row
                   @on-row-dblclick="clickRow"></Table>
            <!--详情弹窗-->
            <Modal v-model="xiangqing" width="960px" title='快讯详情' :mask-closable="false">
                <div slot="footer" class="kuaixun">
                    <Button type="primary" size="large" @click="closexiangqing">关闭</Button>
                </div>
                <Row :gutter="40">
                    <Col :lg="24" :md="24" style="text-align: center;margin-bottom: 30px">
                    <strong v-html="xiangqingtitle" style="font-size: 20px;"></strong>
                    <p v-html="ctime" style="margin-top: 5px"></p>
                    </Col>
                    <Col :lg="12" :md="12">
                    <div style="border-radius: 3px;border: #f5f5f5 1px solid;overflow:hidden;">
                        <img style="display: block;width: 100%" :src='xiangqingImg'/>
                    </div>
                    </Col>
                    <Col :lg="12" :md="12">
                    <div v-html="xiangqingcont"></div>
                    </Col>
                </Row>
            </Modal>

            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="totalnum" :current="currentpage" :page-size="10" @on-change="changePage"
                          show-total></Page>
                </div>
            </div>
        </Card>
        </Col>

    </Row>

</template>

<script>
    import tinymce from 'tinymce';

    export default {
        name: 'gongsikuaixun',
        components: {},
        data() {

            const validateContentCheck = (rule, value, callback) => {
                console.log(this.content.length);
                if (this.content.length == 2) {
                    callback(new Error('请输入资讯内容'));
                } else {
                    callback();
                }
            };

            return {
                xiangqing: false,
                xiangqingtitle: '',
                xiangqingcont: '',
                xiangqingImg: '',
                ctime: '',
                topbutton:'',
                newsModal: false,
                newsmodaltitle: '',
                type:'',
                keyword: '',
                daterange: '',
                totalnum: 0,
                currentpage: 1,
                formValidate: {
                    news_id: '',
                    news_title: '',
                    // is_pop:'',
                    news_content: ''
                },
                ruleValidate: {
                    news_title: [
                        {required: true, message: '请输入资讯标题', trigger: 'blur'},
                    ],
                    news_content: [
                        {validator: validateContentCheck, trigger: 'blur'}
                    ],
                },
                columns: [
                    // {
                    //     title: '弹出',
                    //     key: 'name',
                    //     align: 'center',
                    //     width:88,
                    //     render: (h, params) => {
                    //         if(params.row.is_pop == 1){
                    //             return h('div', [
                    //                 h('Icon', {
                    //                     props: {
                    //                         type: 'checkmark-round'
                    //                     },
                    //                     style:{
                    //                         color:'#ff0000'
                    //                     }
                    //                 }),
                    //             ]);
                    //         }
                    //     }
                    // },
                    {
                        title: '标题',
                        key: 'news_title',
                        align: 'center',
                    },
                    {
                        title: '日期',
                        key: 'ctime',
                        align: 'center'
                    },
                    {
                        title: '操作',
                        key: 'caozuo',
                        align: 'center',
                        render: (h, params) => {
                            let ret = [];
                            if (params.row.button.top == 1) {
                                let text = '置顶';
                                let content = '<h3>您确定要置顶内容吗？</h3>';
                                let status = 1;
                                if (params.row.is_top == 1) {
                                    text = '取消置顶';
                                    content = '<h3>您确定要取消置顶内容吗？</h3>';
                                    status = 0;
                                }
                                ret.push(h('Button', {
                                    props: {
                                        type: 'warning',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            var than = this;
                                            this.$Modal.confirm({
                                                title: '提示',
                                                content: content,
                                                onOk() {
                                                    this.$http.post(api_param.apiurl + 'news/top',
                                                        {
                                                            'news_id': params.row.news_id,
                                                            'status': status
                                                        },
                                                        {
                                                            emulateJSON: true,
                                                            headers: {"X-Access-Token": api_param.XAccessToken}
                                                        }
                                                    ).then(function (response) {
                                                        // 这里是处理正确的回调
                                                        if (response.data.code == 200) {
                                                            this.$Message.success(response.data.message);
                                                            than.getIndex();
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
                                                        this.$Message.warning(response.data.message);
                                                    })
                                                }
                                            });
                                        }
                                    }
                                }, text))
                            }
                            if (params.row.button.edit == 1) {
                                ret.push(h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.editNews(params.row);
                                        }
                                    }
                                }, '编辑'))
                            }
                            if (params.row.button.del == 1) {
                                ret.push(h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            var than = this;
                                            this.$Modal.confirm({
                                                title: '提示',
                                                content: `<h3>您确定要删除内容吗？</h3>`,
                                                onOk() {
                                                    this.$http.post(api_param.apiurl + 'news/del',
                                                        {
                                                            'news_id': params.row.news_id,
                                                        },
                                                        {
                                                            emulateJSON: true,
                                                            headers: {"X-Access-Token": api_param.XAccessToken}
                                                        }
                                                    ).then(function (response) {
                                                        // 这里是处理正确的回调
                                                        if (response.data.code == 200) {
                                                            this.$Message.success(response.data.message);
                                                            than.getIndex();
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
                                                        this.$Message.warning(response.data.message);
                                                    })
                                                }
                                            });
                                        }
                                    }
                                }, '删除'))
                            }
                            if (params.row.button.eject == 1) {
                                let text = '弹出';
                                let content = '<h3>您确定要弹出内容吗？</h3>';
                                let ej_status = 1;
                                if (params.row.is_pop == 1) {
                                    text = '取消弹出';
                                    content = '<h3>您确定要取消弹出内容吗？</h3>';
                                    ej_status = 0;
                                }
                                ret.push(h('Button', {
                                    props: {
                                        type: 'warning',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            var than = this;
                                            this.$Modal.confirm({
                                                title: '提示',
                                                content: content,
                                                onOk() {
                                                    this.$http.post(api_param.apiurl + 'news/eject',
                                                        {
                                                            'news_id': params.row.news_id,
                                                            'status': ej_status
                                                        },
                                                        {
                                                            emulateJSON: true,
                                                            headers: {"X-Access-Token": api_param.XAccessToken}
                                                        }
                                                    ).then(function (response) {
                                                        // 这里是处理正确的回调
                                                        if (response.data.code == 200) {
                                                            this.$Message.success(response.data.message);
                                                            than.getIndex();
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
                                                        this.$Message.warning(response.data.message);
                                                    })
                                                }
                                            });
                                        }
                                    }
                                }, text))
                            }
                            return h('div', ret);
                        }
                    }
                ],
                listData: [],
                // 图片上传
                xtoken: api_param.XAccessToken,
                uploadurl: api_param.apiurl + 'site/upload',
                imgurl: api_param.imgurl,
                defaultList: [],
                imgName: '',
                visible: false,
                uploadList: []
            }
        },
        created() {
            this.getOptions();
            this.getIndex()
        },
        methods: {
            getOptions() { //获取下拉菜单
                this.$http.get(api_param.apiurl + 'news/getsetting', {
                    params: {},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.newstype = response.data.data.newstype,
                            this.topbutton = response.data.data.topbutton
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
                    console.log(response)
                })
            },
            getIndex() { //列表页
                this.$http.get(api_param.apiurl + 'news/getindex', {
                    params: {
                        type: this.type,
                        daterange: this.daterange,
                        kw: this.keyword,
                        page: this.currentpage
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.totalnum = parseInt(response.data.data.totalnum);
                        this.listData = response.data.data.newslist;
                    } else if (response.data.code == 401) {
                        this.$store.commit('logout', this);
                        this.$store.commit('clearOpenedSubmenu');
                        this.$router.push({
                            name: 'login'
                        });
                    } else {
                        this.$Message.warning(response.data.message);
                    }
                    //console.log(this.purviews);
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response)
                })
            },
            //搜索
            doSearch() {
                this.currentpage = 1;
                this.getIndex();
            },
            clearSearch() {
                this.daterange = '';
                this.keyword = '';
                this.getIndex();
            },
            //分页
            changePage(page) {
                this.currentpage = page;
                this.getIndex();
            },
            handleChange(date) { //选择日期回调
                this.daterange = date;
                //console.log(this.month);
            },
            clickRow(data, index) {
                console.log(data);
                this.xiangqing = true;
                this.xiangqingtitle = data.news_title;
                this.xiangqingcont = data.news_content;
                this.xiangqingImg = api_param.imgurl + data.news_images;
                this.ctime = data.utime
            },
            closexiangqing() {
                this.xiangqing = false;
            },
            addNews() {
                this.$refs['formValidate'].resetFields();
                this.newsmodaltitle = '新增资讯';
                this.uploadList = [];
                this.formValidate.news_id = '';
                this.formValidate.news_title = '';
                this.formValidate.is_pop = false;
                this.formValidate.news_content = '';
                let _this = this;
                setTimeout(function () {
                    _this.newsModal = true;
                }, 300);
            },
            editNews(params) {
                this.$refs['formValidate'].resetFields();
                this.newsmodaltitle = '修改资讯';
                this.formValidate.news_id = params.news_id;
                this.formValidate.news_title = params.news_title;
                this.formValidate.is_pop = params.is_pop == 1? true: false;
                console.log(params.news_images);
                this.uploadList = [];
                if (params.news_images) {
                    this.uploadList.push({'name': params.news_images, 'url': api_param.imgurl + params.news_images});
                }
                tinymce.activeEditor.setContent(params.news_content);

                let _this = this;
                setTimeout(function () {
                    _this.newsModal = true;
                }, 300);
            },
            modalOk() {
                var tinymcecontent = tinymce.activeEditor.getContent();
                tinymcecontent = tinymcecontent.split('<body>');
                this.content = tinymcecontent[1].split('</body>')[0];
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {

                        if (this.uploadList.length < 1) {
                            this.$Message.warning('图片必须上传');
                            return false;
                        }
                        let action = 'news/add';
                        if (this.formValidate.news_id) {
                            action = 'news/edit';
                        }
                        this.$http.post(api_param.apiurl + action,
                            {
                                'news_id': this.formValidate.news_id,
                                'news_title': this.formValidate.news_title,
                                'news_images': this.uploadList[0]['name'] == undefined ? '' : this.uploadList[0]['name'],
                                'is_pop': this.formValidate.is_pop,
                                'news_content': tinymce.activeEditor.getContent(),
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['formValidate'].resetFields();
                                this.newsModal = false;
                                tinymce.activeEditor.setContent('');
                                this.getIndex();
                            } else if (response.data.code == 401) {
                                this.$store.commit('logout', this);
                                this.$store.commit('clearOpenedSubmenu');
                                this.$router.push({
                                    name: 'login'
                                });
                            } else {
                                this.$Message.warning(response.data.message);
                                this.newsModal = false;
                            }
                        }, function (response) {
                            // 这里是处理错误的回调
                            console.log(response)
                        })
                    }
                });
            },
            modalCancel() {
                this.$refs['formValidate'].resetFields();//清空form规则检查
                this.newsModal = false;
                this.newsmodaltitle = '';
                tinymce.activeEditor.setContent('');
            },
            // 图片上传
            handleView(name) {
                this.imgName = name;
                this.visible = true;
            },
            handleRemove(file) {
                this.uploadList = [];
            },
            handleSuccess(res, file) {
                file.url = api_param.imgurl + res.data.url;
                file.name = res.data.url;
                this.uploadList.push({'name': file.name, 'url': file.url});
                const fileList = this.$refs.upload.fileList;
                this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
                console.log(fileList);
            },
            handleFormatError(file) {
                this.$Message.warning('文件格式不正确，请选择JPG或PNG');
            },
            handleMaxSize(file) {
                this.$Message.warning('文件  ' + file.name + ' 超过 10M.');
            },
            handleBeforeUpload() {
                const check = this.uploadList.length < 1;
                if (!check) {
                    this.$Message.warning('最多上传1张图片');
                }
                return check;
            }
        },
        mounted() {
            this.uploadList = this.$refs.upload.fileList;
            tinymce.init({
                selector: '#gongsikuaixun',
                branding: false,
                elementpath: false,
                height: 360,
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
            tinymce.get('gongsikuaixun').destroy();
        },
    };
</script>

<style>
	.kuaixun .ivu-row{
		margin: 0 !important;
	}
    .marginBottom {
        margin-bottom: 10px;
    }

    .demo-upload-list {
        display: inline-block;
        width: 120px;
        height: 120px;
        text-align: center;
        line-height: 120px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .2);
        margin-right: 4px;
    }

    .demo-upload-list img {
        width: 100%;
        height: 100%;
    }

    .demo-upload-list-cover {
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, .6);
    }

    .demo-upload-list:hover .demo-upload-list-cover {
        display: block;
    }

    .demo-upload-list-cover i {
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
</style>