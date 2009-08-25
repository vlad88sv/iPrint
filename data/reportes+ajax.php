<?php
require_once ("../lib/vital.php");
//====================PROCESAR REPORTES============================//

if (isset($_GET['sub']) && isset($_GET['reporte']) && isset($_SESSION['autenticado']))
{
    $andUsuario = (_F_usuario_cache('nivel') == _N_administrador) ? "" : "AND id_usuario='" . _F_usuario_cache('id_usuario') . "'";
    $CamposOrdenes = "id_orden,id_orden_indv,estado,(SELECT nombre FROM ahm_usuarios as b WHERE b.id_usuario = a.id_usuario) as nombre,txtFechaorden,txtFechaEntrega,txtFechaEntregado";
    $CamposVisitas = "id_visita,validacion,(SELECT nombre FROM ahm_usuarios as b WHERE b.id_usuario = a.id_usuario) as nombre,FechaVisita,estado";
    switch ($_GET['reporte'])
    {
        case "rapido_ordenes_mes_actual":
        $c = "SELECT $CamposOrdenes FROM ahm_ordenes AS a WHERE MONTH(txtFechaEntrega) = MONTH(CURRENT_DATE) $andUsuario";
        break;
        case "rapido_ordenes_mes_anterior":
        $c = "SELECT $CamposOrdenes FROM ahm_ordenes AS a WHERE MONTH(txtFechaEntrega) = (MONTH(CURRENT_DATE)-1) $andUsuario";
        break;
        case "rapido_ordenes_mes_siguiente":
        $c = "SELECT $CamposOrdenes FROM ahm_ordenes AS a WHERE MONTH(txtFechaEntrega) = (MONTH(CURRENT_DATE)+1) $andUsuario";
        break;
        case "rapido_visitas_mes_actual":
        $c = "SELECT $CamposVisitas FROM ahm_visitas AS a WHERE MONTH(FechaVisita) = MONTH(CURRENT_DATE) $andUsuario";
        break;
        case "rapido_visitas_mes_anterior":
        $c = "SELECT $CamposVisitas FROM ahm_visitas AS a WHERE MONTH(FechaVisita) = (MONTH(CURRENT_DATE)-1) $andUsuario";
        break;
        case "rapido_visitas_mes_siguiente":
        $c = "SELECT $CamposVisitas FROM ahm_visitas AS a WHERE MONTH(FechaVisita) = (MONTH(CURRENT_DATE)+1) $andUsuario";
        break;
    }
    $resultado = db_consultar($c);
    $html = db_ui_tabla($resultado);
    //======GENERAR PDF==========================//
    require_once('../lib/tcpdf/config/lang/eng.php');
    require_once('../lib/tcpdf/tcpdf.php');
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('I·PRINT, CEPASA DE C.V.');
    $pdf->SetTitle('Reporte I·PRINT');
    $pdf->SetSubject('Reporte solicitado vía interfaz web');
    $pdf->SetKeywords('IPRINT, CEPASA, REPORTE');
    // set default header data
    $pdf->SetHeaderData("logo.png", 20, "Reporte de uso de I·Print", date("h:m:ia.d-m-Y"));

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    //set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    //set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //set some language-dependent strings
    $pdf->setLanguageArray($l);

    // ---------------------------------------------------------

    // set font
    $pdf->SetFont('dejavusans', '', 10);

    // add a page
    $pdf->AddPage();

    // add HTML
    $pdf->writeHTML($html, true, 0, true, 0);

    // reset pointer to the last page
    $pdf->lastPage();

    //Close and output PDF document
    $pdf->Output('Reporte.'.date("h:m:ia.d-m-Y").'pdf', 'I');
}
?>
