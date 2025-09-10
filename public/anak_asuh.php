<?php
// Pastikan koneksi sudah ada
if(!$conn){
    die("Koneksi database belum dibuat!");
}

// Ambil data anak beserta nama keluarga
$query = "SELECT a.id, a.nama_anak, a.ttl_anak, a.jenjang, a.sekolah, a.status_tanggungan,
                 k.nama_ibu
          FROM anak_asuh a
          JOIN keluarga_asuh k ON a.keluarga_asuh_id = k.id
          ORDER BY k.id, a.id";

$res = $conn->query($query);
if(!$res){
    echo "<p>Terjadi kesalahan: " . $conn->error . "</p>";
    exit;
}
?>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anak</th>
                <th>TTL</th>
                <th>Jenjang</th>
                <th>Sekolah</th>
                <th>Status</th>
                <th>Nama Ibu/Wali</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>" . htmlspecialchars($row['nama_anak']) . "</td>
                        <td>" . htmlspecialchars($row['ttl_anak']) . "</td>
                        <td>" . htmlspecialchars($row['jenjang']) . "</td>
                        <td>" . htmlspecialchars($row['sekolah']) . "</td>
                        <td>" . htmlspecialchars($row['status_tanggungan']) . "</td>
                        <td>" . htmlspecialchars($row['nama_ibu']) . "</td>
                        <td>
                            <a href='index.php?page=anak_asuh_edit&id={$row['id']}' class='btn-action edit'>Edit</a>
                            <a href='index.php?page=anak_asuh_detail&id={$row['id']}' class='btn-action detail'>Detail</a>
                            <a href='index.php?page=anak_asuh_hapus&id={$row['id']}' class='btn-action hapus' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                        </td>
                      </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<style>
.table-container {
    margin: 20px;
    overflow-x: auto;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
th, td {
    padding: 14px 20px;
    text-align: left;
}
th {
    background-color: #4e73df;
    color: white;
    font-weight: 600;
}
tr:nth-child(even) { background-color: #f8f9fc; }
tr:hover { background-color: #d1d9ff; transition: 0.2s; }

.btn-action {
    display: inline-block;
    margin-right: 5px;
    padding: 5px 10px;
    font-size: 12px;
    border-radius: 4px;
    color: white;
    text-decoration: none;
}
.btn-action.edit { background-color: #1cc88a; }
.btn-action.detail { background-color: #36b9cc; }
.btn-action.hapus { background-color: #e74a3b; }
.btn-action:hover { opacity: 0.8; }
</style>
