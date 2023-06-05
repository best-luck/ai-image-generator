<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GeneratedImage;
use App\Models\ImageSetting;

class HomeController extends Controller
{
    public function index()
    {
        $generatedImages = GeneratedImage::public()->notExpired()->limit(9)->orderbyDesc('id')->get();
        $image_settings = ImageSetting::all();
        return view('frontend.home', ['generatedImages' => $generatedImages, 'image_settings' => $image_settings]);
    }
}