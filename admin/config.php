<?php

use classes\base\ATCore_Db as db;
use classes\base\ATCore_Api as api;

$lang = 'ru';

ATCore::$app = 'admin';

db::$_host = 'localhost';
db::$_login = '';
db::$_password = '';
db::$_db = '';
db::connect();

api::$_host = '';
api::$private_key = '';
api::$app_id = 1;
auth::init();
