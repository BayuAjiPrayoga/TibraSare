<?php

if (!function_exists('format_currency')) {
    /**
     * Format number as Indonesian Rupiah.
     * e.g. format_currency(450000) => "Rp 450.000"
     */
    function format_currency(int|float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('format_date_id')) {
    /**
     * Format date to Indonesian locale.
     * e.g. format_date_id('2026-06-17') => "17 Juni 2026"
     */
    function format_date_id(string|\DateTimeInterface|null $date, string $format = 'j F Y'): string
    {
        if (!$date) return '-';
        return \Carbon\Carbon::parse($date)->translatedFormat($format);
    }
}

if (!function_exists('format_date_short')) {
    /**
     * Format date as short.
     * e.g. format_date_short('2026-06-17') => "17 Jun 2026"
     */
    function format_date_short(string|\DateTimeInterface|null $date): string
    {
        if (!$date) return '-';
        return \Carbon\Carbon::parse($date)->translatedFormat('j M Y');
    }
}

if (!function_exists('calculate_nights')) {
    /**
     * Calculate nights between two dates.
     */
    function calculate_nights(string|\DateTimeInterface $checkIn, string|\DateTimeInterface $checkOut): int
    {
        return max(1, \Carbon\Carbon::parse($checkIn)->diffInDays(\Carbon\Carbon::parse($checkOut)));
    }
}

if (!function_exists('get_initials')) {
    /**
     * Get initials from name.
     * e.g. get_initials('Ahmad Fauzi') => 'AF'
     */
    function get_initials(?string $name): string
    {
        if (!$name) return '?';
        $words = explode(' ', trim($name));
        $initials = '';
        foreach ($words as $word) {
            if (strlen($word) > 0) {
                $initials .= mb_strtoupper(mb_substr($word, 0, 1));
            }
        }
        return mb_substr($initials, 0, 2) ?: '?';
    }
}
