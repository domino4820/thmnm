<?php require_once 'app/views/layout/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Quản lý danh mục (API)</h4>
                    <button id="btnAddCategory" class="btn btn-sm btn-light">
                        <i class="fas fa-plus"></i> Thêm danh mục
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên danh mục</th>
                                    <th>Mô tả</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="categoriesTableBody">
                                <!-- Categories will be loaded here -->
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Đang tải...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Thêm danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <input type="hidden" id="categoryId" value="">
                    
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="categoryName" required>
                        <div class="invalid-feedback">Vui lòng nhập tên danh mục</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="categoryDescription" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="btnSaveCategory">Lưu</button>
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
                <p>Bạn có chắc chắn muốn xóa danh mục <strong id="deleteCategoryName"></strong>?</p>
                <p class="text-danger">Hành động này không thể hoàn tác! Các sản phẩm thuộc danh mục này sẽ không có danh mục.</p>
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
const categoriesEndpoint = `${apiBaseUrl}/categories`;

// Log API URLs for debugging
console.log('API Base URL:', apiBaseUrl);
console.log('Categories Endpoint:', categoriesEndpoint);

// Current state
let deleteCategoryId = null;

// Elements
const categoryForm = document.getElementById('categoryForm');
const categoryModal = document.getElementById('categoryModal');
const categoryModalLabel = document.getElementById('categoryModalLabel');
const deleteConfirmModal = document.getElementById('deleteConfirmModal');
const deleteCategoryName = document.getElementById('deleteCategoryName');
const btnConfirmDelete = document.getElementById('btnConfirmDelete');
const toast = document.getElementById('liveToast');
const toastBody = document.querySelector('.toast-body');
const categoriesTableBody = document.getElementById('categoriesTableBody');

// Create Bootstrap modal instances - safe initialization with fallbacks
let categoryModalInstance;
let deleteConfirmModalInstance;
let toastInstance;

// Initialize Bootstrap components safely
function initializeBootstrapComponents() {
    // Check if Bootstrap is defined
    if (typeof bootstrap !== 'undefined') {
        categoryModalInstance = new bootstrap.Modal(categoryModal);
        deleteConfirmModalInstance = new bootstrap.Modal(deleteConfirmModal);
        toastInstance = new bootstrap.Toast(toast);
    } else {
        console.error('Bootstrap is not defined. Loading Bootstrap JS...');
        
        // Try to load Bootstrap JS
        const bootstrapScript = document.createElement('script');
        bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js';
        bootstrapScript.onload = function() {
            console.log('Bootstrap JS loaded successfully');
            categoryModalInstance = new bootstrap.Modal(categoryModal);
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
    }, 100);
    
    // Set up event handlers
    document.getElementById('btnAddCategory').addEventListener('click', showAddCategoryModal);
    document.getElementById('btnSaveCategory').addEventListener('click', saveCategory);
    btnConfirmDelete.addEventListener('click', deleteCategory);
    
    // Reset form when modal is hidden
    categoryModal.addEventListener('hidden.bs.modal', resetForm);
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

// Category functions
async function loadCategories() {
    categoriesTableBody.innerHTML = `
        <tr>
            <td colspan="4" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                </div>
            </td>
        </tr>
    `;
    
    const categories = await fetchAPI(categoriesEndpoint);
    
    if (!categories || !Array.isArray(categories) || categories.length === 0) {
        categoriesTableBody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center">Không có danh mục nào.</td>
            </tr>
        `;
        return;
    }
    
    categoriesTableBody.innerHTML = '';
    
    categories.forEach(category => {
        const tr = document.createElement('tr');
        
        tr.innerHTML = `
            <td>${category.id}</td>
            <td>${category.name}</td>
            <td>${category.description || 'Không có mô tả'}</td>
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
        `;
        
        // Add event listeners to buttons
        const editBtn = tr.querySelector('.btn-edit');
        const deleteBtn = tr.querySelector('.btn-delete');
        
        editBtn.addEventListener('click', () => showEditCategoryModal(category.id));
        deleteBtn.addEventListener('click', () => showDeleteConfirmation(category.id, category.name));
        
        categoriesTableBody.appendChild(tr);
    });
}

function showAddCategoryModal() {
    resetForm();
    document.getElementById('categoryId').value = '';
    categoryModalLabel.textContent = 'Thêm danh mục mới';
    categoryModalInstance.show();
}

async function showEditCategoryModal(categoryId) {
    resetForm();
    categoryModalLabel.textContent = 'Cập nhật danh mục';
    
    // Hiển thị spinner trong modal
    const modalBody = document.querySelector('#categoryModal .modal-body');
    const originalContent = modalBody.innerHTML;
    modalBody.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Đang tải...</span>
            </div>
            <p class="mt-2">Đang tải thông tin danh mục...</p>
        </div>
    `;
    
    try {
        // Hiện modal trước khi tải dữ liệu để người dùng thấy đang xử lý
        categoryModalInstance.show();
        
        const category = await fetchAPI(`${categoriesEndpoint}/${categoryId}`);
        
        // Khôi phục nội dung modal
        modalBody.innerHTML = originalContent;
        
        if (!category) {
            categoryModalInstance.hide();
            showToast('Không thể tải thông tin danh mục', 'error');
            return;
        }
        
        document.getElementById('categoryId').value = category.id;
        document.getElementById('categoryName').value = category.name;
        document.getElementById('categoryDescription').value = category.description || '';
        
    } catch (error) {
        console.error('Error loading category:', error);
        modalBody.innerHTML = originalContent;
        showToast('Không thể tải thông tin danh mục', 'error');
    }
}

function showDeleteConfirmation(id, name) {
    deleteCategoryId = id;
    deleteCategoryName.textContent = name;
    deleteConfirmModalInstance.show();
}

async function saveCategory() {
    // Validate form
    if (!validateForm()) {
        return;
    }
    
    // Get form values
    const id = document.getElementById('categoryId').value.trim();
    const name = document.getElementById('categoryName').value.trim();
    const description = document.getElementById('categoryDescription').value.trim();
    
    // Create category object
    const categoryData = {
        name,
        description
    };
    
    // Disable save button and show loading state
    const saveButton = document.getElementById('btnSaveCategory');
    const originalText = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang lưu...`;
    
    try {
        let response;
        
        if (id) {
            // Update existing category
            response = await fetchAPI(`${categoriesEndpoint}/${id}`, {
                method: 'PUT',
                body: JSON.stringify(categoryData)
            });
            
            showToast('Danh mục đã được cập nhật thành công', 'success');
        } else {
            // Create new category
            response = await fetchAPI(categoriesEndpoint, {
                method: 'POST',
                body: JSON.stringify(categoryData)
            });
            
            showToast('Danh mục đã được tạo thành công', 'success');
        }
        
        categoryModalInstance.hide();
        loadCategories(); // Reload categories
    } catch (error) {
        console.error('Error saving category:', error);
        showToast(`Không thể lưu danh mục: ${error.message}`, 'error');
    } finally {
        // Restore button state
        saveButton.disabled = false;
        saveButton.innerHTML = originalText;
    }
}

async function deleteCategory() {
    if (!deleteCategoryId) return;
    
    // Disable delete button and show loading state
    const deleteButton = document.getElementById('btnConfirmDelete');
    const originalText = deleteButton.innerHTML;
    deleteButton.disabled = true;
    deleteButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xóa...`;
    
    try {
        await fetchAPI(`${categoriesEndpoint}/${deleteCategoryId}`, {
            method: 'DELETE'
        });
        
        deleteConfirmModalInstance.hide();
        showToast('Danh mục đã được xóa thành công', 'success');
        loadCategories(); // Reload categories
    } catch (error) {
        console.error('Error deleting category:', error);
        showToast(`Không thể xóa danh mục: ${error.message}`, 'error');
    } finally {
        // Restore button state
        deleteButton.disabled = false;
        deleteButton.innerHTML = originalText;
        
        // Reset delete ID
        deleteCategoryId = null;
    }
}

// Helper functions
function validateForm() {
    const name = document.getElementById('categoryName').value.trim();
    
    let isValid = true;
    
    // Validate name
    if (name.length < 1) {
        document.getElementById('categoryName').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('categoryName').classList.remove('is-invalid');
    }
    
    return isValid;
}

function resetForm() {
    // Reset all form fields
    categoryForm.reset();
    
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
</script>

<?php require_once 'app/views/layout/footer.php'; ?> 