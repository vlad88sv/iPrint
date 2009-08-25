<?php
// Este archivo se encargará de levantar la sesión y la conexión a la base de datos.
error_reporting(E_STRICT | E_ALL);
ob_start("ob_gzhandler");
date_default_timezone_set ('America/El_Salvador');
$base = dirname(__FILE__);
ini_set('session.gc_maxlifetime', '600');
require_once ("$base/const.php"); // Constantes
require_once ("$base/sesion.php"); // Sesión
require_once ("$base/secreto.php"); // Datos para la conexión a la base de datos
require_once ("$base/db.php"); // Conexión hacia la base de datos
require_once ("$base/ui.php"); // Generación de HTML: Comboboxes, etc.
require_once ("$base/usuario.php"); // Gestión de usuarios

db_conectar(); // Iniciamos la conexión a la base de datos.


function DEPURAR($sTexto, $forzar=0){
	if ($forzar){echo '<pre>'.$sTexto.'</pre><br />';}
}

function print_ar($array, $count=0) {
    $i=0;
    $tab ='';
		$k=0;
    while($i != $count) {
        $i++;
        $tab .= "&nbsp;&nbsp;|&nbsp;&nbsp;";
    }
    foreach($array as $key=>$value){
        if(is_array($value)){
            echo $tab."[$key]<br />";
            $count++;
            print_ar($value, $count);
            $count--;
        }
        else{
            $tab2 = substr($tab, 0, -12);
            echo "$tab2~ $key: $value<br />";
        }
        $k++;
    }
    $count--;
}

function interpretar_valor($valor, $array_valores){
	foreach($array_valores as $key=>$value){
		if ($valor == $key) return $value;
	}
	return $valor;
}
?>
