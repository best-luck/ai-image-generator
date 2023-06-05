<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\MailTemplate;
use App\Models\Settings;
use Illuminate\Http\Request;
use Validator;

class MailTemplateController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('lang')) {
            $language = Language::where('code', $request->lang)->firstOrFail();
            $mailTemplates = MailTemplate::where('lang', $language->code)->with('language')->get();
            return view('backend.settings.mailtemplates.index', [
                'mailTemplates' => $mailTemplates,
                'active' => $language->name,
            ]);
        } else {
            return redirect(url()->current() . '?lang=' . env('DEFAULT_LANGUAGE'));
        }
    }

    public function edit(Request $request, MailTemplate $mailTemplate)
    {
        return view('backend.settings.mailtemplates.edit', ['mailTemplate' => $mailTemplate]);
    }

    public function update(Request $request, MailTemplate $mailTemplate)
    {
        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {toastr()->error($error);}
            return back();
        }
        if (!$mailTemplate->undisable()) {
            $request->status = ($request->has('status')) ? 1 : 0;
        } else {
            $request->status = 1;
        }
        $update = $mailTemplate->update([
            'subject' => $request->subject,
            'status' => $request->status,
            'body' => $request->body,
        ]);
        if ($update) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }
}
