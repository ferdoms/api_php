<?php

header("Access-Control-Allow-Origins: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Access-Controll-Allow-Headers: access");

include_once dirname(__DIR__) . "/config/database.php";
include_once dirname(__DIR__) . "/model/product.php";

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$product->id = isset($_GET['id']) ? $_GET['id'] : die();

$product->readOne();

if($product->name!=null){
    $product_arr = array(
        "id" => $product->id,
        "name" => $product->name,
        "price" => $product->price,
        "description" => $product->description,
        "category_id" => $product->category_id,
        "created" => $product->created,
    );

    http_response_code(200);
    echo json_encode($product_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message"=>"Product not found."));
}


