<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\AdminResetPasswordMail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected const RESET_TOKEN_EXPIRY_MINUTES = 60;
    
    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }
        return view('backend.pages.auth.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'status' => 1
        ];
        $remember = $request->has('remember');
        if (Auth::attempt($credentials, $remember)) {
            /** @var User $user */
            $user = Auth::user();
            $user->last_login_at = Carbon::now();
            $user->last_login_ip = $request->ip();
            $user->login_attempts = 0;
            $user->save();
            $request->session()->regenerate();
            return redirect()->intended('dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        } else {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->increment('login_attempts');
                if ($user->status == 0) {
                    return redirect()->back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Your account is inactive. Please contact administrator.');
                }
            }

            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid email or password');
        }
    }

    public function showForgetPasswordForm()
    {
        return view('backend.pages.auth.forget-password');
    }

    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );
        try {
            $data = [
                'token' => $token,
            ];
            Mail::to($request->email)->send(new AdminResetPasswordMail($data));
            Log::info('Email sent successfully to ' . $request->email);
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
        }

        return back()->with('success', 'We have e-mailed your password reset link!');
    }

    public function showResetPasswordForm(Request $request, $token)
    {
        $resetRequest = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();

        if (!$resetRequest || $this->tokenExpired($resetRequest->created_at)) {
            return redirect('admin/login')->with('error', 'Invalid or expired password reset request.');
        }

        return view('backend.pages.auth.forget-password-link', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword || $this->tokenExpired($updatePassword->created_at)) {
            return back()->withInput()->with('error', 'Invalid or expired token!');
        }

        User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        return redirect('admin/login')->with('success', 'Your password has been changed!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::flush();
        return redirect(url('admin/login'))->with('success', 'You have been logged out successfully');
    }
    
    protected function tokenExpired($createdAt): bool
    {
        return Carbon::parse($createdAt)
            ->addMinutes(self::RESET_TOKEN_EXPIRY_MINUTES)
            ->isPast();
    }
}
