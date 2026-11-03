<?php

namespace Rahfan\PhpUtils;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use InvalidArgumentException;

class DateHelper
{
    public const BULAN = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public const HARI = [
        'Sun' => 'Minggu',
        'Mon' => 'Senin',
        'Tue' => 'Selasa',
        'Wed' => 'Rabu',
        'Thu' => 'Kamis',
        'Fri' => 'Jumat',
        'Sat' => 'Sabtu',
    ];

    /**
     * Mendeteksi jenis/format string waktu/tanggal.
     */
    public static function detectType(string $input): string
    {
        // Deteksi datetime: YYYY-MM-DD HH:mm:ss atau YYYY-MM-DDTHH:mm:ss (opsional detik)
        if (preg_match('/^\d{4}-(0[1-9]|1[0-2])-([0-2][0-9]|3[0-1])[ T]([01][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $input)) {
            return 'datetime';
        }

        // Deteksi waktu/jam: HH:mm atau HH:mm:ss
        if (preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $input)) {
            return 'waktu';
        }

        // Deteksi tanggal ISO: YYYY-MM-DD
        if (preg_match('/^\d{4}-(0[1-9]|1[0-2])-([0-2][0-9]|3[0-1])$/', $input)) {
            return 'tanggal';
        }

        // Deteksi format tanggal lokal: DD/MM/YYYY atau DD-MM-YYYY
        if (preg_match('/^([0-2][0-9]|3[0-1])[\/-](0[1-9]|1[0-2])[\/-]\d{4}$/', $input)) {
            return 'tanggal';
        }

        // Deteksi bulan: YYYY-MM
        if (preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $input)) {
            return 'bulan';
        }

        // Deteksi tahun: YYYY
        if (preg_match('/^\d{4}$/', $input)) {
            return 'tahun';
        }

        return 'tidak diketahui';
    }

    /**
     * Mengecek apakah tahun merupakan tahun kabisat.
     */
    public static function isKabisat(int $tahun): bool
    {
        return ($tahun % 4 === 0 && $tahun % 100 !== 0) || ($tahun % 400 === 0);
    }

    /**
     * Memvalidasi apakah string tanggal valid sesuai kalender dan format yang diberikan.
     */
    public static function isValidDate(string $input, string $format = 'Y-m-d'): bool
    {
        $d = DateTime::createFromFormat($format, $input);
        return $d !== false && $d->format($format) === $input;
    }

    /**
     * Memformat tanggal ke dalam format teks Bahasa Indonesia.
     * Contoh output: "3 November 2026", "Selasa, 3 November 2026", atau dengan jam.
     */
    public static function formatIndo(
        string|DateTimeInterface $date,
        bool $showDay = false,
        bool $showTime = false
    ): string {
        $dt = self::parseDate($date);

        $dayNumber = (int) $dt->format('j');
        $monthNumber = (int) $dt->format('n');
        $year = $dt->format('Y');

        $monthName = self::BULAN[$monthNumber] ?? '';
        $formatted = "{$dayNumber} {$monthName} {$year}";

        if ($showDay) {
            $dayCode = $dt->format('D');
            $dayName = self::HARI[$dayCode] ?? '';
            $formatted = "{$dayName}, {$formatted}";
        }

        if ($showTime) {
            $formatted .= ' ' . $dt->format('H:i');
        }

        return $formatted;
    }

    /**
     * Menghitung selisih hari antara dua tanggal.
     */
    public static function selisihHari(
        string|DateTimeInterface $from,
        string|DateTimeInterface $to = 'now',
        bool $absolute = true
    ): int {
        $d1 = self::parseDate($from)->setTime(0, 0, 0);
        $d2 = self::parseDate($to)->setTime(0, 0, 0);

        $diff = $d1->diff($d2);
        $days = (int) $diff->format('%r%a');

        return $absolute ? abs($days) : $days;
    }

    /**
     * Mengembalikan teks relatif waktu dalam Bahasa Indonesia (contoh: "kemarin", "3 hari yang lalu", "besok").
     */
    public static function diffForHumans(
        string|DateTimeInterface $date,
        string|DateTimeInterface $reference = 'now'
    ): string {
        $from = self::parseDate($date);
        $ref = self::parseDate($reference);

        $diffSeconds = $ref->getTimestamp() - $from->getTimestamp();
        $isPast = $diffSeconds >= 0;
        $absSeconds = abs($diffSeconds);

        if ($absSeconds < 45) {
            return 'baru saja';
        }

        if ($absSeconds < 3600) {
            $minutes = max(1, (int) round($absSeconds / 60));
            return $isPast ? "{$minutes} menit yang lalu" : "{$minutes} menit lagi";
        }

        if ($absSeconds < 86400) {
            $hours = max(1, (int) round($absSeconds / 3600));
            return $isPast ? "{$hours} jam yang lalu" : "{$hours} jam lagi";
        }

        if ($absSeconds < 86400 * 30) {
            $days = (int) round($absSeconds / 86400);
            if ($days === 1) {
                return $isPast ? 'kemarin' : 'besok';
            }
            if ($days === 2 && !$isPast) {
                return 'lusa';
            }
            return $isPast ? "{$days} hari yang lalu" : "{$days} hari lagi";
        }

        if ($absSeconds < 86400 * 365) {
            $months = max(1, (int) round($absSeconds / (86400 * 30)));
            return $isPast ? "{$months} bulan yang lalu" : "{$months} bulan lagi";
        }

        $years = max(1, (int) round($absSeconds / (86400 * 365)));
        return $isPast ? "{$years} tahun yang lalu" : "{$years} tahun lagi";
    }

    /**
     * Parse input menjadi instance DateTimeImmutable.
     */
    private static function parseDate(string|DateTimeInterface $date): DateTimeImmutable
    {
        if ($date instanceof DateTimeImmutable) {
            return $date;
        }

        if ($date instanceof DateTime) {
            return DateTimeImmutable::createFromMutable($date);
        }

        try {
            return new DateTimeImmutable($date);
        } catch (Exception $e) {
            throw new InvalidArgumentException("Format tanggal tidak valid: '{$date}'", 0, $e);
        }
    }
}
