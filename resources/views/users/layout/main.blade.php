@extends('users.main')
@section('main_content')
    @include('users.layout.header')
    @yield('content')
    @if (!auth()->check())
    <div class="popup" style="display: none;">
        <div id="popup_content">
        <div class="popup_content popup_center_important">
            <h2 class="module_login" style="display: none;">Đăng nhập</h2>
            <h2 class="module_register" style="">Đăng ký mới</h2>
            <h2 class="module_forgot" style="display: none;">Quên mật khẩu</h2>
            <div class="clear"></div>
            <ul class="module_login" style="display: none;">
                <li>
                    <p>Email</p>
                    <input maxlength="150" id="email_login" value="" autocomplete="chrome-off">
                </li>
                <li>
                    <p>Mật khẩu</p>
                    <input type="password" id="password_login" value="" autocomplete="chrome-off">
                </li>
            </ul>
            <ul class="module_register" style="">
                <li>
                    <p>Tên</p>
                    <input maxlength="150" id="name_register" value="" autocomplete="chrome-off">
                </li>
                <li>
                    <p>Email</p>
                    <input maxlength="150" id="email_register" value="" autocomplete="chrome-off">
                </li>
                <li>
                    <p>Mật khẩu:</p>
                    <input type="password" id="password_register" value="" autocomplete="chrome-off">
                </li>
            </ul>
            <ul class="module_forgot" style="display: none;">
                <li>
                    <p>Email</p>
                    <input maxlength="150" id="email-forgot" value="" autocomplete="chrome-off">
                </li>
                <li class="forgot-captcha">
                    <input type="text" class="captcha" id="captcha_forgot" name="captcha_forgot" placeholder="Mã xác nhận">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="Mã Xác Nhận">
                    <span class="refresh-captcha" onclick="captcha('forgot')"><i class="fa fa-refresh" aria-hidden="true"></i></span>
                    <input type="hidden" name="captcha-forgot" id="captcha-forgot" value="">
                </li>
                <li class="step_1"><button class="button-forgot">Gửi</button></li>
            </ul>
            <ul class="module_success" style="display: none;">
                <li>
                    <div class="sent-password-section">
                        <i class="fa fa-check-circle check-icon"></i>
                        <span class="caption">Mật khẩu mới đã được gửi vào hộp thư của bạn.</span>
                    </div>
                </li>
            </ul>
            <div class="clear"></div>
            <div>
                <div class="login_redirect">
                    <p><span class="module_login" style="display: none;"><a onclick="popup('register')">Đăng ký</a> </span><a onclick="popup('login')" class="module_register module_forgot module_success" style="">Đăng nhập</a></p>
                </div>
                <div class="yes_no">
                    <button type="button" class="module_register" onclick="register()" style="">Đăng ký</button>
                    <button type="button" class="module_login button_login" style="display: none;">Đăng nhập</button>
                    <button onclick="$('.popup').hide();" type="button" class="no">Hủy</button>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>

            <div class="social-login">

                <a href="{{route('loginGoogle')}}" class="facebook-link"><i class="fa fa-facebook" aria-hidden="true"></i></a>

                <a href="{{route('loginGoogle')}}" class="google-link"><i class="fa fa-google" aria-hidden="true"></i></a>
            </div>
            <div class="clear"></div>
        </div>
        </div>
        <div class="popup_layer"></div>
    </div>
    @endif
    <a id="back-to-top" style="display: none;">
        <i class="fa fa-angle-up"></i>
    </a>
    @include('users.layout.footer')
@endsection
