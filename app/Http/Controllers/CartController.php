<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\CustomerMail;
//Khi go lenh Mail::to( 'wedquach1981@gmail.com' )->send( new DemoMail );
// se tự hiện ra, khong hien thi danh vao
use Illuminate\Support\Facades\Mail;
//phai khai bao ca thu vien nay ko se loi
use App\productcat;
use App\product;
use App\customer;
use App\order;
use Gloudemans\Shoppingcart\Facades\Cart;
//khai bao thu vien cart
class CartController extends Controller
{
    function manyadd(Request $request)
    {
        $id = request()->id;
        $test = 5 * $id;
        $data = [
            'id' => $id,
            'test' => $test,
        ];
        echo json_encode($data);
    }

    //  Ghép thêm trang giỏ hàng

    function showcart(Request $request)
    {
        $cart = Cart::content();
        // return $cart;
        return view('cart/showcart');
    }

    function search(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:50',
        ]);
        $email = request()->email;
        // return $email;
        return redirect(route('showordercustomer', $email));
    }

    function add(Request $request, $id)
    {
        // cart + tab sẽ sinh ra thư viên Gloudemans\Shoppingcart\Facades\Cart trên cùng : VSC của mình lại không được là sao->tìm hiểu lại sau
        // Cart::add( $id, "Product {$id}", 1, 9.99 );
        // return Cart::content();
        // Hiển thị dạng print cho dễ nhìn
        // echo '<pre>';
        // print_r( Cart::content() );
        // echo '</pre>';
        // return "Thêm sản phẩm {$id} vào giỏ hàng";

        // Bài 232 : Thêm sản phẩm vào giỏ hàng : cấu trúc dạng mảng
        // Lấy thông tin sản phẩm:
        $product = Product::find($id);
        // return $product;
        // Cart::destroy();
        //Xóa toàn bộ giỏ hàng

        $qty = request()->all();
        // return $qty['num_order'];
        // dd( $request->input() );
        if (!empty(request()->all())) {
            $qty = request()->all()['num_order'];
        } else {
            $qty = 1;
        }
        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $product->price,
            //  CHỉ được 4 tham số chính thôi, còn lại phải cho vào tham số phụ : ảnh, kích thươc, mau sac
            // 'options' => ['size' => 'large'] //Tham số đính kèm thêm những thông tn phụ
            // Bài 234 : Hiển thị hình ảnh sản phẩm trong giỏ hàng
            'options' => [
                'thumbnail' => $product->thumbnail,
                'masp' => $product->masp,
                'color' => $product->color,
            ], //Tham số đính kèm thêm những thông tin phụ
        ]);
        // return Cart::content();
        // return Cart::get( '54379001b5101c50449eb2b932ca5f2f' );

        // return redirect( 'cart/showcart' );
        return redirect()
            ->route('showcart')
            ->with('status', 'Thêm sản phẩm vào giỏ hàng thành công!');
    }
    // add cart bang ajax
    // function addajax( Request $request, $id ) {
    function addcartajax(Request $request)
    {
        $id = request()->id;
        $product = Product::find($id);
        // echo json_encode( $product );
        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->price,
            'options' => [
                'thumbnail' => $product->thumbnail,
                'masp' => $product->masp,
                'color' => $product->color,
            ],
        ]);
        echo $id;
    }

    // Bài 235 : Xóa sản phẩm trong giỏ hàng

    function remove($rowId)
    {
        Cart::remove($rowId);
        return redirect()->route('showcart');
    }
    // Bài 237 : Xóa toàn bộ sản phẩm trong giỏ hàng

    function destroy()
    {
        Cart::destroy();
        //Xóa toàn bộ giỏ hàng
        return redirect()->route('showcart');
    }
    // Bài 239 : Cập nhật giỏ hàng

    function update(Request $request)
    {
        // dd( $request->all() );
        //Hàm dd xuất dữ liệu tương tự nhue hàm prin_r
        // dd( $request->input() );
        //Hàm dd xuất dữ liệu tương tự nhu hàm prin_r
        // return Cart::get( 'rowId' );
        $data = $request->get('qty');
        foreach ($data as $k => $v) {
            Cart::update($k, $v);
        }
        return redirect('cart/showcart');
    }

    // Thanh toan gio hang:checkoutcart

    function checkout()
    {
        $cart = Cart::content();
        // return $cart;
        $errorqty = [];
        foreach ($cart as $item) {
            if ($item->qty > product::find($item->id)->qty) {
                $errorqty[$item->id] = 'error';
            }
        }
        if (!empty($errorqty)) {
            return redirect('cart/showcart')->with(
                'status',
                'Sản phẩm bạn chọn hiện không đủ hàng, bạn vui lòng thay đổi số lượng hoặc chọn sản phẩm khác phù hợp!'
            );
        } else {
            return view('cart.checkout');
        }
    }
    // Luu thong tin gio hang

    function insertcart(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh'); //Lấy thời gian theo timezone là HCM
        $cart = Cart::content();
        // $customer = $request->all();
        // return $customer;
        // return $cart;
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

        $email = $request->input('email');
        // return $email;
        $subtotal = 0;
        foreach (Cart::content() as $item) {
            $subtotal += $item->subtotal;
        }
        $time = time();
        $id_customerinsert = customer::create([
            'fullname' => $request->input('fullname'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'note' => $request->input('note'),
            'subtotal' => $subtotal,
            'status' => 'Chờ xử lý',
            'payment_method' => $request->input('payment_method'),
            'MaKH' => $time,
        ])->id;
        $total_cart = 0;
        foreach ($cart as $item) {
            order::create([
                'masp' => $item->options->masp,
                'thumbnail' => $item->options->thumbnail,
                'name' => $item->name,
                'price' => $item->price,
                'qty' => $item->qty,
                'color' => $item->options->color,
                'subtotal' => $item->subtotal,
                'payment' => $request->input('payment_method'),
                'status' => 'Chờ xử lý',
                'customer_id' => $id_customerinsert,
                'MaKH' => $time,
            ]);
            $total_cart += $item->subtotal;
        }
        // Gui mail cho khach hang
        // Mail::to( 'Tên mail nhận' )->send( new Tên email : Nội dung là tên file DemoMail( noi dung gui mail trong file Demomail tao o buoc truoc se return ve view/mails/demo.blade.php ) ben nhan mail se la view nay( html ) );
        // Bài 212 : Gửi dữ liệu động từ controller
        if($request->input('payment_method')=='at-home'){
            $payment_method='Tại nhà';
        }else{
            $payment_method='Chuyển khoản';
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
            'order' => $cart,
            'total_cart' => $total_cart,
        ];
        // return view( 'mailcustomer', compact( 'data' ) );
        Mail::to($email)->send(new CustomerMail($data));
        // $data la tham so( du lieu se la kieu mang truyen sang view demo.blade.php qua DemoMail.php )
        Cart::destroy();
        // return redirect()->route( 'index',['success'=>'success']);
        return redirect()
            ->route('index')
            ->with('status', 'success');
    }

    // Show đơn hàng của khách hàng vừa đăng ký

    function showordercustomer($id)
    {
        $customer = customer::find($id);
        // return $customer ;
        // return $customer->id;
        // $order_customer = order::where( 'customer_id', '=', $customer->id )->get();
        //lay theo tu duy cua minh->oke
        $order_customer = customer::find($customer->id)->products; // lay theo elequent model cung oke( moi quan he one to many )
        // return $order_customer;
        // Lay tong gia tri don hang
        //   $sumorder = order::count();
        //lay so luong don hang
        //   return $sumorder;
        //   $testsumorder = order::sum( 'subtotal' );
        //lay tong gia tri don hang oke
        // $sumorder = 0;
        // foreach ( $order_customer as $subtotal ) {
        //     $sumorder += $subtotal->subtotal;
        // }
        $sumorder = order::where('customer_id', '=', $customer->id)->sum(
            'subtotal'
        );
        //hay

        return view(
            'cart.showordercustomer',
            compact('order_customer', 'customer', 'sumorder')
        );
    }

    // Xử lý ajax

    function updateajax(Request $request)
    {
        $id = request()->id;
        $qty = request()->qty;
        Cart::update($id, $qty);
        $data = [
            'sub_total' =>
                number_format(Cart::get($id)->subtotal, 0, ',', '.') . 'đ',
            'total' => Cart::total() . 'Đ',
            'count_cart' => Cart::count(),
        ];
        echo json_encode($data);
    }

    // =======================================================================
    // Tao thong bao bang ajax va insert du lieu vao database

    // function ajaxorder( Request $request ) {
    //     date_default_timezone_set("Asia/Ho_Chi_Minh");//Lấy thời gian theo timezone là HCM
    //     $cart = Cart::content();
    //     $fullname = request()->fullname;
    //     $email = request()->email;
    //     $address = request()->address;
    //     $phone = request()->phone;
    //     $note = request()->note;
    //     $payment_method = request()->payment_method;

    //     $error=array();
    //     $parttern ="/^[A-Za-z0-9_.]{2,32}@([a-zA-Z0-9]{2,12})(.[a-zA-Z]{2,12})+$/";
    //     if (!preg_match($parttern, $email)){
    //                 $error['email']='no';
    //                 echo $error['email'];
    //             }
    //     foreach ( $cart as $item ) {
    //         if (($item->qty) > (product::find( $item->id )->qty)) {
    //             $error['qty'] = 'error';
    //             echo $error['qty'];
    //             break;
    //         }
    //     }
    //     if(empty($error)){
    //         $subtotal = 0;
    //         foreach ( Cart::content() as $item ) {
    //             $subtotal += $item->subtotal;
    //         }
    //         $id_customerinsert = customer::create(
    //             [
    //                 'fullname'=>$fullname,
    //                 'email'=>$email,
    //                 'address'=>$address,
    //                 'phone'=>$phone,
    //                 'note'=>$note,
    //                 'subtotal'=>$subtotal,
    //                 'status'=>'Chờ xử lý',
    //                 'payment_method'=>$request->input( 'payment_method' ),
    //             ]
    //         )->id;
    //         customer::find($id_customerinsert)->update(
    //             [
    //                 'fullname'=>$fullname,
    //                 'email'=>$email,
    //                 'address'=>$address,
    //                 'phone'=>$phone,
    //                 'note'=>$note,
    //                 'subtotal'=>$subtotal,
    //                 'status'=>'Chờ xử lý',
    //                 'payment_method'=>$request->input( 'payment_method' ),
    //                 'MaKH'=>'MaKH-'.$id_customerinsert,
    //             ]);
    //         $customer_insert = customer::find( $id_customerinsert );
    //         //tham khao tren google lay id cua thang vua insert->hay

    //         $id_customerpayment = $customer_insert->payment_method;
    //         foreach ( $cart as $item ) {
    //             order::create(
    //                 [
    //                     'masp'=>$item->options->masp,
    //                     'thumbnail'=>$item->options->thumbnail,
    //                     'name'=>$item->name,
    //                     'price'=>$item->price,
    //                     'qty'=>$item->qty,
    //                     'color'=>$item->options->color,
    //                     'subtotal'=>$item->subtotal,
    //                     'payment'=>$id_customerpayment,
    //                     'status'=>'Chờ xử lý',
    //                     'customer_id'=>$id_customerinsert,
    //                     'MaKH'=>'MaKH-'.$id_customerinsert,
    //                 ]
    //             );
    //         }
    //         $data = [
    //             'key1'=>'Hệ thống siêu thị ismart gửi mail xác nhận đơn hàng thành công',
    //             'time_send'=>'Thời gian nhận hàng sơm nhất sau 1 tuần kể từ ngày nhận được mail này',
    //             'Customer'=>$request->input( 'fullname' ),
    //             'MaKH'=>'MaKH-'.$id_customerinsert,
    //             'Email'=>$request->input( 'email' ),
    //             'Address'=>$request->input( 'address' ),
    //             'Phone'=>$request->input( 'phone' ),
    //             'order'=>$cart
    //         ];
    //         // Gui mail cho khach hang
    //         // echo json_encode( $data )
    //         // return view( 'mailcustomer', compact( 'data' ) );
    //         Mail::to( $email )->send( new CustomerMail( $data ) );
    //         // $data la tham so( du lieu se la kieu mang truyen sang view demo.blade.php qua DemoMail.php )
    //         Cart::destroy();
    //         echo 'success';
    //     }
    // }

    // if ( $fullname != '' && $email != '' && $address != '' && $phone != '' ) {
    //     $parttern ="/^[A-Za-z0-9_.]{6,32}@([a-zA-Z0-9]{2,12})(.[a-zA-Z]{2,12})+$/";
    //     // $typeemail=array();

    // }

    // }
    // ===========================================================================================
}
