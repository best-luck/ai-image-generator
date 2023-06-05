@extends('frontend.layouts.single')
@section('title', lang('Pricing', 'pages'))
@section('content')
    {!! ads_other_pages_top() !!}
    <div class="section-header mb-5">
        <h1 class="mb-3">{{ lang('Pricing', 'pages') }}</h1>
        <p class="fw-light text-muted col-lg-7 mb-0">
            {{ lang('Pricing description', 'pages') }}
        </p>
    </div>
    @if ($yearlyPlans->count() > 0)
        <div class="d-flex justify-content-center mb-5">
            <div class="plan-switcher">
                <span class="plan-switcher-item active">{{ lang('Monthly', 'plans') }}</span>
                <span class="plan-switcher-item">{{ lang('Yearly', 'plans') }}</span>
            </div>
        </div>
    @endif
    <div class="plans">
        <div class="plans-item active">
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 justify-content-center align-items-center g-3">
                @foreach ($monthlyPlans as $plan)
                    <div class="col">
                        <div class="plan {{ $plan->isFeatured() ? 'pro' : '' }}">
                            <div class="plan-header">
                                <h3>{{ $plan->name }}</h3>
                                <p>{{ $plan->short_description }}</p>
                            </div>
                            <div class="plan-body">
                                <div class="plan-price">
                                    @if ($plan->isFree())
                                        <p class="mb-0">{{ lang('Free', 'plans') }}</p>
                                    @else
                                        <p class="mb-0">{{ priceSymbol($plan->price) }}</p>
                                        <span>/{{ formatInterval($plan->interval) }}</span>
                                    @endif
                                </div>
                                <div class="plan-features">
                                    <div class="plan-feature">
                                        <i class="fa fa-check"></i>
                                        <span>{!! str_replace(
                                            '{total_images}',
                                            '<strong>' . number_format($plan->images) . '</strong>',
                                            lang('Generate {total_images} Images', 'plans'),
                                        ) !!}</span>
                                    </div>
                                    <div class="plan-feature">
                                        <i class="fa fa-check"></i>
                                        <span>{!! str_replace(
                                            '{max_images}',
                                            '<strong>' . number_format($plan->max_images) . '</strong>',
                                            lang('{max_images} Images per request', 'plans'),
                                        ) !!}</span>
                                    </div>
                                    <div class="plan-feature">
                                        <i class="fa fa-check"></i>
                                        @if ($plan->expiration == 1)
                                            <span>{{ str_replace('{day}', number_format($plan->expiration), lang('Images Available For {day} day', 'plans')) }}</span>
                                        @elseif($plan->expiration > 1)
                                            <span>{{ str_replace('{days}', number_format($plan->expiration), lang('Images Available For {days} days', 'plans')) }}</span>
                                        @else
                                            <span>{{ lang('Images Available For Unlimited Time', 'plans') }}</span>
                                        @endif
                                    </div>
                                    <div class="plan-feature">
                                        <i class="fa fa-check"></i>
                                        @php
                                            $sizes = null;
                                            $i = 0;
                                            foreach ($plan->sizes as $size) {
                                                if (++$i === count($plan->sizes)) {
                                                    $sizes .= $size;
                                                } else {
                                                    $sizes .= "{$size}, ";
                                                }
                                            }
                                        @endphp
                                        <span>
                                            <span>{!! str_replace('{sizes}', $sizes, lang('Sizes : {sizes}', 'plans')) !!}</span>
                                        </span>
                                    </div>
                                    @if (!$plan->advertisements)
                                        <div class="plan-feature">
                                            <i class="fa fa-check"></i>
                                            <span>{{ lang('No Advertisements', 'plans') }}</span>
                                        </div>
                                    @endif
                                    @if ($plan->custom_features)
                                        @foreach ($plan->custom_features as $customFeature)
                                            <div class="plan-feature">
                                                <i class="fa fa-check"></i>
                                                <span>{{ $customFeature->name }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="plan-footer">
                                {!! planButton($plan) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @if ($yearlyPlans->count() > 0)
            <div class="plans-item">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 justify-content-center align-items-center g-3">
                    @foreach ($yearlyPlans as $plan)
                        <div class="col">
                            <div class="plan {{ $plan->isFeatured() ? 'pro' : '' }}">
                                <div class="plan-header">
                                    <h3>{{ $plan->name }}</h3>
                                    <p>{{ $plan->short_description }}</p>
                                </div>
                                <div class="plan-body">
                                    <div class="plan-price">
                                        @if ($plan->isFree())
                                            <p class="mb-0">{{ lang('Free', 'plans') }}</p>
                                        @else
                                            <p class="mb-0">{{ priceSymbol($plan->price) }}</p>
                                            <span>/{{ formatInterval($plan->interval) }}</span>
                                        @endif
                                    </div>
                                    <div class="plan-features">
                                        <div class="plan-feature">
                                            <i class="fa fa-check"></i>
                                            <span>{!! str_replace(
                                                '{total_images}',
                                                '<strong>' . number_format($plan->images) . '</strong>',
                                                lang('Generate {total_images} Images', 'plans'),
                                            ) !!}</span>
                                        </div>
                                        <div class="plan-feature">
                                            <i class="fa fa-check"></i>
                                            <span>{!! str_replace(
                                                '{max_images}',
                                                '<strong>' . number_format($plan->max_images) . '</strong>',
                                                lang('{max_images} Images per request', 'plans'),
                                            ) !!}</span>
                                        </div>
                                        <div class="plan-feature">
                                            <i class="fa fa-check"></i>
                                            @if ($plan->expiration == 1)
                                                <span>{{ str_replace('{day}', number_format($plan->expiration), lang('Images Available For {day} day', 'plans')) }}</span>
                                            @elseif($plan->expiration > 1)
                                                <span>{{ str_replace('{days}', number_format($plan->expiration), lang('Images Available For {days} days', 'plans')) }}</span>
                                            @else
                                                <span>{{ lang('Images Available For Unlimited Time', 'plans') }}</span>
                                            @endif
                                        </div>
                                        <div class="plan-feature">
                                            <i class="fa fa-check"></i>
                                            @php
                                                $sizes = null;
                                                $i = 0;
                                                foreach ($plan->sizes as $size) {
                                                    if (++$i === count($plan->sizes)) {
                                                        $sizes .= $size;
                                                    } else {
                                                        $sizes .= "{$size}, ";
                                                    }
                                                }
                                            @endphp
                                            <span>
                                                <span>{!! str_replace('{sizes}', $sizes, lang('Sizes : {sizes}', 'plans')) !!}</span>
                                            </span>
                                        </div>
                                        @if (!$plan->advertisements)
                                            <div class="plan-feature">
                                                <i class="fa fa-check"></i>
                                                <span>{{ lang('No Advertisements', 'plans') }}</span>
                                            </div>
                                        @endif
                                        @if ($plan->custom_features)
                                            @foreach ($plan->custom_features as $customFeature)
                                                <div class="plan-feature">
                                                    <i class="fa fa-check"></i>
                                                    <span>{{ $customFeature->name }}</span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="plan-footer">
                                    {!! planButton($plan) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    {!! ads_other_pages_bottom() !!}
@endsection
