<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectricityBill;
use App\Models\House;
use App\Models\HouseBill;
use App\Models\HouseService;
use App\Models\Service;
use App\Models\TenantDetail;
use App\Models\WaterBill;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DasboardController extends Controller
{
    public function index(Request $request)
    {

        $loggedInUser = auth()->user();

        $year = $request->input('year', Carbon::now()->year);

        if ($loggedInUser->is_super_admin == 1) {
            $houses = House::all();
        } else {
            $houses = House::where('area_id', $loggedInUser->area_id)->get();
        }
        $monthlyTotals = [];
        $waterTotalForYear = 0;
        $electricityTotalForYear = 0;
        $houseBillTotalForYear = 0;


        for ($month = 1; $month <= 12; $month++) {
            $totalAmountForMonth = 0;

            foreach ($houses as $house) {
                $waterBillAmount = WaterBill::where('house_id', $house->house_id)
                    ->whereYear('billing_date', $year)
                    ->whereMonth('billing_date', $month)
                    ->sum('amount');
                $electricityBillAmount = ElectricityBill::where('house_id', $house->house_id)
                    ->whereYear('billing_date', $year)  // Lọc theo năm
                    ->whereMonth('billing_date', $month)  // Lọc theo tháng
                    ->sum('amount');

                $houseBillAmount = HouseBill::where('house_id', $house->house_id)
                    ->whereYear('billing_date', $year)  // Lọc theo năm
                    ->whereMonth('billing_date', $month)  // Lọc theo tháng
                    ->sum('amount');

                $totalAmountForMonth += $waterBillAmount + $electricityBillAmount + $houseBillAmount;

                $waterTotalForYear += $waterBillAmount;
                $electricityTotalForYear += $electricityBillAmount;
                $houseBillTotalForYear += $houseBillAmount;



            }

            $monthlyTotals[$month] = $totalAmountForMonth;
        }

        $yearlyTotals = [
            'water' => $waterTotalForYear,
            'electricity' => $electricityTotalForYear,
            'house_bill' => $houseBillTotalForYear,
        ];

        $services = HouseService::all();
        $totalPrice = 0;

        foreach ($services as $service) {
            $servicePrice = Service::where('service_id', $service->service_id)->first();

            if ($servicePrice) {
                $totalPrice += $servicePrice->price;
            }
        }
        $waterBillAmountTotal = 0;
        $electricityBillAmountTotal = 0;
        $houseBillAmountTotal = 0;

        foreach ($houses as $house) {
            $waterBillAmountTotal += WaterBill::where('house_id', $house->house_id)->sum('amount');

            $electricityBillAmountTotal += ElectricityBill::where('house_id', $house->house_id)->sum('amount');

            $houseBillAmountTotal += HouseBill::where('house_id', $house->house_id)->sum('amount');
        }
        $totalAmountAll = $totalPrice + $waterBillAmountTotal + $electricityBillAmountTotal + $houseBillAmountTotal;

        $toltalTenant = TenantDetail::count();
        $toltalHouse = House::count();
        return view('admin.dashboard', compact('monthlyTotals', 'toltalTenant' ,'toltalHouse' , 'yearlyTotals', 'year','totalPrice','totalAmountAll'));


    }
}
