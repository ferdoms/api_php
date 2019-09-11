<?php 

include_once  './config/database.php';
include_once  './model/product.php';

$database = new Database();

$db = $database->getConnection();
$product = new Product($db);

$stmt = $product->readPaging(3,6);

echo $product->count();
// if($stmt->rowCount()>0){
//     echo $stmt->rowCount();
//     while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//         extract($row);

//         $product_item=array(
//             "id" => $id,
//             "name" => $name,
//             "description" => $description,
//             "price" => $price,
//             "category_id" => $category_id,
//             "category_name" => $category_name,
//         );

//         // array_push($product_arr["records"], $product_item);
//     }
// echo $product_item['name'];
// } else {
//     echo "failed";
// }

?>
