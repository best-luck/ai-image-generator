@extends('backend.layouts.grid')
@section('title', admin_lang('Advertisements'))
@section('content')
    <div class="card ratings custom-card">
        <table class="table ask-datatable w-100">
            <thead>
                <tr>
                    <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Position') }}</th>
                    <th class="tb-w-5x">{{ admin_lang('Size') }}</th>
                    <th class="tb-w-5x">{{ admin_lang('Status') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Last update') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($advertisements as $advertisement)
                    @if ($advertisement->alias != 'head_code')
                        <tr>
                            <td>{{ $advertisement->id }}</td>
                            <td>
                                <a href="{{ route('admin.advertisements.edit', $advertisement->id) }}" class="text-dark">
                                    <i class="fas fa-ad me-2"></i>{{ $advertisement->position }}
                                </a>
                            </td>
                            <td>{{ $advertisement->size }}</td>
                            <td>
                                @if ($advertisement->status)
                                    <span class="badge bg-success">{{ admin_lang('Enabled') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ admin_lang('Disabled') }}</span>
                                @endif
                            </td>
                            <td>{{ dateFormat($advertisement->updated_at) }}</td>
                            <td>
                                <div class="text-end">
                                    <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                        aria-expanded="true">
                                        <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('admin.advertisements.edit', $advertisement->id) }}"><i
                                                    class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
