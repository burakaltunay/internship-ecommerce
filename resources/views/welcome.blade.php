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

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #ffffff;
            min-height: 100vh;
            color: #333;
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: #2563eb;
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
            background: linear-gradient(45deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .auth-btn:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 150px 20px 80px;
            background: #2563eb;
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
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
            transition: all 0.3s ease;
            border: 2px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .category:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.15);
            border-color: #2563eb;
        }

        .category.active {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.25);
            border-color: #2563eb;
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
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
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
            transition: all 0.3s ease;
        }

        .clear-filter:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        #categoryTitle {
            font-size: 18px;
            font-weight: 600;
            color: #2563eb;
        }

        #itemsPerPage {
            padding: 10px 15px;
            font-size: 14px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            background: white;
            transition: all 0.3s ease;
        }

        #itemsPerPage:focus {
            outline: none;
            border-color: #2563eb;
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid #f3f4f6;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            border-color: #2563eb;
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
            color: #333;
            line-height: 1.4;
        }

        .product-price {
            color: #2563eb;
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
            background: #2563eb;
            border: none;
            color: white;
            padding: 8px 12px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .counter button:hover {
            background: #1d4ed8;
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
            border-color: #2563eb;
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
            transition: all 0.3s ease;
        }

        .pagination button.active,
        .pagination button:hover {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
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
            color: #2563eb;
        }

        .error {
            color: #dc2626;
        }

        .no-products {
            color: #6b7280;
        }

        /* Animations */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
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
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="logo" onclick="window.location.href='/'">Tech<span>Shop</span></div>
    <button class="auth-btn" onclick="window.location.href='{{ route('web.login.form') }}'">Giriş Yap / Üye Ol</button>
</header>

<section class="hero">
    <h1>Teknolojinin Kalbi</h1>
    <p>Geleceğin teknolojisiyle bugün tanışın</p>
</section>

<section class="categories" id="categories">
    <!-- Kategoriler JS ile gelecek -->
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
    // Global değişkenler
    let currentPage = 1;
    let productsPerPage = 8;
    let selectedCategoryId = null;
    let categories = [];
    let totalPages = 1;

    // CSRF token'ı al
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // API Base URL
    const API_BASE = '/api/v1';

    // DOM elementleri
    const productListEl = document.getElementById('product-list');
    const paginationEl = document.getElementById('pagination');
    const categoriesEl = document.getElementById('categories');
    const itemsPerPageEl = document.getElementById('itemsPerPage');
    const clearFilterBtn = document.getElementById('clearFilter');
    const categoryTitleEl = document.getElementById('categoryTitle');

    // Event listeners
    itemsPerPageEl.addEventListener('change', () => {
        productsPerPage = parseInt(itemsPerPageEl.value);
        currentPage = 1;
        loadProducts();
    });

    clearFilterBtn.addEventListener('click', () => {
        selectedCategoryId = null;
        currentPage = 1;
        loadProducts();
        updateCategoryDisplay();
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

    // Kategorileri yükle
    async function loadCategories() {
        try {
            const response = await fetch(`${API_BASE}/products/categories`);
            const data = await response.json();
            categories = data.data;
            renderCategories();
        } catch (error) {
            console.error('Kategoriler yüklenirken hata:', error);
        }
    }

    // Kategorileri render et
    function renderCategories() {
        categoriesEl.innerHTML = '';

        categories.forEach(category => {
            const categoryEl = document.createElement('div');
            categoryEl.className = 'category';
            categoryEl.innerHTML = `<h3>${category.name}</h3>`;
            categoryEl.addEventListener('click', () => {
                selectedCategoryId = category.id;
                currentPage = 1;
                loadProducts();
                updateCategoryDisplay();
            });
            categoriesEl.appendChild(categoryEl);
        });
    }

    // Kategori görünümünü güncelle
    function updateCategoryDisplay() {
        // Kategori butonlarını güncelle
        const categoryButtons = document.querySelectorAll('.category');
        categoryButtons.forEach(btn => btn.classList.remove('active'));

        if (selectedCategoryId) {
            const selectedCategory = categories.find(cat => cat.id === selectedCategoryId);
            if (selectedCategory) {
                categoryTitleEl.textContent = `Kategori: ${selectedCategory.name}`;
                clearFilterBtn.style.display = 'inline-block';

                // Aktif kategoriyi işaretle
                categoryButtons.forEach((btn, index) => {
                    if (categories[index].id === selectedCategoryId) {
                        btn.classList.add('active');
                    }
                });
            }
        } else {
            categoryTitleEl.textContent = '';
            clearFilterBtn.style.display = 'none';
        }
    }

    // Ürünleri yükle
    async function loadProducts() {
        try {
            productListEl.innerHTML = '<div class="loading">Ürünler yükleniyor...</div>';

            const params = new URLSearchParams({
                page: currentPage,
                per_page: productsPerPage
            });

            if (selectedCategoryId) {
                params.append('category_id', selectedCategoryId);
            }

            const response = await fetch(`${API_BASE}/products?${params}`);
            const data = await response.json();

            if (data.data && data.data.length > 0) {
                renderProducts(data.data);
                totalPages = data.last_page;
                renderPagination(data);
            } else {
                showNoProducts();
            }

        } catch (error) {
            console.error('Ürünler yüklenirken hata:', error);
            productListEl.innerHTML = '<div class="error">Ürünler yüklenirken bir hata oluştu.</div>';
        }
    }

    // Ürünleri render et
    function renderProducts(products) {
        productListEl.innerHTML = '';

        products.forEach(product => {
            const card = document.createElement('div');
            card.className = 'product-card';

            card.innerHTML = `
                <img src="${product.img}" alt="${product.name}" onerror="this.src='https://via.placeholder.com/180x180?text=Resim+Yok'" />
                <div class="product-name">${product.name}</div>
                <div class="product-price">${parseFloat(product.price).toLocaleString('tr-TR', {style:'currency', currency:'TRY'})}</div>
                <div class="counter">
                    <button aria-label="Azalt" data-id="${product.id}" class="decrement">−</button>
                    <input type="number" min="0" value="0" data-id="${product.id}" class="quantity-input" />
                    <button aria-label="Arttır" data-id="${product.id}" class="increment">+</button>
                </div>
            `;

            productListEl.appendChild(card);
        });

        // Counter event listeners
        setupCounterEvents();
    }

    // Counter event listeners
    function setupCounterEvents() {
        document.querySelectorAll('.decrement').forEach(btn => {
            btn.onclick = (e) => {
                e.preventDefault();
                const id = btn.dataset.id;
                const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
                let val = parseInt(input.value) || 0;
                if (val > 0) {
                    input.value = val - 1;
                }
            }
        });

        document.querySelectorAll('.increment').forEach(btn => {
            btn.onclick = (e) => {
                e.preventDefault();
                const id = btn.dataset.id;
                const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
                let val = parseInt(input.value) || 0;
                input.value = val + 1;
            }
        });

        document.querySelectorAll('.quantity-input').forEach(input => {
            input.oninput = () => {
                let val = parseInt(input.value);
                if (isNaN(val) || val < 0) {
                    input.value = 0;
                }
            }
        });
    }

    // Ürün bulunamadı mesajı
    function showNoProducts() {
        productListEl.innerHTML = '<div class="no-products">Bu kategoride ürün bulunamadı.</div>';
        paginationEl.innerHTML = '';
    }

    // Pagination render
    function renderPagination(data) {
        paginationEl.innerHTML = '';

        if (data.last_page <= 1) return;

        const maxVisible = 5;
        let startPage = Math.max(1, data.current_page - Math.floor(maxVisible / 2));
        let endPage = startPage + maxVisible - 1;

        if (endPage > data.last_page) {
            endPage = data.last_page;
            startPage = Math.max(1, endPage - maxVisible + 1);
        }

        // Prev button
        if (data.current_page > 1) {
            const prevBtn = document.createElement('button');
            prevBtn.textContent = '← Önceki';
            prevBtn.onclick = () => {
                currentPage = data.current_page - 1;
                loadProducts();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };
            paginationEl.appendChild(prevBtn);
        }

        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            if (i === data.current_page) btn.classList.add('active');
            btn.onclick = () => {
                currentPage = i;
                loadProducts();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };
            paginationEl.appendChild(btn);
        }

        // Next button
        if (data.current_page < data.last_page) {
            const nextBtn = document.createElement('button');
            nextBtn.textContent = 'Sonraki →';
            nextBtn.onclick = () => {
                currentPage = data.current_page + 1;
                loadProducts();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };
            paginationEl.appendChild(nextBtn);
        }
    }

    // Sayfa yüklendiğinde
    document.addEventListener('DOMContentLoaded', () => {
        loadCategories();
        loadProducts();
    });
</script>
</body>
</html>
