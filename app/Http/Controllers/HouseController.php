<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\House;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class HouseController extends Controller
{
    public function index()
    {
        $houses = House::all();
        foreach ($houses as $house) {
            $house->area_name = Area::find($house->area_id)->name; // Truy xuất tên khu vực
        }
        return view('admin.page.house.house_listting', compact('houses'));
    }
    public function create()
    {
        $areas = Area::all();
        return view('admin.page.house.add_house',compact('areas'));
    }
    public function edit($house_id)
    {
        $houses = House::findOrFail($house_id);
        $areas = Area::all();
        return view('admin.page.house.edit_house',compact('areas', 'houses'));
    }
    public function store(Request $request)
    {
        $request->validate([
          'name' => 'required|string|max:255',
          'price' => 'required|integer',
            'area_id' =>'required|integer',
            'description' => 'required|string|max:255',
        ]);
        House::create([
            'name' =>$request->name,
            'price'=>$request->price,
            'area_id' =>$request->area_id,
            'description'=>$request->description
        ]);
        return redirect()->route('house.list');
    }
    public function destroy($house_id)
    {
        $house = House::findOrFail($house_id);
        $house->delete();
        return redirect()->back()->with('success', 'Slider deleted successfully');
    }
    public function update(Request $request, $house_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'area_id' => 'required|integer',
            'description' => 'required|string|max:255',
        ]);

        $house = House::findOrFail($house_id); // Tìm nhà theo ID
        $house->update([
            'name' => $request->name,
            'price' => $request->price,
            'area_id' => $request->area_id,
            'description' => $request->description,
        ]);

        return redirect()->route('house.list')->with('success', 'House updated successfully');
    }
//
//    public function tenant()
//    {
//        // Lấy tất cả các ngôi nhà
//        $houses = House::all();
//
//        // Lấy tất cả house_id đã thuê
//        $rentedHouseIds = Tenant::pluck('house_id')->toArray();
//
//        foreach ($houses as $house) {
//            // Chỉ để hiển thị tên khu vực (không lưu vào CSDL)
//            $house->area_name = $house->getAreaName(); // Giả sử phương thức này trả về tên khu vực
//
//            // Kiểm tra và cập nhật giá trị is_rented
//            $house->is_rented = in_array($house->id, $rentedHouseIds) ? 1 : 0;
//        }
//
//        // Cập nhật chỉ trường is_rented cho tất cả các ngôi nhà
//        foreach ($houses as $house) {
//            $house->save(['is_rented' => $house->is_rented]);
//        }
//
//        return view('admin.page.tenant.tenant_listting', compact('houses'));
//    }

    public function tenant()
    {
        // Lấy tất cả các ngôi nhà
        $houses = House::all();

        // Vòng lặp đầu tiên: Cập nhật giá trị is_rented
        foreach ($houses as $house) {
            // Kiểm tra xem có tenant nào thuê house_id này không
            $isRented = Tenant::where('house_id', $house->house_id)->exists();

            // Cập nhật giá trị is_rented
            $house->is_rented = $isRented ? 1 : 0;

            // Chỉ cập nhật trường is_rented vào cơ sở dữ liệu
            $house->update(['is_rented' => $house->is_rented]);
        }

        // Vòng lặp thứ hai: Thêm tên khu vực cho mục hiển thị (không cập nhật CSDL)
        foreach ($houses as $house) {
            // Gọi phương thức getAreaName() để hiển thị tên khu vực
            $house->area_name = $house->getAreaName();
        }

        // Trả về view cùng với danh sách houses
        return view('admin.page.tenant.tenant_listting', compact('houses'));
    }








}
