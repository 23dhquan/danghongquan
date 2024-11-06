<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\Tenant;
use Illuminate\Http\Request;

class WaterBillController extends Controller
{
    public function index(){
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            $tenant->house_name=House::find($tenant->house_id)->name;
        }
        return view('admin.page.waterAndelectricity.water_listting', compact('tenants'));
    }
}
