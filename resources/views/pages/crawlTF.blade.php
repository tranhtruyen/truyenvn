@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Crawl TruyenFull'])
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
                            <textarea id="link" class="form-control" rows="5" name="link">https://truyenfull.vn/danh-sach/truyen-moi/</textarea>
                            <small><i>Mỗi link cách nhau 1 dòng</i></small>
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
            $.ajax({
                url: "{{route("admin.getSingleTruyenFull")}}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    link: value
                },
                success: function(response) {
                    if(response.length > 0){
                        response.forEach((item, index) => {
                            result += '<div class="form-check">' +
                            '<input type="checkbox" class="form-check-input comic-checkbox" checked id="comic-' + index + '" value="' + item.link + '">' +
                            '<label class="custom-control-label">'+ item.name +'</label>' +
                            '</div>';
                            comicSlugs.push(item.link);
                            count++;
                        });
                        $('.selected-movie-count').text(count);
                        $('.total-movie-count').text(count);
                        $('#movie-list').html(result);
                        $('.step-1').addClass("d-none");
                        $('.step-2').removeClass("d-none");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $(".alert-danger").removeClass("d-none");
                    $(".message-error").text("Đường link không đúng định dạng(Ví dụ: https://truyenfull.vn/tuyet-pham-thien-y)");
                },
                complete: function() {
                    btn.prop('disabled', false).text('Tải');
                    sessionStorage.setItem('ListComics', comicSlugs);
                }
            });
        }

        function crawlHomePage(value, btn) {
            let comicSlugs = [];
            const page = $('#page').val();
            const toPage = $('#toPage').val();
            let result = '';
            let count = 0;
            $.ajax({
                url: "{{route("admin.getListTruyenFull")}}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    page: page,
                    toPage: toPage
                },
                success: function(response) {
                    if(response.length > 0){
                        response.forEach((item, index) => {
                            result += '<div class="form-check">' +
                            '<input type="checkbox" class="form-check-input comic-checkbox" checked id="comic-' + index + '" value="' + item.link + '">' +
                            '<label class="custom-control-label">'+ item.name +'</label>' +
                            '</div>';
                            comicSlugs.push(item.link);
                            count++;
                        });
                        $('.selected-movie-count').text(count);
                        $('.total-movie-count').text(count);
                        $('#movie-list').html(result);
                        $('.step-1').addClass("d-none");
                        $('.step-2').removeClass("d-none");
                    }
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

        let urlHome = "https://truyenfull.vn/danh-sach/truyen-moi";
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
            let messages = "";
            let totalChapters = 0;
            let currentChap = 0;
            $(".btn-finally").text('Đang get dữ liệu...');
            for (const comicSlug of comicSlugs) {
                try {
                    const response = await $.ajax({
                        url: "{{route('admin.crawlTruyenFull')}}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            link: comicSlug
                        }
                    });

                    totalChapters += response.chapters.length;
                    $(".total-crawl-chapter-count").text(totalChapters);
                    messages += `<span class='text-success'><i class='bi bi-check'></i>${response.message}</span><br>`;
                    $(".crawl-success-count").text(parseInt($(".crawl-success-count").text()) + 1);
                    $(".crawled-count").text(parseInt($(".crawled-count").text()) + 1);

                    let chapters = response.chapters;
                    if (response.currentChapter != 0) {
                        chapters = chapters.reverse();
                    }

                    for (const chapter of chapters) {
                        if (parseFloat(chapter.name) == parseFloat(response.currentChapter)) {
                            currentChap += chapters.length - parseFloat(chapter.name) + parseFloat(response.currentChapter);
                            break;
                        }

                        try {
                            const response1 = await $.ajax({
                                url: "{{route('admin.crawlTruyenFullChapter')}}",
                                method: 'POST',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    link: chapter.url,
                                    number: chapter.name,
                                    id: response.id
                                },
                                beforeSend: function() {
                                    $(".craw-chapter-status").removeClass("d-none");
                                }
                            });

                            if (response1.status == "success") {
                                $(".crawl-success-chapter-count").text(parseInt($(".crawl-success-chapter-count").text()) + 1);
                                messages += `<span class='text-success'><i class='bi bi-check'></i>${response1.message}.</span><br>`;
                            } else {
                                $(".crawl-failed-chapter-count").text(parseInt($(".crawl-failed-chapter-count").text()) + 1);
                                messages += `<span class='text-danger'><i class='bi bi-x'></i>${response1.message}.</span><br>`;
                            }
                        } catch (error) {
                            $(".crawl-failed-chapter-count").text(parseInt($(".crawl-failed-chapter-count").text()) + 1);
                            messages += `<span class='text-danger'><i class='bi bi-x'></i>Thêm thất bại đường dẫn ${chapter.url}.</span><br>`;
                        } finally {
                            currentChap += 1;
                            $(".crawled-chapter-count").text(parseInt($(".crawled-chapter-count").text()) + 1);
                            $("#crawl-list").html(messages);
                            const listsDiv = document.getElementById('crawl-list');
                            listsDiv.scrollTop = listsDiv.scrollHeight;
                            // await new Promise(resolve => setTimeout(resolve, 1000));
                            if (parseInt(currentChap) == totalChapters) {
                                $(".btn-finally").prop('disabled', false).text('Xong');
                            }
                        }
                    }

                    if (parseInt(currentChap) == totalChapters) {
                        $(".btn-finally").prop('disabled', false).text('Xong');
                    }

                } catch (error) {
                    $(".crawl-failed-count").text(parseInt($(".crawl-failed-count").text()) + 1);
                    $(".crawled-count").text(parseInt($(".crawled-count").text()) + 1);
                    messages += `<span class='text-danger'><i class='bi bi-x'></i>Thêm thất bại bộ ${comicSlug}.</span><br>`;
                } finally {
                    $("#crawl-list").html(messages);
                }
            }
        });

        $('.btn-finally').on('click', function() {
            sessionStorage.setItem('ListComics', "");
            window.location.reload();
        });
    });
</script>
@endpush
