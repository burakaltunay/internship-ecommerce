<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // WEB AUTH METHODS
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function webLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Geçersiz kimlik bilgileri.',
        ])->onlyInput('email');
    }

    public function webRegister(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
        ]);

        $password = Str::random(12);

        User::create([
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        return redirect()->route('login')
            ->with('generated_password', $password)
            ->with('status', 'Kayıt başarılı! Şifreniz: '.$password);
    }

    public function webLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // AJAX/JSON METHODS - Frontend JavaScript için
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $email = $request->email;
            $password = $request->password;

            // E-mail var mı kontrol et
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu e-mail ile kayıtlı kullanıcı bulunamadı. Lütfen üye olun.'
                ], 404);
            }

            // Şifre doğru mu kontrol et
            if (!Hash::check($password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Şifre yanlış!'
                ], 401);
            }

            // Session'a kaydet (Laravel auth)
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Giriş başarılı!',
                'redirect' => '/dashboard'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri girişi.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $email = $request->email;

            // E-mail var mı kontrol et
            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu e-mail adresi zaten kayıtlı!'
                ], 400);
            }

            // E-mail yoksa şifre üret (12 karakter olarak standardize edildi)
            $password = Str::random(12);

            // Veritabanına kaydet
            User::create([
                'email' => $email,
                'password' => Hash::make($password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Şifreniz üretildi, lütfen kopyalayınız.',
                'password' => $password
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri girişi.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    // API AUTH METHODS - Token tabanlı
    public function apiRegister(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:users',
            ]);

            $password = Str::random(12);
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);

            return response()->json([
                'success' => true,
                'password' => $password,
                'token' => $user->createToken('API Token')->plainTextToken
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri girişi.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiLogin(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $email = $request->email;
            $password = $request->password;

            // E-mail var mı kontrol et
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu e-mail ile kayıtlı kullanıcı bulunamadı. Lütfen üye olun.'
                ], 404);
            }

            // Şifre doğru mu kontrol et
            if (!Hash::check($password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Şifre yanlış!'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'token' => $user->createToken('API Token')->plainTextToken
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri girişi.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiLogout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Çıkış yapıldı'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}
