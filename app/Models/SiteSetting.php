<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function logoUrl(): ?string
    {
        $logo = static::get('logo');
        if ($logo && file_exists(public_path('uploads/' . $logo))) {
            return asset('uploads/' . $logo);
        }
        if (file_exists(public_path('uploads/otobi_logo.png'))) {
            return asset('uploads/otobi_logo.png');
        }
        return null;
    }

    public static function faviconUrl(): ?string
    {
        $fav = static::get('favicon');
        if ($fav && file_exists(public_path('uploads/' . $fav))) {
            return asset('uploads/' . $fav);
        }
        return null;
    }
}
