<?php

namespace App\Enums;

class UserTypes
{
    const PROVIDER = 0;
    const CLIENT = 1;

    public static function getText(int $const): string
    {
        switch ($const) {
            case self::PROVIDER:
                return __('Provider');
            case self::CLIENT:
                return __('Client');
            default:
                return __('Unknown');
        }
    }


    public static function getValue(int $user_type): int
    {
        switch ($user_type) {
            case self::PROVIDER:
                return self::PROVIDER;
            case self::CLIENT:
                return self::CLIENT;

            default:
                return 3;
        }
    }
}
