<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <?php $segments = ''; ?>
        @foreach (request()->segments() as $segment)
            <?php $segments .= '/' . $segment; ?>
            @if ($segment != getLang())
                <li class="breadcrumb-item capitalize @if (request()->segment(count(request()->segments())) == $segment) active @endif">
                    @if (request()->segment(count(request()->segments())) != $segment)
                        <a href="{{ url($segments) }}">{{ $segment }}</a>
                    @else
                        {{ $segment }}
                    @endif
                </li>
            @endif
        @endforeach
    </ol>
</nav>
