<style scoped>
    @import '../../../styles/common.less';
    @import "fanghao.less";
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

</style>

<template>
    <Card>
        <p slot="title" style="height: auto">
            <Col :lg="2" :md="2">
            <Icon type="compose"></Icon>
            {{$route.params.projName}}-({{$route.params.label}})-单元信息
            <!--<Badge :count="keshou"style="color: #00aa00"></Badge>-->
            <!--<Badge :count="yishou" class-name="demo-badge-alone"></Badge>-->
            </Col>
            <Col :lg="2" :md="2" offset="20">
                <Button @click="loudongEdit = true" type="primary">新增单元</Button>
                <Modal v-model="loudongEdit" title="新增单元" :closable="false" :mask-closable="true">
                    <!--<div slot="header">-->
                    <!--<a class="ivu-modal-close" @click="modalCancel"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>-->
                    <!--<div class="ivu-modal-header-inner">新增单元</div>-->
                    <!--</div>-->
                    <div slot="header">
                        <a class="ivu-modal-close" @click="modalCancel" style="display: block!important;"><i
                                class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                        <div class="ivu-modal-header-inner">新增单元</div>
                    </div>
                    <div slot="footer">
                        <Button type="text" size="large" @click="modalCancel">取消</Button>
                        <Button type="primary" size="large" @click="modalOk">确定</Button>
                    </div>
                    <div style="border-bottom: 1px solid #e9e9e9;padding-bottom:6px;margin-bottom:6px;overflow: hidden">
                        <Checkbox :indeterminate="indeterminate" :value="checkAll" @click.prevent.native="handleCheckAll">
                            全选
                        </Checkbox>
                    </div>
                    <CheckboxGroup v-model="checkAllGroup" @on-change="checkAllGroupChange">
                   <span v-for="item in loudongbq">
                    <Checkbox :label="item.label"><span>{{item.label}}单元</span></Checkbox>
                    </span>
                    </CheckboxGroup>
                    <div style="overflow: hidden">
                        <Button type="dashed" @click="handleAdd" icon="plus-round" class="addBnt">添加更多单元信息</Button>
                    </div>
                </Modal>
            </Col>
        </p>
        <Row>
            <Col :lg="5" :md="5">
            <!--销售状态：-->
            <!--<Button type="primary">可售</Button>-->
            <!--<Button type="error">已售</Button>-->
            </Col>
            <Col :lg="2" :md="2" offset="17">
            <Row type="flex" justify="end">

            </Row>
            </Col>
            <Col :lg="24" :md="24">
            <ul class="accessLou" v-for="item in loudonglist">
                <li v-if="item.keshou_count>0">
                    <router-link :to="{ name: 'fanghao', params:{ label:$route.params.label,el_id: item.el_id,bu_id:item.bu_id,h_id:item.h_id,projName:$route.params.projName,projNumber:$route.params.projNumber,dy:item.label}}">
                        <Button type="primary" size="large">{{item.label}}单元</Button>
                    </router-link>
                </li>
                <li v-else-if="item.keshou_count==0">
                    <router-link :to="{ name: 'fanghao', params:{ label:$route.params.label,el_id: item.el_id,bu_id:item.bu_id,h_id:item.h_id,projName:$route.params.projName,projNumber:$route.params.projNumber,dy:item.label}}">
                        <Button type="error" size="large">{{item.label}}单元</Button>
                    </router-link>
                </li>
            </ul>
            </Col>
        </Row>
    </Card>
</template>

<script>
    export default {
        keshou:'',
        yishou:'',
        name: 'loudong',
        data () {
            return {
                loudongbq: [
                    {label: '1'},
                    {label: '2'},
                    {label: '3'},
                    {label: '4'},
                    {label: '5'}
                ],
                loudongbq_max:5,
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
            getbuilding () {
                this.$http.get(api_param.apiurl + 'building/element', {
                    params: {
                        bu_id: this.$route.params.bu_id
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    this.loudonglist = response.data.data.list;
                    this.keshou ='可售'+ response.data.data.keshou_count;
                    this.yishou = '已售'+response.data.data.yishou_count;
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

                    if (yu > 0 && zheng >0) {
                        this.loudongbq_max = this.loudongbq_max * zheng + 5;
                    } else if(zheng >0) {
                        this.loudongbq_max = this.loudongbq_max * zheng;
                    }
                    var arr1=[];
                    if (this.loudongbq_max > 5) {
                        this.loudongbq = [];
                        for (var i = 1; i <= this.loudongbq_max; i++) {
                            arr1.push('' + i + '');
                            this.loudongbq.push({label: '' + i + ''});
                        }
                    }
                    this.checkAllGroup = arr;
                    if(arr1.length>0){
                        this.checkgo=arr1;
                    }

                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            }, handleAdd () {
                console.log(this.loudongbq_max);
                for (var i = 0; i < 5; i++) {
                    this.loudongbq_max = this.loudongbq_max + 1;
                    var v = this.loudongbq_max;

                    this.loudongbq.push({label: '' + v + ''});
                }

            },
            handleCheckAll () {
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
            modalOk () {
                this.$http.post(api_param.apiurl + 'building/updateelement',
                    {
                        'el_element': this.checkAllGroup,
                        'bu_id': this.$route.params.bu_id,
                        'h_id': this.$route.params.h_id,
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
            modalCancel () {
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
            checkAllGroupChange (data) {
                console.log(this.checkAllGroup);
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
            '$route.params.bu_id' (to, from) {
                if(this.$route.params.bu_id){
                    this.modalCancel();
                }
            }
        }
    };
</script>

