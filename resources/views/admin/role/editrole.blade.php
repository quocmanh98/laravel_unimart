@extends('layouts.admin')
@section('content')
    @php
    use App\role;
    @endphp
    <div id="content" class="container-fluid">
        <div class="row" style="min-height:1000px;">
            <div class="col-4">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                        {{-- status : du lieu tu cac action ben AdminPostController sang --}}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Cập nhật quyền cho user
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/role/updaterole', $role->id) }}" method="">
                            @csrf
                            <div class="form-group">
                                <label for="user">User</label><br>
                                <select id="user" name="user" class="form-control">
                                    <option value="{{ $user->id }}" selected='selected'>{{ $user->name }}</option>
                                </select>
                                @error('user')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="namerole">Cập nhật quyền</label><br>
                                <select id="namerole" name="namerole" class="form-control">
                                    <option value="" style="font-weight:bold;">Chọn quyền</option>
                                    <optgroup label="Quản trị viên" style="font-weight:bold;">
                                        <option value="administrators" {{$role->namerole == 'administrators'?'selected=selected':''}}>Quản trị viên</option>
                                    </optgroup>
                                    <optgroup label="Quyền thêm" style="font-weight:bold;">
                                        <option value="addcatpost" {{$role->namerole == 'addcatpost'?'selected=selected':''}}>Thêm danh mục bài viết</option>
                                        <option value="addpost" {{$role->namerole == 'addpost'?'selected=selected':''}}>Thêm bài viết</option>
                                        <option value="addcatproduct" {{$role->namerole == 'addcatproduct'?'selected=selected':''}}>Thêm danh mục sản phẩm
                                        </option>
                                        <option value="addproduct" {{$role->namerole == 'addproduct'?'selected=selected':''}}>Thêm sản phẩm</option>
                                        <option value="addslider" {{$role->namerole == 'addslider'?'selected=selected':''}}>Thêm slider</option>
                                        <option value="addpage" {{$role->namerole == 'addpage'?'selected=selected':''}}>Thêm bài viết cho trang</option>
                                        <option value="addadvertisement" {{$role->namerole == 'addadvertisement'?'selected=selected':''}}>Thêm quảng cáo</option>
                                    </optgroup>
                                    <optgroup label="Quyền sửa" style="font-weight:bold;">
                                        <option value="editcatpost" {{$role->namerole == 'editcatpost'?'selected=selected':''}}>Sửa danh mục bài viết</option>
                                        <option value="editpost" {{$role->namerole == 'editpost'?'selected=selected':''}}>Sửa bài viết</option>
                                        <option value="editcatproduct" {{$role->namerole == 'editcatproduct'?'selected=selected':''}}>Sửa danh mục sản phẩm
                                        </option>
                                        <option value="editproduct" {{$role->namerole == 'editproduct'?'selected=selected':''}}>Sửa sản phẩm</option>
                                        <option value="editslider" {{$role->namerole == 'editslider'?'selected=selected':''}}>Sửa slider</option>
                                        <option value="editpage" {{$role->namerole == 'editpage'?'selected=selected':''}}>Sửa bài viết cho trang</option>
                                        <option value="editadvertisement" {{$role->namerole == 'editadvertisement'?'selected=selected':''}}>Sửa quảng cáo</option>
                                    </optgroup>
                                    <optgroup label="Quyền xóa" style="font-weight:bold;">
                                        <option value="deletecatpost" {{$role->namerole == 'deletecatpost'?'selected=selected':''}}>Xóa danh mục bài viết
                                        </option>
                                        <option value="deletepost" {{$role->namerole == 'deletepost'?'selected=selected':''}}>Xóa bài viết</option>
                                        <option value="deletecatproduct" {{$role->namerole == 'deletecatproduct'?'selected=selected':''}}>Xóa danh mục sản phẩm
                                        </option>
                                        <option value="deleteproduct" {{$role->namerole == 'deleteproduct'?'selected=selected':''}}>Xóa sản phẩm</option>
                                        <option value="deleteslider" {{$role->namerole == 'deleteslider'?'selected=selected':''}}>Xóa slider</option>
                                        <option value="deletepage" {{$role->namerole == 'deletepage'?'selected=selected':''}}>Xóa bài viết cho trang</option>
                                        <option value="deleteadvertisement" {{$role->namerole == 'deleteadvertisement'?'selected=selected':''}}>Xóa quảng cáo</option>
                                    </optgroup>
                                    <optgroup label="Quyền xử lý đơn hàng" style="font-weight:bold;">
                                        <option value="order_processing" {{$role->namerole == 'order_processing'?'selected=selected':''}}>Xử lý đơn hàng</option>
                                    </optgroup>
                                </select>
                                @error('namerole')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" name="updaterole" value="updaterole">Cập
                                nhật</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">

                    <div class="card-header font-weight-bold">
                        Danh sách quyền hiện có của các user trong hệ thống
                    </div>
                    <div class="card-body">
                        @if (count($roles) > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">TÊN QUYỀN</th>
                                        <th colspan="2" scope="col">GHI CHÚ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($roles as $key => $role)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <td scope="row">{{ $t }}</td>
                                            <td>{{ $role->namerole }}</td>
                                            <td colspan="2">
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
                                                @if ($role->namerole == 'deletepage'){{ 'Xóa nội dung của trang' }}  @endif
                                                @if ($role->namerole == 'deleteadvertisement'){{ 'Xóa quảng cáo' }}  @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        @else
                            <p style="color:red;">Không có quyền nào của user trong hệ thống!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
