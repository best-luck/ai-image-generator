@extends('backend.layouts.grid')
@section('title', admin_lang('Plugins'))
@section('add_modal', admin_lang('Install New Plugin'))
@section('content')
    <div class="custom-card card">
        <table id="datatable" class="table w-100">
            <thead>
                <tr>
                    <th class="tb-w-1x">#</th>
                    <th class="tb-w-3x">{{ admin_lang('Logo') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Name') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Version') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Status') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Added at') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plugins as $plugin)
                    <tr class="item">
                        <td>{{ $plugin->id }}</td>
                        <td><a href="{{ route('admin.system.plugins.edit', $plugin->id) }}">
                                <img src="{{ $plugin->logo }}" alt="{{ $plugin->name }}" height="35">
                            </a>
                        </td>
                        <td>{{ $plugin->name }}</td>
                        <td><span class="badge bg-dark">{{ $plugin->version }}</span></td>
                        <td>
                            @if ($plugin->status)
                                <span class="badge bg-success">{{ admin_lang('Active') }}</span>
                            @else
                                <span class="badge bg-danger">{{ admin_lang('Disabled') }}</span>
                            @endif
                        </td>
                        <td>{{ dateFormat($plugin->created_at) }}</td>
                        <td>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.system.plugins.edit', $plugin->id) }}"><i
                                                class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                    </li>
                                    @if ($plugin->action_text)
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ url($plugin->action_link) }}"><i
                                                    class="fas fa-external-link-alt me-2"></i>{{ $plugin->action_text }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModallLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{ admin_lang('Install New Plugin') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addNewForm" action="{{ route('admin.system.plugins.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Plugin Purchase Code') }} : <span
                                    class="red">*</span></label>
                            <input type="text" name="purchase_code" class="form-control"
                                placeholder="{{ admin_lang('Purchase code') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Plugin Files (Zip)') }} : <span
                                    class="red">*</span></label>
                            <input type="file" name="plugin_files" class="form-control" accept=".zip" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button form="addNewForm" class="btn btn-primary">{{ admin_lang('Install Now') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
