@extends('backend.layouts.grid')
@section('section', admin_lang('Blog'))
@section('title', $active . ' ' . admin_lang('Blog Articles'))
@section('link', route('articles.create'))
@section('language', true)
@section('content')
    <div class="card">
        <table id="datatable" class="table w-100">
            <thead>
                <tr>
                    <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Language') }}</th>
                    <th class="tb-w-20x">{{ admin_lang('Article') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Author') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Category') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Comments') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Views') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Published date') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($articles as $article)
                    <tr class="item">
                        <td>{{ $article->id }}</td>
                        <td><a href="{{ route('admin.settings.languages.translates', $article->lang) }}"><i
                                    class="fas fa-language me-2"></i>{{ $article->language->name }}</a>
                        </td>
                        <td>
                            <div class="vironeer-content-box">
                                <a class="vironeer-content-image" href="{{ route('articles.edit', $article->id) }}">
                                    <img src="{{ asset($article->image) }}">
                                </a>
                                <div>
                                    <a class="text-reset"
                                        href="{{ route('articles.edit', $article->id) }}">{{ shortertext($article->title, 30) }}</a>
                                    <p class="text-muted mb-0">{{ shortertext($article->short_description, 40) }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if (adminAuthInfo()->id == $article->admin->id)
                                {{ $article->admin->firstname . ' ' . $article->admin->lastname }}
                            @else
                                <a
                                    href="{{ route('admins.edit', $article->admin->id) }}">{{ $article->admin->firstname . ' ' . $article->admin->lastname }}</a>
                            @endif
                        </td>
                        <td><span class="badge bg-girl">{{ $article->blogCategory->name }}</span></td>
                        <td><span class="badge bg-dark">{{ $article->comments_count }}</span></td>
                        <td><span class="badge bg-dark">{{ $article->views }}</span></td>
                        <td>{{ dateFormat($article->created_at) }}</td>
                        <td>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('blog.article', $article->slug) }}"
                                            target="_blank"><i class="fa fa-eye me-2"></i>{{ admin_lang('View') }}</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('comments.index') . '?article_id=' . $article->id }}"><i
                                                class="fa fa-comments me-2"></i>{{ admin_lang('Comments') }}</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('articles.edit', $article->id) }}"><i
                                                class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        <form action="{{ route('articles.destroy', $article->id) }}" method="POST">
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
