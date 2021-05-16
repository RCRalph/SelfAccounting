!function(t){var e={};function n(r){if(e[r])return e[r].exports;var i=e[r]={i:r,l:!1,exports:{}};return t[r].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var i in t)n.d(r,i,function(e){return t[e]}.bind(null,i));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=223)}({0:function(t,e,n){"use strict";function r(t,e,n,r,i,o,a,u){var s,d="function"==typeof t?t.options:t;if(e&&(d.render=e,d.staticRenderFns=n,d._compiled=!0),r&&(d.functional=!0),o&&(d._scopeId="data-v-"+o),a?(s=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},d._ssrRegister=s):i&&(s=u?function(){i.call(this,(d.functional?this.parent:this).$root.$options.shadowRoot)}:i),s)if(d.functional){d._injectStyles=s;var l=d.render;d.render=function(t,e){return s.call(e),l(t,e)}}else{var c=d.beforeCreate;d.beforeCreate=c?[].concat(c,s):[s]}return{exports:t,options:d}}n.d(e,"a",(function(){return r}))},223:function(t,e,n){t.exports=n(224)},224:function(t,e,n){Vue.component("bundle-toggle",n(271).default),Vue.component("premium-bundle-toggle",n(272).default);new Vue({el:"#app"})},271:function(t,e,n){"use strict";n.r(e);var r={props:["enable","id","directory"],data:function(){return{ready:!0,enabled:!1}},computed:{buttonText:function(){return this.enabled?"Disable":"Enable"}},methods:{changeStatus:function(){var t=this;this.ready&&(this.ready=!1,axios.post("/webapi/bundles/".concat(this.id,"/toggle"),{}).then((function(){t.enabled=!t.enabled,t.enabled?window.location="/bundles/".concat(t.directory):location.reload()})).catch((function(){t.ready=!0})))}},mounted:function(){this.enabled=!!this.enable}},i=n(0),o=Object(i.a)(r,(function(){var t=this.$createElement,e=this._self._c||t;return e("button",{staticClass:"big-button-primary",attrs:{disabled:!this.ready},on:{click:this.changeStatus}},[this.ready?e("div",[this._v("\n        "+this._s(this.buttonText)+"\n    ")]):e("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}})])}),[],!1,null,null,null);e.default=o.exports},272:function(t,e,n){"use strict";n.r(e);var r={props:["premium","enable","id","directory"],data:function(){return{ready:!0,enabled:!1}},computed:{buttonText:function(){return this.premium?this.enabled?"Stop using with Premium":"Start using with Premium":"Start using with premium"}},methods:{changeStatus:function(){var t=this;this.ready&&(this.ready=!1,axios.post("/webapi/bundles/".concat(this.id,"/toggle-premium"),{}).then((function(){t.enabled=!t.enabled,t.enabled?window.location="/bundles/".concat(t.directory):location.reload()})).catch((function(){t.ready=!0})))}},mounted:function(){this.enabled=!!this.enable}},i=n(0),o=Object(i.a)(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("button",{staticClass:"big-button-golden",attrs:{disabled:!t.premium||!t.ready,"data-toggle":!t.premium&&"tooltip","data-placement":!t.premium&&"top",title:!t.premium&&"Buy Premium to use this feature"},on:{click:t.changeStatus}},[t.ready?n("div",[t._v("\n        "+t._s(t.buttonText)+"\n    ")]):n("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}})])}),[],!1,null,null,null);e.default=o.exports}});
//# sourceMappingURL=bundle-show.js.map