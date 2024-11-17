@php
    use App\Models\Category;
    use Illuminate\Support\Facades\Cache;
    $categories = Category::all();
@endphp
<header>
    <div class="top">
        <div class="div_middle">
            <div class="logo">
                <a href="/" title="Truyện tranh online">
                    <p class="pc_display">
                        <img src="/logo.png" width="146" height="34" alt="">
                    </p>
                    <img alt="{{env('APP_NAME')}}" class="mobile_display" src="{{asset('favicon.png')}}">
                </a>
                <div class="clear"></div>
            </div>
            <div class="right">
                <ul>
                    <li onclick="$('header .top_search').slideDown()" class="other"><i class="fa fa-search" aria-hidden="true"></i></li>
                    @if (auth()->check())
                    <li class="profile">
                        <div onclick="show_hidden_div('member_control');" class="member_profile">
                            <img src="{{auth()->user()->avatar}}">
                            <div class="clear"></div>
                        </div>
                        <div target="member_control" id="member_control" class="hidden_div" style="display: none;">
                            <ul>
                                <li><a href="{{route('showFollow')}}">{{__('string.listFollow')}}</a></li>
                                <li><a href="{{route('showHistory')}}">{{__('string.historyRead')}}</a></li>
                                <li><a rel="nofollow" href="{{route('showProfile')}}">{{__('string.setting')}}</a></li>
                                <li><a rel="nofollow" href="{{route('logout')}}">{{__('string.logout')}}</a></li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                    </li>
                    @else
                    <li><button onclick="popup('register')">{{__('string.register')}}</button></li>
                    <li><button onclick="popup('login')">{{__('string.login')}}</button></li>
                    @endif
                </ul>
                <div class="clear"></div>
            </div>
            <button id="setting_dark_mode" onclick="setting_active_dark_mode(this);" class="dark_mode">
                <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
            </button>
            <div class="top_search">
                <input class="search" id="search_input" placeholder="{{__('string.search')}}">
                <button>
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
                <div class="search_result">
                    <ul></ul>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="bottom">
        <div class="div_middle">
            <ul id="header_left_menu">
                <li>
                    <a class="tags_name pc_hover" href="{{route('home')}}">{{__('string.home')}}</a>
                </li>
                <li class="menu_hidden">
                    <p class="tags_name pc_hover mega_menu" style="cursor: pointer">{{__('string.category')}} <i class="fa fa-caret-down"></i></p>
                    <div class="hidden_menu book_tags">
                        <div class="div_middle">
                            <div class="book_tags_content">
                                @foreach ($categories as $category)
                                    <p><a title="{{$category->name}}" href="{{route('showCategory', ['slug' => $category->slug])}}">{{$category->name}}</a></p>
                                @endforeach
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="menu_hidden">
                    <p class="tags_name pc_hover mega_menu" style="cursor: pointer">{{__('string.ranking')}} <i class="fa fa-caret-down"></i></p>
                    <div class="hidden_menu book_tags">
                        <div class="div_middle">
                            <div class="book_tags_content">
                                <p><a rel="nofollow" href="{{route('showList', ['slug' => 'top-ngay'])}}">{{__('string.topDaily')}}</a></p>
                                <p><a rel="nofollow" href="{{route('showList', ['slug' => 'top-tuan'])}}">{{__('string.topWeek')}}</a></p>
                                <p><a rel="nofollow" href="{{route('showList', ['slug' => 'top-thang'])}}">{{__('string.topMonth')}}</a></p>
                                <p><a rel="nofollow" href="{{route('showList', ['slug' => 'truyen-yeu-thich'])}}">{{__('string.favorite')}}</a></p>
                                <p><a rel="nofollow" href="{{route('showList', ['slug' => 'truyen-moi-cap-nhat'])}}">{{__('string.comicNewRelease')}}</a></p>
                                <p><a rel="nofollow" href="{{route('showList', ['slug' => 'truyen-tranh-moi'])}}">{{__('string.new')}}</a></p>
                                <p><a rel="nofollow" href="{{route('showList', ['slug' => 'truyen-full'])}}">{{__('string.full')}}</a></p>
                                <p><a rel="nofollow" href="{{route('showList', ['slug' => 'truyen-ngau-nhien'])}}">{{__('string.random')}}</a></p>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="menu_hidden">
                    <a class="tags_name pc_hover" href="{{route('showList', ['slug' => 'truyen-con-gai'])}}">{{__('string.girl')}}</a>
                </li>
                <li class="menu_hidden">
                    <a class="tags_name pc_hover" href="{{route('showList', ['slug' => 'truyen-con-trai'])}}">{{__('string.boy')}}</a>
                </li>
                <li class="menu_hidden">
                    <a class="tags_name pc_hover" href="{{route('showSearch')}}">{{__('string.searchText')}}</a>
                </li>
                <li class="menu_hidden">
                    <a class="tags_name pc_hover" rel="nofollow" href="{{route('showHistory')}}">{{__('string.history')}}</a>
                </li>
                <li class="menu_hidden">
                    <a class="tags_name pc_hover" rel="nofollow" href="{{route('showFollow')}}">{{__('string.follow')}}</a>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="nav_menu mobile_display"><i onclick="show_bottom_menu(this)" class="fa fa-list" aria-hidden="true"></i></div>
    </div>
</header>
