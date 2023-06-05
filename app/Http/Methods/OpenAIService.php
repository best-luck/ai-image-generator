<?php

namespace App\Http\Methods;

use OpenAI;

class OpenAIService
{
    private $client;

    public function __construct()
    {
        $this->client = OpenAI::client(settings('ai_api')->api_key);
    }

    public function generateImages($params)
    {
        try {
            if (settings('ai_api')->provider != "openai") {
                return null;
            }
            $response = $this->client->images()->create($params);
            $imageUrls = [];
            foreach ($response->data as $image) {
                $imageUrls[] = $image->url;
            }
            return $imageUrls;
        } catch (Exception $e) {
            return null;
        }
    }
}