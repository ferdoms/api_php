<?php 
include  dirname( __DIR__ ) . "../../../api.constants.php";
class Database {
    public $conn;
    public function getConnection(){
        $this->conn = null;
        try{
            $this->conn = new PDO(
                "mysql:host=" . DB_HOST .
                ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS
            );

            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();

        }
        return $this->conn;
    }
}

?>