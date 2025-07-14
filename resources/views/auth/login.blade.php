<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş / Üye Ol</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: sans-serif; background: linear-gradient(135deg, #d8191f, #f86306); display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: rgb(255, 255, 255); padding: 2em; border-radius: 1em; width: 100%; max-width: 400px; text-align: center; }
        h1 { margin-bottom: .5em; }
        .toggle, .btn { margin: .5em 0; padding: .75em; width: 100%; border: none; border-radius: .5em; cursor: pointer; font-weight: bold; }
        .toggle.active { background: #ed1616; color: #fff; }
        input { width: 100%; padding: .75em; margin: .5em 0; border: 1px solid #ffffff; border-radius: .5em; }
        .alert { padding: 1em; border-radius: .5em; margin: .5em 0; font-size: .9em; }
        .success { background: #e6fffa; color: #f86306; }
        .error { background: #fff5f5; color: #f86306; }
        .spinner { border: 2px solid #eee; border-top: 2px solid #f86306; border-radius: 50%; width: 20px; height: 20px; animation: spin 1s linear infinite; margin: 1em auto; display: none; }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        .hidden { display: none; }
        .copy { background: #f86306; color: #fff; font-size: .8em; }
    </style>
</head>
<body>
<div class="box">
    <h1>Hoş Geldiniz</h1>
    <p>Giriş yap veya üye ol</p>

    <div>
        <button class="toggle active" id="loginMode">Giriş Yap</button>
        <button class="toggle" id="registerMode">Üye Ol</button>
    </div>

    <div id="alert"></div>

    <form id="form">
        @csrf
        <input type="email" id="email" placeholder="E-posta" required>
        <div id="passWrap">
            <input type="password" id="password" placeholder="Şifre" required>
            <button type="button" id="togglePass">Göster</button>
        </div>
        <div class="spinner" id="loading"></div>
        <button type="submit" class="btn" id="loginBtn">Giriş Yap</button>
        <button type="button" class="btn hidden" id="registerBtn">Üye Ol</button>
    </form>

    <div id="passBox" class="hidden">
        <p><strong>Şifreniz:</strong></p>
        <div id="generated"></div>
        <button class="btn copy" id="copy">Şifreyi Kopyala</button>
    </div>
</div>

<script>
    const csrf = document.querySelector('[name="csrf-token"]').content;
    const loginMode = document.getElementById('loginMode');
    const registerMode = document.getElementById('registerMode');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const form = document.getElementById('form');
    const togglePass = document.getElementById('togglePass');
    const passWrap = document.getElementById('passWrap');
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const loading = document.getElementById('loading');
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
    }

    togglePass.onclick = () => {
        password.type = password.type === 'password' ? 'text' : 'password';
        togglePass.textContent = password.type === 'password' ? 'Göster' : 'Gizle';
    };

    form.onsubmit = e => {
        e.preventDefault();
        isLogin ? login() : register();
    };

    registerBtn.onclick = register;

    async function login() {
        show(true);
        try {
            // FormData kullanarak form verisini hazırla
            const formData = new FormData();
            formData.append('email', email.value);
            formData.append('password', password.value);

            const res = await fetch('/ajax/login', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
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
            show(false);
        }
    }

    async function register() {
        if (!email.value) return notify('E-posta gerekli', 'error');

        show(true);
        try {
            // FormData kullanarak form verisini hazırla
            const formData = new FormData();
            formData.append('email', email.value);

            const res = await fetch('/ajax/register', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
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
            show(false);
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

    function show(state) {
        loading.style.display = state ? 'block' : 'none';
        loginBtn.disabled = registerBtn.disabled = state;
    }
</script>
</body>
</html>
