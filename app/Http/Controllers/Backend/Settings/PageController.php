<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Page;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Validator;

class PageController extends Controller
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
            $pages = Page::where('lang', $language->code)->with('language')->get();
            return view('backend.settings.pages.index', ['pages' => $pages, 'active' => $language->name]);
        } else {
            return redirect(url()->current() . '?lang=' . env('DEFAULT_LANGUAGE'));
        }
    }

    /**
     * Create a page slug using ajax request
     *
     * @return \Illuminate\Http\Response
     */
    public function slug(Request $request)
    {
        $slug = null;
        if ($request->content != null) {
            $slug = SlugService::createSlug(Page::class, 'slug', $request->content);
        }
        return response()->json(['slug' => $slug]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.settings.pages.create');
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
            'title' => ['required', 'max:255', 'min:2'],
            'content' => ['required', 'min:2'],
            'short_description' => ['required', 'max:200', 'min:2'],
            'slug' => ['required', 'unique:pages', 'alpha_dash'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        $page = Page::create([
            'lang' => $request->lang,
            'title' => $request->title,
            'slug' => SlugService::createSlug(Page::class, 'slug', $request->title),
            'content' => $request->content,
            'short_description' => $request->short_description,
        ]);
        if ($page) {
            toastr()->success(admin_lang('Created Successfully'));
            return redirect()->route('admin.settings.pages.edit', $page->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('backend.settings.pages.edit', ['page' => $page]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $validator = Validator::make($request->all(), [
            'lang' => ['required', 'string', 'max:3', 'exists:languages,code'],
            'title' => ['required', 'max:255', 'min:2'],
            'content' => ['required', 'min:2'],
            'short_description' => ['required', 'max:200', 'min:2'],
            'slug' => ['required', 'alpha_dash', 'unique:pages,slug,' . $page->id],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $update = $page->update([
            'lang' => $request->lang,
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'short_description' => $request->short_description,
        ]);
        if ($update) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $page->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
