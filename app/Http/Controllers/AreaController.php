<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AreaController extends Controller
{
    public function index() {
        $areas = Area::all();
        return view('admin.page.area.area_lissting', compact('areas'));
    }
    public function create()
    {
        return view('admin.page.area.add_area'); // View form add
    }

    // Xử lý thêm mới dữ liệu
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        // Tạo mới một Area
        Area::create([
            'name' => $request->name,
            'address' => $request->address,
        ]);

        // Chuyển hướng sau khi thêm thành công
        return redirect()->route('area.list');
    }

    public function destroy($area_id)
    {
        $area = Area::findOrFail($area_id);
        $area->delete();
        return redirect()->back()->with('success', 'Slider deleted successfully');
    }
    public function edit($area_id)
    {
        $area = Area::findOrFail($area_id);
        return view('admin.page.area.edit_area', compact('area'));
    }
    public function update(Request $request, $area_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);
        $area = Area::findOrFail($area_id);
        $area->update([
           'name' => $request->name,
            'address' => $request->address,

        ]);
        return redirect()->route('area.list');
    }




}
