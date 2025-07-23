/**
 * Basket Confirmation JavaScript
 * Kullanım: Bu dosyayı frontend sayfalarınıza include edin
 */

class BasketManager {
    constructor() {
        this.apiUrl = '/api/v1/basket/confirm';
    }

    /**
     * Sepeti onayla ve veritabanına kaydet
     * @param {Object} basketData - Sepet verileri
     * @returns {Promise} - API response
     */
    async confirmBasket(basketData) {
        console.log('BasketManager: confirmBasket called with data:', basketData);
        
        // Validation
        if (!basketData.email || !this.isValidEmail(basketData.email)) {
            this.showErrorMessage('Lütfen geçerli bir email adresi girin');
            return;
        }
        
        if (!basketData.items || basketData.items.length === 0) {
            this.showErrorMessage('Sepetiniz boş!');
            return;
        }
        
        if (!basketData.total_price || basketData.total_price <= 0) {
            this.showErrorMessage('Geçersiz toplam fiyat!');
            return;
        }
        
        console.log('BasketManager: Validation passed');
        
        try {
            console.log('BasketManager: Making API request to:', this.apiUrl);
            
            const response = await fetch(this.apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(basketData)
            });

            console.log('BasketManager: API response status:', response.status);
            
            const result = await response.json();
            console.log('BasketManager: API response data:', result);
            
            if (result.success) {
                // Başarılı onay
                console.log('BasketManager: Success! Basket confirmed.');
                this.showSuccessMessage('Sepetiniz başarıyla onaylandı! Email adresinizi kontrol edin.');
                this.clearCart();
                this.redirectToHome();
                return result;
            } else {
                // API hatası
                console.error('BasketManager: API error:', result.message);
                this.showErrorMessage('Hata: ' + result.message);
                return result;
            }
        } catch (error) {
            console.error('BasketManager: Network error:', error);
            this.showErrorMessage('Bir hata oluştu. Lütfen tekrar deneyin.');
            throw error;
        }
    }

    /**
     * Başarı mesajı göster
     * @param {string} message - Mesaj
     */
    showSuccessMessage(message) {
        // Bootstrap alert kullanıyorsanız
        if (typeof bootstrap !== 'undefined') {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.insertBefore(alertDiv, document.body.firstChild);
        } else {
            // Basit alert
            alert(message);
        }
    }

    /**
     * Hata mesajı göster
     * @param {string} message - Hata mesajı
     */
    showErrorMessage(message) {
        // Bootstrap alert kullanıyorsanız
        if (typeof bootstrap !== 'undefined') {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.insertBefore(alertDiv, document.body.firstChild);
        } else {
            // Basit alert
            alert(message);
        }
    }

    /**
     * Sepeti temizle (localStorage'dan)
     */
    clearCart() {
        // localStorage'dan sepeti temizle
        localStorage.removeItem('cart');
        localStorage.removeItem('cartItems');
        
        // Eğer cart count element'i varsa güncelle
        const cartCountElements = document.querySelectorAll('.cart-count, .cart-badge');
        cartCountElements.forEach(element => {
            element.textContent = '0';
        });
    }

    /**
     * Ana sayfaya yönlendir
     */
    redirectToHome() {
        setTimeout(() => {
            window.location.href = '/';
        }, 2000); // 2 saniye sonra yönlendir
    }

    /**
     * Sepet verilerini hazırla
     * @param {Array} items - Sepet ürünleri
     * @param {number} totalPrice - Toplam fiyat
     * @param {string} discountCode - İndirim kodu
     * @param {string} name - Müşteri adı
     * @param {string} address - Adres
     * @returns {Object} - Hazırlanmış sepet verisi
     */
    prepareBasketData(items, totalPrice, discountCode, name, address) {
        return {
            items: items,
            total_price: totalPrice,
            discount_code: discountCode || null,
            name: name,
            address: address
        };
    }

    /**
     * Email formatını kontrol et
     * @param {string} email - Kontrol edilecek email
     * @returns {boolean} - Geçerli mi?
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
}

// Global instance oluştur
window.basketManager = new BasketManager();

// Kullanım örnekleri:

// 1. Basit kullanım
function confirmBasket() {
    const basketData = {
        items: [
            {product_id: 1, quantity: 2},
            {product_id: 3, quantity: 1}
        ],
        total_price: 120,
        discount_code: "SUMMER25",
        name: "Ali Veli",
        address: "Ankara, Turkey"
    };

    window.basketManager.confirmBasket(basketData);
}

// 2. Form verilerinden kullanım
function confirmBasketFromForm() {
    const name = document.getElementById('customer-name').value;
    const address = document.getElementById('customer-address').value;
    const discountCode = document.getElementById('discount-code').value;
    
    // Sepet verilerini localStorage'dan al
    const cartItems = JSON.parse(localStorage.getItem('cartItems') || '[]');
    const totalPrice = parseFloat(localStorage.getItem('cartTotal') || '0');

    const basketData = window.basketManager.prepareBasketData(
        cartItems,
        totalPrice,
        discountCode,
        name,
        address
    );

    window.basketManager.confirmBasket(basketData);
}

// 3. Event listener örneği
document.addEventListener('DOMContentLoaded', function() {
    const confirmButton = document.getElementById('confirm-basket-btn');
    if (confirmButton) {
        confirmButton.addEventListener('click', confirmBasketFromForm);
    }
}); 