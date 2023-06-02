<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);

    $user = $requestData['user'];
    $password = $requestData['password'];

    if ($user == $_ENV['USER_NAME'] && $password == $_ENV['USER_PASSWORD']) {
        $_SESSION['user'] = $user;
        http_response_code(200);
    } else {
        echo "erro";
        http_response_code(401);
    }
}
