@extends('users.layout.main')
@section('class') detail @endsection
@section('metadata')
    <title>Thay Đổi Mật Khẩu</title>
    <meta property="og:title" content="Thay Đổi Mật Khẩu">
    <meta name="robots" content="nofollow, noindex">
@endsection
@section('content')
<div class="content">
    <div class="div_middle">
        <div class="main_content">
            <title>Thay Đổi Mật Khẩu</title>
            <section class="main-content">
                <div class="container">
                    <div class="messages columns">
                        <div class="column is-narrow col-md-3 col-sm-12">
                            <ul class="nav-user">
                                <li><a class="li01" href="{{route('showProfile')}}"><i class="fa fa-user-circle"></i> {{__('string.manageAccount')}}</a></li>
                                <li><a class="li03 is-active" href="{{route('showChangePass')}}"><i class="fa fa-key"></i> {{__('string.changePassword')}}</a></li>
                            </ul>
                        </div>
                        <div class="column col-md-9 col-sm-12">
                            <div class="level title">
                                <p class="level-left has-text-weight-bold">{{__('string.changePassword')}}</p>
                            </div>
                            <form id="form-change">
                                <div class="form-change-pass">
                                    <div class="field">
                                        <p class="txt">{{__('string.currentPassword')}}</p>
                                        <p class="control">
                                            <input class="input" type="password" value="" name="password_old" id="password_old">
                                        </p>
                                    </div>
                                    <div class="field">
                                        <p class="txt">{{__('string.newPassword')}}</p>
                                        <p class="control">
                                            <input class="input" type="password" value="" name="password_new" id="password_new">
                                        </p>
                                    </div>
                                    <div class="field">
                                        <p class="txt">{{__('string.confirmPassword')}}</p>
                                        <p class="control">
                                            <input class="input" type="password" value="" name="confirm_password_new" id="confirm_password_new">
                                        </p>
                                    </div>
                                    <div class="field">
                                        <p class="control">
                                            <button class="button is-danger">{{__('string.changePassword')}}</button>
                                        </p>
                                    </div>
                                </div>
                            </form>
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
            $('#form-change').submit(function(e) {
                e.preventDefault();
                const oldPass = $('#password_old').val();
                const newPass = $('#password_new').val();
                const confirmPass = $('#confirm_password_new').val();
                if(newPass !== confirmPass) {
                    alert('Mật khẩu mới không trùng khớp');
                    return;
                }
                $.ajax({
                    url: '{{route('updatePassword')}}',
                    type: 'POST',
                    data: {
                        name: name,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        alert(data.message);
                        $('#password_old').val('');
                        $('#password_new').val('');
                        $('#confirm_password_new').val('');
                    },
                    error: function(data){
                        alert('Error');
                    }
                })
            })
        })
    </script>
@endsection
