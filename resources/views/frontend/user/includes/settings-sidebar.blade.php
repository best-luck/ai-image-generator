<div class="col-lg-4 col-xxl-3">
    <div class="card-v p-4">
        <div class="settings-side">
            <div class="settings-user">
                <div class="settings-user-img mb-3">
                    <img id="avatar_preview" src="{{ asset(userAuthInfo()->avatar) }}" alt="{{ userAuthInfo()->name }}" />
                    @if (request()->routeIs('user.settings.index'))
                        <div class="settings-user-img-change">
                            <label for="change_avatar">
                                <i class="fa fa-camera"></i>
                            </label>
                        </div>
                    @endif
                </div>
                <div class="settings-user-title">
                    <p class="mb-0 h5 text-center">{{ userAuthInfo()->name }}</p>
                </div>
            </div>
            <div class="settings-links">
                <a href="{{ route('user.settings.index') }}"
                    class="settings-link {{ request()->routeIs('user.settings.index') ? 'active' : '' }}">
                    <i class="fa fa-edit"></i>{{ lang('Account details', 'account') }}
                </a>
                <a href="{{ route('user.settings.subscription') }}"
                    class="settings-link {{ request()->routeIs('user.settings.subscription') ? 'active' : '' }}">
                    <i class="fa-regular fa-gem"></i>{{ lang('My Subscription', 'account') }}
                </a>
                <a href="{{ route('user.settings.payment-history') }}"
                    class="settings-link {{ request()->routeIs('user.settings.payment-history') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt"></i>{{ lang('Payment History', 'account') }}
                </a>
                <a href="{{ route('user.settings.password') }}"
                    class="settings-link {{ request()->routeIs('user.settings.password') ? 'active' : '' }}">
                    <i class="fas fa-sync-alt"></i>{{ lang('Change Password', 'account') }}
                </a>
                <a href="{{ route('user.settings.2fa') }}"
                    class="settings-link {{ request()->routeIs('user.settings.2fa') ? 'active' : '' }}">
                    <i class="fas fa-fingerprint"></i>{{ lang('2FA Authentication', 'account') }}
                </a>
            </div>
        </div>
    </div>
</div>
