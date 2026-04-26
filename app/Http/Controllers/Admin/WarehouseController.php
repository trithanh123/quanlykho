<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(10);
        return view('warehouses.index', compact('warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:warehouses,name',
            'address' => 'nullable|string'
        ], [
            'name.unique' => 'Tên kho này đã tồn tại!'
        ]);

        Warehouse::create($request->all());
        return redirect()->back()->with('success', 'Đã thêm kho bãi mới!');
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();
        return redirect()->back()->with('success', 'Đã xóa kho bãi thành công!');
    }
}