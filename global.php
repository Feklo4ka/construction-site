<?php
//определение путей
define('DIR', pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_DIRNAME).'/');
define('SCHEME', $_SERVER['REQUEST_SCHEME'].'://');
define('PORT', $_SERVER['SERVER_PORT'] !=80?':'.$_SERVER['SERVER_PORT']:'');
define('DOMAIN', $_SERVER['SERVER_NAME'].PORT);
define('SUBDOMAIN', str_replace('/main.php', '', $_SERVER['SCRIPT_NAME']).'/');
define('MAIN', SCHEME. DOMAIN. SUBDOMAIN);

define('DB_HOST', getenv('DB_HOST'), 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'construction');



//основная секция
define('CSS', MAIN. 'css/');
define('JS', MAIN. 'js/');
define('IMG', MAIN. 'img/');