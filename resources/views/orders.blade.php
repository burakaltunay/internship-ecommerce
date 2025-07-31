<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Siparişlerim - TechShop</title>
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

        .auth-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
        }

        .auth-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Main Content */
        .main-content {
            margin-top: 100px;
            padding: 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .page-subtitle {
            font-size: 1.1rem;
            color: #666;
        }

        /* Controls */
        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .filter-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .filter-select {
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            background: white;
            cursor: pointer;
            transition: var(--transition);
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .total-amount {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            background: rgba(37, 99, 235, 0.1);
            padding: 12px 20px;
            border-radius: 8px;
            border: 2px solid var(--primary-color);
        }

        /* Orders Table */
        .orders-container {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-medium);
            overflow: hidden;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th {
            background: #f8fafc;
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid var(--border-color);
        }

        .orders-table td {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: top;
        }

        .orders-table tr:hover {
            background: #f9fafb;
        }

        .order-id {
            font-weight: 600;
            color: var(--primary-color);
        }

        .order-date {
            color: #666;
            font-size: 0.9rem;
        }

        .order-items {
            max-width: 300px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-weight: 500;
            flex: 1;
        }

        .item-quantity {
            color: #666;
            font-size: 0.9rem;
            margin-left: 10px;
        }

        .order-total {
            font-weight: 600;
            color: var(--primary-color);
        }

        .order-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
        }

        .status-delivered {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .discount-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 8px;
        }

        /* Loading */
        .loading {
            text-align: center;
            padding: 60px 20px;
            font-size: 1.2rem;
            color: #666;
        }

        .loading::after {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
            flex-wrap: wrap;
            gap: 20px;
            position: relative;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .pagination-btn {
            padding: 10px 16px;
            border: 2px solid var(--border-color);
            background: white;
            color: var(--text-color);
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
        }

        .pagination-btn:hover {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }

        .pagination-btn.current {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .pagination-info {
            color: #666;
            font-size: 0.9rem;
            padding: 10px 16px;
            background: #f8fafc;
            border-radius: 8px;
            border: 2px solid var(--border-color);
            position: absolute;
            left: 0;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            color: #d1d5db;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #374151;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .controls {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-controls {
                justify-content: center;
            }

            .total-amount {
                text-align: center;
            }

            .orders-table {
                font-size: 0.9rem;
            }

            .orders-table th,
            .orders-table td {
                padding: 12px 8px;
            }

            .order-items {
                max-width: 200px;
            }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-content {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: var(--shadow-heavy);
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        .loading-text {
            font-size: 1.2rem;
            color: var(--text-color);
            font-weight: 600;
        }

        /* Popup Styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        }

        .popup-content {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: var(--shadow-heavy);
            position: relative;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
        }

        .popup-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .popup-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            padding: 5px;
            border-radius: 50%;
            transition: var(--transition);
        }

        .popup-close:hover {
            background: #f0f0f0;
            color: #333;
        }

        .popup-items {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .popup-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .popup-item-name {
            font-weight: 600;
            color: var(--text-color);
            flex: 1;
        }

        .popup-item-quantity {
            background: var(--primary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .order-items-summary:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text">Siparişler yükleniyor...</div>
        </div>
    </div>

    <!-- Popup Overlay -->
    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-content">
            <div class="popup-header">
                <h3 class="popup-title" id="popupTitle">Sipariş Ürünleri</h3>
                <button class="popup-close" onclick="closePopup()">×</button>
            </div>
            <div class="popup-items" id="popupItems">
                <!-- Items will be loaded here -->
            </div>
        </div>
    </div>

    <header>
        <div class="logo" onclick="navigateToHome()">Tech<span>Shop</span></div>
        <div class="header-actions">
            <!-- Geri Dön butonu kaldırıldı -->
        </div>
    </header>

    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">Siparişlerim</h1>
            <p class="page-subtitle">Tüm siparişlerinizi buradan takip edebilirsiniz</p>
        </div>

        <div class="controls">
            <div class="filter-controls">
                <label for="filterSelect">Filtre:</label>
                <select id="filterSelect" class="filter-select">
                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Tümü</option>
                    <option value="approved" {{ $filter == 'approved' ? 'selected' : '' }}>Onaylanmış</option>
                    <option value="delivered" {{ $filter == 'delivered' ? 'selected' : '' }}>Teslim Edilmiş</option>
                    <option value="rejected" {{ $filter == 'rejected' ? 'selected' : '' }}>Onaylanmamış</option>
                    <option value="with_discount" {{ $filter == 'with_discount' ? 'selected' : '' }}>İndirim Kuponu Kullanılmış</option>
                </select>
            </div>
            <div class="total-amount" id="totalAmount">
                Toplam: ₺{{ number_format($totalAmount, 2) }}
            </div>
        </div>

        <div class="orders-container">
            <div id="ordersContent">
                @if(count($orders) > 0)
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Sipariş No</th>
                                <th>Tarih</th>
                                <th>Ürünler</th>
                                <th>Toplam</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <div class="order-id">#{{ $order->id }}</div>
                                        @if($order->has_discount)
                                            <span class="discount-badge">İndirim</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="order-date">{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="order-items">
                                            @php
                                                $itemCount = count($order->items);
                                            @endphp
                                            <div class="order-items-summary" onclick="showOrderItems({{ $order->id }}, {{ json_encode($order->items) }})" style="cursor: pointer; color: var(--primary-color); font-weight: 600;">
                                                {{ $itemCount }} ürün görüntüle
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="order-total">₺{{ number_format($order->total_price, 2) }}</div>
                                    </td>
                                    <td>
                                        @if($order->is_delivered)
                                            <span class="order-status status-delivered">Teslim Edildi</span>
                                        @elseif($order->is_approved)
                                            <span class="order-status status-approved">Onaylandı</span>
                                        @else
                                            <span class="order-status status-rejected">Onaylanmadı</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3>Sipariş Bulunamadı</h3>
                        <p>Henüz onaylanmış siparişiniz bulunmuyor.</p>
                    </div>
                @endif
            </div>
        </div>

        @if($pagination['total_pages'] > 1)
            <div class="pagination-container">
                @if($pagination['total_orders'] > 0)
                    <div class="pagination-info">
                        {{ $pagination['showing'] }}/{{ $pagination['total_orders'] }} sipariş gösteriliyor
                    </div>
                @endif
                
                <div class="pagination" id="pagination">
                    @if($pagination['current_page'] > 1)
                        <a href="?filter={{ $filter }}&page={{ $pagination['current_page'] - 1 }}" class="pagination-btn">Önceki</a>
                    @endif

                    @for($i = 1; $i <= $pagination['total_pages']; $i++)
                        @if($i == 1 || $i == $pagination['total_pages'] || ($i >= $pagination['current_page'] - 2 && $i <= $pagination['current_page'] + 2))
                            <a href="?filter={{ $filter }}&page={{ $i }}" 
                               class="pagination-btn {{ $i == $pagination['current_page'] ? 'current' : '' }}">
                                {{ $i }}
                            </a>
                        @elseif($i == $pagination['current_page'] - 3 || $i == $pagination['current_page'] + 3)
                            <span>...</span>
                        @endif
                    @endfor

                    @if($pagination['current_page'] < $pagination['total_pages'])
                        <a href="?filter={{ $filter }}&page={{ $pagination['current_page'] + 1 }}" class="pagination-btn">Sonraki</a>
                    @endif
                </div>
            </div>
        @endif


    </div>

    <script>
        // Global variable to track navigation type
        let isNavigatingToHome = false;

        // Function to navigate to home without showing loading overlay
        function navigateToHome() {
            isNavigatingToHome = true;
            window.location.href = '/';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const filterSelect = document.getElementById('filterSelect');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const pagination = document.getElementById('pagination');
            const paginationInfo = document.querySelector('.pagination-info');

            // Sayfa yüklendiğinde kısa bir süre loading göster
            loadingOverlay.style.display = 'flex';
            setTimeout(function() {
                loadingOverlay.style.display = 'none';
            }, 500); // 500ms sonra gizle

            // Filtre değiştiğinde
            filterSelect.addEventListener('change', function() {
                const selectedFilter = this.value;
                
                // Loading overlay'i göster
                loadingOverlay.style.display = 'flex';
                
                // Pagination bar'ı gizle
                if (pagination) {
                    pagination.style.display = 'none';
                }
                if (paginationInfo) {
                    paginationInfo.style.display = 'none';
                }
                
                // Sayfayı yönlendir (ilk sayfaya)
                window.location.href = '?filter=' + selectedFilter;
            });

            // Pagination linklerine tıklandığında da loading göster
            const paginationLinks = document.querySelectorAll('.pagination-btn');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Loading overlay'i göster
                    loadingOverlay.style.display = 'flex';
                });
            });

            // Browser'ın geri tuşunu dinle
            window.addEventListener('beforeunload', function(e) {
                // Eğer ana sayfaya gidiyorsak loading gösterme
                if (!isNavigatingToHome) {
                    // Loading overlay'i göster
                    loadingOverlay.style.display = 'flex';
                }
            });

            // Popstate event (browser geri/ileri tuşları için)
            window.addEventListener('popstate', function(e) {
                // Loading overlay'i göster
                loadingOverlay.style.display = 'flex';
            });
        });

        // Popup functions
        function showOrderItems(orderId, items) {
            const popupOverlay = document.getElementById('popupOverlay');
            const popupTitle = document.getElementById('popupTitle');
            const popupItems = document.getElementById('popupItems');
            
            // Set title
            popupTitle.textContent = `Sipariş #${orderId} Ürünleri`;
            
            // Clear previous items
            popupItems.innerHTML = '';
            
            // Add items
            items.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'popup-item';
                itemDiv.innerHTML = `
                    <div class="popup-item-name">${item.product_name}</div>
                    <div class="popup-item-quantity">x${item.quantity}</div>
                `;
                popupItems.appendChild(itemDiv);
            });
            
            // Show popup
            popupOverlay.style.display = 'flex';
        }

        function closePopup() {
            const popupOverlay = document.getElementById('popupOverlay');
            popupOverlay.style.display = 'none';
        }

        // Close popup when clicking outside
        document.getElementById('popupOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                closePopup();
            }
        });
    </script>

</body>
</html> 