<?php
function DATA_historial(){
    echo ui_barra_lateral("Ahorro total de clientes de Iprint", "$8,500.00", "Metros cuadrados impresos totales por Iprint", "4500.00", "Promedio mensual de ordenes por cliente", "15" );
    echo "<div style='position:absolute;left:200px;width:70%'>";
    echo "Mostrar todas las ordenes " . ui_input("cmdVerOrdenesTodo","Ver","button")."<br />".
         "Con fecha de entrega en el año " . ui_combobox("cmbFiltroAnios","<option value=''>Todos</option>".ui_combobox_o_anios_presencia(),"","","width:auto") .ui_input("cmdVerOrdenesAnios","Ver","button").
         " y el mes " . ui_combobox("cmbFiltroMeses","<option value=''>Todos</option>".ui_combobox_o_meses_presencia(),"","","width:auto") .ui_input("cmdVerOrdenesMeses","Ver","button").
         " y el día " . ui_combobox("cmbFiltroDias",ui_combobox_o_dias_presencia(),"","","width:auto"). " " .ui_input("cmdVerOrdenesDias","Ver","button");
    echo "<div id='resultado_historial' style='margin-top:10px'></div>";
    echo "</div>";
    echo JS_onload('$("#cmdVerOrdenesTodo").click(function(){$("#resultado_historial").load("data/historial+ajax.php",{opcion:"todo"})});');
    echo JS_onload('$("#cmdVerOrdenesAnios").click(function(){$("#resultado_historial").load("data/historial+ajax.php",{opcion:"anio",anio:$("#cmbFiltroAnios").val()})});');
    echo JS_onload('$("#cmdVerOrdenesMeses").click(function(){$("#resultado_historial").load("data/historial+ajax.php",{opcion:"mes",anio:$("#cmbFiltroAnios").val(), mes:$("#cmbFiltroMeses").val()})});');
    echo JS_onload('$("#cmdVerOrdenesDias").click(function(){$("#resultado_historial").load("data/historial+ajax.php",{opcion:"dia",anio:$("#cmbFiltroAnios").val(),mes:$("#cmbFiltroMeses").val(),dia:$("#cmbFiltroDias").val()})});');
}
?>
