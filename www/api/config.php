<?php

use classes\base\ATCore_Db as db;

ATCore::$app = 'api';

db::$_host = 'localhost';
db::$_login = '';
db::$_password = '';
db::$_db = '';

db::connect();
