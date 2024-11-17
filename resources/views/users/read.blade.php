@extends('users.layout.main')
@section('metadata')
{!! SEO::generate() !!}
{!! $metaHtml ?? '' !!}
@endsection
@section('class')
detail
@endsection
@section('content')
<div class="content background-black">
    <div class="div_middle">
        <div class="alert-note-fix"></div>
        <div class="main_content">
            <div id="chapter_content">
                <div class="chapter_content_div chapter_new_load">
                    <div class="box">
                        <div id="path" class="path-top">
                            <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                                <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                                    <a itemprop="item" href="{{route('home')}}">
                                        <span itemprop="name">{{__('string.home')}}</span>
                                    </a>
                                    <meta itemprop="position" content="1">
                                </li>
                                <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                                    <a itemprop="item" href="{{route('detail', ['slug' => $comic->slug])}}">
                                        <span itemprop="name">{{$comic->name}}</span>
                                    </a>
                                    <meta itemprop="position" content="2">
                                </li>
                                <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                                    <a itemprop="item" href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $chapterSelected->slug])}}">
                                        <span itemprop="name">{{__('string.chapter')}} {{$chapterSelected->name}}</span>
                                    </a>
                                    <meta itemprop="position" content="3">
                                </li>
                            </ol>
                        </div>
                        <div>
                            <h1 class="detail-title txt-primary"><a href="{{route('detail', ['slug' => $comic->slug])}}">{{$comic->slug}}</a> - {{__('string.home')}} {{$chapterSelected->name}}</h1>
                            <time datetime="{{$chapterSelected->updated_at}}">({{__('string.updated_at')}}: {{ \Carbon\Carbon::parse($chapterSelected->updated_at)->format('H:i d/m/y') }})</time>
                        </div>
                        <div class="chapter-control">
                            <div class="mrt10">
                                <span class="note-server">{{__('string.noteRead')}}</span>
                                <div class="mrt10">
                                    @foreach ($servers as $server)
                                    <a rel="nofollow" href="#" class="loadchapter btn btn-success server_1">{{$server->server}}</a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="alert alert-info mrt10">
                                <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512" class="mr-1"><path fill="#31708f" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"></path></svg>
                                <i>{{__('string.noteRead2')}}</i>
                            </div>

                            <div class="d-flex align-items-center justify-content-center">
                                @isset($comic->prevChap)
                                <a class="btn btn-info go-btn prev text-white m-1 d-block" href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $comic->prevChap->slug])}}"><em class="fa fa-arrow-left"></em> {{__('string.previisChapter')}}</a>
                                @endisset
                                @isset($comic->nextChap)
                                <a class="btn btn-info go-btn prev text-white m-1 d-block" href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $comic->nextChap->slug])}}">{{__('string.nextChapter')}} <em class="fa fa-arrow-right"></em></a>
                                @endisset
                            </div>
                        </div>
                    </div>
                    <div class="chapter_content">
                        <div style="overflow: hidden;">
                            @foreach ($chapterSelected->images as $image)
                            <div id="{{$image->page}}" class="page-chapter">
                                <img class="lozad" data-src="{{$image->link}}" alt="{{$comic->name}} Chap {{$chapterSelected->name}} - Next Chap {{$chapterSelected->chapter_number + 1}}"></div>
                            @endforeach
                        </div>
                        <div class="clear"></div>
                        <div class="chapter_control" style="z-index: 9999;">
                            <a href="{{route('home')}}" class="home"><i class="fa fa-home" aria-hidden="true"></i></a>
                            <a onclick="" class="home changeserver"><i class="fa fa-undo error"></i><span>1</span></a>
                            @if ($comic->prevChap)
                            <a class="prev control-button link-prev-chap" href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $comic->prevChap->slug])}}" title="{{$comic->name}} Chap {{$comic->prevChap->name}}"><i class="fa fa-chevron-left"></i></a>
                            @else
                            <a class="prev disable control-button link-prev-chap" href="#"><i class="fa fa-chevron-left"></i></a>
                            @endif
                            <select class="chapter_list middle selectEpisode">
                                @foreach ($chapters as $chapter)
                                <option value="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $chapter->slug])}}" @if ($chapter->slug == $chapterSelected->slug)
                                    selected
                                @endif>{{__('string.chapter')}} {{$chapter->name}}</option>
                                @endforeach
                            </select>
                            @isset($comic->nextChap)
                            <a class="next control-button link-next-chap" href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $comic->nextChap->slug])}}" title="{{$comic->name}} Chap {{$comic->nextChap->name}}"><i class="fa fa-chevron-right"></i></a>
                            @else
                            <a class="next disable control-button link-next-chap" href="#"><i class="fa fa-chevron-right"></i></a>
                            @endisset
                            @if ($follow)
                            <a href="javascript:void(0);" class="button btn-follow is-danger is-rounded subscribeBook btn-unsubscribe" data-id="{{$comic->id}}" data-page="detail"><i class="fa fa-times"></i> <span>{{__('string.unfollow')}}</span></a>
                            @else
                            <a href="javascript:void(0);" class="button btn-follow is-danger is-rounded btn-subscribe subscribeBook" data-id="{{$comic->id}}" data-page="detail"><i class="fa fa-heart"></i> <span>{{__('string.follow')}}</span></a>
                            @endif
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="box bottom-chap">
                        <div class="chapter-control" style="z-index: 9999">
                            <div class="d-flex align-items-center justify-content-center">
                                @isset($comic->prevChap)
                                <a class="btn btn-info go-btn prev text-white m-1 d-block" href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $comic->prevChap->slug])}}"><em class="fa fa-arrow-left"></em> {{__('string.previisChapter')}}</a>
                                @endisset
                                @isset($comic->nextChap)
                                <a class="btn btn-info go-btn prev text-white m-1 d-block" href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $comic->nextChap->slug])}}">{{__('string.nextChapter')}} <em class="fa fa-arrow-right"></em></a>
                                @endisset
                            </div>
                        </div>
                        <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                            <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                                <a itemprop="item" href="{{route('home')}}">
                                    <span itemprop="name">{{__('string.home')}}</span>
                                </a>
                                <meta itemprop="position" content="1">
                            </li>
                            <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                                <a itemprop="item" href="{{route('detail', ['slug' => $comic->slug])}}">
                                    <span itemprop="name">{{$comic->name}}</span>
                                </a>
                                <meta itemprop="position" content="2">
                            </li>
                            <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                                <a itemprop="item" href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $chapterSelected->slug])}}">
                                    <span itemprop="name">{{__('string.chapter')}} {{$chapterSelected->name}}</span>
                                </a>
                                <meta itemprop="position" content="3">
                            </li>
                        </ol>
                    </div>
                    <div class="comment-container box" id="comment_list">
                        <x-comment :data="$comments" :total="$comic->comments_count" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    var id_item = '{{$comic->id}}';
    var chapter_id = '{{$chapterSelected->id}}';
    var table = "comic";
</script>
<script type="text/javascript" src="/assets/js/tinymce.min.js"></script>
<script type="text/javascript" src="/assets/js/comment.min.js"></script>
<script>
    $(document).ready(function() {
        @if (auth()->check())
            setTimeout(function() {
                $.ajax({
                    url: "{{route('upExp')}}",
                    type: 'POST',
                    data: {
                        id: '{{auth()->user()->id}}' ?? null,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }, 60000);
        @endif
        $('.btn-follow').click(function() {
            $.ajax({
                url: '{{ route('follow') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: '{{ $comic->id }}',
                    type: 'comic'
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function() {
                    alert("{{__('string.error')}}");
                }
            });
        });
    });
</script>
@endsection
