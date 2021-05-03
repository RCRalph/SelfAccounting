!function(t){var e={};function n(r){if(e[r])return e[r].exports;var s=e[r]={i:r,l:!1,exports:{}};return t[r].call(s.exports,s,s.exports,n),s.l=!0,s.exports}n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var s in t)n.d(r,s,function(e){return t[e]}.bind(null,s));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=214)}({1:function(t,e,n){"use strict";function r(t,e,n,r,s,a,c,i){var o,u="function"==typeof t?t.options:t;if(e&&(u.render=e,u.staticRenderFns=n,u._compiled=!0),r&&(u.functional=!0),a&&(u._scopeId="data-v-"+a),c?(o=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),s&&s.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(c)},u._ssrRegister=o):s&&(o=i?function(){s.call(this,(u.functional?this.parent:this).$root.$options.shadowRoot)}:s),o)if(u.functional){u._injectStyles=o;var l=u.render;u.render=function(t,e){return o.call(e),l(t,e)}}else{var d=u.beforeCreate;u.beforeCreate=d?[].concat(d,o):[o]}return{exports:t,options:u}}n.d(e,"a",(function(){return r}))},2:function(t,e,n){"use strict";var r=n(1),s=Object(r.a)({},(function(){var t=this.$createElement;this._self._c;return this._m(0)}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"d-flex justify-content-center my-2"},[e("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[e("span",{staticClass:"sr-only"},[this._v("Loading...")])])])}],!1,null,null,null);e.a=s.exports},214:function(t,e,n){t.exports=n(215)},215:function(t,e,n){Vue.component("summary-component",n(257).default);new Vue({el:"#app"})},257:function(t,e,n){"use strict";n.r(e);var r=n(3),s=n(2),a={components:{EmptyPlaceholder:r.a,Loading:s.a},data:function(){return{ready:!1,content:{},currencies:[],currentCurrency:1}},computed:{sum:function(){return this.content[this.currentCurrency].map((function(t){return t.balance})).reduce((function(t,e){return t+e}))}},filters:{addSpaces:function(t){return t.toLocaleString("en").split(",").join(" ")}},mounted:function(){var t=this;axios.get("/webapi/summary",{}).then((function(e){t.content=e.data.finalData,t.currencies=e.data.currencies,t.currentCurrency=e.data.lastCurrency,t.ready=!0}))}},c=n(1),i=Object(c.a)(a,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"card"},[n("div",{staticClass:"card-header-flex"},[t._m(0),t._v(" "),t.ready?n("div",{staticClass:"d-flex"},[n("div",{staticClass:"h4 my-auto mr-3"},[t._v("Currency:")]),t._v(" "),n("select",{directives:[{name:"model",rawName:"v-model",value:t.currentCurrency,expression:"currentCurrency"}],staticClass:"form-control",on:{change:function(e){var n=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.currentCurrency=e.target.multiple?n:n[0]}}},t._l(t.currencies,(function(e){return n("option",{key:e.id,domProps:{value:e.id}},[t._v("\n                    "+t._s(e.ISO)+"\n                ")])})),0)]):t._e()]),t._v(" "),n("div",{staticClass:"card-body"},[t.ready&&t.content[t.currentCurrency]?n("div",[n("div",{staticClass:"row"},[n("div",{staticClass:"mx-auto mb-3 col-md-12 col-lg-8 offset-lg-2"},[n("div",{staticClass:"card"},[t._m(1),t._v(" "),n("div",{staticClass:"card-body text-center h2 my-auto"},[t._v(t._s(t._f("addSpaces")(t.sum))+" "+t._s(t.currencies[t.currentCurrency-1].ISO))])])])]),t._v(" "),n("div",{staticClass:"table-responsive-xl w-100"},[n("table",{staticClass:"responsive-table-hover table-themed"},[t._m(2),t._v(" "),n("tbody",t._l(t.content[t.currentCurrency],(function(e,r){return n("tr",{key:r},[n("th",{staticClass:"h5 my-auto font-weight-bold",attrs:{scope:"row"}},[t._v(t._s(e.name))]),t._v(" "),n("td",{staticClass:"h5 my-auto"},[t._v(t._s(t._f("addSpaces")(Math.round(100*e.balance)/100))+" "+t._s(t.currencies[t.currentCurrency-1].ISO))])])})),0)])])]):t.ready&&!t.content[t.currentCurrency]?n("EmptyPlaceholder"):n("Loading")],1)])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"card-header-text"},[e("i",{staticClass:"fas fa-file-invoice-dollar"}),this._v("\n            Summary\n        ")])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"card-header"},[e("div",{staticClass:"m-auto text-center font-weight-bold h2"},[this._v("\n                                Sum\n                            ")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("thead",[e("tr",[e("th",{staticClass:"h3 font-weight-bold",attrs:{scope:"col"}},[this._v("Type")]),this._v(" "),e("th",{staticClass:"h3 font-weight-bold",attrs:{scope:"col"}},[this._v("Balance")])])])}],!1,null,null,null);e.default=i.exports},3:function(t,e,n){"use strict";var r=n(1),s=Object(r.a)({},(function(){var t=this.$createElement;this._self._c;return this._m(0)}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",[e("h1",{staticClass:"text-center"},[this._v("Nothing to see here, for now...")])])}],!1,null,null,null);e.a=s.exports}});
//# sourceMappingURL=summary.js.map