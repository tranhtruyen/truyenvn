@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Crawl Otruyen'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="py-4">
            <div class="card step-1">
                <div class="card-body">
                    <form id="apiForm">
                        <div class="alert alert-danger d-flex align-items-center d-none">
                            <p class="text-danger message-error"></p>
                        </div>
                        <div class="form-group mb-3">
                            <label for="link">Link</label>
                            <textarea id="link" class="form-control" rows="5" name="link">https://otruyenapi.com/v1/api/danh-sach</textarea>
                            <small><i>Mỗi link cách nhau 1 dòng (nếu nhiều link thì bắt buộc không được có link: https://otruyenapi.com/v1/api/danh-sach)</i></small>
                        </div>
                        <div class="form-group mb-3 d-flex gap-2 w-100">
                            <div class="w-50">
                                <label for="page" class="form-label">Crawl từ trang</label>
                                <input type="number" id="page" class="form-control" name="page" min="1" value="1">
                            </div>
                            <div class="w-50">
                                <label for="toPage" class="form-label">Đến trang</label>
                                <input type="number" id="toPage" class="form-control" name="page" min="1" value="1">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="option" class="form-label text-danger">Loại trừ số chương (lớn hơn tự động bỏ qua)</label>
                            <input type="number" id="option" class="form-control" name="option" min="0" value="100">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-load">Tải</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card step-2 d-none">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12">
                            <h4>Chọn truyện</h4>
                            <p>Đã chọn <span class="selected-movie-count">0</span>/<span class="total-movie-count">0</span> bộ</p>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="check-all" checked="">
                                <label class="custom-control-label" for="customCheck1">Lấy tất cả</label>
                            </div>
                            <div class="row px-3 my-3">
                                <div class="w-100 col-form-label overflow-auto rounded-2" id="movie-list"
                                     style="height: 30rem;background-color: #f7f7f7">
                                </div>
                            </div>
                            <button class="btn btn-secondary btn-previous">Trước</button>
                            <button class="btn btn-primary btn-next">Tiếp</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card step-3 row d-none">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12">
                            <h4>Đang tiến hành...</h4>
                            <p>Crawl <span class="crawled-count">0</span>/<span class="total-crawl-count">0</span>
                                truyện (Thành công: <span class="crawl-success-count">0</span>, thất bại: <span
                                    class="crawl-failed-count">0</span>).</p>
                            <p class="craw-chapter-status d-none">Crawl <span class="crawled-chapter-count">0</span>/<span class="total-crawl-chapter-count">0</span>
                                chương (Thành công: <span class="crawl-success-chapter-count">0</span>, thất bại: <span
                                    class="crawl-failed-chapter-count">0</span>).</p>
                            <div class="form-group row p-3">
                                <div class="w-100 col-form-label overflow-auto mb-5" id="crawl-list"
                                        style="height: 50vh;background-color: #f7f7f7">
                                </div>
                                <small><i id="wait_message"></i></small>
                            </div>
                            <button class="btn btn-primary btn-finally">Xong</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
</div>
@endsection
@push('js')
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        function crawlMultiLink(value, btn) {
            let comicSlugs = [];
            let result = '';
            let count = 0;
            value.forEach((link, index) => {
                $.ajax({
                    url: link,
                    method: 'GET',
                    success: function(response) {
                        let comic = response.data.item;
                        if(parseInt(comic.chapters[0].server_data[comic.chapters[0].server_data.length - 1].chapter_name) > parseInt($('#option').val())) {
                            return;
                        }
                        result += '<div class="form-check">' +
                            '<input type="checkbox" class="form-check-input comic-checkbox" checked id="comic-' + index + '" value="' + comic.slug + '">' +
                            '<label class="custom-control-label">'+ comic.name +'</label>' +
                            '</div>';
                        comicSlugs.push(comic.slug);
                        count++;
                        $('#movie-list').html(result);
                        $('.step-1').addClass("d-none");
                        $('.step-2').removeClass("d-none");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $(".alert-danger").removeClass("d-none");
                        $(".message-error").text("Điền link không đúng định dạng(Ví dụ: https://otruyenapi.com/v1/api/truyen-tranh/yukionna-to-kani-wo-kuu-okinawa-hen).");
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Tải');
                        $('.selected-movie-count').text(count);
                        $('.total-movie-count').text(count);
                        sessionStorage.setItem('ListComics', comicSlugs);
                    }
                });
            });
        }
        function crawlHomePage(value, btn) {
            let comicSlugs = [];
            const page = $('#page').val();
            const toPage = $('#toPage').val();
            let result = '';
            let count = 0;
            for(let i=parseInt(page); i <= parseInt(toPage); i++){
                $.ajax({
                    url: value + "?page=" + i,
                    method: 'GET',
                    success: function(response) {
                        let comics = response.data.items;
                        comics.forEach((comic, index) => {
                            if(comic.chaptersLatest && parseInt(comic.chaptersLatest[0].chapter_name) > parseInt($('#option').val())) {
                                return;
                            }
                            result += '<div class="form-check">' +
                            '<input type="checkbox" class="form-check-input comic-checkbox" checked id="comic-' + index + '" value="' + comic.slug + '">' +
                            '<label class="custom-control-label">'+ comic.name +'</label>' +
                            '</div>';
                            comicSlugs.push(comic.slug);
                            count++;
                        });
                        $('.selected-movie-count').text(count);
                        $('.total-movie-count').text(count);
                        $('#movie-list').html(result);
                        $('.step-1').addClass("d-none");
                        $('.step-2').removeClass("d-none");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $(".alert-danger").removeClass("d-none");
                        $(".message-error").text("Ashiba. Lỗi gì nhỉ?");
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Tải');
                        sessionStorage.setItem('ListComics', comicSlugs);
                    }
                });
            }
        }
        let urlHome = "https://otruyenapi.com/v1/api/danh-sach";
        $('#apiForm').on('submit', function(event) {
            event.preventDefault();
            let submitButton = $(this).find('button[type="submit"]');
            submitButton.prop('disabled', true).text('Đang crawl...');
            let linkValue = $('#link').val();
            sessionStorage.setItem('ListComics', "");
            $(".alert-danger").addClass("d-none");
            $('#movie-list').html("");
            $('.selected-movie-count').text("0");
            $('.total-movie-count').text("0");
            if(linkValue.includes(urlHome)) {
                crawlHomePage(linkValue, submitButton);
            } else {
                let links = $('#link').val().split('\n');
                if(links.includes(urlHome)) {
                    $(".alert-danger").removeClass("d-none");
                    $(".message-error").text("Không được chứa link: " + urlHome);
                    submitButton.prop('disabled', false).text('Tải');
                }else{
                    crawlMultiLink(links, submitButton);
                }
            }
        });
        $('#check-all').on('change', function() {
            let comicSlugs = sessionStorage.getItem('ListComics') ? sessionStorage.getItem('ListComics').split(',') : [];
            let comicCheckboxes = $('.comic-checkbox');
            if ($(this).is(':checked')) {
                comicCheckboxes.each(function() {
                    let comicSlug = $(this).val();
                    if (!comicSlugs.includes(comicSlug)) {
                        comicSlugs.push(comicSlug);
                    }
                    $(this).prop('checked', true);
                });
            } else {
                comicCheckboxes.each(function() {
                    let comicSlug = $(this).val();
                    let index = comicSlugs.indexOf(comicSlug);
                    if (index !== -1) {
                        comicSlugs.splice(index, 1);
                    }
                    $(this).prop('checked', false);
                });
            }
            sessionStorage.setItem('ListComics', comicSlugs.join(','));
            updateCounts();
        });
        $(document).on('change', '.comic-checkbox', function() {
            let comicSlugs = sessionStorage.getItem('ListComics') ? sessionStorage.getItem('ListComics').split(',') : [];
            let comicSlug = $(this).val();
            if ($(this).is(':checked')) {
                comicSlugs.push(comicSlug);
            } else {
                let index = comicSlugs.indexOf(comicSlug);
                if (index !== -1) {
                    comicSlugs.splice(index, 1);
                }
            }
            sessionStorage.setItem('ListComics', comicSlugs.join(','));
            updateCounts();
        });
        function updateCounts() {
            let totalComics = $('.comic-checkbox').length;
            let checkedComics = $('.comic-checkbox:checked').length;
            $('.selected-movie-count').text(checkedComics);
            $('.total-movie-count').text(totalComics);
        }
        $('.btn-previous').on('click', function() {
            $('.step-2').addClass("d-none");
            $('.step-1').removeClass("d-none");
        });
        $('.btn-next').on('click', async function() {
            let listComics = sessionStorage.getItem('ListComics');
            let comicSlugs = listComics.split(',');
            $('.step-2').addClass("d-none");
            $('.step-3').removeClass("d-none");
            $(".total-crawl-count").text(comicSlugs.length);
            ajaxRequests = [];
            let messages = "";
            let totalChapters = 0;
            let currentChap = 0;
            for (const comicSlug of comicSlugs) {
                try {
                    const response = await $.ajax({
                        url: "{{env('LINK_OTRUYEN_API')}}/" + comicSlug,
                        method: 'GET'
                    });

                    const res = await $.ajax({
                        url: "{{route("admin.addComicByCrawl")}}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            name: response.data.item.name,
                            slug: response.data.item.slug,
                            origin_name: response.data.item.origin_name[0],
                            content: response.data.item.content,
                            status: response.data.item.status,
                            author: response.data.item.author[0],
                            thumbnail: response.data.seoOnPage.seoSchema.image,
                            categories: response.data.item.category,
                            serverName: 'VIP #1'
                        }
                    });

                    totalChapters += response.data.item.chapters[0] ? response.data.item.chapters[0].server_data.length : 0;
                    $(".total-crawl-chapter-count").text(totalChapters);
                    messages += "<span class='text-success'><i class='bi bi-check'></i>" + res.message + "</span><br>";
                    $(".crawl-success-count").text(parseInt($(".crawl-success-count").text()) + 1);
                    $(".crawled-count").text(parseInt($(".crawled-count").text()) + 1);

                    if (response.data.item.chapters[0]) {
                        for (const chapter of response.data.item.chapters[0].server_data.slice().reverse()) {
                            if (parseFloat(chapter.chapter_name) == parseFloat(res.currentChapter)) {
                                currentChap += response.data.item.chapters[0].server_data.length - parseFloat(chapter.chapter_name) + parseFloat(res.currentChapter);
                                if (parseInt(currentChap) == totalChapters) {
                                    $(".btn-finally").text('Xong');
                                }
                                $(".crawled-chapter-count").text(currentChap);
                                break;
                            }

                            try {
                                const response1 = await $.ajax({
                                    url: "{{route("admin.addChapterByCrawl")}}",
                                    method: 'POST',
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        chapter: chapter,
                                        id: res.id,
                                        serverName: 'VIP #1'
                                    },
                                    beforeSend: function() {
                                        $(".craw-chapter-status").removeClass("d-none");
                                    }
                                });

                                messages += "<span class='text-success'><i class='bi bi-check'></i>" + response1 + "</span><br>";
                                $(".crawl-success-chapter-count").text(parseInt($(".crawl-success-chapter-count").text()) + 1);

                            } catch (error) {
                                $(".crawl-failed-chapter-count").text(parseInt($(".crawl-failed-chapter-count").text()) + 1);
                                messages += "<span class='text-danger'><i class='bi bi-x'></i>" + "Thêm thất bại bộ " + response.data.item.name + " chương " + chapter.chapter_name + ".</span><br>";
                            } finally {
                                currentChap += 1;
                                $(".crawled-chapter-count").text(parseInt($(".crawled-chapter-count").text()) + 1);
                                $("#crawl-list").html(messages);
                                const listsDiv = document.getElementById('crawl-list');
                                listsDiv.scrollTop = listsDiv.scrollHeight;
                                await new Promise(resolve => setTimeout(resolve, 1000)); // Delay time 1s
                            }
                        }
                    }

                } catch (error) {
                    $(".crawl-failed-count").text(parseInt($(".crawl-failed-count").text()) + 1);
                    $(".crawled-count").text(parseInt($(".crawled-count").text()) + 1);
                    messages += "<span class='text-danger'><i class='bi bi-x'></i>" + "Thêm thất bại bộ " + comicSlug + ".</span><br>";
                } finally {
                    $("#crawl-list").html(messages);
                }
            };
        });

        $('.btn-finally').on('click', function() {
            sessionStorage.setItem('ListComics', "");
            window.location.href = "/admin/api";
        });
    });
</script>
@endpush
