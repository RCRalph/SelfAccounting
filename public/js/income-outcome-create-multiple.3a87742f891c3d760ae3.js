(()=>{var e={793:(e,t,a)=>{"use strict";a.d(t,{Z:()=>v});function n(e,t,a,n,r,i,s,l){var c,o="function"==typeof e?e.options:e;if(t&&(o.render=t,o.staticRenderFns=a,o._compiled=!0),n&&(o.functional=!0),i&&(o._scopeId="data-v-"+i),s?(c=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),r&&r.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(s)},o._ssrRegister=c):r&&(c=l?function(){r.call(this,(o.functional?this.parent:this).$root.$options.shadowRoot)}:r),c)if(o.functional){o._injectStyles=c;var u=o.render;o.render=function(e,t){return c.call(t),u(e,t)}}else{var d=o.beforeCreate;o.beforeCreate=d?[].concat(d,c):[c]}return{exports:e,options:o}}const r=n({},(function(){var e=this,t=e.$createElement;e._self._c;return e._m(0)}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"d-flex justify-content-center my-2"},[a("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[a("span",{staticClass:"sr-only"},[e._v("Loading...")])])])}],!1,null,null,null).exports;const i=n({props:{value:{required:!0,type:Boolean},disabled:{required:!1,type:Boolean,default:!1},name:{required:!1,type:String,default:""},choice:{type:Boolean,required:!1,default:!1}},methods:{emitEvents:function(e){this.$emit("input",e.currentTarget.checked),this.$emit("htmlElement",e.currentTarget)}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{class:e.choice?"slider-choice":"slider-checkbox"},[a("label",{staticClass:"switch m-0"},[a("input",{attrs:{type:"checkbox",disabled:e.disabled,name:e.name},domProps:{checked:e.value},on:{change:e.emitEvents}}),e._v(" "),a("span",{staticClass:"slider round"})])])}),[],!1,null,null,null).exports;const s=n({props:{name:{required:!0,type:String},type:{required:!1,type:String,default:"text"},value:{required:!0},invalid:{required:!1,type:Boolean,default:!1},min:{required:!1},max:{required:!1},minlength:{required:!1},maxlength:{required:!1},prepend:{required:!1,type:String},append:{required:!1,type:String},autocomplete:{required:!1,type:String,default:"off"},placeholder:{required:!1,type:String},datalistName:{required:!1,type:String},datalist:{required:!1,type:Array},step:{required:!1,type:String,default:"1"},selectOptions:{required:!1,type:Array},optionValueKey:{required:!1,type:String},optionTextKey:{required:!1,type:String},optionKeyAndValue:{required:!1,type:Boolean,default:!1},capitalizeOptions:{required:!1,type:Boolean,default:!1},nullValue:{required:!1,type:String,default:""},disabled:{required:!1,type:Boolean,default:!1}},components:{SliderCheckbox:i},methods:{capitalize:function(e){return(e.charAt(0).toUpperCase()+e.slice(1)).replace("_"," ")}},watch:{value:function(){this.$emit("input",this.value)}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{class:!(e.prepend||e.append)&&"input-group-row"},[e.prepend||e.append?e._e():a("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[a("div",{staticClass:"h5 fw-bold m-md-0"},[e._v("\n                "+e._s(e.capitalize("password_confirmation"!=e.name?e.name:"confirm"))+"\n            ")])]),e._v(" "),"select"==e.type?a("div",{staticClass:"col-md-7"},[e.optionKeyAndValue?a("select",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{disabled:e.disabled},on:{change:function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.value=t.target.multiple?a:a[0]}}},[e.nullValue?a("option",{domProps:{value:null}},[e._v(e._s(e.nullValue))]):e._e(),e._v(" "),e._l(e.selectOptions,(function(t,n){return a("option",{key:n,domProps:{value:t,selected:e.value==t||1==e.selectOptions.length}},[e._v("\n                    "+e._s(e.capitalizeOptions?e.capitalize(t):t)+"\n                ")])}))],2):a("select",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{disabled:e.disabled},on:{change:function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.value=t.target.multiple?a:a[0]}}},[e.nullValue?a("option",{domProps:{value:null}},[e._v(e._s(e.nullValue))]):e._e(),e._v(" "),e._l(e.selectOptions,(function(t,n){return a("option",{key:n,domProps:{value:t[e.optionValueKey],selected:e.value==t[e.optionValueKey]||1==e.selectOptions.length}},[e._v("\n                    "+e._s(t[e.optionTextKey])+"\n                ")])}))],2),e._v(" "),e.invalid?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v(e._s(e.capitalize(e.name))+" is invalid")])]):e._e()]):e.prepend||e.append?a("div",{staticClass:"input-group-row"},[a("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[a("div",{staticClass:"h5 fw-bold m-md-0"},[e._v("\n                    "+e._s(e.capitalize("password_confirmation"!=e.name?e.name:"confirm"))+"\n                ")])]),e._v(" "),a("div",{staticClass:"col-md-7"},[a("div",{staticClass:"input-group"},[e.prepend?a("span",{staticClass:"input-group-text"},[e._v("\n                        "+e._s(e.prepend)+"\n                    ")]):e._e(),e._v(" "),"checkbox"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"checkbox"},domProps:{checked:Array.isArray(e.value)?e._i(e.value,null)>-1:e.value},on:{change:function(t){var a=e.value,n=t.target,r=!!n.checked;if(Array.isArray(a)){var i=e._i(a,null);n.checked?i<0&&(e.value=a.concat([null])):i>-1&&(e.value=a.slice(0,i).concat(a.slice(i+1)))}else e.value=r}}}):"radio"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"radio"},domProps:{checked:e._q(e.value,null)},on:{change:function(t){e.value=null}}}):a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:e.type},domProps:{value:e.value},on:{input:function(t){t.target.composing||(e.value=t.target.value)}}}),e._v(" "),e.append?a("span",{staticClass:"input-group-text"},[e._v("\n                        "+e._s(e.append)+"\n                    ")]):e._e(),e._v(" "),e.datalist?a("datalist",{attrs:{id:e.datalistName}},e._l(e.datalist,(function(t,n){return a("option",{key:n},[e._v(e._s(t))])})),0):e._e(),e._v(" "),e.invalid?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v(e._s(e.capitalize(e.name))+" is invalid")])]):e._e()])])]):a("div",{staticClass:"col-md-7"},["checkbox"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"checkbox"},domProps:{checked:Array.isArray(e.value)?e._i(e.value,null)>-1:e.value},on:{change:function(t){var a=e.value,n=t.target,r=!!n.checked;if(Array.isArray(a)){var i=e._i(a,null);n.checked?i<0&&(e.value=a.concat([null])):i>-1&&(e.value=a.slice(0,i).concat(a.slice(i+1)))}else e.value=r}}}):"radio"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"radio"},domProps:{checked:e._q(e.value,null)},on:{change:function(t){e.value=null}}}):a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:e.type},domProps:{value:e.value},on:{input:function(t){t.target.composing||(e.value=t.target.value)}}}),e._v(" "),e.datalist?a("datalist",{attrs:{id:e.datalistName}},e._l(e.datalist,(function(t,n){return a("option",{key:n},[e._v(e._s(t))])})),0):e._e(),e._v(" "),e.invalid?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v(e._s(e.capitalize(e.name))+" is invalid")])]):e._e()])])}),[],!1,null,null,null).exports;const l=n({props:{value:{required:!0,type:Object},currencies:{required:!0,type:Array},categories:{required:!0,type:Object},means:{required:!0,type:Object},common:{required:!1,type:Object},titles:{required:!1,type:Array},minDateRestriction:{required:!1,type:Boolean,default:!0},ignoreNegativePrice:{required:!1,type:Boolean,default:!1}},components:{InputGroup:s},data:function(){return{changed:{date:!1,title:!1,amount:!1,price:!1}}},computed:{minDate:function(){var e=this,t=this.means[this.value.currency_id];if(null==t||!this.minDateRestriction)return"1970-01-01";var a=t.filter((function(t){return t.id==e.value.mean_id}));return a.length?a[0].first_entry_date:"1970-01-01"},validDate:function(){var e=this.value.date;return""!==e&&!isNaN(Date.parse(e))&&new Date(e).getTime()>=new Date(this.minDate).getTime()},validTitle:function(){var e=this.value.title;return e.length&&e.length<=64},validAmount:function(){var e=Number(this.value.amount);return!isNaN(e)&&e<=9999999.999&&e>0},validPrice:function(){var e=Number(this.value.price);return!isNaN(e)&Math.abs(e)<=99999999999.99&&(e>0||this.ignoreNegativePrice)}},methods:{currencyChange:function(){this.value.category_id=null,this.value.mean_id=null},meanChange:function(){var e=this.value.date;this.value.date=""===e||isNaN(Date.parse(e))||new Date(e).getTime()<new Date(this.minDate).getTime()?this.minDate:e}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("InputGroup",{attrs:{name:"date",type:"date",min:e.minDate,invalid:e.changed.date&&!e.validDate},on:{input:function(t){e.changed.date=!0}},model:{value:e.value.date,callback:function(t){e.$set(e.value,"date",t)},expression:"value.date"}}),e._v(" "),a("InputGroup",{attrs:{name:"title",datalistName:"titles",datalist:e.titles,maxlength:"64",placeholder:"Your title here...",invalid:e.changed.title&&!e.validTitle},on:{input:function(t){e.changed.title=!0}},model:{value:e.value.title,callback:function(t){e.$set(e.value,"title",t)},expression:"value.title"}}),e._v(" "),a("InputGroup",{attrs:{name:"amount",type:"number",step:"0.001",placeholder:"Your amount here...",invalid:e.changed.amount&&!e.validAmount},on:{input:function(t){e.changed.amount=!0}},model:{value:e.value.amount,callback:function(t){e.$set(e.value,"amount",t)},expression:"value.amount"}}),e._v(" "),a("div",{staticClass:"input-group-row"},[e._m(0),e._v(" "),a("div",{staticClass:"col-md-4 col-sm-12"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.value.price,expression:"value.price"}],class:["form-control",e.changed.price&&!e.validPrice&&"is-invalid"],attrs:{type:"number",step:"0.01",placeholder:"Your price here...",disabled:e.common&&e.common.hasOwnProperty("price")},domProps:{value:e.value.price},on:{input:[function(t){t.target.composing||e.$set(e.value,"price",t.target.value)},function(t){e.changed.price=!0}]}}),e._v(" "),e.changed.price&&!e.validPrice?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v("Price is invalid")])]):e._e()]),e._v(" "),a("div",{staticClass:"col-md-3 col-sm-12 mt-2 mt-md-0"},[a("select",{directives:[{name:"model",rawName:"v-model",value:e.value.currency_id,expression:"value.currency_id"}],staticClass:"form-control",attrs:{disabled:e.common&&e.common.hasOwnProperty("currency_id")},on:{change:[function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.$set(e.value,"currency_id",t.target.multiple?a:a[0])},e.currencyChange]}},e._l(e.currencies,(function(t,n){return a("option",{key:n,domProps:{value:t.id}},[e._v("\n                    "+e._s(t.ISO)+"\n                ")])})),0)])]),e._v(" "),a("InputGroup",{attrs:{name:"value",value:Math.round(e.value.amount*e.value.price*100)/100+" "+e.currencies[e.value.currency_id-1].ISO,disabled:!0}}),e._v(" "),a("InputGroup",{attrs:{type:"select",name:"category",selectOptions:e.categories[e.value.currency_id],optionValueKey:"id",optionTextKey:"name",nullValue:"N/A"},model:{value:e.value.category_id,callback:function(t){e.$set(e.value,"category_id",t)},expression:"value.category_id"}}),e._v(" "),a("InputGroup",{attrs:{type:"select",name:"mean of payment",selectOptions:e.means[e.value.currency_id],optionValueKey:"id",optionTextKey:"name",nullValue:"N/A"},on:{input:e.meanChange},model:{value:e.value.mean_id,callback:function(t){e.$set(e.value,"mean_id",t)},expression:"value.mean_id"}})],1)}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[a("div",{staticClass:"h5 fw-bold m-md-0"},[e._v("Price")])])}],!1,null,null,null).exports;var c=n({props:{currencies:Array,used:Array,cash:Object,value:Object,sums:Object,type:String,usersCash:Object},data:function(){return{currentCurrency:1}},computed:{availableCurrencies:function(){var e=this;return this.currencies.filter((function(t){return e.used.includes(t.id)}))},currentSum:function(){var e=this;for(var t in this.value)if(!this.isValidCashAmount(this.value[t],t))return 0;return Math.round(100*this.cash[this.currentCurrency].map((function(e){return{id:e.id,value:e.value}})).map((function(t){return e.value[t.id]*t.value})).reduce((function(e,t){return e+t})),3)/100}},methods:{isValidCashAmount:function(e,t){return""!==e&&(e=Number(e),!isNaN(e)&&(e>=0&&Math.floor(e)==e&&e<Math.pow(2,63)&&("outcome"==this.type&&(null==this.usersCash[t]&&0==e||this.usersCash[t]>=e)||"income"==this.type)))}},mounted:function(){this.currentCurrency=this.used[0]},watch:{value:function(){this.$emit("input",this.value)}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card"},[a("div",{staticClass:"card-header-flex"},[e._m(0),e._v(" "),e.used.length>1?a("div",{staticClass:"d-flex"},[a("div",{staticClass:"currency-text"},[e._v("Currency:")]),e._v(" "),a("select",{directives:[{name:"model",rawName:"v-model",value:e.currentCurrency,expression:"currentCurrency"}],staticClass:"form-control",on:{change:function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.currentCurrency=t.target.multiple?a:a[0]}}},e._l(e.availableCurrencies,(function(t){return a("option",{key:t.id,domProps:{value:t.id}},[e._v("\n                    "+e._s(t.ISO)+"\n                ")])})),0)]):e._e()]),e._v(" "),a("div",{staticClass:"card-body"},[a("div",{staticClass:"row"},[a("div",{staticClass:"w-100 table-responsive-lg"},[a("table",{staticClass:"table-themed responsive-table-hover"},[e._m(1),e._v(" "),a("tbody",e._l(e.cash[e.currentCurrency],(function(t,n){return a("tr",{key:n},[a("th",{staticClass:"h5 fw-bold",attrs:{scope:"row"}},[e._v("\n                                "+e._s(Number(t.value))+" "+e._s(e.currencies[e.currentCurrency-1].ISO)+"\n                            ")]),e._v(" "),a("td",[a("input",{directives:[{name:"model",rawName:"v-model",value:e.value[t.id],expression:"value[item.id]"}],class:["cash-input",!e.isValidCashAmount(e.value[t.id],t.id)&&"is-invalid"],attrs:{type:"number",step:"1",min:"0"},domProps:{value:e.value[t.id]},on:{input:function(a){a.target.composing||e.$set(e.value,t.id,a.target.value)}}})]),e._v(" "),a("td",{staticClass:"h5 fw-bold"},[e._v("\n                                "+e._s(e.isValidCashAmount(e.value[t.id],t.id)?Math.round(t.value*e.value[t.id]*100)/100:0)+"\n                                "+e._s(e.currencies[e.currentCurrency-1].ISO)+"\n                            ")])])})),0)])])]),e._v(" "),a("div",{staticClass:"row h3 fw-bold"},[a("div",{staticClass:"col-6 text-end"},[e._v("Sum:")]),e._v(" "),a("div",{staticClass:"col-6 "},[e._v(e._s(e.currentSum)+" "+e._s(e.currencies[e.currentCurrency-1].ISO))])]),e._v(" "),a("div",{staticClass:"row h3 fw-bold"},[a("div",{staticClass:"col-6 text-end"},[e._v("Sum from "+e._s(this.type)+":")]),e._v(" "),a("div",{staticClass:"col-6 "},[e._v(e._s(e.sums[e.currentCurrency])+" "+e._s(e.currencies[e.currentCurrency-1].ISO))])]),e._v(" "),a("hr",{staticClass:"hr-dashed w-100"}),e._v(" "),a("div",{class:["row","h3","fw-bold",e.sums[e.currentCurrency]-e.currentSum!=0?"text-danger":"text-success"]},[a("div",{staticClass:"col-6 text-end"},[e._v("Difference:")]),e._v(" "),a("div",{staticClass:"col-6"},[e._v("\n                    "+e._s(e.sums[e.currentCurrency]-e.currentSum>0?"+":"")+e._s(Math.round(100*(e.sums[e.currentCurrency]-e.currentSum))/100)+"\n                    "+e._s(e.currencies[e.currentCurrency-1].ISO)+"\n                ")])])])])}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card-header-text"},[a("i",{staticClass:"fas fa-money-bill-wave"}),e._v("\n            Enter cash\n        ")])},function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("thead",[a("th",{staticClass:"h4 fw-bold",attrs:{scope:"col"}},[e._v("Face value")]),e._v(" "),a("th",{staticClass:"h4 fw-bold",attrs:{scope:"col"}},[e._v("Amount")]),e._v(" "),a("th",{staticClass:"h4 fw-bold",attrs:{scope:"col"}},[e._v("Value")])])}],!1,null,null,null);function o(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,n)}return a}function u(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function d(e){return function(e){if(Array.isArray(e))return m(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,t){if(!e)return;if("string"==typeof e)return m(e,t);var a=Object.prototype.toString.call(e).slice(8,-1);"Object"===a&&e.constructor&&(a=e.constructor.name);if("Map"===a||"Set"===a)return Array.from(e);if("Arguments"===a||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a))return m(e,t)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function m(e,t){(null==t||t>e.length)&&(t=e.length);for(var a=0,n=new Array(t);a<t;a++)n[a]=e[a];return n}const v=n({props:["type"],components:{Loading:r,InputGroup:s,CreateForm:l,IncomeOutcomeCashComponent:c.exports},data:function(){return{ready:!1,submitted:!1,currencies:{},categories:{},means:{},titles:[],cash:{},cashMeans:{},usersCash:[],lastCurrency:1,common:{date:(new Date).toISOString().split("T")[0],title:"",amount:"1",price:"",currency_id:1,category_id:null,mean_id:null},cashUsed:{},data:[],currentCurrencySum:1}},computed:{minDate:function(){var e=this,t=this.means[this.data.currency_id];if(null==t)return"1970-01-01";var a=t.filter((function(t){return t.id==e.data.mean_id}));return a.length&&null!=a.id?a[0].first_entry_date:"1970-01-01"},validDate:function(){var e=this.common.date;return""===e||!isNaN(Date.parse(e))&&new Date(e)>=new Date(this.minDate).getTime()},validTitle:function(){return this.common.title.length<=64},validAmount:function(){var e=Number(this.common.amount);return""===this.common.amount||!isNaN(e)&&e<=9999999.999&&e>0},validPrice:function(){var e=Number(this.common.price);return""===this.common.price||!isNaN(e)&&e<=99999999999.99&&e>0},commonObject:function(){var e={};for(var t in this.common)this.common[t]&&(e[t]=this.common[t]);return e},currenciesWithNA:function(){return[{id:0,ISO:"N/A"}].concat(d(this.currencies))},canSubmit:function(){var e=this;if(!(this.validDate&&this.validTitle&&this.validAmount&&this.validPrice))return!1;var t=this.data.map((function(t){var a=e.means[e.data.currency_id],n="1970-01-01";if(null!=a){var r=a.filter((function(t){return t.id==e.data.mean_id}));n=r.length?r[0].first_entry_date:"1970-01-01"}var i=""!==t.date&&!isNaN(Date.parse(t.date))&&new Date(t.date)>=new Date(n).getTime(),s=t.title.length&&t.title.length<=64,l={amount:Number(t.amount),price:Number(t.price)},c=!isNaN(l.amount)&&l.amount<=9999999.999&&l.amount>0,o=!isNaN(l.price)&&l.price<=99999999999.99&&l.price>0;if(e.cashMeanUsed)for(var u in e.cashUsed)if(!e.isValidCashAmount(e.cashUsed[u],u))return!1;return i&&s&&c&&o}));return 0!=t.length&&t.reduce((function(e,t){return e&&t}))},cashMeanUsed:function(){var e=this,t=[];return this.data.forEach((function(a){null==a.mean_id||a.mean_id!=e.cashMeans[a.currency_id]||t.includes(a.currency_id)||t.push(a.currency_id)})),t},sumObject:function(){var e=this,t={};return this.data.forEach((function(a){null!=a.mean_id&&a.mean_id==e.cashMeans[a.currency_id]&&(null==t[a.currency_id]?t[a.currency_id]=Math.round(a.amount*a.price*100)/100:t[a.currency_id]+=Math.round(a.amount*a.price*100)/100)})),t},usedCurrencies:function(){var e=d(new Set(this.data.map((function(e){return e.currency_id}))));return e.includes(this.currentCurrencySum)||(this.currentCurrencySum=e[0]||1),e},sums:function(){var e={};return this.usedCurrencies.forEach((function(t){return e[t]=0})),this.data.forEach((function(t){return e[t.currency_id]+=Math.round(t.amount*t.price*100)/100})),e}},methods:{newEntry:function(){this.data.push(function(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?o(Object(a),!0).forEach((function(t){u(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):o(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}({},this.common)),0==this.data[this.data.length-1].currency_id&&(this.data[this.data.length-1].currency_id=this.lastCurrency)},deleteEntry:function(e){this.data.splice(e,1)},updateData:function(e){var t=this;setTimeout((function(){"currency_id"==e?(t.common.category_id=null,t.common.mean_id=null,t.data.forEach((function(e,a){t.data[a].category_id=null,t.data[a].mean_id=null,t.data[a].currency_id=t.common.currency_id||1}))):t.data.forEach((function(a,n){t.data[n][e]=t.common[e]}))}),"currency_id"==e&&10)},submit:function(){var e=this;this.submitted=!0;var t={data:this.data};this.cashMeanUsed&&(t.cash=[],this.cashMeanUsed.map((function(a){e.cash[a].map((function(e){return e.id})).forEach((function(a){e.cashUsed[a]>0&&t.cash.push({id:a,amount:e.cashUsed[a]})}))}))),axios.post("/webapi/".concat(this.type,"/store"),t).then((function(){window.location.href="/".concat(e.type)})).catch((function(t){console.log(t),e.submitted=!1}))},isValidCashAmount:function(e,t){return""!==e&&(e=Number(e),!isNaN(e)&&(e>=0&&Math.floor(e)==e&&e<Math.pow(2,63)&&("outcome"==this.type&&(null==this.usersCash[t]||this.usersCash[t]>=e)||"income"==this.type)))}},mounted:function(){var e=this;axios.get("/webapi/".concat(this.type,"/create"),{}).then((function(t){var a=t.data.data;null!=a.cash&&function(){e.cash=a.cash,e.cashMeans=a.cashMeans,e.usersCash=a.usersCash;var t={};for(var n in e.cash)e.cash[n].forEach((function(e){t[e.id]=0}));e.cashUsed=t}(),e.titles=a.titles,e.currencies=a.currencies,e.currentCurrencySum=e.lastCurrency=e.common.currency_id=a.last.currency,e.categories=a.categories,e.means=a.means,e.data.push({date:e.common.date,title:"",amount:e.common.amount,price:"",currency_id:e.lastCurrency,category_id:null!=e.categories[a.last.currency]&&e.categories[a.last.currency].find((function(e){return e.id==a.last.category}))?a.last.category:null,mean_id:null!=e.means[a.last.currency]&&e.means[a.last.currency].find((function(e){return e.id==a.last.mean}))?a.last.mean:null}),e.ready=!0}))}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card"},[a("div",{staticClass:"card-header-flex"},[a("div",{staticClass:"card-header-text"},[a("i",{class:["fas","income"==e.type?"fa-sign-in-alt":"fa-sign-out-alt"]}),e._v("\n                Add multiple "+e._s(e.type)+"s\n            ")])]),e._v(" "),a("div",{staticClass:"card-body"},[e.ready?a("div",[a("div",{staticClass:"h1 fw-bold text-center mb-3"},[e._v("Common values")]),e._v(" "),a("InputGroup",{attrs:{name:"date",type:"date",min:e.minDate,invalid:!e.validDate},on:{input:function(t){return e.updateData("date")}},model:{value:e.common.date,callback:function(t){e.$set(e.common,"date",t)},expression:"common.date"}}),e._v(" "),a("InputGroup",{attrs:{name:"title",datalistName:"titles",datalist:e.titles,maxlength:"64",placeholder:"Your title here...",invalid:!e.validTitle},on:{input:function(t){return e.updateData("title")}},model:{value:e.common.title,callback:function(t){e.$set(e.common,"title",t)},expression:"common.title"}}),e._v(" "),a("InputGroup",{attrs:{name:"amount",type:"number",step:"0.001",placeholder:"Your amount here...",invalid:!e.validAmount},on:{input:function(t){return e.updateData("amount")}},model:{value:e.common.amount,callback:function(t){e.$set(e.common,"amount",t)},expression:"common.amount"}}),e._v(" "),a("div",{staticClass:"input-group-row"},[e._m(0),e._v(" "),a("div",{staticClass:"col-md-4 col-sm-12"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.common.price,expression:"common.price"}],class:["form-control",!e.validPrice&&"is-invalid"],attrs:{type:"number",step:"0.01",placeholder:"Your price here..."},domProps:{value:e.common.price},on:{input:[function(t){t.target.composing||e.$set(e.common,"price",t.target.value)},function(t){return e.updateData("price")}]}}),e._v(" "),e.validPrice?e._e():a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v("Price is invalid")])])]),e._v(" "),a("div",{staticClass:"col-md-3 col-sm-12 mt-2 mt-md-0"},[a("select",{directives:[{name:"model",rawName:"v-model",value:e.common.currency_id,expression:"common.currency_id"}],staticClass:"form-control",on:{input:function(t){return e.updateData("currency_id")},change:function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.$set(e.common,"currency_id",t.target.multiple?a:a[0])}}},e._l(e.currenciesWithNA,(function(t,n){return a("option",{key:n,domProps:{value:t.id}},[e._v("\n                                "+e._s(t.ISO)+"\n                            ")])})),0)])]),e._v(" "),a("InputGroup",{attrs:{type:"select",name:"category",selectOptions:e.categories[e.common.currency_id],optionValueKey:"id",optionTextKey:"name",nullValue:"N/A"},on:{input:function(t){return e.updateData("category_id")}},model:{value:e.common.category_id,callback:function(t){e.$set(e.common,"category_id",t)},expression:"common.category_id"}}),e._v(" "),a("InputGroup",{attrs:{type:"select",name:"mean of payment",selectOptions:e.means[e.common.currency_id],optionValueKey:"id",optionTextKey:"name",nullValue:"N/A"},on:{input:function(t){return e.updateData("mean_id")}},model:{value:e.common.mean_id,callback:function(t){e.$set(e.common,"mean_id",t)},expression:"common.mean_id"}}),e._v(" "),a("hr",{staticClass:"hr"}),e._v(" "),e._l(e.data,(function(t,n){return a("div",{key:n},[a("div",{staticClass:"h4 fw-bold ms-3 mb-3"},[e._v("Entry #"+e._s(n+1))]),e._v(" "),a("CreateForm",{attrs:{currencies:e.currencies,categories:e.categories,means:e.means,titles:e.titles,common:e.commonObject},model:{value:e.data[n],callback:function(t){e.$set(e.data,n,t)},expression:"data[i]"}}),e._v(" "),a("div",{staticClass:"input-group-row"},[a("div",{staticClass:"col-12 col-sm-4 offset-sm-4"},[a("button",{staticClass:"big-button-danger",on:{click:function(t){return e.deleteEntry(n)}}},[a("i",{staticClass:"fas fa-trash"}),e._v("\n                                Delete\n                            ")])])]),e._v(" "),n+1<e.data.length?a("hr",{staticClass:"hr-dashed"}):e._e()],1)})),e._v(" "),e.cashMeanUsed.length?a("div",[a("hr",{staticClass:"hr"}),e._v(" "),a("IncomeOutcomeCashComponent",{attrs:{currencies:e.currencies,used:e.cashMeanUsed,cash:e.cash,sums:e.sumObject,type:e.type,usersCash:e.usersCash},model:{value:e.cashUsed,callback:function(t){e.cashUsed=t},expression:"cashUsed"}})],1):e._e(),e._v(" "),e.data.length?a("div",[a("hr",{staticClass:"hr"}),e._v(" "),a("div",{staticClass:"row mb-3"},[a("div",{staticClass:"col-md-8 offset-md-2"},[a("div",{staticClass:"card"},[a("div",{staticClass:"card-header-flex"},[e._m(1),e._v(" "),e.usedCurrencies.length>1?a("div",{staticClass:"d-flex"},[a("div",{staticClass:"currency-text"},[e._v("Currency:")]),e._v(" "),a("select",{directives:[{name:"model",rawName:"v-model",value:e.currentCurrencySum,expression:"currentCurrencySum"}],staticClass:"form-control",on:{change:function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.currentCurrencySum=t.target.multiple?a:a[0]}}},e._l(e.usedCurrencies,(function(t){return a("option",{key:e.currencies[t-1].id,domProps:{value:e.currencies[t-1].id}},[e._v("\n                                                "+e._s(e.currencies[t-1].ISO)+"\n                                            ")])})),0)]):e._e()]),e._v(" "),a("div",{staticClass:"card-body text-center h2 my-auto"},[e._v("\n                                    "+e._s(e.sums[e.currentCurrencySum].toLocaleString("en").split(",").join(" "))+"\n                                    "+e._s(e.currencies[e.currentCurrencySum-1].ISO)+"\n                                ")])])])]),e._v(" "),a("hr",{staticClass:"hr"})]):e._e(),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-sm-6 my-2 my-sm-0"},[a("button",{staticClass:"big-button-primary",attrs:{type:"button",disabled:e.submitted},on:{click:e.newEntry}},[e._v("\n                            New "+e._s(e.type)+"\n                        ")])]),e._v(" "),a("div",{staticClass:"col-sm-6 my-2 my-sm-0"},[a("button",{staticClass:"big-button-success",attrs:{type:"button",disabled:e.submitted||!e.canSubmit},on:{click:e.submit}},[e.submitted?a("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):a("div",[e._v("Submit")])])])])],2):a("Loading")],1)])}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[a("div",{staticClass:"h5 fw-bold m-md-0"},[e._v("Price")])])},function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card-header-text"},[a("i",{staticClass:"fas fa-calculator"}),e._v("\n                                        Sum\n                                    ")])}],!1,null,null,null).exports}},t={};function a(n){var r=t[n];if(void 0!==r)return r.exports;var i=t[n]={exports:{}};return e[n](i,i.exports,a),i.exports}a.d=(e,t)=>{for(var n in t)a.o(t,n)&&!a.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},a.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{Vue.component("income-outcome-create-multiple-component",a(793).Z);new Vue({el:"#app"})})()})();
//# sourceMappingURL=income-outcome-create-multiple.js.map