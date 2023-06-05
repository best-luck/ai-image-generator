@extends('backend.layouts.grid')
@section('title', $active . ' ' . admin_lang('Features'))
@section('link', route('admin.features.create'))
@section('language', true)
@section('content')
    <div class="card">
        <table id="datatable" class="table w-100">
            <thead>
                <tr>
                    <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Language') }}</th>
                    <th class="tb-w-20x">{{ admin_lang('Details') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Published date') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($features as $feature)
                    <tr class="item">
                        <td>{{ $feature->id }}</td>
                        <td><a href="{{ route('admin.settings.languages.translates', $feature->lang) }}"><i
                                    class="fas fa-language me-2"></i>{{ $feature->language->name }}</a>
                        </td>
                        <td>
                            <div class="vironeer-content-box">
                                <a class="vironeer-content-image" href="{{ route('admin.features.edit', $feature->id) }}">
                                    <img src="{{ asset($feature->image) }}">
                                </a>
                                <div>
                                    <a class="text-reset"
                                        href="{{ route('admin.features.edit', $feature->id) }}">{{ shortertext($feature->title, 40) }}</a>
                                    <p class="text-muted mb-0">{{ shortertext($feature->content, 50) }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ dateFormat($feature->created_at) }}</td>
                        <td>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.features.edit', $feature->id) }}"><i
                                                class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.features.destroy', $feature->id) }}" method="POST">
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
