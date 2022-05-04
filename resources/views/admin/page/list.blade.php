@extends('layouts.admin')
@php
    use Carbon\Carbon;
@endphp
@section('content')
    <div id="content" class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách trang</h5>
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
                    {{-- <a href="" class="text-primary">Số trang<span class="text-muted"> : {{$count_page}}</span></a> --}}
                </div>
                @if (count($listpages) > 0)
                    <form action="{{ url('admin/page/action') }}">
                        <div class="form-action form-inline py-3">
                            <select class="form-control mr-1" id="" name='act'>
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
                                    <th scope="col">Tiêu đề</th>
                                    <th scope="col">Ảnh</th>
                                    {{-- <th scope="col">Nội dung</th> --}}
                                    <th scope="col">Trang</th>
                                    <th scope="col">Ngày tạo</th>
                                    <th scope="col">Ngày cập nhật</th>
                                    {{-- <th scope="col">Trạng thái</th> --}}
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    !isset($_GET['page']) ? ($t = 0) : ($t = 20 * ($_GET['page'] - 1));
                                    // $t=0;
                                @endphp
                                @foreach ($listpages as $listpage)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value={{ $listpage->id }}>
                                        </td>
                                        <td scope="row">{{ $t }}</td>
                                        <td>{{ $listpage->title }}</td>
                                        <td class="column_img_page"><img
                                                src="{{ url('') }}{{ '/' }}{{ $listpage->thumbnail }}"
                                                class="img-page" alt="Logo"></td>
                                        {{-- <td><a href="">{{ $listpage->content }}</a></td> --}}
                                        <td>{{ $listpage->page }}</td>
                                        <td>{{ date('d-m-Y', strtotime($listpage->created_at)) }}</td>
                                        <td>
                                            {{ Carbon::createFromFormat('m/d/Y', date('m/d/Y', strtotime($listpage->updated_at)))->diffForHumans() }}
                                        </td>
                                        {{-- @if ($listpage->disabler != 'active')
                                            <td class="bg-dark text-white">{{ $listpage->disabler }}</td>
                                        @else
                                            <td>{{ $listpage->disabler }}</td>
                                        @endif --}}
                                        <td>

                                            @if (request()->status == 'trash')
                                                <a href="{{ route('restore_page', $listpage->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Restore"><i
                                                        class="fas fa-trash-restore-alt"></i></a>
                                                <a href="{{ route('delete_page', $listpage->id) }}"
                                                    onclick="return confirm('Bạn có chắc xóa bài viết này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
                                            @else
                                                <a href="{{ route('edit_page', $listpage->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{{ route('disable_page', $listpage->id) }}"
                                                    class="btn btn-dark btn-sm rounded-0 text-white mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Disable"><i
                                                        class="fas fa-microphone-alt-slash"></i></a>
                                                <a href="{{ route('delete_page', $listpage->id) }}"
                                                    onclick="return confirm('Bạn có chắc xóa bài viết này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                @else
                    <div class="bg-white text-danger">
                        <p>Không tìm thấy danh mục trang nào trong hệ thống!</p>
                    </div>
                @endif
                {{ $listpages->links() }}
            </div>
        </div>
    </div>
@endsection
