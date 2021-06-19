<?php
// required HTTP-headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// receiving sent data
$data = json_decode(file_get_contents("php://input"));

//---------------------------------------------Autentication-------------------------------------------------------
//receiving database connection
require_once(__DIR__ . "\..\config\database.php");

$database = new Database();
$db = $database->getConnection();

//creating object order
require_once(__DIR__ . "\..\model\user.php");

$user = new User($db);

if (!$user->check($data->token))
{
    echo json_encode(["message" => "Wrong token"]);
    exit();
}
//-------------------------------------------Autentication---------------------------------------------------------
//object order creating
require_once (__DIR__ . "\..\model\order.php");

//initializing object
$listorder = new Order($db);

//current autenticated user
$listorder->user = $user->id;

//requesting orders
$read_order = $listorder->read();
$qty_order = $read_order->rowCount();

//Verification. Are more than 0 records found?
if ($qty_order > 0) {

    //Order's massive
    $mas_order["records"] = Array();
    $one_order = Array();

    //receiving contains of our table
    //fetch() faster, that fetchAll()
    while ($row = $read_order->fetch(PDO::FETCH_ASSOC)){

        //extracting row
        extract($row);
        
        $one_order = Array(
            "id" => $id,
            "name" => $name,
            "userorder" => json_decode($userorder, true),
            "user" => $user,
        );

        array_push($mas_order["records"], $one_order);
    }

    //setting code response - 200 That's ok!
    http_response_code(200);

    //entering the data of product in JSON format
    echo json_encode($mas_order);
} else {

    //setting code response - 404 Not found
    http_response_code(404);

    //inform the user that no orders were found
    echo json_encode(["message" => "Order not found"]);
}