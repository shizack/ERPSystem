@if (isset($breadcrumbs) && count($breadcrumbs) > 0)
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($loop->last)
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                    {{ $breadcrumb['name'] }}
                </li>
            @else
                <li class="breadcrumb-item text-sm">
                    <a class="opacity-5 text-dark" href="{{ $breadcrumb['url'] ?? '#' }}">
                        {{ $breadcrumb['name'] }}
                    </a>
                </li>
            @endif
            @if (!$loop->last)
                <span class="text-gray-500 mx-2">/</span>
            @endif
        @endforeach
    </ol>
    @if (isset($title))
        <h6 class="font-weight-bolder mb-0">{{ $title }}</h6>
    @endif
</nav>
@endif
