<?php

namespace App\Http\Controllers\Backend\Others;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Language;
use Illuminate\Http\Request;
use Validator;

class FeatureController extends Controller
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
            $features = Feature::where('lang', $language->code)->with('language')->get();
            return view('backend.others.features.index', ['features' => $features, 'active' => $language->name]);
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
        return view('backend.others.features.create');
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
            'title' => ['required', 'string', 'max:255', 'min:2'],
            'image' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'content' => ['required', 'string', 'max:600'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        $image = imageUpload($request->file('image'), 'images/others/features/');
        if ($image) {
            $create = Feature::create([
                'lang' => $request->lang,
                'title' => $request->title,
                'image' => $image,
                'content' => $request->content,
            ]);
            if ($create) {
                toastr()->success(admin_lang('Created Successfully'));
                return redirect(route('admin.features.index') . '?lang=' . $create->lang);
            }
        } else {
            toastr()->error(admin_lang('Upload error'));
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function show(Feature $feature)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function edit(Feature $feature)
    {
        return view('backend.others.features.edit', ['feature' => $feature]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feature $feature)
    {
        $validator = Validator::make($request->all(), [
            'lang' => ['required', 'string', 'max:3', 'exists:languages,code'],
            'title' => ['required', 'string', 'max:255', 'min:2'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'content' => ['required', 'string', 'max:600'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        if ($request->has('image')) {
            $image = imageUpload($request->file('image'), 'images/others/features/', null, null, $feature->image);
        } else {
            $image = $feature->image;
        }
        if ($image) {
            $update = $feature->update([
                'lang' => $request->lang,
                'title' => $request->title,
                'image' => $image,
                'content' => $request->content,
                'link' => $request->link,
            ]);
            if ($update) {
                toastr()->success(admin_lang('Updated Successfully'));
                return back();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feature $feature)
    {
        removeFile($feature->image);
        $feature->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}