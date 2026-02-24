<?php
session_start();
include '../config/koneksi.php';

// Data untuk Chart.js
// 1. Saldo Kas Masjid (Harian & Jumat)
$q_kas = mysqli_query($conn, "
    SELECT 
        SUM(CASE WHEN kategori_kas = 'Pemasukan' THEN jumlah ELSE 0 END) as masuk,
        SUM(CASE WHEN kategori_kas = 'Pengeluaran' THEN jumlah ELSE 0 END) as keluar
    FROM kas_masjid
");
$kas = mysqli_fetch_assoc($q_kas);
$kas_masuk = $kas['masuk'] ?? 0;
$kas_keluar = $kas['keluar'] ?? 0;

// 2. Data Zakat & Infak
$q_fitrah = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_zakat) as total FROM zakat_fitrah"));
$q_mal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_zakat) as total FROM zakat_mal"));
$q_infak = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) as total FROM infak"));

$tot_fitrah = $q_fitrah['total'] ?? 0;
$tot_mal = $q_mal['total'] ?? 0;
$tot_infak = $q_infak['total'] ?? 0;

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan & Dashboard - Website Masjid</title>
    <!-- Template CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5 pb-5">
        <div class="row align-items-center mb-5">
            <div class="col-md-8">
                <h2 class="text-primary fw-bold" style="font-family: var(--bs-body-font-family)"><i class="fas fa-chart-line text-primary me-2"></i>Dashboard & Laporan</h2>
                <p class="text-muted">Visualisasi data keuangan dan cetak rekapitulasi laporan dalam format PDF.</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Chart Kas Masjid -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 pb-3">
                    <div class="card-body">
                        <h5 class="fw-bold text-dark mb-4"><i class="fas fa-wallet text-primary me-2"></i>Statistik Kas Masjid</h5>
                        <div style="height: 300px; position: relative;">
                            <canvas id="kasChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Zakat & Infak -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 pb-3">
                    <div class="card-body">
                        <h5 class="fw-bold text-dark mb-4"><i class="fas fa-hand-holding-heart text-success me-2"></i>Penerimaan Zakat & Infak</h5>
                        <div style="height: 300px; position: relative;" class="d-flex justify-content-center">
                            <canvas id="zakatChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- FORM GENERATE PDF -->
                <div class="card border-0 shadow rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white p-4 border-0">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-file-pdf me-2"></i>Cetak Laporan (PDF)</h5>
                    </div>
                    <div class="card-body p-4 bg-light">
                        <form action="cetak_laporan.php" method="GET" target="_blank">
                            <div class="row g-3">
                                <div class="col-md-12 mb-2">
                                    <label class="form-label text-muted fw-bold">Jenis Laporan</label>
                                    <select name="jenis" class="form-select border-0 shadow-sm py-2" required>
                                        <option value="">-- Pilih Jenis Laporan --</option>
                                        <option value="kas">Laporan Kas Masjid (Harian & Jum'at)</option>
                                        <option value="zakat_infak">Laporan Penerimaan Zakat & Infak</option>
                                        <option value="penyaluran">Laporan Penyaluran Dana</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Periode Dari (Tanggal)</label>
                                    <input type="date" name="mulai" class="form-control border-0 shadow-sm py-2" required value="<?= date('Y-m-01') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Sampai (Tanggal)</label>
                                    <input type="date" name="sampai" class="form-control border-0 shadow-sm py-2" required value="<?= date('Y-m-t') ?>">
                                </div>
                                <div class="col-12 text-end mt-2">
                                    <button type="submit" class="btn btn-danger btn-lg shadow px-5 rounded-pill">
                                        <i class="fas fa-print me-2"></i>Generate PDF
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Data PHP to JS
        const kasMasuk = <?= $kas_masuk ?>;
        const kasKeluar = <?= $kas_keluar ?>;
        const saldoAkhir = kasMasuk - kasKeluar;

        const totFitrah = <?= $tot_fitrah ?>;
        const totMal = <?= $tot_mal ?>;
        const totInfak = <?= $tot_infak ?>;

        // 1. Bar Chart Kas Masjid
        const ctxKas = document.getElementById('kasChart').getContext('2d');
        new Chart(ctxKas, {
            type: 'bar',
            data: {
                labels: ['Pemasukan', 'Pengeluaran', 'Saldo Akhir'],
                datasets: [{
                    label: 'Nominal Kas (Rp)',
                    data: [kasMasuk, kasKeluar, saldoAkhir],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)', // Green
                        'rgba(239, 68, 68, 0.8)', // Red
                        'rgba(59, 130, 246, 0.8)'  // Blue
                    ],
                    borderColor: [
                        'rgb(16, 185, 129)',
                        'rgb(239, 68, 68)',
                        'rgb(59, 130, 246)'
                    ],
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // 2. Doughnut Chart Zakat & Infak
        const ctxZakat = document.getElementById('zakatChart').getContext('2d');
        new Chart(ctxZakat, {
            type: 'doughnut',
            data: {
                labels: ['Zakat Fitrah', 'Zakat Mal', 'Infak'],
                datasets: [{
                    data: [totFitrah, totMal, totInfak],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)', // Green
                        'rgba(245, 158, 11, 0.8)', // Amber
                        'rgba(14, 165, 233, 0.8)'  // Sky
                    ],
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { family: 'Outfit', size: 14 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) { label += ': '; }
                                if (context.raw !== null) {
                                    label += 'Rp ' + context.raw.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    </script>
</body>
</html>
