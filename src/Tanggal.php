<?php

namespace Rahfan\PhpUtils;

class Tanggal
{
  public $tanggal;

  public $hari;
  public $bulan;

  public function __construct($tanggal)
  {
    $this->tanggal = $tanggal;

    $this->cekHari();
    $this->cekBulan();
  }

  private function cekHari() {
    $hari = date('D', strtotime($this->tanggal));
    switch ($hari) {
      case 'Mon':
        $this->hari = 'Senin';
        break;
      case 'Tue':
        $this->hari = 'Selasa';
        break;
      case 'Wed':
        $this->hari = 'Rabu';
        break;
      case 'Thu':
        $this->hari = 'Kamis';
        break;
      case 'Fri':
        $this->hari = 'Jumat';
        break;
      case 'Sat':
        $this->hari = 'Sabtu';
        break;
      case 'Sun':
        $this->hari = 'Minggu';
        break;
    }
  }

  private function cekBulan() {
    $bulan = date('m', strtotime($this->tanggal));
    switch ($bulan) {
      case '01':
        $this->bulan = 'Januari';
        break;
      case '02':
        $this->bulan = 'Februari';
        break;
      case '03':
        $this->bulan = 'Maret';
        break;
      case '04':
        $this->bulan = 'April';
        break;
      case '05':
        $this->bulan = 'Mei';
        break;
      case '06':
        $this->bulan = 'Juni';
        break;
      case '07':
        $this->bulan = 'Juli';
        break;
      case '08':
        $this->bulan = 'Agustus';
        break;
      case '09':
        $this->bulan = 'September';
        break;
      case '10':
        $this->bulan = 'Oktober';
        break;
      case '11':
        $this->bulan = 'November';
        break;
      case '12':
        $this->bulan = 'Desember';
        break;
    }
  }
}
