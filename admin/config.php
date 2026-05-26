<?php
session_start();
define('DBDRIVER', 'mysql');
define('DBHOST', 'localhost');
define('DBNAME', 'velior_db1');
define('DBUSER', 'velior_db1');
define('DBPASSWORD', 'velior19Admin!');
define('DBPORT', '3306');
// URL base del sitio (sin barra final)
define('BASE_URL', 'http://velior.proyectosweb.celaya.tecnm.mx/velior');


// --- Mercado Pago ---
define('MP_ACCESS_TOKEN', 'APP_USR-5111158682481261-052602-162e587d1c0b34b784c39f0e3b6c4f07-3425675295
'); // Tu Access Token de Sandbox


// Cuando pases a producción cambia los tokens por los de Producción:
// define('MP_ACCESS_TOKEN', 'APP_USR-...');
// define('MP_PUBLIC_KEY',   'APP_USR-...');
 
define('URL_SUCCESS', BASE_URL . '/pagos/success.php');
define('URL_PENDING', BASE_URL . '/pagos/pending.php');
define('URL_FAILURE', BASE_URL . '/pagos/failure.php');
define('WEBHOOK_URL', BASE_URL . '/pagos/webhook.php');
define('MONEDA', 'MXN');