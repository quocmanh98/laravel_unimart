<!DOCTYPE html>
<html>

<head>
<title>Unimart-shop</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="{{ asset('public/css/bootstrap/bootstrap-theme.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('public/css/reset.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/css/carousel/owl.carousel.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/css/carousel/owl.theme.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />


    <link href="{{ asset('public/css/stylepople.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/css/responsive.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('public/js/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/js/elevatezoom-master/jquery.elevatezoom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/js/bootstrap/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/js/carousel/owl.carousel.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/js/main.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/js/app.js') }}" type="text/javascript"></script>
    {{-- import thu vien sweetalert --}}
    <script src="{{ asset('public/js/sweetalert1.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/js/sweetalert2.js') }}" type="text/javascript"></script>

    {{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> --}}

    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Khai bao meta de su dung ajax trong laravel --}}
</head>

<body>
    <div id="site">
        <div id="container">
            <div id="header-wp">
                <div id="head-top" class="clearfix">
                    <div class="wp-inner">
                        <a href="" title="" id="payment-link" class="fl-left">Hình thức thanh toán</a>
                        <div id="main-menu-wp" class="fl-right">
                            <ul id="main-menu" class="clearfix">
                                <li>
                                    <a href="{{ route('index') }}" title="">Trang chủ</a>
                                </li>
                                <li>
                                    {{-- <a href="san-pham" title="">Sản phẩm</a> --}}
                                    <a href="{{ route('product') }}" title="">Sản phẩm</a>
                                </li>
                                <li>
                                    <a href="{{ route('post') }}" title="">Bài viết</a>
                                </li>
                                <li>
                                    <a href="{{ route('introduce') }}" title="">Giới thiệu</a>
                                </li>
                                <li>
                                    <a href="{{ route('contact') }}" title="">Liên hệ</a>
                                </li>
                                <li>
                                    <a href="" title="" id="order_lookup">Tra đơn hàng</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="head-body" class="clearfix">
                    <div class="wp-inner">
                        <a href="{{ asset('/') }}" title="" id="logo" class="fl-left"><img
                                src="{{ asset('public/image/icons/logo.png') }}" /></a>
                        <div id="search-wp" class="fl-left">
                            <form action="#">
                                <input type="text" name="keyword" value="{{ old('keyword') }}" id="s"
                                    placeholder="Nhập từ khóa tìm kiếm tại đây!">
                                <button type="submit" id="sm-s">Tìm kiếm</button>
                                <div id="search_product">
                                    {{-- Tim du lieu ajax tra ve --}}
                                </div>
                            </form>
                        </div>
                        <div id="action-wp" class="fl-right">
                            <div id="advisory-wp" class="fl-left">
                                <span class="title">Tư vấn</span>
                                <span class="phone">0987.654.321</span>
                            </div>
                            <div id="btn-respon" class="fl-right"><i class="fa fa-bars"
                                    aria-hidden="true"></i></div>
                            <a href="{{ route('showcart') }}" title="giỏ hàng" id="cart-respon-wp"
                                class="fl-right">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span id="num_respon">@if (Cart::count() > 0) {{ Cart::count() }} @endif </span>
                            </a>
                            <div id="cart-wp" class="fl-right">
                                <div id="btn-cart">
                                    <a href="{{ route('showcart') }}" style="color:white;"><i
                                            class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                                    <span id="num">@if (Cart::count() > 0) {{ Cart::count() }} @endif </span>
                                </div>
                                @if (Cart::count() > 0)
                                    <div id="dropdown">
                                        <p class="desc">Có <span>{{ Cart::count() }} </span>sản phẩm trong
                                            giỏ hàng</p>
                                        <ul class="list-cart">
                                            @foreach (Cart::content() as $row)
                                                <li class="clearfix">
                                                    <a href="" title="" class="thumb fl-left">
                                                        <img src="{{ asset($row->options->thumbnail) }}" alt="">
                                                    </a>
                                                    <div class="info fl-right">
                                                        <a href="" title=""
                                                            class="product-name">{{ $row->name }}</a>
                                                        <p class="price">Giá :
                                                            {{ number_format($row->price, 0, ',', '.') }}</p>
                                                        <p class="qty">Số lượng:
                                                            <span>{{ $row->qty }}</span>
                                                        </p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="total-price clearfix">
                                            <p class="title fl-left">Tổng:</p>
                                            <p class="price fl-right">{{ Cart::total() }}đ</p>
                                        </div>
                                        <div class="action-cart clearfix">
                                            <a href="{{ route('showcart') }}" title="Giỏ hàng"
                                                class="view-cart fl-left">Giỏ hàng</a>
                                            <a href="{{ route('cart.checkout') }}" title="Thanh toán"
                                                class="checkout fl-right">Thanh toán</a>
                                        </div>

                                    </div>
                                @else
                                    <div id="dropdown" style="min-height:10px">
                                        <p class="title fl-left" style="color:red;">Không có sản phẩm nào trong giỏ
                                            hàng</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div id="main-content-wp" class="home-page clearfix">
                <div id="order_customer" class="order"></div>
                @yield('content')
            </div>

            <div id="footer-wp">
                <div id="foot-body">
                    <div class="wp-inner clearfix">
                        <div class="block" id="info-company">
                            <h3 class="title">ISMART</h3>
                            <p class="desc">ISMART luôn cung cấp luôn là sản phẩm chính hãng có thông tin rõ
                                ràng, chính sách ưu đãi cực lớn cho khách hàng có thẻ thành viên.</p>
                            <div id="payment">
                                <div class="thumb">
                                    <img src="{{ asset('public/image/icons/img-foot.png') }}" alt="logo">
                                </div>
                            </div>
                        </div>
                        <div class="block menu-ft" id="info-shop">
                            <h3 class="title">Thông tin cửa hàng</h3>
                            <ul class="list-item">
                                <li>
                                    <p>106 - Trần Bình - Cầu Giấy - Hà Nội</p>
                                </li>
                                <li>
                                    <p>0987.654.321 - 0989.989.989</p>
                                </li>
                                <li>
                                    <p>vshop@gmail.com</p>
                                </li>
                            </ul>
                        </div>
                        <div class="block menu-ft policy" id="info-shop">
                            <h3 class="title">Chính sách mua hàng</h3>
                            <ul class="list-item">
                                <li>
                                    <a href="" title="">Quy định - chính sách</a>
                                </li>
                                <li>
                                    <a href="" title="">Chính sách bảo hành - đổi trả</a>
                                </li>
                                <li>
                                    <a href="" title="">Chính sách hội viện</a>
                                </li>
                                <li>
                                    <a href="" title="">Giao hàng - lắp đặt</a>
                                </li>
                            </ul>
                        </div>
                        <div class="block" id="newfeed">
                            <h3 class="title">Bảng tin</h3>
                            <p class="desc">Đăng ký với chung tôi để nhận được thông tin ưu đãi sớm nhất</p>
                            <div id="form-reg">
                                <form method="POST" action="">
                                    <input type="email" name="email" id="email" placeholder="Nhập email tại đây">
                                    <button type="submit" id="sm-reg">Đăng ký</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="foot-bot">
                    <div class="wp-inner">
                        <p id="copyright">© Bản quyền thuộc về unitop.vn | Php Master</p>
                    </div>
                </div>
            </div>
        </div>
        <div id="menu-respon">
            <a href="?" title="" class="logo">VSHOP</a>
            <div id="menu-respon-wp">
                <ul class="" id="main-menu-respon">
                    <li>
                        <a href="{{ route('index') }}" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="{{ route('product') }}" title="">Sản phẩm</a>
                    </li>
                    <li>
                        <a href="{{ route('post') }}" title="">Bài viết</a>
                    </li>
                    <li>
                        <a href="{{ route('introduce', 1) }}" title="">Giới thiệu</a>
                    </li>
                    <li>
                        <a href="{{ route('introduce', 2) }}" title="">Liên hệ</a>
                    </li>

                </ul>
            </div>
        </div>
        <div id="btn-top"><img src="{{ asset('public/image/icons/icon-to-top.png') }}" alt="" /></div>
        <div id="fb-root"></div>
        <script>
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=849340975164592";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
</body>

</html>
