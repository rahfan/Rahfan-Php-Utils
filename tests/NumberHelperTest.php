<?php

namespace Rahfan\PhpUtils\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Rahfan\PhpUtils\NumberHelper;

class NumberHelperTest extends TestCase
{
    public function testZero()
    {
        $this->assertEquals('nol', NumberHelper::terbilang(0));
    }

    public function testSingleDigits()
    {
        $this->assertEquals('satu', NumberHelper::terbilang(1));
        $this->assertEquals('dua', NumberHelper::terbilang(2));
        $this->assertEquals('sembilan', NumberHelper::terbilang(9));
    }

    public function testTeensAndTens()
    {
        $this->assertEquals('sepuluh', NumberHelper::terbilang(10));
        $this->assertEquals('sebelas', NumberHelper::terbilang(11));
        $this->assertEquals('dua belas', NumberHelper::terbilang(12));
        $this->assertEquals('sembilan belas', NumberHelper::terbilang(19));
        $this->assertEquals('dua puluh', NumberHelper::terbilang(20));
        $this->assertEquals('dua puluh satu', NumberHelper::terbilang(21));
        $this->assertEquals('sembilan puluh sembilan', NumberHelper::terbilang(99));
    }

    public function testHundreds()
    {
        $this->assertEquals('seratus', NumberHelper::terbilang(100));
        $this->assertEquals('seratus satu', NumberHelper::terbilang(101));
        $this->assertEquals('seratus sepuluh', NumberHelper::terbilang(110));
        $this->assertEquals('seratus sebelas', NumberHelper::terbilang(111));
        $this->assertEquals('dua ratus', NumberHelper::terbilang(200));
        $this->assertEquals('sembilan ratus sembilan puluh sembilan', NumberHelper::terbilang(999));
    }

    public function testThousands()
    {
        $this->assertEquals('seribu', NumberHelper::terbilang(1000));
        $this->assertEquals('seribu satu', NumberHelper::terbilang(1001));
        $this->assertEquals('seribu seratus', NumberHelper::terbilang(1100));
        $this->assertEquals('dua ribu', NumberHelper::terbilang(2000));
        $this->assertEquals('sepuluh ribu', NumberHelper::terbilang(10000));
        $this->assertEquals('seratus ribu', NumberHelper::terbilang(100000));
        $this->assertEquals('sembilan ratus sembilan puluh sembilan ribu sembilan ratus sembilan puluh sembilan', NumberHelper::terbilang(999999));
    }

    public function testMillions()
    {
        $this->assertEquals('satu juta', NumberHelper::terbilang(1000000));
        $this->assertEquals('dua belas juta tiga ratus empat puluh lima ribu enam ratus tujuh puluh delapan', NumberHelper::terbilang(12345678));
    }

    public function testMiliarAndAbove()
    {
        $this->assertEquals('satu miliar', NumberHelper::terbilang(1000000000));
        $this->assertEquals('satu triliun', NumberHelper::terbilang(1000000000000));
        $this->assertEquals('satu kuadriliun', NumberHelper::terbilang(1000000000000000));
        $this->assertEquals('satu kuintiliun', NumberHelper::terbilang(1000000000000000000));
    }

    public function testNegativeNumbers()
    {
        $this->assertEquals('minus satu', NumberHelper::terbilang(-1));
        $this->assertEquals('minus seratus dua puluh tiga', NumberHelper::terbilang(-123));
    }

    public function testTerbilangWithSuffix()
    {
        $this->assertEquals('seribu rupiah', NumberHelper::terbilang(1000, 'rupiah'));
        $this->assertEquals('nol rupiah', NumberHelper::terbilang(0, 'rupiah'));
        $this->assertEquals('lima belas orang', NumberHelper::terbilang(15, 'orang'));
    }

    public function testTerbilangWithDecimals()
    {
        $this->assertEquals('lima belas koma lima', NumberHelper::terbilang(15.5));
        $this->assertEquals('nol koma dua lima', NumberHelper::terbilang(0.25));
        $this->assertEquals('minus satu koma lima', NumberHelper::terbilang(-1.5));
        $this->assertEquals('dua belas koma nol lima rupiah', NumberHelper::terbilang(12.05, 'rupiah'));
    }

    public function testFormatRupiah()
    {
        $this->assertEquals('Rp 1.500.000', NumberHelper::formatRupiah(1500000));
        $this->assertEquals('1.500.000', NumberHelper::formatRupiah(1500000, withSymbol: false));
        $this->assertEquals('Rp 1.500.000,50', NumberHelper::formatRupiah(1500000.5, decimals: 2));
        $this->assertEquals('-Rp 25.000', NumberHelper::formatRupiah(-25000));
        $this->assertEquals('-25.000', NumberHelper::formatRupiah(-25000, withSymbol: false));
        $this->assertEquals('Rp 0', NumberHelper::formatRupiah(0));
        $this->assertEquals('Rp 0,00', NumberHelper::formatRupiah(0, decimals: 2));
    }

    public function testFormatRingkas()
    {
        // Format Bahasa Indonesia (default: rb, jt, M, T)
        $this->assertEquals('500', NumberHelper::formatRingkas(500));
        $this->assertEquals('1,5 rb', NumberHelper::formatRingkas(1500));
        $this->assertEquals('2 rb', NumberHelper::formatRingkas(2000));
        $this->assertEquals('1,2 jt', NumberHelper::formatRingkas(1200000));
        $this->assertEquals('2,5 M', NumberHelper::formatRingkas(2500000000));
        $this->assertEquals('1 T', NumberHelper::formatRingkas(1000000000000));
        $this->assertEquals('-1,5 rb', NumberHelper::formatRingkas(-1500));

        // Format Internasional (K, M, B, T)
        $this->assertEquals('1.5K', NumberHelper::formatRingkas(1500, style: 'intl'));
        $this->assertEquals('1.2M', NumberHelper::formatRingkas(1200000, style: 'intl'));
        $this->assertEquals('2.5B', NumberHelper::formatRingkas(2500000000, style: 'intl'));
        $this->assertEquals('1T', NumberHelper::formatRingkas(1000000000000, style: 'intl'));
    }

    public function testToRoman()
    {
        $this->assertEquals('I', NumberHelper::toRoman(1));
        $this->assertEquals('IV', NumberHelper::toRoman(4));
        $this->assertEquals('IX', NumberHelper::toRoman(9));
        $this->assertEquals('XL', NumberHelper::toRoman(40));
        $this->assertEquals('XC', NumberHelper::toRoman(90));
        $this->assertEquals('CD', NumberHelper::toRoman(400));
        $this->assertEquals('CM', NumberHelper::toRoman(900));
        $this->assertEquals('MMXXVI', NumberHelper::toRoman(2026));
        $this->assertEquals('MMMCMXCIX', NumberHelper::toRoman(3999));
    }

    public function testToRomanInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        NumberHelper::toRoman(0);
    }

    public function testToRomanInvalidUpper()
    {
        $this->expectException(InvalidArgumentException::class);
        NumberHelper::toRoman(4000);
    }

    public function testFromRoman()
    {
        $this->assertEquals(1, NumberHelper::fromRoman('I'));
        $this->assertEquals(4, NumberHelper::fromRoman('IV'));
        $this->assertEquals(9, NumberHelper::fromRoman('IX'));
        $this->assertEquals(2026, NumberHelper::fromRoman('MMXXVI'));
        $this->assertEquals(2026, NumberHelper::fromRoman('mmxxvi')); // case insensitive
        $this->assertEquals(3999, NumberHelper::fromRoman('MMMCMXCIX'));
    }

    public function testFromRomanInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        NumberHelper::fromRoman('IIII');
    }

    public function testFormatPercent()
    {
        $this->assertEquals('75,5%', NumberHelper::formatPercent(0.755));
        $this->assertEquals('50%', NumberHelper::formatPercent(0.5, decimals: 0));
        $this->assertEquals('12,5%', NumberHelper::formatPercent(12.5, decimals: 1, isDecimal: false));
        $this->assertEquals('-10,0%', NumberHelper::formatPercent(-0.1));
    }

    public function testParseNumber()
    {
        $this->assertEquals(1500000, NumberHelper::parse('Rp 1.500.000'));
        $this->assertEquals(1500000.5, NumberHelper::parse('Rp 1.500.000,50'));
        $this->assertEquals(1250000, NumberHelper::parse('1.250.000'));
        $this->assertEquals(-25000, NumberHelper::parse('-Rp 25.000'));
        $this->assertEquals(-25000, NumberHelper::parse('(Rp 25.000)'));
        $this->assertEquals(75.5, NumberHelper::parse('75,5%'));
        $this->assertEquals(1500000.5, NumberHelper::parse('1,500,000.50'));
        $this->assertEquals(15.5, NumberHelper::parse('15,5'));
        $this->assertEquals(100, NumberHelper::parse('100'));
    }

    public function testParseNumberInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        NumberHelper::parse('bukan angka');
    }

    public function testOrdinal()
    {
        $this->assertEquals('ke-1', NumberHelper::ordinal(1));
        $this->assertEquals('ke-5', NumberHelper::ordinal(5));
        $this->assertEquals('pertama', NumberHelper::ordinal(1, asWord: true));
        $this->assertEquals('kedua', NumberHelper::ordinal(2, asWord: true));
        $this->assertEquals('kesepuluh', NumberHelper::ordinal(10, asWord: true));
        $this->assertEquals('kedua puluh satu', NumberHelper::ordinal(21, asWord: true));
    }

    public function testOrdinalInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        NumberHelper::ordinal(0);
    }
}
