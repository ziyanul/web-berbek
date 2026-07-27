<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function is_produksi() {
    $CI =& get_instance();
    return $CI->session->userdata('hak_akses') == 'Produksi';
}

function is_engineering() {
    $CI =& get_instance();
    return $CI->session->userdata('hak_akses') == 'Engineering';
}

function is_warehouse() {
    $CI =& get_instance();
    return $CI->session->userdata('hak_akses') == 'Warehouse';
}

function is_admin() {
    $CI =& get_instance();
    return $CI->session->userdata('hak_akses') == 'superadmin';
}

function current_departemen()
{
    $CI =& get_instance();
    return $CI->session->userdata('hak_akses');
}

function tanggal_indo($tanggal) {
    $bulan = [
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
        12 => 'Desember'
    ];

    $tgl = strtotime($tanggal);
    return date('d', $tgl) . ' ' . $bulan[(int)date('m', $tgl)] . ' ' . date('Y', $tgl);
}

function bulan_indo($tanggal) {
    $bulan = [
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
        12 => 'Desember'
    ];

    $tgl = strtotime($tanggal);
    return $bulan[(int)date('m', $tgl)] . ' ' . date('Y', $tgl);
}

