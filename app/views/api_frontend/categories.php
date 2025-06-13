<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục - API Frontend</title>
    
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
            <h1 class="mb-0">Quản lý danh mục</h1>
            <div>
                <a href="index.php" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-home me-2"></i> Trang chủ
                </a>
                <button class="btn btn-api" id="btnAddCategory">
                    <i class="fas fa-plus me-2"></i> Thêm danh mục
                </button>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="fas fa-tags me-2"></i> Danh sách danh mục
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="categoriesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên danh mục</th>
                                <th>Mô tả</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sẽ được điền bằng JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalTitle">Thêm danh mục mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm">
                        <input type="hidden" id="categoryId">
                        
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Tên danh mục</label>
                            <input type="text" class="form-control" id="categoryName" required>
                            <div class="invalid-feedback">Vui lòng nhập tên danh mục</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="categoryDescription" rows="4"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-api" id="btnSaveCategory">Lưu</button>
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
                    <p>Bạn có chắc chắn muốn xóa danh mục <strong id="deleteCategoryName"></strong>?</p>
                    <p class="text-danger">Hành động này không thể hoàn tác! Các sản phẩm thuộc danh mục này sẽ không còn được phân loại.</p>
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
            const categoriesEndpoint = 'api.php/categories';
            
            // Khởi tạo các thành phần UI
            const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const toast = new bootstrap.Toast($('.toast'));
            
            // State variables
            let deleteCategoryId = null;
            let dataTable = null;
            
            // Load dữ liệu ban đầu
            loadCategories();
            
            // Event handlers
            $('#btnAddCategory').on('click', function() {
                showAddCategoryModal();
            });
            
            $('#btnSaveCategory').on('click', function() {
                saveCategory();
            });
            
            $('#btnConfirmDelete').on('click', function() {
                deleteCategory();
            });
            
            // Functions
            function loadCategories() {
                showLoading();
                
                $.ajax({
                    url: categoriesEndpoint,
                    method: 'GET',
                    dataType: 'json',
                    success: function(categories) {
                        renderCategories(categories);
                        hideLoading();
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        showToast('Lỗi', 'Không thể tải danh mục: ' + error, 'danger');
                    }
                });
            }
            
            function renderCategories(categories) {
                const tbody = $('#categoriesTable tbody');
                tbody.empty();
                
                if (!categories || categories.length === 0) {
                    tbody.html('<tr><td colspan="4" class="text-center">Không có danh mục nào</td></tr>');
                    return;
                }
                
                categories.forEach(function(category) {
                    const row = `
                        <tr>
                            <td>${category.id}</td>
                            <td>${category.name}</td>
                            <td>${category.description || '<em>Không có mô tả</em>'}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-primary btn-edit" data-id="${category.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-delete" data-id="${category.id}" data-name="${category.name}">
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
                    const categoryId = $(this).data('id');
                    editCategory(categoryId);
                });
                
                $('.btn-delete').on('click', function() {
                    const categoryId = $(this).data('id');
                    const categoryName = $(this).data('name');
                    showDeleteConfirmation(categoryId, categoryName);
                });
                
                // Initialize DataTable
                if (dataTable) {
                    dataTable.destroy();
                }
                
                dataTable = $('#categoriesTable').DataTable({
                    language: {
                        search: "Tìm kiếm:",
                        lengthMenu: "Hiển thị _MENU_ mục",
                        info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                        infoEmpty: "Hiển thị 0 đến 0 của 0 mục",
                        infoFiltered: "(lọc từ _MAX_ mục)",
                        zeroRecords: "Không tìm thấy kết quả",
                        paginate: {
                            first: "Đầu",
                            previous: "Trước",
                            next: "Tiếp",
                            last: "Cuối"
                        }
                    },
                    pageLength: 10,
                    ordering: true,
                    responsive: true
                });
            }
            
            function showAddCategoryModal() {
                resetForm();
                $('#categoryId').val('');
                $('#categoryModalTitle').text('Thêm danh mục mới');
                categoryModal.show();
            }
            
            function editCategory(categoryId) {
                showLoading();
                
                $.ajax({
                    url: `${categoriesEndpoint}/${categoryId}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(category) {
                        resetForm();
                        
                        $('#categoryId').val(category.id);
                        $('#categoryName').val(category.name);
                        $('#categoryDescription').val(category.description || '');
                        
                        $('#categoryModalTitle').text('Chỉnh sửa danh mục');
                        
                        hideLoading();
                        categoryModal.show();
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        showToast('Lỗi', 'Không thể tải thông tin danh mục: ' + error, 'danger');
                    }
                });
            }
            
            function saveCategory() {
                if (!validateForm()) {
                    return;
                }
                
                showLoading();
                
                const categoryId = $('#categoryId').val();
                const categoryData = {
                    name: $('#categoryName').val(),
                    description: $('#categoryDescription').val()
                };
                
                if (categoryId) {
                    // Update existing category
                    $.ajax({
                        url: `${categoriesEndpoint}/${categoryId}`,
                        method: 'PUT',
                        contentType: 'application/json',
                        data: JSON.stringify(categoryData),
                        success: function(response) {
                            hideLoading();
                            categoryModal.hide();
                            showToast('Thành công', 'Danh mục đã được cập nhật', 'success');
                            loadCategories();
                        },
                        error: function(xhr, status, error) {
                            hideLoading();
                            showToast('Lỗi', 'Không thể cập nhật danh mục: ' + error, 'danger');
                        }
                    });
                } else {
                    // Create new category
                    $.ajax({
                        url: categoriesEndpoint,
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(categoryData),
                        success: function(response) {
                            hideLoading();
                            categoryModal.hide();
                            showToast('Thành công', 'Danh mục mới đã được tạo', 'success');
                            loadCategories();
                        },
                        error: function(xhr, status, error) {
                            hideLoading();
                            showToast('Lỗi', 'Không thể tạo danh mục: ' + error, 'danger');
                        }
                    });
                }
            }
            
            function showDeleteConfirmation(categoryId, categoryName) {
                deleteCategoryId = categoryId;
                $('#deleteCategoryName').text(categoryName);
                deleteModal.show();
            }
            
            function deleteCategory() {
                showLoading();
                
                $.ajax({
                    url: `${categoriesEndpoint}/${deleteCategoryId}`,
                    method: 'DELETE',
                    success: function(response) {
                        hideLoading();
                        deleteModal.hide();
                        showToast('Thành công', 'Danh mục đã được xóa', 'success');
                        loadCategories();
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        showToast('Lỗi', 'Không thể xóa danh mục: ' + error, 'danger');
                    }
                });
            }
            
            function validateForm() {
                let isValid = true;
                
                const name = $('#categoryName').val().trim();
                
                // Validate name
                if (!name) {
                    $('#categoryName').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#categoryName').removeClass('is-invalid');
                }
                
                return isValid;
            }
            
            function resetForm() {
                $('#categoryForm')[0].reset();
                $('.is-invalid').removeClass('is-invalid');
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