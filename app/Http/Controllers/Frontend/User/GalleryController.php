<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\GeneratedImage;
use Illuminate\Http\Request;
use Validator;

class GalleryController extends Controller
{
    public function index()
    {
        if (request()->has('search')) {
            $q = request('search');
            $generatedImages = GeneratedImage::where('user_id', userAuthInfo()->id)
                ->notExpired()
                ->whereRaw("MATCH(prompt) AGAINST(? IN BOOLEAN MODE)", [$q])
                ->orderByDesc('id')
                ->paginate(20);
            $generatedImages->appends(['search' => $q]);
        } else {
            $generatedImages = GeneratedImage::where('user_id', userAuthInfo()->id)
                ->notExpired()
                ->orderByDesc('id')
                ->paginate(20);
        }
        $totalGeneratedImages = GeneratedImage::where('user_id', userAuthInfo()->id)->count();
        return view('frontend.user.gallery.index', ['generatedImages' => $generatedImages, 'totalGeneratedImages' => $totalGeneratedImages]);
    }

    public function update(Request $request, $id)
    {
        $generatedImage = GeneratedImage::where('user_id', userAuthInfo()->id)->where('id', unhashid($id))->notExpired()->firstOrFail();
        $validator = Validator::make($request->all(), [
            'visibility' => ['required', 'integer', 'min:0', 'max:1'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                return jsonError($error);
            }
        }
        $generatedImage->update(['visibility' => $request->visibility]);
        toastr()->success(lang('Updated successfully', 'gallery'));
        return back();
    }

    public function destroy($id)
    {
        $generatedImage = GeneratedImage::where('user_id', userAuthInfo()->id)->where('id', unhashid($id))->notExpired()->firstOrFail();
        $handler = $generatedImage->storageProvider->handler;
        $delete = $handler::delete($generatedImage->path);
        $generatedImage->delete();
        toastr()->success(lang('Deleted successfully', 'gallery'));
        return redirect()->route('user.gallery.index');
    }
}
