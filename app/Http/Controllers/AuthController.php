<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function loginView() {
        return view('admin.auth.login', [ 'title' => 'Login to Dashboard' ]);
    }

    public function sendMailView() {
        return view('admin.auth.send_mail', [ 'title' => 'Send Email to Reset password' ]);

    }

    public function forgotPasswordView() {
        return view('admin.auth.forgot_password', [
            'title' => 'Reset Password',
            'token' => request()->token,
            'email' => request()->email
        ]);

    }

    public function loginProcess(Request $request) {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        try {
            if (!$validation->fails()) {
                if (Auth::attempt($request->only(['email', 'password']), $request->remember_me)) {

                    $user = User::where('email', $request->email)->first();
                    try {
                        activity()
                        ->useLog('Authentication')
                        ->causedBy(Auth::user())
                        ->withProperties([
                            'ip' => request()->ip(),
                            'user_name' => Auth::user()->name,
                            'role' => Auth::user()->role,
                            'details' => 'Successful login'
                        ])
                        ->log('Login');
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Gagal simpan log login: ' . $e->getMessage());
                    }



                    if ($request->wantsJson()) {
                        $user->tokens()->delete();

                        $token = $user->createToken('PharmaEaseToken')->plainTextToken;

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Login Berhasil',
                            'token' => $token,
                            'user' => $user
                        ], 200);
                    }

                    $request->session()->regenerate();

                    return redirect()->intended('/admin')->with('login-success', 'Selamat datang kembali!');
                }

            }

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email atau Password salah.',
                    'errors' => $validation->errors()
                ], 401);
            }

            return back()->with('error', 'Email atau Password salah.')
                         ->withInput($request->only('email'))
                         ->withErrors($validation->errors());

        } catch (\Exception $e) {
            Log::error("Login Error: " . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan server.',
                    'stack'=> $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.')->withInput();
        }
    }

    public function sendMailProcess(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Input Email tidak boleh kosong',
            'email.email' => 'Email Harus berupa Email yang valid',
            'email.exists' => 'Email Tidak tersedia di data user',
        ]);

        try {
            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]
            );

            Mail::to($request->email)->send(new ResetPassword($token, $request->email));

            return back()->with('success', 'Kami telah mengirimkan link reset password ke email Anda!');

        } catch (\Exception $e) {
            Log::error("Mail Error: " . $e->getMessage());
            return back()->with('error', 'Gagal mengirim email. Silakan coba lagi.');
        }
    }

    public function forgotPasswordProcess(Request $request) {
        $request->validate([
            'password' => 'required|min:6|',
            'token' => 'required'
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.'
        ]);

        try {
            $resetRecord = DB::table('password_reset_tokens')
                ->where('token', $request->token)
                ->first();
            // dd($resetRecord);
            if (!$resetRecord) {
                return back()->with('error', 'Token tidak valid atau sudah kadaluarsa.');
            }

            User::where('email', $resetRecord->email)->update([
                'password' => Hash::make($request->password)
            ]);

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return redirect()->intended('/login')->with('success-reset', 'Password berhasil diubah! Silakan login.');

        } catch (\Exception $e) {
            Log::error("Reset Password Error: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->to('/login')->with('logout-success', 'Successfully Logout');
    }
}
