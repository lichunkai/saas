webpackJsonp([60],{283:function(n,t,e){"use strict";function a(n){l||e(749)}Object.defineProperty(t,"__esModule",{value:!0});var s=e(518),o=e.n(s);for(var r in s)"default"!==r&&function(n){e.d(t,n,function(){return s[n]})}(r);var i=e(751),c=e.n(i),l=!1,d=e(0),g=a,h=d(o.a,c.a,!1,g,"data-v-589e67d5",null);h.options.__file="src/views/check-work/work-guanli.vue",t.default=h.exports},518:function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={name:"workGuanli",data:function(){var n=this;return{kaoqin:!1,columns:[{title:"部门",key:"d_name",align:"center"},{title:"员工",key:"u_name",align:"center"},{title:"日期",key:"kq_date",align:"center"},{title:"出勤状态",key:"status",align:"center"},{title:"应上班时间",key:"df_st",align:"center"},{title:"应下班时间",key:"df_ed",align:"center"},{title:"实际上班时间",key:"sj_st",align:"center"},{title:"实际下班时间",key:"sj_ed",align:"center"},{title:"上班打卡备注",key:"remark",align:"center"},{title:"操作",key:"caozuo",width:150,align:"center",render:function(t,e){return t("div",[t("Button",{props:{type:"primary",size:"small"},style:{marginRight:"5px"},on:{click:function(){n.d_name=e.row.d_name,n.yuangong=e.row.u_name,n.paiban=e.row.tp_name,n.df_st=e.row.df_st,n.df_ed=e.row.df_ed,n.kaoqinForm.sj_st=e.row.sj_st,n.kaoqinForm.sj_ed=e.row.sj_ed,n.kaoqinForm.kq_mg_id=e.row.kq_mg_id,n.kaoqin=!0}}},"修改考勤")])}}],data:[],kaoqinForm:{yuangong:"",paiban:"",sj_st:"",sj_ed:""},mDaterange:[],searchDaterange:[],departchoose:[],searchkey:"",keyword:"",d_name:"",yuangong:"",paiban:"",df_st:"",df_ed:"",current:1,total:0,pageSize:10}},created:function(){this.getList()},methods:{changePage:function(n){this.current=n,this.getList()},getList:function(){this.$http.get(api_param.apiurl+"oakaoqingguanli/getlist",{params:{page:this.current,d_id:this.searchkey,dateRange:this.searchDaterange,kw:this.keyword},headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(n){200==n.data.code?(this.data=n.data.data.list,this.total=n.data.data.totalnum,this.departchoose=n.data.data.departchoose):401==n.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(n.data.message)},function(n){console.log(n)})},kaoqinOk:function(){this.$http.post(api_param.apiurl+"/oakaoqingguanli/update",{kq_mg_id:this.kaoqinForm.kq_mg_id,sj_st:this.kaoqinForm.sj_st,sj_ed:this.kaoqinForm.sj_ed},{emulateJSON:!0,headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(n){200==n.data.code?(this.kaoqin=!1,this.$Message.success(n.data.message),this.getList()):401==n.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(n.data.message)},function(n){this.$Message.warning("设置失败")})},kaoqinCancel:function(){this.kaoqin=!1},searchDepart:function(n,t){this.searchkey=n[n.length-1]},changeDaterange:function(n){this.searchDaterange=n},clearDaterange:function(n){this.searchDaterange=[]},changeSt:function(n){this.kaoqinForm.sj_st=n},clearSt:function(n){this.kaoqinForm.sj_st=""},changeEd:function(n){this.kaoqinForm.sj_ed=n},clearEd:function(n){this.kaoqinForm.sj_ed=""},doSearch:function(){this.current=1,this.getList()},clearSearch:function(){this.searchkey="",this.keyword="",this.mDaterange=[],this.searchDaterange=[],this.getList()}}}},749:function(n,t,e){var a=e(750);"string"==typeof a&&(a=[[n.i,a,""]]),a.locals&&(n.exports=a.locals);e(8)("15f50d80",a,!1,{})},750:function(n,t,e){t=n.exports=e(7)(!1),t.push([n.i,"\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n",""])},751:function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a=function(){var n=this,t=n.$createElement,e=n._self._c||t;return e("Row",[e("Col",{attrs:{lg:24,md:24}},[e("Card",[e("Row",{attrs:{gutter:10}},[e("Col",{attrs:{lg:2,md:2}},[e("Cascader",{attrs:{data:n.departchoose,value:n.searchkey,filterable:"","change-on-select":"",placeholder:"选择门店",transfer:!0},on:{"update:value":function(t){n.searchkey=t},"on-change":n.searchDepart}})],1),n._v(" "),e("Col",{attrs:{lg:3,md:3}},[e("Input",{attrs:{placeholder:"员工名称、员工编号、员工电话"},model:{value:n.keyword,callback:function(t){n.keyword=t},expression:"keyword"}})],1),n._v(" "),e("Col",{attrs:{lg:3,md:3}},[e("DatePicker",{attrs:{type:"daterange",placeholder:"选择日期段",transfer:!0},on:{"on-change":n.changeDaterange,"on-clear":n.clearDaterange},model:{value:n.mDaterange,callback:function(t){n.mDaterange=t},expression:"mDaterange"}})],1),n._v(" "),e("Col",{attrs:{lg:3,md:3}},[e("Button",{attrs:{type:"primary"},on:{click:n.doSearch}},[n._v("查询")]),n._v(" "),e("Button",{attrs:{type:"primary"},on:{click:n.clearSearch}},[n._v("清空")])],1)],1)],1)],1),n._v(" "),e("Col",{staticStyle:{"margin-top":"10px"},attrs:{lg:24,md:24}},[e("Card",[e("Table",{attrs:{border:"",columns:n.columns,data:n.data}}),n._v(" "),e("div",{staticStyle:{margin:"10px",overflow:"hidden"}},[e("div",{staticStyle:{float:"right"}},[e("Page",{attrs:{total:n.total,current:n.current,"show-total":"","page-size":n.pageSize},on:{"on-change":n.changePage}})],1)])],1)],1),n._v(" "),e("Modal",{attrs:{title:"修改考勤",width:"350","mask-closable":!1},on:{"on-ok":n.kaoqinOk,"on-cancel":n.kaoqinCancel},model:{value:n.kaoqin,callback:function(t){n.kaoqin=t},expression:"kaoqin"}},[e("Form",{ref:"kaoqinForm",attrs:{model:n.kaoqinForm,"label-width":100}},[e("Row",[e("Col",{attrs:{lg:24,md:24}},[e("Row",[e("Col",{staticStyle:{"text-align":"right"},attrs:{lg:7,md:7}},[n._v("员工")]),n._v(" "),e("Col",{attrs:{lg:16,md:16,offset:"1"}},[e("span",[n._v(n._s(n.d_name))]),n._v("  "),e("span",[n._v(n._s(n.yuangong))])])],1)],1),n._v(" "),e("Col",{staticStyle:{"margin-top":"15px"},attrs:{lg:24,md:24}},[e("Row",[e("Col",{staticStyle:{"text-align":"right"},attrs:{lg:7,md:7}},[n._v("考勤模板")]),n._v(" "),e("Col",{attrs:{lg:16,md:16,offset:"1"}},[e("span",[n._v(n._s(n.paiban))])])],1)],1),n._v(" "),e("Col",{staticStyle:{"margin-top":"15px"},attrs:{lg:24,md:24}},[e("Row",[e("Col",{staticStyle:{"text-align":"right"},attrs:{lg:7,md:7}},[n._v("打卡时间")]),n._v(" "),e("Col",{attrs:{lg:16,md:16,offset:"1"}},[e("span",[n._v(n._s(n.df_st)+"-"+n._s(n.df_ed))])])],1)],1),n._v(" "),e("Col",{staticStyle:{"margin-top":"15px"},attrs:{lg:24,md:24}},[e("FormItem",{attrs:{prop:"shangbanshijian",label:"上班时间"}},[e("TimePicker",{staticStyle:{width:"200px"},attrs:{value:n.kaoqinForm.sj_st,format:"HH:mm:ss"},on:{"on-change":n.changeSt,"on-clear":n.clearSt}})],1)],1),n._v(" "),e("Col",{attrs:{lg:24,md:24}},[e("FormItem",{attrs:{prop:"xiabanshijian",label:"下班时间"}},[e("TimePicker",{staticStyle:{width:"200px"},attrs:{value:n.kaoqinForm.sj_ed,format:"HH:mm:ss"},on:{"on-change":n.changeEd,"on-clear":n.clearEd}})],1)],1)],1)],1)],1)],1)},s=[];a._withStripped=!0;var o={render:a,staticRenderFns:s};t.default=o}});