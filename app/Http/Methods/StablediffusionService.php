<?php

namespace App\Http\Methods;

use Exception;
use GuzzleHttp\Client;

class StablediffusionService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://stablediffusionapi.com/api/v3/',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . settings('ai_api')->api_key,
            ],
        ]);
    }

    public function generateImages(string $prompt, int $numImages, string $size)
    {
        try {
            if (settings('ai_api')->provider != "stablediffusion") {
                return null;
            }
            $apiKey = settings('ai_api')->api_key;
            $size = explode('x', $size);
            $response = $this->client->post('text2img', [
                'json' => [
                    'key' => $apiKey,
                    'prompt' => $prompt,
                    'width' => $size[0],
                    'height' => $size[1],
                    'samples' => $numImages,
                    'num_inference_steps' => '20',
                    'guidance_scale' => 7.5,
                ],
            ]);
            $response = json_decode($response->getBody(), true);
            $imageUrls = null;
            if ($response['status'] == "success") {
                $imageUrls = $response['output'];
            } else {
                $retries = 0;
                while ($response['status'] == 'processing' && $retries < 50) {
                    sleep(5);
                    $fetchData = $this->client->post('fetch/' . $response['id'], [
                        'json' => [
                            'key' => $apiKey,
                        ],
                    ]);
                    $output = json_decode($fetchData->getBody(), true);
                    if ($output['status'] == 'success') {
                        $imageUrls = $output['output'];
                    }
                    $retries++;
                }
            }
            return $imageUrls;
        } catch (Exception $e) {
            return null;
        }
    }
}