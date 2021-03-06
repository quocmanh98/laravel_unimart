@extends('layouts.admin')
@section('content')
    <script type="text/javascript"
        src='https://cdn.tiny.cloud/1/4bxsgkp94m5dlcmtom28vs3rcv0r6cee5xd6d5vxuf7k2fwm/tinymce/5/tinymce.min.js'
        referrerpolicy="origin">
    </script>

    <script>
        var editor_config = {
            path_absolute: "http://localhost/unimart/",
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
                Th??m s???n ph???m
            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card-body">
                <form action="{{ url('admin/product/storeproduct') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="masp">M?? s???n ph???m</label>
                                <input class="form-control" type="text" name="masp" id="masp" value="{{ old('masp') }}">
                                @error('masp')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="file">???nh s???n ph???m</label><br>
                                <input  type="file" name='file' id="file" value="{{ old('file') }}" /><br>
                                @error('file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">T??n s???n ph???m</label>
                                <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Gi?? s???n ph???m</label>
                                <input class="form-control" type="number" name="price" id="price"
                                    value="{{ old('price') }}">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="qty">S??? l?????ng</label>
                                <input class="form-control" type="number" name="qty" id="qty"
                                    value="{{ old('qty') }}">
                                @error('qty')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="color">M??u s???n ph???m</label>
                                <select name="color" class="form-control" id="color">
                                    <option value="">Ch???n m??u</option>
                                    @foreach ($colors as $color)
                                        <option value={{ $color->id }} {{ old('color') == $color->id ?'selected=selected' : '' }}>{{ $color->namecolor }}</option>
                                    @endforeach
                                </select>
                                @error('color')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="status">T??nh tr???ng s???n ph???m(m???i,50%,c??)</label>
                                <input class="form-control" type="text" name="status" id="status"
                                    value="{{ old('status') }}">
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="the_firm">H??ng s???n ph???m</label>
                                <select name="the_firm" class="form-control" id="the_firm">
                                    <option value="">Ch???n h??ng</option>
                                    @foreach ($companys as $company)
                                        <option value={{ $company->id }} {{ old('the_firm') == $company->id ?'selected=selected' : '' }}>{{ $company->namecompany }}</option>
                                    @endforeach
                                </select>
                                @error('the_firm')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                                <div class="form-group">
                                    <input type="checkbox" id="product_speak"  name="product_speak" value="speak" {{old('product_speak')=='speak'?'checked=checked':''}}>
                                    <label for="product_speak" class="mx-3">S???n ph???m n???i b???t(c?? th??? kh??ng ch???n)</label>
                                </div>
                            <div class="form-group">
                                <input type="checkbox" id="product_selling" name="product_selling" value="selling" {{old('product_selling')=='selling'?'checked=checked':''}}>
                                <label for="product_selling" class="mx-3">S???n ph???m b??n ch???y(c?? th??? kh??ng ch???n)</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">M?? t??? s???n ph???m</label>
                        <textarea id="description" name="description" class="form-control" cols="30"
                            rows="5">{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="list">Danh m???c s???n ph???m</label>
                        <select name="product_id" class="form-control" id="list" value={{ old('product_id') }}>
                            <option value="">Ch???n danh m???c s???n ph???m</option>
                            @foreach ($catproducts as $catproduct)
                                <option value={{ $catproduct->id }} {{ old('product_id') == $catproduct->id ? 'selected=selected' : '' }}>{{ $catproduct->catname }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary" name="btn_addproduct">Th??m m???i</button>
                </form>
            </div>
        </div>
    </div>
@endsection
