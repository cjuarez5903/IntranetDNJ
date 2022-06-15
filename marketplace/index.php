<?php 

/*=============================================
Mostrar errores
=============================================*/

ini_set('display_errors', 1);
ini_set("log_errors", 1);
ini_set("error_log",  "C:/xampp/htdocs/dnjtool/marketplace/php_error_log");

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST');

require_once "controllers/template.controller.php";
require_once "controllers/curl.controller.php";
require_once "controllers/users.controller.php";
require_once "controllers/vendors.controller.php";

require "extensions/vendor/autoload.php";

$index = new TemplateController();
$index->index();
