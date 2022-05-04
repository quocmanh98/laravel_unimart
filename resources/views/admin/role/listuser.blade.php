@extends('layouts.admin')
@section('content')
    <?php use Illuminate\Support\Facades\Auth; ?>
    <?php use App\role; ?>
    <?php use App\User; ?>
    {{-- Khai bao use Illuminate\Support\Facades\Auth; -> de su dung thu vien auth o dong 83 --}}
    <div id="content" class="container-fluid">
        <div class="card">
            {{-- Phan 24 bai 272 --}}
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                    {{-- status : du lieu tu cac action ben AdminUserController sang --}}
                </div>
            @endif

            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách quyền của các thành viên</h5>
                {{-- Phan 24 bai 269 : Viet chuc nang tim kiem nguoi dung --}}
                <div class="form-search form-inline">
                    {{-- Xu ly tim kiem o file hien tai luon --}}
                    <form action="#">
                        @csrf
                        {{-- <input style="padding : 10px;" type="text" class="form-control form-search float-left" name="keyword" value="{{old('keyword')}}" placeholder="Tìm kiếm"> --}}
                        <input style="padding : 10px;" type="text" class="form-control form-search float-left" name="keyword"
                            value="{{ request()->input('keyword') }}" placeholder="Tìm kiếm">
                        {{-- {{request()->input('keyword')}} : cho thang nay vao de khi nguoi dung nhap gia tri tim kiem thi van hien thi khi an submit form:HAY --}}
                        <input style="margin-left:5px;" type="submit" name="btn-search" value="Tìm kiếm"
                            class="btn btn-primary float-left">
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if(count($users)>0)
                <form action="{{ url('admin/user/action') }}" method="">
                    @foreach ($users as $item)
                        @if (count($item->roles) > 0)
                            <h3>{{ $item->name }}</h3>
                            <table class="table table-striped table-checkall">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Tên quyền</th>
                                        <th scope="col">Ghi chú quyền</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Ngày cập nhật</th>
                                        <th scope="col">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($item->roles as $role)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <td scope="row">{{ $t }}</td>
                                            <td>{{ $role->namerole }}</td>
                                            <td>
                                                @if ($role->namerole == 'administrators'){{ 'Quản trị viên' }}  @endif
                                                @if ($role->namerole == 'addcatpost'){{ 'Thêm danh mục bài viết' }}  @endif
                                                @if ($role->namerole == 'addpost'){{ 'Thêm bài viết' }}  @endif
                                                @if ($role->namerole == 'addcatproduct'){{ 'Thêm danh mục sản phẩm' }}  @endif
                                                @if ($role->namerole == 'addproduct'){{ 'Thêm sản phẩm' }}  @endif
                                                @if ($role->namerole == 'addslider'){{ 'Thêm slider' }}  @endif
                                                @if ($role->namerole == 'addpage'){{ 'Thêm bài viết cho trang' }}  @endif
                                                @if ($role->namerole == 'addadvertisement'){{ 'Thêm quảng cáo' }}  @endif
                                                @if ($role->namerole == 'editcatpost'){{ 'Sửa danh mục bài viết' }}  @endif
                                                @if ($role->namerole == 'editpost'){{ 'Sửa bài viết' }}  @endif
                                                @if ($role->namerole == 'editcatproduct'){{ 'Sửa danh mục sản phẩm' }}  @endif
                                                @if ($role->namerole == 'editproduct'){{ 'Sửa sản phẩm' }}  @endif
                                                @if ($role->namerole == 'editslider'){{ 'Sửa slider' }}  @endif
                                                @if ($role->namerole == 'editpage'){{ 'Sửa bài viết cho trang' }}  @endif
                                                @if ($role->namerole == 'editadvertisement'){{ 'Sửa quảng cáo' }}  @endif
                                                @if ($role->namerole == 'deletecatpost'){{ 'Xóa danh mục bài viết' }}  @endif
                                                @if ($role->namerole == 'deletepost'){{ 'Xóa bài viết' }}  @endif
                                                @if ($role->namerole == 'deletecatproduct'){{ 'Xóa danh mục sản phẩm' }}  @endif
                                                @if ($role->namerole == 'deleteproduct'){{ 'Xóa sản phẩm' }}  @endif
                                                @if ($role->namerole == 'deleteslider'){{ 'Xóa slider' }}  @endif
                                                @if ($role->namerole == 'deletepage'){{ 'Xóa bài viết của trang' }}  @endif
                                                @if ($role->namerole == 'deleteadvertisement'){{ 'Xóa quảng cáo' }}  @endif
                                                @if ($role->namerole == 'order_processing'){{ 'Xử lý đơn hàng' }}  @endif
                                            </td>
                                            <td scope="row">{{date('d-m-Y H:i:s',strtotime($role->created_at))}}</td>
                                            <td scope="row">{{date('d-m-Y H:i:s',strtotime($role->updated_at))}}</td>
                                            <td>
                                                @if ($role->namerole == 'administrators')
                                                    <a href="{{ route('editrole', $role->id) }}"
                                                        class="btn btn-success btn-sm rounded-0 text-white float-left"
                                                        type="button" data-toggle="tooltip" data-placement="top"
                                                        title="Edit"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <a href="{{ route('editrole', $role->id) }}"
                                                        class="btn btn-success btn-sm rounded-0 text-white float-left"
                                                        type="button" data-toggle="tooltip" data-placement="top"
                                                        title="Edit"><i class="fa fa-edit"></i></a>
                                                    <a href="{{ route('deleterole', $role->id) }}"
                                                        onclick="return confirm('Bạn có chắc xóa quyền của user này không ?')"
                                                        class="btn btn-danger btn-sm rounded-0 text-white float-right"
                                                        type="button" data-toggle="tooltip" data-placement="top"
                                                        title="Delete"><i class="fa fa-trash"></i></a>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @endforeach
                </form>
                @else
                    <p class="text-danger">Không tìm thấy user nào với giá trị tìm kiếm</p>
                @endif
            </div>
        </div>
    </div>
@endsection
