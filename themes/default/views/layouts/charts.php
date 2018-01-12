<?php

	$cs = Yii::app()->clientScript;
	$cs->registerCssFile($this->getAssetsUrl().'/css/page.css');

	
	$cs->registerCoreScript('jquery');
	$cs->registerScriptFile($this->getAssetsUrl().'/charting_library/charting_library.min.js', CClientScript::POS_END);
	$cs->registerScriptFile($this->getAssetsUrl().'/charting_library/datafeed/udf/datafeed.js', CClientScript::POS_END);
	$cs->registerScriptFile($this->getAssetsUrl().'/js/page.js', CClientScript::POS_END);
?><!DOCTYPE html>
<html>
	<head>
		<title>TradePlayz Chart black</title>
		<!-- Fix for iOS Safari zooming bug -->
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	</head>

	<body style="margin:0px;">
		<? echo $content; ?>
	</body>

</html>
