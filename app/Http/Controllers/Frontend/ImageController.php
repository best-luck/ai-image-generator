<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Methods\OpenAIService;
use App\Http\Methods\StablediffusionService;
use App\Models\GeneratedImage;
use App\Models\ImageSetting;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\StorageProvider;
use App\Models\Payment;
use App\Models\Settings;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Str;

class ImageController extends Controller
{
    private $openAIService;
    private $stablediffusionService;

    public function __construct(OpenAIService $openAIService, StablediffusionService $stablediffusionService)
    {
        $this->openAIService = $openAIService;
        $this->stablediffusionService = $stablediffusionService;
    }

    public function index()
    {
        $generatedImages = GeneratedImage::public()
            ->notExpired()
            ->when(request()->has('q'), function ($query) {
                $q = request()->input('q');
                $query->whereRaw("MATCH(prompt) AGAINST(? IN BOOLEAN MODE)", [$q]);
            })
            ->when(request()->has('size'), function ($query) {
                $size = request()->input('size');
                $query->where('size', $size);
            })
            ->orderByDesc('id')
            ->paginate(32);
        return view('frontend.images.index', compact('generatedImages'));

    }

    public function secure($id, $filename)
    {
        $generatedImage = GeneratedImage::where('id', unhashid($id))->where('filename', $filename)->notExpired()->firstOrFail();
        $path = $generatedImage->path;
        if (!$generatedImage->canDownload())
            $path = "watermark/".$generatedImage->filename;
        return $generatedImage->storageProvider->handler::getFile($path);
    }

    public function generator(Request $request)
    {
        if (!subscription()->is_subscribed) {
            return response()->json(['error' => lang('You need to have an active subscription to start generating the images', 'home page')]);
        }
        if (!settings('ai_api')->api_key) {
            return response()->json(['error' => lang('API Key is missing', 'home page')]);
        }
        // $validator = Validator::make($request->all(), [
        //     'prompt' => ['required', 'string', 'max:255'],
        //     'samples' => ['required', 'integer', 'min:1', 'max:10'],
        //     'size' => ['required', Rule::in(subscription()->plan->sizes)],
        //     'visibility' => ['required', 'integer', 'min:0', 'max:1'],
        // ]);
        // if ($validator->fails()) {
        //     foreach ($validator->errors()->all() as $error) {
        //         return jsonError($error);
        //     }
        // }
        $image_settings = ImageSetting::all();
        $configs = ["Size", "Tattoo_Stencils_Generated"];
        $params = [
            'model' => 'image-alpha-001',
        ];
        $prompt = $request->prompt??"";

        $visibility=$request->visibility??true;
        foreach ($image_settings as $setting) {
            if (!$setting->enabled) continue;

            $details = str_replace(" ", "_", $setting->details);
            $val = $setting->placeholder;
            if ($setting->type=="multi-select-field") {
                $categories = $setting->getCategoriesArray();
                $val = $categories[0];
            }
            $val = $request->has($details)?$request->$details:$val;

            if (in_array($details, $configs)) {
                if ($details == "Tattoo_Stencils_Generated") {
                    $params['num_images'] = intval($val);
                }
                else {
                    $params['size'] = $val;
                    $size = $val;
                }
            } else {
                if ($details == "Enter_the_details_of_the_tattoo_design")
                    $prompt = $val.$prompt;
                else
                    $prompt = $details.":".$val." ".$prompt;
            }
        }
        $params['prompt'] = $prompt;

        abort_if($request->samples > subscription()->plan->max_images, 403);
        if (subscription()->remaining_images < $request->samples) {
            if (Auth::user()) {
                return jsonError(lang('You have exceeded the limit, please upgrade your plan', 'home page'));
            } else {
                return jsonError(lang('You have exceeded the limit, please register', 'home page'));
            }
        }
        if (subscription()->plan->expiration) {
            $expiryAt = Carbon::now()->addDays(subscription()->plan->expiration);
        } else {
            $expiryAt = null;
        }
        try {
            if (settings('ai_api')->provider == "openai") {
                $images = $this->openAIService->generateImages($params);
            } elseif (settings('ai_api')->provider == "stablediffusion") {
                $images = $this->stablediffusionService->generateImages($prompt, $samples, $size);
            } else {
                $images = null;
            }
            if (!$images) {
                return jsonError(lang('Generation failed, please try again', 'home page'));
            }
            $storageProvider = StorageProvider::where('alias', env('FILESYSTEM_DRIVER'))->first();
            if (!$storageProvider) {
                return jsonError(lang('Storage provider error', 'storage'));
            }
            $generatedImages = [];
            $generated_id = sha1(Str::random(40) . time());
            foreach ($images as $key => $image) {
                $uploadResponse = $storageProvider->handler::upload(file_get_contents($image));

                // Generate WaterMark
                $originalImagePath = storage_path('app/public/'.$uploadResponse->path);
                $watermarkImagePath = storage_path("app/public/watermark/watermark.png");
                $redwatermarkImagePath = storage_path("app/public/watermark/red-watermark.png");
                $redWatermark = imagecreatefrompng($redwatermarkImagePath);

                $generatedWatermarkImagePath = storage_path("app/public/watermark/".$uploadResponse->filename);
                $sourceImage = imagecreatefrompng($originalImagePath);
                $watermark = imagecreatefrompng($watermarkImagePath);
                $image_width = imagesx($sourceImage);
                $image_height = imagesy($sourceImage);
                $width = $image_width*0.1;
                $height = $image_height*0.1;
                $redWidth = 703/1000*$image_width;
                $redHeight = 164/1000*$image_height;
                $watermark_width = imagesx($watermark);
                $watermark_height = imagesy($watermark);
                $redwatermark_width = imagesx($redWatermark);
                $redwatermark_height = imagesy($redWatermark);
                $row = 5;
                $col = 5;
                $gap_x = ($image_width - $width) / ($row - 1);
                $gap_y = ($image_height - $height) / ($col - 1);

                for ($i = 0; $i < 5; $i++) {
                    for ($j = 0; $j < 5; $j++) {
                        $x = $i * $gap_x;
                        $y = $j * $gap_y;
                        imagecopyresampled($sourceImage, $watermark, $x, $y, 0, 0, $width, $height, $watermark_width, $watermark_height);        
                    }
                }
                
                imagecopyresampled($sourceImage, $redWatermark, $image_width - $redWidth, ($image_height / 2 - $redHeight / 2), 0, 0, $redWidth, $redHeight, $redwatermark_width, $redwatermark_height);
                imagejpeg($sourceImage, $generatedWatermarkImagePath);

                if (!$uploadResponse) {
                    return jsonError(lang('Generation failed, please try again', 'home page'));
                }
                $userId = Auth::user() ? Auth::user()->id : null;
                $generatedImage = GeneratedImage::create([
                    'user_id' => $userId,
                    'storage_provider_id' => $storageProvider->id,
                    'ip' => ipInfo()->ip,
                    'prompt' => $prompt,
                    'size' => $size,
                    'filename' => $uploadResponse->filename,
                    'path' => $uploadResponse->path,
                    'expiry_at' => $expiryAt,
                    'visibility' => $visibility,
                    'generated_id' => $generated_id
                ]);
                if ($generatedImage) {
                    if (Auth::user()) {
                        Auth::user()->subscription->increment('generated_images');
                    }
                    $generatedImages[$key]['prompt'] = $generatedImage->prompt;
                    $generatedImages[$key]['src'] = route('images.secure', [hashid($generatedImage->id), $generatedImage->filename]);
                    $generatedImages[$key]['link'] = route('images.show', hashid($generatedImage->id));
                    $generatedImages[$key]['download_link'] = route('images.download', [hashid($generatedImage->id), $generatedImage->filename]);
                }
            }
            return response()->json(['images' => $generatedImages]);
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function show($id)
    {
        $generatedImage = GeneratedImage::where('id', unhashid($id))->notExpired()->firstOrFail();
        if ($generatedImage->isPrivate()) {
            abort_if(auth()->user() && auth()->user()->id != $generatedImage->user_id, 404);
            abort_if(!auth()->user() && $generatedImage->user_id, 404);
            abort_if(!auth()->user() && $generatedImage->ip != ipInfo()->ip, 404);
        }
        $generatedImage->increment('views');
        $setting = Settings::selectSettings('image');
        return view('frontend.images.show', ['generatedImage' => $generatedImage, 'setting' => $setting]);
    }

    public function download($id, $filename)
    {
        $generatedImage = GeneratedImage::where('id', unhashid($id))->where('filename', $filename)->notExpired()->firstOrFail();
        if ($generatedImage->isPrivate()) {
            abort_if(auth()->user() && auth()->user()->id != $generatedImage->user_id, 404);
            abort_if(!auth()->user() && $generatedImage->user_id, 404);
            abort_if(!auth()->user() && $generatedImage->ip != ipInfo()->ip, 404);
        }
        // if (!$this->authorizedUrl(route('images.show', hashid($generatedImage->id)))) {
        //     return redirect()->route('images.show', hashid($generatedImage->id));
        // }

        $user = Auth::user();
        $id = 4;
        $price = $generatedImage->price();
        $plan = Plan::findOrFail($id);
        $tax = ($price * countryTax($user->address->country ?? ipinfo()->location->country)) / 100;
        $total = ($price + $tax);

        if (!$generatedImage->canDownload()) {
            $detailsBeforeDiscount = ['price' => priceFormt($plan->price), 'tax' => priceFormt($tax), 'total' => priceFormt($total)];
            $checkoutId = sha1(Str::random(40) . time());
            $payment = Payment::create([
                'checkout_id' => $checkoutId,
                'user_id' => $user ? $user->id : -1,
                'image_id' => $generatedImage->id,
                'price' => $price,
                'tax' => $tax,
                'total' => $total,
                'ip' => ipinfo()->ip,
                'details_before_discount' => $detailsBeforeDiscount
            ]);

            if ($payment) {
                return redirect()->route('checkout.index', $payment->checkout_id);
            }
            return back();
        }

        $handler = $generatedImage->storageProvider->handler;

        $download = $handler::download($generatedImage);
        $generatedImage->increment('downloads');
        if ($download) {
            if ($generatedImage->storageProvider->alias == "local") {
                return $download;
            } else {
                return redirect($download);
            }
        } else {
            toastr()->error(lang('Download Error', 'image page'));
            return back();
        }
    }

    private function authorizedUrl($url)
    {
        $referer = request()->server('HTTP_REFERER');
        if ($referer && filter_var($referer, FILTER_VALIDATE_URL) !== false) {
            $referer = parse_url($referer);
            $url = parse_url($url);
            if ($url['host'] == $referer['host']) {
                return true;
            }
        }
        return false;
    }
}
