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
                        Thêm quyền cho các user
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/role/storeaddrole') }}" method="">
                            @csrf

                            <div class="form-group">
                                <label for="user">User</label><br>
                                <select id="user" name="user" class="form-control">
                                    <option value="">Chọn user</option>
                                    @if (count($users) > 0)
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{old('user')==$user->id?'selected=selected':''}}>{{ $user->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('user')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="namerole">Chọn quyền</label><br>
                                <select id="namerole" name="namerole" class="form-control">
                                    <option value="">Chọn quyền</option>
                                    <optgroup label="Quản trị viên" style="font-weight:bold;">
                                        <option value="administrators" {{old('namerole')=="administrators"?'selected=selected':''}}>Quản trị viên</option>
                                    </optgroup>
                                    <optgroup label="Quyền thêm" style="font-weight:bold;">
                                        <option value="addcatpost" {{old('namerole')=="addcatpost"?'selected=selected':''}}>Thêm danh mục bài viết</option>
                                        <option value="addpost" {{old('namerole')=="addpost"?'selected=selected':''}}>Thêm bài viết</option>
                                        <option value="addcatproduct" {{old('namerole')=="addcatproduct"?'selected=selected':''}}>Thêm danh mục sản phẩm</option>
                                        <option value="addproduct" {{old('namerole')=="addproduct"?'selected=selected':''}}>Thêm sản phẩm</option>
                                        <option value="addslider" {{old('namerole')=="addslider"?'selected=selected':''}}>Thêm slider</option>
                                        <option value="addpage" {{old('namerole')=="addpage"?'selected=selected':''}}>Thêm bài viết cho trang</option>
                                        <option value="addadvertisement" {{old('namerole')=="addadvertisement"?'selected=selected':''}}>Thêm quảng cáo</option>
                                    </optgroup>
                                    <optgroup label="Quyền sửa" style="font-weight:bold;">
                                        <option value="editcatpost" {{old('namerole')=="editcatpost"?'selected=selected':''}}>Sửa danh mục bài viết</option>
                                        <option value="editpost" {{old('namerole')=="editpost"?'selected=selected':''}}>Sửa bài viết</option>
                                        <option value="editcatproduct" {{old('namerole')=="editcatproduct"?'selected=selected':''}}>Sửa danh mục sản phẩm</option>
                                        <option value="editproduct" {{old('namerole')=="editproduct"?'selected=selected':''}}>Sửa sản phẩm</option>
                                        <option value="editslider" {{old('namerole')=="editslider"?'selected=selected':''}}>Sửa slider</option>
                                        <option value="editpage" {{old('namerole')=="editpage"?'selected=selected':''}}>Sửa bài viết cho trang</option>
                                        <option value="editadvertisement" {{old('namerole')=="editadvertisement"?'selected=selected':''}}>Sửa quảng cáo</option>
                                    </optgroup>
                                    <optgroup label="Quyền xóa" style="font-weight:bold;">
                                        <option value="deletecatpost" {{old('namerole')=="deletecatpost"?'selected=selected':''}}>Xóa danh mục bài viết</option>
                                        <option value="deletepost" {{old('namerole')=="deletepost"?'selected=selected':''}}>Xóa bài viết</option>
                                        <option value="deletecatproduct" {{old('namerole')=="deletecatproduct"?'selected=selected':''}}>Xóa danh mục sản phẩm</option>
                                        <option value="deleteproduct" {{old('namerole')=="deleteproduct"?'selected=selected':''}}>Xóa sản phẩm</option>
                                        <option value="deleteslider" {{old('namerole')=="deleteslider"?'selected=selected':''}}>Xóa slider</option>
                                        <option value="deletepage" {{old('namerole')=="deletepage"?'selected=selected':''}}>Xóa bài viết cho trang</option>
                                        <option value="deleteadvertisement" {{old('namerole')=="deleteadvertisement"?'selected=selected':''}}>Xóa quảng cáo</option>
                                    </optgroup>
                                    <optgroup label="Quyền xử lý đơn hàng" style="font-weight:bold;">
                                        <option value="order_processing" {{old('namerole')=="order_processing"?'selected=selected':''}}>Xử lý đơn hàng</option>
                                    </optgroup>
                                </select>
                                @error('namerole')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" name="addmanyrole" value="addmanyrole">Thêm
                                mới</button>
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
                                                @if ($role->namerole == 'order_processing'){{ 'Xử lý đơn hàng' }}  @endif
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
