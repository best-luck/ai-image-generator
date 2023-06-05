@extends('backend.layouts.grid')
@section('section', admin_lang('Blog'))
@section('title', $active . ' ' . admin_lang('Blog Categories'))
@section('container', 'container-max-lg')
@section('link', route('categories.create'))
@section('language', true)
@section('content')
    <div class="card">
        <table id="datatable" class="table w-100">
            <thead>
                <tr>
                    <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                    <th class="tb-w-2x">{{ admin_lang('Language') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Name') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Views') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Published date') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr class="item">
                        <td>{{ $category->id }}</td>
                        <td><a href="{{ route('admin.settings.languages.translates', $category->lang) }}"><i
                                    class="fas fa-language me-2"></i>{{ $category->language->name }}</a>
                        </td>
                        <td>{{ $category->name }}</td>
                        <td><span class="badge bg-dark">{{ $category->views }}</span></td>
                        <td>{{ dateFormat($category->created_at) }}</td>
                        <td>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('blog.category', $category->slug) }}"
                                            target="_blank"><i class="fa fa-eye me-2"></i>{{ admin_lang('View') }}</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('categories.edit', $category->id) }}"><i
                                                class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
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
