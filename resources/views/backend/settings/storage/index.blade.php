@extends('backend.layouts.grid')
@section('title', admin_lang('Storage Providers'))
@section('section', admin_lang('Settings'))
@section('container', 'container-max-lg')
@section('content')
    <div class="card">
        <table id="datatable" class="table w-100">
            <thead>
                <tr>
                    <th class="tb-w-1x">{{ admin_lang('#') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Logo') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('name') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Status') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Last Update') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($storageProviders as $storageProvider)
                    <tr class="item">
                        <td>{{ $storageProvider->id }}</td>
                        <td>
                            @if (!$storageProvider->isLocal())
                                <a href="{{ route('admin.settings.storage.edit', $storageProvider->id) }}">
                                    <img src="{{ asset($storageProvider->logo) }}" height="40" width="40">
                                </a>
                            @else
                                <img src="{{ asset($storageProvider->logo) }}" height="40" width="40">
                            @endif
                        </td>
                        <td>
                            @if (!$storageProvider->isLocal())
                                <a href="{{ route('admin.settings.storage.edit', $storageProvider->id) }}"
                                    class="text-dark">
                                    {{ $storageProvider->name }}
                                    {{ env('FILESYSTEM_DRIVER') == $storageProvider->alias ? admin_lang('(Default)') : '' }}
                                </a>
                            @else
                                <span>
                                    {{ $storageProvider->name }}
                                    {{ env('FILESYSTEM_DRIVER') == $storageProvider->alias ? admin_lang('(Default)') : '' }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if ($storageProvider->status)
                                <span class="badge bg-success">{{ admin_lang('Enabled') }}</span>
                            @else
                                <span class="badge bg-danger">{{ admin_lang('Disabled') }}</span>
                            @endif
                        </td>
                        <td>{{ dateFormat($storageProvider->updated_at) }}</td>
                        <td>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                    @if (!$storageProvider->isLocal())
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('admin.settings.storage.edit', $storageProvider->id) }}"><i
                                                    class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                    @endif
                                    <li>
                                        <form action="{{ route('admin.settings.storage.default', $storageProvider->id) }}"
                                            method="POST">
                                            @csrf
                                            <button class="vironeer-form-confirm dropdown-item"><i
                                                    class="fas fa-thumbtack me-2"></i>{{ admin_lang('Set As Default') }}</button>
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
@endsection
