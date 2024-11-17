@extends('users.layout.main')
@section('class') detail @endsection
@section('metadata')
    <title>Sửa Thông Tin Cá Nhân</title>
    <meta property="og:title" content="Sửa Thông Tin Cá Nhân">
    <meta name="robots" content="nofollow, noindex">
@endsection
@section('content')
<div class="content">
    <div class="div_middle">
        <div class="main_content">
            <title>Sửa Thông Tin Cá Nhân</title>
            <section class="main-content">
                <div class="container">
                    <div class="messages columns">
                        <div class="column is-narrow col-md-3 col-sm-12">
                            <ul class="nav-user">
                                <li><a class="li01 is-active" href="{{route('showProfile')}}"><i class="fa fa-user-circle"></i> {{__('string.manageAccount')}}</a></li>
                                <li><a class="li03 " href="{{route('showChangePass')}}"><i class="fa fa-key"></i> {{__('string.changePassword')}}</a></li>
                            </ul>
                        </div>
                        <div class="column columns col-md-9 col-sm-12">
                            <div class="user-right column">
                                <div class="img"><img class="image-avatar" width="50" height="50" src="{{$user->avatar}}"></div>
                                {{-- <input type="file" multiple="false" name="files[]" id="uploadavatar" style="display: none;">
                                <label for="uploadavatar" class="button is-danger btn-avatar">Chọn hình</label> --}}
                            </div>
                            {{-- <div class="user-right column">
                                Dùng hình 18+ sẽ bị khóa tài khoản vĩnh viễn.
                            </div> --}}
                            <div class="user-main column">
                                <div class="level title user-title">
                                    <div class="skillbox">
                                        <span class="level-current">{{$user->level->level}}</span>
                                        <span class="level-next">{{$user->nextLevel ? $user->nextLevel->level : 'Cấp Tối Đa' }}</span>
                                        <div class="progress">
                                            @if ($user->nextLevel)
                                            <span class="progress-bar" style="width: {{($user->exp - $user->level->experience ) / ($user->nextLevel->experience -  $user->level->experience) * 100}}%">{{($user->exp - $user->level->experience ) / ($user->nextLevel->experience -  $user->level->experience) * 100}}%</span>
                                            @else
                                            <span class="progress-bar" style="width: 100%">100%</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="level title">
                                    <p class="level-left has-text-weight-bold">{{__('string.infoAccount')}}</p>
                                </div>
                                <form id="form-update">
                                    <div class="form-change-pass">
                                        <div class="field">
                                            <p class="txt">{{__('string.experience')}}:</p>
                                            <p class="control">
                                                <input class="input" type="point" value="{{$user->exp}}" disabled="">
                                            </p>
                                        </div>
                                        <div class="field">
                                            <p class="txt">Email:</p>
                                            <p class="control">
                                                <input class="input" type="email" value="{{$user->email}}" disabled="">
                                            </p>
                                        </div>
                                    </div>
                                    <div class="level title user-title">
                                        <p class="level-left has-text-weight-bold">{{__('string.infoPersonal')}}</p>
                                    </div>
                                    <div class="form-change-pass user-form">
                                        <div class="field">
                                            <p class="txt">{{__('string.name')}}:</p>
                                            <p class="control">
                                                <input class="input" type="text" id="name" name="name" value="{{$user->name}}">
                                            </p>
                                        </div>
                                        <div class="field">
                                            <p class="control">
                                                <button class="button is-danger" type="submit">{{__('string.save')}}</button>
                                            </p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="clear"></div>
</div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#form-update').submit(function(e) {
                e.preventDefault();
                let name = $('#name').val();
                $.ajax({
                    url: '{{route('updateProfile')}}',
                    type: 'POST',
                    data: {
                        name: name,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        alert('Cập nhật thông tin thành công');
                        window.location.reload();
                    },
                    error: function(data){
                        alert('Cập nhật thông tin thất bại');
                    }
                })
            })
        })
    </script>
@endsection
