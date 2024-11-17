@php
    use Illuminate\Support\Facades\DB;
    $footer = DB::table('themes')->first()->footer ?? '';
@endphp

{{-- {!! $footer !!} --}}
<footer class="">
    <div class="div_middle">
        <div class="left">
            <p class="logo" itemscope="" itemtype="http://schema.org/Organization">
                <a itemprop="url" href="/">
                    <img itemprop="logo" src="/logo.png" alt="Truyen 18 - Truyện Online">
                </a>
            </p>

	    <p>
	    </p>
        
        </div>
        <div class="right">
            <ul>
                <li><a href="/">Truyện Tranh</a></li>
                <li><a href="/">Truyện Tranh Online</a></li>
                <li><a href="/">Truyện Tranh Mới</a></li>
                <li><a href="/">Truyện Tranh Hay</a></li>
                <li><a href="/">Đọc Truyện Tranh</a></li>
		<li><a href="/the-loai/manhwa-49.html">Manhwa</a></li>
                <li><a href="/the-loai/manhua-35.html">Manhua</a></li>
                <li><a href="/truyen-moi-cap-nhat/trang-1.html?country=4">Manga</a></li>
                <li><a href="/the-loai/ngon-tinh-87.html">Truyện Ngôn Tình</a></li>
                <li><a href="/tag/nettruyen">nettruyen</a></li>
                <li><a href="/tag/toptruyen">toptruyen</a></li>
                <li><a href="/tag/blogtruyen">blogtruyen</a></li>
                <li><a href="/tag/vcomycs">vcomycs</a></li>
		<li><a href="/tag/protruyen">protruyen</a></li>
                <li><a href="/tag/tusachxinh">tusachxinh</a></li>
                <li><a href="/tag/tutientruyen">tutientruyen</a></li>
		<li><a href="/tag/teamlanhlung">teamlanhlung</a></li>
		<li><a href="/" rel="noreferrer">xem anime</a></li>
		<li><a href="/" rel="noreferrer">anime vietsub</a></li>
		<li><a href="/" rel="noreferrer">đọc truyện chữ</a></li>
		<li><a href="/" rel="noreferrer">truyện chữ</a></li>
		<li><a href="/" rel="noreferrer">caytruyen</a></li>

		                <div class="clear"></div>
            </ul>
	    <p class="link">Email: truyen18@site.com</p>
	    <p class="link"><a href="/tin-tuc/chinh-sach-bao-mat.html">Chính Sách Bảo Mật</a></p>
        <a class="text_link" href="/">trực tiếp bóng đá</a>

        <div class="clear"></div>
    </div>
</footer>
