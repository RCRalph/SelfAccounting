!function(t){var e={};function n(a){if(e[a])return e[a].exports;var i=e[a]={i:a,l:!1,exports:{}};return t[a].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=t,n.c=e,n.d=function(t,e,a){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:a})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var a=Object.create(null);if(n.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var i in t)n.d(a,i,function(e){return t[e]}.bind(null,i));return a},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=225)}({1:function(t,e,n){"use strict";function a(t,e,n,a,i,r,s,o){var l,u="function"==typeof t?t.options:t;if(e&&(u.render=e,u.staticRenderFns=n,u._compiled=!0),a&&(u.functional=!0),r&&(u._scopeId="data-v-"+r),s?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(s)},u._ssrRegister=l):i&&(l=o?function(){i.call(this,(u.functional?this.parent:this).$root.$options.shadowRoot)}:i),l)if(u.functional){u._injectStyles=l;var c=u.render;u.render=function(t,e){return l.call(e),c(t,e)}}else{var d=u.beforeCreate;u.beforeCreate=d?[].concat(d,l):[l]}return{exports:t,options:u}}n.d(e,"a",(function(){return a}))},152:function(t,e){t.exports=function(t){var e={};function n(a){if(e[a])return e[a].exports;var i=e[a]={i:a,l:!1,exports:{}};return t[a].call(i.exports,i,i.exports,n),i.l=!0,i.exports}return n.m=t,n.c=e,n.d=function(t,e,a){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:a})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var a=Object.create(null);if(n.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var i in t)n.d(a,i,function(e){return t[e]}.bind(null,i));return a},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s="fb15")}({f6fd:function(t,e){!function(t){var e=t.getElementsByTagName("script");"currentScript"in t||Object.defineProperty(t,"currentScript",{get:function(){try{throw new Error}catch(a){var t,n=(/.*at [^\(]*\((.*):.+:.+\)$/gi.exec(a.stack)||[!1])[1];for(t in e)if(e[t].src==n||"interactive"==e[t].readyState)return e[t];return null}}})}(document)},fb15:function(t,e,n){"use strict";var a;(n.r(e),"undefined"!=typeof window)&&(n("f6fd"),(a=window.document.currentScript)&&(a=a.src.match(/(.+\/)[^/]+\.js(\?.*)?$/))&&(n.p=a[1]));function i(t,e,n,a,i,r,s,o){var l,u="function"==typeof t?t.options:t;if(e&&(u.render=e,u.staticRenderFns=n,u._compiled=!0),a&&(u.functional=!0),r&&(u._scopeId="data-v-"+r),s?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(s)},u._ssrRegister=l):i&&(l=o?function(){i.call(this,this.$root.$options.shadowRoot)}:i),l)if(u.functional){u._injectStyles=l;var c=u.render;u.render=function(t,e){return l.call(e),c(t,e)}}else{var d=u.beforeCreate;u.beforeCreate=d?[].concat(d,l):[l]}return{exports:t,options:u}}var r=i({props:{data:{type:Object,default:function(){}},limit:{type:Number,default:0},showDisabled:{type:Boolean,default:!1},size:{type:String,default:"default",validator:function(t){return-1!==["small","default","large"].indexOf(t)}},align:{type:String,default:"left",validator:function(t){return-1!==["left","center","right"].indexOf(t)}}},computed:{isApiResource:function(){return!!this.data.meta},currentPage:function(){return this.isApiResource?this.data.meta.current_page:this.data.current_page},firstPageUrl:function(){return this.isApiResource?this.data.links.first:null},from:function(){return this.isApiResource?this.data.meta.from:this.data.from},lastPage:function(){return this.isApiResource?this.data.meta.last_page:this.data.last_page},lastPageUrl:function(){return this.isApiResource?this.data.links.last:null},nextPageUrl:function(){return this.isApiResource?this.data.links.next:this.data.next_page_url},perPage:function(){return this.isApiResource?this.data.meta.per_page:this.data.per_page},prevPageUrl:function(){return this.isApiResource?this.data.links.prev:this.data.prev_page_url},to:function(){return this.isApiResource?this.data.meta.to:this.data.to},total:function(){return this.isApiResource?this.data.meta.total:this.data.total},pageRange:function(){if(-1===this.limit)return 0;if(0===this.limit)return this.lastPage;for(var t,e=this.currentPage,n=this.lastPage,a=this.limit,i=e-a,r=e+a+1,s=[],o=[],l=1;l<=n;l++)(1===l||l===n||l>=i&&l<r)&&s.push(l);return s.forEach((function(e){t&&(e-t==2?o.push(t+1):e-t!=1&&o.push("...")),o.push(e),t=e})),o}},methods:{previousPage:function(){this.selectPage(this.currentPage-1)},nextPage:function(){this.selectPage(this.currentPage+1)},selectPage:function(t){"..."!==t&&this.$emit("pagination-change-page",t)}},render:function(){var t=this;return this.$scopedSlots.default({data:this.data,limit:this.limit,showDisabled:this.showDisabled,size:this.size,align:this.align,computed:{isApiResource:this.isApiResource,currentPage:this.currentPage,firstPageUrl:this.firstPageUrl,from:this.from,lastPage:this.lastPage,lastPageUrl:this.lastPageUrl,nextPageUrl:this.nextPageUrl,perPage:this.perPage,prevPageUrl:this.prevPageUrl,to:this.to,total:this.total,pageRange:this.pageRange},prevButtonEvents:{click:function(e){e.preventDefault(),t.previousPage()}},nextButtonEvents:{click:function(e){e.preventDefault(),t.nextPage()}},pageButtonEvents:function(e){return{click:function(n){n.preventDefault(),t.selectPage(e)}}}})}},void 0,void 0,!1,null,null,null).exports,s=i({props:{data:{type:Object,default:function(){}},limit:{type:Number,default:0},showDisabled:{type:Boolean,default:!1},size:{type:String,default:"default",validator:function(t){return-1!==["small","default","large"].indexOf(t)}},align:{type:String,default:"left",validator:function(t){return-1!==["left","center","right"].indexOf(t)}}},methods:{onPaginationChangePage:function(t){this.$emit("pagination-change-page",t)}},components:{RenderlessLaravelVuePagination:r}},(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("renderless-laravel-vue-pagination",{attrs:{data:t.data,limit:t.limit,"show-disabled":t.showDisabled,size:t.size,align:t.align},on:{"pagination-change-page":t.onPaginationChangePage},scopedSlots:t._u([{key:"default",fn:function(e){e.data,e.limit;var a=e.showDisabled,i=e.size,r=e.align,s=e.computed,o=e.prevButtonEvents,l=e.nextButtonEvents,u=e.pageButtonEvents;return s.total>s.perPage?n("ul",{staticClass:"pagination",class:{"pagination-sm":"small"==i,"pagination-lg":"large"==i,"justify-content-center":"center"==r,"justify-content-end":"right"==r}},[s.prevPageUrl||a?n("li",{staticClass:"page-item pagination-prev-nav",class:{disabled:!s.prevPageUrl}},[n("a",t._g({staticClass:"page-link",attrs:{href:"#","aria-label":"Previous",tabindex:!s.prevPageUrl&&-1}},o),[t._t("prev-nav",[n("span",{attrs:{"aria-hidden":"true"}},[t._v("«")]),n("span",{staticClass:"sr-only"},[t._v("Previous")])])],2)]):t._e(),t._l(s.pageRange,(function(e,a){return n("li",{key:a,staticClass:"page-item pagination-page-nav",class:{active:e==s.currentPage}},[n("a",t._g({staticClass:"page-link",attrs:{href:"#"}},u(e)),[t._v("\n                "+t._s(e)+"\n                "),e==s.currentPage?n("span",{staticClass:"sr-only"},[t._v("(current)")]):t._e()])])})),s.nextPageUrl||a?n("li",{staticClass:"page-item pagination-next-nav",class:{disabled:!s.nextPageUrl}},[n("a",t._g({staticClass:"page-link",attrs:{href:"#","aria-label":"Next",tabindex:!s.nextPageUrl&&-1}},l),[t._t("next-nav",[n("span",{attrs:{"aria-hidden":"true"}},[t._v("»")]),n("span",{staticClass:"sr-only"},[t._v("Next")])])],2)]):t._e()],2):t._e()}}],null,!0)})}),[],!1,null,null,null).exports;e.default=s}}).default},2:function(t,e,n){"use strict";var a=n(1),i=Object(a.a)({},(function(){var t=this.$createElement;this._self._c;return this._m(0)}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"d-flex justify-content-center my-2"},[e("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[e("span",{staticClass:"sr-only"},[this._v("Loading...")])])])}],!1,null,null,null);e.a=i.exports},225:function(t,e,n){t.exports=n(226)},226:function(t,e,n){Vue.component("user-list-component",n(252).default),Vue.component("pagination",n(152));new Vue({el:"#app"})},252:function(t,e,n){"use strict";n.r(e);var a=n(3),i=n(2),r={components:{EmptyPlaceholder:a.a,Loading:i.a},data:function(){return{darkmode:!1,ready:!1,paginationData:{}}},methods:{getPaginationData:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.ready=!1,axios.get("/webapi/admin/users",{params:{page:e}}).then((function(e){t.paginationData=e.data.users,t.ready=!0}))}},beforeMount:function(){this.darkmode=document.getElementById("darkmode-status").innerHTML.includes("1")},mounted:function(){this.getPaginationData()},beforeUpdate:function(){this.darkmode=document.getElementById("darkmode-status").innerHTML.includes("1")}},s=n(1),o=Object(s.a)(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{class:t.darkmode?"dark-card":"card"},[t._m(0),t._v(" "),n("div",{staticClass:"card-body"},[t.ready?n("div",[t.paginationData.data.length?n("div",[n("div",{staticClass:"table-responsive-xl w-100"},[n("table",{class:["responsive-table-hover",t.darkmode?"table-darkmode":"table-lightmode"]},[t._m(1),t._v(" "),n("tbody",t._l(t.paginationData.data,(function(e,a){return n("tr",{key:e.id,attrs:{id:e.id,index:a}},[n("th",{staticClass:"h5 my-auto font-weight-bold",attrs:{scope:"row"}},[t._v(t._s(e.id))]),t._v(" "),n("td",{staticClass:"h5 my-auto"},[t._v(t._s(e.email))]),t._v(" "),n("td",{staticClass:"h5 my-auto"},[n("a",{staticClass:"big-button-primary",attrs:{role:"button",href:"/admin/users/"+e.id}},[t._v("View details")])])])})),0)])])]):n("EmptyPlaceholder"),t._v(" "),n("div",{staticClass:"d-flex justify-content-center"},[n("pagination",{attrs:{data:t.paginationData},on:{"pagination-change-page":t.getPaginationData}})],1)],1):n("Loading")],1)])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"card-header-flex"},[e("div",{staticClass:"card-header-text"},[e("i",{staticClass:"fas fa-users"}),this._v("\n            Users\n        ")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("thead",[e("th",{staticClass:"h3 font-weight-bold",attrs:{scope:"col"}},[this._v("ID")]),this._v(" "),e("th",{staticClass:"h3 font-weight-bold",attrs:{scope:"col"}},[this._v("Email Address")]),this._v(" "),e("th",{staticClass:"h3 font-weight-bold",attrs:{scope:"col"}},[this._v("Details")])])}],!1,null,null,null);e.default=o.exports},3:function(t,e,n){"use strict";var a=n(1),i=Object(a.a)({},(function(){var t=this.$createElement;this._self._c;return this._m(0)}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",[e("h1",{staticClass:"text-center"},[this._v("Nothing to see here, for now...")])])}],!1,null,null,null);e.a=i.exports}});
//# sourceMappingURL=list.js.map