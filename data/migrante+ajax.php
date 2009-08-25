?
require_once ("../lib/vital.php");
$tabla_d_departamento = 'ahm_data_departamentos';
$tabla_d_municipio = 'ahm_data_municipio';

if ( isset($_GET['pedido']) ) {
	switch ($_GET['pedido']) {
		case 'departamentos':
			if ( isset($_GET['pais']) ) {
			exit (ui_combobox('dep_pro_es_residencia', db_ui_opciones('id_departamento', 'nombre', $tabla_d_departamento, "WHERE id_pais='".$_GET['pais']."'")));
			}
		break;
	}
}

?>
