<?php

$lang = 'ru';

ATCore::$app = 'client';

db::$_host = 'localhost';
db::$_login = 'root';
db::$_password = '';
db::$_db = 'at_framework';

auth::init();