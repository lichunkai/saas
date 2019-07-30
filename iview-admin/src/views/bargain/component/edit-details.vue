<style>

</style>

<template>
    <Modal v-model="editRoom" ok-text="确认" cancel-text="取消" :closable="false" title="修改信息" width="960">
        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
            <Row>
                <Col :lg="8" :md="8">
                <FormItem label="小区" prop="name">
                    <Row>
                        <Col :lg="16" :md="16">
                        <Select placeholder="" :transfer="true">
                            <Option value="zhonghai">中海瓜园</Option>
                            <Option value="xinghai">鑫海国际</Option>
                            <Option value="xingshu">星墅湾</Option>
                        </Select>
                        </Col>
                        <Col :lg="8" :md="8">
                        <div class="choiseDong"><a>选择座栋</a></div>
                        </Col>
                    </Row>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="门牌号" prop="">
                    <Row>
                        <Col :lg="8" :md="8">
                        <Input v-model="value" placeholder="座栋"></Input>
                        </Col>
                        <Col :lg="8" :md="8">
                        <Input v-model="value" placeholder="单元"></Input>
                        </Col>
                        <Col :lg="8" :md="8">
                        <Input v-model="value" placeholder="房号"></Input>
                        </Col>
                    </Row>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="姓名" prop="">
                    <Row>
                        <Col :lg="12" :md="12">
                        <Input v-model="value" placeholder=""></Input>
                        </Col>
                        <Col :lg="12" :md="12">
                        <Select placeholder="" :transfer="true">
                            <Option value="xiansheng">先生</Option>
                            <Option value="nvshi">女士</Option>
                            <Option value="gongsi">公司</Option>
                        </Select>
                        </Col>
                    </Row>
                </FormItem>
                </Col>
                <Col :lg="24" :md="24">
                <FormItem label="电话" prop="">
                    <Row>
                        <Col :lg="3" :md="3">
                        <Input v-model="value" placeholder=""></Input>
                        </Col>
                        <Col :lg="3" :md="3">
                        <Select placeholder="" :transfer="true">
                            <Option value="xiansheng">先生</Option>
                            <Option value="nvshi">女士</Option>
                            <Option value="gongsi">公司</Option>
                        </Select>
                        </Col>
                        <Col :lg="12" :md="12">
                        <span style="margin-left: 10px;color: #ff0000">* 固话与区号间请用"-"分隔，例:0521-88888888</span>
                        </Col>
                    </Row>
                </FormItem>
                </Col>
                <Col :lg="24" :md="24">
                <div class="line"></div>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="房源标题" prop="">
                    <Input v-model="value" placeholder=""></Input>
                </FormItem>
                </Col>
                <Col :lg="16" :md="16">
                <FormItem label="推荐标签" prop="">
                    <Select v-model="model10" multiple :transfer="true">
                        <Option v-for="item in cityList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="售价" prop="">
                    <Input v-model="value" placeholder=""><span slot="append">万元</span></Input>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="使用面积" prop="">
                    <Input v-model="value" placeholder=""><span slot="append">平方</span></Input>
                </FormItem>
                </Col>
                <Col :lg="12" :md="12">
                <FormItem label="户型" prop="">
                    <Row>
                        <Col :lg="4" :md="4">
                        <Input v-model="value" placeholder="室"></Input>
                        </Col>
                        <Col :lg="4" :md="4">
                        <Input v-model="value" placeholder="厅"></Input>
                        </Col>
                        <Col :lg="4" :md="4">
                        <Input v-model="value" placeholder="卫"></Input>
                        </Col>
                        <Col :lg="6" :md="6">
                        <Input v-model="value" placeholder="厨"></Input>
                        </Col>
                        <Col :lg="6" :md="6">
                        <Input v-model="value" placeholder="阳台"></Input>
                        </Col>
                    </Row>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="使用面积" prop="">
                    <Input v-model="value" placeholder=""><span slot="append">平方</span></Input>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="楼层" prop="">
                    <Row>
                        <Col :lg="11" :md="11">
                        <Input v-model="value" placeholder=""><span slot="append">楼</span></Input>
                        </Col>
                        <Col :lg="2" :md="2">
                        <span style="font-size: 20px">/</span>
                        </Col>
                        <Col :lg="11" :md="11">
                        <Input v-model="value" placeholder=""><span slot="append">楼</span></Input>
                        </Col>
                    </Row>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="朝向" prop="">
                    <Select placeholder="" :transfer="true">
                        <Option value="zhonghai">中海瓜园</Option>
                        <Option value="xinghai">鑫海国际</Option>
                        <Option value="xingshu">星墅湾</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="楼层" prop="">
                    <Row>
                        <Col :lg="11" :md="11">
                        <Input v-model="value" placeholder=""><span slot="append">梯</span></Input>
                        </Col>
                        <Col :lg="11" :md="11">
                        <Input v-model="value" placeholder=""><span slot="append">户</span></Input>
                        </Col>
                    </Row>
                </FormItem>
                </Col>
                <Col :lg="16" :md="16">
                <FormItem label="推荐标签" prop="">
                    <Select v-model="model10" multiple :transfer="true">
                        <Option v-for="item in cityList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="8" :md="8">
                <FormItem label="装修" prop="">
                    <Select placeholder="" :transfer="true">
                        <Option value="zhonghai">精装修</Option>
                        <Option value="xinghai">简装</Option>
                        <Option value="xingshu">毛坯</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="现状" prop="">
                    <Select placeholder="" :transfer="true">
                        <Option value="zhonghai">精装修</Option>
                        <Option value="xinghai">简装</Option>
                        <Option value="xingshu">毛坯</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="房屋类型" prop="" :transfer="true">
                    <Select placeholder="" :transfer="true">
                        <Option value="zhonghai">精装修</Option>
                        <Option value="xinghai">简装</Option>
                        <Option value="xingshu">毛坯</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="建筑结构" prop="" :transfer="true">
                    <Select placeholder="" :transfer="true">
                        <Option value="zhonghai">精装修</Option>
                        <Option value="xinghai">简装</Option>
                        <Option value="xingshu">毛坯</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="建筑年代" prop="" :transfer="true">
                    <Select placeholder="" :transfer="true">
                        <Option value="zhonghai">精装修</Option>
                        <Option value="xinghai">简装</Option>
                        <Option value="xingshu">毛坯</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="产权性质" prop="" :transfer="true">
                    <Select placeholder="">
                        <Option value="zhonghai">精装修</Option>
                        <Option value="xinghai">简装</Option>
                        <Option value="xingshu">毛坯</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="产证日期" prop="" :transfer="true">
                    <Select placeholder="">
                        <Option value="zhonghai">精装修</Option>
                        <Option value="xinghai">简装</Option>
                        <Option value="xingshu">毛坯</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="产证年限" prop="" :transfer="true">
                    <Select placeholder="">
                        <Option value="zhonghai">精装修</Option>
                        <Option value="xinghai">简装</Option>
                        <Option value="xingshu">毛坯</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="房源税费" prop="" :transfer="true">
                    <Select placeholder="">
                        <Option value="zhonghai">精装修</Option>
                        <Option value="xinghai">简装</Option>
                        <Option value="xingshu">毛坯</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="24" :md="24">
                <FormItem label="" prop="">
                    <CheckboxGroup v-model="fruit" :transfer="true">
                        <Checkbox label="满两年"></Checkbox>
                        <Checkbox label="唯一住房"></Checkbox>
                        <Checkbox label="急切"></Checkbox>
                        <Checkbox label="全款"></Checkbox>
                    </CheckboxGroup>
                </FormItem>
                </Col>
                <Col :lg="24" :md="24">
                <div class="line"></div>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="看房方式" prop="" :transfer="true">
                    <Select placeholder="">
                        <Option value="zhonghai">精装修</Option>
                        <Option value="xinghai">简装</Option>
                        <Option value="xingshu">毛坯</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="来源" prop="" :transfer="true">
                    <Select placeholder="">
                        <Option value="zhonghai">精装修</Option>
                        <Option value="xinghai">简装</Option>
                        <Option value="xingshu">毛坯</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="委托编号" prop="" :transfer="true">
                    <Select placeholder="">
                        <Option value="zhonghai">精装修</Option>
                        <Option value="xinghai">简装</Option>
                        <Option value="xingshu">毛坯</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="付款方式" prop="" :transfer="true">
                    <Select placeholder="">
                        <Option value="quankuan">全款</Option>
                        <Option value="shangdai">商贷</Option>
                        <Option value="daikuan">公积金贷款</Option>
                    </Select>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="钥匙店" prop="">
                    <Cascader :data="data" ></Cascader>
                </FormItem>
                </Col>
                <Col :lg="6" :md="6">
                <FormItem label="净价" prop="">
                    <Input v-model="value" placeholder=""><span slot="append">万元</span></Input>
                </FormItem>
                </Col>
                <Col :lg="24" :md="24">
                <div class="line"></div>
                </Col>
                <Col :lg="24" :md="24">
                <FormItem label="备注" prop="">
                    <Input type="textarea" placeholder=""></Input>
                </FormItem>
                </Col>
            </Row>
        </Form>
        <div slot="footer">
            <Button type="text" size="large" @click="modalCancel">取消</Button>
            <Button type="primary" size="large" @click="modalOk">确定</Button>
        </div>
    </Modal>
</template>
<script>
    export default {
        name: 'editDetails',
        props: ['editRoom', 'usermodaltitle'],
        data () {
            return {
                formValidate: {
                    name: '',
                    mail: '',
                    city: '',
                },
                cityList: [
                    {
                        value: 'New York',
                        label: 'New York'
                    },
                    {
                        value: 'London',
                        label: 'London'
                    },
                    {
                        value: 'Sydney',
                        label: 'Sydney'
                    },],
                model10: [],
                fruit: [],
            };
        },


        methods: {
            modalOk() {
                this.editRoom = false;
                this.$emit('resetModal');
            },
            modalCancel() {
                this.editRoom = false;
                this.$emit('resetModal');
            }
        }

    };
</script>
