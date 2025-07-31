<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TechShop - Siparişiniz Onaylanmadı</title>
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
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
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
            background: #f8d7da;
            border: 2px solid #dc3545;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            font-size: 18px;
            color: #721c24;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #dc3545;
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
        .help-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>❌ Siparişiniz Onaylanmadı</h1>
        <p>Sayın {{ $basket->email }}</p>
    </div>
    
    <div class="content">
        <h2>Üzgünüz 😔</h2>
        <p>Maalesef siparişiniz onaylanamadı. Bu durumun birkaç nedeni olabilir.</p>
        
        <div class="status-box">
            <strong>❌ Sipariş Durumu: Onaylanmadı</strong>
        </div>
        
        <div class="info-box">
            <p><strong>📋 Sipariş Detayları:</strong></p>
            <ul style="text-align: left; margin: 10px 0;">
                <li>Sipariş tarihi: {{ $basket->created_at->format('d.m.Y H:i') }}</li>
                <li>Sipariş durumu: Onaylanmadı</li>
                <li>Durum: İnceleniyor</li>
            </ul>
        </div>
        
        <div class="help-box">
            <p><strong>🔍 Olası Nedenler:</strong></p>
            <ul style="text-align: left; margin: 10px 0;">
                <li>Stok yetersizliği</li>
                <li>Adres bilgilerinde eksiklik</li>
                <li>Ödeme bilgilerinde sorun</li>
                <li>Teknik bir problem</li>
            </ul>
        </div>
        
        <p><strong>💡 Ne Yapabilirsiniz:</strong></p>
        <ol style="text-align: left;">
            <li>Yeni bir sipariş oluşturun</li>
            <li>Adres bilgilerinizi kontrol edin</li>
            <li>Farklı ürünler deneyin</li>
            <li>Müşteri hizmetlerimizle iletişime geçin</li>
        </ol>
        
        <p><strong>📞 Yardım için:</strong></p>
        <p>Herhangi bir sorunuz olursa müşteri hizmetlerimizle iletişime geçebilirsiniz. Size yardımcı olmaktan mutluluk duyarız.</p>
        
        <div style="text-align: center;">
            <a href="{{ url('/dashboard') }}" class="btn">Yeni Sipariş Ver</a>
        </div>
        
        <div class="footer">
            <p>Bu email TechShop tarafından gönderilmiştir.</p>
            <p>Tekrar denemek için bekleriz! 🙏</p>
        </div>
    </div>
</body>
</html> 