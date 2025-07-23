<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Sipariş Onayı</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .credit-card-form { max-width: 400px; margin: 0 auto; }
        .card-preview {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white; padding: 20px; border-radius: 10px; margin: 20px 0;
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
    </style>
</head>
<body>
    <div class="checkout-container">
        <h1 class="text-center mb-4">Sipariş Onayı</h1>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active" id="step-1">
                <div class="step-number">1</div>
                <span>Sepeti Görüntüle</span>
            </div>
            <div class="step" id="step-2">
                <div class="step-number">2</div>
                <span>Kargo Bilgileri</span>
            </div>
            <div class="step" id="step-3">
                <div class="step-number">3</div>
                <span>Ödeme</span>
            </div>
        </div>

        <!-- Step 1: Basket View -->
        <div class="step-content active" id="step-content-1">
            <div class="form-section">
                <h3>Sepet İçeriği</h3>
                <div id="basket-items">
                    <!-- Basket items will be loaded here -->
                </div>
                <div class="text-end">
                    <h5>Toplam: <span id="basket-total">0.00 TL</span></h5>
                </div>
            </div>
            <div class="text-center">
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
                                <label for="full-name" class="form-label">Ad Soyad *</label>
                                <input type="text" class="form-control" id="full-name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefon *</label>
                                <input type="tel" class="form-control" id="phone" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Adres *</label>
                        <textarea class="form-control" id="address" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="city" class="form-label">Şehir *</label>
                                <input type="text" class="form-control" id="city" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="district" class="form-label">İlçe *</label>
                                <input type="text" class="form-control" id="district" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="postal-code" class="form-label">Posta Kodu</label>
                                <input type="text" class="form-control" id="postal-code">
                            </div>
                        </div>
                    </div>
                </form>
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
                    </div>

                    <form id="payment-form">
                        <div class="mb-3">
                            <label for="card-number" class="form-label">Kart Numarası *</label>
                            <input type="text" class="form-control" id="card-number"
                                   placeholder="1234 5678 9012 3456" maxlength="19" required>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="card-holder" class="form-label">Kart Sahibi *</label>
                                    <input type="text" class="form-control" id="card-holder"
                                           placeholder="AD SOYAD" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="expiry" class="form-label">Son Kullanma *</label>
                                    <input type="text" class="form-control" id="expiry"
                                           placeholder="MM/YY" maxlength="5" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="cvv" class="form-label">CVV *</label>
                                    <input type="text" class="form-control" id="cvv"
                                           placeholder="123" maxlength="3" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-secondary btn-navigation" onclick="prevStep()">Geri</button>
                <button class="btn btn-success btn-navigation" onclick="completeOrder()">Siparişi Tamamla</button>
            </div>
        </div>
    </div>

    <!-- Success Popup -->
    <div class="overlay" id="overlay"></div>
    <div class="success-popup" id="successPopup">
        <h3>🎉 Siparişiniz Başarıyla Oluşturuldu!</h3>
        <p>Siparişiniz alındı ve işleme alındı. Email adresinizi kontrol edin.</p>
        <button class="btn btn-primary" onclick="closeSuccessPopup()">Tamam</button>
    </div>

    <script>
        let currentStep = 1;
        let basketData = null;

        // URL'den basket ID'yi al
        const urlParams = new URLSearchParams(window.location.search);
        const basketId = urlParams.get('basket_id');

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

            // Email validation
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email-error');

            emailInput.addEventListener('blur', function() {
                const email = this.value;
                // Daha güçlü email regex - abc@abc gibi formatları kabul etmez
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                if (email && !emailRegex.test(email)) {
                    emailError.style.display = 'block';
                    this.classList.add('is-invalid');
                } else {
                    emailError.style.display = 'none';
                    this.classList.remove('is-invalid');
                }
            });

            emailInput.addEventListener('input', function() {
                if (emailError.style.display === 'block') {
                    const email = this.value;
                    // Daha güçlü email regex - abc@abc gibi formatları kabul etmez
                    const emailRegex = /^[a-zA-Z0-9._%+-]+@[^\s@]+\.[a-zA-Z]{2,}$/;

                    if (emailRegex.test(email)) {
                        emailError.style.display = 'none';
                        this.classList.remove('is-invalid');
                    }
                }
            });
        });

        async function loadBasketData(basketId) {
            console.log('Loading basket data for ID:', basketId);
            try {
                const response = await fetch(`/api/v1/basket/${basketId}`);
                console.log('API response status:', response.status);

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
                alert('Basket verileri yüklenemedi: ' + error.message);
                window.location.href = '/';
            }
        }

        function displayBasketItems(basket) {
            console.log('Displaying basket items:', basket);

            const container = document.getElementById('basket-items');
            const totalElement = document.getElementById('basket-total');

            try {
                // Check if basket has items
                if (!basket.items || !Array.isArray(basket.items)) {
                    throw new Error('Basket items is not an array');
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

            } catch (error) {
                console.error('Error displaying basket items:', error);
                container.innerHTML = '<div class="alert alert-danger">Sepet verileri gösterilirken hata oluştu.</div>';
                totalElement.textContent = '0.00 TL';
            }
        }

        function nextStep() {
            if (currentStep === 1) {
                // Step 1 validation
                if (!basketData || basketData.items.length === 0) {
                    alert('Sepet boş!');
                    return;
                }
            } else if (currentStep === 2) {
                // Step 2 validation
                const form = document.getElementById('shipping-form');
                if (!form.checkValidity()) {
                    form.reportValidity();
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

        // Credit card preview
        document.getElementById('card-number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})/g, '$1 ').trim();
            e.target.value = value;
            document.getElementById('card-number-preview').textContent = value || '**** **** **** ****';
        });

        document.getElementById('card-holder').addEventListener('input', function(e) {
            const value = e.target.value.toUpperCase();
            document.getElementById('card-holder-preview').textContent = value || 'AD SOYAD';
        });

        document.getElementById('expiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
            document.getElementById('expiry-preview').textContent = value || 'MM/YY';
        });

        document.getElementById('cvv').addEventListener('input', function(e) {
            const value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
            document.getElementById('cvv-preview').textContent = value || '***';
        });

        async function completeOrder() {
            // Payment validation
            const form = document.getElementById('payment-form');
            if (!form.checkValidity()) {
                form.reportValidity();
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
                    alert(result.message || 'Ödeme işlemi başarısız! Lütfen tekrar deneyin.');
                }
            } catch (error) {
                console.error('Ödeme hatası:', error);
                alert('Ödeme işlemi başarısız! Lütfen tekrar deneyin.');
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
                    exitBtn.className = 'btn btn-danger exit-basket-btn';
                    exitBtn.textContent = 'Sepetten Çık';
                    exitBtn.style.position = 'absolute';
                    exitBtn.style.top = '20px';
                    exitBtn.style.right = '20px';
                    exitBtn.onclick = function() { window.location.href = '/'; };
                    step.style.position = 'relative';
                    step.appendChild(exitBtn);
                }
            });
        }
        addExitButton();

            function showSuccessPopup() {
                document.getElementById('overlay').classList.add('show');
                document.getElementById('successPopup').classList.add('show');
            }

            function closeSuccessPopup() {
                document.getElementById('overlay').classList.remove('show');
                document.getElementById('successPopup').classList.remove('show');
                window.location.href = '/';
            }
    </script>
</body>
</html> 