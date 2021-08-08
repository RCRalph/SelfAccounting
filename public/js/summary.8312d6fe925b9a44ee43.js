(()=>{var t={4711:(t,e,r)=>{"use strict";r.d(e,{Z:()=>c});function n(t,e,r,n,s,a,c,i){var o,l="function"==typeof t?t.options:t;if(e&&(l.render=e,l.staticRenderFns=r,l._compiled=!0),n&&(l.functional=!0),a&&(l._scopeId="data-v-"+a),c?(o=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),s&&s.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(c)},l._ssrRegister=o):s&&(o=i?function(){s.call(this,(l.functional?this.parent:this).$root.$options.shadowRoot)}:s),o)if(l.functional){l._injectStyles=o;var u=l.render;l.render=function(t,e){return o.call(e),u(t,e)}}else{var d=l.beforeCreate;l.beforeCreate=d?[].concat(d,o):[o]}return{exports:t,options:l}}const s=n({},(function(){var t=this,e=t.$createElement;t._self._c;return t._m(0)}),[function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("h1",{staticClass:"text-center"},[t._v("Nothing to see here, for now...")])])}],!1,null,null,null).exports;var a=n({},(function(){var t=this,e=t.$createElement;t._self._c;return t._m(0)}),[function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"d-flex justify-content-center my-2"},[r("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[r("span",{staticClass:"sr-only"},[t._v("Loading...")])])])}],!1,null,null,null);const c=n({components:{EmptyPlaceholder:s,Loading:a.exports},data:function(){return{ready:!1,content:{},currencies:[],currentCurrency:1}},computed:{sum:function(){return this.content[this.currentCurrency]?this.content[this.currentCurrency].map((function(t){return t.balance})).reduce((function(t,e){return t+e})):0},availableCurrencies:function(){var t=[];for(var e in this.currencies)this.content[this.currencies[e].id]&&t.push(this.currencies[e]);return t}},filters:{addSpaces:function(t){return t.toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2}).split(",").join(" ")}},mounted:function(){var t=this;axios.get("/webapi/summary",{}).then((function(e){t.content=e.data.finalData,t.currencies=e.data.currencies,t.currentCurrency=e.data.lastCurrency,t.ready=!0}))}},(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"card"},[r("div",{staticClass:"card-header-flex"},[t._m(0),t._v(" "),t.ready&&t.availableCurrencies.length>1?r("div",{staticClass:"d-flex"},[r("div",{staticClass:"h4 my-auto me-3"},[t._v("Currency:")]),t._v(" "),r("select",{directives:[{name:"model",rawName:"v-model",value:t.currentCurrency,expression:"currentCurrency"}],staticClass:"form-control",on:{change:function(e){var r=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.currentCurrency=e.target.multiple?r:r[0]}}},t._l(t.availableCurrencies,(function(e){return r("option",{key:e.id,domProps:{value:e.id}},[t._v("\n                    "+t._s(e.ISO)+"\n                ")])})),0)]):t._e()]),t._v(" "),r("div",{staticClass:"card-body"},[t.ready&&t.content[t.currentCurrency]?r("div",[r("div",{staticClass:"row"},[r("div",{staticClass:"mx-auto mb-3 col-md-12 col-lg-8 offset-lg-2"},[r("div",{staticClass:"card"},[t._m(1),t._v(" "),r("div",{staticClass:"card-body text-center h2 my-auto"},[t._v(t._s(t._f("addSpaces")(t.sum))+" "+t._s(t.currencies[t.currentCurrency-1].ISO))])])])]),t._v(" "),r("div",{staticClass:"table-responsive w-100"},[r("table",{staticClass:"table-themed responsive-table-hover"},[t._m(2),t._v(" "),r("tbody",t._l(t.content[t.currentCurrency],(function(e,n){return r("tr",{key:n},[r("th",{staticClass:"h5 my-auto fw-bold",attrs:{scope:"row"}},[t._v(t._s(e.name))]),t._v(" "),r("td",{staticClass:"h5 my-auto"},[t._v(t._s(t._f("addSpaces")(e.balance))+" "+t._s(t.currencies[t.currentCurrency-1].ISO))])])})),0)])])]):t.ready&&!t.content[t.currentCurrency]?r("EmptyPlaceholder"):r("Loading")],1)])}),[function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"card-header-text"},[r("i",{staticClass:"fas fa-file-invoice-dollar"}),t._v("\n            Summary\n        ")])},function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"card-header"},[r("div",{staticClass:"m-auto text-center fw-bold h2"},[t._v("\n                                Sum\n                            ")])])},function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("thead",[r("tr",[r("th",{staticClass:"h3 fw-bold",attrs:{scope:"col"}},[t._v("Mean")]),t._v(" "),r("th",{staticClass:"h3 fw-bold",attrs:{scope:"col"}},[t._v("Balance")])])])}],!1,null,null,null).exports}},e={};function r(n){var s=e[n];if(void 0!==s)return s.exports;var a=e[n]={exports:{}};return t[n](a,a.exports,r),a.exports}r.d=(t,e)=>{for(var n in e)r.o(e,n)&&!r.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:e[n]})},r.o=(t,e)=>Object.prototype.hasOwnProperty.call(t,e),(()=>{Vue.component("summary-component",r(4711).Z);new Vue({el:"#app"})})()})();
//# sourceMappingURL=summary.js.map