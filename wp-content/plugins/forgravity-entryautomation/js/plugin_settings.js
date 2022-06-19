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

/***/ "./js/src/plugin_settings.js":
/*!***********************************!*\
  !*** ./js/src/plugin_settings.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

eval("__webpack_require__(/*! ./../../scss/plugin_settings.scss */ \"./scss/plugin_settings.scss\");\n\nvar FGEntryAutomationSettings = function () {\n\n\tvar self = this,\n\t    $ = jQuery;\n\n\tself.init = function () {\n\n\t\t// Add localized strings.\n\t\tself.strings = forgravity_entryautomation_plugin_settings_strings;\n\n\t\t// Initialize Extension Actions.\n\t\tself.runExtensionAction();\n\t};\n\n\t// # EXTENSIONS ----------------------------------------------------------------------------------------------------\n\n\t/**\n  * Handle Run Task Now button.\n  *\n  * @since 1.3\n  */\n\tself.runExtensionAction = function () {\n\n\t\t$(document).on('click', '#gaddon-setting-row-extensions a[data-action], #gform_setting_extensions a[data-action]', function (e) {\n\n\t\t\tvar $button = $(this),\n\t\t\t    action = $button.data('action'),\n\t\t\t    plugin = $button.data('plugin');\n\n\t\t\t// If this is the upgrade action, return.\n\t\t\tif ('upgrade' === action) {\n\t\t\t\treturn true;\n\t\t\t}\n\n\t\t\te.preventDefault();\n\n\t\t\t// Disable button.\n\t\t\t$button.attr('disabled', 'disabled');\n\n\t\t\t// Change button text.\n\t\t\t$button.html(self.strings.processing[action]);\n\n\t\t\t// Prepare request data.\n\t\t\tvar data = {\n\t\t\t\taction: 'fg_entryautomation_extension_action',\n\t\t\t\textension: {\n\t\t\t\t\taction: action,\n\t\t\t\t\tplugin: plugin\n\t\t\t\t},\n\t\t\t\tnonce: self.strings.nonce\n\t\t\t};\n\n\t\t\t// Run task.\n\t\t\t$.ajax({\n\t\t\t\turl: ajaxurl,\n\t\t\t\ttype: 'POST',\n\t\t\t\tdataType: 'json',\n\t\t\t\tdata: data,\n\t\t\t\tsuccess: function (response) {\n\n\t\t\t\t\t// If could not process action, display error message.\n\t\t\t\t\tif (!response.success) {\n\t\t\t\t\t\talert(response.data.error);\n\t\t\t\t\t}\n\n\t\t\t\t\t// Update button.\n\t\t\t\t\t$button.data('action', response.data.newAction);\n\t\t\t\t\t$button.html(response.data.newText);\n\n\t\t\t\t\t// Enable button.\n\t\t\t\t\t$button.removeAttr('disabled');\n\t\t\t\t}\n\n\t\t\t});\n\t\t});\n\t};\n\n\tself.init();\n};\n\njQuery(document).ready(function () {\n\tnew FGEntryAutomationSettings();\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9qcy9zcmMvcGx1Z2luX3NldHRpbmdzLmpzLmpzIiwibWFwcGluZ3MiOiJBQUFBOztBQUVBOztBQUVBO0FBQUE7O0FBR0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBRUE7O0FBTUE7O0FBRUE7Ozs7O0FBS0E7O0FBRUE7O0FBRUE7QUFBQTtBQUFBOztBQUlBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFGQTtBQUlBO0FBTkE7O0FBU0E7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFFQTs7QUFuQkE7QUF5QkE7QUFFQTs7QUFFQTtBQUVBOztBQUVBO0FBQUE7QUFBQSIsInNvdXJjZXMiOlsid2VicGFjazovL2ZvcmdyYXZpdHktZW50cnlhdXRvbWF0aW9uLy4vanMvc3JjL3BsdWdpbl9zZXR0aW5ncy5qcz9hODVmIl0sInNvdXJjZXNDb250ZW50IjpbInJlcXVpcmUoICcuLy4uLy4uL3Njc3MvcGx1Z2luX3NldHRpbmdzLnNjc3MnICk7XG5cbnZhciBGR0VudHJ5QXV0b21hdGlvblNldHRpbmdzID0gZnVuY3Rpb24oKSB7XG5cblx0dmFyIHNlbGYgPSB0aGlzLFxuXHRcdCQgICAgPSBqUXVlcnk7XG5cblx0c2VsZi5pbml0ID0gZnVuY3Rpb24oKSB7XG5cblx0XHQvLyBBZGQgbG9jYWxpemVkIHN0cmluZ3MuXG5cdFx0c2VsZi5zdHJpbmdzID0gZm9yZ3Jhdml0eV9lbnRyeWF1dG9tYXRpb25fcGx1Z2luX3NldHRpbmdzX3N0cmluZ3M7XG5cblx0XHQvLyBJbml0aWFsaXplIEV4dGVuc2lvbiBBY3Rpb25zLlxuXHRcdHNlbGYucnVuRXh0ZW5zaW9uQWN0aW9uKCk7XG5cblx0fVxuXG5cblxuXG5cblx0Ly8gIyBFWFRFTlNJT05TIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cblxuXHQvKipcblx0ICogSGFuZGxlIFJ1biBUYXNrIE5vdyBidXR0b24uXG5cdCAqXG5cdCAqIEBzaW5jZSAxLjNcblx0ICovXG5cdHNlbGYucnVuRXh0ZW5zaW9uQWN0aW9uID0gZnVuY3Rpb24oKSB7XG5cblx0XHQkKCBkb2N1bWVudCApLm9uKCAnY2xpY2snLCAnI2dhZGRvbi1zZXR0aW5nLXJvdy1leHRlbnNpb25zIGFbZGF0YS1hY3Rpb25dLCAjZ2Zvcm1fc2V0dGluZ19leHRlbnNpb25zIGFbZGF0YS1hY3Rpb25dJywgZnVuY3Rpb24oIGUgKSB7XG5cblx0XHRcdHZhciAkYnV0dG9uID0gJCggdGhpcyApLFxuXHRcdFx0XHRhY3Rpb24gPSAkYnV0dG9uLmRhdGEoICdhY3Rpb24nICksXG5cdFx0XHRcdHBsdWdpbiA9ICRidXR0b24uZGF0YSggJ3BsdWdpbicgKTtcblxuXHRcdFx0Ly8gSWYgdGhpcyBpcyB0aGUgdXBncmFkZSBhY3Rpb24sIHJldHVybi5cblx0XHRcdGlmICggJ3VwZ3JhZGUnID09PSBhY3Rpb24gKSB7XG5cdFx0XHRcdHJldHVybiB0cnVlO1xuXHRcdFx0fVxuXG5cdFx0XHRlLnByZXZlbnREZWZhdWx0KCk7XG5cblx0XHRcdC8vIERpc2FibGUgYnV0dG9uLlxuXHRcdFx0JGJ1dHRvbi5hdHRyKCAnZGlzYWJsZWQnLCAnZGlzYWJsZWQnICk7XG5cblx0XHRcdC8vIENoYW5nZSBidXR0b24gdGV4dC5cblx0XHRcdCRidXR0b24uaHRtbCggc2VsZi5zdHJpbmdzLnByb2Nlc3NpbmdbIGFjdGlvbiBdICk7XG5cblx0XHRcdC8vIFByZXBhcmUgcmVxdWVzdCBkYXRhLlxuXHRcdFx0dmFyIGRhdGEgPSB7XG5cdFx0XHRcdGFjdGlvbjogICAgJ2ZnX2VudHJ5YXV0b21hdGlvbl9leHRlbnNpb25fYWN0aW9uJyxcblx0XHRcdFx0ZXh0ZW5zaW9uOiB7XG5cdFx0XHRcdFx0YWN0aW9uOiBhY3Rpb24sXG5cdFx0XHRcdFx0cGx1Z2luOiBwbHVnaW5cblx0XHRcdFx0fSxcblx0XHRcdFx0bm9uY2U6ICAgICBzZWxmLnN0cmluZ3Mubm9uY2Vcblx0XHRcdH07XG5cblx0XHRcdC8vIFJ1biB0YXNrLlxuXHRcdFx0JC5hamF4KFxuXHRcdFx0XHR7XG5cdFx0XHRcdFx0dXJsOiAgICAgIGFqYXh1cmwsXG5cdFx0XHRcdFx0dHlwZTogICAgICdQT1NUJyxcblx0XHRcdFx0XHRkYXRhVHlwZTogJ2pzb24nLFxuXHRcdFx0XHRcdGRhdGE6ICAgICBkYXRhLFxuXHRcdFx0XHRcdHN1Y2Nlc3M6ICBmdW5jdGlvbiggcmVzcG9uc2UgKSB7XG5cblx0XHRcdFx0XHRcdC8vIElmIGNvdWxkIG5vdCBwcm9jZXNzIGFjdGlvbiwgZGlzcGxheSBlcnJvciBtZXNzYWdlLlxuXHRcdFx0XHRcdFx0aWYgKCAhIHJlc3BvbnNlLnN1Y2Nlc3MgKSB7XG5cdFx0XHRcdFx0XHRcdGFsZXJ0KCByZXNwb25zZS5kYXRhLmVycm9yICk7XG5cdFx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHRcdC8vIFVwZGF0ZSBidXR0b24uXG5cdFx0XHRcdFx0XHQkYnV0dG9uLmRhdGEoICdhY3Rpb24nLCByZXNwb25zZS5kYXRhLm5ld0FjdGlvbiApO1xuXHRcdFx0XHRcdFx0JGJ1dHRvbi5odG1sKCByZXNwb25zZS5kYXRhLm5ld1RleHQgKTtcblxuXHRcdFx0XHRcdFx0Ly8gRW5hYmxlIGJ1dHRvbi5cblx0XHRcdFx0XHRcdCRidXR0b24ucmVtb3ZlQXR0ciggJ2Rpc2FibGVkJyApO1xuXG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdH1cblxuXHRcdFx0KVxuXG5cdFx0fSApO1xuXG5cdH1cblxuXHRzZWxmLmluaXQoKTtcblxufVxuXG5qUXVlcnkoIGRvY3VtZW50ICkucmVhZHkoIGZ1bmN0aW9uKCkgeyBuZXcgRkdFbnRyeUF1dG9tYXRpb25TZXR0aW5ncygpOyB9ICk7XG4iXSwibmFtZXMiOltdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./js/src/plugin_settings.js\n");

/***/ }),

/***/ "./scss/plugin_settings.scss":
/*!***********************************!*\
  !*** ./scss/plugin_settings.scss ***!
  \***********************************/
/***/ (() => {

eval("// extracted by mini-css-extract-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9zY3NzL3BsdWdpbl9zZXR0aW5ncy5zY3NzLmpzIiwibWFwcGluZ3MiOiJBQUFBIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vZm9yZ3Jhdml0eS1lbnRyeWF1dG9tYXRpb24vLi9zY3NzL3BsdWdpbl9zZXR0aW5ncy5zY3NzPzI4MjUiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gZXh0cmFjdGVkIGJ5IG1pbmktY3NzLWV4dHJhY3QtcGx1Z2luIl0sIm5hbWVzIjpbXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./scss/plugin_settings.scss\n");

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
/******/ 	/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./js/src/plugin_settings.js");
/******/ 	
/******/ })()
;