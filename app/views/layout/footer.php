    </div> <!-- Đóng container từ header -->
    <footer class="footer mt-auto py-4 bg-dark text-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">Gundam Store</h5>
                    <p class="mb-3">Chuyên cung cấp mô hình Gundam chính hãng từ Nhật Bản với đa dạng các dòng sản phẩm từ các thương hiệu nổi tiếng.</p>
                    <div class="social-icons">
                        <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">Thông tin liên hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Đường ABC, Quận 1, TP.HCM</li>
                        <li class="mb-2"><i class="fas fa-phone-alt me-2"></i> (028) 1234 5678</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@gundamstore.vn</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="text-uppercase mb-4">Danh mục</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?= UrlHelper::url('Product') ?>" class="text-light">Tất cả sản phẩm</a></li>
                        <li class="mb-2"><a href="<?= UrlHelper::url('Product?category=1') ?>" class="text-light">Perfect Grade (PG)</a></li>
                        <li class="mb-2"><a href="<?= UrlHelper::url('Product?category=2') ?>" class="text-light">Master Grade (MG)</a></li>
                        <li class="mb-2"><a href="<?= UrlHelper::url('Product?category=3') ?>" class="text-light">High Grade (HG)</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <hr class="bg-secondary">
                    <p class="text-center mb-0">&copy; <?= date('Y') ?> Gundam Store. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (required for some Bootstrap components) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="<?= UrlHelper::asset('js/main.js') ?>" onerror="console.log('Failed to load main.js')"></script>
    
    <script>
    // Fallback script nếu main.js không load được
    document.addEventListener('DOMContentLoaded', function() {
        // Tự động đóng thông báo (alerts) sau 5 giây
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert && typeof bootstrap !== 'undefined') {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                } else if (alert) {
                    alert.classList.add('fade');
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }
            });
        }, 5000);
        
        // 检查和修复导航栏可能的问题
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            // 确保导航栏显示正常
            navbar.style.display = 'flex';
            
            // 确保下拉菜单工作正常
            const dropdownToggleList = document.querySelectorAll('.dropdown-toggle');
            if (typeof bootstrap !== 'undefined') {
                dropdownToggleList.forEach(function(dropdownToggle) {
                    new bootstrap.Dropdown(dropdownToggle);
                });
            }
            
            // 修复导航栏在移动设备上的问题
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            
            if (navbarToggler && navbarCollapse) {
                // 在移动设备上点击导航项后自动关闭菜单
                const navLinks = navbarCollapse.querySelectorAll('.nav-link');
                navLinks.forEach(function(navLink) {
                    if (!navLink.classList.contains('dropdown-toggle')) {
                        navLink.addEventListener('click', function() {
                            if (window.innerWidth < 992) {
                                navbarCollapse.classList.remove('show');
                            }
                        });
                    }
                });
            }
            
            // 优化导航栏在不同屏幕尺寸下的显示
            function adjustNavbar() {
                const searchForm = document.querySelector('.search-form');
                if (searchForm) {
                    if (window.innerWidth < 992) {
                        searchForm.classList.remove('mx-auto');
                        searchForm.style.width = '100%';
                    } else {
                        searchForm.classList.add('mx-auto');
                        searchForm.style.width = 'auto';
                    }
                }
                
                // 调整导航项的间距
                const navItems = document.querySelectorAll('.navbar-nav .nav-item');
                navItems.forEach(function(navItem) {
                    if (window.innerWidth < 992) {
                        navItem.style.margin = '5px 0';
                    } else {
                        navItem.style.margin = '0 2px';
                    }
                });
                
                // 确保导航栏水平均匀分布
                if (window.innerWidth >= 992) {
                    const firstSection = document.querySelector('.navbar-nav.me-auto');
                    const searchSection = document.querySelector('.search-form');
                    const lastSection = document.querySelector('.navbar-nav.ms-auto');
                    
                    if (firstSection && searchSection && lastSection) {
                        // 计算总宽度
                        const containerWidth = navbar.querySelector('.container-fluid').offsetWidth;
                        const firstWidth = firstSection.offsetWidth;
                        const lastWidth = lastSection.offsetWidth;
                        const searchWidth = Math.min(350, containerWidth - firstWidth - lastWidth - 40);
                        
                        // 设置搜索表单宽度
                        searchSection.style.width = searchWidth + 'px';
                    }
                }
            }
            
            // 初次加载和窗口尺寸变化时调整导航栏
            adjustNavbar();
            window.addEventListener('resize', adjustNavbar);
        }
        
        // 确保Bootstrap加载
        if (typeof bootstrap === 'undefined') {
            console.warn('Bootstrap JS未能加载！正在尝试重新加载...');
            const bootstrapScript = document.createElement('script');
            bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js';
            bootstrapScript.onload = function() {
                console.log('Bootstrap JS已成功重新加载');
                // 重新初始化Bootstrap组件
                const dropdownToggleList = document.querySelectorAll('.dropdown-toggle');
                dropdownToggleList.forEach(function(dropdownToggle) {
                    new bootstrap.Dropdown(dropdownToggle);
                });
            };
            document.body.appendChild(bootstrapScript);
        }
    });
    </script>
</body>
</html> 