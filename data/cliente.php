<?php
  function DATA_clientes($sub)
    {
      switch ($sub)
        {
          case 'registrar':
              if (isset($_POST['cmdEnviar']))
                {
                  unset($_POST['cmdEnviar']);
                  // Chequear validez
                  if (_F_form_cache('usuario') && (strlen(_F_form_cache('clave')) > 5) && _F_form_cache('nombre') && _F_form_cache('email'))
                    {
                      $_POST['usuario'] = strtolower($_POST['usuario']);
                      $_POST['clave'] = md5($_POST['clave']);
                      $_POST['nivel'] = _N_usuario;
                      $_POST['contraclave'] = '';
                      $_POST['avatar'] = '';
                      $_POST['u_acceso'] = time();
                      if (_F_usuario_agregar($_POST))
                        {
                          echo JS_growl("Usuario exitosamente agregado.");
                          unset($_POST);
                        }
                      else
                        {
                          echo JS_growl("Usuario NO pudo ser registrado, ya existia.");
                        }
                    }
                  else
                    {
                      echo JS_growl("Faltan datos o contraseña demasiado corta (6 caracteres minimo).");
                    }
                }
              echo "<div style='position:absolute;left:200px;width:70%'>";
              echo "<form action='./?accion=clientes&sub=registrar' method='POST'>";
              echo "<table class='limpia'>";
              echo ui_tr(ui_td("Nombre del usuario", "", "font-weight:bold") . ui_td(ui_input("usuario", _F_form_cache('usuario'))));
              echo ui_tr(ui_td("Clave del usuario", "", "font-weight:bold") . ui_td(ui_input("clave", _F_form_cache('clave'), "password")));
              echo ui_tr(ui_td("Nombre del cliente", "", "font-weight:bold") . ui_td(ui_input("nombre", _F_form_cache('nombre'))));
              echo ui_tr(ui_td("email", "", "font-weight:bold") . ui_td(ui_input("email", _F_form_cache('email'))));
              echo ui_tr(ui_td("Telefono 1") . ui_td(ui_input("telefono1", _F_form_cache('telefono1'))));
              echo ui_tr(ui_td("Telefono 2") . ui_td(ui_input("telefono2", _F_form_cache('telefono2'))));
              echo ui_tr(ui_td("Telefono 3") . ui_td(ui_input("telefono3", _F_form_cache('telefono3'))));
              //echo ui_tr(ui_td("").ui_td(ui_input(""))); //Avatar
              echo ui_tr(ui_td("Notas") . ui_td(ui_textarea("notas", _F_form_cache('notas'))));
              echo "</table>";
              echo ui_input("cmdEnviar", "Registrar", "submit");
              echo ui_input("cmdCancelar", "Cancelar", "button");
              echo "</form>";
              echo "<br /><br />
Notas:
<ol>
<li>El nombre de usuario será convertido a minúsculas</li>
<li>La contraseña debe ser mayor a 6 letras</li>
<li>Los campos en negrita son obligatorios</li>
</ol>";
              echo "</div>";
              echo JS_onload('$("#cmdCancelar").click(function(){window.location="./?accion=clientes"});');
              break;

          case "editar":
          case "materiales":
              $id_usuario = mysql_real_escape_string($_GET['usuario']);
              $c = "SELECT id_material, material, coalesce((SELECT costo FROM ahm_materiales_indv AS b WHERE id_usuario='$id_usuario' and a.id_material = b.id_material),0) AS costo, (SELECT activo FROM ahm_materiales_indv AS b WHERE id_usuario='$id_usuario' and a.id_material = b.id_material) as activo FROM ahm_materiales AS a;";
              $resultado = db_consultar($c);
              $n_filas = mysql_num_rows($resultado);
              echo "<div style='position:relative;width:90%;margin-left:auto;margin-right:auto'>";
              echo "<form action='./?accion=clientes' method='POST'>";
              echo "<table style='width:100%' summary='Materiales disponibles'>";
              echo "<thead>";
              echo ui_tr(ui_th("Código") . ui_th("Material") . ui_th("Coto ($)") . ui_th("Activo"));
              echo "</thead>";
              echo "<tfoot>";
              echo "<tr><td colspan='8'>Se encontraron en total <span style='color:#00F'>$n_filas</span> materiales disponibles. " . ui_input("cmdGrabarMateriales", "Grabar lista de materiales", "submit") . ui_input("cmdCancelar", "Cancelar", "button", "", "", 'onclick="window.location=\'./?accion=clientes\'"') . "</td></tr>";
              echo "</tfoot>";
              echo "<tbody>";
              for ($i = 0; $i < $n_filas; $i++)
                {
                  $id_material = mysql_result($resultado, $i, "id_material");
                  $material = mysql_result($resultado, $i, "material");
                  $costo = ui_input("txt_" . mysql_result($resultado, $i, "id_material"), mysql_result($resultado, $i, "costo"));
                  $activo = ui_input("chk_" . mysql_result($resultado, $i, "id_material"), "1", "checkbox", "", "", (mysql_result($resultado, $i, "activo")) ? 'checked="checked"' : '');
                  echo ui_tr(ui_td($id_material) . ui_td($material) . ui_td($costo) . ui_td($activo));
                }
              echo "</tbody>";
              echo "</table>";
              echo ui_input('id_usuario', $_GET['usuario'], 'hidden');
              echo "</form>";
              echo "</div>";
              break;

          default:
              echo "<div style='position:relative;width:90%;margin-left:auto;margin-right:auto'>";
              //**************************************************************************
              // ¿Será que necesitamos registrar los materiales?
              //__________________________________________________________________________
              if (isset($_POST['cmdGrabarMateriales']) && isset($_POST['id_usuario']))
                {
                  // echo print_ar($_POST);
                  // Primero nos deshacemos de los materiales a su cuenta.
                  $id_usuario = mysql_real_escape_string($_POST['id_usuario']);
                  $c = "DELETE FROM ahm_materiales_indv WHERE id_usuario='$id_usuario'";
                  db_consultar($c);
                  foreach ($_POST as $key => $value)
                    {
                      if (ereg("^txt_([0-9]+)$", $key, $reg_))
                        {
                          $activo = isset($_POST['chk_' . $reg_[1]]) ? 1 : 0;
                          db_agregar_datos("ahm_materiales_indv", array("id_usuario" => $id_usuario, "id_material" => $reg_[1], "costo" => $_POST['txt_' . $reg_[1]], "activo" => $activo));
                        }
                    }
                  echo "<b>Los materiales fueron registrados.</b><br /><br />";
                }
              //**************************************************************************

              $c = "SELECT id_usuario, usuario, nombre, email, u_acceso FROM ahm_usuarios";
              $resultado = db_consultar($c);
              $n_filas = mysql_num_rows($resultado);

              echo "<table style='width:100%' summary='Lista de clientes'>";
              echo "<thead>";
              echo ui_tr(ui_th("N°") . ui_th("Usuario") . ui_th("Nombre") . ui_th("email") . ui_th("Acceso") . ui_th("Acción"));
              echo "</thead>";
              echo "<tfoot>";
              echo "<tr><td colspan='8'>Se encontraron en total <span style='color:#00F'>$n_filas</span> usuarios/clientes registrados. <a href='./?accion=clientes&amp;sub=registrar'>Clic aquí para registrar uno nuevo</a></td></tr>";
              echo "</tfoot>";
              echo "<tbody>";
              for ($i = 0; $i < $n_filas; $i++)
                {
                  $id_usuario = mysql_result($resultado, $i, "id_usuario");
                  $usuario = mysql_result($resultado, $i, "usuario");
                  $nombre = mysql_result($resultado, $i, "nombre");
                  $email = mysql_result($resultado, $i, "email");
                  $u_acceso = date("h:ia @ d/m/Y", mysql_result($resultado, $i, "u_acceso"));
                  $Accion = "[<a href='./?accion=clientes&amp;sub=editar&amp;usuario=$id_usuario'>Modificar</a>][<a href='./?accion=clientes&amp;sub=materiales&amp;usuario=$id_usuario'>Materiales</a>]";
                  echo ui_tr(ui_td($id_usuario) . ui_td($usuario) . ui_td($nombre) . ui_td($email) . ui_td($u_acceso) . ui_td($Accion));
                }
              echo "</tbody>";
              echo "</table>";
              echo "</div>";
        }
    }

  function _F_form_cache($campo)
    {
      if (!isset($_POST))
          return '';
      if (array_key_exists($campo, $_POST))
        {
          return $_POST[$campo];
        }
      else
        {
          return '';
        }
    }
?>
