<div class="drop-down languages" data-dropdown data-dropdown-position="top">
    <div class="drop-down-btn">
        <div class="language-img">
            <img src="{{ getLangFlag() }}" alt="{{ getLangName() }}" />
        </div>
        <span>{{ getLangName() }}</span>
        <i class="fa fa-angle-down ms-2"></i>
    </div>
    <div class="drop-down-menu">
        @foreach ($languages as $language)
            <a href="{{ langURL($language->code) }}"
                class="drop-down-item {{ getLang() == $language->code ? 'active' : '' }}">
                <div class="language-img">
                    <img src="{{ asset($language->flag) }}" alt="{{ $language->name }}" />
                </div>
                <span>{{ $language->name }}</span>
            </a>
        @endforeach
    </div>
</div>
