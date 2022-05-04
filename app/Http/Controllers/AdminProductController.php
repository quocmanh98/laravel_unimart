<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\product;
use App\productcat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; //Phải khai báo thằng này vào đây không sẽ lỗi
class AdminProductController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Su dung middleware toi uu cho active module_active
            //  Su dung middleware de rang buoc cai session khi di vao moi module, sesion se duoc thay doi theo thiet lap o moi module
            // Neu khong co middleware thi session luon lay cai dau tien la dashbord->khong dung yeu cau bai toan dat ra->HAY
            Session(['module_active' => 'product']);
            return $next($request);
        });
    }

    //Xay dung module product
    // action danh muc san pham
    function listproduct(Request $request)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'editproduct') {
                $roles['editproduct'] = 'editproduct';
            }
            if ($role->namerole == 'deleteproduct') {
                $roles['deleteproduct'] = 'deleteproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép truy cập vào trang thêm danh mục sản phẩm!'
            );
        }
        // xu ly hien view danh sach san pham
        $status = request()->input('status');
        $list_act = [
            'delete' => 'Vô hiệu hóa',
        ];
        if ($status == 'trash') {
            $list_act = [
                'restore' => 'Kích hoạt',
                'forceDelete' => 'Xóa vĩnh viễn',
            ];
            // $listproducts = product::onlyTrashed()->paginate(16);
            $listproducts = product::where(
                'disabler',
                '<>',
                'active'
            )->paginate(16);
        } else {
            $keyword = '';
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $listproducts = product::where('name', 'LIKE', "%{$keyword}%")
                ->where('disabler', '=', 'active')
                ->paginate(16);
        }

        $count_product_active = product::where(
            'disabler',
            '=',
            'active'
        )->count();
        // $count_product_active = product::count();
        $count_product_trash = product::where(
            'disabler',
            '<>',
            'active'
        )->count();
        $count = [$count_product_active, $count_product_trash];
        return view(
            'admin/product/listproduct',
            compact('listproducts', 'count', 'list_act')
        );
    }
    // Them mau sac san pham
    function addcolorproduct()
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'editproduct') {
                $roles['editproduct'] = 'editproduct';
            }
            if ($role->namerole == 'addproduct') {
                $roles['addproduct'] = 'addproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép truy cập vào trang thêm màu sản phẩm!'
            );
        }
        $colors = DB::table('product_colors')->get();
        return view('admin.product.add-color-product', compact('colors'));
    }
    // Xu ly them mau sac san pham
    function storeaddcolorproduct(Request $request)
    {
        // return request()->all();
        // return request()->namecolor;
        $request->validate(
            [
                'namecolor' => 'required|max:50|unique:product_colors',
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute đã tồn tại trong bảng product_colors',
            ],
            [
                'namecolor' => 'Màu sản phẩm',
            ]
        );
        DB::table('product_colors')->insert([
            'namecolor' => request()->namecolor,
            'creator' => Auth::id(),
            'disabler' => 'active',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        return redirect('admin/product/addcolorproduct')->with(
            'status',
            'Thêm màu sản phẩm thành công!'
        );
    }
    // Edit mau san pham
    function editcolorproduct($id)
    {
        $color = DB::table('product_colors')->find($id);
        $colors = DB::table('product_colors')->get();
        return view(
            'admin.product.editcolorproduct',
            compact('color', 'colors')
        );
    }
    // Cap nhat mau san pham
    function updatecolorproduct(Request $request, $id)
    {
        $request->validate(
            [
                'namecolor' => 'required|max:50|unique:product_colors',
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute đã tồn tại trong bảng product_colors',
            ],
            [
                'namecolor' => 'Màu sản phẩm',
            ]
        );
        DB::table('product_colors')
            ->where('id', '=', $id)
            ->update([
                'namecolor' => request()->namecolor,
                'repairer' => Auth::id(),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        return redirect('admin/product/addcolorproduct')->with(
            'status',
            'Cập nhật màu sản phẩm thành công!'
        );
    }
    // Xoa vinh vien mau san pham
    function deletecolorproduct($id)
    {
        DB::table('product_colors')
            ->where('id', '=', $id)
            ->delete();
        return redirect('admin/product/addcolorproduct')->with(
            'status',
            'Xóa vĩnh viễn màu sản phẩm thành công!'
        );
    }
    // Them hang san pham
    function add_company_product()
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'editproduct') {
                $roles['editproduct'] = 'editproduct';
            }
            if ($role->namerole == 'addproduct') {
                $roles['addproduct'] = 'addproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép truy cập vào trang thêm hãng sản phẩm!'
            );
        }
        $companys = DB::table('product_company')->get();
        return view('admin.product.add-company-product', compact('companys'));
    }
    // Xu ly them hang san pham
    function storeaddcompanyproduct(Request $request)
    {
        // return request()->all();
        // return request()->namecolor;
        $request->validate(
            [
                'namecompany' => 'required|max:50|unique:product_company',
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute đã tồn tại trong bảng product_company',
            ],
            [
                'namecompany' => 'Hãng sản phẩm',
            ]
        );
        DB::table('product_company')->insert([
            'namecompany' => request()->namecompany,
            'creator' => Auth::id(),
            'disabler' => 'active',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        return redirect('admin/product/add_company_product')->with(
            'status',
            'Thêm hãng sản phẩm thành công!'
        );
    }
    // Edit hang san pham
    function edit_company_product($id)
    {
        $company = DB::table('product_company')->find($id);
        $companys = DB::table('product_company')->get();
        return view(
            'admin.product.edit_company_product',
            compact('company', 'companys')
        );
    }
    // Cap nhat hang san pham
    function update_company_product(Request $request, $id)
    {
        $request->validate(
            [
                'namecompany' => 'required|max:50|unique:product_company',
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute đã tồn tại trong bảng product_company',
            ],
            [
                'namecompany' => 'Hãng sản phẩm',
            ]
        );
        DB::table('product_company')
            ->where('id', '=', $id)
            ->update([
                'namecompany' => request()->namecompany,
                'repairer' => Auth::id(),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        return redirect('admin/product/add_company_product')->with(
            'status',
            'Cập nhật hãng sản phẩm thành công!'
        );
    }
    //  Xoa vinh vien hang san pham
    function delete_company_product($id)
    {
        DB::table('product_company')
            ->where('id', '=', $id)
            ->delete();
        return redirect('admin/product/add_company_product')->with(
            'status',
            'Xóa vĩnh viễn hãng sản phẩm thành công!'
        );
    }
    // Them san pham
    function addproduct()
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'addproduct') {
                $roles['addproduct'] = 'addproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép thêm sản phẩm!'
            );
        }
        // xu ly hien thi view them san pham
        $catproducts = productcat::where('disabler', '=', 'active')->get();
        $companys = DB::table('product_company')->get();
        // return $company;
        $colors = DB::table('product_colors')->get();
        return view(
            'admin.product.addproduct',
            compact('catproducts', 'companys', 'colors')
        );
    }
    function storeproduct(Request $request)
    {
        // $page=new Page;
        // $page=$request->all();
        // $input=$request->all();
        // return $request->all();
        // return $request->input('file');
        // return request()->product_speak;
        $request->validate(
            [
                'masp' => 'required|string|max:50|unique:products',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                // 'file' => 'required|image',
                'name' => 'required|string|min:3|max:100',
                'price' => 'required|max:20',
                'qty' => 'required|integer',
                'color' => 'required',
                'description' => 'required|min:8',
                'product_id' => 'required',
                'status' => 'required|string',
                'the_firm' => 'required|string|max:50',
            ],
            [
                'required' => ':attribute không được để trống',
                'product_id.required' => ':attribute danh mục sản phẩm',
                'color.required' => ':attribute màu sản phẩm',
                'the_firm.required' => ':attribute hãng sản phẩm',
                'file.required' => ':attribute ảnh sản phẩm',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute phải duy nhất',
                'image' => ':attribute ảnh có dạng file ảnh',
                'integer' => ':attribute có dạng số nguyên',
                'mimes' => ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                'file.max' => ':attribute ảnh có dung lượng dưới 2048kb',
            ],
            [
                'masp' => 'Mã sản phẩm',
                'file' => 'Phải chọn',
                'name' => 'Tên sản phẩm',
                'price' => 'Giá sản phẩm',
                'qty' => 'Số lượng',
                'color' => 'Bạn phải chọn',
                'description' => 'Mô tả sản phẩm',
                'product_id' => 'Phải chọn',
                'status' => 'Tình trạng sản phẩm',
                'the_firm' => 'Bạn phải chọn',
            ]
        );
        if ($request->hasFile('file')) {
            //    echo "Có file"."<br>";
            $file = $request->file; //Gán biến file vào $request:$request->file goi đến cái thuộc tính trong $request
            // echo $file;
            // Lấy tên file
            $fileName = $file->getClientOriginalName();
            //   echo $file->getClientOriginalName();
            //   echo "<br>";
            //   Lay ten file khong co duoi
            // echo pathinfo($fileName, PATHINFO_FILENAME)."<br>";
            //   echo 'public/products/'.$file->getClientOriginalName();
            // Lấy đuôi file
            // echo  "Duoi file : ".$file->getClientOriginalExtension()."<br>";

            // Xu ly trung ten file
            if (!file_exists('public/image/products/' . $fileName)) {
                $path = $file->move(
                    'public/image/products',
                    $file->getClientOriginalName()
                ); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'public/image/products/' . $fileName; //Đường dẫn của file lưu vào database
            } else {
                $newfileName = time() . '-' . $fileName;
                $path = $file->move('public/image/products', $newfileName); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'public/image/products/' . $newfileName; //Đường dẫn của file lưu vào database
            }

            $input['thumbnail'] = $thumbnail;
        }
        product::create([
            'masp' => $request->input('masp'),
            'thumbnail' => $input['thumbnail'],
            'name' => $request->input('name'),
            'qty' => $request->input('qty'),
            'color' => DB::table('product_colors')->find(
                $request->input('color')
            )->namecolor,
            'status' => $request->input('status'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'the_firm' => DB::table('product_company')->find(
                $request->input('the_firm')
            )->namecompany,
            'product_speak' => $request->input('product_speak'),
            'product_selling' => $request->input('product_selling'),
            'creator' => Auth::id(),
            'disabler' => 'active',
            'productcat_id' => $request->input('product_id'),
        ]);

        // Xu ly chuyen huong sau khi insert vao database
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'addproduct') {
                $roles['addproduct'] = 'addproduct';
                return redirect('admin/product/addproduct')->with(
                    'status',
                    'Thêm sản phẩm thành công!'
                );
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
                return redirect('admin/product/listproduct')->with(
                    'status',
                    'Thêm sản phẩm thành công!'
                );
            }
        }
        return redirect('admin/product/listproduct')->with(
            'status',
            'Thêm sản phẩm thành công!'
        );
    }

    // Edit san pham
    function editproduct($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'editproduct') {
                $roles['editproduct'] = 'editproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/product/listproduct')->with(
                'status',
                'Bạn không được phép cập nhật sản phẩm!'
            );
        }
        $product = product::find($id);
        $productcats = productcat::where('disabler', '=', 'active')->get();
        $companys = DB::table('product_company')->get();
        // return $company;
        $colors = DB::table('product_colors')->get();
        return view(
            'admin/product/editproduct',
            compact('product', 'productcats', 'companys', 'colors')
        );
    }
    function updateproduct(Request $request, $id)
    {
        $request->validate(
            [
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                // 'file' => 'required|image',
                'name' => 'required|string|min:3|max:100',
                'price' => 'required|max:20',
                'qty' => 'required|integer',
                'color' => 'required',
                'description' => 'required|min:8',
                'product_id' => 'required',
                'status' => 'required|string',
                'the_firm' => 'required|string|max:50',
            ],
            [
                'required' => ':attribute không được để trống',
                'product_id.required' => ':attribute danh mục sản phẩm',
                'color.required' => ':attribute màu sản phẩm',
                'the_firm.required' => ':attribute hãng sản phẩm',
                'file.required' => ':attribute ảnh sản phẩm',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute phải duy nhất',
                'image' => ':attribute ảnh có dạng file ảnh',
                'integer' => ':attribute có dạng số nguyên',
                'mimes' => ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                'file.max' => ':attribute ảnh có dung lượng dưới 2048kb',
            ],
            [
                'masp' => 'Mã sản phẩm',
                'file' => 'Phải chọn',
                'name' => 'Tên sản phẩm',
                'price' => 'Giá sản phẩm',
                'qty' => 'Số lượng',
                'color' => 'Bạn phải chọn',
                'description' => 'Mô tả sản phẩm',
                'product_id' => 'Phải chọn',
                'status' => 'Tình trạng sản phẩm',
                'the_firm' => 'Bạn phải chọn',
            ]
        );
        if ($request->hasFile('file')) {
            $file = $request->file; //Gán biến file vào $request:$request->file goi đến cái thuộc tính trong $request
            // echo $file;
            // Lấy tên file
            $fileName = $file->getClientOriginalName();
            //   echo $file->getClientOriginalName();
            if (!file_exists('public/image/products/' . $fileName)) {
                $path = $file->move(
                    'public/image/products',
                    $file->getClientOriginalName()
                ); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'public/image/products/' . $fileName; //Đường dẫn của file lưu vào database
            } else {
                $newfileName = time() . '-' . $fileName;
                $path = $file->move('public/image/products', $newfileName); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'public/image/products/' . $newfileName; //Đường dẫn của file lưu vào database
            }
            // echo $path;
            //  $thumbnail='public/products/'.$fileName; //Đường dẫn của file lưu vào database
            //  echo $thumbnail;
            $input['thumbnail'] = $thumbnail; //Đường dẫn của file lưu vào database
        }
        // Xoa anh cu
        $path_image_product = product::find($id);
        if (file_exists($path_image_product->thumbnail)) {
            @unlink($path_image_product->thumbnail);
        }
        product::where('id', $id)->update([
            // 'masp'=>$request->input('masp'),
            'thumbnail' => $input['thumbnail'],
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'qty' => $request->input('qty'),
            'color' => DB::table('product_colors')->find(
                $request->input('color')
            )->namecolor,
            'status' => $request->input('status'),
            'description' => $request->input('description'),
            'productcat_id' => $request->input('product_id'),
            'the_firm' => DB::table('product_company')->find(
                $request->input('the_firm')
            )->namecompany,
            'product_speak' => $request->input('product_speak'),
            'product_selling' => $request->input('product_selling'),
            'repairer' => Auth::id(),
        ]);

        return redirect('admin/product/listproduct')->with(
            'status',
            'Cập nhật sản phẩm thành công!'
        );
    }
    // Vo hieu hoa san pham
    function disableproduct($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deleteproduct') {
                $roles['deleteproduct'] = 'deleteproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/product/listproduct')->with(
                'status',
                'Bạn không được phép vô hiệu hóa sản phẩm!'
            );
        }
        //  xu ly vo hieu hoa san pham
        $product = product::find($id);
        $product->update(['disabler' => Auth::id()]);
        return redirect('admin/product/listproduct')->with(
            'status',
            'Vô hiệu hóa sản phẩm thành công!'
        );
    }
    // Kich hoat lai san pham
    function restoreproduct($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deleteproduct') {
                $roles['deleteproduct'] = 'deleteproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/product/listproduct')->with(
                'status',
                'Bạn không được phép kích hoạt lại sản phẩm!'
            );
        }
        $product = product::find($id);
        $product->update(['disabler' => 'active']);
        return redirect('admin/product/listproduct')->with(
            'status',
            'Kích hoạt lại sản phẩm thành công!'
        );
    }

    // Xoa san pham
    function deleteproduct($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deleteproduct') {
                $roles['deleteproduct'] = 'deleteproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/product/listproduct')->with(
                'status',
                'Bạn không được phép xóa sản phẩm!'
            );
        }
        // xu ly xoa vinh vien san pham
        $product = product::find($id);
        // xoa file
        $file_product = $product->thumbnail;
        if (file_exists($file_product)) {
            @unlink($file_product);
        }
        $product->delete();
        return redirect('admin/product/listproduct')->with(
            'status',
            'Xóa vĩnh viễn sản phẩm thành công!'
        );
    }
    // Thuc hien tren nhieu ban ghi khac nhau
    function actionproduct(Request $request)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deleteproduct') {
                $roles['deleteproduct'] = 'deleteproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/product/listproduct')->with(
                'status',
                'Bạn không được phép xóa hay kích hoạt lại các sản phẩm!'
            );
        }
        // xu ly tren nhieu ban ghi khac nhau
        $list_check = $request->input('list_check');
        // return $list_check;
        if (isset($list_check)) {
            //Kiem tra $list_check da duoc tao thi
            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == 'delete') {
                    // Cap nhat san pham cho them user xoa vao
                    product::whereIn('id', $list_check)->update([
                        'disabler' => Auth::id(),
                    ]);
                    // Xoa tam thoi sp
                    // product::destroy($list_check);
                    return redirect('admin/product/listproduct')->with(
                        'status',
                        'Vô hiệu hóa các sản phẩm thành công!'
                    );
                }
                if ($act == 'restore') {
                    // Lay danh muc san pham duy nhat
                    $list_productcat_id = product::whereIn('id', $list_check)
                        ->get('productcat_id')
                        ->unique('productcat_id');
                    //return $list_productcat_id;
                    $list_id_productcat = [];
                    foreach ($list_productcat_id as $item) {
                        $list_id_productcat[] = $item->productcat_id;
                    }
                    //    return $list_id_productcat;
                    // Khoi phuc lai danh muc san pham truoc
                    $productcats = productcat::whereIn(
                        'id',
                        $list_id_productcat
                    )->get();
                    foreach ($productcats as $item) {
                        if ($item->disabler != 'active') {
                            $item->update(['disabler' => 'active']);
                        }
                    }
                    // Khoi phuc lai san pham
                    product::whereIn('id', $list_check)->update([
                        'disabler' => 'active',
                    ]);
                    return redirect('admin/product/listproduct')->with(
                        'status',
                        'Bạn đã khôi phục các danh mục sản phẩm và các sản phẩm thành công!'
                    );
                }
                // Phan 24 bai 277 : Xoa vinh vien san pham
                if ($act == 'forceDelete') {
                    // Xoa anh san pham
                    $products = product::whereIn('id', $list_check)->get();
                    foreach ($products as $item) {
                        if (file_exists($item->thumbnail)) {
                            @unlink($item->thumbnail);
                        }
                    }
                    // Xoa o database
                    product::whereIn('id', $list_check)->delete();
                    return redirect('admin/product/listproduct')->with(
                        'status',
                        'Bạn đã xóa vĩnh viễn các sản phẩm thành công!'
                    );
                }
            }
            return redirect('admin/product/listproduct')->with(
                'status',
                'Bạn phải chọn hình thức vô hiệu hóa, xóa vĩnh viễn hoặc khôi phục!'
            );
        } else {
            return redirect('admin/product/listproduct')->with(
                'status',
                'Bạn cần chọn phần tử cần thực hiện!'
            );
        }
    }
    // Them danh muc san pham
    function addcatproduct()
    {
        $catproducts = productcat::all();
        return view('admin.product.addcat', compact('catproducts'));
    }

    // Xu ly insert cat product vao bang danh muc san pham
    function storeaddcatproduct(Request $request)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'addcatproduct') {
                $roles['addcatproduct'] = 'addcatproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Bạn không được phép thêm danh mục sản phẩm!'
            );
        }
        // xu ly them danh muc san pham
        $input = $request->all();
        // return $request->all();
        $request->validate(
            [
                'catname' => 'required|max:50|min:3|unique:productcats',
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có độ dài ít nhât :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute đã tồn tại trong bảng productcats',
            ],
            [
                'catname' => 'Danh mục sản phẩm',
            ]
        );

        productcat::create([
            'catname' => $request->input('catname'),
            'creator' => Auth::id(),
            'disabler' => 'active',
        ]);
        return redirect('admin/product/cat/addcatproduct')->with(
            'status',
            'Thêm danh mục sản phẩm thành công!'
        );
    }

    // Edit catproduct
    function editcatproduct($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'editcatproduct') {
                $roles['editcatproduct'] = 'editcatproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Bạn không được phép cập nhật danh mục sản phẩm!'
            );
        }
        // xu ly edit danh muc san pham
        $editcatproduct = productcat::find($id);
        if ($editcatproduct->disabler != 'active') {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Bạn chỉ cập nhật được danh mục sản phẩm đang ở trạng thái kích hoạt!'
            );
        } else {
            $catproducts = productcat::all();
            return view(
                'admin/product/editcatproduct',
                compact('catproducts', 'editcatproduct')
            );
        }
    }
    // Update catproduct
    function updatecatproduct(Request $request, $id)
    {
        $input = $request->all();
        // return $request->all();
        $catname = productcat::find($id)->catname;
        $request->validate(
            [
                'catname' => 'required|max:50|min:3|unique:productcats',
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có độ dài ít nhât :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute đã tồn tại trong bảng productcats',
            ],
            [
                'catname' => 'Danh mục sản phẩm',
            ]
        );
        // Cap nhat catproduct
        productcat::where('id', $id)->update([
            'catname' => $request->input('catname'),
            'repairer' => Auth::id(),
        ]);
        return redirect('admin/product/cat/addcatproduct')->with(
            'status',
            'Cập nhật thành công danh mục sản phẩm!'
        );
    }
    // Vo hieu hoa danh muc san pham
    function disablecatproduct($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deletecatproduct') {
                $roles['editcatproduct'] = 'deletecatproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Bạn không được phép vô hiệu hóa danh mục sản phẩm!'
            );
        }
        //  xu ly vo hieu hoa danh muc san pham
        $catproduct = productcat::find($id);
        if ($catproduct->disabler != 'active') {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Danh mục sản phẩm đã vô hiệu hóa, bạn chỉ vô hiệu hóa được khi danh mục đang kích hoạt!'
            );
        } else {
            $catproduct->update(['disabler' => Auth::id()]);
            $products = product::where('productcat_id', '=', $id)->get();
            foreach ($products as $item) {
                if ($item->disabler == 'active') {
                    $item->update(['disabler' => Auth::id()]);
                }
            }
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Vô hiệu hóa danh mục sản phẩm và các sản phẩm của danh mục này thành công!'
            );
        }
    }
    // Kich hoat lai danh muc san pham
    function restorecatproduct($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deletecatproduct') {
                $roles['editcatproduct'] = 'deletecatproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Bạn không được phép khôi phục lại danh mục sản phẩm!'
            );
        }
        //  xu ly kich hoat lai danh muc san pham
        $catproduct = productcat::find($id);
        if ($catproduct->disabler == 'active') {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Danh mục này đang kích hoạt, bạn chỉ kích hoạt được khi danh mục đang vô hiệu hóa!'
            );
        } else {
            // kich hoat lai danh muc san pham
            $catproduct->update(['disabler' => 'active']);
            // kich hoat lai san pham
            $products = product::where('productcat_id', '=', $id)->get();
            // return $products;
            foreach ($products as $item) {
                if ($item->disabler != 'active') {
                    $item->update(['disabler' => 'active']);
                }
            }
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Kích hoạt danh mục sản phẩm và các sản phẩm của danh mục này thành công!'
            );
        }
    }
    // Xoa danh muc san pham
    function deletecatproduct($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'deletecatproduct') {
                $roles['editcatproduct'] = 'deletecatproduct';
            }
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Bạn không được phép xóa danh mục sản phẩm!'
            );
        }
        // xu ly xoa danh muc san pham
        // xoa sp truoc
        $products = product::where('productcat_id', '=', $id)->get();
        // xoa anh sp
        foreach ($products as $item) {
            if (file_exists($item->thumbnail)) {
                @unlink($item->thumbnail);
            }
            // xoa san pham o database
            $item->delete();
        }
        // xoa danh muc san pham sau
        $deletecatproduct = productcat::find($id)->delete();
        return redirect('admin/product/cat/addcatproduct')->with(
            'status',
            'Xóa vĩnh viễn danh mục sản phẩm và các sản phẩm của danh mục này thành công!'
        );
    }
}
