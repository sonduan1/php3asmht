<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    //Client
    public function getLogin()
    {
        return view('client.auth.login');
    }
    public function postLogin(Request $request)
    {
        $data = $request->only('username', 'password');
        $data = $request->validate([
            'username' => ['required'],
            'password' => ['required', 'min:6'],
        ]);
        if (Auth::attempt($data)) {
            return redirect()->route('client.index')->with('success', 'Bạn đã đăng nhập thành công !');
        } else {
            return back()->with('error', 'Username hoặc password sai !');
        }
    }

    public function getRegister()
    {
        return view('client.auth.register');
    }
    public function postRegister(PostRegisterRequest $request)
    {
        // dd($request->username);
        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password
        ];
        User::query()->create($data);
        return redirect()->route('getLogin')->with('success', 'Đăng ký thành công !');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('client.index')->with('success', 'Đăng xuất thành công !');
    }
    public function edit(User $user)
    {
        return view('client.auth.update', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username' => ['required','unique:users,username,'.$user->id, 'min:3'],
            'fullname' => ['required', 'min:3'],
            'email' => ['required','unique:users,email,'.$user->id, 'email'],
            'image' => ['nullable', 'image'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = Storage::put('users', $request->file('image'));
            if ($user->image) {
                Storage::delete($user->image);
            }
        }
        $user->update($data);
        return redirect()->route('editUser', $user)->with('success', 'Cập nhập thành công!');
    }

    //Admin
    public function listUser()
    {
        $users = User::all();
        return view('admin.users.list', compact('users'));
    }
    public function editActive(Request $request, User $user)
    {
        // dd($request->all());
        $data['active'] = $request->active;
        $user->update($data);
        return redirect()->route('admin.user')->with('success', 'Cập nhập thành công !');
    }
    public function addUser(){
        return view('admin.users.add');
    }
    public function postUser(Request $request){
        // dd($request->all());
        $data = $request->validate([
            'username'      => ['required','unique:users','min:3'],
            'email'         =>['required','unique:users','email'],
            'password'      =>['required','min:5'],
            'confirmpass'   =>['required','min:5','same:password'],
            'fullname'      =>['nullable','min:3'],
            'image'         =>['nullable','image'],
            'role'          =>['nullable'],
        ]);
        if($request->hasFile('image')){
            $data['image'] = Storage::put('users',$request->file('image'));
        }
        User::query()->create($data);
        return redirect()->route('admin.addUser')->with('success','Thêm mới thành công !');
    }   
    
}
