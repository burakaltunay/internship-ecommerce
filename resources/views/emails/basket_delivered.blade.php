<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TechShop - Siparişiniz Teslim Edildi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .status-box {
            background: #d1ecf1;
            border: 2px solid #17a2b8;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            font-size: 18px;
            color: #0c5460;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #17a2b8;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }
        .success-box {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📦 Siparişiniz Teslim Edildi</h1>
        <p>Sayın {{ $basket->email }}</p>
    </div>
    
    <div class="content">
        <h2>Mükemmel! 🎉</h2>
        <p>Siparişiniz başarıyla adresinize teslim edildi.</p>
        
        <div class="status-box">
            <strong>📦 Sipariş Durumu: Teslim Edildi</strong>
        </div>
        
        <div class="info-box">
            <p><strong>📋 Teslimat Detayları:</strong></p>
            <ul style="text-align: left; margin: 10px 0;">
                <li>Sipariş tarihi: {{ $basket->created_at->format('d.m.Y H:i') }}</li>
                <li>Teslim tarihi: {{ now()->format('d.m.Y H:i') }}</li>
                <li>Sipariş durumu: Teslim Edildi</li>
            </ul>
        </div>
        
        <div class="success-box">
            <p><strong>✅ Teslimat Tamamlandı</strong></p>
            <p>Siparişiniz güvenli bir şekilde adresinize teslim edildi. Paketinizi kontrol etmeyi unutmayın!</p>
        </div>
        
        <p><strong>📝 Sonraki Adımlar:</strong></p>
        <ol style="text-align: left;">
            <li>Paketinizi kontrol edin</li>
            <li>Ürünlerinizi inceleyin</li>
            <li>Memnun kaldıysanız değerlendirme yapın</li>
            <li>Yeni alışverişler için tekrar bekleriz!</li>
        </ol>
        
        <p><strong>⭐ Değerlendirme:</strong></p>
        <p>Deneyiminizi değerlendirmek ve diğer müşterilere yardımcı olmak için ürünlerinizi puanlayabilirsiniz.</p>
        
        <div style="text-align: center;">
            <a href="{{ url('/dashboard') }}" class="btn">Yeni Alışverişe Başla</a>
        </div>
        
        <div class="footer">
            <p>Bu email TechShop tarafından gönderilmiştir.</p>
            <p>Tekrar görüşmek üzere! 👋</p>
        </div>
    </div>
</body>
</html> 