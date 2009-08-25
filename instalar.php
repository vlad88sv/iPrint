<?php require_once ('lib/vital.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Impresiones·al·costo - I·Print</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="generator" content="vlad@todosv.com" />
    <link rel="stylesheet" type="text/css" href="estilo.css" />
</head>

<body>
<?php
// Tabla de usuarios
$campos = "id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY, usuario VARCHAR(100), clave VARCHAR(32) not null, nombre VARCHAR(32) not null, email VARCHAR(50), telefono1 VARCHAR(20) not null, telefono2 VARCHAR(20), telefono3 VARCHAR(20), avatar INT, notas TEXT, nivel TINYINT UNSIGNED NOT NULL, contraclave VARCHAR(32), u_acceso int(11) unsigned not null";
echo db_crear_tabla("ahm_usuarios", $campos, true);

// Agregamos al usuario Admin
$usuario['usuario']	= 'admin';
$usuario['clave'] 	= md5('admin');
$usuario['nombre'] 	= 'Administrador principal';
$usuario['email'] 	= 'webmaster@localhost';
$usuario['nivel'] 	= _N_administrador;
$usuario['u_acceso'] 	= time();
_F_usuario_agregar ($usuario);

// Agregamos al usuario Usuario
$usuario['usuario'] 	= 'usuario';
$usuario['clave'] 	= md5('usuario');
$usuario['nombre'] 	= 'Usuario ejemplo';
$usuario['email'] 	= 'usuario@localhost';
$usuario['nivel'] 	= _N_usuario;
$usuario['u_acceso'] 	= time();
_F_usuario_agregar ($usuario);
unset ($usuario);

// Tabla de ordenes
$campos = "id_orden INT NOT NULL AUTO_INCREMENT PRIMARY KEY, id_orden_indv INT DEFAULT 0, validacion INT, id_usuario INT, cmbListaMateriales INT, txtOtroMaterial VARCHAR(255), txtCantidad INT, txtMedidaImpresionAncho INT, cmbSisMetricoAncho VARCHAR(10), txtMedidaImpresionLargo INT, cmbSisMetricoLargo VARCHAR(10), optNoSi TINYINT, txtFechaOrden DATETIME, txtFechaEntrega DATETIME, txtFechaEntregado DATETIME, lblCosto DOUBLE PRECISION, txtNotas TEXT, flArchivo VARCHAR(500), estado INT";
echo db_crear_tabla("ahm_ordenes", $campos, true);

// Tabla de solicitud de visitas
$campos = "id_visita INT NOT NULL AUTO_INCREMENT PRIMARY KEY, validacion INT, id_usuario INT, FechaVisita DATETIME, estado INT";
echo db_crear_tabla("ahm_visitas", $campos, true);

// Tabla de comentarios
$campos = "id_comentario INT NOT NULL AUTO_INCREMENT PRIMARY KEY, id_usuario INT, comentario TEXT, tipo TINYINT(1), fecha DATETIME";
echo db_crear_tabla("ahm_comentarios", $campos, true);

// Tabla de Materiales
$campos = "id_material INT NOT NULL AUTO_INCREMENT PRIMARY KEY, material VARCHAR(100), activo TINYINT(1)";
echo db_crear_tabla("ahm_materiales", $campos, true);
db_agregar_datos("ahm_materiales",array("material" => "Otros", "activo" => "1"));
db_agregar_datos("ahm_materiales",array("material" => "Vinilo", "activo" => "1"));
db_agregar_datos("ahm_materiales",array("material" => "Tela", "activo" => "1"));

// Tabla de Materiales por usuario
$campos = "id_material_indv INT NOT NULL AUTO_INCREMENT PRIMARY KEY, id_material INT, id_usuario INT, costo DOUBLE PRECISION, activo TINYINT(1)";
echo db_crear_tabla("ahm_materiales_indv", $campos, true);
db_agregar_datos("ahm_materiales_indv",array("id_material" => "1", "id_usuario" => "2", "costo" => "10.0", "activo" => "1"));
db_agregar_datos("ahm_materiales_indv",array("id_material" => "2", "id_usuario" => "2", "costo" => "5.0", "activo" => "1"));
db_agregar_datos("ahm_materiales_indv",array("id_material" => "3", "id_usuario" => "2", "costo" => "15.0", "activo" => "0"));

?>
</body>
</html>
