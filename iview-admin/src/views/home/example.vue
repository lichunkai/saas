<template>
    <div ref="dom"></div>
</template>

<script>
    import echarts from 'echarts'
    import { on, off } from '@/libs/tools'
    export default {
        name: 'serviceRequests',
        props: {
            seriesdata: Object
        },
        data () {
            return {
                dom: null
            }
        },
        methods: {
            resize () {
                this.dom.resize()
            },
            genxinshuju(){
                this.$nextTick(() => {
                const option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'cross',
                            label: {
                                backgroundColor: '#6a7985'
                            }
                        }
                    },
                    grid: {
                        top: '15%',
                        left: '1.2%',
                        right: '1%',
                        bottom: '3%',
                        containLabel: true
                    },
                    legend: {
                        data: this.seriesdata.legend
                    },
                    xAxis: [
                        {
                            type: 'category',
                            boundaryGap: false,
                            data: this.seriesdata.days
                        }
                    ],
                    yAxis: [
                        {
                            type: 'value',
                            minInterval: 1
                        }
                    ],
                    series: [
                        {
                            name: '房源总数',
                            type: 'line',
                            stack: '总量',
                            data: this.seriesdata.fyseries
                        },
                        {
                            name: '买卖客源总数',
                            type: 'line',
                            stack: '总量',
                            data: this.seriesdata.kyseries
                        },
                        {
                            name: '买卖成交总数',
                            type: 'line',
                            stack: '总量',
                            data: this.seriesdata.cjseries
                        },
                        {
                            name: '收钥匙数',
                            type: 'line',
                            stack: '总量',
                            data: this.seriesdata.keyseries
                        },
                        {
                            name: '独家委托总数',
                            type: 'line',
                            stack: '总量',
                            data: this.seriesdata.dujiaseries
                        },
                        {
                            name: '房源带看数',
                            type: 'line',
                            stack: '总量',
                            data: this.seriesdata.fydkseries
                        },
                        {
                            name: '客源带看数',
                            type: 'line',
                            stack: '总量',
                            data: this.seriesdata.kydkseries
                        }
                    ]
                }

                    this.dom = echarts.init(this.$refs.dom)
                    this.dom.setOption(option)
                    on(window, 'resize', this.resize)
                })
            }
        },
        mounted () {},
        watch: {
            seriesdata: {
                handler() {
                    this.genxinshuju();
                },
                // 代表在wacth里声明了firstName这个方法之后立即先去执行handler方法
                immediate: true,
                deep:true
            }
        },
        beforeDestroy () {
            off(window, 'resize', this.resize)
        }
    }
</script>
