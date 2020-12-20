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

/***/ "./resources/js/scripts/table-hovering.js":
/*!************************************************!*\
  !*** ./resources/js/scripts/table-hovering.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("if ($(\"#table-multi-hover\").length) {\n  var headerValues = Array.from($(\"thead\")[0].children[0].children).map(function (item) {\n    return item.innerText.toLowerCase();\n  });\n  var table = [];\n  var tableCells = Array.from($(\"tbody\")[0].children).map(function (item) {\n    return item.children;\n  }).map(function (item) {\n    return Array.from(item);\n  });\n\n  for (var i = 0; i < tableCells.length; i++) {\n    var tempObj = {};\n\n    for (var j = 0; j < tableCells[i].length; j++) {\n      tempObj[tableCells[i][j].attributes.rep.value] = tableCells[i][j];\n    }\n\n    for (var _j = 0; _j < headerValues.length; _j++) {\n      if (i != 0 && tempObj[headerValues[_j]] == undefined) {\n        tempObj[headerValues[_j]] = table[i - 1][headerValues[_j]];\n      }\n    }\n\n    table.push(Object.assign({}, tempObj));\n  }\n\n  $(\"tbody td, tbody th\").on(\"mouseover\", function (event) {\n    var isDarkmode = $('#sun-moon').html().includes('<i class=\"fas fa-sun\"></i>');\n    var rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);\n    var rowspan = event.currentTarget.attributes.rowspan != undefined ? parseInt(event.currentTarget.attributes.rowspan.value) : 1;\n\n    for (var _i = 0; _i < rowspan; _i++) {\n      for (var _j2 in table[rowIndex + _i]) {\n        $(table[rowIndex + _i][_j2]).addClass(\"hover-bg-\" + (isDarkmode ? \"dark\" : \"light\"));\n      }\n    }\n  });\n  $(\"tbody td, tbody th\").on(\"mouseleave\", function (event) {\n    var isDarkmode = $('#sun-moon').html().includes('<i class=\"fas fa-sun\"></i>');\n    var rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);\n    var rowspan = event.currentTarget.attributes.rowspan != undefined ? parseInt(event.currentTarget.attributes.rowspan.value) : 1;\n\n    for (var _i2 = 0; _i2 < rowspan; _i2++) {\n      for (var _j3 in table[rowIndex + _i2]) {\n        $(table[rowIndex + _i2][_j3]).removeClass(\"hover-bg-\" + (isDarkmode ? \"dark\" : \"light\"));\n      }\n    }\n  });\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvc2NyaXB0cy90YWJsZS1ob3ZlcmluZy5qcz84MTljIl0sIm5hbWVzIjpbIiQiLCJsZW5ndGgiLCJoZWFkZXJWYWx1ZXMiLCJBcnJheSIsImZyb20iLCJjaGlsZHJlbiIsIm1hcCIsIml0ZW0iLCJpbm5lclRleHQiLCJ0b0xvd2VyQ2FzZSIsInRhYmxlIiwidGFibGVDZWxscyIsImkiLCJ0ZW1wT2JqIiwiaiIsImF0dHJpYnV0ZXMiLCJyZXAiLCJ2YWx1ZSIsInVuZGVmaW5lZCIsInB1c2giLCJPYmplY3QiLCJhc3NpZ24iLCJvbiIsImV2ZW50IiwiaXNEYXJrbW9kZSIsImh0bWwiLCJpbmNsdWRlcyIsInJvd0luZGV4IiwicGFyc2VJbnQiLCJjdXJyZW50VGFyZ2V0IiwicGFyZW50RWxlbWVudCIsInJvd3NwYW4iLCJhZGRDbGFzcyIsInJlbW92ZUNsYXNzIl0sIm1hcHBpbmdzIjoiQUFBQSxJQUFJQSxDQUFDLENBQUMsb0JBQUQsQ0FBRCxDQUF3QkMsTUFBNUIsRUFBb0M7QUFDaEMsTUFBTUMsWUFBWSxHQUFHQyxLQUFLLENBQUNDLElBQU4sQ0FBV0osQ0FBQyxDQUFDLE9BQUQsQ0FBRCxDQUFXLENBQVgsRUFBY0ssUUFBZCxDQUF1QixDQUF2QixFQUEwQkEsUUFBckMsRUFDaEJDLEdBRGdCLENBQ1osVUFBQUMsSUFBSTtBQUFBLFdBQUlBLElBQUksQ0FBQ0MsU0FBTCxDQUFlQyxXQUFmLEVBQUo7QUFBQSxHQURRLENBQXJCO0FBR0EsTUFBSUMsS0FBSyxHQUFHLEVBQVo7QUFFQSxNQUFNQyxVQUFVLEdBQUdSLEtBQUssQ0FBQ0MsSUFBTixDQUFXSixDQUFDLENBQUMsT0FBRCxDQUFELENBQVcsQ0FBWCxFQUFjSyxRQUF6QixFQUNkQyxHQURjLENBQ1YsVUFBQUMsSUFBSTtBQUFBLFdBQUlBLElBQUksQ0FBQ0YsUUFBVDtBQUFBLEdBRE0sRUFFZEMsR0FGYyxDQUVWLFVBQUFDLElBQUk7QUFBQSxXQUFJSixL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S0FBaEQsQ0FBekI7QUFDQSxRQUFNYyxPQUFPLEdBQUdSLEtBQUssQ0FBQ00sYUFBTixDQUFvQmQsVUFBcEIsQ0FBK0JnQixPQUEvQixJQUEwQ2IsU0FBMUMsR0FDWlUsUUFBUSxDQUFDTCxLQUFLLENBQUNNLGFBQU4sQ0FBb0JkLFVBQXBCLENBQStCZ0IsT0FBL0IsQ0FBdUNkLEtBQXhDLENBREksR0FDNkMsQ0FEN0Q7O0FBR0EsU0FBSyxJQUFJTCxHQUFDLEdBQUcsQ0FBYixFQUFnQkEsR0FBQyxHQUFHbUIsT0FBcEIsRUFBNkJuQixHQUFDLEVBQTlCLEVBQWtDO0FBQzlCLFdBQUssSUFBSUUsR0FBVCxJQUFjSixLQUFLLENBQUNpQixRQUFRLEdBQUdmLEdBQVosQ0FBbkIsRUFBbUM7QUFDL0JaLFNBQUMsQ0FBQ1UsS0FBSyxDQUFDaUIsUUFBUSxHQUFHZixHQUFaLENBQUwsQ0FBb0JFLEdBQXBCLENBQUQsQ0FBRCxDQUEwQm1CLFdBQTFCLENBQXNDLGVBQWVULFVBQVUsR0FBRyxNQUFILEdBQVksT0FBckMsQ0FBdEM7QUFDSDtBQUNKO0FBQ0osR0FYRDtBQVlIIiwiZmlsZSI6Ii4vcmVzb3VyY2VzL2pzL3NjcmlwdHMvdGFibGUtaG92ZXJpbmcuanMuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpZiAoJChcIiN0YWJsZS1tdWx0aS1ob3ZlclwiKS5sZW5ndGgpIHtcbiAgICBjb25zdCBoZWFkZXJWYWx1ZXMgPSBBcnJheS5mcm9tKCQoXCJ0aGVhZFwiKVswXS5jaGlsZHJlblswXS5jaGlsZHJlbilcbiAgICAgICAgLm1hcChpdGVtID0+IGl0ZW0uaW5uZXJUZXh0LnRvTG93ZXJDYXNlKCkpO1xuXG4gICAgbGV0IHRhYmxlID0gW107XG5cbiAgICBjb25zdCB0YWJsZUNlbGxzID0gQXJyYXkuZnJvbSgkKFwidGJvZHlcIilbMF0uY2hpbGRyZW4pXG4gICAgICAgIC5tYXAoaXRlbSA9PiBpdGVtLmNoaWxkcmVuKVxuICAgICAgICAubWFwKGl0ZW0gPT4gQXJyYXkuZnJvbShpdGVtKSk7XG5cbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IHRhYmxlQ2VsbHMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgbGV0IHRlbXBPYmogPSB7fTtcbiAgICAgICAgZm9yIChsZXQgaiA9IDA7IGogPCB0YWJsZUNlbGxzW2ldLmxlbmd0aDsgaisrKSB7XG4gICAgICAgICAgICB0ZW1wT2JqW3RhYmxlQ2VsbHNbaV1bal0uYXR0cmlidXRlcy5yZXAudmFsdWVdID0gdGFibGVDZWxsc1tpXVtqXTtcbiAgICAgICAgfVxuXG4gICAgICAgIGZvciAobGV0IGogPSAwOyBqIDwgaGVhZGVyVmFsdWVzLmxlbmd0aDsgaisrKSB7XG4gICAgICAgICAgICBpZiAoaSAhPSAwICYmIHRlbXBPYmpbaGVhZGVyVmFsdWVzW2pdXSA9PSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgICAgICB0ZW1wT2JqW2hlYWRlclZhbHVlc1tqXV0gPSB0YWJsZVtpIC0gMV1baGVhZGVyVmFsdWVzW2pdXTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICB0YWJsZS5wdXNoKE9iamVjdC5hc3NpZ24oe30sIHRlbXBPYmopKTtcbiAgICB9XG5cbiAgICAkKFwidGJvZHkgdGQsIHRib2R5IHRoXCIpLm9uKFwibW91c2VvdmVyXCIsIGV2ZW50ID0+IHtcbiAgICAgICAgY29uc3QgaXNEYXJrbW9kZSA9ICQoJyNzdW4tbW9vbicpLmh0bWwoKS5pbmNsdWRlcygnPGkgY2xhc3M9XCJmYXMgZmEtc3VuXCI+PC9pPicpO1xuICAgICAgICBjb25zdCByb3dJbmRleCA9IHBhcnNlSW50KGV2ZW50LmN1cnJlbnRUYXJnZXQucGFyZW50RWxlbWVudC5hdHRyaWJ1dGVzLmkudmFsdWUpO1xuICAgICAgICBjb25zdCByb3dzcGFuID0gZXZlbnQuY3VycmVudFRhcmdldC5hdHRyaWJ1dGVzLnJvd3NwYW4gIT0gdW5kZWZpbmVkID9cbiAgICAgICAgICAgIHBhcnNlSW50KGV2ZW50LmN1cnJlbnRUYXJnZXQuYXR0cmlidXRlcy5yb3dzcGFuLnZhbHVlKSA6IDE7XG5cbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCByb3dzcGFuOyBpKyspIHtcbiAgICAgICAgICAgIGZvciAobGV0IGogaW4gdGFibGVbcm93SW5kZXggKyBpXSkge1xuICAgICAgICAgICAgICAgICQodGFibGVbcm93SW5kZXggKyBpXVtqXSkuYWRkQ2xhc3MoXCJob3Zlci1iZy1cIiArIChpc0Rhcmttb2RlID8gXCJkYXJrXCIgOiBcImxpZ2h0XCIpKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0pO1xuXG4gICAgJChcInRib2R5IHRkLCB0Ym9keSB0aFwiKS5vbihcIm1vdXNlbGVhdmVcIiwgZXZlbnQgPT4ge1xuICAgICAgICBjb25zdCBpc0Rhcmttb2RlID0gJCgnI3N1bi1tb29uJykuaHRtbCgpLmluY2x1ZGVzKCc8aSBjbGFzcz1cImZhcyBmYS1zdW5cIj48L2k+Jyk7XG4gICAgICAgIGNvbnN0IHJvd0luZGV4ID0gcGFyc2VJbnQoZXZlbnQuY3VycmVudFRhcmdldC5wYXJlbnRFbGVtZW50LmF0dHJpYnV0ZXMuaS52YWx1ZSk7XG4gICAgICAgIGNvbnN0IHJvd3NwYW4gPSBldmVudC5jdXJyZW50VGFyZ2V0LmF0dHJpYnV0ZXMucm93c3BhbiAhPSB1bmRlZmluZWQgP1xuICAgICAgICAgICAgcGFyc2VJbnQoZXZlbnQuY3VycmVudFRhcmdldC5hdHRyaWJ1dGVzLnJvd3NwYW4udmFsdWUpIDogMTtcblxuICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IHJvd3NwYW47IGkrKykge1xuICAgICAgICAgICAgZm9yIChsZXQgaiBpbiB0YWJsZVtyb3dJbmRleCArIGldKSB7XG4gICAgICAgICAgICAgICAgJCh0YWJsZVtyb3dJbmRleCArIGldW2pdKS5yZW1vdmVDbGFzcyhcImhvdmVyLWJnLVwiICsgKGlzRGFya21vZGUgPyBcImRhcmtcIiA6IFwibGlnaHRcIikpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSk7XG59XG4iXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/scripts/table-hovering.js\n");

/***/ }),

/***/ 2:
/*!******************************************************!*\
  !*** multi ./resources/js/scripts/table-hovering.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\rafal\Desktop\Important\Programy\PHP\Laravel\SelfAccounting\resources\js\scripts\table-hovering.js */"./resources/js/scripts/table-hovering.js");


/***/ })

/******/ });