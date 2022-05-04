<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\customer;
use App\order;
use App\product;
use Illuminate\Support\Facades\Auth;
class AdminOrderController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Su dung middleware toi uu cho active module_active
            //  Su dung middleware de rang buoc cai session khi di vao moi module, sesion se duoc thay doi theo thiet lap o moi module
            // Neu khong co middleware thi session luon lay cai dau tien la dashbord->khong dung yeu cau bai toan dat ra ->HAY
            Session(['module_active' => 'order']);
            return $next($request);
        });
    }

    //Xay dung module don hang
    function listorder(Request $request)
    {
        // Quyen truy cap cua user
        // $roles = [];
        // foreach (Auth::user()->roles as $role) {
        //     if ($role->namerole == 'administrators') {
        //         $roles['administrators'] = 'administrators';
        //     }
        //     if ($role->namerole == 'order_processing') {
        //         $roles['order_processing'] = 'order_processing';
        //     }
        // }
        // // return $roles;
        // if (empty($roles)) {
        //     return redirect('admin')->with(
        //         'status',
        //         'Bạn không được phép truy cập vào phần đơn hàng!'
        //     );
        // }
        $list_act = [
            'success' => 'Thành công',
            'forceDelete' => 'Xóa',
        ];
        $keyword = '';
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
        }
        $customers = customer::orderBy('id', 'desc')
            ->where('fullname', 'LIKE', "%{$keyword}%")
            ->orWhere('email', 'LIKE', "%{$keyword}%")
            ->orWhere('address', 'LIKE', "%{$keyword}%")
            ->orWhere('phone', 'LIKE', "%{$keyword}%")
            ->orWhere('status', 'LIKE', "%{$keyword}%")
            ->orWhere('subtotal', 'LIKE', "%{$keyword}%")
            ->orWhere('payment_method', 'LIKE', "%{$keyword}%")
            ->orWhere('created_at', 'LIKE', "%{$keyword}%")
            ->orWhere('MaKH', 'LIKE', "%{$keyword}%")
            ->paginate(10);
        // Cach 2 : Lay tren nhieu truong cua bang nhung phai cho vao mang gia tri cu the moi duoc
        // $customers=customer::where('fullName','LIKE',"%{$keyword}%")
        // ->orWhere(['email','LIKE',"%{$keyword}%",'address','LIKE',"%{$keyword}%",'phone','LIKE',"%{$keyword}%",'payment_method','LIKE',"%{$keyword}%",'created_at','LIKE',"%{$keyword}%"])
        // ->paginate(5);
        // return $customers;
        if (count($customers) > 0) {
            // if(count($customers)==1&&$customers[0]->status=="Chờ xử lý"){
            if (count($customers) == 1) {
                if ($customers[0]->status == 'Chờ xử lý') {
                    $d_show = '';
                    $fullnamecustomer = $customers[0]->fullname;
                    $status = $customers[0]->status;
                } else {
                    $d_show = 'd-none';
                    $fullnamecustomer = $customers[0]->fullname;
                    $status = $customers[0]->status;
                }
            } else {
                $status = '';
                $fullnamecustomer = '';
                $d_show = 'd-none';
            }
            foreach ($customers as $item) {
                $customers_id[] = $item->id;
            }
            // return $customers_id;
            // $orders=order::paginate(20);
            $orders = order::orderBy('id', 'desc')
                ->whereIn('customer_id', $customers_id) // lay theo elequent model cung oke(moi quan he one to many)
                ->get();
            // return  $orders;
            // Tổng tất cả sản phẩm
            $count_products_all = 0;
            $sum_subtotal_product = 0;
            foreach ($orders as $item) {
                $count_products_all += $item->qty;
                $sum_subtotal_product += $item->subtotal;
            }
            // Tổng tất cả sản phẩm thành công
            $orders_success = order::where('status', '=', 'Thành công')->get();
            $count_products_success = 0;
            $sum_subtotal_product_success = 0;
            foreach ($orders_success as $item) {
                $count_products_success += $item->qty;
                $sum_subtotal_product_success += $item->subtotal;
            }
            // Tổng tất cả sản phẩm chờ xử lý
            $orders_Waiting = order::where('status', '=', 'Chờ xử lý')->get();
            $count_products_Waiting = 0;
            $sum_subtotal_product_Waiting = 0;
            foreach ($orders_Waiting as $item) {
                $count_products_Waiting += $item->qty;
                $sum_subtotal_product_Waiting += $item->subtotal;
            }
            // Tổng tất cả sản phẩm đã hủy
            $orders_cancel = order::where('status', '=', 'Đã hủy')->get();
            $count_products_cancel = 0;
            $sum_subtotal_product_cancel = 0;
            foreach ($orders_cancel as $item) {
                $count_products_cancel += $item->qty;
                $sum_subtotal_product_cancel += $item->subtotal;
            }
            $count_products = [
                'count_products_all' => $count_products_all,
                'count_products_success' => $count_products_success,
                'count_products_Waiting' => $count_products_Waiting,
                'count_products_cancel' => $count_products_cancel,
            ];

            $subtotal_products = [
                'sum_subtotal_product_all' => $sum_subtotal_product,
                'sum_subtotal_product_success' => $sum_subtotal_product_success,
                'sum_subtotal_product_Waiting' => $sum_subtotal_product_Waiting,
                'sum_subtotal_product' => $sum_subtotal_product,
                'sum_subtotal_product_cancel' => $sum_subtotal_product_cancel,
            ];
            $customers_Waiting = customer::where(
                'status',
                '=',
                'Chờ xử lý'
            )->get();
            $customers_success = customer::where(
                'status',
                '=',
                'Thành công'
            )->get();
            $customers_cancel = customer::where('status', '=', 'Đã hủy')->get();
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
            // return $count_products;
            // $count_active=customer::count(); //phuong thuc lay so luong cua ORM->HAY
            // $count_trash=customer::onlyTrashed()->count();//phuong thuc lay so luong cua ORM->HAY
            // $count=[$count_active,$count_trash];
            return view(
                'admin/order/listorder',
                compact(
                    'customers',
                    'orders',
                    'sum_subtotals',
                    'count_products',
                    'subtotal_products',
                    'd_show',
                    'fullnamecustomer',
                    'status',
                    'list_act'
                )
            );
            // return view('admin/order/listorder',compact('customers','counts','orders','sum_subtotals','count_products','subtotal_products','d_show','fullnamecustomer','status','list_act'));
        } else {
            $status = '';
            $fullnamecustomer = '';
            $d_show = 'd-none';
            return view(
                'admin/order/listorder',
                compact('d_show', 'fullnamecustomer', 'status', 'list_act')
            );
        }
    }
    // Xu ly cho don hang thanh cong(cho xu ly chuyen ve thanh cong)
    function successcustomer($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
            if ($role->namerole == 'order_processing') {
                $roles['order_processing'] = 'order_processing';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép truy cập vào phần xử lý đơn hàng!'
            );
        }
        // return $id;
        $customer = customer::find($id);
        // return  $customer;
        if ($customer->status == 'Thành công') {
            return redirect('admin/order/listorder')->with(
                'status',
                'Đơn hàng này đang ở trạng thái thành công, bạn không thể thực hiện cho đơn hàng thành công được!'
            );
        }
        if ($customer->status == 'Đã hủy') {
            return redirect('admin/order/listorder')->with(
                'status',
                'Đơn hàng này đang ở trạng thái đã hủy, bạn không thể thực hiện cho đơn hàng thành công được!'
            );
        }

        customer::where('id', $id)->update([
            'status' => 'Thành công',
            'disabler' => Auth::id(),
        ]);
        $productorders = order::where('customer_id', $id)->get();
        // return $productorders;
        foreach ($productorders as $success) {
            order::where('id', $success->id)->update([
                'status' => 'Thành công',
                'disabler' => Auth::id(),
            ]);
        }

        // Cap nhat lai so luong cho bang products
        foreach ($productorders as $item) {
            $product_update = product::where('masp', '=', $item->masp)->first();
            // return $product_update->the_firm;
            product::where('masp', '=', $item->masp)
                ->first()
                ->update([
                    'qty' => $product_update->qty - $item->qty,
                    'repairer' => Auth::id(),
                ]);
        }
        return redirect('admin/order/listorder')->with(
            'status',
            'Đơn hàng đã cập nhật thành công, cập nhật cả bảng product về số lượng và user cập nhật!'
        );
    }
    // Xu ly huy don hang
    function cancelcustomer($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
            if ($role->namerole == 'order_processing') {
                $roles['order_processing'] = 'order_processing';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép truy cập vào phần xử lý đơn hàng!'
            );
        }

        $customer = customer::find($id);
        if ($customer->status == 'Đã hủy') {
            return redirect('admin/order/listorder')->with(
                'status',
                'Đơn hàng này đang ở trạng thái đã hủy, bạn không thể thực hiện cho hủy đơn hàng được!'
            );
        }
        if ($customer->status == 'Thành công') {
            return redirect('admin/order/listorder')->with(
                'status',
                'Đơn hàng này đang ở trạng thái thành công, bạn không thể thực hiện cho hủy đơn hàng được!'
            );
        }
        customer::where('id', $id)->update([
            'status' => 'Đã hủy',
            'disabler' => Auth::id(),
        ]);
        $productorders = order::where('customer_id', $id)->get();
        // return $productorders;
        foreach ($productorders as $sucess) {
            order::where('id', $sucess->id)->update([
                'status' => 'Đã hủy',
                'disabler' => Auth::id(),
            ]);
        }
        return redirect('admin/order/listorder')->with(
            'status',
            'Hủy đơn hàng thành công!'
        );
    }
    function showordercustomer($id)
    {
        // Quyen truy cap cua user
        // $roles = [];
        // foreach (Auth::user()->roles as $role) {
        //     if ($role->namerole == 'administrators') {
        //         $roles['administrators'] = 'administrators';
        //     }
        //     if ($role->namerole == 'order_processing') {
        //         $roles['order_processing'] = 'order_processing';
        //     }
        // }
        // // return $roles;
        // if (empty($roles)) {
        //     return redirect('admin')->with(
        //         'status',
        //         'Bạn không được phép truy cập vào phần xử lý đơn hàng!'
        //     );
        // }
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
            'admin/order/showordercustomer',
            compact('order_customer', 'customer', 'sumorder')
        );
    }
    // Xoa han don hang
    function deletecustomer($id)
    {
        // Quyen truy cap cua user
        $roles = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->namerole == 'administrators') {
                $roles['administrators'] = 'administrators';
            }
            if ($role->namerole == 'order_processing') {
                $roles['order_processing'] = 'order_processing';
            }
        }
        // return $roles;
        if (empty($roles)) {
            return redirect('admin')->with(
                'status',
                'Bạn không được phép truy cập vào phần xử lý đơn hàng!'
            );
        }
        $products_order = order::where('customer_id', '=', $id)->delete();
        $customer = customer::find($id)->delete();
        return redirect('admin/order/listorder')->with(
            'status',
            'Xóa hẳn đơn hàng thành công!'
        );
    }
}
