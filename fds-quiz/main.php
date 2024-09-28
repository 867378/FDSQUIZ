<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type");
header("Access-Control-Max-Age: 86400");

date_default_timezone_set("Asia/Manila");
set_time_limit(1000);

$root = $_SERVER['DOCUMENT_ROOT'];
$api = $root . '/fds-quiz/'; 
require_once($api . 'config/connection.php');
require_once($api . 'model/crud.model.php');

$dbase = new connection();
$pdo = $dbase->connect();
$crud = new Crud_model($pdo);

$data = json_decode(file_get_contents("php://input"));
$req = isset($_REQUEST['request']) ? explode('/', rtrim($_REQUEST['request'], '/')) : ['errorcatcher'];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if($req[0]=='Get'){
            if($req[1]=='All'){echo json_encode($crud->getAlldata()); return;}
            if($req[1]== 'One'){echo json_encode($crud->getOnedata($data)); return;}
        }
        break;

    case 'POST':
        if ($req[0] == 'Insert') {
            echo json_encode($crud->insert($data));
        }
        break;

    case 'PUT':
        if ($req[0] == 'Update') {
            echo json_encode($crud->update($data));
        }
        break;

    case 'DELETE':
        if ($req[0] == 'Remove') {
            echo json_encode($crud->delete($data));
        }
        break;

    default:
        echo json_encode(["message" => "Invalid Request"]);
        http_response_code(404);
}
