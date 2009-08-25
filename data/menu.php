<?php
$menu = '';
//print_ar($_SESSION);
// Solo mostramos el menú si el usuario esta autenticado
if ( isset($_SESSION['autenticado']) ) {
	//Iniciamos el menú.
	$menu .= '<div class="chromestyle" id="chromemenu"><ul>';
	// Mostramos el menú de acuerdo con el nivel de acceso del usuario
	switch (_F_usuario_cache('nivel')){
		//Nivel Administrador
		case _N_administrador:
			$menu .= '<li><a href="./"><img src="img/b_inicio.png" alt="Inicio" /></a></li>';
			$menu .= '<li><a href="./?accion=orden&amp;sub=revisar"><img src="img/b_orden_admin.png" alt="Nueva orden" /></a></li>';
			$menu .= '<li><a href="./?accion=historial"><img src="img/b_historial.png" alt="Ver historial de ordenes" /></a></li>';
			$menu .= '<li><a href="./?accion=visita"><img src="img/b_visita_admin.png" alt="Ver visitas" /></a></li>';
			$menu .= '<li><a href="./?accion=reportes"><img src="img/b_reportes.png" alt="Reportes" /></a></li>';
			$menu .= '<li><a href="./?accion=comentarios"><img src="img/b_comentarios.png" alt="Comentarios" /></a></li>';
			$menu .= '<li><a href="./?accion=salir"><img src="img/b_salir.png" alt="Salir" /></a></li>';
		break;
		case _N_usuario:
			$menu .= '<li><a href="./"><img src="img/b_inicio.png" alt="Inicio" /></a></li>';
			$menu .= '<li><a href="./?accion=orden&amp;sub=registrar"><img src="img/b_orden.png" alt="Nueva orden" /></a></li>';
			$menu .= '<li><a href="./?accion=historial"><img src="img/b_historial.png" alt="Ver historial de ordenes" /></a></li>';
			$menu .= '<li><a href="./?accion=visita"><img src="img/b_visita.png" alt="Solicitar visita" /></a></li>';
			$menu .= '<li><a href="./?accion=reportes"><img src="img/b_reportes.png" alt="Reportes" /></a></li>';
			$menu .= '<li><a href="./?accion=comentarios"><img src="img/b_comentarios.png" alt="Comentarios" /></a></li>';
			$menu .= '<li><a href="./?accion=salir"><img src="img/b_salir.png" alt="Salir" /></a></li>';
		break;
	}
	//Cerramos el menú.
	$menu .= '</ul></div>';
}
echo $menu;
?>
