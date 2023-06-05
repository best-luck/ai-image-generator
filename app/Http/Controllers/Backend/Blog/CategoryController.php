<?php

namespace App\Http\Controllers\Backend\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\Language;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
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
            $categories = BlogCategory::where('lang', $language->code)->with('language')->get();
            return view('backend.blog.categories.index', ['categories' => $categories, 'active' => $language->name]);
        } else {
            return redirect(url()->current() . '?lang=' . env('DEFAULT_LANGUAGE'));
        }
    }

    /**
     * Create a blog category slug using ajax request
     *
     * @return \Illuminate\Http\Response
     */
    public function slug(Request $request)
    {
        $slug = null;
        if ($request->content != null) {
            $slug = SlugService::createSlug(BlogCategory::class, 'slug', $request->content);
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
        return view('backend.blog.categories.create');
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
            'lang' => ['required', 'string', 'max:3'],
            'name' => ['required', 'max:255', 'min:2'],
            'slug' => ['required', 'unique:blog_categories', 'alpha_dash'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        $lang = Language::where('code', $request->lang)->first();
        if ($lang == null) {
            toastr()->error(admin_lang('Language not exists'));
            return back();
        }
        $create = BlogCategory::create([
            'lang' => $lang->code,
            'name' => $request->name,
            'slug' => SlugService::createSlug(BlogCategory::class, 'slug', $request->name),
        ]);
        if ($create) {
            toastr()->success(admin_lang('Created Successfully'));
            return redirect(route('categories.index') . '?lang=' . $create->lang);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlogCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function show(BlogCategory $category)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlogCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(BlogCategory $category)
    {
        return view('backend.blog.categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlogCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlogCategory $category)
    {
        $validator = Validator::make($request->all(), [
            'lang' => ['required', 'string', 'max:3'],
            'name' => ['required', 'max:255', 'min:2'],
            'slug' => ['required', 'alpha_dash', 'unique:blog_categories,slug,' . $category->id],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $lang = Language::where('code', $request->lang)->first();
        if ($lang == null) {
            toastr()->error(admin_lang('Language not exists'));
            return back();
        }
        $updateCategory = $category->update([
            'lang' => $lang->code,
            'name' => $request->name,
            'slug' => $request->slug,
        ]);
        if ($updateCategory) {
            $articles = BlogArticle::where('category_id', $category->id)->get();
            if ($articles->count() > 0) {
                foreach ($articles as $article) {
                    $article->update(['lang' => $category->lang]);
                }
            }
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlogCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogCategory $category)
    {
        $articles = BlogArticle::where('category_id', $category->id)->get();
        if ($articles->count() >= 1) {
            foreach ($articles as $article) {
                removeFile($article->image);
            }
        }
        $category->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
