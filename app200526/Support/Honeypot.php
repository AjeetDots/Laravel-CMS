<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Simple honeypot for public forms. Bots often fill every field; humans never see this input.
 */
class Honeypot
{
    public const FIELD = '_hp_company_url';

    public static function isTriggered(Request $request): bool
    {
        return filled($request->input(self::FIELD));
    }
}
