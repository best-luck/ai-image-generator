@extends('backend.layouts.application')
@section('title', admin_lang('Editor Files'))
@section('content')
    <div class="alert alert-warning">
        <i class="far fa-question-circle me-2"></i>
        <span>
            {{ admin_lang('Here you can manage the files that have been uploaded using the editor all over the website.') }}</span>
    </div>
    <div class="card">
        <table id="datatable" class="table w-100">
            <thead>
                <tr>
                    <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                    <th class="tb-w-20x">{{ admin_lang('File') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Uploaded at') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($files as $file)
                    <tr class="item">
                        <td>{{ $file->id }}</td>
                        <td>
                            <div class="vironeer-content-box">
                                <div class="vironeer-content-image">
                                    <img src="{{ asset($file->path) }}">
                                </div>
                                <div>
                                    <span class="text-reset">{{ $file->path }}</span>
                                    <p class="mb-0">
                                        <a class="text-muted" href="{{ asset($file->path) }}"
                                            target="_blank">{{ asset($file->path) }}</a>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>{{ dateFormat($file->created_at) }}</td>
                        <td>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                    <li>
                                        <form action="{{ route('admin.system.editor-files.destroy', $file->id) }}"
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
@endsection
