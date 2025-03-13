<?php

namespace App\Helpers;

class FormatHelper
{
    public static function formatAmount($amount)
    {
        return '₹' . number_format($amount, 2);
    }

    public static function cleanNonPrintableChars($string) {
        return preg_replace('/[\x00-\x1F\x7F]/', '', $string);  // Remove non-printable characters
    }
}