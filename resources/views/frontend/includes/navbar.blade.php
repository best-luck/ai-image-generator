<div class="nav-bar">
    <div class="container">
        <div class="nav-bar-container">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset($settings->media->dark_logo) }}" alt="{{ $settings->general->site_name }}" />
            </a>
            <div class="nav-bar-menu">
                <div class="overlay"></div>
                <div class="nav-bar-links">
                    <div class="nav-bar-menu-header">
                        <a class="nav-bar-menu-close ms-auto">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                    @foreach ($navbarMenuLinks as $navbarMenuLink)
                        @if ($navbarMenuLink->children->count() > 0)
                            <div class="drop-down" data-dropdown data-dropdown-position="top">
                                <div class="drop-down-btn">
                                    <span>{{ $navbarMenuLink->name }}</span>
                                    <i class="fa fa-angle-down ms-2"></i>
                                </div>
                                <div class="drop-down-menu">
                                    @foreach ($navbarMenuLink->children as $child)
                                        <a href="{{ $child->link }}" class="drop-down-item">
                                            <span>{{ $child->name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $navbarMenuLink->link }}" class="link">
                                <div class="link-title">
                                    <span>{{ $navbarMenuLink->name }}</span>
                                </div>
                            </a>
                        @endif
                    @endforeach
                    @include('frontend.global.language-menu')
                    @guest
                        <a href="{{ route('login') }}" class="link-btn">
                            <button class="btn btn-outline-primary">{{ lang('Sign In', 'auth') }}</button>
                        </a>
                        @if ($settings->actions->registration_status)
                            <a href="{{ route('register') }}" class="link-btn">
                                <button class="btn btn-primary">{{ lang('Sign Up', 'auth') }}</button>
                            </a>
                        @endif
                    @endguest
                </div>
            </div>
            <div class="nav-bar-actions">
                @auth
                    @include('frontend.global.user-menu')
                @endauth
                <div class="nav-bar-menu-btn">
                    <i class="fa-solid fa-bars-staggered fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>
