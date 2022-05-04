@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Cập nhật Danh mục sản phẩm
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/product/cat/updatecatproduct', $editcatproduct->id) }}" method="GET">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên danh mục</label>
                                <input class="form-control" type="text" name="catname" id="name" @isset($editcatproduct)
                                    value="{{ $editcatproduct->catname }}" @endisset value="{{ old('catname') }}">
                                @error('catname')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách Danh mục
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Danh mục sản phẩm</th>
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
                                @foreach ($catproducts as $catproduct)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $t }}</th>
                                        <td>{{ $catproduct->catname }}</td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($catproduct->created_at)) }}</td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($catproduct->updated_at)) }}</td>
                                        <td>{{ $catproduct->disabler }}</td>
                                        <td>
                                            <a href="{{ route('edit_cat_product', $catproduct->id) }}"
                                                class="btn btn-success btn-sm rounded-0 mb-2" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                            <br>
                                            <a href="{{ route('disablecatproduct', $catproduct->id) }}"
                                                class="btn btn-dark btn-sm rounded-0 text-white mb-2" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Disable"><i
                                                    class="fas fa-microphone-alt-slash"></i></a>
                                            <br>
                                            <a href="{{ route('restorecatproduct', $catproduct->id) }}"
                                                class="btn btn-success btn-sm rounded-0 text-white mb-2" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Restore"><i
                                                    class="fas fa-trash-restore-alt"></i></a>
                                            <br>
                                            <a href="{{ route('delete_cat_product', $catproduct->id) }}"
                                                onclick="return confirm('Bạn có chắc chắn xóa vĩnh viễn danh mục sản phẩm này không, xóa là toàn bộ sản phẩm của danh mục này cũng xóa vĩnh viễn theo ?')"
                                                class="btn btn-danger btn-sm rounded-0 mb-2" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
