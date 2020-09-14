<?php

namespace WColognese\LaravelSupports\Useful;

abstract class Constant
{
    protected static $labels = array();

    public static function getConstants()
    {
        $reflectionClass = new \ReflectionClass(static::class);
        
        return $reflectionClass->getConstants();
    }

    public static function toLabel($value)
    {
        $constantsFlip = array_flip(self::getConstants());

        if(isset($constantsFlip[$value]) && isset(static::$labels[$constantsFlip[$value]]))
            return static::$labels[$constantsFlip[$value]];

        return NULL;
    }

    public static function getValues(): array
    {
        return array_values(self::getConstants());
    }

    public static function hasValue($value)
    {
        return in_array($value, self::getConstants(), true);
    }

    public static function hasKey(string $key)
    {
        return isset(self::getConstants()[$key]);
    }

    public static function toSelectOptions(): array
    {
        $options = array();

        foreach(self::getConstants() as $key => $value)
            $options[] = (object)['value' => $value, 'text' => self::toLabel($value) ?: $key];

        return $options;
    }
}