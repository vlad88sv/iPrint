<?php
function DATA_reportes(){
echo "<div style='position:relative;width:95%;margin-left:auto;margin-right:auto'>";
echo "<h1>Reportes rápidos</h1>";
echo "<ul>";
echo "<li>Generar reporte de las ordenes solicitadas en el <a href='data/reportes+ajax.php?sub=generar&reporte=rapido_ordenes_mes_anterior'>mes anterior</a>, <a href='data/reportes+ajax.php?sub=generar&reporte=rapido_ordenes_mes_actual'>mes actual</a>, <a href='data/reportes+ajax.php?sub=generar&reporte=rapido_ordenes_mes_siguiente'>mes siguiente</a></li>";
echo "<li>Generar reporte de las visitas solicitadas en el <a href='data/reportes+ajax.php?sub=generar&reporte=rapido_visitas_mes_anterior'>mes anterior</a>, <a href='data/reportes+ajax.php?sub=generar&reporte=rapido_visitas_mes_actual'>mes actual</a>, <a href='data/reportes+ajax.php?sub=generar&reporte=rapido_visitas_mes_siguiente'>mes siguiente</a></li>";
echo "</ul>";
echo "</div>";
echo "<div id='punch'></div>";
}

?>
