<?php
include '../config/koneksi.php';

/* =====================
   PROSES SIMPAN DATA
===================== */
if (isset($_POST['nama_kepala'])) {

    $nama_kepala = $_POST['nama_kepala'];
    $jenis_zakat = $_POST['jenis_zakat'];
    $tanggal     = $_POST['tanggal'];
    $keterangan  = $_POST['keterangan'];

    // simpan kepala keluarga
    $stmt = $conn->prepare(
        "INSERT INTO zakat_fitrah (id_zakat_fitrah, tanggal,nama_kepala_keluarga,alamat,jumlah_jiwa,harga_beras,total_zakat,id_user,created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())"
    );
    $stmt->bind_param("ssssssss", $nama_kepala, $jenis_zakat, $tanggal, $keterangan);
    $stmt->execute();
    $kepala_id = $stmt->insert_id;

    // simpan anggota
    $stmt2 = $conn->prepare(
        "INSERT INTO zakat_anggota (zakat_kepala_id, nama_anggota, jumlah)
         VALUES (?, ?, ?)"
    );

    foreach ($_POST['nama_anggota'] as $i => $nama) {
        $jumlah = $_POST['jumlah'][$i];
        $stmt2->bind_param("isi", $kepala_id, $nama, $jumlah);
        $stmt2->execute();
    }

    echo "<script>
        alert('Data zakat berhasil disimpan');
        window.location='zakat.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Zakat Masjid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary">Data Zakat Masjid</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalZakat">
            + Tambah Zakat
        </button>
    </div>

    <!-- =====================
         TABEL DATA ZAKAT
    ====================== -->
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Kepala Keluarga</th>
                        <th>Jenis Zakat</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($conn, "
                    SELECT zk.id, zk.nama_kepala, zk.jenis_zakat, zk.tanggal,
                           SUM(za.jumlah) as total
                    FROM zakat_kepala zk
                    JOIN zakat_anggota za ON zk.id = za.zakat_kepala_id
                    GROUP BY zk.id
                ");

                while ($row = mysqli_fetch_assoc($query)) {
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama_kepala'] ?></td>
                        <td><?= $row['jenis_zakat'] ?></td>
                        <td><?= $row['tanggal'] ?></td>
                        <td>Rp <?= number_format($row['total']) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- =====================
     MODAL TAMBAH ZAKAT
===================== -->
<div class="modal fade" id="modalZakat">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <form method="POST">

        <div class="modal-header">
          <h5 class="modal-title">Tambah Data Zakat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="row mb-3">
            <div class="col-md-6">
              <label>Kepala Keluarga</label>
              <input type="text" name="nama_kepala" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Jenis Zakat</label>
              <select name="jenis_zakat" class="form-select" required>
                <option value="">-- Pilih --</option>
                <option>Zakat Fitrah</option>
                <option>Zakat Mal</option>
              </select>
            </div>
          </div>

          <hr>
          <h6>Anggota Keluarga</h6>

          <div id="anggota-wrapper">
            <div class="row mb-2">
              <div class="col-md-7">
                <input type="text" name="nama_anggota[]" class="form-control" placeholder="Nama Anggota" required>
              </div>
              <div class="col-md-4">
                <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required>
              </div>
            </div>
          </div>

          <button type="button" class="btn btn-sm btn-success mb-3" onclick="tambahAnggota()">
            + Tambah Anggota
          </button>

          <div class="row">
            <div class="col-md-6">
              <label>Tanggal</label>
              <input type="date" name="tanggal" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Keterangan</label>
              <textarea name="keterangan" class="form-control"></textarea>
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
    const wrapper = document.getElementById('anggota-wrapper');
    wrapper.insertAdjacentHTML('beforeend', `
        <div class="row mb-2">
            <div class="col-md-7">
                <input type="text" name="nama_anggota[]" class="form-control" placeholder="Nama Anggota" required>
            </div>
            <div class="col-md-4">
                <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required>
            </div>
        </div>
    `);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
