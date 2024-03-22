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
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['url']);
    }

    public function testDuplicateUrl()
    {
        $originalUrl = 'https://example.com';
        $shortUrl = ShortUrl::create([
            'original_url' => $originalUrl,
            'short_code' => 'abc123'
        ]);

        $response = $this->postJson('/api/shorten', ['url' => $originalUrl]);
        $response->assertStatus(200);
        $response->assertJson(['short_url' => url("/{$shortUrl->short_code}")]);
    }
}