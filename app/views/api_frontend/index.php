<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Frontend - Gundam Store</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding-top: 20px;
            padding-bottom: 40px;
        }
        
        .api-container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            font-weight: bold;
            background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border-bottom: none;
        }
        
        .btn-api {
            background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            color: white;
        }
        
        .btn-api:hover {
            background: linear-gradient(45deg, #4fa0fe 0%, #00e7fe 100%);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white;
        }
        
        .api-header {
            margin-bottom: 30px;
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .api-header h1 {
            color: #1e3799;
            margin-bottom: 10px;
        }
        
        .api-header p {
            color: #666;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .api-icon {
            font-size: 2.5rem;
            color: #4facfe;
            margin-bottom: 15px;
        }
        
        pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        
        code {
            color: #d63384;
        }
        
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
        }
    </style>
</head>
<body>
    <div class="container-fluid api-container">
        <div class="api-header">
            <i class="fas fa-code api-icon"></i>
            <h1>API Frontend - Gundam Store</h1>
            <p>Giao diện trực quan để làm việc với API của Gundam Store. Sử dụng jQuery và AJAX để tương tác với các API endpoint.</p>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-boxes me-2"></i> Quản lý sản phẩm
                    </div>
                    <div class="card-body">
                        <p>Truy cập vào giao diện quản lý sản phẩm với đầy đủ chức năng xem, thêm, sửa và xóa sản phẩm.</p>
                        <a href="products.php" class="btn btn-api">
                            <i class="fas fa-arrow-right me-2"></i> Truy cập
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-tags me-2"></i> Quản lý danh mục
                    </div>
                    <div class="card-body">
                        <p>Truy cập vào giao diện quản lý danh mục với đầy đủ chức năng xem, thêm, sửa và xóa danh mục sản phẩm.</p>
                        <a href="categories.php" class="btn btn-api">
                            <i class="fas fa-arrow-right me-2"></i> Truy cập
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-2"></i> API Endpoints
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Products API</h5>
                                <ul class="list-group mb-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><code>GET /api.php/products</code> - Lấy tất cả sản phẩm</span>
                                        <button class="btn btn-sm btn-outline-primary test-api" data-url="api.php/products" data-method="GET">Test</button>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><code>GET /api.php/products/{id}</code> - Lấy chi tiết sản phẩm</span>
                                        <button class="btn btn-sm btn-outline-primary test-api-id" data-url="api.php/products/" data-method="GET" data-prompt="Nhập ID sản phẩm cần xem">Test</button>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><code>POST /api.php/products</code> - Tạo sản phẩm mới</span>
                                        <button class="btn btn-sm btn-outline-primary test-api-modal" data-toggle="modal" data-target="#testApiModal" data-url="api.php/products" data-method="POST" data-body='{"name":"Test Product","description":"Test description","price":100000,"category_id":1,"image":"freedom.jpg"}'>Test</button>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><code>PUT /api.php/products/{id}</code> - Cập nhật sản phẩm</span>
                                        <button class="btn btn-sm btn-outline-primary test-api-id-modal" data-toggle="modal" data-target="#testApiModal" data-url="api.php/products/" data-method="PUT" data-prompt="Nhập ID sản phẩm cần cập nhật" data-body='{"name":"Updated Product","description":"Updated description","price":150000,"category_id":1,"image":"freedom.jpg"}'>Test</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5>Categories API</h5>
                                <ul class="list-group mb-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><code>GET /api.php/categories</code> - Lấy tất cả danh mục</span>
                                        <button class="btn btn-sm btn-outline-primary test-api" data-url="api.php/categories" data-method="GET">Test</button>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><code>GET /api.php/categories/{id}</code> - Lấy chi tiết danh mục</span>
                                        <button class="btn btn-sm btn-outline-primary test-api-id" data-url="api.php/categories/" data-method="GET" data-prompt="Nhập ID danh mục cần xem">Test</button>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><code>POST /api.php/categories</code> - Tạo danh mục mới</span>
                                        <button class="btn btn-sm btn-outline-primary test-api-modal" data-toggle="modal" data-target="#testApiModal" data-url="api.php/categories" data-method="POST" data-body='{"name":"Test Category","description":"Test description"}'>Test</button>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><code>PUT /api.php/categories/{id}</code> - Cập nhật danh mục</span>
                                        <button class="btn btn-sm btn-outline-primary test-api-id-modal" data-toggle="modal" data-target="#testApiModal" data-url="api.php/categories/" data-method="PUT" data-prompt="Nhập ID danh mục cần cập nhật" data-body='{"name":"Updated Category","description":"Updated description"}'>Test</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-terminal me-2"></i> Kết quả API
            </div>
            <div class="card-body">
                <pre id="apiResult">// Kết quả từ API sẽ hiển thị ở đây
// Nhấn nút "Test" để xem kết quả từ các endpoint</pre>
            </div>
        </div>
    </div>
    
    <!-- API Test Modal -->
    <div class="modal fade" id="testApiModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Test API Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Endpoint:</label>
                        <div class="input-group">
                            <span class="input-group-text">URL</span>
                            <input type="text" class="form-control" id="apiUrlInput">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Method:</label>
                        <input type="text" class="form-control" id="apiMethodInput" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Request Body:</label>
                        <textarea class="form-control" id="apiBodyInput" rows="8"></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i> Hướng dẫn</h6>
                        <p class="mb-1"><strong>Cập nhật ảnh:</strong> Khi cập nhật sản phẩm, trường <code>image</code> chỉ cần chứa tên file ảnh (VD: "product.jpg").</p>
                        <p class="mb-1">Hệ thống sẽ tự động thêm đường dẫn <code>uploads/products/</code> vào trước tên file nếu cần.</p>
                        <p class="mb-1">Nếu để trống trường <code>image</code>, hệ thống sẽ giữ nguyên ảnh hiện tại.</p>
                        <p class="mb-0"><small>Lưu ý: API này không xử lý việc upload file. Tên ảnh phải là file đã tồn tại trên server.</small></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="sendApiRequest">Gửi Request</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast Container -->
    <div class="toast-container">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toastTitle">Thông báo</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage"></div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Cấu hình API
            const baseUrl = '<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]"; ?>';
            
            // Khởi tạo toast
            const toast = new bootstrap.Toast($('.toast'));
            
            // Khởi tạo modal
            const apiTestModal = new bootstrap.Modal(document.getElementById('testApiModal'));
            
            // Test API trực tiếp 
            $('.test-api').on('click', function() {
                const url = $(this).data('url');
                const method = $(this).data('method');
                
                sendApiRequest(url, method);
            });
            
            // Test API với ID được nhập bởi người dùng
            $('.test-api-id').on('click', function() {
                const apiUrl = $(this).data('url');
                const method = $(this).data('method');
                const promptText = $(this).data('prompt') || "Nhập ID";
                
                const id = prompt(promptText);
                if (id !== null && id.trim() !== '') {
                    const url = apiUrl + id;
                    sendApiRequest(url, method);
                }
            });
            
            // Mở modal test API
            $('.test-api-modal').on('click', function() {
                const url = $(this).data('url');
                const method = $(this).data('method');
                const body = $(this).data('body');
                
                $('#apiUrlInput').val(baseUrl + '/' + url);
                $('#apiMethodInput').val(method);
                $('#apiBodyInput').val(JSON.stringify(body, null, 2));
                
                apiTestModal.show();
            });
            
            // Mở modal test API với ID được nhập bởi người dùng
            $('.test-api-id-modal').on('click', function() {
                const apiUrl = $(this).data('url');
                const method = $(this).data('method');
                const body = $(this).data('body');
                const promptText = $(this).data('prompt') || "Nhập ID";
                
                const id = prompt(promptText);
                if (id !== null && id.trim() !== '') {
                    const fullUrl = apiUrl + id;
                    
                    $('#apiUrlInput').val(baseUrl + '/' + fullUrl);
                    $('#apiMethodInput').val(method);
                    $('#apiBodyInput').val(JSON.stringify(body, null, 2));
                    
                    apiTestModal.show();
                }
            });
            
            // Gửi request từ modal
            $('#sendApiRequest').on('click', function() {
                const url = $('#apiUrlInput').val().replace(baseUrl + '/', '');
                const method = $('#apiMethodInput').val();
                const body = $('#apiBodyInput').val();
                
                sendApiRequest(url, method, body);
                apiTestModal.hide();
            });
            
            // Hàm gửi API request
            function sendApiRequest(url, method, body = null) {
                $('#apiResult').text('Đang tải...');
                
                const settings = {
                    url: url,
                    method: method,
                    dataType: 'json',
                    success: function(data) {
                        $('#apiResult').text(JSON.stringify(data, null, 2));
                        showToast('Thành công', 'Request đã được xử lý thành công', 'success');
                    },
                    error: function(xhr, status, error) {
                        let errorMsg = error;
                        try {
                            const errorObj = JSON.parse(xhr.responseText);
                            errorMsg = errorObj.message || error;
                        } catch (e) {}
                        
                        $('#apiResult').text('Lỗi: ' + errorMsg + '\nStatus: ' + xhr.status);
                        showToast('Lỗi', 'Không thể thực hiện request: ' + errorMsg, 'danger');
                    }
                };
                
                if (body && (method === 'POST' || method === 'PUT')) {
                    settings.contentType = 'application/json';
                    settings.data = body;
                }
                
                $.ajax(settings);
            }
            
            // Hiển thị toast message
            function showToast(title, message, type = 'info') {
                $('#toastTitle').text(title);
                $('#toastMessage').text(message);
                
                $('.toast').removeClass('bg-success bg-danger bg-warning bg-info text-white');
                
                switch(type) {
                    case 'success':
                        $('.toast').addClass('bg-success text-white');
                        break;
                    case 'danger':
                        $('.toast').addClass('bg-danger text-white');
                        break;
                    case 'warning':
                        $('.toast').addClass('bg-warning');
                        break;
                    case 'info':
                        $('.toast').addClass('bg-info text-white');
                        break;
                }
                
                toast.show();
            }
        });
    </script>
</body>
</html> 