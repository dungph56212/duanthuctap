<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('client.home');
        }
        
        return view('client.auth.login');
    }

    public function showRegisterForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('client.home');
        }
        
        return view('client.auth.register');
    }public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        
        // Sử dụng web guard cho client
        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::guard('web')->user();
            
            // Kiểm tra nếu user bị khóa
            if (!$user->is_active) {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin.',
                ])->withInput($request->only('email'));
            }
            
            // Chặn admin đăng nhập vào client
            if ($user->is_admin) {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Tài khoản admin không thể đăng nhập vào trang client. Vui lòng sử dụng trang admin.',
                ])->withInput($request->only('email'));
            }
            
            // Chỉ redirect user thường về trang chủ client
            $intended = $request->session()->pull('url.intended', route('client.home'));
            return redirect($intended)
                ->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput($request->only('email'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'terms' => 'required|accepted',
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã được sử dụng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'terms.required' => 'Vui lòng đồng ý với điều khoản sử dụng',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'is_admin' => false,
                'is_active' => true,
                'email_verified_at' => now(), // Tự động verify email
            ]);            // Đăng nhập luôn sau khi đăng ký (chỉ cho user thường)
            Auth::guard('web')->login($user);

            return redirect()->route('client.home')
                ->with('success', 'Đăng ký thành công! Chào mừng bạn đến với BookStore!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['email' => 'Có lỗi xảy ra. Vui lòng thử lại.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.home')
            ->with('success', 'Đăng xuất thành công!');
    }    public function profile()
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('client.login');
        }

        $user = Auth::guard('web')->user();
        return view('client.auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('client.login');
        }

        $user = Auth::guard('web')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|string|min:6',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã được sử dụng',
            'current_password.min' => 'Mật khẩu hiện tại phải có ít nhất 6 ký tự',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('current_password', 'password', 'password_confirmation'));
        }

        // Nếu có đổi mật khẩu, kiểm tra mật khẩu hiện tại
        if ($request->filled('password')) {
            if (!$request->filled('current_password')) {
                return back()
                    ->withErrors(['current_password' => 'Vui lòng nhập mật khẩu hiện tại'])
                    ->withInput($request->except('current_password', 'password', 'password_confirmation'));
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác'])
                    ->withInput($request->except('current_password', 'password', 'password_confirmation'));
            }
        }        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            User::where('id', $user->id)->update($updateData);

            return redirect()->route('client.profile')
                ->with('success', 'Cập nhật thông tin thành công!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['email' => 'Có lỗi xảy ra. Vui lòng thử lại.'])
                ->withInput($request->except('current_password', 'password', 'password_confirmation'));
        }
    }
}
