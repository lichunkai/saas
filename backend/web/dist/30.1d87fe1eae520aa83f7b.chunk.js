webpackJsonp([30],{259:function(t,a,e){"use strict";function i(t){h||e(681)}Object.defineProperty(a,"__esModule",{value:!0});var s=e(494),n=e.n(s);for(var o in s)"default"!==o&&function(t){e.d(a,t,function(){return s[t]})}(o);var r=e(683),l=e.n(r),h=!1,c=e(0),u=i,d=c(n.a,l.a,!1,u,null,null);d.options.__file="src/views/ziyuanguanli/houseWeituo.vue",a.default=d.exports},494:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default={name:"houseWeituo",data:function(){var t=this;return{shixiao:!1,pageSize:10,page:1,pageCount:0,searchData:{},settings:{},users:[],daikan:"",genjin:"",totallog:0,currentpage:1,keylogdata:[],shixiaoValidate:{shixiaoyuanyin:[{required:!0,message:"请选择失效原因",trigger:"change"}],shixiaoBeizhu:[{required:!0,message:"请选择失效原因",trigger:"blur"}]},shixiaoData:{shixiaoyuanyin:"",shixiaoBeizhu:""},chColumns:[{title:"标签",key:"biaoqian",fixed:"left",width:128,align:"center",render:function(t,a){var e=[];return a.row.genjincishu&&e.push(t("Tag",{props:{color:"green"}},"跟("+a.row.genjincishu+")")),a.row.daikancishu&&e.push(t("Tag",{props:{color:"yellow"}},"带("+a.row.daikancishu+")")),t("div",e)}},{title:"房源类型",key:"sale_type",align:"center",width:88,render:function(t,a){var e=a.row.sale_type,i="";if("2"==e)i="出售";else if("1"==e)i="出租";else{if("3"!=e)return"未知";i="高端"}return t("div",{props:{}},i)}},{title:"委托状态",key:"hw_status",width:88,align:"center",render:function(t,a){var e="";return"1"==a.row.hw_status&&(e="有效"),"9"==a.row.hw_status&&(e="失效"),t("div",{props:{}},e)}},{title:"房源状态",key:"house_status_text",width:88,align:"center"},{title:"片区",key:"dts_name",width:108,align:"center"},{title:"小区",key:"village_name",width:88,align:"center"},{title:"座栋",key:"loudong_name",width:88,align:"center"},{title:"单元",key:"danyuan_name",width:88,align:"center"},{title:"房号",key:"fanghao_name",width:88,align:"center"},{title:"房源编号",key:"house_sn",align:"center",width:148,render:function(a,e){return a("div",[a("a",{props:{type:"warning",size:"small"},style:{textDecoration:"underline"},domProps:{innerHTML:e.row.house_sn},on:{click:function(){var a={houseId:e.row.house_uuid,saleType:e.row.sale_type};t.$router.push({name:"roomDetails",params:a})}}})])}},{title:"开始时间",key:"hw_start_time",align:"center",width:128},{title:"结束时间",key:"hw_end_time",align:"center",width:128},{title:"添加时间",key:"ctime",align:"center",width:128},{title:"带看日期",key:"daikanshijian",align:"center",width:148},{title:"维护人跟进日期",key:"weihurengenjin",align:"center",width:128},{title:"全员跟进日期",key:"quanyuangenjin",align:"center",width:128},{title:"委托部门",key:"d_name",align:"center",width:128},{title:"委托人",key:"u_name",align:"center",width:128},{title:"委托编号",key:"hw_sn",align:"center",width:128},{title:"添加时间",key:"ctime",align:"center",width:128},{title:"失效操作人",key:"hw_invalid_uname",align:"center",width:128},{title:"失效原因",key:"hw_invalid_reason",align:"center",width:128},{title:"操作",key:"action",align:"center",fixed:"right",width:110,render:function(a,e){var i=[];return 9!=e.row.hw_status&&i.push(a("Button",{props:{type:"warning",size:"small"},style:{marginRight:"5px"},on:{click:function(){t.hw_id=e.row.hw_id,t.house_id=e.row.house_uuid,t.house_sn=e.row.house_sn,t.shixiao=!0}}},"失效")),a("div",i)}}],chData:[],hw_id:"",house_id:"",house_sn:""}},created:function(){this.getSetting(),this.getIndex()},methods:{daikan_s:function(){this.genjin="",this.page=1,this.getIndex()},paixu:function(t){this.searchData.paixu=t,this.pageCurrent=1,this.getIndex()},genjin_s:function(){this.page=1,this.daikan="",this.getIndex()},getSetting:function(){this.$http.get(api_param.apiurl+"house_weituo/getsetting",{params:{},headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(t){if(200==t.data.code)for(var a in t.data.data)this.$set(this.settings,a,t.data.data[a]);else 401==t.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(t.data.message)},function(t){this.$Message.error("你的网络开小差了^—^")})},getIndex:function(){this.searchData.page=this.page,this.searchData.daikan=this.daikan,this.searchData.genjin=this.genjin;this.$http.post(api_param.apiurl+"house_weituo/getindex",this.searchData,{emulateJSON:!0,headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(t){200==t.data.code?(this.chData=t.data.data.list,this.pageCount=parseInt(t.data.data.count),this.$Message.success("获取成功")):401==t.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(t.data.message)},function(t){this.$Message.error("网络异常")})},changeDepart:function(t,a){var e=t.pop();this.users=this.settings.users[e]},changeJiechuDepart:function(t,a){var e=t.pop();this.jiechu_users=this.settings.users[e]},selectDate:function(t){this.searchData.daterange=t},doSearch:function(){this.page=1,this.getIndex()},doClear:function(){this.page=1,this.searchData.sale_type="",this.searchData.house_status="",this.searchData.hw_status="",this.searchData.departpath=[],this.searchData.u_id="",this.searchData.villages="",this.searchData.danyuan="",this.searchData.daterange="",this.users=[],this.daikan="",this.genjin="",this.getIndex()},changelogPage:function(t){this.lpage=t,this.keyLog()},changePage:function(t){this.page=t,this.getIndex()},shixiaoModalCancel:function(){this.shixiao=!1,this.$refs.shixiaoData.resetFields(),this.shixiaoData.shixiaoyuanyin="",this.shixiaoData.shixiaoBeizhu=""},shixiaoModalOk:function(){var t=this;this.$refs.shixiaoData.validate(function(a){a&&(t.shixiaoData.hw_id=t.hw_id,t.shixiaoData.hk_status=9,t.shixiaoData.house_id=t.house_id,t.shixiaoData.house_sn=t.house_sn,t.$http.post(api_param.apiurl+"house_weituo/shixiao",t.shixiaoData,{emulateJSON:!0,headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(t){200==t.data.code?(this.$Message.success(t.data.message),this.shixiao=!1,this.getIndex()):401==t.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(t.data.message)},function(t){this.$Message.error("网络异常")}))})}}}},681:function(t,a,e){var i=e(682);"string"==typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);e(8)("4e18c6a8",i,!1,{})},682:function(t,a,e){a=t.exports=e(7)(!1),a.push([t.i,"\n.marTop10px {\n  margin-top: 10px;\n}\n.marTop10px .ivu-table-cell {\n  padding: 0 !important;\n}\n",""])},683:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("Row",[e("Col",{attrs:{lg:24,md:24}},[e("Card",[e("Row",{attrs:{gutter:5}},[e("Col",{attrs:{lg:2,md:2}},[e("Select",{attrs:{placeholder:"房源类别"},model:{value:t.searchData.sale_type,callback:function(a){t.$set(t.searchData,"sale_type",a)},expression:"searchData.sale_type"}},[e("Option",{attrs:{value:"0"}},[t._v("全部")]),t._v(" "),e("Option",{attrs:{value:"2"}},[t._v("二手房出售")]),t._v(" "),e("Option",{attrs:{value:"1"}},[t._v("二手房出租")]),t._v(" "),e("Option",{attrs:{value:"3"}},[t._v("二手房高端")])],1)],1),t._v(" "),e("Col",{attrs:{lg:2,md:2}},[e("Select",{attrs:{placeholder:"房源状态"},model:{value:t.searchData.house_status,callback:function(a){t.$set(t.searchData,"house_status",a)},expression:"searchData.house_status"}},[e("Option",{attrs:{value:"1"}},[t._v("有效")]),t._v(" "),e("Option",{attrs:{value:"0"}},[t._v("无效")]),t._v(" "),e("Option",{attrs:{value:"2"}},[t._v("其他")]),t._v(" "),e("Option",{attrs:{value:"3"}},[t._v("撤单")]),t._v(" "),e("Option",{attrs:{value:"4"}},[t._v("成交")])],1)],1),t._v(" "),e("Col",{attrs:{lg:2,md:2}},[e("Select",{attrs:{placeholder:"委托状态"},model:{value:t.searchData.hw_status,callback:function(a){t.$set(t.searchData,"hw_status",a)},expression:"searchData.hw_status"}},[e("Option",{attrs:{value:"1"}},[t._v("正常")]),t._v(" "),e("Option",{attrs:{value:"9"}},[t._v("失效")])],1)],1)],1),t._v(" "),e("Row",{staticStyle:{"margin-top":"10px"},attrs:{gutter:5}},[e("Col",{attrs:{lg:2,md:2}},[e("Cascader",{attrs:{data:t.settings.alldepartlist,value:t.searchData.departpath,filterable:"","change-on-select":"",placeholder:"选择部门"},on:{"update:value":function(a){return t.$set(t.searchData,"departpath",a)},"on-change":t.changeDepart}})],1),t._v(" "),e("Col",{attrs:{lg:2,md:2}},[e("Select",{attrs:{transfer:!0,placeholder:"选择员工"},model:{value:t.searchData.u_id,callback:function(a){t.$set(t.searchData,"u_id",a)},expression:"searchData.u_id"}},t._l(t.users,function(a){return e("Option",{key:a.u_id,attrs:{value:a.u_id}},[t._v(t._s(a.u_name))])}),1)],1),t._v(" "),e("Col",{attrs:{lg:3,md:3}},[e("DatePicker",{staticStyle:{width:"100%"},attrs:{type:"daterange",value:t.searchData.daterange,format:"yyyy-MM-dd",placeholder:"选择时间段"},on:{"on-change":t.selectDate}})],1),t._v(" "),e("Col",{attrs:{lg:2,md:2}},[e("Cascader",{attrs:{data:t.settings.villages,placeholder:"区域选择"},model:{value:t.searchData.villages,callback:function(a){t.$set(t.searchData,"villages",a)},expression:"searchData.villages"}})],1),t._v(" "),e("Col",{attrs:{lg:2,md:2}},[e("Input",{attrs:{placeholder:"单元、房号"},model:{value:t.searchData.danyuan,callback:function(a){t.$set(t.searchData,"danyuan",a)},expression:"searchData.danyuan"}})],1),t._v(" "),e("Col",{attrs:{lg:3,md:3}},[e("Button",{attrs:{type:"primary"},on:{click:t.doSearch}},[t._v("查询")]),t._v(" "),e("Button",{attrs:{type:"primary"},on:{click:t.doClear}},[t._v("清空")])],1)],1),t._v(" "),e("Row",{staticStyle:{"margin-top":"10px"},attrs:{gutter:5}},[e("Col",{staticClass:"margin-10px",attrs:{lg:24,md:24}},[e("RadioGroup",{on:{"on-change":t.daikan_s},model:{value:t.daikan,callback:function(a){t.daikan=a},expression:"daikan"}},[e("Radio",{attrs:{label:"3"}},[t._v("三天有带看")]),t._v(" "),e("Radio",{attrs:{label:"7"}},[t._v("七天有带看")]),t._v(" "),e("Radio",{attrs:{label:"15"}},[t._v("十五天有带看")])],1),t._v(" "),e("RadioGroup",{on:{"on-change":t.genjin_s},model:{value:t.genjin,callback:function(a){t.genjin=a},expression:"genjin"}},[e("Radio",{attrs:{label:"3"}},[t._v("三天有跟进")]),t._v(" "),e("Radio",{attrs:{label:"7"}},[t._v("七天有跟进")]),t._v(" "),e("Radio",{attrs:{label:"15"}},[t._v("十五天有跟进")])],1),t._v(" "),e("Dropdown",{attrs:{trigger:"click"},on:{"on-click":t.paixu}},[e("Button",{attrs:{type:"primary"}},[e("a",{staticStyle:{color:"#fff"},attrs:{href:"javascript:void(0)"}},[t._v(" 条件排序\n                        "),e("Icon",{attrs:{type:"arrow-down-b"}})],1)]),t._v(" "),e("DropdownMenu",{attrs:{slot:"list"},slot:"list"},[e("DropdownItem",{attrs:{name:"1"}},[t._v("按跟进次数升序")]),t._v(" "),e("DropdownItem",{attrs:{name:"2"}},[t._v("按跟进次数降序")]),t._v(" "),e("DropdownItem",{attrs:{name:"3"}},[t._v("按带看次数升序")]),t._v(" "),e("DropdownItem",{attrs:{name:"4"}},[t._v("按带看次数降序")]),t._v(" "),e("DropdownItem",{attrs:{name:"5"}},[t._v("按录入时间升序")]),t._v(" "),e("DropdownItem",{attrs:{name:"6"}},[t._v("按录入时间降序")])],1)],1)],1)],1)],1)],1),t._v(" "),e("Col",{staticClass:"marTop10px",attrs:{lg:24,md:24}},[e("Card",[e("Table",{attrs:{border:"",columns:t.chColumns,data:t.chData}}),t._v(" "),e("div",{staticStyle:{margin:"10px",overflow:"hidden"}},[e("div",{staticStyle:{float:"right"}},[e("Page",{attrs:{total:t.pageCount,current:t.page,"show-total":!0,"page-size":t.pageSize},on:{"on-change":t.changePage}})],1)])],1)],1),t._v(" "),e("Modal",{attrs:{title:"失效","mask-closable":!1},model:{value:t.shixiao,callback:function(a){t.shixiao=a},expression:"shixiao"}},[e("div",{attrs:{slot:"header"},slot:"header"},[e("a",{staticClass:"ivu-modal-close",staticStyle:{display:"block!important"},on:{click:t.shixiaoModalCancel}},[e("i",{staticClass:"ivu-icon ivu-icon-ios-close-empty"})]),t._v(" "),e("div",{staticClass:"ivu-modal-header-inner"},[t._v("失效")])]),t._v(" "),e("div",{attrs:{slot:"footer"},slot:"footer"},[e("Button",{attrs:{type:"text",size:"large"},on:{click:t.shixiaoModalCancel}},[t._v("取消")]),t._v(" "),e("Button",{attrs:{type:"primary",size:"large"},on:{click:t.shixiaoModalOk}},[t._v("确定")])],1),t._v(" "),e("Form",{ref:"shixiaoData",attrs:{model:t.shixiaoData,rules:t.shixiaoValidate,"label-width":80}},[e("Row",[e("Col",{attrs:{lg:12,md:12}},[e("FormItem",{attrs:{label:"失效原因",prop:"shixiaoyuanyin"}},[e("Select",{attrs:{placeholder:"失效原因",transfer:!0},model:{value:t.shixiaoData.shixiaoyuanyin,callback:function(a){t.$set(t.shixiaoData,"shixiaoyuanyin",a)},expression:"shixiaoData.shixiaoyuanyin"}},t._l(t.settings.weituoshixiao,function(a){return e("Option",{key:a.child_name,attrs:{value:a.child_name}},[t._v(t._s(a.child_name))])}),1)],1)],1)],1),t._v(" "),e("Row",[e("Col",{attrs:{lg:24,md:24}},[e("FormItem",{attrs:{label:"备注",prop:"shixiaoBeizhu"}},[e("Input",{attrs:{type:"textarea",rows:4,placeholder:"请输入失效备注"},model:{value:t.shixiaoData.shixiaoBeizhu,callback:function(a){t.$set(t.shixiaoData,"shixiaoBeizhu",a)},expression:"shixiaoData.shixiaoBeizhu"}})],1)],1)],1)],1)],1)],1)},s=[];i._withStripped=!0;var n={render:i,staticRenderFns:s};a.default=n}});