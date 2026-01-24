@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="custom-pagination">
        <div class="pagination-info">
            <span>Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results</span>
        </div>
        
        <div class="pagination-links">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn disabled">
                    <i class="bi bi-chevron-left"></i>
                    <span>Previous</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" rel="prev">
                    <i class="bi bi-chevron-left"></i>
                    <span>Previous</span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="pagination-numbers">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="pagination-dots">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-number active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" rel="next">
                    <span>Next</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span class="pagination-btn disabled">
                    <span>Next</span>
                    <i class="bi bi-chevron-right"></i>
                </span>
            @endif
        </div>
    </nav>

    <style>
        .custom-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            gap: 20px;
            flex-wrap: wrap;
        }

        .pagination-info {
            font-size: 14px;
            color: #6b7280;
        }

        .pagination-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pagination-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            color: #374151;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination-btn:hover:not(.disabled) {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #111827;
        }

        .pagination-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination-btn i {
            font-size: 12px;
        }

        .pagination-numbers {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .pagination-number {
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            color: #374151;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination-number:hover:not(.active) {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #111827;
        }

        .pagination-number.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
        }

        .pagination-dots {
            padding: 0 8px;
            color: #9ca3af;
            font-size: 14px;
        }

        @media (max-width: 640px) {
            .custom-pagination {
                flex-direction: column;
                gap: 16px;
            }

            .pagination-info {
                order: 2;
            }

            .pagination-links {
                order: 1;
                width: 100%;
                justify-content: center;
            }

            .pagination-btn span {
                display: none;
            }

            .pagination-numbers {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
@endif
