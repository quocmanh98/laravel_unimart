@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        THÊM hãng sản phẩm
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/product/storeaddcompanyproduct') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="namecompany">Tên hãng</label>
                                <input class="form-control" type="text" name="namecompany" id="namecompany"
                                    value="{{ old('namecompany') }}">
                                @error('namecompany')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" name="add_company_product">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="card-header font-weight-bold">
                        Danh sách hãng sản phẩm
                    </div>
                    @if (count($companys) > 0)
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Hãng sản phẩm</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Ngày cập nhật</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($companys as $item)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <td scope="row">{{ $t }}</td>
                                            <td>{{ $item->namecompany }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($item->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($item->updated_at)) }}</td>
                                            @if ($item->disabler != 'active')
                                                <td class="text-danger">Vô hiệu hóa</td>
                                            @else
                                                <td class="text-success">Đang kích hoạt</td>
                                            @endif
                                            <td>
                                                <a href="{{ route('edit_company_product', $item->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 mb-2" type="button"
                                                    style="padding:4px;" data-toggle="tooltip" data-placement="top"
                                                    title="Edit"><i class="fa fa-edit"></i></a>
                                                <a href="{{ route('delete_company_product', $item->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn xóa vĩnh viễn hãng sản phẩm này không ?')"
                                                    class="btpx-2n btn-danger btn-sm rounded-0" type="button"
                                                    style="padding:4px 6px;" data-toggle="tooltip" data-placement="top"
                                                    title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="min-height:348px;">
                            <p style="color:red;">Không có hãng sản phẩm nào trong hệ thống</p>
                        </div>

                    @endif

                </div>
            </div>
        </div>

    </div>
@endsection
