<?php
$request = $_SERVER['REQUEST_URI'];

switch (true) {
    case preg_match('/\/api\/CrudUser/', $request):
        include 'CrudUser.php';
        break;
    case preg_match('/\/api\/Login/', $request):
        include 'Login.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['status' => 0, 'message' => 'Invalid endpoint']);
        break;
}
