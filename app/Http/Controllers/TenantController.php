<?php

namespace App\Http\Controllers;

use App\Models\TenantDetail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TenantController extends Controller
{
    public function index()
    {
        $tenantDetails = TenantDetail::all();
        return view('admin.page.tenant.detail.detail_listting', compact('tenantDetails'));
    }

}
