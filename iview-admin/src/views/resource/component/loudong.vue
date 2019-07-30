<style scoped>

    ul {
        margin: 20px 0;
    }

    ul li {
        float: left;
        margin: 20px;
    }

    .addBnt {
        width: 180px;
        margin: 20px auto 0 auto;
        display: block;
    }

    .ivu-checkbox-wrapper {
        float: left !important;
        width: 20%;
        margin: 5px 0px !important;
        display: block;
    }

    .ivu-modal-wrap * {
        overflow: hidden !important;
    }
    .accessLft{
        float: left;
    }
    .accessInput{
        margin: 0;
        padding: 0;
        border: 1px red solid;
        width: 68px;
        height: 36px;
        text-align: center;
        border-radius: 3px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        display: none;
        float: right;
    }
    .accessLou li:hover .accessInput{
        display: block;
    }

</style>

<template>
    <Card>
        <p slot="title" style="height: auto">
            <Col :lg="2" :md="2" >
                <Icon type="compose"></Icon>
                {{$route.params.projName}}-楼栋信息
            </Col>
            <!--<Badge :count="keshou"></Badge>-->
            <!--<Badge :count="yishou" class-name="demo-badge-alone"></Badge>-->

            <Col :lg="2" :md="2" offset="20">
                <Row type="flex" justify="end">
                    <Col>
                        <Button @click="loudongEdit = true" type="primary">新增楼栋</Button>
                        <Modal v-model="loudongEdit" title="新增楼栋" :closable="false" :mask-closable="false">
                            <div slot="header">
                                <a class="ivu-modal-close" @click="modalCancel"><i
                                        class="ivu-icon ivu-icon-ios-close-empty" style="display: block!important;"></i></a>
                                <div class="ivu-modal-header-inner">新增楼栋</div>
                            </div>
                            <div slot="footer">
                                <Button type="text" size="large" @click="modalCancel">取消</Button>
                                <Button type="primary" size="large" @click="modalOk">确定</Button>
                            </div>
                            <div style="border-bottom: 1px solid #e9e9e9;padding-bottom:6px;margin-bottom:6px;overflow: hidden">
                                <Checkbox :indeterminate="indeterminate" :value="checkAll"
                                          @click.prevent.native="handleCheckAll">
                                    全选
                                </Checkbox>
                            </div>
                            <CheckboxGroup v-model="checkAllGroup" @on-change="checkAllGroupChange">
                   <span v-for="item in loudongbq">
                        <Checkbox :label="item.label"><span>{{item.label}}栋</span></Checkbox>
                    </span>
                            </CheckboxGroup>
                            <div style="overflow: hidden">
                                <Button type="dashed" @click="handleAdd" icon="plus-round" class="addBnt">添加更多楼栋信息</Button>
                            </div>
                        </Modal>
                    </Col>
                </Row>
            </Col>
        </p>
        <Row>
            <!--<Col :lg="5" :md="5">-->
            <!--销售状态：-->
            <!--<Button type="primary">可售</Button>-->
            <!--<Button type="error">已售</Button>-->
            <!--</Col>-->

            <Col :lg="24" :md="24">
            <ul class="accessLou" v-for="item in loudonglist">
                <li v-if="item.keshou_count>0">
                    <router-link
                            v-if="item.remark"  :to="{ name: 'danyuan', params:{ label:item.remark,bu_id: item.bu_id,h_id:item.h_id,projName:$route.params.projName}}">
                        <Button type="primary" size="large" class="accessLft" >{{item.remark}}</Button>
                    </router-link>
                    <router-link
                            v-if="!item.remark"    :to="{ name: 'danyuan', params:{ label:item.label+'栋',bu_id: item.bu_id,h_id:item.h_id,projName:$route.params.projName}}">
                        <Button type="primary" size="large" >{{item.label}}栋</Button>
                    </router-link>
                    <input v-model="gaibian"  class="accessInput"  v-on:blur ="change(gaibian,item.bu_id)">
                </li>
                <li v-else-if="item.keshou_count==0">
                    <router-link
                            v-if="item.remark"  :to="{ name: 'danyuan', params:{ label:item.remark,bu_id: item.bu_id,h_id:item.h_id,projName:$route.params.projName,projNumber:$route.params.projNumber}}">
                        <Button type="error" size="large" class="accessLft" >{{item.remark}}</Button>
                    </router-link>
                    <router-link
                            v-if="!item.remark"    :to="{ name: 'danyuan', params:{ label:item.label+'栋',bu_id: item.bu_id,h_id:item.h_id,projName:$route.params.projName,projNumber:$route.params.projNumber}}">
                        <Button type="error" size="large" >{{item.label}}栋</Button>
                    </router-link>
                    <input v-model="gaibian" class="accessInput"  v-on:blur="change(gaibian,item.bu_id)">
                </li>
            </ul>
            </Col>
        </Row>
    </Card>
</template>

<script>
    export default {
        name: 'loudong',
        data() {
            return {
                keshou: '',
                yishou: '',
                gaibian:'',
                loudongbq: [
                    {label: '1'},
                    {label: '2'},
                    {label: '3'},
                    {label: '4'},
                    {label: '5'}
                ],
                loudongbq_max: 5,
                checkgo: [
                    '1', '2', '3', '4', '5'
                ],
                loudongEdit: false,
                indeterminate: true,
                checkAll: false,
                loudonglist: [],
                checkAllGroup: []
            };
        },
        created: function () {
            this.getbuilding();              //定义方法
        },
        methods: {
            getbuilding() {
                this.$http.get(api_param.apiurl + 'building/buildinglist', {
                    params: {
                        h_id: this.$route.params.projId
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    this.loudonglist = response.data.data.list;
                    this.keshou = '可售' + response.data.data.keshou_count;
                    this.yishou = '已售' + response.data.data.yishou_count;
                    var arr = [];
                    var max = 0;
                    for (var i = 0; i < this.loudonglist.length; i++) {
                        var l = this.loudonglist[i]['label'];
                        max = (max < parseInt(l)) ? parseInt(l) : max;
                        arr.push('' + l + '');
                    }
                    if (max > 5) {
                        var yu = max % 5;
                        var zheng = parseInt(max / 5);
                    }

                    if (yu > 0 && zheng > 0) {
                        this.loudongbq_max = this.loudongbq_max * zheng + 5;
                    } else if (zheng > 0) {
                        this.loudongbq_max = this.loudongbq_max * zheng;
                    }
                    var arr1 = [];
                    if (this.loudongbq_max > 5) {
                        this.loudongbq = [];
                        for (var i = 1; i <= this.loudongbq_max; i++) {
                            arr1.push('' + i + '');
                            this.loudongbq.push({label: '' + i + ''});
                        }
                    }
                    this.checkAllGroup = arr;
                    if (arr1.length > 0) {
                        this.checkgo = arr1;
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            }, change (gaibian,bu_id) {
                if(gaibian!=""){
                    this.$http.post(api_param.apiurl + 'building/bq',
                        {
                            'bu_id': bu_id,
                            'gaibian':gaibian,
                        },
                        {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                    ).then(function (response) {
                        // 这里是处理正确的回调
                        this.gaibian="";
                        this.modalCancel();
                    }, function (response) {
                        // 这里是处理错误的回调
                        //console.log(response)
                        this.gaibian="";
                    });
                }
            },handleAdd() {
                for (var i = 0; i < 5; i++) {
                    this.loudongbq_max = this.loudongbq_max + 1;
                    var v = this.loudongbq_max;
                    this.loudongbq.push({label: '' + v + ''});
                }

            },
            handleCheckAll() {
                if (this.indeterminate) {
                    this.checkAll = false;
                } else {
                    this.checkAll = !this.checkAll;
                }
                this.indeterminate = false;

                if (this.checkAll) {
                    this.checkAllGroup = this.checkgo;
                } else {
                    this.checkAllGroup = [];
                }
            },
            modalOk() {
                this.$http.post(api_param.apiurl + 'building/updatebuilding',
                    {
                        'h_id': this.$route.params.projId,
                        'bu_ridgepole': this.checkAllGroup,
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    this.$Message.success('保存成功');
                    this.modalCancel();
                }, function (response) {
                    // 这里是处理错误的回调
                    //console.log(response)
                    this.$Message.warning('保存失败');
                });

            },
            modalCancel() {
                this.checkAllGroup = [];
                this.loudongbq = [
                    {label: '1'},
                    {label: '2'},
                    {label: '3'},
                    {label: '4'},
                    {label: '5'}
                ];
                this.loudongbq_max = 5;
                this.getbuilding();
                this.loudongEdit = false;
                var arr = [];
                for (var i = 0; i < this.loudonglist.length; i++) {
                    var l = this.loudonglist[i]['label'];
                    arr.push('' + l + '');
                }
                this.checkAllGroup = arr;
            },
            checkAllGroupChange(data) {
                if (data.length === 3) {
                    this.indeterminate = false;
                    this.checkAll = true;
                } else if (data.length > 0) {
                    this.indeterminate = true;
                    this.checkAll = false;
                } else {
                    this.indeterminate = false;
                    this.checkAll = false;
                }
            }
        }, watch: {
            '$route.params.projId'(to, from) {
                if (this.$route.params.projId) {
                    this.modalCancel();
                }
            }
        }
    };
</script>

