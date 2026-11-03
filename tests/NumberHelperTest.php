<?php

namespace Rahfan\PhpUtils\Tests;

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
}
