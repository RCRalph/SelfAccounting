!function(e){var t={};function n(a){if(t[a])return t[a].exports;var r=t[a]={i:a,l:!1,exports:{}};return e[a].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,a){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:a})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(n.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(a,r,function(t){return e[t]}.bind(null,r));return a},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=233)}({0:function(e,t,n){"use strict";function a(e,t,n,a,r,s,i,c){var o,u="function"==typeof e?e.options:e;if(t&&(u.render=t,u.staticRenderFns=n,u._compiled=!0),a&&(u.functional=!0),s&&(u._scopeId="data-v-"+s),i?(o=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),r&&r.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(i)},u._ssrRegister=o):r&&(o=c?function(){r.call(this,(u.functional?this.parent:this).$root.$options.shadowRoot)}:r),o)if(u.functional){u._injectStyles=o;var d=u.render;u.render=function(e,t){return o.call(t),d(e,t)}}else{var l=u.beforeCreate;u.beforeCreate=l?[].concat(l,o):[o]}return{exports:e,options:u}}n.d(t,"a",(function(){return a}))},2:function(e,t,n){"use strict";var a=n(0),r=Object(a.a)({},(function(){var e=this.$createElement;this._self._c;return this._m(0)}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"d-flex justify-content-center my-2"},[t("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[t("span",{staticClass:"sr-only"},[this._v("Loading...")])])])}],!1,null,null,null);t.a=r.exports},233:function(e,t,n){e.exports=n(234)},234:function(e,t,n){Vue.component("backup-component",n(257).default);new Vue({el:"#app"})},257:function(e,t,n){"use strict";n.r(t);var a=n(2),r={props:["data","currencies","charts"],computed:{header:function(){var e=["Currency","Name","Income","Outcome","Summary","Start","End"];return this.charts&&e.splice(5,0,"Charts"),e}}},s=n(0),i={props:["data","currencies","charts"],computed:{header:function(){var e=["Currency","Name","Income","Outcome","Summary","First entry","Starting balance"];return this.charts&&e.splice(5,0,"Charts"),e}}},c={props:["data","currencies","title","categories","means"],data:function(){return{header:["Date","Title","Amount","Price","Category","Mean"]}}},o={props:["cash","cashMeans","currencies","means"],data:function(){return{headerCash:["Currency","Face Value","Amount"],headerCashMeans:["Currency","Name"]}}};function u(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,a)}return n}function d(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?u(Object(n),!0).forEach((function(t){l(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):u(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function l(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}var _={props:["data","currencies","categories","means"],data:function(){return{header:{reports:["Title","Income addition","Sort dates desc","Calculate sum"],queries:["Report","Type","Min date","Max date","Title","Min amount","Max amount","Min Price","Max Price","Currency","Category","Mean"],additionalEntries:["Report","Date","Title","Amount","Price","Category","Mean"],users:["Report","Email"]}}},computed:{queries:function(){var e=[];return this.data.forEach((function(t){t.queries.forEach((function(n){e.push(d(d({},n),{},{report:t.title}))}))})),e},additionalEntries:function(){var e=[];return this.data.forEach((function(t){t.additionalEntries.forEach((function(n){e.push(d(d({},n),{},{report:t.title}))}))})),e},users:function(){var e=[];return this.data.forEach((function(t){t.users.forEach((function(n){e.push({user:n,report:t.title})}))})),e}},filters:{capitalize:function(e){return e.charAt(0).toUpperCase()+e.slice(1)}}},f={props:["data","currencies","bundles"],components:{CategoriesTableComponent:Object(s.a)(r,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"restore-table"},[n("div",{staticClass:"header"},[e._v("\n        Categories:\n    ")]),e._v(" "),n("div",{staticClass:"restore-table-content"},[n("table",[n("thead",e._l(e.header,(function(t,a){return n("th",{key:a,attrs:{scope:"col"}},[e._v(e._s(t))])})),0),e._v(" "),n("tbody",e._l(e.data,(function(t,a){return n("tr",{key:a},[n("td",{staticClass:"font-weight-bold"},[e._v("\n                        "+e._s(e.currencies.find((function(e){return e.id==t.currency_id})).ISO)+"\n                    ")]),e._v(" "),n("td",[e._v("\n                        "+e._s(t.name)+"\n                    ")]),e._v(" "),n("td",[n("i",{class:["fas","h4","my-auto",t.income_category?"fa-check-square":"fa-times-circle",t.income_category?"text-success":"text-danger"]})]),e._v(" "),n("td",[n("i",{class:["fas","h4","my-auto",t.outcome_category?"fa-check-square":"fa-times-circle",t.outcome_category?"text-success":"text-danger"]})]),e._v(" "),n("td",[n("i",{class:["fas","h4","my-auto",t.count_to_summary?"fa-check-square":"fa-times-circle",t.count_to_summary?"text-success":"text-danger"]})]),e._v(" "),e.charts?n("td",[n("i",{class:["fas","h4","my-auto",t.show_on_charts||void 0===t.show_on_charts?"fa-check-square":"fa-times-circle",t.show_on_charts||void 0===t.show_on_charts?"text-success":"text-danger"]})]):e._e(),e._v(" "),n("td",[e._v("\n                        "+e._s(t.start_date||"N/A")+"\n                    ")]),e._v(" "),n("td",[e._v("\n                        "+e._s(t.end_date||"N/A")+"\n                    ")])])})),0)])])])}),[],!1,null,null,null).exports,MeansTableComponent:Object(s.a)(i,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"restore-table"},[n("div",{staticClass:"header"},[e._v("\n        Means of payment:\n    ")]),e._v(" "),n("div",{staticClass:"restore-table-content"},[n("table",[n("thead",e._l(e.header,(function(t,a){return n("th",{key:a,attrs:{scope:"col"}},[e._v(e._s(t))])})),0),e._v(" "),n("tbody",e._l(e.data,(function(t,a){return n("tr",{key:a},[n("td",{staticClass:"font-weight-bold"},[e._v("\n                        "+e._s(e.currencies.find((function(e){return e.id==t.currency_id})).ISO)+"\n                    ")]),e._v(" "),n("td",[e._v("\n                        "+e._s(t.name)+"\n                    ")]),e._v(" "),n("td",[n("i",{class:["fas","h4","my-auto",t.income_mean?"fa-check-square":"fa-times-circle",t.income_mean?"text-success":"text-danger"]})]),e._v(" "),n("td",[n("i",{class:["fas","h4","my-auto",t.outcome_mean?"fa-check-square":"fa-times-circle",t.outcome_mean?"text-success":"text-danger"]})]),e._v(" "),n("td",[n("i",{class:["fas","h4","my-auto",t.count_to_summary?"fa-check-square":"fa-times-circle",t.count_to_summary?"text-success":"text-danger"]})]),e._v(" "),e.charts?n("td",[n("i",{class:["fas","h4","my-auto",t.show_on_charts||void 0===t.show_on_charts?"fa-check-square":"fa-times-circle",t.show_on_charts||void 0===t.show_on_charts?"text-success":"text-danger"]})]):e._e(),e._v(" "),n("td",[e._v("\n                        "+e._s(t.first_entry_date)+"\n                    ")]),e._v(" "),n("td",[e._v("\n                        "+e._s(t.first_entry_amount)+"\n                    ")])])})),0)])])])}),[],!1,null,null,null).exports,IncomeOutcomeTableComponent:Object(s.a)(c,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"restore-table"},[n("div",{staticClass:"header"},[e._v("\n        "+e._s(e.title)+":\n    ")]),e._v(" "),n("div",{staticClass:"restore-table-content"},[n("table",[n("thead",e._l(e.header,(function(t,a){return n("th",{key:a,attrs:{scope:"col"}},[e._v(e._s(t))])})),0),e._v(" "),n("tbody",e._l(e.data,(function(t,a){return n("tr",{key:a},[n("td",{staticClass:"font-weight-bold"},[e._v("\n                        "+e._s(t.date)+"\n                    ")]),e._v(" "),n("td",[e._v("\n                        "+e._s(t.title)+"\n                    ")]),e._v(" "),n("td",[e._v("\n                        "+e._s(t.amount)+"\n                    ")]),e._v(" "),n("td",[e._v("\n                        "+e._s(t.price)+" "+e._s(e.currencies.find((function(e){return e.id==t.currency_id})).ISO)+"\n                    ")]),e._v(" "),n("td",[e._v("\n                        "+e._s(t.category_id?e.categories[t.category_id-1].name:"N/A")+"\n                    ")]),e._v(" "),n("td",[e._v("\n                        "+e._s(t.mean_id?e.means[t.mean_id-1].name:"N/A")+"\n                    ")])])})),0)])])])}),[],!1,null,null,null).exports,CashTableComponent:Object(s.a)(o,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[e.cash.length?n("div",{staticClass:"col-lg-6 offset-lg-3"},[n("div",{staticClass:"restore-table"},[n("div",{staticClass:"header"},[e._v("\n                Cash:\n            ")]),e._v(" "),n("div",{staticClass:"restore-table-content"},[n("table",[n("thead",e._l(e.headerCash,(function(t,a){return n("th",{key:a,attrs:{scope:"col"}},[e._v(e._s(t))])})),0),e._v(" "),n("tbody",e._l(e.cash,(function(t,a){return n("tr",{key:a},[n("td",{staticClass:"font-weight-bold"},[e._v("\n                                "+e._s(e.currencies.find((function(e){return e.id==t.currency_id})).ISO)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(t.value)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(t.amount)+"\n                            ")])])})),0)])])])]):e._e(),e._v(" "),n("hr",{staticClass:"hr-dashed"}),e._v(" "),n("div",{staticClass:"col-lg-6 offset-lg-3"},[n("div",{staticClass:"restore-table"},[n("div",{staticClass:"header"},[e._v("\n                Cash means of payment:\n            ")]),e._v(" "),n("div",{staticClass:"restore-table-content"},[n("table",[n("thead",e._l(e.headerCashMeans,(function(t,a){return n("th",{key:a,attrs:{scope:"col"}},[e._v(e._s(t))])})),0),e._v(" "),n("tbody",e._l(e.cashMeans,(function(t,a){return n("tr",{key:a},[n("td",{staticClass:"font-weight-bold"},[e._v("\n                                "+e._s(e.currencies.find((function(e){return e.id==t.currency_id})).ISO)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(e.means[t.mean_id-1].name)+"\n                            ")])])})),0)])])])])])}),[],!1,null,null,null).exports,ReportsTableComponent:Object(s.a)(_,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("div",{staticClass:"row"},[n("div",{staticClass:"col-lg-10 offset-lg-1"},[n("div",{staticClass:"restore-table"},[n("div",{staticClass:"header"},[e._v("\n                    Reports:\n                ")]),e._v(" "),n("div",{staticClass:"restore-table-content"},[n("table",[n("thead",e._l(e.header.reports,(function(t,a){return n("th",{key:a,attrs:{scope:"col"}},[e._v(e._s(t))])})),0),e._v(" "),n("tbody",e._l(e.data,(function(t,a){return n("tr",{key:a},[n("td",[e._v("\n                                    "+e._s(t.title)+"\n                                ")]),e._v(" "),n("td",[n("i",{class:["fas","h3","my-auto",t.income_addition?"text-success":"text-danger",t.income_addition?"fa-check-square":"fa-times-circle"]})]),e._v(" "),n("td",[n("i",{class:["fas","h3","my-auto",t.sort_dates_desc?"text-success":"text-danger",t.sort_dates_desc?"fa-check-square":"fa-times-circle"]})]),e._v(" "),n("td",[n("i",{class:["fas","h3","my-auto",t.calculate_sum?"text-success":"text-danger",t.calculate_sum?"fa-check-square":"fa-times-circle"]})])])})),0)])])])])]),e._v(" "),e.queries.length?n("div",[n("hr",{staticClass:"hr-dashed"}),e._v(" "),n("div",{staticClass:"restore-table"},[n("div",{staticClass:"header"},[e._v("\n                Report queries:\n            ")]),e._v(" "),n("div",{staticClass:"restore-table-content"},[n("table",[n("thead",e._l(e.header.queries,(function(t,a){return n("th",{key:a,attrs:{scope:"col"}},[e._v(e._s(t))])})),0),e._v(" "),n("tbody",e._l(e.queries,(function(t,a){return n("tr",{key:a},[n("td",[e._v("\n                                "+e._s(t.report)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(e._f("capitalize")(t.query_data))+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(t.min_date||"N/A")+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(t.max_date||"N/A")+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(t.title||"N/A")+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(null===t.min_amount?"N/A":t.min_amount)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(null===t.max_amount?"N/A":t.max_amount)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(null===t.min_price?"N/A":t.min_price)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(null===t.max_price?"N/A":t.max_price)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(null===t.currency_id?"N/A":e.currencies[t.currency_id-1].ISO)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(0===t.category_id?"N/A":e.categories[t.category_id].name)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(0===t.mean_id?"N/A":e.means[t.mean_id].name)+"\n                            ")])])})),0)])])])]):e._e(),e._v(" "),e.additionalEntries.length?n("div",[n("hr",{staticClass:"hr-dashed"}),e._v(" "),n("div",{staticClass:"restore-table"},[n("div",{staticClass:"header"},[e._v("\n                Report additional entries:\n            ")]),e._v(" "),n("div",{staticClass:"restore-table-content"},[n("table",[n("thead",e._l(e.header.additionalEntries,(function(t,a){return n("th",{key:a,attrs:{scope:"col"}},[e._v(e._s(t))])})),0),e._v(" "),n("tbody",e._l(e.additionalEntries,(function(t,a){return n("tr",{key:a},[n("td",[e._v("\n                                "+e._s(t.report)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(t.date)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(t.title)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(t.amount)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(t.price)+" "+e._s(e.currencies[t.currency_id-1].ISO)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(e.categories[t.category_id].name)+"\n                            ")]),e._v(" "),n("td",[e._v("\n                                "+e._s(e.means[t.mean_id].name)+"\n                            ")])])})),0)])])])]):e._e(),e._v(" "),e.users.length?n("div",[n("hr",{staticClass:"hr-dashed"}),e._v(" "),n("div",{staticClass:"row"},[n("div",{staticClass:"col-lg-6 offset-lg-3"},[n("div",{staticClass:"restore-table"},[n("div",{staticClass:"header"},[e._v("\n                        Report shared users:\n                    ")]),e._v(" "),n("div",{staticClass:"restore-table-content"},[n("table",[n("thead",e._l(e.header.users,(function(t,a){return n("th",{key:a,attrs:{scope:"col"}},[e._v(e._s(t))])})),0),e._v(" "),n("tbody",e._l(e.users,(function(t,a){return n("tr",{key:a},[n("td",[e._v("\n                                        "+e._s(t.report)+"\n                                    ")]),e._v(" "),n("td",[e._v("\n                                        "+e._s(t.user)+"\n                                    ")])])})),0)])])])])])]):e._e()])}),[],!1,null,null,null).exports}},m=Object(s.a)(f,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("hr",{staticClass:"hr"}),e._v(" "),n("CategoriesTableComponent",{attrs:{data:e.data.categories,currencies:e.currencies,charts:e.bundles.charts}}),e._v(" "),n("hr",{staticClass:"hr-dashed"}),e._v(" "),n("MeansTableComponent",{attrs:{data:e.data.means,currencies:e.currencies,charts:e.bundles.charts}}),e._v(" "),n("hr",{staticClass:"hr-dashed"}),e._v(" "),n("IncomeOutcomeTableComponent",{attrs:{title:"Income",data:e.data.income,currencies:e.currencies,categories:e.data.categories,means:e.data.means}}),e._v(" "),n("hr",{staticClass:"hr-dashed"}),e._v(" "),n("IncomeOutcomeTableComponent",{attrs:{title:"Outcome",data:e.data.outcome,currencies:e.currencies,categories:e.data.categories,means:e.data.means}}),e._v(" "),null!=e.data.bundleData.cash&&e.data.bundleData.cash.length?n("div",[n("hr",{staticClass:"hr-dashed"}),e._v(" "),n("CashTableComponent",{attrs:{cash:e.data.bundleData.cash,cashMeans:e.data.bundleData.cashMeans,currencies:e.currencies,means:e.data.means}})],1):e._e(),e._v(" "),null!=e.data.bundleData.reports&&e.data.bundleData.reports.length?n("div",[n("hr",{staticClass:"hr-dashed"}),e._v(" "),n("ReportsTableComponent",{attrs:{data:e.data.bundleData.reports,currencies:e.currencies,categories:e.data.categories,means:e.data.means}})],1):e._e()],1)}),[],!1,null,null,null).exports;function h(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,a)}return n}function v(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function p(e){return(p="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}var y={components:{Loading:a.a,RestoreTablesComponent:m},data:function(){return{ready:!1,canCreate:!1,canRestore:!1,restoreDate:"",createSpinner:!1,submitSpinner:!1,dataToDisplay:!0,currencies:[],hasBundles:{}}},methods:{createBackup:function(){var e=this;this.createSpinner=!0,axios.get("/webapi/bundles/backup/create").then((function(t){var n=t.data,a=document.createElement("a");a.style.display="none",a.href="data:application/octet-stream;charset:utf-8,".concat(encodeURIComponent(JSON.stringify(n))),a.download="".concat((new Date).toISOString().split("T")[0],".selfacc"),document.body.appendChild(a),a.click(),document.body.removeChild(a),e.canCreate=!1})).catch((function(e){console.error(e)})).finally((function(){e.createSpinner=!1}))},restoreData:function(){document.getElementById("file-input").click()},isValidString:function(e,t,n){return"string"==typeof e&&e.length>=t&&e.length<=n},isBetweenNumbers:function(e,t,n){return"number"==typeof e&&e>=t&&e<=n},isObject:function(e){return"object"==p(e)&&!Array.isArray(e)&&null!==e},isDate:function(e){return"string"==typeof e&&!isNaN(Date.parse(e))},isCurrency:function(e){return"number"==typeof e&&this.currencies.map((function(e){return e.id})).includes(e)},checkCategories:function(e){var t=this,n=[];if(e.forEach((function(e){var a=[t.isCurrency(e.currency_id),t.isValidString(e.name,1,32),"boolean"==typeof e.income_category,"boolean"==typeof e.outcome_category,"boolean"==typeof e.count_to_summary,void 0===e.show_on_charts||"boolean"==typeof e.show_on_charts,null===e.start_date||t.isDate(e.start_date),null===e.end_date||t.isDate(e.end_date)&&Date.parse(e.start_date)<=Date.parse(e.end_date)];n.push(a.reduce((function(e,t){return e&&t})))})),n.length&&!n.reduce((function(e,t){return e&&t})))return!1;var a={};return e.forEach((function(e,t){a[t+1]=e.currency_id})),a},checkMeans:function(e){var t=this,n=[];if(e.forEach((function(e){var a=[t.isCurrency(e.currency_id),t.isValidString(e.name,1,32),"boolean"==typeof e.income_mean,"boolean"==typeof e.outcome_mean,"boolean"==typeof e.count_to_summary,void 0===e.show_on_charts||"boolean"==typeof e.show_on_charts,t.isDate(e.first_entry_date),t.isBetweenNumbers(e.first_entry_amount,-99999999999.99,99999999999.99)];n.push(a.reduce((function(e,t){return e&&t})))})),n.length&&!n.reduce((function(e,t){return e&&t})))return!1;var a={};return e.forEach((function(e,t){a[t+1]={currency:e.currency_id,first_entry:e.first_entry_date}})),a},checkIncomeOutcomeData:function(e,t,n){var a=this;return e.forEach((function(e){var r=0==e.mean_id||n[e.mean_id].currency==e.currency_id,s=0==e.category_id||t[e.category_id]==e.currency_id;return!(!r||!s)&&(!![a.isDate(e.date)&&Date.parse(e.date)>=Date.parse(0==e.mean_id?"1970":n[e.mean_id].first_entry),a.isValidString(e.title,1,64),a.isCurrency(e.currency_id),a.isBetweenNumbers(e.amount,.001,9999999.999),a.isBetweenNumbers(e.price,.01,99999999999.99)].reduce((function(e,t){return e&&t}))&&void 0)})),!0},checkBundleData:function(e,t,n){var a=this;if(this.hasBundles.cashan&&null!=e.cash&&null!=e.cashMeans){if(!Array.isArray(e.cash)||!Array.isArray(e.cashMeans))return!1;e.cash.forEach((function(e){if(![a.isCurrency(e.currency_id),a.isBetweenNumbers(e.value,.001,99999999.99),a.isBetweenNumbers(e.amount,1,Math.pow(2,63)-1)].reduce((function(e,t){return e&&t})))return!1})),e.cashMeans.forEach((function(e){if(![a.isCurrency(e.currency_id),a.isBetweenNumbers(e.mean_id,1,n.length),n[e.mean_id-1].currency_id==e.currency_id].reduce((function(e,t){return e&&t})))return!1}))}if(this.hasBundles.report&&null!=e.reports){if(!Array.isArray(e.reports))return!1;e.reports.forEach((function(e){if(![p(a.isValidString(e.title,1,64)),"boolean"==typeof e.income_addition,"boolean"==typeof e.sort_dates_desc,"boolean"==typeof e.calculate_sum,Array.isArray(e.queries)&&e.queries.map((function(e){return[["income","outcome"].includes(e.query_data),null===e.min_date||a.isDate(e.min_date)&&(!e.max_date||Date.parse(e.min_date)<=Date.parse(e.max_date)),null===e.max_date||!isNaN(Date.parse(e.max_date))&&(!e.min_date||Date.parse(e.min_date)<=Date.parse(e.max_date)),null===e.min_amount||"number"==typeof e.min_amount&&(null===e.max_amount||e.min_amount<=e.max_amount)&&a.isBetweenNumbers(e.min_date,0,9999999.999),null===e.max_amount||"number"==typeof e.max_amount&&(null===e.min_amount||e.min_amount<=e.max_amount)&&a.isBetweenNumbers(e.min_date,0,9999999.999),null===e.min_price||"number"==typeof e.min_price&&(null===e.max_price||e.min_price<=e.max_price)&&a.isBetweenNumbers(e.min_date,0,99999999999.99),null===e.max_price||"number"==typeof e.max_price&&(null===e.min_price||e.min_price<=e.max_price)&&a.isBetweenNumbers(e.min_date,0,99999999999.99),null===e.currency_id||a.isCurrency(e.currency_id),0===e.category_id||t[e.category_id-1].currency_id==e.currency_id,0===e.mean_id||n[e.mean_id-1].currency_id==e.currency_id]})).reduce((function(e,t){return e&&t})),Array.isArray(e.additionalEntries)&&e.additionalEntries.map((function(e){return[a.isDate(e.date),a.isValidString(e.title,1,64),a.isBetweenNumbers(e.amount,.001,9999999.999),a.isBetweenNumbers(e.price,.01,99999999999.99),a.isCurrency(e.currency_id),0==e.category_id||t[e.category_id].currency==e.currency_id,0==e.mean_id||n[e.mean_id].currency==e.currency_id]})).reduce((function(e,t){return e&&t})),Array.isArray(e.users)&&e.additionalEntries.map((function(e){return a.isValidString(e.title,1,64)&&/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(e.title)})).reduce((function(e,t){return e&&t}))].reduce((function(e,t){return e&&t})))return!1}))}return!0},readFile:function(){var e=this,t=document.getElementById("file-input").files[0];new File([t],{type:"application/json"}).text().then((function(e){return JSON.parse(e)})).then((function(t){if(!e.isObject(t))throw new Error("Invalid data wrapper (wrapper not an object)");var n=["categories","means","income","outcome"];n.forEach((function(e){if(void 0===t[e]||!Array.isArray(t[e]))throw new Error("Invalid data type (".concat(e," not an array)"))}));var a={};if(n.forEach((function(n){switch(n){case"categories":a[n]=e.checkCategories(t.categories);break;case"means":a[n]=e.checkMeans(t.means);break;case"income":case"outcome":a[n]=e.checkIncomeOutcomeData(t[n],a.categories,a.means)}if(!1===a[n])throw new Error("Invalid ".concat(n," data"))})),void 0===t.bundleData||!(t.bundleData instanceof Object))throw new Error("Invalid data type (bundle data not an object)");if(!1===e.checkBundleData(t.bundleData,t.categories,t.means))throw new Error("Invalid bundle data");e.dataToDisplay=t})).catch((function(t){e.dataToDisplay=!1,console.error(t)}))},submitData:function(){var e=this;this.submitSpinner=!0,axios.post("/webapi/bundles/backup/restore",function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?h(Object(n),!0).forEach((function(t){v(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):h(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}({},this.dataToDisplay)).then((function(){})).catch((function(t){console.error(t),e.submitSpinner=!1}))}},mounted:function(){var e=this;axios.get("/webapi/bundles/backup",{}).then((function(t){var n=t.data;e.canCreate=n.canCreate,e.canRestore=n.canRestore,e.restoreDate=n.restoreDate,e.currencies=n.currencies,e.hasBundles=n.hasBundles,e.ready=!0}))},updated:function(){$('[data-toggle="tooltip"]').tooltip()}},b=Object(s.a)(y,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"card"},[e._m(0),e._v(" "),n("div",{staticClass:"card-body"},[e.ready?n("div",[n("div",{staticStyle:{display:"none"}},[n("input",{attrs:{id:"file-input",type:"file",accept:".selfacc"},on:{change:e.readFile}})]),e._v(" "),n("div",{staticClass:"row"},[n("div",{staticClass:"col-lg-4 offset-lg-2 my-lg-0 my-2"},[n("button",{staticClass:"big-button-primary",attrs:{disabled:!e.canCreate||e.createSpinner,"data-toggle":!e.canCreate&&"tooltip","data-placement":!e.canCreate&&"bottom",title:!e.canCreate&&"You can only create one backup per 24 hours"},on:{click:e.createBackup}},[e.createSpinner?n("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):n("div",[e._v("\n                                Create backup\n                            ")])])]),e._v(" "),n("div",{staticClass:"col-lg-4 my-lg-0 my-2"},[n("button",{staticClass:"big-button-primary",attrs:{disabled:!e.canRestore,"data-toggle":!e.canRestore&&"tooltip","data-placement":!e.canRestore&&"bottom",title:!e.canRestore&&(e.restoreDate.length?"Contact the developer or wait until "+e.restoreDate+" to enable this option":"You can restore data once per 24 hours")},on:{click:e.restoreData}},[e._v("Restore from file")])])]),e._v(" "),!1===e.dataToDisplay?n("div",{staticClass:"row"},[n("div",{staticClass:"h3 w-100 mt-3 font-weight-bold text-danger text-center"},[e._v("\n                        This file is invalid. Please try a different file.\n                    ")])]):!0!==e.dataToDisplay?n("div",[n("RestoreTablesComponent",{attrs:{data:e.dataToDisplay,currencies:e.currencies,bundles:e.hasBundles}}),e._v(" "),n("hr",{staticClass:"hr"}),e._v(" "),n("div",{staticClass:"row"},[n("div",{staticClass:"col-md-6 offset-md-3"},[n("button",{staticClass:"big-button-success",attrs:{disabled:e.submitSpinner},on:{click:e.submitData}},[e.submitSpinner?n("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):n("div",[e._v("\n                                    Restore data\n                                ")])])])]),e._v(" "),n("div",{staticClass:"h3 font-weight-bold text-danger mt-3 text-center"},[e._v("\n\t\t\t\t\t\tWarning! Performing this action will result in all of your data being erased!\n\t\t\t\t\t")])],1):e._e()]):n("Loading")],1)])}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"card-header-flex"},[t("div",{staticClass:"card-header-text"},[t("i",{staticClass:"fas fa-hdd"}),this._v("\n                Backup data\n            ")])])}],!1,null,null,null);t.default=b.exports}});
//# sourceMappingURL=backup.js.map