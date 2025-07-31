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
    // Displays the login form view.
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Displays the registration form view.
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handles web login requests (form submission).
    public function webLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $request->email;
        $password = $request->password;

        // Checks if a user with the given email exists.
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Bu email adresi ile kayıtlı kullanıcı bulunamadı. Lütfen üye olun.',
            ])->onlyInput('email');
        }

        // Attempts to authenticate the user with the provided credentials.
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return back()->withErrors([
                'password' => 'Geçersiz şifre!',
            ])->onlyInput('email');
        }

        // Logs in the user via Laravel's authentication system.
        Auth::login($user);
        $request->session()->regenerate();
        
        // Generate and save authentication token
        $token = Str::random(60);
        $user->update(['auth_token' => $token]);
        
        return redirect('/dashboard')->with('status', 'Giriş başarılı!');
    }

    // Handles web registration requests (form submission).
    public function webRegister(Request $request)
    {
        $request->validate([
            'email' => ['required', 'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/', 'unique:users'],
        ], [
            'email.regex' => 'Lütfen geçerli bir e-posta adresi girin (ör: a@b.c)'
        ]);

        // Generates a random password for the new user.
        $password = Str::random(12);

        // Creates a new user in the database.
        User::create([
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        // Send password via email
        try {
            \Mail::to($request->email)->send(new \App\Mail\WelcomePasswordMail($password));
        } catch (\Exception $e) {
            \Log::error('Web registration password email failed', ['error' => $e->getMessage(), 'email' => $request->email]);
        }

        // Redirects to login with a success message.
        return redirect()->route('login')
            ->with('status', 'Kayıt başarılı! Şifreniz email adresinize gönderildi.');
    }

    // Handles web logout requests.
    public function webLogout(Request $request)
    {
        // Clear auth token
        if (Auth::check()) {
            $user = Auth::user();
            $user->update(['auth_token' => null]);
        }
        
        Auth::logout(); // Logs out the user.
        $request->session()->invalidate(); // Invalidates the session.
        $request->session()->regenerateToken(); // Regenerates the CSRF token.

        return redirect('/'); // Redirects to the homepage.
    }

    // AJAX/JSON METHODS - For Frontend JavaScript

    // Handles AJAX login requests.
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $email = $request->email;
            $password = $request->password;

            // Checks if a user with the given email exists.
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu email adresi ile kayıtlı kullanıcı bulunamadı. Lütfen üye olun.'
                ], 404);
            }

            // Attempts to authenticate the user with the provided credentials.
            if (!Auth::attempt(['email' => $email, 'password' => $password])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Geçersiz şifre!'
                ], 401);
            }

            // Logs in the user via Laravel's authentication system.
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Giriş başarılı!',
                'redirect' => '/dashboard'
            ]);

        } catch (ValidationException $e) {
            // Handles validation errors.
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz email formatı.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handles general exceptions.
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    // Handles AJAX registration requests.
    public function register(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/'],
            ], [
                'email.regex' => 'Lütfen geçerli bir e-posta adresi girin (ör: a@b.c)'
            ]);

            $email = $request->email;

            // Checks if the email is already registered.
            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu email adresi zaten kayıtlı!'
                ], 400);
            }

            // Generates a random 12-character password.
            $password = Str::random(12);

            // Saves the new user to the database.
            User::create([
                'email' => $email,
                'password' => Hash::make($password)
            ]);

            // Send password via email
            try {
                \Mail::to($email)->send(new \App\Mail\WelcomePasswordMail($password));
            } catch (\Exception $e) {
                \Log::error('Registration password email failed', ['error' => $e->getMessage(), 'email' => $email]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kayıt başarılı! Şifreniz email adresinize gönderildi.'
            ]);

        } catch (ValidationException $e) {
            // Handles validation errors.
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz email formatı.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handles general exceptions.
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    // API AUTH METHODS - Token-based authentication

    // Handles API registration requests, generating a token.
    public function apiRegister(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/', 'unique:users'],
            ], [
                'email.regex' => 'Lütfen geçerli bir e-posta adresi girin (ör: a@b.c)'
            ]);

            $password = Str::random(12);
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);

            // Returns the generated password and a new API token.
            return response()->json([
                'success' => true,
                'password' => $password,
                'token' => $user->createToken('API Token')->plainTextToken
            ], 201);

        } catch (ValidationException $e) {
            // Handles validation errors.
            return response()->json([
                'success' => false,
                'message' => 'Invalid data input.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handles general exceptions.
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    // Handles API login requests, returning a token on success.
    public function apiLogin(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $email = $request->email;
            $password = $request->password;

            // Checks if a user with the given email exists.
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No user found with this email. Please register.'
                ], 404);
            }

            // Checks if the provided password is correct.
            if (!Hash::check($password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect password!'
                ], 401);
            }

            // Returns a new API token for the authenticated user.
            return response()->json([
                'success' => true,
                'token' => $user->createToken('API Token')->plainTextToken
            ]);

        } catch (ValidationException $e) {
            // Handles validation errors.
            return response()->json([
                'success' => false,
                'message' => 'Invalid data input.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handles general exceptions.
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    // Handles API logout requests by revoking the current access token.
    public function apiLogout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete(); // Deletes the current API token.

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);

        } catch (\Exception $e) {
            // Handles general exceptions.
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
