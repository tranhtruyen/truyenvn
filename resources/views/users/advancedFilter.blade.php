@extends("users.layout.main")
@section('metadata')
    <title>Tìm kiếm nâng cao</title>
    <meta property="og:title" content="Tìm kiếm nâng cao">
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
                            <i class="fa fa-font-awesome" aria-hidden="true"></i> {{__('string.advancedFilter')}}            </p>
                    </h1>
                    <div class="clear"></div>
                </div>
                <div class="story-list-bl01 box">
                    <div class="text-center">
                        <button type="button" class="btn btn-info btn-collapse">
                            <span class="show-text">{{__('string.show')}}</span>
                            <span class="hide-text hidden">{{__('string.hide')}}  </span>{{__('string.frameSearch')}}
                        </button>
                    </div>
                    <div class="advsearch-form hidden">
                        <div class="form-group clearfix">
                            <p><span class="icon-tick"></span> {{__('string.checkCategory')}}</p>
                            <p><span class="icon-cross"></span> {{__('string.removeCategory')}}</p>
                            <p><span class="icon-checkbox"></span> {{__('string.bothCategory')}}</p>
                        </div>
                        <div class="form-group row text-center">
                            <a class="btn btn-primary btn-sm btn-reset" href="{{route('showSearch')}}"><i class="fa fa-repeat"></i> Reset</a>
                        </div>
                        <div class="form-group row">
                            <div class="label-search">{{__('string.categoryComic')}}</div>
                            <div class="">
                                @foreach ($categories as $category)
                                <div class="genre-item">
                                    <span class="{{ request()->has('category') && in_array($category->id, explode(',', request()->get('category'))) ? 'icon-tick' : (request()->has('notcategory') && in_array($category->id, explode(',', request()->get('notcategory'))) ? 'icon-cross' : 'icon-checkbox') }}" data-id="{{$category->id}}"></span>{{$category->name}}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="label-search">{{__('string.countChapter')}}</div>
                            <div class="select select-search is-warning">
                                <select class="custom-select" id="minchapter">
                                    <option @if ((request()->has('minchapter') && request()->input('minchapter') == 0) || !request()->has('minchapter'))
                                        selected
                                    @endif value="0">&gt; 0</option>
                                    <option @if (request()->has('minchapter') && request()->input('minchapter') == 50)
                                        selected
                                    @endif value="50">&gt;= 50</option>
                                    <option @if (request()->has('minchapter') && request()->input('minchapter') == 100)
                                        selected
                                    @endif value="100">&gt;= 100</option>
                                    <option @if (request()->has('minchapter') && request()->input('minchapter') == 200)
                                        selected
                                    @endif value="200">&gt;= 200</option>
                                    <option @if (request()->has('minchapter') && request()->input('minchapter') == 300)
                                        selected
                                    @endif value="300">&gt;= 300</option>
                                    <option @if (request()->has('minchapter') && request()->input('minchapter') == 400)
                                        selected
                                    @endif value="400">&gt;= 400</option>
                                    <option @if (request()->has('minchapter') && request()->input('minchapter') == 500)
                                        selected
                                    @endif value="500">&gt;= 500</option>
                                </select>
                            </div>
                            <div class="label-search">{{__('string.sort')}}</div>
                            <div class="select select-search is-warning">
                                <select class="custom-select" id="sort">
                                    <option @if (request()->has('sort') && request()->input('sort') == 0)
                                        selected

                                    @endif value="0">{{__('string.descCreated')}}</option>
                                    <option @if (request()->has('sort') && request()->input('sort') == 1)
                                        selected

                                    @endif value="1">{{__('string.ascCreated')}}</option>
                                    <option @if (request()->has('sort') && request()->input('sort') == 2)
                                        selected

                                    @endif value="2">{{__('string.descUpdate')}}</option>
                                    <option @if (request()->has('sort') && request()->input('sort') == 3)
                                        selected

                                    @endif value="3">{{__('string.ascUpdate')}}</option>
                                    <option @if (request()->has('sort') && request()->input('sort') == 4)
                                        selected

                                    @endif value="4">{{__('string.descView')}}</option>
                                    <option @if (request()->has('sort') && request()->input('sort') == 5)
                                        selected

                                    @endif value="5">{{__('string.ascView')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="label-search">{{__('string.status')}}</div>
                            <div class="select select-search is-warning">
                                <select class="custom-select" id="status">
                                    <option @if (request()->has('status') && request()->input('status') == 0)
                                        selected

                                    @endif value="0">{{__('string.all')}}</option>
                                    <option @if (request()->has('status') && request()->input('status') == 1)
                                        selected

                                    @endif value="1">{{__('string.ongoing')}}</option>
                                    <option @if (request()->has('status') && request()->input('status') == 2)
                                        selected

                                    @endif value="2">{{__('string.completed')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="text-center">
                                <button type="button" class="btn btn-success btn-search is-danger">{{__('string.searchText')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list_grid_out">
                    @if ($comics->count() == 0)
                    <div class="warning-list box">{{__('string.notFound')}}</div>
                    @endif
                    <ul class="list_grid grid">
                        @foreach ($comics as $item)
                        <li>
                            <div class="book_avatar">
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
                                @if ($item->chapters->count() > 0)
                                    <a href="{{route('showRead', ['slug' => $item->slug, 'chapter' => $item->chapters->last()->slug])}}" title="Chapter {{$item->chapters->last()->name}}">Chapter {{$item->chapters->last()->name}}</a>
                                @else
                                    <a href="#">{{__('string.updating')}}</a>
                                @endif
                                </div>
                            </div>
                            <div class="clear"></div>
                        </li>
                        @endforeach
                    </ul>
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

@endsection
