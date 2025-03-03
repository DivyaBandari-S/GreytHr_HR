<?php

namespace App\Helpers;

class FormatHelper
{
    public static function formatAmount($amount)
    {
        return '₹' . number_format($amount, 2);
    }
}