webpackJsonp([29],{262:function(e,t,a){"use strict";function n(e){c||a(690)}Object.defineProperty(t,"__esModule",{value:!0});var i=a(497),r=a.n(i);for(var s in i)"default"!==s&&function(e){a.d(t,e,function(){return i[e]})}(s);var o=a(692),l=a.n(o),c=!1,u=a(0),g=n,d=u(r.a,l.a,!1,g,null,null);d.options.__file="src/views/ziyuanguanli/ky-daikan.vue",t.default=d.exports},497:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=a(9),i=function(e){return e&&e.__esModule?e:{default:e}}(n);t.default={name:"kyDaikan",data:function(){var e=this;return{genjinjilv_ky:!1,pageTotal:0,pageCurrent:1,pageSize:10,FPpageTotal:0,FPpageCurrent:1,FPpageSize:10,settings:[],mDaterange:[],searchDaterange:[],searchData:{u_id:"",c_type:""},departpath:[],users:[],selId:"",genjinColumns:[{title:"类型",key:"customer_type",align:"center",render:function(e,t){var a=t.row.customer_type,n="";if("0"==a)n="买卖客源";else if("1"==a)n="租赁客源";else{if("2"!=a)return"未知客源";n="高端客源"}return e("div",{props:{}},n)}},{title:"客户姓名",key:"customer_name",align:"center"},{title:"客源编号",key:"xuqiubianhao",align:"center",render:function(t,a){return t("div",[t("a",{props:{type:"warning",size:"small"},style:{textDecoration:"underline"},domProps:{innerHTML:a.row.xuqiubianhao},on:{click:function(){var t={customer_uuid:a.row.customer_uuid,customer_type:a.row.customer_type};e.$router.push({name:"customerEtails",params:t})}}})])}},{title:"带看人",key:"daikanren",align:"center"},{title:"部门",key:"bumen",align:"center"},{title:"客户评价",key:"d_pingjia",align:"center"},{title:"带看时间",key:"ctime",align:"center"},{title:"带盘量",key:"action",align:"center",render:function(t,a){return t("div",[t("a",{props:{type:"primary",size:"small"},style:{marginRight:"5px"},on:{click:function(){e.selId=a.row.customer_uuid,e.listData=[],e.genjinjilv_ky=!0,e.getFollowup()}}},a.row.cstCountFy)])}}],genjinData:[],listColumns:[{title:"房源类型",key:"sale_type",align:"center",render:function(e,t){var a=t.row.sale_type,n="";if("2"==a)n="出售房源";else if("1"==a)n="出租房源";else{if("3"!=a)return"未知房源";n="高端房源"}return e("div",{props:{}},n)}},{title:"房源编号",key:"house_sn",align:"center",width:108,render:function(t,a){return t("div",[t("a",{props:{type:"warning",size:"small"},style:{textDecoration:"underline"},domProps:{innerHTML:a.row.house_sn},on:{click:function(){if(a.row.house_uuid){var t={houseId:a.row.house_uuid,saleType:a.row.sale_type};e.$router.push({name:"roomDetails",params:t})}else e.$Message.warning("参数错误");e.genjinjilv_ky=!1,i.default.set("genjinjilv_ky",1)}}})])}},{title:"小区",key:"village_name",align:"center"},{title:"楼层",key:"louceng",align:"center",render:function(e,t){return e("div",{props:{}},t.row.loudong_name+"栋"+t.row.danyuan_name+"单元"+t.row.fanghao_name+"室")}},{title:"户型",key:"fyhuxing",align:"center",render:function(e,t){return e("div",{props:{}},t.row.huxing_shi+"室"+t.row.huxing_ting+"厅"+t.row.huxing_wei+"卫")}},{title:"面积",key:"jianzhumianji",align:"center"},{title:"售价",key:"sell_price",align:"center"}],listData:[]}},created:function(){this.getSetting(),this.getIndex()},methods:{genjinjilv_kyCancel:function(){i.default.set("genjinjilv_ky",0),this.genjinjilv_ky=!1},changeSearchDepart:function(e,t){this.departpath=e;var a=e[e.length-1];this.users=this.settings.users[a]},getSetting:function(){this.$http.get(api_param.apiurl+"house/getsetting",{params:{},headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(e){if(200==e.data.code){for(var t in e.data.data)this.$set(this.settings,t,e.data.data[t]);e.data.data.customcolumns[5]&&null!=e.data.data.customcolumns[5]&&(this.tableColumnsChecked=e.data.data.customcolumns[5])}else 401==e.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(e.data.message)},function(e){this.$Message.error("你的网络开小差了^—^")})},changePage:function(e){this.pageCurrent=e,this.getIndex()},changeFPPage:function(e){this.FPpageCurrent=e,this.getFollowup()},doSearch:function(){this.pageCurrent=1,this.getIndex()},clearSearch:function(){for(var e in this.searchData)this.$set(this.searchData,e,"");this.searchDaterange=[],this.mDaterange=[],this.getIndex()},changeDaterange:function(e){this.searchDaterange=e},clearDaterange:function(e){this.searchDaterange=[]},getIndex:function(){var e=this.searchData;e.page=this.pageCurrent,e.dateRange=this.searchDaterange,e.daikan="cust",this.$http.post(api_param.apiurl+"customer_daikan/index",e,{emulateJSON:!0,headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(e){200==e.data.code?(this.genjinData=e.data.data.list,this.pageTotal=parseInt(e.data.data.count)):401==e.data.code?(this.$Message.error("登录超时"),this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(e.data.message)},function(e){this.$Message.error("网络异常")})},getFollowup:function(){this.$http.post(api_param.apiurl+"customer_daikan/daikan",{customer_uuid:this.selId,page:this.FPpageCurrent},{emulateJSON:!0,headers:{"X-Access-Token":api_param.XAccessToken}}).then(function(e){200==e.data.code?(this.listData=e.data.data.list,this.FPpageTotal=parseInt(e.data.data.count)):401==e.data.code?(this.$store.commit("logout",this),this.$store.commit("clearOpenedSubmenu"),this.$router.push({name:"login"})):this.$Message.warning(e.data.message)},function(e){this.$Message.error("网络异常")})}},watch:{$route:function(e,t){"kyDaikan"==this.$route.name&&(1==i.default.get("genjinjilv_ky")?(this.genjinjilv_ky=i.default.get("genjinjilv_ky"),i.default.set("genjinjilv_ky",0)):this.genjinjilv_ky=!1)}}}},690:function(e,t,a){var n=a(691);"string"==typeof n&&(n=[[e.i,n,""]]),n.locals&&(e.exports=n.locals);a(8)("82ce66fc",n,!1,{})},691:function(e,t,a){t=e.exports=a(7)(!1),t.push([e.i,"",""])},692:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("Row",[a("Col",{attrs:{lg:24}},[a("Card",[a("Row",{attrs:{gutter:5}},[a("Col",{attrs:{lg:2}},[a("Select",{attrs:{placeholder:"客源类型",transfer:!0},model:{value:e.searchData.c_type,callback:function(t){e.$set(e.searchData,"c_type",t)},expression:"searchData.c_type"}},[a("Option",{attrs:{value:"0"}},[e._v("买卖客源")]),e._v(" "),a("Option",{attrs:{value:"1"}},[e._v("租赁客源")]),e._v(" "),a("Option",{attrs:{value:"2"}},[e._v("高端客源")])],1)],1),e._v(" "),a("Col",{attrs:{lg:2}},[a("Cascader",{attrs:{data:e.settings.departlist,placeholder:"部门选择",filterable:"","change-on-select":"",transfer:!0},on:{"on-change":e.changeSearchDepart},model:{value:e.searchData.departpath,callback:function(t){e.$set(e.searchData,"departpath",t)},expression:"searchData.departpath"}})],1),e._v(" "),a("Col",{attrs:{lg:2}},[a("Select",{attrs:{placeholder:"带看人",transfer:!0},model:{value:e.searchData.u_id,callback:function(t){e.$set(e.searchData,"u_id",t)},expression:"searchData.u_id"}},e._l(e.users,function(t){return a("Option",{key:t.u_id,attrs:{value:t.u_id}},[e._v(e._s(t.u_name))])}),1)],1),e._v(" "),a("Col",{attrs:{lg:3}},[a("DatePicker",{staticStyle:{width:"100%"},attrs:{type:"daterange",placement:"bottom-end",placeholder:"开始-截止日期"},on:{"on-change":e.changeDaterange,"on-clear":e.clearDaterange},model:{value:e.mDaterange,callback:function(t){e.mDaterange=t},expression:"mDaterange"}})],1),e._v(" "),a("Col",{attrs:{lg:3}},[a("Button",{attrs:{type:"primary"},on:{click:e.doSearch}},[e._v("查询")]),e._v(" "),a("Button",{attrs:{type:"primary"},on:{click:e.clearSearch}},[e._v("清空")])],1)],1)],1)],1),e._v(" "),a("Col",{staticStyle:{"margin-top":"10px"},attrs:{lg:24,md:24}},[a("Card",[a("Row",[a("Table",{attrs:{border:"",columns:e.genjinColumns,data:e.genjinData}}),e._v(" "),a("div",{staticStyle:{margin:"10px",overflow:"hidden"}},[a("div",{staticStyle:{float:"right"}},[a("Page",{attrs:{total:e.pageTotal,current:e.pageCurrent,"show-total":"","page-size":e.pageSize},on:{"on-change":e.changePage}})],1)])],1)],1)],1),e._v(" "),a("Modal",{attrs:{title:"客户带看过的房源列表",width:"640"},model:{value:e.genjinjilv_ky,callback:function(t){e.genjinjilv_ky=t},expression:"genjinjilv_ky"}},[a("div",{attrs:{slot:"footer"},slot:"footer"},[a("Button",{attrs:{type:"text",size:"large"},on:{click:e.genjinjilv_kyCancel}},[e._v("取消")]),e._v(" "),a("Button",{attrs:{type:"primary",size:"large"},on:{click:e.genjinjilv_kyCancel}},[e._v("确定")])],1),e._v(" "),a("Row",[a("Col",[a("Table",{attrs:{border:"",columns:e.listColumns,data:e.listData}}),e._v(" "),a("div",{staticStyle:{margin:"10px",overflow:"hidden"}},[a("div",{staticStyle:{float:"right"}},[a("Page",{attrs:{total:e.FPpageTotal,current:e.FPpageCurrent,"show-total":"","page-size":e.FPpageSize},on:{"on-change":e.changeFPPage}})],1)])],1)],1)],1)],1)},i=[];n._withStripped=!0;var r={render:n,staticRenderFns:i};t.default=r}});