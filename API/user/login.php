<?php
// required HTTP-headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// receiving  database connection
require_once(__DIR__ . "\..\config\database.php");

$database = new Database();
$db = $database->getConnection();
//object order creating
require_once(__DIR__ . "\..\model\user.php");

//receiving sent data
$data = json_decode(file_get_contents("php://input"));

if ( !empty($data->login) && !empty($data->password)) {

    $user = new User($db);
    $token = $user->login($data->login, $data->password);

    if ($token != "")
    {
        // setting code response - 200 All right!
        http_response_code(200);

        // Let the user be advised
        echo json_encode(["message" => "Login Success", "token" => $token]);
    }


    else{
        // setting code response - 404 Client not found
        http_response_code(404);

        // let the user be advised
        echo json_encode(["message" => "Login Failed!", "token" => 0]);

    }
}