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
                <div class="section" id="list-blog-wp">

                    <div class="section-detail">
                        <a href="{{ route('catpost', ['id'=>$catpost->id,'slug'=>str::slug($catpost->name)]) }}"
                            class="cat_post">{{ $catpost->name }}</a>
                        <ul class="list-item">
                            @foreach ($posts as $post)
                                <li class="clearfix">
                                    <a href="{{ route('detailpost', ['id'=>$post->id,'slug'=>str::slug($post->name)]) }}" title="" class="thumb fl-left">
                                        <img src="{{ asset($post->thumbnail) }}" alt="Logo">
                                    </a>
                                    <div class="info fl-right">
                                        <a href="{{ route('detailpost',['id'=>$post->id,'slug'=>str::slug($post->name)]) }}" title=""
                                            class="title">{{ $post->name }}</a>
                                        <span class="create-date">Ngày tạo : {{ $post->created_at }}</span>
                                        <p class="desc">{{ $post->content }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
            <div class="sidebar fl-left">
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
