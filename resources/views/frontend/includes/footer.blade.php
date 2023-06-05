<footer class="footer mt-auto">
    <div class="container">
        <div class="footer-inner">
            @if ($footerMenuLinks->count() > 0)
                <div class="footer-content">
                    <div class="row row-cols-2 row-cols-lg-4 g-4">
                        @foreach ($footerMenuLinks as $footerMenuLink)
                            @if ($footerMenuLink->children->count() > 0)
                                <div class="col">
                                    <h4 class="mb-4">{{ $footerMenuLink->name }}</h4>
                                    <div class="footer-links">
                                        @foreach ($footerMenuLink->children as $child)
                                            <a href="{{ $child->link }}" class="footer-link">{{ $child->name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="col">
                                    <a href="{{ $footerMenuLink->link }}" class="mb-4">{{ $footerMenuLink->name }}</a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="footer-copyright">
                <p class="mb-0">&copy; <span data-year></span>
                    {{ $settings->general->site_name }} - {{ lang('All rights reserved') }}.</p>
            </div>
        </div>
    </div>
</footer>
