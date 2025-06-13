/**
 * main.js - JavaScript chính cho Gundam Store
 */

// Tự động đóng thông báo (alerts) sau 5 giây
document.addEventListener("DOMContentLoaded", function () {
  setTimeout(function () {
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(function (alert) {
      if (alert && typeof bootstrap !== "undefined") {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      } else if (alert) {
        alert.classList.add("fade");
        setTimeout(() => {
          alert.remove();
        }, 500);
      }
    });
  }, 5000);

  // Hiệu ứng khi hover vào sản phẩm
  const productCards = document.querySelectorAll(".product-card");
  if (productCards.length > 0) {
    productCards.forEach((card) => {
      card.addEventListener("mouseenter", function () {
        this.classList.add("shadow-lg");
      });
      card.addEventListener("mouseleave", function () {
        this.classList.remove("shadow-lg");
      });
    });
  }

  // Initialize tooltips
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Animation for elements with .animate-fadeInUp class
  setTimeout(() => {
    document.querySelectorAll(".animate-fadeInUp").forEach((item) => {
      item.style.opacity = 1;
      item.style.transform = "translateY(0)";
    });
  }, 100);

  // Add event listeners to any delete buttons to confirm deletion
  const deleteButtons = document.querySelectorAll(".confirm-delete");
  if (deleteButtons) {
    deleteButtons.forEach((button) => {
      button.addEventListener("click", function (e) {
        if (!confirm("Bạn có chắc chắn muốn xóa?")) {
          e.preventDefault();
        }
      });
    });
  }

  // Cập nhật số lượng giỏ hàng realtime
  const quantityInputs = document.querySelectorAll(".cart-quantity");
  if (quantityInputs.length > 0) {
    quantityInputs.forEach((input) => {
      input.addEventListener("change", function () {
        const form = this.closest("form");
        if (form) {
          form.submit();
        }
      });
    });
  }

  // Handle image preview for file inputs
  const imageInputs = document.querySelectorAll(".image-input");
  if (imageInputs) {
    imageInputs.forEach((input) => {
      input.addEventListener("change", function () {
        const preview = document.getElementById(this.dataset.previewTarget);
        if (preview && this.files && this.files[0]) {
          const reader = new FileReader();
          reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = "block";
          };
          reader.readAsDataURL(this.files[0]);
        }
      });
    });
  }

  // Handle quantity inputs in product detail page
  const quantityInput = document.getElementById("quantity");
  const increaseBtn = document.querySelector(".btn-increase");
  const decreaseBtn = document.querySelector(".btn-decrease");

  if (quantityInput && increaseBtn && decreaseBtn) {
    increaseBtn.addEventListener("click", function () {
      quantityInput.value = parseInt(quantityInput.value) + 1;
    });

    decreaseBtn.addEventListener("click", function () {
      if (parseInt(quantityInput.value) > 1) {
        quantityInput.value = parseInt(quantityInput.value) - 1;
      }
    });
  }

  // Hiệu ứng active cho liên kết menu
  highlightActiveNavLink();
});

// Hàm đánh dấu menu hiện tại
function highlightActiveNavLink() {
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll(".navbar .nav-link");

  navLinks.forEach((link) => {
    const href = link.getAttribute("href");
    if (
      href === currentPath ||
      (href !== "/webbanhang/" && currentPath.startsWith(href))
    ) {
      link.classList.add("active");
    }
  });
}
