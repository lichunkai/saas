webpackJsonp([36],{277:function(a,t,e){"use strict";function i(a){d||e(731)}Object.defineProperty(t,"__esModule",{value:!0});var s=e(512),o=e.n(s);for(var l in s)"default"!==l&&function(a){e.d(t,a,function(){return s[a]})}(l);var n=e(733),r=e.n(n),d=!1,u=e(0),c=i,m=u(o.a,r.a,!1,c,null,null);m.options.__file="src/views/resource/xuequ.vue",t.default=m.exports},512:function(a,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i="",s="";t.default={name:"xuequ",data:function(){var a=this;return{DtsData:[],guanjianzi:"",totalnum1:0,totalnum2:0,currentpage1:1,currentpage2:1,pageSize:10,village:"",addSchoolModal:!1,amendSchoolModal:!1,addxiaoquModal:!1,setxiaoquModal:!1,addxiaoquformValidate:{xiaoqu:"",pianqu:[],beizhu:""},addxiaoquruleValidate:{},setxiaoquformValidate:{xiaoqu:"",pianqu:[],beizhu:""},setxiaoquruleValidate:{},formValidate:{name:"",address:""},formValidateAmend:{s_name:"",s_address:""},ruleValidateAmend:{},ruleValidate:{name:[{required:!0,message:"请输入学校名称",trigger:"blur"}],address:[{required:!0,message:"请输入学校地址",trigger:"blur"}]},school:[{title:"学校名称",key:"schoolName",align:"center"},{title:"学校地址",key:"schoolAddree",align:"center"},{title:"操作",key:"caozuo",align:"center",width:258,render:function(t,e){return t("div",[t("Button",{props:{type:"text",size:"small"},style:{marginRight:"5px",color:"#1877a8"},on:{click:function(){a.addxiaoqu(e)}}},"新增周边小区"),t("Button",{props:{type:"text",size:"small"},style:{marginRight:"5px",color:"#1877a8"},on:{click:function(){a.amend(e)}}},"修改学校名称"),t("Button",{props:{type:"text",size:"small"},style:{marginRight:"5px",color:"#1877a8"},on:{click:function(){a.removeSchool(e)}}},"删除")])}}],housing:[{title:"小区名称",key:"leixing",align:"center"},{title:"备注",key:"beizhu",align:"center"},{title:"操作",key:"caozuo",align:"center",render:function(t,e){return t("div",[t("Button",{props:{type:"text",size:"small"},style:{marginRight:"5px",color:"#1877a8"},on:{click:function(){a.xiugaichild(e)}}},"修改"),t("Button",{props:{type:"text",size:"small"},style:{marginRight:"5px",color:"#1877a8"},on:{click:function(){a.removechild(e)}}},"删除")])}}],schoolData:[],housingData:[]}},created:function(){this.getxuequliset(),this.getxuequchildlist()},methods:{rowClick1:function(a,t){i=t,this.getxuequchildlist()},rowClick2:function(a,t){s=t,this.setxiaoquformValidate.pianqu=[a.area_id,a.dts_id],this.setxiaoquformValidate.xiaoqu=a.rn_id},addSchool:function(){this.addSchoolModal=!0},addOk:function(){var a=this;this.$refs.formValidate.validate(function(t){t&&a.$http.post(api_param.apiurl+"/school/add",{s_name:a.formValidate.name,s_address:a.formValidate.address},{emulateJSON:!0,headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(a){console.log(a),200==a.data.code?(this.$refs.formValidate.resetFields(),this.addSchoolModal=!1,this.$Message.success(a.data.message),this.getxuequliset()):401==a.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(a.data.message)},function(a){this.$Message.warning("更新失败")})})},addCancel:function(){this.$refs.formValidate.resetFields(),this.addSchoolModal=!1},changePage1:function(a){this.currentpage1=a,this.getxuequliset()},getxuequliset:function(){this.$http.get(api_param.apiurl+"/school/index",{params:{page:this.currentpage1,kw:this.guanjianzi},headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(a){if(200==a.data.code){this.totalnum1=parseInt(a.body.data.count),this.schoolData=a.body.data.list,this.DtsData=a.body.data.dts;for(var t=0;t<this.schoolData.length;t++)this.schoolData[t].schoolName=this.schoolData[t].s_name,this.schoolData[t].schoolAddree=this.schoolData[t].s_address}else 401==a.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(a.data.message)},function(a){console.log(a),this.$Message.warning("更新失败")})},amend:function(a){this.amendSchoolModal=!0,this.formValidateAmend.s_name=a.row.s_name,this.formValidateAmend.s_address=a.row.s_address},amendOk:function(){var a=this;this.$refs.formValidateAmend.validate(function(t){t&&a.$http.post(api_param.apiurl+"/school/edit",{s_name:a.formValidateAmend.s_name,s_address:a.formValidateAmend.s_address,s_id:a.schoolData[i].s_id},{emulateJSON:!0,headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(a){console.log(a),200==a.data.code?(this.$refs.formValidateAmend.resetFields(),this.amendSchoolModal=!1,this.$Message.success(a.data.message),this.getxuequliset()):401==a.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(a.data.message)},function(a){this.$Message.warning("更新失败")})})},amendCancel:function(){this.$refs.formValidateAmend.resetFields(),this.amendSchoolModal=!1},removeSchool:function(a){var t=this;this.$Modal.confirm({title:"删除部门",content:"确定要删除吗",okText:"确定",cancelText:"取消",onOk:function(){t.$http.post(api_param.apiurl+"/school/del",{s_id:a.row.s_id},{emulateJSON:!0,headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(a){200==a.data.code?(this.$Message.success(a.data.message),this.getxuequliset()):401==a.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(a.data.message)},function(a){this.$Message.warning("删除失败")})}})},doSearch:function(){this.currentpage1=1,this.getxuequliset(),this.housingData=""},clearSearch:function(){this.guanjianzi="",this.getxuequliset(),this.housingData=""},addxiaoqu:function(a){this.addxiaoquModal=!0,this.addxiaoquformValidate.xiaoqu="",this.addxiaoquformValidate.beizhu="",console.log(a)},addxiaoquOk:function(){var a=this;this.addxiaoquformValidate.xiaoqu?this.$refs.addxiaoquformValidate.validate(function(t){t&&a.$http.post(api_param.apiurl+"/school_district/add",{rn_id:a.addxiaoquformValidate.xiaoqu,s_id:a.schoolData[i].s_id,beizhu:a.addxiaoquformValidate.beizhu},{emulateJSON:!0,headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(a){console.log(a),200==a.data.code?(this.$refs.addxiaoquformValidate.resetFields(),this.addxiaoquModal=!1,this.$Message.success(a.data.message),this.getxuequchildlist()):401==a.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(a.data.message)},function(a){this.$Message.warning("更新失败")})}):this.$Message.warning("请选择小区")},addxiaoquCancel:function(){this.$refs.addxiaoquformValidate.resetFields(),this.addxiaoquModal=!1},changePage2:function(a){this.currentpage2=a,this.getxuequchildlist()},getxuequchildlist:function(){this.$http.get(api_param.apiurl+"/school_district/index",{params:{page:this.currentpage2,s_id:this.schoolData[i].s_id},headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(a){if(200==a.data.code){this.totalnum2=parseInt(a.body.data.count),this.housingData=a.body.data.list,console.log(this.housingData);for(var t=0;t<this.housingData.length;t++)this.housingData[t].leixing=this.housingData[t].village_name,this.housingData[t].beizhu=this.housingData[t].beizhu}else 401==a.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(a.data.message)},function(a){console.log(a),this.$Message.warning("更新失败")})},xiaoquDataChange:function(a,t){var e=t[1].value;this.$http.get(api_param.apiurl+"village/getvillage",{params:{dts_id:e},headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(a){200==a.data.code?(this.addxiaoquformValidate.xiaoqu="",this.formValidate.village="",this.village=a.data.data.list):401==a.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(a.data.message)},function(a){this.$Message.error("你的网络开小差了^—^")})},xiugaichild:function(a){this.setxiaoquModal=!0,this.getDts(a.row.dts_id),this.setxiaoquformValidate.beizhu=a.row.beizhu,this.setxiaoquformValidate.xiaoqu=a.row.rn_id},setxiaoquOk:function(){var a=this;this.setxiaoquformValidate.xiaoqu?this.$refs.setxiaoquformValidate.validate(function(t){t&&a.$http.post(api_param.apiurl+"/school_district/edit",{rn_id:a.setxiaoquformValidate.xiaoqu,s_id:a.schoolData[i].s_id,sd_id:a.housingData[s].sd_id,beizhu:a.setxiaoquformValidate.beizhu},{emulateJSON:!0,headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(a){200==a.data.code?(this.$refs.addxiaoquformValidate.resetFields(),this.setxiaoquModal=!1,this.$Message.success(a.data.message),this.getxuequchildlist()):401==a.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(a.data.message)},function(a){this.$Message.warning("更新失败")})}):this.$Message.warning("请选择小区")},setxiaoquCancel:function(){this.$refs.setxiaoquformValidate.resetFields(),this.setxiaoquModal=!1},removechild:function(a){var t=this;console.log(a),this.$Modal.confirm({title:"删除部门",content:"确定要删除吗",okText:"确定",cancelText:"取消",onOk:function(){t.$http.post(api_param.apiurl+"/school_district/del",{sd_id:a.row.sd_id},{emulateJSON:!0,headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(a){200==a.data.code?(this.$Message.success(a.data.message),this.getxuequchildlist()):401==a.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(a.data.message)},function(a){this.$Message.warning("删除失败")})}})},changeDts:function(a,t){var e=t[1].value;this.formValidate.dts_id=t[1].value,this.formValidate.dts_name=t[1].label,this.getDts(e)},getDts:function(a){this.$http.get(api_param.apiurl+"village/getvillage",{params:{dts_id:a},headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(a){200==a.data.code?this.village=a.data.data.list:401==a.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(a.data.message)},function(a){this.$Message.error("你的网络开小差了^—^")})}},computed:{}}},731:function(a,t,e){var i=e(732);"string"==typeof i&&(i=[[a.i,i,""]]),i.locals&&(a.exports=i.locals);e(8)("32974795",i,!1,{})},732:function(a,t,e){t=a.exports=e(7)(!1),t.push([a.i,"",""])},733:function(a,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("Row",[e("Col",{attrs:{lg:24,md:24}},[e("Card",[e("Row",{attrs:{gutter:10}},[e("Col",{attrs:{lg:4,md:4}},[e("Input",{attrs:{placeholder:"学校名称、学校地址"},model:{value:a.guanjianzi,callback:function(t){a.guanjianzi=t},expression:"guanjianzi"}})],1),a._v(" "),e("Col",{attrs:{lg:4,md:4}},[e("Button",{attrs:{type:"primary"},on:{click:a.doSearch}},[a._v("查询")]),a._v(" "),e("Button",{attrs:{type:"primary"},on:{click:a.clearSearch}},[a._v("清空")])],1),a._v(" "),e("Col",{attrs:{lg:16,md:16}},[e("Row",{attrs:{type:"flex",justify:"end"}},[e("Col",[e("Button",{attrs:{type:"primary"},on:{click:a.addSchool}},[a._v("新增学校")]),a._v(" "),e("Modal",{attrs:{title:"新增学校","mask-closable":!1},model:{value:a.addSchoolModal,callback:function(t){a.addSchoolModal=t},expression:"addSchoolModal"}},[e("div",{attrs:{slot:"header"},slot:"header"},[e("a",{staticClass:"ivu-modal-close",staticStyle:{display:"block!important"},on:{click:a.addCancel}},[e("i",{staticClass:"ivu-icon ivu-icon-ios-close-empty"})]),a._v(" "),e("div",{staticClass:"ivu-modal-header-inner"},[a._v("新增学校")])]),a._v(" "),e("div",{attrs:{slot:"footer"},slot:"footer"},[e("Button",{attrs:{type:"text",size:"large"},on:{click:a.addCancel}},[a._v("取消")]),a._v(" "),e("Button",{attrs:{type:"primary",size:"large"},on:{click:a.addOk}},[a._v("确定")])],1),a._v(" "),e("Form",{ref:"formValidate",attrs:{model:a.formValidate,rules:a.ruleValidate,"label-width":80}},[e("FormItem",{attrs:{label:"学校名称",prop:"name"}},[e("Input",{model:{value:a.formValidate.name,callback:function(t){a.$set(a.formValidate,"name",t)},expression:"formValidate.name"}})],1),a._v(" "),e("FormItem",{attrs:{label:"学校地址",prop:"address"}},[e("Input",{model:{value:a.formValidate.address,callback:function(t){a.$set(a.formValidate,"address",t)},expression:"formValidate.address"}})],1)],1)],1)],1)],1)],1)],1)],1)],1),a._v(" "),e("Col",{staticStyle:{"margin-top":"10px"},attrs:{lg:24,md:24}},[e("Card",[e("Row",{attrs:{gutter:20}},[e("Col",{attrs:{lg:12,md:12}},[e("Table",{attrs:{columns:a.school,data:a.schoolData,border:"",stripe:"","highlight-row":""},on:{"on-row-click":a.rowClick1}}),a._v(" "),e("Modal",{attrs:{title:"修改学校","mask-closable":!1},model:{value:a.amendSchoolModal,callback:function(t){a.amendSchoolModal=t},expression:"amendSchoolModal"}},[e("div",{attrs:{slot:"header"},slot:"header"},[e("a",{staticClass:"ivu-modal-close",staticStyle:{display:"block!important"},on:{click:a.amendCancel}},[e("i",{staticClass:"ivu-icon ivu-icon-ios-close-empty"})]),a._v(" "),e("div",{staticClass:"ivu-modal-header-inner"},[a._v("修改学校")])]),a._v(" "),e("div",{attrs:{slot:"footer"},slot:"footer"},[e("Button",{attrs:{type:"text",size:"large"},on:{click:a.amendCancel}},[a._v("取消")]),a._v(" "),e("Button",{attrs:{type:"primary",size:"large"},on:{click:a.amendOk}},[a._v("确定")])],1),a._v(" "),e("Form",{ref:"formValidateAmend",attrs:{model:a.formValidateAmend,rules:a.ruleValidateAmend,"label-width":80}},[e("FormItem",{attrs:{label:"学校名称",prop:"s_name"}},[e("Input",{model:{value:a.formValidateAmend.s_name,callback:function(t){a.$set(a.formValidateAmend,"s_name",t)},expression:"formValidateAmend.s_name"}})],1),a._v(" "),e("FormItem",{attrs:{label:"学校地址",prop:"s_address"}},[e("Input",{model:{value:a.formValidateAmend.s_address,callback:function(t){a.$set(a.formValidateAmend,"s_address",t)},expression:"formValidateAmend.s_address"}})],1)],1)],1),a._v(" "),e("Modal",{attrs:{title:"新增周边小区","mask-closable":!1},model:{value:a.addxiaoquModal,callback:function(t){a.addxiaoquModal=t},expression:"addxiaoquModal"}},[e("div",{attrs:{slot:"header"},slot:"header"},[e("a",{staticClass:"ivu-modal-close",staticStyle:{display:"block!important"},on:{click:a.addxiaoquCancel}},[e("i",{staticClass:"ivu-icon ivu-icon-ios-close-empty"})]),a._v(" "),e("div",{staticClass:"ivu-modal-header-inner"},[a._v("新增周边小区")])]),a._v(" "),e("div",{attrs:{slot:"footer"},slot:"footer"},[e("Button",{attrs:{type:"text",size:"large"},on:{click:a.addxiaoquCancel}},[a._v("取消")]),a._v(" "),e("Button",{attrs:{type:"primary",size:"large"},on:{click:a.addxiaoquOk}},[a._v("确定")])],1),a._v(" "),e("Form",{ref:"addxiaoquformValidate",attrs:{model:a.addxiaoquformValidate,rules:a.addxiaoquruleValidate,"label-width":80}},[e("FormItem",{attrs:{label:"片区",prop:"xiaoqu"}},[e("Cascader",{attrs:{data:a.DtsData},on:{"on-change":a.changeDts},model:{value:a.addxiaoquformValidate.pianqu,callback:function(t){a.$set(a.addxiaoquformValidate,"pianqu",t)},expression:"addxiaoquformValidate.pianqu"}})],1),a._v(" "),e("FormItem",{attrs:{label:"小区",prop:"xiaoqu"}},[e("Select",{attrs:{transfer:!0},model:{value:a.addxiaoquformValidate.xiaoqu,callback:function(t){a.$set(a.addxiaoquformValidate,"xiaoqu",t)},expression:"addxiaoquformValidate.xiaoqu"}},a._l(a.village,function(t){return e("Option",{key:t.village_name,attrs:{value:t.village_id}},[a._v(a._s(t.village_name))])}),1)],1),a._v(" "),e("FormItem",{attrs:{label:"备注",prop:"beizhu"}},[e("Input",{model:{value:a.addxiaoquformValidate.beizhu,callback:function(t){a.$set(a.addxiaoquformValidate,"beizhu",t)},expression:"addxiaoquformValidate.beizhu"}})],1)],1)],1),a._v(" "),e("Row",{staticStyle:{"margin-top":"10px"},attrs:{type:"flex",justify:"end"}},[e("Col",[e("Page",{attrs:{total:a.totalnum1,current:a.currentpage1,"page-size":a.pageSize,"show-total":""},on:{"on-change":a.changePage1}})],1)],1)],1),a._v(" "),e("Col",{attrs:{lg:12,md:12}},[e("Table",{attrs:{columns:a.housing,data:a.housingData,border:"",stripe:""},on:{"on-row-click":a.rowClick2}}),a._v(" "),e("Modal",{attrs:{title:"修改周边小区","mask-closable":!1},model:{value:a.setxiaoquModal,callback:function(t){a.setxiaoquModal=t},expression:"setxiaoquModal"}},[e("div",{attrs:{slot:"header"},slot:"header"},[e("a",{staticClass:"ivu-modal-close",staticStyle:{display:"block!important"},on:{click:a.setxiaoquCancel}},[e("i",{staticClass:"ivu-icon ivu-icon-ios-close-empty"})]),a._v(" "),e("div",{staticClass:"ivu-modal-header-inner"},[a._v("修改周边小区")])]),a._v(" "),e("div",{attrs:{slot:"footer"},slot:"footer"},[e("Button",{attrs:{type:"text",size:"large"},on:{click:a.setxiaoquCancel}},[a._v("取消")]),a._v(" "),e("Button",{attrs:{type:"primary",size:"large"},on:{click:a.setxiaoquOk}},[a._v("确定")])],1),a._v(" "),e("Form",{ref:"setxiaoquformValidate",attrs:{model:a.setxiaoquformValidate,rules:a.setxiaoquruleValidate,"label-width":80}},[e("FormItem",{attrs:{label:"片区",prop:"xiaoqu"}},[e("Cascader",{attrs:{data:a.DtsData},on:{"on-change":a.changeDts},model:{value:a.setxiaoquformValidate.pianqu,callback:function(t){a.$set(a.setxiaoquformValidate,"pianqu",t)},expression:"setxiaoquformValidate.pianqu"}})],1),a._v(" "),e("FormItem",{attrs:{label:"小区",prop:"xiaoqu"}},[e("Select",{attrs:{transfer:!0},model:{value:a.setxiaoquformValidate.xiaoqu,callback:function(t){a.$set(a.setxiaoquformValidate,"xiaoqu",t)},expression:"setxiaoquformValidate.xiaoqu"}},a._l(a.village,function(t){return e("Option",{key:t.village_name,attrs:{value:t.village_id}},[a._v(a._s(t.village_name))])}),1)],1),a._v(" "),e("FormItem",{attrs:{label:"备注",prop:"beizhu"}},[e("Input",{model:{value:a.setxiaoquformValidate.beizhu,callback:function(t){a.$set(a.setxiaoquformValidate,"beizhu",t)},expression:"setxiaoquformValidate.beizhu"}})],1)],1)],1),a._v(" "),e("Row",{staticStyle:{"margin-top":"10px"},attrs:{type:"flex",justify:"end"}},[e("Col",[e("Page",{attrs:{total:a.totalnum2,current:a.currentpage2,"page-size":a.pageSize,"show-total":""},on:{"on-change":a.changePage2}})],1)],1)],1)],1)],1)],1)],1)},s=[];i._withStripped=!0;var o={render:i,staticRenderFns:s};t.default=o}});