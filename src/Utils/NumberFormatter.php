<?php

namespace App\Utils;

class NumberFormatter
{
    /**
     * Format number to currency.
     *
     * @param float $number
     * @param string $currencySymbol
     * @param int $decimals
     * @return string
     */
    public static function toCurrency($number, $decimals = 2, $currencySymbol = 'Rp')
    {
        return $currencySymbol . ' ' . number_format($number, $decimals, ',', '.');
    }

    /**
     * Format number with thousands separator.
     *
     * @param float $number
     * @param int $decimals
     * @return string
     */
    public static function toReadable($number, $decimals = 2)
    {
        return number_format($number, $decimals, ',', '.');
    }

    /**
     * Format number to percentage.
     *
     * @param float $number
     * @param int $decimals
     * @return string
     */
    public static function toPercentage($number, $decimals = 2)
    {
        return number_format($number * 100, $decimals) . '%';
    }

    /**
     * Format number with fixed decimal places.
     *
     * @param float $number
     * @param int $decimals
     * @return string
     */
    public static function toFixed($number, $decimals = 0)
    {
        return number_format($number, $decimals, ',', '.');
    }

    /**
     * Format number with counted.
     *
     * @param float $number
     * @return string
     */
    public static function toCounted($number): string
    {
        return formatCounted($number) . ' Rupiah';
    }
}
