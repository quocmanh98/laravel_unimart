@extends('layouts.pople')
@section('content')
<div id="main-content-wp" class="checkout-page">
    <div class="section" id="breadcrumb-wp">
        <div class="wp-inner">
            <div class="section-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="{{route('index')}}" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="{{route('product')}}" title="">Sản phẩm</a>
                    </li>
                    <li>
                        <a href="{{route('post')}}" title="">Bài viết</a>
                    </li>
                    <li>
                        <a href="{{route('introduce',1)}}" title="">Giới thiệu</a>
                    </li>
                    <li>
                        <a href="{{route('introduce',2)}}" title="">Liên hệ</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- <form method="GET" action="#" name="form-checkout"> -->
    <form method="POST" action="{{route('insertcart')}}" name="form-checkout"> 
        @csrf
    <div id="wrapper" class="wp-inner clearfix">
        <div class="section" id="customer-info-wp">

            <div class="section-head">
                <h1 class="section-title">Thông tin khách hàng</h1>
            </div>
            <p style="color:#007F00;">Những input có dấu * là bắt buộc nhập</p>
            <div class="section-detail">

                    <div class="form-row clearfix">
                        <div class="form-col fl-left">
                            <label for="fullname">* Họ tên</label>
                            <input type="text" name="fullname" id="fullname" value="{{old('fullname')}}">
                            @error('fullname')
                                    <small id="" class="text-danger" style="color:red;" >{{$message}}</small>
                            @enderror
                            <!-- <small id="fullname_js" class="text-danger" style="color:red;" ></small> -->
                        </div>
                        <div class="form-col fl-right">
                            <label for="email">* Email</label>
                            <input type ="email" name="email" id="email" value="{{old('email')}}">
                            @error('email')
                                    <small id="" class="text-danger" style="color:red;">{{$message}}</small>
                            @enderror
                            <!-- <small id="email_js" class="text-danger" style="color:red;"></small> -->
                        </div>
                    </div>
                    <div class="form-row clearfix">
                        <div class="form-col fl-left">
                            <label for="address">* Địa chỉ</label>
                            <input type="text" name="address" id="address" value="{{old('address')}}">
                            @error('address')
                                 <small id="" class="text-danger" style="color:red;">{{$message}}</small>
                            @enderror
                            <!-- <small id="address_js" class="text-danger" style="color:red;"></small> -->
                        </div>
                        <div class="form-col fl-right">
                            <label for="phone">* Số điện thoại</label>
                            <input type="tel" name="phone" id="phone" value="{{old('phone')}}">
                            @error('phone')
                                <small id="" class="text-danger" style="color:red;">{{$message}}</small>
                            @enderror
                            <!-- <small id="tel_js" class="text-danger" style="color:red;"></small> -->
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <label for="notes">Ghi chú(có thể bỏ trống)</label>
                            <textarea name="note" id="notes" style="width=80px;" >{{old('note')}}</textarea>
                        </div>
                    </div>
            </div>
        </div>
        <div class="section" id="order-review-wp">
            <div class="section-head">
                <h1 class="section-title">Thông tin đơn hàng</h1>
            </div>
            <div class="section-detail">
                @if(Cart::count()>0)
                        <table class="shop-table">
                            <thead>
                                <tr>
                                    <td>Sản phẩm</td>
                                    <td>Tổng</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Cart::content() as $row)
                                     <tr class="cart-item">
                                        <td class="product-name">{{$row->name}}<strong class="product-quantity">x {{$row->qty}}</strong></td>
                                        <td class="product-total">{{number_format($row->total,0,',','.')}}đ</td>
                                    </tr>
                                 @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="order-total">
                                    <td>Tổng đơn hàng:</td>
                                    <td><strong class="total-price">{{Cart::total()}}đ</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                @endif
                <p>payment_method</p>
                <div id="payment-checkout-wp">
                    <ul id="payment_methods">
                        <li>
                            <input type="radio" id="direct-payment" name="payment_method" class="payment_method" value="online" >
                            <label for="direct-payment">Thanh toán online</label>
                        </li>
                        <li>
                            <input type="radio" id="payment-home" name="payment_method" class="payment_method" checked="checked" value="at-home" >
                            <!-- <input type="radio" id="payment-home" name="payment_method" class="payment_method" checked="checked" value="at-home" > -->
                            <label for="payment-home">Thanh toán tại nhà</label>
                        </li>
                    </ul>
                </div>
                <div class="place-order-wp clearfix">
                    <input type="submit" id="order-now" class="btn btn-primary" value="Thực hiện">
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

