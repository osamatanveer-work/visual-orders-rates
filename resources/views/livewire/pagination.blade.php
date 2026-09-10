<style>
    .my-active {
        background: grey !important;
    }
</style>

@if ($paginator->hasPages())
    <ul class="pagination pagination-rounded justify-content-center mt-4">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item me-1 shadow rounded-3"><a href="javascript:;" wire:click="previousPage" class="page-link rounded-3"><i class="bi bi-arrow-left-short fw-bold"></i></a></li>
        @else
            <li class="page-item me-1 shadow rounded-3"><a href="javascript:;" wire:click="previousPage" rel="prev" class="page-link rounded-3"><i class="bi bi-arrow-left-short fw-bold"></i></a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item"><a class="page-link mx-1"><span>{{ $element }}</span></a></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><a href="javascript:;" wire:click="gotoPage({{ $page }})" class="page-link shadow mx-1 rounded-3 border px-3">{{ $page }}</a></li>
                    @else
                        <li class="page-item"><a href="javascript:;" wire:click="gotoPage({{ $page }})" class="page-link mx-1">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item ms-1 shadow rounded-3"><a href="javascript:;" wire:click="nextPage" class="page-link rounded-3"><i class="bi bi-arrow-right-short"></i></a></li>
        @else
            <li class="page-item ms-1 shadow rounded-3"><a href="javascript:;" class="page-link rounded-3"><i class="bi bi-arrow-right-short"></i></a></li>
        @endif
    </ul>
@endif

<style>
    .pagination {
        --bs-pagination-active-bg: #ececed !important;
        --bs-pagination-active-border-color: transparent !important;
    }
</style>
