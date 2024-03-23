<?php

namespace App\Services;

use App\Exceptions\UrlNotSafeException;
use Illuminate\Support\Facades\Http;

/**
 * Service class for checking URL safety using the Google Safe Browsing API.
 *
 * This service provides functionality to check URLs against Google's Safe Browsing API
 * to determine if they are considered safe. It uses an API key for authentication with
 * the service and performs checks for specific types of threats like MALWARE and
 * SOCIAL_ENGINEERING for specified platform types.
 */
class GoogleSafeBrowsingService
{
    private $apiKey;

    /**
     * Constructor for the GoogleSafeBrowsingService.
     * Initializes the service with the API key from the environment configuration.
     */
    public function __construct()
    {
        $this->apiKey = env('SAFE_BROWSING_API_KEY');
    }

    /**
     * Checks if the provided URL is safe according to Google Safe Browsing.
     * 
     * Sends a POST request to the Google Safe Browsing API with the URL to be checked,
     * along with client and threat information. If the response indicates that the URL
     * is unsafe, it throws a UrlNotSafeException.
     *
     * @param string $url The URL to be checked for safety.
     * @throws UrlNotSafeException If the URL is found to be unsafe according to the API response.
     */
    public function urlIsSafe(string $url): void
    {
        $safeBrowsingResponse = Http::post("https://safebrowsing.googleapis.com/v4/threatMatches:find?key={$this->apiKey}", [
            'client' => [
                'clientId'      => 'Url Shortener',
                'clientVersion' => '1.0.0'
            ],
            'threatInfo' => [
                'threatTypes'      => ['MALWARE', 'SOCIAL_ENGINEERING'],
                'platformTypes'    => ['WINDOWS'],
                'threatEntryTypes' => ['URL'],
                'threatEntries'    => [
                    ['url' => $url]
                ]
            ]
        ]);

        if(!empty($safeBrowsingResponse->json())) {
            throw new UrlNotSafeException('URL is not safe according to Google Safe Browsing', 422);
        }
    }
}
