(()=>{var t={1606:t=>{t.exports=function(t){var e={};function a(s){if(e[s])return e[s].exports;var r=e[s]={i:s,l:!1,exports:{}};return t[s].call(r.exports,r,r.exports,a),r.l=!0,r.exports}return a.m=t,a.c=e,a.d=function(t,e,s){a.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:s})},a.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},a.t=function(t,e){if(1&e&&(t=a(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var s=Object.create(null);if(a.r(s),Object.defineProperty(s,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)a.d(s,r,function(e){return t[e]}.bind(null,r));return s},a.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return a.d(e,"a",e),e},a.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},a.p="",a(a.s="fb15")}({f6fd:function(t,e){!function(t){var e="currentScript",a=t.getElementsByTagName("script");e in t||Object.defineProperty(t,e,{get:function(){try{throw new Error}catch(s){var t,e=(/.*at [^\(]*\((.*):.+:.+\)$/gi.exec(s.stack)||[!1])[1];for(t in a)if(a[t].src==e||"interactive"==a[t].readyState)return a[t];return null}}})}(document)},fb15:function(t,e,a){"use strict";var s;(a.r(e),"undefined"!=typeof window)&&(a("f6fd"),(s=window.document.currentScript)&&(s=s.src.match(/(.+\/)[^/]+\.js(\?.*)?$/))&&(a.p=s[1]));function r(t,e,a,s,r,n,i,o){var l,c="function"==typeof t?t.options:t;if(e&&(c.render=e,c.staticRenderFns=a,c._compiled=!0),s&&(c.functional=!0),n&&(c._scopeId="data-v-"+n),i?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),r&&r.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(i)},c._ssrRegister=l):r&&(l=o?function(){r.call(this,this.$root.$options.shadowRoot)}:r),l)if(c.functional){c._injectStyles=l;var u=c.render;c.render=function(t,e){return l.call(e),u(t,e)}}else{var d=c.beforeCreate;c.beforeCreate=d?[].concat(d,l):[l]}return{exports:t,options:c}}var n=r({props:{data:{type:Object,default:function(){}},limit:{type:Number,default:0},showDisabled:{type:Boolean,default:!1},size:{type:String,default:"default",validator:function(t){return-1!==["small","default","large"].indexOf(t)}},align:{type:String,default:"left",validator:function(t){return-1!==["left","center","right"].indexOf(t)}}},computed:{isApiResource:function(){return!!this.data.meta},currentPage:function(){return this.isApiResource?this.data.meta.current_page:this.data.current_page},firstPageUrl:function(){return this.isApiResource?this.data.links.first:null},from:function(){return this.isApiResource?this.data.meta.from:this.data.from},lastPage:function(){return this.isApiResource?this.data.meta.last_page:this.data.last_page},lastPageUrl:function(){return this.isApiResource?this.data.links.last:null},nextPageUrl:function(){return this.isApiResource?this.data.links.next:this.data.next_page_url},perPage:function(){return this.isApiResource?this.data.meta.per_page:this.data.per_page},prevPageUrl:function(){return this.isApiResource?this.data.links.prev:this.data.prev_page_url},to:function(){return this.isApiResource?this.data.meta.to:this.data.to},total:function(){return this.isApiResource?this.data.meta.total:this.data.total},pageRange:function(){if(-1===this.limit)return 0;if(0===this.limit)return this.lastPage;for(var t,e=this.currentPage,a=this.lastPage,s=this.limit,r=e-s,n=e+s+1,i=[],o=[],l=1;l<=a;l++)(1===l||l===a||l>=r&&l<n)&&i.push(l);return i.forEach((function(e){t&&(e-t==2?o.push(t+1):e-t!=1&&o.push("...")),o.push(e),t=e})),o}},methods:{previousPage:function(){this.selectPage(this.currentPage-1)},nextPage:function(){this.selectPage(this.currentPage+1)},selectPage:function(t){"..."!==t&&this.$emit("pagination-change-page",t)}},render:function(){var t=this;return this.$scopedSlots.default({data:this.data,limit:this.limit,showDisabled:this.showDisabled,size:this.size,align:this.align,computed:{isApiResource:this.isApiResource,currentPage:this.currentPage,firstPageUrl:this.firstPageUrl,from:this.from,lastPage:this.lastPage,lastPageUrl:this.lastPageUrl,nextPageUrl:this.nextPageUrl,perPage:this.perPage,prevPageUrl:this.prevPageUrl,to:this.to,total:this.total,pageRange:this.pageRange},prevButtonEvents:{click:function(e){e.preventDefault(),t.previousPage()}},nextButtonEvents:{click:function(e){e.preventDefault(),t.nextPage()}},pageButtonEvents:function(e){return{click:function(a){a.preventDefault(),t.selectPage(e)}}}})}},undefined,undefined,!1,null,null,null).exports,i=r({props:{data:{type:Object,default:function(){}},limit:{type:Number,default:0},showDisabled:{type:Boolean,default:!1},size:{type:String,default:"default",validator:function(t){return-1!==["small","default","large"].indexOf(t)}},align:{type:String,default:"left",validator:function(t){return-1!==["left","center","right"].indexOf(t)}}},methods:{onPaginationChangePage:function(t){this.$emit("pagination-change-page",t)}},components:{RenderlessLaravelVuePagination:n}},(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("renderless-laravel-vue-pagination",{attrs:{data:t.data,limit:t.limit,"show-disabled":t.showDisabled,size:t.size,align:t.align},on:{"pagination-change-page":t.onPaginationChangePage},scopedSlots:t._u([{key:"default",fn:function(e){e.data,e.limit;var s=e.showDisabled,r=e.size,n=e.align,i=e.computed,o=e.prevButtonEvents,l=e.nextButtonEvents,c=e.pageButtonEvents;return i.total>i.perPage?a("ul",{staticClass:"pagination",class:{"pagination-sm":"small"==r,"pagination-lg":"large"==r,"justify-content-center":"center"==n,"justify-content-end":"right"==n}},[i.prevPageUrl||s?a("li",{staticClass:"page-item pagination-prev-nav",class:{disabled:!i.prevPageUrl}},[a("a",t._g({staticClass:"page-link",attrs:{href:"#","aria-label":"Previous",tabindex:!i.prevPageUrl&&-1}},o),[t._t("prev-nav",[a("span",{attrs:{"aria-hidden":"true"}},[t._v("«")]),a("span",{staticClass:"sr-only"},[t._v("Previous")])])],2)]):t._e(),t._l(i.pageRange,(function(e,s){return a("li",{key:s,staticClass:"page-item pagination-page-nav",class:{active:e==i.currentPage}},[a("a",t._g({staticClass:"page-link",attrs:{href:"#"}},c(e)),[t._v("\n                "+t._s(e)+"\n                "),e==i.currentPage?a("span",{staticClass:"sr-only"},[t._v("(current)")]):t._e()])])})),i.nextPageUrl||s?a("li",{staticClass:"page-item pagination-next-nav",class:{disabled:!i.nextPageUrl}},[a("a",t._g({staticClass:"page-link",attrs:{href:"#","aria-label":"Next",tabindex:!i.nextPageUrl&&-1}},l),[t._t("next-nav",[a("span",{attrs:{"aria-hidden":"true"}},[t._v("»")]),a("span",{staticClass:"sr-only"},[t._v("Next")])])],2)]):t._e()],2):t._e()}}],null,!0)})}),[],!1,null,null,null).exports;e.default=i}}).default},9659:(t,e,a)=>{"use strict";a.d(e,{Z:()=>i});function s(t,e,a,s,r,n,i,o){var l,c="function"==typeof t?t.options:t;if(e&&(c.render=e,c.staticRenderFns=a,c._compiled=!0),s&&(c.functional=!0),n&&(c._scopeId="data-v-"+n),i?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),r&&r.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(i)},c._ssrRegister=l):r&&(l=o?function(){r.call(this,(c.functional?this.parent:this).$root.$options.shadowRoot)}:r),l)if(c.functional){c._injectStyles=l;var u=c.render;c.render=function(t,e){return l.call(e),u(t,e)}}else{var d=c.beforeCreate;c.beforeCreate=d?[].concat(d,l):[l]}return{exports:t,options:c}}const r=s({},(function(){var t=this,e=t.$createElement;t._self._c;return t._m(0)}),[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"d-flex justify-content-center my-2"},[a("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[a("span",{staticClass:"sr-only"},[t._v("Loading...")])])])}],!1,null,null,null).exports;var n=s({},(function(){var t=this,e=t.$createElement;t._self._c;return t._m(0)}),[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("h1",{staticClass:"text-center"},[t._v("Nothing to see here, for now...")])])}],!1,null,null,null);const i=s({components:{Loading:r,EmptyPlaceholder:n.exports},data:function(){return{ready:!1,userReports:{},userReportsReady:!1,sharedReports:{},sharedReportsReady:!1}},methods:{getUserReports:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.userReportsReady=!1,axios.get("/webapi/bundles/reports/user-reports",{params:{page:e}}).then((function(e){t.userReports=e.data.reports,t.userReportsReady=!0,t.sharedReportsReady&&(t.ready=!0)})).catch((function(t){console.error(t)}))},getSharedReports:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.sharedReportsReady=!1,axios.get("/webapi/bundles/reports/shared-reports",{params:{page:e}}).then((function(e){t.sharedReports=e.data.reports,t.sharedReportsReady=!0,t.userReportsReady&&(t.ready=!0)})).catch((function(t){console.error(t)}))}},mounted:function(){this.getUserReports(),this.getSharedReports()}},(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"card"},[a("div",{staticClass:"card-header-flex"},[t._m(0),t._v(" "),t.ready?a("div",{staticClass:"d-flex"},[a("a",{staticClass:"big-button-primary",attrs:{role:"button",href:"/bundles/reports/create"}},[t._v("\n                New Report\n            ")])]):t._e()]),t._v(" "),a("div",{staticClass:"card-body"},[t.ready?a("div",[t.userReports.total?a("div",{staticClass:"card"},[t._m(1),t._v(" "),a("div",{staticClass:"card-body"},[t.userReportsReady?a("div",{staticClass:"table-responsive w-100"},[a("table",{staticClass:"table-themed responsive-table-hover"},[t._m(2),t._v(" "),a("tbody",t._l(t.userReports.data,(function(e,s){return a("tr",{key:e.id,attrs:{id:e.id,index:s}},[a("th",{staticClass:"h5 my-auto fw-bold",attrs:{scope:"row"}},[t._v(t._s(e.id))]),t._v(" "),a("td",{staticClass:"h5 my-auto"},[t._v(t._s(e.title))]),t._v(" "),a("td",{staticClass:"h5 my-auto"},[a("a",{staticClass:"big-button-primary",attrs:{role:"button",href:"/bundles/reports/"+e.id}},[t._v("View report")])]),t._v(" "),a("td",{staticClass:"h5 my-auto"},[a("a",{staticClass:"big-button-primary",attrs:{role:"button",href:"/bundles/reports/"+e.id+"/edit"}},[t._v("Edit report")])])])})),0)]),t._v(" "),a("div",{staticClass:"d-flex justify-content-center"},[a("pagination",{attrs:{data:t.userReports},on:{"pagination-change-page":t.getUserReports}})],1)]):a("Loading")],1)]):t._e(),t._v(" "),t.userReports.total&&t.sharedReports.total?a("hr",{staticClass:"hr"}):t._e(),t._v(" "),t.sharedReports.total?a("div",{staticClass:"card"},[t._m(3),t._v(" "),a("div",{staticClass:"card-body"},[t.sharedReportsReady?a("div",[a("div",{staticClass:"table-responsive w-100"},[a("table",{staticClass:"table-themed responsive-table-hover"},[t._m(4),t._v(" "),a("tbody",t._l(t.sharedReports.data,(function(e,s){return a("tr",{key:e.id,attrs:{index:s}},[a("th",{staticClass:"h5 my-auto fw-bold",attrs:{scope:"row"}},[t._v(t._s(e.id))]),t._v(" "),a("td",{staticClass:"h5 my-auto"},[t._v(t._s(e.title))]),t._v(" "),a("td",{staticClass:"h5 my-auto"},[a("a",{staticClass:"big-button-primary",attrs:{role:"button",href:"/bundles/reports/"+e.id}},[t._v("View report")])])])})),0)])]),t._v(" "),a("div",{staticClass:"d-flex justify-content-center"},[a("pagination",{attrs:{data:t.sharedReports},on:{"pagination-change-page":t.getSharedReports}})],1)]):a("Loading")],1)]):t._e(),t._v(" "),t.sharedReports.total||t.userReports.total?t._e():a("EmptyPlaceholder")],1):a("Loading")],1)])}),[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"card-header-text"},[a("i",{staticClass:"fas fa-newspaper"}),t._v("\n            Reports\n        ")])},function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"card-header-flex"},[a("div",{staticClass:"card-header-text"},[a("i",{staticClass:"fas fa-file-alt"}),t._v("\n                        My reports\n                    ")])])},function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("thead",[a("tr",[a("th",{staticClass:"h3 fw-bold",attrs:{scope:"col"}},[t._v("ID")]),t._v(" "),a("th",{staticClass:"h3 fw-bold",attrs:{scope:"col"}},[t._v("Title")]),t._v(" "),a("th",{staticClass:"h3 fw-bold",attrs:{scope:"col"}},[t._v("View")]),t._v(" "),a("th",{staticClass:"h3 fw-bold",attrs:{scope:"col"}},[t._v("Edit")])])])},function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"card-header-flex"},[a("div",{staticClass:"card-header-text"},[a("i",{staticClass:"fas fa-share-alt"}),t._v("\n                        Reports shared with me\n                    ")])])},function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("thead",[a("tr",[a("th",{staticClass:"h3 fw-bold",attrs:{scope:"col"}},[t._v("ID")]),t._v(" "),a("th",{staticClass:"h3 fw-bold",attrs:{scope:"col"}},[t._v("Title")]),t._v(" "),a("th",{staticClass:"h3 fw-bold",attrs:{scope:"col"}},[t._v("View")])])])}],!1,null,null,null).exports}},e={};function a(s){var r=e[s];if(void 0!==r)return r.exports;var n=e[s]={exports:{}};return t[s](n,n.exports,a),n.exports}a.d=(t,e)=>{for(var s in e)a.o(e,s)&&!a.o(t,s)&&Object.defineProperty(t,s,{enumerable:!0,get:e[s]})},a.o=(t,e)=>Object.prototype.hasOwnProperty.call(t,e),(()=>{Vue.component("reports-component",a(9659).Z),Vue.component("pagination",a(1606));new Vue({el:"#app"})})()})();
//# sourceMappingURL=all.js.map