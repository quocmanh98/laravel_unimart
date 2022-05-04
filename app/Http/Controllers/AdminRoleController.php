<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; //Phải khai báo thằng này vào đây không sẽ lỗi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\role;
class AdminRoleController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            Session(['module_active' => 'role']);
            return $next($request);
        });
        // Quyen truy cap cua user:lam bang middleware nay ko on
        // $this->middleware(function($request,$next){
        //     foreach(Auth::user()->roles as $role){
        //         if( $role->namerole!='administrators'){
        //             return redirect('admin')->with('status','Bạn không được phép truy cập vào trang phân quyền user!');
        //         }
        //     }
        //      return $next($request);
        // });
    }

    function listuser(Request $request)
    {
        // Xu ly quyen user cho vao
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép vào trang phân quyền user!'
            );
        }
        //  xu ly hien view quyen cua cac user
        $keyword = '';
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
        }
        $users = User::where('name', 'LIKE', "%{$keyword}%")->get();
        $roles = role::all();
        // return count($rolesuser);
        return view('admin.role.listuser', compact('users', 'roles'));
    }
    function add()
    {
        // Xu ly quyen user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép vào trang phân quyền uesr!'
            );
        }
        //  xu ly hien view va them quyen cho user
        $users = User::all();
        // $roleadmintrator= DB::table('roles')->select('administrators')->where('administrators','!=','null')->get()->unique('administrators');
        // Lay gia tri duy nhat va co them cac truong trong query buider
        // $roleAll=[$roleadmintrator,$roleaddrole,$roleeditrole,$roledeleterole];
        // Noi ket qua tra ve
        // $roleAll = $roleadmintrator->merge($roleaddrole)->merge($roleeditrole)->merge($roledeleterole);
        // $roleadmintrator= DB::table('roles')->select('administrators')->where('administrators','!=','null')->get()->unique('administrators');

        // Lay danh sach quyen duy nhat tren truong namerole
        $roles = DB::table('roles')
            ->select('namerole')
            ->get()
            ->unique('namerole');
        return view('admin.role.add', compact('users', 'roles'));
    }
    function storeaddrole(Request $request)
    {
        // Xu ly quyen user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép vào trang thêm quyền uesr!'
            );
        }
        //  xu ly them quyen vao he thong cho user
        $input = $request->all();
        // return $input;
        $request->validate(
            [
                'user' => 'required',
                'namerole' => 'required|min:5|max:50',
            ],
            [
                'required' => ':attribute tên quyền',
                'user.required' => ':attribute tên user',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
            ],
            [
                'user' => 'Bạn phải chọn',
                'namerole' => 'Bạn phải chọn',
            ]
        );
        // Xư lý trương hop user da co quyen nay roi thi ko cho insert vao database
        $roles = role::where('user_id', '=', $request->input('user'))
            ->where('namerole', '=', $request->input('namerole'))
            ->get();
        if (count($roles) == 0) {
            // Insert user vao he thong
            role::create([
                'namerole' => $request->input('namerole'),
                'user_id' => $request->input('user'),
            ]);
            $nameuser = user::find($request->input('user'))->name;
            return redirect('admin/role/add')->with(
                'status',
                "Đã thêm quyền cho user : {$nameuser} thành công!"
            );
        } else {
            return redirect('admin/role/add')->with(
                'status',
                'Quyền của user này đã tồn tại trong hệ thống!'
            );
        }
    }
    // Sua quyen thanh vien
    function editrole($id)
    {
        // Xu ly quyen user
        $rolesuser = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'administrators') {
                $rolesuser['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($rolesuser)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép vào trang cập nhật quyền uesr!'
            );
        }
        //  xu ly sua quyen
        $roles = DB::table('roles')
            ->select('namerole')
            ->get()
            ->unique('namerole');
        $role = role::find($id);
        $user = User::find($role->user_id);
        return view('admin.role.editrole', compact('roles', 'user', 'role'));
    }
    // Sua quyen thanh vien
    function updaterole(Request $request, $id)
    {
        // Xu ly quyen user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép vào trang cập nhật quyền uesr!'
            );
        }
        // Cap nhat lai quyen vao he thong cho user
        $request->validate(
            [
                'namerole' => 'required|min:5|max:50',
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
            ],
            [
                'namerole' => 'Tên quyền',
            ]
        );
        // Cap nhat lai quyen vao he thong cho user
        $role = role::find($id);
        $roles = role::where('user_id', '=', $request->input('user'))
            ->where('namerole', '=', $request->input('namerole'))
            ->get();
        if (count($roles) == 0) {
            // Insert user vao he thong
            $role->update([
                'namerole' => $request->input('namerole'),
                'user_id' => $request->input('user'),
            ]);
            return redirect('admin/role/listuser')->with(
                'status',
                'Cập nhật quyền cho user thành công!'
            );
        } else {
            return redirect('admin/role/listuser')->with(
                'status',
                'Quyền của user này đã tồn tại trong hệ thống!'
            );
        }
    }
    // Xoa quyen
    function deleterole($id)
    {
        // Xu ly quyen user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép vào xóa quyền uesr!'
            );
        }
        $role = role::find($id)->delete();
        return redirect('admin/role/listuser')->with(
            'status',
            'Xóa quyền của user thành công!'
        );
    }
}
