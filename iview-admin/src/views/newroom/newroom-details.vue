<template>
    <Card>
        <p slot="title">{{house.name}}</p>
        <Row class="newroomdetails">

            <Col :lg="24" :md="24">
            <Icon type="ios-bookmarks"></Icon>
            <strong>项目图片</strong></Col>
            <Col :lg="23" :md="23" style="margin-left: 20px">
            <Row>
                <Col :lg="6" :md="6" style="margin-left: 10px"  v-for="v in house.descimgs ">
                        <img :src="v" >
                </Col>
            </Row>
            </Col>
            <Col :lg="24" :md="24">
            <Icon type="ios-bookmarks"></Icon>
            <strong>基本信息</strong></Col>
            <Col :lg="23" :md="23" style="margin-left: 20px">
            项目均价：{{house.format_price}}元/㎡</Col>
            <Col :lg="23" :md="23" style="margin-left: 20px">
            项目区域：{{house.province}}-{{house.city}}-{{house.district}}</Col>
            <Col :lg="23" :md="23" style="margin-left: 20px">
            详细地址：{{house.address}}</Col>
            <Col :lg="23" :md="23" style="margin-left: 20px">
            开盘时间：{{house.opentime}}</Col>

            <Col :lg="24" :md="24">
            <Icon type="ios-bookmarks"></Icon>
            <strong>优惠方案</strong></Col>
            <Col :lg="23" :md="23" style="margin-left: 20px">
            {{house.hotmsg}}</Col>

            <Col :lg="24" :md="24">
            <Icon type="ios-bookmarks"></Icon>
            <strong>报备规则</strong></Col>
            <Col :lg="23" :md="23" style="margin-left: 20px">
                <dl v-html="house.bb_rules">
                 {{house.bb_rules}}
              </dl>
            </Col>

            <Col :lg="24" :md="24">
            <Icon type="ios-bookmarks"></Icon>
            <strong>项目动态</strong></Col>
            <Col :lg="23" :md="23" style="margin-left: 20px">
            <dl v-html="house.house_trend">
                {{house.house_trend}}
            </dl></Col>

            <Col :lg="24" :md="24">
            <Icon type="ios-bookmarks"></Icon>
            <strong>楼盘详情</strong></Col>
            <Col :lg="23" :md="23" style="margin-left: 20px">
            <dl v-html="house.format_desc">
                {{house.format_desc}}
            </dl></Col>

            <Col :lg="24" :md="24">
            <Icon type="ios-bookmarks"></Icon>
            <strong>楼盘卖点</strong></Col>
            <Col :lg="23" :md="23" style="margin-left: 20px" v-for="v in house.params ">
            {{v.key}}：{{v.value}}</Col>
            <Col :lg="24" :md="24">
            <Icon type="ios-bookmarks"></Icon>
            <strong>户型图</strong></Col>
            <Col :lg="23" :md="23" style="margin-left: 20px">
            <Row>
                <Col :lg="6" :md="6" style="margin-left: 10px"  v-for="v in house.layouts ">
                <img :src="v.img">
                <p>{{v.name}} {{v.area}}㎡</p>
                </Col>

            </Row>
            </Col>
        </Row>
    </Card>
</template>

<script>
    export default {
        name: 'newroomDetails',
        data () {
            return {
                house: []
            };
        }, created () {
            this.getDetail();
        }, methods: {
            getDetail () {
                this.$http.get(api_param.newHouseUrl + '/web/index.php?c=site&a=entry&act=detail&do=house_api&m=superman_house', {
                    params: {
                        token: this.$md5(api_param.newHouseKey + api_param.newHouseTime),
                        tokenTime: api_param.newHouseTime,
                        id: this.$route.params.id,
                    }, headers: {}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    this.house = response.body.data;
                    this.house['bb_rules']= this.htmlDecodeByRegExp(this.house.bb_rules);
                    this.house['house_trend']= this.htmlDecodeByRegExp(this.house.house_trend);
                    this.house['format_desc']= this.htmlDecodeByRegExp(this.house.format_desc);
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                });
            },
            htmlDecodeByRegExp (str) {
                var s = '';
                if (str.length == 0) return '';
                s = str.replace(/&amp;/g, '&');
                s = s.replace(/&lt;/g, '<');
                s = s.replace(/&gt;/g, '>');
                s = s.replace(/&nbsp;/g, ' ');
                s = s.replace(/&#39;/g, '\'');
                s = s.replace(/&quot;/g, '\"');
                return s;
            }
        }, watch: {
            '$route.params.id'(to, from) {
                if (this.$route.params.id) {
                    this.getDetail();
                }
            }
        }
    };
</script>

<style scoped>
    .newroomdetails {
        line-height: 32px;
    }

    .newroomdetails i {
        margin-right: 10px;
        color: rgb(45, 140, 240);
    }

    .newroomdetails img {
        display: block;
        margin: 0 auto;
        width: 100%;
        height: 100%;
    }
</style>
