<?php
session_start();
include '../config/koneksi.php';

// Proses Delete
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM penyaluran_dana WHERE id_penyaluran=$id");
    header("Location: penyaluran.php");
    exit;
}

// Proses Simpan / Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_edit = $_POST['id_edit'] ?? '';
    $tanggal = $_POST['tanggal'];
    $jenis_dana = $_POST['jenis_dana'];
    $penerima = $_POST['penerima'];
    $jumlah = floatval($_POST['jumlah']);
    $keterangan = $_POST['keterangan'];

    if ($id_edit != '') {
        $stmt = $conn->prepare("UPDATE penyaluran_dana SET tanggal=?, jenis_dana=?, penerima=?, jumlah=?, keterangan=? WHERE id_penyaluran=?");
        $stmt->bind_param("sssdsi", $tanggal, $jenis_dana, $penerima, $jumlah, $keterangan, $id_edit);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO penyaluran_dana (tanggal, jenis_dana, penerima, jumlah, keterangan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssds", $tanggal, $jenis_dana, $penerima, $jumlah, $keterangan);
        $stmt->execute();
    }
    header("Location: penyaluran.php");
    exit;
}

// Ambil Statistik Penyaluran
$q_stats = mysqli_query($conn, "
    SELECT jenis_dana, SUM(jumlah) as total 
    FROM penyaluran_dana 
    GROUP BY jenis_dana
");
$stats = [];
while ($row = mysqli_fetch_assoc($q_stats)) {
    $stats[$row['jenis_dana']] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penyaluran Dana - Website Masjid</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-primary fw-bold" style="font-family: var(--bs-body-font-family)"><i class="fas fa-hands-helping text-primary me-2"></i>Penyaluran Dana</h2>
                <p class="text-muted">Distribusi Zakat, Infak, dan Kas kepada Mustahik & Keperluan Masjid</p>
            </div>
            <div>
                <button class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPenyaluran">
                    <i class="fas fa-handshake me-1"></i> Input Penyaluran
                </button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6 col-md-3 mb-3">
                <div class="card bg-white border-start border-5 border-success shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted d-block fw-bold mb-1">Penyaluran Zakat Fitrah</small>
                        <h4 class="text-success fw-bold">Rp <?= number_format($stats['Zakat Fitrah'] ?? 0, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card bg-white border-start border-5 border-warning shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted d-block fw-bold mb-1">Penyaluran Zakat Mal</small>
                        <h4 class="text-warning fw-bold">Rp <?= number_format($stats['Zakat Mal'] ?? 0, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card bg-white border-start border-5 border-info shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted d-block fw-bold mb-1">Penyaluran Infak</small>
                        <h4 class="text-info fw-bold">Rp <?= number_format($stats['Infak'] ?? 0, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card bg-white border-start border-5 border-primary shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted d-block fw-bold mb-1">Penyaluran Kas Masjid</small>
                        <h4 class="text-primary fw-bold">Rp <?= number_format($stats['Kas Masjid'] ?? 0, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body table-responsive">
                <table id="tabelPenyaluran" class="table table-striped table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="12%">Tanggal</th>
                            <th width="15%">Sumber Dana</th>
                            <th width="20%">Penerima/Mustahik</th>
                            <th width="23%">Keterangan</th>
                            <th width="15%" class="text-end">Jumlah</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($conn, "SELECT * FROM penyaluran_dana ORDER BY tanggal DESC, id_penyaluran DESC");
                        while ($row = mysqli_fetch_assoc($query)) {
                            // Badge color logic
                            $badge = 'bg-secondary';
                            if ($row['jenis_dana'] == 'Zakat Fitrah') $badge = 'bg-success';
                            if ($row['jenis_dana'] == 'Zakat Mal') $badge = 'bg-warning text-dark';
                            if ($row['jenis_dana'] == 'Infak') $badge = 'bg-info text-dark';
                            if ($row['jenis_dana'] == 'Kas Masjid') $badge = 'bg-primary';
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                            <td><span class="badge <?= $badge ?> rounded-pill px-3"><?= $row['jenis_dana'] ?></span></td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($row['penerima']) ?></td>
                            <td><small><?= htmlspecialchars($row['keterangan']) ?></small></td>
                            <td class="text-end fw-bold text-danger">- Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning me-1 rounded-3" onclick='editPenyaluran(<?= json_encode($row) ?>)'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?hapus=<?= $row['id_penyaluran'] ?>" class="btn btn-sm btn-danger rounded-3" onclick="return confirm('Batalkan/hapus penyaluran dana ini?')">
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
    <div class="modal fade" id="modalPenyaluran" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content shadow rounded-4 border-0">
                <form method="POST">
                    <input type="hidden" name="id_edit" id="id_edit">
                    <div class="modal-header bg-danger text-white border-bottom-0 pb-3">
                        <h5 class="modal-title" id="modalTitle"><i class="fas fa-hand-holding-heart me-2"></i>Input Penyaluran Dana</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="mb-3">
                            <label class="form-label text-muted">Tanggal Penyaluran</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control bg-white shadow-sm border-0" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Sumber Dana</label>
                            <select name="jenis_dana" id="jenis_dana" class="form-select bg-white shadow-sm border-0" required>
                                <option value="Zakat Fitrah">Zakat Fitrah</option>
                                <option value="Zakat Mal">Zakat Mal</option>
                                <option value="Infak">Infak</option>
                                <option value="Kas Masjid">Kas Masjid</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Penerima / Mustahik / Keperluan</label>
                            <input type="text" name="penerima" id="penerima" class="form-control bg-white shadow-sm border-0" required placeholder="Contoh: Bpk Ahmad (Fakir), Pembangunan Toilet, dll">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Jumlah Disalurkan (Rp)</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control form-control-lg bg-white shadow-sm border-0 text-danger fw-bold" required min="1" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Keterangan Khusus</label>
                            <textarea name="keterangan" id="keterangan" class="form-control bg-white shadow-sm border-0" rows="3" placeholder="-"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top-0 pt-0">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger px-4 shadow"><i class="fas fa-paper-plane me-2"></i>Salurkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#tabelPenyaluran').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }
            });
        });

        function editPenyaluran(data) {
            document.getElementById('id_edit').value = data.id_penyaluran;
            document.getElementById('tanggal').value = data.tanggal;
            document.getElementById('jenis_dana').value = data.jenis_dana;
            document.getElementById('penerima').value = data.penerima;
            document.getElementById('jumlah').value = data.jumlah;
            document.getElementById('keterangan').value = data.keterangan;
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Penyaluran Dana';
            
            var modal = new bootstrap.Modal(document.getElementById('modalPenyaluran'));
            modal.show();
        }

        document.getElementById('modalPenyaluran').addEventListener('hidden.bs.modal', function () {
            document.getElementById('id_edit').value = '';
            document.getElementById('tanggal').value = '<?= date('Y-m-d') ?>';
            document.getElementById('jenis_dana').value = 'Zakat Fitrah';
            document.getElementById('penerima').value = '';
            document.getElementById('jumlah').value = '';
            document.getElementById('keterangan').value = '';
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-hand-holding-heart me-2"></i>Input Penyaluran Dana';
        });
    </script>
</body>
</html>
