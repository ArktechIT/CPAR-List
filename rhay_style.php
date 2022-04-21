/*
 * This combined file was created by the DataTables downloader builder:
 *   https://datatables.net/download
 *
 * To rebuild or modify this file with the latest versions of the included
 * software please visit:
 *   https://datatables.net/download/#dt/dt-1.10.18/b-1.5.2/b-colvis-1.5.2/b-flash-1.5.2/b-print-1.5.2/fc-3.2.5/fh-3.1.4/kt-2.4.0/r-2.2.2/sc-1.5.0
 *
 * Included libraries:
 *   DataTables 1.10.18, Buttons 1.5.2, Column visibility 1.5.2, Flash export 1.5.2, Print view 1.5.2, FixedColumns 3.2.5, FixedHeader 3.1.4, KeyTable 2.4.0, Responsive 2.2.2, Scroller 1.5.0
 */

/*
 * Table styles
 */
table.dataTable {
  width: 100%;
  margin: 0 auto;
  clear: both;
  border-collapse: separate;
  border-spacing: 0;
  /*
   * Header and footer styles
   */
  /*
   * Body styles
   */
}
table.dataTable thead th,
table.dataTable tfoot th {
  font-weight: bold;
}
table.dataTable thead th,
table.dataTable thead td {
  border-bottom: 1px solid #111;
}
table.dataTable thead th:active,
table.dataTable thead td:active {
  outline: none;
}
table.dataTable tfoot th,
table.dataTable tfoot td {
  padding: 5px;
  border-top: 1px solid #111;
}
table.dataTable thead .sorting,
table.dataTable thead .sorting_asc,
table.dataTable thead .sorting_desc,
table.dataTable thead .sorting_asc_disabled,
table.dataTable thead .sorting_desc_disabled {
  cursor: pointer;
  *cursor: hand;
  background-repeat: no-repeat;
  background-position: center right;
}
table.dataTable thead .sorting {
  background-image: url("DataTables-1.10.18/images/sort_both.png");
}
table.dataTable thead .sorting_asc {
  background-image: url("DataTables-1.10.18/images/sort_asc.png");
}
table.dataTable thead .sorting_desc {
  background-image: url("DataTables-1.10.18/images/sort_desc.png");
}
table.dataTable thead .sorting_asc_disabled {
  background-image: url("DataTables-1.10.18/images/sort_asc_disabled.png");
}
table.dataTable thead .sorting_desc_disabled {
  background-image: url("DataTables-1.10.18/images/sort_desc_disabled.png");
}
table.dataTable tbody tr {
  background-color: #ffffff;
}
table.dataTable tbody tr.selected {
  background-color: #B0BED9;
}
table.dataTable tbody th,
table.dataTable tbody td {
  padding: 3px 5px;
}
table.dataTable.row-border tbody th, table.dataTable.row-border tbody td, table.dataTable.display tbody th, table.dataTable.display tbody td {
  border-top: 1px solid #ddd;
}
table.dataTable.row-border tbody tr:first-child th,
table.dataTable.row-border tbody tr:first-child td, table.dataTable.display tbody tr:first-child th,
table.dataTable.display tbody tr:first-child td {
  border-top: none;
}
table.dataTable.cell-border tbody th, table.dataTable.cell-border tbody td {
  border-top: 1px solid #ddd;
  border-right: 1px solid #ddd;
}
table.dataTable.cell-border tbody tr th:first-child,
table.dataTable.cell-border tbody tr td:first-child {
  border-left: 1px solid #ddd;
}
table.dataTable.cell-border tbody tr:first-child th,
table.dataTable.cell-border tbody tr:first-child td {
  border-top: none;
}
table.dataTable.stripe tbody tr.odd, table.dataTable.display tbody tr.odd {
  background-color: #f9f9f9;
}
table.dataTable.stripe tbody tr.odd.selected, table.dataTable.display tbody tr.odd.selected {
  background-color: #acbad4;
}
table.dataTable.hover tbody tr:hover, table.dataTable.display tbody tr:hover {
  background-color: #f6f6f6;
}
table.dataTable.hover tbody tr:hover.selected, table.dataTable.display tbody tr:hover.selected {
  background-color: #aab7d1;
}
table.dataTable.order-column tbody tr > .sorting_1,
table.dataTable.order-column tbody tr > .sorting_2,
table.dataTable.order-column tbody tr > .sorting_3, table.dataTable.display tbody tr > .sorting_1,
table.dataTable.display tbody tr > .sorting_2,
table.dataTable.display tbody tr > .sorting_3 {
  background-color: #fafafa;
}
table.dataTable.order-column tbody tr.selected > .sorting_1,
table.dataTable.order-column tbody tr.selected > .sorting_2,
table.dataTable.order-column tbody tr.selected > .sorting_3, table.dataTable.display tbody tr.selected > .sorting_1,
table.dataTable.display tbody tr.selected > .sorting_2,
table.dataTable.display tbody tr.selected > .sorting_3 {
  background-color: #acbad5;
}
table.dataTable.display tbody tr.odd > .sorting_1, table.dataTable.order-column.stripe tbody tr.odd > .sorting_1 {
  background-color: #f1f1f1;
}
table.dataTable.display tbody tr.odd > .sorting_2, table.dataTable.order-column.stripe tbody tr.odd > .sorting_2 {
  background-color: #f3f3f3;
}
table.dataTable.display tbody tr.odd > .sorting_3, table.dataTable.order-column.stripe tbody tr.odd > .sorting_3 {
  background-color: whitesmoke;
}
table.dataTable.display tbody tr.odd.selected > .sorting_1, table.dataTable.order-column.stripe tbody tr.odd.selected > .sorting_1 {
  background-color: #a6b4cd;
}
table.dataTable.display tbody tr.odd.selected > .sorting_2, table.dataTable.order-column.stripe tbody tr.odd.selected > .sorting_2 {
  background-color: #a8b5cf;
}
table.dataTable.display tbody tr.odd.selected > .sorting_3, table.dataTable.order-column.stripe tbody tr.odd.selected > .sorting_3 {
  background-color: #a9b7d1;
}
table.dataTable.display tbody tr.even > .sorting_1, table.dataTable.order-column.stripe tbody tr.even > .sorting_1 {
  background-color: #fafafa;
}
table.dataTable.display tbody tr.even > .sorting_2, table.dataTable.order-column.stripe tbody tr.even > .sorting_2 {
  background-color: #fcfcfc;
}
table.dataTable.display tbody tr.even > .sorting_3, table.dataTable.order-column.stripe tbody tr.even > .sorting_3 {
  background-color: #fefefe;
}
table.dataTable.display tbody tr.even.selected > .sorting_1, table.dataTable.order-column.stripe tbody tr.even.selected > .sorting_1 {
  background-color: #acbad5;
}
table.dataTable.display tbody tr.even.selected > .sorting_2, table.dataTable.order-column.stripe tbody tr.even.selected > .sorting_2 {
  background-color: #aebcd6;
}
table.dataTable.display tbody tr.even.selected > .sorting_3, table.dataTable.order-column.stripe tbody tr.even.selected > .sorting_3 {
  background-color: #afbdd8;
}
table.dataTable.display tbody tr:hover > .sorting_1, table.dataTable.order-column.hover tbody tr:hover > .sorting_1 {
  background-color: #eaeaea;
}
table.dataTable.display tbody tr:hover > .sorting_2, table.dataTable.order-column.hover tbody tr:hover > .sorting_2 {
  background-color: #ececec;
}
table.dataTable.display tbody tr:hover > .sorting_3, table.dataTable.order-column.hover tbody tr:hover > .sorting_3 {
  background-color: #efefef;
}
table.dataTable.display tbody tr:hover.selected > .sorting_1, table.dataTable.order-column.hover tbody tr:hover.selected > .sorting_1 {
  background-color: #a2aec7;
}
table.dataTable.display tbody tr:hover.selected > .sorting_2, table.dataTable.order-column.hover tbody tr:hover.selected > .sorting_2 {
  background-color: #a3b0c9;
}
table.dataTable.display tbody tr:hover.selected > .sorting_3, table.dataTable.order-column.hover tbody tr:hover.selected > .sorting_3 {
  background-color: #a5b2cb;
}
table.dataTable.no-footer {
  border-bottom: 1px solid #111;
}
table.dataTable.nowrap th, table.dataTable.nowrap td {
  white-space: nowrap;
}
table.dataTable.compact thead th,
table.dataTable.compact thead td {
  padding: 4px 17px 4px 4px;
}
table.dataTable.compact tfoot th,
table.dataTable.compact tfoot td {
  padding: 4px;
}
table.dataTable.compact tbody th,
table.dataTable.compact tbody td {
  padding: 4px;
}
table.dataTable th.dt-left,
table.dataTable td.dt-left {
  text-align: left;
}
table.dataTable th.dt-center,
table.dataTable td.dt-center,
table.dataTable td.dataTables_empty {
  text-align: center;
}
table.dataTable th.dt-right,
table.dataTable td.dt-right {
  text-align: right;
}
table.dataTable th.dt-justify,
table.dataTable td.dt-justify {
  text-align: justify;
}
table.dataTable th.dt-nowrap,
table.dataTable td.dt-nowrap {
  white-space: nowrap;
}
table.dataTable thead th.dt-head-left,
table.dataTable thead td.dt-head-left,
table.dataTable tfoot th.dt-head-left,
table.dataTable tfoot td.dt-head-left {
  text-align: left;
}
table.dataTable thead th.dt-head-center,
table.dataTable thead td.dt-head-center,
table.dataTable tfoot th.dt-head-center,
table.dataTable tfoot td.dt-head-center {
  text-align: center;
}
table.dataTable thead th.dt-head-right,
table.dataTable thead td.dt-head-right,
table.dataTable tfoot th.dt-head-right,
table.dataTable tfoot td.dt-head-right {
  text-align: right;
}
table.dataTable thead th.dt-head-justify,
table.dataTable thead td.dt-head-justify,
table.dataTable tfoot th.dt-head-justify,
table.dataTable tfoot td.dt-head-justify {
  text-align: justify;
}
table.dataTable thead th.dt-head-nowrap,
table.dataTable thead td.dt-head-nowrap,
table.dataTable tfoot th.dt-head-nowrap,
table.dataTable tfoot td.dt-head-nowrap {
  white-space: nowrap;
}
table.dataTable tbody th.dt-body-left,
table.dataTable tbody td.dt-body-left {
  text-align: left;
}
table.dataTable tbody th.dt-body-center,
table.dataTable tbody td.dt-body-center {
  text-align: center;
}
table.dataTable tbody th.dt-body-right,
table.dataTable tbody td.dt-body-right {
  text-align: right;
}
table.dataTable tbody th.dt-body-justify,
table.dataTable tbody td.dt-body-justify {
  text-align: justify;
}
table.dataTable tbody th.dt-body-nowrap,
table.dataTable tbody td.dt-body-nowrap {
  white-space: nowrap;
}

table.dataTable,
table.dataTable th,
table.dataTable td {
  box-sizing: content-box;
}

/*
 * Control feature layout
 */
.dataTables_wrapper {
  position: relative;
  clear: both;
  *zoom: 1;
  zoom: 1;
}
.dataTables_wrapper .dataTables_length {
  float: left;
}
.dataTables_wrapper .dataTables_filter {
  float: right;
  text-align: right;
}
.dataTables_wrapper .dataTables_filter input {
  margin-left: 0.5em;
}
.dataTables_wrapper .dataTables_info {
  clear: both;
  float: left;
  padding-top: 0.755em;
}
.dataTables_wrapper .dataTables_paginate {
  float: right;
  text-align: right;
  padding-top: 0.25em;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
  box-sizing: border-box;
  display: inline-block;
  min-width: 1.5em;
  padding: 0.5em 1em;
  margin-left: 2px;
  text-align: center;
  text-decoration: none !important;
  cursor: pointer;
  *cursor: hand;
  color: #333 !important;
  border: 1px solid transparent;
  border-radius: 2px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
  color: #333 !important;
  border: 1px solid #979797;
  background-color: white;
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(100%, #dcdcdc));
  /* Chrome,Safari4+ */
  background: -webkit-linear-gradient(top, white 0%, #dcdcdc 100%);
  /* Chrome10+,Safari5.1+ */
  background: -moz-linear-gradient(top, white 0%, #dcdcdc 100%);
  /* FF3.6+ */
  background: -ms-linear-gradient(top, white 0%, #dcdcdc 100%);
  /* IE10+ */
  background: -o-linear-gradient(top, white 0%, #dcdcdc 100%);
  /* Opera 11.10+ */
  background: linear-gradient(to bottom, white 0%, #dcdcdc 100%);
  /* W3C */
}
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
  cursor: default;
  color: #666 !important;
  border: 1px solid transparent;
  background: transparent;
  box-shadow: none;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
  color: white !important;
  border: 1px solid #111;
  background-color: #585858;
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #585858), color-stop(100%, #111));
  /* Chrome,Safari4+ */
  background: -webkit-linear-gradient(top, #585858 0%, #111 100%);
  /* Chrome10+,Safari5.1+ */
  background: -moz-linear-gradient(top, #585858 0%, #111 100%);
  /* FF3.6+ */
  background: -ms-linear-gradient(top, #585858 0%, #111 100%);
  /* IE10+ */
  background: -o-linear-gradient(top, #585858 0%, #111 100%);
  /* Opera 11.10+ */
  background: linear-gradient(to bottom, #585858 0%, #111 100%);
  /* W3C */
}
.dataTables_wrapper .dataTables_paginate .paginate_button:active {
  outline: none;
  background-color: #2b2b2b;
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #2b2b2b), color-stop(100%, #0c0c0c));
  /* Chrome,Safari4+ */
  background: -webkit-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);
  /* Chrome10+,Safari5.1+ */
  background: -moz-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);
  /* FF3.6+ */
  background: -ms-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);
  /* IE10+ */
  background: -o-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);
  /* Opera 11.10+ */
  background: linear-gradient(to bottom, #2b2b2b 0%, #0c0c0c 100%);
  /* W3C */
  box-shadow: inset 0 0 3px #111;
}
.dataTables_wrapper .dataTables_paginate .ellipsis {
  padding: 0 1em;
}
.dataTables_wrapper .dataTables_processing {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 100%;
  height: 40px;
  margin-left: -50%;
  margin-top: -25px;
  padding-top: 20px;
  text-align: center;
  font-size: 1.2em;
  background-color: white;
  background: -webkit-gradient(linear, left top, right top, color-stop(0%, rgba(255, 255, 255, 0)), color-stop(25%, rgba(255, 255, 255, 0.9)), color-stop(75%, rgba(255, 255, 255, 0.9)), color-stop(100%, rgba(255, 255, 255, 0)));
  background: -webkit-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);
  background: -moz-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);
  background: -ms-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);
  background: -o-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);
  background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);
}
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_processing,
.dataTables_wrapper .dataTables_paginate {
  color: #333;
}
.dataTables_wrapper .dataTables_scroll {
  clear: both;
}
.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody {
  *margin-top: -1px;
  -webkit-overflow-scrolling: touch;
}
.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody > table > thead > tr > th, .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody > table > thead > tr > td, .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody > table > tbody > tr > th, .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody > table > tbody > tr > td {
  vertical-align: middle;
}
.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody > table > thead > tr > th > div.dataTables_sizing,
.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody > table > thead > tr > td > div.dataTables_sizing, .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody > table > tbody > tr > th > div.dataTables_sizing,
.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody > table > tbody > tr > td > div.dataTables_sizing {
  height: 0;
  overflow: hidden;
  margin: 0 !important;
  padding: 0 !important;
}
.dataTables_wrapper.no-footer .dataTables_scrollBody {
  border-bottom: 1px solid #111;
}
.dataTables_wrapper.no-footer div.dataTables_scrollHead table.dataTable,
.dataTables_wrapper.no-footer div.dataTables_scrollBody > table {
  border-bottom: none;
}
.dataTables_wrapper:after {
  visibility: hidden;
  display: block;
  content: "";
  clear: both;
  height: 0;
}

@media screen and (max-width: 767px) {
  .dataTables_wrapper .dataTables_info,
  .dataTables_wrapper .dataTables_paginate {
    float: none;
    text-align: center;
  }
  .dataTables_wrapper .dataTables_paginate {
    margin-top: 0.5em;
  }
}
@media screen and (max-width: 640px) {
  .dataTables_wrapper .dataTables_length,
  .dataTables_wrapper .dataTables_filter {
    float: none;
    text-align: center;
  }
  .dataTables_wrapper .dataTables_filter {
    margin-top: 0.5em;
  }
}


@keyframes dtb-spinner {
  100% {
    transform: rotate(360deg);
  }
}
@-o-keyframes dtb-spinner {
  100% {
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-ms-keyframes dtb-spinner {
  100% {
    -ms-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-webkit-keyframes dtb-spinner {
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes dtb-spinner {
  100% {
    -moz-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
div.dt-button-info {
  position: fixed;
  top: 50%;
  left: 50%;
  width: 400px;
  margin-top: -100px;
  margin-left: -200px;
  background-color: white;
  border: 2px solid #111;
  box-shadow: 3px 3px 8px rgba(0, 0, 0, 0.3);
  border-radius: 3px;
  text-align: center;
  z-index: 21;
}
div.dt-button-info h2 {
  padding: 0.5em;
  margin: 0;
  font-weight: normal;
  border-bottom: 1px solid #ddd;
  background-color: #f3f3f3;
}
div.dt-button-info > div {
  padding: 1em;
}

button.dt-button,
div.dt-button,
a.dt-button {
  position: relative;
  display: inline-block;
  box-sizing: border-box;
  margin-right: 0.333em;
  margin-bottom: 0.333em;
  padding: 0.5em 1em;
  border: 1px solid #999;
  border-radius: 2px;
  cursor: pointer;
  font-size: 0.88em;
  line-height: 1.6em;
  color: black;
  white-space: nowrap;
  overflow: hidden;
  background-color: #e9e9e9;
  /* Fallback */
  background-image: -webkit-linear-gradient(top, white 0%, #e9e9e9 100%);
  /* Chrome 10+, Saf5.1+, iOS 5+ */
  background-image: -moz-linear-gradient(top, white 0%, #e9e9e9 100%);
  /* FF3.6 */
  background-image: -ms-linear-gradient(top, white 0%, #e9e9e9 100%);
  /* IE10 */
  background-image: -o-linear-gradient(top, white 0%, #e9e9e9 100%);
  /* Opera 11.10+ */
  background-image: linear-gradient(to bottom, white 0%, #e9e9e9 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='white', EndColorStr='#e9e9e9');
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  text-decoration: none;
  outline: none;
}
button.dt-button.disabled,
div.dt-button.disabled,
a.dt-button.disabled {
  color: #999;
  border: 1px solid #d0d0d0;
  cursor: default;
  background-color: #f9f9f9;
  /* Fallback */
  background-image: -webkit-linear-gradient(top, #ffffff 0%, #f9f9f9 100%);
  /* Chrome 10+, Saf5.1+, iOS 5+ */
  background-image: -moz-linear-gradient(top, #ffffff 0%, #f9f9f9 100%);
  /* FF3.6 */
  background-image: -ms-linear-gradient(top, #ffffff 0%, #f9f9f9 100%);
  /* IE10 */
  background-image: -o-linear-gradient(top, #ffffff 0%, #f9f9f9 100%);
  /* Opera 11.10+ */
  background-image: linear-gradient(to bottom, #ffffff 0%, #f9f9f9 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='#ffffff', EndColorStr='#f9f9f9');
}
button.dt-button:active:not(.disabled), button.dt-button.active:not(.disabled),
div.dt-button:active:not(.disabled),
div.dt-button.active:not(.disabled),
a.dt-button:active:not(.disabled),
a.dt-button.active:not(.disabled) {
  background-color: #e2e2e2;
  /* Fallback */
  background-image: -webkit-linear-gradient(top, #f3f3f3 0%, #e2e2e2 100%);
  /* Chrome 10+, Saf5.1+, iOS 5+ */
  background-image: -moz-linear-gradient(top, #f3f3f3 0%, #e2e2e2 100%);
  /* FF3.6 */
  background-image: -ms-linear-gradient(top, #f3f3f3 0%, #e2e2e2 100%);
  /* IE10 */
  background-image: -o-linear-gradient(top, #f3f3f3 0%, #e2e2e2 100%);
  /* Opera 11.10+ */
  background-image: linear-gradient(to bottom, #f3f3f3 0%, #e2e2e2 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='#f3f3f3', EndColorStr='#e2e2e2');
  box-shadow: inset 1px 1px 3px #999999;
}
button.dt-button:active:not(.disabled):hover:not(.disabled), button.dt-button.active:not(.disabled):hover:not(.disabled),
div.dt-button:active:not(.disabled):hover:not(.disabled),
div.dt-button.active:not(.disabled):hover:not(.disabled),
a.dt-button:active:not(.disabled):hover:not(.disabled),
a.dt-button.active:not(.disabled):hover:not(.disabled) {
  box-shadow: inset 1px 1px 3px #999999;
  background-color: #cccccc;
  /* Fallback */
  background-image: -webkit-linear-gradient(top, #eaeaea 0%, #cccccc 100%);
  /* Chrome 10+, Saf5.1+, iOS 5+ */
  background-image: -moz-linear-gradient(top, #eaeaea 0%, #cccccc 100%);
  /* FF3.6 */
  background-image: -ms-linear-gradient(top, #eaeaea 0%, #cccccc 100%);
  /* IE10 */
  background-image: -o-linear-gradient(top, #eaeaea 0%, #cccccc 100%);
  /* Opera 11.10+ */
  background-image: linear-gradient(to bottom, #eaeaea 0%, #cccccc 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='#eaeaea', EndColorStr='#cccccc');
}
button.dt-button:hover,
div.dt-button:hover,
a.dt-button:hover {
  text-decoration: none;
}
button.dt-button:hover:not(.disabled),
div.dt-button:hover:not(.disabled),
a.dt-button:hover:not(.disabled) {
  border: 1px solid #666;
  background-color: #e0e0e0;
  /* Fallback */
  background-image: -webkit-linear-gradient(top, #f9f9f9 0%, #e0e0e0 100%);
  /* Chrome 10+, Saf5.1+, iOS 5+ */
  background-image: -moz-linear-gradient(top, #f9f9f9 0%, #e0e0e0 100%);
  /* FF3.6 */
  background-image: -ms-linear-gradient(top, #f9f9f9 0%, #e0e0e0 100%);
  /* IE10 */
  background-image: -o-linear-gradient(top, #f9f9f9 0%, #e0e0e0 100%);
  /* Opera 11.10+ */
  background-image: linear-gradient(to bottom, #f9f9f9 0%, #e0e0e0 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='#f9f9f9', EndColorStr='#e0e0e0');
}
button.dt-button:focus:not(.disabled),
div.dt-button:focus:not(.disabled),
a.dt-button:focus:not(.disabled) {
  border: 1px solid #426c9e;
  text-shadow: 0 1px 0 #c4def1;
  outline: none;
  background-color: #79ace9;
  /* Fallback */
  background-image: -webkit-linear-gradient(top, #bddef4 0%, #79ace9 100%);
  /* Chrome 10+, Saf5.1+, iOS 5+ */
  background-image: -moz-linear-gradient(top, #bddef4 0%, #79ace9 100%);
  /* FF3.6 */
  background-image: -ms-linear-gradient(top, #bddef4 0%, #79ace9 100%);
  /* IE10 */
  background-image: -o-linear-gradient(top, #bddef4 0%, #79ace9 100%);
  /* Opera 11.10+ */
  background-image: linear-gradient(to bottom, #bddef4 0%, #79ace9 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='#bddef4', EndColorStr='#79ace9');
}

.dt-button embed {
  outline: none;
}

div.dt-buttons {
  position: relative;
  float: left;
}
div.dt-buttons.buttons-right {
  float: right;
}

div.dt-button-collection {
  position: absolute;
  top: 0;
  left: 0;
  width: 150px;
  margin-top: 3px;
  padding: 8px 8px 4px 8px;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.4);
  background-color: white;
  overflow: hidden;
  z-index: 2002;
  border-radius: 5px;
  box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);
  -webkit-column-gap: 8px;
  -moz-column-gap: 8px;
  -ms-column-gap: 8px;
  -o-column-gap: 8px;
  column-gap: 8px;
}
div.dt-button-collection button.dt-button,
div.dt-button-collection div.dt-button,
div.dt-button-collection a.dt-button {
  position: relative;
  left: 0;
  right: 0;
  width: 100%;
  display: block;
  float: none;
  margin-bottom: 4px;
  margin-right: 0;
}
div.dt-button-collection button.dt-button:active:not(.disabled), div.dt-button-collection button.dt-button.active:not(.disabled),
div.dt-button-collection div.dt-button:active:not(.disabled),
div.dt-button-collection div.dt-button.active:not(.disabled),
div.dt-button-collection a.dt-button:active:not(.disabled),
div.dt-button-collection a.dt-button.active:not(.disabled) {
  background-color: #dadada;
  /* Fallback */
  background-image: -webkit-linear-gradient(top, #f0f0f0 0%, #dadada 100%);
  /* Chrome 10+, Saf5.1+, iOS 5+ */
  background-image: -moz-linear-gradient(top, #f0f0f0 0%, #dadada 100%);
  /* FF3.6 */
  background-image: -ms-linear-gradient(top, #f0f0f0 0%, #dadada 100%);
  /* IE10 */
  background-image: -o-linear-gradient(top, #f0f0f0 0%, #dadada 100%);
  /* Opera 11.10+ */
  background-image: linear-gradient(to bottom, #f0f0f0 0%, #dadada 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='#f0f0f0', EndColorStr='#dadada');
  box-shadow: inset 1px 1px 3px #666;
}
div.dt-button-collection.fixed {
  position: fixed;
  top: 50%;
  left: 50%;
  margin-left: -75px;
  border-radius: 0;
}
div.dt-button-collection.fixed.two-column {
  margin-left: -150px;
}
div.dt-button-collection.fixed.three-column {
  margin-left: -225px;
}
div.dt-button-collection.fixed.four-column {
  margin-left: -300px;
}
div.dt-button-collection > * {
  -webkit-column-break-inside: avoid;
  break-inside: avoid;
}
div.dt-button-collection.two-column {
  width: 300px;
  padding-bottom: 1px;
  -webkit-column-count: 2;
  -moz-column-count: 2;
  -ms-column-count: 2;
  -o-column-count: 2;
  column-count: 2;
}
div.dt-button-collection.three-column {
  width: 450px;
  padding-bottom: 1px;
  -webkit-column-count: 3;
  -moz-column-count: 3;
  -ms-column-count: 3;
  -o-column-count: 3;
  column-count: 3;
}
div.dt-button-collection.four-column {
  width: 600px;
  padding-bottom: 1px;
  -webkit-column-count: 4;
  -moz-column-count: 4;
  -ms-column-count: 4;
  -o-column-count: 4;
  column-count: 4;
}
div.dt-button-collection .dt-button {
  border-radius: 0;
}

div.dt-button-background {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  /* Fallback */
  background: -ms-radial-gradient(center, ellipse farthest-corner, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.7) 100%);
  /* IE10 Consumer Preview */
  background: -moz-radial-gradient(center, ellipse farthest-corner, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.7) 100%);
  /* Firefox */
  background: -o-radial-gradient(center, ellipse farthest-corner, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.7) 100%);
  /* Opera */
  background: -webkit-gradient(radial, center center, 0, center center, 497, color-stop(0, rgba(0, 0, 0, 0.3)), color-stop(1, rgba(0, 0, 0, 0.7)));
  /* Webkit (Safari/Chrome 10) */
  background: -webkit-radial-gradient(center, ellipse farthest-corner, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.7) 100%);
  /* Webkit (Chrome 11+) */
  background: radial-gradient(ellipse farthest-corner at center, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.7) 100%);
  /* W3C Markup, IE10 Release Preview */
  z-index: 2001;
}

@media screen and (max-width: 640px) {
  div.dt-buttons {
    float: none !important;
    text-align: center;
  }
}
button.dt-button.processing,
div.dt-button.processing,
a.dt-button.processing {
  color: rgba(0, 0, 0, 0.2);
}
button.dt-button.processing:after,
div.dt-button.processing:after,
a.dt-button.processing:after {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 16px;
  height: 16px;
  margin: -8px 0 0 -8px;
  box-sizing: border-box;
  display: block;
  content: ' ';
  border: 2px solid #282828;
  border-radius: 50%;
  border-left-color: transparent;
  border-right-color: transparent;
  animation: dtb-spinner 1500ms infinite linear;
  -o-animation: dtb-spinner 1500ms infinite linear;
  -ms-animation: dtb-spinner 1500ms infinite linear;
  -webkit-animation: dtb-spinner 1500ms infinite linear;
  -moz-animation: dtb-spinner 1500ms infinite linear;
}


table.DTFC_Cloned thead,
table.DTFC_Cloned tfoot {
  background-color: white;
}

div.DTFC_Blocker {
  background-color: white;
}

div.DTFC_LeftWrapper table.dataTable,
div.DTFC_RightWrapper table.dataTable {
  margin-bottom: 0;
  z-index: 2;
}
div.DTFC_LeftWrapper table.dataTable.no-footer,
div.DTFC_RightWrapper table.dataTable.no-footer {
  border-bottom: none;
}


table.fixedHeader-floating {
  position: fixed !important;
  background-color: white;
}

table.fixedHeader-floating.no-footer {
  border-bottom-width: 0;
}

table.fixedHeader-locked {
  position: absolute !important;
  background-color: white;
}

@media print {
  table.fixedHeader-floating {
    display: none;
  }
}


table.dataTable tbody th.focus,
table.dataTable tbody td.focus {
  box-shadow: inset 0 0 1px 2px #3366FF;
}


table.dataTable.dtr-inline.collapsed > tbody > tr > td.child,
table.dataTable.dtr-inline.collapsed > tbody > tr > th.child,
table.dataTable.dtr-inline.collapsed > tbody > tr > td.dataTables_empty {
  cursor: default !important;
}
table.dataTable.dtr-inline.collapsed > tbody > tr > td.child:before,
table.dataTable.dtr-inline.collapsed > tbody > tr > th.child:before,
table.dataTable.dtr-inline.collapsed > tbody > tr > td.dataTables_empty:before {
  display: none !important;
}
table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child,
table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child {
  position: relative;
  padding-left: 30px;
  cursor: pointer;
}
table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before,
table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
  top: 9px;
  left: 4px;
  height: 14px;
  width: 14px;
  display: block;
  position: absolute;
  color: white;
  border: 2px solid white;
  border-radius: 14px;
  box-shadow: 0 0 3px #444;
  box-sizing: content-box;
  text-align: center;
  text-indent: 0 !important;
  font-family: 'Courier New', Courier, monospace;
  line-height: 14px;
  content: '+';
  background-color: #31b131;
}
table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td:first-child:before,
table.dataTable.dtr-inline.collapsed > tbody > tr.parent > th:first-child:before {
  content: '-';
  background-color: #d33333;
}
table.dataTable.dtr-inline.collapsed.compact > tbody > tr > td:first-child,
table.dataTable.dtr-inline.collapsed.compact > tbody > tr > th:first-child {
  padding-left: 27px;
}
table.dataTable.dtr-inline.collapsed.compact > tbody > tr > td:first-child:before,
table.dataTable.dtr-inline.collapsed.compact > tbody > tr > th:first-child:before {
  top: 5px;
  left: 4px;
  height: 14px;
  width: 14px;
  border-radius: 14px;
  line-height: 14px;
  text-indent: 3px;
}
table.dataTable.dtr-column > tbody > tr > td.control,
table.dataTable.dtr-column > tbody > tr > th.control {
  position: relative;
  cursor: pointer;
}
table.dataTable.dtr-column > tbody > tr > td.control:before,
table.dataTable.dtr-column > tbody > tr > th.control:before {
  top: 50%;
  left: 50%;
  height: 16px;
  width: 16px;
  margin-top: -10px;
  margin-left: -10px;
  display: block;
  position: absolute;
  color: white;
  border: 2px solid white;
  border-radius: 14px;
  box-shadow: 0 0 3px #444;
  box-sizing: content-box;
  text-align: center;
  text-indent: 0 !important;
  font-family: 'Courier New', Courier, monospace;
  line-height: 14px;
  content: '+';
  background-color: #31b131;
}
table.dataTable.dtr-column > tbody > tr.parent td.control:before,
table.dataTable.dtr-column > tbody > tr.parent th.control:before {
  content: '-';
  background-color: #d33333;
}
table.dataTable > tbody > tr.child {
  padding: 0.5em 1em;
}
table.dataTable > tbody > tr.child:hover {
  background: transparent !important;
}
table.dataTable > tbody > tr.child ul.dtr-details {
  display: inline-block;
  list-style-type: none;
  margin: 0;
  padding: 0;
}
table.dataTable > tbody > tr.child ul.dtr-details > li {
  border-bottom: 1px solid #efefef;
  padding: 0.5em 0;
}
table.dataTable > tbody > tr.child ul.dtr-details > li:first-child {
  padding-top: 0;
}
table.dataTable > tbody > tr.child ul.dtr-details > li:last-child {
  border-bottom: none;
}
table.dataTable > tbody > tr.child span.dtr-title {
  display: inline-block;
  min-width: 75px;
  font-weight: bold;
}

div.dtr-modal {
  position: fixed;
  box-sizing: border-box;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  z-index: 100;
  padding: 10em 1em;
}
div.dtr-modal div.dtr-modal-display {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  width: 50%;
  height: 50%;
  overflow: auto;
  margin: auto;
  z-index: 102;
  overflow: auto;
  background-color: #f5f5f7;
  border: 1px solid black;
  border-radius: 0.5em;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6);
}
div.dtr-modal div.dtr-modal-content {
  position: relative;
  padding: 1em;
}
div.dtr-modal div.dtr-modal-close {
  position: absolute;
  top: 6px;
  right: 6px;
  width: 22px;
  height: 22px;
  border: 1px solid #eaeaea;
  background-color: #f9f9f9;
  text-align: center;
  border-radius: 3px;
  cursor: pointer;
  z-index: 12;
}
div.dtr-modal div.dtr-modal-close:hover {
  background-color: #eaeaea;
}
div.dtr-modal div.dtr-modal-background {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 101;
  background: rgba(0, 0, 0, 0.6);
}

@media screen and (max-width: 767px) {
  div.dtr-modal div.dtr-modal-display {
    width: 95%;
  }
}


div.DTS {
  display: block !important;
}
div.DTS tbody th,
div.DTS tbody td {
  white-space: nowrap;
}
div.DTS div.DTS_Loading {
  z-index: 1;
}
div.DTS div.dataTables_scrollBody {
  background: repeating-linear-gradient(45deg, #edeeff, #edeeff 10px, white 10px, white 20px);
}
div.DTS div.dataTables_scrollBody table {
  z-index: 2;
}
div.DTS div.dataTables_paginate,
div.DTS div.dataTables_length {
  display: none;
}


