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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/table-hovering.js":
/*!****************************************!*\
  !*** ./resources/js/table-hovering.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

if ($("#table-multi-hover").length) {
  var headerValues = Array.from($("thead")[0].children[0].children).map(function (item) {
    return item.innerText.toLowerCase();
  });
  var table = [];
  var tableCells = Array.from($("tbody")[0].children).map(function (item) {
    return item.children;
  }).map(function (item) {
    return Array.from(item);
  });

  for (var i = 0; i < tableCells.length; i++) {
    var tempObj = {};

    for (var j = 0; j < tableCells[i].length; j++) {
      tempObj[tableCells[i][j].attributes.rep.value] = tableCells[i][j];
    }

    for (var _j = 0; _j < headerValues.length; _j++) {
      if (i != 0 && tempObj[headerValues[_j]] == undefined) {
        tempObj[headerValues[_j]] = table[i - 1][headerValues[_j]];
      }
    }

    table.push(Object.assign({}, tempObj));
  }

  $("tbody td, tbody th").on("mouseover", function (event) {
    var isDarkmode = $('#sun-moon').html().includes('<i class="fas fa-sun"></i>');
    var rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);
    var rowspan = event.currentTarget.attributes.rowspan != undefined ? parseInt(event.currentTarget.attributes.rowspan.value) : 1;

    for (var _i = 0; _i < rowspan; _i++) {
      for (var _j2 in table[rowIndex + _i]) {
        $(table[rowIndex + _i][_j2]).addClass("hover-bg-" + (isDarkmode ? "dark" : "light"));
      }
    }
  });
  $("tbody td, tbody th").on("mouseleave", function (event) {
    var isDarkmode = $('#sun-moon').html().includes('<i class="fas fa-sun"></i>');
    var rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);
    var rowspan = event.currentTarget.attributes.rowspan != undefined ? parseInt(event.currentTarget.attributes.rowspan.value) : 1;

    for (var _i2 = 0; _i2 < rowspan; _i2++) {
      for (var _j3 in table[rowIndex + _i2]) {
        $(table[rowIndex + _i2][_j3]).removeClass("hover-bg-" + (isDarkmode ? "dark" : "light"));
      }
    }
  });
}

/***/ }),

/***/ 1:
/*!**********************************************!*\
  !*** multi ./resources/js/table-hovering.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\rafal\Desktop\Important\Programy\PHP\Laravel\SelfAccounting\resources\js\table-hovering.js */"./resources/js/table-hovering.js");


/***/ })

/******/ });