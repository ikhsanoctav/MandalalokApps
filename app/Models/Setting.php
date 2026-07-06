<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = ['key', 'value', 'group', 'type', 'label'];

    /**
     * Helper to get a setting value quickly.
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (! $setting) {
            return $default;
        }

        if ($setting->type === 'boolean') {
            return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
        }

        if ($setting->type === 'number') {
            return (float) $setting->value;
        }

        return $setting->value;
    }

    /**
     * Helper to set a setting value quickly.
     */
    public static function set(string $key, $value)
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            $setting->value = $value;
            $setting->save();

            return $setting;
        }

        return null;
    }
}
