<?php
function DATA_material($sub){
    $c = "SELECT id_material, material, activo FROM ahm_materiales";
    $resultado = db_consultar($c);
    $n_filas = mysql_num_rows($resultado);
    echo "<div style='position:relative;width:90%;margin-left:auto;margin-right:auto'>";
    echo "<table style='width:100%' summary='ordens de impresión sin atender'>";
    echo "<thead>";
    echo ui_tr(ui_th("N°").ui_th("Nombre").ui_th("Activo"));
    echo "</thead>";
    echo "<tfoot>";
    echo "<tr><td colspan='3'>Se encontraron en total <span style='color:#00F'>$n_filas</span> metariales para impresión.  <a href='./?accion=materiales&amp;sub=registrar'>Clic aquí para registrar uno nuevo</a></td></tr>";
    echo "</tfoot>";
    echo "<tbody>";
    for($i=0; $i<$n_filas; $i++){
    $id_material  		= mysql_result($resultado,$i,"id_material");
    $material  	        = ui_input("txt_".mysql_result($resultado,$i,"id_material"),mysql_result($resultado,$i,"material")).'<input type="button" onclick="$(\'#resultados\').load(\'data/material+ajax.php?id_material='.$id_material.'&material=\'+$(\'#'."txt_".mysql_result($resultado,$i,"id_material").'\').val())" value="Ok"/>';
    $activo  	        = ui_input("chk_".mysql_result($resultado,$i,"id_material"),"1","checkbox","","",(mysql_result($resultado,$i,"activo")) ? 'checked="checked"' : '').'<input type="button" onclick="$(\'#resultados\').load(\'data/material+ajax.php?id_material='.$id_material.'&activo=\'+$(\'#'."chk_".mysql_result($resultado,$i,"id_material").'\').is(\':checked\'))" value="Ok"/>';
    echo ui_tr(ui_td($id_material).ui_td($material).ui_td($activo));
    }
    echo "</tbody>";
    echo "</table>";
    echo "<div id='resultados'></div>";
    echo "</div>";
}
?>
