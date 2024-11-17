@if ($paginator->hasPages())
    <div class="page_redirect">
        @if(!$paginator->onFirstPage())
            <a href="{{ $paginator->url(1) }}" >
                <p><span aria-hidden="true">«</span></p>
            </a>
        @endif
        @if (!$paginator->onFirstPage())
            <a href="{{ $paginator->previousPageUrl() }}" >
                <p><span aria-hidden="true">‹</span></p>
            </a>
        @endif
        @foreach (range(1, $paginator->lastPage()) as $page)
            @if ($page >= $paginator->currentPage() - 2 && $page <= $paginator->currentPage() + 2)
                <a href="{{ $paginator->url($page) }}" >
                    <p class="{{$page == $paginator->currentPage() ? 'active' : ''}}">{{ $page }}</p>
                </a>
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" >
                <p><span aria-hidden="true">›</span></p>
            </a>
        @endif
        @if (!$paginator->onLastPage())
            <a href="{{ $paginator->url($paginator->lastPage()) }}">
                <p><span aria-hidden="true">»</span></p>
            </a>
        @endif
    </div>
@endif
