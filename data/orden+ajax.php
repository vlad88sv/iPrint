<?php
require_once ("../lib/vital.php");
// ===================================================================
// Cambio de estado de orden
// -------------------------------------------------------------------
if ( _F_usuario_cache('nivel') == _N_administrador && isset($_GET['orden']) && isset($_GET['estado']) ){
    $Orden  = db_codex($_GET['orden']);
    $Estado = db_codex($_GET['estado']);
    $setEntregado = ($Estado == _EO_entregada) ? "'".mysql_date() . "'" : "NULL";
    $c = "UPDATE ahm_ordenes SET estado='$Estado', txtFechaEntregado=$setEntregado where id_orden='$Orden'";
    $resultado = db_consultar($c);
    if ($resultado) {
        echo "Se actualizó correctamente la orden.<br />Deberá recargar la tabla para poder ver los cambios";
    } else {
        echo "No se pudo actualizar el estado de la orden.";
    }
} /* Cambio de estado de orden */
// ===================================================================

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// ===================================================================
// Listas de administrador [Incluido control de filtros]
// -------------------------------------------------------------------
if ( _F_usuario_cache('nivel') == _N_administrador &&  isset($_GET['tabla']) && isset($_GET['f_estado']) && isset($_GET['f_desde']) && isset($_GET['f_hasta']) ){
$EstadoOrden = mysql_real_escape_string($_GET['f_estado']);
$_GET['f_desde'] = trim($_GET['f_desde']);
$_GET['f_hasta'] = trim($_GET['f_hasta']);
if (ereg("^[-]{0,1}[0-9]+$",$_GET['f_desde'])) $Desde  = mysql_real_escape_string(mysql_date($_GET['f_desde'].' day')); else $Desde = '';
if (ereg("^[-]{0,1}[0-9]+$",$_GET['f_hasta'])) $Hasta  = mysql_real_escape_string(mysql_date($_GET['f_hasta'].' day')); else $Hasta = '';

if ($EstadoOrden) $EstadoOrden = "AND estado='$EstadoOrden'";
if ($Desde && $Hasta) {
    echo "<i>Mostrando ordenes con fecha de entrega entre <b>$Desde</b> hasta <b>$Hasta</b></i><br /><br />";
    $Rango = "AND txtFechaEntrega BETWEEN '$Desde' AND '$Hasta'";
} else {
    $Rango = "";
}
$c = "SELECT id_orden,id_orden_indv,estado,id_usuario,(SELECT nombre FROM ahm_usuarios as b WHERE b.id_usuario = a.id_usuario) as nombre,txtFechaorden,txtFechaEntrega,txtFechaEntregado,flArchivo FROM ahm_ordenes as a WHERE 1 $EstadoOrden $Rango ORDER BY txtFechaEntrega ASC";
DEPURAR($c,0);
$resultado = db_consultar($c);
$n_filas = mysql_num_rows($resultado);
echo "<table style='width:100%' summary='ordenes de impresión'>";
echo "<thead>";
echo ui_tr(ui_th("N°").ui_th("N°[i]").ui_th("Estado").ui_th("Cliente").ui_th("Solicitado").ui_th("Entrega").ui_th("Entregado").ui_th("Archivo"));
echo "</thead>";
echo "<tfoot>";
echo "<tr><td colspan='9'>Se encontraron en total <span style='color:#00F'>$n_filas</span> ordenes de impresión para el estado seleccionado</td></tr>";
echo "</tfoot>";
echo "<tbody>";
for($i=0; $i<$n_filas; $i++){
    $id_orden  		    = mysql_result($resultado,$i,"id_orden");
    $id_orden_indv  	= mysql_result($resultado,$i,"id_orden_indv");
    $estado  		    = ui_combobox("cmdEstado_$id_orden",ui_combobox_o_const_estados(mysql_result($resultado,$i,"estado")),'','','width:auto').'<input type="button" onclick="$(\'#resultados\').load(\'data/orden+ajax.php?orden='.$id_orden.'&estado=\'+$(\'#cmdEstado_'.$id_orden.' :selected\').val())" value="Ok"/>';
    //$id_usuario  		= mysql_result($resultado,$i,"id_usuario");
    $nombre  		    = mysql_result($resultado,$i,"nombre");
    $txtFechaorden  	= mysql_date_a_fecha_y_hora(mysql_result($resultado,$i,"txtFechaorden"));
    $txtFechaEntrega  	= mysql_date_a_fecha_y_hora(mysql_result($resultado,$i,"txtFechaEntrega"));
    $txtFechaEntregado	= mysql_date_a_fecha_y_hora(mysql_result($resultado,$i,"txtFechaEntregado"));
    /* safe_mode off */
    //$flArchivo  		= '<a target="_blank" href="+/'.mysql_result($resultado,$i,"id_usuario").'/'.mysql_result($resultado,$i,"flArchivo").'">'.basename(mysql_result($resultado,$i,"flArchivo")).'</a>';
    $flArchivo  		= '<a target="_blank" href="+'.mysql_result($resultado,$i,"flArchivo").'">'.basename(mysql_result($resultado,$i,"flArchivo")).'</a>';
    echo "<tr><td>$id_orden</td><td>$id_orden_indv</td><td width='30%'>$estado</td><td>$nombre</td><td>$txtFechaorden</td><td>$txtFechaEntrega</td><td>$txtFechaEntregado</td><td>$flArchivo</td></tr>";
}
echo "</tbody>";
echo "</table>";
echo '<div id="resultados"></div>';
return;
} /* ->Cierre de tablas y filtros<- */
// ===================================================================

$NombreCampo = 'flArchivo';
if ( isset($_SESSION['orden']['activa']) && isset($_FILES[$NombreCampo]['error']) ) {
    /* Esto solo funciona con safe_mode off */
    //$dir = date('Y.m.d');
    //$predir =  "../+/"._F_usuario_cache('id_usuario')."/";
    //if ( !file_exists($predir.$dir) )mkdir($predir.$dir,0777,true);
    /* safe_mode off */
    if ($_FILES[$NombreCampo]['error'] != 0) {
        $msg = "#ERROR# N° " . $_FILES[$NombreCampo]['error'];
    }
    $dir = "";
    $predir =  "../+/";

    @chmod($predir.$dir,0777);
    do {
        $_SESSION['orden']['datos'][$NombreCampo] = $dir ."/".crc32(time())."-".$_FILES[$NombreCampo]['name'];
    } while ( file_exists($predir.$_SESSION['orden']['datos'][$NombreCampo]) );
    $ret = is_uploaded_file($_FILES[$NombreCampo]['tmp_name']);

    if ($ret){
        $ret = move_uploaded_file($_FILES[$NombreCampo]['tmp_name'], $predir.$dir.$_SESSION['orden']['datos'][$NombreCampo]);
        if ($ret){
            @chmod($predir.$_SESSION['orden']['datos'][$NombreCampo],0777);
            $msg = $_FILES[$NombreCampo]['name'];
        } else {
            $msg = "#ERROR# Imposible cargar el archivo seleccionado por problemas del servidor.<br />".print_ar($_FILES);
        }
    } else {
        $msg = "#ERROR# Se intentó mover un archivo que no fue cargado via el formulario.<br />";
    }
    $_SESSION['orden']['resumen']['7'] = basename($_FILES[$NombreCampo]['name']);
    echo "{" . "\n";
    echo "msg: '" . addslashes($msg) . "'\n";
    echo "}";
    return;
}

// Controla los pasos de una orden por usuario.
// Se establacerá una bandera de  "activa" para que el usuario no pueda
// crear mas de una orden simultaneamente y dañar el proceso.

if ( isset($_GET['abortar'] ) ) {
    unset($_SESSION['orden']);
}

if ( !isset($_SESSION['orden']['activa']) ) {
    $_SESSION['orden']['activa'] = time();
    $_SESSION['orden']['paso'] = 1;
    $_SESSION['orden']['datos']['id_usuario'] = _F_usuario_cache('id_usuario');
    $_SESSION['orden']['datos']['estado'] = _EO_nueva;
    $_SESSION['orden']['datos']['validacion'] = rand(0,32500);
    $_SESSION['orden']['datos']['txtFechaEntrega'] = mysql_date('+3 day');
    $_SESSION['orden']['resumen']['1'] = '';
    $_SESSION['orden']['resumen']['7'] = '';

}

$_SESSION['orden']['paso_anterior'] = $_SESSION['orden']['paso'];

if ( isset($_GET['siguiente'] ) ) {
    $_SESSION['orden']['paso']++;
}
if ( isset($_GET['anterior'] ) ) {
    $_SESSION['orden']['paso']--;
}

$InfoPaso2 = $Recoger = $Script = '';

// =============================================================================================================
// Recoger los datos posteados y generar resumen
// _____________________________________________________________________________________________________________
switch ($_SESSION['orden']['paso_anterior'])
{
    case 1:
        if (isset($_POST['cmbListaMateriales'])) $_SESSION['orden']['datos']['cmbListaMateriales'] = $_POST['cmbListaMateriales'];
        if (isset($_POST['txtOtroMaterial'])) $_SESSION['orden']['datos']['txtOtroMaterial'] = $_POST['txtOtroMaterial'];
        if (isset($_POST['cmbListaMateriales_txt'])) $_SESSION['orden']['resumen']['1'] = $_POST['cmbListaMateriales_txt'];
    break;
    case 2:
        if (isset($_POST['txtCantidad'])) $_SESSION['orden']['datos']['txtCantidad'] = $_POST['txtCantidad'];
        if (isset($_POST['txtCantidad'])) $_SESSION['orden']['resumen']['2'] = $_POST['txtCantidad'];
    break;
    case 3:
        if (isset($_POST['txtMedidaImpresionAncho'])) $_SESSION['orden']['datos']['txtMedidaImpresionAncho'] = $_POST['txtMedidaImpresionAncho'];
        if (isset($_POST['cmbSisMetricoAncho'])) $_SESSION['orden']['datos']['cmbSisMetricoAncho'] = $_POST['cmbSisMetricoAncho'];
        if (isset($_POST['txtMedidaImpresionLargo'])) $_SESSION['orden']['datos']['txtMedidaImpresionLargo'] = $_POST['txtMedidaImpresionLargo'];
        if (isset($_POST['cmbSisMetricoLargo'])) $_SESSION['orden']['datos']['cmbSisMetricoLargo'] = $_POST['cmbSisMetricoLargo'];
        if (isset($_SESSION['orden']['datos']['txtMedidaImpresionAncho']) && isset($_SESSION['orden']['datos']['txtMedidaImpresionLargo'])) $InfoPaso2 = $_SESSION['orden']['datos']['txtMedidaImpresionAncho'] .  $_SESSION['orden']['datos']['cmbSisMetricoAncho'] ."x" . $_SESSION['orden']['datos']['txtMedidaImpresionLargo'] .  $_SESSION['orden']['datos']['cmbSisMetricoLargo'];
        if (isset($_POST['txtMedidaImpresionAncho']) && isset($_POST['txtMedidaImpresionLargo'])) $_SESSION['orden']['resumen']['3'] = $_POST['txtMedidaImpresionAncho'] .  $_POST['cmbSisMetricoAncho'] ."x" . $_POST['txtMedidaImpresionLargo'] .  $_POST['cmbSisMetricoLargo'];
    break;
    case 4:
        if (isset($_POST['optNoSi'])) $_SESSION['orden']['datos']['optNoSi'] = $_POST['optNoSi'];
        if (isset($_SESSION['orden']['datos']['optNoSi'])) interpretar_valor($_SESSION['orden']['datos']['optNoSi'], array('No', 'Si'));
        if (isset($_POST['optNoSi'])) $_SESSION['orden']['resumen']['4'] = interpretar_valor($_POST['optNoSi'], array('No', 'Si'));;
    break;
    case 5:
        if (isset($_POST['txtFechaEntrega']) && isset($_POST['cmbHoraEntrega'])) $_SESSION['orden']['datos']['txtFechaEntrega'] = mysql_date($_POST['txtFechaEntrega'] . ' ' . $_POST['cmbHoraEntrega']);
        if (isset($_POST['txtFechaEntrega']) && isset($_POST['cmbHoraEntrega'])) $_SESSION['orden']['resumen']['5'] = date("h:ia",strtotime($_POST['cmbHoraEntrega'])) . '<br />' . $_POST['txtFechaEntrega'];
    break;
    case 6:
        // Manejado durante el calculo para evitar estafas
    break;
    case 7:
        // Manejado por aparte en la subida del archivo
    case 8:
        if (isset($_POST['txtNotas'])) $_SESSION['orden']['datos']['txtNotas'] = $_POST['txtNotas'];
        if (isset($_POST['txtNotas'])) $_SESSION['orden']['resumen']['8'] = $_POST['txtNotas'];
    break;
    default:
        $InfoPaso2 = '';
}


// =============================================================================================================

switch ( $_SESSION['orden']['paso'] ) {
    // Selección de material:
    case 1:
        $nPaso = "1: Material";
        $InfoPaso = "Seleccione el tipo de material en el cual desea que su impresión sea realizada";
        $Datos = "Material "  . ui_combobox ('cmbListaMateriales',ui_combobox_materiales(_F_usuario_cache('id_usuario'))) .'<br /><br />' . 'Especificaciones para el material:<br />' . ui_textarea('txtOtroMaterial',_F_orden_cache('txtOtroMaterial'),'','width:99%');
        $InfoExtra = "Se recomienda escoger el material de acuerdo a los siguientes criterios:<br /><ol><li>Vinilo, se recomienda para su uso en interior-exterior; área máxima de impresión: XxY</li><li>Tela, se recomienda para su uso en interiores; área máxima de impresión: XxY</li></ol>";
        $Recoger = 'cmbListaMateriales: $("#cmbListaMateriales").val(), cmbListaMateriales_txt: $("#cmbListaMateriales :selected").text(), txtOtroMaterial: $("#txtOtroMaterial").val()';
        $Script = '$("#cmdAnterior").hide();$("#cmbListaMateriales").val("'._F_orden_cache('cmbListaMateriales').'");';
    break;
    case 2:
        $nPaso = "2: Copias";
        $InfoPaso = "Especifique la cantidad de copias a imprimir";
        $Datos = "Cantidad " . ui_input('txtCantidad',siVacio(_F_orden_cache('txtCantidad'),1));
        $InfoExtra = '';
        $Recoger = 'txtCantidad: $("#txtCantidad").val()';
    break;
    case 3:
        $nPaso = "3: Dimensiones";
        $InfoPaso = "Especifique la medida real en la que desea la impresión";
        $Datos = "Dimensiones<br />" . "Ancho:" . ui_input('txtMedidaImpresionAncho',_F_orden_cache('txtMedidaImpresionAncho'),'','','width:4em') . ui_combobox('cmbSisMetricoAncho',ui_combobox_o_sismetrico(),'','','width:auto'). " x Largo:" . ui_input('txtMedidaImpresionLargo',_F_orden_cache('txtMedidaImpresionLargo'),'','','width:4em') . ui_combobox('cmbSisMetricoLargo',ui_combobox_o_sismetrico(),'','','width:auto');
        $InfoExtra = "La medidas pueden especificarse en el sistema metrico de su preferencia, pero no olvide incluir las unidades en las cuales estan expresadas estas medidas.<br /><br />Ejemplos de medidas validas:<br /><ol><li>'<b>5mx1m</b>' será interpretado como '<b>5 metros por 1 metro</b>'</li><li>'<b>7\"x30cm</b>' será interpretado como '<b>7 pulgadas por 30 centimetros</b>'</li></ol>";
        $Recoger = 'txtMedidaImpresionAncho: $("#txtMedidaImpresionAncho").val(), cmbSisMetricoAncho: $("#cmbSisMetricoAncho").val(), txtMedidaImpresionLargo: $("#txtMedidaImpresionLargo").val(), cmbSisMetricoLargo: $("#cmbSisMetricoLargo").val()';
    break;
    case 4:
        $nPaso = "4: Escala";
        $InfoPaso = "¿El arte que envía se encuentra ya en la escala real de impresión?";
        $Datos = "¿Escala real? " . ui_optionbox_nosi('optNoSi');
        $InfoExtra = "Recuerde que si la imagen <b>no</b> es vectorial, pedir una impresión a mayor escala puede resultar en imagenes borrosas.<br />Para la mejor calidad posible, siempre envie sus impresiones en formato vectorial evitando incustrar imagenes de mapa de bits.";
        $Recoger = 'optNoSi: $("input[name=\'optNoSi\']:checked").val()';
        $Script = '$("input[name=\'optNoSi\']:nth('._F_orden_cache('optNoSi').')").attr("checked","checked");';
    break;
    case 5:
        $nPaso = "5: Entrega";
        $InfoPaso = "¿Cúal es la fecha máxima de entrega para este orden?";
        $Datos = "Fecha de entrega " . ui_input('txtFechaEntrega',mysql_date_a_fecha(_F_orden_cache('txtFechaEntrega')),'text','date-pick','','READONLY').' Hora: '. ui_combobox("cmbHoraEntrega",ui_combobox_o_horas_habiles(),'','','width:auto');
        $InfoExtra = "Tip: para nosotros todas las impresiones son 'urgentes', por ello evite solicitar todas las impresiones para el día siguiente si no es realmente necesario.";
        $Recoger = 'txtFechaEntrega: $("#txtFechaEntrega").val(), cmbHoraEntrega: $("#cmbHoraEntrega").val(),';
        $Script = ui_js_ini_datepicker("+1").'$("#cmbHoraEntrega").val("'.date("H:i:s", strtotime(_F_orden_cache('txtFechaEntrega'))).'");';
    break;
    case 6:
        // Calcular el costo.
        /* Formula:
            * 1. Obtener los m² de impresión.
            * 2. Multiplicarlos por el precio del material seleccionado.
            * 3. Multiplcar por el número de copias
        */

        // Obtengamos el precio del material
        $c = "SELECT costo FROM ahm_materiales_indv WHERE id_material='"._F_orden_cache('cmbListaMateriales')."' AND id_usuario='"._F_usuario_cache('id_usuario')."'";
        DEPURAR ($c,0);
        $resultado = db_consultar($c);
        if ($resultado) $costo = db_resultado($resultado,'costo'); else $costo = 0;

        // Obtegamos el área en m²
        $area = _F_orden_cache('txtMedidaImpresionAncho') * _F_orden_cache('txtMedidaImpresionLargo');

        // Obtengamos el precio/costo
        $precio = ($area*$costo*_F_orden_cache('txtCantidad'));
        $_SESSION['orden']['datos']['lblCosto'] = $precio;
        $_SESSION['orden']['resumen']['6'] = "$".$precio;

        $nPaso = "6: Costo";
        $InfoPaso = "En base a sus preferencias se ha calculado el costo de la impresión";
        $Datos = "Área: ".$area."m², precio m²: $".$costo."; "._F_orden_cache('txtCantidad')." copias.<br /> Costo <b>$$precio ; ahorro: $".($precio * 0.10)."</b>";
        $InfoExtra = "El costo y el ahorro es aproximado";
        $Recoger = "lblCosto: '$$precio'";
    break;
    case 7:
        $ArchivoPrevio =  isset($_SESSION['orden']['resumen'][$NombreCampo]) ? '<span style="color:#f00#">Ud. ha cargado el siguiente archivo para esta orden: "<b>'.$_SESSION['orden']['resumen'][$NombreCampo] . '</b>", si desea reemplazarlo simplemente seleccione y cargue un nuevo archivo.</span><br />' : '';
        $nPaso = "7: Archivo";
        $InfoPaso = "Cargue el archivo que desea imprimir.";
        $Datos = $ArchivoPrevio."Archivo " .ui_input('flArchivo','','file').ui_input('cmdCargarArchivo','Cargar','button').'<br /><div id="flArchivo_ajaxer"></div>';
        $Recoger = '';
        $InfoExtra = "Nota: el tiempo de cargar varia de acuerdo al tamaño del archivo.<br />Puede experimentar fallos en la carga del archivo debido a la naturaleza de las conexiones, en este respecto nos es imposible mejorar la situación de nuestra parte.";
        $Script = '
        function ajaxFileUpload()
        {
        $.ajaxFileUpload
        (
            {
                url:"data\/orden+ajax.php",
                fileElementId:"flArchivo",
                dataType: "json",
                success: function (data, status)
                {
                    $("#flArchivo_ajaxer").html("Archivo cargado: <b>"+data.msg+"</b>");
                },
                error: function (data, status, e)
                {
                    alert(e);
                }
            }
        )
        return false;
    }
    $("#cmdCargarArchivo").click(function(){ajaxFileUpload();});
    ';
    break;
    case 8:
        $nPaso = "8: Información";
        $InfoPaso = "Ingrese cualquier nota que desee que tomemos en cuenta al momento de procesar esta orden";
        $Datos = "Notas y observaciones:<br />" . ui_textarea('txtNotas',_F_orden_cache('txtNotas'),'','width:99%');
        $InfoExtra = "Tip: Las sugerencias generales puede hacerlas utilizando el botón 'Comentarios' en el menú superior";
        $Recoger = 'txtNotas: $("#txtNotas").val()';
        $Script = '$("#cmdSiguiente").attr("value", "Imprimir");';
    break;
    case 9:
        $_SESSION['orden']['datos']['txtFechaorden'] = mysql_date('now');
        $id_orden = db_agregar_datos('ahm_ordenes', $_SESSION['orden']['datos']);
        $nPaso = "9. Confirmación";
        $InfoPaso = "Se ha completado con exito el asistente de solicitud de impresión.";
        $Datos = "Su número de comprobante de impresión es <b>".$id_orden . "+" . $_SESSION['orden']['datos']['validacion']."</b>. Este número a sido registrado en el sistema y puede ser consultado en cualquier momento. Ud. necesitará este número para realizar cualquier consulta vía telefónica sobre el estado de esta impresión.<br /><br />Datos de la orden:<br /><br />".dumpOrden($_SESSION['orden']['datos']);
        $InfoExtra = "La orden ha sido enviada y <b>no</b> puede modificarla.<br />Si desea intentar cancelarla puede comunicarse (lo antes posible) de forma telefonica con I·Print, se le solicitará el código de la orden de impresión.";
        $Script = '$("#cmdAnterior").hide();$("#cmdSiguiente").attr("value", "Nueva orden");$("#cmdAbortar").attr("value", "Salir");';
        despachar_notificaciones('El usuario '._F_usuario_cache('nombre').' ha solicitado un impresion para el '._F_orden_cache('txtFechaEntrega'));
    break;
    case 10:
        $nPaso = "Creando nueva orden...";
        $InfoPaso = "Por favor espere...";
        $Datos = "";
        $InfoExtra = "";
        $Script = "window.location='./?accion=orden'";
    break;

}
retornarAjax (orden_ajax_compuesto($nPaso,$InfoPaso,$InfoPaso2,$Datos,$InfoExtra,$Recoger,$Script));

function orden_ajax_compuesto($nPaso,$InfoPaso,$InfoPaso2,$Datos,$InfoExtra,$Recoger,$Script){
    global $NombreCampo;
    return JS_onload('
    $("#lblPasoActual").html("Paso '.addslashes($nPaso).'");
    $("#lblInformacionPaso").html("'.addslashes($InfoPaso).'");
    $("#Datos").html("'.addslashes($Datos).'");
    $("#lblInformacionPasoExt").html("'.addslashes($InfoExtra).'");
    $("#bloque_resumen span").css("font-weight","normal");
    $("#cmdAnterior").unbind();
    $("#cmdAnterior").click(function(){$.post("data/orden+ajax.php?anterior=paso", { '.$Recoger.' } ,function(data){$("#scripter").html(data);});});
    $("#cmdSiguiente").attr("value","Siguiente");
    $("#cmdSiguiente").unbind();
    $("#cmdSiguiente").click(function(){$("#cmdAnterior").show();$.post("data/orden+ajax.php?siguiente=paso", { '.$Recoger.' } ,function(data){$("#scripter").html(data);});});
    $("#cmdAbortar").attr("value","Descartar orden y salir");
    $("#bloque_resumen #lblpaso'.$_SESSION['orden']['paso'].'").css("font-weight","bold");
    $("#bloque_resumen #lblIpaso'.($_SESSION['orden']['paso_anterior']).'").html("·'.addslashes($_SESSION['orden']['resumen'][$_SESSION['orden']['paso_anterior']]).'");
    '.$Script.'
    ');
}

function _F_orden_cache($campo){
    if (!array_key_exists ('orden', $_SESSION)) return '';
    if (!array_key_exists ('datos', $_SESSION['orden'])) return '';

	if ( array_key_exists ($campo, $_SESSION['orden']['datos']) ) {
		return $_SESSION['orden']['datos'][$campo];
	}else{
		return '';
	}
}

function dumpOrden($array) {
    $data = "<table class='limpio'>";
    foreach($array as $key=>$value){
        $data .= "<tr><td width='20%'>".interpretar_valor($key,array('cmbListaMateriales' => 'Código material', 'txtOtroMaterial' => 'Especificación de material', 'txtCantidad' => 'N° de copias', 'txtMedidaImpresionAncho' => 'Ancho', 'cmbSisMetricoAncho' => 'Unidades Ancho', 'txtMedidaImpresionLargo' => 'Largo', 'cmbSisMetricoLargo' => 'Unidades Largo', 'optNoSi' => 'Escala', 'txtFechaEntrega' => 'Fecha de entrega', 'txtHoraEntrega' => 'Hora de entrega', 'lblCosto' => 'Costo', 'txtNotas' => 'Notas extra', 'flArchivo' => 'Archivo'))."</td><td>$value</td></tr>";
    }
    $data .= "</table>";
    return $data;
}
?>
