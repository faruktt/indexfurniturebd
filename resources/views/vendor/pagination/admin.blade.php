@if ($paginator->hasPages())
    <div class="admin-pagination-container">
        <div class="pagination-info">
            Showing <span class="font-semibold">{{ $paginator->firstItem() }}</span> to <span class="font-semibold">{{ $paginator->lastItem() }}</span> of <span class="font-semibold">{{ $paginator->total() }}</span> results
        </div>

        <nav role="navigation" aria-label="Pagination Navigation" class="pagination-nav">
            <ul class="admin-pagination-list">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <span class="page-link" aria-hidden="true">
                            <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                            <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                            <i class="fas fa-chevron-right" style="font-size: 11px;"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span class="page-link" aria-hidden="true">
                            <i class="fas fa-chevron-right" style="font-size: 11px;"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@elseif($paginator->total() > 0)
    <div class="admin-pagination-container">
        <div class="pagination-info">
            Showing <span class="font-semibold">1</span> to <span class="font-semibold">{{ $paginator->total() }}</span> of <span class="font-semibold">{{ $paginator->total() }}</span> results
        </div>
    </div>
@endif
