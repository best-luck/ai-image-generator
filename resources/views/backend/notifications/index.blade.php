@extends('backend.layouts.grid')
@section('title', admin_lang('Notifications (' . $unreadNotificationsCount . ')'))
@section('container', 'container-max-lg')
@section('content')
    <div class="notifications">
        @forelse ($notifications as $notification)
            @if ($notification->link)
                <a href="{{ route('admin.notifications.view', hashid($notification->id)) }}"
                    class="notification-item d-flex justify-content-between align-items-center">

                    <div class="flex-shrink-0">
                        <img class="rounded-2" src="{{ $notification->image }}" width="60" height="60">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-1">{{ $notification->title }}</h5>
                        <p class="mb-0 text-muted">{{ $notification->created_at->diffforhumans() }}</p>
                    </div>
                    @if (!$notification->status)
                        <div class="flex-grow-2 ms-3">
                            <span class="icon text-danger flashit"><i class="fas fa-circle"></i></span>
                        </div>
                    @endif
                </a>
            @else
                <div class="notification-item d-flex justify-content-between align-items-center">
                    <div class="flex-shrink-0">
                        <img class="rounded-2" src="{{ $notification->image }}" width="60" height="60">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-1">{{ $notification->title }}</h5>
                        <p class="mb-0 text-muted">{{ $notification->created_at->diffforhumans() }}</p>
                    </div>
                    @if (!$notification->status)
                        <div class="flex-grow-2 ms-3">
                            <span class="icon text-danger flashit"><i class="fas fa-circle"></i></span>
                        </div>
                    @endif
                </div>
            @endif
        @empty
            <div class="card">
                <div class="card-body">
                    @include('backend.includes.empty')
                </div>
            </div>
        @endforelse
    </div>
    {{ $notifications->links() }}
@endsection
