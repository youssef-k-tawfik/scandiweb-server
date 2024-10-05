<?php

/**
 * This file is the entry point of the application.
 *  It routes the incoming requests to the appropriate controller.
 *  It also handles CORS requests.
 * 
 * @package App
 */

// echo any error encountered
error_reporting(E_ALL);
ini_set('display_errors', 1);

// logging successful request routing
error_log("Accessing /public/index.php");
error_log("requested method:" . $_SERVER['REQUEST_METHOD']);

require_once __DIR__ . '/../vendor/autoload.php';

// $allowed_domains = [
//     'https://scandiweb-client.netlify.app',
// ];

// Get the Origin of the incoming request
// $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// Check if the origin is in the list of allowed domains
// if (in_array($origin, $allowed_domains)) {
if (true) {
    // header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('content-type: application/json; charset=utf-8');
} else {
    // Return 403 Forbidden response
    http_response_code(403);
    echo "403 Forbidden - This origin is not allowed.";
    exit();
}

// Handle preflight requests (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->post('/graphql', [App\Controller\GraphQL::class, 'handle']);
    // testing server endpoint
    $r->get('/', function () {
        echo "Server is running";
    });
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Not Found', 'route' => $_SERVER['REQUEST_URI']]);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        echo $handler($vars);
        break;
}
