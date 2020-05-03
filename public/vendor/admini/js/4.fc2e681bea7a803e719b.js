(window.webpackJsonp=window.webpackJsonp||[]).push([[4],{21:function(t,e){function n(t,e,n){var i,a,r,s,o;function c(){var l=Date.now()-s;l<e&&l>=0?i=setTimeout(c,e-l):(i=null,n||(o=t.apply(r,a),r=a=null))}null==e&&(e=100);var l=function(){r=this,a=arguments,s=Date.now();var l=n&&!i;return i||(i=setTimeout(c,e)),l&&(o=t.apply(r,a),r=a=null),o};return l.clear=function(){i&&(clearTimeout(i),i=null)},l.flush=function(){i&&(o=t.apply(r,a),r=a=null,clearTimeout(i),i=null)},l}n.debounce=n,t.exports=n},29:function(t,e,n){"use strict";n.r(e);var i={components:{AdminiTable:n(5).a},data:function(){return{model:""}},created:function(){this.model=this.$route.params.model},beforeRouteUpdate:function(t,e,n){this.model=t.params.model,n()}},a=n(0),r=Object(a.a)(i,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"container"},[n("h1",{staticClass:"page-title"},[t._v("Table of "+t._s(t.model))]),t._v(" "),n("router-link",{staticClass:"button is-primary",attrs:{to:"/"+t.model+"/create"}},[t._v("Create new")]),t._v(" "),n("admini-table",{attrs:{model:t.model}})],1)}),[],!1,null,null,null);e.default=r.exports},5:function(t,e,n){"use strict";var i={props:["filters","activeFilters","isSearching"],data:function(){return{searchString:""}},computed:{},methods:{}},a=n(0),r={components:{AdminiTableFilters:Object(a.a)(i,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("nav",{staticClass:"navbar navbar__filter",attrs:{role:"navigation"}},[n("div",{staticClass:"navbar-menu"},[n("div",{staticClass:"navbar-start"},[t._l(t.filters,(function(e){return n("div",{staticClass:"navbar-filter"},[["b-checkbox-button","b-radio-button"].includes(e.control)?n("b-field",t._l(e.options,(function(i){return n(e.control,{key:i.value,tag:"component",attrs:{"native-value":i.value||!0,type:i.type},model:{value:t.activeFilters[e.name],callback:function(n){t.$set(t.activeFilters,e.name,n)},expression:"activeFilters[filter.name]"}},[i.icon?n("b-icon",{attrs:{icon:i.icon}}):t._e(),t._v(" "),n("span",[t._v(t._s(i.label))])],1)})),1):t._e(),t._v(" "),"b-dropdown"===e.control?n("b-dropdown",{attrs:{"aria-role":"list"},model:{value:t.activeFilters[e.name],callback:function(n){t.$set(t.activeFilters,e.name,n)},expression:"activeFilters[filter.name]"}},[n("button",{staticClass:"button",attrs:{slot:"trigger",type:"button"},slot:"trigger"},[n("b-icon",{attrs:{icon:"earth"}}),t._v(" "),n("span",[t._v(t._s(t.activeFilters[e.name].length?e.options.filter((function(n){return n.value===t.activeFilters[e.name]}))[0].label:e.label))]),t._v(" "),n("b-icon",{attrs:{icon:"menu-down"}})],1),t._v(" "),t._l(e.options,(function(e){return n("b-dropdown-item",{key:e.value,attrs:{value:e.value,"aria-role":"listitem"}},[n("div",{staticClass:"media"},[e.icon?n("b-icon",{staticClass:"media-left",attrs:{icon:e.icon}}):t._e(),t._v(" "),n("div",{staticClass:"media-content"},[n("h3",[t._v(t._s(e.label))]),t._v(" "),n("small",[t._v(t._s(e.description))])])],1)])}))],2):t._e()],1)})),t._v(" "),n("b-button",{attrs:{type:"is-text"},on:{click:function(e){return t.$emit("reset-filters")}}},[t._v("Clear filters")])],2),t._v(" "),n("div",{staticClass:"navbar-end"},[n("div",{staticClass:"navbar-item"},[n("div",{staticClass:"control search-control"},[n("b-input",{attrs:{width:"40",rounded:"",placeholder:"Search",type:"text",loading:t.isSearching},on:{input:function(e){return t.$emit("update-search",e)}},model:{value:t.searchString,callback:function(e){t.searchString=e},expression:"searchString"}})],1)])])])])}),[],!1,null,null,null).exports},props:["model"],data:function(){return{resource:[],page:1,checkedRows:[],sortField:"id",sortOrder:"asc",searchString:"",activeFilters:{},dataLoading:!1,filtersLoaded:!1,isSearching:!1}},computed:{queryString:function(){return["?page=".concat(this.page),"order_by=".concat(this.sortField),"order=".concat(this.sortOrder),"s=".concat(this.searchString),this.filterString].join("&")},filterString:function(){var t=this,e=[];return Object.keys(this.activeFilters).map((function(n){""!=t.activeFilters[n]&&e.push("filter[".concat(n,"]=").concat(t.activeFilters[n]))})),e.join("&")}},watch:{activeFilters:{handler:function(){this.fetchData()},deep:!0},searchString:function(){this.doSearch()},model:function(){this.fetchData()}},created:function(){this.fetchData()},methods:{format:function(t,e){return e&&Vue.filter(e)?Vue.filter(e)(t):t},deleteItem:function(t,e){var n=this;this.$buefy.dialog.confirm({title:"Permanently deleting entry",message:"Are you sure you want to <b>".concat(e?"permanently delete":"delete","</b> this entry?"),confirmText:e?"Permanently delete":"Delete",type:e?"is-danger":"is-warning",hasIcon:!0,onConfirm:function(){return axios.delete("/admini/"+n.model+"/"+t+(e?"/forcedelete":"/delete")).then((function(t){var e=t.data;return n.postDelete(e)}))}})},postDelete:function(t){this.$buefy.snackbar.open({message:t.message,position:"is-bottom-right",type:t.type}),this.fetchData()},fetchData:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.model;this.dataLoading=!0,axios.get("/admini/".concat(e,"/index/").concat(this.queryString)).then((function(e){var n=e.data;return t.populateData(n)}))},populateData:function(t){this.resource=t,this.dataLoading=!1,this.isSearching=!1,!this.filtersLoaded&&this.resource.filters&&this.resetFilters()},updateSearch:function(t){this.searchString=t},doSearch:n(21)((function(){this.isSearching=!0,this.fetchData()}),500),resetFilters:function(){var t=this;this.resource.filters.forEach((function(e){return t.$set(t.activeFilters,e.name,e.options.length>1?[]:"")})),this.filtersLoaded=!0},onPageChange:function(t){this.page=t,this.fetchData()},onSort:function(t,e){this.sortField=t,this.sortOrder=e,this.fetchData()}}},s=Object(a.a)(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[t.resource.filters?n("admini-table-filters",{attrs:{filters:t.resource.filters,isSearching:t.isSearching,activeFilters:t.activeFilters},on:{"reset-filters":t.resetFilters,"update-search":t.updateSearch}}):t._e(),t._v(" "),t.resource.entries?n("b-table",{key:t.resource.title,attrs:{data:t.resource.entries.data,paginated:"","backend-pagination":"","per-page":t.resource.entries.per_page,total:t.resource.entries.total,"backend-sorting":"",loading:t.dataLoading,"checked-rows":t.checkedRows,checkable:""},on:{"page-change":t.onPageChange,sort:t.onSort,"update:checkedRows":function(e){t.checkedRows=e},"update:checked-rows":function(e){t.checkedRows=e}},scopedSlots:t._u([{key:"default",fn:function(e){return[t._l(t.resource.columns,(function(i){return n("b-table-column",{attrs:{label:i.label,field:i.name,sortable:i.sortable,numeric:i.numeric}},[n("span",{domProps:{innerHTML:t._s(t.format(e.row[i.name],i.filter))}})])})),t._v(" "),n("b-table-column",{key:"deleted"},[e.row.deleted_at?n("b-tag",{attrs:{type:"is-danger"}},[t._v("Deleted")]):t._e()],1),t._v(" "),n("b-table-column",{key:"actions",attrs:{label:"Actions",field:"actions"}},[n("b-field",[n("p",{staticClass:"control"},[n("router-link",{staticClass:"button is-primary",attrs:{to:"/"+t.$route.params.model+"/"+e.row.id}},[t._v("\n               Edit\n            ")])],1),t._v(" "),n("p",{staticClass:"control"},[n("b-dropdown",{attrs:{position:"is-bottom-left"}},[n("button",{staticClass:"button is-primary",attrs:{slot:"trigger"},slot:"trigger"},[n("b-icon",{attrs:{icon:"menu-down"}})],1),t._v(" "),e.row.deleted_at?n("b-dropdown-item",{on:{click:function(n){return t.deleteItem(e.row.id,!0)}}},[t._v("Permanently delete")]):n("b-dropdown-item",{on:{click:function(n){return t.deleteItem(e.row.id)}}},[t._v("Delete")])],1)],1)])],1)]}}],null,!1,929451379)},[t._v(" "),t.dataLoading?t._e():n("template",{slot:"empty"},[n("section",{staticClass:"section"},[n("div",{staticClass:"content has-text-grey has-text-centered"},[n("p",[n("b-icon",{attrs:{icon:"emoticon-sad",size:"is-large"}})],1),t._v(" "),n("p",[t._v("No results found.")])])])]),t._v(" "),n("template",{slot:"bottom-left"},[t.checkedRows.length?n("p",[t._v(t._s(t.checkedRows.length)+" entries selected")]):t.resource.entries.total?n("p",[t._v("Showing "+t._s(t.resource.entries.from)+" to "+t._s(t.resource.entries.to)+" of "+t._s(t.resource.entries.total)+" entries")]):t._e()])],2):t._e()],1)}),[],!1,null,null,null);e.a=s.exports}}]);