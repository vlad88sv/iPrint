<?php
require_once ("../lib/vital.php");

// Cambiar el nombre de un material
if ( _F_usuario_cache('nivel') == _N_administrador && isset($_GET['id_material']) && isset($_GET['material']) ){
$Id_material = mysql_real_escape_string($_GET['id_material']);
$Material = mysql_real_escape_string($_GET['material']);
$c = "UPDATE ahm_materiales SET material='$Material' WHERE id_material='$Id_material'";
$resultado = db_consultar($c);
if ( $resultado ) {
    echo "Nombre exitosamente cambiado a '$Material' para material N° $Id_material";
} else {
    echo "No pudo cambiarse el nombre del material N° $Id_material";
}
}

// Cambiar el estado de actividad de un material
if ( _F_usuario_cache('nivel') == _N_administrador && isset($_GET['id_material']) && isset($_GET['activo']) ){
$Id_material = mysql_real_escape_string($_GET['id_material']);
$Activo = mysql_real_escape_string($_GET['activo']) == "true" ? "1" : "0";
$c = "UPDATE ahm_materiales SET activo='$Activo' WHERE id_material='$Id_material'";
$resultado = db_consultar($c);
if ( $resultado ) {
    echo "Actividad exitosamente cambiada a '$Activo' para material N° $Id_material";
} else {
    echo "No pudo cambiarse la actividad para material N° $Id_material";
}
}

?>
