<?php
//---------------------------------------Login---------------------------------------------------------------
$data = Array(
    'login' => 'user_api',
    'password' => 'api_pas'
);
$url = 'http://api/user/login.php';
//Test($data, $url);

//----------------------------------------Calculate----------------------------------------------------------
$data = Array(
    'items' => [
        ['name' => 'tshirt', 'price' => 20 ],
        ['name' => 'pants', 'price' => 100 ],
        ['name' => 'shoes', 'price' => 150 ],
    ],
    'token' => '550483be38eb47f2c76603acda1bf937b41cc3dba2fd56eadbbe83260cc0a575'
);
$url = 'http://api/order/calculate.php';
Test($data, $url);

//--------------------------------------------Create-----------------------------------------------------------
$data = Array(
    'name' => 'Order #244',
    'userorder' => [
        ['name' => 'tshirt', 'price' => 20 ],
        ['name' => 'pants', 'price' => 100 ],
        ['name' => 'shoes', 'price' => 150 ],
    ],
    'token' => '550483be38eb47f2c76603acda1bf937b41cc3dba2fd56eadbbe83260cc0a575'
);
$url = 'http://api/order/create.php';
Test($data, $url);

//--------------------------------------------Get---------------------------------------------------------------
$data = Array(
    'id' => '3',
    'token' => '550483be38eb47f2c76603acda1bf937b41cc3dba2fd56eadbbe83260cc0a575'
);
$url = 'http://api/order/get.php';
Test($data, $url);

//--------------------------------------------ListOrder---------------------------------------------------------------
$data = Array(
    'token' => '550483be38eb47f2c76603acda1bf937b41cc3dba2fd56eadbbe83260cc0a575'
);
$url = 'http://api/order/listorder.php';
Test($data, $url);

//---------------------------------------------TestFunction-----------------------------------------------------------
function Test($data = [], $url = "")
{
    $field = json_encode($data);

    $myCurl = curl_init();
    curl_setopt_array($myCurl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HEADER => 0,
        CURLOPT_POSTFIELDS => $field,
        CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($field))
    ));

    $response = curl_exec($myCurl);

    print_r(curl_error($myCurl));
    print_r($response);

    curl_close($myCurl);
}
//-------------------------------------------------------------------------------------------------------------------