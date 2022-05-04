@extends('layouts.pople')
@section('content')
    @php
    use App\product;
    use App\productcat;
    use Illuminate\Support\Str;
    @endphp
    <div id="main-content-wp" class="clearfix detail-product-page">
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
            </div>
            <div class="main-content fl-right">
                <div class="section" id="detail-product-wp">
                    <div class="section-detail clearfix">
                        <div class="thumb-wp fl-left">
                            <a href="{{ asset('/') }}" title="" id="main-thumb">
                                <img id="" src="{{ asset($product->thumbnail) }}" />
                            </a>

                        </div>
                        <div class="thumb-respon-wp fl-left">
                            <img src="" alt="">
                        </div>
                        <div class="info fl-right">
                            <h3 class="product-name">Tên sản phầm:{{ $product->name }}</h3>
                            <div class="desc">
                                <p>Bộ vi xử lý :Intel Core i505200U 2.2 GHz (3MB L3)</p>
                                <p>Cache upto 2.7 GHz</p>
                                <p>Bộ nhớ RAM :4 GB (DDR3 Bus 1600 MHz)</p>
                                <p>Đồ họa :Intel HD Graphics</p>
                                <p>Ổ đĩa cứng :500 GB (HDD)</p>
                            </div>
                            <div class="num-product">
                                <span class="title">Tình trạng: </span>
                                <span class="status">@if ($product->qty == 0)Hết hàng @else Còn {{ $product->qty }} sản phẩm @endif</span>
                            </div>
                            <p class="price" style="color:#466ac0;">Giá :
                                {{ number_format($product->price, 0, ',', '.') }}đ</p>
                            <p class="price" style="color:#466ac0;">Màu : {{ $product->color }}</p>
                            <form action="{{ route('cart.add', $product->id) }}" method="GET">
                                @csrf
                                <div id="num-order-wp">
                                    <p>Số lượng : <a title="giảm" id="minus" class="hidden_qty" style="margin-right:5px;"><i class="fa fa-minus"></i></a><input type="text" name="num_order" style="margin-right:5px;" min=1 value="1" id="num-order"><a
                                            title="tăng" id="plus" class="icon_plus" ><i class="fa fa-plus"></i></a></p>
                                </div>
                                <input type="submit" class="btnaddcart" data-id="{{ $product->id }}"
                                    style="background:green;color:white;padding:5px;" name="add_cart_many"
                                    value="Thêm giỏ hàng">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="section" id="post-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Mô tả sản phẩm</h3>
                    </div>
                    <div class="section-detail">
                        <p style="color:#e55039;">Click vào xem thêm để xem chi tiết mô tả sản phẩm </p>
                    </div>
                    <div id="seemore">
                        <p class="seemore">Xem thêm</p>
                        <div id="section_detail_product" class="section_detail_product">
                            {!! $product->description !!}
                            <p class="finishseemore">Ẩn đi</p>
                        </div>
                    </div>
                </div>
                <div class="section" id="same-category-wp">
                    <div class="section-head">
                        <h3 class="section-title">Cùng chuyên mục</h3>
                    </div>
                    <div class="section-detail">
                        @if (isset($products))
                            <ul class="list-item">
                                @foreach ($products as $item)
                                    <li>
                                        <a href="{{ route('detailproduct', ['id'=>$item->id,'slug'=>str::slug($item->name)]) }}" title="" class="thumb">
                                            <img src="{{ asset($item->thumbnail) }}" alt="Logo">
                                        </a>
                                        <a href="" title="" class="product-name">{{ $item->name }}</a>
                                        <div class="price">
                                            <span
                                                class="new">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                                        </div>
                                        <div class="action clearfix">
                                            <a href="{{ route('detailproduct', ['id'=>$item->id,'slug'=>str::slug($item->name)]) }}" title=""
                                                style="margin-left:45px;" class="add-cart fl-left">Chọn sản phẩm</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                    </div>

                </div>

            </div>
            <div class="sidebar fl-left">
                <div class="section" id="category-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Danh mục sản phẩm</h3>
                    </div>
                    @if (isset($productcats))
                        <div class="secion-detail">
                            @foreach ($productcats as $productcat)
                                @if (count(product::where('productcat_id','=',$productcat->id)->where('disabler','=','active')->get()) > 0)
                                    <ul class="list-item">
                                        <li>
                                            <a href="{{ route('product', 'id=' . $productcat->id) }}"
                                                title="">{{ $productcat->catname }}</a>
                                            {{-- hay cai url nay --}}
                                            @if (count($productcat->products) > 0)
                                                <ul class="sub-menu">
                                                    @foreach ($the_firms as $the_firm)
                                                        @if ($productcat->id == $the_firm->productcat_id)
                                                            <li>
                                                                <a href="{{ route('product', 'the_firm=' . $the_firm->the_firm) }}"
                                                                    title="">{{ $the_firm->the_firm }}</a>
                                                                {{-- hay cai url nay --}}
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    </ul>
                                @endif
                            @endforeach
                        </div>
                    @endif
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

