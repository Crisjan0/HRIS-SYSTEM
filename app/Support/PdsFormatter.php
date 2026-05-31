<?php

namespace App\Support;

use Carbon\Carbon;

class PdsFormatter
{
    public static function date(mixed $value, string $fallback = ''): string
    {
        if (blank($value)) {
            return $fallback;
        }

        try {
            return Carbon::parse($value)->format('m/d/Y');
        } catch (\Throwable) {
            return (string) $value;
        }
    }

    public static function val(mixed $value, string $fallback = 'N/A'): string
    {
        return filled($value) ? (string) $value : $fallback;
    }

    public static function check(?string $value, string $expected): string
    {
        return strcasecmp((string) $value, $expected) === 0 ? '☑' : '☐';
    }
}
