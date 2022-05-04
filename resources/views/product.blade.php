@extends('layouts.pople')
@section('content')
    {{-- Khai bao thang nay de su dung cho danh muc san pham:Tu bang productcat tim ra cac san pham tuong ung de cho xuat hien ul li cho dep --}}
    @php
    use App\product;
    use App\productcat;
    use Illuminate\Support\Str;
    @endphp
    {{-- <p>Thời gian hiện tại (tính theo máy tính của bạn là): <span id="time"></span></p> --}}
    <div id="main-content-wp" class="clearfix category-product-page">
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
                <div class="section" id="list-product-wp">
                    <div class="section-head clearfix">
                        {{-- <h3 class="section-title fl-left">Laptop</h3> --}}
                        <div class="filter-wp fl-right">
                            <p class="desc" style="color:#0652DD">Hiển thị {{ $counts[1] }} trên
                                {{ $counts[0] }} sản phẩm</p>
                            <div class="form-filter">
                                {{-- <form method="GET" action="{{route('fillter')}}"> --}}

                                <form method="" action="#">
                                    @csrf
                                    <select id="select" name="select">
                                        <option value="0" {{ request()->input('select') == 0 ? 'selected=selected' : '' }}>Sắp xếp
                                        </option>
                                        <option value="1" {{ request()->input('select') == 1 ? 'selected=selected' : '' }}>Từ A-Z
                                        </option>
                                        <option value="2" {{ request()->input('select') == 2 ? 'selected=selected' : '' }}>Từ Z-A
                                        </option>
                                        <option value="3" {{ request()->input('select') == 3 ? 'selected=selected' : '' }}>Giá
                                            cao xuống thấp</option>
                                        <option value="4" {{ request()->input('select') == 4 ? 'selected=selected' : '' }}>Giá
                                            thấp lên cao</option>
                                    </select>
                                    <button type="submit">Lọc</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="section-detail">
                        @if (isset($products))
                            <ul class="list-item clearfix">
                                @if (count($products))
                                    @foreach ($products as $product)
                                        <li>
                                            <a href="{{ route('detailproduct',['id'=>$product->id,'slug'=>str::slug($product->name)] ) }}"
                                                title="{{ $product->name }}" class="thumb">
                                                <img src="{{ $product->thumbnail }}" alt="Img_product">
                                            </a>
                                            <a href="{{ route('detailproduct', ['id'=>$product->id,'slug'=>str::slug($product->name)]) }}" title=""
                                                class="product-name">{{ $product->name }}</a>
                                            <div class="price">
                                                <span
                                                    class="new">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                            </div>
                                            <div class="action clearfix">
                                                <a href="{{ route('cart.add', $product->id) }}"
                                                    data-id="{{ $product->id }}" 
                                                    title="Thêm giỏ hàng" class="add-cart fl-left create_notification">Thêm
                                                    giỏ hàng</a>
                                                    <a href="{{ route('buynowproduct', ['slug'=>str::slug($product->name),'id'=> $product->id]) }}" title="Mua ngay" class="buy-now fl-right">Mua ngay</a>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <a href="" title="" class="thumb">
                                        <p style="color:#e74c3c;">Không có sản phẩm nào phù hợp tiêu chí tìm kiếm!</p>
                                    </a>
                                @endif
                            </ul>
                        @endisset
                </div>
            </div>
            <div class="section" id="paging-wp">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        @if (@isset($products)) {{ $products->links() }} @endif
                    </ul>
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
                                        {{-- <!-- -- <a href="{{request()->fullUrlwithQuery(['id'=>$productcat->id])}}" title="">{{$productcat->catname}}</a> -- --> --}}
                                        <!-- -- Su dung route de lam moi url sach se hon -- -->
                                        <a href="{{ route('product', ['id' => $productcat->id.'-'.str::slug($productcat->catname)]) }}"
                                            title="">{{ $productcat->catname }}</a>
                                        @if (count($productcat->products) > 0)
                                            <ul class="sub-menu">
                                                @foreach ($the_firm_for_menu_slidebar as $the_firm)
                                                    @if ($productcat->id == $the_firm->productcat_id)
                                                        <li>
                                                            {{-- <!-- -- <a href="{{request()->fullUrlwithQuery(['the_firm'=>$the_firm->the_firm])}}" title="">{{$the_firm->the_firm}}</a> -- --> --}}
                                                            <a href="{{ route('product', ['the_firm' => $the_firm->the_firm]) }}"
                                                                title="">{{ $the_firm->the_firm }}</a>
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
            <div class="section" id="filter-product-wp">
                <div class="section-head">
                    <h3 class="section-title">Bộ lọc</h3>
                </div>
                <div class="section-detail">
                    {{-- <form method="GET" action="{{route('fillter')}}"> --}}
                    <form method="" action="#">
                        @csrf
                        <table>
                            <thead>
                                <tr>
                                    <td colspan="2">Giá</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="radio" id="price-5" name="price" value=5></td>
                                    <td><label for="price-5">Dưới 5.000.000đ</label></td>
                                </tr>
                                <tr>
                                    <td><input type="radio" id="price-1" name="price" value=1></td>
                                    <td><label for="price-1">5.000.000đ - 10.000.000đ</label></td>
                                </tr>
                                <tr>
                                    <td><input type="radio" id="price-2" name="price" value=2></td>
                                    <td><label for="price-2">10.000.000đ - 15.000.000đ</label></td>
                                <tr>
                                    <td><input type="radio" id="price-3" name="price" value=3></td>
                                    <td><label for="price-3">15.000.000đ - 20.000.000đ</label></td>
                                </tr>
                                <tr>
                                    <td><input type="radio" id="price-4" name="price" value=4></td>
                                    <td><label for="price-4">Trên 20.000.000đ</label></td>
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <thead>
                                <tr>
                                    <td colspan="2">Hãng</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($the_firms))
                                    @foreach ($the_firms as $the_firm)
                                        <tr>
                                            <td><input type="radio" name="brand" id="{{ $the_firm->the_firm }}"
                                                    value="{{ $the_firm->the_firm }}"></td>
                                            {{-- <td>{{$the_firm->the_firm}}</td> --}}
                                            <td><label
                                                    for="{{ $the_firm->the_firm }}">{{ $the_firm->the_firm }}</label>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                        </tbody>
                    </table>
                    <table>
                        <thead>
                            <tr>
                                <td colspan="2">Loại</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($productcats))
                                @foreach ($productcats as $productcat)
                                @if (count(product::where('productcat_id','=',$productcat->id)
                            ->where('disabler','=','active')->get()) > 0)
                                        <tr>
                                            <td><input type="radio" name="species" id="{{ $productcat->id }}"
                                                    value={{ $productcat->id }}></td>
                                            {{-- <td>{{$productcat->catname}}</td> --}}
                                            <td><label
                                                    for="{{ $productcat->id }}">{{ $productcat->catname }}</label>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <input type="submit" name="select_fillter" value="Submit">
                </form>
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
