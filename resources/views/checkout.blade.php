<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Sipariş Onayı</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .checkout-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .step-indicator { display: flex; justify-content: center; margin-bottom: 30px; }
        .step {
            flex: 1 1 0;
            display: flex; align-items: center; justify-content: center;
            margin: 0 5px;
            padding: 10px 20px; border-radius: 25px; background: #f8f9fa;
            transition: all 0.3s ease;
            min-width: 0;
            max-width: 100%;
        }
        .step.active { background: #007bff; color: white; }
        .step.completed { background: #28a745; color: white; }
        .step-number {
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-right: 10px; font-weight: bold;
        }
        .step-content { display: none; }
        .step-content.active { display: block; }
        .cart-item { border: 1px solid #eee; padding: 15px; margin: 10px 0; border-radius: 8px; }
        .form-section { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .btn-navigation { margin: 10px; }
        .btn:disabled { 
            opacity: 0.6; 
            cursor: not-allowed;
        }
        
        .btn:disabled:hover {
            opacity: 0.6;
        }
        .credit-card-form { max-width: 400px; margin: 0 auto; }
        .card-preview {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white; padding: 20px; border-radius: 10px; margin: 20px 0;
        }
        .required-field { color: red; }
        .exit-basket-btn {
            background: none !important;
            border: none !important;
            color: red !important;
            font-size: 12px !important;
            padding: 5px 10px !important;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .exit-basket-btn:hover {
            background: none !important;
            color: darkred !important;
        }
        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #007bff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin: 0 auto;
            vertical-align: middle;
        }
        
        .btn-with-loader {
            min-width: 200px;
            min-height: 38px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .success-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            z-index: 10000;
            text-align: center;
            display: none;
        }
        .success-popup.show {
            display: block;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            display: none;
        }
        .overlay.show {
            display: block;
        }
        
        /* Modal'ın tam ortada görünmesi için */
        .modal {
            display: none;
        }
        
        .modal.show {
            display: block !important;
        }
        
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }
        
        @media (min-width: 576px) {
            .modal-dialog-centered {
                min-height: calc(100% - 3.5rem);
            }
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h1 class="text-center mb-4">Sipariş Onayı</h1>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active" id="step-1">
                <span>Sepeti Görüntüle</span>
            </div>
            <div class="step" id="step-2">
                <span>Kargo Bilgileri</span>
            </div>
            <div class="step" id="step-3">
                <span>Ödeme</span>
            </div>
        </div>

        <!-- Step 1: Basket View -->
        <div class="step-content active" id="step-content-1">
            <div class="form-section">
                <h3>Sepet İçeriği</h3>
                <div id="basket-items">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Yükleniyor...</span>
                        </div>
                        <p class="mt-2">Sepet içeriği yükleniyor...</p>
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <label for="discount-code-step1" class="form-label">İndirim Kodu</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="discount-code-step1" placeholder="İndirim kodunuzu girin">
                        <button class="btn btn-outline-primary" type="button" id="apply-discount-btn-step1">Kodu Uygula</button>
                    </div>
                    <div id="discount-code-feedback-step1" class="form-text text-danger" style="display:none;"></div>
                </div>
                <div class="text-end">
                    <span id="old-total-step1" style="text-decoration:line-through; color:#888; display:none;"></span>
                    <span id="discounted-total-step1" style="font-size:1.2em; color:#28a745; font-weight:bold;"></span>
                </div>
                <div class="text-end mt-2">
                    <h5>Toplam: 
                        <span id="old-total-step1" style="text-decoration:line-through; color:#888; display:none;"></span>
                        <span id="discounted-total-step1" style="font-size:1.2em; color:#28a745; font-weight:bold;"></span>
                        <span id="basket-total">0.00 TL</span>
                    </h5>
                </div>
            </div>
            <div class="text-center mt-2">
                <button class="btn btn-primary btn-navigation" onclick="nextStep()">Devam Et</button>
            </div>
        </div>

        <!-- Step 2: Shipping Info -->
        <div class="step-content" id="step-content-2">
            <div class="form-section">
                <h3>Kargo Bilgileri</h3>
                <form id="shipping-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="full-name" class="form-label">Ad Soyad <span style="color:red">*</span></label>
                                <input type="text" class="form-control" id="full-name" required pattern="[A-Za-zÇçĞğİıÖöŞşÜü\s]+" oninput="this.value = this.value.replace(/[0-9]/g, '')">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefon <span style="color:red">*</span></label>
                                <input type="tel" class="form-control" id="phone" required pattern="[0-9]+">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Adres <span style="color:red">*</span></label>
                        <textarea class="form-control" id="address" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="city" class="form-label">Şehir <span style="color:red">*</span></label>
                                <input type="text" class="form-control" id="city" required pattern="[A-Za-zÇçĞğİıÖöŞşÜü\s]+">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="district" class="form-label">İlçe <span style="color:red">*</span></label>
                                <input type="text" class="form-control" id="district" required pattern="[A-Za-zÇçĞğİıÖöŞşÜü\s]+">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="postal-code" class="form-label">Posta Kodu <span style="color:red">*</span></label>
                                <input type="text" class="form-control" id="postal-code" required pattern="[0-9]+">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="mt-2 text-end"><span style="color:red">*</span> işaretli alanlar zorunludur.</div>
            </div>
            <div class="text-center">
                <button class="btn btn-secondary btn-navigation" onclick="prevStep()">Geri</button>
                <button class="btn btn-primary btn-navigation" onclick="nextStep()">Devam Et</button>
            </div>
        </div>

        <!-- Step 3: Payment -->
        <div class="step-content" id="step-content-3">
            <div class="form-section">
                <h3>Ödeme Bilgileri</h3>
                <div class="credit-card-form">
                    <div class="card-preview">
                        <div class="row">
                            <div class="col-8">
                                <h6>Kart Numarası</h6>
                                <p id="card-number-preview">**** **** **** ****</p>
                            </div>
                            <div class="col-4 text-end">
                                <h6>CVV</h6>
                                <p id="cvv-preview">***</p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <h6>Kart Sahibi</h6>
                                <p id="card-holder-preview">AD SOYAD</p>
                            </div>
                            <div class="col-6 text-end">
                                <h6>Son Kullanma</h6>
                                <p id="expiry-preview">MM/YY</p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-end">
                                <span id="old-total" style="text-decoration:line-through; color:#888; display:none;"></span>
                                <span id="discounted-total" style="font-size:1.2em; color:#28a745; font-weight:bold;"></span>
                            </div>
                        </div>
                    </div>

                    <form id="payment-form">
                        <div class="mb-3">
                            <label for="card-number" class="form-label">Kart Numarası <span style="color:red">*</span></label>
                            <input type="text" class="form-control" id="card-number"
                                   placeholder="1234 5678 9012 3456" maxlength="19" required>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="card-holder" class="form-label">Kart Sahibi <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" id="card-holder" required pattern="[A-Za-zÇçĞğİıÖöŞşÜü\s]+">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="expiry" class="form-label">Son Kullanma <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" id="expiry"
                                           placeholder="MM/YY" maxlength="5" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="cvv" class="form-label">CVV <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" id="cvv" placeholder="123" maxlength="3" required>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="mt-2 text-end"><span style="color:red">*</span> işaretli alanlar zorunludur.</div>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-secondary btn-navigation" onclick="prevStep()">Geri</button>
                                 <button class="btn btn-success btn-navigation btn-with-loader" id="completeOrderBtn" onclick="completeOrder()">
                     <span id="completeOrderText">Siparişi Tamamla</span>
                     <span id="completeOrderLoader" class="loader" style="display: none;"></span>
                 </button>
            </div>
        </div>
    </div>

    <!-- Success Popup -->
    <div class="overlay" id="overlay"></div>
    <div class="success-popup" id="successPopup">
        <h3 style="font-size:2em;">🎉 Siparişiniz Başarıyla Oluşturuldu!</h3>
        <p style="font-size:1.2em;">Siparişiniz alındı ve işleme alındı. Email adresinizi kontrol edin.</p>
        <button class="btn btn-primary" onclick="closeSuccessPopup()">Tamam</button>
    </div>

    <!-- Custom Popup -->
    <div class="overlay" id="customPopupOverlay" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);z-index:19999;"></div>
    <div class="custom-popup" id="customPopup" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:48px 36px 36px 36px;border-radius:20px;box-shadow:0 16px 64px rgba(0,0,0,0.25);z-index:20000;text-align:center;min-width:400px;min-height:120px;max-width:95vw;">
        <div id="customPopupMessage"></div>
        <button class="btn btn-primary mt-3" onclick="closeCustomPopup()">Tamam</button>
    </div>



    <script>
        let currentStep = 1;
        let basketData = null;

        // URL'den basketId yerine id parametresi al
        const urlParams = new URLSearchParams(window.location.search);
        const basketId = urlParams.get('id');

        // Sayfa yüklendiğinde basket verilerini al
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Checkout page loaded');
            console.log('Basket ID from URL:', basketId);

            if (basketId && basketId !== 'null' && basketId !== 'undefined') {
                console.log('Loading basket data...');
                loadBasketData(basketId);
            } else {
                console.error('No valid basket ID found in URL');
                alert('Basket ID bulunamadı! Ana sayfaya yönlendiriliyorsunuz.');
                setTimeout(() => {
                    window.location.href = '/';
                }, 2000);
            }




        });



        async function loadBasketData(basketId) {
            console.log('Loading basket data for ID:', basketId);
            try {
                const response = await fetch(`/api/v1/basket/${basketId}`);
                console.log('API response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('API response data:', data);

                if (data.success) {
                    basketData = data.basket;
                    console.log('Basket data loaded:', basketData);
                    displayBasketItems(basketData);
                } else {
                    console.error('API returned error:', data.message);
                    alert('Basket verileri yüklenemedi: ' + data.message);
                    window.location.href = '/';
                }
            } catch (error) {
                console.error('Basket yükleme hatası:', error);
                const container = document.getElementById('basket-items');
                if (container) {
                    container.innerHTML = '<div class="alert alert-danger">Sepet verileri yüklenemedi: ' + error.message + '</div>';
                }
            }
        }

        function displayBasketItems(basket) {
            console.log('Displaying basket items:', basket);

            const container = document.getElementById('basket-items');
            const totalElement = document.getElementById('basket-total');

            console.log('Container element:', container);
            console.log('Total element:', totalElement);

            try {
                // Check if basket has items
                if (!basket.items || !Array.isArray(basket.items)) {
                    console.error('Basket items is not an array:', basket.items);
                    container.innerHTML = '<div class="alert alert-warning">Sepet boş veya veri yüklenemedi.</div>';
                    return;
                }

                console.log('Basket items array:', basket.items);

                if (basket.items.length === 0) {
                    container.innerHTML = '<div class="alert alert-warning">Sepet boş.</div>';
                    return;
                }

                container.innerHTML = basket.items.map(item => `
                    <div class="cart-item">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>${item.product_name || 'Bilinmeyen Ürün'}</h6>
                                <p class="text-muted">Ürün ID: ${item.product_id || 'N/A'}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Adet: ${item.quantity || 0}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="fw-bold">${((item.quantity || 0) * (item.product_price || 50)).toFixed(2)} TL</p>
                            </div>
                        </div>
                    </div>
                `).join('');

                // Safely handle total price
                const totalPrice = parseFloat(basket.total_price) || 0;
                totalElement.textContent = totalPrice.toFixed(2) + ' TL';

                console.log('Basket items displayed successfully');
                console.log('Generated HTML:', container.innerHTML);

            } catch (error) {
                console.error('Error displaying basket items:', error);
                container.innerHTML = '<div class="alert alert-danger">Sepet verileri gösterilirken hata oluştu.</div>';
                totalElement.textContent = '0.00 TL';
            }
        }

        // Step 2 ve 3'te form validasyonu ve buton disable
        function updateShippingButtonState() {
            const form = document.getElementById('shipping-form');
            const btn = document.querySelector('#step-content-2 .btn-primary.btn-navigation');
            btn.disabled = !form.checkValidity();
        }

        function updatePaymentButtonState() {
            const form = document.getElementById('payment-form');
            const btn = document.querySelector('#step-content-3 .btn-success.btn-navigation');
            if (btn) {
                btn.disabled = !form.checkValidity();
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            const shippingForm = document.getElementById('shipping-form');
            if (shippingForm) {
                shippingForm.addEventListener('input', updateShippingButtonState);
                updateShippingButtonState();
            }
            
            const paymentForm = document.getElementById('payment-form');
            if (paymentForm) {
                paymentForm.addEventListener('input', updatePaymentButtonState);
                updatePaymentButtonState();
            }
            

        });

            // Form validasyonları
            setupFormValidations();

            // İndirim kodu butonu
            const discountBtn = document.getElementById('apply-discount-btn-step1');
            if (discountBtn) {
                discountBtn.addEventListener('click', async function() {
                    const code = document.getElementById('discount-code-step1').value.trim();
                    const feedback = document.getElementById('discount-code-feedback-step1');
                    const discountedTotal = document.getElementById('discounted-total-step1');
                    const oldTotal = document.getElementById('old-total-step1');
                    feedback.style.display = 'none';
                    discountedTotal.textContent = '';
                    oldTotal.style.display = 'none';
                    if (!code) {
                        feedback.textContent = 'Lütfen bir indirim kodu girin.';
                        feedback.style.display = 'block';
                        return;
                    }
                    // Sepet toplamını al
                    const total = basketData ? basketData.total_price : 0;
                    const email = basketData ? basketData.email : '';
                    try {
                        const response = await fetch('/api/v1/discount/validate', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ code, email, total, basket_id: basketId })
                        });
                        const result = await response.json();
                        if (result.success) {
                            oldTotal.textContent = (Number(total) || 0).toFixed(2) + ' TL';
                            oldTotal.style.display = 'inline';
                            discountedTotal.textContent = (Number(result.discounted_total) || 0).toFixed(2) + ' TL';
                            discountedTotal.style.display = 'inline';
                            document.getElementById('basket-total').style.display = 'none';
                            feedback.style.display = 'none';
                            // DB'de basket total_price güncelle
                            if (basketId) {
                                fetch(`/api/v1/basket/${basketId}/update-total`, {
                                    method: 'PATCH',
                                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                    body: JSON.stringify({ total_price: result.discounted_total })
                                });
                            }
                        } else {
                            feedback.textContent = result.message || 'İndirim kodu geçersiz.';
                            feedback.style.display = 'block';
                            discountedTotal.textContent = '';
                            discountedTotal.style.display = 'none';
                            oldTotal.style.display = 'none';
                            document.getElementById('basket-total').style.display = 'inline';
                        }
                    } catch (error) {
                        feedback.textContent = 'Sunucu hatası. Lütfen tekrar deneyin.';
                        feedback.style.display = 'block';
                        discountedTotal.textContent = '';
                        discountedTotal.style.display = 'none';
                        oldTotal.style.display = 'none';
                        document.getElementById('basket-total').style.display = 'inline';
                    }
                });
            }

        // Form validasyonları
        function setupFormValidations() {
            // Kargo bilgileri validasyonları
            const fullNameInput = document.getElementById('full-name');
            const phoneInput = document.getElementById('phone');
            const addressInput = document.getElementById('address');
            const cityInput = document.getElementById('city');
            const districtInput = document.getElementById('district');
            const postalCodeInput = document.getElementById('postal-code');

            // Kredi kartı validasyonları
            const cardNumberInput = document.getElementById('card-number');
            const cardHolderInput = document.getElementById('card-holder');
            const expiryInput = document.getElementById('expiry');
            const cvvInput = document.getElementById('cvv');

            // Ad Soyad validasyonu (iki kelime, arasında boşluk, sadece harf)
            fullNameInput.addEventListener('input', function() {
                // Sayıları kaldır
                this.value = this.value.replace(/[0-9]/g, '');
                
                const value = this.value.trim();
                const words = value.split(' ').filter(word => word.length > 0);
                if (value && words.length < 2) {
                    this.setCustomValidity('Ad ve soyad arasında boşluk bırakarak iki kelime giriniz');
                } else {
                    this.setCustomValidity('');
                }
                updateShippingButtonState();
            });

            // Telefon validasyonu (sadece rakam)
            phoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                updateShippingButtonState();
            });

            // Şehir ve İlçe validasyonu (sadece harf ve boşluk)
            [cityInput, districtInput].forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^A-Za-zÇçĞğİıÖöŞşÜü\s]/g, '');
                    updateShippingButtonState();
                });
            });

            // Posta kodu validasyonu (sadece rakam)
            postalCodeInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                updateShippingButtonState();
            });

            // Kredi kartı numarası validasyonu
            cardNumberInput.addEventListener('input', function() {
                let value = this.value.replace(/\s/g, '').replace(/[^0-9]/g, '');
                value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
                this.value = value;
                
                // 16 haneli kart numarası kontrolü
                const cleanValue = value.replace(/\s/g, '');
                if (cleanValue.length > 0 && cleanValue.length !== 16) {
                    this.setCustomValidity('Kart numarası 16 haneli olmalıdır');
                } else {
                    this.setCustomValidity('');
                }
                updatePaymentButtonState();
            });

            // Kart sahibi validasyonu (büyük harf, iki kelime)
            cardHolderInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
                const value = this.value.trim();
                const words = value.split(' ').filter(word => word.length > 0);
                if (value && words.length < 2) {
                    this.setCustomValidity('Ad ve soyad arasında boşluk bırakarak iki kelime giriniz');
                } else {
                    this.setCustomValidity('');
                }
                updatePaymentButtonState();
            });

            // Son kullanma tarihi validasyonu
            expiryInput.addEventListener('input', function() {
                let value = this.value.replace(/[^0-9]/g, '');
                if (value.length >= 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                this.value = value;
                
                // Slash'tan sonra 2 hane kontrolü - daha sıkı
                const parts = value.split('/');
                if (parts.length === 2 && parts[1].length !== 2) {
                    this.setCustomValidity('Son kullanma tarihi MM/YY formatında olmalıdır');
                } else if (parts.length === 1 && value.length === 2) {
                    this.setCustomValidity('Son kullanma tarihi MM/YY formatında olmalıdır');
                } else if (value.length > 0 && value.length < 5) {
                    this.setCustomValidity('Son kullanma tarihi MM/YY formatında olmalıdır');
                } else {
                    this.setCustomValidity('');
                }
                updatePaymentButtonState();
            });

            // CVV validasyonu (3 hane) - daha sıkı
            cvvInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 3) {
                    this.value = this.value.substring(0, 3);
                }
                if (this.value.length > 0 && this.value.length !== 3) {
                    this.setCustomValidity('CVV 3 haneli olmalıdır');
                } else {
                    this.setCustomValidity('');
                }
                updatePaymentButtonState();
            });
        }

        function nextStep() {
            if (currentStep === 1) {
                // Step 1 validation
                if (!basketData || basketData.items.length === 0) {
                    showCustomPopup('Sepet boş!');
                    return;
                }
            } else if (currentStep === 2) {
                // Step 2 validation
                const form = document.getElementById('shipping-form');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    showCustomPopup('Lütfen tüm zorunlu alanları doldurun.');
                    return;
                }
            }

            if (currentStep < 3) {
                currentStep++;
                updateStepDisplay();
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                updateStepDisplay();
            }
        }

        function updateStepDisplay() {
            // Step indicators
            document.querySelectorAll('.step').forEach((step, index) => {
                step.classList.remove('active', 'completed');
                if (index + 1 < currentStep) {
                    step.classList.add('completed');
                } else if (index + 1 === currentStep) {
                    step.classList.add('active');
                }
            });

            // Step content
            document.querySelectorAll('.step-content').forEach((content, index) => {
                content.classList.remove('active');
                if (index + 1 === currentStep) {
                    content.classList.add('active');
                }
            });
        }

        // Credit card preview - Bu kısım setupFormValidations içinde zaten var, burada sadece preview için
        document.addEventListener('DOMContentLoaded', function() {
            const cardNumberInput = document.getElementById('card-number');
            const cardHolderInput = document.getElementById('card-holder');
            const expiryInput = document.getElementById('expiry');
            const cvvInput = document.getElementById('cvv');

            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.replace(/(\d{4})/g, '$1 ').trim();
                    e.target.value = value;
                    document.getElementById('card-number-preview').textContent = value || '**** **** **** ****';
                });
            }

            if (cardHolderInput) {
                cardHolderInput.addEventListener('input', function(e) {
                    const value = e.target.value.toUpperCase();
                    document.getElementById('card-holder-preview').textContent = value || 'AD SOYAD';
                });
            }

            if (expiryInput) {
                expiryInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length >= 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    e.target.value = value;
                    document.getElementById('expiry-preview').textContent = value || 'MM/YY';
                });
            }

            if (cvvInput) {
                cvvInput.addEventListener('input', function(e) {
                    const value = e.target.value.replace(/\D/g, '');
                    e.target.value = value;
                    document.getElementById('cvv-preview').textContent = value || '***';
                });
            }
        });

        // İndirim kodu inputunu ödeme adımından kaldır
        // İndirim kodu inputu ve indirimli toplam success popup içinde yer alacak ve kod girildiğinde backend'e doğrulama isteği atacak.

        async function completeOrder() {
            // Show loader
            const btn = document.getElementById('completeOrderBtn');
            const text = document.getElementById('completeOrderText');
            const loader = document.getElementById('completeOrderLoader');
            
            btn.disabled = true;
            text.style.display = 'none';
            loader.style.display = 'inline-block';
            
            // Payment validation
            const form = document.getElementById('payment-form');
                            if (!form.checkValidity()) {
                    form.reportValidity();
                    showCustomPopup('Lütfen tüm zorunlu alanları doldurun.');
                    // Reset button
                    btn.disabled = false;
                    text.style.display = 'inline';
                    loader.style.display = 'none';
                    return;
                }
            // Shipping info
            const shippingInfo = {
                full_name: document.getElementById('full-name').value,
                phone: document.getElementById('phone').value,
                address: document.getElementById('address').value,
                city: document.getElementById('city').value,
                district: document.getElementById('district').value,
                postal_code: document.getElementById('postal-code').value
            };
            // Payment info
            const paymentInfo = {
                card_number: document.getElementById('card-number').value,
                card_holder: document.getElementById('card-holder').value,
                expiry: document.getElementById('expiry').value,
                cvv: document.getElementById('cvv').value
            };
            try {
                // Simüle edilmiş ödeme işlemi (API çağrısı)
                const response = await fetch(`/api/v1/basket/${basketId}/pay`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ shipping: shippingInfo, payment: paymentInfo })
                });
                const result = await response.json();
                if (result.success) {
                    showSuccessPopup();
                } else {
                    showCustomPopup(result.message || 'Ödeme başarısız. Lütfen tekrar deneyin.');
                    // Reset button
                    btn.disabled = false;
                    text.style.display = 'inline';
                    loader.style.display = 'none';
                }
            } catch (error) {
                console.error('Ödeme hatası:', error);
                showCustomPopup('Ödeme başarısız. Lütfen tekrar deneyin.');
                // Reset button
                btn.disabled = false;
                text.style.display = 'inline';
                loader.style.display = 'none';
            }
        }

        // Geri tuşunda kredi kartı formunu temizle
        document.querySelector('#step-content-3 .btn-secondary').addEventListener('click', function() {
            document.getElementById('payment-form').reset();
            document.getElementById('card-number-preview').textContent = '**** **** **** ****';
            document.getElementById('card-holder-preview').textContent = 'AD SOYAD';
            document.getElementById('expiry-preview').textContent = 'MM/YY';
            document.getElementById('cvv-preview').textContent = '***';
        });

        // Sepetten Çık butonu ekle
        function addExitButton() {
            document.querySelectorAll('.step-content').forEach((step) => {
                let exitBtn = step.querySelector('.exit-basket-btn');
                if (!exitBtn) {
                    exitBtn = document.createElement('button');
                    exitBtn.className = 'btn exit-basket-btn';
                    exitBtn.innerHTML = 'Sepetten Çık <span style="margin-left: 5px;">X</span>';
                    exitBtn.onclick = function() { window.location.href = '/'; };
                    step.style.position = 'relative';
                    step.appendChild(exitBtn);
                }
            });
        }
        addExitButton();

            function showSuccessPopup() {
                // Reset button before showing popup
                const btn = document.getElementById('completeOrderBtn');
                const text = document.getElementById('completeOrderText');
                const loader = document.getElementById('completeOrderLoader');
                
                btn.disabled = false;
                text.style.display = 'inline';
                loader.style.display = 'none';
                
                document.getElementById('overlay').classList.add('show');
                document.getElementById('successPopup').classList.add('show');
            }

            function closeSuccessPopup() {
                document.getElementById('overlay').classList.remove('show');
                document.getElementById('successPopup').classList.remove('show');
                window.location.href = '/';
            }

        // Success popup'ta indirim kodu kontrolü ve toplam güncelleme
        // document.getElementById('discount-code-popup').addEventListener('blur', async function() {
        //     const code = this.value.trim();
        //     const feedback = document.getElementById('discount-code-feedback-popup');
        //     const oldTotal = document.getElementById('old-total-popup');
        //     const discountedTotal = document.getElementById('discounted-total-popup');
        //     if (!basketData) return;
        //     if (!code) {
        //         feedback.style.display = 'none';
        //         oldTotal.style.display = 'none';
        //         discountedTotal.textContent = '';
        //         return;
        //     }
        //     try {
        //         const response = await fetch(`/api/v1/discount/validate`, {
        //             method: 'POST',
        //             headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        //             body: JSON.stringify({ code, email: basketData.email, total: basketData.total_price })
        //         });
        //         const result = await response.json();
        //         if (result.success) {
        //             feedback.style.display = 'none';
        //             oldTotal.style.display = 'inline';
        //             oldTotal.textContent = basketData.total_price.toFixed(2) + ' TL';
        //             discountedTotal.textContent = result.discounted_total.toFixed(2) + ' TL';
        //         } else {
        //             feedback.textContent = result.message || 'Kod geçersiz.';
        //             feedback.style.display = 'block';
        //             oldTotal.style.display = 'none';
        //             discountedTotal.textContent = '';
        //         }
        //     } catch (error) {
        //         feedback.textContent = 'Sunucu hatası.';
        //         feedback.style.display = 'block';
        //         oldTotal.style.display = 'none';
        //         discountedTotal.textContent = '';
        //     }
        // });

        // Step 1'de indirim kodu kontrolü ve toplam güncelleme
        // document.getElementById('apply-discount-btn-step1').addEventListener('click', async function() {
        //     const code = document.getElementById('discount-code-step1').value.trim();
        //     const feedback = document.getElementById('discount-code-feedback-step1');
        //     const discountedTotal = document.getElementById('discounted-total-step1');
        //     const oldTotal = document.getElementById('old-total-step1');
        //     feedback.style.display = 'none';
        //     discountedTotal.textContent = '';
        //     oldTotal.style.display = 'none';
        //     if (!code) {
        //         feedback.textContent = 'Lütfen bir indirim kodu girin.';
        //         feedback.style.display = 'block';
        //         return;
        //     }
        //     // Sepet toplamını al
        //     const total = basketData ? basketData.total_price : 0;
        //     const email = basketData ? basketData.email : '';
        //     try {
        //         const response = await fetch('/api/v1/discount/validate', {
        //             method: 'POST',
        //             headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        //             body: JSON.stringify({ code, email, total })
        //         });
        //         const result = await response.json();
        //         if (result.success) {
        //             oldTotal.textContent = total.toFixed(2) + ' TL';
        //             oldTotal.style.display = 'inline';
        //             discountedTotal.textContent = result.discounted_total.toFixed(2) + ' TL';
        //             feedback.style.display = 'none';
        //         } else {
        //             feedback.textContent = result.message || 'İndirim kodu geçersiz.';
        //             feedback.style.display = 'block';
        //             discountedTotal.textContent = '';
        //             oldTotal.style.display = 'none';
        //         }
        //     } catch (error) {
        //         feedback.textContent = 'Sunucu hatası. Lütfen tekrar deneyin.';
        //         feedback.style.display = 'block';
        //         discountedTotal.textContent = '';
        //         oldTotal.style.display = 'none';
        //     }
        // });

        // Custom popup fonksiyonları
        function showCustomPopup(message) {
            document.getElementById('customPopupMessage').innerHTML = message;
            document.getElementById('customPopup').style.display = 'block';
            document.getElementById('customPopupOverlay').style.display = 'block';
        }
        function closeCustomPopup() {
            document.getElementById('customPopup').style.display = 'none';
            document.getElementById('customPopupOverlay').style.display = 'none';
        }


    </script>
</body>
</html> 