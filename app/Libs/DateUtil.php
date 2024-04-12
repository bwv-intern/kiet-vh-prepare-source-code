<?php

namespace App\Libs;

use Carbon\Carbon;

class DateUtil
{
    /**
     * Format date
     * @param string|object $date
     * @param string $format
     * @return string;
     */
    public static function formatDate($date, $format = 'Y/m/d') {
        $result = null;
        if (is_string($date)) {
            $carbon = new Carbon($date);
            $result = $carbon->format($format);
        }
        return $result;
    }

    /**
     * Get full date time
     * @return string
     */
    public static function parseStringFullDateTime() {
        return Carbon::now()->format('YmdHis');
    }

    /**
     * Validate a string with format
     * @param string $dateStr
     * @param string $format
     * @return bool
     */
    public static function validateDateTime($dateStr, $format = 'Y/m/d H:i:s') {
        $date = Carbon::createFromFormat($format, $dateStr);
        return $date && ($date->format($format) === $dateStr);
    }

    /**
     * convert date to dd/mm/yyyy format
     * @param string $dateStr
     * 
     * @return date formated
     */
    public static function convertDateFormat($date) {
        $carbonDate = Carbon::createFromFormat('d/m/Y', $date);

        return $carbonDate->format('Y/m/d');
    }
}
