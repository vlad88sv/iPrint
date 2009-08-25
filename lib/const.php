<?php
// Niveles
define('_N_administrador',      9);
define('_N_usuario',            3);

//Estado de orden -- errores
define('_EO_cancelada_otros',  -5); // Orden cancelada por otros motivos
define('_EO_cancelada_medidas',-4); // Orden cancelada por medidas de impresion invalidas
define('_EO_cancelada_archivo',-3); // Orden cancelada por archivo invalido o de mala calidad
define('_EO_cancelada_usuario',-2); // Orden cancelada por peticion de cliente
define('_EO_faltan_datos',     -1); // Orden pospuesta por algun dato faltante

// Estado de orden
define('_EO_desconocido',       0); // Orden en estado desconocido, necesita revisión manual
define('_EO_nueva',             1); // Esperando aprobación para imprimir
define('_EO_aprobada',          2); // Se superviso que los datos estuvieran bien, mandando a ploteo
define('_EO_imprimiendo',       3); // En ploteo
define('_EO_impresa',           4); // Impresa y almacenada
define('_EO_esperando_entrega', 5); // Se informó al cliente de que la impresión esta lista
define('_EO_entregada',         6); //Orden entregada

// Estado de visita -- errores
define('_EV_cancelada_usuario',-2); // Visita tuvo que ser cancelada
define('_EV_cancelada'        ,-1); // Visita tuvo que ser cancelada

// Estado de visita
define('_EV_desconocido',       0); // Visita en estado desconocido
define('_EV_nueva'      ,       1); // Visita nueva
define('_EV_aprobada'   ,       2); // Visita aprobada
define('_EV_visitando'  ,       3); // Visita atendiendose
define('_EV_visitado'   ,       4); // Visita concluida

// Funciones sobre constantes.
function convertir_EO_str($EO) {
    switch ($EO) {
        case 0:
            return "Necesita revisión manual";
        break;
        case 1:
            return "Nueva";
        break;
        case 2:
            return "Aprobada";
        break;
        case 3:
            return "Imprimiendo";
        break;
        case 4:
            return "Impreso";
        break;
        case 5:
            return "Entregando";
        break;
        case 6:
            return "Entregado";
        break;
        case -1:
            return "Faltan datos";
        break;
        case -2:
            return "Cancelada por usuario";
        break;
        case -3:
            return "Cancelada por mal archivo";
        break;
        case -4:
            return "Cancelada por malas medidas";
        break;
        case -5:
            return "Cancelada";
        break;
        default:
            return "¿EO:$EO?";
        break;
    }
}

function convertir_EV_str($EV) {
    switch ($EV) {
        case 0:
            return "Necesita revisión manual";
        break;
        case 1:
            return "Nueva";
        break;
        case 2:
            return "Aprobada";
        break;
        case 3:
            return "Visitando";
        break;
        case 4:
            return "Visitado";
        break;
        break;
        case -1:
            return "Cancelada";
        break;
        case -2:
            return "Cancelada por usuario";
        break;
        default:
            return "¿EV:$EV?";
        break;
    }
}
?>
