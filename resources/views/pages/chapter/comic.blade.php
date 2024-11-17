@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.1/dist/quill.snow.css" rel="stylesheet">
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Chương'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h3>Chương truyện {{$comic->name}}</h3>
                            <button type="button" data-type="add" class="text-white btn btn-add rounded font-weight-bold bg-success" data-bs-toggle="modal" data-bs-target="#exampleModalMessage">
                                Thêm mới
                            </button>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-2">
                                <table id="myTable" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Chap</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Server</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Slug</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tiêu đề</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($comic->chapters as $item)
                                        <tr>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success">{{$item->name}}</span>
                                            </td>
                                            <td>
                                                <span class="text-primary">{{$item->server}}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <span style="color: #7c69ef;">{{$item->slug}}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <span style="color: #7c69ef;">{{$item->title}}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex gap-2">
                                                    <button data-name="{{$item->name}}" data-title="{{$item->title}}" data-server="{{$item->server}}" data-images="{{$item->images}}" data-type="edit" data-id="{{$item->id}}" data-slug="{{$item->slug}}" style="background: #7c69ef;border: none" class="text-white btn-edit px-2 py-1 rounded font-weight-bold" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalMessage">
                                                        Sửa
                                                    </button>
                                                    <button data-name="{{$item->chapter_number}}" data-id="{{$item->id}}" type="button" style="border: none" class="text-white px-2 btn-delete py-1 rounded font-weight-bold bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modal-notification">
                                                        Xóa
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
                                    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h6 class="modal-title" id="modal-title-notification">Xác nhận xóa</h6>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          <div class="py-3 text-center">
                                            <i class="ni ni-bell-55 ni-3x"></i>
                                            <h4 class="text-gradient text-danger container-text mt-4"></h4>
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-white btn-submit">Chắc chắn</button>
                                          <button type="button" class="btn btn-link ml-auto" style="color: #5e72e4" data-bs-dismiss="modal">Đóng</button>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="labelModal"></h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          <form>
                                            <div class="form-group">
                                              <label for="name" class="col-form-label">Chap:</label>
                                              <input type="text" class="form-control" value="" id="name">
                                            </div>
                                            <div class="form-group">
                                                <label for="title" class="col-form-label">Tiêu đề:</label>
                                                <input type="text" class="form-control" value="" id="title">
                                              </div>
                                            <div class="form-group">
                                              <label for="server" class="col-form-label">Server:</label>
                                              <input type="text" class="form-control" value="" id="server">
                                            </div>
                                            <div class="form-group">
                                                <label for="images">PAGE|LINK</label>
                                                <textarea class="form-control" id="images" rows="10"></textarea>
                                            </div>
                                          </form>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Đóng</button>
                                          <button type="button" class="btn bg-gradient-primary btn-submit-abc"></button>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footers.auth.footer')
        </div>
    </div>
@endsection
@push('js')
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script>
        let table = new DataTable('#myTable');
        let id = null;
        let type = null;
        $(document).on('click', '.btn-delete', function() {
            id = null;
            $('.container-text').text('');
            id = $(this).data('id');
            $('.container-text').text('Bạn có chắc chắn muốn xóa chương ' + $(this).data('name') + ' không?' );
        });
        $('.btn-submit').click(function(){
            $.ajax({
                url: '{{route('admin.chapterComic.destroy', ["chapterComic" => 'id'])}}',
                method: 'DELETE',
                data: {
                    id: id,
                    _token: '{{csrf_token()}}'
                },
                success: function(){
                    alert('Xóa thành công');
                    window.location.reload();
                }
            })
        })
        $('.btn-add').click(function(){
            $('#labelModal').text('Thêm mới chương');
            $('.btn-submit-abc').text('Thêm mới');
            $('#name').val('');
            $('#title').val('');
            $('#server').val('');
            $('#images').empty();
            type = 'add';
        })
        $(document).on('click', '.btn-edit', function() {
            id = null;
            id = $(this).data('id');
            $('#labelModal').text('Sửa chương');
            $('.btn-submit-abc').text('Sửa');
            $('#name').val($(this).data('name'));
            $('#title').val($(this).data('title'));
            $('#server').val($(this).data('server'));
            $('#images').empty();
            let images = $(this).data('images');
            $('#images').empty();
            if(images){
                images.forEach(image => {
                    $('#images').append(image.page + '|' + image.link + '\n');
                });
            }
            type = 'edit';
        });
        $('.btn-submit-abc').click(function(){
            let name = $('#name').val();
            let serverName = $('#server').val();
            let images = $('#images').val();
            let title = $('#title').val();
            images = images.split('\n');
            if(!name || !serverName || !images){
                alert('Vui lòng nhập đầy đủ thông tin');
                return;
            }
            if(type == 'add'){
                $.ajax({
                    url: '{{route('admin.chapterComic.store')}}',
                    method: 'POST',
                    data: {
                        name: name,
                        title: title,
                        serverName: serverName,
                        images: images,
                        comic_id: '{{$comic->id}}',
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data){
                        if(data.status == "success"){
                            alert(data.message);
                            window.location.reload();
                        }else{
                            alert(data.message);
                        }
                    }
                })
            }else{
                $.ajax({
                    url: '{{route('admin.chapterComic.update', ['chapterComic' => 'id'])}}',
                    method: 'PUT',
                    data: {
                        name: name,
                        title: title,
                        serverName: serverName,
                        images: images,
                        id: id,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data){
                        if(data.status == "success"){
                            alert(data.message);
                            window.location.reload();
                        }else{
                            alert(data.message);
                        }
                    }
                })
            }
        })
    </script>
@endpush
