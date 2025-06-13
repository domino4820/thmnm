<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm - API Frontend</title>
    
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
        }
        
        .card-header {
            background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            font-weight: bold;
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
        
        .table-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
        }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
            justify-content: center;
            align-items: center;
        }
        
        .spinner-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container-fluid api-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Quản lý sản phẩm</h1>
            <div>
                <a href="index.php" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-home me-2"></i> Trang chủ
                </a>
                <button class="btn btn-api" id="btnAddProduct">
                    <i class="fas fa-plus me-2"></i> Thêm sản phẩm
                </button>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-filter me-2"></i> Bộ lọc
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Danh mục</label>
                            <select class="form-select" id="filterCategory">
                                <option value="">Tất cả danh mục</option>
                                <!-- Sẽ được điền bằng JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Tìm kiếm</label>
                            <input type="text" class="form-control" id="filterSearch" placeholder="Tên sản phẩm...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Số lượng hiển thị</label>
                            <select class="form-select" id="filterLimit">
                                <option value="10">10 sản phẩm</option>
                                <option value="25">25 sản phẩm</option>
                                <option value="50">50 sản phẩm</option>
                                <option value="100">100 sản phẩm</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button class="btn btn-api" id="btnApplyFilter">
                    <i class="fas fa-search me-2"></i> Áp dụng
                </button>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="fas fa-boxes me-2"></i> Danh sách sản phẩm
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="productsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Danh mục</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sẽ được điền bằng JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div id="productCount">Hiển thị <span id="currentCount">0</span>/<span id="totalCount">0</span> sản phẩm</div>
                    <nav aria-label="Product pagination">
                        <ul class="pagination" id="pagination">
                            <!-- Phân trang -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalTitle">Thêm sản phẩm mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <input type="hidden" id="productId">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="productName" class="form-label">Tên sản phẩm</label>
                                <input type="text" class="form-control" id="productName" required>
                                <div class="invalid-feedback">Vui lòng nhập tên sản phẩm</div>
                            </div>
                            <div class="col-md-6">
                                <label for="productPrice" class="form-label">Giá</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="productPrice" min="0" step="1000" required>
                                    <span class="input-group-text">VNĐ</span>
                                    <div class="invalid-feedback">Vui lòng nhập giá hợp lệ</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="productCategory" class="form-label">Danh mục</label>
                                <select class="form-select" id="productCategory" required>
                                    <option value="" selected disabled>Chọn danh mục</option>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn danh mục</div>
                            </div>
                            <div class="col-md-6">
                                <label for="productImage" class="form-label">Hình ảnh</label>
                                <input type="text" class="form-control" id="productImage" placeholder="Nhập URL hình ảnh">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="productDescription" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="productDescription" rows="5" required></textarea>
                            <div class="invalid-feedback">Vui lòng nhập mô tả</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-api" id="btnSaveProduct">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa sản phẩm <strong id="deleteProductName"></strong>?</p>
                    <p class="text-danger">Hành động này không thể hoàn tác!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmDelete">Xóa</button>
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
    
    <!-- Loading Overlay -->
    <div class="overlay" id="loadingOverlay">
        <div class="spinner-container">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Đang tải...</span>
            </div>
            <div class="mt-2">Đang xử lý...</div>
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
            // API URLs
            const baseUrl = '<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]"; ?>';
            const productsEndpoint = 'api.php/products';
            const categoriesEndpoint = 'api.php/categories';
            
            // Khởi tạo các thành phần UI
            const productModal = new bootstrap.Modal(document.getElementById('productModal'));
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const toast = new bootstrap.Toast($('.toast'));
            
            // State variables
            let currentPage = 1;
            let limit = 10;
            let offset = 0;
            let totalProducts = 0;
            let currentCategoryFilter = '';
            let currentSearchTerm = '';
            let deleteProductId = null;
            
            // Load dữ liệu ban đầu
            loadCategories();
            loadProducts();
            
            // Event handlers
            $('#btnAddProduct').on('click', function() {
                showAddProductModal();
            });
            
            $('#btnSaveProduct').on('click', function() {
                saveProduct();
            });
            
            $('#btnConfirmDelete').on('click', function() {
                deleteProduct();
            });
            
            $('#btnApplyFilter').on('click', function() {
                applyFilters();
            });
            
            // Functions
            function loadCategories() {
                $.ajax({
                    url: categoriesEndpoint,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        const filterCategorySelect = $('#filterCategory');
                        const productCategorySelect = $('#productCategory');
                        
                        // Clear existing options
                        filterCategorySelect.find('option:not(:first)').remove();
                        productCategorySelect.find('option:not(:first)').remove();
                        
                        // Add categories to selects
                        data.forEach(function(category) {
                            filterCategorySelect.append(
                                $('<option></option>')
                                    .attr('value', category.id)
                                    .text(category.name)
                            );
                            
                            productCategorySelect.append(
                                $('<option></option>')
                                    .attr('value', category.id)
                                    .text(category.name)
                            );
                        });
                    },
                    error: function(xhr, status, error) {
                        showToast('Lỗi', 'Không thể tải danh mục: ' + error, 'danger');
                    }
                });
            }
            
            function loadProducts() {
                showLoading();
                
                // Build query parameters
                let params = {
                    limit: limit,
                    offset: offset
                };
                
                if (currentCategoryFilter) {
                    params.category_id = currentCategoryFilter;
                }
                
                if (currentSearchTerm) {
                    params.search = currentSearchTerm;
                }
                
                // Get products from API
                $.ajax({
                    url: productsEndpoint,
                    method: 'GET',
                    data: params,
                    dataType: 'json',
                    success: function(response) {
                        const products = response.data;
                        totalProducts = response.metadata.total;
                        
                        renderProducts(products);
                        updatePagination();
                        hideLoading();
                    },
                    error: function(xhr, status, error) {
                        showToast('Lỗi', 'Không thể tải sản phẩm: ' + error, 'danger');
                        hideLoading();
                    }
                });
            }
            
            function renderProducts(products) {
                const tbody = $('#productsTable tbody');
                tbody.empty();
                
                if (!products || products.length === 0) {
                    tbody.html('<tr><td colspan="6" class="text-center">Không có sản phẩm nào</td></tr>');
                    $('#currentCount').text('0');
                    $('#totalCount').text('0');
                    return;
                }
                
                products.forEach(function(product) {
                    const imageUrl = product.image 
                        ? (product.image.startsWith('http') ? product.image : 'images/products/' + product.image)
                        : 'images/placeholder.png';
                    
                    const row = `
                        <tr>
                            <td>${product.id}</td>
                            <td><img src="${imageUrl}" alt="${product.name}" class="table-img" onerror="this.src='images/placeholder.png'"></td>
                            <td>${product.name}</td>
                            <td>${formatCurrency(product.price)}</td>
                            <td>${product.category_name || 'N/A'}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-primary btn-edit" data-id="${product.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-delete" data-id="${product.id}" data-name="${product.name}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    
                    tbody.append(row);
                });
                
                // Add event listeners
                $('.btn-edit').on('click', function() {
                    const productId = $(this).data('id');
                    editProduct(productId);
                });
                
                $('.btn-delete').on('click', function() {
                    const productId = $(this).data('id');
                    const productName = $(this).data('name');
                    showDeleteConfirmation(productId, productName);
                });
                
                // Update counts
                $('#currentCount').text(products.length);
                $('#totalCount').text(totalProducts);
            }
            
            function updatePagination() {
                const paginationElement = $('#pagination');
                paginationElement.empty();
                
                const totalPages = Math.ceil(totalProducts / limit);
                
                if (totalPages <= 1) {
                    return;
                }
                
                // Previous button
                const prevDisabled = currentPage === 1 ? 'disabled' : '';
                paginationElement.append(`
                    <li class="page-item ${prevDisabled}">
                        <button class="page-link" aria-label="Previous" data-page="${currentPage - 1}">
                            <span aria-hidden="true">&laquo;</span>
                        </button>
                    </li>
                `);
                
                // Page numbers
                const startPage = Math.max(1, currentPage - 2);
                const endPage = Math.min(totalPages, currentPage + 2);
                
                for (let i = startPage; i <= endPage; i++) {
                    const active = i === currentPage ? 'active' : '';
                    paginationElement.append(`
                        <li class="page-item ${active}">
                            <button class="page-link" data-page="${i}">${i}</button>
                        </li>
                    `);
                }
                
                // Next button
                const nextDisabled = currentPage === totalPages ? 'disabled' : '';
                paginationElement.append(`
                    <li class="page-item ${nextDisabled}">
                        <button class="page-link" aria-label="Next" data-page="${currentPage + 1}">
                            <span aria-hidden="true">&raquo;</span>
                        </button>
                    </li>
                `);
                
                // Add event listeners to pagination buttons
                $('.page-link').on('click', function() {
                    if (!$(this).parent().hasClass('disabled')) {
                        const page = $(this).data('page');
                        currentPage = page;
                        offset = (currentPage - 1) * limit;
                        loadProducts();
                    }
                });
            }
            
            function showAddProductModal() {
                resetForm();
                $('#productId').val('');
                $('#productModalTitle').text('Thêm sản phẩm mới');
                productModal.show();
            }
            
            function editProduct(productId) {
                showLoading();
                
                $.ajax({
                    url: `${productsEndpoint}/${productId}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(product) {
                        resetForm();
                        
                        $('#productId').val(product.id);
                        $('#productName').val(product.name);
                        $('#productDescription').val(product.description);
                        $('#productPrice').val(product.price);
                        $('#productCategory').val(product.category_id);
                        $('#productImage').val(product.image || '');
                        
                        $('#productModalTitle').text('Chỉnh sửa sản phẩm');
                        
                        hideLoading();
                        productModal.show();
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        showToast('Lỗi', 'Không thể tải thông tin sản phẩm: ' + error, 'danger');
                    }
                });
            }
            
            function saveProduct() {
                if (!validateForm()) {
                    return;
                }
                
                showLoading();
                
                const productId = $('#productId').val();
                const productData = {
                    name: $('#productName').val(),
                    description: $('#productDescription').val(),
                    price: parseFloat($('#productPrice').val()),
                    category_id: $('#productCategory').val(),
                    image: $('#productImage').val()
                };
                
                if (productId) {
                    // Update existing product
                    $.ajax({
                        url: `${productsEndpoint}/${productId}`,
                        method: 'PUT',
                        contentType: 'application/json',
                        data: JSON.stringify(productData),
                        success: function(response) {
                            hideLoading();
                            productModal.hide();
                            showToast('Thành công', 'Sản phẩm đã được cập nhật', 'success');
                            loadProducts();
                        },
                        error: function(xhr, status, error) {
                            hideLoading();
                            showToast('Lỗi', 'Không thể cập nhật sản phẩm: ' + error, 'danger');
                        }
                    });
                } else {
                    // Create new product
                    $.ajax({
                        url: productsEndpoint,
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(productData),
                        success: function(response) {
                            hideLoading();
                            productModal.hide();
                            showToast('Thành công', 'Sản phẩm mới đã được tạo', 'success');
                            loadProducts();
                        },
                        error: function(xhr, status, error) {
                            hideLoading();
                            showToast('Lỗi', 'Không thể tạo sản phẩm: ' + error, 'danger');
                        }
                    });
                }
            }
            
            function showDeleteConfirmation(productId, productName) {
                deleteProductId = productId;
                $('#deleteProductName').text(productName);
                deleteModal.show();
            }
            
            function deleteProduct() {
                showLoading();
                
                $.ajax({
                    url: `${productsEndpoint}/${deleteProductId}`,
                    method: 'DELETE',
                    success: function(response) {
                        hideLoading();
                        deleteModal.hide();
                        showToast('Thành công', 'Sản phẩm đã được xóa', 'success');
                        loadProducts();
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        showToast('Lỗi', 'Không thể xóa sản phẩm: ' + error, 'danger');
                    }
                });
            }
            
            function applyFilters() {
                currentCategoryFilter = $('#filterCategory').val();
                currentSearchTerm = $('#filterSearch').val();
                limit = parseInt($('#filterLimit').val());
                currentPage = 1;
                offset = 0;
                loadProducts();
            }
            
            function validateForm() {
                let isValid = true;
                
                const name = $('#productName').val().trim();
                const description = $('#productDescription').val().trim();
                const price = $('#productPrice').val();
                const categoryId = $('#productCategory').val();
                
                // Validate name
                if (!name || name.length < 3) {
                    $('#productName').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#productName').removeClass('is-invalid');
                }
                
                // Validate description
                if (!description || description.length < 10) {
                    $('#productDescription').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#productDescription').removeClass('is-invalid');
                }
                
                // Validate price
                if (!price || parseFloat(price) <= 0) {
                    $('#productPrice').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#productPrice').removeClass('is-invalid');
                }
                
                // Validate category
                if (!categoryId) {
                    $('#productCategory').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#productCategory').removeClass('is-invalid');
                }
                
                return isValid;
            }
            
            function resetForm() {
                $('#productForm')[0].reset();
                $('.is-invalid').removeClass('is-invalid');
            }
            
            function formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND',
                    minimumFractionDigits: 0
                }).format(amount);
            }
            
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
            
            function showLoading() {
                $('#loadingOverlay').css('display', 'flex');
            }
            
            function hideLoading() {
                $('#loadingOverlay').css('display', 'none');
            }
        });
    </script>
</body>
</html> 