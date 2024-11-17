@extends("users.layout.main")
@section('metadata')
    <title>{{__('string.comicHistory')}}</title>
    <meta property="og:title" content="{{__('string.comicHistory')}}">
    <meta property="robots" content="noindex, nofollow">
@endsection
@section('class') homepage @endsection
@section('content')
<div class="content">
    <div class="div_middle">
        <div class="alert-note-fix"></div>
        <div class="main_content">
            <div id="main_homepage">
                <div class="homepage_tags">
                    <h1>
                        <p class="text_list_update">
                            <i class="fa fa-font-awesome" aria-hidden="true"></i> {{__('string.comicHistory')}}
                        </p>
                    </h1>
                    <div class="clear"></div>
                </div>
                <div class="list_grid_out">
                    @if (!auth()->check())
                    <div class="warning-list box">{{__('string.notFound')}}</div>
                    @else
                    @if ($comics->count() == 0)
                    <div class="warning-list box">{{__('string.notFound')}}</div>
                    @endif
                    <ul class="list_grid grid">
                        @foreach ($comics as $item)
                        <li>
                            <div class="book_avatar">
                                <span class="remove-history" title="Xóa truyện đã đọc" data-id="{{$item->id}}" style="cursor: pointer;"><i class="fa fa-times-circle-o" aria-hidden="true"></i></span>
                                <a href="{{route('detail', ['slug' => $item->slug])}}">
                                    <img class="center lozad" data-src="{{$item->thumbnail}}" alt="{{$item->name}}">
                                </a>
                                <div class="top-notice">
                                    <span class="time-ago">{{$item->updated_at->diffForHumans()}}</span>
                                    @if ($item->total_views > 1000)
                                    <span class="type-label hot">Hot</span>
                                    @endif
                                </div>
                            </div>
                            <div class="book_info">
                                <div class="book_name qtip" data-qtip="#truyen-tranh-15893">
                                    <h3><a title="Lostend- Thất Lạc" href="{{route('detail', ['slug' => $item->slug])}}">{{$item->name}}</a></h3>
                                </div>
                                <div class="clear"></div>
                                <div class="last_chapter">
                                @if ($item->conti)
                                    <a href="{{route('showRead', ['slug' => $item->slug, 'chapter' => $item->conti->slug])}}" title="Chapter {{$item->conti->name}}">{{__('string.readContinue')}} {{__('string.chapter')}} {{$item->conti->name}}</a>
                                @else
                                    <a href="#">{{__('string.updating')}}</a>
                                @endif
                                </div>
                            </div>
                            <div class="clear"></div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                <div class="clear"></div>
                {{$comics->links('vendor.pagination.custom')}}
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
@endsection
@section('scripts')
    <script>
        $('.remove-history').click(function() {
            var id = $(this).data('id');
            $.ajax({
                url: '{{route('removeHistory')}}',
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    window.location.reload();
                }
            });
        });
    </script>
@endsection
