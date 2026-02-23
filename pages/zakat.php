<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../config/koneksi.php';

/* ================= DELETE ================= */
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM zakat_fitrah_anggota WHERE id_zakat_fitrah=$id");
    mysqli_query($conn, "DELETE FROM zakat_fitrah WHERE id_zakat_fitrah=$id");
    header("Location: zakat.php");
    exit;
}

/* ================= AMBIL DATA EDIT ================= */
$data_edit = null;
$anggota_edit = [];
$kepala_edit = '';

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);

    $stmt = $conn->prepare("SELECT * FROM zakat_fitrah WHERE id_zakat_fitrah=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_edit = $result->fetch_assoc();

    $stmt2 = $conn->prepare("SELECT nama_anggota FROM zakat_fitrah_anggota WHERE id_zakat_fitrah=?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    $first = true;
    while ($row2 = $result2->fetch_assoc()) {
        if ($first) {
            $kepala_edit = $row2['nama_anggota'];
            $first = false;
        } else {
            $anggota_edit[] = $row2['nama_anggota'];
        }
    }
}

/* ================= SIMPAN ================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_edit     = $_POST['id_edit'] ?? '';
    $jenis_zakat = $_POST['jenis_zakat'];
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

        $stmt = $conn->prepare("
            UPDATE zakat_fitrah 
            SET tanggal=?, jumlah_jiwa=?, harga_beras=?, total_zakat=?, jenis_zakat=?, keterangan=?
            WHERE id_zakat_fitrah=?
        ");

        $stmt->bind_param("siddssi",
            $tanggal,
            $jumlah_jiwa,
            $harga_beras,
            $total_zakat,
            $jenis_zakat,
            $keterangan,
            $id_edit
        );

        $stmt->execute();

        mysqli_query($conn, "DELETE FROM zakat_fitrah_anggota WHERE id_zakat_fitrah=$id_edit");

        $stmt2 = $conn->prepare("
            INSERT INTO zakat_fitrah_anggota (id_zakat_fitrah, nama_anggota)
            VALUES (?, ?)
        ");

        foreach ($anggota as $nama) {
            $stmt2->bind_param("is", $id_edit, $nama);
            $stmt2->execute();
        }

    } else {

        $stmt = $conn->prepare("
            INSERT INTO zakat_fitrah
            (tanggal, jumlah_jiwa, harga_beras, total_zakat, jenis_zakat, keterangan)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("siddss",
            $tanggal,
            $jumlah_jiwa,
            $harga_beras,
            $total_zakat,
            $jenis_zakat,
            $keterangan
        );

        $stmt->execute();
        $id_zakat = $stmt->insert_id;

        $stmt2 = $conn->prepare("
            INSERT INTO zakat_fitrah_anggota (id_zakat_fitrah, nama_anggota)
            VALUES (?, ?)
        ");

        foreach ($anggota as $nama) {
            $stmt2->bind_param("is", $id_zakat, $nama);
            $stmt2->execute();
        }
    }

    header("Location: zakat.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Data Zakat</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">
<h3>Data Zakat Masjid</h3>
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalZakat">
+ Tambah Zakat
</button>
</div>

<div class="card">
<div class="card-body table-responsive">

<table class="table table-bordered text-center">
<thead class="table-primary">
<tr>
<th>No</th>
<th>Kepala Keluarga</th>
<th>Tanggal</th>
<th>Jenis</th>
<th>Jumlah Jiwa</th>
<th>Total</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>

<?php
$no = 1;
$query = mysqli_query($conn, "
SELECT z.*,
(SELECT nama_anggota 
 FROM zakat_fitrah_anggota 
 WHERE id_zakat_fitrah = z.id_zakat_fitrah 
 LIMIT 1) AS kepala
FROM zakat_fitrah z
ORDER BY z.id_zakat_fitrah DESC
");

while ($row = mysqli_fetch_assoc($query)) {
?>

<tr>
<td><?= $no++ ?></td>
<td><strong><?= $row['kepala'] ?></strong></td>
<td><?= $row['tanggal'] ?></td>
<td><?= $row['jenis_zakat'] ?></td>
<td><?= $row['jumlah_jiwa'] ?></td>
<td>Rp <?= number_format($row['total_zakat'],0,',','.') ?></td>
<td>
<a href="?edit=<?= $row['id_zakat_fitrah'] ?>" class="btn btn-sm btn-warning">
<i class="bi bi-pencil"></i>
</a>
<a href="?hapus=<?= $row['id_zakat_fitrah'] ?>" 
class="btn btn-sm btn-danger"
onclick="return confirm('Yakin hapus?')">
<i class="bi bi-trash"></i>
</a>
</td>
</tr>

<?php } ?>

</tbody>
</table>

</div>
</div>
</div>

<!-- MODAL -->
<div class="modal fade" id="modalZakat">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<form method="POST">
<input type="hidden" name="id_edit" value="<?= $data_edit['id_zakat_fitrah'] ?? '' ?>">

<div class="modal-header">
<h5 class="modal-title">
<?= isset($_GET['edit']) ? 'Edit Data Zakat' : 'Tambah Data Zakat' ?>
</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<h6>Kepala Keluarga</h6>
<input type="text" name="kepala_keluarga" class="form-control mb-3"
value="<?= $kepala_edit ?>"
placeholder="Nama Kepala Keluarga" required>

<hr>
<h6>Anggota Keluarga</h6>

<div id="anggota-wrapper">
<?php if (!empty($anggota_edit)) {
foreach ($anggota_edit as $nama) { ?>
<div class="row mb-2">
<div class="col-md-10">
<input type="text" name="nama_anggota[]" class="form-control" value="<?= $nama ?>">
</div>
<div class="col-md-2">
<button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()">X</button>
</div>
</div>
<?php }} ?>
</div>

<button type="button" class="btn btn-success btn-sm mt-2" onclick="tambahAnggota()">
+ Tambah Anggota
</button>

<hr>

<div class="row">
<div class="col-md-4">
<label>Tanggal</label>
<input type="date" name="tanggal" class="form-control"
value="<?= $data_edit['tanggal'] ?? '' ?>" required>
</div>

<div class="col-md-4">
<label>Harga Zakat per Jiwa</label>
<input type="number" name="harga_beras" class="form-control"
value="<?= $data_edit['harga_beras'] ?? '' ?>" required>
</div>

<div class="col-md-4">
<label>Keterangan</label>
<textarea name="keterangan" class="form-control"><?= $data_edit['keterangan'] ?? '' ?></textarea>
</div>
</div>

</div>

<div class="modal-footer">
<button type="submit" class="btn btn-primary">Simpan</button>
</div>

</form>
</div>
</div>
</div>

<script>
function tambahAnggota() {
document.getElementById('anggota-wrapper').insertAdjacentHTML('beforeend', `
<div class="row mb-2">
<div class="col-md-10">
<input type="text" name="nama_anggota[]" class="form-control" placeholder="Nama Anggota">
</div>
<div class="col-md-2">
<button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()">X</button>
</div>
</div>
`);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php if (isset($_GET['edit'])) { ?>
<script>
var myModal = new bootstrap.Modal(document.getElementById('modalZakat'));
myModal.show();
</script>
<?php } ?>

</body>
</html>