<?php
session_start();


require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'lib/db_update_manager.php';
require_once 'lib/db_insert_manager.php';
require_once 'lib/db_select_manager.php';
require_once 'lib/db_delete_manager.php';
require_once 'lib/date.php';
require_once 'lib/functions.php';

$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'news';

$conn_string = 'mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8';
$connection = new PDO($conn_string,$db_user,$db_password);

$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
?>