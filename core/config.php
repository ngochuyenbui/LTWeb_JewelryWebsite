<?php
// core/config.php

// DB Params
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_NAME', $_ENV['DB_NAME']);

// App Root
define('APPROOT', dirname(dirname(__FILE__)));

// URL Root (Thay đổi dựa trên thư mục dự án của bạn trên server/localhost)
define('URLROOT', $_ENV['URLROOT']);
define('SITENAME', $_ENV['SITENAME']);

define('ROLE_ADMIN', 'admin');
define('ROLE_USER', 'member');
define('SESSION_TIMEOUT', (int)$_ENV['SESSION_TIMEOUT']);