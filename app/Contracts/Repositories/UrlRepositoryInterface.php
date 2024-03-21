<?php

namespace App\Contracts\Repositories;

use App\Models\Url;

interface UrlRepositoryInterface
{
    public function create(array $data): Url;

    public function findByShortCode(string $shortCode): Url;

    public function findByOriginalUrl(string $originalUrl): ?Url;
}