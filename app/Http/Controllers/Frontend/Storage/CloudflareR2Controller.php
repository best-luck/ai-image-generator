<?php

namespace App\Http\Controllers\Frontend\Storage;

use App\Http\Controllers\Controller;
use Cache;
use Storage;

class CloudflareR2Controller extends Controller
{
    public static function setCredentials($data)
    {
        setEnv('CR2_ACCESS_KEY_ID', $data->credentials->access_key_id);
        setEnv('CR2_SECRET_ACCESS_KEY', $data->credentials->secret_access_key);
        setEnv('CR2_BUCKET', $data->credentials->bucket);
        setEnv('CR2_ENDPOINT', $data->credentials->endpoint);
    }

    public static function upload($file)
    {
        try {
            $filename = Str::random(15) . '_' . time() . '.' . imageExtension();
            $location = "images/";
            $path = $location . $filename;
            $disk = Storage::disk('cloudflare');
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
        $fullPath = Storage::disk('cloudflare')->get($path);
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
            $disk = Storage::disk('cloudflare');
            $filePath = $disk->path($generatedImage->path);
            if ($disk->has($filePath)) {
                return $disk->temporaryUrl($filePath, now()->addHour(), [
                    'ResponseContentDisposition' => 'attachment; filename="' . $generatedImage->filename . '"',
                ]);
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    public static function delete($filePath)
    {
        $disk = Storage::disk('cloudflare');
        if ($disk->has($filePath)) {
            $disk->delete($filePath);
        }
        return true;
    }
}