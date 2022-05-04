@extends('layouts.pople')
{{-- <link rel="stylesheet" href="{{asset('public/css/bootstrap/bootstrap.4.3.1.css')}}"> --}}
@section('content')
    @php
    use App\product;
    use App\postcat;
    use App\post;
    use Illuminate\Support\Str;
    @endphp
    <div id="main-content-wp" class="cart-page">
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
        <div id="wrapper" class="wp-inner clearfix">
            <div class="section" id="info-cart-wp">
                <div class="section-detail table-responsive">
                    @if (session('status'))
                        <div class="alert alert-success" style="padding:10px 10px;">
                                {{session('status')}}
                        </div>
                    @endif
                    <form class="form-control-lg" action="{{route('cart.update')}}" method="POST">
                        {{-- <!-- @csrf : Hình thức bảo mật của laravel cung cấp cho form  --> --}}
                        @csrf
                        {{ csrf_field() }}
                        @if(Cart::count()>0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>STT</td>
                                        <td>Mã sản phẩm</td>
                                        <td>Ảnh sản phẩm</td>
                                        <td>Tên sản phẩm</td>
                                        <td>Màu sắc sản phẩm</td>
                                        <td>Giá sản phẩm</td>
                                        <td>Số lượng</td>
                                        <td>Tình trạng SP</td>
                                        <td>Thành tiền</td>
                                        <td>Xóa</td>
                                    </tr>
                                </thead>
                                <tbody>
                                {{-- Do du lieu san pham     --}}
                                        @php
                                            $t=0;
                                        @endphp
                                        @foreach(Cart::content() as $row)
                                            @php
                                                $t++;
                                            @endphp
                                            <tr>
                                                <td>{{$t}}</td>
                                                <td>{{$row->options->masp}}</td>
                                                <td>
                                                    <a href="{{asset('/')}}" title="" class="thumb">
                                                        <img src="{{asset($row->options->thumbnail)}}" alt="">
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{route('detailproduct',['id'=>$row->id,'slug'=>str::slug($row->name)])}}" title="" class="name-product">{{$row->name}}</a>
                                                </td>
                                                <td>{{$row->options->color}}</td>
                                                <td>{{number_format($row->price,0,',','.')}}</td>
                                                <td>
                                                    <input type="number" data-id="{{$row->rowId}}" name="qty[{{$row->rowId}}]" min=1 max="{{product::find($row->id)->qty}}" value="{{$row->qty}}" class="num-order">
                                                </td>
                                                @if(product::find($row->id)->qty>0)
                                                    <td style="color:#007FFF;">Còn {{product::find($row->id)->qty}} sản phẩm </td>
                                                @else
                                                    <td style="color:#FF0000;">Tạm hết hàng</td>
                                                @endif
                                                <td id="sub-total-{{$row->rowId}}">{{number_format($row->total,0,',','.')}}đ</td>
                                                <td>
                                                    <a href="{{route('cart.remove',$row->rowId)}}" title="" class="del-product"><i id="hover_delete" class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7">
                                            <div class="clearfix">
                                                <p id="total-price" class="fl-right">Tổng giá: <strong>{{Cart::total()}}đ</strong></p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            <div class="clearfix">
                                                <div class="fl-right">
                                                    <!-- Baif 238 : Cập nhật giỏ hàng-->
                                                {{-- <input type="submit" style="padding: 9px; background:#3f5da6;color:white" value="CẬP NHẬT GIỎ HÀNG" class="btn" name="btn-update"> --}}
                                                    {{-- <a href="" title="" id="update-cart">Cập nhật giỏ hàng</a> --}}
                                                    <a href="{{route('cart.checkout')}}" title="" id="checkout-cart">Thanh toán</a>

                                                    {{-- <a href="{{route('cart.destroy')}}" style="padding:13px;background:#3f5da6;color:white;">XÓA GIỎ HÀNG</a> --}}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            @endif
                        </form>
                </div>
            </div>
            @if(Cart::count() >0)
            <div class="section" id="action-cart-wp">
                <div class="section-detail">
                    <p class="title">Click vào <span> số lượng </span> để thay đổi số lượng và cập nhật giỏ hàng. Click vào  <i id="hover_delete" class="fa fa-trash-o"> </i> để xóa sản phẩm khỏi giỏ hàng. Nhấn vào <span> thanh toán  </span>để hoàn tất mua hàng.</p>
                    <a href="{{route('product')}}" title="" id="buy-more">Mua tiếp</a><br/>
                    <a href="{{route('cart.destroy')}}" title="" id="delete-cart">Xóa giỏ hàng</a>
                </div>
            </div>
            @else
                <p style="color:red;">Không có sản phẩm nào trong giỏ hàng</p>
            @endif
        </div>
    </div>
@endsection
