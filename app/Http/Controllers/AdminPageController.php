<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; //Phải khai báo thằng này vào đây không sẽ lỗi
use Illuminate\Support\Facades\Auth;
class AdminPageController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            Session(['module_active' => 'page']);
            return $next($request);
        });
    }
    //Xay dung module page
    function list(Request $request)
    {
        // Xu ly quyen moi cho vao
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'editpage') {
                $roles[] = 'editpage';
            }
            if ($role->namerole == 'deletepage') {
                $roles[] = 'deletepage';
            }
            if ($role->namerole == 'administrators') {
                $roles[] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép vào cập nhật hay xóa bài viết cho trang!'
            );
        }
        // xu ly tac vu cho page
        $status = request()->input('status');
        // Phan 24 bai 277 : Xoa vinh vien user
        $list_act = [
            'delete' => 'Vô hiệu hóa',
        ];
        if ($status == 'trash') {
            $list_act = [
                'restore' => 'Kích hoạt lại',
                'forceDelete' => 'Xóa vĩnh viễn',
            ];
            // $listpages = Page::onlyTrashed()->paginate(12);
            $listpages = DB::table('pages')
                ->where('disabler', '<>', 'active')
                ->paginate(20);
        } else {
            $keyword = '';
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }

            $listpages = DB::table('pages')
                ->where('disabler', '=', 'active')
                ->where('title', 'LIKE', "%{$keyword}%")
                ->paginate(20);
        }

        $count_page_active = DB::table('pages')
            ->where('disabler', '=', 'active')
            ->count();
        $count_page_trash = DB::table('pages')
            ->where('disabler', '<>', 'active')
            ->count();
        $count = [$count_page_active, $count_page_trash];
        return view(
            'admin.page.list',
            compact('listpages', 'count', 'list_act')
        );
    }
    //Xay dung module page
    function add(Request $request)
    {
        // $categorys=Page::all()->unique('category'); //hay lay ra danh muc duy nhat
        // return $categorys;
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'addpage') {
                $roles[] = 'addpage';
            }
            if ($role->namerole == 'administrators') {
                $roles[] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/page/list')->with(
                'status',
                'Bạn không được phép thêm bài viết cho trang!'
            );
        }
        return view('admin.page.pageadd');
    }
    function store(Request $request)
    {
        // $page=new Page;
        // $page=$request->all();
        // $input=$request->all();
        $request->validate(
            [
                'title' => 'required|string|min:8|max:255|unique:pages',
                'birthday' => 'required|date|min:8|max:50',
                // 'file' => 'required|image',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'content' => 'required|string|min:8|max:1000',
                'category' => 'required|max:50',
            ],
            [
                'required' => ':attribute không được để trống',
                'category.required' =>
                    ':attribute trang(giới thiệu hay liên hệ)',
                'file.required' => ':attribute ảnh bài viết cho trang',
                'string' => ':attribute phải có dạng chuỗi',
                'date' => ':attribute phải có dạng ngày tháng',
                'min' => ':attribute có độ dài ít nhât :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute đã tồn tại trong bảng pages',
                'image' => ':attribute ảnh có dạng file ảnh',
                'mimes'=>':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                'file.max'=>':attribute ảnh có dung lượng dưới 2048kb',
            ],
            [
                'title' => 'Tiêu đề bài viết của trang',
                'content' => 'Nội dung bài viết cho trang',
                'birthday' => 'Ngày tạo bài viết cho trang',
                'file' => 'Bạn phải chọn',
                'category' => 'Bạn phải chọn',
            ]
        );
        if ($request->hasFile('file')) {
            $file = $request->file;
            // Lấy tên file
            $fileName = $file->getClientOriginalName();
            //   Lay ten file khong co duoi
            // echo pathinfo($fileName, PATHINFO_FILENAME)."<br>";
            //   echo 'public/products/'.$file->getClientOriginalName();
            // Xu ly trung ten file
            if (!file_exists('public/image/pages/' . $fileName)) {
                $path = $file->move(
                    'public/image/pages',
                    $file->getClientOriginalName()
                ); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'public/image/pages/' . $fileName; //Đường dẫn của file lưu vào database
            } else {
                $newfileName = time() . '-' . $fileName;
                $path = $file->move('public/image/pages', $newfileName); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'public/image/pages/' . $newfileName; //Đường dẫn của file lưu vào database
            }
            $input['thumbnail'] = $thumbnail;
        }
        if ($request->input('category') == 1) {
            $page = 'Giới thiệu';
        } else {
            $page = 'Liên hệ';
        }

        DB::table('pages')->insert([
            'title' => $request->input('title'),
            'thumbnail' => $input['thumbnail'],
            'content' => $request->input('content'),
            'category' => $request->input('category'),
            'page' => $page,
            'birthday' => $request->input('birthday'),
            'creator' => Auth::id(),
            'disabler' => 'active',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        // Xu ly chuyen huong sau khi insert vao database
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'addpage') {
                $roles['addpage'] = 'addpage';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (!empty($roles['addpage'])) {
            return redirect('admin/page/add')->with(
                'status',
                'Thêm bài viết cho trang thành công!'
            );
        } else {
            return redirect('admin/page/list')->with(
                'status',
                'Thêm bài viết cho trang thành công!'
            );
        }
    }
    // Sua noi dung trang
    public function edit($id)
    {
        // Xu ly quyen user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'editpage') {
                $roles['addpage'] = 'editpage';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/page/list')->with(
                'status',
                'Bạn không được phép cập nhật bài viết cho trang!'
            );
        }
        // $page = Page::find($id);
        // $categorys = Page::all()->unique('category'); //hay
        $page = DB::table('pages')->find($id);
        $categorys = DB::table('pages')
            ->get()
            ->unique('category');
        return view('admin.page.pageedit', compact('categorys', 'page'));
    }
    // Cap nhat trang bai viet
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $request->validate(
            [
                'title' => 'required|string|min:8|max:255|unique:pages',
                'birthday' => 'required|date|min:8|max:50',
                // 'file' => 'required|image',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'content' => 'required|string|min:8|max:1000',
                'category' => 'required|max:50',
            ],
            [
                'required' => ':attribute không được để trống',
                'category.required' =>
                    ':attribute trang(giới thiệu hay liên hệ)',
                'file.required' => ':attribute ảnh bài viết cho trang',
                'string' => ':attribute phải có dạng chuỗi',
                'date' => ':attribute phải có dạng ngày tháng',
                'min' => ':attribute có độ dài ít nhât :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute đã tồn tại trong bảng pages',
                'image' => ':attribute ảnh có dạng file ảnh',
                'mimes'=>':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                'file.max'=>':attribute ảnh có dung lượng dưới 2048kb',
            ],
            [
                'title' => 'Tiêu đề bài viết của trang',
                'content' => 'Nội dung bài viết cho trang',
                'birthday' => 'Ngày tạo bài viết cho trang',
                'file' => 'Bạn phải chọn',
                'category' => 'Bạn phải chọn',
            ]
        );
        if ($request->hasFile('file')) {
            $file = $request->file;
            // Lấy tên file
            $fileName = $file->getClientOriginalName();
            //   Lay ten file khong co duoi
            if (!file_exists('public/image/pages/' . $fileName)) {
                $path = $file->move(
                    'public/image/pages',
                    $file->getClientOriginalName()
                ); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'public/image/pages/' . $fileName; //Đường dẫn của file lưu vào database
            } else {
                $newfileName = time() . '-' . $fileName;
                $path = $file->move('public/image/pages', $newfileName); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'public/image/pages/' . $newfileName; //Đường dẫn của file lưu vào database
            }
            $input['thumbnail'] = $thumbnail;
        }
        if ($request->input('category') == 1) {
            $pageupdate = 'Giới thiệu';
        } else {
            $pageupdate = 'Liên hệ';
        }
        // xoa file anh
        $page = DB::table('pages')->find($id);
        if (!empty($page)) {
            @unlink($page->thumbnail);
        }
        // cap nhat
        DB::table('pages')
            ->where('id', $id)
            ->update([
                'title' => $request->input('title'),
                'thumbnail' => $input['thumbnail'],
                'content' => $request->input('content'),
                'category' => $request->input('category'),
                'page' => $pageupdate,
                'birthday' => $request->input('birthday'),
                'repairer' => Auth::id(),
            ]);
        return redirect('admin/page/list')->with(
            'status',
            "Cập nhật bài viết cho trang thành công!"
        );
    }
    // vo hieu hoa bai viet cua trang
    function disable($id)
    {
        // Xu ly quyen user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deletepage') {
                $roles['deletepage'] = 'deletepage';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/page/list')->with(
                'status',
                'Bạn không được phép vô hiệu hóa bài viết cho trang!'
            );
        }
        // xu ly vo hieu hoa bai viet cho page
        DB::table('pages')
            ->where('id', $id)
            ->update([
                'disabler' => Auth::id(),
            ]);
        return redirect('admin/page/list')->with(
            'status',
            'Vô hiệu hóa bài viết cho trang thành công!'
        );
    }
    // Khoi phuc bai viet cho page
    function restore($id)
    {
        // Xu ly quyen user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deletepage') {
                $roles['deletepage'] = 'deletepage';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/page/list')->with(
                'status',
                'Bạn không được phép kích hoạt lại bài viết cho trang!'
            );
        }
        // xu ly kich hoat bai viet cho page
        DB::table('pages')
            ->where('id', $id)
            ->update([
                'disabler' => 'active',
            ]);
        return redirect('admin/page/list')->with(
            'status',
            'Kích hoạt lại bài viết cho trang thành công!'
        );
    }
    //Xoa bai viet cho page
    function delete($id)
    {
        // Xu ly quyen user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deletepage') {
                $roles['addpage'] = 'deletepage';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/page/list')->with(
                'status',
                'Bạn không được phép xóa bài viết cho trang!'
            );
        }
        // xu ly xoa bai viet
        $file_page = DB::table('pages')->find($id);
        if (file_exists($file_page->thumbnail)) {
            @unlink($file_page->thumbnail);
        }
        $page = DB::table('pages')->where('id', $id);
        $page->delete();
        return redirect('admin/page/list')->with(
            'status',
            'Xóa bài viết của trang thành công!'
        );
    }
    // Phan 24 bai 276 : Thuc hien tac vu tren nhieu ban ghi
    function action(Request $request)
    {
        // Xu ly quyen user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deletepage') {
                $roles['deletepage'] = 'deletepage';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/page/list')->with(
                'status',
                'Bạn không được phép xóa hay kích hoạt lại các bài viết của trang!'
            );
        }
        // xu ly tren nhieu ban ghi khac nhau
        $list_check = $request->input('list_check');
        if (isset($list_check)) {
            //Kiem tra $list_check da duoc tao thi
            // return $request->input('list_check');
            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == 'delete') {
                    // Cap nhat user xoa
                    DB::table('pages')
                        ->whereIn('id', $list_check)
                        ->update([
                            'disabler' => Auth::id(),
                        ]);
                    return redirect('admin/page/list')->with(
                        'status',
                        'Vô hiệu hóa các bài viết của trang thành công!'
                    );
                }
                // Khoi phuc lai bai viet
                if ($act == 'restore') {
                    DB::table('pages')
                        ->whereIn('id', $list_check)
                        ->update([
                            'disabler' => 'active',
                        ]);
                    return redirect('admin/page/list')->with(
                        'status',
                        'Bạn đã khôi phục các bài viết của trang thành công!'
                    );
                }
                // Phan 24 bai 277 : Xoa vinh vien cac bai viet cua trang
                if ($act == 'forceDelete') {
                    // Xoa file anh
                    $test = DB::table('pages')
                        ->whereIn('id', $list_check)
                        ->get();
                    foreach ($test as $item) {
                        @unlink($item->thumbnail);
                    }
                    // Xoa trang khoi database
                    DB::table('pages')
                        ->whereIn('id', $list_check)
                        ->delete();
                    return redirect('admin/page/list')->with(
                        'status',
                        'Bạn đã xõa vĩnh viễn các bài viết của trang thành công!'
                    );
                }
            }
            return redirect('admin/page/list')->with(
                'status',
                'Bạn phải chọn hình thức vô hiệu hóa, xóa vĩnh viễn hoặc kích hoạt lại!'
            );
        } else {
            return redirect('admin/page/list')->with(
                'status',
                'Bạn cần chọn các phần tử cần thực hiện!'
            );
        }
    }
}
