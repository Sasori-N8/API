<?php
class Order {

    // connection with database and tables 'listorder'
    private $conn;
    private $table_name = "listorder";

    // object properties
    public $id;
    public $name;
    public $userorder;
    public $user;
    public $status;

    // constructor for connection with database
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user=:user";

        // request preparation
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user", $this->user);

        // request executing
        $stmt->execute();

        return $stmt;
    }

    public function create()
    {
        // request for creating records
        $query = "INSERT INTO " . $this->table_name . "
                SET
                name=:name, userorder=:userorder, user=:user";

        // request preparation
        $stmt = $this->conn->prepare($query);

        // cleaning
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->user = htmlspecialchars(strip_tags($this->user));

        // values binding
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":userorder", $this->userorder);
        $stmt->bindParam(":user", $this->user);

        // request executing
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    public function find()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id=:id AND user=:user";

        // request preparation
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":user", $this->user);

        // request executing
        $stmt->execute();

        return $stmt;
    }
}
?>