<?php require_once ('lib/vital.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Impresiones·al·costo - I·Print</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="generator" content="vlad@todosv.com" />
	<meta name='author' content='Vladimir Hidalgo' />
	<meta name='copyright' content='2009 - Vladimir Hidalgo' />
	<link rel="stylesheet" type="text/css" href="estilo.css" />
	<?php
	if (isset($_SESSION['autenticado'])) {
	echo '<link rel="stylesheet" type="text/css" href="lib/menu/chromestyle.css" />';
	echo '<link rel="stylesheet" type="text/css" href="lib/themes/start/ui.all.css" />';
	echo '<link rel="stylesheet" type="text/css" href="lib/jquery.jgrowl.css" />';
	echo '<link rel="stylesheet" type="text/css" href="lib/themes/ui.combobox.css" />';
	echo '<style>div.jGrowl div.aviso {z-index:50;background-color: #FF0000;color: #FFFFFF;font-size:14pt;width:800px;}</style>';
	}
	?>
	<!--[if IE 6]>
	<style type="text/css">
	html { overflow-y: hidden; }
	body { overflow-y: auto; }
	#bg { position:absolute; z-index:-1; }
	#capa_impresora { position:static; z-index:0;background-image:url('img/Impresora2.gif');}
	</style>
	<![endif]-->
	<!--[if IE 7]>
	<style type="text/css">
	.chromestyle ul {padding-top:0;}
	</style>
	<![endif]-->
	<script type="text/javascript" src="lib/jquery.js"></script>
	<?php
	if (isset($_SESSION['autenticado'])) {
	echo '<script type="text/javascript" src="lib/ajaxfileupload.js"></script>';
	echo '<script type="text/javascript" src="lib/ui/ui.core.js"></script>';
	echo '<script type="text/javascript" src="lib/jquery.blockUI.js"></script>';
	echo '<script type="text/javascript" src="lib/jquery.jgrowl.js" ></script>';
	echo '<script type="text/javascript" src="lib/ui/ui.combobox.js"></script>';
	echo '<script type="text/javascript" src="lib/ui/ui.datepicker.js"></script>';
	echo '<script type="text/javascript" src="lib/menu/chrome.js"></script>';
	echo '<script>$().ajaxStart(function(){$.blockUI( { message: \'<h1><img src="loader.gif" /> Su petición esta siendo procesada...</h1>\' } );}).ajaxStop($.unblockUI);</script>';
	}
	?>
	<!--[if IE 6]>
	<script type="text/javascript" src="lib/jquery.pngFix.pack.js"></script>
	<script type="text/javascript">$(document).ready(function(){ $(document).pngFix(); });</script>
	<![endif]-->
</head>

<body>
	<?php
	// Mostrar el logotipo si esta autenticado.
	if (isset($_SESSION['autenticado'])) {
	echo '<div id="logo">';
	if (_F_usuario_cache('nivel') == _N_administrador) {
		$c = "SELECT id_orden FROM ahm_ordenes WHERE estado="._EO_nueva;
		$resultado = db_consultar($c);
		$OrdenesPendientes = $resultado ? mysql_num_rows($resultado): "#ERROR#";
		$c = "SELECT id_visita FROM ahm_visitas WHERE FechaVisita >= CURRENT_DATE";
		$resultado = db_consultar($c);
		$VisitasPendientes = $resultado ? mysql_num_rows($resultado): "#ERROR#";
		echo JS_growl("$OrdenesPendientes Ordenes pendientes y $VisitasPendientes Visitas pendientes");
	}
	echo '</div>';
	echo '<div id="menu">';
	require_once ('data/menu.php');
	echo '</div>';
	}
	?>
	<?php require_once ('data/body.php');?>
</body>
</html>
