@extends('backend.layouts.form')
@section('title', admin_lang('Edit Subscription for ' . $subscription->user->name))
@section('back', route('admin.subscriptions.index'))
@section('container', 'container-max-lg')
@section('content')
    @if ($subscription->isCancelled())
        <div class="alert bg-danger text-white">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>{{ admin_lang('This subscription has been canceled') }}</strong>
        </div>
    @endif
    <div class="card text-center mb-3 p-4">
        <div class="card-body">
            <img src="{{ asset($subscription->user->avatar) }}" alt="{{ $subscription->name }}" class="rounded-circle mb-3">
            <h4 class="mb-3">{{ $subscription->user->name }}</h4>
            <a href="{{ route('admin.users.edit', $subscription->user->id) }}"
                class="btn btn-primary">{{ admin_lang('View Account details') }}</a>
        </div>
    </div>
    <form id="vironeer-submited-form" action="{{ route('admin.subscriptions.update', $subscription->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card p-2">
            <div class="card-body">
                <div class="mb-1">
                    <label class="form-label">{{ admin_lang('Status') }} : <span class="red">*</span></label>
                    <select name="status" class="form-select form-control-lg">
                        <option value="0" {{ $subscription->status == 0 ? 'selected' : '' }}>
                            {{ admin_lang('Canceled') }}
                        </option>
                        <option value="1" {{ $subscription->status == 1 ? 'selected' : '' }}>
                            {{ admin_lang('Active') }}
                        </option>
                    </select>
                </div>
                <div class="mb-1 mt-3">
                    <label class="form-label">{{ admin_lang('Plan') }} : <span class="red">*</span></label>
                    <select id="subscriptionPlan" name="plan" class="form-select form-control-lg" required>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}"
                                {{ $subscription->plan->id == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }}
                                {{ $plan->interval == 1 ? admin_lang('(Monthly)') : admin_lang('(Yearly)') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-3">
                    <label class="form-label">{{ admin_lang('Expiry at') }} : <span class="red">*</span></label>
                    <input type="datetime-local" name="expiry_at" class="form-control form-control-lg"
                        value="{{ carbon()->parse($subscription->expiry_at)->format('Y-m-d\TH:i:s') }}" required>
                </div>
                <div class="mt-3 mb-1">
                    <label class="form-label">{{ admin_lang('Generated Images') }} : <span class="red">*</span></label>
                    <input type="number" name="generated_images" class="form-control form-control-lg"
                        value="{{ $subscription->generated_images }}">
                </div>
            </div>
        </div>
    </form>
@endsection
