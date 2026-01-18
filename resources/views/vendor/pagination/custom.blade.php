@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <button disabled>&laquo;</button>
        @else
            <button onclick="window.location.href='{{ $paginator->previousPageUrl() }}'">&laquo;</button>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <button disabled>{{ $element }}</button>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button class="active">{{ $page }}</button>
                    @else
                        <button onclick="window.location.href='{{ $url }}'">{{ $page }}</button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button onclick="window.location.href='{{ $paginator->nextPageUrl() }}'">&raquo;</button>
        @else
            <button disabled>&raquo;</button>
        @endif

        <span class="pagination-info">
            Menampilkan {{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} data
        </span>
    </div>
@endif
