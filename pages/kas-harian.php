<?php
session_start();
include '../config/koneksi.php';

// Proses Delete
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM kas_masjid WHERE id_kas=$id");
    header("Location: kas-harian.php");
    exit;
}

// Proses Simpan / Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_edit = $_POST['id_edit'] ?? '';
    $tanggal = $_POST['tanggal'];
    $kategori = $_POST['kategori_kas'];
    $jumlah = floatval($_POST['jumlah']);
    $keterangan = $_POST['keterangan'];
    $jenis_kas = 'Harian'; // Default kas harian

    if ($id_edit != '') {
        $stmt = $conn->prepare("UPDATE kas_masjid SET tanggal=?, kategori_kas=?, jumlah=?, keterangan=? WHERE id_kas=?");
        $stmt->bind_param("ssdsi", $tanggal, $kategori, $jumlah, $keterangan, $id_edit);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO kas_masjid (tanggal, jenis_kas, kategori_kas, jumlah, keterangan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssds", $tanggal, $jenis_kas, $kategori, $jumlah, $keterangan);
        $stmt->execute();
    }
    header("Location: kas-harian.php");
    exit;
}

// Ambil Total Saldo Harian
$query_saldo = mysqli_query($conn, "
    SELECT 
        SUM(CASE WHEN kategori_kas = 'Pemasukan' THEN jumlah ELSE 0 END) as total_masuk,
        SUM(CASE WHEN kategori_kas = 'Pengeluaran' THEN jumlah ELSE 0 END) as total_keluar
    FROM kas_masjid WHERE jenis_kas = 'Harian'
");
$row_saldo = mysqli_fetch_assoc($query_saldo);
$saldo_akhir = ($row_saldo['total_masuk'] ?? 0) - ($row_saldo['total_keluar'] ?? 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Kas Harian - Website Masjid</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Header & Navigation -->
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-primary fw-bold" style="font-family: var(--bs-body-font-family)"><i class="fas fa-wallet text-primary me-2"></i>Kas Harian Masjid</h2>
                <p class="text-muted">Pencatatan Pemasukan dan Pengeluaran Harian</p>
            </div>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKas">
                    <i class="fas fa-plus me-1"></i> Tambah Transaksi
                </button>
            </div>
        </div>

        <!-- Kartu Saldo -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <h5>Total Pemasukan</h5>
                        <h3>Rp <?= number_format($row_saldo['total_masuk'] ?? 0, 0, ',', '.') ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        <h5>Total Pengeluaran</h5>
                        <h3>Rp <?= number_format($row_saldo['total_keluar'] ?? 0, 0, ',', '.') ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body">
                        <h5>Saldo Akhir</h5>
                        <h3>Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body table-responsive">
                <table id="tabelKas" class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="15%">Kategori</th>
                            <th width="35%">Keterangan</th>
                            <th width="15%">Jumlah</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($conn, "SELECT * FROM kas_masjid WHERE jenis_kas = 'Harian' ORDER BY tanggal DESC, id_kas DESC");
                        while ($row = mysqli_fetch_assoc($query)) {
                            $isMasuk = $row['kategori_kas'] == 'Pemasukan';
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                            <td>
                                <span class="badge <?= $isMasuk ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $row['kategori_kas'] ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td class="text-end fw-bold <?= $isMasuk ? 'text-success' : 'text-danger' ?>">
                                <?= $isMasuk ? '+' : '-' ?> Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning me-1" onclick='editKas(<?= json_encode($row) ?>)'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?hapus=<?= $row['id_kas'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="modalKas" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="id_edit" id="id_edit">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalTitle">Tambah Transaksi Kas Harian</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="mb-3">
                            <label>Kategori</label>
                            <select name="kategori_kas" id="kategori_kas" class="form-control" required>
                                <option value="Pemasukan">Pemasukan</option>
                                <option value="Pengeluaran">Pengeluaran</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Jumlah (Rp)</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control" required min="1">
                        </div>
                        <div class="mb-3">
                            <label>Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3" required placeholder="Contoh: Infak Harian, Bayar Listrik..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#tabelKas').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                }
            });
        });

        function editKas(data) {
            document.getElementById('id_edit').value = data.id_kas;
            document.getElementById('tanggal').value = data.tanggal;
            document.getElementById('kategori_kas').value = data.kategori_kas;
            document.getElementById('jumlah').value = data.jumlah;
            document.getElementById('keterangan').value = data.keterangan;
            document.getElementById('modalTitle').innerText = 'Edit Transaksi Kas Harian';
            
            var modal = new bootstrap.Modal(document.getElementById('modalKas'));
            modal.show();
        }

        // Reset form when modal closed
        document.getElementById('modalKas').addEventListener('hidden.bs.modal', function () {
            document.getElementById('id_edit').value = '';
            document.getElementById('tanggal').value = '<?= date('Y-m-d') ?>';
            document.getElementById('kategori_kas').value = 'Pemasukan';
            document.getElementById('jumlah').value = '';
            document.getElementById('keterangan').value = '';
            document.getElementById('modalTitle').innerText = 'Tambah Transaksi Kas Harian';
        });
    </script>
</body>
</html>
