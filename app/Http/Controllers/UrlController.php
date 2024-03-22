<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
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

        // Google Safe Browsing API logic

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
}
