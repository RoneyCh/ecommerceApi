<?php
require_once './config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);

    $user = $requestData['user'];
    $password = $requestData['password'];

    $query = "SELECT u.login, u.senha FROM Usuario u WHERE u.login = '$user' AND u.senha = '$password'";

    $resultado = $conn->query($query);

    if ($resultado->num_rows > 0) {
        $_SESSION['user'] = $user;
        http_response_code(200);
    } else {
        echo "erro";
        http_response_code(401);
    }
}
