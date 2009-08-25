<?php
function DATA_orden($sub){
    switch (_F_usuario_cache('nivel')) {
        case _N_usuario:
            ui_barra_lateral("Material con mayor demanda en Iprint","Vinyl blackout","Promedio real de tiempo de entrega de ordenes","20 Horas","Material más impreso por '". _F_usuario_cache('nombre') ."'","lona banner");
            echo "<div id='bloque_resumen' style='position:absolute;display:block;right:10px;width:165px;height:auto;border:2px dotted #000;padding:5px'>";
            echo "<h1>Resumen</h1>";
            echo "<span id='lblpaso1' style='text-decoration:underline'>1. Material</span><br/>";
            echo "<span id='lblIpaso1' style='padding-left:5px'></span><br/>";
            echo "<span id='lblpaso2' style='text-decoration:underline'>2. Copias</span><br/>";
            echo "<span id='lblIpaso2' style='padding-left:5px'></span><br/>";
            echo "<span id='lblpaso3' style='text-decoration:underline'>3. Dimensiones</span><br/>";
            echo "<span id='lblIpaso3' style='padding-left:5px'></span><br/>";
            echo "<span id='lblpaso4' style='text-decoration:underline'>4. Escala real</span><br/>";
            echo "<span id='lblIpaso4' style='padding-left:5px'></span><br/>";
            echo "<span id='lblpaso5' style='text-decoration:underline'>5. Entrega</span><br/>";
            echo "<span id='lblIpaso5' style='padding-left:5px'></span><br/>";
            echo "<span id='lblpaso6' style='text-decoration:underline'>6. Costo</span><br/>";
            echo "<span id='lblIpaso6' style='padding-left:5px'></span><br/>";
            echo "<span id='lblpaso7' style='text-decoration:underline'>7. Archivo</span><br/>";
            echo "<span id='lblIpaso7' style='padding-left:5px'></span><br/>";
            echo "<span id='lblpaso8' style='text-decoration:underline'>8. Información</span><br/>";
            echo "<span id='lblIpaso8' style='display:none;padding-left:5px'></span><br/>";
            echo "<span id='lblpaso9' style='text-decoration:underline'>9. Comprobante</span><br/>";
            echo "<span id='lblIpaso9' style='display:none;padding-left:5px'></span><br/>";
            echo "</div>";
            echo "<div style='position:absolute;left:185px;width:60%'>";
            // Si ya hay una orden activa, entonces ofrecerle al usuario esperar o cancelar la activa.
            unset($_SESSION['orden']);
            if ( isset($_SESSION['orden']['activa']) ) {
                echo "Ud. tiene una orden sin completar, posiblemente en otra ventana de su navegador o también ha podido suceder que no completo la orden anterior.<br /> Si Ud. tiene otra orden activa, por favor completala antes de iniciar una nueva. Si no concluyó la orden anterior, entonces por favor presione 'Continuar' para retomarla, o 'Cancelar' para descartarla.";
                return;
            }
            $c = "SELECT max(id_orden_indv) 'norden' FROM ahm_ordenes WHERE id_usuario='"._F_usuario_cache('id_usuario')."'";
            depurar($c,0);
            $resultado = db_consultar($c);
            $_SESSION['orden']['datos']['id_orden_indv'] = (int) db_resultado($resultado, 'norden') +1;
            echo '<div id="lblPasoActual" class="cuadrito_gris" style="float:left;"></div>';
            echo '<div class="cuadrito_gris" style="float:right;">'. strftime('%A %d de %B de %Y', time()) .'</div>';
            echo '<div class="cuadrito_gris" style="float:right;margin-right:10px">Orden N° '.$_SESSION['orden']['datos']['id_orden_indv'].'</div>';
            echo '<br /><br /><br />';
            echo '<b>·<span id="lblInformacionPaso"></span></b><br /><br />';
            echo '<span id="Datos"></span><br /><br /><hr />';
            echo '<span id="lblInformacionPasoExt" ></span>';
            echo '<hr />';
            echo "<div style='bottom:10px;text-align:center'>";
            echo ui_input("cmdAnterior", "Anterior", "button").ui_input("cmdSiguiente", "Siguiente", "button").ui_input("cmdAbortar", "Descartar orden y salir", "button");
            echo '</div>';
            echo '</div>'; // 1
            echo '<span id="scripter"></span>';
            echo JS_onload('
            $("#scripter").load("data/orden+ajax.php");
            $("#cmdAbortar").click(function(){$("#scripter").load("data/orden+ajax.php?abortar=orden");window.location="./";});
            ');
        break;

        case _N_administrador:
            echo "<div style='position:relative;width:95%;margin-left:auto;margin-right:auto'>";
            echo "Limitar vista a ordenes con estado: " . ui_combobox('cmbFiltrarEstado','<option value="">Cualquiera</option>'.ui_combobox_o_const_estados(),"","","width:auto")." Con entrega entre hoy ".ui_input("txtDesde","+0","","","width:4em"). "días, hasta " . ui_input("txtHasta","+10","","","width:4em")."días ". ui_input("cmdFiltrar", "Filtrar", "button");
            echo "<br /><br />";
            echo '<div id="tabla_ordenes">Cargando datos...</div>';
            echo JS_onload('
            $("#tabla_ordenes").load("data/orden+ajax.php?tabla=orden&f_estado=1&f_desde=0&f_hasta=30");
            $("#cmdFiltrar").click(function(){$("#tabla_ordenes").load("data/orden+ajax.php?tabla=orden&f_estado="+$("#cmbFiltrarEstado").val()+"&f_desde="+escape($("#txtDesde").val())+"&f_hasta="+escape($("#txtHasta").val()));});
            ');
            echo "</div>";
        break;
    }
}
    //echo '<img src="img/postscript.png" style="postion:absolute;bottom:0px" />';
?>
