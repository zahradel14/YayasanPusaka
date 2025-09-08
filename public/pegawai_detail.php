<?php
$id = (int) ($_GET['id'] ?? 0);
$pegawai = $conn->query("SELECT * FROM pegawai WHERE id=$id")->fetch_assoc();
$anakList = $conn->query("SELECT * FROM anak_pegawai WHERE pegawai_id=$id")->fetch_all(MYSQLI_ASSOC);
?>

<div class="pegawai-detail">
    <h2>Detail Pegawai</h2>

    <!-- Profil Utama -->
    <div class="profil-card">
        <div class="foto">
            <?php if (!empty($pegawai['foto'])): ?>
                <img src="uploads/<?= htmlspecialchars($pegawai['foto']) ?>" alt="Foto Pegawai">
            <?php else: ?>
                <div class="no-foto">Tidak ada foto</div>
            <?php endif; ?>
        </div>

        <div class="biodata">
            <div><strong>NIP:</strong> <?= htmlspecialchars($pegawai['nip']) ?></div>
            <div><strong>Nama:</strong> <?= htmlspecialchars($pegawai['nama_lengkap']) ?></div>
            <div><strong>Tempat, Tgl Lahir:</strong> <?= htmlspecialchars($pegawai['tempat_lahir']) ?>, <?= htmlspecialchars($pegawai['tanggal_lahir']) ?></div>
            <div><strong>Jenis Kelamin:</strong> <?= htmlspecialchars($pegawai['jenis_kelamin']) ?></div>
            <div><strong>Agama:</strong> <?= htmlspecialchars($pegawai['agama']) ?></div>
            <div><strong>Alamat:</strong> <?= htmlspecialchars($pegawai['alamat']) ?></div>
            <div><strong>No. Telp:</strong> <?= htmlspecialchars($pegawai['no_telp']) ?></div>
            <div><strong>Email:</strong> <?= htmlspecialchars($pegawai['email']) ?></div>
            <div><strong>Jabatan:</strong> <?= htmlspecialchars($pegawai['jabatan']) ?></div>
            <div><strong>Tanggal Masuk:</strong> <?= htmlspecialchars($pegawai['tanggal_masuk']) ?></div>
            <div><strong>Status:</strong> <?= htmlspecialchars($pegawai['status']) ?></div>
        </div>
    </div>

    <!-- Data Keluarga -->
    <div class="card-section">
        <h3>Data Keluarga</h3>
        <div class="keluarga-grid">
            <p><strong>Nama Pasangan:</strong> <?= htmlspecialchars($pegawai['nama_pasangan'] ?? '-') ?></p>
            <p><strong>No. Telp Pasangan:</strong> <?= htmlspecialchars($pegawai['telp_pasangan'] ?? '-') ?></p>
            <p><strong>Nama Wali:</strong> <?= htmlspecialchars($pegawai['nama_wali'] ?? '-') ?></p>
            <p><strong>No. Telp Wali:</strong> <?= htmlspecialchars($pegawai['telp_wali'] ?? '-') ?></p>
        </div>
    </div>

    <!-- Data Anak -->
    <div class="card-section">
        <h3>Data Anak</h3>
        <?php if (!empty($anakList)): ?>
            <div class="anak-grid">
                <?php foreach ($anakList as $i => $anak): ?>
                    <div class="anak-card">
                        <div class="anak-header">Anak ke-<?= $i+1 ?></div>
                        <p><strong>Nama:</strong> <?= htmlspecialchars($anak['nama_anak'] ?? '-') ?></p>
                        <p><strong>Tempat Lahir:</strong> <?= htmlspecialchars($anak['tempat_lahir'] ?? '-') ?></p>
                        <p><strong>Tanggal Lahir:</strong> <?= htmlspecialchars($anak['tanggal_lahir'] ?? '-') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Tidak ada data anak.</p>
        <?php endif; ?>
    </div>

    <!-- Tombol Aksi -->
    <div class="actions">
        <a href="index.php?page=pegawai_form&id=<?= $pegawai['id'] ?>" class="btn-small">✏️ Edit</a>
        <a href="index.php?page=pegawai" class="btn-cancel">⬅️ Kembali</a>
    </div>
</div>

<style>
.pegawai-detail {
    max-width: 1000px;
    margin: 30px auto;
    font-family: Arial, sans-serif;
    color: #333;
}

.pegawai-detail h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 24px;
    color: #2e59d9;
}

/* Profil */
.profil-card {
    display: flex;
    gap: 25px;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    margin-bottom: 25px;
}

.foto img {
    width: 180px;
    height: 220px;
    object-fit: cover;
    border-radius: 10px;
    border: 3px solid #f0f0f0;
}

.no-foto {
    width: 180px;
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f7f7f7;
    border-radius: 10px;
    color: #777;
    font-size: 14px;
}

.biodata {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px 20px;
    font-size: 15px;
    line-height: 1.5;
}

/* Keluarga */
.keluarga-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px 30px;
    font-size: 15px;
    line-height: 1.5;
}

/* Card Section */
.card-section {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

.card-section h3 {
    margin-bottom: 15px;
    font-size: 18px;
    color: #444;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 6px;
}

/* Anak */
.anak-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 15px;
}

.anak-card {
    background: #fdfdfd;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.anak-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.anak-header {
    font-weight: 600;
    margin-bottom: 10px;
    color: #2e59d9;
}

/* Actions */
.actions {
    text-align: center;
    margin-top: 25px;
}

.btn-small {
    background: #2e59d9;
    color: #fff;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    margin-right: 10px;
    transition: background 0.2s;
}

.btn-small:hover {
    background: #224abe;
}

.btn-cancel {
    color: #e74a3b;
    text-decoration: none;
    font-weight: bold;
}

.btn-cancel:hover {
    text-decoration: underline;
}
</style>
