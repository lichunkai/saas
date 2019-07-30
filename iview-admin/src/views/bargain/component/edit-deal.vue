<style>
</style>
<template>
    <Modal title="买卖成交" v-model="rDeal" :buyDealtitle="buyDealtitle" :closable="false" :mask-closable="false"
           width="960" class="rdeal">
        <Form ref="formValidate" :rules="ruleValidate" :label-width="80">
            <Row>
                <Col :lg="8" :md="8">
                <FormItem label="成交日期" prop="">
                    <DatePicker type="date" placeholder=""></DatePicker>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="成交人" prop="">
                    <Cascader :data="data" ></Cascader>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="协议编号" prop="">
                    <DatePicker type="date" placeholder=""></DatePicker>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <Row>
                    <Col :lg="16" :md="16">
                    <FormItem label="成交价格" prop="">
                        <Input v-model="value" placeholder=""><span slot="append">元</span></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="7" :md="7" offset="1" style="margin-top: 5px">
                    <CheckboxGroup>
                        <Checkbox label="有贷款"></Checkbox>
                    </CheckboxGroup>
                    </Col>
                </Row>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="应收佣金" prop="">
                    <Input v-model="value" placeholder=""><span slot="append">元</span></Input>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="土地证号" prop="">
                    <Input v-model="value" placeholder=""></Input>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="产权证号" prop="">
                    <Input v-model="value" placeholder=""></Input>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="权证人" prop="">
                    <Cascader :data="data" ></Cascader>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="补充条款" prop="">
                    <Input type="textarea" :rows="2" placeholder=""></Input>
                </FormItem>
                </Col>
                <Col :lg="24" :md="24">
                <div style="width: 100%;height: 1px;background-color: #e5e5e5;margin-bottom: 20px"></div>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="甲方编号" prop="">
                    <Input v-model="value" placeholder=""></Input>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="业主电话" prop="">
                    <Input v-model="value" placeholder=""></Input>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <Row>
                    <Col :lg="16" :md="16">
                    <FormItem label="甲方姓名" prop="">
                        <Input v-model="value" placeholder=""></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="7" :md="7" offset="1">
                    <Select v-model="nameModel" :transfer="true">
                        <Option v-for="item in nameList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                    </Select>
                    </Col>
                </Row>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="证件号码" prop="">
                    <Input v-model="value" placeholder=""></Input>
                </FormItem>
                </Col>
                <Col :lg="16" :md="16">
                <FormItem label="联系地址" prop="">
                    <Input v-model="value" placeholder=""></Input>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <Row>
                    <Col :lg="16" :md="16">
                    <FormItem label="乙方编号" prop="">
                        <Cascader :data="data" ></Cascader>
                    </FormItem>
                    </Col>
                    <Col :lg="7" :md="7" offset="1">
                    <Button type="ghost">选择客源</Button>
                    </Col>
                </Row>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="客户电话" prop="">
                    <Input v-model="value" placeholder=""></Input>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <Row>
                    <Col :lg="16" :md="16">
                    <FormItem label="乙方姓名" prop="">
                        <Input v-model="value" placeholder=""></Input>
                    </FormItem>
                    </Col>
                    <Col :lg="7" :md="7" offset="1">
                    <Select v-model="nameModel" :transfer="true">
                        <Option v-for="item in nameList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                    </Select>
                    </Col>
                </Row>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="证件号码" prop="">
                    <Input v-model="value" placeholder=""></Input>
                </FormItem>
                </Col>
                <Col :lg="16" :md="16">
                <FormItem label="联系地址" prop="">
                    <Input v-model="value" placeholder=""></Input>
                </FormItem>
                </Col>
                <Col :lg="24" :md="24">
                <div style="width: 100%;height: 1px;background-color: #e5e5e5;margin-bottom: 20px"></div>
                </Col>
                <Col :lg="24" :md=24>
                <Row style="text-align: center">
                    <Col :lg="4" :md="4">
                    费用类型</Col>
                    <Col :lg="4" :md="4">
                    费用项目</Col>
                    <Col :lg="3" :md="3">
                    交费人</Col>
                    <Col :lg="3" :md="3">
                    金额</Col>
                    <Col :lg="4" :md="4">
                    预计缴费时间</Col>
                    <Col :lg="3" :md="3">
                    备注</Col>
                    <Col :lg="3" :md="3">
                    操作</Col>
                </Row>
                </Col>
                <Col :lg="24" :md=24>
                <Row :gutter="10">
                    <Col :lg="4" :md="4">
                    <Select v-model="nameModel" :transfer="true">
                        <Option v-for="item in nameList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                    </Select>
                    </Col>
                    <Col :lg="4" :md="4">
                    <Select v-model="nameModel" :transfer="true">
                        <Option v-for="item in nameList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                    </Select>
                    </Col>
                    <Col :lg="3" :md="3">
                    <Select v-model="nameModel" :transfer="true">
                        <Option v-for="item in nameList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                    </Select>
                    </Col>
                    <Col :lg="3" :md="3">
                    <Select v-model="nameModel" :transfer="true">
                        <Option v-for="item in nameList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                    </Select>
                    </Col>
                    <Col :lg="4" :md="4">
                    <DatePicker type="date" placeholder="" :transfer="true"></DatePicker>
                    </Col>
                    <Col :lg="3" :md="3">
                    <Input v-model="value5" type="textarea" :rows="1" placeholder=""></Input>
                    </Col>
                    <Col :lg="3" :md="3">
                    <Button type="primary" long>清空</Button>
                    </Col>
                </Row>
                </Col>
            </Row>
        </Form>
        <div slot="footer">
            <Button type="text" size="large" @click="dealCancel">取消</Button>
            <Button type="primary" size="large" @click="dealOk">确定</Button>
        </div>
    </Modal>
</template>
<script>
    export default {
        name: 'editDeal',
        props: ['rDeal', 'buyDealtitle'],
        data() {
            return {
                nameList: [
                    {
                        value: 'xiansheng',
                        label: '先生'
                    },
                    {
                        value: 'nvshi',
                        label: '女士'
                    },],
                nameModel: '',
            };
        },
        methods: {
            //修改信息
            modalOk() {
                this.editRoom = false;
                this.$emit('resetModal');
            },
            modalCancel() {
                this.editRoom = false;
                this.$emit('resetModal');
            },
            //买卖成交
            dealOk() {
                this.rDeal = false;
                this.$emit('resetModal');
            },
            dealCancel() {
                this.rDeal = false;
                this.$emit('resetModal');
            }
        }

    };
</script>