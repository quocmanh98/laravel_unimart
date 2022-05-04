<?php

use Illuminate\Support\Facades\Route;
// Khai bao thu vien auth khoi bi loi
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Phan nguoi dung
// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', 'ProductController@index')->name('index');
Route::get('san-pham.html', 'ProductController@product')->name('product');
// Route::get('product', 'ProductController@product')->name('product');
// // test form select ajax:cai nay oke
// Route::get('testajax','ProductController@testajax')->name('testajax');//cai nay oke
// // Xay dung Route de tim kiem bang ajax
Route::get('/search', 'ProductController@search')->name('search');
// // Route::get('/','ProductController@index')->name('product');
Route::get('chi-tiet-san-pham/{id}-{slug}', 'ProductController@detailproduct')
    ->where('id', '[0-9]+')
    ->name('detailproduct');
// Route::get('detailproduct/{id}', 'ProductController@detailproduct')
//     ->where('id', '[0-9]+')
//     ->name('detailproduct');
// Route::get('/actionfillter','ProductController@actionfillter')->name('fillter');
// // Xay dung route trang gioi thieu phia nguoi dung
Route::get('gioi-thieu.html', 'ProductController@introduce')->name('introduce');
// Route::get('/introduce', 'ProductController@introduce')->name('introduce');
// // Xay dung route trang lien he phia nguoi dung
Route::get('lien-he.html', 'ProductController@contact')->name('contact');
// // Xay dung route trang bai viet phia nguoi dung
Route::get('bai-viet.html', 'ProductController@post')->name('post');
// Route::get('/post', 'ProductController@post')->name('post');
Route::get('/chi-tiet-bai-viet/{id}-{slug}', 'ProductController@detailpost')
    ->where('id', '[0-9]+')
    ->name('detailpost');
Route::get('danh-muc/{id}-{slug}', 'ProductController@catpost')
    ->where('id', '[0-9]+')
    ->name('catpost');
// tra cuu don hang
Route::get('order_lookup', 'ProductController@order_lookup')->name(
    'order_lookup'
);
//  Show đơn hàng
Route::get('gio-hang.html', 'CartController@showcart')->name('showcart');
//  Xử lý ajax đơn hàng
//  Route::post('cart/updateajax/{id}','CartController@updateajax')->name('ajaxlaravel');
Route::post('cart/updateajax', 'CartController@updateajax')->name(
    'ajaxlaravel'
);
// Mua ngay san pham
Route::get('mua-ngay/{slug}/{id}', 'ProductController@buynowproduct')->name(
    'buynowproduct'
);
// Xu ly insert vao database khi nguoi dung mua luon san pham
Route::get(
    '/insertbuynowproduct/{id}',
    'ProductController@insertbuynowproduct'
)->name('insertbuynowproduct');
//  Tim kiem don hang
Route::get('cart/search', 'CartController@search')->name('searchorder');
// Tạo url đặt hàng sản phẩm
Route::get('cart/add/{id}', 'CartController@add')->name('cart.add'); //Đặt tên thế này để tý nữa để quay lại CartController gọi đến 1 url theo name

// addcart bang ajax(1 san pham)
Route::get('addcartajax', 'CartController@addcartajax');
// addcart bang ajax(nhieu san pham)
Route::get('cart/manyadd', 'CartController@manyadd')->name('addmanycartajax');

// Su dung ajax de tao thong bao thanh cong
// Route::get('cart/createajax','CartController@createajax')->name('cart.createajax');
Route::get('cart/ajaxorder', 'CartController@ajaxorder')->name(
    'cart.ajaxorder'
);

// Tạo url đặt hàng sản phẩm
Route::get('cart/remove/{rowId}', 'CartController@remove')->name('cart.remove');
//  Tạo url đặt hàng sản phẩm
Route::get('cart/destroy', 'CartController@destroy')->name('cart.destroy');
//  Cập nhật giỏ hàng, sử dụng phương thức là post
Route::post('cart/update', 'CartController@update')->name('cart.update');
//  Checkout cart
Route::get('cart-thanh-toan.html', 'CartController@checkout')->name(
    'cart.checkout'
);
//  Thanh toan don hang
Route::post('cart/insertcart', 'CartController@insertcart')->name('insertcart');

// ==================================================================================

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Cua bai 265:route vao dashboard
// Route::get('dashboard', 'DashboardController@show');
// Phan 24 bao 266: //Khi nguoi dung chua login ma vao trang dashboard thi ta phai rang buoc ho khong se bao loi bang middleware
// Route::get('dashboard', 'DashboardController@show')->middleware('auth');
// Phan 24 bai 267 :them duong dan admin sau chuyen huong ve dashboard
// Route::get('admin', 'DashboardController@show'); //Thuong lam trang unimart thuong them duong dan admin dang sau

// Phan 24 bai 268 : Hien thi danh sach quan tri vien
// Route::get('admin/user/list', 'AdminUserController@list');//->middleware('auth'); Thu cong cho rang buoc middleware de khoi loi khi nguoi dung co tinh truy cap vao admin

// Phan 24 bai 271 : them user
// Route::get('admin/user/add', 'AdminUserController@add');//->middleware('auth');
// Phan 24 bai 271 : them user
// Route::post('admin/user/store', 'AdminUserController@store');//->middleware('auth');

// Phan 24 bai 273 : Su dung Route::middleware('auth')->group(function) de khac phuc loi khi nguoi dung chua login co tinh di vao admin
// ===============================================================================================================
// Phan admin
Route::middleware('auth')->group(function () {
    // Route::get('dashboard', 'DashboardController@show')->name('dashboard'); //Khi nguoi dung chua login ma vao trang dashboard thi ta phai rang buoc ho khong se bao loi bang middleware
    Route::get('dashboard', 'DashboardController@show'); //Khi nguoi dung chua login ma vao trang dashboard thi ta phai rang buoc ho khong se bao loi bang middleware
    // On lai bai xu ly logout chuyen den login
    // Route::get('logout', 'DashboardController@logout'); //Xu ly dc nhung khoang thoat han tai khoan login duoc
    // Phan 24 bai 267 :them duong dan admin sau chuyen huong ve dashboard
    Route::get('admin', 'DashboardController@show'); //Thuong lam trang unimart thuong them duong dan admin dang sau
    // Xu ly nhieu ban ghi trang dashboah
    //     Route::get('admin/dashboard/deletecustomer/{id}', 'DashboardController@deletecustomer')->name('dashboard.deletecustomer'); //Thuong lam trang unimart thuong them duong dan admin dang sau
    //     Route::get('admin/dashboard/action', 'DashboardController@action')->name('dashboard.action'); //Thuong lam trang unimart thuong them duong dan admin dang sau

    //     // Phan 24 bai 268 : Hien thi danh sach quan tri vien
    Route::get('admin/user/list', 'AdminUserController@list'); //->middleware('auth'); Thu cong cho rang buoc middleware de khoi loi khi nguoi dung co tinh truy cap vao admin

    //     // Phan 24 bai 271 : them user
    Route::get('admin/user/add', 'AdminUserController@add'); //->middleware('auth');
    //     // Phan 24 bai 271 : them user
    Route::post('admin/user/store', 'AdminUserController@store'); //->middleware('auth');

    //     // Phan 24 bai 274 : Xoa user khoi he thong
    Route::get('admin/user/delete/{id}', 'AdminUserController@delete')->name(
        'delete_user'
    );
    //     // Phan 24 bai 276 : Thuc hien tac vu tren nhieu ban ghi
    Route::get('admin/user/action', 'AdminUserController@action'); //->middleware('auth');

    //      // Phan 24 bai 278 : Cap nhat thong tin nguoi dung
    Route::get('admin/user/edit/{id}', 'AdminUserController@edit')->name(
        'user.edit'
    ); //->middleware('auth');
    //      // Phan 24 bai 278 : Cap nhat thong tin nguoi dung
    Route::post('admin/user/update/{id}', 'AdminUserController@update')->name(
        'user.update'
    );
    // Quyền của các quản trị viên
    Route::get('admin/role/listuser', 'AdminRoleController@listuser');
    // Thêm quyền
    Route::get('admin/role/add', 'AdminRoleController@add'); //->middleware('auth');
    // // Xu ly them quyen moi
    Route::get('admin/role/storeaddrole', 'AdminRoleController@storeaddrole'); //->middleware('auth');
    // Sua quyen cua user
    Route::get(
        'admin/role/editrole/{id}',
        'AdminRoleController@editrole'
    )->name('editrole');
    // Cap nhat lai quyen cua user
    Route::get(
        'admin/role/updaterole/{id}',
        'AdminRoleController@updaterole'
    )->name('updaterole');
    // Xóa quyen cua user
    Route::get(
        'admin/role/deleterole/{id}',
        'AdminRoleController@deleterole'
    )->name('deleterole');

    //      // Module Page
    Route::get('admin/page/list', 'AdminPageController@list');
    // Thêm bai viet
    Route::get('admin/page/add', 'AdminPageController@add');
    Route::post('admin/page/store', 'AdminPageController@store');
    // cap nhat bai viet
    Route::get('admin/page/edit/{id}', 'AdminPageController@edit')->name(
        'edit_page'
    );
    Route::post('admin/page/update/{id}', 'AdminPageController@update')->name(
        'update_page'
    );
    // Vo hieu hoa bai viet cho page
    Route::get('admin/page/restore/{id}', 'AdminPageController@restore')->name(
        'restore_page'
    );
    // Khoi phuc bai viet cho page
    Route::get('admin/page/disable/{id}', 'AdminPageController@disable')->name(
        'disable_page'
    );
    // thuc hien tac vu tren nhieu ban ghi:kich hoat, vo hieu hoa, xoa vinh vien
    Route::get('admin/page/action', 'AdminPageController@action'); //->middleware('auth');
    // xoa bai viet(xoa tung bai viet vinh vien)
    Route::get('admin/page/delete/{id}', 'AdminPageController@delete')->name(
        'delete_page'
    );
    //     // // Module Post
    // Hien thi danh sach bai viet
    Route::get('admin/post/list', 'AdminPostController@list');
    // cap nhat bai viet
    Route::get('admin/post/edit/{id}', 'AdminPostController@edit')->name(
        'edit_post'
    );
    Route::post('admin/post/update/{id}', 'AdminPostController@update')->name(
        'update_post'
    );
    // vo hieu hoa bai viet
    Route::get('admin/post/disable/{id}', 'AdminPostController@disable')->name(
        'disablepost'
    );
    // Kich hoat lai bai viet
    Route::get('admin/post/restore/{id}', 'AdminPostController@restore')->name(
        'restorepost'
    );
    // Xoa vinh vien bai viet
    Route::get('admin/post/delete/{id}', 'AdminPostController@delete')->name(
        'delete_post'
    );
    // Them bai viet
    Route::get('admin/post/addpost', 'AdminPostController@addpost')->name(
        'add_post'
    );
    Route::post('admin/post/store', 'AdminPostController@store');
    // Thuc hien tren nhieu ban ghi
    Route::get('admin/post/actionpost', 'AdminPostController@actionpost');
    //  Module cat post
    // Them danh muc bai viet
    Route::get('admin/post/cat/addcat', 'AdminPostController@addcat');
    Route::post('admin/post/storeaddcat', 'AdminPostController@storeaddcat');
    // Cap nhat danh muc bai viet
    Route::get(
        'admin/post/cat/editcat/{id}',
        'AdminPostController@editcat'
    )->name('edit_cat_post');
    Route::get(
        'admin/post/cat/updatecat/{id}',
        'AdminPostController@updatecat'
    )->name('update_cat_post');
    // Vo hieu hoa danh muc bai viet
    Route::get(
        'admin/post/cat/disablecat/{id}',
        'AdminPostController@disablecat'
    )->name('disablecatpost');
    // Kich hoat lai danh muc bai viet
    Route::get(
        'admin/post/cat/activecat/{id}',
        'AdminPostController@activecat'
    )->name('activecatpost');
    // Xoa han danh muc bai viet
    Route::get(
        'admin/post/cat/deletecat/{id}',
        'AdminPostController@deletecat'
    )->name('delete_cat_post');

    //   Module Product
    //   Danh muc san pham
    // Them danh muc san pham
    Route::get(
        'admin/product/cat/addcatproduct',
        'AdminProductController@addcatproduct'
    );
    Route::post(
        'admin/product/storeaddcatproduct',
        'AdminProductController@storeaddcatproduct'
    );
    // Cap nhat danh muc san pham
    Route::get(
        'admin/product/cat/editcatproduct/{id}',
        'AdminProductController@editcatproduct'
    )->name('edit_cat_product');
    Route::get(
        'admin/product/cat/updatecatproduct/{id}',
        'AdminProductController@updatecatproduct'
    )->name('update_cat_product');
    // Vo hieu hoa danh muc san pham
    Route::get(
        'admin/product/cat/disablecatproduct/{id}',
        'AdminProductController@disablecatproduct'
    )->name('disablecatproduct');
    // Kich hoat lai danh muc san pham
    Route::get(
        'admin/product/cat/restorecatproduct/{id}',
        'AdminProductController@restorecatproduct'
    )->name('restorecatproduct');
    // Xoa vinh vien danh muc san pham
    Route::get(
        'admin/product/cat/deletecatproduct/{id}',
        'AdminProductController@deletecatproduct'
    )->name('delete_cat_product');
    // San pham
    // Them mau sac san pham
    Route::get(
        'admin/product/addcolorproduct',
        'AdminProductController@addcolorproduct'
    )->name('add_color_product');
    Route::post(
        'admin/product/storeaddcolorproduct',
        'AdminProductController@storeaddcolorproduct'
    );
    // Edit mau sac san pham
    Route::get(
        'admin/product/editcolorproduct/{id}',
        'AdminProductController@editcolorproduct'
    )->name('edit_color_product');
    // Edit mau sac san pham
    Route::post(
        'admin/product/updatecolorproduct/{id}',
        'AdminProductController@updatecolorproduct'
    )->name('update_color_product');
    // Xoa vinh vien mau sac san pham
    Route::get(
        'admin/product/deletecolorproduct/{id}',
        'AdminProductController@deletecolorproduct'
    )->name('delete_color_product');
    // Them hang san pham
    Route::get(
        'admin/product/add_company_product',
        'AdminProductController@add_company_product'
    )->name('add_company_product');
    Route::post(
        'admin/product/storeaddcompanyproduct',
        'AdminProductController@storeaddcompanyproduct'
    );
    // Edit hang san pham
    Route::get(
        'admin/product/edit_company_product/{id}',
        'AdminProductController@edit_company_product'
    )->name('edit_company_product');
    // Cap nhat hang san pham
    Route::post(
        'admin/product/update_company_product/{id}',
        'AdminProductController@update_company_product'
    )->name('update_company_product');
    // Xoa vinh vien hang san pham
    Route::get(
        'admin/product/delete_company_product/{id}',
        'AdminProductController@delete_company_product'
    )->name('delete_company_product');
    // Them san pham
    Route::get(
        'admin/product/addproduct',
        'AdminProductController@addproduct'
    )->name('addp_roduct');
    Route::post(
        'admin/product/storeproduct',
        'AdminProductController@storeproduct'
    );
    // Edit san pham
    Route::get(
        'admin/product/editproduct/{id}',
        'AdminProductController@editproduct'
    )->name('edit_product');
    Route::post(
        'admin/product/updateproduct/{id}',
        'AdminProductController@updateproduct'
    );
    // Vo hieu hoa san pham
    Route::get(
        'admin/product/disableproduct/{id}',
        'AdminProductController@disableproduct'
    )->name('disable_product');
    // Kich hoat san pham
    Route::get(
        'admin/product/restoreproduct/{id}',
        'AdminProductController@restoreproduct'
    )->name('restore_product');
    // Xoa vinh vien san pham
    Route::get(
        'admin/product/deleteproduct/{id}',
        'AdminProductController@deleteproduct'
    )->name('delete_product');
    // Thuc hien tac vu tren nhieu ban ghi
    Route::get(
        'admin/product/actionproduct',
        'AdminProductController@actionproduct'
    );
    // Hien danh sach san pham
    Route::get(
        'admin/product/listproduct',
        'AdminProductController@listproduct'
    );

    //     // Module slider
    // them slider
    Route::get('admin/slider/addslider', 'AdminsliderController@addslider');
    Route::post(
        'admin/slider/addstoreslider',
        'AdminsliderController@addstoreslider'
    );
    // edit slider
    Route::get(
        'admin/slider/editslider/{id}',
        'AdminsliderController@editslider'
    )->name('edit_slider');
    Route::post(
        'admin/slider/updateslider/{id}',
        'AdminsliderController@updateslider'
    )->name('update_slider');
    // disable slider
    Route::get(
        'admin/slider/disableslider/{id}',
        'AdminsliderController@disableslider'
    )->name('disable_slider');
    // restore slider
    Route::get(
        'admin/slider/restoreslider/{id}',
        'AdminsliderController@restoreslider'
    )->name('restore_slider');
    // xoa vinh vien slider
    Route::get(
        'admin/slider/deleteslider/{id}',
        'AdminsliderController@deleteslider'
    )->name('delete_slider');

    //    //  Module quang cao
    Route::get(
        'admin/advertisement/addadvertisement',
        'AdminadvertisementController@addadvertisement'
    )->name('add_advertisement');
    // xu ly them quang cao
    Route::post(
        'admin/advertisement/storeadvertisement',
        'AdminadvertisementController@storeadvertisement'
    );
    // xu ly edit quang cao
    Route::get(
        'admin/advertisement/editadvertisement/{id}',
        'AdminadvertisementController@editadvertisement'
    )->name('edit_banner');
    Route::post(
        'admin/advertisement/updateadvertisement/{id}',
        'AdminadvertisementController@updateadvertisement'
    )->name('update_banner');
    // xu ly disable quang cao
    Route::get(
        'admin/advertisement/disableadvertisement/{id}',
        'AdminadvertisementController@disableadvertisement'
    )->name('disable_banner');
    // xu ly kich hoat lai quang cao
    Route::get(
        'admin/advertisement/restoreadvertisement/{id}',
        'AdminadvertisementController@restoreadvertisement'
    )->name('restore_banner');
    // xu ly xoa quang cao
    Route::get(
        'admin/advertisement/deleteadvertisement/{id}',
        'AdminadvertisementController@deleteadvertisement'
    )->name('delete_banner');

    //     // // Module don hang
    Route::get('admin/order/listorder', 'AdminOrderController@listorder')->name(
        'list_order'
    );
    //  Show đơn hàng của khách hàng vua đăng ký cho khách hàng
    Route::get(
        'admin/order/showordercustomer/{id}',
        'AdminOrderController@showordercustomer'
    )->name('showordercustomer');
    //Xu ly cho don hang thanh cong
    Route::get(
        'admin/order/successcustomer/{id}',
        'AdminOrderController@successcustomer'
    )->name('successcustomer');
    //Xu ly huy don hang
    Route::get(
        'admin/order/cancelcustomer/{id}',
        'AdminOrderController@cancelcustomer'
    )->name('cancelcustomer');
    // // Xu ly xóa hẳn don hang
    Route::get(
        'admin/order/deletecustomer/{id}',
        'AdminOrderController@deletecustomer'
    )->name('deletecustomer');
    // // Tích hơp trình soạn thảo tinycloud
    Route::group(
        ['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']],
        function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        }
    );
});
