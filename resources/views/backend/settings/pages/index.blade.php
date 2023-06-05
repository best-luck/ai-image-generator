@extends('backend.layouts.grid')
@section('title', $active . ' ' . admin_lang('Pages'))
@section('section', admin_lang('Settings'))
@section('link', route('admin.settings.pages.create'))
@section('container', 'container-max-lg')
@section('language', true)
@section('content')
    <div class="card">
        <table id="datatable" class="table w-100">
            <thead>
                <tr>
                    <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                    <th class="tb-w-2x">{{ admin_lang('Language') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Page Name') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Views') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Created date') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $page)
                    <tr class="item">
                        <td>{{ $page->id }}</td>
                        <td><a href="{{ route('admin.settings.languages.translates', $page->lang) }}"><i
                                    class="fas fa-language me-2"></i>{{ $page->language->name }}</a>
                        </td>
                        <td>
                            <a href="{{ route('admin.settings.pages.edit', $page->id) }}"
                                class="text-dark">{{ $page->title }}</a>
                        </td>
                        <td><span class="badge bg-dark">{{ $page->views }}</span></td>
                        <td>{{ dateFormat($page->created_at) }}</td>
                        <td>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('page', $page->slug) }}" target="_blank"><i
                                                class="fa fa-eye me-2"></i>{{ admin_lang('Preview') }}</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.settings.pages.edit', $page->id) }}"><i
                                                class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.settings.pages.destroy', $page->id) }}"
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
