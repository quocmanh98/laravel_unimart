<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\postcat;
use App\post;
use Illuminate\Support\Facades\Auth;
class AdminPostController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Su dung middleware toi uu cho active module_active
            //  Su dung middleware de rang buoc cai session khi di vao moi module, sesion se duoc thay doi theo thiet lap o moi module
            // Neu khong co middleware thi session luon lay cai dau tien la dashbord->khong dung yeu cau bai toan dat ra->HAY
            Session(['module_active' => 'post']);
            return $next($request);
        });
    }

    //Xay dung module post
    function list(Request $request)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'editpost') {
                $roles[] = 'editpost';
            }
            if ($role->namerole == 'deletepost') {
                $roles[] = 'deletepost';
            }
            if ($role->namerole == 'administrators') {
                $roles[] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép truy cập vào trang thêm bài viết!'
            );
        }
        // xu ly view danh sach bai viet
        $status = request()->input('status');
        // Phan 24 bai 277 : Xoa vinh vien user
        $list_act = [
            'delete' => 'Vô hiệu hóa',
        ];
        if ($status == 'trash') {
            $list_act = [
                'restore' => 'Kích hoạt',
                'forceDelete' => 'Xóa vĩnh viễn',
            ];
            $posts = post::where('disabler', '<>', 'active')->paginate(8);
        } else {
            // Phan 24 bai 269 : viet chuc nang tim kiem nguoi dung
            $keyword = '';
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $posts = post::where('name', 'LIKE', "%{$keyword}%")
                ->where('disabler', '=', 'active')
                ->paginate(8);
            //dd($users);//In du lieu print
            //dd($users->total());//In du lieu print
        }
        $count_user_active = post::where('disabler', '=', 'active')->count();
        $count_user_trash = post::where('disabler', '<>', 'active')->count(); //phuong thuc lay so luong cua ORM->HAY
        $count = [$count_user_active, $count_user_trash];

        return view('admin.post.list', compact('posts', 'count', 'list_act'));
    }

    // Them bai viet
    function addpost()
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'addpost') {
                $roles[] = 'addpost';
            }
            if ($role->namerole == 'administrators') {
                $roles[] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép truy cập vào trang thêm bài viết!'
            );
        }
        // xu ly view them danh muc bai viet
        $postcats = postcat::where('disabler','=','active')->get();
        return view('admin.post.postadd', compact('postcats'));
    }
    // Xu ly them bai viet
    function store(Request $request)
    {
        $input = $request->all();
        $request->validate(
            [
                'name' => 'required|string|max:255|min:8|unique:posts',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                // 'file' => 'required|image',
                'content' => 'required|string|min:8|max:1000',
                'postcat' => 'required|max:20',
            ],
            [
                'required' => ':attribute không được để trống',
                'file.required' => ':attribute ảnh bài viết',
                'postcat.required' => ':attribute danh mục bài viết',
                'min' => ':attribute có độ dài ít nhât :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'string' => ':attribute phải dạng chuỗi ký tự',
                'unique' => ':attribute phải là duy nhất',
                'image' => ':attribute ảnh có dạng file ảnh',
                'mimes'=>':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                'file.max'=>':attribute ảnh có dung lượng dưới 2048kb',
            ],
            [
                'name' => 'Tiêu đề bài viết',
                'file' => 'Phải chọn',
                // 'file' => 'Ảnh bài viết',
                'content' => 'Nội dung bài viết',
                'postcat' => 'Phải chọn',
            ]
        );

        if ($request->hasFile('file')) {
            $file = $request->file;
            // Lấy tên file
            $fileName = $file->getClientOriginalName();
            // Xu ly trung ten file
            if (!file_exists('public/image/posts/' . $fileName)) {
                $path = $file->move(
                    'public/image/posts',
                    $file->getClientOriginalName()
                ); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'public/image/posts/' . $fileName; //Đường dẫn của file lưu vào database
            } else {
                $newfileName = time() . '-' . $fileName;
                $path = $file->move('public/image/posts', $newfileName); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'public/image/posts/' . $newfileName; //Đường dẫn của file lưu vào database
            }

            $input['thumbnail'] = $thumbnail;
        }

        post::create([
            'name' => $request->input('name'),
            'thumbnail' => $input['thumbnail'],
            'content' => $request->input('content'),
            'description' => $request->input('description_post'),
            'postcat_id' => $request->postcat,
            'creator' => Auth::id(),
            'disabler' => 'active',
        ]);

        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'addpost') {
                $roles['addpost'] = 'addpost';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        //    Xu ly chuyen huong
        if (!empty($roles['addpost'])) {
            return redirect('admin/post/addpost')->with(
                'status',
                'Thêm bài viết thành công!'
            );
        }else{
            return redirect('admin/post/list')->with(
                'status',
                'Thêm bài viết thành công!'
            );
        }
    }

    // Edit bai viet
    function edit($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'editpost') {
                $roles[] = 'editpost';
            }
            if ($role->namerole == 'administrators') {
                $roles[] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/post/list')->with(
                'status',
                'Bạn không được phép cập nhật bài viết!'
            );
        } else {
        $post = post::find($id);

        $postcats = postcat::all();
        $postcat = postcat::find($post->postcat_id);
        return view('admin.post.edit', compact('post', 'postcats', 'postcat'));
        }
    }
    // Cap nhat bai viet
    public function update(Request $request, $id)
    {
        // if($request->input()){
        // dd($request->all());
        //  return $request->input();//xem tat ca
        //  return $request->all();//xem tat ca
        //  }
        $request->validate(
            [
                'name' => 'required|string|max:255|min:8|unique:posts',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                // 'file' => 'required|image',
                'content' => 'required|string|min:8|max:1000',
                'postcat' => 'required|max:20',
            ],
            [
                'required' => ':attribute không được để trống',
                'file.required' => ':attribute ảnh bài viết',
                'postcat.required' => ':attribute danh mục bài viết',
                'min' => ':attribute có độ dài ít nhât :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'string' => ':attribute phải dạng chuỗi ký tự',
                'unique' => ':attribute phải là duy nhất',
                'image' => ':attribute ảnh có dạng file ảnh',
                'mimes'=>':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                'file.max'=>':attribute ảnh có dung lượng dưới 2048kb',
            ],
            [
                'name' => 'Tiêu đề bài viết',
                'file' => 'Phải chọn',
                // 'file' => 'Ảnh bài viết',
                'content' => 'Nội dung bài viết',
                'postcat' => 'Phải chọn',
            ]
        );

        if ($request->hasFile('file')) {
            $file = $request->file;
            // Lấy tên file
            $fileName = $file->getClientOriginalName();
            // Xu ly trung ten file
            if (!file_exists('public/image/posts/' . $fileName)) {
                $path = $file->move(
                    'public/image/posts',
                    $file->getClientOriginalName()
                ); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'public/image/posts/' . $fileName; //Đường dẫn của file lưu vào database
            } else {
                $newfileName = time() . '-' . $fileName;
                $path = $file->move('public/image/posts', $newfileName); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'public/image/posts/' . $newfileName; //Đường dẫn của file lưu vào database
            }

            $input['thumbnail'] = $thumbnail;
            //  echo $input['thumbnail'];
        }
        $path_image_post = post::find($id);
        if (!empty($path_image_post)) {
            @unlink($path_image_post->thumbnail);
        }
        post::where('id', $id)->update([
            'name' => $request->input('name'),
            'thumbnail' => $input['thumbnail'],
            'content' => $request->input('content'),
            'description' => $request->input('description_post'),
            'postcat_id' => $request->postcat,
            'repairer' => Auth::id(),
        ]);
        return redirect('admin/post/list')->with(
            'status',
            'Cập nhật bài viết thành công!'
        );
    }
    // Vo hieu hoa bai viet
    function disable($id)
    {   
        // xu ly quyen user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deletepost') {
                $roles[] = 'deletepost';
            }
            if ($role->namerole == 'administrators') {
                $roles[] = 'administrators';
            }

        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/post/list')->with(
                'status',
                'Bạn không được phép vô hiệu hóa bài viết!'
            );
        }
        // xu ly vo hieu hoa bai viet
        $post = post::find($id)->update(['disabler' => Auth::id()]);
        return redirect('admin/post/list')->with(
            'status',
            'Vô hiệu hóa bài viết thành công!'
        );
    }
    // Kich hoat lai bai viet
    function restore($id)
    {
        // xu ly quyen user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deletepost') {
                $roles[] = 'deletepost';
            }
            if ($role->namerole == 'administrators') {
                $roles[] = 'administrators';
            }

        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/post/list')->with(
                'status',
                'Bạn không được phép kích hoạt lại bài viết!'
            );
        }
        // xu ly kich hoat bai viet
        $post = post::find($id)->update(['disabler' => 'active']);
        return redirect('admin/post/list')->with(
            'status',
            'Kích hoạt bài viết thành công!'
        );
    }
    // Xoa bai viet
    function delete($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deletepost') {
                $roles[] = 'deletepost';
            }
            if ($role->namerole == 'administrators') {
                $roles[] = 'administrators';
            }

        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/post/list')->with(
                'status',
                'Bạn không được phép xóa bài viết!'
            );
        }
        $post = post::find($id)->update([
            'eraser' => Auth::id(),
        ]);
        $post = post::find($id);
        if (file_exists($post->thumbnail)) {
            @unlink($post->thumbnail);
        }
        $post->delete(); //phuong thuc xoa cua ELEQUENT ORM
        return redirect('admin/post/list')->with(
            'status',
            'Xóa vĩnh viễn bài viết thành công!'
        );
    }
    // Thuc hien tac vu tren nhieu ban ghi cung 1 luc
    function actionpost(Request $request)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deletepost') {
                $roles[] = 'deletepost';
            }
            if ($role->namerole == 'administrators') {
                $roles[] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/post/list')->with(
                'status',
                'Bạn không được phép thực hiện xóa hay kích hoạt lại các bài viết!'
            );
        }

        $list_check = $request->input('list_check');
        // <input type="checkbox" name ="list_check[]" value={{$post->id}}>
        // name ="list_check[]" : quy tac dat ten cho checkbox trong php
        // $list_check=$request->all();
        // return $list_check;

        if (isset($list_check)) {
            //Kiem tra $list_check da duoc tao thi
            // return $request->input('list_check');
            if (!empty($list_check)) {
                // return $list_check;
                $act = $request->input('act');
                if ($act == 'delete') {
                    $posts = post::whereIn('id', $list_check)->get();
                    // Cap nhat bai viet cho them user xoa vao
                    foreach ($posts as $item) {
                        $item->update(['disabler' => Auth::id()]);
                    }
                    return redirect('admin/post/list')->with(
                        'status',
                        'Vô hiệu hóa các bài viết thành công!'
                    );
                }
                if ($act == 'restore') {
                    // Lay danh sach postcat_id duy nhat
                    $list_postcat_id = post::whereIn('id', $list_check)
                        ->get('postcat_id')
                        ->unique('postcat_id');
                    // return $list_postcat_id;
                    // Nap mang $list_id_postcat
                    $list_id_postcat = [];
                    foreach ($list_postcat_id as $item) {
                        $list_id_postcat[] = $item->postcat_id;
                    }
                    // return $list_id_postcat;
                    // Tim cac danh muc bai viet theo $list_id_postcat
                    $postcats = postcat::whereIn('id', $list_id_postcat)->get();
                    // return $postcats;
                    // Kich hoat lai nhung danh muc dang bi vo hieu hoa de dam bao lien ket du lieu
                    foreach ($postcats as $item) {
                        if ($item->disabler != 'active') {
                            $item->update(['disabler' => 'active']);
                        }
                    }
                    // Kich hoat lai cac bai viet
                    $posts = post::whereIn('id', $list_check)->get();
                    foreach ($posts as $item) {
                        $item->update(['disabler' => 'active']);
                    }
                    return redirect('admin/post/list')->with(
                        'status',
                        'Kích hoạt các bài viết và danh mục bài viết liên quan thành công!'
                    );
                }
                // Xoa vinh vien cac bai viet
                if ($act == 'forceDelete') {
                    // Xoa file anh
                    $posts = post::whereIn('id', $list_check)->get();
                    foreach ($posts as $item) {
                        if (file_exists($item->thumbnail)) {
                            @unlink($item->thumbnail);
                        }
                    }
                    // Xoa vinh vien o database
                    foreach ($posts as $item) {
                        $item->delete();
                    }
                    return redirect('admin/post/list')->with(
                        'status',
                        'Xóa vĩnh viễn các bài viết thành công!'
                    );
                }
            }
            return redirect('admin/post/list')->with(
                'status',
                'Bạn phải chọn hình thức vô hiệu hóa, xóa vĩnh viễn hoặc khôi phục'
            );
        } else {
            return redirect('admin/post/list')->with(
                'status',
                'Bạn cần chọn phần tử cần thực hiện'
            );
        }
    }
    // Them danh muc bai viet
    function addcat()
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'addcatpost') {
                $roles[] = 'addcatpost';
            }
            if ($role->namerole == 'editcatpost') {
                $roles[] = 'editcatpost';
            }
            if ($role->namerole == 'deletecatpost') {
                $roles[] = 'deletecatpost';
            }
            if ($role->namerole == 'administrators') {
                $roles[] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/post/cat/addcat')->with(
                'status',
                'Bạn không được phép truy cập vào trang thêm danh mục bài viết!'
            );
        }
        // xu ly hien view addcat
        $catposts = postcat::all();
        // return $catposts;
        return view('admin.post.addcat', compact('catposts'));
    }
    // Xu ly them danh muc
    function storeaddcat(Request $request)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'addcatpost') {
                $roles[] = 'addcatpost';
            }
            if ($role->namerole == 'administrators') {
                $roles[] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/post/cat/addcat')->with(
                'status',
                'Bạn không được phép truy cập vào thêm danh mục bài viết!'
            );
        }
        // xu ly them danh muc bai viet
        $request->validate(
            [
                'name' => 'required|string|max:200|min:5|unique:postcats',
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có độ dài ít nhât :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'string' => ':attribute phải dạng chuỗi ký tự',
                'unique' => ':attribute đã tồn tại trong bảng postcats',
            ],
            [
                'name' => 'Danh mục bài viết',
            ]
        );
        postcat::create([
            'name' => $request->input('name'),
            'creator' => Auth::id(),
            'disabler' => 'active',
        ]);
        return redirect('admin/post/cat/addcat')->with(
            'status',
            'Thêm danh mục bài viết thành công!'
        );
    }
    // Chinh sua danh muc bai viet
    function editcat($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'editcatpost') {
                $roles[] = 'editcatpost';
            }
            if ($role->namerole == 'administrators') {
                $roles[] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/post/cat/addcat')->with(
                'status',
                'Bạn không được phép truy cập vào trang cập nhật danh mục bài viết!'
            );
        }
        // xu ly hien thi view edit danh muc
        $editcat = postcat::find($id);
        if ($editcat->disabler != 'active') {
            return redirect('admin/post/cat/addcat')->with(
                'status',
                'Danh mục đang vô hiệu hóa, bạn chỉ vô hiệu hóa được khi danh mục mục đang kích hoạt!'
            );
        } else {
            $catposts = postcat::all();
            return view('admin.post.editcat', compact('catposts', 'editcat'));
        }
    }
    // Cập nhat danh mục bài viết
    function updatecat(Request $request, $id)
    {
        // return $id;
        $request->validate(
            [
                'name' => 'required|string|max:55|min:5|unique:postcats',
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có độ dài ít nhât :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'string' => ':attribute phải dạng chuỗi ký tự',
                'unique' => ':attribute phải là duy nhất',
            ],
            [
                'name' => 'Danh mục bài viết',
            ]
        );
        postcat::where('id', $id)->update([
            'name' => $request->input('name'),
            'repairer' => Auth::id(),
        ]);
        return redirect('admin/post/cat/addcat')->with(
            'status',
            'Cập nhật danh mục bài viết thành công!'
        );
    }
    function disablecat($id)
    {
         // Quyen truy cap cua user
         $roles = [];
         foreach (Auth::user()->roles as $role) {
             if ($role->namerole == 'deletecatpost') {
                 $roles[] = 'deletecatpost';
             }
             if ($role->namerole == 'administrators') {
                 $roles[] = 'administrators';
             }
         }
         // return $roles;
         if (empty($roles)) {
             return redirect('admin/post/cat/addcat')->with(
                 'status',
                 'Bạn không được phép vô hiệu hóa danh mục bài viết!'
             );
         }
        //  xu ly vo hieu hoa
        $catpost = postcat::find($id);
        if ($catpost->disabler != 'active') {
            return redirect('admin/post/cat/addcat')->with(
                'status',
                'Danh mục đã vô hiệu hóa rồi, bạn chỉ vô hiệu hóa được khi danh mục đang kích hoạt!'
            );
        } else {
            $catpost->update(['disabler' => Auth::id()]);
            $posts = post::where('postcat_id', '=', $id)->get();
            foreach ($posts as $item) {
                if ($item->disabler == 'active') {
                    $item->update(['disabler' => Auth::id()]);
                }
            }
            return redirect('admin/post/cat/addcat')->with(
                'status',
                'Vô hiệu hóa danh mục bài viết và các bài viết của danh mục này thành công!'
            );
        }
    }
    // Kich hoat lai danh muc bai viet
    function activecat($id)
    {
         // Quyen truy cap cua user
         $roles = [];
         foreach (Auth::user()->roles as $role) {
             if ($role->namerole == 'deletecatpost') {
                 $roles[] = 'deletecatpost';
             }
             if ($role->namerole == 'administrators') {
                 $roles[] = 'administrators';
             }
         }
         // return $roles;
         if (empty($roles)) {
             return redirect('admin/post/cat/addcat')->with(
                 'status',
                 'Bạn không được phép kích hoạt lại danh mục bài viết!'
             );
         }
        //  xu ly kich hoat lai
        $catpost = postcat::find($id);
        if ($catpost->disabler == 'active') {
            return redirect('admin/post/cat/addcat')->with(
                'status',
                'Bạn không thể kích hoạt lại danh mục này, bạn chỉ kích hoạt được khi danh mục đang vô hiệu hóa!'
            );
        } else {
            // kich hoat lai danh muc bai viet
            $catpost->update(['disabler' => 'active']);
            // kich hoat lai bai viet
            $posts=post::where('postcat_id','=',$id)->get();
            foreach($posts as $item){
                if($item->disabler!='active'){
                    $item->update(['disabler' => 'active']);
                }
            }
            
            return redirect('admin/post/cat/addcat')->with(
                'status',
                'Kích hoạt danh mục bài viết thành công!'
            );
        }
    }
    // Xóa danh muc bai viet
    function deletecat($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deletecatpost') {
                $roles[] = 'deletecatpost';
            }
            if ($role->namerole == 'administrators') {
                $roles[] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/post/cat/addcat')->with(
                'status',
                'Bạn không được phép xóa danh mục bài viết!'
            );
        }
        // xu ly xoa danh muc bai viet
        $deletecat = postcat::find($id);
        $posts = post::where('postcat_id', '=', $id)->get();
        // xoa file anh cua bai viet
        foreach ($posts as $item) {
            if (file_exists($item->thumbnail)) {
                @unlink($item->thumbnail);
            }
            // xoa bai viet o database
            $item->delete();
        }
        // Xoa danh muc bai viet sau
        $deletecat->delete(); //phuong thuc xoa cua ELEQUENT ORM
        return redirect('admin/post/cat/addcat')->with(
            'status',
            'Xóa danh mục bài viết thành công, bên bảng posts các bài viết của danh mục này cũng xóa luôn!'
        );
    }
}
