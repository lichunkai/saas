<style lang="less">
    @import "roomdetails.less";
</style>

<template>
    <Row>
        <Col :lg="18" :md="18" class="editBorder alignCenter">
        <Row>
            <Col :lg="24" :md="24">
            <Row type="flex" justify="space-between">
                <Col :lg="3" :md="3">
                <Button type="primary">上传图片</Button>
                </Col>
                <Col :lg="4" :md="4">
                <Button type="primary">图片下载</Button>
                <Button type="primary">删除</Button>
                </Col>
            </Row>
            </Col>
            <Col :lg="24" :md="24" class="imageBox">
            <Row :gutter="40">
                <Col :lg="4" :md="4">
                <Upload multiple type="drag">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    </div>
                </Upload>
                <p>封面</p>
                </Col>
                <Col :lg="4" :md="4">
                <Upload multiple type="drag">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    </div>
                </Upload>
                <p>委托合同</p>
                </Col>
                <Col :lg="4" :md="4">
                <Upload multiple type="drag">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    </div>
                </Upload>
                <p>独家合同</p>
                </Col>
                <Col :lg="4" :md="4">
                <Upload multiple type="drag">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    </div>
                </Upload>
                <p>产证图片</p>
                </Col>
                <Col :lg="4" :md="4">
                <Upload multiple type="drag">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    </div>
                </Upload>
                <p>身份证正面</p>
                </Col>
                <Col :lg="4" :md="4">
                <Upload multiple type="drag">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    </div>
                </Upload>
                <p>身份证反面</p>
                </Col>
                <Col :lg="24" :md="24">
                <br><br>
                <Button type="primary" style="width: 200px">保存</Button>
                <br>
                <br>
                </Col>
            </Row>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
    export default {
        name: 'editRelevant',
        components: {},
        data () {
            return {};
        },
    };
</script>
