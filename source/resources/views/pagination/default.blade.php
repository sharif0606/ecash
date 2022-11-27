@if ($paginator->lastPage() > 1)
{{-- @php
if(count(explode('?',request()->fullUrl())) > 1){
echo "
<pre>";
        print_r(request()->fullUrl()."&".explode('?',$paginator->url(0))[1]);
        print_r(explode('?',$paginator->url(0))[1]);
    echo "</pre>";
}

@endphp --}}
<nav>
    <ul class="pagination">
        <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
            <a class="page-link" href="
            @if (count(explode('?',request()->fullUrl())) > 1)
            {{ request()->fullUrl()."&".explode('?',$paginator->url(1))[1] }}
            @else
            {{ $paginator->url(1) }}
            @endif
            ">‹</a>
        </li>
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                <a class="page-link" href="
                @if (count(explode('?',request()->fullUrl())) > 1)
                {{ request()->fullUrl()."&".explode('?',$paginator->url($i))[1] }}
                @else
                {{ $paginator->url($i) }}
                @endif
                ">{{ $i }}</a>
            </li>
            @endfor
            <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
                <a class="page-link" href="
                @if (count(explode('?',request()->fullUrl())) > 1)
                {{ request()->fullUrl()."&".explode('?',$paginator->url($paginator->currentPage()+1))[1] }}
                @else
                {{ $paginator->url($paginator->currentPage()+1) }}
                @endif
                ">›</a>
            </li>
    </ul>
</nav>
@endif