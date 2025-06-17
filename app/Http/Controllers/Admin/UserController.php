<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::when(request('search'), function($query) {
                $search = request('search');
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->when(request('role'), function($query) {
                if (request('role') === 'admin') {
                    $query->where('is_admin', true);
                } else {
                    $query->where('is_admin', false);
                }
            })
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('orders', 'addresses', 'reviews');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }    public function update(Request $request, User $user)
    {        $request->validate([            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'password' => 'nullable|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'email_verified' => 'boolean'
        ]);

        $data = $request->except(['password', 'password_confirmation', 'email_verified']);
          // Xử lý is_admin
        $data['is_admin'] = $request->has('is_admin');
        
        // Xử lý is_active
        $data['is_active'] = $request->has('is_active');
        
        // Xử lý email verification
        if ($request->has('email_verified')) {
            $data['email_verified_at'] = $user->email_verified_at ?: now();
        } else {
            $data['email_verified_at'] = null;
        }
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Thông tin người dùng đã được cập nhật!');
    }

    public function toggleAdmin(User $user)
    {
        $user->update(['is_admin' => !$user->is_admin]);
        
        $role = $user->is_admin ? 'admin' : 'người dùng thường';
        return back()->with('success', "Đã chuyển {$user->name} thành {$role}!");
    }

    public function destroy(User $user)
    {
        // Prevent deleting admin users
        if ($user->is_admin) {
            return back()->with('error', 'Không thể xóa tài khoản admin!');
        }

        // Check if user has orders
        if ($user->orders()->count() > 0) {
            return back()->with('error', 'Không thể xóa người dùng đã có đơn hàng!');
        }

        $user->delete();        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được xóa thành công!');
    }

    public function create()
    {
        return view('admin.users.create');
    }    public function store(Request $request)
    {        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'email_verified' => 'boolean'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,            'is_admin' => $request->has('is_admin'),
            'is_active' => $request->has('is_active'),
            'email_verified_at' => $request->has('email_verified') ? now() : null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được tạo thành công!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,make_admin,remove_admin',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $action = $request->action;
        $userIds = $request->user_ids;
        $count = count($userIds);        // Không cho phép thao tác trên tài khoản admin hiện tại
        $currentUserId = Auth::id();
        if (in_array($currentUserId, $userIds)) {
            return back()->with('error', 'Không thể thao tác trên tài khoản của chính bạn!');
        }

        switch ($action) {
            case 'delete':
                // Kiểm tra users có orders không
                $usersWithOrders = User::whereIn('id', $userIds)->whereHas('orders')->count();
                if ($usersWithOrders > 0) {
                    return back()->with('error', 'Không thể xóa người dùng đã có đơn hàng!');
                }
                User::whereIn('id', $userIds)->delete();
                $message = "Đã xóa {$count} người dùng thành công!";
                break;

            case 'make_admin':
                User::whereIn('id', $userIds)->update(['is_admin' => true]);
                $message = "Đã chuyển {$count} người dùng thành admin thành công!";
                break;

            case 'remove_admin':
                User::whereIn('id', $userIds)->update(['is_admin' => false]);
                $message = "Đã bỏ quyền admin của {$count} người dùng thành công!";
                break;
        }        return redirect()->route('admin.users.index')->with('success', $message);
    }    public function toggleStatus(User $user)
    {
        // Kiểm tra không được thao tác trên tài khoản của chính mình
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Không thể thao tác trên tài khoản của chính bạn!');
        }

        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'kích hoạt' : 'tạm khóa';
        return back()->with('success', "Đã {$status} tài khoản thành công!");
    }

    public function verifyEmail(User $user)
    {
        // Kiểm tra không được thao tác trên tài khoản của chính mình
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Không thể thao tác trên tài khoản của chính bạn!');
        }

        if ($user->email_verified_at) {
            // Nếu đã xác thực, hủy xác thực
            $user->update(['email_verified_at' => null]);
            return back()->with('success', 'Đã hủy xác thực email thành công!');
        } else {
            // Nếu chưa xác thực, xác thực
            $user->update(['email_verified_at' => now()]);            return back()->with('success', 'Đã xác thực email thành công!');
        }
    }

    public function resetPassword(User $user)
    {
        // Kiểm tra không được thao tác trên tài khoản của chính mình
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Không thể reset mật khẩu tài khoản của chính bạn!');
        }

        // Tạo mật khẩu ngẫu nhiên
        $newPassword = Str::random(8);
        
        // Cập nhật mật khẩu
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // Gửi email thông báo mật khẩu mới (tùy chọn)
        // Mail::to($user->email)->send(new NewPasswordMail($newPassword));

        return back()->with('success', "Đã reset mật khẩu thành công! Mật khẩu mới: {$newPassword}");
    }
}
