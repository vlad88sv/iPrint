<?php
  require_once("../lib/vital.php");
  if (!isset($_POST['opcion']))
      return;
  $andUsuario = (_F_usuario_cache('nivel') == _N_administrador) ? "" : "AND id_usuario='" . _F_usuario_cache('id_usuario') . "'";
  $c = $resultado = "";
  switch ($_POST['opcion'])
    {
      case 'todo':
          $onClick = "onclick=\'$(\"#resultado_historial\").load(\"data/historial+ajax.php\",{opcion:\"anio\",anio:\"', YEAR(txtFechaEntrega), '\"})\'";
          $c = "SELECT DISTINCT CONCAT('<a $onClick>',YEAR(txtFechaEntrega), '</a>') AS 'Año', CONCAT('$', SUM(lblCosto)) AS 'Precio costo del año', CONCAT('$', SUM(lblCosto) * 0.10)  AS 'Ahorro del año' FROM ahm_ordenes AS a WHERE 1 $andUsuario GROUP BY YEAR(txtFechaEntrega)";
          break;
      case 'anio':
          if (!isset($_POST['anio']))
              return;
          $andAnio = $_POST['anio'] ? "AND YEAR(txtFechaEntrega)='" . db_codex($_POST['anio']) . "'" : "";
          $onClick = "onclick=\'$(\"#resultado_historial\").load(\"data/historial+ajax.php\",{opcion:\"mes\",anio:\"', YEAR(txtFechaEntrega), '\",mes:\"', MONTH(txtFechaEntrega), '\"})\'";
          $c = "SELECT DISTINCT YEAR(txtFechaEntrega) AS 'Año', CONCAT('<a $onClick>',MONTHNAME(txtFechaEntrega),'</a>') AS 'Mes', CONCAT('$', SUM(lblCosto)) AS 'Precio costo del mes', CONCAT('$', SUM(lblCosto) * 0.10)  AS 'Ahorro del mes' FROM ahm_ordenes AS a WHERE 1 $andAnio $andUsuario GROUP BY MONTH(txtFechaEntrega)";
          break;
          case 'mes';
          if (!isset($_POST['anio']) || !isset($_POST['mes']))
              return;
          $andAnio = $_POST['anio'] ? "AND YEAR(txtFechaEntrega)='" . db_codex($_POST['anio']) . "'" : "";
          $andMes = $_POST['mes'] ? "AND MONTH(txtFechaEntrega)='" . db_codex($_POST['mes']) . "'" : "";
          $onClick = "onclick=\'$(\"#resultado_historial\").load(\"data/historial+ajax.php\",{opcion:\"dia\",anio:\"', YEAR(txtFechaEntrega), '\",mes:\"', MONTH(txtFechaEntrega), '\",dia:\"', DAY(txtFechaEntrega), '\"})\'";
          $c = "SELECT DISTINCT YEAR(txtFechaEntrega) AS 'Año', MONTHNAME(txtFechaEntrega) AS 'Mes', CONCAT('<a $onClick>',DAY(txtFechaEntrega),'</a>') AS 'Día', CONCAT('$', SUM(lblCosto)) AS 'Precio costo del día', CONCAT('$', SUM(lblCosto) * 0.10)  AS 'Ahorro del día' FROM ahm_ordenes AS a WHERE 1 $andAnio $andMes $andUsuario GROUP BY DAY(txtFechaEntrega)";
          break;
          case 'dia';
          if (!isset($_POST['anio']) || !isset($_POST['mes']) || !isset($_POST['dia']))
              return;
          $andAnio = $_POST['anio'] ? "AND YEAR(txtFechaEntrega)='" . db_codex($_POST['anio']) . "'" : "";
          $andMes = $_POST['mes'] ? "AND MONTH(txtFechaEntrega)='" . db_codex($_POST['mes']) . "'" : "";
          $andDia = $_POST['dia'] ? "AND DAY(txtFechaEntrega)='" . db_codex($_POST['dia']) . "'" : "";
          $onClick = "onclick=\'$(\"#resultado_historial\").load(\"data/historial+ajax.php\",{opcion:\"orden\",id_orden:\"', id_orden, '\"})\'";
          $c = "SELECT CONCAT('<a $onClick>',id_orden,'</a>') AS 'N° orden', validacion AS 'N° Validación', CONCAT('$', lblCosto) AS 'Precio costo de orden', CONCAT('$', lblCosto * 0.10) AS 'Ahorro de la orden' FROM ahm_ordenes AS a WHERE 1 $andAnio $andMes $andDia $andUsuario";
          break;
      case 'orden':
          if (!isset($_POST['id_orden']))
              return;
          $c = "SELECT id_orden AS 'N° orden Global', id_orden_indv AS 'N° orden', validacion AS 'N° Validación', lblCosto AS 'Costo', CONCAT('$', lblCosto * 0.10)  AS 'Ahorro', cmbListaMateriales AS 'Material', txtOtroMaterial AS 'Detalle Material', txtCantidad AS 'Número de copias', CONCAT(txtMedidaImpresionAncho,cmbSisMetricoAncho,'x',txtMedidaImpresionLargo,cmbSisMetricoLargo) as 'Medidas', optNoSi AS 'Escala', txtFechaOrden AS 'Fecha Orden', txtFechaEntrega AS 'Fecha Entrega', txtFechaEntregado AS 'Fecha Entregado', txtNotas AS 'Notas', CONCAT('<a target=\"_blank\" href=\"+',flArchivo, '\">', flArchivo, '</a>') AS 'Archivo', estado AS 'Estado' FROM ahm_ordenes AS a WHERE 1 $andUsuario";
    }

  $resultado = db_consultar($c);
  if (($resultado) && ($_POST['opcion'] != 'orden'))
    {
      echo db_ui_tabla($resultado, 'style="width:100%" class="historial"');
    }
  else
    {
      echo db_ui_tabla_vertical($resultado);
    }
?>
