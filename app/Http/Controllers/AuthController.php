<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginView() {
        return view('admin.auth.login', [ 'title' => 'Login to Dashboard' ]);
    }

    public function sendMailView() {
        return view('admin.auth.send_mail', [ 'title' => 'Send Email to Reset password' ]);

    }

    public function forgotPasswordView() {
        return view('admin.auth.forgot_password', [ 'title' => 'Reset Password' ]);

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

                    return redirect()->intended('/admin')->with('success', 'Selamat datang kembali!');
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
            // 6. Handle Error Server
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
        try {
            $validation = Validator::Make();
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    public function forgotPasswordProcess(Request $request) {
        try {
            $validation = Validator::Make();
        } catch (\Exception $e) {
            //throw $th;
        }

    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
