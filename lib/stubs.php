<?php
function ui_barra_lateral($texto1, $texto1b, $texto2, $texto2b, $texto3, $texto3b) {
echo "
<div style='position:absolute;left:15px;width:165px;font-weight:bolder;font-size:.9em;'>
<div style='border:2px dotted #000;padding:3px;margin-top:0px;height:130px;width:150px;background-color:#00a6e0;color:#fff'>$texto1:<br /><br /><span style='font-size:1.1em;color:#ede800'>$texto1b</span></div>
<div style='border:2px dotted #000;padding:3px;margin-top:10px;height:130px;width:150px;background-color:#d70073;color:#ccc'>$texto2:<br /><br /><span style='font-size:1.1em;color:#00a6e0'>$texto2b</span></div>
<div style='border:2px dotted #000;padding:3px;margin-top:10px;height:130px;width:150px;background-color:#ede800;color:#000'>$texto3:<br /><br /><span style='font-size:1.1em;color:#d70073;'>$texto3b</span></div>
</div>
";
}
function JS_($script){
    return "<script type='text/javascript'>".$script."</script>";
}
function JS_onload($script){
    return "<script type='text/javascript'>$(document).ready(function(){".$script."});</script>";
}

function JS_growl($mensaje){
    return JS_onload("$.jGrowl('".addslashes($mensaje)."', {theme: 'aviso',life:5000})");
}

function ui_combobox_o_horas_habiles() {
    $Datos = '';
    for ($i=510; $i<=1050; $i+=60){
        $Datos .= '<option value="'. date("H:i:s", mktime(0,$i,0)) .'">'.date("h:ia", mktime(0,$i)).'</option>';
    }
    return $Datos;
}

function ui_combobox_o_sismetrico (){
	$opciones = '';
	$opciones .= '<option value="m">metros</option>';
	$opciones .= '<option value="cm">centímetros</option>';
	$opciones .= '<option value="pulgada">pulgadas</option>';
	$opciones .= '<option value="pie">pies</option>';
	$opciones .= '<option value="yarda">yardas</option>';
	return $opciones;
}

function ui_combobox_o_anios_presencia($id_usuario = ""){
$andUsuario= $id_usuario ? "AND id_usuario='".$id_usuario."'" : "";
$c = "SELECT DISTINCT YEAR(txtFechaEntrega) AS 'opt' FROM ahm_ordenes  WHERE 1 $andUsuario";
$resultado = db_consultar($c);
$n_filas = mysql_num_rows($resultado);
$opciones = '';
for ($i = 0; $i < $n_filas; $i++){
    $opciones .= "<option value='".mysql_result($resultado,$i,'opt')."'>".mysql_result($resultado,$i,'opt')."</option>";
}
return $opciones;
}

function ui_combobox_o_meses_presencia($id_usuario = ""){
$andUsuario= $id_usuario ? "AND id_usuario='".$id_usuario."'" : "";
$c = "SELECT DISTINCT MONTH(txtFechaEntrega) AS 'opt' FROM ahm_ordenes WHERE 1 $andUsuario";
$resultado = db_consultar($c);
$n_filas = mysql_num_rows($resultado);
$opciones = '';
for ($i = 0; $i < $n_filas; $i++){
    $opciones .= "<option value='".mysql_result($resultado,$i,'opt')."'>".nMes_nombre(mysql_result($resultado,$i,'opt'))."</option>";
}
return $opciones;
}

function ui_combobox_o_dias_presencia($id_usuario = ""){
$andUsuario= $id_usuario ? "AND id_usuario='".$id_usuario."'" : "";
$c = "SELECT DISTINCT DAY(txtFechaEntrega) AS 'opt' FROM ahm_ordenes WHERE 1 $andUsuario";
$resultado = db_consultar($c);
$n_filas = mysql_num_rows($resultado);
$opciones = '';
for ($i = 0; $i < $n_filas; $i++){
    $opciones .= "<option value='".mysql_result($resultado,$i,'opt')."'>".mysql_result($resultado,$i,'opt')."</option>";
}
return $opciones;
}

function ui_combobox_materiales($id_usuario){
$c = "SELECT id_material, material FROM ahm_materiales WHERE activo=1 AND id_material IN (SELECT id_material FROM ahm_materiales_indv WHERE activo=1 AND id_usuario='$id_usuario')";
$resultado = db_consultar($c);
$n_filas = mysql_num_rows($resultado);
$opciones = '';
for ($i = 0; $i < $n_filas; $i++){
    $opciones .= "<option value='".mysql_result($resultado,$i,'id_material')."'>".mysql_result($resultado,$i,'material')."</option>";
}
return $opciones;
}

function ui_combobox_o_const_estados($seleccionado=1){
$datos = '';
for ($i = -5; $i < 7; $i++){
    if ($i == $seleccionado){$selected = 'selected="selected"';} else {$selected = '';}
    $datos .= '<option value="'.$i.'"'.$selected.'>'.convertir_EO_str($i).'</option>';
}
return $datos;
}

function ui_combobox_o_const_visitas($seleccionado=1){
$datos = '';
for ($i = -2; $i < 5; $i++){
    if ($i == $seleccionado){$selected = 'selected="selected"';} else {$selected = '';}
    $datos .= '<option value="'.$i.'"'.$selected.'>'.convertir_EV_str($i).'</option>';
}
return $datos;
}

//Timestamp to MYSQL DATETIME
function mysql_date($tiempo = 'now'){
    return date( 'Y-m-d H:i:s',strtotime($tiempo) );
}

//MYSQL DATETIME a fecha normal (sin hora)
function mysql_date_a_fecha($tiempo){
    return date( 'd-m-Y',strtotime($tiempo) );
}

//MYSQL DATETIME a fecha normal + hora
function mysql_date_a_fecha_y_hora($tiempo){

    return $tiempo ? date( 'h:ia @ d-m-Y',strtotime($tiempo) ) : "";
}

function suerte($una, $dos){
    if (rand(0,1)) {
        return $una;
    } else {
        return $dos;
    }
}

function despachar_notificaciones($mensaje){
    tsv_sms_enviar('77521234',$mensaje,'IPrint');
}

function nMes_nombre($mes){
return ucfirst(strftime('%B', mktime (0,0,0,$mes,1,2009)));
}
?>
