@extends('backend.layouts.grid')
@section('title', admin_lang('Payment gateways'))
@section('section', admin_lang('Settings'))
@section('content')
    <div class="alert {{ $gateways->count() > 0 ? 'alert-success' : 'alert-danger' }} ">
        <strong>{{ $gateways->count() }}</strong>
        <span>{{ admin_lang('Payment gateways supports your website currency') }}</span>
        <strong>{{ $settings->currency->code }}</strong>
        <span>{{ admin_lang(',You can change your currency form') }}</span>
        <a href="{{ route('admin.settings.general') }}">{{ admin_lang('general settings') }}.</a>
    </div>
    <div class="card custom-card">
        <table id="datatable" class="table w-100">
            <thead>
                <tr>
                    <th class="tb-w-1x">{{ admin_lang('#') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('name') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Logo') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Fees') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Status') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Last Update') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gateways as $gateway)
                    <tr class="item">
                        <td>{{ $gateway->id }}</td>
                        <td>
                            <a href="{{ route('admin.settings.gateways.edit', $gateway->id) }}" class="text-dark">
                                {{ $gateway->name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.settings.gateways.edit', $gateway->id) }}">
                                <img src="{{ asset($gateway->logo) }}" height="35" width="100">
                            </a>
                        </td>
                        <td><span class="badge bg-dark">{{ $gateway->fees }}%</span></td>
                        <td>
                            @if ($gateway->status)
                                <span class="badge bg-success">{{ admin_lang('Active') }}</span>
                            @else
                                <span class="badge bg-danger">{{ admin_lang('Disabled') }}</span>
                            @endif
                        </td>
                        <td>{{ dateFormat($gateway->updated_at) }}</td>
                        <td>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.settings.gateways.edit', $gateway->id) }}"><i
                                                class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
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
