<?php

header("Access-Control-Allow-Origins: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: access");

include_once dirname(__DIR__) . "/config/core.php";
include_once dirname(__DIR__) . "/config/database.php";
include_once dirname(__DIR__) . "/model/product.php";

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$keywords = isset($_GET['s']) ? $_GET['s'] : "";

$stmt = $product->search($keywords);

if($stmt->rowCount()>0){

   
    $product_arr = array();
    $product_arr["records"] = array();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $product_item=array(
            "id" => $id,
            "name" => $name,
            "description" => $description,
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name,
        );

        array_push($product_arr["records"], $product_item);
    }
    http_response_code(200);
    echo json_encode($product_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No products found."));
}

?>
