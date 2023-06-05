@extends('backend.layouts.grid')
@section('title', admin_lang('Admins'))
@section('section', admin_lang('Settings'))
@section('container', 'container-max-lg')
@section('link', route('admin.settings.admins.create'))
@section('content')
    <div class="card">
        <div>
            <table id="datatable" class="table w-100">
                <thead>
                    <tr>
                        <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                        <th class="tb-w-20x">{{ admin_lang('Details') }}</th>
                        <th class="tb-w-3x">{{ admin_lang('Added date') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admins as $admin)
                        <tr>
                            <td>{{ $admin->id }}</td>
                            <td>
                                <div class="vironeer-user-box">
                                    <a class="vironeer-user-avatar"
                                        href="{{ route('admin.settings.admins.edit', $admin->id) }}">
                                        <img class="rounded-circle" src="{{ asset($admin->avatar) }}" />
                                    </a>
                                    <div>
                                        <a class="text-reset"
                                            href="{{ route('admin.settings.admins.edit', $admin->id) }}">{{ $admin->firstname . ' ' . $admin->lastname }}</a>
                                        <p class="text-muted mb-0">{{ $admin->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ dateFormat($admin->created_at) }}</td>
                            <td>
                                <div class="text-end">
                                    <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                        aria-expanded="true">
                                        <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('admin.settings.admins.edit', $admin->id) }}"><i
                                                    class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.settings.admins.destroy', $admin->id) }}"
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
    </div>
@endsection
