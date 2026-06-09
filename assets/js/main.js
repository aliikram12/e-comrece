// TechStore Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Add to cart
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            addToCart(productId, this);
        });
    });
    
    // Quick buy
    document.querySelectorAll('.quick-buy-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            window.location.href = SITE_URL + 'pages/product-detail.php?id=' + productId;
        });
    });
    
    // Add to wishlist
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            addToWishlist(productId);
        });
    });
    
    // Quantity selector
    document.querySelectorAll('.qty-decrease').forEach(btn => {
        btn.addEventListener('click', function() {
            decreaseQuantity(this);
        });
    });
    
    document.querySelectorAll('.qty-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            increaseQuantity(this);
        });
    });
    
    // Product image thumbnails
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.addEventListener('click', function() {
            const imageSrc = this.dataset.imageSrc;
            document.querySelector('.main-image').src = imageSrc;
            
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Form validation
    document.querySelectorAll('.needs-validation').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    });
});

// Add to cart function
function addToCart(productId, btnElement = null) {
    let quantity = 1;
    let color = null;
    
    if (btnElement) {
        // Try to find quantity and color within the same product container (for index.php/shop.php)
        const container = btnElement.closest('.product-card') || btnElement.closest('.product-detail');
        if (container) {
            const qtyInput = container.querySelector('.qty-input');
            if (qtyInput) quantity = parseInt(qtyInput.value);
            
            const colorSelect = container.querySelector('.color-select');
            if (colorSelect && colorSelect.value) color = colorSelect.value;
        }
    } else {
        // Fallback for direct calls
        const qtyInput = document.querySelector('.qty-input');
        if (qtyInput) quantity = parseInt(qtyInput.value);
        
        const colorSelect = document.querySelector('.color-select');
        if (colorSelect && colorSelect.value) color = colorSelect.value;
    }
    
    if (!color) {
        showNotification('Please select a color first!', 'warning');
        return;
    }
    
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    
    if (color) {
        formData.append('color', color);
    }
    
    fetch(SITE_URL + 'api/add-to-cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Product added to cart!', 'success');
            updateCartCount();
        } else {
            showNotification(data.message || 'Error adding to cart', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding to cart', 'danger');
    });
}

// Add to wishlist function
function addToWishlist(productId) {
    const formData = new FormData();
    formData.append('product_id', productId);
    
    fetch(SITE_URL + 'api/wishlist.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Error', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error', 'danger');
    });
}

// Quantity controls
function increaseQuantity(btn) {
    const input = btn.parentElement.querySelector('.qty-input');
    input.value = parseInt(input.value) + 1;
}

function decreaseQuantity(btn) {
    const input = btn.parentElement.querySelector('.qty-input');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

// Show notification
function showNotification(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('main') || document.body;
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Update cart count
function updateCartCount() {
    fetch(SITE_URL + 'api/get-cart-count.php')
    .then(response => response.json())
    .then(data => {
        const cartBadge = document.querySelector('.cart-icon .badge');
        if (cartBadge) {
            if (data.count > 0) {
                cartBadge.textContent = data.count;
                cartBadge.style.display = 'inline-block';
            } else {
                cartBadge.style.display = 'none';
            }
        }
    });
}

// Format currency
function formatCurrency(amount) {
    return '$' + parseFloat(amount).toFixed(2);
}

// Smooth scroll for anchors
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Dark mode toggle (optional)
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
}

// Load dark mode preference
if (localStorage.getItem('darkMode') === 'true') {
    document.body.classList.add('dark-mode');
}
