<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleSafeBrowsingService
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = "AIzaSyCd6WM-hmRQ7_QS62dBPeZW5AjCUhcPnos";
    }

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
            throw new \Exception('URL is not safe according to Google Safe Browsing', 422);
        }
    }
}