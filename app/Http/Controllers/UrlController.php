<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Exceptions\UrlNotSafeException;
use App\Services\ShortUrlService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UrlController extends Controller
{
    public function __construct(ShortUrlService $shortUrlService)
    {
        $this->shortUrlService = $shortUrlService;
    }

    // Method to handle the storing (creation) of a new short URL.
    // It validates the URL for safety and format before creating or finding the short URL.
    public function store(Request $request)
    {
        try {
            $shortUrl = $this->shortUrlService::url($request->url)
            ->checkUrlSafety()
            ->validateUrl()
            ->findOrCreate('original_url', 'url');

            return response()->json([
                'short_url' => url("/{$shortUrl->short_code}")
            ]);
        } catch (UrlNotSafeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch(ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Method to retrieve and redirect to the original URL based on the short code.
    // It validates the short code format before retrieving the original URL.
    public function show($code)
    {
        $shortUrl = $this->shortUrlService::code($code)
        ->validateCode()
        ->findOrCreate('short_code', 'short_code');

        if($shortUrl) {
            $shortUrl->increment('clicks');

            return response()->json([
                'redirect_url' => $shortUrl->original_url
            ]);
        }

        return response()->json([
            'redirect_url' => url('/')
        ]);
    }
}
