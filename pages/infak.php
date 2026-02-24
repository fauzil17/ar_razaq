<?php
session_start();
include '../config/koneksi.php';

// Proses Delete
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM infak WHERE id_infak=$id");
    header("Location: infak.php");
    exit;
}

// Proses Simpan / Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_edit = $_POST['id_edit'] ?? '';
    $tanggal = $_POST['tanggal'];
    $nama_pemberi = $_POST['nama_pemberi'] ?: 'Hamba Allah';
    $jumlah = floatval($_POST['jumlah']);
    $keterangan = $_POST['keterangan'];

    if ($id_edit != '') {
        $stmt = $conn->prepare("UPDATE infak SET tanggal=?, nama_pemberi=?, jumlah=?, keterangan=? WHERE id_infak=?");
        $stmt->bind_param("ssdsi", $tanggal, $nama_pemberi, $jumlah, $keterangan, $id_edit);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO infak (tanggal, nama_pemberi, jumlah, keterangan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $tanggal, $nama_pemberi, $jumlah, $keterangan);
        $stmt->execute();
    }
    header("Location: infak.php");
    exit;
}

// Ambil Total Infak
$query_total = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM infak");
$row_total = mysqli_fetch_assoc($query_total);
$total_infak = $row_total['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerimaan Infak - Website Masjid</title>
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
                <h2 class="text-primary fw-bold" style="font-family: var(--bs-body-font-family)"><i class="fas fa-hand-holding-water text-primary me-2"></i>Penerimaan Infak</h2>
                <p class="text-muted">Pencatatan dana infak jemaah masjid</p>
            </div>
            <div>
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalInfak">
                    <i class="fas fa-plus me-1"></i> Tambah Infak
                </button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-success text-white shadow border-0 rounded-4">
                    <div class="card-body p-4 text-center">
                        <i class="fas fa-hand-holding-usd fa-3x mb-3 opacity-50"></i>
                        <h5>Total Dana Infak</h5>
                        <h2 class="fw-bold fs-2 mb-0">Rp <?= number_format($total_infak, 0, ',', '.') ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body table-responsive">
                <table id="tabelInfak" class="table table-striped table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="20%">Nama Pemberi</th>
                            <th width="30%">Keterangan</th>
                            <th width="15%" class="text-end">Jumlah</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($conn, "SELECT * FROM infak ORDER BY tanggal DESC, id_infak DESC");
                        while ($row = mysqli_fetch_assoc($query)) {
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($row['nama_pemberi']) ?></td>
                            <td><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td class="text-end fw-bold text-success">+ Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning me-1 rounded-3" onclick='editInfak(<?= json_encode($row) ?>)'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?hapus=<?= $row['id_infak'] ?>" class="btn btn-sm btn-danger rounded-3" onclick="return confirm('Hapus data infak ini?')">
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
    <div class="modal fade" id="modalInfak" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content shadow rounded-4 border-0">
                <form method="POST">
                    <input type="hidden" name="id_edit" id="id_edit">
                    <div class="modal-header bg-primary text-white border-bottom-0 pb-3">
                        <h5 class="modal-title" id="modalTitle"><i class="fas fa-plus-circle me-2"></i>Tambah Data Infak</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="mb-3">
                            <label class="form-label text-muted">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control bg-white shadow-sm border-0" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama Pemberi</label>
                            <input type="text" name="nama_pemberi" id="nama_pemberi" class="form-control bg-white shadow-sm border-0" placeholder="(Kosongkan jika Hamba Allah)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Jumlah (Rp)</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control form-control-lg bg-white shadow-sm border-0 text-success fw-bold" required min="1" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Keterangan / Peruntukan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control bg-white shadow-sm border-0" rows="3" placeholder="Contoh: Pembangunan Masjid, Yatim, dll"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top-0 pt-0">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 shadow"><i class="fas fa-save me-2"></i>Simpan</button>
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
            $('#tabelInfak').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }
            });
        });

        function editInfak(data) {
            document.getElementById('id_edit').value = data.id_infak;
            document.getElementById('tanggal').value = data.tanggal;
            document.getElementById('nama_pemberi').value = data.nama_pemberi;
            document.getElementById('jumlah').value = data.jumlah;
            document.getElementById('keterangan').value = data.keterangan;
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Data Infak';
            
            var modal = new bootstrap.Modal(document.getElementById('modalInfak'));
            modal.show();
        }

        document.getElementById('modalInfak').addEventListener('hidden.bs.modal', function () {
            document.getElementById('id_edit').value = '';
            document.getElementById('tanggal').value = '<?= date('Y-m-d') ?>';
            document.getElementById('nama_pemberi').value = '';
            document.getElementById('jumlah').value = '';
            document.getElementById('keterangan').value = '';
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle me-2"></i>Tambah Data Infak';
        });
    </script>
</body>
</html>
