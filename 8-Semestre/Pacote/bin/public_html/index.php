<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

require './core/Config.php';
require './vendor/autoload.php';

$url = new Core\ConfigController;
$url->loadPage();
