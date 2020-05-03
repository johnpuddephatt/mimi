(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[2],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./src/resources/js/components/AdminiFields.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./src/resources/js/components/AdminiFields.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue2_editor__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue2-editor */ "./node_modules/vue2-editor/dist/vue2-editor.esm.js");
/* harmony import */ var _components_AdminiTable_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/AdminiTable.vue */ "./src/resources/js/components/AdminiTable.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

 // import { ImageDrop } from 'quill-image-drop-module'
// import ImageResize from 'quill-image-resize-module'
// Quill.register('modules/imageDrop', ImageDrop)
// Quill.register('modules/imageResize', ImageResize)

/* harmony default export */ __webpack_exports__["default"] = ({
  props: ['fields', 'data', 'errors'],
  components: {
    VueEditor: vue2_editor__WEBPACK_IMPORTED_MODULE_0__["VueEditor"],
    AdminiTable: _components_AdminiTable_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  methods: {
    convertStringToDate: function convertStringToDate(value) {
      return new Date(value);
    }
  },
  data: function data() {
    return {// customModulesForEditor: [{ alias: "imageDrop", module: ImageDrop }, { alias: "imageResize", module: ImageResize }],
      // editorSettings: {
      //   modules: {
      //     imageDrop: true,
      //     imageResize: {}
      //   }
      // }
    };
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./src/resources/js/components/AdminiFieldsets.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./src/resources/js/components/AdminiFieldsets.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_AdminiFields_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/AdminiFields.vue */ "./src/resources/js/components/AdminiFields.vue");
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

//
//
//
//
//
//
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
  props: ['fields', 'data', 'errors'],
  components: {
    AdminiFields: _components_AdminiFields_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  computed: {
    fieldsetNames: function fieldsetNames() {
      return _toConsumableArray(new Set(this.fields.map(function (field) {
        return field.fieldset;
      })));
    }
  },
  methods: {
    getFieldsByFieldset: function getFieldsByFieldset(fieldsetName) {
      return this.fields.filter(function (field) {
        return field.fieldset === fieldsetName;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./src/resources/js/components/AdminiFields.vue?vue&type=template&id=61eb24ee&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/resources/js/components/AdminiFields.vue?vue&type=template&id=61eb24ee& ***!
  \*******************************************************************************************************************************************************************************************************************/
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
    _vm._l(_vm.fields, function(field) {
      return _c(
        "b-field",
        _vm._b(
          {
            key: field.name,
            attrs: {
              label: field.label,
              message:
                _vm.errors[field.name] ||
                (field.fieldProps ? field.fieldProps.message : "") ||
                null,
              type: _vm.errors[field.name] ? "is-danger" : null
            }
          },
          "b-field",
          field.fieldProps,
          false
        ),
        [
          field.control == "b-autocomplete"
            ? _c(
                "div",
                [
                  _c(
                    "b-autocomplete",
                    _vm._b(
                      {
                        on: {
                          select: function(selected) {
                            return (_vm.data[(_vm.name = field.name)] =
                              selected[field.inputProps.dataKey])
                          }
                        }
                      },
                      "b-autocomplete",
                      field.inputProps,
                      false
                    )
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.data[(_vm.name = field.name)],
                        expression: "data[name=field.name]"
                      }
                    ],
                    attrs: { type: "hidden" },
                    domProps: { value: _vm.data[(_vm.name = field.name)] },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(
                          _vm.data,
                          (_vm.name = field.name),
                          $event.target.value
                        )
                      }
                    }
                  })
                ],
                1
              )
            : ["b-select"].includes(field.control)
            ? _c(
                field.control || "b-input",
                _vm._b(
                  {
                    tag: "component",
                    model: {
                      value: _vm.data[(_vm.name = field.name)],
                      callback: function($$v) {
                        _vm.$set(_vm.data, (_vm.name = field.name), $$v)
                      },
                      expression: "data[name=field.name]"
                    }
                  },
                  "component",
                  field.inputProps,
                  false
                ),
                _vm._l(field.dataProps.data, function(option) {
                  return _c(
                    "option",
                    {
                      domProps: {
                        value: field.dataProps.store_as
                          ? option[field.dataProps.store_as]
                          : option
                      }
                    },
                    [
                      _vm._v(
                        _vm._s(
                          field.dataProps.display_as
                            ? option[field.dataProps.display_as]
                            : option
                        ) + " "
                      )
                    ]
                  )
                }),
                0
              )
            : field.control == "b-table"
            ? _c("admini-table", { attrs: { model: "posts" } })
            : _c(
                field.control || "b-input",
                _vm._b(
                  {
                    tag: "component",
                    model: {
                      value: _vm.data[(_vm.name = field.name)],
                      callback: function($$v) {
                        _vm.$set(_vm.data, (_vm.name = field.name), $$v)
                      },
                      expression: "data[name=field.name]"
                    }
                  },
                  "component",
                  field.inputProps,
                  false
                )
              )
        ],
        1
      )
    }),
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./src/resources/js/components/AdminiFieldsets.vue?vue&type=template&id=2f4b44aa&":
/*!**********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/resources/js/components/AdminiFieldsets.vue?vue&type=template&id=2f4b44aa& ***!
  \**********************************************************************************************************************************************************************************************************************/
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
    _vm._l(_vm.fieldsetNames, function(fieldsetName) {
      return _c("fieldset", { staticClass: "card" }, [
        fieldsetName
          ? _c("div", { staticClass: "card-header" }, [
              _c("p", { staticClass: "card-header-title" }, [
                _vm._v("\n        " + _vm._s(fieldsetName) + "\n      ")
              ])
            ])
          : _vm._e(),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "card-content" },
          [
            _c("admini-fields", {
              attrs: {
                fields: _vm.getFieldsByFieldset(fieldsetName),
                data: _vm.data,
                errors: _vm.errors
              }
            })
          ],
          1
        )
      ])
    }),
    0
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./src/resources/js/components/AdminiFields.vue":
/*!******************************************************!*\
  !*** ./src/resources/js/components/AdminiFields.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AdminiFields_vue_vue_type_template_id_61eb24ee___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AdminiFields.vue?vue&type=template&id=61eb24ee& */ "./src/resources/js/components/AdminiFields.vue?vue&type=template&id=61eb24ee&");
/* harmony import */ var _AdminiFields_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AdminiFields.vue?vue&type=script&lang=js& */ "./src/resources/js/components/AdminiFields.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AdminiFields_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AdminiFields_vue_vue_type_template_id_61eb24ee___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AdminiFields_vue_vue_type_template_id_61eb24ee___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/js/components/AdminiFields.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./src/resources/js/components/AdminiFields.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./src/resources/js/components/AdminiFields.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminiFields_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./AdminiFields.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./src/resources/js/components/AdminiFields.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminiFields_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/js/components/AdminiFields.vue?vue&type=template&id=61eb24ee&":
/*!*************************************************************************************!*\
  !*** ./src/resources/js/components/AdminiFields.vue?vue&type=template&id=61eb24ee& ***!
  \*************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminiFields_vue_vue_type_template_id_61eb24ee___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./AdminiFields.vue?vue&type=template&id=61eb24ee& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./src/resources/js/components/AdminiFields.vue?vue&type=template&id=61eb24ee&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminiFields_vue_vue_type_template_id_61eb24ee___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminiFields_vue_vue_type_template_id_61eb24ee___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./src/resources/js/components/AdminiFieldsets.vue":
/*!*********************************************************!*\
  !*** ./src/resources/js/components/AdminiFieldsets.vue ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AdminiFieldsets_vue_vue_type_template_id_2f4b44aa___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AdminiFieldsets.vue?vue&type=template&id=2f4b44aa& */ "./src/resources/js/components/AdminiFieldsets.vue?vue&type=template&id=2f4b44aa&");
/* harmony import */ var _AdminiFieldsets_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AdminiFieldsets.vue?vue&type=script&lang=js& */ "./src/resources/js/components/AdminiFieldsets.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AdminiFieldsets_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AdminiFieldsets_vue_vue_type_template_id_2f4b44aa___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AdminiFieldsets_vue_vue_type_template_id_2f4b44aa___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/js/components/AdminiFieldsets.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./src/resources/js/components/AdminiFieldsets.vue?vue&type=script&lang=js&":
/*!**********************************************************************************!*\
  !*** ./src/resources/js/components/AdminiFieldsets.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminiFieldsets_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./AdminiFieldsets.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./src/resources/js/components/AdminiFieldsets.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminiFieldsets_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/js/components/AdminiFieldsets.vue?vue&type=template&id=2f4b44aa&":
/*!****************************************************************************************!*\
  !*** ./src/resources/js/components/AdminiFieldsets.vue?vue&type=template&id=2f4b44aa& ***!
  \****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminiFieldsets_vue_vue_type_template_id_2f4b44aa___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./AdminiFieldsets.vue?vue&type=template&id=2f4b44aa& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./src/resources/js/components/AdminiFieldsets.vue?vue&type=template&id=2f4b44aa&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminiFieldsets_vue_vue_type_template_id_2f4b44aa___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminiFieldsets_vue_vue_type_template_id_2f4b44aa___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./src/resources/js/core/Errors.js":
/*!*****************************************!*\
  !*** ./src/resources/js/core/Errors.js ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Errors = /*#__PURE__*/function () {
  /**
   * Create a new Errors instance.
   */
  function Errors() {
    _classCallCheck(this, Errors);

    this.errors = {};
  }
  /**
   * Determine if an errors exists for the given field.
   *
   * @param {string} field
   */


  _createClass(Errors, [{
    key: "has",
    value: function has(field) {
      return this.errors.hasOwnProperty(field);
    }
    /**
     * Determine if we have any errors.
     */

  }, {
    key: "any",
    value: function any() {
      return Object.keys(this.errors).length > 0;
    }
    /**
     * Retrieve the error message for a field.
     *
     * @param {string} field
     */

  }, {
    key: "get",
    value: function get(field) {
      if (this.errors[field]) {
        return this.errors[field][0];
      }
    }
    /**
     * Record the new errors.
     *
     * @param {object} errors
     */

  }, {
    key: "record",
    value: function record(errors) {
      this.errors = errors; // fields.filter(field => Object.keys(errors).includes(field.name)).map(field=> field['error'] = 'foo1');
    }
    /**
     * Clear one or all error fields.
     *
     * @param {string|null} field
     */

  }, {
    key: "clear",
    value: function clear(field) {
      if (field) {
        delete this.errors[field];
        return;
      }

      this.errors = {};
    }
  }]);

  return Errors;
}();

/* harmony default export */ __webpack_exports__["default"] = (Errors);

/***/ }),

/***/ "./src/resources/js/core/Form.js":
/*!***************************************!*\
  !*** ./src/resources/js/core/Form.js ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Errors__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Errors */ "./src/resources/js/core/Errors.js");
/* harmony import */ var buefy__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! buefy */ "./node_modules/buefy/dist/esm/index.js");
/* harmony import */ var _routes__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../routes */ "./src/resources/js/routes.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }





var Form = /*#__PURE__*/function () {
  /**
   * Create a new Form instance.
   *
   * @param {object} data
   */
  function Form(fields) {
    var entry = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

    _classCallCheck(this, Form);

    this.fields = fields;

    if (entry) {
      var datetimepickerFields = fields.filter(function (field) {
        return field.control == 'b-datetimepicker';
      }).map(function (field) {
        return field['name'];
      });
      datetimepickerFields.forEach(function (name) {
        return entry[name] = new Date(entry[name]);
      });
      this.data = entry;
      this.originalData = JSON.parse(JSON.stringify(entry));
    }

    this.errors = new _Errors__WEBPACK_IMPORTED_MODULE_0__["default"]();
  }

  _createClass(Form, [{
    key: "reset",
    value: function reset() {
      this.data = this.originalData;
      this.errors.clear();
      buefy__WEBPACK_IMPORTED_MODULE_1__["SnackbarProgrammatic"].open({
        message: "Changes have been reset",
        position: 'is-bottom-right',
        type: 'is-success'
      });
    }
    /**
     * Send a POST request to the given URL.
     * .
     * @param {string} url
     */

  }, {
    key: "post",
    value: function post(url) {
      return this.submit('post', url);
    }
    /**
     * Send a PUT request to the given URL.
     * .
     * @param {string} url
     */

  }, {
    key: "put",
    value: function put(url) {
      return this.submit('put', url);
    }
    /**
     * Send a PATCH request to the given URL.
     * .
     * @param {string} url
     */

  }, {
    key: "patch",
    value: function patch(url) {
      return this.submit('patch', url);
    }
    /**
     * Send a DELETE request to the given URL.
     * .
     * @param {string} url
     */

  }, {
    key: "delete",
    value: function _delete(url) {
      return this.submit('delete', url);
    }
    /**
     * Submit the form.
     *
     * @param {string} requestType
     * @param {string} url
     */

  }, {
    key: "submit",
    value: function submit(requestType, url) {
      var _this = this;

      return new Promise(function (resolve, reject) {
        axios[requestType](url, _this.data).then(function (response) {
          _this.onSuccess(response.data);

          _this.response = response;
          resolve(response.data);
        })["catch"](function (error) {
          _this.onFail(error.response.data); // reject(error.response.data);

        });
      });
    }
    /**
     * Handle a successful form submission.
     *
     * @param {object} data
     */

  }, {
    key: "onSuccess",
    value: function onSuccess(data) {
      buefy__WEBPACK_IMPORTED_MODULE_1__["SnackbarProgrammatic"].open({
        message: data.message.errorInfo ? "<b>Database error:</b> ".concat(data.message.errorInfo[2]) : data.message,
        position: 'is-bottom-right',
        type: data.type
      });

      if (data.entry_id) {
        _routes__WEBPACK_IMPORTED_MODULE_2__["default"].push("./".concat(data.entry_id));
      }
    }
    /**
     * Handle a failed form submission.
     *
     * @param {object} errors
     */

  }, {
    key: "onFail",
    value: function onFail(errors) {
      var _errors$message;

      this.errors.message = errors.message;
      this.errors.record(errors.errors);
      buefy__WEBPACK_IMPORTED_MODULE_1__["SnackbarProgrammatic"].open({
        message: (_errors$message = errors.message) !== null && _errors$message !== void 0 ? _errors$message : 'An error has occured',
        position: 'is-bottom-right',
        type: 'is-danger'
      });
    }
  }]);

  return Form;
}();

/* harmony default export */ __webpack_exports__["default"] = (Form);

/***/ })

}]);