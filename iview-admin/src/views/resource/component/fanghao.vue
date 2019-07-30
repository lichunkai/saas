<template>
    <Row>
        <Col :lg="2" :md="2" offset="20">
        <Modal v-model="houseNew" title="房号信息">
            <div slot="header">
                <a class="ivu-modal-close" @click="modalCancel" style="display: block!important;"><i class="ivu-icon ivu-icon-ios-close-empty"></i></a>
                <div class="ivu-modal-header-inner">房号信息</div>
            </div>
            <div slot="footer">
                <Button type="text" size="large" @click="modalCancel">取消</Button>
                <Button type="primary" size="large" @click="modalOk">确定</Button>
            </div>
            <ul class="orderEditMain" :model="editdata">
                <li>
                    <div class="orderEditCountent"><p>楼层:&nbsp;&nbsp;</p><span>{{editdata.bu_floor}}</span></div>
                </li>
                <li>
                    <div class="orderEditCountent"><p>面积:&nbsp;&nbsp;</p><span>{{editdata.bu_acreage}} m</span></div>
                </li>
                <li>
                    <div class="orderEditCountent"><p>总价:&nbsp;&nbsp;</p><span>{{editdata.bu_total}} 元</span></div>
                </li>
                <li>
                    <div class="orderEditCountent"><p>房号:&nbsp;&nbsp;</p><span>{{editdata.bu_h_number}}</span></div>
                </li>
                <li>
                    <!--<div class="orderEditCountent">-->
                        <!--<p>状态:&nbsp;&nbsp;</p>-->
                        <!--<span v-if="editdata.bu_market == 0 || editdata.bu_market ==  1">-->
                            <!--<Select v-model="editdata.bu_market">-->
                                   <!--<Option v-for="item in houseNewList" :value="item.value" :key="item.value">{{ item.label }}</Option>-->
                            <!--</Select>-->
                        <!--</span>-->
                        <!--<span v-if="editdata.bu_market == 2">-->
                            <!--认购-->
                        <!--</span>-->
                        <!--<span v-if="editdata.bu_market == 3">-->
                            <!--网签-->
                        <!--</span>-->
                        <!--<span v-if="editdata.bu_market == 4">-->
                            <!--草签-->
                        <!--</span>-->
                    <!--</div>-->
                </li>
            </ul>
        </Modal>
        </Col>
        <Col :lg="24" :md="24">
        <Row type="flex" justify="end" :gutter="10">
            <Col>
            <Button for="id" type="primary" icon="ios-cloud-upload-outline" @click="$refs.file.click()">导入房号</Button>
            <form method="post" enctype="multipart/form-data" name="form1">
                <input id="inputFile" type="file" ref="file" style="display:none" accept=".xls,.xlsx"
                       @change="getFile($event)">
            </form>
            </Col>
            <Col>
            <Button type="ghost"><a :href="imgurl" target="_blank">下载模板</a></Button>
            </Col>
        </Row>
        </Col>
        <Col :lg="24" :md="24" class="margintop10px">
        <Row class="houseList">
            <Col :lg="7" :md="7" offset="7">
            <div class="houseNum">
                <div class="housemain">
                    <span>{{$route.params.projName}}</span>  <span>{{$route.params.label}}</span>
                    <span>{{$route.params.dy}}单元</span>
                </div>
            </div>
            </Col>
            <!--<Col :lg="10" :md="10">-->
            <!--<Row type="flex" justify="end">-->
                <!--<Col>-->
                <!--<Button type="error" @click="skw(0)">销控({{xiaokong_count}})</Button>-->
                <!--<Button type="primary" @click="skw(3)">网签({{yishou_qy}})</Button>-->
                <!--<Button type="warning" @click="skw(4)">草签({{yishou_qy}})</Button>-->
                <!--<Button type="success" @click="skw(2)">认购({{yishou_rg}})</Button>-->
                <!--<Button type="ghost" @click="skw(1)">可售({{keshou_count}})</Button>-->
                <!--<Button type="primary" @click="rskw()">重置</Button>-->
                <!--</Col></Row>-->
            <!--</Col>-->
            <Col :lg="24" :md="24">
            <ul class="sellTable">

                <li v-for="v in fanghaolistzu">
                    <div class="unitNum"><strong>{{v.bu_floor}}</strong></div>
                    <div class="unitHouse">
                        <Button type="error" @click="edit(vzu)" v-for="vzu in fanghaolist"
                                v-if="v.bu_floor==vzu.bu_floor && vzu.bu_market==0">{{vzu.bu_h_number}}
                        </Button>
                        <Button type="ghost" @click="edit(vzu)" v-for="vzu in fanghaolist"
                                v-if="v.bu_floor==vzu.bu_floor && vzu.bu_market==1">{{vzu.bu_h_number}}
                        </Button>
                        <Button type="success" @click="edit(vzu)" v-for="vzu in fanghaolist"
                                v-if="v.bu_floor==vzu.bu_floor && vzu.bu_market==2">{{vzu.bu_h_number}}
                        </Button>
                        <Button type="primary" @click="edit(vzu)" v-for="vzu in fanghaolist"
                                v-if="v.bu_floor==vzu.bu_floor && vzu.bu_market==3">{{vzu.bu_h_number}}
                        </Button>
                        <Button type="warning" @click="edit(vzu)" v-for="vzu in fanghaolist"
                                v-if="v.bu_floor==vzu.bu_floor && vzu.bu_market==4">{{vzu.bu_h_number}}
                        </Button>
                    </div>
                </li>
            </ul>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'fanghao',
        data() {
            return {
                imgurl: api_param.imgurl + '/fhmb.xls',
                fanghaolist: '',
                fanghaolistzu: '',
                xiaokong_count: '',
                yishou_qy: '',
                yishou_rg: '',
                kw: '',
                keshou_count: '',
                houseNew: false,
                editdata: {
                    bu_floor: '',
                    bu_h_number: '',
                    bu_acreage: '',
                    bu_total: '',
                    bu_market: '',
                },
                houseNewList: [
                    {
                        value: '0',
                        label: '销控'
                    },
                    {
                        value: '1',
                        label: '可售'
                    }
                ],
            };
        }, created: function () {
            console.log(1);
            this.getTower();              //定义方法
        },
        methods: {
            skw(i) {
                this.kw = i;
                this.getTower();
            },
            rskw() {
                this.kw = '';
                this.getTower();
            },
            getTower() {
                this.$http.get(api_param.apiurl + 'building/towerlist', {
                    params: {
                        el_id: this.$route.params.el_id,
                        kw: this.kw
                    },
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    this.fanghaolist = response.data.data.list;
                    this.xiaokong_count = response.data.data.xiaokong_count;
                    this.yishou_rg = response.data.data.yishou_rg;
                    this.yishou_qy = response.data.data.yishou_qy;
                    this.keshou_count = response.data.data.keshou_count;
                    //楼层
                    this.fanghaolistzu = response.data.data.list1;
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            }, getFile(event) {
                this.file = event.target.files[0];
                event.preventDefault();
                let formData = new FormData();
                formData.append('file', this.file);
                formData.append('el_id', this.$route.params.el_id);
                formData.append('bu_id', this.$route.params.bu_id);
                formData.append('h_id', this.$route.params.h_id);
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-Access-Token': api_param.XAccessToken
                    }
                };
                this.$http.post(api_param.apiurl + 'building/import', formData, config).then(function (response) {
                    // 这里是处理正确的回调
                    this.$Message.success({content: response.data.message, duration: 10});
                    this.getTower();
                }, function (response) {
                    // 这里是处理错误的回调
                    //console.log(response)
                    this.$Message.warning('导入失败');
                });
            }, edit(vzu) {
                this.editdata.bu_acreage = vzu.bu_acreage;
                this.editdata.t_id = vzu.t_id;
                this.editdata.bu_floor = vzu.bu_floor;
                this.editdata.bu_h_number = vzu.bu_h_number;
                this.editdata.bu_total = vzu.bu_total;
                this.editdata.bu_market = vzu.bu_market;
                this.houseNew = true;
                console.log(vzu);
            }, modalOk() {
                this.$http.post(api_param.apiurl + 'building/updatatower',
                    {
                        't_id': this.editdata.t_id,
                        'bu_market': this.editdata.bu_market,
                    },
                    {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    this.$Message.success('保存成功');
                    this.modalCancel();
                    this.getTower();
                }, function (response) {
                    // 这里是处理错误的回调
                    //console.log(response)
                    this.$Message.warning('保存失败');
                });

            }, modalCancel() {
                this.editdata.bu_acreage = '';
                this.editdata.bu_floor = '';
                this.editdata.bu_h_number = '';
                this.editdata.bu_total = '';
                this.editdata.bu_market = '';
                this.houseNew = false;
            }
        }, watch: {
            '$route.params.el_id'(to, from) {
                this.getTower();
            }
        }
    };
</script>

<style scoped>
    @import "fanghao.less";
</style>

