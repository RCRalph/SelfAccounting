!function(e){var t={};function s(n){if(t[n])return t[n].exports;var a=t[n]={i:n,l:!1,exports:{}};return e[n].call(a.exports,a,a.exports,s),a.l=!0,a.exports}s.m=e,s.c=t,s.d=function(e,t,n){s.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},s.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},s.t=function(e,t){if(1&t&&(e=s(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(s.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)s.d(n,a,function(t){return e[t]}.bind(null,a));return n},s.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return s.d(t,"a",t),t},s.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},s.p="/",s(s.s=230)}({1:function(e,t,s){"use strict";function n(e,t,s,n,a,r,i,c){var u,o="function"==typeof e?e.options:e;if(t&&(o.render=t,o.staticRenderFns=s,o._compiled=!0),n&&(o.functional=!0),r&&(o._scopeId="data-v-"+r),i?(u=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),a&&a.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(i)},o._ssrRegister=u):a&&(u=c?function(){a.call(this,(o.functional?this.parent:this).$root.$options.shadowRoot)}:a),u)if(o.functional){o._injectStyles=u;var l=o.render;o.render=function(e,t){return u.call(t),l(e,t)}}else{var h=o.beforeCreate;o.beforeCreate=h?[].concat(h,u):[u]}return{exports:e,options:o}}s.d(t,"a",(function(){return n}))},2:function(e,t,s){"use strict";var n=s(1),a=Object(n.a)({},(function(){var e=this.$createElement;this._self._c;return this._m(0)}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"d-flex justify-content-center my-2"},[t("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[t("span",{staticClass:"sr-only"},[this._v("Loading...")])])])}],!1,null,null,null);t.a=a.exports},230:function(e,t,s){e.exports=s(231)},231:function(e,t,s){Vue.component("cash-component",s(261).default);new Vue({el:"#app"})},261:function(e,t,s){"use strict";s.r(t);var n=s(2),a=s(5),r={components:{Loading:n.a,SaveResetChanges:a.a},data:function(){return{ready:!1,currencies:[],currentCurrency:1,cash:{},means:{},cashMeans:{},cashMeansCopy:{},usersCash:{},usersCashCopy:{},saveChangesSpinner:!1}},computed:{sumOfCash:function(){var e=this;return this.disableSubmit?0:Math.round(1e3*this.cash[this.currentCurrency].map((function(e){return{id:e.id,value:e.value}})).map((function(t){return e.usersCash[t.id]*t.value})).reduce((function(e,t){return e+t})),3)/1e3},currentCashMeanBalance:function(){var e=this;return null!=this.cashMeans[this.currentCurrency]&&this.means[this.currentCurrency].find((function(t){return t.id==e.cashMeans[e.currentCurrency]})).balance},disableSubmit:function(){for(var e in this.usersCash)if(!this.isValidCashAmount(this.usersCash[e]))return!0;return!1}},methods:{isValidCashAmount:function(e){return""!==e&&(e=Number(e),!isNaN(e)&&(e>=0&&Math.floor(e)==e&&e<Math.pow(2,63)))},saveChanges:function(){var e=this;this.saveChangesSpinner=!0;var t=[];for(var s in this.usersCash)0!=this.usersCash[s]&&t.push({id:s,amount:this.usersCash[s]});var n=[];for(var a in this.cashMeans)null!=this.cashMeans[a]&&n.push(this.cashMeans[a]);axios.post("/webapi/bundles/cash",{usersCash:t,cashMeans:n}).then((function(){e.cashMeansCopy=_.cloneDeep(e.cashMeans),e.usersCashCopy=_.cloneDeep(e.usersCash)})).catch((function(e){console.error(e)})).finally((function(){e.saveChangesSpinner=!1}))},resetChanges:function(){this.cashMeans=_.cloneDeep(this.cashMeansCopy),this.usersCash=_.cloneDeep(this.usersCashCopy)}},mounted:function(){var e=this;axios.get("/webapi/bundles/cash",{}).then((function(t){var s=t.data;for(var n in e.currencies=s.currencies,e.currentCurrency=s.lastCurrency,e.cash=s.cash,e.means=s.means,e.cashMeans=s.cashMeans,e.cashMeansCopy=_.cloneDeep(s.cashMeans),e.cash)e.cash[n].forEach((function(e){null==s.usersCash[e.id]&&(s.usersCash[e.id]=0)}));e.usersCash=s.usersCash,e.usersCashCopy=_.cloneDeep(s.usersCash),e.ready=!0}))}},i=s(1),c=Object(i.a)(r,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"card"},[s("div",{staticClass:"card-header-flex"},[e._m(0),e._v(" "),e.ready?s("div",{staticClass:"d-flex"},[s("div",{staticClass:"h4 my-auto mr-3"},[e._v("Currency:")]),e._v(" "),s("select",{directives:[{name:"model",rawName:"v-model",value:e.currentCurrency,expression:"currentCurrency"}],staticClass:"form-control",on:{change:function(t){var s=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.currentCurrency=t.target.multiple?s:s[0]}}},e._l(e.currencies,(function(t){return s("option",{key:t.id,domProps:{value:t.id}},[e._v("\n                        "+e._s(t.ISO)+"\n                    ")])})),0)]):e._e()]),e._v(" "),s("div",{staticClass:"card-body"},[e.ready?s("div",[s("div",{staticClass:"form-group row"},[e._m(1),e._v(" "),s("div",{staticClass:"col-lg-5"},[s("select",{directives:[{name:"model",rawName:"v-model",value:e.cashMeans[e.currentCurrency],expression:"cashMeans[currentCurrency]"}],staticClass:"form-control",on:{change:function(t){var s=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.$set(e.cashMeans,e.currentCurrency,t.target.multiple?s:s[0])}}},[s("option",{domProps:{value:null,selected:null}},[e._v("N/A")]),e._v(" "),e._l(e.means[e.currentCurrency],(function(t,n){return s("option",{key:n,domProps:{value:t.id,selected:t.id}},[e._v("\n                                "+e._s(t.name)+"\n                            ")])}))],2)])]),e._v(" "),s("hr",{staticClass:"hr"}),e._v(" "),s("div",{staticClass:"row"},[s("div",{staticClass:"col-lg-10 offset-lg-1 table-responsive-lg"},[s("table",{staticClass:"table-themed responsive-table-hover"},[e._m(2),e._v(" "),s("tbody",e._l(e.cash[e.currentCurrency],(function(t,n){return s("tr",{key:n},[s("th",{staticClass:"h5 font-weight-bold",attrs:{scope:"row"}},[e._v("\n                                        "+e._s(Number(t.value))+" "+e._s(e.currencies[e.currentCurrency-1].ISO)+"\n                                    ")]),e._v(" "),s("td",[s("input",{directives:[{name:"model",rawName:"v-model",value:e.usersCash[t.id],expression:"usersCash[item.id]"}],class:["cash-input",!e.isValidCashAmount(e.usersCash[t.id])&&"is-invalid"],attrs:{type:"number",step:"1",min:"0"},domProps:{value:e.usersCash[t.id]},on:{input:function(s){s.target.composing||e.$set(e.usersCash,t.id,s.target.value)}}})]),e._v(" "),s("td",{staticClass:"h5 font-weight-bold"},[e._v("\n                                        "+e._s(e.isValidCashAmount(e.usersCash[t.id])?Math.round(t.value*e.usersCash[t.id]*1e3)/1e3:0)+"\n\t\t\t\t\t\t\t\t\t\t"+e._s(e.currencies[e.currentCurrency-1].ISO)+"\n                                    ")])])})),0)])])]),e._v(" "),s("div",{staticClass:"row h3 font-weight-bold"},[s("div",{staticClass:"col-6 text-right"},[e._v("Sum:")]),e._v(" "),s("div",{staticClass:"col-6 "},[e._v(e._s(e.sumOfCash)+" "+e._s(e.currencies[e.currentCurrency-1].ISO))])]),e._v(" "),!1!==e.currentCashMeanBalance?s("div",[s("div",{staticClass:"row h3 font-weight-bold"},[s("div",{staticClass:"col-6 text-right"},[e._v("Current balance:")]),e._v(" "),s("div",{staticClass:"col-6 "},[e._v(e._s(e.currentCashMeanBalance)+" "+e._s(e.currencies[e.currentCurrency-1].ISO))])]),e._v(" "),s("hr",{staticClass:"hr-dashed w-75"}),e._v(" "),s("div",{class:["row","h3","font-weight-bold",e.currentCashMeanBalance-e.sumOfCash!=0?"text-danger":"text-success"]},[s("div",{staticClass:"col-6 text-right"},[e._v("Balance difference:")]),e._v(" "),s("div",{staticClass:"col-6"},[e._v("\n                            "+e._s(e.currentCashMeanBalance-e.sumOfCash>0?"+":"")+e._s(Math.round(1e3*(e.currentCashMeanBalance-e.sumOfCash),3)/1e3)+"\n                            "+e._s(e.currencies[e.currentCurrency-1].ISO)+"\n                        ")])])]):e._e(),e._v(" "),s("hr",{staticClass:"hr"}),e._v(" "),s("SaveResetChanges",{attrs:{disableSave:e.disableSubmit,spinner:e.saveChangesSpinner},on:{save:e.saveChanges,reset:e.resetChanges}})],1):s("Loading")],1)])}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"card-header-text"},[t("i",{staticClass:"fas fa-money-bill-wave"}),this._v("\n                Cash handling\n            ")])},function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"col-lg-5 d-flex justify-content-lg-end justify-content-start align-items-center"},[t("div",{staticClass:"h5 font-weight-bold m-lg-0"},[this._v("Mean of payment used as cash:")])])},function(){var e=this.$createElement,t=this._self._c||e;return t("thead",[t("th",{staticClass:"h4 font-weight-bold",attrs:{scope:"col"}},[this._v("Face value")]),this._v(" "),t("th",{staticClass:"h4 font-weight-bold",attrs:{scope:"col"}},[this._v("Amount")]),this._v(" "),t("th",{staticClass:"h4 font-weight-bold",attrs:{scope:"col"}},[this._v("Value")])])}],!1,null,null,null);t.default=c.exports},5:function(e,t,s){"use strict";var n={props:{disableAll:{type:Boolean,required:!1,default:!1},disableSave:{type:Boolean,required:!1,default:!1},disableReset:{type:Boolean,required:!1,default:!1},spinner:{type:Boolean,required:!1,default:!1}},methods:{save:function(){this.$emit("save")},reset:function(){this.$emit("reset")}}},a=s(1),r=Object(a.a)(n,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"row"},[s("div",{staticClass:"col-sm-6 my-2 my-sm-0"},[s("button",{staticClass:"big-button-success",attrs:{type:"button",disabled:e.disableAll||e.disableSave||e.spinner},on:{click:e.save}},[e.spinner?s("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):s("div",[e._v("\n                Save changes\n            ")])])]),e._v(" "),s("div",{staticClass:"col-sm-6 my-2 my-sm-0"},[s("button",{staticClass:"big-button-danger",attrs:{type:"button",disabled:e.disableAll||e.disableReset||e.spinner},on:{click:e.reset}},[e._v("\n            Reset changes\n        ")])])])}),[],!1,null,null,null);t.a=r.exports}});
//# sourceMappingURL=cash.js.map