@extends('layouts.pople')
@section('content')
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
                            <a href="{{ route('introduce', 1) }}" title="">Giới thiệu</a>
                        </li>
                        <li>
                            <a href="{{ route('introduce', 2) }}" title="">Liên hệ</a>
                        </li>
                    </ul>
                </div>
            </div>
            <style>
                .error {
                    background: #fa983a;
                    padding: 15px 15px;
                }
                .title_error {
                    font-size: 26px;
                    padding: 5px;
                    color:#1e3799;
                }
                .note {
                    font-size: 22px;
                    padding: 5px;
                    color:#1e3799;
                }
            </style>
            <div class="error">
                <p class="title_error">Không tìm thấy trang yêu cầu :</p>
                <p class="note">Click vào trang chủ để quay lại trang chủ</p>
                <p class="note">Click vào Sản phẩm để quay lại trang sản phẩm</p>
                <p class="note">Click vào Bài viết để quay lại trang bài viết</p>
                <p class="note">Click vào trang Giới thiệu để quay lại trang giới thiệu</p>
                <p class="note">Click vào trang Liên hệ để quay lại trang liên hệ</p>
            </div>
        </div>
    </div>
@endsection
