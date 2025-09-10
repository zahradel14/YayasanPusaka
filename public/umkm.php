<?php
include __DIR__ . "/../backend/koneksi.php";

// Pencarian sederhana
$cari = trim($_GET['cari'] ?? '');
$where = '';
if ($cari !== '') {
    $esc = mysqli_real_escape_string($koneksi, $cari);
    $where = "WHERE u.nama_usaha LIKE '%$esc%' OR k.nama_ibu LIKE '%$esc%'";
}

// Hitung total semua data UMKM
$totalAll = mysqli_fetch_assoc(mysqli_query(
    $koneksi, 
    "SELECT COUNT(*) AS jml FROM umkm"
))['jml'];

// Hitung total sesuai filter/pencarian
$totalFiltered = mysqli_fetch_assoc(mysqli_query(
    $koneksi, 
    "SELECT COUNT(*) AS jml 
     FROM umkm u 
     JOIN keluarga_asuh k ON u.id_keluarga = k.id 
     $where"
))['jml'];

// Query utama ambil data UMKM
$sql = "SELECT u.id_umkm, u.nama_usaha, u.jenis_usaha, u.alamat_usaha, 
               k.nama_ibu, k.no_telp 
        FROM umkm u
        JOIN keluarga_asuh k ON u.id_keluarga = k.id
        $where
        ORDER BY u.id_umkm ASC";
$result = mysqli_query($koneksi, $sql);
$no = 1;
?>

<section class="dashboard">
    <div class="card">
        <h2>Daftar UMKM Binaan<br>
        YAYASAN PUSAKA KAI</h2>
    </div>
</section>

<div class="table-container">
    <div style="margin-bottom: 15px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <!-- Tombol Tambah -->
        <a href="index.php?page=umkm" 
           style="background-color:#2e59d9; color:white; padding:10px 15px; 
                  border-radius:6px; text-decoration:none; font-weight:bold;">
            + Tambah UMKM
        </a>

        <!-- (opsional) Form pencarian/filter -->
        <form method="get" style="margin:0; display:flex; gap:10px; align-items:center;">
            <input type="hidden" name="page" value="umkm">

            <input type="text" name="cari" placeholder="Cari Nama UMKM / Pemilik" 
                   value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>" 
                   style="padding:6px; border:1px solid #ccc; border-radius:4px;">

            <button type="submit" 
                    style="padding:6px 12px; background:#2e59d9; color:#fff; border:none; border-radius:4px; cursor:pointer;">
                Cari
            </button>

            <a href="index.php?page=umkm" 
               style="padding:6px 12px; background:#6c757d; color:#fff; border-radius:4px; text-decoration:none;">
                Reset
            </a>
        </form>
    </div>

    <!-- Info jumlah hasil -->
    <p style="margin:10px 0; font-size:14px; color:#555;">
        Menampilkan <b><?= $totalFiltered ?></b> data
        <?php if ($totalFiltered < $totalAll): ?>
            dari total <b><?= $totalAll ?></b> UMKM
        <?php endif; ?>
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pemilik</th>
                <th>Nama UMKM</th>
                <th>Jenis Usaha</th>
                <th>Alamat Usaha</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>".$no++."</td>
                            <td>".htmlspecialchars($row['nama_ibu'])."</td>
                            <td>".htmlspecialchars($row['nama_usaha'])."</td>
                            <td>".htmlspecialchars($row['jenis_usaha'])."</td>
                            <td>".htmlspecialchars($row['alamat_usaha'])."</td>
                            <td>
                                <a href='index.php?page=umkm_detail&id=".$row['id_umkm']."' style='color:green;'>Detail</a> |
                                <a href='index.php?page=umkm_form&id=".$row['id_umkm']."' style='color:#007bff;'>Edit</a> |
                                <a href='index.php?page=umkm&delete=".$row['id_umkm']."' 
                                   onclick=\"return confirm('Yakin ingin menghapus data ini?')\" 
                                   style='color:red;'>Hapus</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center; color:gray; padding:20px;'>
                        Belum ada data UMKM
                      </td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    mysqli_query($koneksi, "DELETE FROM umkm WHERE id_umkm = $id");
    echo "<script>location.href='index.php?page=umkm';</script>";
}
?>
