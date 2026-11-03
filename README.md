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
Utilitas pemrosesan, konversi kata (terbilang), dan pemformatan angka:

- **`terbilang(int|float $angka, string $suffix = ''): string`**
  Mengonversi angka ke teks Bahasa Indonesia. Mendukung bilangan bulat, negatif, desimal/pecahan, dan akhiran kata (suffix).
  ```php
  use Rahfan\PhpUtils\NumberHelper;

  NumberHelper::terbilang(1500000); // "satu juta lima ratus ribu"
  NumberHelper::terbilang(1500, 'rupiah'); // "seribu lima ratus rupiah"
  NumberHelper::terbilang(15.5); // "lima belas koma lima"
  NumberHelper::terbilang(-50); // "minus lima puluh"
  ```

- **`formatRupiah(int|float $angka, bool $withSymbol = true, int $decimals = 0): string`**
  Memformat angka ke mata uang Rupiah standar.
  ```php
  NumberHelper::formatRupiah(1500000); // "Rp 1.500.000"
  NumberHelper::formatRupiah(1500000, withSymbol: false); // "1.500.000"
  NumberHelper::formatRupiah(1500000.5, decimals: 2); // "Rp 1.500.000,50"
  NumberHelper::formatRupiah(-25000); // "-Rp 25.000"
  ```

- **`formatRingkas(int|float $angka, int $precision = 1, string $style = 'id'): string`**
  Memendekkan angka besar untuk visualisasi/dashboard (`style: 'id'` untuk `rb`/`jt`/`M`/`T`, atau `style: 'intl'` untuk `K`/`M`/`B`/`T`).
  ```php
  NumberHelper::formatRingkas(1500); // "1,5 rb"
  NumberHelper::formatRingkas(1200000); // "1,2 jt"
  NumberHelper::formatRingkas(2500000000); // "2,5 M"
  NumberHelper::formatRingkas(1500, style: 'intl'); // "1.5K"
  ```

- **`toRoman(int $angka): string` & `fromRoman(string $roman): int`**
  Konversi angka arab ke angka romawi dan sebaliknya (rentang 1 sampai 3999).
  ```php
  NumberHelper::toRoman(4); // "IV"
  NumberHelper::toRoman(2026); // "MMXXVI"

  NumberHelper::fromRoman('MMXXVI'); // 2026
  NumberHelper::fromRoman('iv'); // 4
  ```

- **`formatPercent(int|float $angka, int $decimals = 1, bool $isDecimal = true): string`**
  Memformat angka menjadi format persentase.
  ```php
  NumberHelper::formatPercent(0.755); // "75,5%"
  NumberHelper::formatPercent(50, decimals: 0, isDecimal: false); // "50%"
  ```

- **`parse(string $input): int|float`**
  Membersihkan string teks terformat menjadi tipe numerik (`int` atau `float`).
  ```php
  NumberHelper::parse('Rp 1.500.000'); // 1500000 (int)
  NumberHelper::parse('Rp 1.500.000,50'); // 1500000.5 (float)
  NumberHelper::parse('75,5%'); // 75.5 (float)
  NumberHelper::parse('-Rp 25.000'); // -25000 (int)
  ```

- **`ordinal(int $angka, bool $asWord = false): string`**
  Mengembalikan representasi tingkatan/urutan angka.
  ```php
  NumberHelper::ordinal(1); // "ke-1"
  NumberHelper::ordinal(1, asWord: true); // "pertama"
  NumberHelper::ordinal(2, asWord: true); // "kedua"
  NumberHelper::ordinal(10, asWord: true); // "kesepuluh"
  ```

---

## Pengujian (Testing)
Untuk menjalankan suite unit testing:
```bash
vendor/bin/phpunit
```

## Lisensi
MIT License.
