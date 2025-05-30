    </div> <!-- Đóng container từ header -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Gundam Store</h5>
                    <p>Chuyên cung cấp mô hình Gundam chính hãng</p>
                </div>
                <div class="col-md-4">
                    <h5>Liên Hệ</h5>
                    <p>
                        <i class="fas fa-phone"></i> 0123 456 789<br>
                        <i class="fas fa-envelope"></i> support@gundamstore.com
                    </p>
                </div>
                <div class="col-md-4">
                    <h5>Kết Nối</h5>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-3 bg-light">
            <p>&copy; 2024 Gundam Store. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

    <script>
        // Scroll Reveal Animation
        function scrollReveal() {
            const reveals = document.querySelectorAll('.scroll-reveal');
            
            reveals.forEach(reveal => {
                const windowHeight = window.innerHeight;
                const revealTop = reveal.getBoundingClientRect().top;
                const revealPoint = 150;

                if (revealTop < windowHeight - revealPoint) {
                    reveal.classList.add('active');
                } else {
                    reveal.classList.remove('active');
                }
            });
        }

        // Add scroll event listener
        window.addEventListener('scroll', scrollReveal);
        
        // Initial call to reveal elements already in view
        scrollReveal();
    </script>
</body>
</html> 