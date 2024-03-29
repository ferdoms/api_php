<?php 

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once dirname( __DIR__ ) . '/config/database.php';
include_once dirname( __DIR__ ) . '/model/product.php';

// Get db connection
$database = new Database();
$db = $database->getConnection();

// DAO Products
$product = new Product($db);

$stmt = $product->read();

if($stmt->rowCount() > 0){
    $products_arr = array();
    $products_arr["records"] = array();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $product_item = array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        );

        array_push($products_arr["records"], $product_item);
    }

    http_response_code(200);

    echo json_encode($products_arr);
} else {
    http_response_code(404);

    echo json_encode(
        array("message" => "No products found.")
    );
}
?>