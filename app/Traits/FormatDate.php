<?php

namespace App\Traits;

use Carbon\Carbon;

trait FormatDate
{

    /**
     * @param $date
     * @param string $format
     * @return string
     */
    public function formatDate($date, string $format = 'd.m.Y H:i'): string
    {
        if (!$date) {
            return '–';
        }

        return Carbon::parse($date)->format($format);
    }
}
