<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TechShop - İndirim Kodunuz</title>
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
        .discount-box {
            background: #fff;
            border: 3px dashed #28a745;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            letter-spacing: 2px;
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
        .highlight {
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
        <h1>🎉 Tebrikler! İndirim Kodunuz Hazır</h1>
        <p>5. siparişinizi tamamladınız</p>
    </div>
    
    <div class="content">
        <h2>Merhaba!</h2>
        <p>5. siparişinizi başarıyla tamamladığınız için size özel bir indirim kodu kazandınız!</p>
        
        <div class="discount-box">
            {{ $code }}
        </div>
        
        <div class="highlight">
            <p><strong>🎯 Bu kod ile:</strong></p>
            <ul style="text-align: left; margin: 10px 0;">
                <li>Toplam tutar üzerinden <strong>%20 indirim</strong> kazanabilirsiniz</li>
                <li>Sepet onaylama sayfasında kullanabilirsiniz</li>
                <li>Tek kullanımlık bir koddur</li>
            </ul>
        </div>
        
        <p><strong>Nasıl kullanılır?</strong></p>
        <ol style="text-align: left;">
            <li>Sepetinizi oluşturun</li>
            <li>Onaylama sayfasına gidin</li>
            <li>İndirim kodu alanına yukarıdaki kodu yazın</li>
            <li>İndiriminizi görün!</li>
        </ol>
        
        <div style="text-align: center;">
            <a href="{{ url('/dashboard') }}" class="btn">Alışverişe Başla</a>
        </div>
        
        <div class="footer">
            <p>Bu email TechShop tarafından gönderilmiştir.</p>
            <p>İyi alışverişler! 🛒</p>
        </div>
    </div>
</body>
</html> 