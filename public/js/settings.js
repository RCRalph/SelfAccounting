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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/SliderCheckbox.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/SliderCheckbox.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    checked: Boolean,
    disabled: Boolean
  },
  methods: {
    emitEvents: function emitEvents(event) {
      this.$emit('input', event.currentTarget.checked);
      this.$emit('htmlElement', event.currentTarget);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/TableHeader.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/TableHeader.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    cells: Array
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/SettingsComponent.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/settings/SettingsComponent.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _categories_Categories_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./categories/Categories.vue */ "./resources/js/components/settings/categories/Categories.vue");
/* harmony import */ var _means_of_payment_MeansOfPayment_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./means-of-payment/MeansOfPayment.vue */ "./resources/js/components/settings/means-of-payment/MeansOfPayment.vue");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

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
    Categories: _categories_Categories_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
    Means: _means_of_payment_MeansOfPayment_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  data: function data() {
    return {
      currencies: [],
      ready: false,
      currentCurrency: 1,
      darkmode: false,
      componentKey: 0,
      // Categories
      categories: {},
      categoriesCopy: {},
      axiosCategories: true,
      // Means of Payment
      means: {},
      meansCopy: {},
      axiosMeans: true
    };
  },
  computed: {
    categoriesCanSave: function categoriesCanSave() {
      this.componentKey;

      for (var currency in this.categories) {
        for (var i = 0; i < this.categories[currency].length; i++) {
          var item = this.categories[currency][i];
          var dateEmpty = !item.start_date || !item.end_date;
          var validDates = dateEmpty ? true : new Date(item.start_date).getTime() <= new Date(item.end_date).getTime();

          if (item.name.length > 32 || !item.name.length || !validDates) {
            return false;
          }
        }
      }

      return true;
    },
    meansCanSave: function meansCanSave() {
      this.componentKey;

      for (var currency in this.means) {
        for (var i = 0; i < this.means[currency].length; i++) {
          var item = this.means[currency][i];

          if (item.name.length > 32 || !item.name.length || !item.first_entry_date || new Date(item.first_entry_date) > new Date(item.date_limit) && item.date_limit != null || parseFloat(item.first_entry_amount) != item.first_entry_amount) {
            return false;
          }
        }
      }

      return true;
    }
  },
  methods: {
    categoriesReset: function categoriesReset() {
      this.categories = _.cloneDeep(this.categoriesCopy);
      this.componentKey++;
    },
    categoriesAdd: function categoriesAdd() {
      if (!(this.currentCurrency in this.categories)) {
        this.categories[this.currentCurrency] = [];
      }

      this.categories[this.currentCurrency].push({
        count_to_summary: false,
        currency_id: this.currentCurrency,
        end_date: null,
        id: null,
        income_category: false,
        name: "",
        outcome_category: false,
        show_on_charts: false,
        start_date: null
      });
      this.componentKey++;
    },
    categoriesDelete: function categoriesDelete(index) {
      this.categories[this.currentCurrency].splice(index, 1);
      this.componentKey++;
    },
    categoriesSave: function categoriesSave() {
      var _this = this;

      this.axiosCategories = false;
      axios.post("/webapi/settings/categories", {
        categories: this.categories
      }).then(function (response) {
        _this.categories = _objectSpread({}, _.cloneDeep(response.data.categories));
        _this.categoriesCopy = _objectSpread({}, _.cloneDeep(response.data.categories));
        _this.componentKey++;
        _this.axiosCategories = true;
      })["catch"](function () {
        _this.axiosCategories = true;
        _this.componentKey++;
      });
    },
    meansReset: function meansReset() {
      this.means = _.cloneDeep(this.meansCopy);
      this.componentKey++;
    },
    meansAdd: function meansAdd() {
      if (!(this.currentCurrency in this.means)) {
        this.means[this.currentCurrency] = [];
      }

      this.means[this.currentCurrency].push({
        count_to_summary: false,
        currency_id: this.currentCurrency,
        first_entry_date: null,
        first_entry_amount: 0,
        id: null,
        income_mean: false,
        name: "",
        outcome_mean: false,
        show_on_charts: false
      });
      this.componentKey++;
    },
    meansDelete: function meansDelete(index) {
      this.means[this.currentCurrency].splice(index, 1);
      this.componentKey++;
    },
    meansSave: function meansSave() {
      var _this2 = this;

      this.axiosMeans = false;
      axios.post("/webapi/settings/means", {
        means: this.means
      }).then(function (response) {
        _this2.means = _objectSpread({}, _.cloneDeep(response.data.means));
        _this2.meansCopy = _objectSpread({}, _.cloneDeep(response.data.means));
        _this2.componentKey++;
        _this2.axiosMeans = true;
      })["catch"](function () {
        _this2.axiosMeans = true;
        _this2.componentKey++;
      });
    },
    updateKey: function updateKey() {
      this.componentKey++;
    }
  },
  beforeMount: function beforeMount() {
    this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
  },
  mounted: function mounted() {
    var _this3 = this;

    axios.get("/webapi/settings", {}).then(function (response) {
      _this3.currencies = response.data.currencies;
      _this3.currentCurrency = response.data.lastCurrency; // Categories

      _this3.categories = _objectSpread({}, response.data.categories);
      _this3.categoriesCopy = _.cloneDeep(_objectSpread({}, response.data.categories)); // Means of Payment

      _this3.means = _objectSpread({}, response.data.means);
      _this3.meansCopy = _.cloneDeep(_objectSpread({}, response.data.means));
      _this3.ready = true;

      _this3.$nextTick(function () {
        $('[data-toggle="tooltip"]').tooltip();
      });
    });
  },
  beforeUpdate: function beforeUpdate() {
    this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
  },
  updated: function updated() {
    this.$nextTick(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/categories/Categories.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/settings/categories/Categories.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _TableHeader_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../TableHeader.vue */ "./resources/js/components/TableHeader.vue");
/* harmony import */ var _CategoriesTableContent_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CategoriesTableContent.vue */ "./resources/js/components/settings/categories/CategoriesTableContent.vue");
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
    TableHeader: _TableHeader_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
    TableBody: _CategoriesTableContent_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  props: {
    darkmode: Boolean,
    buttonsEnabled: Boolean,
    content: Array
  },
  data: function data() {
    return {
      headerCells: [{
        text: "Name",
        tooltip: "The name of your category"
      }, {
        text: "Income",
        tooltip: "Use in income"
      }, {
        text: "Outcome",
        tooltip: "Use in outcome"
      }, {
        text: "Charts",
        tooltip: "Display on charts"
      }, {
        text: "Summary",
        tooltip: "Count to summary"
      }, {
        text: "Start date",
        tooltip: "Count from this date"
      }, {
        text: "End date",
        tooltip: "Count to this date"
      }, {}],
      saveButton: 'Save changes'
    };
  },
  methods: {
    dataSave: function dataSave() {
      this.saveButton = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
      this.$emit('data-save');
    },
    updateData: function updateData() {
      this.$emit("data-change");
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/categories/CategoriesTableContent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/settings/categories/CategoriesTableContent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SliderCheckbox_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../SliderCheckbox.vue */ "./resources/js/components/SliderCheckbox.vue");
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
    content: Object,
    index: Number
  },
  components: {
    Slider: _SliderCheckbox_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  data: function data() {
    return {
      componentKey: 0
    };
  },
  methods: {
    updateComponentKey: function updateComponentKey() {
      this.componentKey++;
      this.$emit("update");
    }
  },
  computed: {
    correctDates: function correctDates() {
      this.componentKey;
      var dateEmpty = !this.content.start_date || !this.content.end_date;
      return dateEmpty ? true : new Date(this.content.start_date).getTime() <= new Date(this.content.end_date).getTime();
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/means-of-payment/MeansOfPayment.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/settings/means-of-payment/MeansOfPayment.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _TableHeader_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../TableHeader.vue */ "./resources/js/components/TableHeader.vue");
/* harmony import */ var _MeansOfPaymentTableContent_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MeansOfPaymentTableContent.vue */ "./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue");
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
    TableHeader: _TableHeader_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
    TableBody: _MeansOfPaymentTableContent_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  props: {
    darkmode: Boolean,
    buttonsEnabled: Boolean,
    content: Array
  },
  data: function data() {
    return {
      headerCells: [{
        text: "Name",
        tooltip: "The name of your mean of payment"
      }, {
        text: "Income",
        tooltip: "Use in income"
      }, {
        text: "Outcome",
        tooltip: "Use in outcome"
      }, {
        text: "Charts",
        tooltip: "Display on charts"
      }, {
        text: "Summary",
        tooltip: "Count to summary"
      }, {
        text: "First entry",
        tooltip: "Date from which to add the starting balance"
      }, {
        text: "Starting balance",
        tooltip: "Amount which to use for the first entry"
      }, {}],
      saveButton: 'Save changes'
    };
  },
  methods: {
    dataSave: function dataSave() {
      this.saveButton = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
      this.$emit('data-save');
    },
    updateData: function updateData() {
      this.$emit("data-change");
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SliderCheckbox_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../SliderCheckbox.vue */ "./resources/js/components/SliderCheckbox.vue");
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
    content: Object,
    index: Number
  },
  components: {
    Slider: _SliderCheckbox_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  data: function data() {
    return {
      componentKey: 0
    };
  },
  methods: {
    updateComponentKey: function updateComponentKey() {
      this.componentKey++;
      this.$emit("update");
    }
  },
  computed: {
    correctDate: function correctDate() {
      this.componentKey;
      var dateEmpty = !this.content.first_entry_date;

      if (this.content.date_limit == null && !dateEmpty) {
        return true;
      }

      return dateEmpty ? false : new Date(this.content.first_entry_date) <= new Date(this.content.date_limit);
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/SliderCheckbox.vue?vue&type=template&id=147a70ef&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/SliderCheckbox.vue?vue&type=template&id=147a70ef& ***!
  \*****************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "slider-checkbox" }, [
    _c("label", { staticClass: "switch m-0" }, [
      _c("input", {
        attrs: { type: "checkbox", disabled: _vm.disabled },
        domProps: { checked: _vm.checked },
        on: { change: _vm.emitEvents }
      }),
      _vm._v(" "),
      _c("span", { staticClass: "slider round" })
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/TableHeader.vue?vue&type=template&id=6f15fd60&":
/*!**************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/TableHeader.vue?vue&type=template&id=6f15fd60& ***!
  \**************************************************************************************************************************************************************************************************************/
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
  return _c("thead", [
    _c(
      "tr",
      _vm._l(_vm.cells, function(cell, index) {
        return _c(
          "th",
          {
            key: index,
            class: cell.text && "h5 font-weight-bold",
            attrs: {
              scope: cell.text && "col",
              "data-toggle": cell.tooltip && "tooltip",
              "data-placement": cell.tooltip && "bottom",
              title: cell.tooltip && cell.tooltip
            }
          },
          [_vm._v("\n            " + _vm._s(cell.text) + "\n        ")]
        )
      }),
      0
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/SettingsComponent.vue?vue&type=template&id=79d9b899&":
/*!*****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/settings/SettingsComponent.vue?vue&type=template&id=79d9b899& ***!
  \*****************************************************************************************************************************************************************************************************************************/
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
    _c("div", { staticClass: "card-header-flex" }, [
      _vm._m(0),
      _vm._v(" "),
      _vm.ready
        ? _c("div", { staticClass: "d-flex" }, [
            _c("div", { staticClass: "h4 my-auto mr-3" }, [
              _vm._v("Currency:")
            ]),
            _vm._v(" "),
            _c(
              "select",
              {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.currentCurrency,
                    expression: "currentCurrency"
                  }
                ],
                staticClass: "form-control",
                on: {
                  change: function($event) {
                    var $$selectedVal = Array.prototype.filter
                      .call($event.target.options, function(o) {
                        return o.selected
                      })
                      .map(function(o) {
                        var val = "_value" in o ? o._value : o.value
                        return val
                      })
                    _vm.currentCurrency = $event.target.multiple
                      ? $$selectedVal
                      : $$selectedVal[0]
                  }
                }
              },
              _vm._l(_vm.currencies, function(currency) {
                return _c(
                  "option",
                  { key: currency.id, domProps: { value: currency.id } },
                  [
                    _vm._v(
                      "\n                    " +
                        _vm._s(currency.ISO) +
                        "\n                "
                    )
                  ]
                )
              }),
              0
            )
          ])
        : _vm._e()
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "card-body" }, [
      _vm.ready
        ? _c(
            "div",
            [
              _c("Categories", {
                key: _vm.componentKey,
                attrs: {
                  content: _vm.categories[_vm.currentCurrency],
                  darkmode: _vm.darkmode,
                  buttonsEnabled: _vm.axiosCategories && _vm.categoriesCanSave
                },
                on: {
                  "data-reset": _vm.categoriesReset,
                  "data-add": _vm.categoriesAdd,
                  "data-delete": _vm.categoriesDelete,
                  "data-save": _vm.categoriesSave,
                  "data-change": _vm.updateKey
                }
              }),
              _vm._v(" "),
              _c("Means", {
                key: _vm.componentKey + 1,
                staticClass: "mt-4",
                attrs: {
                  content: _vm.means[_vm.currentCurrency],
                  darkmode: _vm.darkmode,
                  buttonsEnabled: _vm.axiosMeans && _vm.meansCanSave
                },
                on: {
                  "data-reset": _vm.meansReset,
                  "data-add": _vm.meansAdd,
                  "data-delete": _vm.meansDelete,
                  "data-save": _vm.meansSave,
                  "data-change": _vm.updateKey
                }
              })
            ],
            1
          )
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
    return _c("div", { staticClass: "card-header-text" }, [
      _c("i", { staticClass: "fas fa-cog" }),
      _vm._v("\n            Settings\n        ")
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/categories/Categories.vue?vue&type=template&id=cc25d2a4&":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/settings/categories/Categories.vue?vue&type=template&id=cc25d2a4& ***!
  \*********************************************************************************************************************************************************************************************************************************/
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
      _vm.content != undefined && _vm.content.length > 0
        ? _c("div", { staticClass: "table-responsive-xl w-100" }, [
            _c(
              "table",
              {
                class: [
                  "responsive-table-hover",
                  _vm.darkmode ? "table-darkmode" : "table-lightmode"
                ]
              },
              [
                _c("TableHeader", { attrs: { cells: _vm.headerCells } }),
                _vm._v(" "),
                _c(
                  "tbody",
                  _vm._l(_vm.content, function(item, index) {
                    return _c("TableBody", {
                      key: index,
                      attrs: { content: item, index: index },
                      on: {
                        delete: function($event) {
                          return _vm.$emit("data-delete", index)
                        },
                        update: _vm.updateData
                      }
                    })
                  }),
                  1
                )
              ],
              1
            )
          ])
        : _c("div", [
            _c("div", { staticClass: "h1 text-center" }, [
              _vm._v("Nothing to see here, for now...")
            ])
          ]),
      _vm._v(" "),
      _c("hr"),
      _vm._v(" "),
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col-md-4" }, [
          _c(
            "button",
            {
              staticClass: "big-button-primary",
              attrs: { disabled: !_vm.buttonsEnabled },
              on: {
                click: function($event) {
                  return _vm.$emit("data-add")
                }
              }
            },
            [_vm._v("\n                    New category\n                ")]
          )
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "col-md-4 my-2 my-md-0" }, [
          _c(
            "button",
            {
              staticClass: "big-button-danger",
              attrs: { disabled: !_vm.buttonsEnabled },
              on: {
                click: function($event) {
                  return _vm.$emit("data-reset")
                }
              }
            },
            [_vm._v("\n                    Reset changes\n                ")]
          )
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "col-md-4" }, [
          _c("button", {
            staticClass: "big-button-success",
            attrs: { disabled: !_vm.buttonsEnabled },
            domProps: { innerHTML: _vm._s(_vm.saveButton) },
            on: { click: _vm.dataSave }
          })
        ])
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
        _c("i", { staticClass: "fab fa-buffer" }),
        _vm._v("\n            Categories\n        ")
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/categories/CategoriesTableContent.vue?vue&type=template&id=9be44dce&":
/*!*********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/settings/categories/CategoriesTableContent.vue?vue&type=template&id=9be44dce& ***!
  \*********************************************************************************************************************************************************************************************************************************************/
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
  return _c("tr", { attrs: { i: _vm.index } }, [
    _c("td", [
      _c("input", {
        directives: [
          {
            name: "model",
            rawName: "v-model",
            value: _vm.content.name,
            expression: "content.name"
          }
        ],
        key: _vm.componentKey,
        class: [
          "form-text",
          (!_vm.content.name.length || _vm.content.name.length > 32) &&
            "is-invalid"
        ],
        attrs: { type: "text", placeholder: "Name", maxlength: "32" },
        domProps: { value: _vm.content.name },
        on: {
          change: _vm.updateComponentKey,
          input: function($event) {
            if ($event.target.composing) {
              return
            }
            _vm.$set(_vm.content, "name", $event.target.value)
          }
        }
      })
    ]),
    _vm._v(" "),
    _c(
      "td",
      [
        _c("Slider", {
          attrs: { checked: _vm.content.income_category },
          model: {
            value: _vm.content.income_category,
            callback: function($$v) {
              _vm.$set(_vm.content, "income_category", $$v)
            },
            expression: "content.income_category"
          }
        })
      ],
      1
    ),
    _vm._v(" "),
    _c(
      "td",
      [
        _c("Slider", {
          attrs: { checked: _vm.content.outcome_category },
          model: {
            value: _vm.content.outcome_category,
            callback: function($$v) {
              _vm.$set(_vm.content, "outcome_category", $$v)
            },
            expression: "content.outcome_category"
          }
        })
      ],
      1
    ),
    _vm._v(" "),
    _c(
      "td",
      [
        _c("Slider", {
          attrs: { checked: _vm.content.show_on_charts },
          model: {
            value: _vm.content.show_on_charts,
            callback: function($$v) {
              _vm.$set(_vm.content, "show_on_charts", $$v)
            },
            expression: "content.show_on_charts"
          }
        })
      ],
      1
    ),
    _vm._v(" "),
    _c(
      "td",
      [
        _c("Slider", {
          attrs: { checked: _vm.content.count_to_summary },
          on: { input: _vm.updateComponentKey },
          model: {
            value: _vm.content.count_to_summary,
            callback: function($$v) {
              _vm.$set(_vm.content, "count_to_summary", $$v)
            },
            expression: "content.count_to_summary"
          }
        })
      ],
      1
    ),
    _vm._v(" "),
    _c("td", [
      _c("input", {
        directives: [
          {
            name: "model",
            rawName: "v-model",
            value: _vm.content.start_date,
            expression: "content.start_date"
          }
        ],
        key: _vm.componentKey,
        class: ["form-date", !_vm.correctDates && "is-invalid"],
        attrs: { type: "date", disabled: !_vm.content.count_to_summary },
        domProps: { value: _vm.content.start_date },
        on: {
          change: _vm.updateComponentKey,
          input: function($event) {
            if ($event.target.composing) {
              return
            }
            _vm.$set(_vm.content, "start_date", $event.target.value)
          }
        }
      })
    ]),
    _vm._v(" "),
    _c("td", [
      _c("input", {
        directives: [
          {
            name: "model",
            rawName: "v-model",
            value: _vm.content.end_date,
            expression: "content.end_date"
          }
        ],
        key: _vm.componentKey,
        class: ["form-date", !_vm.correctDates && "is-invalid"],
        attrs: { type: "date", disabled: !_vm.content.count_to_summary },
        domProps: { value: _vm.content.end_date },
        on: {
          change: _vm.updateComponentKey,
          input: function($event) {
            if ($event.target.composing) {
              return
            }
            _vm.$set(_vm.content, "end_date", $event.target.value)
          }
        }
      })
    ]),
    _vm._v(" "),
    _c(
      "td",
      {
        staticClass: "trashbin",
        on: {
          click: function($event) {
            return _vm.$emit("delete", _vm.index)
          }
        }
      },
      [_c("i", { staticClass: "fas fa-trash" })]
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/means-of-payment/MeansOfPayment.vue?vue&type=template&id=52f690c0&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/settings/means-of-payment/MeansOfPayment.vue?vue&type=template&id=52f690c0& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
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
      _vm.content != undefined && _vm.content.length > 0
        ? _c("div", { staticClass: "table-responsive-xl w-100" }, [
            _c(
              "table",
              {
                class: [
                  "responsive-table-hover",
                  _vm.darkmode ? "table-darkmode" : "table-lightmode"
                ]
              },
              [
                _c("TableHeader", { attrs: { cells: _vm.headerCells } }),
                _vm._v(" "),
                _c(
                  "tbody",
                  _vm._l(_vm.content, function(item, index) {
                    return _c("TableBody", {
                      key: index,
                      attrs: { content: item, index: index },
                      on: {
                        delete: function($event) {
                          return _vm.$emit("data-delete", index)
                        },
                        update: _vm.updateData
                      }
                    })
                  }),
                  1
                )
              ],
              1
            )
          ])
        : _c("div", [
            _c("div", { staticClass: "h1 text-center" }, [
              _vm._v("Nothing to see here, for now...")
            ])
          ]),
      _vm._v(" "),
      _c("hr"),
      _vm._v(" "),
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col-md-4" }, [
          _c(
            "button",
            {
              staticClass: "big-button-primary",
              attrs: { disabled: !_vm.buttonsEnabled },
              on: {
                click: function($event) {
                  return _vm.$emit("data-add")
                }
              }
            },
            [
              _vm._v(
                "\n                    New mean of payment\n                "
              )
            ]
          )
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "col-md-4 my-2 my-md-0" }, [
          _c(
            "button",
            {
              staticClass: "big-button-danger",
              attrs: { disabled: !_vm.buttonsEnabled },
              on: {
                click: function($event) {
                  return _vm.$emit("data-reset")
                }
              }
            },
            [_vm._v("\n                    Reset changes\n                ")]
          )
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "col-md-4" }, [
          _c("button", {
            staticClass: "big-button-success",
            attrs: { disabled: !_vm.buttonsEnabled },
            domProps: { innerHTML: _vm._s(_vm.saveButton) },
            on: { click: _vm.dataSave }
          })
        ])
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
        _c("i", { staticClass: "fas fa-coins" }),
        _vm._v("\n            Means of Payment\n        ")
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue?vue&type=template&id=20119f0b&":
/*!*******************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue?vue&type=template&id=20119f0b& ***!
  \*******************************************************************************************************************************************************************************************************************************************************/
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
  return _c("tr", { attrs: { i: _vm.index } }, [
    _c("td", [
      _c("input", {
        directives: [
          {
            name: "model",
            rawName: "v-model",
            value: _vm.content.name,
            expression: "content.name"
          }
        ],
        key: _vm.componentKey,
        class: [
          "form-text",
          (!_vm.content.name.length || _vm.content.name.length > 32) &&
            "is-invalid"
        ],
        attrs: { type: "text", placeholder: "Name", maxlength: "32" },
        domProps: { value: _vm.content.name },
        on: {
          change: _vm.updateComponentKey,
          input: function($event) {
            if ($event.target.composing) {
              return
            }
            _vm.$set(_vm.content, "name", $event.target.value)
          }
        }
      })
    ]),
    _c(
      "td",
      [
        _c("Slider", {
          attrs: { checked: _vm.content.income_mean },
          model: {
            value: _vm.content.income_mean,
            callback: function($$v) {
              _vm.$set(_vm.content, "income_mean", $$v)
            },
            expression: "content.income_mean"
          }
        })
      ],
      1
    ),
    _vm._v(" "),
    _c(
      "td",
      [
        _c("Slider", {
          attrs: { checked: _vm.content.outcome_mean },
          model: {
            value: _vm.content.outcome_mean,
            callback: function($$v) {
              _vm.$set(_vm.content, "outcome_mean", $$v)
            },
            expression: "content.outcome_mean"
          }
        })
      ],
      1
    ),
    _vm._v(" "),
    _c(
      "td",
      [
        _c("Slider", {
          attrs: { checked: _vm.content.show_on_charts },
          model: {
            value: _vm.content.show_on_charts,
            callback: function($$v) {
              _vm.$set(_vm.content, "show_on_charts", $$v)
            },
            expression: "content.show_on_charts"
          }
        })
      ],
      1
    ),
    _vm._v(" "),
    _c(
      "td",
      [
        _c("Slider", {
          attrs: { checked: _vm.content.count_to_summary },
          model: {
            value: _vm.content.count_to_summary,
            callback: function($$v) {
              _vm.$set(_vm.content, "count_to_summary", $$v)
            },
            expression: "content.count_to_summary"
          }
        })
      ],
      1
    ),
    _vm._v(" "),
    _c("td", [
      _c("input", {
        directives: [
          {
            name: "model",
            rawName: "v-model",
            value: _vm.content.first_entry_date,
            expression: "content.first_entry_date"
          }
        ],
        key: _vm.componentKey,
        class: ["form-date", !_vm.correctDate && "is-invalid"],
        attrs: { type: "date" },
        domProps: { value: _vm.content.first_entry_date },
        on: {
          change: _vm.updateComponentKey,
          input: function($event) {
            if ($event.target.composing) {
              return
            }
            _vm.$set(_vm.content, "first_entry_date", $event.target.value)
          }
        }
      })
    ]),
    _vm._v(" "),
    _c("td", [
      _c("input", {
        directives: [
          {
            name: "model",
            rawName: "v-model",
            value: _vm.content.first_entry_amount,
            expression: "content.first_entry_amount"
          }
        ],
        key: _vm.componentKey,
        class: [
          "form-text",
          parseFloat(_vm.content.first_entry_amount) !=
            _vm.content.first_entry_amount && "is-invalid"
        ],
        attrs: { type: "number", step: ".01" },
        domProps: { value: _vm.content.first_entry_amount },
        on: {
          change: _vm.updateComponentKey,
          input: function($event) {
            if ($event.target.composing) {
              return
            }
            _vm.$set(_vm.content, "first_entry_amount", $event.target.value)
          }
        }
      })
    ]),
    _vm._v(" "),
    _c(
      "td",
      {
        staticClass: "trashbin",
        on: {
          click: function($event) {
            return _vm.$emit("delete", _vm.index)
          }
        }
      },
      [_c("i", { staticClass: "fas fa-trash" })]
    )
  ])
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

/***/ "./resources/js/components/SliderCheckbox.vue":
/*!****************************************************!*\
  !*** ./resources/js/components/SliderCheckbox.vue ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SliderCheckbox_vue_vue_type_template_id_147a70ef___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SliderCheckbox.vue?vue&type=template&id=147a70ef& */ "./resources/js/components/SliderCheckbox.vue?vue&type=template&id=147a70ef&");
/* harmony import */ var _SliderCheckbox_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SliderCheckbox.vue?vue&type=script&lang=js& */ "./resources/js/components/SliderCheckbox.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SliderCheckbox_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SliderCheckbox_vue_vue_type_template_id_147a70ef___WEBPACK_IMPORTED_MODULE_0__["render"],
  _SliderCheckbox_vue_vue_type_template_id_147a70ef___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/SliderCheckbox.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/SliderCheckbox.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/SliderCheckbox.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SliderCheckbox_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./SliderCheckbox.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/SliderCheckbox.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SliderCheckbox_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/SliderCheckbox.vue?vue&type=template&id=147a70ef&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/SliderCheckbox.vue?vue&type=template&id=147a70ef& ***!
  \***********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SliderCheckbox_vue_vue_type_template_id_147a70ef___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./SliderCheckbox.vue?vue&type=template&id=147a70ef& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/SliderCheckbox.vue?vue&type=template&id=147a70ef&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SliderCheckbox_vue_vue_type_template_id_147a70ef___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SliderCheckbox_vue_vue_type_template_id_147a70ef___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/TableHeader.vue":
/*!*************************************************!*\
  !*** ./resources/js/components/TableHeader.vue ***!
  \*************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _TableHeader_vue_vue_type_template_id_6f15fd60___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TableHeader.vue?vue&type=template&id=6f15fd60& */ "./resources/js/components/TableHeader.vue?vue&type=template&id=6f15fd60&");
/* harmony import */ var _TableHeader_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TableHeader.vue?vue&type=script&lang=js& */ "./resources/js/components/TableHeader.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _TableHeader_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _TableHeader_vue_vue_type_template_id_6f15fd60___WEBPACK_IMPORTED_MODULE_0__["render"],
  _TableHeader_vue_vue_type_template_id_6f15fd60___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/TableHeader.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/TableHeader.vue?vue&type=script&lang=js&":
/*!**************************************************************************!*\
  !*** ./resources/js/components/TableHeader.vue?vue&type=script&lang=js& ***!
  \**************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TableHeader_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./TableHeader.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/TableHeader.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TableHeader_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/TableHeader.vue?vue&type=template&id=6f15fd60&":
/*!********************************************************************************!*\
  !*** ./resources/js/components/TableHeader.vue?vue&type=template&id=6f15fd60& ***!
  \********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TableHeader_vue_vue_type_template_id_6f15fd60___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./TableHeader.vue?vue&type=template&id=6f15fd60& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/TableHeader.vue?vue&type=template&id=6f15fd60&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TableHeader_vue_vue_type_template_id_6f15fd60___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TableHeader_vue_vue_type_template_id_6f15fd60___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/settings/SettingsComponent.vue":
/*!****************************************************************!*\
  !*** ./resources/js/components/settings/SettingsComponent.vue ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SettingsComponent_vue_vue_type_template_id_79d9b899___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SettingsComponent.vue?vue&type=template&id=79d9b899& */ "./resources/js/components/settings/SettingsComponent.vue?vue&type=template&id=79d9b899&");
/* harmony import */ var _SettingsComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SettingsComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/settings/SettingsComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SettingsComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SettingsComponent_vue_vue_type_template_id_79d9b899___WEBPACK_IMPORTED_MODULE_0__["render"],
  _SettingsComponent_vue_vue_type_template_id_79d9b899___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/settings/SettingsComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/settings/SettingsComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/components/settings/SettingsComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingsComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./SettingsComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/SettingsComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingsComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/settings/SettingsComponent.vue?vue&type=template&id=79d9b899&":
/*!***********************************************************************************************!*\
  !*** ./resources/js/components/settings/SettingsComponent.vue?vue&type=template&id=79d9b899& ***!
  \***********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingsComponent_vue_vue_type_template_id_79d9b899___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./SettingsComponent.vue?vue&type=template&id=79d9b899& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/SettingsComponent.vue?vue&type=template&id=79d9b899&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingsComponent_vue_vue_type_template_id_79d9b899___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingsComponent_vue_vue_type_template_id_79d9b899___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/settings/categories/Categories.vue":
/*!********************************************************************!*\
  !*** ./resources/js/components/settings/categories/Categories.vue ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Categories_vue_vue_type_template_id_cc25d2a4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Categories.vue?vue&type=template&id=cc25d2a4& */ "./resources/js/components/settings/categories/Categories.vue?vue&type=template&id=cc25d2a4&");
/* harmony import */ var _Categories_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Categories.vue?vue&type=script&lang=js& */ "./resources/js/components/settings/categories/Categories.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Categories_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Categories_vue_vue_type_template_id_cc25d2a4___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Categories_vue_vue_type_template_id_cc25d2a4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/settings/categories/Categories.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/settings/categories/Categories.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/components/settings/categories/Categories.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Categories.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/categories/Categories.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/settings/categories/Categories.vue?vue&type=template&id=cc25d2a4&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/components/settings/categories/Categories.vue?vue&type=template&id=cc25d2a4& ***!
  \***************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_template_id_cc25d2a4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Categories.vue?vue&type=template&id=cc25d2a4& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/categories/Categories.vue?vue&type=template&id=cc25d2a4&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_template_id_cc25d2a4___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_template_id_cc25d2a4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/settings/categories/CategoriesTableContent.vue":
/*!********************************************************************************!*\
  !*** ./resources/js/components/settings/categories/CategoriesTableContent.vue ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _CategoriesTableContent_vue_vue_type_template_id_9be44dce___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CategoriesTableContent.vue?vue&type=template&id=9be44dce& */ "./resources/js/components/settings/categories/CategoriesTableContent.vue?vue&type=template&id=9be44dce&");
/* harmony import */ var _CategoriesTableContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CategoriesTableContent.vue?vue&type=script&lang=js& */ "./resources/js/components/settings/categories/CategoriesTableContent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _CategoriesTableContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _CategoriesTableContent_vue_vue_type_template_id_9be44dce___WEBPACK_IMPORTED_MODULE_0__["render"],
  _CategoriesTableContent_vue_vue_type_template_id_9be44dce___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/settings/categories/CategoriesTableContent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/settings/categories/CategoriesTableContent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/components/settings/categories/CategoriesTableContent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CategoriesTableContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./CategoriesTableContent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/categories/CategoriesTableContent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CategoriesTableContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/settings/categories/CategoriesTableContent.vue?vue&type=template&id=9be44dce&":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/components/settings/categories/CategoriesTableContent.vue?vue&type=template&id=9be44dce& ***!
  \***************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CategoriesTableContent_vue_vue_type_template_id_9be44dce___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./CategoriesTableContent.vue?vue&type=template&id=9be44dce& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/categories/CategoriesTableContent.vue?vue&type=template&id=9be44dce&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CategoriesTableContent_vue_vue_type_template_id_9be44dce___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CategoriesTableContent_vue_vue_type_template_id_9be44dce___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/settings/means-of-payment/MeansOfPayment.vue":
/*!******************************************************************************!*\
  !*** ./resources/js/components/settings/means-of-payment/MeansOfPayment.vue ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _MeansOfPayment_vue_vue_type_template_id_52f690c0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MeansOfPayment.vue?vue&type=template&id=52f690c0& */ "./resources/js/components/settings/means-of-payment/MeansOfPayment.vue?vue&type=template&id=52f690c0&");
/* harmony import */ var _MeansOfPayment_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MeansOfPayment.vue?vue&type=script&lang=js& */ "./resources/js/components/settings/means-of-payment/MeansOfPayment.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _MeansOfPayment_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _MeansOfPayment_vue_vue_type_template_id_52f690c0___WEBPACK_IMPORTED_MODULE_0__["render"],
  _MeansOfPayment_vue_vue_type_template_id_52f690c0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/settings/means-of-payment/MeansOfPayment.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/settings/means-of-payment/MeansOfPayment.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/components/settings/means-of-payment/MeansOfPayment.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MeansOfPayment_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./MeansOfPayment.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/means-of-payment/MeansOfPayment.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MeansOfPayment_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/settings/means-of-payment/MeansOfPayment.vue?vue&type=template&id=52f690c0&":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/components/settings/means-of-payment/MeansOfPayment.vue?vue&type=template&id=52f690c0& ***!
  \*************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MeansOfPayment_vue_vue_type_template_id_52f690c0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./MeansOfPayment.vue?vue&type=template&id=52f690c0& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/means-of-payment/MeansOfPayment.vue?vue&type=template&id=52f690c0&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MeansOfPayment_vue_vue_type_template_id_52f690c0___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MeansOfPayment_vue_vue_type_template_id_52f690c0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue":
/*!******************************************************************************************!*\
  !*** ./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue ***!
  \******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _MeansOfPaymentTableContent_vue_vue_type_template_id_20119f0b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MeansOfPaymentTableContent.vue?vue&type=template&id=20119f0b& */ "./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue?vue&type=template&id=20119f0b&");
/* harmony import */ var _MeansOfPaymentTableContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MeansOfPaymentTableContent.vue?vue&type=script&lang=js& */ "./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _MeansOfPaymentTableContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _MeansOfPaymentTableContent_vue_vue_type_template_id_20119f0b___WEBPACK_IMPORTED_MODULE_0__["render"],
  _MeansOfPaymentTableContent_vue_vue_type_template_id_20119f0b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************!*\
  !*** ./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MeansOfPaymentTableContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./MeansOfPaymentTableContent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MeansOfPaymentTableContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue?vue&type=template&id=20119f0b&":
/*!*************************************************************************************************************************!*\
  !*** ./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue?vue&type=template&id=20119f0b& ***!
  \*************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MeansOfPaymentTableContent_vue_vue_type_template_id_20119f0b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./MeansOfPaymentTableContent.vue?vue&type=template&id=20119f0b& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/means-of-payment/MeansOfPaymentTableContent.vue?vue&type=template&id=20119f0b&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MeansOfPaymentTableContent_vue_vue_type_template_id_20119f0b___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MeansOfPaymentTableContent_vue_vue_type_template_id_20119f0b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/settings.js":
/*!**********************************!*\
  !*** ./resources/js/settings.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

Vue.component('settings-component', __webpack_require__(/*! ./components/settings/SettingsComponent.vue */ "./resources/js/components/settings/SettingsComponent.vue")["default"]);
var app = new Vue({
  el: '#app'
});

/***/ }),

/***/ 2:
/*!****************************************!*\
  !*** multi ./resources/js/settings.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\rafal\Desktop\Important\Programy\PHP\Laravel\SelfAccounting\resources\js\settings.js */"./resources/js/settings.js");


/***/ })

/******/ });