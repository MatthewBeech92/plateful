@if ($paginator->hasPages())
    <nav class="pagination-container">
        <ul>
            {{-- First Page Link --}}
            @if ($paginator->currentPage() > 1)
                <li>
                    <a href="{{ $paginator->url(1) }}" class="chevron pagination-first" rel="first" aria-label="First Page"></a>
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="First Page">
                    <span class="chevron pagination-first-disabled" aria-hidden="true"></span>
                </li>
            @endif

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true" aria-label="Previous Page">
                    <span class="chevron pagination-prev-disabled" aria-hidden="true"></span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="chevron pagination-prev" rel="prev" aria-label="Previous Page"></a>
                </li>
            @endif

            

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
               
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif
        

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @if ($paginator->lastPage() > 3) 
                        @if ($paginator->onFirstPage())	
                            @foreach ($paginator->getUrlRange(1, 3)  as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @elseif ($paginator->currentPage() === $paginator->lastPage())
                            @foreach ($paginator->getUrlRange($paginator->lastPage()-2, $paginator->lastPage())  as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @else
                            @foreach ($paginator->getUrlRange($paginator->currentPage()-1, $paginator->currentPage()+1) as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @else 
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                            @else
                                <li><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="chevron pagination-next" rel="next" aria-label="Next Page"></a>
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="Next Page">
                    <span class="chevron pagination-next-disabled" aria-hidden="true"></span>
                </li>
            @endif

            {{-- Last Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->url($paginator->lastPage())	}}" class="chevron pagination-last" rel="next" aria-label="Last Page"></a>
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="Last Page">
                    <span class="chevron pagination-last-disabled" aria-hidden="true"></span>
                </li>
            @endif

        </ul>
    </nav>
@endif
