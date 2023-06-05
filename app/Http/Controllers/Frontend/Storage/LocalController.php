<?php

namespace App\Http\Controllers\Frontend\Storage;

use App\Http\Controllers\Controller;
use Cache;
use Image;
use Storage;
use Str;

class LocalController extends Controller
{
    public static function upload($file)
    {
        try {
            $filename = Str::random(15) . '_' . time() . '.' . imageExtension();
            $location = "images/";
            $path = $location . $filename;
            $disk = Storage::disk('public');
            $upload = $disk->put($path, $file);
            if ($upload) {
                $data['filename'] = $filename;
                $data['path'] = $path;
                return responseHandler($data);
            }
        } catch (\Exception$e) {
            return null;
        }
    }

    public static function getFile($path)
    {
        $fullPath = Storage::disk('public')->path($path);
        $img = Image::cache(function ($image) use ($fullPath) {
            $image->make($fullPath);
        });
        $response = \Response::make($img, 200);
        $response->header("Content-Type", 'image/' . imageExtension());
        return $response;
    }

    public static function download($generatedImage)
    {
        try {
            $disk = Storage::disk('public');
            if ($disk->has($generatedImage->path)) {
                return $disk->download($generatedImage->path);
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    public static function delete($filePath)
    {
        $disk = Storage::disk('public');
        if ($disk->has($filePath)) {
            $disk->delete($filePath);
        }
        return true;
    }
}