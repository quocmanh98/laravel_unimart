{{-- Phan 24 bai 266 --}}
@extends('layouts.admin')
@section('content')
@php
    use App\User;
@endphp
    @isset($customers)
        <div class="container-fluid py-4">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="row">
                <div class="col">
                    <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                        <div class="card-header">ĐƠN HÀNG THÀNH CÔNG</div>
                        <div class="card-body">
                            <h5 class="card-title">Số lượng : @isset($counts) {{ $counts['count_customers_success'] }}
                                @endisset</h5>
                            <h5 class="card-title">Tổng tiền : @isset($sum_subtotals)
                                {{ number_format($sum_subtotals['sum_subtotal_success'], 0, ',', '.') }}đ @endisset</h5>
                            <p class="card-text">Đơn hàng giao dịch thành công</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                        <div class="card-header">ĐANG XỬ LÝ</div>
                        <div class="card-body">
                            <h5 class="card-title">@isset($counts) {{ $counts['count_customers_Waiting'] }} @endisset</h5>
                            <p class="card-text">Số lượng đơn hàng đang xử lý</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                        <div class="card-header">DOANH SỐ</div>
                        <div class="card-body">
                            <h5 class="card-title">@isset($sum_subtotals)
                                    {{ number_format($sum_subtotals['sum_subtotal_success'] + $sum_subtotals['sum_subtotal_Waiting'], 0, ',', '.') }}đ
                                @endisset</h5>
                            <p class="card-text">Doanh số hệ thống</p>
                            <p class="card-text">Doanh số hệ thống(bao gồm đơn hàng thành công và chờ xử lý)</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                        <div class="card-header">ĐƠN HÀNG HỦY</div>
                        <div class="card-body">
                            <h5 class="card-title"> @isset($counts) {{ $counts['count_customers_cancel'] }} @endisset</h5>
                            <p class="card-text">Số đơn hàng bị hủy trong hệ thống</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end analytic  -->
            <div class="card">
                <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                    <h5 class="m-0 ">Danh sách Khách hàng</h5>
                    <div class="form-search form-inline">
                        <form action="#">
                            <input style="padding: 5px; margin-left:10px" type="" class="form-control form-search"
                                name="keyword" value="{{ request()->input('keyword') }}" placeholder="Tìm kiếm">
                            <input style="padding: 5px; margin-left:10px" type="submit" name="btn-search" value="Tìm kiếm"
                                class="btn btn-primary">
                        </form>
                    </div>
                </div>
                <div class="card-body">

                    <form action="" method="GET">
                        <table class="table table-striped table-checkall">
                            <thead>
                                <tr>
                                    {{-- <th style="width:30px;">
                                            <input type="checkbox" name="checkall">
                                        </th> --}}
                                    <th scope="col">STT</th>
                                    <th scope="col">Họ tên</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Địa chỉ</th>
                                    <th scope="col">Số điện thoại</th>
                                    <th scope="col">Ghi chú</th>
                                    <th scope="col">Thành tiền</th>
                                    <th scope="col">Tình trạng</th>
                                    <th scope="col">User xử lý</th>
                                    <th scope="col">Thanh toán</th>
                                    <th scope="col">Thời gian đặt hàng</th>
                                    <th scope="col">MaKH(CTĐH)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $t = 0;
                                @endphp
                                @foreach ($customers as $customer)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        {{-- <td>
                                                    <input type="checkbox" name ="list_check[]" value={{$customer->id}}>
                                                </td> --}}
                                        <td>{{ $t }}</td>
                                        <td>{{ $customer->fullname }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->note }}</td>
                                        <td>{{ number_format($customer->subtotal, 0, ',', '.') }}đ</td>
                                        @if ($customer->status == 'Thành công')
                                            <td class="text-success">{{ $customer->status }}</td>
                                        @elseif($customer->status=="Chờ xử lý")
                                            <td class="text-danger">{{ $customer->status }}</td>
                                        @else
                                            <td class="">{{ $customer->status }}</td>
                                        @endif
                                        <td>{{$customer->disabler!=''?user::find($customer->disabler)->name:''}}</td>
                                        <td>{{ $customer->payment_method=='at-home'?'Tại nhà':'Chuyển khoản' }}</td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($customer->created_at)) }}</td>
                                        <td><a
                                                href="{{ route('showordercustomer', $customer->id) }}">{{ $customer->MaKH }}</a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col">
                    <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                        <div class="card-header">ĐƠN HÀNG THÀNH CÔNG</div>
                        <div class="card-body">
                            <h5 class="card-title">Số lượng : 0</h5>
                            <h5 class="card-title">Tổng tiền : 0</h5>
                            <p class="card-text">Đơn hàng giao dịch thành công</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                        <div class="card-header">ĐANG XỬ LÝ</div>
                        <div class="card-body">
                            <h5 class="card-title">0</h5>
                            <p class="card-text">Số lượng đơn hàng đang xử lý</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                        <div class="card-header">DOANH SỐ</div>
                        <div class="card-body">
                            <h5 class="card-title">0</h5>
                            <p class="card-text">Doanh số hệ thống</p>
                            <p class="card-text">Doanh số hệ thống(bao gồm đơn hàng thành công và chờ xử lý)</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                        <div class="card-header">ĐƠN HÀNG HỦY</div>
                        <div class="card-body">
                            <h5 class="card-title">0</h5>
                            <p class="card-text">Số đơn hàng bị hủy trong hệ thống</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end analytic  -->
        </div>
    @endisset
    {{-- ===================================================================================== --}}
    <div class="container-fluid py-1">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                @isset($sliders) <h3 class="m-0 ">Danh sách slider : {{ count($sliders) }}</h3> @endisset
            </div>
            <div class="card-body">
                @isset($sliders)
                    @if (count($sliders) > 0)
                        <form action="" method="GET">
                            <table class="table table-striped table-checkall">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Ảnh</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Ngày cập nhật</th>
                                        <th scope="col">user tạo</th>
                                        <th scope="col">user cập nhật</th>
                                        <th scope="col">user vô hiệu hóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($sliders as $slider)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr class="{{ $slider->disabler != '' ? 'text-danger' : '' }}">
                                            <td>{{ $t }}</td>
                                            <td class="column-sliceder"><img src="{{ asset($slider->image_slider) }}"
                                                    class="img-sliceder" alt="Logo"></td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($slider->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($slider->updated_at)) }}</td>
                                            <td>{{ $slider->creator }}</td>
                                            <td>{{ $slider->repairer }}</td>
                                            <td>{{ $slider->disabler }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </form>
                    @endif
                @endisset
            </div>
        </div>
        {{-- Danh sach quang cao --}}
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                @isset($advertisements)
                    <h3 class="m-0 ">Danh sách các quảng cáo cho trang : {{ count($advertisements) }}</h3>
                @endisset

            </div>
            <div class="card-body">
                @isset($advertisements)
                    @if (count($advertisements) > 0)
                        <form action="" method="GET">
                            <table class="table table-striped table-checkall">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Ảnh</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Ngày cập nhật</th>
                                        <th scope="col">user tạo</th>
                                        <th scope="col">user cập nhật</th>
                                        <th scope="col">user vô hiệu hóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($advertisements as $advertisement)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr class="{{ $advertisement->disabler != '' ? 'text-danger' : '' }}">
                                            <td>{{ $t }}</td>
                                            <td class="column-sliceder"><img src="{{ asset($advertisement->img_banner) }}"
                                                    class="img-sliceder" alt="Logo"></td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($advertisement->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($advertisement->updated_at)) }}</td>
                                            <td>{{ $advertisement->creator }}</td>
                                            <td>{{ $advertisement->repairer }}</td>
                                            <td>{{ $advertisement->disabler }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </form>
                    @endif
                @endisset
            </div>
        </div>

        {{-- ===================================================== --}}
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                @isset($pages)
                    <h3 class="m-0 ">Danh sách các bài viết cho trang : {{ count($pages) }}</h3>
                @endisset
            </div>
            <div class="card-body">
                @isset($pages)
                    @if (count($pages) > 0)
                        <form action="" method="GET">
                            <table class="table table-striped table-checkall">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Ảnh</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Ngày cập nhật</th>
                                        <th scope="col">user tạo</th>
                                        <th scope="col">user cập nhật</th>
                                        <th scope="col">user vô hiệu hóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($pages as $page)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr class="{{ $page->disabler != '' ? 'text-danger' : '' }}">
                                            <td>{{ $t }}</td>
                                            <td class="column-sliceder"><img src="{{ asset($page->thumbnail) }}"
                                                    class="img-sliceder" alt="Logo"></td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($page->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($page->updated_at)) }}</td>
                                            <td>{{ $page->creator }}</td>
                                            <td>{{ $page->repairer }}</td>
                                            <td>{{ $page->disabler }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </form>
                    @endif
                @endisset
            </div>
        </div>
        {{-- ================================================================================== --}}
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                @isset($article_list)

                @endisset
                <h3 class="m-0 ">Danh sách danh mục bài viết : {{ count($article_list) }}</h3>
            </div>
            <div class="card-body">
                @isset($article_list)
                    @if (count($article_list) > 0)
                        <form action="" method="GET">
                            <table class="table table-striped table-checkall">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Tên danh mục</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Ngày cập nhật</th>
                                        <th scope="col">user tạo</th>
                                        <th scope="col">user cập nhật</th>
                                        <th scope="col">user vô hiệu hóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($article_list as $item)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr class="{{ $item->disabler != '' ? 'text-danger' : '' }}">
                                            <td>{{ $t }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($item->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($item->updated_at)) }}</td>
                                            <td>{{ $item->creator }}</td>
                                            <td>{{ $item->repairer }}</td>
                                            <td>{{ $item->disabler }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </form>
                    @endif
                @endisset
            </div>
        </div>
        {{-- ================================================================================== --}}
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                @isset($posts)
                    <h3 class="m-0 ">Danh sách bài viết : {{ count($posts) }}</h3>
                @endisset
            </div>
            <div class="card-body">
                @isset($posts)
                    @if (count($posts) > 0)
                        <form action="" method="GET">
                            <table class="table table-striped table-checkall">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Ảnh bài viêt</th>
                                        <th scope="col">Tiêu đề bài viết</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Ngày cập nhật</th>
                                        <th scope="col">user tạo</th>
                                        <th scope="col">user cập nhật</th>
                                        <th scope="col">user vô hiệu hóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($posts as $item)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr class="{{ $item->disabler != '' ? 'text-danger' : '' }}">
                                            <td>{{ $t }}</td>
                                            <td class="column-sliceder"><img src="{{ $item->thumbnail }}"
                                                    class="img-sliceder" alt="logo"></td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($item->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($item->updated_at)) }}</td>
                                            <td>{{ $item->creator }}</td>
                                            <td>{{ $item->repairer }}</td>
                                            <td>{{ $item->disabler }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                    @endif
                @endisset
            </div>
        </div>
        {{-- ================================================================================== --}}
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                @isset($product_portfolio)
                    <h3 class="m-0 ">Danh sách danh mục sản phẩm : {{ count($product_portfolio) }}</h3>
                @endisset
            </div>
            <div class="card-body">
                @isset($product_portfolio)
                    @if (count($product_portfolio) > 0)
                        <form action="" method="GET">
                            <table class="table table-striped table-checkall">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Tên danh mục</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Ngày cập nhật</th>
                                        <th scope="col">user tạo</th>
                                        <th scope="col">user cập nhật</th>
                                        <th scope="col">user vô hiệu hóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($product_portfolio as $item)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr class="{{ $item->disabler != '' ? 'text-danger' : '' }}">
                                            <td>{{ $t }}</td>
                                            <td>{{ $item->catname }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($item->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($item->updated_at)) }}</td>
                                            <td>{{ $item->creator }}</td>
                                            <td>{{ $item->repairer }}</td>
                                            <td>{{ $item->disabler }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                    @endif
                @endisset
            </div>
        </div>
        {{-- ================================================================================== --}}
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                @isset($products)
                    <h3 class="m-0 ">Danh sách sản phẩm : {{ count($products) }}</h3>
                @endisset
            </div>
            <div class="card-body">
                @isset($products)
                    @if (count($products) > 0)
                        <form action="" method="GET">
                            <table class="table table-striped table-checkall">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Ảnh SP</th>
                                        <th scope="col">Tên SP</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Ngày cập nhật</th>
                                        <th scope="col">user tạo</th>
                                        <th scope="col">user cập nhật</th>
                                        <th scope="col">user vô hiệu hóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($products as $item)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr class="{{ $item->disabler != '' ? 'text-danger' : '' }}">
                                            <td>{{ $t }}</td>
                                            <td class="column-sliceder"><img src="{{ $item->thumbnail }}"
                                                    class="img-sliceder" alt="Logo"></td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($item->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($item->updated_at)) }}</td>
                                            <td>{{ $item->creator }}</td>
                                            <td>{{ $item->repairer }}</td>
                                            <td>{{ $item->disabler }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                    @endif
                @endisset
            </div>
        </div>
        {{-- ================================================================================== --}}
    </div>
    </div>
@endsection
