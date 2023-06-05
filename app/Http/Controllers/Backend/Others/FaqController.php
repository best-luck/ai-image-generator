<?php

namespace App\Http\Controllers\Backend\Others;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Language;
use Illuminate\Http\Request;
use Validator;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('lang')) {
            $language = Language::where('code', $request->lang)->firstOrFail();
            $faqs = Faq::where('lang', $language->code)->get();
            return view('backend.others.faqs.index', ['faqs' => $faqs, 'active' => $language->name]);
        } else {
            return redirect(url()->current() . '?lang=' . env('DEFAULT_LANGUAGE'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.others.faqs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lang' => ['required', 'string', 'max:3', 'exists:languages,code'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        $create = Faq::create([
            'lang' => $request->lang,
            'title' => $request->title,
            'content' => $request->content,
        ]);
        if ($create) {
            toastr()->success(admin_lang('Created Successfully'));
            return redirect(route('admin.faqs.index') . '?lang=' . $create->lang);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq)
    {
        return view('backend.others.faqs.edit', ['faq' => $faq]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faq $faq)
    {
        $validator = Validator::make($request->all(), [
            'lang' => ['required', 'string', 'max:3', 'exists:languages,code'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        $update = $faq->update([
            'lang' => $request->lang,
            'title' => $request->title,
            'content' => $request->content,
        ]);
        if ($update) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}