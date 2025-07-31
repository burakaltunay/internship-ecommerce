<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TechShop - Teknolojinin Kalbi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --text-color: #333;
            --border-color: #e5e7eb;
            --shadow-light: 0 2px 10px rgba(0, 0, 0, 0.08);
            --shadow-medium: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-heavy: 0 8px 25px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #ffffff;
            min-height: 100vh;
            color: var(--text-color);
            overflow-x: hidden;
        }

        /* Header Styles */
        header {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 80px;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            border-bottom: 1px solid #f0f0f0;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary-color);
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo::before {
            content: "⚡";
            font-size: 32px;
            animation: pulse 2s ease-in-out infinite;
        }

        .logo span {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .cart-icon-wrapper { /* Yeni eklenen wrapper */
            position: relative;
            display: inline-block; /* Dropdown'ın konumlandırılması için */
        }

        .cart-icon {
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            padding: 12px;
            border-radius: 50%;
            transition: var(--transition);
            color: var(--primary-color);
        }

        .cart-icon:hover {
            background: rgba(37, 99, 235, 0.1);
        }

        .cart-icon svg {
            width: 24px;
            height: 24px;
        }

        .orders-icon {
            background: none;
            border: none;
            cursor: pointer;
            padding: 12px;
            border-radius: 50%;
            transition: var(--transition);
            color: var(--primary-color);
        }

        .orders-icon:hover {
            background: rgba(37, 99, 235, 0.1);
        }

        .orders-icon svg {
            width: 24px;
            height: 24px;
        }

        .cart-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #dc2626;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
        }

        /* Yeni Sepet Dropdown Stilleri */
        .cart-dropdown {
            position: fixed;
            top: 80px;
            right: 5vw;
            background: white;
            border-radius: 16px;
            width: 500px;
            max-width: 90vw;
            height: 55vh;
            min-height: 300px;
            max-height: 55vh;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-heavy);
            z-index: 1100;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
            border: 1px solid var(--border-color);
            margin-top: 0;
        }
        .cart-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-header {
            position: sticky;
            top: 0;
            background: white;
            z-index: 2;
            padding: 0 20px 0 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .dropdown-body {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 20px;
            min-height: 0;
        }
        .cart-total {
            position: sticky;
            bottom: 0;
            background: white;
            border-top: 2px solid var(--border-color);
            padding-top: 10px;
            margin-top: 0;
            text-align: center;
            z-index: 2;
        }

        .dropdown-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .close-dropdown {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6b7280;
            padding: 5px;
            border-radius: 50%;
            transition: var(--transition);
        }

        .close-dropdown:hover {
            background: #f3f4f6;
        }

        .dropdown-body {
            padding: 20px;
        }

        .empty-cart {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }

        .empty-cart svg {
            width: 48px;
            height: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f3f4f6;
            /* **Hizalama ve esneklik için yeni eklemeler** */
            width: 100%; /* Ensure it takes full width */
            box-sizing: border-box; /* Include padding in width calculation */
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-image {
            flex-shrink: 0; /* Resmin küçülmesini engelle */
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .cart-item-info {
            flex: 1; /* Mevcut alanı doldurmasını sağla */
            min-width: 0; /* Flexbox içinde metnin taşmasını engellemek için */
        }

        .cart-item-name {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 14px;
            white-space: normal; /* Metnin yeni satıra geçmesini sağla */
            overflow-wrap: break-word; /* Uzun kelimelerin kırılmasını sağla */
        }

        .cart-item-price {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 14px;
        }

        .cart-item-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0; /* Kontrollerin küçülmesini engelle */
        }

        .cart-quantity {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border-radius: 8px;
            padding: 4px 8px;
        }

        .cart-quantity button {
            background: var(--primary-color);
            border: none;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .cart-quantity button:hover {
            background: var(--primary-dark);
        }

        .cart-quantity input {
            width: 40px;
            text-align: center;
            border: none;
            background: transparent;
            font-size: 14px;
            font-weight: 600;
        }

        .remove-item {
            background: #ef4444;
            border: none;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .remove-item:hover {
            background: #dc2626;
        }

        .cart-total {
            border-top: 2px solid var(--border-color);
            padding-top: 20px;
            margin-top: 20px;
            text-align: center;
        }

        .total-price {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .cart-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: var(--transition);
            border: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 150px 20px 80px;
            background: var(--primary-color);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        /* Categories */
        .categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
        }

        .category {
            background: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition);
            border: 2px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .category:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.15);
            border-color: var(--primary-color);
        }

        .category.active {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.25);
            border-color: var(--primary-color);
        }

        .category h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        /* Product Container */
        .product-list-container {
            background: white;
            padding: 40px;
            margin: 40px auto;
            max-width: 1200px;
            border-radius: 12px;
            box-shadow: var(--shadow-medium);
            border: 1px solid var(--border-color);
        }

        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-controls {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .clear-filter {
            background: #6b7280;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: var(--transition);
        }

        .clear-filter:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        #categoryTitle {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
        }

        #itemsPerPage {
            padding: 10px 15px;
            font-size: 14px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            background: white;
            transition: var(--transition);
        }

        #itemsPerPage:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        /* Product Grid */
        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            padding-bottom: 20px;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: var(--shadow-light);
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            border: 1px solid #f3f4f6;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-heavy);
            border-color: var(--primary-color);
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: contain;
            margin-bottom: 20px;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .product-card:hover img {
            transform: scale(1.03);
        }

        .product-name {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 10px;
            text-align: center;
            color: var(--text-color);
            line-height: 1.4;
        }

        .product-price {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .counter {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #f8fafc;
            padding: 8px 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .counter button {
            background: var(--primary-color);
            border: none;
            color: white;
            padding: 8px 12px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .counter button:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }

        .counter input {
            width: 50px;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 8px;
            background: white;
            transition: border-color 0.3s ease;
        }

        .counter input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        /* Pagination */
        .pagination {
            margin-top: 40px;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .pagination button {
            background: white;
            border: 1px solid #d1d5db;
            color: #374151;
            padding: 12px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            min-width: 50px;
            transition: var(--transition);
        }

        .pagination button.active,
        .pagination button:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Status Messages */
        .loading, .error, .no-products {
            text-align: center;
            padding: 60px 20px;
            font-size: 18px;
            font-weight: 500;
            grid-column: 1 / -1;
        }

        .loading {
            color: var(--primary-color);
        }

        .error {
            color: #dc2626;
        }

        .no-products {
            color: #6b7280;
        }

        /* Success Message */
        .success-message {
            position: fixed;
            top: 100px;
            right: 20px;
            background: #10b981;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: var(--shadow-medium);
            z-index: 1500;
            transform: translateX(100%);
            transition: var(--transition);
        }

        .success-message.show {
            transform: translateX(0);
        }

        /* Animations */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Auth Button Styles (Giriş Yap / Üye Ol) */
        .auth-btn {
            background-color: var(--primary-color); /* Mavi renk */
            color: white;
            padding: 12px 24px; /* Daha büyük padding */
            font-size: 16px; /* Daha büyük font boyutu */
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .auth-btn:hover {
            background-color: var(--primary-dark); /* Hover'da daha koyu mavi */
            transform: translateY(-1px);
        }


        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 36px;
            }

            .hero p {
                font-size: 16px;
            }

            .categories {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 15px;
                padding: 40px 15px;
            }

            .product-list-container {
                margin: 20px 15px;
                padding: 25px;
            }

            .controls {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-controls {
                justify-content: center;
            }

            .pagination {
                gap: 5px;
            }

            .pagination button {
                padding: 10px 14px;
                min-width: 40px;
            }

            /* Modal kaldırıldığı için dropdown responsive ayarı */
            .cart-dropdown {
                width: 90%;
                left: 5%; /* Ortalamak için */
                right: 5%;
                max-width: 400px; /* Sabit maksimum genişlik */
            }

            .cart-actions {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .hero {
                padding: 120px 15px 60px;
            }

            .hero h1 {
                font-size: 28px;
            }

            .logo {
                font-size: 24px;
            }

            .auth-btn {
                padding: 10px 20px;
                font-size: 14px; /* Adjusted for mobile */
            }

            .cart-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .cart-item-controls {
                align-self: stretch;
                justify-content: space-between;
            }
        }
    </style>
    <style>
        /* ... (diğer stil kurallarınız) ... */

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body>
<header>
    <div class="logo" onclick="window.location.href='/'">Tech<span>Shop</span></div>
    <div class="header-actions">
        <div class="cart-icon-wrapper">
            <button class="cart-icon" id="cartIcon" onclick="cartManager.toggleCartDropdown()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" width="32" height="32">
                    <circle cx="9" cy="21" r="1"/>
                    <circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                <span class="cart-badge" id="cartBadge">0</span>
            </button>
            <div class="cart-dropdown" id="cartDropdown">
                <div class="dropdown-header">
                    <h2 class="dropdown-title">Sepetim</h2>
                    <button class="close-dropdown" onclick="cartManager.toggleCartDropdown()">×</button>
                </div>
                <div class="dropdown-body" id="cartDropdownBody">
                    <div class="empty-cart">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"></path>
                        </svg>
                        <p>Sepetiniz boş</p>
                    </div>
                </div>
            </div>
        </div>
        <button class="orders-icon" onclick="window.location.href='{{ route('login') }}'" title="Siparişlerim (Giriş Yapın)">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" width="32" height="32">
                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </button>
        <button class="auth-btn" onclick="window.location.href='{{ route('login') }}'">Giriş Yap / Üye Ol</button>
    </div>
</header>

<section class="hero">
    <h1>Teknolojinin Kalbi</h1>
    <p>Geleceğin teknolojisiyle bugün tanışın</p>
</section>

<div style="background: #fef3c7; color: #92400e; text-align: center; padding: 16px 0; font-size: 18px; font-weight: 600; border-bottom: 2px solid #fde68a;">
    🎁 Her 5. siparişte bir indirim kodu kazanırsınız! Kazandığınız kodu sipariş onayı sayfasında kullanarak %20 indirim elde edebilirsiniz.
</div>

<section class="categories" id="categories">
</section>

<section class="product-list-container" id="products">
    <div class="controls">
        <div class="filter-controls">
            <button class="clear-filter" id="clearFilter" style="display: none;">Tüm Ürünler</button>
            <span id="categoryTitle"></span>
        </div>
        <div>
            <label for="itemsPerPage">Sayfa başına ürün: </label>
            <select id="itemsPerPage">
                <option value="4">4</option>
                <option value="8" selected>8</option>
                <option value="12">12</option>
            </select>
        </div>
    </div>

    <div class="product-list" id="product-list">
        <div class="loading">Ürünler yükleniyor...</div>
    </div>

    <div class="pagination" id="pagination"></div>
</section>



<script>
    // Cart Management Class
    class CartManager {
        constructor() {
            this.cart = new Map();
            this.products = new Map();
            this.isDropdownOpen = false; // Modal yerine dropdown durumu
            this.initEventListeners();
        }
        updateCartItemAndSync(productId, quantity) {
            if (quantity <= 0) {
                this.removeFromCartAndSync(productId);
                return;
            }
            this.cart.set(productId, quantity);
            this.updateBadge();
            this.renderCartDropdown(); // Modal yerine dropdown'ı render et
            this.updateProductQuantityDisplay(productId);
        }

        removeFromCartAndSync(productId) {
            const input = document.querySelector(`.quantity-input[data-id="${productId}"]`);
            if (input) {
                input.value = 0;
            }
            this.cart.delete(productId);
            this.updateBadge();
            this.renderCartDropdown(); // Modal yerine dropdown'ı render et
        }
        updateProductQuantityDisplay(productId) {
            const input = document.querySelector(`.quantity-input[data-id="${productId}"]`);
            if (input) {
                const cartQuantity = this.cart.get(productId) || 0;
                input.value = cartQuantity;
            }
        }
        initEventListeners() {
            // Dropdown dış tıklama
            document.addEventListener('click', (e) => {
                const cartIconWrapper = document.querySelector('.cart-icon-wrapper');
                const cartDropdown = document.getElementById('cartDropdown');
                if (this.isDropdownOpen && !cartIconWrapper.contains(e.target)) {
                    this.toggleCartDropdown(false); // Dropdown'ı kapat
                }
            });

            // ESC tuşu ile dropdown kapatma
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isDropdownOpen) {
                    this.toggleCartDropdown(false); // Dropdown'ı kapat
                }
            });
        }

        addProduct(productId, product) {
            this.products.set(productId, product);
        }

        addToCart(productId, quantity = 1) {
            if (quantity <= 0) return;

            const currentQuantity = this.cart.get(productId) || 0;
            this.cart.set(productId, currentQuantity + quantity);
            this.updateBadge();

            this.renderCartDropdown(); // Ürün eklendiğinde dropdown'ı güncelle
        }

        updateCartItem(productId, quantity) {
            if (quantity <= 0) {
                this.removeFromCart(productId);
                return;
            }
            this.cart.set(productId, quantity);
            this.updateBadge();
            this.renderCartDropdown(); // Modal yerine dropdown'ı render et
            this.updateProductQuantityDisplay(productId);
        }

        removeFromCart(productId) {
            this.cart.delete(productId);
            this.updateBadge();
            this.renderCartDropdown(); // Modal yerine dropdown'ı render et
            this.updateProductQuantityDisplay(productId);
        }

        clearCart() {
            // Tüm ürünlerin sayacını sıfırla
            for (const [productId, quantity] of this.cart) {
                const input = document.querySelector(`.quantity-input[data-id="${productId}"]`);
                if (input) {
                    input.value = 0;
                }
            }

            this.cart.clear();
            this.updateBadge();
            this.renderCartDropdown(); // Modal yerine dropdown'ı render et
        }

        getTotal() {
            let total = 0;
            for (const [productId, quantity] of this.cart) {
                const product = this.products.get(productId);
                if (product) {
                    total += parseFloat(product.price) * quantity;
                }
            }
            return total;
        }

        getTotalItems() {
            // Sepetteki benzersiz ürün çeşidi sayısını döndürür
            return this.cart.size;
        }

        updateBadge() {
            const badge = document.getElementById('cartBadge');
            const totalItems = this.getTotalItems();
            badge.textContent = totalItems;
            badge.style.display = totalItems > 0 ? 'flex' : 'none';
        }

        // Modal yerine Dropdown açma/kapama fonksiyonu
        toggleCartDropdown(forceState = null) {
            const dropdown = document.getElementById('cartDropdown');
            this.isDropdownOpen = forceState !== null ? forceState : !this.isDropdownOpen;

            if (this.isDropdownOpen) {
                dropdown.classList.add('active');
                this.renderCartDropdown(); // Dropdown açıldığında içeriği render et
            } else {
                dropdown.classList.remove('active');
            }
        }

        // Sepet içeriğini dropdown'a render eden fonksiyon
        renderCartDropdown() {
            const dropdownBody = document.getElementById('cartDropdownBody');

            if (this.cart.size === 0) {
                dropdownBody.innerHTML = `
            <div class="empty-cart">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"></path>
                </svg>
                <p>Sepetiniz boş</p>
            </div>
        `;
                return;
            }

            let cartHTML = '';
            for (const [productId, quantity] of this.cart) {
                const product = this.products.get(productId);
                if (product) {
                    const itemTotal = parseFloat(product.price) * quantity;
                    cartHTML += `
                <div class="cart-item">
                    <img src="${product.img}" alt="${product.name}" class="cart-item-image"
                         onerror="this.src='https://via.placeholder.com/60x60?text=Resim+Yok'" />
                    <div class="cart-item-info">
                        <div class="cart-item-name">${product.name}</div>
                        <div class="cart-item-price">${itemTotal.toLocaleString('tr-TR', {style:'currency', currency:'TRY'})}</div>
                    </div>
                    <div class="cart-item-controls">
                        <div class="cart-quantity">
                            <button onclick="cartManager.updateCartItemAndSync(${productId}, ${quantity - 1})">−</button>
                            <input type="number" value="${quantity}"
                                   onchange="cartManager.updateCartItemAndSync(${productId}, parseInt(this.value) || 0)"
                                   min="0" />
                            <button onclick="cartManager.updateCartItemAndSync(${productId}, ${quantity + 1})">+</button>
                        </div>
                        <button class="remove-item" onclick="cartManager.removeFromCartAndSync(${productId})">×</button>
                    </div>
                </div>
            `;
                }
            }

            const total = this.getTotal();
            cartHTML += `
        <div class="cart-total">
            <div class="total-price">
                Toplam: ${total.toLocaleString('tr-TR', {style:'currency', currency:'TRY'})}
            </div>
            <div class="cart-actions">
                <button class="btn btn-secondary" onclick="cartManager.clearCart()">Sepeti Temizle</button>
                <button class="btn btn-primary" onclick="cartManager.confirmOrder()">Sipariş Ver</button>
            </div>
        </div>
    `;

            dropdownBody.innerHTML = cartHTML;
        }

        confirmOrder() {
            if (this.cart.size === 0) return;

            const total = this.getTotal();
            const itemCount = this.getTotalItems();

            // Modal'ı göster
            this.showOrderConfirmModal(itemCount, total);
        }

        showOrderConfirmModal(itemCount, total) {
            // Modal HTML'ini oluştur
            const modalHTML = `
                <div class="modal-overlay" id="orderConfirmModal" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 10000;
                ">
                    <div class="modal-content" style="
                        background: white;
                        padding: 30px;
                        border-radius: 15px;
                        max-width: 500px;
                        width: 90%;
                        text-align: center;
                        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                    ">
                        <h3 style="margin-bottom: 20px; color: #333;">Sipariş Onayı</h3>
                        <p style="margin-bottom: 25px; font-size: 16px; line-height: 1.5;">
                            ${itemCount} çeşit ürün, toplam <strong>${total.toLocaleString('tr-TR', {style:'currency', currency:'TRY'})}</strong> tutarındaki siparişi onaylıyor musunuz?
                        </p>
                        <div style="display: flex; gap: 15px; justify-content: center;">
                            <button id="cancelOrderBtn" style="
                                padding: 12px 24px;
                                border: 1px solid #ddd;
                                background: white;
                                border-radius: 8px;
                                cursor: pointer;
                                font-size: 14px;
                                transition: all 0.3s ease;
                            ">Vazgeç</button>
                            <button id="confirmOrderBtn" style="
                                padding: 12px 24px;
                                background: #2563eb;
                                color: white;
                                border: none;
                                border-radius: 8px;
                                cursor: pointer;
                                font-size: 14px;
                                transition: all 0.3s ease;
                            ">Onayla</button>
                        </div>
                    </div>
                </div>
            `;

            // Modal'ı sayfaya ekle
            document.body.insertAdjacentHTML('beforeend', modalHTML);

            // Event listener'ları ekle
            document.getElementById('cancelOrderBtn').addEventListener('click', () => {
                this.closeOrderConfirmModal();
            });

            document.getElementById('confirmOrderBtn').addEventListener('click', () => {
                this.closeOrderConfirmModal();
                this.processOrder();
            });

            // Modal dışına tıklayınca kapat
            document.getElementById('orderConfirmModal').addEventListener('click', (e) => {
                if (e.target.id === 'orderConfirmModal') {
                    this.closeOrderConfirmModal();
                }
            });
        }

        closeOrderConfirmModal() {
            const modal = document.getElementById('orderConfirmModal');
            if (modal) {
                modal.remove();
            }
        }

        processOrder() {
            // Burada gerçek bir API çağrısı yapılabilir
            alert('Siparişiniz başarıyla alındı! Teşekkür ederiz.');
            this.clearCart();
            this.toggleCartDropdown(false); // Sipariş sonrası dropdown'ı kapat
        }


    }

    // App State Management
    class TechShopApp {
        constructor() {
            this.currentPage = 1;
            this.productsPerPage = 8;
            this.selectedCategoryId = null;
            this.categories = [];
            this.totalPages = 1;
            this.isLoading = false;
            this.API_BASE = '/api/v1';

            this.initElements();
            this.initEventListeners();
        }

        initElements() {
            this.productListEl = document.getElementById('product-list');
            this.paginationEl = document.getElementById('pagination');
            this.categoriesEl = document.getElementById('categories');
            this.itemsPerPageEl = document.getElementById('itemsPerPage');
            this.clearFilterBtn = document.getElementById('clearFilter');
            this.categoryTitleEl = document.getElementById('categoryTitle');
        }

        initEventListeners() {
            this.itemsPerPageEl.addEventListener('change', () => {
                this.productsPerPage = parseInt(this.itemsPerPageEl.value);
                this.currentPage = 1;
                this.loadProducts();
            });

            this.clearFilterBtn.addEventListener('click', () => {
                this.selectedCategoryId = null;
                this.currentPage = 1;
                this.loadProducts();
                this.updateCategoryDisplay();
            });

            // Header scroll effect
            window.addEventListener('scroll', () => {
                const header = document.querySelector('header');
                if (window.scrollY > 50) {
                    header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.12)';
                } else {
                    header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.08)';
                }
            });
        }

        async loadCategories() {
            try {
                const response = await fetch(`${this.API_BASE}/products/categories`);
                const data = await response.json();

                let apiCategories = data.data || [];

                // Beyaz Eşyalar kategorisini ekle (eğer yoksa)
                const beyazEsyalarExists = apiCategories.some(cat => cat.name === 'Beyaz Eşyalar');
                if (!beyazEsyalarExists) {
                    const maxId = apiCategories.length > 0 ? Math.max(...apiCategories.map(cat => cat.id)) : 0;
                    apiCategories.push({ id: maxId + 1, name: 'Beyaz Eşyalar' });
                }

                this.categories = apiCategories;
                this.renderCategories();
            } catch (error) {
                console.error('Kategoriler yüklenirken hata:', error);
                // Hata durumunda varsayılan kategoriler
                this.categories = [
                    { id: 1, name: 'Telefonlar' },
                    { id: 2, name: 'Bilgisayarlar' },
                    { id: 3, name: 'Tabletler' },
                    { id: 4, name: 'Aksesuarlar' },
                    { id: 5, name: 'Beyaz Eşyalar' }
                ];
                this.renderCategories();
            }
        }

        renderCategories() {
            this.categoriesEl.innerHTML = '';

            this.categories.forEach(category => {
                const categoryEl = document.createElement('div');
                categoryEl.className = 'category';
                categoryEl.innerHTML = `<h3>${category.name}</h3>`;
                categoryEl.addEventListener('click', () => {
                    this.selectedCategoryId = category.id;
                    this.currentPage = 1;
                    this.loadProducts();
                    this.updateCategoryDisplay();
                });
                this.categoriesEl.appendChild(categoryEl);
            });
        }

        updateCategoryDisplay() {
            const categoryButtons = document.querySelectorAll('.category');
            categoryButtons.forEach(btn => btn.classList.remove('active'));

            if (this.selectedCategoryId) {
                const selectedCategory = this.categories.find(cat => cat.id === this.selectedCategoryId);
                if (selectedCategory) {
                    this.categoryTitleEl.textContent = `Kategori: ${selectedCategory.name}`;
                    this.clearFilterBtn.style.display = 'inline-block';

                    categoryButtons.forEach((btn, index) => {
                        if (this.categories[index].id === this.selectedCategoryId) {
                            btn.classList.add('active');
                        }
                    });
                }
            } else {
                this.categoryTitleEl.textContent = '';
                this.clearFilterBtn.style.display = 'none';
            }
        }

        async loadProducts() {
            if (this.isLoading) return;

            try {
                this.isLoading = true;
                this.productListEl.innerHTML = '<div class="loading">Ürünler yükleniyor...</div>';
                this.paginationEl.style.display = 'none';

                const params = new URLSearchParams({
                    page: this.currentPage,
                    per_page: this.productsPerPage
                });

                if (this.selectedCategoryId) {
                    params.append('category_id', this.selectedCategoryId);
                }

                const response = await fetch(`${this.API_BASE}/products?${params}`);
                const data = await response.json();

                if (data.data && data.data.length > 0) {
                    this.renderProducts(data.data);
                    this.totalPages = data.last_page;
                    this.renderPagination(data);
                } else {
                    this.showNoProducts();
                }

            } catch (error) {
                console.error('Ürünler yüklenirken hata:', error);
                this.productListEl.innerHTML = '<div class="error">Ürünler yüklenirken bir hata oluştu.</div>';
                this.paginationEl.style.display = 'none';
            } finally {
                this.isLoading = false;
            }
        }

        renderProducts(products) {
            this.productListEl.innerHTML = '';

            products.forEach(product => {
                // Ürünü cart manager'a ekle
                cartManager.addProduct(product.id, product);

                const card = document.createElement('div');
                card.className = 'product-card';

                // Sepetteki mevcut miktarı al
                const cartQuantity = cartManager.cart.get(product.id) || 0;

                card.innerHTML = `
            <img src="${product.img}" alt="${product.name}"
                 onerror="this.src='https://via.placeholder.com/180x180?text=Resim+Yok'" />
            <div class="product-name">${product.name}</div>
            <div class="product-price">${parseFloat(product.price).toLocaleString('tr-TR', {style:'currency', currency:'TRY'})}</div>
            <div class="counter">
                <button data-id="${product.id}" class="decrement">−</button>
                <input type="number" min="0" value="${cartQuantity}" data-id="${product.id}" class="quantity-input" />
                <button data-id="${product.id}" class="increment">+</button>
            </div>
        `;

                this.productListEl.appendChild(card);
            });

            this.setupProductEvents();
        }
        setupProductEvents() {
            // Decrement buttons
            document.querySelectorAll('.decrement').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const id = parseInt(btn.dataset.id);
                    const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
                    let val = parseInt(input.value) || 0;
                    if (val > 0) {
                        const newVal = val - 1;
                        input.value = newVal;
                        // Sepeti güncelle
                        if (newVal > 0) {
                            cartManager.updateCartItem(id, newVal);
                        } else {
                            cartManager.removeFromCart(id);
                        }
                    }
                });
            });

            // Increment buttons
            document.querySelectorAll('.increment').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const id = parseInt(btn.dataset.id);
                    const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
                    let val = parseInt(input.value) || 0;
                    const newVal = val + 1;
                    input.value = newVal;

                    // Sepeti güncelle
                    cartManager.updateCartItem(id, newVal);
                });
            });

            // Quantity inputs
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('input', () => {
                    let val = parseInt(input.value);
                    if (isNaN(val) || val < 0) {
                        input.value = 0;
                    }
                });

                input.addEventListener('change', () => {
                    const id = parseInt(input.dataset.id);
                    const quantity = parseInt(input.value) || 0;

                    if (quantity > 0) {
                        cartManager.updateCartItem(id, quantity);
                    } else {
                        cartManager.removeFromCart(id);
                    }
                });
            });
        }

        showNoProducts() {
            this.productListEl.innerHTML = '<div class="no-products">Bu kategoride ürün bulunamadı.</div>';
            this.paginationEl.style.display = 'none';
        }

        renderPagination(data) {
            this.paginationEl.innerHTML = '';

            if (data.last_page <= 1) {
                this.paginationEl.style.display = 'none';
                return;
            }

            this.paginationEl.style.display = 'flex';

            const maxVisible = 5;
            let startPage = Math.max(1, data.current_page - Math.floor(maxVisible / 2));
            let endPage = startPage + maxVisible - 1;

            if (endPage > data.last_page) {
                endPage = data.last_page;
                startPage = Math.max(1, endPage - maxVisible + 1);
            }

            // Previous button
            if (data.current_page > 1) {
                const prevBtn = document.createElement('button');
                prevBtn.textContent = '← Önceki';
                prevBtn.addEventListener('click', () => {
                    this.currentPage = data.current_page - 1;
                    this.loadProducts();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
                this.paginationEl.appendChild(prevBtn);
            }

            // Page numbers
            for (let i = startPage; i <= endPage; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                if (i === data.current_page) btn.classList.add('active');
                btn.addEventListener('click', () => {
                    this.currentPage = i;
                    this.loadProducts();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
                this.paginationEl.appendChild(btn);
            }

            // Next button
            if (data.current_page < data.last_page) {
                const nextBtn = document.createElement('button');
                nextBtn.textContent = 'Sonraki →';
                nextBtn.addEventListener('click', () => {
                    this.currentPage = data.current_page + 1;
                    this.loadProducts();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
                this.paginationEl.appendChild(nextBtn);
            }
        }
    }

    // Global instances
    const cartManager = new CartManager();
    const app = new TechShopApp();

    // Global functions for HTML onclick events
    function toggleCart() {
        cartManager.toggleModal();
    }

    function closeCartIfOutside(event) {
        if (event.target === event.currentTarget) {
            cartManager.toggleModal();
        }
    }

    // Initialize app
    document.addEventListener('DOMContentLoaded', () => {
        app.loadCategories();
        app.loadProducts();
    });
</script>
</body>
</html>
