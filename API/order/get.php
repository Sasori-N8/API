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
// receiving  database connection
require_once(__DIR__ . "\..\config\database.php");

$database = new Database();
$db = $database->getConnection();

// creating object order
require_once(__DIR__ . "\..\model\user.php");

$user = new User ($db);

if (!$user->check($data->token)){

    echo json_encode(["message" => "Wrong token"]);
    exit();
}

//-----------------------------------------------------------------------------------------------------------------

// creating object order
require_once(__DIR__ . "\..\model\order.php");

$get_one_order = new Order($db);

// Make sure, that data aren't empty
if ( !empty($data->id) ) {

    // setting values of order properties
    $get_one_order->id = $data->id;
    $get_one_order->user = $user->id;

    $order = $get_one_order->find();
    $qty_order = $order->rowCount();

    if ($qty_order > 0)
    {
        $one_order = Array();

        $row = $order->fetch(PDO::FETCH_ASSOC);

        // extracting row
        extract($row);

        $one_order = Array(
            "id" => $id,
            "status" => $status,
            "user" => $user,
        );

        // setting code response - 302 Got it!
        http_response_code(302);

        // let the user be advised
        echo json_encode(["message" => "The status of your order: " . $one_order["status"], "order" => $one_order]);
    }

       //  if the order can't be created, let the user be adviced
    else {

        // setting code response  - 404 order not found
        http_response_code(404);

        // let the user be advised
        echo json_encode(["message" => "Order is not found", "data" => $data]);
    }
}

       // let the user be advised, that data is incomplete
    else {

    // setting code response - 400 Wrong request
    http_response_code(400);

    // let the user be advised
    echo json_encode(["message" => "Wrong id or user!"]);
}
?>