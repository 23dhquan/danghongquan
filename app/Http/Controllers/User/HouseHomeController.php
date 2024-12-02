<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Contact;
use App\Models\House;
use App\Models\HouseImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HouseHomeController extends Controller
{
    public function index()
    {
        $houses = House::where('is_rented', 0)->get();
        foreach ($houses as $house) {
            $houseImage = HouseImage::where('house_id', $house->house_id)->first();
            $house->house_image = $houseImage ? $houseImage->image_path : null;


        }

        return view('home', compact('houses'));
    }
    public function house()
    {
        $houses = House::where('is_rented', 0)->get();

        foreach ($houses as $house) {
            $houseImage = HouseImage::where('house_id', $house->house_id)->first();
            $house->house_image = $houseImage ? $houseImage->image_path : null;
            $house->area_name = $house->getAreaName();
            $house->area_address = $house->getAreaAddress();


        }

        return view('house', compact('houses'));
    }
    public function contact()
    {
        return view('contact');
    }

    public function storeContact(Request $request)
    {
        Contact::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'title' => $request->input('title'),
            'message' => $request->input('message'),
        ]);

        return redirect()->back()->with('success', 'Đã gửi thành công!');
    }



    public function show($house_id)
    {
        $house = House::with('images')->where('house_id', $house_id)->first();
        $house->area_name = $house->getAreaName();
        $house->area_address = $house->getAreaAddress();
        $house->user_name = User::where('area_id', $house->area_id)->first()?->name;
        $house->user_note = User::where('area_id', $house->area_id)->first()?->note;
        $house->user_avatar = User::where('area_id', $house->area_id)->first()?->avatar;
        $house->user_email = User::where('area_id', $house->area_id)->first()?->email;


        return view('house-detail', compact('house'));
    }

}
