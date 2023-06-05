<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Methods\ReCaptchaValidation;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Page;
use App\Models\Plan;
use Illuminate\Http\Request;
use Validator;

class GlobalController extends Controller
{
    public function features()
    {
        $features = Feature::where('lang', getLang())->get();
        return view('frontend.features', ['features' => $features]);
    }

    public function page($slug)
    {
        $page = Page::where([['slug', $slug], ['lang', getLang()]])->first();
        if ($page) {
            $page->increment('views');
            return view('frontend.page', ['page' => $page]);
        } else {
            return redirect()->route('home');
        }
    }

    public function pricing()
    {
        $monthlyPlans = Plan::monthly()->get();
        $yearlyPlans = Plan::yearly()->get();
        return view('frontend.pricing', ['monthlyPlans' => $monthlyPlans, 'yearlyPlans' => $yearlyPlans]);
    }

    public function faqs()
    {
        $faqs = Faq::where('lang', getLang())->paginate(15);
        return view('frontend.faqs', ['faqs' => $faqs]);
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function contactSend(Request $request)
    {
        if (!settings('smtp')->status || !settings('general')->contact_email) {
            toastr()->error(lang('Sending emails is not available right now'));
            return back();
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ] + ReCaptchaValidation::validate());
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        try {
            $name = $request->name;
            $email = $request->email;
            $subject = $request->subject;
            $msg = allowBr($request->message);
            \Mail::send([], [], function ($message) use ($msg, $email, $subject, $name) {
                $message->to(settings('general')->contact_email)
                    ->from(env('MAIL_FROM_ADDRESS'), $name)
                    ->replyTo($email)
                    ->subject($subject)
                    ->html($msg);
            });
            toastr()->success(lang('Your message has been sent successfully'));
            return back();
        } catch (\Exception$e) {
            toastr()->error(lang('Error on sending'));
            return back();
        }
    }
}
