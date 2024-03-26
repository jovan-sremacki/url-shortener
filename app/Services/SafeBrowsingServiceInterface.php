<?php

namespace App\Services;

interface SafeBrowsingServiceInterface
{
    public function urlIsSafe(string $url): void;
}
