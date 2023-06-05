@extends('backend.layouts.grid')
@section('title', admin_lang('Generated Images'))
@section('content')
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-xl-2 row-cols-xxl-2 g-3 mb-3">
        <div class="col">
            <div class="counter-card-v c-purple">
                <div class="counter-card-icon">
                    <i class="fa fa-users"></i>
                </div>
                <div class="counter-card-info">
                    <p class="counter-card-title">{{ admin_lang('Users Images') }}</p>
                    <p class="counter-card-number">{{ number_format($widget['users_images']) }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="counter-card-v c-orange">
                <div class="counter-card-icon">
                    <i class="fas fa-user-slash"></i>
                </div>
                <div class="counter-card-info">
                    <p class="counter-card-title">{{ admin_lang('Guests Images') }}</p>
                    <p class="counter-card-number">{{ number_format($widget['guests_images']) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card custom-card">
        <div class="card-header p-3 border-bottom-small">
            <form class="multiple-select-search-form" action="{{ request()->url() }}" method="GET">
                <div class="input-group vironeer-custom-input-group">
                    <input type="text" name="search" class="form-control" placeholder="{{ admin_lang('Search...') }}"
                        value="{{ request()->input('search') ?? '' }}" required>
                    <button class="btn btn-secondary" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                    @if (request()->input('search'))
                        <a href="{{ request()->url() }}" class="btn btn-secondary">{{ admin_lang('View All') }}</a>
                    @endif
                </div>
            </form>
            <form class="multiple-select-delete-form d-none" action="{{ route('admin.images.nultiDelete') }}"
                method="POST">
                @csrf
                <input type="hidden" name="delete_ids" class="multiple-select-delete-ids" value="">
                <button class="vironeer-able-to-delete btn btn-danger"><i
                        class="far fa-trash-alt me-2"></i>{{ admin_lang('Delete Selected') }}</button>
            </form>
        </div>
        @if ($generatedImages->count() > 0)
            <div class="table-responsive">
                <table class="vironeer-normal-table table w-100">
                    <thead>
                        <tr>
                            <th class="tb-w-3x">
                                <input class="multiple-select-check-all form-check-input" type="checkbox">
                            </th>
                            <th class="tb-w-20x">{{ admin_lang('Image details') }}</th>
                            <th class="tb-w-7x text-center">{{ admin_lang('Uploaded by') }}</th>
                            <th class="tb-w-3x text-center">{{ admin_lang('Downloads') }}</th>
                            <th class="tb-w-3x text-center">{{ admin_lang('Views') }}</th>
                            <th class="tb-w-3x text-center">{{ admin_lang('Storage') }}</th>
                            <th class="tb-w-3x text-center">{{ admin_lang('Expiration') }}</th>
                            <th class="tb-w-3x text-center">{{ admin_lang('Upload date') }}</th>
                            <th class="tb-w-3x text-center">{{ admin_lang('Visibility') }}</th>
                            <th class="text-end"><i class="fas fa-sliders-h me-1"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($generatedImages as $generatedImage)
                            <tr>
                                <td>
                                    <input class="form-check-input multiple-select-checkbox"
                                        data-id="{{ $generatedImage->id }}" type="checkbox">
                                </td>
                                <td>
                                    <div class="vironeer-content-box">
                                        <a class="vironeer-content-image text-center"
                                            href="{{ route('admin.images.edit', $generatedImage->id) }}">
                                            <img src="{{ route('images.secure', [hashid($generatedImage->id), $generatedImage->filename]) }}"
                                                alt="{{ $generatedImage->prompt }}">
                                        </a>
                                        <div>
                                            <a class="text-reset"
                                                href="{{ route('admin.images.edit', $generatedImage->id) }}">{{ shortertext($generatedImage->prompt, 50) }}</a>
                                            <p class="text-muted mb-0">
                                                {{ $generatedImage->size }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if ($generatedImage->user)
                                        <a href="{{ route('admin.users.edit', $generatedImage->user->id) }}"
                                            class="text-dark">
                                            <i class="fa fa-user me-1"></i>
                                            {{ $generatedImage->user->name }}
                                        </a>
                                    @else
                                        <i class="text-muted">{{ admin_lang('Guest') }}</i>
                                    @endif
                                </td>
                                <td class="text-center">{{ formatNumber($generatedImage->downloads) }}</td>
                                <td class="text-center">{{ formatNumber($generatedImage->views) }}</td>
                                <td class="text-center">
                                    @if ($generatedImage->storageProvider->alias == 'local')
                                        <span><i
                                                class="fas fa-server me-2"></i>{{ $generatedImage->storageProvider->alias }}</span>
                                    @else
                                        <a class="text-dark capitalize"
                                            href="{{ route('admin.settings.storage.edit', $generatedImage->storageProvider->id) }}">
                                            <i class="fas fa-server me-2"></i>{{ $generatedImage->storageProvider->alias }}
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $generatedImage->expiry_at ? dateFormat($generatedImage->expiry_at) : admin_lang('Unlimited time') }}
                                </td>
                                <td class="text-center">{{ dateFormat($generatedImage->created_at) }}</td>
                                <td class="text-center">
                                    @if ($generatedImage->visibility)
                                        <span class="badge bg-success">{{ admin_lang('Public') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ admin_lang('Private') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                            aria-expanded="true">
                                            <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-md-end dropdown-menu-lg"
                                            data-popper-placement="bottom-end">
                                            @if ($generatedImage->visibility)
                                                <li>
                                                    <a class="dropdown-item" target="_blank"
                                                        href="{{ route('images.show', hashid($generatedImage->id)) }}"><i
                                                            class="fas fa-external-link-alt me-2"></i>{{ admin_lang('Preview') }}</a>
                                                </li>
                                            @endif
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.images.download', $generatedImage->id) }}"><i
                                                        class="fas fa-download me-2"></i>{{ admin_lang('Download') }}</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.images.edit', $generatedImage->id) }}"><i
                                                        class="fas fa-edit me-2"></i>{{ admin_lang('Image Details') }}</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.images.destroy', $generatedImage->id) }}"
                                                    method="POST">
                                                    @csrf @method('DELETE')
                                                    <button class="vironeer-able-to-delete dropdown-item text-danger"><i
                                                            class="far fa-trash-alt me-2"></i>{{ admin_lang('Delete') }}</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            @include('backend.includes.empty')
        @endif
    </div>
    {{ $generatedImages->links() }}
@endsection
