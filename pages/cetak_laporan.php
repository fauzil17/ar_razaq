<?php
session_start();
include '../config/koneksi.php';
require '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['jenis']) || !isset($_GET['mulai']) || !isset($_GET['sampai'])) {
    die("Parameter tidak lengkap.");
}

$jenis = $_GET['jenis'];
$mulai = $_GET['mulai'];
$sampai = $_GET['sampai'];

$tgl_mulai = date('d/m/Y', strtotime($mulai));
$tgl_sampai = date('d/m/Y', strtotime($sampai));

$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Masjid Ar-Razaq</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1e3a8a; /* Warna Biru Soft Main */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #1e3a8a;
            font-family: serif;
        }
        .header p {
            margin: 5px 0 0;
            color: #555;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .periode {
            text-align: center;
            margin-bottom: 20px;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #3b82f6; /* Soft Blue */
            color: white;
            padding: 8px;
            text-align: center;
        }
        td {
            padding: 8px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row {
            font-weight: bold;
            background-color: #f8fafc;
        }
        .text-masuk { color: #10b981; }
        .text-keluar { color: #ef4444; }
    </style>
</head>
<body>

<div class="header">
    <h1>MASJID AR-RAZAQ</h1>
    <p>Jl. Trans Maumere - Larantuka, Sikka, NTT, Kode Pos :86182</p>
    <p>Laporan Resmi Keuangan Masjid</p>
</div>
';

if ($jenis == 'kas') {
    $html .= '<div class="title">Laporan Kas Masjid Ar-Razaq(Harian & Jum\'at)</div>';
    $html .= '<div class="periode">Periode: ' . $tgl_mulai . ' s/d ' . $tgl_sampai . '</div>';
    
    $html .= '<table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Jenis Kas</th>
                        <th width="15%">Kategori</th>
                        <th width="35%">Keterangan</th>
                        <th width="15%">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>';
    
    $query = $conn->prepare("SELECT * FROM kas_masjid WHERE tanggal >= ? AND tanggal <= ? ORDER BY tanggal ASC");
    $query->bind_param("ss", $mulai, $sampai);
    $query->execute();
    $result = $query->get_result();
    
    $no = 1;
    $tot_masuk = 0;
    $tot_keluar = 0;

    while ($row = $result->fetch_assoc()) {
        if ($row['kategori_kas'] == 'Pemasukan') {
            $tot_masuk += $row['jumlah'];
            $warna = 'text-masuk';
            $tanda = '+ ';
        } else {
            $tot_keluar += $row['jumlah'];
            $warna = 'text-keluar';
            $tanda = '- ';
        }

        $html .= '<tr>
                    <td class="text-center">'.$no++.'</td>
                    <td class="text-center">'.date('d/m/Y', strtotime($row['tanggal'])).'</td>
                    <td class="text-center">'.$row['jenis_kas'].'</td>
                    <td class="text-center">'.$row['kategori_kas'].'</td>
                    <td>'.$row['keterangan'].'</td>
                    <td class="text-right '.$warna.'">'.$tanda.number_format($row['jumlah'],0,',','.').'</td>
                  </tr>';
    }

    $saldo = $tot_masuk - $tot_keluar;
    $html .= '</tbody></table>';

    $html .= '<table>
                <tr class="total-row">
                    <td width="85%" class="text-right">Total Pemasukan:</td>
                    <td width="15%" class="text-right text-masuk">'.number_format($tot_masuk,0,',','.').'</td>
                </tr>
                <tr class="total-row">
                    <td class="text-right">Total Pengeluaran:</td>
                    <td class="text-right text-keluar">'.number_format($tot_keluar,0,',','.').'</td>
                </tr>
                <tr class="total-row" style="background-color: #e0f2fe;">
                    <td class="text-right">SALDO AKHIR PERIODE:</td>
                    <td class="text-right" style="color: #1e3a8a;">'.number_format($saldo,0,',','.').'</td>
                </tr>
              </table>';

} elseif ($jenis == 'zakat_infak') {
    $html .= '<div class="title">Laporan Penerimaan Zakat & Infak</div>';
    $html .= '<div class="periode">Periode: ' . $tgl_mulai . ' s/d ' . $tgl_sampai . '</div>';

    // 1. Zakat Fitrah
    $html .= '<h3>1. Zakat Fitrah</h3>';
    $html .= '<table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="20%">Kepala Keluarga</th>
                        <th width="15%">Jumlah Jiwa</th>
                        <th width="30%">Keterangan</th>
                        <th width="15%">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody>';
    
    $q_fitrah = $conn->prepare("SELECT z.*, (SELECT nama_anggota FROM zakat_fitrah_anggota WHERE id_zakat_fitrah = z.id_zakat_fitrah LIMIT 1) as kepala FROM zakat_fitrah z WHERE tanggal >= ? AND tanggal <= ? ORDER BY tanggal ASC");
    $q_fitrah->bind_param("ss", $mulai, $sampai);
    $q_fitrah->execute();
    $res_fitrah = $q_fitrah->get_result();

    $no = 1; $tot_fitrah = 0;
    while ($r = $res_fitrah->fetch_assoc()) {
        $tot_fitrah += $r['total_zakat'];
        $html .= '<tr>
                    <td class="text-center">'.$no++.'</td>
                    <td class="text-center">'.date('d/m/Y', strtotime($r['tanggal'])).'</td>
                    <td>'.$r['kepala'].'</td>
                    <td class="text-center">'.$r['jumlah_jiwa'].' Jiwa</td>
                    <td>'.$r['keterangan'].'</td>
                    <td class="text-right">'.number_format($r['total_zakat'],0,',','.').'</td>
                  </tr>';
    }
    $html .= '<tr class="total-row"><td colspan="5" class="text-right">Total Penerimaan Zakat Fitrah:</td><td class="text-right text-masuk">'.number_format($tot_fitrah,0,',','.').'</td></tr>';
    $html .= '</tbody></table>';

    // 2. Zakat Mal
    $html .= '<h3>2. Zakat Mal</h3>';
    $html .= '<table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="25%">Nama Pembayar</th>
                        <th width="20%">Jenis Harta</th>
                        <th width="20%">Nilai Harta (Rp)</th>
                        <th width="15%">Total Zakat (Rp)</th>
                    </tr>
                </thead>
                <tbody>';
    
    $q_mal = $conn->prepare("SELECT * FROM zakat_mal WHERE tanggal >= ? AND tanggal <= ? ORDER BY tanggal ASC");
    $q_mal->bind_param("ss", $mulai, $sampai);
    $q_mal->execute();
    $res_mal = $q_mal->get_result();

    $no = 1; $tot_mal = 0;
    while ($r = $res_mal->fetch_assoc()) {
        $tot_mal += $r['total_zakat'];
        $html .= '<tr>
                    <td class="text-center">'.$no++.'</td>
                    <td class="text-center">'.date('d/m/Y', strtotime($r['tanggal'])).'</td>
                    <td>'.$r['nama_pembayar'].'</td>
                    <td>'.$r['jenis_harta'].'</td>
                    <td class="text-right">'.number_format($r['nilai_harta'],0,',','.').'</td>
                    <td class="text-right">'.number_format($r['total_zakat'],0,',','.').'</td>
                  </tr>';
    }
    $html .= '<tr class="total-row"><td colspan="5" class="text-right">Total Penerimaan Zakat Mal:</td><td class="text-right text-masuk">'.number_format($tot_mal,0,',','.').'</td></tr>';
    $html .= '</tbody></table>';

    // 3. Infak
    $html .= '<h3>3. Infak</h3>';
    $html .= '<table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="25%">Nama Pemberi</th>
                        <th width="40%">Keterangan</th>
                        <th width="15%">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody>';
    
    $q_infak = $conn->prepare("SELECT * FROM infak WHERE tanggal >= ? AND tanggal <= ? ORDER BY tanggal ASC");
    $q_infak->bind_param("ss", $mulai, $sampai);
    $q_infak->execute();
    $res_infak = $q_infak->get_result();

    $no = 1; $tot_infak = 0;
    while ($r = $res_infak->fetch_assoc()) {
        $tot_infak += $r['jumlah'];
        $html .= '<tr>
                    <td class="text-center">'.$no++.'</td>
                    <td class="text-center">'.date('d/m/Y', strtotime($r['tanggal'])).'</td>
                    <td>'.$r['nama_pemberi'].'</td>
                    <td>'.$r['keterangan'].'</td>
                    <td class="text-right">'.number_format($r['jumlah'],0,',','.').'</td>
                  </tr>';
    }
    $html .= '<tr class="total-row"><td colspan="4" class="text-right">Total Penerimaan Infak:</td><td class="text-right text-masuk">'.number_format($tot_infak,0,',','.').'</td></tr>';
    $html .= '</tbody></table>';


} elseif ($jenis == 'penyaluran') {
    $html .= '<div class="title">Laporan Penyaluran Dana</div>';
    $html .= '<div class="periode">Periode: ' . $tgl_mulai . ' s/d ' . $tgl_sampai . '</div>';
    
    $html .= '<table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="12%">Tanggal</th>
                        <th width="18%">Sumber Dana</th>
                        <th width="25%">Penerima/Mustahik</th>
                        <th width="25%">Keterangan</th>
                        <th width="15%">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>';
    
    $query = $conn->prepare("SELECT * FROM penyaluran_dana WHERE tanggal >= ? AND tanggal <= ? ORDER BY tanggal ASC");
    $query->bind_param("ss", $mulai, $sampai);
    $query->execute();
    $result = $query->get_result();
    
    $no = 1;
    $tot = 0;

    while ($row = $result->fetch_assoc()) {
        $tot += $row['jumlah'];
        $html .= '<tr>
                    <td class="text-center">'.$no++.'</td>
                    <td class="text-center">'.date('d/m/Y', strtotime($row['tanggal'])).'</td>
                    <td class="text-center">'.$row['jenis_dana'].'</td>
                    <td>'.$row['penerima'].'</td>
                    <td>'.$row['keterangan'].'</td>
                    <td class="text-right text-keluar">'.number_format($row['jumlah'],0,',','.').'</td>
                  </tr>';
    }

    $html .= '</tbody></table>';

    $html .= '<table>
                <tr class="total-row" style="background-color: #fee2e2;">
                    <td width="85%" class="text-right">TOTAL KESELURUHAN PENYALURAN DANA:</td>
                    <td width="15%" class="text-right text-keluar">'.number_format($tot,0,',','.').'</td>
                </tr>
              </table>';
}

$html .= '
    <div style="margin-top: 50px; text-align: right; font-size: 11px;">
        <p>Maumere, '.date('d M Y').'<br>Mengetahui,</p>
        <br><br><br>
        <p>_______________________<br><b>Ketua Takmir Masjid</b></p>
    </div>
</body>
</html>
';

// Setup Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); 

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

// Setup kerta (A4 Portrait)
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$filename = "Laporan_Masjid_" . $jenis . "_" . date('Ymd_His') . ".pdf";
$dompdf->stream($filename, ["Attachment" => false]); // Set to false to open in browser, true to download
?>
