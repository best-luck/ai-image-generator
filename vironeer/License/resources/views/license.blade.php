@extends('vironeer::layouts.app')
@section('title', admin_lang('License'))
@section('content')
    <div class="vironeer-steps-body">
        <p class="vironeer-form-info-text">
            {{ admin_lang('As part of protecting our products we are building our systems to validate the license for every customer, the license means your purchase code.') }}
        </p>
        <div class="mb-4">
            <form action="" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Purchase Code') }} : <span class="red">*</span></label>
                    <input type="text" name="purchase_code" class="form-control form-control-md"
                        placeholder="{{ admin_lang('Enter your purchase code') }}" autocomplete="off" autofocus required>
                </div>
                <button class="btn btn-primary btn-md">{{ admin_lang('Continue') }}<i class="fas fa-arrow-right ms-2"></i></button>
            </form>
        </div>
        <div class="vironeer-links">
            <h6 class="mb-3">{{ admin_lang('Follow the links below to learn more about licenses and how you can get it') }} :</h6>
            <li class="mb-1">
                <a target="_blank" href="https://codecanyon.net/licenses/standard">{{ admin_lang('What The Licence Mean') }}?</a>
            </li>
            <li class="mb-1">
                <a target="__blank"
                    href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">{{ admin_lang('Where Is My Purchase Code') }}?</a>
            </li>
            <li class="mb-0">
                <a target="_blank"
                    href="https://codecanyon.net/user/vironeer/portfolio">{{ admin_lang('Where I Can Bought a Licence') }}?</a>
            </li>
        </div>
    </div>
@endsection
