@extends('layouts.pople')
@section('content')
    @php
    use App\productcat;
    use App\product;
    use Illuminate\Support\Str;
    @endphp
    <div id="main-content-wp" class="home-page clearfix">
        <div class="wp-inner">
            <div class="secion" id="breadcrumb-wp">
                <div class="secion-detail">
                    <ul class="list-item clearfix">
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
                            <a href="{{ route('introduce') }}" title="">Giới thiệu</a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}" title="">Liên hệ</a>
                        </li>
                    </ul>
                </div>
                {{-- Tao hop thong bao thanh cong khi dat hang --}}
                {{-- @if (isset($_GET['success']))
                        <p class="success">{{ $_GET['success'] }}</p>
                    @endif --}}
                @if (session('status'))
                    <p class="success">{{ session('status') }}</p>
                @endif
            </div>
            <div class="main-content fl-right">
                <div class="section" id="slider-wp">
                    @isset($listsliders)
                        <div class="section-detail">
                            @foreach ($listsliders as $slider)
                                <div class="item">
                                    <img src="{{ asset($slider->image_slider) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                    @endisset
                </div>
                <div class="section" id="support-wp">
                    <div class="section-detail">
                        <ul class="list-item clearfix">
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('public/image/icons/icon-1.png') }}">
                                </div>
                                <h3 class="title">Miễn phí vận chuyển</h3>
                                <p class="desc">Tới tận tay khách hàng</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('public/image/icons/icon-2.png') }}">
                                </div>
                                <h3 class="title">Tư vấn 24/7</h3>
                                <p class="desc">1900.9999</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('public/image/icons/icon-3.png') }}">
                                </div>
                                <h3 class="title">Tiết kiệm hơn</h3>
                                <p class="desc">Với nhiều ưu đãi cực lớn</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('public/image/icons/icon-4.png') }}">
                                </div>
                                <h3 class="title">Thanh toán nhanh</h3>
                                <p class="desc">Hỗ trợ nhiều hình thức</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('public/image/icons/icon-5.png') }}">
                                </div>
                                <h3 class="title">Đặt hàng online</h3>
                                <p class="desc">Thao tác đơn giản</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="section" id="feature-product-wp">
                    @isset($listproductspeeks)
                        @if (count($listproductspeeks) > 0)
                            <div class="section-head">
                                <h3 class="section-title">Sản phẩm nổi bật</h3>
                            </div>

                            <div class="section-detail">
                                <ul class="list-item">
                                    @foreach ($listproductspeeks as $speek)
                                        <li>
                                            <a href="{{ route('detailproduct',['id'=>$speek->id,'slug'=>str::slug($speek->name)]) }}" title="" class="thumb">
                                                <img src="{{ asset($speek->thumbnail) }}">
                                            </a>
                                            <a href="{{ route('detailproduct',['id'=>$speek->id,'slug'=>str::slug($speek->name)]) }}" title=""
                                                class="product-name">{{ $speek->name }}</a>
                                            <div class="price">
                                                <span
                                                    class="new">{{ number_format($speek->price, 0, ',', '.') }}đ</span>
                                            </div>
                                            <div class="action clearfix">
                                                <a href="{{ route('cart.add', $speek->id) }}" data-id="{{ $speek->id }}"
                                                    title="Thêm giỏ hàng"
                                                    class="add-cart fl-left create_notification">Thêm giỏ hàng</a>
                                                    <a href="{{ route('buynowproduct',['slug'=>str::slug($speek->name),'id'=> $speek->id]) }}" title="Mua ngay" class="buy-now fl-right">Mua ngay</a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p style="color:red;margin-top:15px;">Không có sản phẩm nổi bật nào trong hệ thống!</p>
                        @endif
                    @endisset
                </div>

                <div class="section" id="list-product-wp">
                    @isset($productcats)
                        @if(count($productcats)>0)
@foreach ($productcats as $cat)
                            @if (count(product::where('productcat_id','=',$cat->id)
                            ->where('disabler','=','active')->get()) > 0)
                                <div class="section-head">
                                    <a href="{{ route('index', ['id' => $cat->id.'-'.str::slug($cat->catname)]) }}">
                                        <h3 class="section-title">{{ $cat->catname }}</h3>
                                    </a>
                                </div>
                                <div class="section-detail">
                                    <ul class="list-item clearfix">
                                        @foreach ($products as $product)
                                            @if ($product->productcat_id == $cat->id)
                                                <li>
                                                    <a href="{{ route('detailproduct',['id'=>$product->id,str::slug($product->name)] ) }}" title=""
                                                        class="thumb">
                                                        <img src="{{ asset($product->thumbnail) }}">
                                                    </a>
                                                    <a href="{{ route('detailproduct',['id'=>$product->id,'slug'=>str::slug($product->name)] ) }}" title=""
                                                        class="product-name">{{ $product->name }}</a>
                                                    <div class="price">
                                                        <span
                                                            class="new">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                                    </div>
                                                    <div class="action clearfix">
                                                        <a href="{{ route('cart.add', $product->id) }}"
                                                            data-id="{{ $product->id }}" 
                                                            title="Thêm giỏ hàng"
                                                            class="add-cart fl-left create_notification">Thêm giỏ hàng</a>
                                                            <a href="{{ route('buynowproduct', ['slug'=>str::slug($product->name),'id'=> $product->id]) }}" title="Mua ngay" class="buy-now fl-right">Mua ngay</a>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endforeach
                        @else
                         <p style="color:#e74c3c;">Không có sản phẩm nào phù hợp tiêu chí tìm kiếm!</p>
                        @endif
                        
                    @endisset
                </div>
            </div>

            <div class="sidebar fl-left">
                <div class="section" id="category-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Danh mục sản phẩm</h3>
                    </div>
                    @isset($productcats_sidebar)
                        <div class="secion-detail">
                            <ul class="list-item">
                                @foreach ($productcats_sidebar as $item)
                                    @if (count(product::where('productcat_id','=',$item->id)->where('disabler','=','active')->get()) > 0)
                                        <li>
                                            {{-- <a href="{{request()->fullUrlwithQuery(['id'=>$item->id])}}" title="">{{$item->catname}}</a> --}}
                                            {{-- Su dung cau truc duoi de lam moi trang --}}
                                            <a href="{{ route('index', ['id' => $item->id.'-'.str::slug($item->catname)]) }}" title="">{{ $item->catname }}</a>
                                            @if (count($item->products) > 0)
                                                <ul class="sub-menu">
                                                    @foreach ($the_firms as $the_firm)
                                                        @if ($the_firm->productcat_id == $item->id)
                                                            <li>
                                                                {{-- <a href="{{request()->fullUrlwithQuery(['the_firm'=>$the_firm->the_firm])}}" title="">{{$the_firm->the_firm}}</a> --}}
                                                                <a href="{{ route('index', ['the_firm' => $the_firm->the_firm]) }}"
                                                                    title="">{{ $the_firm->the_firm }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endisset
                </div>
                <div class="section" id="selling-wp">
                    <div class="section-head">
                        <h3 class="section-title">Sản phẩm bán chạy</h3>
                    </div>
                    <div class="section-detail">
                        @isset($listproductsellings)
                            <ul class="list-item">
                                @foreach ($listproductsellings as $productselling)
                                    <li class="clearfix">
                                        <a href="{{ route('detailproduct', ['id'=>$productselling->id,'slug'=>str::slug($productselling->name)]) }}" title=""
                                            class="thumb fl-left">
                                            <img src="{{ $productselling->thumbnail }}" alt="Logo">
                                        </a>
                                        <div class="info fl-right">
                                            <a href="{{ route('detailproduct', ['id'=>$productselling->id,'slug'=>str::slug($productselling->name)]) }}" title=""
                                                class="product-name">{{ $productselling->name }}</a>
                                            <div class="price">
                                                <span
                                                    class="new">{{ number_format($productselling->price, 0, ',', '.') }}đ</span>
                                            </div>
                                            <a href="{{ route('buynowproduct', ['slug'=>str::slug($productselling->name),'id'=> $productselling->id]) }}" title="Mua ngay" class="buy-now fl-left">Mua ngay</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endisset
                    </div>
                </div>
                <div class="section" id="banner-wp">
                    <div class="section-detail">
                        @foreach ($banners as $banner)
                            <a href="" title="" class="thumb">
                                <img src="{{ asset($banner->img_banner) }}" style="margin-bottom:15px;" alt="Logo">
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
