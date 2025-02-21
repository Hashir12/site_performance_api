<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LightHouseService
{
    private $apiUrl = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';
    public function trackPerformance($data)
    {
        try {
            if (!isset($data['platform']) || !in_array($data['platform'], ['mobile', 'desktop'])) {
                throw new \Exception("Invalid platform. Choose 'mobile' or 'desktop'.");
            }

            $apiKey = config('services.pagespeed.key');
            if (!$apiKey) {
                throw new \Exception("Google PageSpeed API key is missing.");
            }

            $response = Http::get($this->apiUrl, [
                'key' => $apiKey,
                'url' => $data['websiteLink'],
                'strategy' => $data['platform'],
            ]);

            if ($response->failed()) {
                $statusCode = $response->status();
                $errorMessage = $response->json('error.message', 'Unknown error occurred.');
                throw new \Exception("API Request Failed with Status Code $statusCode: $errorMessage");
            }

            $jsonResponse = $response->json();


            $performanceScore = data_get($jsonResponse, 'lighthouseResult.categories.performance.score', null);

            if ($performanceScore === null) {
                throw new \Exception("Performance score not found in API response.");
            }

            return $performanceScore * 100;
        } catch (RequestException $e) {
            Log::error('Lighthouse API RequestException: ' . $e->getMessage());
            return ['error' => 'A network error occurred while contacting the API.'];
        } catch (\Exception $e) {
            Log::error('Lighthouse API Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
