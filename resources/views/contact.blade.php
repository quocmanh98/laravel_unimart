@extends('layouts.pople')
@section('content')
    @php
    use App\postcat;
    use App\post;
    use Illuminate\Support\Str;
    @endphp
    <div id="main-content-wp" class="clearfix detail-blog-page">
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
                <div class="section" id="detail-blog-wp">
                    @isset($pages)
                        @if (count($pages) > 0)
                            @foreach ($pages as $page)
                                <div class="section-head clearfix">
                                    <h3 class="section-title">{{ $page->title }}</h3>
                                </div>
                                <div class="section-detail">
                                    <span class="create-date">"{{ $page->birthday }}"</span>
                                    <div class="detail">
                                        <p style="text-align: center;">
                                            <img class="img_page" src="{{ asset($page->thumbnail) }}" alt="Logo">
                                        </p>
                                        <p>{{ $page->content }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p style="color:red;">Không có trang liên hệ nào trong hệ thống!</p>
                        @endif
                    @endisset

                </div>
                <div class="section" id="social-wp">
                    <div class="section-detail">
                        <div class="fb-like" data-href="" data-layout="button_count" data-action="like"
                            data-size="small" data-show-faces="true" data-share="true"></div>
                        <div class="g-plusone-wp">
                            <div class="g-plusone" data-size="medium"></div>
                        </div>
                        <div class="fb-comments" id="fb-comment" data-href="" data-numposts="5"></div>
                    </div>
                </div>
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
                                            <img src="{{ asset($productselling->thumbnail) }}" alt="Logo">
                                        </a>
                                        <div class="info fl-right">
                                            <a href="{{ route('detailproduct', ['id'=>$productselling->id,'slug'=>str::slug($productselling->name)]) }}" title=""
                                                class="product-name">{{ $productselling->name }}</a>
                                            <div class="price">
                                                <span
                                                    class="new">{{ number_format($productselling->price, 0, ',', '.') }}đ</span>
                                            </div>
                                            <a href="{{ route('buynowproduct', ['slug'=>str::slug($productselling->name),'id'=> $productselling->id]) }}" title="Mua ngay"
                                                class="fl-left create_notification buy-now">Mua ngay</a>
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
