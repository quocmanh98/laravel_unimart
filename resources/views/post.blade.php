@extends('layouts.pople')
@section('content')
    @php
    use App\postcat;
    use App\post;
    use Illuminate\Support\Str;
    @endphp
    <div id="main-content-wp" class="clearfix blog-page">
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
                @if (count($listcatposts) > 0)
                    <div class="section" id="list-blog-wp">

                        <div class="section-detail">
                            @foreach ($listcatposts as $catpost)
                                @if (count($catpost->posts) > 0)
                                    <a href="{{ route('catpost', ['id'=>$catpost->id,'slug'=>str::slug($catpost->name)]) }}"
                                        class="list_post_content">{{ $catpost->name }}</a>
                                    <ul class="list-item" style="margin:15px 0px;">
                                        @foreach ($listposts as $post)
                                            @if ($post->postcat_id == $catpost->id)
                                                <li class="clearfix">
                                                    <a href="{{ route('detailpost', ['id'=>$post->id,'slug'=>str::slug($post->name)]) }}" title=""
                                                        class="thumb fl-left">
                                                        <img src="{{ $post->thumbnail }}" alt="Logo">
                                                    </a>
                                                    <div class="info fl-right">
                                                        <a href="{{ route('detailpost', ['id'=>$post->id,'slug'=>str::slug($post->name)]) }}" title=""
                                                            class="title">{{ $post->name }}</a>
                                                        <span class="create-date">Ngày tạo :
                                                            {{ $post->created_at }}</span>
                                                        <p class="desc">{{ $post->content }}</p>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif

                            @endforeach
                        </div>
                    </div>
                @else
                    <p style="color:red;">Không có bài viết nào trong hệ thống!</p>
                @endif
            </div>
            <div class="sidebar fl-left">
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
                                            <a href="{{ route('buynowproduct', ['slug'=>str::slug($productselling->name),'id'=> $productselling->id]) }}"
                                                title="Mua ngay" class="fl-left create_notification buy-now">Mua ngay</a>
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
