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

    public function store(Request $request)
    {
        try {
            $response = ShortUrlService::url($request->url)
            ->checkSafety()
            ->validate()
            ->findOrCreate('original_url');

            return response()->json([
                'short_url' => url("/{$response->short_code}")
            ]);
        } catch (UrlNotSafeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch(ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
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
