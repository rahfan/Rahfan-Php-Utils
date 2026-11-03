<?php

namespace Rahfan\PhpUtils;

use InvalidArgumentException;

class NumberHelper
{
    /**
     * Mengonversi angka menjadi sebutan kata (terbilang) dalam Bahasa Indonesia.
     * Mendukung bilangan bulat, negatif, desimal pecahan, dan opsional suffix kata (misal: "rupiah").
     */
    public static function terbilang(int|float $angka, string $suffix = ''): string
    {
        if ($angka === 0 || $angka === 0.0) {
            $hasil = 'nol';
        } else {
            $isNegative = $angka < 0;
            $abs = abs($angka);

            // Cek apakah angka memiliki pecahan desimal
            if (is_float($angka) && fmod($abs, 1) !== 0.0) {
                $intPart = (int) floor($abs);
                $intText = $intPart === 0 ? 'nol' : self::terbilangRecursive($intPart);

                // Ambil string desimal setelah titik
                $fractionStr = substr((string) $abs, strlen((string) $intPart) + 1);
                $digitWords = [
                    '0' => 'nol',
                    '1' => 'satu',
                    '2' => 'dua',
                    '3' => 'tiga',
                    '4' => 'empat',
                    '5' => 'lima',
                    '6' => 'enam',
                    '7' => 'tujuh',
                    '8' => 'delapan',
                    '9' => 'sembilan',
                ];

                $decimalWords = [];
                foreach (str_split($fractionStr) as $char) {
                    $decimalWords[] = $digitWords[$char] ?? '';
                }

                $hasil = $intText . ' koma ' . implode(' ', $decimalWords);
            } else {
                $hasil = self::terbilangRecursive((int) $abs);
            }

            if ($isNegative) {
                $hasil = 'minus ' . $hasil;
            }
        }

        if ($suffix !== '') {
            $hasil .= ' ' . trim($suffix);
        }

        return trim(preg_replace('/\s+/', ' ', $hasil));
    }

    private static function terbilangRecursive(int $angka): string
    {
        if ($angka === 0) {
            return '';
        }

        if ($angka < 0) {
            return 'minus ' . self::terbilangRecursive(abs($angka));
        }

        $huruf = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        $hasil = '';

        if ($angka < 12) {
            $hasil = $huruf[$angka];
        } elseif ($angka < 20) {
            $hasil = self::terbilangRecursive($angka - 10) . ' belas';
        } elseif ($angka < 100) {
            $hasil = self::terbilangRecursive(intval($angka / 10)) . ' puluh ' . self::terbilangRecursive($angka % 10);
        } elseif ($angka < 200) {
            $hasil = 'seratus ' . self::terbilangRecursive($angka - 100);
        } elseif ($angka < 1000) {
            $hasil = self::terbilangRecursive(intval($angka / 100)) . ' ratus ' . self::terbilangRecursive($angka % 100);
        } elseif ($angka < 2000) {
            $hasil = 'seribu ' . self::terbilangRecursive($angka - 1000);
        } elseif ($angka < 1000000) {
            $hasil = self::terbilangRecursive(intval($angka / 1000)) . ' ribu ' . self::terbilangRecursive($angka % 1000);
        } elseif ($angka < 1000000000) {
            $hasil = self::terbilangRecursive(intval($angka / 1000000)) . ' juta ' . self::terbilangRecursive($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $hasil = self::terbilangRecursive(intval($angka / 1000000000)) . ' miliar ' . self::terbilangRecursive($angka % 1000000000);
        } elseif ($angka < 1000000000000000) {
            $hasil = self::terbilangRecursive(intval($angka / 1000000000000)) . ' triliun ' . self::terbilangRecursive($angka % 1000000000000);
        } elseif ($angka < 1000000000000000000) {
            $hasil = self::terbilangRecursive(intval($angka / 1000000000000000)) . ' kuadriliun ' . self::terbilangRecursive($angka % 1000000000000000);
        } else {
            $hasil = self::terbilangRecursive(intval($angka / 1000000000000000000)) . ' kuintiliun ' . self::terbilangRecursive($angka % 1000000000000000000);
        }

        return trim(preg_replace('/\s+/', ' ', $hasil));
    }

    /**
     * Memformat angka menjadi format mata uang Rupiah.
     * Contoh output: "Rp 1.500.000", "Rp 1.500.000,50", atau tanpa simbol.
     */
    public static function formatRupiah(
        int|float $angka,
        bool $withSymbol = true,
        int $decimals = 0
    ): string {
        $isNegative = $angka < 0;
        $abs = abs($angka);
        $formatted = number_format($abs, $decimals, ',', '.');

        if ($withSymbol) {
            return $isNegative ? "-Rp {$formatted}" : "Rp {$formatted}";
        }

        return $isNegative ? "-{$formatted}" : $formatted;
    }

    /**
     * Memformat angka menjadi representasi ringkas/pendek (contoh: 1,5 rb / 1.5K, 2,5 jt / 2.5M).
     *
     * @param string $style 'id' untuk rb/jt/M/T, atau 'intl' untuk K/M/B/T
     */
    public static function formatRingkas(
        int|float $angka,
        int $precision = 1,
        string $style = 'id'
    ): string {
        $isNegative = $angka < 0;
        $abs = abs($angka);

        $tiers = $style === 'intl' ? [
            1_000_000_000_000 => 'T',
            1_000_000_000 => 'B',
            1_000_000 => 'M',
            1_000 => 'K',
        ] : [
            1_000_000_000_000 => ' T',
            1_000_000_000 => ' M',
            1_000_000 => ' jt',
            1_000 => ' rb',
        ];

        foreach ($tiers as $threshold => $suffix) {
            if ($abs >= $threshold) {
                $val = $abs / $threshold;
                $decSep = $style === 'intl' ? '.' : ',';
                $thousandSep = $style === 'intl' ? ',' : '.';
                $formatted = number_format($val, $precision, $decSep, $thousandSep);

                // Hapus angka 0 tidak signifikan di akhir desimal
                if ($precision > 0) {
                    $parts = explode($decSep, $formatted);
                    $decimals = rtrim($parts[1] ?? '', '0');
                    $formatted = $decimals !== '' ? $parts[0] . $decSep . $decimals : $parts[0];
                }

                $result = $formatted . $suffix;
                return $isNegative ? "-{$result}" : $result;
            }
        }

        $decSep = $style === 'intl' ? '.' : ',';
        $formatted = is_float($angka) && fmod($abs, 1) !== 0.0
            ? number_format($abs, $precision, $decSep, '')
            : (string) (int) $abs;

        return $isNegative ? "-{$formatted}" : $formatted;
    }

    /**
     * Mengonversi bilangan bulat (1 - 3999) menjadi angka Romawi.
     */
    public static function toRoman(int $angka): string
    {
        if ($angka < 1 || $angka > 3999) {
            throw new InvalidArgumentException("Angka romawi hanya mendukung nilai antara 1 sampai 3999. Diberikan: {$angka}");
        }

        $map = [
            1000 => 'M',
            900 => 'CM',
            500 => 'D',
            400 => 'CD',
            100 => 'C',
            90 => 'XC',
            50 => 'L',
            40 => 'XL',
            10 => 'X',
            9 => 'IX',
            5 => 'V',
            4 => 'IV',
            1 => 'I',
        ];

        $roman = '';
        foreach ($map as $value => $numeral) {
            while ($angka >= $value) {
                $roman .= $numeral;
                $angka -= $value;
            }
        }

        return $roman;
    }

    /**
     * Mengonversi string angka Romawi menjadi bilangan bulat.
     */
    public static function fromRoman(string $roman): int
    {
        $roman = strtoupper(trim($roman));

        if ($roman === '' || !preg_match('/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/', $roman)) {
            throw new InvalidArgumentException("Format angka romawi tidak valid: '{$roman}'");
        }

        $values = [
            'I' => 1,
            'V' => 5,
            'X' => 10,
            'L' => 50,
            'C' => 100,
            'D' => 500,
            'M' => 1000,
        ];

        $result = 0;
        $len = strlen($roman);

        for ($i = 0; $i < $len; $i++) {
            $curr = $values[$roman[$i]] ?? 0;
            $next = ($i + 1 < $len) ? ($values[$roman[$i + 1]] ?? 0) : 0;

            if ($curr < $next) {
                $result -= $curr;
            } else {
                $result += $curr;
            }
        }

        return $result;
    }

    /**
     * Memformat angka menjadi bentuk persentase.
     *
     * @param bool $isDecimal Jika true, 0.755 akan diubah menjadi 75,5%. Jika false, 75.5 menjadi 75,5%.
     */
    public static function formatPercent(
        int|float $angka,
        int $decimals = 1,
        bool $isDecimal = true
    ): string {
        $value = $isDecimal ? $angka * 100 : $angka;
        $formatted = number_format($value, $decimals, ',', '.');
        return "{$formatted}%";
    }

    /**
     * Membersihkan dan mengubah string berformat angka/uang menjadi integer atau float.
     * Contoh: "Rp 1.500.000,50" -> 1500000.5
     */
    public static function parse(string $input): int|float
    {
        $trimmed = trim($input);
        if ($trimmed === '') {
            throw new InvalidArgumentException("String angka tidak boleh kosong");
        }

        $isNegative = str_contains($trimmed, '-') || str_contains($trimmed, '(');

        $cleaned = preg_replace('/[^\d.,]/', '', $trimmed);
        if ($cleaned === '') {
            throw new InvalidArgumentException("Tidak ditemukan karakter numerik yang valid pada: '{$input}'");
        }

        $hasDot = str_contains($cleaned, '.');
        $hasComma = str_contains($cleaned, ',');

        if ($hasDot && $hasComma) {
            $lastDot = strrpos($cleaned, '.');
            $lastComma = strrpos($cleaned, ',');

            if ($lastComma > $lastDot) {
                // Format Indonesia: 1.500.000,50
                $cleaned = str_replace('.', '', $cleaned);
                $cleaned = str_replace(',', '.', $cleaned);
            } else {
                // Format US/Internasional: 1,500,000.50
                $cleaned = str_replace(',', '', $cleaned);
            }
        } elseif ($hasComma) {
            if (preg_match('/,\d{1,2}$/', $cleaned)) {
                $cleaned = str_replace(',', '.', $cleaned);
            } else {
                $cleaned = str_replace(',', '', $cleaned);
            }
        } elseif ($hasDot) {
            $dotsCount = substr_count($cleaned, '.');
            if ($dotsCount > 1 || preg_match('/\.\d{3}$/', $cleaned)) {
                $cleaned = str_replace('.', '', $cleaned);
            }
        }

        if (!is_numeric($cleaned)) {
            throw new InvalidArgumentException("Format angka tidak valid: '{$input}'");
        }

        $num = (float) $cleaned;
        if ($isNegative) {
            $num = -$num;
        }

        return fmod($num, 1) === 0.0 ? (int) $num : $num;
    }

    /**
     * Mengembalikan bentuk ordinal (tingkatan) angka.
     * Contoh: ordinal(1) -> "ke-1", ordinal(1, asWord: true) -> "pertama", ordinal(2, asWord: true) -> "kedua"
     */
    public static function ordinal(int $angka, bool $asWord = false): string
    {
        if ($angka < 1) {
            throw new InvalidArgumentException("Angka ordinal harus lebih besar atau sama dengan 1. Diberikan: {$angka}");
        }

        if (!$asWord) {
            return "ke-{$angka}";
        }

        if ($angka === 1) {
            return 'pertama';
        }

        return 'ke' . self::terbilang($angka);
    }
}
