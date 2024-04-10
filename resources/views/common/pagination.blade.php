@if ($paginator->total() >= 1)
    <div class="card-header pagination-border-box" id="pagination-box">
        <div class="float-left">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries
        </div>
        <ul class="pagination pagination-sm m-0 float-right ">
            <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url(1) }}">First</a>
            </li>

            <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->currentPage()-1) }}"><</a>
            </li>

            @php
                $firstPage = 1;
                $pageLimit = 9;
                $lastPage = $paginator->lastPage() > $pageLimit ? $pageLimit : $paginator->lastPage();
                $diff = $lastPage - $firstPage;
                $half = floor($pageLimit/2);
                $currentPage = $paginator->currentPage();

                if ($currentPage - $half > 0) {
                    if ($currentPage < $paginator->lastPage() && $currentPage + $half < $paginator->lastPage()) {
                        $lastPage = $currentPage + $half;
                    } else {
                        $lastPage = $paginator->lastPage();
                    }

                    $firstPage = $lastPage - $diff;
                }
            @endphp
            @for ($i = $firstPage; $i <= $lastPage; $i++)
                <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active not-click' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->currentPage()+1) }}">></a>
            </li>

            <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">Last</a>
            </li>
        </ul>
    </div>
@endif
