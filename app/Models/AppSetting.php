<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        if ($setting->type === 'boolean') {
            return (bool) $setting->value;
        }

        if ($setting->type === 'integer') {
            return (int) $setting->value;
        }

        return $setting->value;
    }

    public static function bool(string $key, bool $default = false): bool
    {
        return (bool) self::getValue($key, $default);
    }

    public static function setValue(string $key, mixed $value, string $type = 'string'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => (string) $value,
                'type' => $type,
            ]
        );
    }
}