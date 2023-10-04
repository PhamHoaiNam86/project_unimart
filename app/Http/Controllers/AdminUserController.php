<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    //
    function list(Request $request)
    {
        $keyword = "";
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
        }
        $users = User::where('name', 'LIKE', "%{$keyword}%")->paginate(10);

        return view('admin.user.list', compact('users'));
    }


    function add()
    {
        return view('admin.user.add');
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',

            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có độ dài ít nhất là :min kí tự',
                'max' => ':attribute có độ dài lớn nhất là :max kí tự',
                // 'confirmed' => 'xác nhận mật khẩu không thành công',
            ],
            [
                'name' => 'Tên người dùng',
                'email' => 'Email',
                'password' => 'Mật khẩu'
            ]
        );
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),

        ]);

            return redirect('admin/user/list')->with('status', 'Đã thêm tài khoản thành công');
    }

    function delete($id){
        if(Auth::id()!=$id){
            $user = User::find($id);
            $user->delete($id);
            return redirect('admin/user/list')->with('status', 'Đã xoá tài khoản thành công');
        }
    }
}
