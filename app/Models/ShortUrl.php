<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShortUrl extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['original_url', 'short_code'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($url) {
            $maxAttempts = 100;
            $attempts = 0;

            do {
                if ($attempts >= $maxAttempts) {
                    throw new Exception("Could not generate a unique short code.");
                }

                $shortCode = Str::random(6);
                $attempts++;
            } while (ShortUrl::where('short_code', $shortCode)->exists());

            $url->short_code = $shortCode;
        });
    }
}
