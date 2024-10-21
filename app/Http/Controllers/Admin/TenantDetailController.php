<?php

namespace App\Http\Controllers\Admin;

use App\Models\House;
use App\Models\Tenant;
use App\Models\TenantDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class TenantDetailController extends Controller
{
    public function index()
    {
        $tenantDetails = TenantDetail::all();
        foreach ($tenantDetails as $tenantDetail) {
            $tenant = Tenant::find($tenantDetail->tenant_id);
            $house_name = $tenant->house_id;
            $house = House::find($house_name);
            $tenantDetail->house_name_teantDetail = $house->name;
        }
        return view('admin.page.tenant.detail.detail_listting',compact('tenantDetails'));
    }
    public function create()
    {
        $tenants=Tenant::all();
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
        $tenants=Tenant::all();
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
