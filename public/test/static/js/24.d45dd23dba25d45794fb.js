webpackJsonp([24],{"4CbP":function(t,o,n){o=t.exports=n("FZ+f")(!1),o.push([t.i,".scan[data-v-ca4b7d3e]{height:100%;background:#fff}.scan .set[data-v-ca4b7d3e]{position:fixed;top:10px;right:10px;height:20px;z-index:10001;color:#fff;font-size:14px}.scan input[data-v-ca4b7d3e]{text-align:right}.scan .vanInput[data-v-ca4b7d3e]{float:left;height:60px;width:80%}.scan .snBtn[data-v-ca4b7d3e]{float:left;height:60px;line-height:60px}.scan .mui-input-group[data-v-ca4b7d3e]{padding-top:40px}.scan .right-select[data-v-ca4b7d3e]{width:65%;height:50px;line-height:2;float:right;text-align:right;padding:10px 15px;color:#a2aab5;font-size:15px}.scan .right-val[data-v-ca4b7d3e]{display:block;color:#333;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}.scan .select-area[data-v-ca4b7d3e]{position:fixed;z-index:1001;left:0;bottom:0;width:100%;height:50%;border-radius:0}.scan i[data-v-ca4b7d3e]{font-size:15px}",""])},"6FWe":function(t,o,n){"use strict";function e(t){n("isYl")}Object.defineProperty(o,"__esModule",{value:!0});var i=n("vvz6"),s=n("li9E"),a=n("VU/8"),c=e,r=a(i.a,s.a,!1,c,"data-v-ca4b7d3e",null);o.default=r.exports},isYl:function(t,o,n){var e=n("4CbP");"string"==typeof e&&(e=[[t.i,e,""]]),e.locals&&(t.exports=e.locals);n("rjj0")("176dce47",e,!0,{})},li9E:function(t,o,n){"use strict";var e=function(){var t=this,o=t.$createElement,n=t._self._c||o;return n("div",{staticClass:"scan"},[n("span",{staticClass:"set",on:{tap:t.unbindDevice}},[t._v("解绑")]),t._v(" "),n("form",{staticClass:"mui-input-group",attrs:{action:""},on:{submit:function(o){return o.preventDefault(),t.creatDevice(o)}}},[n("div",{staticClass:"mui-input-row"},[n("label",[t._v("设备SN号")]),t._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:t.snForm.snNumber,expression:"snForm.snNumber"}],staticClass:"mui-input-clear",attrs:{type:"text",disabled:""},domProps:{value:t.snForm.snNumber},on:{input:function(o){o.target.composing||t.$set(t.snForm,"snNumber",o.target.value)}}})]),t._v(" "),n("div",{staticClass:"mui-input-row"},[n("label",[t._v("设备类型")]),t._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:t.snForm.type,expression:"snForm.type"}],staticClass:"mui-input-clear",attrs:{type:"text",disabled:""},domProps:{value:t.snForm.type},on:{input:function(o){o.target.composing||t.$set(t.snForm,"type",o.target.value)}}})]),t._v(" "),n("div",{staticClass:"mui-input-row"},[n("label",[t._v("通信技术")]),t._v(" "),n("div",{staticClass:"right-select",on:{click:function(o){t.showPicker1=!0}}},[""==t.snForm.commTech?n("span",[t._v("请选择通信技术"),n("i",{staticClass:"van-icon van-icon-arrow"})]):n("span",{staticClass:"right-val"},[t._v(t._s(t.snForm.commTech))])])]),t._v(" "),n("div",{staticClass:"mui-input-row"},[n("label",[t._v("设备名称")]),t._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:t.snForm.name,expression:"snForm.name"}],staticClass:"mui-input-clear",attrs:{type:"text",placeholder:"请输入设备名称"},domProps:{value:t.snForm.name},on:{input:function(o){o.target.composing||t.$set(t.snForm,"name",o.target.value)}}})]),t._v(" "),n("div",{staticClass:"mui-input-row"},[n("label",[t._v("绑定园区")]),t._v(" "),n("div",{staticClass:"right-select",on:{click:function(o){t.showPicker2=!0}}},[""==t.snForm.gardenName?n("span",[t._v("请绑定园区"),n("i",{staticClass:"van-icon van-icon-arrow"})]):n("span",{staticClass:"right-val"},[t._v(t._s(t.snForm.gardenName))])])]),t._v(" "),n("div",{staticClass:"mui-input-row"},[n("label",[t._v("绑定地块")]),t._v(" "),n("div",{staticClass:"right-select",on:{click:function(o){t.showPicker3=!0}}},[""==t.snForm.plotName?n("span",[t._v("请绑定地块"),n("i",{staticClass:"van-icon van-icon-arrow"})]):n("span",{staticClass:"right-val"},[t._v(t._s(t.snForm.plotName))])])]),t._v(" "),n("van-button",{staticClass:"button_box fix-bottom",attrs:{loading:t.btnLoading,type:"info","loading-text":"保存中...",color:"linear-gradient(to right, #EE9742, #EA7739)"}},[t._v("保存")])],1),t._v(" "),n("van-popup",{attrs:{position:"bottom"},model:{value:t.showPicker1,callback:function(o){t.showPicker1=o},expression:"showPicker1"}},[n("van-picker",{attrs:{"show-toolbar":"",columns:t.columns1},on:{cancel:function(o){t.showPicker1=!1},confirm:t.onConfirm1}})],1),t._v(" "),n("van-popup",{attrs:{position:"bottom"},model:{value:t.showPicker2,callback:function(o){t.showPicker2=o},expression:"showPicker2"}},[n("van-picker",{attrs:{"show-toolbar":"",columns:t.columns2},on:{cancel:function(o){t.showPicker2=!1},confirm:t.onConfirm2}})],1),t._v(" "),n("van-popup",{attrs:{position:"bottom"},model:{value:t.showPicker3,callback:function(o){t.showPicker3=o},expression:"showPicker3"}},[n("van-picker",{attrs:{"show-toolbar":"",columns:t.columns3},on:{cancel:function(o){t.showPicker3=!1},confirm:t.onConfirm3}})],1)],1)},i=[],s={render:e,staticRenderFns:i};o.a=s},vvz6:function(t,o,n){"use strict";var e=n("BUCX"),i=(n.n(e),n("Au9i"));n.n(i);o.a={name:"ControlSetup",data:function(){return{snForm:{id:"",snNumber:"",type:"",commTech:"",commTechCode:"",name:"",gardenId:"",gardenName:"",plotId:"",plotName:""},btnLoading:!1,commTechList:[],showPicker1:!1,columns1:[],showPicker2:!1,columns2:[],showPicker3:!1,columns3:[],gardenList:[],plotList:[]}},mounted:function(){this.getSnCode()},methods:{getSnCode:function(){var t=this;console.log(this.$route.params.id),this.snForm.id=this.$route.params.id,this.$axios.getDeviceInfoById({id:this.snForm.id}).then(function(o){o.code&&200==o.code&&(console.log("device",o),t.snForm.snNumber=o.data.snNumber,t.snForm.commTechCode=o.data.commTech,t.snForm.name=o.data.name,t.snForm.type=o.data.secondTheDeviceName,t.snForm.gardenId=o.data.gardenId,t.snForm.plotId=o.data.plotId,t.getCommTechList(),t.getGardenList())})},getCommTechList:function(){var t=this;this.$axios.getCommTechList({}).then(function(o){if(console.log("Tech",o),o.code&&200==o.code){t.commTechList=o.data;var n=[];o.data.forEach(function(t){n.push(t.description)}),t.columns1=n,t.snForm.commTechCode&&o.data.forEach(function(o){o.code==t.snForm.commTechCode&&(t.snForm.commTech=o.description)}),t.snForm.commTechCode&&o.data.forEach(function(o){o.code==t.snForm.commTechCode&&(t.snForm.commTech=o.description)})}})},getGardenList:function(){var t=this;this.$axios.getGardenList({}).then(function(o){if(console.log("garden",o),o.code&&200==o.code){t.gardenList=o.data;var n=[];o.data.forEach(function(t){n.push(t.name)}),t.columns2=n,t.snForm.gardenId&&o.data.forEach(function(o){t.snForm.gardenId==o.id&&(t.snForm.gardenName=o.name,t.getBlockList())})}})},onConfirm1:function(t){var o=this;this.snForm.commTech=t,this.showPicker1=!1,this.commTechList.forEach(function(n){n.description==t&&(o.snForm.commTechCode=n.code)})},onConfirm2:function(t){var o=this;this.snForm.gardenName=t,this.showPicker2=!1,this.gardenList.forEach(function(n){n.name==t&&(o.snForm.gardenId=n.id,o.snForm.plotId="",o.snForm.plotName="")}),this.snForm.gardenId&&this.getBlockList()},getBlockList:function(){var t=this,o={gardenId:this.snForm.gardenId};this.$axios.getBlockList(o).then(function(o){if(console.log("block",o),o.code&&200==o.code){t.plotList=o.data;var n=[];o.data.forEach(function(t){n.push(t.name)}),t.columns3=n,t.snForm.plotId&&o.data.forEach(function(o){t.snForm.plotId==o.id&&(t.snForm.plotName=o.name)})}})},onConfirm3:function(t){var o=this;this.snForm.plotName=t,this.showPicker3=!1,this.plotList.forEach(function(n){n.name==t&&(o.snForm.plotId=n.id)})},creatDevice:function(){var t=this;if(!this.snForm.commTechCode)return n.i(i.Toast)("请选择通信技术"),!1;if(this.snForm.name||n.i(i.Toast)("请输入设备名称"),!this.snForm.gardenId)return n.i(i.Toast)("请选择园区"),!1;if(!this.snForm.plotId)return n.i(i.Toast)("请输入地块"),!1;var o={id:this.snForm.id,name:this.snForm.name,commTech:this.snForm.commTechCode,plotId:this.snForm.plotId,hasPlot:1};this.btnLoading=!0,this.$axios.updateFacility(o).then(function(o){t.btnLoading=!1,console.log("update",o),o.code&&200==o.code&&n.i(i.Toast)("编辑成功")})},unbindDevice:function(){var t=this,o={id:this.snForm.id,plotId:0,hasPlot:0};this.$axios.updateFacility(o).then(function(o){t.btnLoading=!1,console.log("unbind",o),o.code&&200==o.code&&(n.i(i.Toast)("解绑成功"),t.$router.push("/home"))})}}}}});