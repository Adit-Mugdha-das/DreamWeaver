<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'regex:/@dream\.com$/i'],
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect('/welcome');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => ['required', 'email', 'ends_with:@dream.com', 'unique:users,email'],
            'recovery_email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/', 'unique:users,recovery_email'],
            'password'       => 'required|confirmed|min:6',
        ]);

        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'recovery_email' => $request->recovery_email,
            'password'       => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. Please log in.');
    }

    /**
     * Send password reset link to the recovery email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'regex:/@gmail\.com$/'],
        ]);

        $user = User::where('recovery_email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this recovery email.']);
        }

        // Generate token for the actual login email
        $token = Password::createToken($user);

        // Save token in password_resets table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        // Create the reset link
        $resetLink = url(route('password.reset', ['token' => $token, 'email' => $user->email], false));

        // Send the email to the recovery email
        Mail::send('emails.custom-reset', ['resetLink' => $resetLink], function ($message) use ($user, $request) {
            $message->to($request->email);
            $message->subject('DreamWeaver Password Reset');
        });

        return back()->with('status', 'Reset link has been sent to your recovery Gmail.');
    }

    /**
     * Handle actual password reset submission (user comes from reset link)
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // ✅ Hashing the new password to prevent Bcrypt error
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
