@extends('backend.layouts.grid')
@section('title', admin_lang('Coupons'))
@section('link', route('admin.coupons.create'))
@section('content')
    <div class="card ratings custom-card">
        <table id="datatable" class="table w-100">
            <thead>
                <tr>
                    <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Coupon') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Percentage') }}</th>
                    <th class="tb-w-2x">{{ admin_lang('Limit') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Plan') }}</th>
                    <th class="tb-w-2x">{{ admin_lang('Action type') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('expiry date') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->id }}</td>
                        <td>{{ $coupon->code }}</td>
                        <td><strong>{{ $coupon->percentage }}% {{ admin_lang('OFF') }}</strong></td>
                        <td>{{ $coupon->limit }}</td>
                        <td>
                            @if (!is_null($coupon->plan_id))
                                <a href="{{ route('admin.plans.edit', $coupon->plan->id) }}"
                                    style="color: {{ $coupon->plan->color }}"><i class="far fa-gem me-2"></i>
                                    {{ $coupon->plan->name }}
                                </a>
                            @else
                                <a href="{{ route('admin.plans.index') }}" class="text-dark">
                                    <i class="far fa-gem me-2"></i>{{ admin_lang('All plans') }}
                                </a>
                            @endif
                        </td>
                        <td>
                            @if ($coupon->action_type == 0)
                                <span class="badge bg-lg-1">{{ admin_lang('All actions') }}</span>
                            @elseif($coupon->action_type == 1)
                                <span class="badge bg-lg-2">{{ admin_lang('Subscribing') }}</span>
                            @elseif($coupon->action_type == 2)
                                <span class="badge bg-lg-3">{{ admin_lang('Renewal') }}</span>
                            @elseif($coupon->action_type == 3)
                                <span class="badge bg-lg-4">{{ admin_lang('Upgrade') }}</span>
                            @endif
                        </td>
                        <td>
                            @if (!$coupon->isExpiry())
                                {{ dateFormat($coupon->expiry_at) }}
                            @else
                                <span class="badge bg-danger">{{ admin_lang('Expired') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.coupons.edit', $coupon->id) }}"><i
                                                class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST">
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
