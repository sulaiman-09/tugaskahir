@if ($paginator->hasPages())
    <nav class="flex justify-end mt-3">
        <ul class="inline-flex items-center gap-1 list-none">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="px-3 py-1 border rounded-md text-sm opacity-50 cursor-not-allowed" aria-hidden="true">‹</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-1 border rounded-md text-sm text-blue-600 hover:bg-gray-100" aria-label="@lang('pagination.previous')">‹</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li aria-disabled="true">
                        <span class="px-3 py-1 border rounded-md text-sm">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page">
                                <span class="px-3 py-1 border rounded-md text-sm bg-blue-600 text-white border-blue-600">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="px-3 py-1 border rounded-md text-sm text-blue-600 hover:bg-gray-100">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-1 border rounded-md text-sm text-blue-600 hover:bg-gray-100" aria-label="@lang('pagination.next')">›</a>
                </li>
            @else
                <li aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="px-3 py-1 border rounded-md text-sm opacity-50 cursor-not-allowed" aria-hidden="true">›</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
