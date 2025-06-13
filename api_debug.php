<?php
require_once 'app/helpers/UrlHelper.php';

header('Content-Type: application/json');

// Thông tin về URL API và cấu hình
$debug = [
    'apiBaseUrl' => UrlHelper::url('api.php'),
    'absoluteApiUrl' => UrlHelper::baseUrl() . '/api.php',
    'serverInfo' => [
        'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? 'unknown',
        'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'unknown',
        'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'] ?? 'unknown',
        'PHP_SELF' => $_SERVER['PHP_SELF'] ?? 'unknown',
        'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'] ?? 'unknown',
    ]
];

// Kiểm tra kết nối đến API
try {
    $categoriesUrl = $debug['apiBaseUrl'] . '/categories';
    $ch = curl_init($categoriesUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    $debug['apiTest'] = [
        'url' => $categoriesUrl,
        'status' => $info['http_code'],
        'response' => $response ? (json_decode($response, true) ?? $response) : null,
        'error' => $error ?: null
    ];
} catch (Exception $e) {
    $debug['apiTest'] = [
        'url' => $categoriesUrl ?? 'unknown',
        'error' => $e->getMessage()
    ];
}

// Kiểm tra Bootstrap JS
$debug['bootstrapTest'] = [
    'cdnUrl' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js',
    'instruction' => 'Đảm bảo rằng file Bootstrap JS được tải trước khi sử dụng các đối tượng bootstrap'
];

// Output debug information
echo json_encode($debug, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?> 