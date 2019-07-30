<style scoped>
    @import "roomdetails.less";
</style>
<template>
    <Row>
        <Col :lg="18" :md="18" class="editBorder">
        <Row :gutter="10" style="margin-top: 10px">
            <Col :lg="24" :md="24">
            <Table :columns="columns1" :data="followupList" border script height="560"></Table>
            </Col>
        </Row>
        </Col>
    </Row>
</template>

<script>
    export default {
	    name: "editGenjin",
	    props: ['editRoom', 'settings', 'followupList'],
	    data() {
		    return {
			    columns1: [
				    {
					    title: '跟进时间',
					    key: 'ctime',
					    align: 'center'
				    },
				    {
					    title: '跟进方式',
					    key: 'hf_type',
					    align: 'center'
				    },
				    {
					    title: '跟进人',
					    key: 'u_name',
					    align: 'center'
				    },
				    {
					    title: '所在部门',
					    key: 'd_name',
					    align: 'center'
				    },
				    {
					    title: '跟进内容',
					    key: 'hf_content',
					    align: 'center',
					    width:220
				    },
				    // {
				    //     title: '操作',
				    //     key: 'action',
				    //     align: 'center',
				    //     render: (h, params) => {
				    //         return h('div', [
				    //             h('Button', {
				    //                 props: {
				    //                     type: 'primary',
				    //                     size: 'small'
				    //                 },
				    //                 style: {
				    //                     marginRight: '5px'
				    //                 },
				    //                 on: {
				    //                     click: () => {
				    //                        this.$http.post(api_param.apiurl + 'house/delfollowup',
				    //                            {'hf_id':params.row.hf_id},
				    //                            {emulateJSON: true, headers: {"X-Access-Token": api_param.XAccessToken}}
				    //                        ).then(function (response) {
				    //                            // 这里是处理正确的回调
				    //                            if (response.data.code == 200) {
				    //                                this.$emit('getFollowup');
				    //                                this.$Message.success('删除成功');
				    //
				    //                            } else if (response.data.code == 401) {
				    //                                this.$store.commit('logout', this);
				    //                                this.$store.commit('clearOpenedSubmenu');
				    //                                this.$router.push({
				    //                                    name: 'login'
				    //                                });
				    //                            } else {
				    //                                this.$Message.warning(response.data.message);
				    //                            }
				    //                        }, function (response) {
				    //                            // 这里是处理错误的回调
				    //                            this.$Message.error('网络异常');
				    //                        })
				    //
				    //                     }
				    //                 }
				    //             }, '删除'),
				    //         ]);
				    //     }
				    // }
			    ],

		    }
	    },
	    methods: {},
    }
</script>
