<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::when(request('search'), function($query) {
                $search = request('search');
                $query->where('code', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
            })
            ->when(request('status'), function($query) {
                if (request('status') === 'active') {
                    $query->where('is_active', true);
                } elseif (request('status') === 'inactive') {
                    $query->where('is_active', false);
                } elseif (request('status') === 'expired') {
                    $query->where('expires_at', '<', now());
                }
            })
            ->latest()
            ->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date|after_or_equal:today',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Mã giảm giá đã được tạo thành công!');
    }

    public function show(Coupon $coupon)
    {
        return view('admin.coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Mã giảm giá đã được cập nhật thành công!');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        
        $status = $coupon->is_active ? 'kích hoạt' : 'tắt';
        return back()->with('success', "Đã {$status} mã giảm giá thành công!");
    }    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Mã giảm giá đã được xóa thành công!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'coupon_ids' => 'required|array',
            'coupon_ids.*' => 'exists:coupons,id'
        ]);

        $action = $request->action;
        $couponIds = $request->coupon_ids;
        $count = count($couponIds);

        switch ($action) {
            case 'delete':
                Coupon::whereIn('id', $couponIds)->delete();
                $message = "Đã xóa {$count} mã giảm giá thành công!";
                break;

            case 'activate':
                Coupon::whereIn('id', $couponIds)->update(['is_active' => true]);
                $message = "Đã kích hoạt {$count} mã giảm giá thành công!";
                break;

            case 'deactivate':
                Coupon::whereIn('id', $couponIds)->update(['is_active' => false]);
                $message = "Đã tắt {$count} mã giảm giá thành công!";
                break;
        }

        return redirect()->route('admin.coupons.index')->with('success', $message);
    }
}
