<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServicesController extends Controller
{
    public function index(){
        $services = Service::all();
        return view('admin.page.service.service_listting', compact('services'));
    }
    public function create(){
        return view('admin.page.service.service_create');
    }
    public function store(Request $request){
        $request->validate([

           'name' => 'required|string|max:255',
            'price' => 'required|integer',
        ]);
        Service::create([
           'name'=> $request->name,
            'price' => $request->price,
        ]);
        return redirect()->route('service.list');
    }

    public function edit($service_id)
    {
        $service = Service::findOrFail($service_id); // Tìm dịch vụ theo ID
        return view('admin.page.service.edit_service', compact('service'));
    }

    // Thêm phương thức update
    public function update(Request $request, $service_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
        ]);

        $service = Service::findOrFail($service_id); // Tìm dịch vụ theo ID
        $service->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->route('service.list')->with('success', 'Dịch vụ đã được cập nhật thành công.');
    }
    public function destroy($service_id)
    {
        $service = Service::find($service_id);
        $service->delete();
        return redirect()->back()->with('success', 'Service deleted successfully');
    }


}
