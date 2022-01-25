<?php

namespace Salt\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Setting extends Model
{
    /** @var string The name of the setting */
    public $name;

    /** @var string The data type of the settings value, e.g string, boolean, integer  */
    public $type;

    /** @var string The value of the setting */
    public $value;

    protected $guarded = [];

    /**
     * Add a new settings value
     *
     * @param string $key
     * @param mixed $value
     * @param string $type The data type of the settings value, e.g string, boolean, integer
     * @return mixed The value for the setting
     */
    public static function add(string $key, $value, string $type)
    {
        if (self::has($key)) {
            return self::set($key, $value, $type);
        }

        return self::create(['name' => $key, 'value' => $value, 'type' => $type]);
    }

    /**
     * Get the value for a setting
     *
     * @param string $key
     * @param mixed|null $default If the setting doesn't exist, this will be returned instead
     * @return mixed Either the value for the setting if found, or the default passed
     */
    public static function get(string $key, $default = null)
    {
        if (self::has($key)) {
            $setting = self::getAllSettings()->where('name', $key)->first();

            return self::castValue($setting->value, $setting->type);
        }

        return $default;
    }

    /**
     * Set a value for a setting
     *
     * @param string $key
     * @param mixed $value
     * @param string $type the data type of the settings value, e.g string, boolean, integer
     * @return mixed The value for the setting added
     */
    public static function set(string $key, $value, string $type)
    {
        if ($setting = self::getAllSettings()->where('name', $key)->first()) {
            return $setting->update([
                'name' => $key,
                'value' => $value,
                'type' => $type,
            ]) ? $value : false;
        }

        return self::add($key, $value, $type);
    }

    /**
     * Remove a setting
     *
     * @param string $key
     * @return bool|null
     */
    public static function remove(string $key): ?bool
    {
        if (self::has($key)) {
            return self::whereName($key)->delete();
        }

        return false;
    }

    /**
     * Check if a setting exists
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return (bool) self::getAllSettings()->whereStrict('name', $key)->count();
    }

    /**
     * Cast value into respective type
     *
     * @param mixed $value
     * @param string $castTo
     * @return bool|int|mixed
     */
    private static function castValue($value, string $castTo)
    {
        switch ($castTo) {
            case 'int':
            case 'integer':
                return intval($value);

            case 'bool':
            case 'boolean':
                return boolval($value);

            default:
                return $value;
        }
    }

    /**
     * Get a collection of all settings
     *
     * @return Collection
     */
    public static function getAllSettings(): Collection
    {
        return self::all()->each(function ($setting) {
            $setting->value = self::castValue($setting->value, $setting->type);

            return $setting;
        });
    }
}
