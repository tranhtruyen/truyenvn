@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Quản Lý Truyện Tranh'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Truyện tranh</h6>
                            <a href="{{route('admin.comic.create')}}" class="btn btn-primary">Thêm Mới</a>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-2">
                                <table id="myTable" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th style="max-width: 100px" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Thông tin</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                ẢNH</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Thể loại</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Lần cập nhật gần nhất</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Lượt xem</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($comics as $item)
                                        <tr>
                                            <td style="max-width: 100px; overflow:hidden">
                                                <div class="d-flex px-2 py-1">
                                                    <div class="">
                                                        <div>
                                                            <span style="color: #7c69ef;">{{$item->name}}</span>
                                                        </div>
                                                        <div>
                                                            <small class="text-gray-500">({{$item->origin_name ?? 'Đang cập nhật'}})</small>&nbsp;
                                                            <small style="color: rgb(239 68 68/1)">[{{$item->chapters_count}} chap]</small> -
                                                            @if ($item->chapters_count > 0)
                                                            <small style="color: rgb(239 68 68/1)">[Chapter {{$item->chapters->first()->name}}]</small>&nbsp;
                                                            @endif
                                                        </div>
                                                        <div class="text-[75%] text-white gap-2" style="display: inline-flex">
                                                            <div style="background: #7c69ef" class="bg-[#7c69ef] rounded px-1">Truyện tranh</div>
                                                            <div class="bg-success rounded px-1">{{$item->status}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <img src="{{$item->thumbnail}}" class="avatar me-3" style="width:80px;height:100px" alt="{{$item->name}}">
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 text-center d-flex flex-wrap gap-2">
                                                    @if ($item->categories->count() >0)
                                                        @foreach ($item->categories as $category)
                                                            <span class="badge bg-gradient-dark">{{$category->name}}</span>
                                                        @endforeach
                                                    @endif
                                                </p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success">{{$item->updated_at->diffForHumans()}}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{$item->total_views}}</span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex gap-2">
                                                    <a target="_blank" href="{{route('detail', ['slug' => $item->slug])}}" class="rounded font-weight-bold bg-success btn text-white">
                                                        Xem
                                                    </a>
                                                    <a href="{{ route('admin.chapterComic.index', ['comic_id' => $item->id]) }}" class="btn btn-warning">Danh sách chương</a>

                                                    <a href="{{route('admin.comic.edit', ["comic" => $item->id])}}" style="background: #7c69ef" class="text-white btn rounded font-weight-bold">
                                                        Sửa
                                                    </a>
                                                    <button data-id="{{$item->id}}" type="button" data-bs-toggle="modal" data-bs-target="#modal-notification" data-name="{{$item->name}}" class="btn-delete btn btn-danger">
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
        let table = new DataTable('#myTable', {
            order: []
        });
        let id =null;
        $(document).on('click', '.btn-delete', function() {
            id = null;
            $('.container-text').text('');
            id = $(this).data('id');
            $('.container-text').text('Bạn có chắc chắn muốn xóa truyện ' + $(this).data('name') + ' không?' );
        });
        $('.btn-submit').click(function(){
            $.ajax({
                url: '{{route('admin.comic.destroy', ["comic" => 'id'])}}',
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
    </script>
@endpush
