<?php

namespace Rahfan\PhpUtils;

class NumberHelper
{
    public static function terbilang(int $angka): string
    {
        if ($angka === 0) {
            return 'nol';
        }

        return self::terbilangRecursive($angka);
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

}
