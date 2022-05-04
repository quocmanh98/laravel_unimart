<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\customer;
use App\order;
use App\User;
use App\postcat;
use App\post;
use App\productcat;
use App\product;
use Illuminate\Support\Facades\DB; //Phải khai báo thằng này vào đây không sẽ lỗi
class DashboardController extends Controller
{
    // Phan 24 bai 280 : Active menu nguoi dung truy cap
    // Tao phuong thuc khoi tao construct
    function __construct()
    {
        // Su dung middleware toi uu cho active module_active
        //  Su dung middleware de rang buoc cai session khi di vao moi module, sesion se duoc thay doi theo thiet lap o moi module
        // Neu khong co middleware thi session luon lay cai dau tien la dashbord->khong dung yeu cau bai toan dat ra
        $this->middleware(function ($request, $next) {
            Session(['module_active' => 'dashboard']);
            return $next($request);
        });
    }
    //Phan 24 bai 265
    function show(Request $request)
    {
        // Lay du lieu slider dang active, tu id cap nhat sang ten nguoi tao
        $sliders = DB::table('sliders')->get();
        foreach ($sliders as $item) {
            $item->creator = user::find($item->creator)->name;
            if (!empty($item->repairer)) {
                $item->repairer = user::find($item->repairer)->name;
            }
            if (!empty($item->disabler) && ($item->disabler!='active')) {
                $item->disabler = user::find($item->disabler)->name;
            }else{
                $item->disabler=''; 
            }
        }
        // Lay du lieu quang cao, tu id cap nhat sang ten nguoi tao
        $advertisements = DB::table('advertisements')->get();
        //    Cap nhat lai ten user tao va user cap nhat
        foreach ($advertisements as $item) {
            $item->creator = user::find(
                $item->creator
            )->name;
            if (!empty($item->repairer)) {
                $item->repairer = user::find(
                    $item->repairer
                )->name;
            }
            if (!empty($item->disabler)&&($item->disabler!='active')) {
                $item->disabler = user::find(
                    $item->disabler
                )->name;
            }else{
                $item->disabler=''; 
            }
        }
        // Lay du lieu bai viet cua trang
        $pages = DB::table('pages')->get();
        //    Cap nhat lai ten user tao va user cap nhat
        foreach ($pages as $item) {
            $item->creator = user::find($item->creator)->name;
            if (!empty($item->repairer)) {
                $item->repairer = user::find($item->repairer)->name;
            }
            if (!empty($item->disabler)&&($item->disabler!='active')) {
                $item->disabler = user::find($item->disabler)->name;
            }else{
                $item->disabler=''; 
            }
        }
        // Lay danh muc bai viet 
        $article_list = postcat::all();
        //    Cap nhat lai ten user tao va user cap nhat
        foreach ($article_list as $item) {
            $item->creator = user::find($item->creator)->name;
            if (!empty($item->repairer)) {
                $item->repairer = user::find($item->repairer)->name;
            }
            if (!empty($item->disabler)&&($item->disabler!='active')) {
                $item->disabler = user::find($item->disabler)->name;
            }else{
                $item->disabler=''; 
            }
        }
        // Lay danh sach bai viet 
        $posts = post::all();
        //    Cap nhat lai ten user tao va user cap nhat
        foreach ($posts as $item) {
            $item->creator = user::find($item->creator)->name;
            if (!empty($item->repairer)) {
                $item->repairer = user::find($item->repairer)->name;
            }
            if (!empty($item->disabler)&&($item->disabler!='active')) {
                $item->disabler = user::find($item->disabler)->name;
            }else{
                $item->disabler=''; 
            }
        }
        // Lay danh muc san pham
        $product_portfolio = productcat::all();
        //    Cap nhat lai ten user tao va user cap nhat
        foreach ($product_portfolio as $item) {
            $item->creator = user::find($item->creator)->name;
            if (!empty($item->repairer)) {
                $item->repairer = user::find($item->repairer)->name;
            }
            if (!empty($item->disabler)&&($item->disabler!='active')) {
                $item->disabler = user::find($item->disabler)->name;
            }else{
                $item->disabler=''; 
            }
        }
        // Lay danh sach san pham 
        $products = product::all();
        //    Cap nhat lai ten user tao va user cap nhat
        foreach ($products as $item) {
            $item->creator = user::find($item->creator)->name;
            if (!empty($item->repairer)) {
                $item->repairer = user::find($item->repairer)->name;
            }
            if (!empty($item->disabler)&&($item->disabler!='active')) {
                $item->disabler = user::find($item->disabler)->name;
            }else{
                $item->disabler=''; 
            }
        }
        // phan don hang
        $keyword = '';
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
        }
        // Cach 1 : Lay tren nhieu truong cua bang
        // Insert va lay thang cuoi cho len dau de de quan sat don hang moi vao
        $customers = customer::orderBy('id', 'desc')
            ->where('fullname', 'LIKE', "%{$keyword}%")
            ->orWhere('email', 'LIKE', "%{$keyword}%")
            ->orWhere('address', 'LIKE', "%{$keyword}%")
            ->orWhere('phone', 'LIKE', "%{$keyword}%")
            ->orWhere('status', 'LIKE', "%{$keyword}%")
            ->orWhere('subtotal', 'LIKE', "%{$keyword}%")
            ->orWhere('payment_method', 'LIKE', "%{$keyword}%")
            ->orWhere('created_at', 'LIKE', "%{$keyword}%")
            ->paginate(16);
        // return $customers;
        if (count($customers) > 0) {
            foreach ($customers as $item) {
                $customers_id[] = $item->id;
            }
            // return $customers_id;
            // $orders=order::paginate(20);
            $orders = order::orderBy('id', 'desc')
                ->whereIn('customer_id', $customers_id)
                ->get(); // lay theo elequent model cung oke(moi quan he one to many)

            $count_products_all = 0;
            $sum_subtotal_product = 0;
            foreach ($orders as $item) {
                $count_products_all += $item->qty;
                $sum_subtotal_product += $item->subtotal;
            }
            // return $count_products_all;
            // return $sum_subtotal_product;
            // Tổng tất cả sản phẩm thành công
            $orders_success = order::where('status', '=', 'Thành công')->get();
            // return $orders_success;
            $count_products_success = 0;
            $sum_subtotal_product_success = 0;
            foreach ($orders_success as $item) {
                $count_products_success += $item->qty;
                $sum_subtotal_product_success += $item->subtotal;
            }
            // return $count_products_success;
            // return $sum_subtotal_product_success;
            // Tổng tất cả sản phẩm chờ xử lý
            $orders_Waiting = order::where('status', '=', 'Chờ xử lý')->get();
            // return $orders_Waiting;
            $count_products_Waiting = 0;
            $sum_subtotal_product_Waiting = 0;
            foreach ($orders_Waiting as $item) {
                $count_products_Waiting += $item->qty;
                $sum_subtotal_product_Waiting += $item->subtotal;
            }
            // return $count_products_Waiting;
            // return $sum_subtotal_product_Waiting;
            // Tổng tất cả sản phẩm đã hủy
            $orders_cancel = order::where('status', '=', 'Đã hủy')->get();
            // return $orders_cancel;
            $count_products_cancel = 0;
            $sum_subtotal_product_cancel = 0;
            foreach ($orders_cancel as $item) {
                $count_products_cancel += $item->qty;
                $sum_subtotal_product_cancel += $item->subtotal;
            }
            // return $count_products_cancel;
            // return $sum_subtotal_product_cancel;
            $count_products = [
                'count_products_all' => $count_products_all,
                'count_products_success' => $count_products_success,
                'count_products_Waiting' => $count_products_Waiting,
                'count_products_cancel' => $count_products_cancel,
            ];
            // return  $count_products;
            $subtotal_products = [
                'sum_subtotal_product_all' => $sum_subtotal_product,
                'sum_subtotal_product_success' => $sum_subtotal_product_success,
                'sum_subtotal_product_Waiting' => $sum_subtotal_product_Waiting,
                'sum_subtotal_product' => $sum_subtotal_product,
                'sum_subtotal_product_cancel' => $sum_subtotal_product_cancel,
            ];
            // return $subtotal_products;
            // $sum_subtotal=customer::sum('subtotal'); //Tong tien lay chet theo bang du lieu

            $customers_Waiting = customer::where(
                'status',
                '=',
                'Chờ xử lý'
            )->get();
            // return $customers_Waiting;
            $customers_success = customer::where(
                'status',
                '=',
                'Thành công'
            )->get();
            // return $customers_success;
            $customers_cancel = customer::where('status', '=', 'Đã hủy')->get();
            // return $customers_cancel;
            $count_customers = count($customers);
            $count_customers_success = count($customers_success);
            $count_customers_Waiting = count($customers_Waiting);
            $count_customers_cancel = count($customers_cancel);
            $counts = [
                'count_customers' => count($customers),
                'count_customers_success' => count($customers_success),
                'count_customers_Waiting' => count($customers_Waiting),
                'count_customers_cancel' => count($customers_cancel),
            ];
            //  return $counts;
            $sum_subtotal = 0;
            foreach ($customers as $item) {
                $sum_subtotal += $item->subtotal;
            }
            $sum_subtotal_success = 0;
            foreach ($customers_success as $item) {
                $sum_subtotal_success += $item->subtotal;
            }
            $sum_subtotal_Waiting = 0;
            foreach ($customers_Waiting as $item) {
                $sum_subtotal_Waiting += $item->subtotal;
            }
            $sum_subtotal_cancel = 0;
            foreach ($customers_cancel as $item) {
                $sum_subtotal_cancel += $item->subtotal;
            }
            $sum_subtotals = [
                'sum_subtotal' => $sum_subtotal,
                'sum_subtotal_success' => $sum_subtotal_success,
                'sum_subtotal_Waiting' => $sum_subtotal_Waiting,
                'sum_subtotal_cancel' => $sum_subtotal_cancel,
            ];
            // return $sum_subtotals;
            // return $count_products;

            return view(
                'admin.dashboard',
                compact(
                    'customers',
                    'counts',
                    'orders',
                    'sum_subtotals',
                    'count_products',
                    'subtotal_products',
                    'sliders',
                    'advertisements',
                    'pages',
                    'article_list',
                    'posts',
                    'product_portfolio',
                    'products',
                )
            );
            // return view('admin.dashboard');
        } else {
            return view(
                'admin.dashboard',
                compact(
                    'sliders',
                    'advertisements',
                    'pages',
                    'article_list',
                    'posts',
                    'product_portfolio',
                    'products',
                )
            );
        }
    }
    // Xoa khach hang
    function deletecustomer($id)
    {
        $status = request()->input('status');
        $customer = customer::find($id);
        // return $customer;
        $customer->delete();
        // Xoa san pham cho vao thung rac theo khach hang
        $orders = order::where('customer_id', $id)->delete();
        // return $orders;
        return redirect('admin')->with(
            'status',
            'Đã xóa tạm thời khách hàng thành công'
        );
    }
}
