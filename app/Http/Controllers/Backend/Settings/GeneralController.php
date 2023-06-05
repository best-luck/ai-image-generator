<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Validator;

class GeneralController extends Controller
{
    public function index()
    {
        return view('backend.settings.general.index');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'general.site_name' => 'required|string|max:255',
            'general.site_url' => 'required|url',
            'general.contact_email' => 'required|email',
            'general.terms_of_service_link' => 'nullable|url',
            'general.date_format' => 'required|in:' . implode(',', array_keys(dateFormatsArray())),
            'general.timezone' => 'required|in:' . implode(',', array_keys(timezonesArray())),
            'ai_api.provider' => 'required|string|in:openai,stablediffusion',
            'ai_api.api_key' => 'required|string|max:255',
            'currency.code' => ['required', 'string', 'max:4', 'regex:/^[A-Z]{3}$/'],
            'currency.symbol' => ['required', 'string', 'max:4'],
            'currency.position' => ['required', 'integer', 'min:1', 'max:2'],
            'subscription.about_to_expire_reminder' => ['required', 'integer', 'min:1', 'max:14'],
            'subscription.expired_reminder' => ['required', 'integer', 'min:1', 'max:14'],
            'subscription.delete_expired' => ['required', 'integer', 'min:3', 'max:365'],
            'colors.primary_color' => 'required|regex:/^#[A-Fa-f0-9]{6}$/',
            'colors.secondary_color' => 'required|regex:/^#[A-Fa-f0-9]{6}$/',
            'colors.third_color' => 'required|regex:/^#[A-Fa-f0-9]{6}$/',
            'colors.background_color' => 'required|regex:/^#[A-Fa-f0-9]{6}$/',
            'media.dark_logo' => 'nullable|mimes:png,jpg,jpeg,svg',
            'media.light_logo' => 'nullable|mimes:png,jpg,jpeg,svg',
            'media.favicon' => 'nullable|mimes:png,jpg,jpeg,ico',
            'media.social_image' => 'nullable|mimes:jpg,jpeg',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {toastr()->error($error);}
            return back();
        }

        if ($request->has('action.email_verification_status') && !settings('smtp')->status) {
            toastr()->error(admin_lang('SMTP is not enabled'));
            return back()->withInput();
        }

        if ($request->input('subscription.delete_expired') < $request->input('subscription.expired_reminder')) {
            toastr()->error(admin_lang('Subscription Expired reminder should be less than delete expired subscriptions period'));
            return back()->withInput();
        }

        $requestData = $request->except('_token');

        if ($request->has('media.dark_logo')) {
            $filename = 'dark-logo';
            $darkLogo = fileUpload($request->file('media.dark_logo'), 'images/', $filename, settings('media')->dark_logo);
            $requestData['media']['dark_logo'] = $darkLogo;
        } else {
            $requestData['media']['dark_logo'] = settings('media')->dark_logo;
        }

        if ($request->has('media.light_logo')) {
            $filename = 'light-logo';
            $lightLogo = fileUpload($request->file('media.light_logo'), 'images/', $filename, settings('media')->light_logo);
            $requestData['media']['light_logo'] = $lightLogo;
        } else {
            $requestData['media']['light_logo'] = settings('media')->light_logo;
        }

        if ($request->has('media.favicon')) {
            $filename = 'favicon';
            $favicon = fileUpload($request->file('media.favicon'), 'images/', $filename, settings('media')->favicon);
            $requestData['media']['favicon'] = $favicon;
        } else {
            $requestData['media']['favicon'] = settings('media')->favicon;
        }

        if ($request->has('media.social_image')) {
            $filename = 'social-image';
            $ogImage = imageUpload($request->file('media.social_image'), 'images/', '600x315', $filename, settings('media')->social_image);
            $requestData['media']['social_image'] = $ogImage;
        } else {
            $requestData['media']['social_image'] = settings('media')->social_image;
        }

        $requestData['actions']['email_verification_status'] = ($request->has('actions.email_verification_status')) ? 1 : 0;
        $requestData['actions']['registration_status'] = ($request->has('actions.registration_status')) ? 1 : 0;
        $requestData['actions']['gdpr_cookie_status'] = ($request->has('actions.gdpr_cookie_status')) ? 1 : 0;
        $requestData['actions']['force_ssl_status'] = ($request->has('actions.force_ssl_status')) ? 1 : 0;
        $requestData['actions']['blog_status'] = ($request->has('actions.blog_status')) ? 1 : 0;
        $requestData['actions']['contact_page'] = ($request->has('actions.contact_page')) ? 1 : 0;
        $requestData['actions']['features_page'] = ($request->has('actions.features_page')) ? 1 : 0;
        $requestData['actions']['faqs_status'] = ($request->has('actions.faqs_status')) ? 1 : 0;
        $requestData['actions']['language_type'] = ($request->has('actions.language_type')) ? 1 : 0;

        foreach ($requestData as $key => $value) {
            $update = Settings::updateSettings($key, $value);
            if (!$update) {
                toastr()->error(admin_lang(ucfirst($key) . ' ' . 'Updated Error'));
                return back();
            }
        }

        setEnv('APP_URL', $requestData['general']['site_url']);
        setEnv('APP_TIMEZONE', "'{$requestData['general']['timezone']}'");

        $colorsFile = 'assets/extra/css/colors.css';
        if (!file_exists($colorsFile)) {
            fopen($colorsFile, "w");
        }
        $colors = "
        :root {
            --primaryColor: {$requestData['colors']['primary_color']};
            --secondaryColor: {$requestData['colors']['secondary_color']};
            --thirdColor: {$requestData['colors']['third_color']};
            --bgColor: {$requestData['colors']['background_color']};
        }
        ";
        file_put_contents($colorsFile, $colors);

        toastr()->success(admin_lang('Updated Successfully'));
        return back();
    }
}