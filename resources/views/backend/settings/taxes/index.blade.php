@extends('backend.layouts.grid')
@section('title', admin_lang('Taxes'))
@section('section', admin_lang('Settings'))
@section('container', 'container-max-lg')
@section('link', route('admin.settings.taxes.create'))
@section('content')
    @if (count($taxes) > 0)
        <div class="card custom-card">
            <ul class="custom-list-group list-group list-group-flush">
                @foreach ($taxes as $tax)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="details">
                            <h5 class="m-0 d-inline">
                                {{ !$tax->country_id ? admin_lang('All countries') : $tax->country->name }}
                            </h5>
                            <i class="fas fa-chevron-right me-1 ms-1"></i>
                            <span class="text-muted">({{ $tax->percentage }}%)</span>
                        </div>
                        <div class="buttons">
                            <a href="{{ route('admin.settings.taxes.edit', $tax->id) }}" class="btn btn-dark btn-sm me-2"><i
                                    class="fa fa-edit"></i></a>
                            <form class="d-inline" action="{{ route('admin.settings.taxes.destroy', $tax->id) }}"
                                method="POST">
                                @method('DELETE')
                                @csrf
                                <button class="vironeer-able-to-delete btn btn-danger btn-sm"><i
                                        class="far fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        {{ $taxes->links() }}
    @else
        <div class="card">
            <div class="card-body">
                @include('backend.includes.empty')
            </div>
        </div>
    @endif
@endsection
