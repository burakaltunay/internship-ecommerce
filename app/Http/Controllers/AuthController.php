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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempts to log in the user with provided credentials, including "remember me".
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        // Returns with an error if authentication fails.
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    // Handles web registration requests (form submission).
    public function webRegister(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
        ]);

        // Generates a random password for the new user.
        $password = Str::random(12);

        // Creates a new user in the database.
        User::create([
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        // Redirects to login with the generated password and a success message.
        return redirect()->route('login')
            ->with('generated_password', $password)
            ->with('status', 'Registration successful! Your password: '.$password);
    }

    // Handles web logout requests.
    public function webLogout(Request $request)
    {
        Auth::logout(); // Logs out the user.
        $request->session()->invalidate(); // Invalidates the session.
        $request->session()->put('_token', null); // Clears the CSRF token.

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

            // Logs in the user via Laravel's authentication system.
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'redirect' => '/dashboard' // Redirects to the dashboard on success.
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

    // Handles AJAX registration requests.
    public function register(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $email = $request->email;

            // Checks if the email is already registered.
            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email address is already registered!'
                ], 400);
            }

            // Generates a random 12-character password.
            $password = Str::random(12);

            // Saves the new user to the database.
            User::create([
                'email' => $email,
                'password' => Hash::make($password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your password has been generated, please copy it.',
                'password' => $password
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

    // API AUTH METHODS - Token-based authentication

    // Handles API registration requests, generating a token.
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
