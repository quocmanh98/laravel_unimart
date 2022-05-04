{{-- @extends('layouts.pople') --}}
@extends('layouts.admin')
@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @isset($customer)
        <div id="content" class="container-fluid">
            <div class="card">
                {{-- <div class="card-header font-weight-bold d-flex justify-content-between align-items-center"> --}}
                <div class="card-header">
                    <h5 class="m-0">Khách hàng</h5>
                    <p class="my-1">Tình trạng : {{ $customer->status }}</p>
                    @if ($customer->status == 'Chờ xử lý')
                        <p class="my-1">Xử lý đơn hàng :  <a href="{{ route('successcustomer', $customer->id) }}"
                                class="btn btn-outline-success mr-3"> Thành công</a><a
                                href="{{ route('cancelcustomer', $customer->id) }}"
                                onclick="return confirm('Bạn có chắc hủy đơn hàng này không ?')"
                                class="btn btn-outline-dark mr-3">Hủy</a>
                                <a
                                href="{{ route('deletecustomer', $customer->id) }}"
                                onclick="return confirm('Bạn có chắc xóa vĩnh viễn đơn hàng này không ?')"
                                class="btn btn-outline-danger">Xóa</a>
                        </p>
                    @endif
                </div>
                <div class="card-body">
                    @if (!empty($customer))
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>Mã KH</td>
                                    <td>Họ tên</td>
                                    <td>Email</td>
                                    <td>Địa chỉ</td>
                                    <td>Số điện thoại</td>
                                    <td>Ghi chú</td>
                                    <td>Thanh toán</td>
                                    <td>Thời gian đặt hàng</td>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Do du lieu khach hang --}}
                                <tr>
                                    <td>{{ $customer->MaKH }}</td>
                                    <td>{{ $customer->fullname }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->address }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->note }}</td>
                                    <td>{{ $customer->payment_method == 'at-home' ? 'Tại nhà' : 'Chuyển khoản' }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($customer->created_at)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <h3 style="padding-left:20px;">Thông tin đơn hàng</h3>
        <div id="content" class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @if (!empty($order_customer))
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>STT</td>
                                    <td>MaKH</td>
                                    <td>Mã sản phẩm</td>
                                    <td>Ảnh sản phẩm</td>
                                    <td>Tên sản phẩm</td>
                                    <td>Giá sản phẩm</td>
                                    <td>Số lượng</td>
                                    <td>Màu sắc</td>
                                    <td>Thành tiền</td>
                                    {{-- <td>Thanh toán</td> --}}
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Do du lieu san pham --}}
                                @php
                                    $t = 0;
                                @endphp
                                @foreach ($order_customer as $row)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td>{{ $t }}</td>
                                        <td>{{ $row->MaKH }}</td>
                                        <td>{{ $row->masp }}</td>
                                        <td>
                                            <img src="{{ asset($row->thumbnail) }}" class="img-order" alt="Logo">
                                        </td>
                                        <td>
                                            {{ $row->name }}
                                        </td>
                                        <td>{{ number_format($row->price, 0, ',', '.') }}đ</td>
                                        <td>
                                            {{ $row->qty }}
                                        </td>
                                        <td>{{ $row->color }}</td>
                                        <td>{{ number_format($row->subtotal, 0, ',', '.') }}đ</td>
                                        {{-- <td>{{$row->payment}}</td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">
                                        <div class="clearfix">
                                            <p id="total-price" class="fl-right">TỔNG ĐƠN HÀNG:
                                                <strong>{{ number_format($sumorder, 0, ',', '.') }}đ</strong></p>
                                        </div>
                                    </td>
                                </tr>

                            </tfoot>
                        </table>
                    @endif

                </div>
            </div>
        </div>
    @else
        <div id="content" class="container-fluid text-info">
            Khách hàng này không tồn tại trong hệ thống!
        </div>
    @endisset
@endsection
