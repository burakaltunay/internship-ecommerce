<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TechShop - Siparişiniz Onaylandı</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            font-size: 18px;
            color: #155724;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #28a745;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ Siparişiniz Onaylandı</h1>
        <p>Sayın {{ $basket->email }}</p>
    </div>
    
    <div class="content">
        <h2>Harika! 🎉</h2>
        <p>Siparişiniz başarıyla onaylandı ve hazırlanmaya başlandı.</p>
        
        <div class="status-box">
            <strong>✅ Sipariş Durumu: Onaylandı</strong>
        </div>
        
        <div class="info-box">
            <p><strong>📋 Sipariş Detayları:</strong></p>
            <ul style="text-align: left; margin: 10px 0;">
                <li>Sipariş tarihi: {{ $basket->created_at->format('d.m.Y H:i') }}</li>
                <li>Sipariş durumu: Onaylandı</li>
                <li>Sonraki adım: Hazırlanıyor</li>
            </ul>
        </div>
        
        <p><strong>📦 Sonraki Adımlar:</strong></p>
        <ol style="text-align: left;">
            <li>Siparişiniz hazırlanıyor</li>
            <li>Kargo firmasına teslim edilecek</li>
            <li>Kargo takip numarası size iletilecek</li>
            <li>Siparişiniz adresinize teslim edilecek</li>
        </ol>
        
        <p><strong>📞 Sorularınız için:</strong></p>
        <p>Herhangi bir sorunuz olursa müşteri hizmetlerimizle iletişime geçebilirsiniz.</p>
        
        <div style="text-align: center;">
            <a href="{{ url('/orders') }}" class="btn">Siparişlerimi Görüntüle</a>
        </div>
        
        <div class="footer">
            <p>Bu email TechShop tarafından gönderilmiştir.</p>
            <p>Teşekkür ederiz! 🙏</p>
        </div>
    </div>
</body>
</html> 