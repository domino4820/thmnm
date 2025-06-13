<?php
require_once 'app/helpers/UrlHelper.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Test Page</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        pre {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 15px;
            border-radius: 4px;
            overflow: auto;
            max-height: 400px;
        }
        .response-container {
            margin-top: 20px;
        }
        .url-info {
            margin-bottom: 20px;
            padding: 10px;
            background: #e9f5ff;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">API Test Page</h1>
        
        <div class="url-info">
            <h3>URL Information</h3>
            <p><strong>Base URL:</strong> <span id="baseUrl"><?= UrlHelper::baseUrl() ?></span></p>
            <p><strong>API URL:</strong> <span id="apiUrl"><?= UrlHelper::url('api.php') ?></span></p>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Bootstrap Test</h5>
                    </div>
                    <div class="card-body">
                        <p>Kiểm tra Bootstrap đã được tải:</p>
                        <button id="testBootstrapBtn" class="btn btn-primary">Kiểm tra Bootstrap</button>
                        <div id="bootstrapResult" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Categories API Test -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Categories API Test</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <button id="getCategoriesBtn" class="btn btn-success">GET All Categories</button>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="number" id="categoryId" class="form-control" placeholder="Category ID">
                                <button id="getOneCategoryBtn" class="btn btn-outline-success">GET One</button>
                            </div>
                        </div>
                        <div class="response-container">
                            <h6>Response:</h6>
                            <pre id="categoriesResponse">Results will appear here...</pre>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products API Test -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Products API Test</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <button id="getProductsBtn" class="btn btn-info">GET All Products</button>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="number" id="productId" class="form-control" placeholder="Product ID">
                                <button id="getOneProductBtn" class="btn btn-outline-info">GET One</button>
                            </div>
                        </div>
                        <div class="response-container">
                            <h6>Response:</h6>
                            <pre id="productsResponse">Results will appear here...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">Custom API Request</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customEndpoint" class="form-label">Endpoint:</label>
                            <div class="input-group">
                                <span class="input-group-text"><?= UrlHelper::url('api.php') ?>/</span>
                                <input type="text" id="customEndpoint" class="form-control" placeholder="products/1">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="requestMethod" class="form-label">Method:</label>
                            <select id="requestMethod" class="form-select">
                                <option value="GET">GET</option>
                                <option value="POST">POST</option>
                                <option value="PUT">PUT</option>
                                <option value="DELETE">DELETE</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="requestBody" class="form-label">Request Body (JSON):</label>
                            <textarea id="requestBody" class="form-control" rows="5" placeholder='{"name": "Product name", "description": "Product description", "price": "100000", "category_id": "1", "image": ""}'></textarea>
                        </div>
                        <button id="sendCustomRequestBtn" class="btn btn-warning">Send Request</button>
                        <div class="response-container">
                            <h6>Response:</h6>
                            <pre id="customResponse">Results will appear here...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // API URL bases
        const apiBaseUrl = '<?= UrlHelper::url('api.php') ?>';
        const categoriesEndpoint = `${apiBaseUrl}/categories`;
        const productsEndpoint = `${apiBaseUrl}/products`;
        
        // Log API URLs for debugging
        console.log('API Base URL:', apiBaseUrl);
        console.log('Categories Endpoint:', categoriesEndpoint);
        console.log('Products Endpoint:', productsEndpoint);
        
        // Test Bootstrap
        document.getElementById('testBootstrapBtn').addEventListener('click', () => {
            const bootstrapResult = document.getElementById('bootstrapResult');
            
            if (typeof bootstrap !== 'undefined') {
                bootstrapResult.innerHTML = `<div class="alert alert-success">Bootstrap được tải thành công! Phiên bản: ${bootstrap?.Tooltip?.VERSION || 'không xác định'}</div>`;
                
                // Create a tooltip to test Bootstrap functionality
                const tooltipTriggerEl = document.createElement('button');
                tooltipTriggerEl.className = 'btn btn-sm btn-secondary ms-2';
                tooltipTriggerEl.setAttribute('data-bs-toggle', 'tooltip');
                tooltipTriggerEl.setAttribute('data-bs-placement', 'top');
                tooltipTriggerEl.setAttribute('title', 'Tooltip đang hoạt động!');
                tooltipTriggerEl.textContent = 'Hover để kiểm tra tooltip';
                bootstrapResult.querySelector('.alert').appendChild(tooltipTriggerEl);
                
                try {
                    // Initialize tooltip
                    new bootstrap.Tooltip(tooltipTriggerEl);
                } catch (e) {
                    console.error('Error initializing tooltip:', e);
                }
            } else {
                bootstrapResult.innerHTML = `
                    <div class="alert alert-danger">
                        Bootstrap chưa được tải! Đang thử tải lại...
                        <div class="spinner-border spinner-border-sm ms-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;
                
                // Try to reload Bootstrap
                const bootstrapScript = document.createElement('script');
                bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js';
                bootstrapScript.onload = function() {
                    bootstrapResult.innerHTML = '<div class="alert alert-success">Bootstrap đã được tải lại thành công!</div>';
                };
                bootstrapScript.onerror = function() {
                    bootstrapResult.innerHTML = '<div class="alert alert-danger">Không thể tải Bootstrap! Vui lòng kiểm tra kết nối internet.</div>';
                };
                document.body.appendChild(bootstrapScript);
            }
        });
        
        // API functions
        async function fetchAPI(url, options = {}) {
            try {
                console.log('Calling API:', url, options);
                
                const response = await fetch(url, {
                    ...options,
                    headers: {
                        'Content-Type': 'application/json',
                        ...options.headers
                    }
                });
                
                // Log the raw response for debugging
                const responseText = await response.text();
                console.log('API Raw Response:', responseText);
                
                // Try to parse JSON
                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error('JSON Parse Error:', e);
                    return {
                        success: false,
                        error: `Invalid JSON response: ${responseText.substring(0, 100)}...`,
                        raw: responseText,
                        status: response.status
                    };
                }
                
                return {
                    success: response.ok,
                    data,
                    status: response.status,
                    raw: responseText
                };
            } catch (error) {
                console.error('API Error:', error);
                return {
                    success: false,
                    error: error.message,
                    raw: null,
                    status: 0
                };
            }
        }
        
        // Format response for display
        function formatResponse(response) {
            return JSON.stringify(response, null, 2);
        }
        
        // Categories API test handlers
        document.getElementById('getCategoriesBtn').addEventListener('click', async () => {
            const response = await fetchAPI(categoriesEndpoint);
            document.getElementById('categoriesResponse').textContent = formatResponse(response);
        });
        
        document.getElementById('getOneCategoryBtn').addEventListener('click', async () => {
            const categoryId = document.getElementById('categoryId').value;
            if (!categoryId) {
                document.getElementById('categoriesResponse').textContent = 'Please enter a Category ID';
                return;
            }
            const response = await fetchAPI(`${categoriesEndpoint}/${categoryId}`);
            document.getElementById('categoriesResponse').textContent = formatResponse(response);
        });
        
        // Products API test handlers
        document.getElementById('getProductsBtn').addEventListener('click', async () => {
            const response = await fetchAPI(productsEndpoint);
            document.getElementById('productsResponse').textContent = formatResponse(response);
        });
        
        document.getElementById('getOneProductBtn').addEventListener('click', async () => {
            const productId = document.getElementById('productId').value;
            if (!productId) {
                document.getElementById('productsResponse').textContent = 'Please enter a Product ID';
                return;
            }
            const response = await fetchAPI(`${productsEndpoint}/${productId}`);
            document.getElementById('productsResponse').textContent = formatResponse(response);
        });
        
        // Custom API request handler
        document.getElementById('sendCustomRequestBtn').addEventListener('click', async () => {
            const endpoint = document.getElementById('customEndpoint').value;
            const method = document.getElementById('requestMethod').value;
            const bodyText = document.getElementById('requestBody').value;
            
            let options = {
                method
            };
            
            if (['POST', 'PUT'].includes(method) && bodyText) {
                try {
                    const bodyJson = JSON.parse(bodyText);
                    options.body = JSON.stringify(bodyJson);
                } catch (e) {
                    document.getElementById('customResponse').textContent = `Error parsing JSON: ${e.message}`;
                    return;
                }
            }
            
            const response = await fetchAPI(`${apiBaseUrl}/${endpoint}`, options);
            document.getElementById('customResponse').textContent = formatResponse(response);
        });
    });
    </script>
</body>
</html> 