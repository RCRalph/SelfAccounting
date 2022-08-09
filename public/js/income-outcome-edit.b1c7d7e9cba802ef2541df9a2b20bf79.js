(()=>{var e={5287:(e,t,a)=>{"use strict";a.d(t,{Z:()=>p});function n(e,t,a,n,i,l,r,s){var o,c="function"==typeof e?e.options:e;if(t&&(c.render=t,c.staticRenderFns=a,c._compiled=!0),n&&(c.functional=!0),l&&(c._scopeId="data-v-"+l),r?(o=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),i&&i.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(r)},c._ssrRegister=o):i&&(o=s?function(){i.call(this,(c.functional?this.parent:this).$root.$options.shadowRoot)}:i),o)if(c.functional){c._injectStyles=o;var d=c.render;c.render=function(e,t){return o.call(t),d(e,t)}}else{var u=c.beforeCreate;c.beforeCreate=u?[].concat(u,o):[o]}return{exports:e,options:c}}const i=n({},(function(){var e=this,t=e.$createElement;e._self._c;return e._m(0)}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"d-flex justify-content-center my-2"},[a("div",{staticClass:"spinner-grow",staticStyle:{width:"3rem",height:"3rem"},attrs:{role:"status"}},[a("span",{staticClass:"sr-only"},[e._v("Loading...")])])])}],!1,null,null,null).exports;const l=n({props:{value:{required:!0,type:Boolean},disabled:{required:!1,type:Boolean,default:!1},name:{required:!1,type:String,default:""},choice:{type:Boolean,required:!1,default:!1}},methods:{emitEvents:function(e){this.$emit("input",e.currentTarget.checked),this.$emit("htmlElement",e.currentTarget)}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{class:e.choice?"slider-choice":"slider-checkbox"},[a("label",{staticClass:"switch m-0"},[a("input",{attrs:{type:"checkbox",disabled:e.disabled,name:e.name},domProps:{checked:e.value},on:{change:e.emitEvents}}),e._v(" "),a("span",{staticClass:"slider round"})])])}),[],!1,null,null,null).exports;const r=n({props:{name:{required:!0,type:String},type:{required:!1,type:String,default:"text"},value:{required:!0},invalid:{required:!1,type:Boolean,default:!1},min:{required:!1},max:{required:!1},minlength:{required:!1},maxlength:{required:!1},prepend:{required:!1,type:String},append:{required:!1,type:String},autocomplete:{required:!1,type:String,default:"off"},placeholder:{required:!1,type:String},datalistName:{required:!1,type:String},datalist:{required:!1,type:Array},step:{required:!1,type:String,default:"1"},selectOptions:{required:!1,type:Array},optionValueKey:{required:!1,type:String},optionTextKey:{required:!1,type:String},optionKeyAndValue:{required:!1,type:Boolean,default:!1},capitalizeOptions:{required:!1,type:Boolean,default:!1},nullValue:{required:!1,type:String,default:""},disabled:{required:!1,type:Boolean,default:!1}},components:{SliderCheckbox:l},methods:{capitalize:function(e){return(e.charAt(0).toUpperCase()+e.slice(1)).replace("_"," ")}},watch:{value:function(){this.$emit("input",this.value)}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{class:!(e.prepend||e.append)&&"input-group-row"},[e.prepend||e.append?e._e():a("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[a("div",{staticClass:"h5 fw-bold m-md-0"},[e._v("\n                "+e._s(e.capitalize("password_confirmation"!=e.name?e.name:"confirm"))+"\n            ")])]),e._v(" "),"select"==e.type?a("div",{staticClass:"col-md-7"},[e.optionKeyAndValue?a("select",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{disabled:e.disabled},on:{change:function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.value=t.target.multiple?a:a[0]}}},[e.nullValue?a("option",{domProps:{value:null}},[e._v(e._s(e.nullValue))]):e._e(),e._v(" "),e._l(e.selectOptions,(function(t,n){return a("option",{key:n,domProps:{value:t,selected:e.value==t||1==e.selectOptions.length}},[e._v("\n                    "+e._s(e.capitalizeOptions?e.capitalize(t):t)+"\n                ")])}))],2):a("select",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{disabled:e.disabled},on:{change:function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.value=t.target.multiple?a:a[0]}}},[e.nullValue?a("option",{domProps:{value:null}},[e._v(e._s(e.nullValue))]):e._e(),e._v(" "),e._l(e.selectOptions,(function(t,n){return a("option",{key:n,domProps:{value:t[e.optionValueKey],selected:e.value==t[e.optionValueKey]||1==e.selectOptions.length}},[e._v("\n                    "+e._s(t[e.optionTextKey])+"\n                ")])}))],2),e._v(" "),e.invalid?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v(e._s(e.capitalize(e.name))+" is invalid")])]):e._e()]):e.prepend||e.append?a("div",{staticClass:"input-group-row"},[a("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[a("div",{staticClass:"h5 fw-bold m-md-0"},[e._v("\n                    "+e._s(e.capitalize("password_confirmation"!=e.name?e.name:"confirm"))+"\n                ")])]),e._v(" "),a("div",{staticClass:"col-md-7"},[a("div",{staticClass:"input-group"},[e.prepend?a("span",{staticClass:"input-group-text"},[e._v("\n                        "+e._s(e.prepend)+"\n                    ")]):e._e(),e._v(" "),"checkbox"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"checkbox"},domProps:{checked:Array.isArray(e.value)?e._i(e.value,null)>-1:e.value},on:{change:function(t){var a=e.value,n=t.target,i=!!n.checked;if(Array.isArray(a)){var l=e._i(a,null);n.checked?l<0&&(e.value=a.concat([null])):l>-1&&(e.value=a.slice(0,l).concat(a.slice(l+1)))}else e.value=i}}}):"radio"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"radio"},domProps:{checked:e._q(e.value,null)},on:{change:function(t){e.value=null}}}):a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:e.type},domProps:{value:e.value},on:{input:function(t){t.target.composing||(e.value=t.target.value)}}}),e._v(" "),e.append?a("span",{staticClass:"input-group-text"},[e._v("\n                        "+e._s(e.append)+"\n                    ")]):e._e(),e._v(" "),e.datalist?a("datalist",{attrs:{id:e.datalistName}},e._l(e.datalist,(function(t,n){return a("option",{key:n},[e._v(e._s(t))])})),0):e._e(),e._v(" "),e.invalid?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v(e._s(e.capitalize(e.name))+" is invalid")])]):e._e()])])]):a("div",{staticClass:"col-md-7"},["checkbox"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"checkbox"},domProps:{checked:Array.isArray(e.value)?e._i(e.value,null)>-1:e.value},on:{change:function(t){var a=e.value,n=t.target,i=!!n.checked;if(Array.isArray(a)){var l=e._i(a,null);n.checked?l<0&&(e.value=a.concat([null])):l>-1&&(e.value=a.slice(0,l).concat(a.slice(l+1)))}else e.value=i}}}):"radio"===e.type?a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:"radio"},domProps:{checked:e._q(e.value,null)},on:{change:function(t){e.value=null}}}):a("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:["form-control",e.invalid&&"is-invalid"],attrs:{name:e.name,min:e.min,max:e.max,minlength:e.minlength,maxlength:e.maxlength,autocomplete:e.autocomplete,placeholder:e.placeholder,list:e.datalistName,step:e.step,disabled:e.disabled,type:e.type},domProps:{value:e.value},on:{input:function(t){t.target.composing||(e.value=t.target.value)}}}),e._v(" "),e.datalist?a("datalist",{attrs:{id:e.datalistName}},e._l(e.datalist,(function(t,n){return a("option",{key:n},[e._v(e._s(t))])})),0):e._e(),e._v(" "),e.invalid?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v(e._s(e.capitalize(e.name))+" is invalid")])]):e._e()])])}),[],!1,null,null,null).exports;const s=n({props:{value:{required:!0,type:Object},currencies:{required:!0,type:Array},categories:{required:!0,type:Object},means:{required:!0,type:Object},common:{required:!1,type:Object},titles:{required:!1,type:Array},minDateRestriction:{required:!1,type:Boolean,default:!0},ignoreNegativePrice:{required:!1,type:Boolean,default:!1}},components:{InputGroup:r},data:function(){return{changed:{date:!1,title:!1,amount:!1,price:!1}}},computed:{minDate:function(){var e=this,t=this.means[this.value.currency_id];if(null==t||!this.minDateRestriction)return"1970-01-01";var a=t.filter((function(t){return t.id==e.value.mean_id}));return a.length?a[0].first_entry_date:"1970-01-01"},validDate:function(){var e=this.value.date;return""!==e&&!isNaN(Date.parse(e))&&new Date(e).getTime()>=new Date(this.minDate).getTime()},validTitle:function(){var e=this.value.title;return e.length&&e.length<=64},validAmount:function(){var e=Number(this.value.amount);return!isNaN(e)&&e<=9999999.999&&e>0},validPrice:function(){var e=Number(this.value.price);return!isNaN(e)&Math.abs(e)<=99999999999.99&&(e>0||this.ignoreNegativePrice)}},methods:{currencyChange:function(){this.value.category_id=null,this.value.mean_id=null},meanChange:function(){var e=this.value.date;this.value.date=""===e||isNaN(Date.parse(e))||new Date(e).getTime()<new Date(this.minDate).getTime()?this.minDate:e}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("InputGroup",{attrs:{name:"date",type:"date",min:e.minDate,invalid:e.changed.date&&!e.validDate},on:{input:function(t){e.changed.date=!0}},model:{value:e.value.date,callback:function(t){e.$set(e.value,"date",t)},expression:"value.date"}}),e._v(" "),a("InputGroup",{attrs:{name:"title",datalistName:"titles",datalist:e.titles,maxlength:"64",placeholder:"Your title here...",invalid:e.changed.title&&!e.validTitle},on:{input:function(t){e.changed.title=!0}},model:{value:e.value.title,callback:function(t){e.$set(e.value,"title",t)},expression:"value.title"}}),e._v(" "),a("InputGroup",{attrs:{name:"amount",type:"number",step:"0.001",placeholder:"Your amount here...",invalid:e.changed.amount&&!e.validAmount},on:{input:function(t){e.changed.amount=!0}},model:{value:e.value.amount,callback:function(t){e.$set(e.value,"amount",t)},expression:"value.amount"}}),e._v(" "),a("div",{staticClass:"input-group-row"},[e._m(0),e._v(" "),a("div",{staticClass:"col-md-4 col-sm-12"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.value.price,expression:"value.price"}],class:["form-control",e.changed.price&&!e.validPrice&&"is-invalid"],attrs:{type:"number",step:"0.01",placeholder:"Your price here...",disabled:e.common&&e.common.hasOwnProperty("price")},domProps:{value:e.value.price},on:{input:[function(t){t.target.composing||e.$set(e.value,"price",t.target.value)},function(t){e.changed.price=!0}]}}),e._v(" "),e.changed.price&&!e.validPrice?a("span",{staticClass:"invalid-feedback",attrs:{role:"alert"}},[a("strong",[e._v("Price is invalid")])]):e._e()]),e._v(" "),a("div",{staticClass:"col-md-3 col-sm-12 mt-2 mt-md-0"},[a("select",{directives:[{name:"model",rawName:"v-model",value:e.value.currency_id,expression:"value.currency_id"}],staticClass:"form-control",attrs:{disabled:e.common&&e.common.hasOwnProperty("currency_id")},on:{change:[function(t){var a=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.$set(e.value,"currency_id",t.target.multiple?a:a[0])},e.currencyChange]}},e._l(e.currencies,(function(t,n){return a("option",{key:n,domProps:{value:t.id}},[e._v("\n                    "+e._s(t.ISO)+"\n                ")])})),0)])]),e._v(" "),a("InputGroup",{attrs:{name:"value",value:Math.round(e.value.amount*e.value.price*100)/100+" "+e.currencies[e.value.currency_id-1].ISO,disabled:!0}}),e._v(" "),a("InputGroup",{attrs:{type:"select",name:"category",selectOptions:e.categories[e.value.currency_id],optionValueKey:"id",optionTextKey:"name",nullValue:"N/A"},model:{value:e.value.category_id,callback:function(t){e.$set(e.value,"category_id",t)},expression:"value.category_id"}}),e._v(" "),a("InputGroup",{attrs:{type:"select",name:"mean of payment",selectOptions:e.means[e.value.currency_id],optionValueKey:"id",optionTextKey:"name",nullValue:"N/A"},on:{input:e.meanChange},model:{value:e.value.mean_id,callback:function(t){e.$set(e.value,"mean_id",t)},expression:"value.mean_id"}})],1)}),[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"col-md-4 d-flex justify-content-md-end justify-content-start align-items-center"},[a("div",{staticClass:"h5 fw-bold m-md-0"},[e._v("Price")])])}],!1,null,null,null).exports;var o=n({props:{disableAll:{type:Boolean,required:!1,default:!1},disableSave:{type:Boolean,required:!1,default:!1},disableReset:{type:Boolean,required:!1,default:!1},disableDelete:{type:Boolean,required:!1,default:!1},spinnerDelete:{type:Boolean,required:!1,default:!1},spinnerSave:{type:Boolean,required:!1,default:!1},deleteText:{type:String,required:!1,default:""}}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"row"},[a("div",{staticClass:"col-sm-4 my-2 my-sm-0"},[a("button",{staticClass:"big-button-danger",attrs:{type:"button",disabled:e.disableAll||e.disableDelete||e.spinnerDelete},on:{click:function(t){return e.$emit("delete")}}},[e.spinnerDelete?a("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):a("div",[e._v("\n                Delete "+e._s(e.deleteText)+"\n            ")])])]),e._v(" "),a("div",{staticClass:"col-sm-4 my-2 my-sm-0"},[a("button",{staticClass:"big-button-success",attrs:{type:"button",disabled:e.disableAll||e.disableSave||e.spinnerSave},on:{click:function(t){return e.$emit("save")}}},[e.spinnerSave?a("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):a("div",[e._v("\n                Save changes\n            ")])])]),e._v(" "),a("div",{staticClass:"col-sm-4 my-2 my-sm-0"},[a("button",{staticClass:"big-button-primary",attrs:{type:"button",disabled:e.disableAll||e.disableReset},on:{click:function(t){return e.$emit("reset")}}},[e._v("\n            Reset changes\n        ")])])])}),[],!1,null,null,null);function c(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,n)}return a}function d(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?c(Object(a),!0).forEach((function(t){u(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):c(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}function u(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}const p=n({props:["type","id"],components:{Loading:i,CreateForm:s,DeleteSaveResetChanges:o.exports},data:function(){return{ready:!1,submitted:!1,destroyed:!1,goBackClicked:!1,currencies:{},categories:{},means:{},titles:[],data:{date:"",title:"",amount:"",price:"",currency_id:"",category_id:null,mean_id:null},dataCopy:{}}},computed:{minDate:function(){var e=this,t=this.means[this.data.currency_id];if(null==t)return"1970-01-01";var a=t.filter((function(t){return t.id==e.data.mean_id}));return a.length?a[0].first_entry_date:"1970-01-01"},canSave:function(){var e=""!==this.data.date&&!isNaN(Date.parse(this.data.date))&&new Date(this.data.date)>=new Date(this.minDate).getTime(),t=this.data.title.length&&this.data.title.length<=64,a={amount:Number(this.data.amount),price:Number(this.data.price)},n=!isNaN(a.amount)&&a.amount<=9999999.999&&a.amount>0,i=!isNaN(a.price)&&a.price<=99999999999.99&&a.price>0;return e&&t&&n&&i}},methods:{submit:function(){var e=this;return this.submitted=!0,axios.patch("/webapi/".concat(this.type,"/").concat(this.id,"/update"),{data:[this.data]}).then((function(t){var a=t.data.data;e.data=d(d({},a),{},{amount:Number(a.amount),price:Number(a.price)}),e.dataCopy=_.cloneDeep(e.data)})).catch((function(t){console.error(t),e.goBackClicked=!1})).finally((function(){return e.submitted=!1}))},reset:function(){this.data=_.cloneDeep(this.dataCopy)},destroy:function(){var e=this;this.destroyed=!0,axios.delete("/webapi/".concat(this.type,"/").concat(this.id,"/delete"),{}).then((function(){window.location.href="/".concat(e.type)})).catch((function(t){console.log(t),e.destroyed=!1}))},goBack:function(){var e=this;this.goBackClicked=!0,this.submit().then((function(){e.goBackClicked&&(window.location="/".concat(e.type))}))}},mounted:function(){var e=this;axios.get("/webapi/".concat(this.type,"/").concat(this.id),{}).then((function(t){var a=t.data.data;e.currencies=a.currencies,e.categories=a.categories,e.means=a.means,e.titles=a.titles,e.data=d(d({},a.data),{},{amount:Number(a.data.amount),price:Number(a.data.price)}),e.dataCopy=_.cloneDeep(e.data),e.ready=!0}))}},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card"},[a("div",{staticClass:"card-header-flex"},[a("div",{staticClass:"card-header-text"},[a("i",{class:["fas","income"==e.type?"fa-sign-in-alt":"fa-sign-out-alt"]}),e._v("\n            Edit "+e._s(e.type)+"\n        ")]),e._v(" "),e.ready?a("div",{staticClass:"d-flex"},[a("button",{staticClass:"big-button-primary",attrs:{disabled:e.goBackClicked},on:{click:e.goBack}},[e.goBackClicked?a("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):a("div",[e._v("\n                    Go back\n                ")])])]):e._e()]),e._v(" "),a("div",{staticClass:"card-body"},[e.ready?a("div",[a("CreateForm",{attrs:{currencies:e.currencies,categories:e.categories,means:e.means,titles:e.titles},model:{value:e.data,callback:function(t){e.data=t},expression:"data"}}),e._v(" "),a("hr",{staticClass:"hr"}),e._v(" "),a("DeleteSaveResetChanges",{attrs:{disableAll:e.destroyed||e.submitted,disableSave:!e.canSave,spinnerDelete:e.destroyed,spinnerSave:e.submitted,deleteText:e.type},on:{delete:e.destroy,save:e.submit,reset:e.reset}})],1):a("Loading")],1)])}),[],!1,null,null,null).exports}},t={};function a(n){var i=t[n];if(void 0!==i)return i.exports;var l=t[n]={exports:{}};return e[n](l,l.exports,a),l.exports}a.d=(e,t)=>{for(var n in t)a.o(t,n)&&!a.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},a.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{Vue.component("income-outcome-edit-component",a(5287).Z);new Vue({el:"#app"})})()})();
//# sourceMappingURL=income-outcome-edit.js.map