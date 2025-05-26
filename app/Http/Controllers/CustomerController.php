<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Rank;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $customers = Customer::with('ranks')->get();
            return Inertia::render('admin/Customers/Index', [
                'customers' => $customers,
            ]);
        } catch (\Exception $e) {
            Log::error('Customer index error: ' . $e->getMessage());
            return response()->json(['error' => 'Lỗi server'], 500);
        }
    }

    public function create()
    {
        return Inertia::render('admin/Customers/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'contact_person' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'password' => 'required|string|max:50',
            'address' => 'nullable|string',
            'current_debt' => 'nullable|numeric',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        Customer::create($data);

        return redirect()->route('admin.customers.index')->with('success', 'Thêm khách hàng thành công.');
    }

    public function edit(Customer $customer)
    {
        try {
            return Inertia::render('admin/Customers/Edit', [
                'customer' => $customer->load('ranks'),
            ]);
        } catch (\Exception $e) {
            Log::error('Customer edit error: ' . $e->getMessage());
            return response()->json(['error' => 'Lỗi server'], 500);
        }
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'contact_person' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'password' => 'required|string|max:50',
            'address' => 'nullable|string',
            'current_debt' => 'nullable|numeric',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $customer->update($data);

        return redirect()->route('admin.customers.index')->with('success', 'Cập nhật khách hàng thành công.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Xóa khách hàng thành công.');
    }

    public function storeRank(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'min_total_spent' => 'required|integer',
            'discount_percent' => 'required|integer',
            'credit_percent' => 'required|integer',
            'note' => 'nullable|string',
        ]);

        $customer->ranks()->create($request->all());

        return redirect()->route('admin.customers.edit', $customer)->with('success', 'Thêm hạng khách hàng thành công.');
    }
}