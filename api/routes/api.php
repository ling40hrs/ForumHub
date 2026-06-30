<?php

declare(strict_types=1);

// Simple router — maps URI patterns to controllers
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Strip base path if behind a subdirectory
$base = '/api';
if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

// Route map
$routes = [
    'GET'  => [
        '/'                => ['App\\Controllers\\HealthController', 'index'],
        '/posts'           => ['App\\Controllers\\PostController', 'index'],
        '/posts/{id}'      => ['App\\Controllers\\PostController', 'show'],
        '/communities'     => ['App\\Controllers\\CommunityController', 'index'],
        '/communities/{id}'=> ['App\\Controllers\\CommunityController', 'show'],
        '/users/{id}'      => ['App\\Controllers\\UserController', 'show'],
    ],
    'POST' => [
        '/auth/register'   => ['App\\Controllers\\AuthController', 'register'],
        '/auth/login'      => ['App\\Controllers\\AuthController', 'login'],
        '/posts'           => ['App\\Controllers\\PostController', 'store'],
        '/comments'        => ['App\\Controllers\\CommentController', 'store'],
        '/votes'           => ['App\\Controllers\\VoteController', 'store'],
    ],
    'PUT'  => [
        '/posts/{id}'      => ['App\\Controllers\\PostController', 'update'],
        '/users/{id}'      => ['App\\Controllers\\UserController', 'update'],
    ],
    'DELETE' => [
        '/posts/{id}'      => ['App\\Controllers\\PostController', 'destroy'],
    ],
];

// Match route
if (isset($routes[$method][$uri])) {
    [$controller, $action] = $routes[$method][$uri];
    (new $controller())->$action();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}
