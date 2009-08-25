<?php
  function DATA_comentarios()
    {
      echo "<div style='position:absolute;padding:0;height:100%;width:100%;text-align:center'>";
      if (isset($_SESSION['autenticado']) && isset($_POST['txtComentarios']))
        {
          $datos['id_usuario'] = _F_usuario_cache('id_usuario');
          $datos['comentario'] = db_codex($_POST['txtComentarios']);
          $datos['tipo'] = isset($_POST['chkTipo']) ? 1 : 0;
          $datos['fecha'] = mysql_date();
          $ret = db_agregar_datos('ahm_comentarios', $datos);
          if ($ret > 0)
            {
              echo "¡Gracias por su comentario!.<br />";
              despachar_notificaciones('Se ha ingresado un nuevo comentario en IPrint por ' . _F_usuario_cache('nombre'));
            }
          else
            {
              echo "Su comentario no pudo ser agregado, por favor intente luego.<br />";
            }
        }
      if (_F_usuario_cache('nivel') != _N_administrador)
        {
          echo "<form action='./?accion=comentarios' method='POST'>";
          echo "<b>Ingresar comentario</b><br />";
          echo ui_textarea("txtComentarios", "", "", "width:50%") . "<br />";
          echo ui_input("chkTipo", "1", "checkbox") . " Hacer de este comentario una consulta privada";
          echo "<br />";
          echo ui_input("cmdEnviar", "Enviar comentario", "submit");
          echo "</form>";
          echo "<br />";
          echo "<br />";
        }
      echo "<center><div style='postion:absolute;width:90%;border:2px solid #00a6e0'>";
      if (_F_usuario_cache('nivel') == _N_administrador)
        {
          $c = "SELECT id_comentario AS 'N°', (SELECT nombre FROM ahm_usuarios as b WHERE b.id_usuario = a.id_usuario) AS Cliente, Comentario, CASE tipo WHEN '0' THEN 'Público' WHEN '1' THEN 'Privado' END AS Tipo, fecha AS Fecha, CONCAT(\"<a href='./?accion=comentarios&amp;sub=eliminar&amp;comentario=\", id_comentario, \"'>Eliminar</a>\") AS Accion FROM ahm_comentarios AS a";
        }
      else
        {
          $c = "SELECT (SELECT nombre FROM ahm_usuarios as b WHERE b.id_usuario = a.id_usuario) as 'Nombre', comentario AS 'Comentario', fecha AS 'Fecha' FROM ahm_comentarios AS a WHERE tipo = 0";
        }

      $resultado = db_consultar($c);
      echo db_ui_tabla($resultado, 'style="width:100%"');
      echo "</div></center>";
      echo "</div>";
    }
?>
