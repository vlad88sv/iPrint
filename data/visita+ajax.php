<?php
require_once ("../lib/vital.php");
// ===================================================================
// Cambio de estado de orden
// -------------------------------------------------------------------
if ( _F_usuario_cache('nivel') == _N_administrador && isset($_GET['visita']) && isset($_GET['estado']) )
{
    $Visita  = mysql_real_escape_string($_GET['visita']);
    $Estado  = mysql_real_escape_string($_GET['estado']);
    $c       = "UPDATE ahm_visitas SET estado='$Estado' where id_visita='$Visita'";
    $resultado = db_consultar($c);
    if ($resultado)
    {
        echo "Se actualizó correctamente la visita.<br />Deberá recargar la tabla para poder ver los cambios";
    }
    else
    {
        echo "No se pudo actualizar el estado de la visita.";
    }
    return;
} /* Cambio de estado de orden */
// ===================================================================

// =====================================================================
//  Reservar una visita
// _____________________________________________________________________
if ( isset($_SESSION['autenticado']) && isset($_POST['fecha']) && isset($_POST['hora']) )
{
    $datos['validacion']    = rand(0,32500);
    $datos['id_usuario']    = _F_usuario_cache('id_usuario');
    $datos['FechaVisita']   = mysql_date($_POST['hora'] . " " . $_POST['fecha']);
    $datos['estado']        = _EV_nueva;
    $id_visita = db_agregar_datos('ahm_visitas', $datos);
    if ($id_visita)
    {
        echo "Visita reservada para el <b>".$datos['FechaVisita']."</b>.<br />Código de validación: <b>$id_visita+".$datos['validacion']."</b><br />";
        despachar_notificaciones("Nueva visita pendiente para el ".$datos['FechaVisita']. ". Hecha por ". _F_usuario_cache('nombre'));
    }
    return;
}
// =====================================================================

// =====================================================================
//  Tabla de selección de visita
// _____________________________________________________________________
if ( isset($_SESSION['autenticado']) && isset($_POST['fecha']) ) {
    $Fecha          = db_codex($_POST['fecha']);
    $FechaInicio    = mysql_date($Fecha);
    $FechaFin       = mysql_date($Fecha . " +1 day");

    // Obtenemos los visitas para ese día de ese usuario.
    $c = "SELECT CONCAT(id_visita, '+', validacion) AS validacion_2, FechaVisita FROM ahm_visitas WHERE (FechaVisita BETWEEN '$FechaInicio' AND '$FechaFin') AND (id_usuario='"._F_usuario_cache('id_usuario')."')";
    DEPURAR($c,0);
    $resultado = db_consultar($c);
    $n_filas = mysql_num_rows($resultado);
    if ($n_filas > 0){
        echo 'Ud. tiene reservada una visita para este día.<br />Su hora de visita: <b>'.db_resultado($resultado,'FechaVisita').'</b><br />Su código de validación: <b>'.db_resultado($resultado,'validacion_2').'</b><br />';
        return;
    }
    echo "<b>Cupos disponibles</b><br />";
    echo "<table >";
    echo "<tr><th>Hora</th><th>Estado</th></tr>";
    for ($i=510; $i<=1050; $i+=60){
        echo "<tr><td><b>". date("h:ia", mktime(0,$i)) . "</b></td><td><a onclick='$(\"#resultados\").load(\"data/visita+ajax.php\", { fecha: \"$Fecha\", hora: \"".date("h:ia", mktime(0,$i))."\"});'>Reservar</a></td></tr>";
    }
    echo "</table>";
    return;
}
// =====================================================================

// =====================================================================
//  Tabla de visita solicitadas - administrador
// _____________________________________________________________________
if ( _F_usuario_cache('nivel') == _N_administrador &&  isset($_GET['tabla']) && isset($_GET['f_estado']) && isset($_GET['f_desde']) && isset($_GET['f_hasta']) ){
$EstadoOrden = mysql_real_escape_string($_GET['f_estado']);
$_GET['f_desde'] = trim($_GET['f_desde']);
$_GET['f_hasta'] = trim($_GET['f_hasta']);
if (ereg("^[-]{0,1}[0-9]+$",$_GET['f_desde'])) $Desde  = mysql_real_escape_string(mysql_date($_GET['f_desde'].' day')); else $Desde = '';
if (ereg("^[-]{0,1}[0-9]+$",$_GET['f_hasta'])) $Hasta  = mysql_real_escape_string(mysql_date($_GET['f_hasta'].' day')); else $Hasta = '';

if ($EstadoOrden) $EstadoOrden = "AND estado='$EstadoOrden'";
if ($Desde && $Hasta) {
    echo "<i>Mostrando visitas pendientes entre <b>$Desde</b> hasta <b>$Hasta</b></i><br /><br />";
    $Rango = "AND FechaVisita BETWEEN '$Desde' AND '$Hasta'";
} else {
    $Rango = "";
}
$c = "SELECT id_visita,validacion,(SELECT nombre FROM ahm_usuarios as b WHERE b.id_usuario = a.id_usuario) as nombre,FechaVisita,estado FROM ahm_visitas as a WHERE 1 $EstadoOrden $Rango ORDER BY FechaVisita ASC";
DEPURAR($c,0);
$resultado = db_consultar($c);
$n_filas = mysql_num_rows($resultado);
echo "<table style='width:100%' summary='ordens de impresión sin atender'>";
echo "<thead>";
echo ui_tr(ui_th("N°").ui_th("Validador").ui_th("Estado").ui_th("Usuario").ui_th("Fecha de visita"));
echo "</thead>";
echo "<tfoot>";
echo "<tr><td colspan='5'>Se encontraron en total <span style='color:#00F'>$n_filas</span> visitas para el estado seleccionado</td></tr>";
echo "</tfoot>";
echo "<tbody>";
for($i=0; $i<$n_filas; $i++){
    $id_visita  		= mysql_result($resultado,$i,"id_visita");
    $validacion  	    = mysql_result($resultado,$i,"validacion");
    $estado  		    = ui_combobox("cmdEstado_$id_visita",ui_combobox_o_const_visitas(mysql_result($resultado,$i,"estado")),'','','width:auto').'<input type="button" onclick="$(\'#resultados\').load(\'data/visita+ajax.php?visita='.$id_visita.'&estado=\'+$(\'#cmdEstado_'.$id_visita.' :selected\').val())" value="Ok"/>';
    $nombre  		    = mysql_result($resultado,$i,"nombre");
    $FechaVisita  	    = mysql_date_a_fecha_y_hora(mysql_result($resultado,$i,"FechaVisita"));
    echo "<tr><td>$id_visita</td><td>$validacion</td><td>$estado</td><td>$nombre</td><td>$FechaVisita</td></tr>";
}
echo "</tbody>";
echo "</table>";
echo '<div id="resultados"></div>';
return;
}
// =====================================================================
?>
