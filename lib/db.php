<?php
$db_link = NULL;
db_conectar(); // Iniciamos la conexión a la base de datos.

function db_conectar(){
	global $db_link;
	$db_link = @mysql_connect(db__host, db__usuario, db__clave) or die("Fue imposible conectarse a la base de datos, posiblemente no ha ejecutado el instalador (instalar.php) de " . _NOMBRE_ . " correctamente.<br /><hr />Detalles del error:<pre>" . mysql_error() . "</pre>");
	mysql_select_db(db__db, $db_link) or die("Imposible seleccionar la base de datos: ". mysql_error());
	mysql_query("set lc_time_names='es_ES'", $db_link);
}

function db_consultar($consulta){
	global $db_link;
	if ( !$db_link ) {
		db_conectar();
	}
	$resultado = @mysql_query($consulta, $db_link);
	if ( mysql_error($db_link) ) {
		echo '<pre>MySQL:' . mysql_error() . '</pre>';
	}
	return $resultado;
}
function db_codex($datos){
	global $db_link;
	if ( !$db_link ) {
		db_conectar();
	}
	return mysql_real_escape_string($datos, $db_link);
}
function db_crear_tabla($tabla, $campos, $botarPrimero=false){
	$salida = "";
	if ( $botarPrimero ) {
		if ( db_consultar ("DROP TABLE IF EXISTS $tabla") ) {
			$salida .= "Tabla '$tabla' botada"."<br />";
		} else {
			$salida .= "Tabla '$tabla' no pudo ser botada"."<br />";
		}
	}
	if ( db_consultar ("CREATE TABLE IF NOT EXISTS $tabla ($campos)") ) {
		$salida .= "Tabla '$tabla' creada"."<br />";
	} else {
		$salida .= "Tabla '$tabla' no pudo ser creada"."<br />";
	}
	return $salida;
}

function db_agregar_datos($tabla, $datos) {
	global $db_link;
	$campos = $valores = NULL;
	foreach ($datos as $clave => $valor) {
		//echo "clave: $clave; valor: $valor<br />\n";
		$arr_campos[] 	= mysql_real_escape_string($clave);
		$arr_valores[] 	= mysql_real_escape_string($valor);
	}
	$campos = implode (",", $arr_campos);
	$valores = "'".implode ("','", $arr_valores)."'";
	$c = "INSERT INTO $tabla ($campos) VALUES ($valores)";
	$resultado = db_consultar ($c);
	$id = @mysql_insert_id ($db_link);
	DEPURAR ($c, 0);
	return $id;
}

function db_resultado($resultado, $campo, $posicion='0'){
	return @mysql_result($resultado, $posicion, $campo);
}

function db_fila_a_array($resultado, $posicion='0'){
 $_arr = NULL;
 $n_campos = mysql_num_fields($resultado);
 $r = mysql_fetch_row($resultado);
 for ($i = 0; $i < $n_campos; $i++) {
	 $clave = mysql_field_name($resultado, $i);
	 $valor = $r[$i];
	 $_arr[$clave] = $valor;
 }
 return $_arr;
}

function db_ui_opciones($clave, $valor, $tabla, $cuales="", $orden="", $grupo_ui="", $seleccionada="") {
 $html = NULL;
 //La función es crear un combobox con name=id=$clave y value=$valor y HTML a partir de un SELECT $clave, $valor FROM $tabla
 $c = "SELECT $clave, $valor FROM $tabla $cuales $orden";
 DEPURAR ($c, 0);
 $resultado = db_consultar ($c);
 $n_campos = mysql_num_rows($resultado);
 if ( $grupo_ui ) {
 	$html .= "<optgroup label='$grupo_ui'>";
 }
for ($i = 0; $i < $n_campos; $i++) {
  $t_clave = mysql_result($resultado, $i, $clave);
  $t_valor = mysql_result($resultado, $i, $valor);
  if ($t_clave == $seleccionada) {
	  $selected = ' selected="selected"';
  } else {
	  $selected = "";
  }
  $html .= '<option value="' . $t_clave . '"' . $selected . '>' . $t_valor . '</option>';
}
return $html;
}

function db_ui_tabla($resultado, $CSS="") {
 global $db_link;
 if ( !mysql_num_rows($resultado) ) {
 	return "No se encontraron datos";
 }

 $table = "";
 $table .= "<table $CSS>\n\n";
 $noFields = mysql_num_fields($resultado);
 $table .= "<tr>\n";
 for ($i = 0; $i < $noFields; $i++) {
 $field = mysql_field_name($resultado, $i);
 $table .= "\t<th>$field</th>\n";
 }
 $table .= "</tr>\n";
 while ($r = mysql_fetch_row($resultado)) {
 $table .= "<tr>\n";
 foreach ($r as $column) {
 $table .= "\t<td>$column</td>\n";
 }
 $table .= "</tr>\n";
 }
 $table .= "</table>\n\n";
 return $table;
 }

function db_ui_tabla_vertical($resultado, $CSS="") {
 global $db_link;
 if ( !mysql_num_rows($resultado) ) {
 	return "No se encontraron datos";
 }

 $table = "";
 $table .= "<table $CSS>\n\n";
 $noFields = mysql_num_fields($resultado);
 $r = mysql_fetch_row($resultado);

 for ($i = 0; $i < $noFields; $i++) {
 $field = mysql_field_name($resultado, $i);
 $table .= "<tr>";
 $table .= "<td>$field</td><td>".$r[$i]."</td>";
 $table .= "</tr>\n";
 }
 return $table;
 }

function db_t_where($clave, $comparador, $valor, $prefijo = "", $sufijo = ""){
	return " AND $clave $comparador '$prefijo".mysql_real_escape_string($valor)."$sufijo'";
}
?>
