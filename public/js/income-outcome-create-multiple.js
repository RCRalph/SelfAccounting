!function(e){var t={};function n(a){if(t[a])return t[a].exports;var r=t[a]={i:a,l:!1,exports:{}};return e[a].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,a){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:a})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(n.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(a,r,function(t){return e[t]}.bind(null,r));return a},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=210)}({1:function(e,t,n){"use strict";function a(e,t,n,a,r,i,s,o){var c,u="function"==typeof e?e.options:e;if(t&&(u.render=t,u.staticRenderFns=n,u._compiled=!0),a&&(u.functional=!0),i&&(u._scopeId="data-v-"+i),s?(c=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),r&&r.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(s)},u._ssrRegister=c):r&&(c=o?function(){r.call(this,(u.functional?this.parent:this).$root.$options.shadowRoot)}:r),c)if(u.functional){u._injectStyles=c;var l=u.render;u.render=function(e,t){return c.call(t),l(e,t)}}else{var d=u.beforeCreate;u.beforeCreate=d?[].concat(d,c):[c]}return{exports:e,options:u}}n.d(t,"a",(function(){return a}))},151:function(e,t,n){"use strict";var a={props:{currencies:Array,used:Array,cash:Object,value:Object,sums:Object,type:String,userscash:Object},data:function(){return{currentCurrency:1}},computed:{availableCurrencies:function(){var e=this;return this.currencies.filter((function(t){return e.used.includes(t.id)}))},currentSum:function(){var e=this;for(var t in this.value)if(!this.isValidCashAmount(this.value[t],t))return 0;return Math.round(1e3*this.cash[this.currentCurrency].map((function(e){return{id:e.id,value:e.value}})).map((function(t){return e.value[t.id]*t.value})).reduce((function(e,t){return e+t})),3)/1e3}},methods:{isValidCashAmount:function(e,t){return""!==e&&(e=Number(e),!isNaN(e)&&(e>=0&&Math.floor(e)==e&&e<Math.pow(2,63)&&("outcome"==this.type&&(null==this.userscash[t]||this.userscash[t]>=e)||"income"==this.type)))}},mounted:function(){this.currentCurrency=this.used[0]},watch:{value:function(){this.$emit("input",this.value)}}},r=n(1),i=Object(r.a)(a,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"card"},[n("div",{staticClass:"card-header-flex"},[e._m(0),e._v(" "),e.used.length>1?n("div",{staticClass:"d-flex"},[n("div",{staticClass:"h4 my-auto mr-3"},[e._v("Currency:")]),e._v(" "),n("select",{directives:[{name:"model",rawName:"v-model",value:e.currentCurrency,expression:"currentCurrency"}],staticClass:"form-control",on:{change:function(t){var n=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.currentCurrency=t.target.multiple?n:n[0]}}},e._l(e.availableCurrencies,(function(t){return n("option",{key:t.id,domProps:{value:t.id}},[e._v("\n                    "+e._s(t.ISO)+"\n                ")])})),0)]):e._e()]),e._v(" "),n("div",{staticClass:"card-body"},[n("div",{staticClass:"row"},[n("div",{staticClass:"w-100 table-responsive-lg"},[n("table",{staticClass:"table-themed responsive-table-hover"},[e._m(1),e._v(" "),n("tbody",e._l(e.cash[e.currentCurrency],(function(t,a){return n("tr",{key:a},[n("th",{staticClass:"h5 font-weight-bold",attrs:{scope:"row"}},[e._v("\n                                "+e._s(Number(t.value))+" "+e._s(e.currencies[e.currentCurrency-1].ISO)+"\n                            ")]),e._v(" "),n("td",[n("input",{directives:[{name:"model",rawName:"v-model",value:e.value[t.id],expression:"value[item.id]"}],class:["cash-input",!e.isValidCashAmount(e.value[t.id],t.id)&&"is-invalid"],attrs:{type:"number",step:"1",min:"0"},domProps:{value:e.value[t.id]},on:{input:function(n){n.target.composing||e.$set(e.value,t.id,n.target.value)}}})]),e._v(" "),n("td",{staticClass:"h5 font-weight-bold"},[e._v("\n                                "+e._s(e.isValidCashAmount(e.value[t.id],t.id)?Math.round(t.value*e.value[t.id]*1e3)/1e3:0)+"\n                                "+e._s(e.currencies[e.currentCurrency-1].ISO)+"\n                            ")])])})),0)])])]),e._v(" "),n("div",{staticClass:"row h3 font-weight-bold"},[n("div",{staticClass:"col-6 text-right"},[e._v("Sum:")]),e._v(" "),n("div",{staticClass:"col-6 "},[e._v(e._s(e.currentSum)+" "+e._s(e.currencies[e.currentCurrency-1].ISO))])]),e._v(" "),n("div",{staticClass:"row h3 font-weight-bold"},[n("div",{staticClass:"col-6 text-right"},[e._v("Sum from "+e._s(this.type)+":")]),e._v(" "),n("div",{staticClass:"col-6 "},[e._v(e._s(e.sums[e.currentCurrency])+" "+e._s(e.currencies[e.currentCurrency-1].ISO))])]),e._v(" "),n("hr",{staticClass:"hr-dashed w-75"}),e._v(" "),n("div",{class:["row","h3","font-weight-bold",e.sums[e.currentCurrency]-e.currentSum!=0?"text-danger":"text-success"]},[n("div",{staticClass:"col-6 text-right"},[e._v("Difference:")]),e._v(" "),n("div",{staticClass:"col-6"},[e._v("\n                    "+e._s(e.sums[e.currentCurrency]-e.currentSum>0?"+":"")+e._s(Math.round(1e3*(e.sums[e.currentCurrency]-e.currentSum))/1e3)+"\n                    "+e._s(e.currencies[e.currentCurrency-1].ISO)+"\n                ")])])])])}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"card-header-text"},[t("i",{staticClass:"fas fa-money-bill-wave"}),this._v("\n            Enter cash\n        ")])},function(){var e=this.$createElement,t=this._self._c||e;return t("thead",[t("th",{staticClass:"h4 font-weight-bold",attrs:{scope:"col"}},[this._v("Face value")]),this._v(" "),t("th",{staticClass:"h4 font-weight-bold",attrs:{scope:"col"}},[this._v("Amount")]),this._v(" "),t("th",{staticClass:"h4 font-weight-bold",attrs:{scope:"col"}},[this._v("Value")])])}],!1,null,null,null);t.a=i.exports},2:function(e,t,n){"use strict";var a=n(1),r=Object(a.a)({},(function(){var e=this.$createElement;this._self._c;return this._m(0)}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"d-flex justify-content-center my-2"},[t("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[t("span",{staticClass:"sr-only"},[this._v("Loading...")])])])}],!1,null,null,null);t.a=r.exports},210:function(e,t,n){e.exports=n(211)},211:function(e,t,n){Vue.component("income-outcome-create-multiple-component",n(253).default);new Vue({el:"#app"})},253:function(e,t,n){"use strict";n.r(t);var a=n(2),r=n(4),i=n(9),s=n(151);function o(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,a)}return n}function c(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function u(e){return function(e){if(Array.isArray(e))return l(e)}(e)||function(e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}(e)||function(e,t){if(!e)return;if("string"==typeof e)return l(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return l(e,t)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function l(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,a=new Array(t);n<t;n++)a[n]=e[n];return a}var d={props:["type"],components:{Loading:a.a,InputGroup:r.a,CreateForm:i.a,IncomeOutcomeCashComponent:s.a},data:function(){return{ready:!1,submitted:!1,currencies:{},categories:{},means:{},titles:[],cash:{},cashMeans:{},usersCash:[],common:{date:"",title:"",amount:"",price:"",currency_id:0,category_id:0,mean_id:0},cashUsed:{},data:[]}},computed:{minDate:function(){var e=this,t=this.means[this.data.currency_id];if(null==t)return"1970-01-01";var n=t.filter((function(t){return t.id==e.data.mean_id}));return n.length?n[0].first_entry_date:"1970-01-01"},validDate:function(){var e=this.common.date;return""===e||!isNaN(Date.parse(e))&&new Date(e)>=new Date(this.minDate).getTime()},validTitle:function(){return this.common.title.length<=64},validAmount:function(){var e=Number(this.common.amount);return""===this.common.amount||!isNaN(e)&&e<1e6&&e>0},validPrice:function(){var e=Number(this.common.price);return""===this.common.price||!isNaN(e)&&e<1e11&&e>0},commonObject:function(){var e={};for(var t in this.common)this.common[t]&&(e[t]=this.common[t]);return e},currenciesWithNA:function(){return[{id:0,ISO:"N / A"}].concat(u(this.currencies))},canSubmit:function(){var e=this;if(!(this.validDate&&this.validTitle&&this.validAmount&&this.validPrice))return!1;var t=this.data.map((function(t){var n=e.means[e.data.currency_id],a="1970-01-01";if(null!=n){var r=n.filter((function(t){return t.id==e.data.mean_id}));a=r.length?r[0].first_entry_date:"1970-01-01"}var i=""!==t.date&&!isNaN(Date.parse(t.date))&&new Date(t.date)>=new Date(a).getTime(),s=t.title.length&&t.title.length<=64,o={amount:Number(t.amount),price:Number(t.price)},c=!isNaN(o.amount)&&o.amount<=1e6&&o.amount>0,u=!isNaN(o.price)&&o.price<=1e11&&o.price>0;if(e.cashMeanUsed)for(var l in e.cashUsed)if(!e.isValidCashAmount(e.cashUsed[l],l))return!1;return i&&s&&c&&u}));return 0!=t.length&&t.reduce((function(e,t){return e&&t}))},cashMeanUsed:function(){var e=this,t=[];return this.data.forEach((function(n){null==n.mean_id||n.mean_id!=e.cashMeans[n.currency_id]||t.includes(n.currency_id)||t.push(n.currency_id)})),t},sumObject:function(){var e=this,t={};return this.data.forEach((function(n){null!=n.mean_id&&n.mean_id==e.cashMeans[n.currency_id]&&(null==t[n.currency_id]?t[n.currency_id]=Math.round(n.amount*n.price*1e3)/1e3:t[n.currency_id]+=Math.round(n.amount*n.price*1e3)/1e3)})),t}},methods:{newEntry:function(){this.data.push(function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?o(Object(n),!0).forEach((function(t){c(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):o(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}({},this.common))},deleteEntry:function(e){this.data.splice(e,1)},updateData:function(e){var t=this;setTimeout((function(){"currency_id"==e?(t.common.category_id=0,t.common.mean_id=0,t.data.forEach((function(e,n){t.data[n].category_id=0,t.data[n].mean_id=0,t.data[n].currency_id=t.common.currency_id||1}))):t.data.forEach((function(n,a){t.data[a][e]=t.common[e]}))}),"currency_id"==e&&10)},submit:function(){var e=this;this.submitted=!0;var t={data:this.data};this.cashMeanUsed&&(t.cash=[],this.cashMeanUsed.map((function(n){e.cash[n].map((function(e){return e.id})).forEach((function(n){e.cashUsed[n]>0&&t.cash.push({id:n,amount:e.cashUsed[n]})}))}))),axios.post("/webapi/".concat(this.type,"/store"),t).then((function(){window.location.href="/".concat(e.type)})).catch((function(t){console.log(t),e.submitted=!1}))},isValidCashAmount:function(e,t){return""!==e&&(e=Number(e),!isNaN(e)&&(e>=0&&Math.floor(e)==e&&e<Math.pow(2,63)&&("outcome"==this.type&&(null==this.usersCash[t]||this.usersCash[t]>=e)||"income"==this.type)))}},mounted:function(){var e=this;axios.get("/webapi/".concat(this.type,"/create"),{}).then((function(t){var n=t.data.data;e.titles=n.titles,e.currencies=n.currencies,e.common.currency_id=n.last.currency,e.categories=n.categories,e.categories[0]=[{id:0,name:"N / A"}],e.means=n.means,e.means[0]=[{id:0,name:"N / A",first_entry_date:null}],null!=n.cash&&function(){e.cash=n.cash,e.cashMeans=n.cashMeans,e.usersCash=n.usersCash;var t={};for(var a in e.cash)e.cash[a].forEach((function(e){t[e.id]=0}));e.cashUsed=t}(),e.data.push({date:"",title:"",amount:"",price:"",currency_id:n.last.currency,category_id:n.last.category||0,mean_id:n.last.mean||0}),e.ready=!0}))}},m=n(1),p=Object(m.a)(d,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"card"},[n("div",{staticClass:"card-header-flex"},[n("div",{staticClass:"card-header-text"},[n("i",{class:["fas","income"==e.type?"fa-sign-in-alt":"fa-sign-out-alt"]}),e._v("\n                Add multiple "+e._s(e.type)+"\n            ")])]),e._v(" "),n("div",{staticClass:"card-body"},[e.ready?n("div",[n("div",{staticClass:"h1 font-weight-bold text-center mb-3"},[e._v("Common values")]),e._v(" "),n("InputGroup",{attrs:{name:"date",type:"date",min:e.minDate,invalid:!e.validDate},on:{input:function(t){return e.updateData("date")}},model:{value:e.common.date,callback:function(t){e.$set(e.common,"date",t)},expression:"common.date"}}),e._v(" "),n("InputGroup",{attrs:{name:"title",datalistName:"titles",datalist:e.titles,maxlength:"64",placeholder:"Your title here...",invalid:!e.validTitle},on:{input:function(t){return e.updateData("title")}},model:{value:e.common.title,callback:function(t){e.$set(e.common,"title",t)},expression:"common.title"}}),e._v(" "),n("InputGroup",{attrs:{name:"amount",type:"number",step:"0.001",placeholder:"Your amount here...",invalid:!e.validAmount},on:{input:function(t){return e.updateData("amount")}},model:{value:e.common.amount,callback:function(t){e.$set(e.common,"amount",t)},expression:"common.amount"}}),e._v(" "),n("div",{staticClass:"form-group row"},[e._m(0),e._v(" "),n("div",{staticClass:"col-md-4 col-sm-12"},[n("input",{directives:[{name:"model",rawName:"v-model",value:e.common.price,expression:"common.price"}],class:["form-control",!e.validPrice&&"is-invalid"],attrs:{type:"number",step:"0.01",placeholder:"Your price here..."},domProps:{value:e.common.price},on:{input:[function(t){t.target.composing||e.$set(e.common,"price",t.target.value)},function(t){return e.updateData("price")}]}}),e._v(" "),e.validPrice?e._e():n("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[n("strong",[e._v("Price is invalid")])])]),e._v(" "),n("div",{staticClass:"col-md-3 col-sm-12 mt-2 mt-md-0"},[n("select",{directives:[{name:"model",rawName:"v-model",value:e.common.currency_id,expression:"common.currency_id"}],staticClass:"form-control",on:{input:function(t){return e.updateData("currency_id")},change:function(t){var n=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.$set(e.common,"currency_id",t.target.multiple?n:n[0])}}},e._l(e.currenciesWithNA,(function(t,a){return n("option",{key:a,domProps:{value:t.id}},[e._v("\n                                "+e._s(t.ISO)+"\n                            ")])})),0)])]),e._v(" "),n("InputGroup",{attrs:{type:"select",name:"category",selectOptions:e.categories[e.common.currency_id],optionValueKey:"id",optionTextKey:"name"},on:{input:function(t){return e.updateData("category_id")}},model:{value:e.common.category_id,callback:function(t){e.$set(e.common,"category_id",t)},expression:"common.category_id"}}),e._v(" "),n("InputGroup",{attrs:{type:"select",name:"mean of payment",selectOptions:e.means[e.common.currency_id],optionValueKey:"id",optionTextKey:"name"},on:{input:function(t){return e.updateData("mean_id")}},model:{value:e.common.mean_id,callback:function(t){e.$set(e.common,"mean_id",t)},expression:"common.mean_id"}}),e._v(" "),n("hr",{staticClass:"hr"}),e._v(" "),e._l(e.data,(function(t,a){return n("div",{key:a},[n("div",{staticClass:"h4 font-weight-bold ml-3 mb-3"},[e._v("Entry #"+e._s(a+1))]),e._v(" "),n("CreateForm",{attrs:{currencies:e.currencies,categories:e.categories,means:e.means,titles:e.titles,common:e.commonObject},model:{value:e.data[a],callback:function(t){e.$set(e.data,a,t)},expression:"data[i]"}}),e._v(" "),n("div",{staticClass:"row"},[n("div",{staticClass:"col-12 col-sm-4 offset-sm-4"},[n("button",{staticClass:"big-button-danger",on:{click:function(t){return e.deleteEntry(a)}}},[n("i",{staticClass:"fas fa-trash"}),e._v("\n                                Delete\n                            ")])])]),e._v(" "),a+1<e.data.length?n("hr",{staticClass:"hr-dashed"}):e._e()],1)})),e._v(" "),e.cashMeanUsed.length?n("div",[n("hr",{staticClass:"hr"}),e._v(" "),n("IncomeOutcomeCashComponent",{attrs:{currencies:e.currencies,used:e.cashMeanUsed,cash:e.cash,sums:e.sumObject,type:e.type,userscash:e.usersCash},model:{value:e.cashUsed,callback:function(t){e.cashUsed=t},expression:"cashUsed"}})],1):e._e(),e._v(" "),e.data.length?n("hr",{staticClass:"hr"}):e._e(),e._v(" "),n("div",{staticClass:"row"},[n("div",{staticClass:"col-sm-6 my-2 my-sm-0"},[n("button",{staticClass:"big-button-primary",attrs:{type:"button",disabled:e.submitted},on:{click:e.newEntry}},[e._v("\n                            New "+e._s(e.type)+"\n                        ")])]),e._v(" "),n("div",{staticClass:"col-sm-6 my-2 my-sm-0"},[n("button",{staticClass:"big-button-success",attrs:{type:"button",disabled:e.submitted||!e.canSubmit},on:{click:e.submit}},[e.submitted?n("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):n("div",[e._v("Submit")])])])])],2):n("Loading")],1)])}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[t("div",{staticClass:"h5 font-weight-bold m-md-0"},[this._v("Price")])])}],!1,null,null,null);t.default=p.exports},4:function(e,t,n){"use strict";var a={props:{name:{required:!0,type:String},type:{required:!1,type:String,default:"text"},value:{required:!0},invalid:{required:!1,type:Boolean,default:!1},min:{required:!1},max:{required:!1},minlength:{required:!1},maxlength:{required:!1},prepend:{required:!1,type:String},append:{required:!1,type:String},autocomplete:{required:!1,type:String,default:"off"},placeholder:{required:!1,type:String},datalistName:{required:!1,type:String},datalist:{required:!1,type:Array},step:{required:!1,type:String,default:"1"},selectOptions:{required:!1,type:Array},optionValueKey:{required:!1,type:String},optionTextKey:{required:!1,type:String},disabled:{required:!1,type:Boolean,default:!1}},filters:{capitalize:function(e){return(e.charAt(0).toUpperCase()+e.slice(1)).replace("_"," ")}},watch:{value:function(){this.$emit("input",this.value)}}},r=n(1),i=Object(r.a)(a,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"form-group row"},[n("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[n("div",{staticClass:"h5 font-weight-bold m-md-0"},[e._v(e._s(e._f("capitalize")("password_confirmation"!=e.name?e.name:"confirm")))])]),e._v(" "),"select"==e.type?n("div",{staticClass:"col-md-7"},[n("select",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],staticClass:"form-control",attrs:{disabled:e.disabled},on:{change:function(t){var n=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.value=t.target.multiple?n:n[0]}}},e._l(e.selectOptions,(function(t,a){return n("option",{key:a,domProps:{value:t[e.optionValueKey],selected:e.value==t[e.optionValueKey]||1==e.selectOptions.length}},[e._v("\n                    "+e._s(t[e.optionTextKey])+"\n                ")])})),0)]):n("div",{class:["col-md-7",(e.prepend||e.append)&&"input-group"]},[e.prepend?n("div",{staticClass:"input-group-prepend"},[n("div",{staticClass:"input-group-text"},[e._v(e._s(e.prepend))])]):e._e(),e._v(" "),"checkbox"===e.type?n("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"checkbox"},domProps:{checked:Array.isArray(e.value)?e._i(e.value,null)>-1:e.value},on:{change:function(t){var n=e.value,a=t.target,r=!!a.checked;if(Array.isArray(n)){var i=e._i(n,null);a.checked?i<0&&(e.value=n.concat([null])):i>-1&&(e.value=n.slice(0,i).concat(n.slice(i+1)))}else e.value=r}}}):"radio"===e.type?n("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"radio"},domProps:{checked:e._q(e.value,null)},on:{change:function(t){e.value=null}}}):n("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:e.type},domProps:{value:e.value},on:{input:function(t){t.target.composing||(e.value=t.target.value)}}}),e._v(" "),e.datalist?n("datalist",{attrs:{id:e.datalistName}},e._l(e.datalist,(function(t,a){return n("option",{key:a},[e._v(e._s(t))])})),0):e._e(),e._v(" "),e.append?n("div",{staticClass:"input-group-append"},[n("div",{staticClass:"input-group-text"},[e._v(e._s(e.append))])]):e._e(),e._v(" "),e.invalid?n("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[n("strong",[e._v(e._s(e._f("capitalize")(e.name))+" is invalid")])]):e._e()])])}),[],!1,null,null,null);t.a=i.exports},9:function(e,t,n){"use strict";var a=n(4),r={props:{value:{required:!0,type:Object},currencies:{required:!0,type:Array},categories:{required:!0,type:Object},means:{required:!0,type:Object},common:{required:!1,type:Object},titles:{required:!1,type:Array}},components:{InputGroup:a.a},data:function(){return{changed:{date:!1,title:!1,amount:!1,price:!1}}},computed:{minDate:function(){var e=this,t=this.means[this.value.currency_id];if(null==t)return"1970-01-01";var n=t.filter((function(t){return t.id==e.value.mean_id}));return n.length?n[0].first_entry_date:"1970-01-01"},validDate:function(){var e=this.value.date;return""!==e&&!isNaN(Date.parse(e))&&new Date(e)>=new Date(this.minDate).getTime()},validTitle:function(){var e=this.value.title;return e.length&&e.length<=64},validAmount:function(){var e=Number(this.value.amount);return!isNaN(e)&&e<1e6&&e>0},validPrice:function(){var e=Number(this.value.price);return!isNaN(e)&e<1e11&&e>0}},methods:{currencyChange:function(){this.value.category_id=0,this.value.mean_id=0},meanChange:function(){var e=this.value.date;this.value.date=""===e||isNaN(Date.parse(e))||new Date(e).getTime()<new Date(this.minDate).getTime()?this.minDate:e}}},i=n(1),s=Object(i.a)(r,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("InputGroup",{attrs:{name:"date",type:"date",min:e.minDate,invalid:e.changed.date&&!e.validDate,disabled:e.common&&e.common.hasOwnProperty("date")},on:{input:function(t){e.changed.date=!0}},model:{value:e.value.date,callback:function(t){e.$set(e.value,"date",t)},expression:"value.date"}}),e._v(" "),n("InputGroup",{attrs:{name:"title",datalistName:"titles",datalist:e.titles,maxlength:"64",placeholder:"Your title here...",invalid:e.changed.title&&!e.validTitle,disabled:e.common&&e.common.hasOwnProperty("title")},on:{input:function(t){e.changed.title=!0}},model:{value:e.value.title,callback:function(t){e.$set(e.value,"title",t)},expression:"value.title"}}),e._v(" "),n("InputGroup",{attrs:{name:"amount",type:"number",step:"0.001",placeholder:"Your amount here...",invalid:e.changed.amount&&!e.validAmount,disabled:e.common&&e.common.hasOwnProperty("amount")},on:{input:function(t){e.changed.amount=!0}},model:{value:e.value.amount,callback:function(t){e.$set(e.value,"amount",t)},expression:"value.amount"}}),e._v(" "),n("div",{staticClass:"form-group row"},[e._m(0),e._v(" "),n("div",{staticClass:"col-md-4 col-sm-12"},[n("input",{directives:[{name:"model",rawName:"v-model",value:e.value.price,expression:"value.price"}],class:["form-control",e.changed.price&&!e.validPrice&&"is-invalid"],attrs:{type:"number",step:"0.01",placeholder:"Your price here...",disabled:e.common&&e.common.hasOwnProperty("price")},domProps:{value:e.value.price},on:{input:[function(t){t.target.composing||e.$set(e.value,"price",t.target.value)},function(t){e.changed.price=!0}]}}),e._v(" "),e.validPrice?e._e():n("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[n("strong",[e._v("Price is invalid")])])]),e._v(" "),n("div",{staticClass:"col-md-3 col-sm-12 mt-2 mt-md-0"},[n("select",{directives:[{name:"model",rawName:"v-model",value:e.value.currency_id,expression:"value.currency_id"}],staticClass:"form-control",attrs:{disabled:e.common&&e.common.hasOwnProperty("currency_id")},on:{change:[function(t){var n=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.$set(e.value,"currency_id",t.target.multiple?n:n[0])},e.currencyChange]}},e._l(e.currencies,(function(t,a){return n("option",{key:a,domProps:{value:t.id}},[e._v("\n                    "+e._s(t.ISO)+"\n                ")])})),0)])]),e._v(" "),n("InputGroup",{attrs:{name:"value",value:Math.round(e.value.amount*e.value.price*100)/100,disabled:!0}}),e._v(" "),n("InputGroup",{attrs:{type:"select",name:"category",selectOptions:e.categories[e.value.currency_id],optionValueKey:"id",optionTextKey:"name",disabled:e.common&&e.common.hasOwnProperty("category_id")},model:{value:e.value.category_id,callback:function(t){e.$set(e.value,"category_id",t)},expression:"value.category_id"}}),e._v(" "),n("InputGroup",{attrs:{type:"select",name:"mean of payment",selectOptions:e.means[e.value.currency_id],optionValueKey:"id",optionTextKey:"name",disabled:e.common&&e.common.hasOwnProperty("mean_id")},on:{input:e.meanChange},model:{value:e.value.mean_id,callback:function(t){e.$set(e.value,"mean_id",t)},expression:"value.mean_id"}})],1)}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[t("div",{staticClass:"h5 font-weight-bold m-md-0"},[this._v("Price")])])}],!1,null,null,null);t.a=s.exports}});
//# sourceMappingURL=income-outcome-create-multiple.js.map