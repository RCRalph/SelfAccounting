!function(t){var e={};function n(a){if(e[a])return e[a].exports;var r=e[a]={i:a,l:!1,exports:{}};return t[a].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=t,n.c=e,n.d=function(t,e,a){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:a})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var a=Object.create(null);if(n.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(a,r,function(e){return t[e]}.bind(null,r));return a},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=226)}({1:function(t,e,n){"use strict";function a(t,e,n,a,r,s,c,o){var i,u="function"==typeof t?t.options:t;if(e&&(u.render=e,u.staticRenderFns=n,u._compiled=!0),a&&(u.functional=!0),s&&(u._scopeId="data-v-"+s),c?(i=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),r&&r.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(c)},u._ssrRegister=i):r&&(i=o?function(){r.call(this,(u.functional?this.parent:this).$root.$options.shadowRoot)}:r),i)if(u.functional){u._injectStyles=i;var l=u.render;u.render=function(t,e){return i.call(e),l(t,e)}}else{var d=u.beforeCreate;u.beforeCreate=d?[].concat(d,i):[i]}return{exports:t,options:u}}n.d(e,"a",(function(){return a}))},2:function(t,e,n){"use strict";var a=n(1),r=Object(a.a)({},(function(){var t=this.$createElement;this._self._c;return this._m(0)}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"d-flex justify-content-center my-2"},[e("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[e("span",{staticClass:"sr-only"},[this._v("Loading...")])])])}],!1,null,null,null);e.a=r.exports},226:function(t,e,n){t.exports=n(227)},227:function(t,e,n){Vue.component("backup-component",n(242).default);new Vue({el:"#app"})},242:function(t,e,n){"use strict";n.r(e);var a=n(2),r={props:["data","currencies","charts"],computed:{header:function(){var t=["Currency","Name","Income","Outcome","Summary","Start","End"];return this.charts&&t.splice(5,0,"Charts"),t}}},s=n(1),c={props:["data","currencies","charts"],computed:{header:function(){var t=["Currency","Name","Income","Outcome","Summary","First entry","Starting balance"];return this.charts&&t.splice(5,0,"Charts"),t}}},o={props:["data","currencies","title","categories","means"],data:function(){return{header:["Currency","Date","Title","Amount","Price","Category","Mean"]}}},i={props:["data","currencies"],data:function(){return{header:["Currency","Face Value","Amount"]}}},u={props:["data","currencies","bundles"],components:{CategoriesTableComponent:Object(s.a)(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"restore-table"},[n("div",{staticClass:"header"},[t._v("\n        Categories:\n    ")]),t._v(" "),n("div",{staticClass:"restore-table-content"},[n("table",[n("thead",t._l(t.header,(function(e,a){return n("th",{key:a,attrs:{scope:"col"}},[t._v(t._s(e))])})),0),t._v(" "),n("tbody",t._l(t.data,(function(e,a){return n("tr",{key:a},[n("td",{staticClass:"font-weight-bold"},[t._v("\n                        "+t._s(t.currencies.find((function(t){return t.id==e.currency_id})).ISO)+"\n                    ")]),t._v(" "),n("td",[t._v("\n                        "+t._s(e.name)+"\n                    ")]),t._v(" "),n("td",[n("i",{class:["fas","h4","my-auto",e.income_category?"fa-check-square":"fa-times-circle",e.income_category?"text-success":"text-danger"]})]),t._v(" "),n("td",[n("i",{class:["fas","h4","my-auto",e.outcome_category?"fa-check-square":"fa-times-circle",e.outcome_category?"text-success":"text-danger"]})]),t._v(" "),n("td",[n("i",{class:["fas","h4","my-auto",e.count_to_summary?"fa-check-square":"fa-times-circle",e.count_to_summary?"text-success":"text-danger"]})]),t._v(" "),t.charts?n("td",[n("i",{class:["fas","h4","my-auto",e.show_on_charts||void 0===e.show_on_charts?"fa-check-square":"fa-times-circle",e.show_on_charts||void 0===e.show_on_charts?"text-success":"text-danger"]})]):t._e(),t._v(" "),n("td",[t._v("\n                        "+t._s(e.start_date||"N/A")+"\n                    ")]),t._v(" "),n("td",[t._v("\n                        "+t._s(e.end_date||"N/A")+"\n                    ")])])})),0)])])])}),[],!1,null,null,null).exports,MeansTableComponent:Object(s.a)(c,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"restore-table"},[n("div",{staticClass:"header"},[t._v("\n        Means of payment:\n    ")]),t._v(" "),n("div",{staticClass:"restore-table-content"},[n("table",[n("thead",t._l(t.header,(function(e,a){return n("th",{key:a,attrs:{scope:"col"}},[t._v(t._s(e))])})),0),t._v(" "),n("tbody",t._l(t.data,(function(e,a){return n("tr",{key:a},[n("td",{staticClass:"font-weight-bold"},[t._v("\n                        "+t._s(t.currencies.find((function(t){return t.id==e.currency_id})).ISO)+"\n                    ")]),t._v(" "),n("td",[t._v("\n                        "+t._s(e.name)+"\n                    ")]),t._v(" "),n("td",[n("i",{class:["fas","h4","my-auto",e.income_mean?"fa-check-square":"fa-times-circle",e.income_mean?"text-success":"text-danger"]})]),t._v(" "),n("td",[n("i",{class:["fas","h4","my-auto",e.outcome_mean?"fa-check-square":"fa-times-circle",e.outcome_mean?"text-success":"text-danger"]})]),t._v(" "),n("td",[n("i",{class:["fas","h4","my-auto",e.count_to_summary?"fa-check-square":"fa-times-circle",e.count_to_summary?"text-success":"text-danger"]})]),t._v(" "),t.charts?n("td",[n("i",{class:["fas","h4","my-auto",e.show_on_charts||void 0===e.show_on_charts?"fa-check-square":"fa-times-circle",e.show_on_charts||void 0===e.show_on_charts?"text-success":"text-danger"]})]):t._e(),t._v(" "),n("td",[t._v("\n                        "+t._s(e.first_entry_date)+"\n                    ")]),t._v(" "),n("td",[t._v("\n                        "+t._s(e.first_entry_amount)+"\n                    ")])])})),0)])])])}),[],!1,null,null,null).exports,IncomeOutcomeTableComponent:Object(s.a)(o,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"restore-table"},[n("div",{staticClass:"header"},[t._v("\n        "+t._s(t.title)+":\n    ")]),t._v(" "),n("div",{staticClass:"restore-table-content"},[n("table",[n("thead",t._l(t.header,(function(e,a){return n("th",{key:a,attrs:{scope:"col"}},[t._v(t._s(e))])})),0),t._v(" "),n("tbody",t._l(t.data,(function(e,a){return n("tr",{key:a},[n("td",{staticClass:"font-weight-bold"},[t._v("\n                        "+t._s(t.currencies.find((function(t){return t.id==e.currency_id})).ISO)+"\n                    ")]),t._v(" "),n("td",[t._v("\n                        "+t._s(e.date)+"\n                    ")]),t._v(" "),n("td",[t._v("\n                        "+t._s(e.title)+"\n                    ")]),t._v(" "),n("td",[t._v("\n                        "+t._s(e.amount)+"\n                    ")]),t._v(" "),n("td",[t._v("\n                        "+t._s(e.price)+"\n                    ")]),t._v(" "),n("td",[t._v("\n                        "+t._s(0==e.category_id?"N/A":t.categories[e.category_id-1].name)+"\n                    ")]),t._v(" "),n("td",[t._v("\n                        "+t._s(0==e.mean_id?"N/A":t.categories[e.mean_id-1].name)+"\n                    ")])])})),0)])])])}),[],!1,null,null,null).exports,CashTableComponent:Object(s.a)(i,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"col-lg-6 offset-lg-3"},[n("div",{staticClass:"restore-table"},[n("div",{staticClass:"header"},[t._v("\n            Cash:\n        ")]),t._v(" "),n("div",{staticClass:"restore-table-content"},[n("table",[n("thead",t._l(t.header,(function(e,a){return n("th",{key:a,attrs:{scope:"col"}},[t._v(t._s(e))])})),0),t._v(" "),n("tbody",t._l(t.data,(function(e,a){return n("tr",{key:a},[n("td",{staticClass:"font-weight-bold"},[t._v("\n                            "+t._s(t.currencies.find((function(t){return t.id==e.currency_id})).ISO)+"\n                        ")]),t._v(" "),n("td",[t._v("\n                            "+t._s(e.value)+"\n                        ")]),t._v(" "),n("td",[t._v("\n                            "+t._s(e.amount)+"\n                        ")])])})),0)])])])])}),[],!1,null,null,null).exports}},l=Object(s.a)(u,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("hr",{staticClass:"hr"}),t._v(" "),n("CategoriesTableComponent",{attrs:{data:t.data.categories,currencies:t.currencies,charts:t.bundles.charts}}),t._v(" "),n("hr",{staticClass:"hr-dashed"}),t._v(" "),n("MeansTableComponent",{attrs:{data:t.data.means,currencies:t.currencies,charts:t.bundles.charts}}),t._v(" "),n("hr",{staticClass:"hr-dashed"}),t._v(" "),n("IncomeOutcomeTableComponent",{attrs:{title:"Income",data:t.data.income,currencies:t.currencies,categories:t.data.categories,means:t.data.means}}),t._v(" "),n("hr",{staticClass:"hr-dashed"}),t._v(" "),n("IncomeOutcomeTableComponent",{attrs:{title:"Outcome",data:t.data.outcome,currencies:t.currencies,categories:t.data.categories,means:t.data.means}}),t._v(" "),null!=t.data.bundleData.cash?n("div",[n("hr",{staticClass:"hr-dashed"}),t._v(" "),n("CashTableComponent",{attrs:{data:t.data.bundleData.cash,currencies:t.currencies}})],1):t._e()],1)}),[],!1,null,null,null).exports;function d(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,a)}return n}function f(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function _(t){return(_="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}var h={components:{Loading:a.a,RestoreTablesComponent:l},data:function(){return{ready:!1,canCreate:!1,canRestore:!1,restoreDate:"",createSpinner:!1,submitSpinner:!1,dataToDisplay:!0,currencies:[],hasBundles:{}}},methods:{createBackup:function(){var t=this;this.createSpinner=!0,axios.get("/webapi/bundles/backup/create").then((function(e){var n=e.data,a=document.createElement("a");a.style.display="none",a.href="data:application/octet-stream;charset:utf-8,".concat(encodeURIComponent(JSON.stringify(n))),a.download="".concat((new Date).toISOString().split("T")[0],".selfacc"),document.body.appendChild(a),a.click(),document.body.removeChild(a),t.canCreate=!1})).catch((function(t){console.error(t)})).finally((function(){t.createSpinner=!1}))},restoreData:function(){document.getElementById("file-input").click()},isValidString:function(t,e,n){return"string"==typeof t&&t.length>=e&&t.length<=n},isBetweenNumbers:function(t,e,n){return"number"==typeof t&&t>=e&&t<=n},isObject:function(t){return"object"==_(t)&&!Array.isArray(t)&&null!==t},isDate:function(t){return"string"==typeof t&&!isNaN(Date.parse(t))},isCurrency:function(t){return"number"==typeof t&&this.currencies.map((function(t){return t.id})).includes(t)},checkCategories:function(t){var e=this,n=[];if(t.forEach((function(t){var a=[e.isCurrency(t.currency_id),e.isValidString(t.name,1,32),"boolean"==typeof t.income_category,"boolean"==typeof t.outcome_category,"boolean"==typeof t.count_to_summary,void 0===t.show_on_charts||"boolean"==typeof t.show_on_charts,null===t.start_date||e.isDate(t.start_date),null===t.end_date||e.isDate(t.end_date)&&Date.parse(t.start_date)<=Date.parse(t.end_date)];n.push(a.reduce((function(t,e){return t&&e})))})),n.length&&!n.reduce((function(t,e){return t&&e})))return!1;var a={};return t.forEach((function(t,e){a[e+1]=t.currency_id})),a},checkMeans:function(t){var e=this,n=[];if(t.forEach((function(t){var a=[e.isCurrency(t.currency_id),e.isValidString(t.name,1,32),"boolean"==typeof t.income_mean,"boolean"==typeof t.outcome_mean,"boolean"==typeof t.count_to_summary,void 0===t.show_on_charts||"boolean"==typeof t.show_on_charts,e.isDate(t.first_entry_date),e.isBetweenNumbers(t.first_entry_amount,-99999999999,99999999999)];n.push(a.reduce((function(t,e){return t&&e})))})),n.length&&!n.reduce((function(t,e){return t&&e})))return!1;var a={};return t.forEach((function(t,e){a[e+1]={currency:t.currency_id,first_entry:t.first_entry_date}})),a},checkIncomeOutcomeData:function(t,e,n){var a=this,r=[];return t.forEach((function(t){var s=0==t.mean_id||n[t.mean_id].currency==t.currency_id,c=0==t.category_id||e[t.category_id]==t.currency_id;if(s&&c){var o=[a.isDate(t.date)&&Date.parse(t.date)>=Date.parse(0==t.mean_id?"1970":n[t.mean_id].first_entry),a.isValidString(t.title,1,64),a.isCurrency(t.currency_id),a.isBetweenNumbers(t.amount,.001,999999.999),a.isBetweenNumbers(t.price,.01,99999999999.99)];r.push(o.reduce((function(t,e){return t&&e})))}else r.push(!1)})),!r.length||r.reduce((function(t,e){return t&&e}))},checkBundleData:function(t){var e=this,n=[];if(this.hasBundles.cashan&&null!=t.cash){if(!Array.isArray(t.cash))return!1;t.cash.forEach((function(t){var a=[e.isCurrency(t.currency_id),e.isBetweenNumbers(t.value,.001,9999999.999),e.isBetweenNumbers(t.amount,1,Math.pow(2,63)-1)];n.push(a.reduce((function(t,e){return t&&e})))}))}return!n.length||n.reduce((function(t,e){return t&&e}))},readFile:function(){var t=this,e=document.getElementById("file-input").files[0];new File([e],{type:"application/json"}).text().then((function(t){return JSON.parse(t)})).then((function(e){if(!t.isObject(e))throw new Error("Invalid data wrapper (wrapper not an object)");var n=["categories","means","income","outcome"];n.forEach((function(t){if(void 0===e[t]||!Array.isArray(e[t]))throw new Error("Invalid data type (".concat(t," not an array)"))}));var a={};if(n.forEach((function(n){switch(n){case"categories":a[n]=t.checkCategories(e.categories);break;case"means":a[n]=t.checkMeans(e.means);break;case"income":case"outcome":a[n]=t.checkIncomeOutcomeData(e[n],a.categories,a.means)}if(!1===a[n])throw new Error("Invalid ".concat(n," data"))})),void 0===e.bundleData||!(e.bundleData instanceof Object))throw new Error("Invalid data type (bundle data not an object)");if(!1===t.checkBundleData(e.bundleData))throw new Error("Invalid bundle data");t.dataToDisplay=e})).catch((function(e){t.dataToDisplay=!1,console.error(e)}))},submitData:function(){var t=this;this.submitSpinner=!0,axios.post("/webapi/bundles/backup/restore",function(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?d(Object(n),!0).forEach((function(e){f(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):d(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}({},this.dataToDisplay)).then((function(){window.location.href="/summary"})).catch((function(e){console.error(e),t.submitSpinner=!1}))}},mounted:function(){var t=this;axios.get("/webapi/bundles/backup",{}).then((function(e){var n=e.data;t.canCreate=n.canCreate,t.canRestore=n.canRestore,t.restoreDate=n.restoreDate,t.currencies=n.currencies,t.hasBundles=n.hasBundles,t.ready=!0}))},updated:function(){$('[data-toggle="tooltip"]').tooltip()}},m=Object(s.a)(h,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"card"},[t._m(0),t._v(" "),n("div",{staticClass:"card-body"},[t.ready?n("div",[n("div",{staticStyle:{display:"none"}},[n("input",{attrs:{id:"file-input",type:"file",accept:".selfacc"},on:{change:t.readFile}})]),t._v(" "),n("div",{staticClass:"row"},[n("div",{staticClass:"col-lg-4 offset-lg-2 my-lg-0 my-2"},[n("button",{staticClass:"big-button-primary",attrs:{disabled:!t.canCreate||t.createSpinner,"data-toggle":!t.canCreate&&"tooltip","data-placement":!t.canCreate&&"bottom",title:!t.canCreate&&"You can only create one backup per 24 hours"},on:{click:t.createBackup}},[t.createSpinner?n("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):n("div",[t._v("\n                                Create backup\n                            ")])])]),t._v(" "),n("div",{staticClass:"col-lg-4 my-lg-0 my-2"},[n("button",{staticClass:"big-button-primary",attrs:{disabled:!t.canRestore,"data-toggle":!t.canRestore&&"tooltip","data-placement":!t.canRestore&&"bottom",title:!t.canRestore&&(t.restoreDate.length?"Contact the developer or wait until "+t.restoreDate+" to enable this option":"You can restore data once per 24 hours")},on:{click:t.restoreData}},[t._v("Restore from file")])])]),t._v(" "),!1===t.dataToDisplay?n("div",{staticClass:"row"},[n("div",{staticClass:"h3 w-100 mt-3 font-weight-bold text-danger text-center"},[t._v("\n                        This file is invalid. Please try a different file.\n                    ")])]):!0!==t.dataToDisplay?n("div",[n("RestoreTablesComponent",{attrs:{data:t.dataToDisplay,currencies:t.currencies,bundles:t.hasBundles}}),t._v(" "),n("hr",{staticClass:"hr"}),t._v(" "),n("div",{staticClass:"row"},[n("div",{staticClass:"col-md-6 offset-md-3"},[n("button",{staticClass:"big-button-success",attrs:{disabled:t.submitSpinner},on:{click:t.submitData}},[t.submitSpinner?n("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):n("div",[t._v("\n                                    Restore data\n                                ")])])])]),t._v(" "),n("div",{staticClass:"h3 font-weight-bold text-danger mt-3 text-center"},[t._v("\n\t\t\t\t\t\tWarning! Performing this action will result in all of your data being erased!\n\t\t\t\t\t")])],1):t._e()]):n("Loading")],1)])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"card-header-flex"},[e("div",{staticClass:"card-header-text"},[e("i",{staticClass:"fas fa-hdd"}),this._v("\n                Backup data\n            ")])])}],!1,null,null,null);e.default=m.exports}});
//# sourceMappingURL=backup.js.map