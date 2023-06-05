@extends('vironeer::layouts.app')
@section('title', admin_lang('Requirements'))
@section('content')
    <div class="vironeer-steps-body">
        @foreach ($extensions as $extension)
            <div class="vironeer-steps-req">
                <p class="mb-0">{{ $extension }}</p>
                @if (extensionAvailability($extension))
                    <div class="vironeer-steps-req-success">
                        <i class="fa fa-check"></i>
                    </div>
                @else
                    <div class="vironeer-steps-req-fail">
                        <i class="fa fa-times"></i>
                    </div>
                @endif
            </div>
        @endforeach
        <div class="mt-3">
            @if (!$error)
                <div class="alert alert-success">
                    <i class="fa fa-check-circle me-2"></i>
                    {{ admin_lang('Congrats all extensions are enabled you can continue to next step') }}
                </div>
                <form action="{{ route('install.requirements') }}" method="POST">
                    @csrf
                    <button class="btn btn-primary btn-md">{{ admin_lang('Continue') }}<i
                            class="fas fa-arrow-right ms-2"></i></button>
                </form>
            @else
                <div class="alert alert-danger">
                    <i class="fa fa-times-circle me-2"></i>
                    {{ admin_lang('Some extensions are required please enable them before you can continue.') }}
                </div>
                <button class="btn btn-primary btn-md" disabled>{{ admin_lang('Continue') }}<i
                        class="fas fa-arrow-right ms-2"></i></button>
            @endif
        </div>
    </div>
@endsection
