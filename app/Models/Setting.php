<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'category',
        'type',
        'description',
        'is_encrypted',
        'is_public',
        'sort_order',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
        'is_public' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            $value = $setting->is_encrypted 
                ? Crypt::decryptString($setting->value) 
                : $setting->value;

            // Type casting
            return match ($setting->type) {
                'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
                'integer' => (int) $value,
                'decimal', 'float' => (float) $value,
                'array' => json_decode($value, true) ?? [],
                default => $value,
            };
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, bool $encrypt = false): void
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return;
        }

        // Convert value to string for storage
        if (is_array($value)) {
            $value = json_encode($value);
        } elseif (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        // Encrypt if needed
        if ($encrypt || $setting->is_encrypted) {
            $value = Crypt::encryptString($value);
        }

        $setting->update(['value' => $value]);

        // Clear cache
        Cache::forget("setting.{$key}");
    }

    /**
     * Get all settings for a category
     */
    public static function getByCategory(string $category): array
    {
        return Cache::remember("settings.category.{$category}", 3600, function () use ($category) {
            return self::where('category', $category)
                ->orderBy('sort_order')
                ->get()
                ->mapWithKeys(function ($setting) {
                    $value = $setting->is_encrypted 
                        ? Crypt::decryptString($setting->value) 
                        : $setting->value;

                    // Type casting
                    $value = match ($setting->type) {
                        'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
                        'integer' => (int) $value,
                        'decimal', 'float' => (float) $value,
                        'array' => json_decode($value, true) ?? [],
                        default => $value,
                    };

                    return [$setting->key => $value];
                })
                ->toArray();
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}
