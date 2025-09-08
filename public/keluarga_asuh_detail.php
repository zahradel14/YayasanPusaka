<?php
$id = (int) ($_GET['id'] ?? 0);
if (!$id) {
    echo "<p style='color:red;'>Data tidak ditemukan.</p>";
    exit;
}

// Data keluarga asuh
$keluarga = $conn->query("SELECT * FROM keluarga_asuh WHERE id = $id")->fetch_assoc();
if (!$keluarga) {
    echo "<p style='color:red;'>Data tidak ditemukan.</p>";
    exit;
}

// Data anak asuh
$anak = $conn->query("SELECT * FROM anak_asuh WHERE keluarga_asuh_id = $id")->fetch_all(MYSQLI_ASSOC);
?>

<section class="dashboard">
    <div class="card">
        <h2 style="text-align:center; margin-bottom:20px;">Detail Keluarga Asuh</h2>

        <!-- Data Alm. Pegawai -->
        <div class="detail-card">
            <h3>Data Alm. Pegawai</h3>
            <div class="detail-grid">
                <div><strong>NIP Alm:</strong> <?= htmlspecialchars($keluarga['nip_alm']) ?></div>
                <div><strong>Nama Alm. Pegawai:</strong> <?= htmlspecialchars($keluarga['nama_alm_pegawai']) ?></div>
                <div><strong>Jabatan Terakhir:</strong> <?= htmlspecialchars($keluarga['jabatan_terakhir']) ?></div>
                <div><strong>Meninggal/Tewas:</strong> <?= htmlspecialchars($keluarga['meninggal_tewas']) ?></div>
                <div><strong>Penyebab Meninggal:</strong> <?= htmlspecialchars($keluarga['penyebab_meninggal']) ?></div>
                <div><strong>Wilayah:</strong> <?= htmlspecialchars($keluarga['wilayah']) ?></div>
            </div>
        </div>

        <div class="detail-card">
            <h3>Data Ibu</h3>
            <div class="detail-grid">
                <div><strong>Nama Ibu:</strong> <?= htmlspecialchars($keluarga['nama_ibu']) ?></div>
                <div><strong>TTL Ibu:</strong> <?= htmlspecialchars($keluarga['ttl_ibu']) ?></div>
                <div><strong>No. Telp:</strong> <?= htmlspecialchars($keluarga['no_telp']) ?></div>
                <div><strong>Alamat:</strong> 
                    <?= htmlspecialchars($keluarga['alamat']) ?>,
                    <?= htmlspecialchars($keluarga['kelurahan']) ?>,
                    <?= htmlspecialchars($keluarga['kecamatan']) ?>,
                    <?= htmlspecialchars($keluarga['kota']) ?>
                </div>
            </div>
        </div>


        <!-- Data Anak Asuh -->
        <div class="detail-card">
            <h3>Data Anak Asuh</h3>
            <?php if (!empty($anak)): ?>
                <table class="table-anak">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Anak</th>
                            <th>TTL Anak</th>
                            <th>Jenjang</th>
                            <th>Sekolah</th>
                            <th>Status Tanggungan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anak as $i => $a): ?>
                            <tr>
                                <td><?= $i+1 ?></td>
                                <td><?= htmlspecialchars($a['nama_anak']) ?></td>
                                <td><?= htmlspecialchars($a['ttl_anak']) ?></td>
                                <td><?= htmlspecialchars($a['jenjang']) ?></td>
                                <td><?= htmlspecialchars($a['sekolah']) ?></td>
                                <td><?= htmlspecialchars($a['status_tanggungan']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align:center; color:gray;">Tidak ada data anak asuh.</p>
            <?php endif; ?>
        </div>

        <!-- Tombol -->
        <div class="actions">
            <a href="index.php?page=keluarga_asuh" class="btn-cancel">⬅ Kembali</a>
            <a href="index.php?page=keluarga_asuh_form&id=<?= $keluarga['id'] ?>" class="btn-edit">✏ Edit</a>
        </div>
    </div>
</section>

<style>
.detail-card {
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
    margin-bottom:20px;
}
.detail-card h3 {
    margin-bottom:15px;
    color:#2e59d9;
    border-bottom:2px solid #f0f0f0;
    padding-bottom:6px;
    text-align: left;
}
.grid-2 {
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap:10px 20px;
}
.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr; /* kiri - kanan */
    gap: 10px 30px;
    margin-top: 10px;
    text-align: left; /* penting! semua isi rata kiri */
}
.detail-grid div {
    font-size: 15px;
    color: #333;
}
.detail-grid strong {
    font-weight: 600;
    color: #000;
    margin-right: 5px;
}
.table-anak {
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
    font-size:14px;
}
.table-anak th, .table-anak td {
    border:1px solid #ddd;
    padding:8px;
}
.table-anak th {
    background:#2e59d9;
    color:#fff;
    text-align:center;
}
.table-anak tr:nth-child(even) {
    background:#f9f9f9;
}
.table-anak td {
    text-align:center;
}
.actions {
    text-align:center;
    margin-top:20px;
}
.btn-cancel {
    background:#6c757d;
    color:#fff;
    padding:8px 14px;
    border-radius:6px;
    text-decoration:none;
    margin-right:8px;
}
.btn-edit {
    background:#2e59d9;
    color:#fff;
    padding:8px 14px;
    border-radius:6px;
    text-decoration:none;
}
.btn-cancel:hover, .btn-edit:hover {
    opacity:0.85;
}
</style>
