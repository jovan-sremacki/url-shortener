<?php

namespace App\Services;

use App\Models\ShortUrl;
use App\Validators\ShortUrlValidator;
use App\Validators\ShortCodeValidator;

/**
 * Service class responsible for handling the creation and retrieval of ShortUrl instances.
 *
 */
class ShortUrlService
{
    private $url;
    private $short_code;
    private $safeBrowsingService;
    private $validatorService;

    /**
     * Constructor for the ShortUrlService class.
     * Initializes the GoogleSafeBrowsingService.
     */
    public function __construct(SafeBrowsingServiceInterface $safeBrowsingService)
    {
        $this->safeBrowsingService = $safeBrowsingService;
    }

    /**
     * Static method to set the URL and instantiate the service.
     * 
     * @param string $url The URL to be shortened.
     * @return ShortUrlService An instance of this service with the URL set.
     */
    public static function url($url)
    {
        $instance = app(self::class);
        $instance->url = $url;
        return $instance;
    }

    /**
     * Static method to set the code and instantiate the service.
     * 
     * @param string $code.
     * @return ShortUrlService An instance of this service with the code set.
     */
    public static function code($short_code)
    {
        $instance = app(self::class);
        $instance->short_code = $short_code;
        return $instance;
    }

    /**
     * Checks if the URL is safe to use by consulting the GoogleSafeBrowsingService.
     * 
     * @return $this Allows method chaining by returning the instance itself.
     */
    public function checkUrlSafety()
    {
        $this->safeBrowsingService->urlIsSafe($this->url);
        return $this;
    }

    /**
     * Validates the URL format and conformity to certain standards.
     * 
     * @return $this Allows method chaining by returning the instance itself.
     */
    public function validateUrl()
    {
        ShortUrlValidator::validate(['url' => $this->url]);
        return $this;
    }

    /**
     * Validates the URL format and conformity to certain standards.
     * 
     * @return $this Allows method chaining by returning the instance itself.
     */
    public function validateCode()
    {
        ShortCodeValidator::validate(['short_code' => $this->short_code]);
        return $this;
    }

    /**
     * Finds an existing ShortUrl model by a specified column value or creates a new one with the provided URL.
     * 
     * @param string $column The database column to search for the URL.
     * @return ShortUrl The found or created ShortUrl model instance.
     */
    public function findOrCreate($column, $property)
    {
        $existingShortUrl = ShortUrl::where($column, $this->$property)->first();

        if ($existingShortUrl) {
            return $existingShortUrl;
        }

        if ($this->url !== null) {
            return ShortUrl::create(['original_url' => $this->url]);
        }
    }
}
