<?php

namespace Rahfan\PhpUtils;

class DateHelper
{
    public static function detectType(string $input): string
    {
        if (preg_match('/^\d{4}$/', $input)) {
            return 'tahun';
        }

        if (preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $input)) {
            return 'bulan';
        }

        if (preg_match('/^\d{4}-(0[1-9]|1[0-2])-([0-2][0-9]|3[0-1])$/', $input)) {
            return 'tanggal';
        }

        return 'tidak diketahui';
    }
}
