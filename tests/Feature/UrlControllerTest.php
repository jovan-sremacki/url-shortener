<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class UrlControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUrlShortening()
    {
        $response = $this->postJson('/api/shorten', ['url' => 'https://www.example.com']);
        $response->assertStatus(200);
        $response->assertJsonStructure(['short_url']);
    }

    public function testInvalidUrl()
    {
        $response = $this->postJson('/api/shorten', ['url' => 'not-a-valid-url']);
        $response->assertStatus(400);
        $response->assertJson(['error' => 'The url field must be a valid URL.']);
    }

    public function testDuplicateUrl()
    {
        $originalUrl = 'https://example.com';
        $shortUrl = ShortUrl::create([
            'original_url' => $originalUrl,
            'short_code' => 'ampoix'
        ]);

        $response = $this->postJson('/api/shorten', ['url' => $originalUrl]);
        $response->assertStatus(200);
        $response->assertJson(['short_url' => url("/{$shortUrl->short_code}")]);
    }

    public function testUnsafeUrl()
    {
        $apiKey = env('SAFE_BROWSING_API_KEY');
        Http::fake([
            "safebrowsing.googleapis.com/v4/threatMatches:find?key={$apiKey}" => Http::response([
                'error' => [
                    'code' => 400,
                    'message' => 'Invalid Value'
                ]
            ], 400),
        ]);

        $response = $this->postJson('/api/shorten', ['url' => 'https://unsafe-website.com']);
        $response->assertStatus(422);
        $response->assertJson(['error' => 'URL is not safe according to Google Safe Browsing']);
    }

    public function testUrlRedirection()
    {
        $shortUrl = ShortUrl::create([
            'original_url' => 'https://example.com',
            'short_code' => 'ampoix'
        ]);

        $response = $this->getJson("/api/{$shortUrl->short_code}");

        $response->assertStatus(200);
        $response->assertJson([
            'redirect_url' => $shortUrl->original_url
        ]);
    }

    public function testUrlRedirectionWithInvalidCode()
    {
        $response = $this->getJson('/api/ffzzaa');

        $response->assertStatus(200);
        $response->assertJson([
            'redirect_url' => url('/')
        ]);
    }

    public function testRedirectCodeIsNotSixCharactersLong()
    {
        $response = $this->getJson("/api/2123");

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }
}
