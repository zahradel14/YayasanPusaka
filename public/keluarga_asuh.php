<section class="dashboard">
    <div class="card">
        <h2>Daftar Keluarga Asuh<br>
        YAYASAN PUSAKA KAI</h2>
    </div>
</section>

<?php
// Ambil daftar wilayah unik
$wilayahRes = $conn->query("SELECT DISTINCT wilayah FROM keluarga_asuh ORDER BY wilayah ASC");
$wilayahList = [];
while ($w = $wilayahRes->fetch_assoc()) {
    $wilayahList[] = $w['wilayah'];
}

// Cek filter wilayah & pencarian
$filterWilayah = $_GET['wilayah'] ?? '';
$cari          = trim($_GET['cari'] ?? '');

// Base SQL
$sql = "
    SELECT k.*, COUNT(a.id) AS total_anak
    FROM keluarga_asuh k
    LEFT JOIN anak_asuh a ON a.keluarga_asuh_id = k.id
    WHERE 1=1
";

// Tambah filter wilayah jika ada
if ($filterWilayah !== '') {
    $sql .= " AND k.wilayah = '".$conn->real_escape_string($filterWilayah)."'";
}

// Tambah pencarian jika ada
if ($cari !== '') {
    $esc = $conn->real_escape_string($cari);
    $sql .= " AND (k.nama_alm_pegawai LIKE '%$esc%' OR k.nama_ibu LIKE '%$esc%')";
}

// GROUP BY + urutkan
$sql .= " GROUP BY k.id ORDER BY k.created_at DESC";

// Eksekusi query utama
$keluarga_asuh = $conn->query($sql);

// Hitung total semua data (tanpa filter)
$totalAll = $conn->query("SELECT COUNT(*) AS jml FROM keluarga_asuh")->fetch_assoc()['jml'];

// Hitung total hasil filter sekarang
$totalFiltered = $keluarga_asuh ? $keluarga_asuh->num_rows : 0;
?>

<div class="table-actions" style="margin-bottom:15px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
    
    <!-- Kiri: Tombol tambah, export, import -->
    <div style="display:flex; gap:10px; align-items:center;">
        <!-- Tombol Tambah -->
        <a href="index.php?page=keluarga_asuh_form" 
           style="background-color:#2e59d9; color:white; padding:10px 15px; 
                  border-radius:6px; text-decoration:none; font-weight:bold;">
            + Tambah Keluarga Asuh
        </a>

        <!-- Tombol Export -->
        <a href="export_keluarga_asuh.php"
           style="background:#28a745; color:#fff; padding:10px 15px;
                  border-radius:6px; text-decoration:none; font-weight:bold;">
            ⬇ Export Excel
        </a>

        <!-- Form Import -->
        <form action="import_keluarga_asuh.php" method="post" enctype="multipart/form-data" 
              style="display:flex; gap:5px; align-items:center;">
            <input type="file" name="file_excel" accept=".csv,.xls,.xlsx" required
                   style="padding:6px; border:1px solid #ccc; border-radius:4px;">
            <button type="submit"
                    style="background:#17a2b8; color:#fff; padding:10px 15px;
                           border-radius:6px; border:none; cursor:pointer;">
                ⬆ Import
            </button>
        </form>
    </div>

    <!-- Kanan: Filter & Pencarian -->
    <form method="get" style="margin:0; display:flex; gap:10px; align-items:center;">
        <input type="hidden" name="page" value="keluarga_asuh">

        <!-- Dropdown wilayah -->
        <select name="wilayah" onchange="this.form.submit()">
            <option value="">-- Semua Wilayah --</option>
            <?php foreach ($wilayahList as $w): ?>
                <option value="<?= htmlspecialchars($w) ?>" <?= $w===$filterWilayah?'selected':'' ?>>
                    <?= htmlspecialchars($w) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Input pencarian -->
        <input type="text" name="cari" placeholder="Cari Nama Alm. / Ibu" 
               value="<?= htmlspecialchars($cari) ?>" 
               style="padding:6px; border:1px solid #ccc; border-radius:4px;">

        <button type="submit" 
                style="padding:6px 12px; background:#2e59d9; color:#fff; border:none; border-radius:4px; cursor:pointer;">
            Cari
        </button>

        <!-- Tombol reset -->
        <a href="index.php?page=keluarga_asuh" 
           style="padding:6px 12px; background:#6c757d; color:#fff; border-radius:4px; text-decoration:none;">
            Reset
        </a>
    </form>
</div>

    <!-- Info jumlah hasil -->
    <p style="margin:10px 0; font-size:14px; color:#555;">
        Menampilkan <b><?= $totalFiltered ?></b> data
        <?php if ($totalFiltered < $totalAll): ?>
            dari total <b><?= $totalAll ?></b> keluarga asuh
        <?php endif; ?>
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIP Alm</th>
                <th>Nama Alm. Pegawai</th>
                <th>Jabatan Terakhir</th>
                <th>Meninggal/Tewas</th>
                <th>Penyebab Meninggal</th>
                <th>Wilayah</th>
                <th>Nama Ibu</th>
                <th>Pekerjaan Ibu</th>
                <th>No. Telp</th>
                <th>Alamat</th>
                <th>Total Anak</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($keluarga_asuh && $keluarga_asuh->num_rows > 0): ?>
            <?php $no=1; while($row = $keluarga_asuh->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nip_alm']) ?></td>
                    <td><?= htmlspecialchars($row['nama_alm_pegawai']) ?></td>
                    <td><?= htmlspecialchars($row['jabatan_terakhir']) ?></td>
                    <td><?= htmlspecialchars($row['meninggal_tewas']) ?></td>
                    <td><?= htmlspecialchars($row['penyebab_meninggal']) ?></td>
                    <td><?= htmlspecialchars($row['wilayah']) ?></td>
                    <td><?= htmlspecialchars($row['nama_ibu']) ?></td>
                    <td><?= htmlspecialchars($row['pekerjaan_ibu']) ?></td>
                    <td><?= htmlspecialchars($row['no_telp']) ?></td>
                    <td>
                        <?= htmlspecialchars($row['alamat']) ?>,
                        <?= htmlspecialchars($row['kelurahan']) ?>,
                        <?= htmlspecialchars($row['kecamatan']) ?>,
                        <?= htmlspecialchars($row['kota']) ?>
                    </td>
                    <td style="text-align:center; font-weight:bold;">
                        <?= (int)$row['total_anak'] ?>
                    </td>
                    <td>
                        <a href="index.php?page=keluarga_asuh_detail&id=<?= $row['id'] ?>" style="color: green;">Detail</a> |
                        <a href="index.php?page=keluarga_asuh_form&id=<?= $row['id'] ?>" style="color: #007bff;">Edit</a> |
                        <a href="index.php?page=keluarga_asuh&delete=<?= $row['id'] ?>" 
                           onclick="return confirm('Yakin ingin menghapus data ini?')" 
                           style="color: red;">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="13" style="text-align:center; color: gray; padding:20px;">
                    Data keluarga asuh belum ada
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM keluarga_asuh WHERE id = $id");
    echo "<script>location.href='index.php?page=keluarga_asuh';</script>";
}
?>
