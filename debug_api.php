<?php
// debug_api.php - Tập tin để debug API

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>API Debugger</h1>";

// Test ProductAPI
echo "<h2>Testing Products API</h2>";
$productsApiUrl = "http://localhost:8080/ProjectBanHang/api.php/products";
echo "Calling URL: $productsApiUrl<br>";

// Use cURL to make the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $productsApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status Code: $httpCode<br>";
if ($error) {
    echo "cURL Error: $error<br>";
}

echo "<h3>Response:</h3>";
echo "<pre>";
if ($response) {
    $decoded = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        print_r($decoded);
    } else {
        echo htmlspecialchars($response);
        echo "\n\nJSON Error: " . json_last_error_msg();
    }
} else {
    echo "No response received.";
}
echo "</pre>";

// Test CategoriesAPI
echo "<h2>Testing Categories API</h2>";
$categoriesApiUrl = "http://localhost:8080/ProjectBanHang/api.php/categories";
echo "Calling URL: $categoriesApiUrl<br>";

// Use cURL to make the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $categoriesApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status Code: $httpCode<br>";
if ($error) {
    echo "cURL Error: $error<br>";
}

echo "<h3>Response:</h3>";
echo "<pre>";
if ($response) {
    $decoded = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        print_r($decoded);
    } else {
        echo htmlspecialchars($response);
        echo "\n\nJSON Error: " . json_last_error_msg();
    }
} else {
    echo "No response received.";
}
echo "</pre>";

// Show HTTP request details used in JavaScript
echo "<h2>Client-side JavaScript API URL</h2>";
include_once 'app/helpers/UrlHelper.php';
echo "Base URL from UrlHelper: " . UrlHelper::url('api.php') . "<br>";
echo "Expected Products URL: " . UrlHelper::url('api.php') . "/products<br>";
echo "Expected Categories URL: " . UrlHelper::url('api.php') . "/categories<br>"; 