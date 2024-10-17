<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        return view('admin.page.user.user_lissting',compact('user'));
    }
    public function create()
    {
        $areas  = Area::all();
        return view('admin.page.user.add_user', compact('areas'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'note' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'area_id' => 'required|integer',
        ]);

        // Rút gọn phần tạo người dùng
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'note' => $request->note,
            'role' => $request->role,
            'area_id' => $request->area_id,
        ]);

        return redirect()->route('user.list')->with('success', 'User created successfully!');
    }

    public function destroy($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->delete();
        return redirect()->back()->with('user_id', 'user deleted successfully');
    }
    public function edit($user_id)
    {
        $users= User::findOrFail($user_id);
        $areas = Area::all();
        return view('admin.page.user.edit_user', compact('users','areas'));
    }
    public function update(Request $request, $user_id)
    {
        $users = User::findOrFail($user_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user_id.'́,user_id',
            'password' => 'nullable|string|min:6|confirmed',
            'note' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'area_id' => 'required|integer',
        ]);

        // Cập nhật thông tin người dùng
        $users->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $users->password, // Giữ mật khẩu cũ nếu không nhập mới
            'note' => $request->note,
            'role' => $request->role,
            'area_id' => $request->area_id,
        ]);

        return redirect()->route('user.list')->with('success', 'User updated successfully!');
    }

    public function toggleStatus($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $user->status = !$user->status; // Đảo trạng thái
        $user->save();

        return response()->json(['success' => true, 'status' => $user->status]); // Trả về trạng thái mới
    }

    public function resetPassword(Request $request)
    {
        // Kiểm tra người dùng đã đăng nhập hay chưa
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Người dùng chưa đăng nhập.'], 401);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Mật khẩu đã được cập nhật.']);
    }


}
