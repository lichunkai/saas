<template>
    <Row>
        <Col :lg="24" :md="24" class="marginBottom">
        <Card>
            <Row :gutter="5">
                <Col :lg="2" :md="2">
                <Select v-model.sync="type" placeholder="公告类别">
                    <Option v-for="(item,index) in noticetype" :value="item.child_name" :key="index">{{ item.child_name
                        }}
                    </Option>
                </Select>
                </Col>
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
                <Col :lg="3" :md="3" offset="9">
                <Row type="flex" justify="end">
                    <Col>
                    <div v-if="topbutton.add == 1">
                        <Button type="primary" @click="addNotice">新增</Button>
                    </div>
                    <Modal v-model="noticeModal" width="960" :mask-closable="false">
                        <div slot="header">
                            <a class="ivu-modal-close" @click="modalCancel"><i
                                    class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                            <div class="ivu-modal-header-inner">{{noticemodaltitle}}</div>
                        </div>
                        <div slot="footer">
                            <Button type="text" size="large" @click="modalCancel">取消</Button>
                            <Button type="primary" size="large" @click="modalOk">确定</Button>
                        </div>

                        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
                            <Row>
                                <Col :lg="24" :md="24">
                                <Row>
                                    <Col :lg="6" :md="6">
                                    <FormItem label="公告类别" prop="notice_type">
                                        <Select placeholder="公告类别" v-model="formValidate.notice_type">
                                            <Option v-for="(item,index) in noticetype" :value="item.child_name"
                                                    :key="item.value">{{ item.child_name}}
                                            </Option>
                                        </Select>
                                    </FormItem>
                                    </Col>
                                    <Col :lg="12" :md="12">
                                    <FormItem label="标题" prop="notice_title">
                                        <Input v-model="formValidate.notice_title"></Input>
                                    </FormItem>
                                    </Col>
<!--                                    <Col :lg="6" :md="6">-->
<!--                                    <FormItem label="" prop="">-->
<!--                                        <Checkbox v-model="formValidate.is_pop">是否弹出</Checkbox>-->
<!--                                    </FormItem>-->
<!--                                    </Col>-->
                                </Row>
                                </Col>
                                <Col :lg="24" :md="24">
                                <FormItem label="正文" prop="notice_content">
                                    <textarea id="articleEditor" v-model="formValidate.notice_content"></textarea>
                                </FormItem>
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

            <Modal v-model="xiangqing" width="960px" :title='xiangqingtitle' :mask-closable="false">
                <div slot="footer" class="gonggao">
                    <Button type="primary" size="large" @click="closexiangqing">关闭</Button>
                </div>
                <Row :gutter="40">
                    <Col :lg="24" :md="24">
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
        name: 'gongsigonggao',
        components: {},
        data() {

            const validateContentCheck = (rule, value, callback) => {
                console.log(this.content.length);
                if (this.content.length == 2) {
                    callback(new Error('请输入公告内容'));
                } else {
                    callback();
                }
            };

            return {
                xiangqing: false,
                xiangqingtitle: '',
                xiangqingcont: '',
                ctime: '',
                topbutton:'',
                noticeModal: false,
                noticemodaltitle: '',
                noticetype: [],
                daterange: [],
                totalnum: 0,
                currentpage: 1,
                content: '',
                type:'',
                keyword:'',
                formValidate: {
                    notice_id: '',
                    notice_type: '',
                    notice_title: '',
                    notice_content: ''
                },
                ruleValidate: {
                    notice_type: [
                        {required: true, message: '请选择公告类型', trigger: 'change'},
                    ],
                    notice_title: [
                        {required: true, message: '请输入公告标题', trigger: 'blur'},
                    ],
                    notice_content: [
                        {validator: validateContentCheck, trigger: 'blur'}
                    ],
                },
                columns: [
                    // {
                    //     title: '弹出',
                    //     key: 'name',
                    //     align: 'center',
                    //      width:88,
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
                        title: '类型',
                        key: 'notice_type',
                        align: 'center',
                    },
                    {
                        title: '标题',
                        key: 'notice_title',
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
                                                    this.$http.post(api_param.apiurl + 'notice/top',
                                                        {
                                                            'notice_id': params.row.notice_id,
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
                                                        } else{
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
                                            this.editNotice(params.row);
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
                                                    this.$http.post(api_param.apiurl + 'notice/del',
                                                        {
                                                            'notice_id': params.row.notice_id,
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
                                                    this.$http.post(api_param.apiurl + 'notice/eject',
                                                        {
                                                            'notice_id': params.row.notice_id,
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
                                                        } else{
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
                listData: []

            }
        },
        created() {
            this.getSetting();
            this.getIndex()
        },
        methods: {
            getSetting() { //获取下拉菜单
                this.$http.get(api_param.apiurl + 'notice/getsetting', {
                    params: {},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    if (response.data.code == 200) {
                        this.noticetype = response.data.data.noticetype,
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
                this.$http.get(api_param.apiurl + 'notice/getindex', {
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
                        this.listData = response.data.data.noticelist;
                        // console.log(response);
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
                this.type = '';
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
                // this.$Modal.info({
                //     title: data.notice_title,
                //     content: data.notice_content,
                //     width:'640'
                // });
                this.xiangqing = true;
                this.xiangqingtitle = data.notice_title;
                this.xiangqingcont = data.notice_content;
                this.ctime = data.utime;
            },
            closexiangqing() {
                this.xiangqing = false;
            },

            addNotice() {
                this.$refs['formValidate'].resetFields();
                this.noticemodaltitle = '新增公告';
                this.formValidate.notice_id = '';
                this.formValidate.notice_type = '';
                this.formValidate.notice_title = '';
                this.formValidate.is_pop = false;
                this.formValidate.notice_content = '';
                let _this = this;
                setTimeout(function () {
                    _this.noticeModal = true;
                }, 300);
            },
            editNotice(params) {
                this.$refs['formValidate'].resetFields();
                this.noticemodaltitle = '修改公告';
                this.formValidate.notice_id = params.notice_id;
                this.formValidate.notice_type = params.notice_type;
                this.formValidate.notice_title = params.notice_title;
                this.formValidate.is_pop = params.is_pop == 1 ? true : false;
                tinymce.activeEditor.setContent(params.notice_content);

                let _this = this;
                setTimeout(function () {
                    _this.noticeModal = true;
                }, 300);
            },
            modalOk() {
                var tinymcecontent = tinymce.activeEditor.getContent();
                tinymcecontent = tinymcecontent.split('<body>');
                this.content = tinymcecontent[1].split('</body>')[0];
                this.$refs['formValidate'].validate((valid) => {
                    if (valid) {
                        let action = 'notice/add';
                        if (this.formValidate.notice_id) {
                            action = 'notice/edit';
                        }
                        this.$http.post(api_param.apiurl + action,
                            {
                                'notice_id': this.formValidate.notice_id,
                                'notice_type': this.formValidate.notice_type,
                                'notice_title': this.formValidate.notice_title,
                                // 'is_pop': this.formValidate.is_pop,
                                'notice_content': tinymce.activeEditor.getContent(),
                            },
                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.$refs['formValidate'].resetFields();
                                this.noticeModal = false;
                                tinymce.activeEditor.setContent('');
                                this.getIndex();
                            } else if (response.data.code == 401) {
                                this.$store.commit('logout', this);
                                this.$store.commit('clearOpenedSubmenu');
                                this.$router.push({
                                    name: 'login'
                                });
                            } else{
                                this.$Message.warning(response.data.message);
                                this.noticeModal = false;
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
                this.noticeModal = false;
                this.noticemodaltitle = '';
                tinymce.activeEditor.setContent('');
            },
        },
        mounted() {
            tinymce.init({
                selector: '#articleEditor',
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
            tinymce.get('articleEditor').destroy();
        }
    };
</script>

<style>
	.gonggao .ivu-row{
		margin: 0px !important;
	}
    .marginBottom {
        margin-bottom: 10px;
    }

    .mce-edit-area {
        height: 360px;
        overflow-y: auto;
    }
</style>