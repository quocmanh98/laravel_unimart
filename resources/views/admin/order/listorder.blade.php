@extends('layouts.admin')
@section('content')
    @isset($customers)
        <div id="content" class="container-fluid">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                    <h5 class="m-0 ">Danh sách Khách hàng</h5>
                    <div class="form-search form-inline" style="float:none;">
                        <form action="#" class="form_search">
                            <input style="padding: 5px; margin-left:10px" type="" class="form-control form-search" name="keyword"
                                value="{{ request()->input('keyword') }}" placeholder="Tìm kiếm">
                            <input style="padding: 5px; margin-left:10px" type="submit" name="btn-search" value="Tìm kiếm"
                                class="btn btn-primary">
                        </form>
                    </div>

                </div>
                <div class="card-header">
                    <div class="text-info">Chọn lấy 1 đơn hàng chờ xư lý để kích hoạt(chọn trên MKH là duy nhất)</div>
                    <div style="width:300px;">Tên khách hàng : {{ $fullnamecustomer }} </div>
                    <!-- {{-- <div  style="width:300px;">Tên khách hàng : @if (@isset($fullnamecustomer)){{$fullnamecustomer}} @endif </div> --}} -->
                    <div style="width:300px;">Tình trạng : {{ $status }} </div>
                    <!-- {{-- <div  style="width:300px;">Tình trạng :  @if (@isset($status)){{$status}} @endif </div> --}} -->
                    <div class="{{ $d_show }} p-0" style="width:500px;">Xử lý đơn hàng: <a
                            href="{{ route('successcustomer', $customers[0]->id) }}" class="btn btn-outline-success mr-3">Thành
                            công</a><a href="{{ route('cancelcustomer', $customers[0]->id) }}"
                            onclick="return confirm('Bạn có chắc hủy đơn hàng này không ?')"
                            class="btn btn-outline-dark mr-3">Hủy</a>
                            <a href="{{ route('deletecustomer', $customers[0]->id) }}"
                                onclick="return confirm('Bạn có chắc xóa vĩnh viễn đơn hàng này không ?')"
                                class="btn btn-outline-danger">Xóa</a></div>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/order/actioncustomer') }}" method="GET">
                        <table class="table table-striped table-checkall">
                            <thead>
                                <tr>
                                    <!-- {{-- <th >
                                        <input type="checkbox" name="checkall" >
                                    </th> --}} -->
                                    <th scope="col">STT</th>
                                    <th scope="col">Họ tên</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Địa chỉ</th>
                                    <th scope="col">Số điện thoại</th>
                                    {{-- <th scope="col">Ghi chú</th> --}}
                                    <th scope="col">Thành tiền</th>
                                    <th scope="col">Tình trạng</th>
                                    <th scope="col">Thanh toán</th>
                                    <th scope="col">Tg đặt hàng</th>
                                    <th scope="col">MaKH(CTĐH)</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    !isset($_GET['page']) ? ($t = 0) : ($t = 10 * ($_GET['page'] - 1));
                                @endphp
                                @foreach ($customers as $customer)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        {{-- <td >
                                                <input type="checkbox" name ="list_check[]" value={{$customer->id}} ">
                                            </td> --}}
                                        <td>{{ $t }}</td>
                                        <td>{{ $customer->fullname }}</td>
                                        <td class="column-email">{{ $customer->email }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        {{-- <td>{{$customer->note}}</td> --}}
                                        <td>{{ number_format($customer->subtotal, 0, ',', '.') }}đ</td>
                                        @if ($customer->status == 'Thành công')
                                            <td class="text-success">{{ $customer->status }}</td>
                                        @elseif($customer->status=="Chờ xử lý")
                                            <td class="text-danger">{{ $customer->status }}</td>
                                        @else
                                            <td class="">{{ $customer->status }}</td>
                                        @endif
                                        <td>{{ $customer->payment_method == 'at-home' ? 'Tại nhà' : 'Chuyển khoản' }}</td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($customer->created_at)) }}</td>
                                        <td><a href="{{ route('showordercustomer', $customer->id) }}">{{ $customer->MaKH }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('successcustomer', $customer->id) }}"
                                                class="btn btn-success btn-sm rounded-0 mb-2" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Success"><i
                                                    class="fas fa-check-square"></i></a>
                                            <a href="{{ route('cancelcustomer', $customer->id) }}"
                                                class="btn btn-dark btn-sm rounded-0 text-white mb-2" type="button"
                                                style="padding:4px 5px;" data-toggle="tooltip" data-placement="top"
                                                title="Disable"><i class="fas fa-microphone-alt-slash"></i></a>
                                                <br>
                                            <a href="{{ route('deletecustomer', $customer->id) }}"
                                                onclick="return confirm('Bạn có chắc xóa vĩnh viễn đơn hàng này không ?')"
                                                class="btn btn-danger btn-sm rounded-0 text-white float-left" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    @else
        <div id="content" class="container-fluid text-info">
            Chưa có đơn hàng nào trong hệ thống
        </div>
    @endisset
@endsection
