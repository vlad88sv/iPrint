<?php
  // Este es un wrapper para body, utilizado desde el indice para mostrar el contenido adecuado en base a la solicitud GET.

  //======================================================================
  // Primero intrepetamos lo que se ha orden.
  //______________________________________________________________________

  if (!isset($_GET["accion"]) || !isset($_SESSION['autenticado']))
    {
      $_GET['accion'] = 'iniciar';
    }
  //if (!isset($_GET["accion"]) || !isset($_SESSION['autenticado']))

  $accion = $_GET['accion'];

  switch ($accion)
    {
      case 'salir':
          _F_sesion_cerrar();
          break;
      case 'iniciar':
          require_once("inicio.php");
          echo DATA_inicio();
          break;
      case 'orden':
          if (_F_usuario_cache('nivel') == _N_administrador || _F_usuario_cache('nivel') == _N_usuario)
            {
              require_once("orden.php");
              $sub = isset($_GET['sub']) ? $_GET['sub'] : "";
              echo DATA_orden($sub);
              break;
            }
          //if (_F_usuario_cache('nivel') == _N_administrador || _F_usuario_cache('nivel') == _N_usuario)
      case 'historial':
          if (_F_usuario_cache('nivel') == _N_administrador || _F_usuario_cache('nivel') == _N_usuario)
            {
              require_once("historial.php");
              $sub = isset($_GET['sub']) ? $_GET['sub'] : "";
              echo DATA_historial($sub);
              break;
            }
          //if (_F_usuario_cache('nivel') == _N_administrador || _F_usuario_cache('nivel') == _N_usuario)
      case 'visita':
          if (_F_usuario_cache('nivel') == _N_administrador || _F_usuario_cache('nivel') == _N_usuario)
            {
              require_once("visita.php");
              $sub = isset($_GET['sub']) ? $_GET['sub'] : "";
              echo DATA_visita($sub);
              break;
            }
          //if (_F_usuario_cache('nivel') == _N_administrador || _F_usuario_cache('nivel') == _N_usuario)
      case 'reportes':
          if (_F_usuario_cache('nivel') == _N_administrador || _F_usuario_cache('nivel') == _N_usuario)
            {
              require_once("reportes.php");
              $sub = isset($_GET['sub']) ? $_GET['sub'] : "";
              echo DATA_reportes($sub);
              break;
            }
          //if (_F_usuario_cache('nivel') == _N_administrador || _F_usuario_cache('nivel') == _N_usuario)
      case 'comentarios':
          if (_F_usuario_cache('nivel') == _N_administrador || _F_usuario_cache('nivel') == _N_usuario)
            {
              require_once("comentarios.php");
              $sub = isset($_GET['sub']) ? $_GET['sub'] : "";
              echo DATA_comentarios($sub);
              break;
            }
          //if (_F_usuario_cache('nivel') == _N_administrador || _F_usuario_cache('nivel') == _N_usuario)
      case 'clientes':
          if (_F_usuario_cache('nivel') == _N_administrador)
            {
              require_once("cliente.php");
              $sub = isset($_GET['sub']) ? $_GET['sub'] : "";
              echo DATA_clientes($sub);
              break;
            }
          //if (_F_usuario_cache('nivel') == _N_administrador)
      case 'materiales':
          if (_F_usuario_cache('nivel') == _N_administrador)
            {
              require_once("material.php");
              $sub = isset($_GET['sub']) ? $_GET['sub'] : "";
              echo DATA_material($sub);
              break;
            }
          //if (_F_usuario_cache('nivel') == _N_administrador)
      default:
          echo DATA_pagina_no_encontrada();
    }
  //switch ($accion)

  //======================================================================

  function DATA_pagina_no_encontrada()
    {
      return '¡Error!<br />
¡Lo sentimos pero Ud. ha intentado ingresar a un área de esta web que no existe!.<br />
Ud. intentó ingresar a <b>"' . htmlspecialchars(urldecode($_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'])) . '"</b>.<br />
<br />Sin embargo un reporte de error fue generado y enviado automáticamente a los administradores de para corroborar el problema.<br />
<a href="./">Regresar a la página de inicio</a>';
    }
  //function DATA_pagina_no_encontrada()
?>
