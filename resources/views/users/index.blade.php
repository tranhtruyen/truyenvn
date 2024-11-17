@extends('users.layout.main')
@section('metadata')
{!! SEO::generate() !!}
{!! $metaHtml ?? '' !!}
@endsection
@section('class') homepage @endsection
@section('content')
    <div class="content">
        <div class="div_middle">
            <div class="alert-note-fix"></div>
            <div class="main_content">
                <div class="homepage_suggest">
                    <h2>
                        <p class="text_list_hot">
                            <i class="fa fa-star"></i> {{ __('string.hot') }}
                        </p>
                    </h2>
                    <div id="div_suggest">
                        <ul class="list_grid grid" id="list_suggest" style="margin-left: -380px;">
                            @foreach ($comicsHot as $item)
                            <li>
                                <div class="book_avatar">
                                    <a href="{{route('detail', ['slug' => $item->slug])}}"><img class="center lozad" data-src="{{$item->thumbnail}}" alt="{{$item->name}}"></a>
                                    <div class="top-notice">
                                        <span class="time-ago">{{$item->updated_at->diffForHumans()}}</span>
                                        <span class="type-label hot">Hot</span>
                                    </div>
                                </div>
                                <div class="book_info">
                                    <div class="book_name">
                                        <h3 itemprop="name"><a title="{{$item->name}}" href="{{route('detail', ['slug' => $item->slug])}}">{{$item->name}}</a></h3>
                                    </div>
                                    <div class="clear"></div>
                                    @if ($item->chapters->count() > 0)
                                    <div class="last_chapter">
                                        <a href="{{route('showRead', ['slug' => $item->slug, 'chapter' => $item->chapters->last()->slug])}}">{{ __('string.chapter') }} {{$item->chapters->last()->name}}</a>
                                    </div>
                                    @endif
                                </div>
                                <div class="clear"></div>
                            @endforeach
                            </li>
                        </ul>
                        <div class="clear"></div>
                        <div class="scroll" style="display: block;">
                            <div onclick="scroll_div('left');" class="left">
                                <i class="fa fa-angle-left"></i>
                            </div>
                            <div onclick="scroll_div('right');" class="right">
                                <i class="fa fa-angle-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="main_homepage">
                    <div class="homepage_tags">
                        <h1>
                            <p class="text_list_update">
                                <a href="/truyen-moi-cap-nhat.html"><i class="fa fa-cloud-download" aria-hidden="true"></i> {{ __('string.newRelease') }}</a>
                            </p>
                        </h1>
                        <div class="sort">
                            <a href="{{route('showSearch')}}">
                                <button> <i class="fa fa-filter" aria-hidden="true"></i></button>
                            </a>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="list_grid_out">
                        <ul id="list_new" class="list_grid grid">
                            @foreach ($comicsRecentUpdate as $item)
                            <li>
                                <div class="book_avatar">
                                    <a href="{{route('detail', ['slug' => $item->slug])}}">
                                        <img class="center lozad" data-src="{{$item->thumbnail}}" alt="{{$item->name}}">
                                    </a>
                                    <div class="top-notice">
                                        <span class="time-ago">{{$item->updated_at->diffForHumans()}}</span>
                                        @if ($item->total_views > 1000)
                                        <span class="type-label hot">Hot</span>
                                        @elseif ($item->created_at->diffInDays() < 7)
                                        <span class="type-label new">New</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="book_info">
                                    <div class="book_name qtip" data-qtip="#truyen-tranh-2950">
                                        <h3><a title="{{$item->name}}" href="{{route('detail', ['slug' => $item->slug])}}">{{$item->name}}</a></h3>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="last_chapter">
                                    @if ($item->chapters->count() > 0)
                                        <a title="Chapter {{$item->chapters->last()->name}}" href="{{route('showRead', ['slug' => $item->slug, 'chapter' => $item->chapters->last()->slug])}}">{{ __('string.chapter') }} {{$item->chapters->last()->name}}</a>
                                    @else
                                        <a href="#">{{__('string.updating')}}</a>
                                    @endif
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </li>
                            @endforeach
                        </ul>
                        <div class="clear"></div>
                    </div>
                    <div class="has-text-centered">
                        <a href="{{route('showList', ['slug' => 'truyen-moi-cap-nhat'])}}" class="view-more-btn">{{ __('string.viewMore')}}</a>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
@endsection
