<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Input Zakat Fitrah</h5>
        </div>

        <div class="card-body">
            <form action="simpan_zakat_fitrah.php" method="POST">

                <!-- Kepala Keluarga -->
                <div class="mb-3">
                    <label class="form-label">Nama Kepala Keluarga</label>
                    <input type="text" name="kepala_keluarga" class="form-control" required>
                </div>

                <!-- Alamat -->
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control"></textarea>
                </div>

                <!-- Jumlah Jiwa -->
                <div class="mb-3">
                    <label class="form-label">Jumlah Jiwa</label>
                    <input type="number" name="jumlah_jiwa" id="jumlahJiwa" class="form-control" min="1" required>
                </div>

                <!-- Harga Beras -->
                <div class="mb-3">
                    <label class="form-label">Harga Zakat per Jiwa (Rp)</label>
                    <input type="number" name="harga_beras" class="form-control" required>
                </div>

                <!-- Anggota Keluarga -->
                <div class="mb-3">
                    <label class="form-label">Anggota Keluarga</label>
                    <div id="anggotaContainer"></div>
                </div>

                <button type="submit" class="btn btn-success">
                    Simpan Zakat Fitrah
                </button>
            </form>
        </div>
    </div>
</div>

<!-- JS -->
<script>
document.getElementById('jumlahJiwa').addEventListener('change', function () {
    let jumlah = this.value;
    let container = document.getElementById('anggotaContainer');
    container.innerHTML = '';

    for (let i = 1; i <= jumlah; i++) {
        container.innerHTML += `
            <input type="text"
                   name="anggota[]"
                   class="form-control mb-2"
                   placeholder="Nama Anggota ke-${i}"
                   required>
        `;
    }
});
</script>

<?php include '../includes/footer.php'; ?>
