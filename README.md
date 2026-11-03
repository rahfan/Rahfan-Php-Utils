# Rahfan-Php-Utils

Kumpulan fungsi dan kelas utilitas PHP buatan Rahfan untuk mempermudah manipulasi angka, tanggal, dan format dalam Bahasa Indonesia.

## Persyaratan
- PHP >= 8.2

## Instalasi
```bash
composer require rahfan/php-utils
```

---

## Modul dan Fitur

### 1. `Rahfan\PhpUtils\DateHelper`
Utilitas pemrosesan tanggal dan waktu:

- **`detectType(string $input): string`**
  Mendeteksi jenis format teks tanggal/waktu (`'tahun'`, `'bulan'`, `'tanggal'`, `'datetime'`, `'waktu'`, atau `'tidak diketahui'`).
  ```php
  use Rahfan\PhpUtils\DateHelper;

  DateHelper::detectType('2026'); // 'tahun'
  DateHelper::detectType('2026-11'); // 'bulan'
  DateHelper::detectType('2026-11-03'); // 'tanggal'
  DateHelper::detectType('03/11/2026'); // 'tanggal'
  DateHelper::detectType('2026-11-03 14:05:00'); // 'datetime'
  DateHelper::detectType('14:05:00'); // 'waktu'
  ```

- **`isKabisat(int $tahun): bool`**
  Mengecek apakah tahun tertentu adalah tahun kabisat.
  ```php
  DateHelper::isKabisat(2024); // true
  DateHelper::isKabisat(2026); // false
  ```

- **`isValidDate(string $input, string $format = 'Y-m-d'): bool`**
  Memvalidasi keabsahan kalender nyata untuk tanggal dan format yang diberikan.
  ```php
  DateHelper::isValidDate('2024-02-29'); // true
  DateHelper::isValidDate('2026-02-29'); // false (bukan kabisat)
  DateHelper::isValidDate('03/11/2026', 'd/m/Y'); // true
  ```

- **`formatIndo(string|DateTimeInterface $date, bool $showDay = false, bool $showTime = false): string`**
  Memformat tanggal ke teks Bahasa Indonesia standar.
  ```php
  DateHelper::formatIndo('2026-11-03'); // "3 November 2026"
  DateHelper::formatIndo('2026-11-03', showDay: true); // "Selasa, 3 November 2026"
  DateHelper::formatIndo('2026-11-03 14:05:00', showDay: true, showTime: true); // "Selasa, 3 November 2026 14:05"
  ```

- **`selisihHari(string|DateTimeInterface $from, string|DateTimeInterface $to = 'now', bool $absolute = true): int`**
  Menghitung selisih jumlah hari antara dua tanggal.
  ```php
  DateHelper::selisihHari('2026-11-01', '2026-11-05'); // 4
  DateHelper::selisihHari('2026-11-05', '2026-11-01', absolute: false); // -4
  ```

- **`diffForHumans(string|DateTimeInterface $date, string|DateTimeInterface $reference = 'now'): string`**
  Menampilkan selisih waktu secara relatif dalam Bahasa Indonesia.
  ```php
  DateHelper::diffForHumans('2026-11-02 12:00:00', '2026-11-03 12:00:00'); // "kemarin"
  DateHelper::diffForHumans('2026-11-04 12:00:00', '2026-11-03 12:00:00'); // "besok"
  DateHelper::diffForHumans('2026-11-05 12:00:00', '2026-11-03 12:00:00'); // "lusa"
  DateHelper::diffForHumans('2026-10-29 12:00:00', '2026-11-03 12:00:00'); // "5 hari yang lalu"
  ```

---

### 2. `Rahfan\PhpUtils\NumberHelper`
Utilitas konversi angka ke ucapan teks (terbilang):

- **`terbilang(int $angka): string`**
  ```php
  use Rahfan\PhpUtils\NumberHelper;

  NumberHelper::terbilang(123); // "seratus dua puluh tiga"
  NumberHelper::terbilang(1500000); // "satu juta lima ratus ribu"
  NumberHelper::terbilang(-50); // "minus lima puluh"
  ```

---

## Pengujian (Testing)
Untuk menjalankan suite unit testing:
```bash
vendor/bin/phpunit
```

## Lisensi
MIT License.
