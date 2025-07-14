<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TechShop - Ana Sayfa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .logo span {
            color: #e74c3c;
        }

        .auth-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .hero {
            text-align: center;
            padding: 60px 20px;
        }

        .hero h1 {
            font-size: 28px;
            margin-bottom: 15px;
            color: #333;
        }

        .hero p {
            color: #666;
            margin-bottom: 25px;
        }

        .cta-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 25px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }

        .categories {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 30px;
            flex-wrap: wrap;
        }

        .category {
            background-color: white;
            padding: 20px;
            width: 150px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .category:hover {
            background-color: #f8f9fa;
        }

        .category.active {
            background-color: #e74c3c;
            color: white;
        }

        .product-list-container {
            background-color: white;
            padding: 20px;
            margin: 20px auto;
            max-width: 900px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-controls {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .clear-filter {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        #itemsPerPage {
            padding: 5px 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding-bottom: 10px;
            overflow: hidden;
        }

        .product-card {
            background-color: #fafafa;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .product-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
            text-align: center;
            color: #333;
        }

        .product-price {
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .counter {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .counter button {
            background-color: #e74c3c;
            border: none;
            color: white;
            padding: 5px 12px;
            font-size: 18px;
            border-radius: 4px;
            cursor: pointer;
            user-select: none;
        }

        .counter input {
            width: 40px;
            text-align: center;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 4px;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
            user-select: none;
        }

        .pagination button {
            background-color: white;
            border: 1px solid #e74c3c;
            color: #e74c3c;
            padding: 6px 12px;
            margin: 0 5px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            min-width: 40px;
        }

        .pagination button.active,
        .pagination button:hover {
            background-color: #e74c3c;
            color: white;
        }

        .no-products {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .error {
            text-align: center;
            padding: 40px;
            color: #e74c3c;
        }

        @media (max-width: 768px) {
            .product-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .product-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="logo">Tech<span>Shop</span></div>
    <button class="auth-btn" onclick="window.location.href='{{ route('web.login.form') }}'">Giriş Yap / Üye Ol</button>
</header>

<section class="hero">
    <h1>Teknoloji Ürünlerinin Adresi</h1>
    <p>En yeni elektronik ürünlerle tanışın</p>
    <a href="#products" class="cta-btn">Ürünleri Keşfet</a>
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
                <img src="${product.img}" alt="${product.name}" onerror="this.src='https://via.placeholder.com/150x150?text=Resim+Yok'" />
                <div class="product-name">${product.name}</div>
                <div class="product-price">${parseFloat(product.price).toLocaleString('tr-TR', {style:'currency', currency:'TRY'})}</div>
                <div class="counter">
                    <button aria-label="Azalt" data-id="${product.id}" class="decrement">-</button>
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
            btn.onclick = () => {
                const id = btn.dataset.id;
                const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
                let val = parseInt(input.value) || 0;
                if (val > 0) {
                    input.value = val - 1;
                }
            }
        });

        document.querySelectorAll('.increment').forEach(btn => {
            btn.onclick = () => {
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
            prevBtn.textContent = 'Önceki';
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
            nextBtn.textContent = 'Sonraki';
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
