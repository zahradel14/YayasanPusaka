<?php
$id = $_GET['id'] ?? '';
if(!$id){
    echo "<p>ID anak tidak ditemukan!</p>";
    exit;
}

$stmt = $conn->prepare("SELECT a.*, k.nama_ibu FROM anak_asuh a JOIN keluarga_asuh k ON a.keluarga_asuh_id=k.id WHERE a.id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$anak = $res->fetch_assoc();
if(!$anak){
    echo "<p>Data anak tidak ditemukan!</p>";
    exit;
}
?>

<h2>Detail Anak Asuh</h2>
<table>
    <tr><th>Nama Anak</th><td><?= htmlspecialchars($anak['nama_anak']) ?></td></tr>
    <tr><th>TTL</th><td><?= htmlspecialchars($anak['ttl_anak']) ?></td></tr>
    <tr><th>Jenjang</th><td><?= htmlspecialchars($anak['jenjang']) ?></td></tr>
    <tr><th>Sekolah</th><td><?= htmlspecialchars($anak['sekolah']) ?></td></tr>
    <tr><th>Status</th><td><?= htmlspecialchars($anak['status_tanggungan']) ?></td></tr>
    <tr><th>Nama Ibu/Wali</th><td><?= htmlspecialchars($anak['nama_ibu']) ?></td></tr>
</table>

<a href="index.php?page=anak_asuh">Kembali</a>
