<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GeneratedImage;
use App\Models\ImageSetting;
use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Redirect;

class GeneratedImageController extends Controller
{
    public function index()
    {
        $unviewedGeneratedImages = GeneratedImage::where('is_viewed', 0)->get();
        if ($unviewedGeneratedImages->count() > 0) {
            foreach ($unviewedGeneratedImages as $unviewedGeneratedImage) {
                $unviewedGeneratedImage->is_viewed = 1;
                $unviewedGeneratedImage->save();
            }
        }
        $widget['guests_images'] = GeneratedImage::guests()->notExpired()->count();
        $widget['users_images'] = GeneratedImage::users()->notExpired()->count();
        if (request()->has('search')) {
            $q = request()->input('search');
            $query = GeneratedImage::notExpired()->where(function ($query) use ($q) {
                $query->whereRaw("MATCH(prompt) AGAINST(? IN BOOLEAN MODE)", [$q]);
            });
        } else {
            $query = GeneratedImage::notExpired();
        }
        if (request()->has('user')) {
            $query = $query->where('user_id', request('user'));
        }
        $generatedImages = $query->orderbyDesc('id')->paginate(20);
        $generatedImages->appends(['search' => $q ?? '']);
        return view('backend.Images.index', ['generatedImages' => $generatedImages, 'widget' => $widget]);
    }

    public function edit(GeneratedImage $image)
    {
        return view('backend.Images.edit', ['generatedImage' => $image]);
    }

    public function update(Request $request, GeneratedImage $image)
    {
        $request->visibility = ($request->has('visibility')) ? 1 : 0;
        $image->visibility = $request->visibility;
        $image->update();
        toastr()->success(admin_lang('Updated Successfully'));
        return back();
    }

    public function download(GeneratedImage $image)
    {
        $handler = $image->storageProvider->handler;
        $download = $handler::download($image);
        $image->increment('downloads');
        if ($download) {
            if ($image->storageProvider->alias == "local") {
                return $download;
            } else {
                return redirect($download);
            }
        } else {
            toastr()->error(admin_lang('Download Error'));
            return back();
        }
    }

    public function nultiDelete(Request $request)
    {
        if (empty($request->delete_ids)) {
            toastr()->error(admin_lang('You have not selected any Image'));
            return back();
        }
        try {
            $ImagesIds = explode(',', $request->delete_ids);
            $totalDelete = 0;
            foreach ($ImagesIds as $ImagesId) {
                $generatedImage = GeneratedImage::where('id', $ImagesId)->notExpired()->first();
                if (!is_null($generatedImage)) {
                    $handler = $generatedImage->storageProvider->handler;
                    $handler::delete($generatedImage->path);
                    $generatedImage->delete();
                    $totalDelete += 1;
                }
            }
            if ($totalDelete != 0) {
                $countFiles = ($totalDelete > 1) ? $totalDelete . ' ' . admin_lang('Images') : $totalDelete . ' ' . admin_lang('Image');
                toastr()->success($countFiles . ' ' . admin_lang('deleted successfully'));
                return back();
            } else {
                toastr()->info(admin_lang('No files have been deleted'));
                return back();
            }
        } catch (\Exception$e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    public function destroy(GeneratedImage $image)
    {
        $handler = $image->storageProvider->handler;
        $delete = $handler::delete($image->path);
        $image->delete();
        toastr()->success(admin_lang('Deleted successfully'));
        return redirect()->route('admin.images.index');
    }

    public function settings(Request $request) 
    {
        if ($request->method() == "GET") {
            $image_settings = ImageSetting::get();
            return view("backend/Images/settings", ['image_settings' => $image_settings]);
        }

        $method = $request->request_method;
        if ($method == "POST") {
            $setting = new ImageSetting([
                'details' => '',
                'type' => '',
                'placeholder' => '',
                'visibility' => true,
                'categories' => '',
                'description' => ''
            ]);
            $setting->save();
        }

        if ($method == "PUT") {
            $fields = [
                'details',
                'type',
                'placeholder',
                'visibility',
                'categories',
                'description',
                'enabled'
            ];
            $id = $request->id;
            $setting = ImageSetting::where('id', $id)->first();

            foreach ($fields as $field) {
                if ($request->has($field) && $request->$field !== Null) {
                    $setting->$field = $request->$field;
                }
            }

            $setting->save();
        }

        if ($method == "DELETE") {
            $setting = ImageSetting::whereId($request->id)->delete();
        }
        return redirect("https://custominkstudio.com/admin/images/settings");
    }

    public function price(Request $request) {
        if ($request->method() == "GET") {
            $setting = Settings::selectSettings('image');
            return view("backend/Images/price", ['setting' => $setting]);
        } else if ($request->method() == "POST") {
            Settings::updateSettings('image', ['originalPrice' => $request->originalPrice, 'discountPrice' => $request->discountPrice, 'discountMessage' => $request->discountMessage]);
            return back();
        }
    }
}
