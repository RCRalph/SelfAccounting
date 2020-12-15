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

/***/ "./resources/js/table-hovering.js":
/*!****************************************!*\
  !*** ./resources/js/table-hovering.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("if ($(\"#table-multi-hover\").length) {\n  var headerValues = Array.from($(\"thead\")[0].children[0].children).map(function (item) {\n    return item.innerText.toLowerCase();\n  });\n  var table = [];\n  var tableCells = Array.from($(\"tbody\")[0].children).map(function (item) {\n    return item.children;\n  }).map(function (item) {\n    return Array.from(item);\n  });\n\n  for (var i = 0; i < tableCells.length; i++) {\n    var tempObj = {};\n\n    for (var j = 0; j < tableCells[i].length; j++) {\n      tempObj[tableCells[i][j].attributes.rep.value] = tableCells[i][j];\n    }\n\n    for (var _j = 0; _j < headerValues.length; _j++) {\n      if (i != 0 && tempObj[headerValues[_j]] == undefined) {\n        tempObj[headerValues[_j]] = table[i - 1][headerValues[_j]];\n      }\n    }\n\n    table.push(Object.assign({}, tempObj));\n  }\n\n  $(\"tbody td, tbody th\").on(\"mouseover\", function (event) {\n    var isDarkmode = $('#sun-moon').html().includes('<i class=\"fas fa-sun\"></i>');\n    var rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);\n    var rowspan = event.currentTarget.attributes.rowspan != undefined ? parseInt(event.currentTarget.attributes.rowspan.value) : 1;\n\n    for (var _i = 0; _i < rowspan; _i++) {\n      for (var _j2 in table[rowIndex + _i]) {\n        $(table[rowIndex + _i][_j2]).addClass(\"hover-bg-\" + (isDarkmode ? \"dark\" : \"light\"));\n      }\n    }\n  });\n  $(\"tbody td, tbody th\").on(\"mouseleave\", function (event) {\n    var isDarkmode = $('#sun-moon').html().includes('<i class=\"fas fa-sun\"></i>');\n    var rowIndex = parseInt(event.currentTarget.parentElement.attributes.i.value);\n    var rowspan = event.currentTarget.attributes.rowspan != undefined ? parseInt(event.currentTarget.attributes.rowspan.value) : 1;\n\n    for (var _i2 = 0; _i2 < rowspan; _i2++) {\n      for (var _j3 in table[rowIndex + _i2]) {\n        $(table[rowIndex + _i2][_j3]).removeClass(\"hover-bg-\" + (isDarkmode ? \"dark\" : \"light\"));\n      }\n    }\n  });\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvdGFibGUtaG92ZXJpbmcuanM/OTYwMCJdLCJuYW1lcyI6WyIkIiwibGVuZ3RoIiwiaGVhZGVyVmFsdWVzIiwiQXJyYXkiLCJmcm9tIiwiY2hpbGRyZW4iLCJtYXAiLCJpdGVtIiwiaW5uZXJUZXh0IiwidG9Mb3dlckNhc2UiLCJ0YWJsZSIsInRhYmxlQ2VsbHMiLCJpIiwidGVtcE9iaiIsImoiLCJhdHRyaWJ1dGVzIiwicmVwIiwidmFsdWUiLCJ1bmRlZmluZWQiLCJwdXNoIiwiT2JqZWN0IiwiYXNzaWduIiwib24iLCJldmVudCIsImlzRGFya21vZGUiLCJodG1sIiwiaW5jbHVkZXMiLCJyb3dJbmRleCIsInBhcnNlSW50IiwiY3VycmVudFRhcmdldCIsInBhcmVudEVsZW1lbnQiLCJyb3dzcGFuIiwiYWRkQ2xhc3MiLCJyZW1vdmVDbGFzcyJdLCJtYXBwaW5ncyI6IkFBQUEsSUFBSUEsQ0FBQyxDQUFDLG9CQUFELENBQUQsQ0FBd0JDLE1BQTVCLEVBQW9DO0FBQ2hDLE1BQU1DLFlBQVksR0FBR0MsS0FBSyxDQUFDQyxJQUFOLENBQVdKLENBQUMsQ0FBQyxPQUFELENBQUQsQ0FBVyxDQUFYLEVBQWNLLFFBQWQsQ0FBdUIsQ0FBdkIsRUFBMEJBLFFBQXJDLEVBQ2hCQyxHQURnQixDQUNaLFVBQUFDLElBQUk7QUFBQSxXQUFJQSxJQUFJLENBQUNDLFNBQUwsQ0FBZUMsV0FBZixFQUFKO0FBQUEsR0FEUSxDQUFyQjtBQUdBLE1BQUlDLEtBQUssR0FBRyxFQUFaO0FBRUEsTUFBTUMsVUFBVSxHQUFHUixLQUFLLENBQUNDLElBQU4sQ0FBV0osQ0FBQyxDQUFDLE9BQUQsQ0FBRCxDQUFXLENBQVgsRUFBY0ssUUFBekIsRUFDZEMsR0FEYyxDQUNWLFVBQUFDLElBQUk7QUFBQSxXQUFJQSxJQUFJLENBQUNGLFFBQVQ7QUFBQSxHQURNLEVBRWRDLEdBRmMsQ0FFVixVQUFBQyxJQUFJO0FBQUEsV0FBSUosS0FBSyxDQUFDQyxJQUFOLENBQVdHLElBQVgsQ0FBSjtBQUFBLEdBRk0sQ0FBbkI7O0FBSUEsT0FBSyxJQUFJSyxDQUFDLEdBQUcsQ0FBYixFQUFnQkEsQ0FBQyxHQUFHRCxVQUFVLENBQUNWLE1BQS9CLEVBQXVDVyxDQUFDLEVBQXhDLEVBQTRDO0FBQ3hDLFFBQUlDLE9BQU8sR0FBRyxFQUFkOztBQUNBLFNBQUssSUFBSUMsQ0FBQyxHQUFHLENBQWIsRUFBZ0JBLENBQUMsR0FBR0gsVUFBVSxDQUFDQyxDQUFELENBQVYsQ0FBY1gsTUFBbEMsRUFBMENhLENBQUMsRUFBM0MsRUFBK0M7QUFDM0NELGFBQU8sQ0FBQ0YsVUFBVSxDQUFDQyxDQUFELENBQVYsQ0FBY0UsQ0FBZCxFQUFpQkMsVUFBakIsQ0FBNEJDLEdBQTVCLENBQWdDQyxLQUFqQyxDQUFQLEdBQWlETixVQUFVLENBQUNDLENBQUQsQ0FBVixDQUFjRSxDQUFkLENBQWpEO0FBQ0g7O0FBRUQsU0FBSyxJQUFJQSxFQUFDLEdBQUcsQ0FBYixFQUFnQkEsRUFBQyxHQUFHWixZQUFZLENBQUNELE1BQWpDLEVBQXlDYSxFQUFDLEVBQTFDLEVBQThDO0FBQzFDLFVBQUlGLENBQUMsSUFBSSxDQUFMLElBQVVDLE9BQU8sQ0FBQ1gsWUFBWSxDQUFDWSxFQUFELENBQWIsQ0FBUCxJQUE0QkksU0FBMUMsRUFBcUQ7QUFDakRMLGVBQU8sQ0FBQ1gsWUFBWSxDQUFDWSxFQUFELENBQWIsQ0FBUCxHQUEyQkosS0FBSyxDQUFDRSxDQUFDLEdBQUcsQ0FBTCxDQUFMLENBQWFWLFlBQVksQ0FBQ1ksRUFBRCxDQUF6QixDQUEzQjtBQUNIO0FBQ0o7O0FBQ0RKLFNBQUssQ0FBQ1MsSUFBTixDQUFXQyxNQUFNLENBQUNDLE1BQVAsQ0FBYyxFQUFkLEVBQWtCUixPQUFsQixDQUFYO0FBQ0g7O0FBRURiLEdBQUMsQ0FBQyxvQkFBRCxDQUFELENBQXdCc0IsRUFBeEIsQ0FBMkIsV0FBM0IsRUFBd0MsVUFBQUMsS0FBSyxFQUFJO0FBQzdDLFFBQU1DLFVBQVUsR0FBR3hCLENBQUMsQ0FBQyxXQUFELENBQUQsQ0FBZXlCLElBQWYsR0FBc0JDLFFBQXRCLENBQStCLDRCQUEvQixDQUFuQjtBQUNBLFFBQU1DLFFBQVEsR0FBR0MsUUFBUSxDQUFDTCxLQUFLLENBQUNNLGFBQU4sQ0FBb0JDLGFBQXBCLENBQWtDZixVQUFsQyxDQUE2Q0gsQ0FBN0MsQ0FBK0NLLEtBQWhELENBQXpCO0FBQ0EsUUFBTWMsT0FBTyxHQUFHUixLQUFLLENBQUNNLGFBQU4sQ0FBb0JkLFVBQXBCLENBQStCZ0IsT0FBL0IsSUFBMENiLFNBQTFDLEdBQ1pVLFFBQVEsQ0FBQ0wsS0FBSyxDQUFDTSxhQUFOLENBQW9CZCxVQUFwQixDQUErQmdCLE9BQS9CLENBQXVDZCxLQUF4QyxDQURJLEdBQzZDLENBRDdEOztBQUdBLFNBQUssSUFBSUwsRUFBQyxHQUFHLENBQWIsRUFBZ0JBLEVBQUMsR0FBR21CLE9BQXBCLEVBQTZCbkIsRUFBQyxFQUE5QixFQUFrQztBQUM5QixXQUFLLElBQUlFLEdBQVQsSUFBY0osS0FBSyxDQUFDaUIsUUFBUSxHQUFHZixFQUFaLENBQW5CLEVBQW1DO0FBQy9CWixTQUFDLENBQUNVLEtBQUssQ0FBQ2lCLFFBQVEsR0FBR2YsRUFBWixDQUFMLENBQW9CRSxHQUFwQixDQUFELENBQUQsQ0FBMEJrQixRQUExQixDQUFtQyxlQUFlUixVQUFVLEdBQUcsTUFBSCxHQUFZLE9BQXJDLENBQW5DO0FBQ0g7QUFDSjtBQUNKLEdBWEQ7QUFhQXhCLEdBQUMsQ0FBQyxvQkFBRCxDQUFELENBQXdCc0IsRUFBeEIsQ0FBMkIsWUFBM0IsRUFBeUMsVUFBQUMsS0FBSyxFQUFJO0FBQzlDLFFBQU1DLFVBQVUsR0FBR3hCLENBQUMsQ0FBQyxXQUFELENBQUQsQ0FBZXlCLElBQWYsR0FBc0JDLFFBQXRCLENBQStCLDRCQUEvQixDQUFuQjtBQUNBLFFBQU1DLFFBQVEsR0FBR0MsUUFBUSxDQUFDTCxLQUFLLENBQUNNLGFBQU4sQ0FBb0JDLGFBQXBCLENBQWtDZixVQUFsQyxDQUE2Q0gsQ0FBN0MsQ0FBK0NLLEtBQWhELENBQXpCO0FBQ0EsUUFBTWMsT0FBTyxHQUFHUixLQUFLLENBQUNNLGFBQU4sQ0FBb0JkLFVBQXBCLENBQStCZ0IsT0FBL0IsSUFBMENiLFNBQTFDLEdBQ1pVLFFBQVEsQ0FBQ0wsS0FBSyxDQUFDTSxhQUFOLENBQW9CZCxVQUFwQixDQUErQmdCLE9BQS9CLENBQXVDZCxLQUF4QyxDQURJLEdBQzZDLENBRDdEOztBQUdBLFNBQUssSUFBSUwsR0FBQyxHQUFHLENBQWIsRUFBZ0JBLEdBQUMsR0FBR21CLE9BQXBCLEVBQTZCbkIsR0FBQyxFQUE5QixFQUFrQztBQUM5QixXQUFLLElBQUlFLEdBQVQsSUFBY0osS0FBSyxDQUFDaUIsUUFBUSxHQUFHZixHQUFaLENBQW5CLEVBQW1DO0FBQy9CWixTQUFDLENBQUNVLEtBQUssQ0FBQ2lCLFFBQVEsR0FBR2YsR0FBWixDQUFMLENBQW9CRSxHQUFwQixDQUFELENBQUQsQ0FBMEJtQixXQUExQixDQUFzQyxlQUFlVCxVQUFVLEdBQUcsTUFBSCxHQUFZLE9BQXJDLENBQXRDO0FBQ0g7QUFDSjtBQUNKLEdBWEQ7QUFZSCIsImZpbGUiOiIuL3Jlc291cmNlcy9qcy90YWJsZS1ob3ZlcmluZy5qcy5qcyIsInNvdXJjZXNDb250ZW50IjpbImlmICgkKFwiI3RhYmxlLW11bHRpLWhvdmVyXCIpLmxlbmd0aCkge1xuICAgIGNvbnN0IGhlYWRlclZhbHVlcyA9IEFycmF5LmZyb20oJChcInRoZWFkXCIpWzBdLmNoaWxkcmVuWzBdLmNoaWxkcmVuKVxuICAgICAgICAubWFwKGl0ZW0gPT4gaXRlbS5pbm5lclRleHQudG9Mb3dlckNhc2UoKSk7XG5cbiAgICBsZXQgdGFibGUgPSBbXTtcblxuICAgIGNvbnN0IHRhYmxlQ2VsbHMgPSBBcnJheS5mcm9tKCQoXCJ0Ym9keVwiKVswXS5jaGlsZHJlbilcbiAgICAgICAgLm1hcChpdGVtID0+IGl0ZW0uY2hpbGRyZW4pXG4gICAgICAgIC5tYXAoaXRlbSA9PiBBcnJheS5mcm9tKGl0ZW0pKTtcblxuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgdGFibGVDZWxscy5sZW5ndGg7IGkrKykge1xuICAgICAgICBsZXQgdGVtcE9iaiA9IHt9O1xuICAgICAgICBmb3IgKGxldCBqID0gMDsgaiA8IHRhYmxlQ2VsbHNbaV0ubGVuZ3RoOyBqKyspIHtcbiAgICAgICAgICAgIHRlbXBPYmpbdGFibGVDZWxsc1tpXVtqXS5hdHRyaWJ1dGVzLnJlcC52YWx1ZV0gPSB0YWJsZUNlbGxzW2ldW2pdO1xuICAgICAgICB9XG5cbiAgICAgICAgZm9yIChsZXQgaiA9IDA7IGogPCBoZWFkZXJWYWx1ZXMubGVuZ3RoOyBqKyspIHtcbiAgICAgICAgICAgIGlmIChpICE9IDAgJiYgdGVtcE9ialtoZWFkZXJWYWx1ZXNbal1dID09IHVuZGVmaW5lZCkge1xuICAgICAgICAgICAgICAgIHRlbXBPYmpbaGVhZGVyVmFsdWVzW2pdXSA9IHRhYmxlW2kgLSAxXVtoZWFkZXJWYWx1ZXNbal1dO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHRhYmxlLnB1c2goT2JqZWN0LmFzc2lnbih7fSwgdGVtcE9iaikpO1xuICAgIH1cblxuICAgICQoXCJ0Ym9keSB0ZCwgdGJvZHkgdGhcIikub24oXCJtb3VzZW92ZXJcIiwgZXZlbnQgPT4ge1xuICAgICAgICBjb25zdCBpc0Rhcmttb2RlID0gJCgnI3N1bi1tb29uJykuaHRtbCgpLmluY2x1ZGVzKCc8aSBjbGFzcz1cImZhcyBmYS1zdW5cIj48L2k+Jyk7XG4gICAgICAgIGNvbnN0IHJvd0luZGV4ID0gcGFyc2VJbnQoZXZlbnQuY3VycmVudFRhcmdldC5wYXJlbnRFbGVtZW50LmF0dHJpYnV0ZXMuaS52YWx1ZSk7XG4gICAgICAgIGNvbnN0IHJvd3NwYW4gPSBldmVudC5jdXJyZW50VGFyZ2V0LmF0dHJpYnV0ZXMucm93c3BhbiAhPSB1bmRlZmluZWQgP1xuICAgICAgICAgICAgcGFyc2VJbnQoZXZlbnQuY3VycmVudFRhcmdldC5hdHRyaWJ1dGVzLnJvd3NwYW4udmFsdWUpIDogMTtcblxuICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IHJvd3NwYW47IGkrKykge1xuICAgICAgICAgICAgZm9yIChsZXQgaiBpbiB0YWJsZVtyb3dJbmRleCArIGldKSB7XG4gICAgICAgICAgICAgICAgJCh0YWJsZVtyb3dJbmRleCArIGldW2pdKS5hZGRDbGFzcyhcImhvdmVyLWJnLVwiICsgKGlzRGFya21vZGUgPyBcImRhcmtcIiA6IFwibGlnaHRcIikpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSk7XG5cbiAgICAkKFwidGJvZHkgdGQsIHRib2R5IHRoXCIpLm9uKFwibW91c2VsZWF2ZVwiLCBldmVudCA9PiB7XG4gICAgICAgIGNvbnN0IGlzRGFya21vZGUgPSAkKCcjc3VuLW1vb24nKS5odG1sKCkuaW5jbHVkZXMoJzxpIGNsYXNzPVwiZmFzIGZhLXN1blwiPjwvaT4nKTtcbiAgICAgICAgY29uc3Qgcm93SW5kZXggPSBwYXJzZUludChldmVudC5jdXJyZW50VGFyZ2V0LnBhcmVudEVsZW1lbnQuYXR0cmlidXRlcy5pLnZhbHVlKTtcbiAgICAgICAgY29uc3Qgcm93c3BhbiA9IGV2ZW50LmN1cnJlbnRUYXJnZXQuYXR0cmlidXRlcy5yb3dzcGFuICE9IHVuZGVmaW5lZCA/XG4gICAgICAgICAgICBwYXJzZUludChldmVudC5jdXJyZW50VGFyZ2V0LmF0dHJpYnV0ZXMucm93c3Bhbi52YWx1ZSkgOiAxO1xuXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgcm93c3BhbjsgaSsrKSB7XG4gICAgICAgICAgICBmb3IgKGxldCBqIGluIHRhYmxlW3Jvd0luZGV4ICsgaV0pIHtcbiAgICAgICAgICAgICAgICAkKHRhYmxlW3Jvd0luZGV4ICsgaV1bal0pLnJlbW92ZUNsYXNzKFwiaG92ZXItYmctXCIgKyAoaXNEYXJrbW9kZSA/IFwiZGFya1wiIDogXCJsaWdodFwiKSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9KTtcbn1cbiJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/table-hovering.js\n");

/***/ }),

/***/ 2:
/*!**********************************************!*\
  !*** multi ./resources/js/table-hovering.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\rafal\Desktop\Important\Programy\PHP\Laravel\SelfAccounting\resources\js\table-hovering.js */"./resources/js/table-hovering.js");


/***/ })

/******/ });