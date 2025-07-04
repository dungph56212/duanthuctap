<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;

class AddressController extends Controller
{
    /**
     * Display a listing of the addresses.
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->get();
        return view('client.addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new address.
     */
    public function create()
    {
        return view('client.addresses.create');
    }    /**
     * Store a newly created address in storage.
     */
    public function store(Request $request)
    {        // Validation linh hoạt theo input_mode
        $rules = [
            'type' => 'required|in:Giao hàng,Thanh toán',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string|max:500',
            'is_default' => 'boolean'
        ];

        // Nếu chọn từ danh sách
        if ($request->input_mode === 'select') {
            $rules['ten_phuong'] = 'required|string|max:100';
            $rules['ten_quan'] = 'required|string|max:100';
            $rules['ten_tinh'] = 'required|string|max:100';
        } else {
            // Nếu nhập tay
            $rules['manual_ten_phuong'] = 'required|string|max:100';
            $rules['manual_ten_quan'] = 'required|string|max:100';
            $rules['manual_ten_tinh'] = 'required|string|max:100';
        }

        $request->validate($rules);

        // If this is set as default, remove default from other addresses of same type
        if ($request->is_default) {
            Auth::user()->addresses()
                ->where('type', $request->type)
                ->update(['is_default' => false]);
        }        // Xử lý địa chỉ từ danh sách hoặc nhập tay
        if ($request->input_mode === 'select') {
            $city = $request->ten_phuong;  // ward -> city
            $state = $request->ten_quan;   // district -> state
            $country = $request->ten_tinh; // province -> country
        } else {
            $city = $request->manual_ten_phuong;
            $state = $request->manual_ten_quan;
            $country = $request->manual_ten_tinh;
        }

        // Tách tên thành first_name và last_name
        $nameParts = explode(' ', trim($request->name), 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';        $address = new Address([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $request->phone,
            'address_line_1' => $request->address_line,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'postal_code' => '10000', // Mã bưu điện mặc định
            'is_default' => $request->is_default ?? false
        ]);

        $address->save();

        return redirect()->route('client.addresses.index')
            ->with('success', 'Địa chỉ đã được thêm thành công!');
    }

    /**
     * Show the form for editing the specified address.
     */
    public function edit(Address $address)
    {
        // Check if address belongs to current user
        if ($address->user_id !== Auth::id()) {
            abort(404);
        }

        return view('client.addresses.edit', compact('address'));
    }

    /**
     * Update the specified address in storage.
     */
    public function update(Request $request, Address $address)
    {
        // Check if address belongs to current user
        if ($address->user_id !== Auth::id()) {
            abort(404);
        }

        $request->validate([
            'type' => 'required|in:shipping,billing',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string|max:500',
            'ten_phuong' => 'required|string|max:100',
            'ten_quan' => 'required|string|max:100',
            'ten_tinh' => 'required|string|max:100',
            'is_default' => 'boolean'
        ]);

        // If this is set as default, remove default from other addresses of same type
        if ($request->is_default) {
            Auth::user()->addresses()
                ->where('type', $request->type)
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update([
            'type' => $request->type,
            'name' => $request->name,
            'phone' => $request->phone,
            'address_line' => $request->address_line,
            'ward' => $request->ten_phuong,
            'district' => $request->ten_quan,
            'province' => $request->ten_tinh,
            'is_default' => $request->is_default ?? false
        ]);

        return redirect()->route('client.addresses.index')
            ->with('success', 'Địa chỉ đã được cập nhật thành công!');
    }

    /**
     * Remove the specified address from storage.
     */
    public function destroy(Address $address)
    {
        // Check if address belongs to current user
        if ($address->user_id !== Auth::id()) {
            abort(404);
        }

        $address->delete();

        return redirect()->route('client.addresses.index')
            ->with('success', 'Địa chỉ đã được xóa thành công!');
    }

    /**
     * Set address as default
     */
    public function setDefault(Address $address)
    {
        // Check if address belongs to current user
        if ($address->user_id !== Auth::id()) {
            abort(404);
        }

        // Remove default from other addresses of same type
        Auth::user()->addresses()
            ->where('type', $address->type)
            ->update(['is_default' => false]);

        // Set this address as default
        $address->update(['is_default' => true]);

        return redirect()->route('client.addresses.index')
            ->with('success', 'Đã đặt làm địa chỉ mặc định!');
    }
}
