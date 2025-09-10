<?php
include __DIR__ . "/../backend/koneksi.php";

$id = (int) ($_GET['id'] ?? 0);

// Query ambil detail UMKM + keluarga
$sql = "SELECT u.*, k.id AS id_keluarga, k.nama_ibu, k.nama_alm_pegawai, k.wilayah, k.alamat, k.no_telp
        FROM umkm u
        JOIN keluarga_asuh k ON u.id_keluarga = k.id
        WHERE u.id_umkm = $id
        LIMIT 1";

$result = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<p style='color:red; text-align:center;'>Data UMKM tidak ditemukan.</p>";
    exit;
}

// Ambil daftar anak asuh
$anak_sql = "SELECT nama_anak FROM anak_asuh WHERE keluarga_asuh_id = " . (int)$data['id_keluarga'];
$anak_res = mysqli_query($koneksi, $anak_sql);
$anak_list = [];
while ($row = mysqli_fetch_assoc($anak_res)) {
    $anak_list[] = $row['nama_anak'];
}
?>

<section class="dashboard">
    <div class="card">
        <h2>Detail UMKM</h2>
    </div>
</section>

<div style="max-width:1000px; margin:20px auto; background:#fff; padding:20px; border-radius:8px; border:1px solid #ddd;">
    
    <!-- Bagian 1: Data Diri & Keluarga -->
    <h3 style="margin-bottom:10px; border-bottom:2px solid #2e59d9; padding-bottom:5px;">Data Diri & Keluarga</h3>
    <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
        <tr><td style="padding:8px; font-weight:bold;">Nama Ibu</td><td><?= htmlspecialchars($data['nama_ibu']) ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Nama Suami</td><td><?= htmlspecialchars($data['nama_alm_pegawai'] ?? '-') ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Wilayah DAOP/DIVRE</td><td><?= htmlspecialchars($data['wilayah'] ?? '-') ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Alamat</td><td><?= htmlspecialchars($data['alamat']) ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">No Telepon</td><td><?= htmlspecialchars($data['no_telp']) ?></td></tr>
        <tr>
            <td style="padding:8px; font-weight:bold;">Daftar Anak</td>
            <td><?= !empty($anak_list) ? implode(', ', $anak_list) : '-' ?></td>
        </tr>
    </table>

    <!-- Bagian 2: Data Usaha -->
    <h3 style="margin-bottom:10px; border-bottom:2px solid #2e59d9; padding-bottom:5px;">Data Usaha</h3>
    <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
        <tr><td style="padding:8px; font-weight:bold;">Nama Usaha/Brand</td><td><?= htmlspecialchars($data['nama_usaha']) ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Alamat Usaha</td><td><?= htmlspecialchars($data['alamat_usaha']) ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Jenis Usaha</td><td><?= htmlspecialchars($data['jenis_usaha']) ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Produk</td><td><?= htmlspecialchars($data['produk']) ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Tahun Mulai</td><td><?= htmlspecialchars($data['tahun_mulai']) ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Nomor Izin Usaha</td><td><?= htmlspecialchars($data['nomor_izin'] ?? '-') ?></td></tr>
    </table>

    <!-- Bagian 3: Produksi & Penjualan -->
    <h3 style="margin-bottom:10px; border-bottom:2px solid #2e59d9; padding-bottom:5px;">Data Produksi & Penjualan</h3>
    <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
        <tr><td style="padding:8px; font-weight:bold;">Omzet / Bulan</td><td>Rp <?= number_format($data['omzet'], 0, ',', '.') ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Jumlah Produksi</td><td><?= htmlspecialchars($data['jumlah_produksi'] ?? '-') ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Jumlah Karyawan</td><td><?= htmlspecialchars($data['jumlah_karyawan'] ?? '-') ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Saluran Penjualan</td><td><?= htmlspecialchars($data['saluran_penjualan'] ?? '-') ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Harga Rata-rata Produk</td><td><?= htmlspecialchars($data['harga_rata2'] ?? '-') ?></td></tr>
    </table>

    <!-- Bagian 4: Dukungan & Kebutuhan -->
    <h3 style="margin-bottom:10px; border-bottom:2px solid #2e59d9; padding-bottom:5px;">Dukungan & Kebutuhan</h3>
    <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
        <tr><td style="padding:8px; font-weight:bold;">Permodalan</td><td><?= htmlspecialchars($data['permodalan'] ?? '-') ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Kendala Usaha</td><td><?= htmlspecialchars($data['kendala'] ?? '-') ?></td></tr>
        <tr><td style="padding:8px; font-weight:bold;">Kebutuhan Utama</td><td><?= htmlspecialchars($data['kebutuhan'] ?? '-') ?></td></tr>
    </table>

    <!-- Bagian 5: Dokumentasi -->
    <h3 style="margin-bottom:10px; border-bottom:2px solid #2e59d9; padding-bottom:5px;">Dokumentasi</h3>
    <div style="display:flex; gap:20px; flex-wrap:wrap;">
        <?php if ($data['foto_produk']) { ?>
            <div>
                <b>Foto Produk:</b><br>
                <img src="uploads/<?= htmlspecialchars($data['foto_produk']) ?>" style="max-width:200px; border:1px solid #ccc; border-radius:6px;">
            </div>
        <?php } ?>
        <?php if ($data['foto_pemilik']) { ?>
            <div>
                <b>Foto Pemilik:</b><br>
                <img src="uploads/<?= htmlspecialchars($data['foto_pemilik']) ?>" style="max-width:200px; border:1px solid #ccc; border-radius:6px;">
            </div>
        <?php } ?>
        <?php if ($data['logo_brand']) { ?>
            <div>
                <b>Logo/Brand:</b><br>
                <img src="uploads/<?= htmlspecialchars($data['logo_brand']) ?>" style="max-width:200px; border:1px solid #ccc; border-radius:6px;">
            </div>
        <?php } ?>
    </div>

    <div style="margin-top:15px;">
        <b>Media Sosial / Marketplace:</b><br>
        <?= nl2br(htmlspecialchars($data['medsos'] ?? '-')) ?>
    </div>

    <!-- Tombol kembali -->
    <div style="margin-top:20px; text-align:right;">
        <a href="index.php?page=umkm" 
           style="padding:10px 16px; background:#6c757d; color:#fff; border-radius:6px; text-decoration:none;">
            Kembali
        </a>
    </div>
</div>
