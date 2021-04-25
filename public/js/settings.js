!function(t){var e={};function n(a){if(e[a])return e[a].exports;var r=e[a]={i:a,l:!1,exports:{}};return t[a].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=t,n.c=e,n.d=function(t,e,a){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:a})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var a=Object.create(null);if(n.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(a,r,function(e){return t[e]}.bind(null,r));return a},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=200)}({1:function(t,e,n){"use strict";function a(t,e,n,a,r,s,i,o){var c,l="function"==typeof t?t.options:t;if(e&&(l.render=e,l.staticRenderFns=n,l._compiled=!0),a&&(l.functional=!0),s&&(l._scopeId="data-v-"+s),i?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),r&&r.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(i)},l._ssrRegister=c):r&&(c=o?function(){r.call(this,(l.functional?this.parent:this).$root.$options.shadowRoot)}:r),c)if(l.functional){l._injectStyles=c;var u=l.render;l.render=function(t,e){return c.call(e),u(t,e)}}else{var d=l.beforeCreate;l.beforeCreate=d?[].concat(d,c):[c]}return{exports:t,options:l}}n.d(e,"a",(function(){return a}))},2:function(t,e,n){"use strict";var a=n(1),r=Object(a.a)({},(function(){var t=this.$createElement;this._self._c;return this._m(0)}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"d-flex justify-content-center my-2"},[e("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[e("span",{staticClass:"sr-only"},[this._v("Loading...")])])])}],!1,null,null,null);e.a=r.exports},200:function(t,e,n){t.exports=n(201)},201:function(t,e,n){Vue.component("settings-component",n(243).default);new Vue({el:"#app"})},243:function(t,e,n){"use strict";n.r(e);var a=n(2),r=n(7),s=n(3),i=n(8),o={props:{value:{required:!0,type:Object},index:{required:!0,type:Number}},components:{Slider:i.a},computed:{validName:function(){var t=this.value.name;return t&&t.length<=32},validDates:function(){return!(this.value.start_date&&this.value.end_date)||new Date(this.value.start_date).getTime()<=new Date(this.value.end_date).getTime()}}},c=n(1),l=Object(c.a)(o,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("tr",{attrs:{i:t.index}},[n("td",[n("input",{directives:[{name:"model",rawName:"v-model",value:t.value.name,expression:"value.name"}],class:["form-text",!t.validName&&"is-invalid"],attrs:{type:"text",placeholder:"Name...",maxlength:"32"},domProps:{value:t.value.name},on:{input:function(e){e.target.composing||t.$set(t.value,"name",e.target.value)}}})]),t._v(" "),n("td",[n("Slider",{model:{value:t.value.income_category,callback:function(e){t.$set(t.value,"income_category",e)},expression:"value.income_category"}})],1),t._v(" "),n("td",[n("Slider",{model:{value:t.value.outcome_category,callback:function(e){t.$set(t.value,"outcome_category",e)},expression:"value.outcome_category"}})],1),t._v(" "),n("td",[n("Slider",{model:{value:t.value.count_to_summary,callback:function(e){t.$set(t.value,"count_to_summary",e)},expression:"value.count_to_summary"}})],1),t._v(" "),n("td",[n("input",{directives:[{name:"model",rawName:"v-model",value:t.value.start_date,expression:"value.start_date"}],class:["form-date",!t.validDates&&"is-invalid"],attrs:{type:"date",disabled:!t.value.count_to_summary},domProps:{value:t.value.start_date},on:{input:function(e){e.target.composing||t.$set(t.value,"start_date",e.target.value)}}})]),t._v(" "),n("td",[n("input",{directives:[{name:"model",rawName:"v-model",value:t.value.end_date,expression:"value.end_date"}],class:["form-date",!t.validDates&&"is-invalid"],attrs:{type:"date",disabled:!t.value.count_to_summary},domProps:{value:t.value.end_date},on:{input:function(e){e.target.composing||t.$set(t.value,"end_date",e.target.value)}}})]),t._v(" "),n("td",{staticClass:"trashbin",on:{click:function(e){return t.$emit("delete")}}},[n("i",{staticClass:"fas fa-trash"})])])}),[],!1,null,null,null).exports,u={props:{categories:{required:!0,type:Object},currency:{required:!0,type:Number}},components:{TableHeader:r.a,EmptyPlaceholder:s.a,CategoriesTableRow:l},data:function(){return{header:[{text:"Name",tooltip:"The name of your category"},{text:"Income",tooltip:"Use in income"},{text:"Outcome",tooltip:"Use in outcome"},{text:"Summary",tooltip:"Count to summary"},{text:"Start date",tooltip:"Count from this date"},{text:"End date",tooltip:"Count to this date"},{}],content:{},contentCopy:{},submitted:!1}},computed:{equalContent:function(){return _.isEqual(this.content,this.contentCopy)},canSave:function(){var t=[];for(var e in this.content)t=t.concat(this.content[e].map((function(t){var e=t.name&&t.name.length<=32,n=!(t.start_date&&t.end_date)||new Date(t.start_date).getTime()<=new Date(t.end_date).getTime();return e&&n})));return!t.length||!!t.reduce((function(t,e){return t&&e}))}},methods:{create:function(){void 0===this.content[this.currency]&&(this.content[this.currency]=[]),this.content[this.currency].push({id:0,currency_id:this.currency,name:"",income_category:!1,outcome_category:!1,count_to_summary:!1,start_date:"",end_date:""})},remove:function(t){this.content[this.currency].splice(t,1)},reset:function(){this.content=_.cloneDeep(this.contentCopy)},submit:function(){var t=this;this.submitted=!0,axios.post("/webapi/settings/categories",{data:this.content}).then((function(e){var n=e.data;t.content=n.data,t.contentCopy=_.cloneDeep(n.data),t.submitted=!1})).catch((function(e){console.log(e),t.submitted=!1}))},refreshTooltip:function(){this.$nextTick((function(){$('[data-toggle="tooltip"]').tooltip()}))}},beforeMount:function(){this.content=_.cloneDeep(this.categories),this.contentCopy=_.cloneDeep(this.categories)},mounted:function(){this.refreshTooltip()},updated:function(){this.refreshTooltip()}},d=Object(c.a)(u,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"card"},[t._m(0),t._v(" "),n("div",{staticClass:"card-body"},[n("div",[t.content[t.currency].length?n("div",{staticClass:"table-responsive-xl"},[n("table",{staticClass:"responsive-table-hover table-themed"},[n("TableHeader",{attrs:{cells:t.header}}),t._v(" "),n("tbody",t._l(t.content[t.currency],(function(e,a){return n("CategoriesTableRow",{key:a,attrs:{index:a},on:{delete:function(e){return t.remove(a)}},model:{value:t.content[t.currency][a],callback:function(e){t.$set(t.content[t.currency],a,e)},expression:"content[currency][i]"}})})),1)],1)]):n("EmptyPlaceholder")],1),t._v(" "),n("hr",{staticClass:"hr"}),t._v(" "),n("div",{staticClass:"row"},[n("div",{staticClass:"col-md-4"},[n("button",{staticClass:"big-button-primary",attrs:{disabled:t.submitted},on:{click:t.create}},[t._v("\n                    New category\n                ")])]),t._v(" "),n("div",{staticClass:"col-md-4 my-2 my-md-0"},[n("button",{staticClass:"big-button-danger",attrs:{disabled:t.submitted},on:{click:t.reset}},[t._v("\n                    Reset changes\n                ")])]),t._v(" "),n("div",{staticClass:"col-md-4"},[n("button",{class:["big-button-success",!t.equalContent&&!t.submitted&&"text-stand-out"],attrs:{disabled:!t.canSave||t.submitted},on:{click:t.submit}},[t.submitted?n("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):n("div",[t._v("\n                        Save changes\n                    ")])])])])])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"card-header-flex"},[e("div",{staticClass:"card-header-text"},[e("i",{staticClass:"fab fa-buffer"}),this._v("\n            Categories\n        ")])])}],!1,null,null,null).exports,m={props:{value:{required:!0,type:Object},index:{required:!0,type:Number}},components:{Slider:i.a},computed:{validName:function(){var t=this.value.name;return t&&t.length<=32},validDate:function(){var t=this.value.first_entry_date;return t&&(new Date(t).getTime()<=new Date(this.value.date_limit).getTime()||null===this.value.date_limit)},validAmount:function(){var t=Number(this.value.first_entry_amount);return""!==this.value.first_entry_amount&&Math.abs(t)<1e11}}},v=Object(c.a)(m,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("tr",{attrs:{i:t.index}},[n("td",[n("input",{directives:[{name:"model",rawName:"v-model",value:t.value.name,expression:"value.name"}],class:["form-text",!t.validName&&"is-invalid"],attrs:{type:"text",placeholder:"Name...",maxlength:"32"},domProps:{value:t.value.name},on:{input:function(e){e.target.composing||t.$set(t.value,"name",e.target.value)}}})]),t._v(" "),n("td",[n("Slider",{model:{value:t.value.income_mean,callback:function(e){t.$set(t.value,"income_mean",e)},expression:"value.income_mean"}})],1),t._v(" "),n("td",[n("Slider",{model:{value:t.value.outcome_mean,callback:function(e){t.$set(t.value,"outcome_mean",e)},expression:"value.outcome_mean"}})],1),t._v(" "),n("td",[n("Slider",{model:{value:t.value.count_to_summary,callback:function(e){t.$set(t.value,"count_to_summary",e)},expression:"value.count_to_summary"}})],1),t._v(" "),n("td",[n("input",{directives:[{name:"model",rawName:"v-model",value:t.value.first_entry_date,expression:"value.first_entry_date"}],class:["form-date",!t.validDate&&"is-invalid"],attrs:{type:"date"},domProps:{value:t.value.first_entry_date},on:{input:function(e){e.target.composing||t.$set(t.value,"first_entry_date",e.target.value)}}})]),t._v(" "),n("td",[n("input",{directives:[{name:"model",rawName:"v-model",value:t.value.first_entry_amount,expression:"value.first_entry_amount"}],class:["form-date",!t.validAmount&&"is-invalid"],attrs:{type:"number",step:"0.01"},domProps:{value:t.value.first_entry_amount},on:{input:function(e){e.target.composing||t.$set(t.value,"first_entry_amount",e.target.value)}}})]),t._v(" "),n("td",{staticClass:"trashbin",on:{click:function(e){return t.$emit("delete")}}},[n("i",{staticClass:"fas fa-trash"})])])}),[],!1,null,null,null).exports,p={props:{means:{required:!0,type:Object},currency:{required:!0,type:Number}},components:{TableHeader:r.a,EmptyPlaceholder:s.a,MeansOfPaymentTableRow:v},data:function(){return{header:[{text:"Name",tooltip:"The name of your mean of payment"},{text:"Income",tooltip:"Use in income"},{text:"Outcome",tooltip:"Use in outcome"},{text:"Summary",tooltip:"Count to summary"},{text:"First entry",tooltip:"Date from which to add the starting balance"},{text:"Starting balance",tooltip:"Amount which to use for the first entry"},{}],content:{},contentCopy:{},submitted:!1}},computed:{equalContent:function(){return _.isEqual(this.content,this.contentCopy)},canSave:function(){var t=[];for(var e in this.content)t=t.concat(this.content[e].map((function(t){var e=t.name&&t.name.length<=32,n=t.first_entry_date&&(new Date(t.first_entry_date).getTime()<=new Date(t.date_limit).getTime()||null===t.date_limit),a=""!==t.first_entry_amount&&Math.abs(Number(t.first_entry_amount))<1e11;return e&&n&&a})));return!t.length||!!t.reduce((function(t,e){return t&&e}))}},methods:{create:function(){this.content[this.currency].push({id:0,currency_id:this.currency,income_mean:!1,outcome_mean:!1,count_to_summary:!1,first_entry_date:"",first_entry_amount:0,date_limit:null})},remove:function(t){this.content[this.currency].splice(t,1)},reset:function(){this.content=_.cloneDeep(this.contentCopy)},submit:function(){var t=this;this.submitted=!0,axios.post("/webapi/settings/means",{data:this.content}).then((function(e){var n=e.data;t.content=n.data,t.contentCopy=_.cloneDeep(n.data),t.submitted=!1})).catch((function(e){console.log(e),t.submitted=!1}))},refreshTooltip:function(){this.$nextTick((function(){$('[data-toggle="tooltip"]').tooltip()}))}},beforeMount:function(){this.content=_.cloneDeep(this.means),this.contentCopy=_.cloneDeep(this.means)},mounted:function(){this.refreshTooltip()},updated:function(){this.refreshTooltip()}},f=Object(c.a)(p,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"card"},[t._m(0),t._v(" "),n("div",{staticClass:"card-body"},[n("div",[t.content[t.currency].length?n("div",{staticClass:"table-responsive-xl"},[n("table",{staticClass:"responsive-table-hover table-themed"},[n("TableHeader",{attrs:{cells:t.header}}),t._v(" "),n("tbody",t._l(t.content[t.currency],(function(e,a){return n("MeansOfPaymentTableRow",{key:a,attrs:{index:a},on:{delete:function(e){return t.remove(a)}},model:{value:t.content[t.currency][a],callback:function(e){t.$set(t.content[t.currency],a,e)},expression:"content[currency][i]"}})})),1)],1)]):n("EmptyPlaceholder")],1),t._v(" "),n("hr",{staticClass:"hr"}),t._v(" "),n("div",{staticClass:"row"},[n("div",{staticClass:"col-md-4"},[n("button",{staticClass:"big-button-primary",attrs:{disabled:t.submitted},on:{click:t.create}},[t._v("\n                        New mean of payment\n                    ")])]),t._v(" "),n("div",{staticClass:"col-md-4 my-2 my-md-0"},[n("button",{staticClass:"big-button-danger",attrs:{disabled:t.submitted},on:{click:t.reset}},[t._v("\n                        Reset changes\n                    ")])]),t._v(" "),n("div",{staticClass:"col-md-4"},[n("button",{class:["big-button-success",!t.equalContent&&!t.submitted&&"text-stand-out"],attrs:{disabled:!t.canSave||t.submitted},on:{click:t.submit}},[t.submitted?n("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):n("div",[t._v("\n                            Save changes\n                        ")])])])])])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"card-header-flex"},[e("div",{staticClass:"card-header-text"},[e("i",{staticClass:"fas fa-coins"}),this._v("\n                Means of Payment\n            ")])])}],!1,null,null,null).exports,h={components:{Loading:a.a,CategoriesComponent:d,MeansOfPaymentComponent:f},data:function(){return{ready:!1,currentCurrency:1,currencies:[],categories:{},means:{}}},mounted:function(){var t=this;axios.get("/webapi/settings",{}).then((function(e){var n=e.data;t.currencies=n.currencies,t.categories=n.categories,t.means=n.means,t.currentCurrency=n.lastCurrency,t.ready=!0}))}},y=Object(c.a)(h,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"card"},[n("div",{staticClass:"card-header-flex"},[t._m(0),t._v(" "),t.ready?n("div",{staticClass:"d-flex"},[n("div",{staticClass:"h4 my-auto mr-3"},[t._v("Currency:")]),t._v(" "),n("select",{directives:[{name:"model",rawName:"v-model",value:t.currentCurrency,expression:"currentCurrency"}],staticClass:"form-control",on:{change:function(e){var n=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.currentCurrency=e.target.multiple?n:n[0]}}},t._l(t.currencies,(function(e){return n("option",{key:e.id,domProps:{value:e.id}},[t._v("\n                    "+t._s(e.ISO)+"\n                ")])})),0)]):t._e()]),t._v(" "),n("div",{staticClass:"card-body"},[t.ready?n("div",[n("CategoriesComponent",{attrs:{categories:t.categories,currency:t.currentCurrency}}),t._v(" "),n("MeansOfPaymentComponent",{staticClass:"mt-3",attrs:{means:t.means,currency:t.currentCurrency}})],1):n("Loading")],1)])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"card-header-text"},[e("i",{staticClass:"fas fa-cog"}),this._v("\n            Settings\n        ")])}],!1,null,null,null);e.default=y.exports},3:function(t,e,n){"use strict";var a=n(1),r=Object(a.a)({},(function(){var t=this.$createElement;this._self._c;return this._m(0)}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",[e("h1",{staticClass:"text-center"},[this._v("Nothing to see here, for now...")])])}],!1,null,null,null);e.a=r.exports},7:function(t,e,n){"use strict";var a={props:{cells:Array}},r=n(1),s=Object(r.a)(a,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("thead",[n("tr",t._l(t.cells,(function(e,a){return n("th",{key:a,class:e.text&&"h5 font-weight-bold",attrs:{scope:e.text&&"col","data-toggle":e.tooltip&&"tooltip","data-placement":e.tooltip&&"bottom",title:e.tooltip}},[t._v("\n            "+t._s(e.text)+"\n        ")])})),0)])}),[],!1,null,null,null);e.a=s.exports},8:function(t,e,n){"use strict";var a={props:{value:Boolean,disabled:Boolean,name:String},methods:{emitEvents:function(t){this.$emit("input",t.currentTarget.checked),this.$emit("htmlElement",t.currentTarget)}}},r=n(1),s=Object(r.a)(a,(function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"slider-checkbox"},[e("label",{staticClass:"switch m-0"},[e("input",{attrs:{type:"checkbox",disabled:this.disabled,name:this.name},domProps:{checked:this.value},on:{change:this.emitEvents}}),this._v(" "),e("span",{staticClass:"slider round"})])])}),[],!1,null,null,null);e.a=s.exports}});
//# sourceMappingURL=settings.js.map