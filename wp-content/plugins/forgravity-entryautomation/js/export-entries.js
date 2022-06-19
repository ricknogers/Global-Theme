/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./js/src/export-entries.js":
/*!**********************************!*\
  !*** ./js/src/export-entries.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _scss_export_entries_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../scss/export-entries.scss */ \"./scss/export-entries.scss\");\n/* harmony import */ var _scss_export_entries_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_export_entries_scss__WEBPACK_IMPORTED_MODULE_0__);\n/**\n * WordPress dependencies\n */\nconst { __ } = wp.i18n;\n\n/**\n * Internal dependencies\n */\n\n\nwindow.addEventListener('load', () => {\n\n\tconst noticeID = 'entryautomation-create-task__notice',\n\t      $submitButton = document.getElementById('submit_button'),\n\t      $exportForm = document.getElementById('gform_export');\n\n\tlet $button;\n\n\t/**\n  * Setup Create Task functionality.\n  *\n  * @since 4.0\n  */\n\tfunction initialize() {\n\n\t\tif (!document.getElementById('export_submit_container')) {\n\t\t\treturn;\n\t\t}\n\n\t\tcreateButton();\n\n\t\t// Add click event to Create Task button.\n\t\t$button.addEventListener('click', createTask);\n\t}\n\n\t// # CREATE THE TASK -----------------------------------------------------------------------------------------\n\n\t/**\n  * Create the task.\n  *\n  * @since 4.0\n  *\n  * @returns {Promise<void>}\n  */\n\tasync function createTask() {\n\n\t\t// Remove existing notice.\n\t\tremoveNotice();\n\n\t\t// Set button to processing.\n\t\tsetButtonState('processing');\n\n\t\t// Get Export Entries settings, append AJAX action.\n\t\tlet formData = new FormData($exportForm);\n\t\tformData.append('action', 'fg_entryautomation_export_entries_task');\n\n\t\t// Get Task settings.\n\t\tawait fetch(ajaxurl, {\n\t\t\tmethod: 'POST',\n\t\t\tbody: formData\n\t\t}).then(response => response.json()).then(response => {\n\n\t\t\t// Display notice with result.\n\t\t\tdisplayNotice(response.data, response.success ? 'success' : 'error');\n\n\t\t\t// Set button to initial state.\n\t\t\tsetButtonState('initial');\n\t\t});\n\t}\n\n\t// # BUTTON --------------------------------------------------------------------------------------------------\n\n\t/**\n  * Create the Create Task button.\n  *\n  * @since 4.0\n  */\n\tfunction createButton() {\n\n\t\tif (document.getElementById('entryautomation-create-task')) {\n\t\t\treturn;\n\t\t}\n\n\t\t$button = document.createElement('button');\n\t\t$button.id = 'entryautomation-create-task';\n\t\t$button.type = 'button';\n\t\t$button.innerText = __('Create Entry Automation Task', 'forgravity_entryautomation');\n\t\t$button.classList.add('button', 'large');\n\n\t\t$submitButton.parentNode.insertBefore($button, $submitButton.nextSibling);\n\t}\n\n\t/**\n  * Change the Create Task button state.\n  *\n  * @since 4.0\n  *\n  * @param {string} state Button state.\n  */\n\tfunction setButtonState(state = 'initial') {\n\n\t\tswitch (state) {\n\n\t\t\tcase 'initial':\n\t\t\t\t$button.disabled = false;\n\t\t\t\tbreak;\n\n\t\t\tcase 'processing':\n\t\t\t\t$button.disabled = true;\n\t\t\t\tbreak;\n\n\t\t}\n\t}\n\n\t// # NOTICE --------------------------------------------------------------------------------------------------\n\n\t/**\n  * Display result of request.\n  *\n  * @since 4.0\n  *\n  * @param {string} message Result message.\n  * @param {string} type    Result type.\n  */\n\tfunction displayNotice(message, type = 'success') {\n\n\t\t// Prepare notice markup.\n\t\tlet $notice = document.createElement('div');\n\t\t$notice.id = noticeID;\n\t\t$notice.innerHTML = `<p>${message}</p>`;\n\t\t$notice.classList.add('notice', `notice-${type}`, 'below-h1', 'is-dismissible', 'gf-notice');\n\n\t\t// Add notice to DOM.\n\t\tdocument.getElementById('gf-admin-notices-wrapper').append($notice);\n\n\t\t// Add close button to notice.\n\t\tdocument.dispatchEvent(new Event('wp-updates-notice-added'));\n\t}\n\n\t/**\n  * Remove existing result notice.\n  *\n  * @since 4.0\n  */\n\tfunction removeNotice() {\n\n\t\tlet $notice = document.getElementById(noticeID);\n\n\t\tif ($notice) {\n\t\t\t$notice.remove();\n\t\t}\n\t}\n\n\tinitialize();\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9qcy9zcmMvZXhwb3J0LWVudHJpZXMuanMuanMiLCJtYXBwaW5ncyI6Ijs7O0FBQUE7OztBQUdBOztBQUVBOzs7QUFHQTs7QUFFQTs7QUFFQTtBQUFBO0FBQUE7O0FBSUE7O0FBRUE7Ozs7O0FBS0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFFQTs7QUFNQTs7QUFFQTs7Ozs7OztBQU9BOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUdBO0FBQ0E7QUFGQTs7QUFTQTtBQUNBOztBQUtBO0FBQ0E7QUFFQTtBQUdBOztBQU1BOztBQUVBOzs7OztBQUtBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBS0E7O0FBRUE7Ozs7Ozs7QUFPQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQVJBO0FBWUE7O0FBTUE7O0FBRUE7Ozs7Ozs7O0FBUUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFFQTs7QUFFQTs7Ozs7QUFLQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFFQTs7QUFNQTtBQUVBIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vZm9yZ3Jhdml0eS1lbnRyeWF1dG9tYXRpb24vLi9qcy9zcmMvZXhwb3J0LWVudHJpZXMuanM/YjdmYSJdLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIFdvcmRQcmVzcyBkZXBlbmRlbmNpZXNcbiAqL1xuY29uc3QgeyBfXyB9ID0gd3AuaTE4bjtcblxuLyoqXG4gKiBJbnRlcm5hbCBkZXBlbmRlbmNpZXNcbiAqL1xuaW1wb3J0ICcuLy4uLy4uL3Njc3MvZXhwb3J0LWVudHJpZXMuc2Nzcyc7XG5cbndpbmRvdy5hZGRFdmVudExpc3RlbmVyKCAnbG9hZCcsICgpID0+IHtcblxuXHRjb25zdCBub3RpY2VJRCAgICAgID0gJ2VudHJ5YXV0b21hdGlvbi1jcmVhdGUtdGFza19fbm90aWNlJyxcblx0ICAgICAgJHN1Ym1pdEJ1dHRvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCAnc3VibWl0X2J1dHRvbicgKSxcblx0ICAgICAgJGV4cG9ydEZvcm0gICA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCAnZ2Zvcm1fZXhwb3J0JyApO1xuXG5cdGxldCAkYnV0dG9uO1xuXG5cdC8qKlxuXHQgKiBTZXR1cCBDcmVhdGUgVGFzayBmdW5jdGlvbmFsaXR5LlxuXHQgKlxuXHQgKiBAc2luY2UgNC4wXG5cdCAqL1xuXHRmdW5jdGlvbiBpbml0aWFsaXplKCkge1xuXG5cdFx0aWYgKCAhIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCAnZXhwb3J0X3N1Ym1pdF9jb250YWluZXInICkgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0Y3JlYXRlQnV0dG9uKCk7XG5cblx0XHQvLyBBZGQgY2xpY2sgZXZlbnQgdG8gQ3JlYXRlIFRhc2sgYnV0dG9uLlxuXHRcdCRidXR0b24uYWRkRXZlbnRMaXN0ZW5lciggJ2NsaWNrJywgY3JlYXRlVGFzayApO1xuXG5cdH1cblxuXG5cblxuXG5cdC8vICMgQ1JFQVRFIFRIRSBUQVNLIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXG5cblx0LyoqXG5cdCAqIENyZWF0ZSB0aGUgdGFzay5cblx0ICpcblx0ICogQHNpbmNlIDQuMFxuXHQgKlxuXHQgKiBAcmV0dXJucyB7UHJvbWlzZTx2b2lkPn1cblx0ICovXG5cdGFzeW5jIGZ1bmN0aW9uIGNyZWF0ZVRhc2soKSB7XG5cblx0XHQvLyBSZW1vdmUgZXhpc3Rpbmcgbm90aWNlLlxuXHRcdHJlbW92ZU5vdGljZSgpO1xuXG5cdFx0Ly8gU2V0IGJ1dHRvbiB0byBwcm9jZXNzaW5nLlxuXHRcdHNldEJ1dHRvblN0YXRlKCAncHJvY2Vzc2luZycgKTtcblxuXHRcdC8vIEdldCBFeHBvcnQgRW50cmllcyBzZXR0aW5ncywgYXBwZW5kIEFKQVggYWN0aW9uLlxuXHRcdGxldCBmb3JtRGF0YSA9IG5ldyBGb3JtRGF0YSggJGV4cG9ydEZvcm0gKTtcblx0XHRmb3JtRGF0YS5hcHBlbmQoICdhY3Rpb24nLCAnZmdfZW50cnlhdXRvbWF0aW9uX2V4cG9ydF9lbnRyaWVzX3Rhc2snICk7XG5cblx0XHQvLyBHZXQgVGFzayBzZXR0aW5ncy5cblx0XHRhd2FpdCBmZXRjaChcblx0XHRcdGFqYXh1cmwsXG5cdFx0XHR7XG5cdFx0XHRcdG1ldGhvZDogJ1BPU1QnLFxuXHRcdFx0XHRib2R5OiAgIGZvcm1EYXRhXG5cdFx0XHR9XG5cdFx0KS50aGVuKFxuXHRcdFx0KCByZXNwb25zZSApID0+IHJlc3BvbnNlLmpzb24oKVxuXHRcdCkudGhlbihcblx0XHRcdCggcmVzcG9uc2UgKSA9PiB7XG5cblx0XHRcdFx0Ly8gRGlzcGxheSBub3RpY2Ugd2l0aCByZXN1bHQuXG5cdFx0XHRcdGRpc3BsYXlOb3RpY2UoXG5cdFx0XHRcdFx0cmVzcG9uc2UuZGF0YSxcblx0XHRcdFx0XHRyZXNwb25zZS5zdWNjZXNzID8gJ3N1Y2Nlc3MnIDogJ2Vycm9yJ1xuXHRcdFx0XHQpXG5cblx0XHRcdFx0Ly8gU2V0IGJ1dHRvbiB0byBpbml0aWFsIHN0YXRlLlxuXHRcdFx0XHRzZXRCdXR0b25TdGF0ZSggJ2luaXRpYWwnICk7XG5cblx0XHRcdH1cblx0XHQpO1xuXG5cdH1cblxuXG5cblxuXG5cdC8vICMgQlVUVE9OIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXG5cblx0LyoqXG5cdCAqIENyZWF0ZSB0aGUgQ3JlYXRlIFRhc2sgYnV0dG9uLlxuXHQgKlxuXHQgKiBAc2luY2UgNC4wXG5cdCAqL1xuXHRmdW5jdGlvbiBjcmVhdGVCdXR0b24oKSB7XG5cblx0XHRpZiAoIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCAnZW50cnlhdXRvbWF0aW9uLWNyZWF0ZS10YXNrJyApICkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblxuXHRcdCRidXR0b24gICAgICAgICAgID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCggJ2J1dHRvbicgKTtcblx0XHQkYnV0dG9uLmlkICAgICAgICA9ICdlbnRyeWF1dG9tYXRpb24tY3JlYXRlLXRhc2snO1xuXHRcdCRidXR0b24udHlwZSAgICAgID0gJ2J1dHRvbic7XG5cdFx0JGJ1dHRvbi5pbm5lclRleHQgPSBfXyggJ0NyZWF0ZSBFbnRyeSBBdXRvbWF0aW9uIFRhc2snLCAnZm9yZ3Jhdml0eV9lbnRyeWF1dG9tYXRpb24nICk7XG5cdFx0JGJ1dHRvbi5jbGFzc0xpc3QuYWRkKCAnYnV0dG9uJywgJ2xhcmdlJyApO1xuXG5cdFx0JHN1Ym1pdEJ1dHRvbi5wYXJlbnROb2RlLmluc2VydEJlZm9yZShcblx0XHRcdCRidXR0b24sXG5cdFx0XHQkc3VibWl0QnV0dG9uLm5leHRTaWJsaW5nXG5cdFx0KTtcblxuXHR9XG5cblx0LyoqXG5cdCAqIENoYW5nZSB0aGUgQ3JlYXRlIFRhc2sgYnV0dG9uIHN0YXRlLlxuXHQgKlxuXHQgKiBAc2luY2UgNC4wXG5cdCAqXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSBzdGF0ZSBCdXR0b24gc3RhdGUuXG5cdCAqL1xuXHRmdW5jdGlvbiBzZXRCdXR0b25TdGF0ZSggc3RhdGUgPSAnaW5pdGlhbCcgKSB7XG5cblx0XHRzd2l0Y2ggKCBzdGF0ZSApIHtcblxuXHRcdFx0Y2FzZSAnaW5pdGlhbCc6XG5cdFx0XHRcdCRidXR0b24uZGlzYWJsZWQgPSBmYWxzZTtcblx0XHRcdFx0YnJlYWs7XG5cblx0XHRcdGNhc2UgJ3Byb2Nlc3NpbmcnOlxuXHRcdFx0XHQkYnV0dG9uLmRpc2FibGVkID0gdHJ1ZTtcblx0XHRcdFx0YnJlYWs7XG5cblx0XHR9XG5cblx0fVxuXG5cblxuXG5cblx0Ly8gIyBOT1RJQ0UgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cblxuXHQvKipcblx0ICogRGlzcGxheSByZXN1bHQgb2YgcmVxdWVzdC5cblx0ICpcblx0ICogQHNpbmNlIDQuMFxuXHQgKlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gbWVzc2FnZSBSZXN1bHQgbWVzc2FnZS5cblx0ICogQHBhcmFtIHtzdHJpbmd9IHR5cGUgICAgUmVzdWx0IHR5cGUuXG5cdCAqL1xuXHRmdW5jdGlvbiBkaXNwbGF5Tm90aWNlKCBtZXNzYWdlLCB0eXBlID0gJ3N1Y2Nlc3MnICkge1xuXG5cdFx0Ly8gUHJlcGFyZSBub3RpY2UgbWFya3VwLlxuXHRcdGxldCAkbm90aWNlICAgICAgID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCggJ2RpdicgKTtcblx0XHQkbm90aWNlLmlkICAgICAgICA9IG5vdGljZUlEO1xuXHRcdCRub3RpY2UuaW5uZXJIVE1MID0gYDxwPiR7IG1lc3NhZ2UgfTwvcD5gO1xuXHRcdCRub3RpY2UuY2xhc3NMaXN0LmFkZCggJ25vdGljZScsIGBub3RpY2UtJHsgdHlwZSB9YCwgJ2JlbG93LWgxJywgJ2lzLWRpc21pc3NpYmxlJywgJ2dmLW5vdGljZScgKTtcblxuXHRcdC8vIEFkZCBub3RpY2UgdG8gRE9NLlxuXHRcdGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCAnZ2YtYWRtaW4tbm90aWNlcy13cmFwcGVyJyApLmFwcGVuZCggJG5vdGljZSApO1xuXG5cdFx0Ly8gQWRkIGNsb3NlIGJ1dHRvbiB0byBub3RpY2UuXG5cdFx0ZG9jdW1lbnQuZGlzcGF0Y2hFdmVudCggbmV3IEV2ZW50KCAnd3AtdXBkYXRlcy1ub3RpY2UtYWRkZWQnICkgKTtcblxuXHR9XG5cblx0LyoqXG5cdCAqIFJlbW92ZSBleGlzdGluZyByZXN1bHQgbm90aWNlLlxuXHQgKlxuXHQgKiBAc2luY2UgNC4wXG5cdCAqL1xuXHRmdW5jdGlvbiByZW1vdmVOb3RpY2UoKSB7XG5cblx0XHRsZXQgJG5vdGljZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCBub3RpY2VJRCApO1xuXG5cdFx0aWYgKCAkbm90aWNlICkge1xuXHRcdFx0JG5vdGljZS5yZW1vdmUoKTtcblx0XHR9XG5cblx0fVxuXG5cblxuXG5cblx0aW5pdGlhbGl6ZSgpO1xuXG59ICk7XG4iXSwibmFtZXMiOltdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./js/src/export-entries.js\n");

/***/ }),

/***/ "./scss/export-entries.scss":
/*!**********************************!*\
  !*** ./scss/export-entries.scss ***!
  \**********************************/
/***/ (() => {

eval("// extracted by mini-css-extract-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9zY3NzL2V4cG9ydC1lbnRyaWVzLnNjc3MuanMiLCJtYXBwaW5ncyI6IkFBQUEiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9mb3JncmF2aXR5LWVudHJ5YXV0b21hdGlvbi8uL3Njc3MvZXhwb3J0LWVudHJpZXMuc2Nzcz8zNWUzIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpbiJdLCJuYW1lcyI6W10sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./scss/export-entries.scss\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./js/src/export-entries.js");
/******/ 	
/******/ })()
;