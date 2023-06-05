@extends('backend.layouts.form')
@section('title', admin_lang('Edit Plan') . ' | ' . $plan->name)
@section('container', 'container-max-lg')
@section('back', route('admin.plans.index'))
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.plans.update', $plan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card custom-card mb-4">
            <div class="card-header bg-primary text-white">
                {{ admin_lang('Plan details') }}
            </div>
            <ul id="customFeatures" class="custom-list-group list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-lg-6">
                            <label class="col-form-label"><strong>{{ admin_lang('Plan Name') }} : <span
                                        class="red">*</span></strong></label>
                        </div>
                        <div class="col-12 col-lg-2">
                            <input type="checkbox" name="is_featured" class="form-check-input"
                                {{ $plan->isFeatured() ? 'checked' : '' }}>
                            <label>{{ admin_lang('Featured plan') }}</label>
                        </div>
                        <div class="col col-lg-4">
                            <input type="text" name="name" class="form-control" required value="{{ $plan->name }}"
                                placeholder="{{ admin_lang('Enter plan name') }}" autofocus>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-lg-8">
                            <label class="col-form-label d-block"><strong>{{ admin_lang('Short description') }} : <span
                                        class="red">*</span></strong></label>
                        </div>
                        <div class="col-12 col-lg-4">
                            <textarea name="short_description" class="form-control" required placeholder="{{ admin_lang('Max 150 character') }}">{{ $plan->short_description }}</textarea>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-lg-8">
                            <label class="col-form-label"><strong>{{ admin_lang('Plan Interval') }} :
                                </strong></strong></label>
                        </div>
                        <div class="col col-lg-4">
                            <select class="form-select" disabled required>
                                <option value="1" {{ old('interval') == 1 ? 'selected' : '' }}>
                                    {{ admin_lang('Monthly') }}
                                </option>
                                <option value="2" {{ old('interval') == 2 ? 'selected' : '' }}>
                                    {{ admin_lang('Yearly') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-lg-6">
                            <label class="col-form-label"><strong>{{ admin_lang('Plan Price') }} : <span
                                        class="red">*</span></strong></strong></label>
                        </div>
                        <div class="col-12 col-lg-2">
                            <input type="checkbox" name="is_free" class="free-plan-checkbox form-check-input"
                                {{ $plan->isFree() ? 'checked' : '' }}>
                            <label>{{ admin_lang('Free') }}</label>
                        </div>
                        <div class="col col-lg-4">
                            <div class="custom-input-group input-group plan-price">
                                <input type="text" name="price" class="form-control input-price"
                                    value="{{ price($plan->price) }}" placeholder="0.00" required
                                    {{ $plan->isFree() ? 'disabled' : '' }} />
                                <span
                                    class="input-group-text {{ $plan->isFree() ? 'disabled' : '' }}"><strong>{{ $settings->currency->code }}</strong></span>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="plan-require-login list-group-item {{ $plan->isFree() ? '' : 'd-none' }}">
                    <div class="row align-items-center">
                        <div class="col-8 col-lg-8">
                            <label class="col-form-label d-block"><strong>{{ admin_lang('Require Login') }} :
                                </strong></label>
                            <small>{{ admin_lang('Without login, the guests will be able to generate the images.') }}</small>
                        </div>
                        <div class="col-4 col-lg-4">
                            <input type="checkbox" name="login_require" data-toggle="toggle"
                                data-on="{{ admin_lang('Yes') }}" data-off="{{ admin_lang('No') }}"
                                {{ $plan->login_require ? 'checked' : '' }}>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-lg-8">
                            <label class="col-form-label d-block"><strong>{{ admin_lang('Total Images') }} : <span
                                        class="red">*</span></strong></label>
                            <small>{{ admin_lang('Total images that users or guests can generate every month or year.') }}</small>
                        </div>
                        <div class="col-12 col-lg-4">
                            <input type="number" name="images" class="form-control" placeholder="0"
                                value="{{ $plan->images }}" min="1" required>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-lg-8">
                            <label class="col-form-label d-block"><strong>{{ admin_lang('Max Images For Every Request') }}
                                    : <span class="red">*</span></strong></label>
                            <small>{{ admin_lang('How many images can be generated on every request from 1 to 10 max.') }}</small>
                        </div>
                        <div class="col-12 col-lg-4">
                            <input type="number" name="max_images" class="form-control" placeholder="0"
                                value="{{ $plan->max_images }}" min="1" max="10" required>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-lg-6">
                            <label class="col-form-label d-block"><strong>{{ admin_lang('Expiration') }} : <span
                                        class="red">*</span></strong></strong></label>
                            <small>{{ admin_lang('When will generated images expire.') }}</small>
                        </div>
                        <div class="col-12 col-lg-2">
                            <input type="checkbox" name="no_expiration" class="form-check-input plan-expiration"
                                {{ !$plan->expiration ? 'checked' : '' }}>
                            <label>{{ admin_lang('Unlimited time') }}</label>
                        </div>
                        <div class="col col-lg-4">
                            <div class="custom-input-group input-group plan-expiration-input">
                                <input type="text" name="expiration" class="form-control"
                                    value="{{ $plan->expiration }}" placeholder="0" required
                                    {{ !$plan->expiration ? 'disabled' : '' }} />
                                <span class="input-group-text"><strong>{{ admin_lang('Days') }}</strong></span>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-lg-7">
                            <label class="col-form-label d-block"><strong>{{ admin_lang('Image Sizes') }}
                                    : <span class="red">*</span></strong></label>
                            <small>{{ admin_lang('Choose the image sizes that can be generated 256x256, 512x512, or 1024x1024.') }}</small>
                        </div>
                        <div class="col-12 col-lg-5">
                            <select class="form-select select2" name="sizes[]" multiple="multiple">
                                @foreach (\App\Models\Plan::SIZES as $size)
                                    <option value="{{ $size }}"
                                        {{ in_array($size, $plan->sizes) ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col-8 col-lg-8">
                            <label class="col-form-label d-block"><strong>{{ admin_lang('Show advertisements') }} :
                                </strong></label>
                            <small>{{ admin_lang('Show or hide the advertisements ') }}</small>
                        </div>
                        <div class="col-4 col-lg-4">
                            <input type="checkbox" name="advertisements" data-toggle="toggle"
                                data-on="{{ admin_lang('Yes') }}" data-off="{{ __('No') }}"
                                {{ $plan->advertisements ? 'checked' : '' }}>
                        </div>
                    </div>
                </li>
                @if ($plan->custom_features)
                    @foreach ($plan->custom_features as $key => $value)
                        <li id="customFeature{{ $key }}" class="list-group-item">
                            <div class="row g-2 align-items-center">
                                <div class="col">
                                    <input type="text" name="custom_features[{{ $key }}][name]"
                                        placeholder="{{ admin_lang('Enter name') }}" class="form-control"
                                        value="{{ $value->name }}" required>
                                </div>
                                <div class="col-auto">
                                    <button type="button" data-id="{{ $key }}"
                                        class="removeFeature btn btn-danger"><i class="fa fa-trash-alt"></i></button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
        <button type="button" id="addCustomFeature" class="btn btn-primary"><i
                class="fa fa-plus me-2"></i>{{ admin_lang('Add custom feature') }}</button>
    </form>
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.priceformat.min.js') }}"></script>
    @endpush
    @push('top_scripts')
        <script>
            "use strict";
            var customFeatureI = {{ $plan->custom_features ? count($plan->custom_features) - 1 : -1 }};
        </script>
    @endpush
@endsection
