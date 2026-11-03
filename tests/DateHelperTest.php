<?php

namespace Rahfan\PhpUtils\Tests;

use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Rahfan\PhpUtils\DateHelper;

class DateHelperTest extends TestCase
{
    public function testDetectTypeTahun()
    {
        $this->assertEquals('tahun', DateHelper::detectType('2026'));
        $this->assertEquals('tahun', DateHelper::detectType('1995'));
    }

    public function testDetectTypeBulan()
    {
        $this->assertEquals('bulan', DateHelper::detectType('2026-01'));
        $this->assertEquals('bulan', DateHelper::detectType('2026-12'));
    }

    public function testDetectTypeTanggal()
    {
        $this->assertEquals('tanggal', DateHelper::detectType('2026-11-03'));
        $this->assertEquals('tanggal', DateHelper::detectType('03-11-2026'));
        $this->assertEquals('tanggal', DateHelper::detectType('03/11/2026'));
    }

    public function testDetectTypeWaktu()
    {
        $this->assertEquals('waktu', DateHelper::detectType('14:05'));
        $this->assertEquals('waktu', DateHelper::detectType('14:05:30'));
        $this->assertEquals('waktu', DateHelper::detectType('00:00:00'));
        $this->assertEquals('waktu', DateHelper::detectType('23:59:59'));
    }

    public function testDetectTypeDatetime()
    {
        $this->assertEquals('datetime', DateHelper::detectType('2026-11-03 14:05:00'));
        $this->assertEquals('datetime', DateHelper::detectType('2026-11-03 14:05'));
        $this->assertEquals('datetime', DateHelper::detectType('2026-11-03T14:05:00'));
    }

    public function testDetectTypeUnknown()
    {
        $this->assertEquals('tidak diketahui', DateHelper::detectType('bukan tanggal'));
        $this->assertEquals('tidak diketahui', DateHelper::detectType('2026-99-99'));
        $this->assertEquals('tidak diketahui', DateHelper::detectType('25:00:00'));
        $this->assertEquals('tidak diketahui', DateHelper::detectType(''));
    }

    public function testIsKabisat()
    {
        $this->assertTrue(DateHelper::isKabisat(2024));
        $this->assertTrue(DateHelper::isKabisat(2000));
        $this->assertTrue(DateHelper::isKabisat(2028));

        $this->assertFalse(DateHelper::isKabisat(2026));
        $this->assertFalse(DateHelper::isKabisat(1900));
        $this->assertFalse(DateHelper::isKabisat(2023));
    }

    public function testIsValidDate()
    {
        $this->assertTrue(DateHelper::isValidDate('2026-11-03'));
        $this->assertTrue(DateHelper::isValidDate('2024-02-29')); // Kabisat

        $this->assertFalse(DateHelper::isValidDate('2026-02-29')); // Bukan kabisat
        $this->assertFalse(DateHelper::isValidDate('2026-04-31')); // April hanya 30 hari
        $this->assertFalse(DateHelper::isValidDate('invalid-date'));

        // Custom format
        $this->assertTrue(DateHelper::isValidDate('03/11/2026', 'd/m/Y'));
        $this->assertFalse(DateHelper::isValidDate('31/04/2026', 'd/m/Y'));
        $this->assertTrue(DateHelper::isValidDate('2026-11-03 14:05:00', 'Y-m-d H:i:s'));
    }

    public function testFormatIndoDefault()
    {
        $this->assertEquals('3 November 2026', DateHelper::formatIndo('2026-11-03'));
        $this->assertEquals('1 Januari 2025', DateHelper::formatIndo('2025-01-01'));
        $this->assertEquals('17 Agustus 1945', DateHelper::formatIndo('1945-08-17'));
    }

    public function testFormatIndoWithDay()
    {
        // 2026-11-03 adalah hari Selasa
        $this->assertEquals('Selasa, 3 November 2026', DateHelper::formatIndo('2026-11-03', showDay: true));
        // 2026-11-01 adalah hari Minggu
        $this->assertEquals('Minggu, 1 November 2026', DateHelper::formatIndo('2026-11-01', showDay: true));
    }

    public function testFormatIndoWithTime()
    {
        $this->assertEquals(
            'Selasa, 3 November 2026 14:05',
            DateHelper::formatIndo('2026-11-03 14:05:00', showDay: true, showTime: true)
        );
        $this->assertEquals(
            '3 November 2026 14:05',
            DateHelper::formatIndo('2026-11-03 14:05:00', showDay: false, showTime: true)
        );
    }

    public function testFormatIndoWithDateTimeObjects()
    {
        $mutable = new DateTime('2026-11-03');
        $immutable = new DateTimeImmutable('2026-11-03');

        $this->assertEquals('3 November 2026', DateHelper::formatIndo($mutable));
        $this->assertEquals('3 November 2026', DateHelper::formatIndo($immutable));
    }

    public function testFormatIndoInvalidDateThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        DateHelper::formatIndo('tanggal-salah');
    }

    public function testSelisihHari()
    {
        $this->assertEquals(4, DateHelper::selisihHari('2026-11-01', '2026-11-05'));
        $this->assertEquals(4, DateHelper::selisihHari('2026-11-05', '2026-11-01'));
        $this->assertEquals(0, DateHelper::selisihHari('2026-11-03', '2026-11-03'));

        // Signed
        $this->assertEquals(4, DateHelper::selisihHari('2026-11-01', '2026-11-05', absolute: false));
        $this->assertEquals(-4, DateHelper::selisihHari('2026-11-05', '2026-11-01', absolute: false));
    }

    public function testDiffForHumans()
    {
        $ref = '2026-11-03 12:00:00';

        // Baru saja (< 45 detik)
        $this->assertEquals('baru saja', DateHelper::diffForHumans('2026-11-03 12:00:20', $ref));
        $this->assertEquals('baru saja', DateHelper::diffForHumans('2026-11-03 11:59:30', $ref));

        // Menit
        $this->assertEquals('10 menit yang lalu', DateHelper::diffForHumans('2026-11-03 11:50:00', $ref));
        $this->assertEquals('15 menit lagi', DateHelper::diffForHumans('2026-11-03 12:15:00', $ref));

        // Jam
        $this->assertEquals('3 jam yang lalu', DateHelper::diffForHumans('2026-11-03 09:00:00', $ref));
        $this->assertEquals('4 jam lagi', DateHelper::diffForHumans('2026-11-03 16:00:00', $ref));

        // Hari khusus: kemarin, besok, lusa
        $this->assertEquals('kemarin', DateHelper::diffForHumans('2026-11-02 12:00:00', $ref));
        $this->assertEquals('besok', DateHelper::diffForHumans('2026-11-04 12:00:00', $ref));
        $this->assertEquals('lusa', DateHelper::diffForHumans('2026-11-05 12:00:00', $ref));

        // Hari umum
        $this->assertEquals('5 hari yang lalu', DateHelper::diffForHumans('2026-10-29 12:00:00', $ref));
        $this->assertEquals('5 hari lagi', DateHelper::diffForHumans('2026-11-08 12:00:00', $ref));

        // Bulan
        $this->assertEquals('2 bulan yang lalu', DateHelper::diffForHumans('2026-09-03 12:00:00', $ref));
        $this->assertEquals('2 bulan lagi', DateHelper::diffForHumans('2027-01-03 12:00:00', $ref));

        // Tahun
        $this->assertEquals('1 tahun yang lalu', DateHelper::diffForHumans('2025-11-03 12:00:00', $ref));
        $this->assertEquals('2 tahun lagi', DateHelper::diffForHumans('2028-11-03 12:00:00', $ref));
    }
}
