
    <span class="story-detail-title"><i class="fa fa-comments"></i>{{__('string.comment')}} (<span class="comment-count">{{$total}}</span>)</span>
    <div class="group01 comments-container">
        <form data-type="comment" class="form-comment main_comment">
            <div class="message-content">
                @if (auth()->check())
                <div class="comment-placeholder" onclick="openComment(this);">{{__('string.note')}}</div>
                <div id="comment_form" class="relative"><textarea id="comment_content" class="form-control hidden" placeholder="Nội dung bình luận"></textarea><div class="comment-info"></div></div>
                <div class="comment-action">
                    <button type="submit" class="btn btn-primary">{{__('string.send')}}</button>
                </div>
                @else
                <div class="comment-placeholder" onclick="alert('{{__('string.warnignLogin')}}')">{{__('string.note')}}</div>
                @endif
            </div>
        </form>
        <div class="list-comment">
            @foreach ($data as $comment)
            <article class="info-comment child_5276162 parent_0 comment-main-level">
                <div class="outsite-comment comment-main-level">
                    <figure class="avartar-comment">
                        <img class="" src="{{$comment->user->avatar}}" alt="{{$comment->user->name}}" style="">
                    </figure>
                    <div class="item-comment">
                        <div class="outline-content-comment">
                            <div>
                                <strong class="level" style="background-image: url({{$comment->user->level->image}});background-size:auto;color:transparent;-webkit-background-clip: text;background-position: center;">{{$comment->user->name}}</strong>
                                <span class="title-user-comment title-member level_6">{{$comment->user->level->level}}</span>
                            </div>
                            <div class="content-comment">
                                {!! $comment->content !!}
                            </div>
                        </div>
                        <div class="action-comment">
                            <span class="like-comment btn-like" data-type="comment" data-id="{{$comment->id}}"><i class="fa fa-thumbs-up"></i> <span class="total-like-comment" id="comment-like-{{$comment->id}}">{{$comment->like }}</span></span>
                            <span class="time"><i class="fa fa-clock"></i> {{$comment->created_at->diffForHumans()}}</span>
                        </div>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
