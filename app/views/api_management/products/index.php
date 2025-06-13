<?php require_once 'app/views/layout/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Quản lý sản phẩm (API)</h4>
                    <button id="btnAddProduct" class="btn btn-sm btn-light">
                        <i class="fas fa-plus"></i> Thêm sản phẩm
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select id="categoryFilter" class="form-select">
                                <option value="">Tất cả danh mục</option>
                                <!-- Category options will be loaded here -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                                <button class="btn btn-outline-secondary" type="button" id="btnSearch">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex justify-content-end">
                                <select id="limitSelect" class="form-select" style="width: auto;">
                                    <option value="5">5 mục</option>
                                    <option value="10" selected>10 mục</option>
                                    <option value="25">25 mục</option>
                                    <option value="50">50 mục</option>
                                    <option value="100">100 mục</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Products table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Danh mục</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody">
                                <!-- Products will be loaded here -->
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div id="productCount">Hiển thị <span id="currentCount">0</span>/<span id="totalCount">0</span> sản phẩm</div>
                        <nav aria-label="Product pagination">
                            <ul class="pagination" id="pagination">
                                <!-- Pagination will be generated here -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Thêm sản phẩm mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="productId" value="">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="productName" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="productName" required>
                            <div class="invalid-feedback">Vui lòng nhập tên sản phẩm</div>
                        </div>
                        <div class="col-md-6">
                            <label for="productPrice" class="form-label">Giá sản phẩm</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="productPrice" min="0" step="1000" required>
                                <span class="input-group-text">VNĐ</span>
                                <div class="invalid-feedback">Giá không hợp lệ</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="productCategory" class="form-label">Danh mục</label>
                            <select class="form-select" id="productCategory" required>
                                <!-- Categories will be loaded here -->
                                <option value="" disabled selected>Chọn danh mục</option>
                            </select>
                            <div class="invalid-feedback">Vui lòng chọn danh mục</div>
                        </div>
                        <div class="col-md-6">
                            <label for="productImage" class="form-label">Hình ảnh</label>
                            <input type="text" class="form-control" id="productImage" placeholder="URL hình ảnh">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="productDescription" rows="5" required></textarea>
                        <div class="invalid-feedback">Vui lòng nhập mô tả sản phẩm</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="btnSaveProduct">Lưu</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Delete Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Thông báo</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            
        </div>
    </div>
</div>

<script>
// API URL bases
const apiBaseUrl = '<?= UrlHelper::url('api.php') ?>';
const productsEndpoint = `${apiBaseUrl}/products`;
const categoriesEndpoint = `${apiBaseUrl}/categories`;

// Log API URLs for debugging
console.log('API Base URL:', apiBaseUrl);
console.log('Products Endpoint:', productsEndpoint);
console.log('Categories Endpoint:', categoriesEndpoint);

// Current state
let currentPage = 1;
let limit = 10;
let offset = 0;
let totalProducts = 0;
let currentCategoryFilter = '';
let currentSearchTerm = '';
let currentModal = null;
let deleteProductId = null;

// Elements
const productsTableBody = document.getElementById('productsTableBody');
const categoryFilter = document.getElementById('categoryFilter');
const searchInput = document.getElementById('searchInput');
const btnSearch = document.getElementById('btnSearch');
const limitSelect = document.getElementById('limitSelect');
const pagination = document.getElementById('pagination');
const currentCount = document.getElementById('currentCount');
const totalCount = document.getElementById('totalCount');
const productForm = document.getElementById('productForm');
const productModal = document.getElementById('productModal');
const productModalLabel = document.getElementById('productModalLabel');
const deleteConfirmModal = document.getElementById('deleteConfirmModal');
const deleteProductName = document.getElementById('deleteProductName');
const btnConfirmDelete = document.getElementById('btnConfirmDelete');
const toast = document.getElementById('liveToast');
const toastBody = document.querySelector('.toast-body');

// Create Bootstrap modal instances - safe initialization with fallbacks
let productModalInstance;
let deleteConfirmModalInstance;
let toastInstance;

// Initialize Bootstrap components safely
function initializeBootstrapComponents() {
    // Check if Bootstrap is defined
    if (typeof bootstrap !== 'undefined') {
        productModalInstance = new bootstrap.Modal(productModal);
        deleteConfirmModalInstance = new bootstrap.Modal(deleteConfirmModal);
        toastInstance = new bootstrap.Toast(toast);
    } else {
        console.error('Bootstrap is not defined. Loading Bootstrap JS...');
        
        // Try to load Bootstrap JS
        const bootstrapScript = document.createElement('script');
        bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js';
        bootstrapScript.onload = function() {
            console.log('Bootstrap JS loaded successfully');
            productModalInstance = new bootstrap.Modal(productModal);
            deleteConfirmModalInstance = new bootstrap.Modal(deleteConfirmModal);
            toastInstance = new bootstrap.Toast(toast);
        };
        document.body.appendChild(bootstrapScript);
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Bootstrap components
    initializeBootstrapComponents();
    
    // Initial data loading - using a small timeout to prevent giật lag
    setTimeout(() => {
        // Load initial data
        loadCategories();
        loadProducts();
    }, 100);
    
    // Set up event handlers
    document.getElementById('btnAddProduct').addEventListener('click', showAddProductModal);
    document.getElementById('btnSaveProduct').addEventListener('click', saveProduct);
    categoryFilter.addEventListener('change', filterProducts);
    btnSearch.addEventListener('click', filterProducts);
    searchInput.addEventListener('keypress', e => {
        if (e.key === 'Enter') filterProducts();
    });
    limitSelect.addEventListener('change', () => {
        limit = parseInt(limitSelect.value);
        offset = 0;
        currentPage = 1;
        loadProducts();
    });
    btnConfirmDelete.addEventListener('click', deleteProduct);
    
    // Cache các selector thường dùng để tăng hiệu suất
    document.getElementById('productModal').addEventListener('hidden.bs.modal', () => {
        // Reset form state khi đóng modal
        resetForm();
    });
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
            throw new Error(`Invalid JSON response: ${responseText.substring(0, 100)}...`);
        }
        
        if (!response.ok) {
            throw new Error(data.message || `HTTP Error ${response.status}: ${response.statusText}`);
        }
        
        return data;
    } catch (error) {
        console.error('API Error:', error);
        showToast(`Lỗi: ${error.message}`, 'error');
        return null;
    }
}

// Product functions
async function loadProducts() {
    productsTableBody.innerHTML = `
        <tr>
            <td colspan="6" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                </div>
            </td>
        </tr>
    `;
    
    // Build query parameters
    const params = new URLSearchParams();
    params.append('limit', limit);
    params.append('offset', offset);
    
    if (currentCategoryFilter) {
        params.append('category_id', currentCategoryFilter);
    }
    
    if (currentSearchTerm) {
        params.append('search', currentSearchTerm);
    }
    
    const result = await fetchAPI(`${productsEndpoint}?${params.toString()}`);
    
    if (!result || !result.data || !Array.isArray(result.data) || result.data.length === 0) {
        productsTableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center">Không có sản phẩm nào.</td>
            </tr>
        `;
        updatePagination(0);
        return;
    }
    
    // Update pagination info
    totalProducts = result.metadata.total;
    updatePagination(totalProducts);
    
    // Clear table body
    productsTableBody.innerHTML = '';
    
    // Add products to table
    result.data.forEach(product => {
        const tr = document.createElement('tr');
        
        // Process image URL
        let imageUrl = '';
        if (product.image) {
            if (product.image.startsWith('http')) {
                imageUrl = product.image;
            } else if (product.image.startsWith('uploads/')) {
                imageUrl = `${window.location.origin}/public/${product.image}`;
            } else {
                imageUrl = `${window.location.origin}/public/uploads/products/${product.image}`;
            }
        } else {
            imageUrl = `${window.location.origin}/public/images/no-image.jpg`;
        }
        
        tr.innerHTML = `
            <td>${product.id}</td>
            <td>
                <img src="${imageUrl}" alt="${product.name}" 
                     onerror="this.onerror=null; this.src='${window.location.origin}/public/images/no-image.jpg';" 
                     class="img-thumbnail" style="max-width: 80px; max-height: 80px;">
            </td>
            <td>${product.name}</td>
            <td>${formatCurrency(product.price)}</td>
            <td>${product.category_name || 'Không có danh mục'}</td>
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
        `;
        
        // Add event listeners to buttons
        const editBtn = tr.querySelector('.btn-edit');
        const deleteBtn = tr.querySelector('.btn-delete');
        
        editBtn.addEventListener('click', () => showEditProductModal(product.id));
        deleteBtn.addEventListener('click', () => showDeleteConfirmation(product.id, product.name));
        
        productsTableBody.appendChild(tr);
    });
}

function updatePagination(total) {
    const totalPages = Math.ceil(total / limit);
    pagination.innerHTML = '';
    
    // Update counts
    currentCount.textContent = productsTableBody.querySelectorAll('tr').length;
    totalCount.textContent = total;
    
    if (totalPages <= 1) return;
    
    // Previous button
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = '<a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
    if (currentPage > 1) {
        prevLi.addEventListener('click', () => {
            currentPage--;
            offset = (currentPage - 1) * limit;
            loadProducts();
        });
    }
    pagination.appendChild(prevLi);
    
    // Page numbers
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, currentPage + 2);
    
    if (endPage - startPage < 4) {
        if (currentPage < 3) {
            endPage = Math.min(totalPages, 5);
        } else {
            startPage = Math.max(1, totalPages - 4);
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        const pageLi = document.createElement('li');
        pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
        pageLi.innerHTML = `<a class="page-link" href="#">${i}</a>`;
        
        if (i !== currentPage) {
            pageLi.addEventListener('click', () => {
                currentPage = i;
                offset = (currentPage - 1) * limit;
                loadProducts();
            });
        }
        
        pagination.appendChild(pageLi);
    }
    
    // Next button
    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
    nextLi.innerHTML = '<a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
    if (currentPage < totalPages) {
        nextLi.addEventListener('click', () => {
            currentPage++;
            offset = (currentPage - 1) * limit;
            loadProducts();
        });
    }
    pagination.appendChild(nextLi);
}

async function loadCategories(selectedCategoryId = null) {
    try {
        const categories = await fetchAPI(categoriesEndpoint);
        
        if (!categories || !Array.isArray(categories) || categories.length === 0) {
            categoryFilter.innerHTML = '<option value="">Không có danh mục nào</option>';
            document.getElementById('productCategory').innerHTML = '<option value="" disabled selected>Không có danh mục nào</option>';
            return;
        }
        
        // Update category filter
        let filterOptions = '<option value="">Tất cả danh mục</option>';
        categories.forEach(category => {
            filterOptions += `<option value="${category.id}">${category.name}</option>`;
        });
        categoryFilter.innerHTML = filterOptions;
        
        // If there was a previous selection, restore it
        if (currentCategoryFilter) {
            categoryFilter.value = currentCategoryFilter;
        }
        
        // Update product category select in modal
        let categoryOptions = '<option value="" disabled>Chọn danh mục</option>';
        categories.forEach(category => {
            const selected = selectedCategoryId && category.id == selectedCategoryId ? 'selected' : '';
            categoryOptions += `<option value="${category.id}" ${selected}>${category.name}</option>`;
        });
        document.getElementById('productCategory').innerHTML = categoryOptions;
        
    } catch (error) {
        console.error('Error loading categories:', error);
        showToast('Không thể tải danh sách danh mục', 'error');
    }
}

function filterProducts() {
    currentCategoryFilter = categoryFilter.value;
    currentSearchTerm = searchInput.value.trim();
    currentPage = 1;
    offset = 0;
    loadProducts();
}

function showAddProductModal() {
    resetForm();
    document.getElementById('productId').value = '';
    productModalLabel.textContent = 'Thêm sản phẩm mới';
    productModalInstance.show();
}

async function showEditProductModal(productId) {
    resetForm();
    productModalLabel.textContent = 'Cập nhật sản phẩm';

    // Hiển thị spinner trong modal
    const modalBody = document.querySelector('#productModal .modal-body');
    const originalContent = modalBody.innerHTML;
    modalBody.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Đang tải...</span>
            </div>
            <p class="mt-2">Đang tải thông tin sản phẩm...</p>
        </div>
    `;
    
    try {
        // Hiện modal trước khi tải dữ liệu để người dùng thấy đang xử lý
        productModalInstance.show();
        
        const product = await fetchAPI(`${productsEndpoint}/${productId}`);
        
        // Khôi phục nội dung modal
        modalBody.innerHTML = originalContent;
        
        if (!product) {
            productModalInstance.hide();
            showToast('Không thể tải thông tin sản phẩm', 'error');
            return;
        }
        
        // Điền thông tin sản phẩm vào form
        document.getElementById('productId').value = product.id;
        document.getElementById('productName').value = product.name;
        document.getElementById('productDescription').value = product.description;
        document.getElementById('productPrice').value = product.price;
        document.getElementById('productImage').value = product.image || '';

        // Đảm bảo danh mục đã được tải
        await loadCategories(product.category_id);
        
    } catch (error) {
        console.error('Error loading product:', error);
        modalBody.innerHTML = originalContent;
        showToast('Không thể tải thông tin sản phẩm', 'error');
    }
}

function showDeleteConfirmation(id, name) {
    deleteProductId = id;
    document.getElementById('deleteProductName').textContent = name;
    deleteConfirmModalInstance.show();
}

async function saveProduct() {
    // Validate form
    if (!validateForm()) {
        return;
    }
    
    // Collect form data
    const id = document.getElementById('productId').value.trim();
    const name = document.getElementById('productName').value.trim();
    const description = document.getElementById('productDescription').value.trim();
    const price = document.getElementById('productPrice').value.trim();
    const categoryId = document.getElementById('productCategory').value.trim();
    const image = document.getElementById('productImage').value.trim();
    
    // Create product object
    const productData = {
        name,
        description,
        price,
        category_id: categoryId,
        image
    };
    
    // Disable save button and show loading state
    const saveButton = document.getElementById('btnSaveProduct');
    const originalText = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang lưu...`;
    
    try {
        let response;
        
        if (id) {
            // Update existing product
            response = await fetchAPI(`${productsEndpoint}/${id}`, {
                method: 'PUT',
                body: JSON.stringify(productData)
            });
            
            showToast('Sản phẩm đã được cập nhật thành công', 'success');
        } else {
            // Create new product
            response = await fetchAPI(productsEndpoint, {
                method: 'POST',
                body: JSON.stringify(productData)
            });
            
            showToast('Sản phẩm đã được tạo thành công', 'success');
        }
        
        productModalInstance.hide();
        loadProducts(); // Reload products
    } catch (error) {
        console.error('Error saving product:', error);
        showToast(`Không thể lưu sản phẩm: ${error.message}`, 'error');
    } finally {
        // Restore button state
        saveButton.disabled = false;
        saveButton.innerHTML = originalText;
    }
}

async function deleteProduct() {
    if (!deleteProductId) return;
    
    // Disable delete button and show loading state
    const deleteButton = document.getElementById('btnConfirmDelete');
    const originalText = deleteButton.innerHTML;
    deleteButton.disabled = true;
    deleteButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xóa...`;
    
    try {
        await fetchAPI(`${productsEndpoint}/${deleteProductId}`, {
            method: 'DELETE'
        });
        
        deleteConfirmModalInstance.hide();
        showToast('Sản phẩm đã được xóa thành công', 'success');
        loadProducts(); // Reload products
    } catch (error) {
        console.error('Error deleting product:', error);
        showToast(`Không thể xóa sản phẩm: ${error.message}`, 'error');
    } finally {
        // Restore button state
        deleteButton.disabled = false;
        deleteButton.innerHTML = originalText;
        
        // Reset delete ID
        deleteProductId = null;
    }
}

// Helper functions
function validateForm() {
    const name = document.getElementById('productName').value.trim();
    const description = document.getElementById('productDescription').value.trim();
    const price = document.getElementById('productPrice').value.trim();
    const categoryId = document.getElementById('productCategory').value;
    
    let isValid = true;
    
    // Validate name
    if (name.length < 1) {
        document.getElementById('productName').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('productName').classList.remove('is-invalid');
    }
    
    // Validate description
    if (description.length < 1) {
        document.getElementById('productDescription').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('productDescription').classList.remove('is-invalid');
    }
    
    // Validate price
    if (!price || isNaN(price) || Number(price) <= 0) {
        document.getElementById('productPrice').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('productPrice').classList.remove('is-invalid');
    }
    
    // Validate category
    if (!categoryId) {
        document.getElementById('productCategory').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('productCategory').classList.remove('is-invalid');
    }
    
    return isValid;
}

function resetForm() {
    // Reset all form fields
    document.getElementById('productForm').reset();
    
    // Clear validation
    const invalidElements = document.querySelectorAll('.is-invalid');
    invalidElements.forEach(el => el.classList.remove('is-invalid'));
}

function showToast(message, type = 'info') {
    toastBody.textContent = message;
    
    // Set toast color based on type
    const toast = document.getElementById('liveToast');
    toast.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info');
    
    switch (type) {
        case 'success':
            toast.classList.add('bg-success', 'text-white');
            break;
        case 'error':
            toast.classList.add('bg-danger', 'text-white');
            break;
        case 'warning':
            toast.classList.add('bg-warning');
            break;
        default:
            toast.classList.add('bg-info', 'text-white');
    }
    
    toastInstance.show();
}

function formatCurrency(amount) {
    if (!amount) return '0 ₫';
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}
</script>

<?php require_once 'app/views/layout/footer.php'; ?> 