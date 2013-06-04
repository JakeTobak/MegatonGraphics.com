<?php
    header("Content-type: text/css");
    include_once('assets/includes/csscolor.php');
?>
* {
	margin: 0;
	padding: 0;
	font-family: Helvetica, Arial, sans-serif;
}

body, html {
	height: 100%;
	margin: 0;
	padding: 0;
}

body {
	background: #333;
}

img {
	width: 100%;
}

p, h1, h2, h3, h4, h5, h6 {
	margin: 0.5em auto 0;
}

/* *********** *
 *  CONTAINER  *
 * *********** */

#container {
	width: 80%;
	min-width: 800px;
	margin: 0.5em auto;
	background: #fff;
	-moz-border-radius: 15px;
	border-radius: 15px;
	overflow: hidden;
}

/* ******** *
 *  HEADER  *
 * ******** */

header {
	background: #26b;
	overflow: auto;
	height: 4.5em;
	padding: 0;
	magin: 0;
}

header nav {
	height: 100%;
	float: right;
	margin: 0;
}

header img#mainlogo {
	width: 300px;
	padding: 1em;
	margin: 0;
	float: left;
}

header nav a {
	color: #fff;
	text-decoration: none;
	height: 1.5em;
	padding: 1.5em 1em;
	display: inline-block;
}

header nav a.current, header nav a:hover {
	color: #e94;
	padding-top: 1em;
	border-top: 0.5em solid #e94;
}

/* ******** *
 *  FOOTER  *
 * ******** */

footer {
	background: #888;
	color: #ccc;
	overflow: auto;
}

footer h3 {
	color: #fff;
	text-shadow: 2px 2px 0px #aaa;
}

footer #copyright {
	background: #444;
	padding: 1em;
	color: #fff;
	font-size: 0.75em;
}

#content {
	overflow: auto;
	padding: 1em 7.5%;
	box-shadow: 0 10px;
}

/* ******* *
 *  BOXES  *
 * ******* */

.box {
	overflow: auto;
}

/* icon boxes */

img.icon, img[src^="assets/icons"] {
	width: 4em;
	height: 4em;
	float: left;
	margin-right: 1em;
}

.box.icon {
	padding-top: 1em;
	margin: 0 auto;
}

.box.icon.short {
	width: 50%;
	float: left;
}

.box.icon.short:nth-of-type(odd) {
	clear: left;
}

.box.icon.short div p {
	color: #999;
	font-size: 0.8em;
	margin: 0 auto;
}

.box.icon.long {
	width: 100%;
	clear: both;
}

.box.icon.long img.icon, .box.icon.long img[src^="assets/icons"] {
	margin-top: 0.5em;
}

.box.icon.long div {
	background: #6aa;
	padding: 1em 2em;
}

.box.icon.long div h3 {
	color: #ddd;
	margin: 0;
}

.box.icon.long div p {
	text-indent: 5em;
	line-height: 1.8em;
}

/* vertical boxes */

.box.vertical {
	width: 30%;
	margin: 1em 1.5% 0;
	float: left;
}

.box.vertical:nth-of-type(3n+2) h2 {
	color: #b33;
	border-bottom: 2px solid #b33;
}

.box.vertical:nth-of-type(3n+3) h2 {
	color: #26b;
	border-bottom: 2px solid #26b;
}

.box.vertical:nth-of-type(3n+4) h2 {
	color: #694;
	border-bottom: 2px solid #694;
}

