<?php

namespace App\Http\Controllers\Admin;

use App\Models\House;
use App\Models\Tenant;
use App\Models\TenantDetail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class TenantDetailController extends Controller
{
    public function index()
    {
        $tenantDetails = TenantDetail::all();
        foreach ($tenantDetails as $tenantDetail) {
            $tenant = Tenant::find($tenantDetail->tenant_id);

            // Kiểm tra nếu tenant tồn tại
            if ($tenant) {
                // Nếu tenant đã bị xóa
                if ($tenant->is_delete == 1) {
                    $tenantDetail->house_name_teantDetail = "Đã chấm dứt thuê";
                } else {
                    // Lấy house_id
                    $houseId = $tenant->house_id ?? null;

                    if ($houseId) {
                        $house = House::find($houseId);
                        // Nếu house tồn tại
                        if ($house) {
                            $tenantDetail->house_name_teantDetail = $house->name ?? "Đang Trống";
                        } else {
                            $tenantDetail->house_name_teantDetail = "Đang Trống";
                        }
                    } else {
                        $tenantDetail->house_name_teantDetail = "Đang Trống";
                    }
                }
            } else {
                // Nếu tenant không tồn tại
                $tenantDetail->house_name_teantDetail = "Đang Trống";
            }
        }

        return view('admin.page.tenant.detail.detail_listting', compact('tenantDetails'));
    }


    public function create()
    {
        // Lấy area_id từ người dùng hiện tại
        $currentUser = auth()->user();
        $currentAreaId = $currentUser->area_id;

        $tenants = Tenant::where('is_delete', 0) // Lọc tenant có is_delete = 0
        ->where(function ($query) use ($currentAreaId) {
            // Nếu area_id của người dùng là 0, lấy tất cả tenants
            if ($currentAreaId != 0) {
                // Nếu area_id không phải là 0, lọc tenants trong các nhà thuộc khu vực của người dùng
                $query->whereIn('house_id', function ($subQuery) use ($currentAreaId) {
                    $subQuery->select('house_id')
                        ->from('houses')
                        ->where('area_id', $currentAreaId); // Lọc house theo area_id của user
                });
            }
        })
            ->get();

        foreach ($tenants as $teant)
        {
            $house = House::find($teant->house_id);
            $teant->house_name=$house->name;
        }
        return view('admin.page.tenant.detail.detail_create',compact('tenants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|integer',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'identity_card' => 'required|string|max:20',
            'identity_card_image.*' => 'nullable|image',
            'portrait_image' => 'nullable|image',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'leader' => 'boolean',
        ]);


        $tenantDetail = new TenantDetail();
        $tenantDetail->tenant_id = $request->tenant_id;
        $tenantDetail->full_name = $request->full_name;
        $tenantDetail->phone = $request->phone;
        $tenantDetail->email = $request->email;
        $tenantDetail->identity_card = $request->identity_card;

        // Khởi tạo mảng để lưu trữ đường dẫn các ảnh
        $images = [];
        $folderName = str_replace(' ', '_', $request->full_name); // Thay dấu cách bằng dấu gạch dưới
        $baseFolderPath = public_path('/tenant_images/' . $folderName); // Đường dẫn lưu ảnh bên ngoài thư mục public

        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($baseFolderPath)) {
            mkdir($baseFolderPath, 0777, true);
        }

        // Xử lý nhiều file cho identity_card_image
        if ($request->hasFile('identity_card_image')) {
            foreach ($request->file('identity_card_image') as $key => $file) {
                // Tạo tên tệp an toàn với str_replace
                $identityCardImageName = 'identity_card_' . $key . '.' . $file->getClientOriginalExtension();

                // Lưu vào thư mục bên ngoài public
                $file->move($baseFolderPath, $identityCardImageName);

                // Đặt lại đường dẫn để lưu vào cơ sở dữ liệu
                $images[] = 'tenant_images/' . str_replace(' ', '_', $folderName) . '/' . $identityCardImageName; // Đường dẫn tương đối
            }
            // Lưu mảng ảnh dưới dạng chuỗi JSON vào cột identity_card_image
            $tenantDetail->identity_card_image = json_encode($images, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }



        // Xử lý ảnh chân dung (portrait_image)
        if ($request->hasFile('portrait_image')) {
            $portraitImageName = 'portrait.' . $request->file('portrait_image')->getClientOriginalExtension();
            // Lưu vào thư mục bên ngoài public
            $request->file('portrait_image')->move($baseFolderPath, $portraitImageName);
            // Đặt lại đường dẫn để lưu vào cơ sở dữ liệu
            $tenantDetail->portrait_image = 'tenant_images/' . $folderName . '/' . $portraitImageName; // Đường dẫn tương đối
        }

        // Gán các giá trị còn lại cho TenantDetail
        $tenantDetail->gender = $request->gender;
        $tenantDetail->date_of_birth = $request->date_of_birth;
        $tenantDetail->leader = $request->leader ?? 0;

        // Lưu thông tin vào cơ sở dữ liệu
        $tenantDetail->save();

        // Trả về phản hồi thành công
        return redirect()->back()->with('success', 'Thêm thông tin người thuê thành công.');
    }
    public function edit($id)
    {
        $currentUser = auth()->user();
        $currentAreaId = $currentUser->area_id;

        $tenants = Tenant::where('is_delete', 0) // Lọc tenant có is_delete = 0
        ->where(function ($query) use ($currentAreaId) {
            // Nếu area_id của người dùng là 0, lấy tất cả tenants
            if ($currentAreaId != 0) {
                // Nếu area_id không phải là 0, lọc tenants trong các nhà thuộc khu vực của người dùng
                $query->whereIn('house_id', function ($subQuery) use ($currentAreaId) {
                    $subQuery->select('house_id')
                        ->from('houses')
                        ->where('area_id', $currentAreaId); // Lọc house theo area_id của user
                });
            }
        })
            ->get();
        foreach ($tenants as $teant)
        {
            $house = House::find($teant->house_id);
            $teant->house_name=$house->name;
        }
        $tenantDetail = TenantDetail::findOrFail($id);
        return view('admin.page.tenant.detail.detail_edit', compact('tenantDetail','tenants'));
    }

    public function update(Request $request, $id)
    {
        // Validate các trường từ request
        $request->validate([
            'tenant_id' => 'required|integer',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'identity_card' => 'required|string|max:20',
            'identity_card_image.*' => 'nullable|image',
            'portrait_image' => 'nullable|image',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'leader' => 'boolean',
        ]);

        // Cập nhật thông tin người thuê
        $tenantDetail = TenantDetail::findOrFail($id);
        $tenantDetail->tenant_id = $request->tenant_id;
        $tenantDetail->full_name = $request->full_name;
        $tenantDetail->phone = $request->phone;
        $tenantDetail->email = $request->email;
        $tenantDetail->identity_card = $request->identity_card;

        // Khởi tạo mảng để lưu trữ đường dẫn các ảnh
        $images = [];
        $folderName = str_replace(' ', '_', $request->full_name);
        $baseFolderPath = public_path('/tenant_images/' . $folderName);

        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($baseFolderPath)) {
            mkdir($baseFolderPath, 0777, true);
        }

        // Xử lý nhiều file cho identity_card_image
        if ($request->hasFile('identity_card_image')) {
            foreach ($request->file('identity_card_image') as $key => $file) {
                $identityCardImageName = 'identity_card_' . $key . '.' . $file->getClientOriginalExtension();
                $file->move($baseFolderPath, $identityCardImageName);
                $images[] = 'tenant_images/' . str_replace(' ', '_', $folderName) . '/' . $identityCardImageName;
            }
            $tenantDetail->identity_card_image = json_encode($images, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        // Xử lý ảnh chân dung (portrait_image)
        if ($request->hasFile('portrait_image')) {
            $portraitImageName = 'portrait.' . $request->file('portrait_image')->getClientOriginalExtension();
            $request->file('portrait_image')->move($baseFolderPath, $portraitImageName);
            $tenantDetail->portrait_image = 'tenant_images/' . $folderName . '/' . $portraitImageName;
        }

        // Gán các giá trị còn lại cho TenantDetail
        $tenantDetail->gender = $request->gender;
        $tenantDetail->date_of_birth = $request->date_of_birth;
        $tenantDetail->leader = $request->leader ?? 0;

        // Lưu thông tin vào cơ sở dữ liệu
        $tenantDetail->save();

        // Trả về phản hồi thành công
        return redirect()->route('tenant-detail.list');
    }

    public function destroy($tenant_detail_id){
        $tenantDetail= TenantDetail::findOrFail($tenant_detail_id);
        $tenantDetail->delete();
        return redirect()->back()->with('Delete successfully');
    }




}
