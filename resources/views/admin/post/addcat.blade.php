@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm danh mục bài viết
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/post/storeaddcat') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên danh mục</label>
                                <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" name="addcat" value="addcatpost">Thêm mới</button>
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
                        Danh mục bài viết
                    </div>
                    @if (count($catposts) > 0)
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">TÊN DANH MỤC</th>
                                        <th scope="col">NGÀY TẠO</th>
                                        <th scope="col">NGÀY CẬP NHẬT</th>
                                        <th scope="col">TRẠNG THÁI</th>
                                        <th scope="col">TÁC VỤ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($catposts as $catpost)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <td scope="row">{{ $t }}</td>
                                            <td>{{ $catpost->name }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($catpost->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($catpost->updated_at)) }}</td>
                                            @if ($catpost->disabler == 'active')
                                                <td class='text-success'>Đang kích hoạt</td>
                                            @else
                                                <td class="text-danger">Vô hiệu hóa</td>
                                            @endif
                                            <td>
                                                <a href="{{ route('edit_cat_post', $catpost->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <br>
                                                <a href="{{ route('disablecatpost', $catpost->id) }}"
                                                    class="btn btn-dark btn-sm rounded-0 text-white mb-2" type="button"
                                                    style="padding:4px 7px;" data-toggle="tooltip" data-placement="top"
                                                    title="Disable"><i class="fas fa-microphone-alt-slash"></i></a>
                                                <br>
                                                <a href="{{ route('activecatpost', $catpost->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white mb-2" type="button"
                                                    style="padding:4px 10px;" data-toggle="tooltip" data-placement="top"
                                                    title="Disable"><i class="fas fa-trash-restore-alt"></i></a>
                                                <br>
                                                <a href="{{ route('delete_cat_post', $catpost->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn xóa danh mục bài viết này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0" type="button"
                                                    style="padding:4px 10px;" data-toggle="tooltip" data-placement="top"
                                                    title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p style="color:red;">Không có danh mục bài viết nào trong hệ thống!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
