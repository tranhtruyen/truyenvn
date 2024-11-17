@extends('users.layout.main')
@section('metadata')
    {!! SEO::generate() !!}
    {!! $metaHtml ?? '' !!}
@endsection
@section('styles')

@endsection
@section('class')
detail
@endsection
@section('content')
    <div class="content">
        <div class="div_middle">
            <div class="alert-note-fix"></div>
            <div class="main_content">
                <div class="book_detail">
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
                    </ol>
                    <div class="book_info">
                        <div class="book_avatar" itemtype="https://schema.org/ImageObject">
                            <img itemprop="image" alt="{{$comic->name}}" src="{{$comic->thumbnail}}">
                        </div>
                        <div class="book_other" itemscope="" itemtype="http://schema.org/Book">
                            <h1 itemprop="name">{{$comic->name}}</h1>
                            <div class="txt">
                                <ul class="list-info">
                                    <li class="author row">
                                        <p class="name col-xs-3">
                                            <i class="fa fa-user"></i> {{__('string.author')}}
                                        </p>
                                        <p class="col-xs-9">
                                            @if ($comic->author->count() > 0)
                                            <a class="org" href="{{route('showAuthor', ['slug' => $comic->author->first()->slug])}}">{{$comic->author->first()->name}}</a>
                                            @else
                                            <a class="org" href="#">{{__('string.updating')}}</a>
                                            @endif
                                        </p>
                                    </li>
                                    <li class="status row">
                                        <p class="name col-xs-3">
                                            <i class="fa fa-rss"></i> {{__('string.status')}}
                                        </p>
                                        <p class="col-xs-9">{{$comic->status}}</p>
                                    </li>
                                    <li class="row">
                                        <p class="name col-xs-3">
                                            <i class="fa fa-thumbs-up"></i> {{__('string.viewLike')}}
                                        </p>
                                        <p class="col-xs-9 number-like">{{$comic->votes_count}}</p>
                                    </li>
                                    <li class="row">
                                        <p class="name col-xs-3">
                                            <i class="fa fa-heart"></i> {{__('string.viewFollow')}}
                                        </p>
                                        <p class="col-xs-9">{{$comic->follows_count}}</p>
                                    </li>
                                    <li class="row">
                                        <p class="name col-xs-3">
                                            <i class="fa fa-eye"></i> {{__('string.view')}}
                                        </p>
                                        <p class="col-xs-9">{{$comic->total_views}}</p>
                                    </li>
                                </ul>
                            </div>
                            @if ($comic->categories->count() > 0)
                            <ul class="list01">
                                @foreach ($comic->categories as $category)
                                    <li class="li03"><a href="{{route('showCategory', ['slug' => $category->slug])}}">{{$category->name}}</a></li>
                                @endforeach
                            </ul>
                            @endif
                            <div class="clear"></div>
                            <ul class="story-detail-menu">
                                <li class="li01"><a href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $comic->chapters->first()->slug])}}" class="button is-danger is-rounded"><i class="fa fa-book"></i> {{__('string.readFirst')}}</a></li>
                                <li class="li02">
                                    @if ($follow)
                                    <a href="javascript:void(0);" class="button btn-follow is-danger is-rounded btn-subscribe subscribeBook" data-page="index" data-id="{{$comic->id}}"><span class="fa fa-times"></span> {{__('string.unfollow')}}</a>
                                    @else
                                    <a href="javascript:void(0);" class="button btn-follow is-danger is-rounded btn-subscribe subscribeBook" data-page="index" data-id="{{$comic->id}}"><span class="fa fa-heart"></span> {{__('string.follow')}}</a>
                                    @endif
                                </li>
                                <li class="li03"><a href="javascript:void(0);" class="button is-danger is-rounded btn-vote" data-id="{{$comic->id}}"><i class="fa fa-thumbs-up"></i> {{__('string.like')}}</a></li>
                                <li class="li04"><a href="{{ route('showRead', ['slug' => $comic->slug, 'chapter' => $comic->conti ?? $comic->chapters->first()->slug]) }}" class="button is-info is-rounded"><i class="fa fa-location-arrow" aria-hidden="true"></i> {{__('string.readContinue')}}</a></li>
                            </ul>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <h3><i class="fa fa-info-circle"></i> {{__('string.intro')}}</h3>
                    <div class="story-detail-info detail-content">
                        {!! $comic->content !!}
                    </div>
                    <h3><i class="fa fa-database" aria-hidden="true"></i> {{__('string.listChapter')}}</h3>
                    <div class="list_chapter">
                        <div class="works-chapter-list">
                            @foreach ($chapters as $chapter)
                            <div class="works-chapter-item">
                                <div class="col-md-10 col-sm-10 col-xs-8 name-chap">
                                    <a target="_self" class="" href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $chapter->slug])}}">{{__('string.chapter')}} {{$chapter->name}}</a>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-4 time-chap">
                                    {{$chapter->updated_at->format('d/m/Y')}}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="comment-container">
                        <x-comment :data="$comments" :total="$comic->comments_count" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
            var id_item = '{{$comic->id}}';
            var chapter_id = null;
            var table = "comic";
    </script>
    <script type="text/javascript" src="/assets/js/tinymce.min.js"></script>
    <script type="text/javascript" src="/assets/js/comment.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-vote').click(function() {
                $.ajax({
                    url: '{{ route('vote') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: '{{ $comic->id }}',
                        type: 'comic',
                        vote: 5
                    },
                    success: function(response) {
                        alert(response.message);
                        if(response.status == "success"){
                            location.reload();
                        }
                    },
                    error: function() {
                        alert("{{__('string.error')}}");
                    }
                });
            });

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
