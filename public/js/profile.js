/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 9);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfileComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/profile/ProfileComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ProfileShowcase_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProfileShowcase.vue */ "./resources/js/components/profile/ProfileShowcase.vue");
/* harmony import */ var _ProfileInfoChange_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProfileInfoChange.vue */ "./resources/js/components/profile/ProfileInfoChange.vue");
/* harmony import */ var _ProfilePasswordChange_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ProfilePasswordChange.vue */ "./resources/js/components/profile/ProfilePasswordChange.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    ProfileShowcase: _ProfileShowcase_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
    ProfileInfoChange: _ProfileInfoChange_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
    ProfilePasswordChange: _ProfilePasswordChange_vue__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  data: function data() {
    return {
      ready: false,
      userData: {}
    };
  },
  beforeMount: function beforeMount() {
    this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
  },
  mounted: function mounted() {
    var _this = this;

    axios.get("/webapi/profile", {}).then(function (response) {
      _this.userData = response.data.user;
      _this.ready = true;
    });
  },
  beforeUpdate: function beforeUpdate() {
    this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfileInfoChange.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/profile/ProfileInfoChange.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    userData: Object
  },
  data: function data() {
    return {
      correctFile: true,
      userDataCopy: {},
      submit: false
    };
  },
  methods: {
    dataReset: function dataReset() {
      document.getElementById("picture").value = "";
      this.userDataCopy = this.userData;
    },
    checkFile: function checkFile() {
      var fileType = document.getElementById("picture").files[0].type;
      this.correctFile = fileType.includes("image");
    },
    submitForm: function submitForm() {
      document.getElementById("data-form").submit();
      this.submit = true;
    }
  },
  beforeMount: function beforeMount() {
    this.userDataCopy = _.cloneDeep(this.userData);
  },
  computed: {
    CSRF_TOKEN: function CSRF_TOKEN() {
      return document.head.querySelectorAll("meta[name=csrf-token]")[0].attributes.content.value;
    },
    validEmail: function validEmail() {
      var emailRegex = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
      return emailRegex.test(this.userDataCopy.email) && this.userDataCopy.email.length <= 64;
    },
    validUsername: function validUsername() {
      return this.userDataCopy.username.length <= 32 && this.userDataCopy.username.length > 0;
    },
    disableSubmit: function disableSubmit() {
      return !(this.validUsername && this.validEmail && this.correctFile);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfilePasswordChange.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/profile/ProfilePasswordChange.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      passwords: ["", ""],
      submit: false
    };
  },
  methods: {
    submitForm: function submitForm() {
      document.getElementById("password-form").submit();
      this.submit = true;
    }
  },
  computed: {
    CSRF_TOKEN: function CSRF_TOKEN() {
      return document.head.querySelectorAll("meta[name=csrf-token]")[0].attributes.content.value;
    },
    validPasswords: function validPasswords() {
      return this.passwords[0].length >= 8 && this.passwords[1].length >= 8 && this.passwords[0] === this.passwords[1] || this.passwords[0] == "" && this.passwords[1] == "";
    },
    canSubmit: function canSubmit() {
      return this.passwords[0].length > 0 && this.passwords[1].length > 0;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfileShowcase.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/profile/ProfileShowcase.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    darkmode: Boolean,
    userData: Object
  },
  data: function data() {
    return {
      userTypes: ["Basic", "Premium", "Admin"]
    };
  },
  computed: {
    createDate: function createDate() {
      var date = new Date(this.userData.created_at);
      return "".concat(date.getFullYear(), "-").concat(date.getMonth() + 1 < 10 ? '0' : '').concat(date.getMonth() + 1, "-").concat(date.getDate() < 10 ? '0' : '').concat(date.getDate());
    },
    premiumObject: function premiumObject() {
      if (this.userData.admin) {
        return {
          type: 2
        };
      }

      if (this.userData.premium_expiration === null) {
        return {
          expiration: false,
          type: 1
        };
      } else {
        var MILISECONDS_IN_DAY = 24 * 60 * 60 * 1000;
        var date = new Date(this.userData.premium_expiration);

        if (new Date().getTime() - MILISECONDS_IN_DAY < date.getTime()) {
          return {
            expiration: Math.ceil((date.getTime() - new Date().getTime()) / MILISECONDS_IN_DAY) + 1,
            type: 1
          };
        } else {
          return {
            type: 0
          };
        }
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfileComponent.vue?vue&type=template&id=14594365&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/profile/ProfileComponent.vue?vue&type=template&id=14594365& ***!
  \***************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { class: _vm.darkmode ? "dark-card" : "card" }, [
    _vm._m(0),
    _vm._v(" "),
    _c("div", { staticClass: "card-body" }, [
      _vm.ready
        ? _c("div", [
            _c("div", { staticClass: "row" }, [
              _c(
                "div",
                { staticClass: "col-lg-6 col-xl-5" },
                [
                  _c("ProfileShowcase", {
                    attrs: { darkmode: _vm.darkmode, userData: _vm.userData }
                  })
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "col-lg-6 col-xl-7 mt-sm-3" },
                [
                  _c("ProfileInfoChange", {
                    attrs: { userData: _vm.userData }
                  }),
                  _vm._v(" "),
                  _c("hr", {
                    class: [
                      _vm.darkmode ? "hr-darkmode" : "hr-lightmode",
                      "my-3"
                    ]
                  }),
                  _vm._v(" "),
                  _c("ProfilePasswordChange")
                ],
                1
              )
            ])
          ])
        : _c("div", { staticClass: "d-flex justify-content-center my-2" }, [
            _vm._m(1)
          ])
    ])
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "card-header-flex" }, [
      _c("div", { staticClass: "card-header-text" }, [
        _c("i", { staticClass: "fas fa-user" }),
        _vm._v("\n                Profile\n            ")
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass: "spinner-grow",
        staticStyle: { width: "3rem", height: "3rem" },
        attrs: { role: "status" }
      },
      [_c("span", { staticClass: "sr-only" }, [_vm._v("Loading...")])]
    )
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfileInfoChange.vue?vue&type=template&id=40918574&":
/*!****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/profile/ProfileInfoChange.vue?vue&type=template&id=40918574& ***!
  \****************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c(
      "form",
      {
        attrs: {
          id: "data-form",
          action: "/profile/update",
          method: "POST",
          enctype: "multipart/form-data"
        }
      },
      [
        _c("input", {
          attrs: { type: "hidden", name: "_token" },
          domProps: { value: _vm.CSRF_TOKEN }
        }),
        _vm._v(" "),
        _c("input", {
          attrs: { type: "hidden", name: "_method", value: "PATCH" }
        }),
        _vm._v(" "),
        _c("div", { staticClass: "form-group row" }, [
          _c(
            "label",
            {
              staticClass: "col-xl-3 offset-xl-1 col-form-label text-xl-right"
            },
            [_vm._v("Username")]
          ),
          _vm._v(" "),
          _c("div", { staticClass: "col-xl-7" }, [
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.userDataCopy.username,
                  expression: "userDataCopy.username"
                }
              ],
              class: ["form-control", !_vm.validUsername && "is-invalid"],
              attrs: {
                type: "text",
                id: "username",
                maxlength: "32",
                name: "username",
                required: ""
              },
              domProps: { value: _vm.userDataCopy.username },
              on: {
                input: function($event) {
                  if ($event.target.composing) {
                    return
                  }
                  _vm.$set(_vm.userDataCopy, "username", $event.target.value)
                }
              }
            })
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "form-group row" }, [
          _c(
            "label",
            {
              staticClass: "col-xl-3 offset-xl-1 col-form-label text-xl-right"
            },
            [_vm._v("Email")]
          ),
          _vm._v(" "),
          _c("div", { staticClass: "col-xl-7" }, [
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.userDataCopy.email,
                  expression: "userDataCopy.email"
                }
              ],
              class: ["form-control", !_vm.validEmail && "is-invalid"],
              attrs: {
                type: "text",
                id: "email",
                maxlength: "64",
                name: "email",
                required: ""
              },
              domProps: { value: _vm.userDataCopy.email },
              on: {
                input: function($event) {
                  if ($event.target.composing) {
                    return
                  }
                  _vm.$set(_vm.userDataCopy, "email", $event.target.value)
                }
              }
            })
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "form-group row" }, [
          _c(
            "label",
            {
              staticClass: "col-xl-3 offset-xl-1 col-form-label text-xl-right"
            },
            [_vm._v("Profile picture")]
          ),
          _vm._v(" "),
          _c("div", { staticClass: "col-xl-7 my-auto" }, [
            _c("input", {
              class: [
                "form-control",
                "form-control-file",
                !_vm.correctFile && "is-invalid"
              ],
              attrs: { type: "file", id: "picture", name: "picture" },
              on: { change: _vm.checkFile }
            })
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "row" }, [
          _c("div", { staticClass: "col-sm-6 my-2 my-sm-0" }, [
            _c(
              "button",
              {
                staticClass: "big-button-success",
                attrs: {
                  type: "button",
                  disabled: _vm.disableSubmit || _vm.submit
                },
                on: { click: _vm.submitForm }
              },
              [
                !_vm.submit
                  ? _c("div", [
                      _vm._v(
                        "\n                            Save changes\n                        "
                      )
                    ])
                  : _c("span", {
                      staticClass: "spinner-border spinner-border-sm",
                      attrs: { role: "status", "aria-hidden": "true" }
                    })
              ]
            )
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col-sm-6 my-2 my-sm-0" }, [
            _c(
              "button",
              {
                staticClass: "big-button-danger",
                attrs: { type: "button", disabled: _vm.submit },
                on: { click: _vm.dataReset }
              },
              [_vm._v("Reset changes")]
            )
          ])
        ])
      ]
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfilePasswordChange.vue?vue&type=template&id=1cb376f3&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/profile/ProfilePasswordChange.vue?vue&type=template&id=1cb376f3& ***!
  \********************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c(
      "form",
      {
        attrs: {
          id: "password-form",
          action: "/profile/password",
          method: "POST",
          enctype: "multipart/form-data"
        }
      },
      [
        _c("input", {
          attrs: { type: "hidden", name: "_token" },
          domProps: { value: _vm.CSRF_TOKEN }
        }),
        _vm._v(" "),
        _c("input", {
          attrs: { type: "hidden", name: "_method", value: "PATCH" }
        }),
        _vm._v(" "),
        _c("div", { staticClass: "form-group row" }, [
          _c(
            "label",
            { staticClass: "col-xl-4 col-form-label text-xl-right" },
            [_vm._v("New password")]
          ),
          _vm._v(" "),
          _c("div", { staticClass: "col-xl-7" }, [
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.passwords[0],
                  expression: "passwords[0]"
                }
              ],
              class: ["form-control", !_vm.validPasswords && "is-invalid"],
              attrs: {
                type: "password",
                name: "password",
                autocomplete: "off"
              },
              domProps: { value: _vm.passwords[0] },
              on: {
                input: function($event) {
                  if ($event.target.composing) {
                    return
                  }
                  _vm.$set(_vm.passwords, 0, $event.target.value)
                }
              }
            })
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "form-group row" }, [
          _c(
            "label",
            { staticClass: "col-xl-4 col-form-label text-xl-right" },
            [_vm._v("Confirm password")]
          ),
          _vm._v(" "),
          _c("div", { staticClass: "col-xl-7" }, [
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.passwords[1],
                  expression: "passwords[1]"
                }
              ],
              class: ["form-control", !_vm.validPasswords && "is-invalid"],
              attrs: {
                type: "password",
                name: "password_confirmation",
                autocomplete: "off"
              },
              domProps: { value: _vm.passwords[1] },
              on: {
                input: function($event) {
                  if ($event.target.composing) {
                    return
                  }
                  _vm.$set(_vm.passwords, 1, $event.target.value)
                }
              }
            })
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "form-group row" }, [
          _c("div", { staticClass: "col-xl-7 offset-xl-4" }, [
            _c(
              "button",
              {
                staticClass: "big-button-success",
                attrs: {
                  type: "button",
                  disabled: !_vm.canSubmit || !_vm.validPasswords
                },
                on: { click: _vm.submitForm }
              },
              [
                !_vm.submit
                  ? _c("div", [
                      _vm._v(
                        "\n                        Save changes\n                    "
                      )
                    ])
                  : _c("span", {
                      staticClass: "spinner-border spinner-border-sm",
                      attrs: { role: "status", "aria-hidden": "true" }
                    })
              ]
            )
          ])
        ])
      ]
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfileShowcase.vue?vue&type=template&id=1244df75&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/profile/ProfileShowcase.vue?vue&type=template&id=1244df75& ***!
  \**************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { class: _vm.darkmode ? "profile-wrapper-dark" : "profile-wrapper-light" },
    [
      _c("div", { staticClass: "profile-picture" }, [
        _c("img", {
          attrs: {
            src: _vm.userData.profile_picture,
            alt: _vm.userData.username
          }
        })
      ]),
      _vm._v(" "),
      _c("p", { class: ["username", _vm.premiumObject.type && "premium"] }, [
        _vm._v("\n        " + _vm._s(_vm.userData.username) + "\n    ")
      ]),
      _vm._v(" "),
      _c("hr"),
      _vm._v(" "),
      _c("div", [
        _c(
          "p",
          { class: ["account-type", _vm.premiumObject.type && "premium"] },
          [
            _vm._v(
              "\n            Account type: " +
                _vm._s(_vm.userTypes[_vm.premiumObject.type]) +
                "\n        "
            )
          ]
        ),
        _vm._v(" "),
        _vm.premiumObject.type && _vm.premiumObject.expiration
          ? _c("p", { staticClass: "account-type premium" }, [
              _vm._v("\n            Premium left:"),
              _c("br"),
              _vm._v(
                "\n            " +
                  _vm._s(_vm.premiumObject.expiration) +
                  " " +
                  _vm._s(_vm.premiumObject.expiration == 1 ? "day" : "days") +
                  "\n        "
              )
            ])
          : _vm._e()
      ]),
      _vm._v(" "),
      _c("hr"),
      _vm._v(" "),
      _c("p", { staticClass: "sa-since" }, [
        _vm._v("\n        With us since:"),
        _c("br"),
        _vm._v("\n        " + _vm._s(_vm.createDate) + "\n    ")
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return normalizeComponent; });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        )
      }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ "./resources/js/components/profile/ProfileComponent.vue":
/*!**************************************************************!*\
  !*** ./resources/js/components/profile/ProfileComponent.vue ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ProfileComponent_vue_vue_type_template_id_14594365___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProfileComponent.vue?vue&type=template&id=14594365& */ "./resources/js/components/profile/ProfileComponent.vue?vue&type=template&id=14594365&");
/* harmony import */ var _ProfileComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProfileComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/profile/ProfileComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ProfileComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProfileComponent_vue_vue_type_template_id_14594365___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ProfileComponent_vue_vue_type_template_id_14594365___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/profile/ProfileComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/profile/ProfileComponent.vue?vue&type=script&lang=js&":
/*!***************************************************************************************!*\
  !*** ./resources/js/components/profile/ProfileComponent.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProfileComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfileComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/profile/ProfileComponent.vue?vue&type=template&id=14594365&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/components/profile/ProfileComponent.vue?vue&type=template&id=14594365& ***!
  \*********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileComponent_vue_vue_type_template_id_14594365___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProfileComponent.vue?vue&type=template&id=14594365& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfileComponent.vue?vue&type=template&id=14594365&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileComponent_vue_vue_type_template_id_14594365___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileComponent_vue_vue_type_template_id_14594365___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/profile/ProfileInfoChange.vue":
/*!***************************************************************!*\
  !*** ./resources/js/components/profile/ProfileInfoChange.vue ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ProfileInfoChange_vue_vue_type_template_id_40918574___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProfileInfoChange.vue?vue&type=template&id=40918574& */ "./resources/js/components/profile/ProfileInfoChange.vue?vue&type=template&id=40918574&");
/* harmony import */ var _ProfileInfoChange_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProfileInfoChange.vue?vue&type=script&lang=js& */ "./resources/js/components/profile/ProfileInfoChange.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ProfileInfoChange_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProfileInfoChange_vue_vue_type_template_id_40918574___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ProfileInfoChange_vue_vue_type_template_id_40918574___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/profile/ProfileInfoChange.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/profile/ProfileInfoChange.vue?vue&type=script&lang=js&":
/*!****************************************************************************************!*\
  !*** ./resources/js/components/profile/ProfileInfoChange.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileInfoChange_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProfileInfoChange.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfileInfoChange.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileInfoChange_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/profile/ProfileInfoChange.vue?vue&type=template&id=40918574&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/components/profile/ProfileInfoChange.vue?vue&type=template&id=40918574& ***!
  \**********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileInfoChange_vue_vue_type_template_id_40918574___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProfileInfoChange.vue?vue&type=template&id=40918574& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfileInfoChange.vue?vue&type=template&id=40918574&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileInfoChange_vue_vue_type_template_id_40918574___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileInfoChange_vue_vue_type_template_id_40918574___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/profile/ProfilePasswordChange.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/components/profile/ProfilePasswordChange.vue ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ProfilePasswordChange_vue_vue_type_template_id_1cb376f3___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProfilePasswordChange.vue?vue&type=template&id=1cb376f3& */ "./resources/js/components/profile/ProfilePasswordChange.vue?vue&type=template&id=1cb376f3&");
/* harmony import */ var _ProfilePasswordChange_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProfilePasswordChange.vue?vue&type=script&lang=js& */ "./resources/js/components/profile/ProfilePasswordChange.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ProfilePasswordChange_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProfilePasswordChange_vue_vue_type_template_id_1cb376f3___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ProfilePasswordChange_vue_vue_type_template_id_1cb376f3___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/profile/ProfilePasswordChange.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/profile/ProfilePasswordChange.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/profile/ProfilePasswordChange.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfilePasswordChange_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProfilePasswordChange.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfilePasswordChange.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfilePasswordChange_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/profile/ProfilePasswordChange.vue?vue&type=template&id=1cb376f3&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/profile/ProfilePasswordChange.vue?vue&type=template&id=1cb376f3& ***!
  \**************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfilePasswordChange_vue_vue_type_template_id_1cb376f3___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProfilePasswordChange.vue?vue&type=template&id=1cb376f3& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfilePasswordChange.vue?vue&type=template&id=1cb376f3&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfilePasswordChange_vue_vue_type_template_id_1cb376f3___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfilePasswordChange_vue_vue_type_template_id_1cb376f3___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/profile/ProfileShowcase.vue":
/*!*************************************************************!*\
  !*** ./resources/js/components/profile/ProfileShowcase.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ProfileShowcase_vue_vue_type_template_id_1244df75___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProfileShowcase.vue?vue&type=template&id=1244df75& */ "./resources/js/components/profile/ProfileShowcase.vue?vue&type=template&id=1244df75&");
/* harmony import */ var _ProfileShowcase_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProfileShowcase.vue?vue&type=script&lang=js& */ "./resources/js/components/profile/ProfileShowcase.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ProfileShowcase_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProfileShowcase_vue_vue_type_template_id_1244df75___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ProfileShowcase_vue_vue_type_template_id_1244df75___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/profile/ProfileShowcase.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/profile/ProfileShowcase.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/profile/ProfileShowcase.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileShowcase_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProfileShowcase.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfileShowcase.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileShowcase_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/profile/ProfileShowcase.vue?vue&type=template&id=1244df75&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/profile/ProfileShowcase.vue?vue&type=template&id=1244df75& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileShowcase_vue_vue_type_template_id_1244df75___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProfileShowcase.vue?vue&type=template&id=1244df75& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/profile/ProfileShowcase.vue?vue&type=template&id=1244df75&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileShowcase_vue_vue_type_template_id_1244df75___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileShowcase_vue_vue_type_template_id_1244df75___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/profile.js":
/*!*********************************!*\
  !*** ./resources/js/profile.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

Vue.component('profile-component', __webpack_require__(/*! ./components/profile/ProfileComponent.vue */ "./resources/js/components/profile/ProfileComponent.vue")["default"]);
var app = new Vue({
  el: '#app'
});

/***/ }),

/***/ 9:
/*!***************************************!*\
  !*** multi ./resources/js/profile.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\rafal\Desktop\Important\Programy\PHP\Laravel\SelfAccounting\resources\js\profile.js */"./resources/js/profile.js");


/***/ })

/******/ });