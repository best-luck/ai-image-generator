<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Methods\ReCaptchaValidation;
use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class BlogController extends Controller
{
    public function index()
    {
        if (request()->has('search')) {
            $q = request('search');
            $blogArticles = BlogArticle::where([['title', 'like', '%' . $q . '%'], ['lang', getLang()]])
                ->OrWhere([['slug', 'like', '%' . $q . '%'], ['lang', getLang()]])
                ->OrWhere([['content', 'like', '%' . $q . '%'], ['lang', getLang()]])
                ->OrWhere([['short_description', 'like', '%' . $q . '%'], ['lang', getLang()]])
                ->orderbyDesc('id')
                ->paginate(8);
            $blogArticles->appends(['search' => $q]);
        } else {
            $blogArticles = BlogArticle::where('lang', getLang())->orderbyDesc('id')->paginate(8);
        }
        return view('frontend.blog.index', ['blogArticles' => $blogArticles]);
    }

    public function categories()
    {
        $blogCategories = BlogCategory::where('lang', getLang())->get();
        return view('frontend.blog.categories', ['blogCategories' => $blogCategories]);
    }

    public function category($slug)
    {
        $blogCategory = BlogCategory::where([['lang', getLang()], ['slug', $slug]])->first();
        if ($blogCategory) {
            $blogCategory->increment('views');
            $blogArticles = BlogArticle::where('category_id', $blogCategory->id)->orderbyDesc('id')->paginate(8);
            return view('frontend.blog.category', [
                'blogCategory' => $blogCategory,
                'blogArticles' => $blogArticles,
            ]);
        } else {
            return redirect()->route('blog.index');
        }
    }

    public function articles()
    {
        $blogArticles = BlogArticle::where('lang', getLang())->orderbyDesc('id')->paginate(9);
        return view('frontend.blog.articles', ['blogArticles' => $blogArticles]);
    }

    public function article($slug)
    {
        $blogArticle = BlogArticle::where([['lang', getLang()], ['slug', $slug]])->with('admin')->first();
        if ($blogArticle) {
            $blogArticle->increment('views');
            $blogArticleComments = BlogComment::where([['article_id', $blogArticle->id], ['status', 1]])->get();
            return view('frontend.blog.article', [
                'blogArticle' => $blogArticle,
                'blogArticleComments' => $blogArticleComments,
            ]);
        } else {
            return redirect()->route('blog.index');
        }
    }

    public function comment(Request $request, $slug)
    {
        if (!Auth::check()) {
            toastr()->error(lang('Login is required to post comments', 'blog'));
            return back();
        }
        $blogArticle = BlogArticle::where('slug', $slug)->with('admin')->firstOrFail();
        $validator = Validator::make($request->all(), [
            'comment' => ['required', 'string'],
        ] + ReCaptchaValidation::validate());
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        $comment = BlogComment::create([
            'user_id' => userAuthInfo()->id,
            'article_id' => $blogArticle->id,
            'comment' => $request->comment,
        ]);
        if ($comment) {
            $title = admin_lang('New comment waiting review');
            $image = asset('images/notifications/comment.png');
            $link = route('comments.index');
            adminNotify($title, $image, $link);
            toastr()->success(lang('Your comment is under review it will be published soon', 'blog'));
            return back();
        }
    }
}
