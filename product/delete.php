<?php

header("Access-Control-Allow-Origins: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Controll-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once dirname(__DIR__) . "/config/database.php";
include_once dirname(__DIR__) . "/model/product.php";

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$data= json_decode(file_get_contents("php://input"), true);

$product->id = $data['id'];

if($product->delete()){
    http_response_code(200);
    echo json_encode(array("message" => "Product deleted."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to delete product."));
}
?>