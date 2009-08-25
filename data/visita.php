<?php
function DATA_visita(){
    switch (_F_usuario_cache('nivel')) {
    case _N_usuario:
    echo "<div>";
    echo ui_barra_lateral("Visitas solicitadas por '". _F_usuario_cache('nombre') ."'", "12", "Porcentaje de cumplimiento en la puntualidad de visitas", "95%", "Nota promedio otorgada por los clientes a Iprint en " . ucfirst(strftime('%B', strtotime("-1 month"))), "9.5");
    echo "<div style='position:absolute;left:200px;width:70%'>";
    echo "<div style='float:left'>";
    echo "<b>Seleccione el día " . "<div style='margin-top:10px;' class='date-pick'></div><br />" ."</b><br />";
    echo "</div>";
    echo "<div style='float:right'>";
    echo "<div id='resultados'>";
    echo "</div>"; //Resultados
    echo "</div>";
    echo "<div style='clear:both'></div>";
    echo "<hr />";
    echo "<b>Para solicitar un visita</b>
    <ol>
    <li>Seleccione en el calendario el día que desee ser visitado.</li>
    <li>Se mostrará una tabla de horarios de visitas disponibles para el día seleccionado.</li>
    <li>Proceda a reservar su cita realizando clic sobre el enlace <b>Disponible</b> adecuado.</li>
    <li>Se confirmará la selección y se le mostrará un código validación de reservación de cita, este es el único código con el cúal Ud. podrá cancelar o modificar esta cita, y esto deberá realizarse vía telefónica.</li>
    </ol>";
    echo "</div>";
    echo "</div>";
    echo JS_onload(ui_js_ini_datepicker("+0", "+30", ', onSelect: function(dateText) { $("#resultados").load("data/visita+ajax.php",{fecha:dateText}); }') . '$.jGrowl("Si necesita una visita con menos de dos horas de anticipación, por favor hable a nuestras oficinas al número: XXXXXX", {theme: "aviso",life:5000});');
    break;

    case _N_administrador:
    echo "<div style='position:relative;width:95%;margin-left:auto;margin-right:auto'>";
    echo "Limitar vista a visitas con estado: " . ui_combobox('cmbFiltrarEstado','<option value="">Cualquiera</option>'.ui_combobox_o_const_visitas(),"","","width:auto")." entre hoy ".ui_input("txtDesde","+0","","","width:4em"). "días, hasta " . ui_input("txtHasta","+10","","","width:4em")."días ". ui_input("cmdFiltrar", "Filtrar", "button");
    echo "<br /><br />";
    echo '<div id="tabla_visitas">Cargando datos...</div>';
    echo JS_onload('
    $("#tabla_visitas").load("data/visita+ajax.php?tabla=visitas&f_estado=1&f_desde=0&f_hasta=30");
    $("#cmdFiltrar").click(function(){$("#tabla_visitas").load("data/visita+ajax.php?tabla=orden&f_estado="+$("#cmbFiltrarEstado").val()+"&f_desde="+escape($("#txtDesde").val())+"&f_hasta="+escape($("#txtHasta").val()));});
    ');
    echo "</div>";
    break;
    }
}

?>
