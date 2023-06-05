<?php

namespace App\Http\Controllers\Backend\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\Language;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Validator;

class ArticleController extends Controller
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
            $articles = BlogArticle::where('lang', $language->code)->with(['blogCategory', 'admin', 'language'])->withCount('comments')->get();
            return view('backend.blog.articles.index', ['articles' => $articles, 'active' => $language->name]);
        } else {
            return redirect(url()->current() . '?lang=' . env('DEFAULT_LANGUAGE'));
        }
    }

    /**
     * Create a blog article slug using ajax request
     *
     * @return \Illuminate\Http\Response
     */
    public function slug(Request $request)
    {

        $slug = null;
        if ($request->content != null) {
            $slug = SlugService::createSlug(BlogArticle::class, 'slug', $request->content);
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
        $categories = BlogCategory::orderbyDesc('id')->get();
        return view('backend.blog.articles.create', ['categories' => $categories]);
    }

    /**
     * Get categories by lang
     *
     * @return \Illuminate\Http\Response as JSON
     */
    public function getCategories($lang)
    {
        $categories = BlogCategory::where('lang', $lang)->pluck("name", "id");
        if ($categories->count() > 0) {
            return response()->json($categories);
        } else {
            return response()->json(['info' => admin_lang('No categories on this language')]);
        }
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
            'title' => ['required', 'string', 'max:255', 'min:2'],
            'slug' => ['required', 'unique:blog_articles', 'alpha_dash'],
            'image' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'category' => ['required', 'numeric'],
            'content' => ['required'],
            'short_description' => ['required', 'string', 'max:200', 'min:2'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        $category = BlogCategory::where('id', $request->category)->first();
        if ($category == null) {
            toastr()->error(admin_lang('Something went wrong please try again'));
            return back();
        }
        $lang = Language::where('code', $request->lang)->first();
        if ($lang == null) {
            toastr()->error(admin_lang('Language not exists'));
            return back();
        }
        if ($category->lang != $lang->code) {
            toastr()->error(admin_lang('Category and article must be in the same language'));
            return back();
        }
        $image = imageUpload($request->file('image'), 'images/blog/articles/', '1280x720');
        if ($image) {
            $create = BlogArticle::create([
                'lang' => $lang->code,
                'admin_id' => adminAuthInfo()->id,
                'title' => $request->title,
                'slug' => SlugService::createSlug(BlogArticle::class, 'slug', $request->title),
                'image' => $image,
                'category_id' => $category->id,
                'content' => $request->content,
                'short_description' => $request->short_description,
            ]);
            if ($create) {
                toastr()->success(admin_lang('Created Successfully'));
                return redirect(route('articles.index') . '?lang=' . $create->lang);
            }
        } else {
            toastr()->error(admin_lang('Upload error'));
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlogArticle  $article
     * @return \Illuminate\Http\Response
     */
    public function show(BlogArticle $article)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlogArticle  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(BlogArticle $article)
    {
        $categories = BlogCategory::where('lang', $article->lang)->orderbyDesc('id')->get();
        return view('backend.blog.articles.edit', ['article' => $article, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlogArticle  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlogArticle $article)
    {
        $validator = Validator::make($request->all(), [
            'lang' => ['required', 'string', 'max:3'],
            'title' => ['required', 'string', 'max:255', 'min:2'],
            'slug' => ['required', 'alpha_dash', 'unique:blog_articles,slug,' . $article->id],
            'image' => ['mimes:png,jpg,jpeg', 'max:2048'],
            'category' => ['required', 'numeric'],
            'content' => ['required'],
            'short_description' => ['required', 'string', 'max:200', 'min:2'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $category = BlogCategory::where('id', $request->category)->first();
        if ($category == null) {
            toastr()->error(admin_lang('Something went wrong please try again'));
            return back();
        }
        $lang = Language::where('code', $request->lang)->first();
        if ($lang == null) {
            toastr()->error(admin_lang('Language not exists'));
            return back();
        }
        if ($category->lang != $lang->code) {
            toastr()->error(admin_lang('Category and article must be in the same language'));
            return back();
        }
        if ($request->has('image')) {
            $image = imageUpload($request->file('image'), 'images/blog/articles/', '1280x720', null, $article->image);
        } else {
            $image = $article->image;
        }
        if ($image) {
            $update = $article->update([
                'lang' => $lang->code,
                'title' => $request->title,
                'slug' => $request->slug,
                'image' => $image,
                'category_id' => $category->id,
                'content' => $request->content,
                'short_description' => $request->short_description,
            ]);
            if ($update) {
                toastr()->success(admin_lang('Updated Successfully'));
                return back();
            }
        } else {
            toastr()->error(admin_lang('Upload error'));
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlogArticle  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogArticle $article)
    {
        removeFile($article->image);
        $article->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
