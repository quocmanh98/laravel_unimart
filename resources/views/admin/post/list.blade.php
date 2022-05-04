@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                    {{-- status : du lieu tu cac action ben AdminPostController sang --}}
                </div>
            @endif
            {{-- Khai bao 2 model nay de su dung elequent orm --}}
            @php
                use App\postcat;
                use App\post;
            @endphp
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách bài viết</h5>
                <div class="form-search form-inline">
                    <form action="#">
                        <input style="padding:0px;" type="" class="form-control form-search" name="keyword"
                            value="{{ request()->input('keyword') }}" placeholder="Tìm kiếm">
                        <input style="padding:6px;" type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlwithQuery(['status' => 'active']) }}" class="text-primary">Kích
                        hoạt<span class="text-muted">({{ $count[0] }})</span></a>
                    <a href="{{ request()->fullUrlwithQuery(['status' => 'trash']) }}" class="text-primary">Vô hiệu
                        hóa<span class="text-muted">({{ $count[1] }})</span></a>
                </div>
                <form action="{{ url('admin/post/actionpost') }}" method="GET">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name="act">
                            <option>Chọn</option>
                            @foreach ($list_act as $k => $act)
                                <option value="{{ $k }}">{{ $act }}</option>
                            @endforeach

                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">STT</th>
                                {{-- <th scope="col">Ảnh</th> --}}
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Ảnh bài viết</th>
                                {{-- <th scope="col">Nội dung</th> --}}
                                <th scope="col">Danh mục</th>
                                <th scope="col">Ngày tạo</th>
                                @if (request()->status == 'trash')
                                    <th scope="col">Ngày vô hiệu hóa</th>
                                @else
                                    <th scope="col">Ngày cập nhật</th>
                                @endif
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($posts) > 0)
                                @php
                                    !isset($_GET['page']) ? ($t = 0) : ($t = 8 * ($_GET['page'] - 1));
                                    // $t=0;
                                @endphp
                                @foreach ($posts as $post)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value={{ $post->id }}>
                                            {{-- <input type="checkbox"> --}}
                                        </td>
                                        <td scope="row">{{ $t }}</td>
                                        <td><a href="">{{ $post->name }}</a></td>
                                        <td class="column-img"><img src="{{ asset($post->thumbnail) }}"
                                                class="img-post" alt="Logo"></td>
                                        {{-- <td><a href="">{{ $post->content }}</a></td> --}}
                                        <td>{{ $post->postcat->name }}</td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($post->created_at)) }}</td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($post->updated_at)) }}</td>
                                        @if (request()->status == 'trash')
                                            <td>
                                                <a href="{{ route('restorepost', $post->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Disable"><i
                                                        class="fas fa-trash-restore-alt"></i></a>
                                                <a href="{{ route('delete_post', $post->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn xóa bài viết này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0 mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ route('edit_post', $post->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{{ route('disablepost', $post->id) }}"
                                                    class="btn btn-dark btn-sm rounded-0 text-white mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Disable"><i
                                                        class="fas fa-microphone-alt-slash"></i></a>
                                                <a href="{{ route('delete_post', $post->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn xóa bài viết này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="bg-white text-danger">
                                        Không tìm thấy bài viết nào trong hệ thống
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection
