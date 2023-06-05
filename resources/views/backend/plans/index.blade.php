@extends('backend.layouts.grid')
@section('title', admin_lang('Pricing plans'))
@section('link', route('admin.plans.create'))
@section('content')
    <div class="card custom-card custom-tabs mb-3">
        <div class="card-body">
            <ul class="nav nav-pills" role="tablist">
                <li role="presentation">
                    <button class="nav-link active me-2" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly"
                        type="button" role="tab" aria-controls="monthly"
                        aria-selected="true">{{ admin_lang('Monthly plans') }}
                        ({{ count($monthlyPlans) }})</button>
                </li>
                <li role="presentation">
                    <button class="nav-link me-2" id="yearly-tab" data-bs-toggle="tab" data-bs-target="#yearly"
                        type="button" role="tab" aria-controls="yearly"
                        aria-selected="false">{{ admin_lang('Yearly plans') }}
                        ({{ count($yearlyPlans) }})</button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card custom-card">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
                <table class="datatable50 table w-100">
                    <thead>
                        <tr>
                            <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Name') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Price') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Interval') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Total Images') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Created date') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monthlyPlans as $plan)
                            <tr class="item">
                                <td>{{ $plan->id }}</td>
                                <td>
                                    <a href="{{ route('admin.plans.edit', $plan->id) }}" class="text-dark">
                                        {{ $plan->name }}
                                        {{ $plan->isFeatured() ? '(' . admin_lang('Featured') . ')' : '' }}
                                    </a>
                                </td>
                                <td>
                                    <strong>
                                        @if ($plan->isFree())
                                            <span class="text-success">{{ admin_lang('Free') }}</span>
                                        @else
                                            <span class="text-dark">{{ priceSymbolCode($plan->price) }}</span>
                                        @endif
                                    </strong>
                                </td>
                                <td>{{ admin_lang('Monthly') }}</td>
                                <td>{{ number_format($plan->images) }}</td>
                                <td>{{ dateFormat($plan->created_at) }}</td>
                                <td>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                            aria-expanded="true">
                                            <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.plans.edit', $plan->id) }}"><i
                                                        class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.plans.destroy', $plan->id) }}"
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
            <div class="tab-pane fade" id="yearly" role="tabpanel" aria-labelledby="yearly-tab">
                <table class="datatable50 table w-100">
                    <thead>
                        <tr>
                            <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Name') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Price') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Interval') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Total Images') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Created date') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($yearlyPlans as $plan)
                            <tr class="item">
                                <td>{{ $plan->id }}</td>
                                <td>
                                    <a href="{{ route('admin.plans.edit', $plan->id) }}" class="text-dark">
                                        {{ $plan->name }}
                                        {{ $plan->isFeatured() ? '(' . admin_lang('Featured') . ')' : '' }}
                                    </a>
                                </td>
                                <td>
                                    <strong>
                                        @if ($plan->isFree())
                                            <span class="text-success">{{ admin_lang('Free') }}</span>
                                        @else
                                            <span class="text-dark">{{ priceSymbolCode($plan->price) }}</span>
                                        @endif
                                    </strong>
                                </td>
                                <td>{{ admin_lang('Yearly') }}</td>
                                <td>{{ number_format($plan->images) }}</td>
                                <td>{{ dateFormat($plan->created_at) }}</td>
                                <td>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                            aria-expanded="true">
                                            <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.plans.edit', $plan->id) }}"><i
                                                        class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.plans.destroy', $plan->id) }}"
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
    </div>
@endsection
