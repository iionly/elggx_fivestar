<?php
?>

.ui-stars-star,
.ui-stars-cancel {
	float: left;
	display: block;
	overflow: hidden;
	text-indent: -999em;
	cursor: pointer;
}
.ui-stars-star a,
.ui-stars-cancel a {
	width: 16px;
	height: 15px;
	display: block;
	background: url(<?= elgg_get_simplecache_url('elggx_fivestar/ui_stars.gif'); ?>) no-repeat 0 0;
}
.ui-stars-star a {
	background-position: 0 -32px;
}
.ui-stars-star-on a {
	background-position: 0 -48px;
}
.ui-stars-star-hover a {
	background-position: 0 -64px;
}
.ui-stars-cancel-hover a {
	background-position: 0 -16px;
}
.ui-stars-star-disabled,
.ui-stars-star-disabled a,
.ui-stars-cancel-disabled a {
	cursor: default !important;
}
.fivestar-messages {
	margin-left: 1em;
	float: left;
	line-height: 15px;
	color: #fd1c24
}
#fivestar_izap_videos_list {
	float:right;
	font-size:xx-small;
	width:120px;
}
#fivestar_izap_videos_list p {
	margin:0;
	float:left;
}
fieldset.fivestar-collapsible {
	padding: 10px;
	border: 1px solid black;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-right-width: 1px;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	margin-bottom: 1em;
}
fieldset.fivestar-collapsed {
	border-bottom-width: 0;
	border-left-width: 0;
	border-right-width: 0;
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
	border-radius: 0px;
	margin-bottom: 0;
	margin-left: 3px;
}
legend.fivestar-collapsible {
	cursor: pointer;
	padding: 2px;
	color: blue;
	border: 1px solid black;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}
legend.fivestar-collapsed {
	cursor: pointer;
	padding: 2px;
	color:green;
	border: 1px solid black;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}
