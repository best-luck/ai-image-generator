@extends('backend.layouts.grid')
@section('title', $active . ' ' . admin_lang('FAQs'))
@section('link', route('admin.faqs.create'))
@section('language', true)
@section('content')
    <div class="card">
        <table id="datatable" class="table w-100">
            <thead>
                <tr>
                    <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Language') }}</th>
                    <th class="tb-w-20x">{{ admin_lang('Title') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Published date') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($faqs as $faq)
                    <tr class="item">
                        <td>{{ $faq->id }}</td>
                        <td><a href="{{ route('admin.settings.languages.translates', $faq->lang) }}"><i
                                    class="fas fa-language me-2"></i>{{ $faq->language->name }}</a>
                        </td>
                        <td>
                            <a href="{{ route('admin.faqs.edit', $faq->id) }}"
                                class="text-dark">{{ shortertext($faq->title, 40) }}</a>
                        </td>
                        <td>{{ dateFormat($faq->created_at) }}</td>
                        <td>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.faqs.edit', $faq->id) }}"><i
                                                class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST">
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
