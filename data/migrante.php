<?php
$tablaMigrantes = 'ahm_migrantes';
$tabla_d_pais = 'ahm_data_pais';

function DATA_migrante ($sub = "registrar"){
	switch ($sub) {
		case "registrar":
			return DATA__migrante_registrar();
		break;
		case "editar":
			return DATA__migrante_editar();
		break;
		case "eliminar":
			return DATA__migrante_eliminar();
		break;
	}
}

function DATA__migrante_registrar(){
	global $tablaMigrantes;
	//print_ar ($_POST);
	//print_ar ($_FILES);

	// Verificamos si es que ya hicieron el post, para asi agregarlo a la tabla de migrantes.
	if ( isset($_POST['registrar_migrante']) ) {
		// Quitamos los campos que no queremos agregar...
		unset ($_POST['av']);
		unset ($_POST['registrar_migrante']);
		// Obtenemos la imagen subida y la inyectamos en el $_POST
		if ( $_FILES['fotografia']['error'] == 0) {
			$_POST['fotografia'] = mysql_real_escape_string(file_get_contents($_FILES['fotografia']['tmp_name']));
		}
		//print_ar ($_POST);
		if ( db_agregar_datos($tablaMigrantes, $_POST) ) {
			echo 'Datos de migrante agregados';
		} else {
			echo 'Datos de migrante NO agregados';
		}
	}

	echo '<form action="./?accion=migrante&amp;sub=registrar" enctype="multipart/form-data" method="post" >';
	echo DATA___migrante_formulario();
	echo '<input type="hidden" name="registrar_migrante" value="1" />';
	echo '<input type="submit" value="Registrar migrante" />';
	echo '</form>';
}
function DATA__migrante_editar(){}
function DATA__migrante_eliminar(){}

function DATA___migrante_formulario(){
	global $tabla_d_pais;

	$script_combobox = '
	<script>
	$(document).ready(function() {
   	$("#pais_residencia").click(
   	function() {
   		if ( $("#pais_residencia").val() != 5 ) {
   			$("#departamentos").load("data/migrante+ajax.php?orden=departamentos&pais="+$("#pais_residencia").val());
		} else {
			//alert ("Los Otros");
		}
   	}); // Fin función lambda y evento click
 	}); // Fin document.ready
 	</script>
	';

	return "
$script_combobox
<table>
<tr><td width='40%'>Nombre(s)*</td><td valign='top'><input type='text' name='nombre' id='nombre' value='' maxlength='100'></td></tr>
<tr><td>Apellido(s)*</td><td valign='top'><input type='text' name='apellidos' id='apellidos' value='' maxlength='100'></td></tr>

<tr><td>Sexo*</td><td valign='top'><select name='sexo'><option value=''>Seleccione el sexo</option><option value='1'>Femenino  </option><option value='2'>Masculino </option></select></td></tr>
<tr><td>Edad*</td><td valign='top'><input type='text' name='edad' id='edad' value='0' maxlength='2'></td></tr>

<tr><td>Estado civil*</td><td valign='top'><select name='estado_civil'><option value=''>Seleccione el estado civil</option><option value='0'>soltero</option><option value='1'>casado</option><option value='2'>divorciado</option><option value='3'>viudo</option><option value='4'>unión libre</option></select></td></tr>
<tr><td>Número de hijos*</td><td valign='top'><input type='text' name='n_hijos' id='n_hijos' value='0' maxlength='2'></td></tr>

<tr><td>Teléfono de referencia familiar</td><td valign='top'><input type='text' name='tel_ref' value='' maxlength='250'></td></tr>

<tr><td>País de origen*</td><td valign='top'>".ui_combobox('pais_residencia', db_ui_opciones('id_pais', 'nombre', $tabla_d_pais))."</td></tr>

<tr><td>Departamento/Provincia/Estado de residencia*</td><td valign='top'><div id='departamentos'>Seleccione país dep_pro_es_residencia</div></td></tr>
<tr><td>Localidad/Municipio de residencia</td><td valign='top'><div id='municipios'>Seleccione departamento loc_mun_residencia</div></td></tr>
<tr><td>Nivel de escolaridad*</td><td valign='top'><select name='nivel_escolar'><option value=''>Seleccione la escolaridad</option><option value='1'>Sin escolaridad</option><option value='2'>Primaria incompleta</option><option value='3'>Primaria completa</option><option value='4'>Secundaria incompleta</option><option value='5'>Secundaria completa</option><option value='6'>Preparatoria o nivel técnico incompeto</option><option value='7'>Preparatoria o nivel técnico completo                                                               </option><option value='8'>Al menos un grado de licenciatura                                                                   </option><option value='9'>Licenciatura terminada</option><option value='10'>Maestría</option><option value='11'>Especialidad</option><option value='12'>Doctorado</option></select></td></tr>

<tr><td>Cédula de identidad/Documento migratorio*</td><td valign='top'><select name='docu_migratorio'><option value=''>Seleccione el documento migratorio</option><option value='1'>Sin documentos</option><option value='2'>Pase local</option><option value='3'>Forma Migratoria Visitante Agrícola (FMVA)</option><option value='4'>Forma Migratoría Visitante Local (FMVL)</option><option value='5'>Pasaporte con visa de turista o Transmigrante</option><option value='6'>Inmigrante FM-2                                                                                                                                                                                                                                           </option><option value='7'>Visa de trabajo FM-3</option></select></td></tr>
<tr><td>Número en su documento de identidad</td><td valign='top'><input type='text' name='n_docu_migratorio' value='' maxlength='250'></td></tr>
<tr><td>Estado de salud en el que llega a la casa</td><td valign='top'><input type='text' name='estado_salud' value='' maxlength='250'></td></tr>

<tr><td>Religión</td><td valign='top'><input type='text' name='religion' value='' maxlength='250'></td></tr>
<tr><td>¿A qué se dedicaban en su país de origen?</td><td valign='top'><input type='text' name='ocupacion_origen' value='' maxlength='250'></td></tr>
<tr><td>Fotografía</td><td valign='top'><input type='file' name='fotografia' value='' maxlength='250'></td></tr>
<tr><td>¿Desea agregar una violación al registro?</td><td valign='top'><input type='checkbox' name='av' value='1'></td></tr>
</table>
";
}
?>
