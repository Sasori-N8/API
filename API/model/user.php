<?php
class user{

    // getting a database connection and 'user' connection

    private $conn;
    private $table_name = "user";
    private $login;
    private $password;
    private $keyword;
    private $token;

    public $id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login($login = "", $password = "")
    {
        $this->login = $login;
        $this->password = $password;

        // searching of user
        if ($this->find())
        {
            return $this->token;
        }
        // if user not found
        else {
            return "";
        }

}

public function find()
{
    $query = "SELECT * FROM " . $this->table_name . " WHERE login=:login AND password=:password";

    // request preparation
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":login", $this->login);
    $stmt->bindParam(":password", $this->password);

    // request executing
    $stmt->execute();

    $qty_user = $stmt->rowCount();

    //if users are found greater than zero
    if ($qty_user > 0)
    {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //extracting of a row
        extract($row);

        $this->keyword = $keyword;
        $this->token = $this->generateToken();

        $this->id = $id;
        $this->saveToken();

        return true;

    }

    return false;
}
public function generateToken()
{
    return hash ("sha256", $this->keyword .time());
}
    public function saveToken()
    {
        $query = "UPDATE " . $this->table_name . " SET token=:token WHERE id=:id";

        // request preparation
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":token", $this->token);
        $stmt->bindParam(":id", $this->id);

        // request executing
        $stmt->execute();

    }
    public function check($token="")
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE token=:token";

        // request preparation
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":token", $token);

        // request executing
        $stmt->execute();

        $qty_user = $stmt->rowCount();

        // if users are found greater than zero
        if ($qty_user > 0)

        {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // row extracting
            extract($row);
            $this->id = $id;

            return true;
        }
        return false;

    }
}