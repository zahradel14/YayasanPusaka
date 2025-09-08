<section class="dashboard">
    <div class="card">
        <h2>Daftar Pegawai<br>YAYASAN PUSAKA KAI</h2>
    </div>
</section>

<?php
// Ambil keyword pencarian dan sorting
$search = $_GET['search'] ?? '';
$order_by = $_GET['order_by'] ?? 'p.id';
$order_dir = $_GET['order_dir'] ?? 'ASC';

// Validasi kolom yang boleh diurutkan
$allowed_order = [
    'nip' => 'p.nip',
    'nama' => 'p.nama_lengkap',
    'tgl_masuk' => 'p.tanggal_masuk',
    'id' => 'p.id'
];
$order_by = $allowed_order[$order_by] ?? 'p.id';
$order_dir = strtoupper($order_dir) === 'DESC' ? 'DESC' : 'ASC';

// Query dengan pencarian + sorting
$pegawai = $conn->query("
    SELECT p.*, COUNT(a.id) AS jumlah_anak
    FROM pegawai p
    LEFT JOIN anak_pegawai a ON p.id = a.pegawai_id
    WHERE p.nip LIKE '%$search%' OR p.nama_lengkap LIKE '%$search%'
    GROUP BY p.id
    ORDER BY $order_by $order_dir
");
?>

<div class="table-container">
    <!-- Toolbar -->
    <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <!-- Form Search + Sort -->
        <form method="get" style="display: flex; gap: 8px; align-items: center;">
            <input type="hidden" name="page" value="pegawai">

            <input type="text" name="search" placeholder="Cari NIP / Nama..." 
                   value="<?= htmlspecialchars($search) ?>"
                   style="padding: 6px 10px; border:1px solid #ccc; border-radius:6px;">

            <select name="order_by" style="padding:6px; border:1px solid #ccc; border-radius:6px;">
                <option value="id" <?= $order_by == 'p.id' ? 'selected' : '' ?>>Default</option>
                <option value="nip" <?= $order_by == 'p.nip' ? 'selected' : '' ?>>NIP</option>
                <option value="nama" <?= $order_by == 'p.nama_lengkap' ? 'selected' : '' ?>>Nama</option>
                <option value="tgl_masuk" <?= $order_by == 'p.tanggal_masuk' ? 'selected' : '' ?>>Tanggal Masuk</option>
            </select>

            <select name="order_dir" style="padding:6px; border:1px solid #ccc; border-radius:6px;">
                <option value="ASC" <?= $order_dir == 'ASC' ? 'selected' : '' ?>>Naik</option>
                <option value="DESC" <?= $order_dir == 'DESC' ? 'selected' : '' ?>>Turun</option>
            </select>

            <button type="submit" 
                    style="background:#2e59d9; color:white; border:none; padding:6px 12px; border-radius:6px; cursor:pointer;">
                Terapkan
            </button>

            <?php if ($search || $order_by != 'p.id' || $order_dir != 'ASC'): ?>
                <a href="index.php?page=pegawai" 
                   style="background:#e74a3b; color:white; padding:6px 12px; border-radius:6px; text-decoration:none;">
                   Reset
                </a>
            <?php endif; ?>
        </form>

        <!-- Tombol Tambah -->
        <a href="index.php?page=pegawai_form" 
           style="background-color:#2e59d9; color:white; padding:10px 15px; 
                  border-radius:6px; text-decoration:none; font-weight:bold;">
            + Tambah Pegawai
        </a>
    </div>

    <!-- Tabel Pegawai -->
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Nama Lengkap</th>
            <th>TTL</th>
            <th>Jenis Kelamin</th>
            <th>Agama</th>
            <th>Jabatan</th>
            <th>Tanggal Masuk</th>
            <th>Status</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Anak</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if ($pegawai->num_rows > 0): 
            $no = 1;
            while($row = $pegawai->fetch_assoc()): 
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nip']) ?></td>
                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($row['tempat_lahir'] . ', ' . $row['tanggal_lahir']) ?></td>
                <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                <td><?= htmlspecialchars($row['agama']) ?></td>
                <td><?= htmlspecialchars($row['jabatan']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_masuk']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['no_telp']) ?></td>
                <td>
                    <?= $row['jumlah_anak'] > 0 ? $row['jumlah_anak'] . ' Anak' : '-' ?>
                </td>
                <td style="text-align:center;">
                    <?php if (!empty($row['foto'])): ?>
                        <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" 
                             alt="Foto" 
                             style="width:50px; height:50px; object-fit:cover; border-radius:6px; border:1px solid #ccc;">
                    <?php else: ?>
                        <span style="color:#999;">-</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="index.php?page=pegawai_detail&id=<?= $row['id'] ?>" style="color: green;">Detail</a> |
                    <a href="index.php?page=pegawai_form&id=<?= $row['id'] ?>" style="color: #007bff;">Edit</a> |
                    <a href="index.php?page=pegawai&delete=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" style="color: red;">Hapus</a>
                </td>
            </tr>
        <?php 
            endwhile; 
        else: 
        ?>
            <tr>
                <td colspan="14" style="text-align:center; color: gray; padding:20px;">
                    Data pegawai tidak ditemukan
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    // Hapus anak juga agar data tidak menggantung
    $conn->query("DELETE FROM anak_pegawai WHERE pegawai_id = $id");

    // Hapus pegawai
    $conn->query("DELETE FROM pegawai WHERE id = $id");

    echo "<script>location.href='index.php?page=pegawai';</script>";
}
?>
