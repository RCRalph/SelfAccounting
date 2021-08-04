(()=>{var t={5176:(t,e,n)=>{"use strict";n.d(e,{Z:()=>o});const r={props:["enable","id","directory"],data:function(){return{ready:!0,enabled:!1}},computed:{buttonText:function(){return this.enabled?"Disable":"Enable"}},methods:{changeStatus:function(){var t=this;this.ready&&(this.ready=!1,axios.post("/webapi/bundles/".concat(this.id,"/toggle"),{}).then((function(){t.enabled=!t.enabled,t.enabled?window.location="/bundles/".concat(t.directory):location.reload()})).catch((function(){t.ready=!0})))}},mounted:function(){this.enabled=!!this.enable}};const o=(0,n(1900).Z)(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("button",{staticClass:"big-button-primary",attrs:{disabled:!t.ready},on:{click:t.changeStatus}},[t.ready?n("div",[t._v("\n        "+t._s(t.buttonText)+"\n    ")]):n("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}})])}),[],!1,null,null,null).exports},5780:(t,e,n)=>{"use strict";n.d(e,{Z:()=>o});const r={props:["premium","enable","id","directory"],data:function(){return{ready:!0,enabled:!1}},computed:{buttonText:function(){return this.premium?this.enabled?"Stop using with Premium":"Start using with Premium":"Start using with premium"}},methods:{changeStatus:function(){var t=this;this.ready&&(this.ready=!1,axios.post("/webapi/bundles/".concat(this.id,"/toggle-premium"),{}).then((function(){t.enabled=!t.enabled,t.enabled?window.location="/bundles/".concat(t.directory):location.reload()})).catch((function(){t.ready=!0})))}},mounted:function(){this.enabled=!!this.enable}};const o=(0,n(1900).Z)(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{attrs:{"data-bs-toggle":!t.premium&&"tooltip","data-bs-placement":!t.premium&&"top",title:!t.premium&&"Buy Premium to use this feature"}},[n("button",{staticClass:"big-button-golden",attrs:{disabled:!t.premium||!t.ready},on:{click:t.changeStatus}},[t.ready?n("div",[t._v("\n            "+t._s(t.buttonText)+"\n        ")]):n("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}})])])}),[],!1,null,null,null).exports},1900:(t,e,n)=>{"use strict";function r(t,e,n,r,o,i,a,s){var d,u="function"==typeof t?t.options:t;if(e&&(u.render=e,u.staticRenderFns=n,u._compiled=!0),r&&(u.functional=!0),i&&(u._scopeId="data-v-"+i),a?(d=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},u._ssrRegister=d):o&&(d=s?function(){o.call(this,(u.functional?this.parent:this).$root.$options.shadowRoot)}:o),d)if(u.functional){u._injectStyles=d;var c=u.render;u.render=function(t,e){return d.call(e),c(t,e)}}else{var l=u.beforeCreate;u.beforeCreate=l?[].concat(l,d):[d]}return{exports:t,options:u}}n.d(e,{Z:()=>r})}},e={};function n(r){var o=e[r];if(void 0!==o)return o.exports;var i=e[r]={exports:{}};return t[r](i,i.exports,n),i.exports}n.d=(t,e)=>{for(var r in e)n.o(e,r)&&!n.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:e[r]})},n.o=(t,e)=>Object.prototype.hasOwnProperty.call(t,e),(()=>{Vue.component("bundle-toggle",n(5176).Z),Vue.component("premium-bundle-toggle",n(5780).Z);new Vue({el:"#app"})})()})();
//# sourceMappingURL=bundle-show.js.map