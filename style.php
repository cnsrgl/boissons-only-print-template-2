<?php
/* @var $fontSize
 * @var $fontWeight
 * @var $headerSize
 * @var $headerWeight
 */

$css = <<<CSS
body {
	width: 100%;
	font-size: {$fontSize}px;
	font-weight: {$fontWeight};
	font-family: 'Arial', sans-serif;
	margin: 0;
	color: #003366;
}

@page {
	margin: 0;
}

* {
	-webkit-print-color-adjust: exact;
	max-width: 100%;
	box-sizing: border-box;
}

header {
	text-align: center;
	margin-bottom: 20px;
	width: 90%;
	margin-left: auto;
	margin-right: auto;
	border-bottom: 2px solid #003366;
	padding-bottom: 10px;
}

td, th {
	border-width: 0;
	border-style: solid;
	border-color: #003366;
}

.logo {
	width: auto;
	height: 60px;
	float: left;
	margin-right: 10px;
}

h1, h2, h3, h4, h5, h6, th {
	font-size: {$headerSize}px;
	font-weight: {$headerWeight};
	color: #003366;
}

header:after {
	content: '';
	display: block;
	width: 100%;
	clear: both;
}

table {
	width: 90%;
	border-collapse: collapse;
	font-size: inherit;
	margin-bottom: 20px;
	margin-left: auto;
	margin-right: auto;
}

h2.caption {
	width: 90%;
	display: block;
	margin-left: auto;
	margin-right: auto;
	text-align: left;
}

.info td, .info th {
	border: 1px solid #003366;
}

.info tfoot tr td {
	background: #e6f2ff;
	color: #003366;
	font-size: {$headerSize}px;
	font-weight: {$headerWeight};
	text-align: center;
}

.order tfoot tr, .order thead tr, .order tbody {
	border: 1px solid #003366;
}

.order tfoot td:first-child, .order thead th {
	text-transform: uppercase;
	font-weight: {$headerWeight};
}

.order tfoot td:first-child, .order thead th:first-child {
	text-align: left;
	font-weight: {$headerWeight};
	font-size: {$headerSize};
}

.order tfoot td:last-child {
	text-align: center;
}

.order tbody tr:first-child td:first-child {
	text-align: left;
}

.order tbody tr:first-child td {
	text-align: center;
}

.customer tr {
	border: 1px solid #003366;
}

.customer .base tr td:first-child {
	text-transform: uppercase;
}

.customer .base tr td {
	text-align: center;
}

.customer .notes tr {
	border-top: 0;
}

.customer .notes tr:first-child {
	border-bottom: 0;
	border-top: 1px solid #003366;
}

.customer .notes tr:first-child td {
	text-transform: uppercase;
	text-align: center;
}

header h1, header h2, header h3 {
	text-align: center;
	margin: 7px;
}

header h1 {
	margin-top: 0;
}

header h2.kitchen {
	line-height: 60px;
}

header h3 {
	margin-bottom: 0;
}

footer h4, h5 {
	text-align: center;
	margin: 0;
}

footer h5 {
	margin-top: 5px;
}

footer {
	margin-bottom: 20px;
	border-top: 1px solid #003366;
	padding-top: 10px;
	width: 90%;
	margin-left: auto;
	margin-right: auto;
}

table.customer:empty {
	display: none;
}

table.customer_details td, table.customer_details th {
	border: 1px solid #003366;
}

table.customer_details th {
	text-transform: uppercase;
}
CSS;
echo $css;
