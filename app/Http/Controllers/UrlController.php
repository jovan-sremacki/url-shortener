<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Services\GoogleSafeBrowsingService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UrlController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'url' => 'required|url'
        ]);

        $originalUrl = $validatedData['url'];

        try {
            $safeBrowsingService = new GoogleSafeBrowsingService();
            $safeBrowsingService->urlIsSafe($originalUrl);
        } catch (\Exception $e) {
            return response()->json(['error' => 'URL is not safe according to Google Safe Browsing'], 422);        
        }

        // if($safeBrowsingResponse->json()) {
        //     return response()->json(['error' => 'URL is not safe according to Google Safe Browsing'], 422);
        // }

        $existingShortUrl = ShortUrl::where('original_url', $originalUrl)->first();

        if($existingShortUrl) {
            return response()->json([
                'short_url' => url("/{$existingShortUrl->short_code}")
            ]);
        }

        do {
            $hash = Str::random(6);
        } while (ShortUrl::where('short_code', $hash)->exists());

        $shortUrl = ShortUrl::create([
            'original_url' => $originalUrl,
            'short_code' => $hash
        ]);

        return response()->json([
            'short_url' => url("/{$shortUrl->short_code}")
        ]);
    }

    public function show($code)
    {
        $short_url = ShortUrl::where('short_code', $code)->first();

        if($short_url) {
            return response()->json([
                'redirect_url' => url("/{$short_url->original_url}")
            ]);
        }

        return response()->json([
            'redirect_url' => url('/')
        ]);
    }
}
