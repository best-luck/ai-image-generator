<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\SeoConfiguration;
use Illuminate\Http\Request;
use Validator;

class SeoController extends Controller
{
    private $robots_index_array = ['index', 'noindex'];
    private $robots_follow_links_array = ['follow', 'nofollow'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configurations = SeoConfiguration::with('language')->get();
        return view('backend.settings.seo.index', ['configurations' => $configurations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::orderbyDesc('id')->get();
        return view('backend.settings.seo.create', ['languages' => $languages]);
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
            'lang' => ['required', 'string', 'unique:seo_configurations', 'exists:languages,code'],
            'title' => ['required', 'string', 'max:70'],
            'description' => ['required', 'string', 'max:150'],
            'keywords' => ['required', 'string', 'max:255'],
            'robots_index' => ['required', 'string', 'max:50'],
            'robots_follow_links' => ['required', 'string', 'max:50'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        if (!in_array($request->robots_index, $this->robots_index_array) ||
            !in_array($request->robots_follow_links, $this->robots_follow_links_array)) {
            toastr()->error(admin_lang('Something went wrong please try again'));
            return back();
        }
        $create = SeoConfiguration::create([
            'lang' => $request->lang,
            'title' => $request->title,
            'description' => $request->description,
            'keywords' => $request->keywords,
            'robots_index' => $request->robots_index,
            'robots_follow_links' => $request->robots_follow_links,
        ]);
        if ($create) {
            toastr()->success(admin_lang('Created Successfully'));
            return redirect()->route('admin.settings.seo.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SeoConfiguration  $seoConfiguration
     * @return \Illuminate\Http\Response
     */
    public function show(SeoConfiguration $seo)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SeoConfiguration  $seoConfiguration
     * @return \Illuminate\Http\Response
     */
    public function edit(SeoConfiguration $seo)
    {
        $languages = Language::orderbyDesc('id')->get();
        return view('backend.settings.seo.edit', ['languages' => $languages, 'configuration' => $seo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SeoConfiguration  $seoConfiguration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SeoConfiguration $seo)
    {
        $validator = Validator::make($request->all(), [
            'lang' => ['required', 'string', 'unique:seo_configurations,lang,' . $seo->id, 'exists:languages,code'],
            'title' => ['required', 'string', 'max:70'],
            'description' => ['required', 'string', 'max:150'],
            'keywords' => ['required', 'string', 'max:255'],
            'robots_index' => ['required', 'string', 'max:50'],
            'robots_follow_links' => ['required', 'string', 'max:50'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if (!in_array($request->robots_index, $this->robots_index_array) ||
            !in_array($request->robots_follow_links, $this->robots_follow_links_array)) {
            toastr()->error(admin_lang('Something went wrong please try again'));
            return back();
        }
        $update = $seo->update([
            'lang' => $request->lang,
            'title' => $request->title,
            'description' => $request->description,
            'keywords' => $request->keywords,
            'robots_index' => $request->robots_index,
            'robots_follow_links' => $request->robots_follow_links,
        ]);
        if ($update) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SeoConfiguration  $seoConfiguration
     * @return \Illuminate\Http\Response
     */
    public function destroy(SeoConfiguration $seo)
    {
        $seo->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
