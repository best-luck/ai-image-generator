@extends('backend.layouts.form')
@section('title', admin_lang('Image Deatils') . ' | #' . hashid($generatedImage->id))
@section('back', route('admin.images.index'))
@section('content')
    @if ($generatedImage->user)
        <div class="card custom-card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.users.edit', $generatedImage->user->id) }}">
                            <img class="border rounded-circle border-2" src="{{ asset($generatedImage->user->avatar) }}"
                                width="60" height="60">
                        </a>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <a href="{{ route('admin.users.edit', $generatedImage->user->id) }}" class="text-dark">
                            <h5 class="mb-1">
                                {{ $generatedImage->user->name }}
                            </h5>
                            <p class="mb-0 text-muted">{{ $generatedImage->user->email }}</p>
                        </a>
                    </div>
                    <div class="flex-grow-3 ms-3">
                        <a href="{{ route('admin.users.edit', $generatedImage->user->id) }}" class="btn btn-dark"
                            target="_blank"><i class="fa fa-eye me-2"></i>{{ admin_lang('View details') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="file d-flex h-100 justify-content-center align-items-center">
                        <img src="{{ route('images.secure', [hashid($generatedImage->id), $generatedImage->filename]) }}"
                            alt="{{ $generatedImage->prompt }}" class="responsive-image">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <ul class="custom-list-group list-group list-group-flush">
                    <li class="list-group-item">
                        @if ($generatedImage->visibility)
                            <a href="{{ route('images.show', hashid($generatedImage->id)) }}" target="_blank"
                                class="btn btn-primary btn-lg w-100 mb-3"><i
                                    class="fas fa-external-link-alt me-2"></i>{{ admin_lang('Preview') }}</a>
                        @endif
                        <a href="{{ route('admin.images.download', $generatedImage->id) }}"
                            class="btn btn-dark btn-lg w-100 mb-3"><i
                                class="fas fa-download me-2"></i>{{ admin_lang('Download') }}</a>
                        <form action="{{ route('admin.images.destroy', $generatedImage->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="vironeer-able-to-delete btn btn-danger btn-lg w-100"><i
                                    class="far fa-trash-alt me-2"></i>{{ admin_lang('Delete') }}</button>
                        </form>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ admin_lang('ID') }}</strong>
                        <span>#{{ hashid($generatedImage->id) }}</span>
                    </li>
                    <li class="list-group-item">
                        <div> <strong>{{ admin_lang('Prompt') }}</strong></div>
                        <span>{{ $generatedImage->prompt }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ admin_lang('Size') }}</strong>
                        <span>{{ $generatedImage->size }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ admin_lang('Storage') }}</strong>
                        <span>
                            @if ($generatedImage->storageProvider->alias == 'local')
                                <span><i
                                        class="fas fa-server me-2"></i>{{ $generatedImage->storageProvider->alias }}</span>
                            @else
                                <a class="text-dark capitalize"
                                    href="{{ route('admin.settings.storage.edit', $generatedImage->storageProvider->id) }}">
                                    <i class="fas fa-server me-2"></i>{{ $generatedImage->storageProvider->alias }}
                                </a>
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ admin_lang('Upload date') }}</strong>
                        <span>{{ dateFormat($generatedImage->created_at) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ admin_lang('Expiry date') }}</strong>
                        <span>{{ $generatedImage->expiry_at ? dateFormat($generatedImage->expiry_at) : admin_lang('Unlimited time') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ admin_lang('Downloads') }}</strong>
                        <span>{{ formatNumber($generatedImage->downloads) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ admin_lang('Views') }}</strong>
                        <span>{{ formatNumber($generatedImage->views) }}</span>
                    </li>
                </ul>
                <div class="card-body border-top">
                    <form id="vironeer-submited-form" action="{{ route('admin.images.update', $generatedImage->id) }}"
                        method="POST">
                        @csrf
                        <label class="form-label">{{ admin_lang('Visibility') }} : </label>
                        <input type="checkbox" name="visibility" data-toggle="toggle" data-on="{{ admin_lang('Public') }}"
                            data-off="{{ admin_lang('Private') }}" {{ $generatedImage->visibility ? 'checked' : '' }}>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
