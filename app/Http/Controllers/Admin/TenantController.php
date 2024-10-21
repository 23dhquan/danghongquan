<?php

namespace App\Http\Controllers\Admin;

use App\Models\House;
use App\Models\Tenant;
use App\Models\TenantDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TenantController extends Controller
{

    public function index()
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $house = House::find($tenant->house_id);
            $tenant->house_name = $house ? $house->name : 'Không có nhà';
            $user = User::find($tenant->user_id);
            $tenant->user_name = $user->name;
            $tenantDetail = TenantDetail::where('tenant_id', $tenant->tenant_id)
                ->where('leader', 1)
                ->first();
            if($tenantDetail){
                $tenant->tenant_name=$tenantDetail->full_name;
            }else{
                $tenant->tenant_name = 'Không tìm thấy tên';
            }

            $startDate = Carbon::parse($tenant->start_date);
            $endDate = Carbon::parse($tenant->end_date);
            $tenant->days_remaining = floor(Carbon::now()->diffInDays($endDate, false));
        }

        return view('admin.page.tenant.manager_tenant_listting', compact('tenants'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'house_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Lưu thông tin người thuê
        Tenant::create([
            'user_id' => $request->user_id,
            'house_id' => $request->house_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json(['message' => 'Thông tin người thuê đã được thêm thành công!'], 200);
    }
    public function destroy($tenant_id)
    {
        $tenant=Tenant::findOrFail($tenant_id);
        $tenant->delete();
        return redirect()->back()->with('success');
    }

}
