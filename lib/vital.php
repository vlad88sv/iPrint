<?php
// Este archivo se encargará de levantar la sesión y la conexión a la base de datos.
error_reporting(E_STRICT | E_ALL);
ob_start("ob_gzhandler");
setlocale(LC_ALL, 'es_AR.UTF-8', 'es_ES.UTF-8');
date_default_timezone_set ('America/El_Salvador');
$base = dirname(__FILE__);
ini_set('session.gc_maxlifetime', '600');
require_once ("$base/const.php"); // Constantes
require_once ("$base/sesion.php"); // Sesión
require_once ("$base/secreto.php"); // Datos para la conexión a la base de datos
require_once ("$base/db.php"); // Conexión hacia la base de datos
require_once ("$base/ui.php"); // Generación de HTML: Comboboxes, etc.
require_once ("$base/stubs.php"); // Gestión de usuarios
require_once ("$base/usuario.php"); // Gestión de usuarios
require_once ("$base/todosv.com.php");

function DEPURAR($s,$f=0){if($f){echo '<pre>'.$s.'</pre><br />';}}

function print_ar($array, $count=0) {
    $k = $i = 0;
    $data = $tab = '';
    while($i != $count) {
        $i++;
        $tab .= "&nbsp;&nbsp;|&nbsp;&nbsp;";
    }
    foreach($array as $key=>$value){
        if(is_array($value)){
            $data .= $tab."[$key]<br />";
            $count++;
            $data .= print_ar($value, $count);
            $count--;
        }
        else{
            $tab2 = substr($tab, 0, -12);
            $data .= "$tab2~$key: $value<br />";
        }
        $k++;
    }
    $count--;
    return $data;
}

function interpretar_valor($valor, $array_valores){
	foreach($array_valores as $key=>$value)	if ($valor == $key) return $value;
	return $valor;
}

function retornarAjax($texto) {exit ('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . $texto);}

function siVacio($Prueba, $Reemplazo){if ($Prueba == '') {return $Reemplazo;} else {return $Prueba;}}
?>
