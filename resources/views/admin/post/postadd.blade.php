@extends('layouts.admin')
@section('content')
<script
    type="text/javascript"
    src='https://cdn.tiny.cloud/1/4bxsgkp94m5dlcmtom28vs3rcv0r6cee5xd6d5vxuf7k2fwm/tinymce/5/tinymce.min.js'
    referrerpolicy="origin">
  </script>

<script>
  var editor_config = {
    path_absolute : "http://localhost/unimart/",
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
    file_picker_callback : function(callback, value, meta) {
      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
      var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

      var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
      if (meta.filetype == 'image') {
        cmsURL = cmsURL + "&type=Images";
      } else {
        cmsURL = cmsURL + "&type=Files";
      }

      tinyMCE.activeEditor.windowManager.openUrl({
        url : cmsURL,
        title : 'Filemanager',
        width : x * 0.8,
        height : y * 0.8,
        resizable : "yes",
        close_previous : "no",
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
                Thêm bài viết
            </div>
            @if (session('status'))
            <div class="alert alert-success">
                    {{session('status')}}
            </div>
            @endif
            <div class="card-body">
                <form action="{{url('admin/post/store')}}" method="POST" enctype="multipart/form-data">
                {{-- <form action="{{url('admin/post/add')}}" method="GET"> --}}
                    @csrf
                    <div class="form-group">
                        <label for="name">Tiêu đề bài viết</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{old('name')}}">
                        @error('name')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="file">Ảnh bài viết</label><br>
                        <input type="file" name='file' id="file" value="{{old('file')}}"/><br>
                        @error('file')
                             <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content1">Nội dung bài viết</label>
                        <textarea id="content1" name="content" class="form-control"  cols="30" rows="5">{{old('content')}}</textarea>
                        @error('content')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Chi tiết bài viết</label>
                        <textarea id="description" name="description_post" class="form-control"  cols="30" rows="5">{{old('description_post')}}</textarea>
                        @error('description_post')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="postcat">Danh mục bài viết</label>
                        <select class="form-control" id="postcat" name="postcat">
                        <option value="">Chọn danh mục bài viết</option>
                            @foreach ($postcats as $name )
                                <option value="{{$name->id}}" {{ old('postcat') == $name->id ? 'selected=selected' : '' }}>{{$name->name}}</option>
                            @endforeach
                        </select>
                        @error('postcat')
                              <small class="text-danger">{{$message}}</small>
                         @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection
