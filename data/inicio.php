<?php
  // Función que decide entre mostrar la pantalla de identificación y mostrar una bienvenida básica

  function DATA_inicio()
    {
      $identificado = isset($_SESSION['autenticado']) && isset($_SESSION['cache_datos_usuario']);
      if ($identificado)
        {
          DATA__identificado();
        }
      else
        {
          DATA__identificar();
        }
    }

  function DATA__identificado()
    {
      switch (_F_usuario_cache('nivel'))
        {
          case _N_usuario:
              echo ui_barra_lateral("Ahorro acumulado", "$2,000.00", "Metros cuadrados impresos", "700.00", "Dias transcurridos del contrato / restantes", "10 / 365");
              echo "<div style='position:absolute;left:200px;width:70%'>";
              echo "
    <table class='limpia' style='width:auto'>
    <tr>
        <td align='center'ui_input valign='top' width='48'><img src='img/Nuevo.png' border='0' /></td>
        <td valign='top'ui_input >
        <a href='./?accion=orden' >Nueva orden</a><br>
        Use esta opción si desea solicitar una nueva orden impresión.
        </td>
    </tr>

    <tr>
        <td align='center'ui_input valign='top' width='48'><img src='img/Historial.png' border='0' /></td>
        <td valign='top'ui_input >
        <a href='./?accion=historial' >Ver historial de ordenes</a><br>
        Muestra las ordenes de impresión solicitadas y su estado actual.
        </td>
    </tr>

    <tr>
        <td align='center'ui_input valign='top' width='48'><img src='img/Visita.png' border='0' /></td>
        <td valign='top'ui_input >
        <a href='./?accion=visita' >Solicitar visita</a><br>
        Le permite solicitar una cita para ser visitado por el personal altamente capacitado de I·Print.
        </td>
    </tr>

    <tr>
        <td align='center'ui_input valign='top' width='48'><img src='img/Reportes.png' border='0' /></td>
        <td valign='top'ui_input >
        <a href='./?accion=reporte' >Reportes</a><br>
        Genera reportes específicos de sus citas e impresiones realizadas.
        </td>
    </tr>

    <tr>
        <td align='center'ui_input valign='top' width='48'><img src='img/Salir.png' border='0' /></td>
        <td valign='top'ui_input >
        <a href='./?accion=salir'>Salir</a><br>
        Utilizela para terminar su uso del sistema, esto evita el posible uso indebido de su cuenta por terceros.
        </td>
    </tr>
    </table>
      ";
              echo "</div>";
              break;

          case _N_administrador:
              echo "<div style='position:relative;width:95%;margin-left:auto;margin-right:auto;'>";
              echo "
    <table class='limpia' style='width:auto'>
    <tr>
        <td align='center'ui_input valign='top' width='48'><img src='img/Usuario.png' border='0' /></td>
        <td valign='top'ui_input >
        <a href='./?accion=clientes' >Clientes</a><br>
        Agregar datos de acceso al sistema para un nuevo cliente o verificar los actuales.
        </td>
    </tr>

    <tr>
        <td align='center'ui_input valign='top' width='48'><img src='img/Material.png' border='0' /></td>
        <td valign='top'ui_input >
        <a href='./?accion=materiales' >Materiales</a><br>
        Agregar, quitar o modificar la lista de materiales a ofrecer.
        </td>
    </tr>

    <tr>
        <td align='center'ui_input valign='top' width='48'><img src='img/Nuevo.png' border='0' /></td>
        <td valign='top'ui_input >
        <a href='./?accion=orden' >Revisar ordenes</a><br>
        Use esta opción si desea revisar las ordenes pendientes.
        </td>
    </tr>

    <tr>
        <td align='center'ui_input valign='top' width='48'><img src='img/Historial.png' border='0' /></td>
        <td valign='top'ui_input >
        <a href='./?accion=historial' >Ver historial de ordenes</a><br>
        Muestra las ordenes de impresión solicitadas y su estado actual.
        </td>
    </tr>

    <tr>
        <td align='center'ui_input valign='top' width='48'><img src='img/Visita.png' border='0' /></td>
        <td valign='top'ui_input >
        <a href='./?accion=visita' >Revisar visitas</a><br>
        Le permite ver las visitas pendientes y administrarlas.
        </td>
    </tr>

    <tr>
        <td align='center'ui_input valign='top' width='48'><img src='img/Reportes.png' border='0' /></td>
        <td valign='top'ui_input >
        <a href='./?accion=reporte' >Reportes</a><br>
        Genera reportes específicos de las visitas e impresiones realizadas.
        </td>
    </tr>

    <tr>
        <td align='center'ui_input valign='top' width='48'><img src='img/Telefono.png' border='0' /></td>
        <td valign='top'ui_input >
        <a href='./?accion=notificaciones' >Números y emails</a><br>
        Lista de números celulares y correos electrónicos a los cúales serán enviados los avisos.
        </td>
    </tr>
      ";
              echo "<b>¡Atención!</b> el servidor esta configurado para aceptar un tamaño máximo para carga de archivos igual a <b>" . ini_get('upload_max_filesize') . "</b>";
              echo "</div>";

              break;
        }
    }

  function DATA__identificar()
    {
      $mensaje = '';
      //Será que ya envió el POST?, validemos los datos.
      if (isset($_POST['usuario']) && isset($_POST['clave']))
        {
          $ret = _F_usuario_acceder($_POST['usuario'], $_POST['clave']);
          switch ($ret)
            {
              case 1:
                  header("location: ./");
                  return;
                  break;
              case - 1:
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
      echo '<div id="bg"><img src="fondo.jpg" width="100%" height="100%" alt="" /></div>';
      // Fonde de formulario
      echo '<div id="capa_impresora">';
      // Mostrar pantalla de acceso
      echo '
<form action="./" method="post">
<fieldset style="border:0">
Usuario<br /><input type="text" name="usuario" class="i2" value="" /><br /><br />
Clave<br /><input type="password"  name="clave" class="i2" value="" /><br /><br />
<input type="submit" name="ingresar" value="Clic aquí para ingresar" />
<input type="hidden" name="sublogin" value="1" />
</fieldset>
</form>
<div style="color:#F00;font-weight:bolder;margin-top:225px">' . $mensaje . '</div>
';
      echo '</div>';
    }
?>
