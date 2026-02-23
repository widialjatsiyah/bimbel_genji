<?php
if (!function_exists('format_rupiah')) {
    /**
     * Format number to Indonesian Rupiah currency format
     *
     * @param int|float $amount
     * @return string
     */
    function format_rupiah($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('format_tanggal_indonesia')) {
    /**
     * Format date to Indonesian date format
     *
     * @param string $date
     * @return string
     */
    function format_tanggal_indonesia($date)
    {
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

        $tanggal = date('j', strtotime($date));
        $bulanIndo = $bulan[(int) date('n', strtotime($date))];
        $tahun = date('Y', strtotime($date));

        return $tanggal . ' ' . $bulanIndo . ' ' . $tahun;
    }
}