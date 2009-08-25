<?php
// Función que decide entre mostrar la pantalla de identificación y mostrar una bienvenida básica

function DATA_inicio(){
	$identificado = isset($_SESSION['autenticado']) && isset($_SESSION['cache_datos_usuario']);
	if ( $identificado ) {
		DATA__identificado();
	} else {
		DATA__identificar();
	}
}

function DATA__identificado() {
	echo "
	<div style='width:75%;margin-left:auto;margin-right:auto'>
            <table>
            <tr height='100'>
                <td align='center' class='limpio' valign='top' width='90'><img src='img/Nuevo.png' border='0' /></td>
                <td valign='top' class='limpio' >
                <a href='./?accion=orden&amp;sub=registrar' >Nueva orden</a><br>
                Permite crear una nueva orden de impresión.
                </td>
            </tr>

	<tr height='100'>
                <td align='center' class='limpio' valign='top' width='90'><img src='img/Historial.png' border='0' /></td>
                <td valign='top' class='limpio' >
                <a href='./?accion=historial&amp;sub=buscar' >Ver historial de ordenes</a><br>
                Muestra las ordenes efectuadas y su estado.
                </td>
           </tr>

	<tr height='100'>
                <td align='center' class='limpio' valign='top' width='90'><img src='img/Visita.png' border='0' /></td>
                <td valign='top' class='limpio' >
                <a href='./?accion=visita&amp;sub=buscar' >Solicitar visita</a><br>
                Le permite reservar una cita para visitar las instalaciones de I·Print.
                </td>
           </tr>

	<tr height='100'>
                <td align='center' class='limpio' valign='top' width='90'><img src='img/Reportes.png' border='0' /></td>
                <td valign='top' class='limpio' >
                <a href='./?accion=reporte&amp;sub=buscar' >Reportes</a><br>
                Genera reportes específicos de sus citas e impresiones realizadas.
                </td>
           </tr>
           
           <tr height='100'>
                <td align='center' class='limpio' valign='top' width='90'><img src='img/Salir.png' border='0' /></td>
                <td valign='top' class='limpio' >
                <a href='./?accion=salir'>Salir</a><br>
                Terminar la sesión, siempre utilizela cuando ya no utilice el sistema para evitar el uso indebido de su cuenta por terceros.
                </td>
            </tr>
            </table>
            </div>
	";
}

function DATA__identificar() {
	$mensaje = '';
	//Será que ya envió el POST?, validemos los datos.
	if ( isset($_POST['usuario']) && isset($_POST['clave']) ){
		switch (_F_usuario_acceder($_POST['usuario'],$_POST['clave']) ){
		    case 1:
			header("location: ./");
			return;
		    break;
		    case -1:
			$mensaje = "Datos incorrectos!";
		    break;
		    case 0:
			$mensaje = "Error general de falla de acceso";
		    break;
		    default:
			$mensaje = "Sucedió un error desconocido";
		    break;
		}
		
	}
	// Fondo de acceso
	echo '<div id="bg"><img src="fondo.jpg" width="100%" height="100%" alt=""></div>';
	// Mostrar pantalla de acceso
	echo '
<div style=" background-image: url(\'img/Impresora2.png\');margin-top:100px;height:501px; width:600px;background-repeat:no-repeat;margin-left:auto;margin-right:auto;text-align:center;padding-top:10px" />
<form action="./" method="post">
Usuario<br /><input type="text" name="usuario" class="i2" value="" /><br /><br />
Clave<br /><input type="password"  name="clave" class="i2" value="" /><br /><br />
<input type="submit" name="ingresar" value="Clic aquí para ingresar" />
<input type="hidden" name="sublogin" value="1">
</form>
<div style="margin-top:10px;height:225px; width:600px;background-repeat:no-repeat;margin-left:auto;margin-right:auto;text-align:center;padding-top:10px;color:#FF0000" /></div>
<span style="color:#F00;font-weight:bolder">'.$mensaje.'</span>
</div>
';
}
?>
