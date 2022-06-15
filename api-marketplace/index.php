<?php 

/*=============================================
Mostrar errores
=============================================*/

ini_set('display_errors', 1);
ini_set("log_errors", 1);
ini_set("error_log",  "C:/xampp/htdocs/dnjtool/marketplace/php_error_log");

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('content-type: application/json; charset=utf-8');

require_once "controllers/route.controller.php";

require_once "controllers/get.controller.php";
require_once "models/get.model.php";

require_once "controllers/post.controller.php";
require_once "models/post.model.php";

require_once "controllers/put.controller.php";
require_once "models/put.model.php";

require_once "controllers/delete.controller.php";
require_once "models/delete.model.php";

require_once "vendor/autoload.php";

$index = new RoutesController();
$index -> index();
