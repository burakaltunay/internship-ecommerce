<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TechShop - Hoş Geldiniz!</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .password-box {
            background: #fff;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>TechShop'a Hoş Geldiniz! 🎉</h1>
        <p>Hesabınız başarıyla oluşturuldu</p>
    </div>
    
    <div class="content">
        <h2>Merhaba!</h2>
        <p>TechShop'a kayıt olduğunuz için teşekkür ederiz. Hesabınıza giriş yapabilmek için aşağıdaki şifreyi kullanabilirsiniz:</p>
        
        <div class="password-box">
            Şifreniz: <strong>{{ $password }}</strong>
        </div>
        
        
        <div style="text-align: center;">
            <a href="{{ url('/login') }}" class="btn">Giriş Yap</a>
        </div>
        
        <div class="footer">
            <p>Bu email TechShop tarafından gönderilmiştir.</p>
            <p>Eğer bu kayıt işlemini siz yapmadıysanız, lütfen bu emaili dikkate almayın.</p>
        </div>
    </div>
</body>
</html> 