    </div>
    <footer class="mt-auto py-4 text-bg-dark">
        <div class="container">
            <div class="row gy-4 gx-5">
                <div class="col-lg-4 col-md-6 animate-slide-left">
                    <h5 class="text-white mb-3"><i class="fas fa-box text-primary me-2 animate-float"></i>Quản lý sản phẩm</h5>
                    <p class="small text-white-50">
                        Hệ thống quản lý sản phẩm giúp bạn theo dõi và cập nhật thông tin sản phẩm một cách hiệu quả.
                    </p>
                </div>
                <div class="col-lg-2 col-md-6 animate-fadeIn" style="animation-delay: 200ms;">
                    <h5 class="text-white mb-3">Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="/projectbanhang/Product/" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-angle-right me-2"></i>Sản phẩm</a></li>
                        <li class="mb-2"><a href="/projectbanhang/Product/add" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-angle-right me-2"></i>Thêm sản phẩm</a></li>
                        <li><a href="/projectbanhang/Category/list" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-angle-right me-2"></i>Danh mục</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 animate-fadeIn" style="animation-delay: 400ms;">
                    <h5 class="text-white mb-3">Hỗ trợ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-question-circle me-2"></i>Trợ giúp</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-book me-2"></i>Tài liệu</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-headset me-2"></i>Liên hệ hỗ trợ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 animate-fadeIn" style="animation-delay: 600ms;">
                    <h5 class="text-white mb-3">Liên hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2 text-white-50"><i class="fas fa-map-marker-alt me-2"></i>TP HCM, Việt Nam</li>
                        <li class="mb-2 text-white-50"><i class="fas fa-phone me-2"></i>(+84) 123 456 789</li>
                        <li class="mb-2 text-white-50"><i class="fas fa-envelope me-2"></i>contact@example.com</li>
                    </ul>
                    <div class="d-flex mt-3">
                        <a href="#" class="btn btn-outline-light btn-sm me-2 animate-pulse"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm me-2 animate-pulse"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm me-2 animate-pulse"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm animate-pulse"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4 border-top border-secondary pt-4">
            <p class="mb-0 text-white-50">&copy; <?php echo date('Y'); ?> Quản lý sản phẩm. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Enable tooltips
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Hover effect for footer links
            const hoverLinks = document.querySelectorAll('.hover-link');
            hoverLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    this.classList.add('text-primary');
                    this.style.transform = 'translateX(5px)';
                    this.style.transition = 'all 0.3s ease';
                });
                link.addEventListener('mouseleave', function() {
                    this.classList.remove('text-primary');
                    this.style.transform = 'translateX(0)';
                });
            });
            
            // Animate elements when they come into view
            const animateOnScroll = (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fadeIn');
                        observer.unobserve(entry.target);
                    }
                });
            };
            
            const observer = new IntersectionObserver(animateOnScroll, {
                root: null,
                threshold: 0.1
            });
            
            document.querySelectorAll('.card, .animate-fadeIn').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
