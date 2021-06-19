<?php
// required HTTP-headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// receiving sent data
$data = json_decode(file_get_contents("php://input"));

//---------------------------------------------Autentication-------------------------------------------------------
//receiving  database connection
require_once(__DIR__ . "\..\config\database.php");

$database = new Database();
$db = $database->getConnection();

//object order creating
require_once(__DIR__ . "\..\model\user.php");

$user = new User ($db);

if (!$user->check($data->token))
{

    echo json_encode(["message" => "Wrong token"]);
    exit();
}

//--------------------------------------------------------------------------------------------------------------------

// object order creating
require_once(__DIR__ . "\..\model\order.php");

$new_order = new Order($db);

// Make sure, that data aren't empty
if (!empty($data->name) && !empty($data->userorder)) {

    // Setting values of order properties
    $new_order->name = $data->name;
    $new_order->userorder = json_encode($data->userorder);
    $new_order->user = $user->id;

    // order creating
    if($new_order->create()){

        // setting code response   - 201 created
        http_response_code(201);

        // let the user know
        echo json_encode(["message" => "New order is created", 'id' => $new_order->id]);
    }

    // if the order can't be created, let the user be adviced
    else {

        // setting code response - 503 service unavailable
        http_response_code(503);

        // let the user be advised
        echo json_encode(["message" => "New order is not created", "data" => $data]);
    }
}

// let the user be advised that data is incomlete
else {

    // setting code response - 400 wrong request
    http_response_code(400);

    // let the user be advised
    echo json_encode(["message" => "Wrong data order!"]);
}
?>