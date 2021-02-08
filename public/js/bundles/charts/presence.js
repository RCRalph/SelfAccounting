!function(e){var t={};function n(r){if(t[r])return t[r].exports;var s=t[r]={i:r,l:!1,exports:{}};return e[r].call(s.exports,s,s.exports,n),s.l=!0,s.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var s in e)n.d(r,s,function(t){return e[t]}.bind(null,s));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=219)}({1:function(e,t,n){"use strict";function r(e,t,n,r,s,a,o,i){var c,l="function"==typeof e?e.options:e;if(t&&(l.render=t,l.staticRenderFns=n,l._compiled=!0),r&&(l.functional=!0),a&&(l._scopeId="data-v-"+a),o?(c=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),s&&s.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(o)},l._ssrRegister=c):s&&(c=i?function(){s.call(this,(l.functional?this.parent:this).$root.$options.shadowRoot)}:s),c)if(l.functional){l._injectStyles=c;var u=l.render;l.render=function(e,t){return c.call(t),u(e,t)}}else{var d=l.beforeCreate;l.beforeCreate=d?[].concat(d,c):[c]}return{exports:e,options:l}}n.d(t,"a",(function(){return r}))},2:function(e,t,n){"use strict";var r=n(1),s=Object(r.a)({},(function(){var e=this.$createElement;this._self._c;return this._m(0)}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"d-flex justify-content-center my-2"},[t("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[t("span",{staticClass:"sr-only"},[this._v("Loading...")])])])}],!1,null,null,null);t.a=s.exports},219:function(e,t,n){e.exports=n(220)},220:function(e,t,n){Vue.component("presence-component",n(239).default);new Vue({el:"#app"})},239:function(e,t,n){"use strict";n.r(t);var r=n(2),s=n(7),a=n(3),o=n(8),i=n(5),c={props:{content:{required:!0,type:Object},darkmode:{required:!0,type:Boolean},currencies:{required:!0,type:Array},lastcurrency:{required:!1,type:Number,default:1}},components:{TableHeader:s.a,EmptyPlaceholder:a.a,Slider:o.a,SaveResetChanges:i.a},data:function(){return{header:[{text:"Name",tooltip:"The name of your category"},{text:"Income",tooltip:"Use in income"},{text:"Outcome",tooltip:"Use in outcome"},{text:"Charts",tooltip:"Category present on charts"}],categories:{},categoriesCopy:{},currentCurrency:1,submitted:!1}},methods:{save:function(){var e=this;this.submitted=!0;var t=[];for(var n in this.categories)this.categories[n].forEach((function(e){e.show_on_charts&&t.push(e.id)}));axios.patch("/webapi/bundles/charts/presence/categories",{data:t}).then((function(t){e.categoriesCopy=_.cloneDeep(e.categories),e.submitted=!1})).catch((function(t){console.log(t),e.submitted=!1}))},reset:function(){this.categories=_.cloneDeep(this.categoriesCopy)},refreshTooltip:function(){this.$nextTick((function(){$('[data-toggle="tooltip"]').tooltip()}))}},beforeMount:function(){this.currentCurrency=this.lastcurrency,this.categories=_.cloneDeep(this.content),this.categoriesCopy=_.cloneDeep(this.content)},mounted:function(){this.refreshTooltip()},updated:function(){this.refreshTooltip()}},l=n(1),u=Object(l.a)(c,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{class:e.darkmode?"dark-card":"card"},[n("div",{staticClass:"card-header-flex"},[e._m(0),e._v(" "),n("div",{staticClass:"d-flex"},[n("div",{staticClass:"h4 my-auto mr-3"},[e._v("Currency:")]),e._v(" "),n("select",{directives:[{name:"model",rawName:"v-model",value:e.currentCurrency,expression:"currentCurrency"}],staticClass:"form-control",on:{change:function(t){var n=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.currentCurrency=t.target.multiple?n:n[0]}}},e._l(e.currencies,(function(t){return n("option",{key:t.id,domProps:{value:t.id}},[e._v("\n                    "+e._s(t.ISO)+"\n                ")])})),0)])]),e._v(" "),n("div",{staticClass:"card-body"},[e.content[e.currentCurrency].length?n("div",{staticClass:"table-responsive-lg"},[n("table",{class:["responsive-table-hover",e.darkmode?"table-darkmode":"table-lightmode"]},[n("TableHeader",{attrs:{cells:e.header}}),e._v(" "),n("tbody",e._l(e.categories[e.currentCurrency],(function(t){return n("tr",{key:t.id},[n("th",{attrs:{scope:"row"}},[e._v("\n                            "+e._s(t.name)+"\n                        ")]),e._v(" "),n("td",[n("i",{class:["fas","h3","my-auto",t.income_category?"fa-check-square":"fa-times-circle",t.income_category?"text-success":"text-danger"]})]),e._v(" "),n("td",[n("i",{class:["fas","h3","my-auto",t.outcome_category?"fa-check-square":"fa-times-circle",t.outcome_category?"text-success":"text-danger"]})]),e._v(" "),n("td",[n("Slider",{model:{value:t.show_on_charts,callback:function(n){e.$set(t,"show_on_charts",n)},expression:"category.show_on_charts"}})],1)])})),0)],1)]):n("EmptyPlaceholder"),e._v(" "),n("hr",{class:e.darkmode?"hr-darkmode":"hr-lightmode"}),e._v(" "),n("SaveResetChanges",{attrs:{disableAll:e.submitted,spinner:e.submitted},on:{save:e.save,reset:e.reset}})],1)])}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"card-header-text"},[t("i",{staticClass:"fab fa-buffer"}),this._v("\n            Categories\n        ")])}],!1,null,null,null).exports,d={props:{content:{required:!0,type:Object},darkmode:{required:!0,type:Boolean},currencies:{required:!0,type:Array},lastcurrency:{required:!1,type:Number,default:1}},components:{TableHeader:s.a,EmptyPlaceholder:a.a,Slider:o.a,SaveResetChanges:i.a},data:function(){return{header:[{text:"Name",tooltip:"The name of your mean of payment"},{text:"Income",tooltip:"Use in income"},{text:"Outcome",tooltip:"Use in outcome"},{text:"Charts",tooltip:"Mean of payment present on charts"}],means:{},meansCopy:{},currentCurrency:1,submitted:!1}},methods:{save:function(){var e=this;this.submitted=!0;var t=[];for(var n in this.means)this.means[n].forEach((function(e){e.show_on_charts&&t.push(e.id)}));axios.patch("/webapi/bundles/charts/presence/means-of-payment",{data:t}).then((function(t){e.meansCopy=_.cloneDeep(e.means),e.submitted=!1})).catch((function(t){console.log(t),e.submitted=!1}))},reset:function(){this.means=_.cloneDeep(this.meansCopy)},refreshTooltip:function(){this.$nextTick((function(){$('[data-toggle="tooltip"]').tooltip()}))}},beforeMount:function(){this.currentCurrency=this.lastcurrency,this.means=_.cloneDeep(this.content),this.meansCopy=_.cloneDeep(this.content)},mounted:function(){this.refreshTooltip()},updated:function(){this.refreshTooltip()}},m=Object(l.a)(d,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{class:e.darkmode?"dark-card":"card"},[n("div",{staticClass:"card-header-flex"},[e._m(0),e._v(" "),n("div",{staticClass:"d-flex"},[n("div",{staticClass:"h4 my-auto mr-3"},[e._v("Currency:")]),e._v(" "),n("select",{directives:[{name:"model",rawName:"v-model",value:e.currentCurrency,expression:"currentCurrency"}],staticClass:"form-control",on:{change:function(t){var n=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.currentCurrency=t.target.multiple?n:n[0]}}},e._l(e.currencies,(function(t){return n("option",{key:t.id,domProps:{value:t.id}},[e._v("\n                    "+e._s(t.ISO)+"\n                ")])})),0)])]),e._v(" "),n("div",{staticClass:"card-body"},[e.content[e.currentCurrency].length?n("div",{staticClass:"table-responsive-lg"},[n("table",{class:["responsive-table-hover",e.darkmode?"table-darkmode":"table-lightmode"]},[n("TableHeader",{attrs:{cells:e.header}}),e._v(" "),n("tbody",e._l(e.means[e.currentCurrency],(function(t){return n("tr",{key:t.id},[n("th",{attrs:{scope:"row"}},[e._v("\n                            "+e._s(t.name)+"\n                        ")]),e._v(" "),n("td",[n("i",{class:["fas","h3","my-auto",t.income_mean?"fa-check-square":"fa-times-circle",t.income_mean?"text-success":"text-danger"]})]),e._v(" "),n("td",[n("i",{class:["fas","h3","my-auto",t.outcome_mean?"fa-check-square":"fa-times-circle",t.outcome_mean?"text-success":"text-danger"]})]),e._v(" "),n("td",[n("Slider",{model:{value:t.show_on_charts,callback:function(n){e.$set(t,"show_on_charts",n)},expression:"mean.show_on_charts"}})],1)])})),0)],1)]):n("EmptyPlaceholder"),e._v(" "),n("hr",{class:e.darkmode?"hr-darkmode":"hr-lightmode"}),e._v(" "),n("SaveResetChanges",{attrs:{disableAll:e.submitted,spinner:e.submitted},on:{save:e.save,reset:e.reset}})],1)])}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"card-header-text"},[t("i",{staticClass:"fab fa-buffer"}),this._v("\n            Means of payment\n        ")])}],!1,null,null,null).exports,h={components:{Loading:r.a,CategoriesComponent:u,MeansOfPaymentComponent:m},data:function(){return{darkmode:!1,ready:!1,currencies:[],categories:{},means:{},currency:1}},beforeMount:function(){this.darkmode=document.getElementById("darkmode-status").innerHTML.includes("1")},mounted:function(){var e=this;axios.get("/webapi/bundles/charts/presence",{}).then((function(t){var n=t.data;e.currencies=n.currencies,e.categories=n.categories,e.means=n.means,e.currency=n.lastCurrency,e.ready=!0}))},beforeUpdate:function(){this.darkmode=document.getElementById("darkmode-status").innerHTML.includes("1")}},f=Object(l.a)(h,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{class:e.darkmode?"dark-card":"card"},[e._m(0),e._v(" "),n("div",{staticClass:"card-body"},[e.ready?n("div",[n("CategoriesComponent",{attrs:{darkmode:e.darkmode,content:e.categories,currencies:e.currencies,lastcurrency:e.currency}}),e._v(" "),n("MeansOfPaymentComponent",{staticClass:"mt-3",attrs:{darkmode:e.darkmode,content:e.means,currencies:e.currencies,currency:e.currency}})],1):n("Loading")],1)])}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"card-header-flex"},[t("div",{staticClass:"card-header-text"},[t("i",{staticClass:"fas fa-chart-bar"}),this._v("\n            Chart presence\n        ")])])}],!1,null,null,null);t.default=f.exports},3:function(e,t,n){"use strict";var r=n(1),s=Object(r.a)({},(function(){var e=this.$createElement;this._self._c;return this._m(0)}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",[t("h1",{staticClass:"text-center"},[this._v("Nothing to see here, for now...")])])}],!1,null,null,null);t.a=s.exports},5:function(e,t,n){"use strict";var r={props:{disableAll:{type:Boolean,required:!1,default:!1},disableSave:{type:Boolean,required:!1,default:!1},disableReset:{type:Boolean,required:!1,default:!1},spinner:{type:Boolean,required:!1,default:!1}},methods:{save:function(){this.$emit("save")},reset:function(){this.$emit("reset")}}},s=n(1),a=Object(s.a)(r,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"row"},[n("div",{staticClass:"col-sm-6 my-2 my-sm-0"},[n("button",{staticClass:"big-button-success",attrs:{type:"button",disabled:e.disableAll||e.disableSave||e.spinner},on:{click:e.save}},[e.spinner?n("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):n("div",[e._v("\n                Save changes\n            ")])])]),e._v(" "),n("div",{staticClass:"col-sm-6 my-2 my-sm-0"},[n("button",{staticClass:"big-button-danger",attrs:{type:"button",disabled:e.disableAll||e.disableReset||e.spinner},on:{click:e.reset}},[e._v("\n            Reset changes\n        ")])])])}),[],!1,null,null,null);t.a=a.exports},7:function(e,t,n){"use strict";var r={props:{cells:Array}},s=n(1),a=Object(s.a)(r,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("thead",[n("tr",e._l(e.cells,(function(t,r){return n("th",{key:r,class:t.text&&"h5 font-weight-bold",attrs:{scope:t.text&&"col","data-toggle":t.tooltip&&"tooltip","data-placement":t.tooltip&&"bottom",title:t.tooltip}},[e._v("\n            "+e._s(t.text)+"\n        ")])})),0)])}),[],!1,null,null,null);t.a=a.exports},8:function(e,t,n){"use strict";var r={props:{value:Boolean,disabled:Boolean,name:String},methods:{emitEvents:function(e){this.$emit("input",e.currentTarget.checked),this.$emit("htmlElement",e.currentTarget)}}},s=n(1),a=Object(s.a)(r,(function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"slider-checkbox"},[t("label",{staticClass:"switch m-0"},[t("input",{attrs:{type:"checkbox",disabled:this.disabled,name:this.name},domProps:{checked:this.value},on:{change:this.emitEvents}}),this._v(" "),t("span",{staticClass:"slider round"})])])}),[],!1,null,null,null);t.a=a.exports}});
//# sourceMappingURL=presence.js.map