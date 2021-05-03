!function(e){var t={};function a(n){if(t[n])return t[n].exports;var i=t[n]={i:n,l:!1,exports:{}};return e[n].call(i.exports,i,i.exports,a),i.l=!0,i.exports}a.m=e,a.c=t,a.d=function(e,t,n){a.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.t=function(e,t){if(1&t&&(e=a(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(a.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)a.d(n,i,function(t){return e[t]}.bind(null,i));return n},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="/",a(a.s=208)}({1:function(e,t,a){"use strict";function n(e,t,a,n,i,r,s,c){var l,u="function"==typeof e?e.options:e;if(t&&(u.render=t,u.staticRenderFns=a,u._compiled=!0),n&&(u.functional=!0),r&&(u._scopeId="data-v-"+r),s?(l=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),i&&i.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(s)},u._ssrRegister=l):i&&(l=c?function(){i.call(this,(u.functional?this.parent:this).$root.$options.shadowRoot)}:i),l)if(u.functional){u._injectStyles=l;var o=u.render;u.render=function(e,t){return l.call(t),o(e,t)}}else{var d=u.beforeCreate;u.beforeCreate=d?[].concat(d,l):[l]}return{exports:e,options:u}}a.d(t,"a",(function(){return n}))},151:function(e,t,a){"use strict";var n={props:{currencies:Array,used:Array,cash:Object,value:Object,sums:Object,type:String,usersCash:Object},data:function(){return{currentCurrency:1}},computed:{availableCurrencies:function(){var e=this;return this.currencies.filter((function(t){return e.used.includes(t.id)}))},currentSum:function(){var e=this;for(var t in this.value)if(!this.isValidCashAmount(this.value[t],t))return 0;return Math.round(1e3*this.cash[this.currentCurrency].map((function(e){return{id:e.id,value:e.value}})).map((function(t){return e.value[t.id]*t.value})).reduce((function(e,t){return e+t})),3)/1e3}},methods:{isValidCashAmount:function(e,t){return""!==e&&(e=Number(e),!isNaN(e)&&(e>=0&&Math.floor(e)==e&&e<Math.pow(2,63)&&("outcome"==this.type&&(null==this.userscash[t]&&0==e||this.userscash[t]>=e)||"income"==this.type)))}},mounted:function(){this.currentCurrency=this.used[0]},watch:{value:function(){this.$emit("input",this.value)}}},i=a(1),r=Object(i.a)(n,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card"},[a("div",{staticClass:"card-header-flex"},[e._m(0),e._v(" "),e.used.length>1?a("div",{staticClass:"d-flex"},[a("div",{staticClass:"h4 my-auto mr-3"},[e._v("Currency:")]),e._v(" "),a("select",{directives:[{name:"model",rawName:"v-model",value:e.currentCurrency,expression:"currentCurrency"}],staticClass:"form-control",on:{change:function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.currentCurrency=t.target.multiple?a:a[0]}}},e._l(e.availableCurrencies,(function(t){return a("option",{key:t.id,domProps:{value:t.id}},[e._v("\n                    "+e._s(t.ISO)+"\n                ")])})),0)]):e._e()]),e._v(" "),a("div",{staticClass:"card-body"},[a("div",{staticClass:"row"},[a("div",{staticClass:"w-100 table-responsive-lg"},[a("table",{staticClass:"table-themed responsive-table-hover"},[e._m(1),e._v(" "),a("tbody",e._l(e.cash[e.currentCurrency],(function(t,n){return a("tr",{key:n},[a("th",{staticClass:"h5 font-weight-bold",attrs:{scope:"row"}},[e._v("\n                                "+e._s(Number(t.value))+" "+e._s(e.currencies[e.currentCurrency-1].ISO)+"\n                            ")]),e._v(" "),a("td",[a("input",{directives:[{name:"model",rawName:"v-model",value:e.value[t.id],expression:"value[item.id]"}],class:["cash-input",!e.isValidCashAmount(e.value[t.id],t.id)&&"is-invalid"],attrs:{type:"number",step:"1",min:"0"},domProps:{value:e.value[t.id]},on:{input:function(a){a.target.composing||e.$set(e.value,t.id,a.target.value)}}})]),e._v(" "),a("td",{staticClass:"h5 font-weight-bold"},[e._v("\n                                "+e._s(e.isValidCashAmount(e.value[t.id],t.id)?Math.round(t.value*e.value[t.id]*1e3)/1e3:0)+"\n                                "+e._s(e.currencies[e.currentCurrency-1].ISO)+"\n                            ")])])})),0)])])]),e._v(" "),a("div",{staticClass:"row h3 font-weight-bold"},[a("div",{staticClass:"col-6 text-right"},[e._v("Sum:")]),e._v(" "),a("div",{staticClass:"col-6 "},[e._v(e._s(e.currentSum)+" "+e._s(e.currencies[e.currentCurrency-1].ISO))])]),e._v(" "),a("div",{staticClass:"row h3 font-weight-bold"},[a("div",{staticClass:"col-6 text-right"},[e._v("Sum from "+e._s(this.type)+":")]),e._v(" "),a("div",{staticClass:"col-6 "},[e._v(e._s(e.sums[e.currentCurrency])+" "+e._s(e.currencies[e.currentCurrency-1].ISO))])]),e._v(" "),a("hr",{staticClass:"hr-dashed w-75"}),e._v(" "),a("div",{class:["row","h3","font-weight-bold",e.sums[e.currentCurrency]-e.currentSum!=0?"text-danger":"text-success"]},[a("div",{staticClass:"col-6 text-right"},[e._v("Difference:")]),e._v(" "),a("div",{staticClass:"col-6"},[e._v("\n                    "+e._s(e.sums[e.currentCurrency]-e.currentSum>0?"+":"")+e._s(Math.round(1e3*(e.sums[e.currentCurrency]-e.currentSum))/1e3)+"\n                    "+e._s(e.currencies[e.currentCurrency-1].ISO)+"\n                ")])])])])}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"card-header-text"},[t("i",{staticClass:"fas fa-money-bill-wave"}),this._v("\n            Enter cash\n        ")])},function(){var e=this.$createElement,t=this._self._c||e;return t("thead",[t("th",{staticClass:"h4 font-weight-bold",attrs:{scope:"col"}},[this._v("Face value")]),this._v(" "),t("th",{staticClass:"h4 font-weight-bold",attrs:{scope:"col"}},[this._v("Amount")]),this._v(" "),t("th",{staticClass:"h4 font-weight-bold",attrs:{scope:"col"}},[this._v("Value")])])}],!1,null,null,null);t.a=r.exports},2:function(e,t,a){"use strict";var n=a(1),i=Object(n.a)({},(function(){var e=this.$createElement;this._self._c;return this._m(0)}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"d-flex justify-content-center my-2"},[t("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[t("span",{staticClass:"sr-only"},[this._v("Loading...")])])])}],!1,null,null,null);t.a=i.exports},208:function(e,t,a){e.exports=a(209)},209:function(e,t,a){Vue.component("income-outcome-create-one-component",a(255).default);new Vue({el:"#app"})},255:function(e,t,a){"use strict";a.r(t);var n=a(2),i=a(9),r=a(151),s={props:["type"],components:{Loading:n.a,CreateForm:i.a,IncomeOutcomeCashComponent:r.a},data:function(){return{ready:!1,submitted:!1,currencies:{},categories:{},means:{},titles:[],cash:{},cashMeans:{},usersCash:[],data:{date:"",title:"",amount:"",price:"",currency_id:"",category_id:"",mean_id:""},cashUsed:{}}},computed:{minDate:function(){var e=this,t=this.means[this.data.currency_id];if(null==t)return"1970-01-01";var a=t.filter((function(t){return t.id==e.data.mean_id}));return a.length?a[0].first_entry_date:"1970-01-01"},canSubmit:function(){var e=""!==this.data.date&&!isNaN(Date.parse(this.data.date))&&new Date(this.data.date)>=new Date(this.minDate).getTime(),t=this.data.title.length&&this.data.title.length<=64,a={amount:Number(this.data.amount),price:Number(this.data.price)},n=!isNaN(a.amount)&&a.amount<=1e6&&a.amount>0,i=!isNaN(a.price)&&a.price<=1e11&&a.price>0;if(this.cashMeanUsed)for(var r in this.cashUsed)if(!this.isValidCashAmount(this.cashUsed[r],r))return!1;return e&&t&&n&&i},cashMeanUsed:function(){return this.data.mean_id==this.cashMeans[this.data.currency_id]},sumObject:function(){var e={};return e[this.data.currency_id]=Math.round(this.data.amount*this.data.price*1e3)/1e3,e}},methods:{submit:function(){var e=this;this.submitted=!0;var t={data:[this.data]};this.cashMeanUsed&&(t.cash=[],this.cash[this.data.currency_id].map((function(e){return e.id})).forEach((function(a){e.cashUsed[a]>0&&t.cash.push({id:a,amount:e.cashUsed[a]})}))),axios.post("/webapi/".concat(this.type,"/store"),t).then((function(){window.location.href="/".concat(e.type)})).catch((function(t){console.log(t),e.submitted=!1}))},isValidCashAmount:function(e,t){return""!==e&&(e=Number(e),!isNaN(e)&&(e>=0&&Math.floor(e)==e&&e<Math.pow(2,63)&&("outcome"==this.type&&(null==this.usersCash[t]||this.usersCash[t]>=e)||"income"==this.type)))}},mounted:function(){var e=this;axios.get("/webapi/".concat(this.type,"/create"),{}).then((function(t){var a=t.data.data;e.currencies=a.currencies,e.categories=a.categories,e.means=a.means,e.titles=a.titles,e.data.currency_id=a.last.currency,e.data.category_id=a.last.category||0,e.data.mean_id=a.last.mean||0,null!=a.cash&&function(){e.cash=a.cash,e.cashMeans=a.cashMeans,e.usersCash=a.usersCash;var t={};for(var n in e.cash)e.cash[n].forEach((function(e){t[e.id]=0}));e.cashUsed=t}(),e.data.date=(new Date(e.minDate).getTime()>(new Date).getTime()?new Date(e.minDate):new Date).toISOString().split("T")[0],e.ready=!0}))}},c=a(1),l=Object(c.a)(s,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card"},[a("div",{staticClass:"card-header-flex"},[a("div",{staticClass:"card-header-text"},[a("i",{class:["fas","income"==e.type?"fa-sign-in-alt":"fa-sign-out-alt"]}),e._v("\n            Add single "+e._s(e.type)+"\n        ")])]),e._v(" "),a("div",{staticClass:"card-body"},[e.ready?a("div",[a("CreateForm",{attrs:{currencies:e.currencies,categories:e.categories,means:e.means,titles:e.titles},model:{value:e.data,callback:function(t){e.data=t},expression:"data"}}),e._v(" "),e.cashMeanUsed?a("div",[a("hr",{staticClass:"hr"}),e._v(" "),a("IncomeOutcomeCashComponent",{attrs:{currencies:e.currencies,used:[e.data.currency_id],cash:e.cash,sums:e.sumObject,type:e.type,usersCash:e.usersCash},model:{value:e.cashUsed,callback:function(t){e.cashUsed=t},expression:"cashUsed"}})],1):e._e(),e._v(" "),a("hr",{staticClass:"hr"}),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-12 col-sm-4 offset-sm-4"},[a("button",{staticClass:"big-button-success",attrs:{type:"button",disabled:e.submitted||!e.canSubmit},on:{click:e.submit}},[e.submitted?a("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):a("div",[e._v("Submit")])])])])],1):a("Loading")],1)])}),[],!1,null,null,null);t.default=l.exports},4:function(e,t,a){"use strict";var n={props:{name:{required:!0,type:String},type:{required:!1,type:String,default:"text"},value:{required:!0},invalid:{required:!1,type:Boolean,default:!1},min:{required:!1},max:{required:!1},minlength:{required:!1},maxlength:{required:!1},prepend:{required:!1,type:String},append:{required:!1,type:String},autocomplete:{required:!1,type:String,default:"off"},placeholder:{required:!1,type:String},datalistName:{required:!1,type:String},datalist:{required:!1,type:Array},step:{required:!1,type:String,default:"1"},selectOptions:{required:!1,type:Array},optionValueKey:{required:!1,type:String},optionTextKey:{required:!1,type:String},disabled:{required:!1,type:Boolean,default:!1}},filters:{capitalize:function(e){return(e.charAt(0).toUpperCase()+e.slice(1)).replace("_"," ")}},watch:{value:function(){this.$emit("input",this.value)}}},i=a(1),r=Object(i.a)(n,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"form-group row"},[a("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[a("div",{staticClass:"h5 font-weight-bold m-md-0"},[e._v(e._s(e._f("capitalize")("password_confirmation"!=e.name?e.name:"confirm")))])]),e._v(" "),"select"==e.type?a("div",{staticClass:"col-md-7"},[a("select",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{disabled:e.disabled},on:{change:function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.value=t.target.multiple?a:a[0]}}},e._l(e.selectOptions,(function(t,n){return a("option",{key:n,domProps:{value:t[e.optionValueKey],selected:e.value==t[e.optionValueKey]||1==e.selectOptions.length}},[e._v("\n                    "+e._s(t[e.optionTextKey])+"\n                ")])})),0),e._v(" "),e.invalid?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v(e._s(e._f("capitalize")(e.name))+" is invalid")])]):e._e()]):a("div",{class:["col-md-7",(e.prepend||e.append)&&"input-group"]},[e.prepend?a("div",{staticClass:"input-group-prepend"},[a("div",{staticClass:"input-group-text"},[e._v(e._s(e.prepend))])]):e._e(),e._v(" "),"checkbox"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"checkbox"},domProps:{checked:Array.isArray(e.value)?e._i(e.value,null)>-1:e.value},on:{change:function(t){var a=e.value,n=t.target,i=!!n.checked;if(Array.isArray(a)){var r=e._i(a,null);n.checked?r<0&&(e.value=a.concat([null])):r>-1&&(e.value=a.slice(0,r).concat(a.slice(r+1)))}else e.value=i}}}):"radio"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"radio"},domProps:{checked:e._q(e.value,null)},on:{change:function(t){e.value=null}}}):a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:e.type},domProps:{value:e.value},on:{input:function(t){t.target.composing||(e.value=t.target.value)}}}),e._v(" "),e.datalist?a("datalist",{attrs:{id:e.datalistName}},e._l(e.datalist,(function(t,n){return a("option",{key:n},[e._v(e._s(t))])})),0):e._e(),e._v(" "),e.append?a("div",{staticClass:"input-group-append"},[a("div",{staticClass:"input-group-text"},[e._v(e._s(e.append))])]):e._e(),e._v(" "),e.invalid?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v(e._s(e._f("capitalize")(e.name))+" is invalid")])]):e._e()])])}),[],!1,null,null,null);t.a=r.exports},9:function(e,t,a){"use strict";var n=a(4),i={props:{value:{required:!0,type:Object},currencies:{required:!0,type:Array},categories:{required:!0,type:Object},means:{required:!0,type:Object},common:{required:!1,type:Object},titles:{required:!1,type:Array}},components:{InputGroup:n.a},data:function(){return{changed:{date:!1,title:!1,amount:!1,price:!1}}},computed:{minDate:function(){var e=this,t=this.means[this.value.currency_id];if(null==t)return"1970-01-01";var a=t.filter((function(t){return t.id==e.value.mean_id}));return a.length?a[0].first_entry_date:"1970-01-01"},validDate:function(){var e=this.value.date;return""!==e&&!isNaN(Date.parse(e))&&new Date(e)>=new Date(this.minDate).getTime()},validTitle:function(){var e=this.value.title;return e.length&&e.length<=64},validAmount:function(){var e=Number(this.value.amount);return!isNaN(e)&&e<1e6&&e>0},validPrice:function(){var e=Number(this.value.price);return!isNaN(e)&e<1e11&&e>0}},methods:{currencyChange:function(){this.value.category_id=0,this.value.mean_id=0},meanChange:function(){var e=this.value.date;this.value.date=""===e||isNaN(Date.parse(e))||new Date(e).getTime()<new Date(this.minDate).getTime()?this.minDate:e}}},r=a(1),s=Object(r.a)(i,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("InputGroup",{attrs:{name:"date",type:"date",min:e.minDate,invalid:e.changed.date&&!e.validDate,disabled:e.common&&e.common.hasOwnProperty("date")},on:{input:function(t){e.changed.date=!0}},model:{value:e.value.date,callback:function(t){e.$set(e.value,"date",t)},expression:"value.date"}}),e._v(" "),a("InputGroup",{attrs:{name:"title",datalistName:"titles",datalist:e.titles,maxlength:"64",placeholder:"Your title here...",invalid:e.changed.title&&!e.validTitle,disabled:e.common&&e.common.hasOwnProperty("title")},on:{input:function(t){e.changed.title=!0}},model:{value:e.value.title,callback:function(t){e.$set(e.value,"title",t)},expression:"value.title"}}),e._v(" "),a("InputGroup",{attrs:{name:"amount",type:"number",step:"0.001",placeholder:"Your amount here...",invalid:e.changed.amount&&!e.validAmount,disabled:e.common&&e.common.hasOwnProperty("amount")},on:{input:function(t){e.changed.amount=!0}},model:{value:e.value.amount,callback:function(t){e.$set(e.value,"amount",t)},expression:"value.amount"}}),e._v(" "),a("div",{staticClass:"form-group row"},[e._m(0),e._v(" "),a("div",{staticClass:"col-md-4 col-sm-12"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.value.price,expression:"value.price"}],class:["form-control",e.changed.price&&!e.validPrice&&"is-invalid"],attrs:{type:"number",step:"0.01",placeholder:"Your price here...",disabled:e.common&&e.common.hasOwnProperty("price")},domProps:{value:e.value.price},on:{input:[function(t){t.target.composing||e.$set(e.value,"price",t.target.value)},function(t){e.changed.price=!0}]}}),e._v(" "),e.changed.price&&!e.validPrice?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v("Price is invalid")])]):e._e()]),e._v(" "),a("div",{staticClass:"col-md-3 col-sm-12 mt-2 mt-md-0"},[a("select",{directives:[{name:"model",rawName:"v-model",value:e.value.currency_id,expression:"value.currency_id"}],staticClass:"form-control",attrs:{disabled:e.common&&e.common.hasOwnProperty("currency_id")},on:{change:[function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.$set(e.value,"currency_id",t.target.multiple?a:a[0])},e.currencyChange]}},e._l(e.currencies,(function(t,n){return a("option",{key:n,domProps:{value:t.id}},[e._v("\n                    "+e._s(t.ISO)+"\n                ")])})),0)])]),e._v(" "),a("InputGroup",{attrs:{name:"value",value:Math.round(e.value.amount*e.value.price*100)/100+" "+e.currencies[e.value.currency_id-1].ISO,disabled:!0}}),e._v(" "),a("InputGroup",{attrs:{type:"select",name:"category",selectOptions:e.categories[e.value.currency_id],optionValueKey:"id",optionTextKey:"name",disabled:e.common&&e.common.hasOwnProperty("category_id")},model:{value:e.value.category_id,callback:function(t){e.$set(e.value,"category_id",t)},expression:"value.category_id"}}),e._v(" "),a("InputGroup",{attrs:{type:"select",name:"mean of payment",selectOptions:e.means[e.value.currency_id],optionValueKey:"id",optionTextKey:"name",disabled:e.common&&e.common.hasOwnProperty("mean_id")},on:{input:e.meanChange},model:{value:e.value.mean_id,callback:function(t){e.$set(e.value,"mean_id",t)},expression:"value.mean_id"}})],1)}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[t("div",{staticClass:"h5 font-weight-bold m-md-0"},[this._v("Price")])])}],!1,null,null,null);t.a=s.exports}});
//# sourceMappingURL=income-outcome-create-one.js.map