<?php
/**
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'sisbioecuador');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'Ik1@m2019!');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'k$-RoovP=Ab6Yu_2,`I6DW{M^|C%]z.PuCeyqfdJ:qj(6D[E2fgd.aD`fN6);nbW');
define('SECURE_AUTH_KEY', '|BB%O<`Qw)F)0+%#I|#7gvBQ1Wh}K%/+9M$1#%Gma6bm^w,kA|bv~%5w5$Z4Q2< ');
define('LOGGED_IN_KEY', 'L9|5Y/#621yfT3*j>@@~/sD/,.Q~O=;$anl?!|iENKhEgWWb](302{3So~<h1/Jz');
define('NONCE_KEY', '+Wn%U]$:gZgj(r=Adl/|M>/0>&*21N=WT*l;)rU^0]a)fjTIhQ<_|T4nc-!.[M7k');
define('AUTH_SALT', '[K.1]yvoFdm>Uf3^bEcE#a:&Is!KQ]qeg&:6:yl|{DyCaFhfW.ax)F]zsyU7FfJ;');
define('SECURE_AUTH_SALT', 'AMbtrT/$m//Qp!?<I;+s97l]OEdeqB.vJ)!}Pc|i-;!aHWO:k; BVQ%!dUFa_t1F');
define('LOGGED_IN_SALT', ' W$n~m;QTP<r&W+,6Q{J@kIk6?!0I840J6oiLqz%apj1)e,Y7^%]xm1ZQ%&]`8=l');
define('NONCE_SALT', 'r`<$:xfXIa3q%$5vJn$DpXgSpeMt]@l;g.HZq+GQX^oMi,UQU=rk>$(*IZ64-!m@');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

/* FTP */
define('FTP_HOST', 'http://172.16.13.168');
define('FTP_USER', 'inabio');
define('FTP_PASS', 'inabio_1234');
// Indicamos la carpeta a la que se debe acceder para llegar a wordpress
// solo es necesario si este ftp no accede directamente.
define( 'FTP_BASE', '/var/www/html/' );
define('FTP_SSL', false); // Indicamos que usaremos FTP y no FTPS

define('FTP_METHOD', 'direct');
