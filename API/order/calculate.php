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
// receiving a database connection
require_once(__DIR__ . "\..\config\database.php");

$database = new Database();
$db = $database->getConnection();

// object order creating
require_once(__DIR__ . "\..\model\user.php");

$user = new User($db);

if (!$user->check($data->token)){

    echo json_encode(["message" => "Wrong token!"]);
    exit();
}
//-------------------------------------------Autentication---------------------------------------------------------

if ( !empty($data->items) )
{
    $price = 0;

    foreach ($data->items as $items)
    {
        if ($items->price > 0)
            $price += $items->price;
    }
        // setting code response - 200 All right!
        http_response_code(200);

        // announce to user
        echo json_encode(["message" => "Your price of order", "price" => $price]);
}

    // if not tasked items
else {

    // setting code response - 400 All wrong!
    http_response_code (400);

    // let the user be advised
    echo json_encode(["message" => "Your price of order", "price" => 0]);

}
