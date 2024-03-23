<?php

namespace App\Services;

use App\Models\ShortUrl;

/**
 * Service class responsible for handling the creation and retrieval of ShortUrl instances.
 *
 * This service offers functionality to ensure a URL is safe and conforms to validation
 * standards before attempting to find an existing ShortUrl record or create a new one. It
 * leverages a fluent interface to allow chaining of method calls for a streamlined process of
 * validating, checking the safety, and either retrieving or creating a ShortUrl instance based
 * on a given URL.
 */
class ShortUrlService
{
    private $url;
    private $safeBrowsingService;
    private $validatorService;

    /**
     * Constructor for the ShortUrlService class.
     * Initializes the GoogleSafeBrowsingService.
     */
    public function __construct()
    {
        $this->safeBrowsingService = new GoogleSafeBrowsingService();
    }

    /**
     * Static method to set the URL and instantiate the service.
     * 
     * @param string $url The URL to be shortened.
     * @return ShortUrlService An instance of this service with the URL set.
     */
    public static function url($url)
    {
        $instance = new self();
        $instance->url = $url;
        return $instance;
    }

    /**
     * Checks if the URL is safe to use by consulting the GoogleSafeBrowsingService.
     * 
     * @return $this Allows method chaining by returning the instance itself.
     */
    public function checkSafety()
    {
        $this->safeBrowsingService->urlIsSafe($this->url);
        return $this;
    }

    /**
     * Validates the URL format and conformity to certain standards.
     * 
     * @return $this Allows method chaining by returning the instance itself.
     */
    public function validate()
    {
        ShortUrlValidatorService::validate(['url' => $this->url]);
        return $this;
    }

    /**
     * Finds an existing ShortUrl model by a specified column value or creates a new one with the provided URL.
     * 
     * @param string $column The database column to search for the URL.
     * @return ShortUrl The found or created ShortUrl model instance.
     */
    public function findOrCreate($column)
    {
        $existingShortUrl = ShortUrl::where($column, $this->url)->first();

        if ($existingShortUrl) {
            return $existingShortUrl;
        }

        return ShortUrl::create(['original_url' => $this->url]);
    }
}
