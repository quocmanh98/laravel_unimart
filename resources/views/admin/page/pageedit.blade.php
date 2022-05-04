@extends('layouts.admin')
@section('content')
    <script type="text/javascript"
        src='https://cdn.tiny.cloud/1/4bxsgkp94m5dlcmtom28vs3rcv0r6cee5xd6d5vxuf7k2fwm/tinymce/5/tinymce.min.js'
        referrerpolicy="origin">
    </script>

    <script>
        var editor_config = {
            path_absolute: "http://localhost/unimart/",
            // Kich hoat texterea co id=description
            selector: 'textarea',
            relative_urls: false,
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table directionality",
                "emoticons template paste textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            file_picker_callback: function(callback, value, meta) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                    'body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
                if (meta.filetype == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.openUrl({
                    url: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no",
                    onMessage: (api, message) => {
                        callback(message.content);
                    }
                });
            }
        };

        tinymce.init(editor_config);
    </script>
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Cập nhật bài viết trang
            </div>
            <div class="card-body">
                <form action="{{ url('admin/page/update', $page->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="title">Tiêu đề bài viết của trang</label>
                        <input id="title" class="form-control" type="text" name="title" value="{{ $page->title }}">
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="file">Ảnh minh họa</label><br>
                        <input id='file' type="file" name='file' value="{{ old('file') }}" /><br>
                        @error('file')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content1">Nội dung bài viết của trang</label>
                        <textarea id="content1" name="content" class="form-control" cols="30"
                            rows="5">{{ $page->content }}</textarea>
                        @error('content')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="birthday1">Ngày tạo</label>
                        <input id="birthday1" class="form-control" type="date" name="birthday"
                            value="{{ $page->birthday }}">
                        @error('birthday')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="category">Trang</label>
                        <select id="category" class="form-control" name="category">
                            <option>Chọn trang</option>
                            @foreach ($categorys as $category)
                                <option value={{ $category->category }}
                                    {{ $category->category == $page->category ? 'selected=selected' : '' }}>
                                    {{ $category->page }} </option>
                            @endforeach
                        </select>
                        @error('category')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary" name="btn-updatepage" value="btn-updatepage">Cập
                        nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
