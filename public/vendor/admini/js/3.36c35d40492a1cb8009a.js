(window.webpackJsonp=window.webpackJsonp||[]).push([[3],{217:function(t,e,n){"use strict";n.r(e);var r=n(7),a=n(6),i=n(8),o=n.n(i);n(12);function s(t){return function(t){if(Array.isArray(t))return c(t)}(t)||function(t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}(t)||function(t,e){if(!t)return;if("string"==typeof t)return c(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);"Object"===n&&t.constructor&&(n=t.constructor.name);if("Map"===n||"Set"===n)return Array.from(t);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return c(t,e)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function c(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,r=new Array(e);n<e;n++)r[n]=t[n];return r}var l={components:{AdminiFieldsets:a.a},data:function(){return{form:""}},computed:{tabNames:function(){return s(new Set(this.form.fields.map((function(t){return t.tab}))))}},created:function(){var t=this;axios.get("/"+this.$route.params.model+"/"+this.$route.params.id).then((function(e){var n=e.data;t.populateData(n.fields,n.entry)}))},methods:{onSubmit:function(){this.form.put("/"+this.$route.params.model+"/"+this.$route.params.id)},populateData:function(t,e){this.form=new r.a(t,e),o.a.initHighlightingOnLoad()},getFieldsByTab:function(t){return this.form.fields.filter((function(e){return e.tab===t}))}}},u=n(0),d=Object(u.a)(l,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"container"},[n("h1",{staticClass:"page-title"},[t._v("Edit")]),t._v(" "),t.form.fields?n("form",{attrs:{method:"POST",action:this.$route.params.model+"/"+this.$route.params.id},on:{submit:function(e){return e.preventDefault(),t.onSubmit(e)},keydown:function(e){return t.form.errors.clear(e.target.name)}}},[n("b-tabs",{attrs:{animated:!1}},t._l(t.tabNames,(function(e){return n("b-tab-item",{key:e||"other",attrs:{label:e||"Other"}},[n("admini-fieldsets",{attrs:{tabs:t.getFieldsByTab(e),data:t.form.data,errors:t.form.errors.errors}})],1)})),1),t._v(" "),n("b-button",{attrs:{tag:"input",value:"Reset"},on:{click:function(e){return t.form.reset()}}}),t._v(" "),n("b-button",{staticClass:"is-primary",attrs:{tag:"input","native-type":"submit",value:"Save"}})],1):t._e(),t._v(" "),n("b-collapse",{attrs:{open:!1,position:"is-bottom","aria-id":"contentIdForA11y1"},scopedSlots:t._u([{key:"trigger",fn:function(e){return n("a",{attrs:{"aria-controls":"contentIdForA11y1"}},[n("b-icon",{attrs:{icon:e.open?"menu-up":"menu-down"}}),t._v("\n      "+t._s(e.open?"Fewer options":"All options")+"\n    ")],1)}}])},[t._v(" "),n("pre",[n("code",{staticClass:"JSON"},[t._v(t._s(t.form))])])])],1)}),[],!1,null,null,null);e.default=d.exports},5:function(t,e,n){"use strict";var r={props:["filters","activeFilters","isSearching"],data:function(){return{searchString:""}},computed:{},methods:{}},a=n(0),i={components:{AdminiTableFilters:Object(a.a)(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("nav",{staticClass:"navbar navbar__filter",attrs:{role:"navigation"}},[n("div",{staticClass:"navbar-menu"},[n("div",{staticClass:"navbar-start"},[t._l(t.filters,(function(e){return n("div",{staticClass:"navbar-filter"},[["b-checkbox-button","b-radio-button"].includes(e.control)?n("b-field",t._l(e.options,(function(r){return n(e.control,{key:r.value,tag:"component",attrs:{"native-value":r.value||!0,type:r.type},model:{value:t.activeFilters[e.name],callback:function(n){t.$set(t.activeFilters,e.name,n)},expression:"activeFilters[filter.name]"}},[r.icon?n("b-icon",{attrs:{icon:r.icon}}):t._e(),t._v(" "),n("span",[t._v(t._s(r.label))])],1)})),1):t._e(),t._v(" "),"b-dropdown"===e.control?n("b-dropdown",{attrs:{"aria-role":"list"},model:{value:t.activeFilters[e.name],callback:function(n){t.$set(t.activeFilters,e.name,n)},expression:"activeFilters[filter.name]"}},[n("button",{staticClass:"button",attrs:{slot:"trigger",type:"button"},slot:"trigger"},[n("b-icon",{attrs:{icon:"earth"}}),t._v(" "),n("span",[t._v(t._s(t.activeFilters[e.name].length?e.options.filter((function(n){return n.value===t.activeFilters[e.name]}))[0].label:e.label))]),t._v(" "),n("b-icon",{attrs:{icon:"menu-down"}})],1),t._v(" "),t._l(e.options,(function(e){return n("b-dropdown-item",{key:e.value,attrs:{value:e.value,"aria-role":"listitem"}},[n("div",{staticClass:"media"},[e.icon?n("b-icon",{staticClass:"media-left",attrs:{icon:e.icon}}):t._e(),t._v(" "),n("div",{staticClass:"media-content"},[n("h3",[t._v(t._s(e.label))]),t._v(" "),n("small",[t._v(t._s(e.description))])])],1)])}))],2):t._e()],1)})),t._v(" "),n("b-button",{attrs:{type:"is-text"},on:{click:function(e){return t.$emit("reset-filters")}}},[t._v("Clear filters")])],2),t._v(" "),n("div",{staticClass:"navbar-end"},[n("div",{staticClass:"navbar-item"},[n("div",{staticClass:"control search-control"},[n("b-input",{attrs:{width:"40",rounded:"",placeholder:"Search",type:"text",loading:t.isSearching},on:{input:function(e){return t.$emit("update-search",e)}},model:{value:t.searchString,callback:function(e){t.searchString=e},expression:"searchString"}})],1)])])])])}),[],!1,null,null,null).exports},props:["model"],data:function(){return{resource:[],page:1,checkedRows:[],sortField:"id",sortOrder:"asc",searchString:"",activeFilters:{},dataLoading:!1,filtersLoaded:!1,isSearching:!1}},computed:{queryString:function(){return["?page=".concat(this.page),"order_by=".concat(this.sortField),"order=".concat(this.sortOrder),"s=".concat(this.searchString),this.filterString].join("&")},filterString:function(){var t=this,e=[];return Object.keys(this.activeFilters).map((function(n){""!=t.activeFilters[n]&&e.push("filter[".concat(n,"]=").concat(t.activeFilters[n]))})),e.join("&")}},watch:{activeFilters:{handler:function(){this.fetchData()},deep:!0},searchString:function(){this.doSearch()},model:function(){this.fetchData()}},created:function(){this.fetchData()},methods:{format:function(t,e){return e&&Vue.filter(e)?Vue.filter(e)(t):t},deleteItem:function(t){var e=this;this.$buefy.dialog.confirm({title:"Permanently deleting entry",message:"Are you sure you want to <b>delete</b> this entry?",confirmText:"Delete",type:"is-warning",hasIcon:!0,onConfirm:function(){return axios.delete("/"+e.model+"/"+t+"/delete").then((function(t){var n=t.data;return e.postDelete(n)}))}})},forceDeleteItem:function(t){var e=this;this.$buefy.dialog.confirm({title:"Deleting entry",message:"Are you sure you want to <b>permanently delete</b> this entry?",confirmText:"Permanently delete",type:"is-danger",hasIcon:!0,onConfirm:function(){return axios.delete("/"+e.model+"/"+t+"/forcedelete").then((function(t){var n=t.data;return e.postDelete(n)}))}})},postDelete:function(t){this.$buefy.snackbar.open({message:t.message,position:"is-bottom-right",type:t.type}),this.fetchData()},fetchData:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.model;this.dataLoading=!0,axios.get("/".concat(e,"/index/").concat(this.queryString)).then((function(e){var n=e.data;return t.populateData(n)}))},populateData:function(t){this.resource=t,this.dataLoading=!1,this.isSearching=!1,!this.filtersLoaded&&this.resource.filters&&this.resetFilters()},updateSearch:function(t){this.searchString=t},doSearch:n(11)((function(){this.isSearching=!0,this.fetchData()}),500),resetFilters:function(){var t=this;this.resource.filters.forEach((function(e){return t.$set(t.activeFilters,e.name,e.options.length>1?[]:"")})),this.filtersLoaded=!0},onPageChange:function(t){this.page=t,this.fetchData()},onSort:function(t,e){this.sortField=t,this.sortOrder=e,this.fetchData()}}},o=Object(a.a)(i,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[t.resource.filters?n("admini-table-filters",{attrs:{filters:t.resource.filters,isSearching:t.isSearching,activeFilters:t.activeFilters},on:{"reset-filters":t.resetFilters,"update-search":t.updateSearch}}):t._e(),t._v(" "),t.resource.entries?n("b-table",{key:t.resource.title,attrs:{data:t.resource.entries.data,paginated:"","backend-pagination":"","per-page":t.resource.entries.per_page,total:t.resource.entries.total,"backend-sorting":"",loading:t.dataLoading,"checked-rows":t.checkedRows,checkable:""},on:{"page-change":t.onPageChange,sort:t.onSort,"update:checkedRows":function(e){t.checkedRows=e},"update:checked-rows":function(e){t.checkedRows=e}},scopedSlots:t._u([{key:"default",fn:function(e){return[t._l(t.resource.columns,(function(r){return n("b-table-column",{attrs:{label:r.label,field:r.name,sortable:r.sortable,numeric:r.numeric}},[n("span",{domProps:{innerHTML:t._s(t.format(e.row[r.name],r.filter))}})])})),t._v(" "),n("b-table-column",{key:"deleted"},[e.row.deleted_at?n("b-tag",{attrs:{type:"is-danger"}},[t._v("Deleted")]):t._e()],1),t._v(" "),n("b-table-column",{key:"actions",attrs:{label:"Actions",field:"actions"}},[n("b-field",[n("p",{staticClass:"control"},[n("router-link",{staticClass:"button is-primary",attrs:{to:"/"+t.$route.params.model+"/"+e.row.id}},[t._v("\n               Edit\n            ")])],1),t._v(" "),n("p",{staticClass:"control"},[n("b-dropdown",{attrs:{position:"is-bottom-left"}},[n("button",{staticClass:"button is-primary",attrs:{slot:"trigger"},slot:"trigger"},[n("b-icon",{attrs:{icon:"menu-down"}})],1),t._v(" "),e.row.deleted_at?n("b-dropdown-item",{on:{click:function(n){return t.forceDeleteItem(e.row.id)}}},[t._v("Permanently delete")]):n("b-dropdown-item",{on:{click:function(n){return t.deleteItem(e.row.id)}}},[t._v("Delete")])],1)],1)])],1)]}}],null,!1,2308814452)},[t._v(" "),t.dataLoading?t._e():n("template",{slot:"empty"},[n("section",{staticClass:"section"},[n("div",{staticClass:"content has-text-grey has-text-centered"},[n("p",[n("b-icon",{attrs:{icon:"emoticon-sad",size:"is-large"}})],1),t._v(" "),n("p",[t._v("No results found.")])])])]),t._v(" "),n("template",{slot:"bottom-left"},[t.checkedRows.length?n("p",[t._v(t._s(t.checkedRows.length)+" entries selected")]):t.resource.entries.total?n("p",[t._v("Showing "+t._s(t.resource.entries.from)+" to "+t._s(t.resource.entries.to)+" of "+t._s(t.resource.entries.total)+" entries")]):t._e()])],2):t._e()],1)}),[],!1,null,null,null);e.a=o.exports},6:function(t,e,n){"use strict";var r=n(13),a=n(5),i={props:["fields","data","errors"],components:{VueEditor:r.a,AdminiTable:a.a},methods:{convertStringToDate:function(t){return new Date(t)}},data:function(){return{}}},o=n(0);function s(t){return function(t){if(Array.isArray(t))return c(t)}(t)||function(t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}(t)||function(t,e){if(!t)return;if("string"==typeof t)return c(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);"Object"===n&&t.constructor&&(n=t.constructor.name);if("Map"===n||"Set"===n)return Array.from(t);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return c(t,e)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function c(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,r=new Array(e);n<e;n++)r[n]=t[n];return r}var l={props:["tabs","data","errors"],components:{AdminiFields:Object(o.a)(i,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",t._l(t.fields,(function(e){return n("b-field",t._b({key:e.name,attrs:{label:e.label,message:t.errors[e.name]||(e.fieldProps?e.fieldProps.message:"")||null,type:t.errors[e.name]?"is-danger":null}},"b-field",e.fieldProps,!1),["b-autocomplete"==e.control?n("div",[n("b-autocomplete",t._b({on:{select:function(n){return t.data[t.name=e.name]=n[e.inputProps.dataKey]}}},"b-autocomplete",e.inputProps,!1)),t._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:t.data[t.name=e.name],expression:"data[name=field.name]"}],attrs:{type:"hidden"},domProps:{value:t.data[t.name=e.name]},on:{input:function(n){n.target.composing||t.$set(t.data,t.name=e.name,n.target.value)}}})],1):["b-select"].includes(e.control)?n(e.control||"b-input",t._b({tag:"component",model:{value:t.data[t.name=e.name],callback:function(n){t.$set(t.data,t.name=e.name,n)},expression:"data[name=field.name]"}},"component",e.inputProps,!1),t._l(e.dataProps.data,(function(r){return n("option",{domProps:{value:e.dataProps.store_as?r[e.dataProps.store_as]:r}},[t._v(t._s(e.dataProps.display_as?r[e.dataProps.display_as]:r)+" ")])})),0):"b-table"==e.control?n("admini-table",{attrs:{model:"posts"}}):n(e.control||"b-input",t._b({tag:"component",model:{value:t.data[t.name=e.name],callback:function(n){t.$set(t.data,t.name=e.name,n)},expression:"data[name=field.name]"}},"component",e.inputProps,!1))],1)})),1)}),[],!1,null,null,null).exports},computed:{fieldsetNames:function(){return s(new Set(this.tabs.map((function(t){return t.fieldset}))))}},methods:{getFieldsByFieldset:function(t){return this.tabs.filter((function(e){return e.fieldset===t}))}}},u=Object(o.a)(l,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",t._l(t.fieldsetNames,(function(e){return n("fieldset",{staticClass:"card"},[e?n("div",{staticClass:"card-header"},[n("p",{staticClass:"card-header-title"},[t._v("\n        "+t._s(e)+"\n      ")])]):t._e(),t._v(" "),n("div",{staticClass:"card-content"},[n("admini-fields",{attrs:{fields:t.getFieldsByFieldset(e),data:t.data,errors:t.errors}})],1)])})),0)}),[],!1,null,null,null);e.a=u.exports},7:function(t,e,n){"use strict";function r(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}var a=function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.errors={}}var e,n,a;return e=t,(n=[{key:"has",value:function(t){return this.errors.hasOwnProperty(t)}},{key:"any",value:function(){return Object.keys(this.errors).length>0}},{key:"get",value:function(t){if(this.errors[t])return this.errors[t][0]}},{key:"record",value:function(t){this.errors=t}},{key:"clear",value:function(t){t?delete this.errors[t]:this.errors={}}}])&&r(e.prototype,n),a&&r(e,a),t}(),i=n(252),o=n(9);function s(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function c(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}var l=function(){function t(e){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;if(s(this,t),this.fields=e,n){var r=e.filter((function(t){return"b-datetimepicker"==t.control})).map((function(t){return t.name}));r.forEach((function(t){return n[t]=new Date(n[t])})),this.data=n,this.originalData=JSON.parse(JSON.stringify(n))}this.errors=new a}var e,n,r;return e=t,(n=[{key:"reset",value:function(){this.data=this.originalData,this.errors.clear(),i.a.open({message:"Changes have been reset",position:"is-bottom-right",type:"is-success"})}},{key:"post",value:function(t){return this.submit("post",t)}},{key:"put",value:function(t){return this.submit("put",t)}},{key:"patch",value:function(t){return this.submit("patch",t)}},{key:"delete",value:function(t){return this.submit("delete",t)}},{key:"submit",value:function(t,e){var n=this;return new Promise((function(r,a){axios[t](e,n.data).then((function(t){n.onSuccess(t.data),n.response=t,r(t.data)})).catch((function(t){n.onFail(t.response.data)}))}))}},{key:"onSuccess",value:function(t){i.a.open({message:t.message.errorInfo?"<b>Database error:</b> ".concat(t.message.errorInfo[2]):t.message,position:"is-bottom-right",type:t.type}),t.entry_id&&o.a.push("./".concat(t.entry_id))}},{key:"onFail",value:function(t){var e;this.errors.message=t.message,this.errors.record(t.errors),i.a.open({message:null!==(e=t.message)&&void 0!==e?e:"An error has occured",position:"is-bottom-right",type:"is-danger"})}}])&&c(e.prototype,n),r&&c(e,r),t}();e.a=l}}]);