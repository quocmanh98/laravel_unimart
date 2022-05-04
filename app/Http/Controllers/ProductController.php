<?php

namespace App\Http\Controllers;

use App\customer;
use App\order;
use Illuminate\Support\Facades\DB; //Phải khai báo thằng này vào đây không sẽ lỗi
use Illuminate\Support\Str; //Phải khai báo thư viện này vào đây mới dùng được các hàm tính chuỗi
use Illuminate\Http\Request;
use App\Mail\CustomerMail;
//Khi go lenh Mail::to( 'wedquach1981@gmail.com' )->send( new DemoMail );
// se tự hiện ra, khong hien thi danh vao
use Illuminate\Support\Facades\Mail;
//phai khai bao ca thu vien nay ko se loi
use App\product;
use App\productcat;
use App\postcat;
use App\post;

class ProductController extends Controller
{
    function index(Request $request)
    {
        // Kiem tra email
        // $parttern ="/^[A-za-z0-9]{2,32}+@([a-zA-Z0-9]{2,12})+$/";
        // $parttern ="/^[A-Za-z0-9_.]{2,32}@([a-zA-Z0-9]{2,12})(.[a-zA-Z]{2,12})+$/";
        // $email="phancuong.qt@gmail.com";
        // $email="tuanss41@gmail.com";
        // if(preg_match($parttern ,$email))
        // return "Mail bạn vừa nhập không đúng định dạng ";
        // return $test;
        //    $test= DB::table('products')
        //     ->select('name')
        //     ->distinct('the_firm')
        //     ->count();
        //     return $test;
        // cau lenh nay la dung
        $productcats_sidebar = productcat::where(
            'disabler',
            '=',
            'active'
        )->get();
        $products = product::where('disabler', '=', 'active')->get();
        // return count($products);
        $productcats = productcat::where('disabler', '=', 'active')->get();
        //  return $productcats;
        // $the_firms=product::all('the_firm')->unique('the_firm'); //hay
        // $the_firms=product::all()->unique('the_firm'); //hay
        // $the_firms=product::all()->where('productcat_id','=',9)
        // ->unique('the_firm'); //hay
        // $the_firms=product::where('productcat_id','=',9)
        // ->get()->unique('the_firm'); //hay

        // return count($the_firms);
        // return $the_firms;
        $the_firms = DB::table('products')
            // ->select('the_firm','productcat_id')
            ->select('the_firm', 'productcat_id')
            ->where('disabler', '=', 'active')
            ->distinct('the_firm')
            ->get();
        // return count($the_firms);
        // San pham noi bat
        $listproductspeeks = product::where('product_speak', '<>', '')
            ->where('disabler', '=', 'active')
            ->get();
        // slider anh
        $listsliders = DB::table('sliders')
            ->where('disabler', '=', 'active')
            ->get();
        // return $listsliders;
        // San pham ban chay
        $listproductsellings = product::where('product_selling', '<>', '')
            ->where('disabler', '=', 'active')
            ->get();
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        $keyword = '';
        $id = '';
        $the_firm = '';
        if (Request()->all()) {
            // Loc theo keyword
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
                // return $keyword;
                $products = product::where('name', 'LIKE', "%{$keyword}%")
                    ->where('disabler', '=', 'active')
                    ->get();
                $list_productcat_id = DB::table('products')
                    // ->select('the_firm','productcat_id')
                    ->select('productcat_id')
                    ->where('disabler', '=', 'active')
                    ->where('name', 'LIKE', "%{$keyword}%")
                    ->distinct('productcat_id')
                    ->get();
                $list_id = [];
                foreach ($list_productcat_id as $customer) {
                    $list_id[] = $customer->productcat_id;
                }
                // return $list_id;
                $productcats = productcat::whereIn('id', $list_id)
                    ->where('disabler', '=', 'active')
                    ->get();
                // return $productcats;
            }
            if (Request()->id) {
                // Loc theo Danh muc san phams
                $id = Request()->id;
                $productcats = productcat::where('id', '=', $id)
                    ->where('disabler', '=', 'active')
                    ->get();
                // return $productcats;
                $products = product::where('productcat_id', '=', $id)
                    ->where('disabler', '=', 'active')
                    ->get();
                //  return $products;
            }
            if (Request()->the_firm) {
                // Loc theo hang san phams
                $the_firm = Request()->the_firm;
                // return $productcats;
                $products = product::where('the_firm', '=', $the_firm)
                    ->where('disabler', '=', 'active')
                    ->get();
                //  return $products;
                $list_productcat_id = DB::table('products')
                    // ->select('the_firm','productcat_id')
                    ->select('productcat_id')
                    ->where('disabler', '=', 'active')
                    ->where('the_firm', '=', $the_firm)
                    ->distinct('productcat_id')
                    ->get();
                $list_id = [];
                foreach ($list_productcat_id as $customer) {
                    $list_id[] = $customer->productcat_id;
                }
                // return $list_id;
                $productcats = productcat::whereIn('id', $list_id)
                    ->where('disabler', '=', 'active')
                    ->get();
                // return count($productcats);
            }
        }
        return view(
            'index',
            compact(
                'productcats_sidebar',
                'products',
                'productcats',
                'listproductspeeks',
                'listsliders',
                'listproductsellings',
                'the_firms',
                'banners'
            )
        );
    }

    function product(Request $request)
    {
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        $select = request()->select;
        // echo $select;
        // $product=productcat::find(12)->products;  //Tim theo elequent relationship
        // return $product;
        $products = product::where('disabler', '=', 'active')->paginate(12);
        $count_product = count($products);
        // $posts=post::paginate(5);
        $productcats = productcat::where('disabler', '=', 'active')->get();
        // $the_firms=product::all()->unique('the_firm'); //hay nhung thieu ban ghi, chac chi ap dung vao TH khac, tim hieu them sau
        $count_product_all = count(
            product::where('disabler', '=', 'active')->get()
        );
        // the_firm_for_menu_slidebar
        $the_firm_for_menu_slidebar = $the_firms = DB::table('products')
            ->where('disabler', '=', 'active')
            ->select('the_firm', 'productcat_id')
            ->distinct('the_firm')
            ->get();
        $the_firms = DB::table('products')
            ->where('disabler', '=', 'active')
            ->select('the_firm')
            ->distinct('the_firm')
            ->get();
        $keyword = '';
        $fillter = '';
        $id = '';
        $the_firm = '';
        $price = '';
        $brand = '';
        $species = '';
        if (Request()->all()) {
            // Loc theo keyword
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
                // return $keyword;
                $products = product::where('name', 'LIKE', "%{$keyword}%")
                    ->where('disabler', '=', 'active')
                    ->paginate(12);
                $count_product = count($products);
            } elseif (Request()->id) {
                // Loc theo Danh muc san phams
                $id = Request()->id;
                // return $catname;
                $products = product::where('productcat_id', '=', $id)
                    ->where('disabler', '=', 'active')
                    ->paginate(12);
                $count_product = count($products);
            } elseif (Request()->the_firm) {
                // Hang san pham
                $the_firm = Request()->the_firm;
                $products = product::where('the_firm', '=', $the_firm)
                    ->where('disabler', '=', 'active')
                    ->paginate(12);
                $count_product = count($products);
            } elseif (request()->select) {
                // Loc theo sap xep
                $fillter = request()->select;
                if ($fillter == 0) {
                    $products = product::where(
                        'disabler',
                        '=',
                        'active'
                    )->paginate(12);
                    $count_product = count($products);
                }
                if ($fillter == 1) {
                    $products = product::where('disabler', '=', 'active')
                        ->orderBy('name', 'desc')
                        ->paginate(12); //tăng dần
                    $count_product = count($products);
                }
                if ($fillter == 2) {
                    $products = product::where('disabler', '=', 'active')
                        ->orderBy('name', 'asc')
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($fillter == 3) {
                    $products = product::where('disabler', '=', 'active')
                        ->orderBy('price', 'desc')
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($fillter == 4) {
                    $products = product::where('disabler', '=', 'active')
                        ->orderBy('price', 'asc')
                        ->paginate(12);
                    $count_product = count($products);
                }
            } elseif (
                Request()->price &&
                Request()->brand &&
                Request()->species
            ) {
                //Lọc theo giá, loại, hãng sản phẩm
                $price = Request()->price;
                $brand = Request()->brand;
                $species = Request()->species;
                if ($price == 1) {
                    $products = product::where('price', '>', 5000000)
                        ->where('disabler', '=', 'active')
                        ->where('price', '<', 10000000)
                        ->where('the_firm', 'LIKE', "%{$brand}%")
                        ->where('productcat_id', '=', "{$species}")
                        ->paginate(12);
                    $count_product = count($products);
                    //  return $products;
                }
                if ($price == 2) {
                    $products = product::where('price', '>', 10000000)
                        ->where('disabler', '=', 'active')
                        ->where('price', '<', 15000000)
                        ->where('the_firm', 'LIKE', "%{$brand}%")
                        ->where('productcat_id', '=', "{$species}")
                        ->paginate(12);
                    $count_product = count($products);
                    //  return $products;
                }
                if ($price == 3) {
                    $products = product::where('price', '>', 15000000)
                        ->where('disabler', '=', 'active')
                        ->where('price', '<', 20000000)
                        ->where('the_firm', 'LIKE', "%{$brand}%")
                        ->where('productcat_id', '=', "{$species}")
                        ->paginate(12);
                    $count_product = count($products);
                    //  return $products;
                }
                if ($price == 4) {
                    $products = product::where('price', '>', 20000000)
                        ->where('disabler', '=', 'active')
                        ->where('the_firm', 'LIKE', "%{$brand}%")
                        ->where('productcat_id', '=', "{$species}")
                        ->paginate(12);
                    //  return $products;
                    $count_product = count($products);
                }
                if ($price == 5) {
                    $products = product::where('price', '<', 5000000)
                        ->where('disabler', '=', 'active')
                        ->paginate(12);
                    //  return $products;
                    $count_product = count($products);
                }
            } elseif (Request()->price and Request()->brand) {
                // Lọc theo giá và hãng sản phẩm
                $price = Request()->price;
                $brand = Request()->brand;
                if ($price == 1) {
                    $products = product::where('price', '>', 5000000)
                        ->where('disabler', '=', 'active')
                        ->where('price', '<', 10000000)
                        ->where('the_firm', 'LIKE', "%{$brand}%")
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($price == 2) {
                    $products = product::where('price', '>', 10000000)
                        ->where('disabler', '=', 'active')
                        ->where('price', '<', 15000000)
                        ->where('the_firm', 'LIKE', "%{$brand}%")
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($price == 3) {
                    $products = product::where('price', '>', 15000000)
                        ->where('disabler', '=', 'active')
                        ->where('price', '<', 20000000)
                        ->where('the_firm', 'LIKE', "%{$brand}%")
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($price == 4) {
                    $products = product::where('price', '>', 20000000)
                        ->where('disabler', '=', 'active')
                        ->where('the_firm', 'LIKE', "%{$brand}%")
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($price == 5) {
                    $products = product::where('price', '<', 5000000)
                        ->where('disabler', '=', 'active')
                        ->where('the_firm', 'LIKE', "%{$brand}%")
                        ->paginate(12);
                    $count_product = count($products);
                }
            } elseif (Request()->price && Request()->species) {
                // Lọc theo giá và loai(danh muc) sản phẩm
                $price = Request()->price;
                $species = Request()->species;
                if ($price == 1) {
                    $products = product::where('price', '>', 5000000)
                        ->where('disabler', '=', 'active')
                        ->where('price', '<', 10000000)
                        ->where('productcat_id', '=', "{$species}")
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($price == 2) {
                    $products = product::where('price', '>', 10000000)
                        ->where('disabler', '=', 'active')
                        ->where('price', '<', 15000000)
                        ->where('productcat_id', '=', "{$species}")
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($price == 3) {
                    $products = product::where('price', '>', 15000000)
                        ->where('disabler', '=', 'active')
                        ->where('price', '<', 20000000)
                        ->where('productcat_id', '=', "{$species}")
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($price == 4) {
                    $products = product::where('price', '>', 20000000)
                        ->where('disabler', '=', 'active')
                        ->where('productcat_id', '=', "{$species}")
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($price == 5) {
                    $products = product::where('price', '<', 5000000)
                        ->where('disabler', '=', 'active')
                        ->where('productcat_id', '=', "{$species}")
                        ->paginate(12);
                    $count_product = count($products);
                }
            } elseif (Request()->brand && Request()->species) {
                // Lọc theo hãng và loai(danh muc) sản phẩm
                $brand = Request()->brand;
                $species = Request()->species;
                $products = product::where('the_firm', 'LIKE', "%{$brand}%")
                    ->where('disabler', '=', 'active')
                    ->where('productcat_id', '=', "{$species}")
                    ->paginate(12);
                $count_product = count($products);
            } elseif (Request()->price) {
                // Lọc theo giá sản phẩm
                $price = Request()->price;
                if ($price == 1) {
                    $products = product::where('price', '>', 5000000)
                        ->where('disabler', '=', 'active')
                        ->where('price', '<', 10000000)
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($price == 2) {
                    $products = product::where('price', '>', 10000000)
                        ->where('disabler', '=', 'active')
                        ->where('price', '<', 15000000)
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($price == 3) {
                    $products = product::where('price', '>', 15000000)
                        ->where('disabler', '=', 'active')
                        ->where('price', '<', 20000000)
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($price == 4) {
                    $products = product::where('price', '>', 20000000)
                        ->where('disabler', '=', 'active')
                        ->paginate(12);
                    $count_product = count($products);
                }
                if ($price == 5) {
                    $products = product::where('price', '<', 5000000)
                        ->where('disabler', '=', 'active')
                        ->paginate(12);
                    $count_product = count($products);
                }
            } elseif (Request()->brand) {
                // Lọc theo hãng sản phẩm
                $brand = Request()->brand;
                $products = product::where('the_firm', 'LIKE', "%{$brand}%")
                    ->where('disabler', '=', 'active')
                    ->paginate(12);
                $count_product = count($products);
            } elseif (Request()->species) {
                // Lọc theo danh mục sản phẩm
                $species = Request()->species;
                $products = product::where('productcat_id', '=', "{$species}")
                    ->where('disabler', '=', 'active')
                    ->paginate(12);
                $count_product = count($products);
            } else {
                $products = product::where('disabler', '=', 'active')->paginate(
                    12
                );
                $count_product = count($products);
            }
        }
        $counts = [$count_product_all, $count_product];
        return view(
            'product',
            compact(
                'products',
                'productcats',
                'the_firm_for_menu_slidebar',
                'the_firms',
                'counts',
                'banners'
            )
        );
    }
    // Trang chi tiet san pham
    function detailproduct($id, $slug)
    {
        // function detailproduct($id)
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        // $posts = post::paginate( 5 );
        $product = product::find($id);
        // return $product->the_firm;
        // Xử lý khi người dùng cố tình nhập sai url
        if (empty($product)) {
            return view('errors.404');
        }
        $products = product::where('the_firm', 'LIKE', $product->the_firm)
            ->where('disabler', '=', 'active')
            ->get();
        // return count($products);
        $productcats = productcat::where('disabler', '=', 'active')->get();
        // $the_firms = product::all()->unique( 'the_firm' );//hay nhung thieu ban ghi, chac chi ap dung vao TH khac, tim hieu them sau
        $the_firms = DB::table('products')
            ->where('disabler', '=', 'active')
            ->select('the_firm', 'productcat_id')
            ->distinct('the_firm')
            ->get();
        // return count($the_firms);
        return view(
            'detailproduct',
            compact(
                'product',
                'productcats',
                'the_firms',
                'products',
                'banners'
            )
        );
    }
    // Xay dung chuc nang tim kiem tren input tim kiem
    function search(Request $request)
    {
        $keyword = Request()->keyword;
        $products = product::where('name', 'LIKE', "%{$keyword}%")
            ->where('disabler', '=', 'active')
            ->get();
        // cap nhat lai duong dan anh khong se bi at khi thao tac o cac trag con
        if (!empty($products)) {
            foreach ($products as $product) {
                if (
                    $product->thumbnail !=
                    'http://localhost/unimart/' . $product->thumbnail
                ) {
                    $product->thumbnail =
                        'http://localhost/unimart/' . $product->thumbnail;
                }
            }
            $str_start = "<ul class='list-cart-search'>";
            $array = [];
            foreach ($products as &$li) {
                $li->price = number_format($li->price, 0, ',', '.') . 'đ';
                // $array[]="<li class=clearfix><a><img style='width:35px;height:35px;display:inline-block;'src='$li->thumbnail'><a><a style='font-size:20px;text-align:center;' href=detailproduct/".$li->id.">".$li->name."</a></li>";
                // $array[]="<li class=clearfix><a href='detailproduct/$li->id'><img class='image_search' src='$li->thumbnail'><a><a style='font-size:20px;text-align:center;'href=detailproduct/".$li->id.">".$li->name."</a></li>";
                // $array[]="<li ><a href='detailproduct/$li->id'><img class='image_search' src='$li->thumbnail'><a><a class='name_product' href=detailproduct/".$li->id.">".$li->name."</a></li>";
                $array[] =
                    "<li class='detailli'><a class='image_thumb' href=chi-tiet-san-pham/" .
                    $li->id .
                    '-' .
                    str::slug($li->name) .
                    '>' .
                    "<img class='image_search' src='$li->thumbnail'><a><a class='name_product' href=chi-tiet-san-pham/" .
                    $li->id .
                    '-' .
                    str::slug($li->name) .
                    '>' .
                    $li->name .
                    "</a><p class='margin-left'>Giá : $li->price</p></li>";
            }
            // chuyen mang thanh chuoi
            $chuyen = implode('', $array);
            $count_product = count($products);
            $search =
                $str_start .
                $chuyen .
                "<li style='font-size:20px;'>Tổng : " .
                $count_product .
                ' sản phẩm</li>' .
                "<li style='text-align:right;padding-bottom:10px;'><button id='close-search' style='margin-right:10px;'>Đóng</button></li>" .
                '</ul>';
            echo $search;
        }
    }
    //Mua ngay san pham
    function buynowproduct($slug, $id)
    {
        $product = product::find($id);
        if (empty($product)) {
            return view('errors.404');
        }
        return view('checkoutbuynow', compact('product'));
    }
    // Xu ly insert vao database khi nguoi dung mua ngay san pham
    function insertbuynowproduct(Request $request, $id)
    {
        // $request=request()->all();

        // $request=request()->id;
        // return $request;
        $product = product::find($id);
        // return $product;
        $request->validate(
            [
                'fullname' => 'required|string|max:50',
                'email' => 'required|email|max:50',
                'address' => 'required|string|max:255',
                'phone' => 'required|max:40',
                'payment_method' => 'required|string|max:50',
            ],
            [
                'required' => ':attribute không được để trống',
                'payment_method.required' =>
                    ':attribute phương thức thanh toán',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'email' => 'phải đúng định dạng',
            ],
            [
                'fullname' => 'Họ và tên',
                'email' => 'Email',
                'address' => 'Địa chỉ',
                'phone' => 'Số điện thoại',
                'payment_method' => 'Bạn phải chọn',
            ]
        );

        // insert khach hang truoc:customer
        $time = time();
        $email = $request->input('email');
        $id_customerinsert = customer::create([
            'fullname' => request()->input('fullname'),
            'email' => request()->input('email'),
            'address' => request()->input('address'),
            'phone' => request()->input('phone'),
            'note' => request()->input('note'),
            'subtotal' => $product->price,
            'status' => 'Chờ xử lý',
            'payment_method' => request()->input('payment_method'),
            'MaKH' => $time,
        ])->id;
        // insert don hang sau::order
        order::create([
            'masp' => $product->masp,
            'thumbnail' => $product->thumbnail,
            'name' => $product->name,
            'price' => $product->price,
            'qty' => 1,
            'color' => $product->color,
            'subtotal' => $product->price,
            'payment' => $request->input('payment_method'),
            'status' => 'Chờ xử lý',
            'customer_id' => $id_customerinsert,
            'MaKH' => $time,
        ]);
        $total_cart = $product->price;
        // Gui mail cho khach hang
        // Mail::to( 'Tên mail nhận' )->send( new Tên email : Nội dung là tên file DemoMail( noi dung gui mail trong file Demomail tao o buoc truoc se return ve view/mails/demo.blade.php ) ben nhan mail se la view nay( html ) );
        // Bài 212 : Gửi dữ liệu động từ controller
        if ($request->input('payment_method') == 'at-home') {
            $payment_method = 'Tại nhà';
        } else {
            $payment_method = 'Chuyển khoản';
        }
        $data = [
            'key1' =>
                'Hệ thống siêu thị unimart gửi mail xác nhận đơn hàng đã đặt thành công',
            'time_send' =>
                'Thời gian nhận hàng sớm nhất sau 1 tuần kể từ ngày nhận được email này',
            'Customer' => $request->input('fullname'),
            'MaKH' => $time,
            'Email' => $request->input('email'),
            'Address' => $request->input('address'),
            'Phone' => $request->input('phone'),
            'payment_method' => $payment_method,
            'order' => [
                // 'id' => $product->id,
                'name' => $product->name,
                'qty' => 1,
                'price' => $product->price,
                'options' => (object) [
                    'thumbnail' => $product->thumbnail,
                    'masp' => $product->masp,
                    'color' => $product->color,
                ],
            ],
            'total_cart' => $total_cart,
        ];
        // return count($data['order']);
        // return view( 'mailcustomer', compact( 'data' ) );
        Mail::to($email)->send(new CustomerMail($data));
        // $data la tham so( du lieu se la kieu mang truyen sang view demo.blade.php qua DemoMail.php )
        // return redirect()->route( 'index',['success'=>'success']);
        return redirect()
            ->route('index')
            ->with('status', 'success');
    }
    // Chuyen sang day khong bao loi do thiet lap quyen o trong admin
    // Xử lý route gioi thieu phía người dùng
    function introduce(Request $request)
    {
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        $listproductsellings = product::where('product_selling', '<>', '')
            ->where('disabler', '=', 'active')
            ->get();
        // return count($listproductsellings);
        $id = 1;
        // return $id;
        // $pages=page::where('category','=',$id)->get();
        $pages = DB::table('pages')
            ->where('category', '=', $id)
            ->where('disabler', '=', 'active')
            ->get();
        // return count($pages);
        if ($id == 1) {
            $category = 'Giới thiệu';
        } else {
            $category = 'Liên hệ';
        }
        return view(
            'introduce',
            compact('pages', 'category', 'listproductsellings', 'banners')
        );
    }
    // Xử lý route lien he phía người dùng
    function contact(Request $request)
    {
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        $listproductsellings = product::where('product_selling', '<>', '')
            ->where('disabler', '=', 'active')
            ->get();
        //  return count($listproductsellings);
        $id = 2;
        // return $id;
        $pages = DB::table('pages')
            ->where('disabler', '=', 'active')
            ->where('category', '=', $id)
            ->get();
        // return count($pages);
        if ($id == 1) {
            $category = 'Giới thiệu';
        } else {
            $category = 'Liên hệ';
        }
        return view(
            'contact',
            compact('pages', 'category', 'listproductsellings', 'banners')
        );
    }

    // Xử lý route bai viet phía người dùng
    function post()
    {
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        $listproductsellings = product::where('product_selling', '<>', '')
            ->where('disabler', '=', 'active')
            ->get();
        $listposts = post::where('disabler', '=', 'active')->get();
        $listcatposts = postcat::where('disabler', '=', 'active')->get();
        if (request()->id) {
            $id = request()->id;
            $listposts = post::where('disabler', '=', 'active')
                ->where('id', '=', $id)
                ->get();
            $listcatposts = postcat::where('disabler', '=', 'active')
                ->where('id', '=', $id)
                ->get();
        }
        // return count($listcatposts);
        return view(
            'post',
            compact(
                'listposts',
                'listcatposts',
                'listproductsellings',
                'banners'
            )
        );
    }
    function detailpost($id, $slug)
    {
        // function detailpost($id)
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        $listproductsellings = product::where('product_selling', '<>', '')
            ->where('disabler', '=', 'active')
            ->get();
        $post = post::find($id);
        // Xử lý khi người dùng cố tình nhập sai url
        if (empty($post)) {
            return view('errors.404');
        }
        $catpost = postcat::find($post->postcat_id);
        return view(
            'detailpost',
            compact('post', 'catpost', 'listproductsellings', 'banners')
        );
    }
    function catpost($id, $slug = '')
    {
        // function catpost($id)
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        // return count($banners);
        $listproductsellings = product::where('product_selling', '<>', '')
            ->where('disabler', '=', 'active')
            ->get();
        //  return count($listproductsellings);
        $catpost = postcat::find($id);
        // return$catpost;
        // Xu ly khi nguoi dung co tinh nhap url sai
        if (empty($catpost)) {
            return view('errors.404');
        }
        $posts = post::where('postcat_id', $id)
            ->where('disabler', '=', 'active')
            ->get();
        // return count($posts);
        return view(
            'catpost',
            compact('catpost', 'posts', 'listproductsellings', 'banners')
        );
    }

    // Test form select ajax
    function testajax(Request $request)
    {
        $banners = DB::table('advertisements')->get();
        // $id=10;
        $select = request()->select;

        return redirect()->route('product', $select);
        echo $select;
        // echo $id;
    }
    function order_lookup(Request $request)
    {
        $email = request()->email;

        // echo 'ket noi thanh cong';
        // $customer=customer::where('email','=','wedquach1981@gmail.com')->get();
        // return count($customer);
        $customers = customer::where('email', '=', $email)->get();
        $list_id = [];
        foreach ($customers as $customer) {
            if ($customer->payment_method == 'at-home') {
                $customer->payment_method = 'Tại nhà';
            } else {
                $customer->payment_method = 'Chuyển khoản';
            }
            $list_id[] = $customer->id;
        }
        $orders = order::whereIn('customer_id', $list_id)->get();
        $mang1 = [];
        foreach ($customers as $customer) {
            $customer->subtotal =
                number_format($customer->subtotal, 0, ',', '.') . 'đ';
            $mang1[
                $customer->id
            ] = "<h3 style='font-size:20px;padding:10px 20px;'>Khách hàng<h3><table class='table'>
            <thead>
                <tr style='font-weight:bold;'>
                    <td>Họ tên</td>
                    <td>Email</td>
                    <td>Địa chỉ</td>
                    <td>Số ĐT</td>
                    <td>Ngày đặt hàng</td>
                    <td>Mã KH</td>
                    <td>Tổng tiền</td>
                    <td>Phương thức TT</td>
                    <td>Tình trạng</td>
                </tr>
            </thead>
            <tbody>
                        <tr>
                            <td>{$customer->fullname}</td>
                            <td>{$customer->email}</td>
                            <td>{$customer->address}</td>                              
                            <td>{$customer->phone}</td>
                            <td>{$customer->created_at}</td>
                            <td>{$customer->MaKH}</td> 
                            <td>{$customer->subtotal}</td> 
                            <td>{$customer->payment_method}</td> 
                            <td>{$customer->status}</td> 
                        </tr>
            </tbody>
        </table>
        <h3 style='font-size:20px;padding:10px 20px;'>Thông tin đơn hàng</h3>
        <table class='table'>
                        <thead>
                            <tr style='font-weight:bold;'>
                                <td>Mã SP</td>
                                <td>Tên SP</td>
                                <td>Giá SP</td>
                                <td>Số lượng</td>
                                <td>Màu sắc</td>
                                <td>Tổng tiền</td>
                                <td>Phương thức TT</td>
                                <td>Ngày đặt hàng</td>
                                <td>Mã KH</td>
                                <td>Tình trạng</td>
                            </tr>
                        </thead>
                        <tbody>";
            foreach ($orders as $order) {
                if ($order->customer_id == $customer->id) {
                    if ($order->payment == 'at-home') {
                        $order->payment = 'Tại nhà';
                    } else {
                        $order->payment = 'Chuyển khoản';
                    }
                    $order->subtotal =
                        number_format($order->subtotal, 0, ',', '.') . 'đ';
                    $order->price =
                        number_format($order->price, 0, ',', '.') . 'đ';
                    $mang1[$customer->id] .= "
                        <tr>
                                <td>{$order->masp}</td>
                                <td>{$order->name}</td>
                                <td>{$order->price}</td>                              
                                <td>{$order->qty}</td>
                                <td>{$order->color}</td>
                                <td>{$order->subtotal}</td> 
                                <td>{$order->payment}</td> 
                                <td>{$order->created_at}</td> 
                                <td>{$order->MaKH}</td> 
                                <td>{$order->status}</td> 
                        ";
                }
            }
            $mang1[$customer->id] .= "</tr>
                        </tbody>
                        </table><hr>";
        }
        $chuyen1 = implode('', $mang1);
        // $chuyen2 = implode('', $mang2);
        // $chuyen=$chuyen1.$chuyen2;
        if (!empty($chuyen1)) {
            echo $chuyen1 . "<button id='close'>Đóng</button>";
        } else {
            echo 'no';
        }
    }
}
