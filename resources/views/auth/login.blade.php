<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechShop - Giriş / Üye Ol</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .box {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 2.5em;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            text-align: center;
            position: relative;
            z-index: 1;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo {
            font-size: 32px;
            font-weight: 800;
            color: #2563eb;
            margin-bottom: 0.3em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .logo::before {
            content: "⚡";
            font-size: 36px;
            animation: pulse 2s ease-in-out infinite;
        }

        .logo span {
            background: linear-gradient(45deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        h1 {
            color: #333;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 0.3em;
        }

        .subtitle {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 1.5em;
        }

        .tab-wrapper {
            display: flex;
            margin-bottom: 1.5em;
            border-bottom: 2px solid #e5e7eb;
            border-radius: 8px 8px 0 0;
        }

        .tab {
            flex: 1;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 1em 0;
            cursor: pointer;
            font-weight: 600;
            color: #6b7280;
            transition: all 0.3s ease;
            position: relative;
            font-size: 16px;
        }

        .tab.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        .tab:hover {
            color: #1d4ed8;
        }

        .input-wrapper {
            position: relative;
            margin: 1em 0;
        }

        input {
            width: 100%;
            padding: 0.9em 1em;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            color: #333;
            background: white;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .password-input {
            padding-right: 50px;
        }

        .eye-button {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #6b7280;
            transition: all 0.3s ease;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }

        .eye-button:hover {
            color: #2563eb;
            background: #f3f4f6;
        }

        .eye-icon {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .alert {
            padding: 1em;
            border-radius: 8px;
            margin: 1em 0;
            font-size: 14px;
            font-weight: 500;
        }

        .success {
            background: #f0f9ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .spinner {
            border: 2px solid #e5e7eb;
            border-top: 2px solid #fff;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 8px;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .hidden {
            display: none;
        }

        .copy {
            background: transparent;
            color: #60a5fa;
            font-size: 13px;
            margin-top: 1em;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 0.5em 0;
            width: 100%;
        }

        .copy:hover {
            text-decoration: underline;
            color: #3b82f6;
        }

        .btn {
            position: relative;
            background: #2563eb;
            color: #fff;
            margin: 1em 0;
            padding: 0.9em 1.5em;
            width: 100%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .success-message {
            background: #f0f9ff;
            color: #2563eb;
            padding: 1em;
            border-radius: 8px;
            margin: 1em 0;
            border: 1px solid #bfdbfe;
        }

        #passBox {
            background: #f8fafc;
            padding: 1.5em;
            border-radius: 8px;
            margin: 1em 0;
            border: 1px solid #e2e8f0;
        }

        #passBox p {
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.5em;
        }

        #generated {
            background: white;
            padding: 1em;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #2563eb;
            border: 1px solid #e5e7eb;
            word-break: break-all;
            margin-bottom: 1em;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .box {
                margin: 20px;
                padding: 2em;
            }

            .logo {
                font-size: 28px;
            }

            .logo::before {
                font-size: 32px;
            }

            h1 {
                font-size: 20px;
            }

            .subtitle {
                font-size: 14px;
            }

            input {
                padding: 0.8em;
                font-size: 14px;
            }

            .btn {
                padding: 0.8em 1.2em;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<div class="box">
    <div class="logo">Tech<span>Shop</span></div>
    <h1>Hoş Geldiniz</h1>
    <p class="subtitle">Giriş yap veya üye ol</p>

    <div class="tab-wrapper">
        <button class="tab active" id="loginMode">Giriş Yap</button>
        <button class="tab" id="registerMode">Üye Ol</button>
    </div>

    <form id="form" novalidate>
        <div class="input-wrapper">
            <input type="email" id="email" placeholder="E-posta" required 
                   oninvalid="this.setCustomValidity('Lütfen geçerli bir e-posta adresi girin')">
            <div id="email-format-error" style="display:none;color:#dc2626;font-style:italic;font-size:13px;margin-top:4px;">Mail formatı hatalı</div>
        </div>
        <div class="input-wrapper" id="passWrap">
            <input type="password" id="password" class="password-input" placeholder="Şifre" required
                   oninvalid="this.setCustomValidity('Şifre gereklidir')"
                   oninput="this.setCustomValidity('')">
            <button type="button" class="eye-button" id="togglePass">
                <svg class="eye-icon" id="eyeIcon" viewBox="0 0 24 24">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </button>
        </div>

        <div id="alert"></div>

        <button type="submit" class="btn" id="loginBtn">
            <span id="loginBtnText">Giriş Yap</span>
        </button>
        <button type="button" class="btn hidden" id="registerBtn">
            <span id="registerBtnText">Üye Ol</span>
        </button>
    </form>

    <div id="passBox" class="hidden">
        <p><strong>Şifreniz:</strong></p>
        <div id="generated"></div>
        <button class="copy" id="copy">Şifreyi Kopyala</button>
    </div>
</div>

<script>
    const loginMode = document.getElementById('loginMode');
    const registerMode = document.getElementById('registerMode');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const form = document.getElementById('form');
    const togglePass = document.getElementById('togglePass');
    const eyeIcon = document.getElementById('eyeIcon');
    const passWrap = document.getElementById('passWrap');
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const loginBtnText = document.getElementById('loginBtnText');
    const registerBtnText = document.getElementById('registerBtnText');
    const alertBox = document.getElementById('alert');
    const passBox = document.getElementById('passBox');
    const generated = document.getElementById('generated');
    const copy = document.getElementById('copy');

    let isLogin = true;

    loginMode.onclick = () => toggleMode(true);
    registerMode.onclick = () => toggleMode(false);

    function toggleMode(state) {
        isLogin = state;
        loginMode.classList.toggle('active', state);
        registerMode.classList.toggle('active', !state);
        passWrap.classList.toggle('hidden', !state);
        loginBtn.classList.toggle('hidden', !state);
        registerBtn.classList.toggle('hidden', state);
        password.required = state;
        passBox.classList.add('hidden');
        alertBox.innerHTML = '';

        // Inputları temizle
        email.value = '';
        password.value = '';
        password.type = 'password';

        // Göz ikonunu sıfırla
        eyeIcon.innerHTML = `
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        `;
    }

    togglePass.onclick = () => {
        const isPassword = password.type === 'password';
        password.type = isPassword ? 'text' : 'password';

        // SVG ikonunu değiştir
        if (isPassword) {
            // Göz kapalı ikonu (şifre görünür)
            eyeIcon.innerHTML = `
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94l9.88 9.88z"></path>
                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19l-6.93-6.93a2.99 2.99 0 0 0-4.17-4.17z"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            `;
        } else {
            // Göz açık ikonu (şifre gizli)
            eyeIcon.innerHTML = `
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            `;
        }
    };

    form.onsubmit = e => {
        e.preventDefault();
        if (!validateEmail(email)) return;
        isLogin ? login() : register();
    };

    // Register butonuna tıklanınca da email formatı kontrolü yap
    registerBtn.onclick = function() {
        if (!validateEmail(email)) return;
        register();
    };

    async function login() {
        showSpinner(loginBtn, loginBtnText, true);
        try {
            const formData = new FormData();
            formData.append('email', email.value);
            formData.append('password', password.value);

            const res = await fetch('/ajax/login', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await res.json();

            if (res.ok && data.success) {
                notify('Giriş başarılı! Yönlendiriliyorsunuz...', 'success');
                setTimeout(() => {
                    window.location.href = data.redirect || '/dashboard';
                }, 1500);
            } else {
                notify(data.message || 'Giriş başarısız!', 'error');
            }
        } catch (error) {
            console.error('Login error:', error);
            notify('Bir hata oluştu', 'error');
        } finally {
            showSpinner(loginBtn, loginBtnText, false);
        }
    }

    async function register() {
        if (!email.value) return notify('E-posta gerekli', 'error');

        showSpinner(registerBtn, registerBtnText, true);
        try {
            const formData = new FormData();
            formData.append('email', email.value);

            const res = await fetch('/ajax/register', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await res.json();

            if (res.ok && data.success) {
                notify(data.message, 'success');
                if (data.password) {
                    generated.textContent = data.password;
                    passBox.classList.remove('hidden');
                }
            } else {
                notify(data.message || 'Kayıt başarısız', 'error');
            }
        } catch (error) {
            console.error('Register error:', error);
            notify('Bir hata oluştu', 'error');
        } finally {
            showSpinner(registerBtn, registerBtnText, false);
        }
    }

    copy.onclick = () => {
        navigator.clipboard.writeText(generated.textContent);
        copy.textContent = 'Kopyalandı!';
        setTimeout(() => copy.textContent = 'Şifreyi Kopyala', 2000);
    };

    function notify(msg, type) {
        alertBox.innerHTML = `<div class="alert ${type}">${msg}</div>`;
    }

    function showSpinner(button, textElement, show) {
        if (show) {
            textElement.innerHTML = '<span class="spinner"></span>';
            button.disabled = true;
        } else {
            textElement.textContent = isLogin ? 'Giriş Yap' : 'Üye Ol';
            button.disabled = false;
        }
    }
    
    function validateEmail(input) {
        const email = input.value;
        // Sadece a@b.c gibi formatları kabul eden regex
        const emailRegex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
        const errorDiv = document.getElementById('email-format-error');
        
        if (!email) {
            input.setCustomValidity('E-posta gerekli');
            if (errorDiv) errorDiv.style.display = 'none';
            return false;
        }
        if (!emailRegex.test(email)) {
            input.setCustomValidity('Mail formatı hatalı');
            if (errorDiv && !isLogin) errorDiv.style.display = 'block';
            return false;
        } else {
            input.setCustomValidity('');
            if (errorDiv) errorDiv.style.display = 'none';
            return true;
        }
    }

    // Register tab'a geçince hata mesajını gizle
    registerMode.onclick = () => {
        toggleMode(false);
        const errorDiv = document.getElementById('email-format-error');
        if (errorDiv) errorDiv.style.display = 'none';
    };
</script>
</body>
</html>
