<?php

ini_set('display_errors', 1);
header('Content-type: text/html; charset=utf-8');
mb_internal_encoding('utf-8');

date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU.utf-8');

include '../autoload.php';
include '../at_core/core.php';

spl_autoload_register('autoload');

include 'config.php';

ATCore::init();