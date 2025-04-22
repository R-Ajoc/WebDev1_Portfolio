<?php

// Show errors for debugging (turn off in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set response header to JSON
header("Content-Type: application/json");

// Load the Router class
require_once __DIR__ . '/router.php';

// Get the current URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/kardex_system/src/routes/index.php', '', $uri);
$method = $_SERVER['REQUEST_METHOD'];

// Initialize the Router and handle the request
$router = new Router();
$router->dispatch($uri, $method);
