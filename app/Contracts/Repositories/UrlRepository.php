<?php

namespace App\Contracts\Repositories;

use App\Models\Url;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UrlRepository implements UrlRepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param array $data
     * @return Url
     */
    public function create(array $data): Url
    {
        // Assumes 'short_code' is generated within the model's creating event or elsewhere
        return Url::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param string $shortCode
     * @return Url
     * @throws ModelNotFoundException
     */
    public function findByShortCode(string $shortCode): Url
    {
        $url = Url::where('short_code', $shortCode)->firstOrFail();
        return $url;
    }

    /**
     * Find URL by its original URL.
     *
     * @param string $originalUrl
     * @return Url|null
     */
    public function findByOriginalUrl(string $originalUrl): ?Url
    {
        return Url::where('original_url', $originalUrl)->first();
    }
}

