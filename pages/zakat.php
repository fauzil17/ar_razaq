<?php
session_start();
include '../config/koneksi.php';

/* ================= ZAKAT FITRAH: DELETE & SAVE ================= */
if (isset($_GET['hapus_fitrah'])) {
    $id = intval($_GET['hapus_fitrah']);
    mysqli_query($conn, "DELETE FROM zakat_fitrah_anggota WHERE id_zakat_fitrah=$id");
    mysqli_query($conn, "DELETE FROM zakat_fitrah WHERE id_zakat_fitrah=$id");
    header("Location: zakat.php?tab=fitrah");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action_fitrah'])) {
    $id_edit     = $_POST['id_edit'] ?? '';
    // $jenis_zakat = $_POST['jenis_zakat']; // Biasanya beras/uang, saya asumsikan fix di keterangan/kategori
    $jenis_zakat = 'Beras/Uang'; 
    $tanggal     = $_POST['tanggal'];
    $keterangan  = $_POST['keterangan'];
    $harga_beras = floatval($_POST['harga_beras']);
    $kepala      = trim($_POST['kepala_keluarga']);

    $anggota = [];
    if ($kepala != '') {
        $anggota[] = $kepala;
    }
    if (!empty($_POST['nama_anggota'])) {
        foreach ($_POST['nama_anggota'] as $a) {
            if (trim($a) != '') {
                $anggota[] = trim($a);
            }
        }
    }

    $jumlah_jiwa = count($anggota);
    $total_zakat = $jumlah_jiwa * $harga_beras;

    if ($id_edit != '') {
        $stmt = $conn->prepare("UPDATE zakat_fitrah SET tanggal=?, jumlah_jiwa=?, harga_beras=?, total_zakat=?, jenis_zakat=?, keterangan=? WHERE id_zakat_fitrah=?");
        $stmt->bind_param("siddssi", $tanggal, $jumlah_jiwa, $harga_beras, $total_zakat, $jenis_zakat, $keterangan, $id_edit);
        $stmt->execute();

        mysqli_query($conn, "DELETE FROM zakat_fitrah_anggota WHERE id_zakat_fitrah=$id_edit");
        $stmt2 = $conn->prepare("INSERT INTO zakat_fitrah_anggota (id_zakat_fitrah, nama_anggota) VALUES (?, ?)");
        foreach ($anggota as $nama) {
            $stmt2->bind_param("is", $id_edit, $nama);
            $stmt2->execute();
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO zakat_fitrah (tanggal, jumlah_jiwa, harga_beras, total_zakat, jenis_zakat, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siddss", $tanggal, $jumlah_jiwa, $harga_beras, $total_zakat, $jenis_zakat, $keterangan);
        $stmt->execute();
        $id_zakat = $stmt->insert_id;

        $stmt2 = $conn->prepare("INSERT INTO zakat_fitrah_anggota (id_zakat_fitrah, nama_anggota) VALUES (?, ?)");
        foreach ($anggota as $nama) {
            $stmt2->bind_param("is", $id_zakat, $nama);
            $stmt2->execute();
        }
    }
    header("Location: zakat.php?tab=fitrah");
    exit;
}

/* ================= ZAKAT MAL: DELETE & SAVE ================= */
if (isset($_GET['hapus_mal'])) {
    $id = intval($_GET['hapus_mal']);
    mysqli_query($conn, "DELETE FROM zakat_mal WHERE id_zakat_mal=$id");
    header("Location: zakat.php?tab=mal");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action_mal'])) {
    $id_edit       = $_POST['id_edit_mal'] ?? '';
    $tanggal       = $_POST['tanggal_mal'];
    $nama_pembayar = $_POST['nama_pembayar'];
    $jenis_harta   = $_POST['jenis_harta'];
    $nilai_harta   = floatval($_POST['nilai_harta']);
    $nisab         = floatval($_POST['nisab']);
    
    // Zakat Mal = 2.5% dari nilai harta (jika mencapai nisab)
    $total_zakat = 0;
    if ($nilai_harta >= $nisab) {
        $total_zakat = $nilai_harta * 0.025;
    }

    if ($id_edit != '') {
        $stmt = $conn->prepare("UPDATE zakat_mal SET tanggal=?, nama_pembayar=?, jenis_harta=?, nilai_harta=?, nisab=?, total_zakat=? WHERE id_zakat_mal=?");
        $stmt->bind_param("sssdddi", $tanggal, $nama_pembayar, $jenis_harta, $nilai_harta, $nisab, $total_zakat, $id_edit);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO zakat_mal (tanggal, nama_pembayar, jenis_harta, nilai_harta, nisab, total_zakat) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssddd", $tanggal, $nama_pembayar, $jenis_harta, $nilai_harta, $nisab, $total_zakat);
        $stmt->execute();
    }
    header("Location: zakat.php?tab=mal");
    exit;
}

// Menentukan tab yang aktif (default: fitrah)
$active_tab = isset($_GET['tab']) && $_GET['tab'] == 'mal' ? 'mal' : 'fitrah';

// AMBIL DATA TOTAL UNTUK SUMMARY
$q_fitrah = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_zakat) as total FROM zakat_fitrah"));
$total_fitrah = $q_fitrah['total'] ?? 0;

$q_mal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_zakat) as total FROM zakat_mal"));
$total_mal = $q_mal['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerimaan Zakat - Website Masjid</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5 pb-5">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="text-primary fw-bold" style="font-family: var(--bs-body-font-family)"><i class="fas fa-hand-holding-heart text-primary me-2"></i>Penerimaan Zakat</h2>
                <p class="text-muted">Kelola data penerimaan Zakat Fitrah dan Zakat Mal jemaah.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-inline-block bg-white shadow-sm p-3 rounded-3 border-start border-5 border-success text-start me-2 mb-2">
                    <small class="text-muted d-block fw-bold">Total Zakat Fitrah</small>
                    <span class="fs-4 fw-bold text-success">Rp <?= number_format($total_fitrah, 0, ',', '.') ?></span>
                </div>
                <div class="d-inline-block bg-white shadow-sm p-3 rounded-3 border-start border-5 border-warning text-start mb-2">
                    <small class="text-muted d-block fw-bold">Total Zakat Mal</small>
                    <span class="fs-4 fw-bold text-warning">Rp <?= number_format($total_mal, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white pt-4 pb-0 border-bottom-0">
                <ul class="nav nav-tabs border-bottom-0" id="zakatTab" role="tablist">
                    <li class="nav-item border-bottom-0" role="presentation">
                        <button class="nav-link fs-5 fw-bold <?= $active_tab == 'fitrah' ? 'active text-primary border-bottom-0 rounded-top' : 'text-muted' ?>" id="fitrah-tab" data-bs-toggle="tab" data-bs-target="#fitrah" type="button" role="tab" onclick="changeTabUrl('fitrah')">
                            <i class="fas fa-seedling me-2"></i>Zakat Fitrah
                        </button>
                    </li>
                    <li class="nav-item border-bottom-0" role="presentation">
                        <button class="nav-link fs-5 fw-bold <?= $active_tab == 'mal' ? 'active text-warning border-bottom-0 rounded-top' : 'text-muted' ?>" id="mal-tab" data-bs-toggle="tab" data-bs-target="#mal" type="button" role="tab" onclick="changeTabUrl('mal')">
                            <i class="fas fa-coins me-2"></i>Zakat Mal
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="card-body bg-white pt-4">
                <div class="tab-content" id="zakatTabContent">
                    
                    <!-- TAB ZAKAT FITRAH -->
                    <div class="tab-pane fade <?= $active_tab == 'fitrah' ? 'show active' : '' ?>" id="fitrah" role="tabpanel">
                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalFitrah">
                                <i class="fas fa-plus me-1"></i> Tambah Zakat Fitrah
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="tabelFitrah" class="table table-striped table-hover align-middle w-100">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Kepala Keluarga</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah Jiwa</th>
                                        <th>Total Zakat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $query = mysqli_query($conn, "
                                    SELECT z.*,
                                    (SELECT nama_anggota FROM zakat_fitrah_anggota WHERE id_zakat_fitrah = z.id_zakat_fitrah LIMIT 1) AS kepala
                                    FROM zakat_fitrah z ORDER BY z.tanggal DESC, z.id_zakat_fitrah DESC");
                                    while ($row = mysqli_fetch_assoc($query)) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="fw-bold text-primary"><?= htmlspecialchars($row['kepala']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                        <td><span class="badge bg-info text-dark rounded-pill"><?= $row['jumlah_jiwa'] ?> Jiwa</span></td>
                                        <td class="text-success fw-bold">Rp <?= number_format($row['total_zakat'], 0, ',', '.') ?></td>
                                        <td class="text-center">
                                            <!-- Edit Fitrah akan reload page karena strukturnya rumit (array iterasi JS), kita disable atau pisahkan khusus -->
                                            <!-- Aksi khusus modal dihapus untuk penyederhanaan UI atau load ajax. Hapus saja -->
                                            <a href="?hapus_fitrah=<?= $row['id_zakat_fitrah'] ?>" class="btn btn-sm btn-danger rounded-3" onclick="return confirm('Hapus Zakat Fitrah ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB ZAKAT MAL -->
                    <div class="tab-pane fade <?= $active_tab == 'mal' ? 'show active' : '' ?>" id="mal" role="tabpanel">
                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#modalMal">
                                <i class="fas fa-plus me-1"></i> Tambah Zakat Mal
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="tabelMal" class="table table-striped table-hover align-middle w-100">
                                <thead style="background-color: var(--accent-color); color: white;">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Muzakki</th>
                                        <th>Tanggal</th>
                                        <th>Jenis Harta</th>
                                        <th>Nilai Harta</th>
                                        <th>Total Zakat (2.5%)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $query_mal = mysqli_query($conn, "SELECT * FROM zakat_mal ORDER BY tanggal DESC, id_zakat_mal DESC");
                                    while ($row = mysqli_fetch_assoc($query_mal)) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="fw-bold text-dark"><?= htmlspecialchars($row['nama_pembayar']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($row['jenis_harta']) ?></span></td>
                                        <td>Rp <?= number_format($row['nilai_harta'], 0, ',', '.') ?></td>
                                        <td class="text-success fw-bold">Rp <?= number_format($row['total_zakat'], 0, ',', '.') ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning rounded-3 me-1 text-white" onclick='editMal(<?= json_encode($row) ?>)'>
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="?hapus_mal=<?= $row['id_zakat_mal'] ?>" class="btn btn-sm btn-danger rounded-3" onclick="return confirm('Hapus Zakat Mal ini?')">
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
            </div>
        </div>
    </div>

    <!-- MODAL ZAKAT FITRAH -->
    <div class="modal fade" id="modalFitrah" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <form method="POST">
                    <input type="hidden" name="action_fitrah" value="1">
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title"><i class="fas fa-seedling me-2"></i>Tambah Zakat Fitrah</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3">Data Pokok</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">Tanggal</label>
                                        <input type="date" name="tanggal" class="form-control bg-light border-0 shadow-sm" required value="<?= date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">Kepala Keluarga</label>
                                        <input type="text" name="kepala_keluarga" class="form-control bg-light border-0 shadow-sm" placeholder="Nama Kepala Keluarga" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3 d-flex justify-content-between align-items-center">
                                    Daftar Anggota Keluarga (Tanggungan)
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="tambahAnggota()">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </h6>
                                <div id="anggota-wrapper" class="vstack gap-2">
                                    <!-- Dynamic fields here -->
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">Harga Beras per Jiwa (Rp)</label>
                                        <input type="number" name="harga_beras" class="form-control bg-light border-0 shadow-sm" required value="40000">
                                        <small class="text-muted">Standar harga beras 2.5kg</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">Keterangan</label>
                                        <input type="text" name="keterangan" class="form-control bg-light border-0 shadow-sm" placeholder="-">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 shadow"><i class="fas fa-save me-2"></i>Simpan Target</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL ZAKAT MAL -->
    <div class="modal fade" id="modalMal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <form method="POST">
                    <input type="hidden" name="action_mal" value="1">
                    <input type="hidden" name="id_edit_mal" id="id_edit_mal">
                    <div class="modal-header bg-warning text-white border-0">
                        <h5 class="modal-title" id="titleMal"><i class="fas fa-coins me-2"></i>Tambah Zakat Mal</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Terbayar</label>
                                <input type="date" name="tanggal_mal" id="tanggal_mal" class="form-control shadow-sm border-0" required value="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Muzakki (Pembayar)</label>
                                <input type="text" name="nama_pembayar" id="nama_pembayar" class="form-control shadow-sm border-0" required placeholder="Nama Lengkap">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Harta</label>
                                <select name="jenis_harta" id="jenis_harta" class="form-select shadow-sm border-0" required>
                                    <option value="Uang Tunai / Tabungan">Uang Tunai / Tabungan</option>
                                    <option value="Emas / Perak">Emas / Perak</option>
                                    <option value="Hasil Perniagaan">Hasil Perniagaan</option>
                                    <option value="Pertanian / Panen">Pertanian / Panen</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Standar Nisab Saat Ini (Rp)</label>
                                <input type="number" name="nisab" id="nisab" class="form-control shadow-sm border-0 bg-white" required value="85000000">
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle"></i> Asumsi ±85 gram emas</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-primary">Total Nilai Harta (Rp)</label>
                                <input type="number" name="nilai_harta" id="nilai_harta" class="form-control form-control-lg shadow-sm border-0 text-success fw-bold" required placeholder="Masukkan total kekayaan" oninput="hitungZakatMal()">
                            </div>
                            
                            <div class="col-12 text-center mt-4 p-3 rounded-3" style="background-color: #e0f2fe; border: 1px dashed #38bdf8;">
                                <h6 class="text-muted mb-1">Estimasi Kewajiban Zakat (2.5%)</h6>
                                <h2 id="estimasiMal" class="text-primary fw-bold mb-0">Rp 0</h2>
                                <small id="statusNisab" class="text-danger fw-bold mt-2 d-none"><i class="fas fa-times-circle"></i> Harta belum mencapai Nisab</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-white px-4 shadow" id="btnSimpanMal"><i class="fas fa-save me-2"></i>Simpan</button>
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
            $('#tabelFitrah, #tabelMal').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }
            });
        });

        // Push state push untuk UI tab refresh
        function changeTabUrl(tab) {
            window.history.pushState(null, null, '?tab=' + tab);
        }

        // JS Logic untuk Zakat Fitrah (Tambah anggota)
        function tambahAnggota() {
            const wrapper = document.getElementById('anggota-wrapper');
            const row = document.createElement('div');
            row.className = 'input-group mb-2 shadow-sm';
            row.innerHTML = `
                <span class="input-group-text bg-white border-0"><i class="fas fa-user-circle text-muted"></i></span>
                <input type="text" name="nama_anggota[]" class="form-control border-0 bg-white" placeholder="Nama Anggota (Istri/Anak/dll)">
                <button type="button" class="btn btn-danger border-0" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
            `;
            wrapper.appendChild(row);
        }

        // Coba Add Minimal 1 pada form buka
        document.getElementById('modalFitrah').addEventListener('shown.bs.modal', function () {
            if(document.getElementById('anggota-wrapper').children.length === 0) {
                tambahAnggota();
            }
        });

        // JS Logic untuk Zakat Mal (Kalkulator instan)
        function hitungZakatMal() {
            let harta = parseFloat(document.getElementById('nilai_harta').value) || 0;
            let nisab = parseFloat(document.getElementById('nisab').value) || 0;
            let estimasi = document.getElementById('estimasiMal');
            let statusDiv = document.getElementById('statusNisab');
            let btn = document.getElementById('btnSimpanMal');

            if (harta >= nisab) {
                let zakat = harta * 0.025;
                estimasi.innerText = 'Rp ' + zakat.toLocaleString('id-ID');
                estimasi.className = 'text-success fw-bold mb-0';
                statusDiv.classList.add('d-none');
                btn.disabled = false;
            } else {
                estimasi.innerText = 'Rp 0';
                estimasi.className = 'text-muted fw-bold mb-0';
                statusDiv.classList.remove('d-none');
                if(harta > 0) btn.disabled = true; // Block jika belum nisab
            }
        }

        function editMal(data) {
            document.getElementById('id_edit_mal').value = data.id_zakat_mal;
            document.getElementById('tanggal_mal').value = data.tanggal;
            document.getElementById('nama_pembayar').value = data.nama_pembayar;
            document.getElementById('jenis_harta').value = data.jenis_harta;
            document.getElementById('nilai_harta').value = data.nilai_harta;
            document.getElementById('nisab').value = data.nisab;
            document.getElementById('titleMal').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Zakat Mal';
            hitungZakatMal();
            var modal = new bootstrap.Modal(document.getElementById('modalMal'));
            modal.show();
        }

        document.getElementById('modalMal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('id_edit_mal').value = '';
            document.getElementById('nama_pembayar').value = '';
            document.getElementById('nilai_harta').value = '';
            document.getElementById('titleMal').innerHTML = '<i class="fas fa-coins me-2"></i>Tambah Zakat Mal';
            hitungZakatMal();
        });
    </script>
</body>
</html>