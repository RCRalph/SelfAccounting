!function(t){var e={};function n(s){if(e[s])return e[s].exports;var i=e[s]={i:s,l:!1,exports:{}};return t[s].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=t,n.c=e,n.d=function(t,e,s){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:s})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var s=Object.create(null);if(n.r(s),Object.defineProperty(s,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var i in t)n.d(s,i,function(e){return t[e]}.bind(null,i));return s},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=220)}({1:function(t,e,n){"use strict";function s(t,e,n,s,i,r,a,o){var l,c="function"==typeof t?t.options:t;if(e&&(c.render=e,c.staticRenderFns=n,c._compiled=!0),s&&(c.functional=!0),r&&(c._scopeId="data-v-"+r),a?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},c._ssrRegister=l):i&&(l=o?function(){i.call(this,(c.functional?this.parent:this).$root.$options.shadowRoot)}:i),l)if(c.functional){c._injectStyles=l;var u=c.render;c.render=function(t,e){return l.call(e),u(t,e)}}else{var d=c.beforeCreate;c.beforeCreate=d?[].concat(d,l):[l]}return{exports:t,options:c}}n.d(e,"a",(function(){return s}))},2:function(t,e,n){"use strict";var s=n(1),i=Object(s.a)({},(function(){var t=this.$createElement;this._self._c;return this._m(0)}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"d-flex justify-content-center my-2"},[e("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[e("span",{staticClass:"sr-only"},[this._v("Loading...")])])])}],!1,null,null,null);e.a=i.exports},220:function(t,e,n){t.exports=n(221)},221:function(t,e,n){Vue.component("payment-component",n(260).default);new Vue({el:"#app"})},260:function(t,e,n){"use strict";n.r(e);var s={props:["user","id"],components:{Loading:n(2).a},data:function(){return{ready:!1,bundles:[],selectedBundle:"p-1"}},computed:{price:function(){var t=this;return"string"==typeof this.selectedBundle?"p-12"==this.selectedBundle?15:1.5:this.bundles.filter((function(e){return e.id==t.selectedBundle}))[0].price}},mounted:function(){var t=this;this.selectedBundle=Number(this.id)||"p-1",axios.get("/webapi/payment",{}).then((function(e){t.bundles=e.data.bundles,t.ready=!0}))}},i=n(1),r=Object(i.a)(s,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"card"},[t._m(0),t._v(" "),n("div",{staticClass:"card-body"},[t.ready?n("div",[n("div",{staticClass:"row"},[t._m(1),t._v(" "),n("div",{staticClass:"col-lg-5"},[n("select",{directives:[{name:"model",rawName:"v-model",value:t.selectedBundle,expression:"selectedBundle"}],staticClass:"form-control",on:{change:function(e){var n=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.selectedBundle=e.target.multiple?n:n[0]}}},[n("option",{attrs:{value:"p-1"}},[t._v("Premium - 1 month")]),t._v(" "),n("option",{attrs:{value:"p-12"}},[t._v("Premium - 12 months")]),t._v(" "),t._l(t.bundles,(function(e){return n("option",{key:e.id,domProps:{value:e.id}},[t._v("\n                            "+t._s(e.title)+"\n                        ")])}))],2)])]),t._v(" "),n("hr",{staticClass:"hr"}),t._v(" "),n("div",{staticClass:"form-group row"},[t._m(2),t._v(" "),n("div",{staticClass:"col-lg-5 input-group"},[t._m(3),t._v(" "),n("input",{staticClass:"form-control",attrs:{disabled:"",type:"text"},domProps:{value:Number(t.price).toFixed(2)}})])]),t._v(" "),n("div",{staticClass:"form-group row"},[t._m(4),t._v(" "),n("div",{staticClass:"col-lg-5"},[n("input",{staticClass:"form-control",attrs:{disabled:"",type:"text"},domProps:{value:t.user+":"+t.selectedBundle}})])]),t._v(" "),t._m(5),t._v(" "),t._m(6)]):n("Loading")],1)])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"card-header-flex"},[e("div",{staticClass:"card-header-text"},[e("i",{staticClass:"fas fa-money-check"}),this._v("\n            Payment\n        ")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"col-lg-5 d-flex justify-content-lg-end justify-content-start align-items-center"},[e("div",{staticClass:"h4 font-weight-bold m-lg-0"},[this._v("Package")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"col-lg-5 d-flex justify-content-lg-end justify-content-start align-items-center"},[e("div",{staticClass:"h4 font-weight-bold m-lg-0"},[this._v("Payment amount")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"input-group-prepend"},[e("div",{staticClass:"input-group-text"},[this._v("€")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"col-lg-5 d-flex justify-content-lg-end justify-content-start align-items-center"},[e("div",{staticClass:"h4 font-weight-bold m-lg-0"},[this._v("Payment note")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"row"},[e("div",{staticClass:"col-lg-6 offset-lg-3"},[e("a",{staticClass:"big-button-primary",attrs:{href:"https://www.paypal.me/RCRalph",role:"button",target:"_blank"}},[this._v("Finalize payment")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"row"},[e("div",{staticClass:"col-12 h5 font-weight-bold text-center my-2 text-danger"},[this._v("\n                    Remember to enter the note given above!\n                ")]),this._v(" "),e("div",{staticClass:"col-12 h5 text-center my-2"},[this._v("\n                    The transaction should be finished within 24 hours after receiving the payment.\n                ")])])}],!1,null,null,null);e.default=r.exports}});
//# sourceMappingURL=payment.js.map