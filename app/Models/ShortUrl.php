<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['original_url', 'short_code'];

    // /**
    //  * The "booting" method of the model.
    //  *
    //  * @return void
    //  */
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($url) {
    //         $url->short_code = $url->generateShortCode();
    //     });
    // }

    // /**
    //  * Generate a unique short code for the URL.
    //  *
    //  * @return string
    //  */
    // protected function generateShortCode(): string
    // {
    //     return substr(md5(time() . uniqid()), 0, 6);
    // }
}
