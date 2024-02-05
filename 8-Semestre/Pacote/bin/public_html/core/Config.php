<?php
session_start();
ob_start();

// DEFINE URL's
define('URL',  $_ENV['URL']);
define('URL_ASSETS',  $_ENV['URL_ASSETS']);

define('CONTROLLER',  $_ENV['CONTROLLER']);
define('METHOD',  $_ENV['METHOD']);

// ERRRO
define('ERROR_CONTROLLER',  $_ENV['ERROR_CONTROLLER']);
define('ERROR_METHOD',  $_ENV['ERROR_METHOD']);

// KEY GOOGLE MAPS
define('KEY_MAPS',  $_ENV['KEY_MAPS']);

// CREDENCIAIS BANCO
define('DB',  $_ENV['DB']);
define('HOST',  $_ENV['HOST']);
define('DBPORT',  $_ENV['DBPORT']);
define('DBNAME',  $_ENV['DBNAME']);
define('USER',  $_ENV['USER']);
define('PASS',  $_ENV['PASS']);
