(()=>{var e={9021:(e,t,a)=>{"use strict";a.d(t,{Z:()=>y});function i(e,t,a,i,n,l,s,r){var o,u="function"==typeof e?e.options:e;if(t&&(u.render=t,u.staticRenderFns=a,u._compiled=!0),i&&(u.functional=!0),l&&(u._scopeId="data-v-"+l),s?(o=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),n&&n.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(s)},u._ssrRegister=o):n&&(o=r?function(){n.call(this,(u.functional?this.parent:this).$root.$options.shadowRoot)}:n),o)if(u.functional){u._injectStyles=o;var c=u.render;u.render=function(e,t){return o.call(t),c(e,t)}}else{var d=u.beforeCreate;u.beforeCreate=d?[].concat(d,o):[o]}return{exports:e,options:u}}const n=i({props:{value:{required:!0,type:Boolean},disabled:{required:!1,type:Boolean,default:!1},name:{required:!1,type:String,default:""},choice:{type:Boolean,required:!1,default:!1}},methods:{emitEvents:function(e){this.$emit("input",e.currentTarget.checked),this.$emit("htmlElement",e.currentTarget)}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{class:e.choice?"slider-choice":"slider-checkbox"},[a("label",{staticClass:"switch m-0"},[a("input",{attrs:{type:"checkbox",disabled:e.disabled,name:e.name},domProps:{checked:e.value},on:{change:e.emitEvents}}),e._v(" "),a("span",{staticClass:"slider round"})])])}),[],!1,null,null,null).exports;const l=i({props:{name:{required:!0,type:String},type:{required:!1,type:String,default:"text"},value:{required:!0},invalid:{required:!1,type:Boolean,default:!1},min:{required:!1},max:{required:!1},minlength:{required:!1},maxlength:{required:!1},prepend:{required:!1,type:String},append:{required:!1,type:String},autocomplete:{required:!1,type:String,default:"off"},placeholder:{required:!1,type:String},datalistName:{required:!1,type:String},datalist:{required:!1,type:Array},step:{required:!1,type:String,default:"1"},selectOptions:{required:!1,type:Array},optionValueKey:{required:!1,type:String},optionTextKey:{required:!1,type:String},optionKeyAndValue:{required:!1,type:Boolean,default:!1},capitalizeOptions:{required:!1,type:Boolean,default:!1},nullValue:{required:!1,type:String,default:""},disabled:{required:!1,type:Boolean,default:!1}},components:{SliderCheckbox:n},methods:{capitalize:function(e){return(e.charAt(0).toUpperCase()+e.slice(1)).replace("_"," ")}},watch:{value:function(){this.$emit("input",this.value)}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{class:!(e.prepend||e.append)&&"input-group-row"},[e.prepend||e.append?e._e():a("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[a("div",{staticClass:"h5 fw-bold m-md-0"},[e._v("\n                "+e._s(e.capitalize("password_confirmation"!=e.name?e.name:"confirm"))+"\n            ")])]),e._v(" "),"select"==e.type?a("div",{staticClass:"col-md-7"},[e.optionKeyAndValue?a("select",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{disabled:e.disabled},on:{change:function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.value=t.target.multiple?a:a[0]}}},[e.nullValue?a("option",{domProps:{value:null}},[e._v(e._s(e.nullValue))]):e._e(),e._v(" "),e._l(e.selectOptions,(function(t,i){return a("option",{key:i,domProps:{value:t,selected:e.value==t||1==e.selectOptions.length}},[e._v("\n                    "+e._s(e.capitalizeOptions?e.capitalize(t):t)+"\n                ")])}))],2):a("select",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{disabled:e.disabled},on:{change:function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.value=t.target.multiple?a:a[0]}}},[e.nullValue?a("option",{domProps:{value:null}},[e._v(e._s(e.nullValue))]):e._e(),e._v(" "),e._l(e.selectOptions,(function(t,i){return a("option",{key:i,domProps:{value:t[e.optionValueKey],selected:e.value==t[e.optionValueKey]||1==e.selectOptions.length}},[e._v("\n                    "+e._s(t[e.optionTextKey])+"\n                ")])}))],2),e._v(" "),e.invalid?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v(e._s(e.capitalize(e.name))+" is invalid")])]):e._e()]):e.prepend||e.append?a("div",{staticClass:"input-group-row"},[a("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[a("div",{staticClass:"h5 fw-bold m-md-0"},[e._v("\n                    "+e._s(e.capitalize("password_confirmation"!=e.name?e.name:"confirm"))+"\n                ")])]),e._v(" "),a("div",{staticClass:"col-md-7"},[a("div",{staticClass:"input-group"},[e.prepend?a("span",{staticClass:"input-group-text"},[e._v("\n                        "+e._s(e.prepend)+"\n                    ")]):e._e(),e._v(" "),"checkbox"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"checkbox"},domProps:{checked:Array.isArray(e.value)?e._i(e.value,null)>-1:e.value},on:{change:function(t){var a=e.value,i=t.target,n=!!i.checked;if(Array.isArray(a)){var l=e._i(a,null);i.checked?l<0&&(e.value=a.concat([null])):l>-1&&(e.value=a.slice(0,l).concat(a.slice(l+1)))}else e.value=n}}}):"radio"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"radio"},domProps:{checked:e._q(e.value,null)},on:{change:function(t){e.value=null}}}):a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:e.type},domProps:{value:e.value},on:{input:function(t){t.target.composing||(e.value=t.target.value)}}}),e._v(" "),e.append?a("span",{staticClass:"input-group-text"},[e._v("\n                        "+e._s(e.append)+"\n                    ")]):e._e(),e._v(" "),e.datalist?a("datalist",{attrs:{id:e.datalistName}},e._l(e.datalist,(function(t,i){return a("option",{key:i},[e._v(e._s(t))])})),0):e._e(),e._v(" "),e.invalid?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v(e._s(e.capitalize(e.name))+" is invalid")])]):e._e()])])]):a("div",{staticClass:"col-md-7"},["checkbox"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"checkbox"},domProps:{checked:Array.isArray(e.value)?e._i(e.value,null)>-1:e.value},on:{change:function(t){var a=e.value,i=t.target,n=!!i.checked;if(Array.isArray(a)){var l=e._i(a,null);i.checked?l<0&&(e.value=a.concat([null])):l>-1&&(e.value=a.slice(0,l).concat(a.slice(l+1)))}else e.value=n}}}):"radio"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"radio"},domProps:{checked:e._q(e.value,null)},on:{change:function(t){e.value=null}}}):a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:e.type},domProps:{value:e.value},on:{input:function(t){t.target.composing||(e.value=t.target.value)}}}),e._v(" "),e.datalist?a("datalist",{attrs:{id:e.datalistName}},e._l(e.datalist,(function(t,i){return a("option",{key:i},[e._v(e._s(t))])})),0):e._e(),e._v(" "),e.invalid?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v(e._s(e.capitalize(e.name))+" is invalid")])]):e._e()])])}),[],!1,null,null,null).exports;const s=i({props:{left:String,right:String,value:Boolean},components:{SliderCheckbox:n},watch:{value:function(){this.$emit("input",this.value)}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"row my-4"},[a("div",{staticClass:"col-5 h5 text-end fw-bold mb-0",domProps:{innerHTML:e._s(e.left)}}),e._v(" "),a("div",{staticClass:"col-2 d-flex justify-content-center align-items-center"},[a("SliderCheckbox",{attrs:{choice:!0},model:{value:e.value,callback:function(t){e.value=t},expression:"value"}})],1),e._v(" "),a("div",{staticClass:"col-5 h5 fw-bold mb-0",domProps:{innerHTML:e._s(e.right)}})])}),[],!1,null,null,null).exports;const r=i({props:{value:{type:Object,required:!0}},components:{InputGroup:l,SliderChoice:s},data:function(){return{changed:{title:!1}}},computed:{validTitle:function(){return!this.changed.title||"string"==typeof this.value.title&&this.value.title.length>0&&this.value.title.length<=64}},watch:{value:function(){this.$emit("input",this.value)}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("InputGroup",{attrs:{name:"report title",type:"text",maxlength:"64",placeholder:"Your title here...",invalid:!e.validTitle},on:{input:function(t){e.changed.title=!0}},model:{value:e.value.title,callback:function(t){e.$set(e.value,"title",t)},expression:"value.title"}}),e._v(" "),a("hr",{staticClass:"hr-dashed"}),e._v(" "),a("SliderChoice",{attrs:{left:"Income subtraction",right:"Outcome subtraction"},model:{value:e.value.income_addition,callback:function(t){e.$set(e.value,"income_addition",t)},expression:"value.income_addition"}}),e._v(" "),a("SliderChoice",{attrs:{left:"Don't calculate sum",right:"Calculate sum"},model:{value:e.value.calculate_sum,callback:function(t){e.$set(e.value,"calculate_sum",t)},expression:"value.calculate_sum"}}),e._v(" "),a("SliderChoice",{attrs:{left:"Sort dates ascending",right:"Sort dates descending"},model:{value:e.value.sort_dates_desc,callback:function(t){e.$set(e.value,"sort_dates_desc",t)},expression:"value.sort_dates_desc"}})],1)}),[],!1,null,null,null).exports;const o=i({props:{value:Object,queryTypes:Array,titles:Array,currencies:Array,categories:Object,means:Object},components:{InputGroup:l},data:function(){return{changed:{min_date:!1,max_date:!1,min_amount:!1,max_amount:!1,min_price:!1,max_price:!1}}},computed:{validMinDate:function(){var e=this.value.min_date;return""===e||!isNaN(Date.parse(e))&&(!this.value.max_date||new Date(e).getTime()<=new Date(this.value.max_date).getTime())},validMaxDate:function(){var e=this.value.max_date;return""===e||!isNaN(Date.parse(e))&&(!this.value.min_date||new Date(e).getTime()>=new Date(this.value.min_date).getTime())},validMinAmount:function(){var e=Number(this.value.min_amount);return""===this.value.min_amount||!isNaN(e)&&(!this.value.max_amount||e<=this.value.max_amount)&&e<=9999999.999&&e>=0},validMaxAmount:function(){var e=Number(this.value.max_amount);return""===this.value.max_amount||!isNaN(e)&&(!this.value.min_amount||e>=this.value.min_amount)&&e<=9999999.999&&e>=0},validMinPrice:function(){var e=Number(this.value.min_price);return""===this.value.min_price||!isNaN(e)&&(!this.value.max_price||e<=this.value.max_price)&&e<=99999999999.99&&e>=0},validMaxPrice:function(){var e=Number(this.value.max_price);return""===this.value.max_price||!isNaN(e)&&(!this.value.min_price||e>=this.value.min_price)&&e<=99999999999.99&&e>=0}},methods:{resetCategoriesAndMeans:function(){this.value.category_id=null,this.value.mean_id=null}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("InputGroup",{attrs:{type:"select",name:"query data",selectOptions:e.queryTypes,optionKeyAndValue:!0,capitalizeOptions:!0},model:{value:e.value.query_data,callback:function(t){e.$set(e.value,"query_data",t)},expression:"value.query_data"}}),e._v(" "),a("InputGroup",{attrs:{type:"date",name:"minimal date",max:e.value.max_date,invalid:e.changed.min_date&&!e.validMinDate},on:{input:function(t){e.changed.min_date=!0}},model:{value:e.value.min_date,callback:function(t){e.$set(e.value,"min_date",t)},expression:"value.min_date"}}),e._v(" "),a("InputGroup",{attrs:{type:"date",name:"maximal date",min:e.value.min_date,invalid:e.changed.max_date&&!e.validMaxDate},on:{input:function(t){e.changed.max_date=!0}},model:{value:e.value.max_date,callback:function(t){e.$set(e.value,"max_date",t)},expression:"value.max_date"}}),e._v(" "),a("InputGroup",{attrs:{type:"select",name:"title",selectOptions:e.titles,optionKeyAndValue:!0,nullValue:"All titles"},model:{value:e.value.title,callback:function(t){e.$set(e.value,"title",t)},expression:"value.title"}}),e._v(" "),a("InputGroup",{attrs:{name:"minimal amount",type:"number",step:"0.001",placeholder:"Leave blank if not set",invalid:e.changed.min_amount&&!e.validMinAmount},on:{input:function(t){e.changed.min_amount=!0}},model:{value:e.value.min_amount,callback:function(t){e.$set(e.value,"min_amount",t)},expression:"value.min_amount"}}),e._v(" "),a("InputGroup",{attrs:{name:"maximal amount",type:"number",step:"0.001",placeholder:"Leave blank if not set",invalid:e.changed.max_amount&&!e.validMaxAmount},on:{input:function(t){e.changed.max_amount=!0}},model:{value:e.value.max_amount,callback:function(t){e.$set(e.value,"max_amount",t)},expression:"value.max_amount"}}),e._v(" "),a("InputGroup",{attrs:{name:"minimal price",type:"number",step:"0.001",placeholder:"Leave blank if not set",invalid:e.changed.min_price&&!e.validMinPrice},on:{input:function(t){e.changed.min_price=!0}},model:{value:e.value.min_price,callback:function(t){e.$set(e.value,"min_price",t)},expression:"value.min_price"}}),e._v(" "),a("InputGroup",{attrs:{name:"maximal price",type:"number",step:"0.001",placeholder:"Leave blank if not set",invalid:e.changed.max_price&&!e.validMaxPrice},on:{input:function(t){e.changed.max_price=!0}},model:{value:e.value.max_price,callback:function(t){e.$set(e.value,"max_price",t)},expression:"value.max_price"}}),e._v(" "),a("InputGroup",{attrs:{name:"currency",type:"select",selectOptions:e.currencies,optionValueKey:"id",optionTextKey:"ISO",nullValue:"All currencies"},on:{input:e.resetCategoriesAndMeans},model:{value:e.value.currency_id,callback:function(t){e.$set(e.value,"currency_id",t)},expression:"value.currency_id"}}),e._v(" "),a("InputGroup",{attrs:{name:"category",type:"select",selectOptions:e.categories[e.value.currency_id],optionValueKey:"id",optionTextKey:"name",nullValue:"All categories",disabled:!e.value.currency_id},model:{value:e.value.category_id,callback:function(t){e.$set(e.value,"category_id",t)},expression:"value.category_id"}}),e._v(" "),a("InputGroup",{attrs:{name:"mean of payment",type:"select",selectOptions:e.means[e.value.currency_id],optionValueKey:"id",optionTextKey:"name",nullValue:"All means of payment",disabled:!e.value.currency_id},model:{value:e.value.mean_id,callback:function(t){e.$set(e.value,"mean_id",t)},expression:"value.mean_id"}})],1)}),[],!1,null,null,null).exports;const u=i({props:{value:Array,queryTypes:Array,titles:Array,currencies:Array,categories:Object,means:Object},components:{ReportQueryComponent:o},methods:{addQuery:function(){return this.$emit("add")},removeQuery:function(e){return this.$emit("remove",e)}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card"},[e._m(0),e._v(" "),a("div",{staticClass:"card-body"},[e._l(e.value,(function(t,i){return a("div",{key:i},[a("h4",{staticClass:"text-center fw-bold mb-3"},[e._v("Query #"+e._s(i+1)+":")]),e._v(" "),a("ReportQueryComponent",{attrs:{queryTypes:e.queryTypes,titles:e.titles,currencies:e.currencies,categories:e.categories,means:e.means},model:{value:e.value[i],callback:function(t){e.$set(e.value,i,t)},expression:"value[i]"}}),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-12 col-sm-4 offset-sm-4"},[a("button",{staticClass:"big-button-danger",on:{click:function(t){return e.removeQuery(i)}}},[a("i",{staticClass:"fas fa-trash"}),e._v("\n                        Delete\n                    ")])])]),e._v(" "),i<e.value.length-1?a("hr",{staticClass:"hr-dashed"}):e._e()],1)})),e._v(" "),e.value.length?a("hr",{staticClass:"hr"}):e._e(),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-6 offset-md-3"},[a("button",{staticClass:"big-button-primary",on:{click:e.addQuery}},[e._v("\n                    Add new query\n                ")])])])],2)])}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card-header-flex"},[a("div",{staticClass:"card-header-text"},[a("i",{staticClass:"fas fa-database"}),e._v("\n            Queries\n        ")])])}],!1,null,null,null).exports;const c=i({props:{value:{required:!0,type:Object},currencies:{required:!0,type:Array},categories:{required:!0,type:Object},means:{required:!0,type:Object},common:{required:!1,type:Object},titles:{required:!1,type:Array},minDateRestriction:{required:!1,type:Boolean,default:!0},ignoreNegativePrice:{required:!1,type:Boolean,default:!1}},components:{InputGroup:l},data:function(){return{changed:{date:!1,title:!1,amount:!1,price:!1}}},computed:{minDate:function(){var e=this,t=this.means[this.value.currency_id];if(null==t||!this.minDateRestriction)return"1970-01-01";var a=t.filter((function(t){return t.id==e.value.mean_id}));return a.length?a[0].first_entry_date:"1970-01-01"},validDate:function(){var e=this.value.date;return""!==e&&!isNaN(Date.parse(e))&&new Date(e).getTime()>=new Date(this.minDate).getTime()},validTitle:function(){var e=this.value.title;return e.length&&e.length<=64},validAmount:function(){var e=Number(this.value.amount);return!isNaN(e)&&e<=9999999.999&&e>0},validPrice:function(){var e=Number(this.value.price);return!isNaN(e)&Math.abs(e)<=99999999999.99&&(e>0||this.ignoreNegativePrice)}},methods:{currencyChange:function(){this.value.category_id=null,this.value.mean_id=null},meanChange:function(){var e=this.value.date;this.value.date=""===e||isNaN(Date.parse(e))||new Date(e).getTime()<new Date(this.minDate).getTime()?this.minDate:e}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("InputGroup",{attrs:{name:"date",type:"date",min:e.minDate,invalid:e.changed.date&&!e.validDate},on:{input:function(t){e.changed.date=!0}},model:{value:e.value.date,callback:function(t){e.$set(e.value,"date",t)},expression:"value.date"}}),e._v(" "),a("InputGroup",{attrs:{name:"title",datalistName:"titles",datalist:e.titles,maxlength:"64",placeholder:"Your title here...",invalid:e.changed.title&&!e.validTitle},on:{input:function(t){e.changed.title=!0}},model:{value:e.value.title,callback:function(t){e.$set(e.value,"title",t)},expression:"value.title"}}),e._v(" "),a("InputGroup",{attrs:{name:"amount",type:"number",step:"0.001",placeholder:"Your amount here...",invalid:e.changed.amount&&!e.validAmount},on:{input:function(t){e.changed.amount=!0}},model:{value:e.value.amount,callback:function(t){e.$set(e.value,"amount",t)},expression:"value.amount"}}),e._v(" "),a("div",{staticClass:"input-group-row"},[e._m(0),e._v(" "),a("div",{staticClass:"col-md-4 col-sm-12"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.value.price,expression:"value.price"}],class:["form-control",e.changed.price&&!e.validPrice&&"is-invalid"],attrs:{type:"number",step:"0.01",placeholder:"Your price here...",disabled:e.common&&e.common.hasOwnProperty("price")},domProps:{value:e.value.price},on:{input:[function(t){t.target.composing||e.$set(e.value,"price",t.target.value)},function(t){e.changed.price=!0}]}}),e._v(" "),e.changed.price&&!e.validPrice?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v("Price is invalid")])]):e._e()]),e._v(" "),a("div",{staticClass:"col-md-3 col-sm-12 mt-2 mt-md-0"},[a("select",{directives:[{name:"model",rawName:"v-model",value:e.value.currency_id,expression:"value.currency_id"}],staticClass:"form-control",attrs:{disabled:e.common&&e.common.hasOwnProperty("currency_id")},on:{change:[function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.$set(e.value,"currency_id",t.target.multiple?a:a[0])},e.currencyChange]}},e._l(e.currencies,(function(t,i){return a("option",{key:i,domProps:{value:t.id}},[e._v("\n                    "+e._s(t.ISO)+"\n                ")])})),0)])]),e._v(" "),a("InputGroup",{attrs:{name:"value",value:Math.round(e.value.amount*e.value.price*100)/100+" "+e.currencies[e.value.currency_id-1].ISO,disabled:!0}}),e._v(" "),a("InputGroup",{attrs:{type:"select",name:"category",selectOptions:e.categories[e.value.currency_id],optionValueKey:"id",optionTextKey:"name",nullValue:"N/A"},model:{value:e.value.category_id,callback:function(t){e.$set(e.value,"category_id",t)},expression:"value.category_id"}}),e._v(" "),a("InputGroup",{attrs:{type:"select",name:"mean of payment",selectOptions:e.means[e.value.currency_id],optionValueKey:"id",optionTextKey:"name",nullValue:"N/A"},on:{input:e.meanChange},model:{value:e.value.mean_id,callback:function(t){e.$set(e.value,"mean_id",t)},expression:"value.mean_id"}})],1)}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[a("div",{staticClass:"h5 fw-bold m-md-0"},[e._v("Price")])])}],!1,null,null,null).exports;const d=i({props:{value:Array,currencies:Array,categories:Object,means:Object,titles:Array},components:{CreateForm:c},methods:{addEntry:function(){this.$emit("add")},removeEntry:function(e){this.$emit("remove",e)}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card"},[e._m(0),e._v(" "),a("div",{staticClass:"card-body"},[e._l(e.value,(function(t,i){return a("div",{key:i},[a("div",{staticClass:"h4 fw-bold ms-3 mb-3"},[e._v("Entry #"+e._s(i+1))]),e._v(" "),a("CreateForm",{attrs:{currencies:e.currencies,categories:e.categories,means:e.means,titles:e.titles,minDateRestriction:!1,ignoreNegativePrice:!0},model:{value:e.value[i],callback:function(t){e.$set(e.value,i,t)},expression:"value[i]"}}),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-12 col-sm-4 offset-sm-4"},[a("button",{staticClass:"big-button-danger",on:{click:function(t){return e.removeEntry(i)}}},[a("i",{staticClass:"fas fa-trash"}),e._v("\n                        Delete\n                    ")])])]),e._v(" "),i<e.value.length-1?a("hr",{staticClass:"hr-dashed"}):e._e()],1)})),e._v(" "),e.value.length?a("hr",{staticClass:"hr"}):e._e(),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-6 offset-md-3"},[a("button",{staticClass:"big-button-primary",on:{click:e.addEntry}},[e._v("\n                    Add new entry\n                ")])])])],2)])}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card-header-flex"},[a("div",{staticClass:"card-header-text"},[a("i",{staticClass:"fas fa-plus-circle"}),e._v("\n            Additional entries\n        ")])])}],!1,null,null,null).exports;const m=i({props:{value:Array},data:function(){return{email:"",changedEmail:!1,awaitingConfirmation:[],emailErrorMessage:""}},computed:{validEmail:function(){var e=/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(this.email)&&this.email.length<=64,t=this.awaitingConfirmation.includes(this.email)||this.value.map((function(e){return e.email})).includes(this.email);return this.emailErrorMessage=e?t?"This user is already in the list":"":"Invalid email",e&&!t}},methods:{addUser:function(){var e=this,t=this.email;this.email="",this.changedEmail=!1,this.awaitingConfirmation.push({email:t,error:!1}),axios.post("/webapi/bundles/reports/get-user-info",{email:t}).then((function(a){e.value.push({email:t,username:a.data.username,profile_picture:a.data.profile_picture});var i=e.awaitingConfirmation.findIndex((function(e){return e.email==t}));e.awaitingConfirmation.splice(i,1)})).catch((function(a){console.error(a);var i=e.awaitingConfirmation.findIndex((function(e){return e.email==t}));e.awaitingConfirmation[i].error=!0,setTimeout((function(){return e.awaitingConfirmation.splice(i,1)}),2e3)}))},removeUser:function(e){this.value.splice(e,1)}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card"},[e._m(0),e._v(" "),a("div",{staticClass:"card-body"},[a("div",{staticClass:"shared-users-wrapper"},e._l(e.value,(function(t,i){return a("div",{key:i,staticClass:"shared-user"},[a("img",{staticClass:"shared-user-image",attrs:{src:t.profile_picture,alt:"Profile picture of "+t.username}}),e._v(" "),a("div",{staticClass:"shared-user-username"},[e._v("\n                    "+e._s(t.username)+"\n                ")]),e._v(" "),a("div",{staticClass:"shared-user-delete",on:{click:function(t){return e.removeUser(i)}}},[a("i",{staticClass:"fas fa-trash-alt"})])])})),0),e._v(" "),e.value.length&&e.awaitingConfirmation.length?a("hr",{staticClass:"hr-dashed"}):e._e(),e._v(" "),a("div",{staticClass:"shared-users-wrapper"},e._l(e.awaitingConfirmation,(function(t,i){return a("div",{key:i+e.value.length,staticClass:"shared-user-waiting"},[a("div",{staticClass:"shared-user-waiting-email"},[e._v("\n                    "+e._s(t.email)+"\n                ")]),e._v(" "),t.error?a("i",{staticClass:"fas fa-exclamation-circle shared-user-waiting-error"}):a("span",{staticClass:"shared-user-waiting-spinner",attrs:{role:"status","aria-hidden":"true"}})])})),0),e._v(" "),e.value.length||e.awaitingConfirmation.length?a("hr",{staticClass:"hr"}):e._e(),e._v(" "),a("div",{staticClass:"row"},[e._m(1),e._v(" "),a("div",{staticClass:"col-md-5"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.email,expression:"email"}],class:["form-control",e.changedEmail&&!e.validEmail&&"is-invalid"],attrs:{type:"email",maxlength:"64",placeholder:"Enter user's email...",autocomplete:"email"},domProps:{value:e.email},on:{input:[function(t){t.target.composing||(e.email=t.target.value)},function(t){e.changedEmail=!0}]}}),e._v(" "),e.emailErrorMessage?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v(e._s(e.emailErrorMessage))])]):e._e()]),e._v(" "),a("div",{staticClass:"col-md-4"},[a("button",{staticClass:"big-button-primary mt-2 mt-md-0",attrs:{disabled:!e.validEmail},on:{click:e.addUser}},[e._v("\n                    Submit\n                ")])])])])])}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card-header-flex"},[a("div",{staticClass:"card-header-text"},[a("i",{staticClass:"fas fa-share-alt"}),e._v("\n            Users shared with\n        ")])])},function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"col-md-3 d-flex justify-content-md-end justify-content-start align-items-center"},[a("div",{staticClass:"h5 fw-bold m-md-0"},[e._v("User's email:")])])}],!1,null,null,null).exports;const v=i({props:{value:{type:Object,required:!0}},components:{SliderCheckbox:n},watch:{value:function(){this.$emit("input",this.value)}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card"},[e._m(0),e._v(" "),a("div",{staticClass:"card-body"},[a("div",{staticClass:"table-responsive"},[a("table",{staticClass:"table-themed responsive-table-bordered"},[e._m(1),e._v(" "),a("tbody",[a("tr",[a("td",[a("SliderCheckbox",{model:{value:e.value.date,callback:function(t){e.$set(e.value,"date",t)},expression:"value.date"}})],1),e._v(" "),a("td",[a("SliderCheckbox",{model:{value:e.value.title,callback:function(t){e.$set(e.value,"title",t)},expression:"value.title"}})],1),e._v(" "),a("td",[a("SliderCheckbox",{model:{value:e.value.amount,callback:function(t){e.$set(e.value,"amount",t)},expression:"value.amount"}})],1),e._v(" "),a("td",[a("SliderCheckbox",{model:{value:e.value.price,callback:function(t){e.$set(e.value,"price",t)},expression:"value.price"}})],1),e._v(" "),a("td",[a("SliderCheckbox",{model:{value:e.value.value,callback:function(t){e.$set(e.value,"value",t)},expression:"value.value"}})],1),e._v(" "),a("td",[a("SliderCheckbox",{model:{value:e.value.category,callback:function(t){e.$set(e.value,"category",t)},expression:"value.category"}})],1),e._v(" "),a("td",[a("SliderCheckbox",{model:{value:e.value.mean,callback:function(t){e.$set(e.value,"mean",t)},expression:"value.mean"}})],1)])])])])])])}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card-header-flex"},[a("div",{staticClass:"card-header-text"},[a("i",{staticClass:"fas fa-table"}),e._v("\n            Columns to show\n        ")])])},function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("thead",[a("tr",[a("th",{attrs:{scope:"col"}},[e._v("Date")]),e._v(" "),a("th",{attrs:{scope:"col"}},[e._v("Title")]),e._v(" "),a("th",{attrs:{scope:"col"}},[e._v("Amount")]),e._v(" "),a("th",{attrs:{scope:"col"}},[e._v("Price")]),e._v(" "),a("th",{attrs:{scope:"col"}},[e._v("Value")]),e._v(" "),a("th",{attrs:{scope:"col"}},[e._v("Category")]),e._v(" "),a("th",{attrs:{scope:"col"}},[e._v("Mean of payment")])])])}],!1,null,null,null).exports;const p=i({},(function(){var e=this,t=e.$createElement;e._self._c;return e._m(0)}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"d-flex justify-content-center my-2"},[a("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[a("span",{staticClass:"sr-only"},[e._v("Loading...")])])])}],!1,null,null,null).exports;const h=i({},(function(){var e=this,t=e.$createElement;e._self._c;return e._m(0)}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("h1",{staticClass:"text-center"},[e._v("Nothing to see here, for now...")])])}],!1,null,null,null).exports;var f=i({props:{disableAll:{type:Boolean,required:!1,default:!1},disableSave:{type:Boolean,required:!1,default:!1},disableReset:{type:Boolean,required:!1,default:!1},disableDelete:{type:Boolean,required:!1,default:!1},spinnerDelete:{type:Boolean,required:!1,default:!1},spinnerSave:{type:Boolean,required:!1,default:!1},deleteText:{type:String,required:!1,default:""}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"row"},[a("div",{staticClass:"col-sm-4 my-2 my-sm-0"},[a("button",{staticClass:"big-button-danger",attrs:{type:"button",disabled:e.disableAll||e.disableDelete||e.spinnerDelete},on:{click:function(t){return e.$emit("delete")}}},[e.spinnerDelete?a("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):a("div",[e._v("\n                Delete "+e._s(e.deleteText)+"\n            ")])])]),e._v(" "),a("div",{staticClass:"col-sm-4 my-2 my-sm-0"},[a("button",{staticClass:"big-button-success",attrs:{type:"button",disabled:e.disableAll||e.disableSave||e.spinnerSave},on:{click:function(t){return e.$emit("save")}}},[e.spinnerSave?a("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):a("div",[e._v("\n                Save changes\n            ")])])]),e._v(" "),a("div",{staticClass:"col-sm-4 my-2 my-sm-0"},[a("button",{staticClass:"big-button-primary",attrs:{type:"button",disabled:e.disableAll||e.disableReset},on:{click:function(t){return e.$emit("reset")}}},[e._v("\n            Reset changes\n        ")])])])}),[],!1,null,null,null);const y=i({props:["id"],components:{Loading:p,EmptyPlaceholder:h,DeleteSaveResetChanges:f.exports,ReportDataComponent:r,ReportQueriesComponent:u,ReportAdditionalEntries:d,ReportUsersSharedComponent:m,ReportColumnsComponent:v},data:function(){return{ready:!1,currencies:{},categories:{},means:{},titles:[],queryTypes:[],columns:{},lastCurrency:1,submitted:!1,destroyed:!1,showReportClicked:!1,copy:{},queries:[],additionalEntries:[],users:[],data:{title:"",income_addition:!0,calculate_sum:!1,sort_dates_desc:!1}}},computed:{validReportData:function(){return this.data.title&&this.data.title.length<=64},validQueries:function(){var e=this,t=this.queries.map((function(t){return[e.isNullLike(t.min_date)||!isNaN(Date.parse(t.min_date))&&(!t.max_date||new Date(t.min_date).getTime()<=new Date(t.max_date).getTime()),e.isNullLike(t.max_date)||!isNaN(Date.parse(t.max_date))&&(!t.min_date||new Date(t.max_date).getTime()>=new Date(t.min_date).getTime()),e.isNullLike(t.min_amount)||!isNaN(Number(t.min_amount))&&(!t.max_amount||t.min_price<=t.max_price)&&t.min_amount<=9999999.999&&t.min_amount>=0,e.isNullLike(t.max_amount)||!isNaN(Number(t.max_amount))&&(!t.min_amount||t.min_price<=t.max_price)&&t.max_amount<=9999999.999&&t.max_amount>=0,e.isNullLike(t.min_price)||!isNaN(Number(t.min_price))&&(!t.max_price||t.min_price<=t.max_price)&&t.min_price<=99999999999.99&&t.min_price>=0,e.isNullLike(t.max_price)||!isNaN(Number(t.max_price))&&(!t.min_price||t.min_price<=t.max_price)&&t.max_price<=99999999999.99&&t.max_price>=0].reduce((function(e,t){return e&&t}))}));return!t.length||t.reduce((function(e,t){return e&&t}))},validAdditionalEntries:function(){var e=this.additionalEntries.map((function(e){return[""!==e.date&&!isNaN(Date.parse(e.date)),e.title&&e.title.length<=64,!isNaN(Number(e.amount))&&e.amount<=9999999.999&&e.amount>0,!isNaN(Number(e.price))&&Math.abs(e.price)<=99999999999.99&&0!=e.price].reduce((function(e,t){return e&&t}))}));return!e.length||e.reduce((function(e,t){return e&&t}))},validUsers:function(){var e=this.users.map((function(e){return e.email}));return e.forEach((function(t,a){if(e.indexOf(t)!=a)return!1})),!0},canSubmit:function(){return this.validReportData&&this.validQueries&&this.validAdditionalEntries&&this.validUsers&&(this.queries.length||this.additionalEntries.length)}},methods:{addQuery:function(){this.queries.push({id:null,query_data:this.queryTypes[0],min_date:null,max_date:null,title:null,min_amount:null,max_amount:null,min_price:null,max_price:null,currency_id:null,category_id:null,mean_id:null})},removeQuery:function(e){this.queries.splice(e,1)},addEntry:function(){this.additionalEntries.push({id:null,date:null,title:null,amount:null,price:null,currency_id:this.lastCurrency,category_id:null,mean_id:null})},removeEntry:function(e){this.additionalEntries.splice(e,1)},submit:function(){var e=this;return this.submitted=!0,axios.patch("/webapi/bundles/reports/".concat(this.id,"/update"),{data:this.data,columns:this.columns,queries:this.queries,additionalEntries:this.additionalEntries,users:this.users.map((function(e){return e.email}))}).then((function(t){e.data=t.data.data,e.queries=t.data.queries,e.additionalEntries=t.data.additionalEntries,e.columns=t.data.columns,e.copy.data=_.cloneDeep(t.data.data),e.copy.queries=_.cloneDeep(t.data.queries),e.copy.additionalEntries=_.cloneDeep(t.data.additionalEntries),e.copy.columns=_.cloneDeep(t.data.columns),e.copy.users=_.cloneDeep(e.users)})).catch((function(t){console.error(t),e.showReportClicked=!1})).finally((function(){return e.submitted=!1}))},reset:function(){this.data=_.cloneDeep(this.copy.data),this.queries=_.cloneDeep(this.copy.queries),this.additionalEntries=_.cloneDeep(this.copy.additionalEntries),this.users=_.cloneDeep(this.copy.users),this.columns=_.cloneDeep(this.copy.columns)},destroy:function(){var e=this;this.destroyed=!0,axios.delete("/webapi/bundles/reports/".concat(this.id,"/delete"),{}).then((function(){return window.location="/bundles/reports"})).catch((function(t){console.error(t),e.destroyed=!1}))},isNullLike:function(e){return null===e||""===e},showReport:function(){var e=this;this.showReportClicked=!0,this.submit().then((function(){e.showReportClicked&&(window.location="/bundles/reports/".concat(e.id))}))}},mounted:function(){var e=this;axios.get("/webapi/bundles/reports/".concat(this.id,"/edit"),{}).then((function(t){e.data=t.data.data,e.queries=t.data.queries,e.additionalEntries=t.data.additionalEntries,e.users=t.data.users,e.columns=t.data.columns,e.copy.data=_.cloneDeep(t.data.data),e.copy.queries=_.cloneDeep(t.data.queries),e.copy.additionalEntries=_.cloneDeep(t.data.additionalEntries),e.copy.users=_.cloneDeep(t.data.users),e.copy.columns=_.cloneDeep(t.data.columns),e.currencies=t.data.currencies,e.categories=t.data.categories,e.means=t.data.means,e.titles=t.data.titles,e.queryTypes=t.data.queryTypes,e.lastCurrency=t.data.lastCurrency,e.ready=!0})).catch((function(e){console.error(e)}))}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card"},[a("div",{staticClass:"card-header-flex"},[e._m(0),e._v(" "),e.ready?a("div",{staticClass:"d-flex"},[a("button",{staticClass:"big-button-primary",attrs:{disabled:e.submitted||e.destroyed||e.showReportClicked||!e.canSubmit},on:{click:e.showReport}},[e.showReportClicked?a("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):a("div",[e._v("\n                    Show report\n                ")])])]):e._e()]),e._v(" "),a("div",{staticClass:"card-body"},[e.ready?a("div",[a("ReportDataComponent",{model:{value:e.data,callback:function(t){e.data=t},expression:"data"}}),e._v(" "),a("hr",{staticClass:"hr"}),e._v(" "),a("ReportColumnsComponent",{model:{value:e.columns,callback:function(t){e.columns=t},expression:"columns"}}),e._v(" "),a("hr",{staticClass:"hr"}),e._v(" "),a("ReportQueriesComponent",{attrs:{queryTypes:e.queryTypes,titles:e.titles,currencies:e.currencies,categories:e.categories,means:e.means},on:{add:e.addQuery,remove:e.removeQuery},model:{value:e.queries,callback:function(t){e.queries=t},expression:"queries"}}),e._v(" "),a("hr",{staticClass:"hr"}),e._v(" "),a("ReportAdditionalEntries",{attrs:{titles:e.titles,currencies:e.currencies,categories:e.categories,means:e.means},on:{add:e.addEntry,remove:e.removeEntry},model:{value:e.additionalEntries,callback:function(t){e.additionalEntries=t},expression:"additionalEntries"}}),e._v(" "),a("hr",{staticClass:"hr"}),e._v(" "),a("ReportUsersSharedComponent",{model:{value:e.users,callback:function(t){e.users=t},expression:"users"}}),e._v(" "),a("hr",{staticClass:"hr"}),e._v(" "),a("DeleteSaveResetChanges",{attrs:{disableAll:e.destroyed||e.submitted,disableSave:!e.canSubmit,spinnerDelete:e.destroyed,spinnerSave:e.submitted,deleteText:"report"},on:{delete:e.destroy,save:e.submit,reset:e.reset}})],1):a("Loading")],1)])}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card-header-text"},[a("i",{staticClass:"fas fa-newspaper"}),e._v("\n            Edit report\n        ")])}],!1,null,null,null).exports}},t={};function a(i){var n=t[i];if(void 0!==n)return n.exports;var l=t[i]={exports:{}};return e[i](l,l.exports,a),l.exports}a.d=(e,t)=>{for(var i in t)a.o(t,i)&&!a.o(e,i)&&Object.defineProperty(e,i,{enumerable:!0,get:t[i]})},a.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{Vue.component("edit-report-component",a(9021).Z);new Vue({el:"#app"})})()})();
//# sourceMappingURL=edit.js.map