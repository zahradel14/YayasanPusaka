<?php
$id   = $_GET['id'] ?? '';
$edit = false;
$pegawai = [];
$anakList = [];

if ($id) {
    $edit = true;
    $result = $conn->query("SELECT * FROM pegawai WHERE id = $id");
    $pegawai = $result->fetch_assoc();

    $anakResult = $conn->query("SELECT * FROM anak_pegawai WHERE pegawai_id = $id");
    $anakList = $anakResult->fetch_all(MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip          = $_POST['nip'];
    $nama         = $_POST['nama_lengkap'];
    $tempat       = $_POST['tempat_lahir'];
    $tgl_lahir    = !empty($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null;
    $jk           = $_POST['jenis_kelamin'];
    $agama        = $_POST['agama'];
    $alamat       = $_POST['alamat'];
    $telp         = $_POST['no_telp'];
    $email        = $_POST['email'];
    $jabatan      = $_POST['jabatan'];
    $tgl_masuk    = !empty($_POST['tanggal_masuk']) ? $_POST['tanggal_masuk'] : null;
    $status       = $_POST['status'];
    $pasangan     = $_POST['nama_pasangan'];
    $telp_pasangan= $_POST['telp_pasangan'];
    $wali         = $_POST['nama_wali'];
    $telp_wali    = $_POST['telp_wali'];

    // handle upload foto
    $foto = $pegawai['foto'] ?? '';
    if (!empty($_FILES['foto']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = time() . "_" . basename($_FILES['foto']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
            $foto = $fileName;
        }
    }

    if ($id) {
        $stmt = $conn->prepare("UPDATE pegawai SET 
            nip=?, nama_lengkap=?, tempat_lahir=?, tanggal_lahir=?, jenis_kelamin=?, agama=?, alamat=?, no_telp=?, email=?, 
            jabatan=?, tanggal_masuk=?, status=?, nama_pasangan=?, telp_pasangan=?, nama_wali=?, telp_wali=?, foto=? 
            WHERE id=?");
        $stmt->bind_param(
            "sssssssssssssssssi",
            $nip, $nama, $tempat, $tgl_lahir, $jk, $agama, $alamat, $telp, $email,
            $jabatan, $tgl_masuk, $status, $pasangan, $telp_pasangan, $wali, $telp_wali, $foto, $id
        );
        $stmt->execute();

        // hapus anak lama
        $conn->query("DELETE FROM anak_pegawai WHERE pegawai_id = $id");
        $pegawai_id = $id;
    } else {
        $stmt = $conn->prepare("INSERT INTO pegawai 
            (nip, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, alamat, no_telp, email, jabatan, tanggal_masuk, status, nama_pasangan, telp_pasangan, nama_wali, telp_wali, foto) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssssssssssssssss",
            $nip, $nama, $tempat, $tgl_lahir, $jk, $agama, $alamat, $telp, $email,
            $jabatan, $tgl_masuk, $status, $pasangan, $telp_pasangan, $wali, $telp_wali, $foto
        );
        $stmt->execute();
        $pegawai_id = $conn->insert_id;
    }

    // simpan anak
    if (!empty($_POST['anak'])) {
        foreach ($_POST['anak'] as $i => $nama_anak) {
            $tempatLahir = $_POST['tempat_anak'][$i] ?? '';
            $tglLahir    = !empty($_POST['tgl_anak'][$i]) ? $_POST['tgl_anak'][$i] : null;

            if ($nama_anak) {
                $stmt = $conn->prepare("INSERT INTO anak_pegawai (pegawai_id, nama_anak, tempat_lahir, tanggal_lahir) VALUES (?,?,?,?)");
                $stmt->bind_param("isss", $pegawai_id, $nama_anak, $tempatLahir, $tglLahir);
                $stmt->execute();
            }
        }
    }

    echo "<script>alert('Data berhasil disimpan!');window.location='index.php?page=pegawai';</script>";
    exit;
}
?>


<section class="dashboard">
    <div class="card">
        <h2><?= $edit ? 'Edit Pegawai' : 'Tambah Pegawai'; ?></h2>
        <form method="post" enctype="multipart/form-data">

            <!-- Identitas Pegawai -->
            <div class="card-section">
                <h3>1. Identitas Pegawai</h3>
                <div class="form-grid">
                    <label>NIP<input type="text" name="nip" value="<?= $pegawai['nip'] ?? '' ?>" required></label>
                    <label>Nama Lengkap<input type="text" name="nama_lengkap" value="<?= $pegawai['nama_lengkap'] ?? '' ?>" required></label>
                    <label>Tempat Lahir<input type="text" name="tempat_lahir" value="<?= $pegawai['tempat_lahir'] ?? '' ?>"></label>
                    <label>Tanggal Lahir<input type="date" name="tanggal_lahir" value="<?= $pegawai['tanggal_lahir'] ?? '' ?>"></label>
                    <label>Jenis Kelamin
                        <select name="jenis_kelamin">
                            <option value="Laki-laki" <?= ($pegawai['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= ($pegawai['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </label>
                    <label>Agama<input type="text" name="agama" value="<?= $pegawai['agama'] ?? '' ?>"></label>
                    <label>Alamat<input type="text" name="alamat" value="<?= $pegawai['alamat'] ?? '' ?>"></label>
                    <label>No. Telepon<input type="text" name="no_telp" value="<?= $pegawai['no_telp'] ?? '' ?>"></label>
                    <label>Email<input type="email" name="email" value="<?= $pegawai['email'] ?? '' ?>"></label>
                </div>
            </div>

            <!-- Data Kepegawaian -->
            <div class="card-section">
                <h3>2. Data Kepegawaian</h3>
                <div class="form-grid">
                    <label>Jabatan<input type="text" name="jabatan" value="<?= $pegawai['jabatan'] ?? '' ?>"></label>
                    <label>Tanggal Masuk<input type="date" name="tanggal_masuk" value="<?= $pegawai['tanggal_masuk'] ?? '' ?>"></label>
                    <label>Status
                        <select name="status">
                            <option value="Aktif" <?= ($pegawai['status'] ?? '') == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="Tidak Aktif" <?= ($pegawai['status'] ?? '') == 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                    </label>
                    <label>Foto Pegawai
                        <input type="file" name="foto">
                        <?php if (!empty($pegawai['foto'])): ?>
                            <br><img src="uploads/<?= htmlspecialchars($pegawai['foto']) ?>" style="max-width:100px; margin-top:8px;">
                        <?php endif; ?>
                    </label>
                </div>
            </div>

            <!-- Data Keluarga -->
            <div class="card-section">
                <h3>3. Data Keluarga</h3>
                <div class="form-grid">
                    <label>Nama Pasangan<input type="text" name="nama_pasangan" value="<?= $pegawai['nama_pasangan'] ?? '' ?>"></label>
                    <label>No. Telp Pasangan<input type="text" name="telp_pasangan" value="<?= $pegawai['telp_pasangan'] ?? '' ?>"></label>
                    <label>Nama Wali<input type="text" name="nama_wali" value="<?= $pegawai['nama_wali'] ?? '' ?>"></label>
                    <label>No. Telp Wali<input type="text" name="telp_wali" value="<?= $pegawai['telp_wali'] ?? '' ?>"></label>
                </div>
            </div>

            <!-- Data Anak -->
            <div class="card-section">
                <h3>4. Data Anak</h3>
                <table class="table-anak">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Nama Anak</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th style="width:70px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="anak-container">
                        <?php if (!empty($anakList)): ?>
                            <?php foreach ($anakList as $i => $anak): ?>
                                <tr class="anak-row">
                                    <td><?= $i+1 ?></td>
                                    <td><input type="text" name="anak[]" value="<?= htmlspecialchars($anak['nama_anak']) ?>" required></td>
                                    <td><input type="text" name="tempat_anak[]" value="<?= htmlspecialchars($anak['tempat_lahir']) ?>"></td>
                                    <td><input type="date" name="tgl_anak[]" value="<?= htmlspecialchars($anak['tanggal_lahir']) ?>"></td>
                                    <td><button type="button" class="btn-remove" onclick="hapusAnak(this)">✖</button></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="anak-row">
                                <td>1</td>
                                <td><input type="text" name="anak[]" placeholder="Nama Anak" required></td>
                                <td><input type="text" name="tempat_anak[]" placeholder="Tempat Lahir"></td>
                                <td><input type="date" name="tgl_anak[]"></td>
                                <td><button type="button" class="btn-remove" onclick="hapusAnak(this)">✖</button></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <button type="button" class="btn-small" onclick="tambahAnak()">+ Tambah Anak</button>
            </div>

            <!-- Tombol -->
            <div class="form-actions">
                <button type="submit" class="btn-small">Simpan</button>
                <a href="index.php?page=pegawai" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</section>

<script>
function tambahAnak() {
    const container = document.getElementById('anak-container');
    const index = container.querySelectorAll('tr').length + 1;
    const row = document.createElement('tr');
    row.className = 'anak-row';
    row.innerHTML = `
        <td>${index}</td>
        <td><input type="text" name="anak[]" placeholder="Nama Anak" required></td>
        <td><input type="text" name="tempat_anak[]" placeholder="Tempat Lahir"></td>
        <td><input type="date" name="tgl_anak[]"></td>
        <td><button type="button" class="btn-remove" onclick="hapusAnak(this)">✖</button></td>
    `;
    container.appendChild(row);
}

function hapusAnak(btn) {
    const row = btn.closest('tr');
    row.remove();
    const rows = document.querySelectorAll('#anak-container tr');
    rows.forEach((r, i) => {
        r.querySelector('td:first-child').textContent = i+1;
    });
}
</script>

<style>
/* Grid utama */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 15px;
}
.card-section {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    margin-bottom: 25px;
}
.card-section h3 { margin-bottom: 15px; }
.card-section label { font-weight: 600; display: block; font-size: 14px; }
.card-section input, .card-section select {
    width: 100%; padding: 8px 10px; margin-top: 5px;
    border: 1px solid #ccc; border-radius: 6px;
}

/* Table Anak */
.table-anak { width: 100%; border-collapse: collapse; margin-top: 10px; }
.table-anak th, .table-anak td {
    border: 1px solid #ddd; padding: 8px; text-align: left;
}
.table-anak th { background: #2e59d9; color: #fff; }

/* Tombol */
.btn-small {
    background: #2e59d9; color: #fff;
    padding: 7px 14px; border: none; border-radius: 6px;
    cursor: pointer; font-size: 14px;
}
.btn-small:hover { background: #1c3fad; }
.btn-remove {
    background: #e74a3b; color: #fff; border: none;
    width: 28px; height: 28px; border-radius: 50%;
    cursor: pointer; font-size: 16px; line-height: 0;
}
.btn-remove:hover { background: #c0392b; }
.form-actions { margin-top: 20px; text-align: center; }
.btn-cancel { margin-left: 10px; color: red; text-decoration: none; }
</style>
