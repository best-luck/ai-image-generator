@extends('backend.layouts.grid')
@section('section', admin_lang('Blog'))
@section('title', admin_lang('Blog Comments'))
@section('content')
    <div class="card">
        <table id="datatable" class="table w-100">
            <thead>
                <tr>
                    <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Posted by') }}</th>
                    <th class="tb-w-20x">{{ admin_lang('Article') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Status') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Posted date') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($comments as $comment)
                    <tr class="item">
                        <td>{{ $comment->id }}</td>
                        <td>
                            @if ($comment->user)
                                <a
                                    href="{{ route('admin.users.edit', $comment->user->id) }}">{{ $comment->user->firstname . ' ' . $comment->user->lastname }}</a>
                            @else
                                <span class="text-muted">{{ admin_lang('Anonymous') }}</span>
                            @endif
                        </td>
                        <td><a href="{{ route('articles.edit', $comment->blogArticle->id) }}"
                                class="text-dark">{{ shortertext($comment->blogArticle->title, 30) }}</a>
                        </td>
                        <td>
                            @if ($comment->status == 0)
                                <span class="badge bg-warning">{{ admin_lang('Pending') }}</span>
                            @else
                                <span class="badge bg-success">{{ admin_lang('Published') }}</span>
                            @endif
                        </td>
                        <td>{{ dateFormat($comment->created_at) }}</td>
                        <td>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                    <li>
                                        <a class="vironeer-view-comment dropdown-item" data-id="{{ $comment->id }}"
                                            href="#"><i class="fa fa-eye me-2"></i>{{ admin_lang('View') }}</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
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
    <div class="modal fade" id="viewComment" tabindex="-1" aria-labelledby="viewCommentLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCommentLabel">{{ admin_lang('View Comment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="comment" class="form-control" rows="10" readonly></textarea>
                </div>
                <div class="modal-footer">
                    <form id="deleteCommentForm" class="d-inline" action="#" method="POST">
                        @csrf @method('DELETE')
                        <button class="vironeer-form-confirm btn btn-danger"><i
                                class="far fa-trash-alt me-2"></i>{{ admin_lang('Delete') }}</button>
                    </form>
                    <form id="publishCommentForm" class="d-inline" action="#" method="POST">
                        @csrf
                        <button class="vironeer-form-confirm publish-comment-btn btn btn-success"><i
                                class="far fa-check-circle me-2"></i>{{ admin_lang('Publish') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
