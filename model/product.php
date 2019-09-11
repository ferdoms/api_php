<?php
class Product
{
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query =
            "SELECT c.name as category_name, p.id, p.name, p.description, " .
            "p.price, p.category_id, p.created " .
            "FROM " . $this->table_name . " as p " .
            "LEFT JOIN categories as c ON p.category_id = c.id " .
            "ORDER BY p.created DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name .
            " SET name=:name, price=:price, description=:description, " .
            "category_id=:category_id, created=:created";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->created = htmlspecialchars(strip_tags($this->created));

        // bind values
        $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
        $stmt->bindParam(":price", $this->price, PDO::PARAM_INT);
        $stmt->bindParam(":description", $this->description, PDO::PARAM_STR);
        $stmt->bindParam(":category_id", $this->category_id, PDO::PARAM_INT);
        $stmt->bindParam(":created", $this->created, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function readOne()
    {
        $query = "SELECT c.name as category_name, p.id, p.name, p.description, " .
            "p.price, p.category_id, p.created " .
            "FROM " . $this->table_name . " as p " .
            "LEFT JOIN categories as c ON p.category_id = c.id " .
            "WHERE p.id=? " .
            "LIMIT 1 ";

        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(1, $this->id, PDO::PARAM_INT);

        $stmt->execute();

        $row = extract($stmt->fetch(PDO::FETCH_ASSOC));

        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->category_id = $category_id;
        $this->category_name = $category_name;
    }
    public function update()
    {
        // update query
        $query = "UPDATE " . $this->table_name . "
                    SET
                        name = :name,
                        price = :price,
                        description = :description,
                        category_id = :category_id
                    WHERE
                        id = :id";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // bind values
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
        $stmt->bindParam(":price", $this->price, PDO::PARAM_INT);
        $stmt->bindParam(":description", $this->description, PDO::PARAM_STR);
        $stmt->bindParam(":category_id", $this->category_id, PDO::PARAM_INT);
                
        if ($stmt->execute()) {
            return true;
        }

        foreach($stmt->errorInfo() as $error){
            echo $error . "\n";
        }
        return false;
    }
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE ((id = ?));";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind values
        $stmt->bindParam(1, $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function search($keywords)
    {
        $query = "SELECT c.name as category_name, p.id, p.name, p.description,
                     p.price, p.category_id, p.created " .
                 "FROM " . $this->table_name . " p " .
                 "LEFT JOIN categories c ON p.category_id = c.id " . 
                 "WHERE p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ? " .
                 "ORDER BY p.created DESC";
        
        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        $stmt->execute();

        // foreach($stmt->errorInfo() as $error){
        //     echo $error . "\n";
        // }
        return $stmt;
    }

    public function readPaging($from_record_num, $records_per_page)
    {
        $query = "SELECT c.name as category_name, p.id, p.name, p.description," .
                    "p.price, p.category_id, p.created " .
                 "FROM " . $this->table_name . " as p " .
                 "LEFT JOIN categories as c ON p.category_id = c.id " . 
                 "ORDER BY p.created DESC " .
                 "LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        foreach($stmt->errorInfo() as $error){
            echo $error . "\n";
        }

        return $stmt;
    }
    public function count()
    {
        $query = "SELECT COUNT(*) as total_rows " .
                 "FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        foreach($stmt->errorInfo() as $error){
            echo $error . "\n";
        }

        $rows = $stmt->fetch(PDO::FETCH_ASSOC);

        return $rows['total_rows'];
    }
}
