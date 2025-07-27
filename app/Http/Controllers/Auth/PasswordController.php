<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Mail\resetPasswordMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    // Menampilkan halaman form untuk reset password
    public function showLinkRequestForm()
    {
        return view('login.fogerPassword'); // Pastikan file view sudah ada
    }

    // Mengirimkan email untuk reset password
    public function sendResetLinkEmail(Request $request)
{
    // Validasi email
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
    ]);

    if ($validator->fails()) {
        return redirect()->route('password.request')
                         ->withErrors($validator)
                         ->withInput();
    }

    // Mengirimkan link reset password
    $response = Password::sendResetLink(
        $request->only('email'),
        function ($user, $token) {
            // Kirim email reset password
            Mail::to($user->email)->send(new resetPasswordMail($user, $token));
        }
    );

    if ($response == Password::RESET_LINK_SENT) {
        return back()->with('status', trans($response));
    } else {
        return back()->withErrors(['email' => trans($response)]);
    }
}

    // Menampilkan halaman untuk reset password setelah link diklik
    public function showResetForm($token)
    {
        return view('login.resetPassword', ['token' => $token]); // Pastikan file view sudah ada dengan nama 'password
    }

    // Mengupdate password setelah reset
    public function reset(Request $request)
    {
        // Validasi form reset password
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:6',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('password.reset', $request->token)
                             ->withErrors($validator)
                             ->withInput();
        }

        // Reset password
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password berhasil direset!');
        } else {
            return back()->withErrors(['email' => trans($response)]);
        }
    }
}
